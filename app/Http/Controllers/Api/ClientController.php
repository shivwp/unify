<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Storage;
use App\Helper\ResponseBuilder;
use App\Http\Resources\Admin\ClientResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
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
            'profile_image'  => 'required',
            'company_email'  => 'email|required',
            'timezone'       =>  'exists:timezone'
            
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
            'timezone' => $timezone,
         ]);
         $user_name = User::where('id',$client->user_id)->first();
         $user_name->first_name = $first_name;
         $user_name->last_name = $last_name;
         $user_name->profile_image = $this->uploadProfile_image($profile_image);
         $user_name->save();

         $client_profile_data = User::with('client')->where('id',$user_id)->first();

         $this->response->client = new ClientResource($client_profile_data);

         return ResponseBuilder::success($this->response, "Update Successfully");

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

         $client_profile_data = User::with('client')->where('id',$user_id)->first();

         $this->response->client = new ClientResource($client_profile_data);

         return ResponseBuilder::success($this->response, "Client Profile data");

      }catch(\Exception $e)
      {
         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
      }
   } 
     
}
