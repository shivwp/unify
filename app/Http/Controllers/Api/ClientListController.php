<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Admin\FreelancerFilterCollection;
use App\Http\Resources\Admin\JobProposalCollection;
use App\Http\Resources\Admin\FreelancerCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Helper\ResponseBuilder;
use Illuminate\Http\Request;
use App\Models\SendProposal;
use App\Models\DeclineData;
use App\Models\ProjectProjectSkill;
use App\Models\FreelancerSkill;
use App\Models\SaveArchive;
use App\Models\Freelancer;
use App\Models\Project;
use App\Models\User;
use Validator;
use DB;


class ClientListController extends Controller
{
	public function jobFreelancerList(Request $request)
	{
		try
		{
			if(Auth::guard('api')->check())
			{
				$singleuser = Auth::guard('api')->user();
	            $user_id = $singleuser->id;
			}else{
				return ResponseBuilder::error("User not found", $this->badRequest);
			}
			$validator = Validator::make($request->all(), [
            	'project_id'  => 'required|exists:projects,id',
         	]);

         	if ($validator->fails()) {
         	   return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
         	}
         	$page = !empty($request->pagination) ? $request->pagination : 10; 
         	$clientId = Project::where('id',$request->project_id)->select('id','client_id')->first();
         	$projectSkills = ProjectProjectSkill::where('project_id',$request->project_id)->pluck('project_skill_id')->toArray();
         	$freelancerSkills = FreelancerSkill::whereIn('skill_id',$projectSkills)->pluck('user_id')->toArray();

         	$freelancerData = Freelancer::join('users','users.id','freelancer.user_id')
         					->leftjoin('project_category','project_category.id','freelancer.category')
         					->whereIn('users.id',$freelancerSkills)
         					->select('users.*','freelancer.*','project_category.name as projectCategoryName');

         	$freelanceSkill= FreelancerSkill::whereIn('user_id',$freelancerSkills)->select('user_id', 'skill_id','skill_name')->get();
         	
         	$freelancerSkillData = [];
         	foreach ($freelanceSkill as $key => $value) {
         		// code...
         		$freelancerSkillData[$value->user_id][] = [
         			'skill_id' => $value->skill_id,
         			'skill_name' => $value->skill_name,
         		];
         	}

         	if(isset($request->title) ) {
	            $freelancerData->where('users.first_name', $request->title)->orwhere('users.last_name', $request->title);
	        }

         	$data = $freelancerData->paginate($page);
         	if(count($data) > 0)
         	{	
         		foreach ($data as $key => $value) {
         			$value->freelancerskills = isset($freelancerSkillData[$value->user_id])?$freelancerSkillData[$value->user_id]:[];
         			$value->client_project = $clientId;
         		}
     			$this->response = new FreelancerFilterCollection($data);
         		return ResponseBuilder::successWithPagination($data,$this->response, "Freelancer profile data",$this->success);
         	}else{
         		return ResponseBuilder::successWithPagination($data,[],"No data found",$this->success);
         	}
		}
		catch(\Expection $e)
		{
			return ResponseBuilder::error("Oops! Something went wrong.",$this->serverError);
		}
	}

	public function jobProposalList(Request $request)
	{
		try
		{
			if(!Auth::guard('api')->check())
			{
				return ResponseBuilder::error("User not found", $this->badRequest);
			}

			$user_id = Auth::guard('api')->user()->id;

			$validator = Validator::make($request->all(),[
				'project_id' => 'required|exists:projects,id',
			]);

			if($validator->fails())
			{
				return ResponseBuilder::error($validator->errors()->first(),$this->badRequest);
			}
			$page = !empty($request->pagination) ? $request->pagination : 10;
			$declineProposal = DeclineData::where('client_id',$user_id)->where('type','decline_proposal')->pluck('data_id')->toArray();
			$archived = SaveArchive::where('client_id',$user_id)->where('job_id',$request->project_id)->pluck('freelancer_id')->toArray();
			$propsalData = SendProposal::join('freelancer','freelancer.user_id','send_proposals.freelancer_id')->join('users','users.id','freelancer.user_id')->where('send_proposals.project_id',$request->project_id)->where('send_proposals.client_id',$user_id)->where('type','proposal')->where('send_proposals.status','pending')->select('send_proposals.freelancer_id','send_proposals.status as proposal_status','send_proposals.id as send_proposal_id','send_proposals.client_id','send_proposals.project_id','send_proposals.cover_letter','users.first_name','users.last_name','users.profile_image','users.country','users.city','freelancer.occcuption','freelancer.amount','freelancer.total_earning');

			if(!empty($archived))
			{
				$propsalData->whereNotIn('freelancer_id',$archived);
			}
			if($declineProposal){
	        	$propsalData->whereNotIn('send_proposals.id',$declineProposal);
	        }
			$dataproposal = $propsalData->paginate($page);
			if(count($dataproposal) > 0)
			{
				foreach($dataproposal as $value)
	        	{
	        		$skills = FreelancerSkill::where('user_id',$value->freelancer_id)->select('skill_id','skill_name')->get();
	        		$value['skills'] = $skills;
	        	}
				$this->response = new JobProposalCollection($dataproposal);
				return ResponseBuilder::successWithPagination($dataproposal, $this->response, "All proposals",$this->success);
			}else{
				return ResponseBuilder::successWithPagination($dataproposal,[],"No data found",$this->success);
			}

		}
		catch(\Expection $e)
		{
			return ResponseBuilder::error("Oops! Something went wrong.",$this->serverError);
		}
	}
}