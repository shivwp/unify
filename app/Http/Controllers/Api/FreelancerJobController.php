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
	        } 
	        else{
	            return ResponseBuilder::error(__("User not found"), $this->unauthorized);
	        }

	        $page = !empty($request->pagination) ? $request->pagination : 10; 
	        $savedP = SavedProject::where('user_id', $user_id)->pluck('project_id')->toArray();
	        
	        $query = Project::with('skills','categories');

			// Filter Project Category
			if(isset($request->project_category) ) {
				$categories = explode(',', $request->project_category);
				$query->whereIn('project_category', $categories);
			}
			// Filter Project Search
			if(isset($request->search) ) {
				$query->where('name', 'like', "%$request->search%");
				$query->orWhere('description', 'like', "%$request->search%");
			}
			
			// Filter Project skills
			if(isset($request->skills) ) {
				$skills = explode(',', $request->skills);
				$projectsIds = ProjectProjectSkill::whereIn('project_skill_id', $skills)->pluck('project_id')->toArray();
				
				$projectIds = array_intersect($projectsIds, $savedP);
				$query->whereIn('id', $projectIds);
			} else {
				$query->whereIn('id', $savedP);
			}

			// Filter Project Type
			if(isset($request->type) ) {
				$query->where('type', $request->type);
			}

			// Filter Project budget type
			if(isset($request->budget_type) ) {
				$query->where('budget_type', $request->budget_type);
			}

			// Filter Project EnglishLevel
			if(isset($request->english_level) ) {
				$query->where('english_level', $request->english_level);
			}

			// Filter Project EnglishLevel
			if(isset($request->language) ) {
				$query->where('language', $request->language);
			}
			
			// Filter Project Price - MIN
			if(isset($request->min_price) ) {
				$query->where('min_price', '>=', $request->min_price);
			}
			// Filter Project Price - MAX
			if(isset($request->max_price) ) {
				$query->where('price', '<=', $request->max_price);
			}

			$jobdata = $query->paginate($page);
			
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