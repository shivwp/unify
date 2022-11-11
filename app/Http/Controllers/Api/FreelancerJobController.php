<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Admin\ProjectResource;
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
use App\Models\Agency;
use App\Models\Client;
use App\Models\IncomeSource;
use Illuminate\Http\Request;
use Validator;


class FreelancerJobController extends Controller
{
	public function saveJobList(Request $request)
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
	        $savedP = SavedProject::where('user_id',$user_id)->pluck('project_id')->toArray();
	        
	        $job_list = Project::whereIn('id',$savedP)->with('skills','categories');
			$jobdata = $job_list->paginate($page);
			if(count($jobdata) > 0){
				$jobdata->user_id = $user_id;
		        $this->response = new ProjectResource($jobdata);
		        return ResponseBuilder::successWithPagination($jobdata,$this->response, "Saved job list",$this->success);
			}else{
				return ResponseBuilder::error('No Saved jobs', $this->notFound);
			}
			
	        // return ResponseBuilder::successWithPagination($jobdata,$this->response, "Jobs List Data",$this->success);
		}
		catch(\Expection $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}
	}
}