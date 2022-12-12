<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Admin\ProjectResource;
use App\Http\Resources\Admin\FreelancerProposalCollection;
use App\Http\Resources\Admin\FreelancerCollection;
use App\Http\Resources\Admin\JobProposalCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Helper\ResponseBuilder;
use App\Models\ProjectProjectSkill;
use App\Models\ProjectCategory;
use App\Models\FreelancerSkill;
use App\Models\SendProposal;
use App\Models\SavedProject;
use App\Models\Project;
use App\Models\Freelancer;
use App\Models\User;
use App\Models\CloseJob;
use App\Models\Agency;
use App\Models\SaveArchive;
use App\Models\Client;
use App\Models\SavedTalent;
use App\Models\IncomeSource;
use App\Models\InviteFreelacner;
use App\Models\ShortListed;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;


class ClientJobController extends Controller
{
	public function allPosting(Request $request)
	{
		try
		{
			if (Auth::guard('api')->check()) {
            $singleuser = Auth::guard('api')->user();
            $user_id = $singleuser->id;
            // dd($user_id);
	        } 
	        else{
	            return ResponseBuilder::error(__("User not found"), $this->unauthorized);
	        }
	        $page = !empty($request->pagination) ? $request->pagination : 10; 
	        $job_list = Project::where('client_id',$user_id)->where('status','publish')->orderBy('created_at','DESC')->with('skills','categories');

       		if(!empty($request->title)){
       			$job_list->where('name', 'LIKE', "%$request->title%");
       		}

       		// Filter Project budget type
	        if(isset($request->budget_type) ) {
	            $job_list->where('budget_type', $request->budget_type);
	        }

	         // Filter Project status
	        if(isset($request->status) ) {
	            $job_list = Project::where('client_id',$user_id)->where('status',$request->status)->orderBy('created_at','DESC')->with('skills','categories');
	        }
       		
       		$jobdata = $job_list->paginate($page);
	       	if(count($jobdata) > 0){
	       		$jobdata->user_id = $user_id;
	       		$this->response = new ProjectResource($jobdata);
	        	return ResponseBuilder::successWithPagination($jobdata,$this->response,'Client all post jobs',$this->success);
	       	}else{
	       		return ResponseBuilder::successWithPagination($jobdata,[],"No data found",$this->success);
	       	}
	        
		}
		catch(\Expection $e)
		{
			return ResponseBuilder::error(__("Oops! Something went wrong."), $this->serverError);
		}
	}
	public function allDraftPosting(Request $request)
	{	
		try
		{
			if (Auth::guard('api')->check()) {
            $singleuser = Auth::guard('api')->user();
            $user_id = $singleuser->id;
            // dd($user_id);
	        } 
	        else{
	            return ResponseBuilder::error(__("User not found"), $this->unauthorized);
	        }
	        $page = !empty($request->pagination) ? $request->pagination : 10; 
	        $job_list = Project::where('client_id',$user_id)->where('status','draft')->orderBy('created_at','DESC')->with('skills','categories');
	       	if(!empty($request->title)){
       			$job_list->where('name', 'LIKE', "%$request->title%");
       		}
       		$jobdata = $job_list->paginate($page);
	        	$jobdata->user_id = $user_id;
	        if(count($jobdata) > 0){
	       		$jobdata->user_id = $user_id;
	       		$this->response = new ProjectResource($jobdata);
	        	return ResponseBuilder::successWithPagination($jobdata,$this->response,'Client all draft post jobs',$this->success);
	       	}else{
	       		return ResponseBuilder::successWithPagination($jobdata,[],"No data found",$this->success);
	       	}

		}
		catch(\Expection $e)
		{
			return ResponseBuilder::error(__("Oops! Something went wrong."), $this->serverError);
		}
	} 

	public function inviteFreelancer(Request $request)
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
         		'freelancer_id'  	=> 'required|exists:freelancer,user_id',
         		'project_id'		=> 'required|exists:projects,id',
         		'description'		=> 'required'
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }
	        $check_exist_freelacner = InviteFreelacner::where('client_id',$user_id)->where('freelancer_id',$request->freelancer_id)->where('project_id',$request->project_id)->first();
	        if(empty($check_exist_freelacner)){
		        $invite_freelancer = new InviteFreelacner;
		        $invite_freelancer->client_id = $user_id;
		        $invite_freelancer->freelancer_id = $request->freelancer_id;
		        $invite_freelancer->project_id = $request->project_id;
		        $invite_freelancer->description = $request->description;
		        $invite_freelancer->status = 'pending';
		        $invite_freelancer->save();
		        return ResponseBuilder::successMessage("Invite sucessfully", $this->success);
	        }else{
	        	return ResponseBuilder::error("Already sent invite", $this->badRequest);
	        }

		}
		catch(\Expection $e)
		{
			return ResponseBuilder::error(__("Oops! Something went wrong."), $this->serverError);
		}
	}

	public function closeJob(Request $request)
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
         		'project_id'	=> 'required|exists:projects,id',
         		'reason_id'		=> 'required|exists:project_close_reasons,id'
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }
	        $clientProject = Project::where('id',$request->project_id)->where('client_id',$user_id)->first();
	        if(!empty($clientProject))
	        {
	        	if($clientProject->status == "active")
	        	{
	        		return ResponseBuilder::error("Project is active , You can not close this project", $this->badRequest);
	        	}
	        	elseif($clientProject->status == "close")
	        	{
	        		return ResponseBuilder::error("Project is already closed", $this->badRequest);
	        	}else
	        	{
	        		$clientProject->status = 'close';
	        		$clientProject->save();
	        		$closeJob = new CloseJob;
	        		$closeJob->project_id = $request->project_id;
	        		$closeJob->reason_id = $request->reason_id;
	        		$closeJob->save();
	        		return ResponseBuilder::successMessage("Project close sucessfully", $this->success);
	        	}
	        }
	        else
	        {
	        	return ResponseBuilder::error("No Data found", $this->notFound);
	        }
		}
		catch(\Expection $e)
		{
			return ResponseBuilder::error("Oops! Something went wrong.", $this->badRequest);
		}
	}

	public function savedTalent(Request $request)
	{
		try {
			if(Auth::guard('api')->check()){
				$singleuser = Auth::guard('api')->user();
				$user_id = $singleuser->id;
			}
			else{
				return ResponseBuilder::error(__("User not found"),$this->unauthorized);
			}

			$validator = Validator::make($request->all(),[
				'freelancer_id' => 'required|exists:freelancer,user_id',
			]);

			if($validator->fails()){
				return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
			}

			$data = SavedTalent::where('client_id','=', $user_id)->where('freelancer_id','=', $request->freelancer_id)->first();

			if(!empty($data))
			{
				return ResponseBuilder::error('Already saved talent', $this->badRequest);
			}
			else
			{
				$savetalent = new SavedTalent;
				$savetalent->client_id = $user_id;
				$savetalent->freelancer_id = $request->freelancer_id;
				$savetalent->save();

				return ResponseBuilder::successMessage('Talent saved successfully', $this->success);
			}

		} catch (\Exception $e) {
			return ResponseBuilder::error(__("Oops! Something went wrong."), $this->serverError);
		}
	}


	public function removeSaveTalent(Request $request)
	{
		try {
			if(Auth::guard('api')->check()){
				$singleuser = Auth::guard('api')->user();
				$user_id = $singleuser->id;
			}
			else{
				return ResponseBuilder::error(__("User not found"),$this->unauthorized);
			}

			$validator = Validator::make($request->all(),[
				'freelancer_id' => 'required|exists:freelancer,user_id',
			]);

			if($validator->fails()){
				return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
			}

			$data = SavedTalent::where('client_id','=', $user_id)->where('freelancer_id','=', $request->freelancer_id)->first();

			if(!empty($data))
			{	
				$data->delete();
				return ResponseBuilder::successMessage('Remove saved talent', $this->success);
			}
			else
			{
				return ResponseBuilder::error('No data found', $this->badRequest);
			}

		} catch (\Exception $e) {
			return ResponseBuilder::error(__("Oops! Something went wrong."), $this->serverError);
		}
	}


	public function saveTalentList(Request $request)
	{
		try
		{
			if(Auth::guard('api')->check()){
				$singleuser = Auth::guard('api')->user();
				$user_id = $singleuser->id;
			}
			else{
				return ResponseBuilder::error(__("User not found"),$this->unauthorized);
			}
			$allsavetalent = SavedTalent::where('client_id',$user_id)->pluck('freelancer_id')->toArray();

			if(!empty($allsavetalent)){
				$talentuser = User::whereIn('id',$allsavetalent)->with('freelancer')->get();
				if(count($talentuser) > 0)
		        {
		        	foreach($talentuser as $value)
		        	{
		        		$skills = FreelancerSkill::where('user_id',$value->id)->select('skill_id','skill_name')->get();
		        		$value['skills'] = $skills;
		        	}
		            $this->response = new FreelancerCollection($talentuser);
		            return ResponseBuilder::success($this->response, "All saved talent list");
		        }
		        else{
		        	return ResponseBuilder::success($talentuser,"No data found");
		        }
			}else{
				return ResponseBuilder::success($allsavetalent,"No data found");
			}
		}
		catch(\Expection $e)
		{
			return ResponseBuilder::error("Oops! Something went wrong.", $this->serverError);
		}
	}	

	public function inviteFreelancerList(Request $request)
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
         	$all_inviteFreelance = InviteFreelacner::where('client_id',$user_id)->where('project_id',$request->project_id)->pluck('freelancer_id')->toArray();
         	if(!empty($all_inviteFreelance)){
				$freelance_data = User::whereIn('id',$all_inviteFreelance)->with('freelancer')->get();
				if(count($freelance_data) > 0)
		        {	
		        	foreach($freelance_data as $value)
		        	{
		        		$skills = FreelancerSkill::where('user_id',$value->id)->select('skill_id','skill_name')->get();
		        		$value['skills'] = $skills;
		        	}
		            $this->response = new FreelancerCollection($freelance_data);
		            return ResponseBuilder::success($this->response, "Invited freelancer's list");
		        }
		        else{
		        	return ResponseBuilder::success($freelance_data,"No data found");
		        }
			}else{
				return ResponseBuilder::success($all_inviteFreelance,'No data found');
			}
		}
		catch(\Expection $e)
		{
			return ResponseBuilder::error("Oops! Something went wrong.", $this->serverError);
		}
	} 

	public function savetoArchive(Request $request)
   	{ 

      	try 
      	{
          	if(Auth::guard('api')->check()){
	            $singleuser = Auth::guard('api')->user();
	            $user_id = $singleuser->id;
         	}else{
            	return ResponseBuilder::error(__("client not found"), $this->unauthorized);
         	}

          	$validator = Validator::make($request->all(), [
	            'freelancer_id'=>'required|exists:freelancer,user_id',
	            'job_id' => 'required|exists:projects,id',
	        ]);

         	if ($validator->fails()) {
            	return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
         	}

         	$exist = SaveArchive::where('client_id',$user_id)
     							->where('freelancer_id',$request->freelancer_id)
	                            ->where('job_id',$request->job_id)->first();

         	if($exist){
         		return ResponseBuilder::error(__("Already saved to archived"), $this->badRequest);
         	}
         	else
         	{
         	$save_archived = new SaveArchive;
         	$save_archived->job_id = $request->job_id;
         	$save_archived->freelancer_id = $request->freelancer_id;
         	$save_archived->client_id = $user_id;
         	$save_archived->save();

         	return ResponseBuilder::successMessage("Save to archive", $this->success);
         	}
            
   		} 
   		catch (Exception $e) 
   		{
       		return ResponseBuilder::error(__("Oops! Something went wrong."), $this->serverError);     
   		}
   	}
   	public function archiveFreelancer($job_id)
   	{
   		try
   		{
   			if(Auth::guard('api')->check())
   			{
   				$singleuser = Auth::guard('api')->user();
   				$user_id = $singleuser->id;
   			}
   			else
   			{
   				return ResponseBuilder::error(__("Client not found"), $this->unauthorized);
   			}

   			$save_archived = SaveArchive::where('client_id',$user_id)->where('job_id',$job_id)->pluck('freelancer_id')->toArray();
   			$data = SendProposal::join('freelancer','freelancer.user_id','send_proposals.freelancer_id')
   								->join('users','users.id','freelancer.user_id')
   								->whereIn('send_proposals.freelancer_id', $save_archived)
   								->where('project_id',$job_id)
   								->select('send_proposals.freelancer_id','send_proposals.id as send_proposal_id','send_proposals.client_id','send_proposals.project_id','send_proposals.cover_letter','users.first_name','users.last_name','users.profile_image','users.country','users.city','freelancer.occcuption','freelancer.amount','freelancer.total_earning')
   								->get();

   			if(count($data) > 0)
   			{
   				foreach($data as $value)
	        	{
	        		$skills = FreelancerSkill::where('user_id',$value->freelancer_id)->select('skill_id','skill_name')->get();
	        		$value['skills'] = $skills;
	        	}
   				$this->response = new JobProposalCollection($data);
   				return ResponseBuilder::success($this->response , $this->success);
   			}
   			else
   			{
   				return ResponseBuilder::success($data,"No Data found");
   			}

   		}
   		catch (Exception $e) 
   		{
       		return ResponseBuilder::error(__("Oops! Something went wrong."), $this->serverError);     
   		}

   	}  
   	public function removeArchiveFreelancer(Request $request)
   	{
   		try 
   		{
   			if(!Auth::guard('api')->check())
   			{
   				return ResponseBuilder::error(__("Client not found"), $this->unauthorized);
   			} 

   			$singleuser = Auth::guard('api')->user();
   			$user_id = $singleuser->id;

   			$validator = Validator::make($request->all(), [
	            'freelancer_id' => 'required|exists:freelancer,user_id',
	            'job_id' =>  'required|exists:projects,id',
	        ]);

   			if($validator->fails())
   			{
   				return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
   			}

   			$data = SaveArchive::where('job_id',$request->job_id)->where('freelancer_id',$request->freelancer_id)->where('client_id',$user_id)->first();

   			if(!$data)
   			{
   				return ResponseBuilder::error("No data found", $this->notFound);
   			}
   			
			$data->delete();
			return ResponseBuilder::successMessage("Remove from archived successfully", $this->success);
   		} 
   		catch (Exception $e) 
   		{
   			return ResponseBuilder::error(__("Oops! Something went wrong."), $this->serverError); 
   		}
   	}

   	public function addInShortList(Request $request)
   	{
   		try
   		{

	   		if(!Auth::guard('api')->check())
   			{
   				return ResponseBuilder::error(__("Client not found"), $this->unauthorized);
   			} 

   			$user_id = Auth::guard('api')->user()->id;

   			$validator = Validator::make($request->all(),[

   				'freelancer_id' => 'required|exists:freelancer,user_id',
	            'job_id' =>  'required|exists:projects,id',

   			]);

   			if($validator->fails())
   			{
   				return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
   			}

   			$exist = ShortListed::where('client_id',$user_id)
     							->where('freelancer_id',$request->freelancer_id)
	                            ->where('job_id',$request->job_id)->first();

         	if($exist)
         	{
         		return ResponseBuilder::error(__("Already shortlisted"), $this->badRequest);
         	}

     		$shortList = new ShortListed;
     		$shortList->freelancer_id = $request->freelancer_id;
     		$shortList->client_id = $user_id;
     		$shortList->job_id = $request->job_id;
     		$shortList->save();

     		return ResponseBuilder::successMessage("ShortListed successfully", $this->success);

   		}
   		catch(Exception $e)
   		{
   			return ResponseBuilder::error(__("Oops! Something went wrong."), $this->serverError);
   		}
   	}

   	public function removeFromShortList(Request $request)
   	{
   		try 
   		{
   			if(!Auth::guard('api')->check())
   			{
   				return ResponseBuilder::error(__("Client not found"), $this->unauthorized);
   			} 

   			$user_id = Auth::guard('api')->user()->id;

   			$validator = Validator::make($request->all(),[

   				'freelancer_id' => 'required|exists:freelancer,user_id',
	            'job_id' =>  'required|exists:projects,id',

   			]);

   			if($validator->fails())
   			{
   				return ResponseBuilder::error($validator->error()->first(), $this->badRequest);
   			}

   			$data = ShortListed::where('client_id',$user_id)->where('job_id',$request->job_id)->where('freelancer_id',$request->freelancer_id)->first();

   			if(empty($data))
   			{
   				return ResponseBuilder::error("Data not found", $this->notFound);
   			}

   			$data->delete();
   			return ResponseBuilder::successMessage("Remove from shortlist", $this->success);

   		}
   		 catch (Exception $e)
   		{
   			return ResponseBuilder::error(__("Oops! Something went wrong."), $this->serverError);
   		}
   	}

   	public function shortListDetail($job_id)
   	{
   		try
   		{
   			
   			if(!Auth::guard('api')->check())
   			{
   				return ResponseBuilder::error(__("Client not found"), $this->unauthorized);
   			} 

   			$user_id = Auth::guard('api')->user()->id;

			
   			$frelancer_ids = ShortListed::where('client_id', $user_id)->where('job_id',$job_id)->pluck('freelancer_id')->toArray();

   			$data = SendProposal::join('freelancer','freelancer.user_id','send_proposals.freelancer_id')
   								->join('users','users.id','send_proposals.freelancer_id')
   								->whereIn('send_proposals.freelancer_id', $frelancer_ids)
   								->where('project_id',$job_id)
   								->select('send_proposals.freelancer_id','send_proposals.id as send_proposal_id','send_proposals.client_id','send_proposals.project_id','send_proposals.cover_letter','users.first_name','users.last_name','users.profile_image','users.country','users.city','freelancer.occcuption','freelancer.amount','freelancer.total_earning')
   								->get();
   			if(count($data) > 0)
   			{
	   			foreach($data as $value)
		        {
	        		$skills = FreelancerSkill::where('user_id',$value->freelancer_id)->select('skill_id','skill_name')->get();
	        		$value['skills'] = $skills;
		        }
				$this->response = new JobProposalCollection($data);
				return ResponseBuilder::success($this->response , "ShortListed freelancers");
   			}
   			else{
   				return ResponseBuilder::success( $data,"No Data found");
   			}
   			
   		}
   		catch (Exception $e)
   		{
   			return ResponseBuilder::error(__("Oops! Something went wrong."), $this->serverError);
   		}
   	}

}