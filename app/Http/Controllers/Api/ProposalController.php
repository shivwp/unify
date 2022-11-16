<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Admin\ProjectResource;
use App\Http\Resources\Admin\ProposalCollectionResource;
use App\Http\Resources\Admin\ProjectSingleResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Helper\ResponseBuilder;
use App\Models\ProjectProjectSkill;
use App\Models\ProjectCategory;
use App\Models\FreelancerSkill;
use App\Models\SendProposal;
use App\Models\ProjectMilestone;
use App\Models\SavedProject;
use App\Models\Project;
use App\Models\IncomeSource;
use Illuminate\Http\Request;
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
	        $proposals = SendProposal::join('projects','send_proposals.project_id','projects.id')->where('send_proposals.freelancer_id',$user_id)->orderBy('send_proposals.created_at','DESC')->select('projects.id','projects.name','projects.description','send_proposals.created_at','send_proposals.status','projects.client_id','send_proposals.id as send_proposal_id','send_proposals.cover_letter')->get();

	        $activeproposals = SendProposal::join('projects','send_proposals.project_id','projects.id')->where('send_proposals.freelancer_id',$user_id)->where('send_proposals.status','active')->select('projects.id','projects.name','projects.description','send_proposals.created_at','send_proposals.status','projects.client_id','send_proposals.id as send_proposal_id','send_proposals.cover_letter')->get();

	        if(count($proposals) > 0){
	        	$this->response->submittedProposal = new ProposalCollectionResource($proposals);
	        	$this->response->activeProposal = new ProposalCollectionResource($activeproposals);
	        	$this->response->interviewForInvitation = new ProposalCollectionResource($activeproposals);
	        	return ResponseBuilder::success($this->response, "All Submitted Proposals");
	        }else{
	        	return ResponseBuilder::error("No Proposal submit",$this->badRequest);
	        }
		}
		catch(\Exception $e)
      	{
         	return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
      	}
	}

	public function hireFreelancer(Request $request)
	{
		// try
		// {
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
                'amount'		=>	'required',
                'contract_date'	=>	'required',
                'milestone_type'=>	'required_if:budget_type,fixed'
            ]);
            if ($validator->fails()) {   
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);  
            }
            $exists_hire = SendProposal::where('project_id',$request->project_id)->where('client_id',$user_id)->where('freelancer_id',$request->freelancer_id)->first();
            if(!empty($exists_hire))
            {
            	return ResponseBuilder::error('Already Send an offer to this freelance !', $this->badRequest);  
            }
            else
            {
            	
            	$hiring = new SendProposal;
            	$hiring->project_id = $request->project_id;
            	$hiring->client_id = $user_id;
            	$hiring->freelancer_id = $request->freelancer_id;
            	$hiring->budget_type = $request->budget_type;
            	$hiring->amount = $request->amount[0];
            	$hiring->weekly_limit = $request->weekly_limit;
            	$hiring->title = $request->title;
            	$hiring->date = $request->contract_date[0];
            	$hiring->cover_letter = $request->description[0];
            	$hiring->status = 'pending';
            	$hiring->type = 'offer';
            	$hiring->save();

            	// dd($hiring);

            	if($request->budget_type == 'fixed'){
            		if($request->milestone_type == 'multiple')
            		{
            			$mileStonesDescription = $request->description;
                  		$mileStonesAmount = $request->amount;
                  		$mileStonesDueDate = $request->contract_date;

		                foreach ($mileStonesDescription as $key => $value) {
		                    # code...
		                    $mileStones[] = [
		                        'proposal_id' => $hiring->id,
		                        'project_id' => $request->project_id,
		                        'client_id' => $user_id,
		                        'freelancer_id' => $request->freelancer_id,
		                        'description' => $value,
		                        'amount' => $mileStonesAmount[$key],
		                        'due_date' => $mileStonesDueDate[$key],
		                        'status' => 'created',
		                        'type' => $request->milestone_type
		                    ];
		                }
		                ProjectMilestone::insert($mileStones);
            		}else
            		{
            			$add_milestone = new ProjectMilestone;
	            		$add_milestone->proposal_id = $hiring->id;
	            		$add_milestone->project_id = $request->project_id;
	            		$add_milestone->client_id = $user_id;
	            		$add_milestone->freelancer_id = $request->freelancer_id;
	            		$add_milestone->description = $request->description;
	            		$add_milestone->amount = $request->amount;
	            		$add_milestone->due_date = $request->contract_date;
	            		$add_milestone->type = $request->milestone_type;
	            		$add_milestone->status = 'created';
	            		$add_milestone->save();
            		}
            		
            	}
            	DB::commit();
            	return ResponseBuilder::successMessage("Send offer successfully",$this->success);

            }
		// }
		// catch(\Exception $e)
		// {
		// 	 DB::rollback();
		// 	return ResponseBuilder::error($e->getMessage(), $this->serverError);
		// }
	}

}
