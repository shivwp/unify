<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Storage;
use App\Helper\ResponseBuilder;
use App\Http\Resources\Admin\FreelancerResource;
use App\Http\Resources\Admin\FreelancerPortfolioResource;
use App\Http\Resources\Admin\FreelancerTestimonialResource;
use App\Http\Resources\Admin\FreelancerCertificateResource;
use App\Http\Resources\Admin\FreelancerExperienceResource;
use App\Http\Resources\Admin\FreelancerSkillResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\FreelancerCertificate;
use App\Models\FreelancerTestimonial;
use App\Models\FreelancerExperience;
use App\Models\FreelancerPortfolio;
use App\Models\FreelancerSkill;
use App\Models\Freelancer;
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
	            $user_id = $singleuser->id;
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
         		'id ' 	 	=> 	'exists:freelancer_skills,id',
         		'skill_id'  => 	'required|exists:project_skill,id',
         		'skill_name'=>	'required|exists:project_skill,name',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

         	$parameters = $request->all();
         	extract($parameters);

         	$skillData = FreelancerSkill::updateOrCreate([
         		'id'		=>	$request->id,
         		'user_id'	=>	$user_id,
         	],[
         		'skill_id'	=>	$request->skill_id,
         		'skill_name'=>	$request->skill_name,
         	]);
         	if(!empty($skillData)){
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
         		return ResponseBuilder::successMessage("Update Successfully", $this->success);
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
         		'title'  => 'required',
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
         		'title'		=>	$request->title,
         		'type'		=>	$request->type,
         		'description'=>	$request->description,
         	]);
         	if(!empty($testimonialData)){
         		return ResponseBuilder::successMessage("Update Successfully", $this->success);
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
         		'issue_date'		=>'required|date',
         		'expiry_date'		=>'after:issue_date|date',
         		'certificate_id'	=>'required'
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
         		'issue_date'		=>	$request->issue_date,
         		'expiry_date'	=>	$request->expiry_date,
         		'certificate_id'	=>	$request->certificate_id,
         	]);
         	if(!empty($certificateData)){
         		return ResponseBuilder::successMessage("Update Successfully", $this->success);
         	}
		}
		catch(\Exception $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}
	}

	public function edit_experience_info(Request $request)
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
         		'subject'  			=>'required',
         		'description'		=>'required',
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
         		'subject'			=>	$request->subject,
         		'description'		=>	$request->description,
         	]);
         	if(!empty($experienceData)){
         		return ResponseBuilder::successMessage("Update Successfully", $this->success);
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
	public function delete_experience_info(Request $request)
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

}