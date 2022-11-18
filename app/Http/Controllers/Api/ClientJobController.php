<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Admin\ProjectResource;
use App\Http\Resources\Admin\FreelancerCollection;
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
use App\Models\Client;
use App\Models\SavedTalent;
use App\Models\IncomeSource;
use App\Models\InviteFreelacner;
use Illuminate\Http\Request;
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
	        	return ResponseBuilder::successWithPagination($jobdata,$this->response,'Client all Post Jobs',$this->success);
	       	}else{
	       		return ResponseBuilder::error("No data found", $this->badRequest);
	       	}
	        
		}
		catch(\Expection $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
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
	        	return ResponseBuilder::successWithPagination($jobdata,$this->response,'Client all draft Post Jobs',$this->success);
	       	}else{
	       		return ResponseBuilder::error("No data found", $this->badRequest);
	       	}

		}
		catch(\Expection $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
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
	        	return ResponseBuilder::error("Already Sent Invite", $this->badRequest);
	        }

		}
		catch(\Expection $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
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
	        		return ResponseBuilder::error("Project is Active , You can not close this Project", $this->badRequest);
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
	        		return ResponseBuilder::successMessage("Project Close sucessfully", $this->success);
	        	}
	        }
	        else
	        {
	        	return ResponseBuilder::error("No Data found", $this->notFound);
	        }
		}
		catch(\Expection $e)
		{
			return ResponseBuilder::error($e->getMessage(), $this->badRequest);
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

				return ResponseBuilder::successMessage('Talent saved Successfully', $this->success);
			}

		} catch (\Exception $e) {
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
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
				if(!empty($talentuser))
		        {
		            $this->response = new FreelancerCollection($talentuser);
		            return ResponseBuilder::success($this->response, "All Saved Talent List");
		        }
		        else{
		        	return ResponseBuilder::error("No Data found", $this->serverError);
		        }
			}else{
				return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
			}
		}
		catch(\Expection $e)
		{

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
         	$all_inviteFreelance = InviteFreelacner::where('client_id',$user_id)->pluck('freelancer_id')->toArray();

         	if(!empty($all_inviteFreelance)){
				$freelance_data = User::whereIn('id',$all_inviteFreelance)->with('freelancer')->get();
				// dd($freelance_data);
				if(!empty($freelance_data))
		        {
		            $this->response = new FreelancerCollection($freelance_data);
		            return ResponseBuilder::success($this->response, "Invited freelacner's List");
		        }
		        else{
		        	return ResponseBuilder::error("No Data found", $this->notFound);
		        }
			}else{
				return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
			}

		}
		catch(\Expection $e)
		{
			return ResponseBuilder::error($e->getMessage(), $this->serverError);
		}
	} 

}