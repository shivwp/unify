<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Storage;
use App\Helper\ResponseBuilder;
use App\Http\Resources\Admin\FreelancerResource;
use App\Http\Resources\Admin\FreelancerPortfolioResource;
use App\Http\Resources\Admin\FreelancerEducationCollection;
use App\Http\Resources\Admin\FreelancerTestimonialResource;
use App\Http\Resources\Admin\FreelancerCertificateResource;
use App\Http\Resources\Admin\FreelancerExperienceResource;
use App\Http\Resources\Admin\FreelancerSkillResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\FreelancerEducation;
use App\Models\FreelancerCertificate;
use App\Models\FreelancerTestimonial;
use App\Models\FreelancerExperience;
use App\Models\FreelancerPortfolio;
use App\Models\FreelancerSkill;
use App\Models\HoursPerWeek;
use App\Models\Freelancer;
use App\Models\ProjectSkill;
use App\Models\User;
use Carbon\Carbon;
use Validator;
use Config;
use Str;
use DB;

class FreelancerController extends Controller
{
	public function get_profile_info()
	{
		try
		{
			if (Auth::guard('api')->check()) {
	            $singleuser = Auth::guard('api')->user();
	            $userRole = strtolower($singleuser->roles()->first()->title);
	            if($userRole == 'freelancer'){
	            	$user_id = $singleuser->id;
	            }else{
	            	return ResponseBuilder::error(__("Login with Valid Freelancer Details"), $this->unauthorized);
	            }
	        } 
         	else{
             	return ResponseBuilder::error(__("User not found"), $this->unauthorized);
         	}
         	$freelancer_profile_data = $this->getFreelancerInfo($user_id);
         	$this->response->basic_info = new FreelancerResource($freelancer_profile_data);
         	$this->response->skills = new FreelancerSkillResource($freelancer_profile_data->freelancer->freelancer_skills);
         	$this->response->portfolio = new FreelancerPortfolioResource($freelancer_profile_data->freelancer->freelancer_portfolio);
         	$this->response->testimonial = new FreelancerTestimonialResource($freelancer_profile_data->freelancer->freelancer_testimonial);
         	$this->response->certificates = new FreelancerCertificateResource($freelancer_profile_data->freelancer->freelancer_certificates);
         	$this->response->experiences = new FreelancerExperienceResource($freelancer_profile_data->freelancer->freelancer_experiences);
         	$this->response->education = new FreelancerEducationCollection($freelancer_profile_data->freelancer->freelancer_education);

         	$freelancer_meta = $this->getFreelancerMeta($user_id);

         	//language
         	$language = [];
         	$languages = isset($freelancer_meta['language']) ? json_decode($freelancer_meta['language']) : [];
         	if(isset($languages) && !empty($languages)){
         		foreach ($languages as $key => $value) {
	         		$language[] = [
	         			'language'=>$key,
	         			'level'=>$value,
	         		];
	         	}
         	}
         	$this->response->language = $language;

         	//hour per week
         	$this->response->hours_per_week = isset($freelancer_meta['hours_per_week']) ? $freelancer_meta['hours_per_week'] : '';

         	//video
         	$video['url'] = isset($freelancer_meta['freelancer_video']) ? $freelancer_meta['freelancer_video'] : '';
         	$video['type'] = isset($freelancer_meta['freelancer_video_type']) ? $freelancer_meta['freelancer_video_type'] : '';
         	$this->response->video = isset($video) ? $video : '';

         	return ResponseBuilder::success($this->response, "Freelancer Profile Data");
		}
		catch(\Exception $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}
	}

	public function edit_name_info(Request $request)
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
         		'first_name'  => 'required',
         		'last_name'  => 'required',
         		'profile_image'=>'image'
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

         	$parameters = $request->all();
         		extract($parameters);
			
			$freelanc = User::with('freelancer')->where('id',$user_id)->first();
			$freelancer = Freelancer::updateOrCreate([
				'user_id' => $user_id,
			],[
				'occcuption'=> isset($request->occcuption) ? $request->occcuption : (($freelanc->freelancer->occcuption) ? $freelanc->freelancer->occcuption : ''),
			]);

			$user_name = User::where('id',$freelancer->user_id)->first();
         	$user_name->first_name = $request->first_name;
         	$user_name->last_name = $request->last_name;
         	$user_name->profile_image = !empty($request->hasFile('profile_image')) ? $this->uploadProfile_image($request->profile_image) : (($user_name->profile_image) ? $user_name->profile_image : '');
         	$user_name->save();

         	return ResponseBuilder::successMessage("Update Successfully", $this->success);
        }
        	catch(\Exception $e){
         	return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
      	}
	}

	public function edit_designation_info(Request $request)
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
         		'title'  => 'required',
         		'description'  => 'required',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

         	$parameters = $request->all();
         		extract($parameters);
			
			$freelanc = User::with('freelancer')->where('id',$user_id)->first();
			$freelancer = Freelancer::updateOrCreate([
				'user_id' => $user_id,
			],[
				'occcuption'=> $request->title,
				'description'=> $request->description,
			]);

         	$freelancer_profile_data = User::with('freelancer')->where('id',$user_id)->first();
         	$this->response->freelancer = new FreelancerResource($freelancer_profile_data);
         	return ResponseBuilder::successMessage("Update Successfully",$this->success);
        }
        	catch(\Exception $e){
         	return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
      	}
	}

	public function edit_skills_info(Request $request)
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
         		'skill_id'  => 	'required',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

         	$parameters = $request->all();
         	extract($parameters);

         	if(!empty($request->skill_id)){
		    $skills  = explode(',', $request->skill_id);

		    $projectSkill = ProjectSkill::all();

		    foreach($skills as $val){
		        $freelanceSkill[] = $val;
		    }
		    
		        foreach($projectSkill as $val){

		            if(in_array($val->id, $freelanceSkill)){
		                $save_d[] = FreelancerSkill::updateOrCreate([
		                    'user_id'   => $user_id,
		                    'skill_id'   => $val->id,
		                ],
		                [
		                    'skill_id'   => $val->id,
		                    'skill_name'   => $val->name,
		                ]);
		            }
		            else{
		                $skilll_id = FreelancerSkill::where('user_id',$user_id)->where('skill_id',$val->id)->first();
		                if($skilll_id)
		                $skilll_id->delete();
		            }
		        }
		        return ResponseBuilder::successMessage("Update Successfully", $this->success);
		    }
		}
		catch(\Exception $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}
	}

	public function edit_portfolio_info(Request $request)
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
         		'title'  => 'required',
         		'image'	=>'required',
         		'image'=>'image',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

         	$parameters = $request->all();
         	extract($parameters);
         	$portfolioData = FreelancerPortfolio::updateOrCreate([
         		'id'		=>	$request->id,
         		'user_id'	=>	$user_id,
         	],[
         		'title'		=>	$request->title,
         		'description'=>	$request->description,
         		'image'		=>	isset($request->image) ? $this->freelancerPortfolioImage($request->image) : '',
         	]);
         	if(!empty($portfolioData)){
         		if(!empty($request->id)){
         			return ResponseBuilder::successMessage("Update Successfully", $this->success);
         		}else{
         			return ResponseBuilder::successMessage("Add Successfully", $this->success);
         		}
         	}
		}
		catch(\Exception $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}
	}

	public function edit_testimonial_info(Request $request)
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
         		'id'		=> 'nullable|exists:freelancer_testimonial,id',
         		'first_name'  => 'required',
         		'last_name'  => 'required',
         		'email'  => 'required|email',
         		'linkdin_url'  => 'nullable|url',
         		'type'	=>'required',
         		'description'	=>'required',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

         	$parameters = $request->all();
         	extract($parameters);
         	$testimonialData = FreelancerTestimonial::updateOrCreate([
         		'id'		=>	$request->id,
         		'user_id'	=>	$user_id,
         	],[
         		'first_name'	=>	$request->first_name,
         		'last_name'		=>	$request->last_name,
         		'email'			=>	$request->email,
         		'linkdin_url'	=>	$request->linkdin_url,
         		'title'			=>	$request->title,
         		'type'			=>	$request->type,
         		'description'	=>	$request->description,
         	]);
         	if(!empty($testimonialData)){
         		if(!empty($request->id)){
         			return ResponseBuilder::successMessage("Update Successfully", $this->success);
         		}else{
         			return ResponseBuilder::successMessage("Add Successfully", $this->success);
         		}
         	}
		}
		catch(\Exception $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}
	}

	public function edit_certificate_info(Request $request)
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
         		'id'				=>'exists:freelancer_certificates,id',
         		'name'  			=>'required',
         		'description'		=>'required',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

         	$parameters = $request->all();
         	extract($parameters);
         	$certificateData = FreelancerCertificate::updateOrCreate([
         		'id'				=>	$request->id,
         		'user_id'			=>	$user_id,
         	],[
         		'name'				=>	$request->name,
         		'description'		=>	$request->description,
         	]);
         	if(!empty($certificateData)){
         		if(!empty($request->id)){
         			return ResponseBuilder::successMessage("Update Successfully", $this->success);
         		}else{
         			return ResponseBuilder::successMessage("Add Successfully", $this->success);
         		}
         	}
		}
		catch(\Exception $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}
	}

	public function edit_employment_info(Request $request)
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
         		'id'				=>'exists:freelancer_experiences,id',
         		'company'			=>'required',
         		'city'				=>'required',
         		'country'			=>'required|exists:country_list,name',
         		'start_date'		=>'required|date',
         		'end_date'			=>'nullable|date|required_if:currently_working,=,0',
         		'currently_working' =>'required_if:end_date,=,null|in:0,1',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

         	$parameters = $request->all();
         	extract($parameters);

         	$experienceData = FreelancerExperience::updateOrCreate([
         		'id'				=>	$request->id,
         		'user_id'			=>	$user_id,
         	],[
         		'company'			=>	$request->company,
         		'city'				=>	$request->city,
         		'country'			=>	$request->country,
         		'start_date'		=>	$request->start_date,
         		'end_date'			=>	$request->end_date,
         		'currently_working' =>	isset($request->currently_working) ? $request->currently_working : '0',
         		'subject'			=>	$request->subject,
         		'description'		=>	$request->description,
         	]);
         	if(!empty($experienceData)){
         		if(!empty($request->id)){
         			return ResponseBuilder::successMessage("Update Successfully", $this->success);
         		}else{
         			return ResponseBuilder::successMessage("Add Successfully", $this->success);
         		}
         	}
		}
		catch(\Exception $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}
	}

	public function delete_portfolio_info(Request $request)
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
         		'id'	=>'required|exists:freelancer_portfolio,id',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

         	$parameters = $request->all();
         	extract($parameters);

         	$dltPortfolio = FreelancerPortfolio::where('user_id',$user_id)->where('id',$request->id)->first();
         	// dd($dltPortfolio);
         	if(!empty($dltPortfolio)){
         		$dltPortfolio->delete();
         		return ResponseBuilder::successMessage("Delete Successfully", $this->success);
         	}else{
         		return ResponseBuilder::error("No Data Available", $this->success);
         	}
		}catch(\Exception $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}
	}
	public function delete_testimonial_info(Request $request)
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
         		'id'	=>'required|exists:freelancer_testimonial,id',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

         	$parameters = $request->all();
         	extract($parameters);

         	$dltPortfolio = FreelancerTestimonial::where('user_id',$user_id)->where('id',$request->id)->first();
         	// dd($dltPortfolio);
         	if(!empty($dltPortfolio)){
         		$dltPortfolio->delete();
         		return ResponseBuilder::successMessage("Delete Successfully", $this->success);
         	}else{
         		return ResponseBuilder::error("No Data Available", $this->success);
         	}
		}catch(\Exception $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}
	}
	public function delete_certificate_info(Request $request)
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
         		'id'	=>'required|exists:freelancer_certificates,id',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

         	$parameters = $request->all();
         	extract($parameters);

         	$dltPortfolio = FreelancerCertificate::where('user_id',$user_id)->where('id',$request->id)->first();
         	// dd($dltPortfolio);
         	if(!empty($dltPortfolio)){
         		$dltPortfolio->delete();
         		return ResponseBuilder::successMessage("Delete Successfully", $this->success);
         	}else{
         		return ResponseBuilder::error("No Data Available", $this->success);
         	}
		}catch(\Exception $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}
	}
	public function delete_employment_info(Request $request)
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
         		'id'	=>'required|exists:freelancer_experiences,id',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

         	$parameters = $request->all();
         	extract($parameters);

         	$dltPortfolio = FreelancerExperience::where('user_id',$user_id)->where('id',$request->id)->first();
         	// dd($dltPortfolio);
         	if(!empty($dltPortfolio)){
         		$dltPortfolio->delete();
         		return ResponseBuilder::successMessage("Delete Successfully", $this->success);
         	}else{
         		return ResponseBuilder::error("No Data Available", $this->success);
         	}
		}catch(\Exception $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}
	}
	public function edit_video(Request $request)
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
         		'video'	=>'required|url',
         		'video_type'=>'required',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

         	$parameters = $request->all();
         	extract($parameters);
			
			$video_meta = [];
			$video_meta = [
				'freelancer_video'=>$request->video,
				'freelancer_video_type'=>$request->video_type,
			];
			if(!empty($video_meta)){
				$uploadvideo = $this->updateFreelancerAllMeta($user_id,$video_meta);
				return ResponseBuilder::successMessage("Update Successfully", $this->success);
			}else{
				return ResponseBuilder::error("No Data available", $this->badRequest);
			}
		}catch(\Exception $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}
	}
	public function edit_education_info(Request $request)
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
         		'school'  => 'required',
         		'start_year'  => 'required|digits:4|integer',
         		'end_year'  => 'required|digits:4|integer',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

         	$parameters = $request->all();
         	extract($parameters);
         	$educationData = FreelancerEducation::updateOrCreate([
         		'id'		=>	$request->id,
         		'user_id'	=>	$user_id,
         	],[
         		'school'	=>	$request->school,
         		'start_date'=>	$request->start_year,
         		'end_date'	=>	$request->end_year,
         		'level'		=>	$request->level,
         		'degree'	=>	$request->degree,
         		'area_study'=>	$request->area_study,
         		'description'=>	$request->description,
         	]);
         	if(!empty($educationData)){
         		if(!empty($request->id)){
         			return ResponseBuilder::successMessage("Update Successfully", $this->success);
         		}else{
         			return ResponseBuilder::successMessage("Add Successfully", $this->success);
         		}
         	}
		}
		catch(\Exception $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}
	}
	public function edit_language(Request $request)
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

         	$parameters = $request->all();
         	extract($parameters);
			
			if(!empty($request->languages)){
				$lang = 'language';
				$uploadvideo = $this->updateFreelancerMeta($user_id,$lang,json_encode($request->languages));
				return ResponseBuilder::successMessage("Update Successfully", $this->success);
			}else{
				return ResponseBuilder::error("No Data available", $this->badRequest);
			}
		}catch(\Exception $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}
	}

	public function set_visibility(Request $request)
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
         		'visibility'  => 'required|in:public,private,unify_users',
         		'project_preference'  => 'required|in:both,long_term,short_term',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

         	$parameters = $request->all();
         	extract($parameters);

         	$visibleData = Freelancer::where('user_id',$user_id)->first();
         	$visibleData->visibility = $request->visibility;
         	$visibleData->project_preference = $request->project_preference;
         	$visibleData->save();

         	return ResponseBuilder::successMessage("Update Successfully", $this->success);
        }
		catch(\Exception $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}
	}

	public function edit_experience_level(Request $request)
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
         		'experience_level'  => 'required|in:entry,intermediate,expert',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

         	$parameters = $request->all();
         	extract($parameters);

         	$expData = Freelancer::where('user_id',$user_id)->first();
         	$expData->experience_level = $request->experience_level;
         	$expData->save();

         	return ResponseBuilder::successMessage("Update Successfully", $this->success);
        }
		catch(\Exception $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}
	}
	
	public function edit_other_experience(Request $request)
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
         		'id'				=>'exists:freelancer_experiences,id',
         		'subject'			=>'required',
         		'description'		=>'required',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

         	$parameters = $request->all();
         	extract($parameters);

         	$expeData = FreelancerExperience::updateOrCreate([
         		'id'				=>	$request->id,
         		'user_id'			=>	$user_id,
         	],[
         		'subject'			=>	$request->subject,
         		'description'		=>	$request->description,
         	]);
         	if(!empty($expeData)){
         		if(!empty($request->id)){
         			return ResponseBuilder::successMessage("Update Successfully", $this->success);
         		}else{
         			return ResponseBuilder::successMessage("Add Successfully", $this->success);
         		}
         	}
		}
		catch(\Exception $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}
	}

	public function edit_location(Request $request)
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
         		'phone'  	=> 'nullable|digits_between:10,12',
            	'timezone'  => 'nullable|exists:timezone',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

         	$parameters = $request->all();
         	extract($parameters);

         	$user = User::where('id',$user_id)->select('phone','address','timezone')->first();

         	$locationData = User::updateOrCreate([
         		'id'			=>	$user_id,
         	],[
         		'phone'			=>	isset($request->phone) ? $request->phone : (isset($user->phone) ? $user->phone : null),
         		'timezone'		=>	isset($request->timezone) ? $request->timezone : (isset($user->timezone) ? $user->timezone : null),
         		'address'		=>	isset($request->address) ? $request->address : (isset($user->address) ? $user->address : null),
         	]);
         	if(!empty($locationData)){
         		return ResponseBuilder::successMessage("Update Successfully", $this->success);
         	}
		}
		catch(\Exception $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}
	}

	public function hours_per_week(Request $request)
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
            	'hours_id'  => 'required|exists:hours_per_week,id',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

         	$parameters = $request->all();
         	extract($parameters);
			
			if(!empty($request->hours_id)){
				$hours_title = HoursPerWeek::where('id',$request->hours_id)->select('title')->first();
				$hour = 'hours_per_week';
				$uploadvideo = $this->updateFreelancerMeta($user_id,$hour,$hours_title->title);
				return ResponseBuilder::successMessage("Update Successfully", $this->success);
			}else{
				return ResponseBuilder::error("No Data available", $this->badRequest);
			}
		}catch(\Exception $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}	
	}

	public function contactInfo(Request $request)
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
         		'email'		 =>'nullable|email|unique:users,email'
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

         	$parameters = $request->all();
         		extract($parameters);
			
			$user_name = User::where('id',$user_id)->first();
			if(!empty($request->first_name)){
				$user_name->first_name = $request->first_name;
			}
			if(!empty($request->last_name)){
         		$user_name->last_name = $request->last_name;
         	}if(!empty($request->email)){
         		$user_name->email = $request->email;
         	}
         	$user_name->save();

         	return ResponseBuilder::successMessage("Update Successfully", $this->success);
		}catch(\Exception $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}
	}

}