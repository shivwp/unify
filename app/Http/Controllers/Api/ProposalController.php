<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Admin\ProjectResource;
use App\Http\Resources\Admin\ClientResource;
use App\Http\Resources\Admin\ProposalCollectionResource;
use App\Http\Resources\Admin\ProjectSingleResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Helper\ResponseBuilder;
use App\Models\ProjectProjectSkill;
use App\Models\ProjectCategory;
use App\Models\FreelancerSkill;
use App\Models\InviteFreelacner;
use App\Models\SendProposal;
use App\Models\Transaction;
use App\Models\ProjectMilestone;
use App\Models\DeclineData;
use App\Models\SavedProject;
use App\Models\Project;
use App\Models\User;
use App\Models\IncomeSource;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;
use DB;


class ProposalController extends Controller
{
	public function allProposal()
	{
		try
		{
			if (Auth::guard('api')->check()) {
            $singleuser = Auth::guard('api')->user();
            $user_id = $singleuser->id;
	        } 
	        else{
	           	return ResponseBuilder::error(__("User not found"), $this->unauthorized);
	        }
	        $offers = SendProposal::join('projects','send_proposals.project_id','projects.id')->where('send_proposals.freelancer_id',$user_id)->where('send_proposals.type','offer')->where('send_proposals.status','pending')->orderBy('send_proposals.created_at','DESC')->select('projects.id','projects.name','send_proposals.created_at','send_proposals.status','projects.client_id','send_proposals.id as auto_increment_id','projects.budget_type')->get();

	        $declineProposal = DeclineData::where('freelancer_id',$user_id)->where('type','withdraw_proposal')->pluck('data_id')->toArray();
	        $proposals = SendProposal::join('projects','send_proposals.project_id','projects.id')->where('send_proposals.freelancer_id',$user_id)->where('send_proposals.type','proposal')->where('send_proposals.status','pending')->orderBy('send_proposals.created_at','DESC')->select('projects.id','projects.name','send_proposals.created_at','send_proposals.status','projects.client_id','send_proposals.id as auto_increment_id','projects.budget_type');

	        $activeproposals = SendProposal::join('projects','send_proposals.project_id','projects.id')->where('send_proposals.freelancer_id',$user_id)->where('send_proposals.status','active')->where('send_proposals.type','proposal')->select('projects.id','projects.name','send_proposals.created_at','send_proposals.status','projects.client_id','send_proposals.id as auto_increment_id','projects.budget_type');
	        if($declineProposal){
	        	$proposals->whereNotIn('send_proposals.id',$declineProposal);
	        	$activeproposals->whereNotIn('send_proposals.id',$declineProposal);
	        }

	        $declineInvite = DeclineData::where('freelancer_id',$user_id)->where('type','decline_invite')->pluck('data_id')->toArray();
	        $prosalInvite = SendProposal::where('freelancer_id',$user_id)->where('invite_id','!=', Null)->pluck('invite_id')->toArray();
	        $inviteList = InviteFreelacner::join('projects','invite_freelancer.project_id','projects.id')->where('invite_freelancer.freelancer_id',$user_id)->orderBy('invite_freelancer.created_at','DESC')->select('projects.id','projects.name','invite_freelancer.created_at','invite_freelancer.status','projects.client_id','invite_freelancer.id as auto_increment_id','projects.budget_type');
	        
	        if($declineInvite){
	        	$inviteList->whereNotIn('invite_freelancer.id',$declineInvite);
	        }
	        if($prosalInvite){
	        	$inviteList->whereNotIn('invite_freelancer.id',$prosalInvite);
	        }
	        $inviteAll = $inviteList->get(); 
	        $proposalsAll = $proposals->get(); 
	        $activeproposalsAll = $activeproposals->get(); 
	        
        	$this->response->offers = new ProposalCollectionResource($offers);
        	$this->response->submittedProposal = new ProposalCollectionResource($proposalsAll);
        	$this->response->activeProposal = new ProposalCollectionResource($activeproposalsAll);
        	$this->response->interviewForInvitation = new ProposalCollectionResource($inviteAll);
        	return ResponseBuilder::success($this->response, "All proposals list");
	        
		}
		catch(\Exception $e)
      	{
         	return ResponseBuilder::error(__("Oops! Something went wrong."), $this->serverError);
      	}
	}

	public function hireFreelancer(Request $request)
	{
		try
		{
			DB::beginTransaction();
			if (Auth::guard('api')->check()) {
            $singleuser = Auth::guard('api')->user();
            $user_id = $singleuser->id;
	        } 
	        else{
	           	return ResponseBuilder::error(__("User not found"), $this->unauthorized);
	        }
	        $validator = Validator::make($request->all(), [
                'freelancer_id' => 	'required|exists:freelancer,user_id',
                'project_id'   	=>	'required|exists:projects,id',
                'title'			=>	'required',
                'budget_type'	=>	'required|in:fixed,hourly',
                'weekly_limit'	=>	'required_if:budget_type,hourly|integer',
                'milestone_type'=>	'required_if:budget_type,fixed',
                'cover_letter'	=>	'required',
                'amount'		=>	'required_if:budget_type,hourly|integer'
            ]);
            if ($validator->fails()) {   
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);  
            }
            $exists_hire = SendProposal::where('project_id',$request->project_id)->where('client_id',$user_id)->where('freelancer_id',$request->freelancer_id)->where('type','offer')->first();
            if(!empty($exists_hire))
            {
            	return ResponseBuilder::error('Already send an offer to this freelance!', $this->badRequest);  
            }
            else
            {
            	$get_fee = IncomeSource::where('name','Unify')->first();
            	$platform_fee = $get_fee->fee_percent;
            	$bidAmount = (isset($request->amount))?($request->amount - $platform_fee):'0';

            	$hiring = new SendProposal;
            	$hiring->project_id = $request->project_id;
            	$hiring->client_id = $user_id;
            	$hiring->freelancer_id = $request->freelancer_id;
            	$hiring->budget_type = $request->budget_type;
            	$hiring->amount = $request->amount;
            	$hiring->weekly_limit = $request->weekly_limit;
            	$hiring->title = $request->title;
            	$hiring->date = $request->date;
            	$hiring->cover_letter = $request->cover_letter;
            	$hiring->status = 'draft';
            	$hiring->type = 'offer';
            	$hiring->image = !empty($request->file('image')) ? $this->proposalImage($request->file('image')  ) : '';
            	$hiring->save();

            	if($request->budget_type == 'fixed'){
            		$milestone_data = json_decode($request->milestone_data,1);
            		if(!empty($milestone_data)){
            			foreach ($milestone_data as $value) {
			                $mileStones[] = [
			                    'proposal_id' => $hiring->id,
			                    'project_id' => $request->project_id,
			                    'client_id' => $user_id,
			                    'freelancer_id' => $request->freelancer_id,
			                    'project_duration'=>$request->project_duration,
			                    'description' => $value['description'],
			                    'due_date' => $value['due_date'],
			                    'amount' => $value['amount'],
			                    'status' => 'created',
			                    'note' => '',
			                    'type' => $request->milestone_type
			                ];
			            }
            		}
            		ProjectMilestone::insert($mileStones);
            		
            	}
            	DB::commit();
            	$milestone_amount = [];
            	if(isset($milestone_data)){
            		$milestone_amount = array_column($milestone_data,'amount');
            	}
            	$subtotal = ($request->budget_type == "hourly") ? $request->amount : $milestone_amount[0];
            	
            	$freelancer = User::where('id',$request->freelancer_id)->select('name','profile_image')->first();
            	$project = Project::where('id',$request->project_id)->select('name')->first();
            	$data = [];
            	$data = [
            		'id' 						=>$hiring->id,
            		'project_name' 				=>$project->name,
            		'freelancer_name' 			=>$freelancer->name,
            		'freelancer_profile_image' 	=>$freelancer->profile_image,
            		'subtotal'					=>(float)$subtotal,
            		'fee'						=>(float)$platform_fee,
            		'total'						=>(float)($subtotal + $platform_fee)
            	];
            	$this->response = $data;
            	return ResponseBuilder::success($this->response, "Send offer successfully");
            }
		}
		catch(\Exception $e)
		{
			DB::rollback();
			return ResponseBuilder::error("Oops! Something went wrong.", $this->serverError);
		}
	}

	public function contractPayment(Request $request)
	{
		//try{
			if(!Auth::guard('api')->check()){
				return ResponseBuilder::error("User not found", $this->unauthorized);
			}
			$user_id = Auth::guard('api')->user()->id;
			$validator = Validator::make($request->all(),[
				'id' 			=>	'required|exists:send_proposals,id',
				'stripe_token' 	=>	'required',
			]); 
			if($validator->fails()){
				return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
			}
			$amount = ProjectMilestone::where('proposal_id',$request->id)->pluck('amount')->first();
			if(empty($amount)){
				return ResponseBuilder::error("Please enter valid amount", $this->badRequest);
			}
			$fund = $this->stripCustomerPayment($user_id, $request->stripe_token, $amount);
			if($fund->status == "succeeded"){
				$update_status = SendProposal::where('id',$request->id)->first();
				$update_status->status = 'pending';
				$update_status->save();

				$transaction = new Transaction;
				$transaction->user_id = $user_id;
        		$transaction->transaction_id = $fund->id;
        		$transaction->amount = $fund->amount;
        		$transaction->description = "payment during send offer to freelancer";
        		$transaction->capture_method = $fund->capture_method;
        		$transaction->client_secret = $fund->client_secret;
        		$transaction->confirmation_method = $fund->confirmation_method;
        		$transaction->currency = $fund->currency;
        		$transaction->customer_id = $fund->customer;
        		$transaction->source = $fund->source;
        		$transaction->status = $fund->status;
        		$transaction->complete_response = json_encode($fund); 
        		$transaction->save();

        		$this->response = $transaction;
        		return ResponseBuilder::success($this->response,"Payment success");
			}else{
				$transaction = new Transaction;
				$transaction->user_id = $user_id;
        		$transaction->transaction_id = $fund->id;
        		$transaction->amount = $fund->amount;
        		$transaction->description = "payment during send offer to freelancer";
        		$transaction->capture_method = $fund->capture_method;
        		$transaction->client_secret = $fund->client_secret;
        		$transaction->confirmation_method = $fund->confirmation_method;
        		$transaction->currency = $fund->currency;
        		$transaction->customer_id = $fund->customer;
        		$transaction->source = $fund->source;
        		$transaction->status = $fund->status;
        		$transaction->complete_response = json_encode($fund); 
        		$transaction->save();

        		return ResponseBuilder::error("Payment status is ".$fund->status,$this->serverError);
			}

		// }
		// catch(\Exception $e){
		// 	return ResponseBuilder::error("Oops! Something went wrong.", $this->serverError);
		// }
	}

	/**
	*
	* @param $proposal_id
	* @param $status
	* 
	* @author Navjot Kaur
	* @return single proposal data
	*/ 
	public function singleProposal($proposal_id, $status)
	{
		try
		{
			if(!Auth::guard('api')->check())
			{
				return ResponseBuilder::error('User not found',$this->unauthorized);
			}
			$user_id = Auth::guard('api')->user()->id;

            if($status == "invite")
            {
            	$propsalData = InviteFreelacner::where('id',$proposal_id)->where('status','pending')->first();
            }else{
            	$propsalData = SendProposal::where('id',$proposal_id)
            					->where('type',($status=='offer') ? 'offer' : 'proposal')
            					->where('status',($status=='submit' || $status=='offer') ? 'pending' : $status)
            					->select('send_proposals.*','send_proposals.amount as bid_amount')->first();
            }					unset($propsalData->amount);

            if(empty($propsalData)){
            	return ResponseBuilder::error("No data found", $this->badRequest);
            }

            $milestone_type = ProjectMilestone::where("proposal_id",$proposal_id)->pluck('type')->first();
            $propsalData->bid_amount = (integer)$propsalData->bid_amount;
            $propsalData->milestone_type = !empty($milestone_type) ? $milestone_type : '';
            $milestonedata = ProjectMilestone::where('proposal_id',$proposal_id)->get();
            
            $clientData = $this->getClientInfo($propsalData->client_id);
            $projectData = Project::where('id',$propsalData->project_id)->first();


         	$this->response->proposal_data = $propsalData;
         	$this->response->milestonedata = $milestonedata;
         	$this->response->client_data = !empty($clientData) ? new ClientResource($clientData) : null;
         	$this->response->project_data = !empty($projectData) ? new ProjectSingleResource($projectData) : null;

            return ResponseBuilder::success($this->response, "Proposal details");
		}
		catch(\Exception $e)
		{
			return ResponseBuilder::error("Oops! Something went wrong.", $this->serverError);
		}
	}

	public function proposalDecline(Request $request)
	{
		try{
			if(!Auth::guard('api')->check())
			{
				return ResponseBuilder::error("User not found", $this->badRequest);
			}
			$user_id = Auth::guard('api')->user()->id;
			$validator = Validator::make($request->all(),[
				'proposal_id' 	=> 'required|exists:send_proposals,id',
				'reason'		=>	'required',
			]);
			if($validator->fails())
			{
				return ResponseBuilder::error($validator->errors()->first(),$this->badRequest);
			}
			$existProposal = DeclineData::where('data_id',$request->proposal_id)->where('type','decline')->first();

			if(!empty($existProposal)){
				return ResponseBuilder::error("Already declined this proposal", $this->badRequest);
			}
			$proposadata = SendProposal::where('id',$request->proposal_id)->where('additional_status','open')->first();
			if($proposadata == null){
				return ResponseBuilder::error("No open proposal", $this->badRequest);
			}
			$proposadata->additional_status = 'decline';
			$proposadata->save();

			$declineProposal = new DeclineData;
			$declineProposal->data_id = $request->proposal_id;
			$declineProposal->client_id = $proposadata->client_id;
			$declineProposal->freelancer_id = $proposadata->freelancer_id;
			$declineProposal->project_id = $proposadata->project_id;
			$declineProposal->reason = $request->reason;
			$declineProposal->description = $request->description;
			$declineProposal->type = 'decline_proposal';
			$declineProposal->save();


			return ResponseBuilder::successMessage("Declined proposal", $this->success);

		}
		catch(\Exception $e)
		{
			return ResponseBuilder::error("Oops! Something went wrong.",$this->serverError);
		}
	}

	public function proposalWithdraw(Request $request)
	{
		try
		{
			if(!Auth::guard('api')->check())
			{
				return ResponseBuilder::error("User not found", $this->badRequest);
			}
			$user_id = Auth::guard('api')->user()->id;
			$validator = Validator::make($request->all(),[
				'proposal_id' 	=> 'required|exists:send_proposals,id',
				'reason'		=>	'required',
			]);
			if($validator->fails())
			{
				return ResponseBuilder::error($validator->errors()->first(),$this->badRequest);
			}
			$existProposal = DeclineData::where('data_id',$request->proposal_id)->where('type','withdraw_proposal')->first();

			if(!empty($existProposal)){
				return ResponseBuilder::error("Already withdraw this proposal ", $this->badRequest);
			}
			$proposadata = SendProposal::where('id',$request->proposal_id)->where('additional_status','open')->first();
			if($proposadata == null){
				return ResponseBuilder::error("No open proposal", $this->badRequest);
			}
			$proposadata->additional_status = 'withdraw';
			$proposadata->save();

			$declineProposal = new DeclineData;
			$declineProposal->data_id = $request->proposal_id;
			$declineProposal->client_id = $proposadata->client_id;
			$declineProposal->freelancer_id = $proposadata->freelancer_id;
			$declineProposal->project_id = $proposadata->project_id;
			$declineProposal->reason = $request->reason;
			$declineProposal->description = $request->description;
			$declineProposal->type = 'withdraw_proposal';
			$declineProposal->save();

			return ResponseBuilder::successMessage("Withdraw proposal", $this->success);

		}
		catch(\Exception $e)
		{
			return ResponseBuilder::error("Oops! Something went wrong.",$this->serverError);
		}
	}

	public function inviteDecline(Request $request)
	{
		try
		{
			if(!Auth::guard('api')->check())
			{
				return ResponseBuilder::error("User not found", $this->badRequest);
			}
			$user_id = Auth::guard('api')->user()->id;
			$validator = Validator::make($request->all(),[
				'invitaion_id' 	=> 'required|exists:invite_freelancer,id',
				'reason'		=>	'required',
			]);
			if($validator->fails())
			{
				return ResponseBuilder::error($validator->errors()->first(),$this->badRequest);
			}
			$existProposal = DeclineData::where('data_id',$request->invitaion_id)->where('type','decline_invite')->first();

			if(!empty($existProposal)){
				return ResponseBuilder::error("Already decline this invitation ", $this->badRequest);
			}
			$inviteData = InviteFreelacner::where('id',$request->invitaion_id)->where('status','pending')->first();

			if($inviteData == null){
				return ResponseBuilder::error("No open invitation", $this->badRequest);
			}

			$inviteData->status = 'decline';
			$inviteData->save();

			$declineInvite = new DeclineData;
			$declineInvite->data_id = $request->invitaion_id;
			$declineInvite->client_id = $inviteData->client_id;
			$declineInvite->freelancer_id = $inviteData->freelancer_id;
			$declineInvite->project_id = $inviteData->project_id;
			$declineInvite->reason = $request->reason;
			$declineInvite->description = $request->description;
			$declineInvite->type = 'decline_invite';
			$declineInvite->save();

			return ResponseBuilder::successMessage("Declined invitation ", $this->success);

		}
		catch(\Exception $e)
		{
			return ResponseBuilder::error("Oops! Something went wrong.", $this->serverError);
		}
	}

	public function offerDecline(Request $request)
	{
		try
		{
			if(!Auth::guard('api')->check())
			{
				return ResponseBuilder::error("User not found", $this->badRequest);
			}
			$user_id = Auth::guard('api')->user()->id;
			$validator = Validator::make($request->all(),[
				'offer_id' 		=> 'required|exists:send_proposals,id',
				'reason'		=>	'required',
			]);
			if($validator->fails())
			{
				return ResponseBuilder::error($validator->errors()->first(),$this->badRequest);
			}
			$existOffer = DeclineData::where('data_id',$request->offer_id)->where('type','decline_offer')->first();

			if(!empty($existOffer)){
				return ResponseBuilder::error("Already decline this offer ", $this->badRequest);
			}
			$offerData = SendProposal::where('id',$request->offer_id)->where('status','pending')->first();

			if($offerData == null){
				return ResponseBuilder::error("No offer ", $this->badRequest);
			}

			$offerData->status = 'decline';
			$offerData->save();

			$declineInvite = new DeclineData;
			$declineInvite->data_id = $request->offer_id;
			$declineInvite->client_id = $offerData->client_id;
			$declineInvite->freelancer_id = $offerData->freelancer_id;
			$declineInvite->project_id = $offerData->project_id;
			$declineInvite->reason = $request->reason;
			$declineInvite->description = $request->description;
			$declineInvite->type = 'decline_offer';
			$declineInvite->save();

			return ResponseBuilder::successMessage("Declined offer ", $this->success);

		}
		catch(\Exception $e)
		{
			return ResponseBuilder::error("Oops! Something went wrong.", $this->serverError);
		}
	}

	public function updateProposal(Request $request)
	{
		try
      	{
         	if (Auth::guard('api')->check()) {
	            $singleuser = Auth::guard('api')->user();
	            $user_id = $singleuser->id;
	        } 
         	else{
            	return ResponseBuilder::error(__("User not found"), $this->unauthorized);
         	} 

        	$validator = Validator::make($request->all(), [
	            'proposal_id'     =>	'required|exists:send_proposals,id',
	            'milestone_type'  =>	'nullable|in:single,multiple',
	            'bid_amount'      => 	'max:10'
         	]);

         	if ($validator->fails()) {
            	return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
         	} 

         	$send_proposal = SendProposal::where('id',$request->proposal_id)->where('type','proposal')->first();

         	if(!$send_proposal)
         	{
            	return ResponseBuilder::error(__("No proposal from this id"), $this->badRequest);
         	} 

         	if(($send_proposal->client_id != $user_id) && ($send_proposal->freelancer_id != $user_id)){
         		return ResponseBuilder::error("You are doing something wrong! Please login with valid credentials.", $this->badRequest);
         	}
         	
            
            $proposal = SendProposal::updateOrCreate([
               'id' => $request->proposal_id
            ], [
               'amount'          => $request->bid_amount,
               'project_duration'=>	$request->project_duration
            ]);
            
	    	if($send_proposal->budget_type == 'fixed') {

	        	$allMilestone = ProjectMilestone::where('proposal_id',$request->proposal_id)->get();

	        	if(count($allMilestone) > 0){
	            	$milestoneHistory = ProjectMilestone::where('proposal_id',$request->proposal_id)->delete();
	        	}
	            if($request->milestone_type == "multiple"){

	            	$milestone_data = json_decode($request->milestone_data,1);
	            	if(!empty($milestone_data)){
	         			foreach ($milestone_data as $value) {
	                        $mileStones[] = [
	                           'proposal_id' => $request->proposal_id,
	                           'project_id' => $send_proposal->project_id,
	                           'client_id' => $send_proposal->client_id,
	                           'freelancer_id' => $send_proposal->freelancer_id,
	                           'project_duration'=>$request->project_duration,
	                           'description' => $value['description'],
	                           'due_date' => $value['due_date'],
	                           'amount' => $value['amount'],
	                           'status' => 'created',
	                           'note' => '',
	                           'type' => $request->milestone_type
	                        ];
                     	}
                     	ProjectMilestone::insert($mileStones);
	      			}
	            }
	    	}
        		
            $this->response = $proposal->id;
            return ResponseBuilder::success($this->response,"Updated proposal details");
         	
      	}
      	catch(\Exception $e)
      	{

         	return ResponseBuilder::error("Oops! Something went wrong.",$this->serverError);
      	}
	}

}
