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
use App\Models\SavedProject;
use App\Models\Project;
use App\Models\IncomeSource;
use Illuminate\Http\Request;
use Validator;


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
	        $proposals = SendProposal::join('projects','send_proposals.job_id','projects.id')->where('send_proposals.user_id',$user_id)->orderBy('send_proposals.created_at','DESC')->select('projects.id','projects.name','projects.description','send_proposals.created_at','send_proposals.status','projects.client_id','send_proposals.id as send_proposal_id','send_proposals.cover_letter')->get();

	        $activeproposals = SendProposal::join('projects','send_proposals.job_id','projects.id')->where('send_proposals.user_id',$user_id)->where('send_proposals.status','active')->select('projects.id','projects.name','projects.description','send_proposals.created_at','send_proposals.status','projects.client_id','send_proposals.id as send_proposal_id','send_proposals.cover_letter')->get();

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

}