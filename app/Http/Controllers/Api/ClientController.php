<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Storage;
use App\Helper\ResponseBuilder;
use App\Http\Resources\Admin\ClientResource;
use App\Models\AccountCloseReason;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\FreelancerRating;
use App\Models\Client;
use App\Models\User;
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
            'profile_image'  => 'required|image',
            'company_email'  => 'email|required',
            'company_phone'  => 'digits_between:10,12',
            'timezone'       =>  'exists:timezone',
            'website'       =>  'url'
            
         ]);

         if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
         }

         $parameters = $request->all();
         extract($parameters);

         $client = Client::updateOrCreate([
            'user_id' =>$user_id
         ],[
            'company_name' => $company_name,
            'website' => $website,
            'description' => $description,
            'company_email' => $company_email,
            'company_phone' => $company_phone,
            'vat_id' => $vat_id,
            'company_address' => $company_address,
         ]);
         $user_name = User::where('id',$client->user_id)->first();
         $user_name->first_name = $first_name;
         $user_name->last_name = $last_name;
         $user_name->timezone = $timezone;
         $user_name->profile_image = $this->uploadProfile_image($profile_image);
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
   
   public function close_account(Request $request){

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
         $userAccountStatus->deleted_at = now();
         $userAccountStatus->save();

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
}
