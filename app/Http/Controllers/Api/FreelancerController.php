<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Storage;
use App\Helper\ResponseBuilder;
use App\Http\Resources\Admin\FreelancerResource;
use App\Http\Resources\Admin\FreelancerPortfolioResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\FreelancerPortfolio;
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

         	$this->response->freelancer = new FreelancerResource($freelancer_profile_data);
         	$this->response->freelancer_portfolio = new FreelancerPortfolioResource($freelancer_profile_data->freelancer->freelancer_portfolio);
         	
         	return ResponseBuilder::success($this->response, "Freelancer All Profile Data");
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
         		'title'  => '',
         		'image'	=>'',
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

		}
		catch(\Exception $e)
		{
			return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
		}
	}

}