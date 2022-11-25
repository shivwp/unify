<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Storage;
use App\Helper\ResponseBuilder;
use App\Http\Resources\Admin\FreelancerResource;
use App\Http\Resources\Admin\FreelancerCollection;
use App\Http\Resources\Admin\FreelancerPortfolioResource;
use App\Http\Resources\Admin\FreelancerEducationCollection;
use App\Http\Resources\Admin\FreelancerTestimonialResource;
use App\Http\Resources\Admin\FreelancerCertificateResource;
use App\Http\Resources\Admin\FreelancerExperienceResource;
use App\Http\Resources\Admin\FreelancerSkillResource;
use App\Models\FreelancerCertificate;
use App\Models\FreelancerTestimonial;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\FreelancerExperience;
use App\Models\FreelancerEducation;
use App\Models\FreelancerPortfolio;
use App\Models\FreelancerSkill;
use App\Models\UserSpecialize;
use Illuminate\Http\Request;
use App\Models\ProjectSkill;
use App\Models\HoursPerWeek;
use App\Models\Freelancer;
use App\Models\User;
use App\Models\Agency;
use App\Models\Client;
use App\Models\Role;
use App\Models\Mails;
use Carbon\Carbon;
use Validator;
use App\Mail\SendMail;
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
         	$checkclient = Client::where('user_id',$user_id)->first();
         	$checkagency = Agency::where('user_id',$user_id)->first();
         	$freelancer_profile_data = $this->getFreelancerInfo($user_id);
         	$this->response->basic_info = new FreelancerResource($freelancer_profile_data);
         	if(!empty($checkclient)){
         		$client = true;
         	}else{
         		$client = false;
         	}
         	if(!empty($checkagency)){
         		$agency = true;
         	}else{
         		$agency = false;
         	}
         	$this->response->is_client = $client;
         	$this->response->is_agency = $agency;
         	$this->response->skills = new FreelancerSkillResource($freelancer_profile_data->freelancer->freelancer_skills);
         	$this->response->portfolio = new FreelancerPortfolioResource($freelancer_profile_data->freelancer->freelancer_portfolio);
         	$this->response->testimonial = new FreelancerTestimonialResource($freelancer_profile_data->freelancer->freelancer_testimonial);
         	$this->response->certificates = new FreelancerCertificateResource($freelancer_profile_data->freelancer->freelancer_certificates);
         	$this->response->employment = new FreelancerExperienceResource($freelancer_profile_data->freelancer->freelancer_experiences);
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
         		'image'=>'image',
         		// 'specialize_profile_id'	=>'required|exists:user_specialize,id',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

         	$parameters = $request->all();
         	extract($parameters);

         	$pImage = FreelancerPortfolio::where('id',$request->id)->first();
         	
         	$portfolioData = FreelancerPortfolio::updateOrCreate([
         		'id'		=>	$request->id,
         		'user_id'	=>	$user_id,
         		// 'specialize_profile_id'	=>	$request->specialize_profile_id,
         	],[
         		// 'specialize_profile_id'	=>	$request->specialize_profile_id,
         		'title'		=>	$request->title,
         		'description'=>	$request->description,
         		'image'		=>	isset($request->image) ? $this->freelancerPortfolioImage($request->image) : (isset($pImage->image) ? $pImage->image : ''),
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
         		'first_name'  => 'required',
         		'last_name'  => 'required',
         		'email'  => 'required|email',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

         	$parameters = $request->all();
         	extract($parameters);
         	$checkemail = FreelancerTestimonial::where('user_id',$user_id)->where('email',$request->email)->first();
         	if(!empty($checkemail)){
         		return ResponseBuilder::error("It looks like you've already sent this person a request", $this->badRequest );
         	}
         	$testimonialData = FreelancerTestimonial::updateOrCreate([
         		'user_id'		=>	$user_id,
         		'email'			=>	$request->email,
         	],[
         		'email'			=>	$request->email,
         		'first_name'	=>	$request->first_name,
         		'last_name'		=>	$request->last_name,
         		'title'			=>	$request->title,
         		'type'			=>	$request->type,
         		'description_freelancer'	=>	$request->description,
         	]);
         	if(!empty($testimonialData)){
         		//mail to client
                   
                $mail_data = Mails::where('user_category', 'client')->where('mail_category', 'request_testimonial')->first();
                $basicinfo = [
                	'{id}'=> $testimonialData->id,
                    '{freelancer_name}'=>$singleuser->name,
                    '{client_name}'=> $request->first_name,
                ];

                $msg = $mail_data->message;
                foreach($basicinfo as $key=> $info){
                    $msg = str_replace($key,$info,$msg);
                }
                
                $config = 
                [	'from_email' => $mail_data->mail_from,
                    'reply_email' => $mail_data->reply_email,
                    'subject' => $mail_data->subject, 
                    'name' => $mail_data->name,
                    'message' => $msg,
                ];

                Mail::to($request->email)->send(new SendMail($config));
                $testimonial_data = [
                	'id'	=> $testimonialData->id,
                	'Request Sent'	=>  Carbon::now()->format('M d Y'),
                ];
         		return ResponseBuilder::success($testimonial_data, "Your testimonial request is awaiting ".$request->first_name." 's response");
         	}
		}
		catch(\Exception $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}
	}

	public function getTestimonial(Request $request)
	{
		try{
			$validator = Validator::make($request->all(), [
         		'id'		=> 'required|exists:freelancer_testimonial,id',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

         	$find_testimonial = FreelancerTestimonial::where('id',$request->id)->select('id','user_id','first_name','last_name','title','email')->first();
         	$freelancer_name = User::where('id',$find_testimonial->user_id)->select('first_name','profile_image')->first();
         	$freelacner_occcuption = Freelancer::where('user_id',$find_testimonial->user_id)->select('occcuption')->first();
         	$find_testimonial['freelancer_name'] = $freelancer_name->first_name;
         	$find_testimonial['freelancer_profile_image'] = isset($freelancer_name->profile_image) ? url('/images/profile-image',$freelancer_name->profile_image) : '';
         	$find_testimonial['occcuption'] = $freelacner_occcuption->occcuption;
         	if(!empty($find_testimonial)){
         		return ResponseBuilder::success($find_testimonial, "Testimonial Data");
         	}else{
         		return ResponseBuilder::successMessage(__("No Data found"), $this->success);
         	}
		}
		catch(\Exception $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}
	}

	public function clientTestimonial(Request $request)
	{
		try{
			$validator = Validator::make($request->all(), [
         		'id'		=> 'required|exists:freelancer_testimonial,id',
         		'description'		=> 'required',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }
	        $testimonialData = FreelancerTestimonial::where('id',$request->id)->first();
	        if(!empty($testimonialData)){
		        $testimonialData->description_client = $request->description;
		        $testimonialData->first_name = $request->first_name;
		        $testimonialData->last_name = $request->last_name;
		        $testimonialData->status = '1';
		        $testimonialData->save();
		        return ResponseBuilder::successMessage("Thank You for submitting testimonial ", $this->success);
	        }else{
	        	return ResponseBuilder::error("No data found", $this->serverError);
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
         		'country'		=>	isset($request->country) ? $request->country : (isset($user->country) ? $user->country : null),
         		'city'			=>	isset($request->city) ? $request->city : (isset($user->city) ? $user->city : null),
         		'zip_code'		=>	isset($request->zip_code) ? $request->zip_code : (isset($user->zip_code) ? $user->zip_code : null),
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
            	'hours_price'  => 'required',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

         	$parameters = $request->all();
         	extract($parameters);
			
			$addhr = Freelancer::where('user_id',$user_id)->first();
			if(!empty($addhr)){
				$addhr->amount = $request->hours_price;
				$addhr->save();
			}
			

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

	public function freelancerList()
	{
		try
      	{
	        $freelancer_data = Role::where('title', 'Freelancer')->first()->users()->where('users.status','approve')->with('freelancer')->get();
	        if(!empty($freelancer_data))
	        {
	            $this->response = new FreelancerCollection($freelancer_data);
	            return ResponseBuilder::success($this->response, "Freelancer Profile data");
	        }
	    }catch(\Exception $e)
	    {
	        return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
	    }
	}

	public function delete_education_info(Request $request)
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
         		'id'	=>'required|exists:freelancer_education,id',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

         	$parameters = $request->all();
         	extract($parameters);

         	$educationDelete = FreelancerEducation::where('user_id',$user_id)->where('id',$request->id)->first();
   
         	if(!empty($educationDelete)){
         		$educationDelete->delete();
         		return ResponseBuilder::successMessage("Delete Successfully", $this->success);
         	}else{
         		return ResponseBuilder::error("No Data Available", $this->success);
         	}
		}catch(\Exception $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}
	}

	public function freelanceSingleData(Request $request)
	{
		try
		{
			
         	$validator = Validator::make($request->all(), [
         		'user_id'	=>'required|exists:users,id',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }
	        
	        $parameters = $request->all();
         	extract($parameters);

         	$freelancer_profile_data = $this->getFreelancerInfo($user_id);
         	$this->response->basic_info = new FreelancerResource($freelancer_profile_data);
         	$this->response->skills = new FreelancerSkillResource($freelancer_profile_data->freelancer->freelancer_skills);
         	$this->response->portfolio = new FreelancerPortfolioResource($freelancer_profile_data->freelancer->freelancer_portfolio);
         	$this->response->testimonial = new FreelancerTestimonialResource($freelancer_profile_data->freelancer->freelancer_testimonial);
         	$this->response->certificates = new FreelancerCertificateResource($freelancer_profile_data->freelancer->freelancer_certificates);
         	$this->response->employment = new FreelancerExperienceResource($freelancer_profile_data->freelancer->freelancer_experiences);
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

	public function userSpecialize(Request $request)
	{
		try{
			if (Auth::guard('api')->check()) {
	            $singleuser = Auth::guard('api')->user();
	            $user_id = $singleuser->id;
	        } 
         	else{
             	return ResponseBuilder::error(__("User not found"), $this->unauthorized);
         	}
         	$validator = Validator::make($request->all(), [
         		'specialize_profile_id'	=>'required|exists:specialize_profile,id',
         	]);

	        if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }
	        $user_specialize = UserSpecialize::where('user_id',$user_id)->get();
	        if(count($user_specialize) == 2){
	        	return ResponseBuilder::error(__("Already 2 out of 2 Published"), $this->badRequest);
	        }
	        else{
	        	$newProfile = UserSpecialize::updateOrCreate([
	        		'specialize_profile_id'=>$request->specialize_profile_id,
	        		'user_id'=>$user_id,
	        	],[
	        		'title'=>$request->title,
	        		'description'=>$request->description,
	        		'status'=>$request->status,

	        	]);
	        	$newProfile->specialize_profile_id = $request->specialize_profile_id;
	        	$newProfile->user_id = $user_id;
	        	$newProfile->title = $request->title;
	        	$newProfile->description = $request->description;
	        	$newProfile->status = $request->status;
	        	$newProfile->save();

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
			                    'specialize_profile_id'   => $request->specialize_profile_id,
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
			    }
	        	return ResponseBuilder::successMessage(__("Created Profile successfully"), $this->success);
	        }

		}
		catch(\Exception $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}
	}
}
