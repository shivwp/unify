<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Storage;
use App\Helper\ResponseBuilder;
use App\Http\Resources\Admin\ClientResource;
use App\Http\Resources\Admin\ClientCollection;
use App\Models\AccountCloseReason;
use App\Http\Resources\Admin\FreelancerCollection;
use App\Models\ProjectProjectSkill;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\FreelancerRating;
use App\Models\FreelancerSkill;
use App\Models\Industries;
use App\Models\Client;
use App\Models\SocialAccount;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;
use Validator;
use Config;
use Str;
use DB;

class ClientController extends Controller
{
   Public function edit_info(Request $request)
   {
      try{
         if (Auth::guard('api')->check()) {
            $singleuser = Auth::guard('api')->user();
            // dd($singleuser->roles()->first()->title);
            if($singleuser->roles()->first()->title == "Client"){
               $user_id = $singleuser->id;
            }else{
               return ResponseBuilder::error(__("Please Login with Valid Credentials"), $this->badRequest);
            }
         } 
         else{
             return ResponseBuilder::error(__("User not found"), $this->unauthorized);
         }
         $validator = Validator::make($request->all(), [
            'profile_image'   =>  'image|nullable',
            'company_email'   => 'email|nullable|unique:users,email',
            'company_phone'   => 'nullable|digits_between:10,12',
            'timezone'        =>  'exists:timezone',
            'website'         =>  'nullable|url',
            'industry'        => 'required|exists:industries,id',
         ]);

         if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
         }

         $parameters = $request->all();
         extract($parameters);
         if(!empty($request->industry)){
            $industry = Industries::where('id',$request->industry)->select('title')->first();
         }
         $client = Client::updateOrCreate([
            'user_id' =>$user_id
         ],[
            'company_name' => $request->company_name,
            'website' => $request->website,
            'tagline' => $request->tagline,
            'industry' => $industry->title,
            'employee_no' => $request->employee_no,
            'description' => $request->description,
            'company_phone' => $request->company_phone,
            'vat_id' => $request->vat_id,
            'company_address' => $request->company_address,
         ]);
         $user_name = User::where('id',$client->user_id)->first();
         $user_name->first_name = $first_name;
         $user_name->last_name = $last_name;
         $user_name->timezone = $timezone;
         if(!empty($request->email)){

            $user_name->email = $request->email;
         }
         if(!empty($request->profile_image)){

            $user_name->profile_image = $this->uploadProfile_image($profile_image);
         }
         $user_name->save();

         return ResponseBuilder::successMessage("Update Successfully",$this->success);

      }catch(\Exception $e){
         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
      }
   } 

   Public function get_info()
   {
      try{
         if (Auth::guard('api')->check()) {
            $singleuser = Auth::guard('api')->user();
            // dd($singleuser->roles()->first()->title);
            if($singleuser->roles()->first()->title == "Client"){
               $user_id = $singleuser->id;
            }else{
               return ResponseBuilder::error(__("Please Login with Valid Credentials"), $this->badRequest);
            }
         } 
         else{
            return ResponseBuilder::error(__("User not found"), $this->unauthorized);
         }
         
         $client_profile_data = $this->getClientInfo($user_id);

         $this->response->client = new ClientResource($client_profile_data);

         return ResponseBuilder::success($this->response, "Client Profile data");

      }catch(\Exception $e)
      {
         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
      }
   } 
   
   public function close_account(Request $request)
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
            'reason_id'  => 'required|exists:account_close_reasons,id',
         ]);

         if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
         }

         $parameters = $request->all();
         extract($parameters);

         $reasonTitle = AccountCloseReason::where('id',$reason_id)->select('title')->first();
         $userAccountStatus = User::where('id',$user_id)->first();
         $userAccountStatus->close_status = $reasonTitle->title;
         $userAccountStatus->delete();

         $socialUser = SocialAccount::where('user_id',$user_id)->first();
         if(!empty($socialUser)){
            $socialUser->delete();
         }
         return ResponseBuilder::successMessage("Close Account successfulyy", $this->success);

      }catch(\Exception $e){
         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
      }
   }

   public function freelancer_rating(Request $request)
   {
      try{
         if (Auth::guard('api')->check()) {
            $singleuser = Auth::guard('api')->user();
            // dd($singleuser->roles()->first()->title);
            if($singleuser->roles()->first()->title == "Client"){
               $user_id = $singleuser->id;
            }else{
               return ResponseBuilder::error(__("Please Login with Valid Credentials"), $this->badRequest);
            }
         } 
         else{
             return ResponseBuilder::error(__("User not found"), $this->unauthorized);
         }
         $validator = Validator::make($request->all(), [
            'rating'  => 'required|between:0,5|integer',
            'description'  => 'required',
            'freelancer_id'  => 'required|exists:freelancer,user_id',
         ]);

         if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
         }

         $parameters = $request->all();
         extract($parameters);

         $client = new FreelancerRating;
         $client->freelancer_id = $request->freelancer_id;
         $client->client_id = $user_id;
         $client->rating = $request->rating;
         $client->description = $request->description;
         $client->save();

         return ResponseBuilder::successMessage("Add Rating Successfully",$this->success);

      }catch(\Exception $e){
         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
      }
   }

   public function clientList()
   {
      try
      {
         $client_data = Role::where('title', 'Client')->first()->users()->where('users.status','approve')->with('client')->get();
         // dd($client_data);
         if(!empty($client_data))
         {
            $this->response = new ClientCollection($client_data);
            return ResponseBuilder::success($this->response, "Client Profile data");
         }
      }catch(\Exception $e)
      {
         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
      }
   }

   public function skillsFreelanceList(Request $request)
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
               'project_id'  =>'required|exists:projects,id',
         ]);

         if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
         }

         $project_skills = ProjectProjectSkill::where('project_id',$request->project_id)->pluck('project_skill_id')->toArray();

         $user_skills = FreelancerSkill::whereIn('skill_id',$project_skills)->pluck('user_id')->toArray();
         
         $users_list = User::whereIn('id',$user_skills)->where('status','approve')->with('freelancer')->get();
         if(!empty($users_list))
         {
            $this->response = new FreelancerCollection($users_list);
            return ResponseBuilder::success($this->response, "Freelancer list according to skills");
         }
         else{
            return ResponseBuilder::error('No freelancer found', $this->serverError);
         }
      }
      catch(\Exception $e){
         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
      }
   }

}
