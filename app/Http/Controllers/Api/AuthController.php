<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Admin\UserResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Helper\ResponseBuilder;
use Illuminate\Http\Request;
use App\Models\UserReferal;
use App\Models\Freelancer;
use App\Helper\Helper;
use App\Models\Client;
use App\Mail\SendMail;
use App\Models\Mails;
use App\Models\User;
use Carbon\Carbon;
use Socialite;
use Exception;
use Validator;
use Config;
use Str;
use DB;
class AuthController extends Controller
{

    public function signup(Request $request): Response
    {
       try{
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name'  => 'required',
                'email'     => 'required|email',
                'password' => 'required|min:8',
                'country'   =>'required',
                    
            ]);
            if ($validator->fails()) {   
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);  
            }   

            $user = User::where('email',$request->email)->withTrashed()->first();

            if($user){
                if($user->deleted_at == null){
                    if($user->email_verified_at){
                        return ResponseBuilder::error(__("User Already Exist with this email"), $this->badRequest);
                    }else{
                        return ResponseBuilder::success($this->response, __("Please verify your email"));
                    }
                }else{
                    $user->name=$request->first_name.' '.$request->last_name;
                    $user->first_name=$request->first_name;
                    $user->last_name=$request->last_name;
                    $user->email=$request->email;
                    $user->password= Hash::make($request->password);
                    $user->country = $request->country;
                    $user->status ='pending';
                    $user->referal_code = $request->referal_code;
                    $user->agree_terms = $request->agree_terms;
                    $user->deleted_at = null;
                    $user->otp = Helper::generateOtp();
                    $user->otp_created_at = now()->addMinutes(3);
                    $user->email_verified_at = null;
                    $user->update();

                    $token = $user->createToken('Token')->accessToken;
                }
            }
            else{
                $user = User::create([
                    'name' => $request->first_name.' '.$request->last_name,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'country' => $request->country,
                    'status' => 'pending',
                    'referal_code' => $request->referal_code,
                    'agree_terms' => $request->agree_terms,
                    'send_email' => $request->send_email,
                ]);
                
                $token = $user->createToken('Token')->accessToken;


            }
            //create client or freelancer
            if(!empty($user)){
                if($request->user_type == 'freelancer'){
                    $role = 2;
                    $freelancer = new Freelancer;
                    $freelancer->user_id = $user->id;
                    $freelancer->save();
                }
                if($request->user_type == 'client'){
                    $role = 3;
                    $client = new Client;
                    $client->user_id = $user->id;
                    $client->save();
                }
                $user->roles()->sync($role);
                if($request->referal_code){
                    $referalrecord = User::where('referal_code',$request->referal_code)->first();
                    if($referalrecord){
                        $referaldata = new UserReferal;
                        $referaldata->refer_coder_id = $referalrecord->id;
                        $referaldata->user_id  = $user_data->id;
                        $referaldata->save();
                    }
                }
                //mail to user
                   
                    $mail_data = Mails::where('user_category', 'user')->where('mail_category', 'signupverification')->first();
                    $basicinfo = [
                        '{otp}'=>$token,
                        '{password}'=>$request->password,
                        '{username}'=>$request->first_name.' '.$request->last_name,
                    ];

                    $msg = $mail_data->message;
                    foreach($basicinfo as $key=> $info){
                        $msg = str_replace($key,$info,$msg);
                    }
                    
                    $config = ['from_email' => $mail_data->mail_from,
                        "reply_email" => $mail_data->reply_email,
                        'subject' => $mail_data->subject, 
                        'name' => $mail_data->name,
                        'message' => $msg,
                    ];

                    Mail::to($request->email)->send(new SendMail($config));
            }
            // generate OTP
            $user->update([
                'otp' => Helper::generateOtp(),
                'otp_created_at' => now()->addMinutes(3),
                'deleted_at' => null
            ]);
            $this->response->email = $user->email;
            $this->response->otp = $user->otp;

            return ResponseBuilder::success($this->response, 'Registered Successfully');
            
        }
        catch (exception $e) {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    }

    public function verifysignup(Request $request)
    {   
        try{
            $validator = Validator::make($request->all(), [
                'email' => 'required|exists:users',
                'otp' => 'required|digits:6'
            ]);
           
            if ($validator->fails()) {   
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
            }   

            $user = User::where('email',$request->email)->first();

            if ($user->otp != $request->otp) {
                return ResponseBuilder::error(__('Invalid OTP'), $this->badRequest);
            }
            if(strtotime($user->otp_created_at) < strtotime(now())) 
            {
                return ResponseBuilder::error(__('Your OTP is Expired , Please Resend OTP'), $this->badRequest);    
            }
            if($user->otp == $request->otp){
                $user->otp_created_at = $user->otp = null;
                $user->email_verified_at = now();
                $user->save();

                // login user
                $token = $user->createToken('Token')->accessToken;
                $this->setAuthResponse($user);

                return ResponseBuilder::successWithToken($token, $this->response, "Your Account Succcessfully Verified");
               
            }
        }catch (\Exception $e) {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }

    }

    public function ResendOtp(Request $request): Response
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users',
            ]);

            if ($validator->fails()) {
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
            }
            $parameters = $request->all();
            extract($parameters);

            $user = User::where('email', $email)->withTrashed()->first();

            if($user){
                $user->update([
                    'otp' => Helper::generateOtp(),
                    'otp_created_at' => now()->addMinutes(3),
                    'deleted_at' => null
                ]);
            }

            $this->response->email = $email;
            $this->response->otp = $user->otp;

            return ResponseBuilder::success($this->response,'Resend OTP Successfully');
        } catch (exception $e) {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    }

    public function login(Request $request)
    {
        try
        {

            $validator = Validator::make($request->all(), [
                 'email' => 'required|email|exists:users',
                 'password' => 'required',
                 'user_type' => 'required',
            ]);

            if ($validator->fails()) {
                 return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
            }
            $user = User::where('email', $request->email)->first();
            if($user){
                if($user->email_verified_at){
                    if($user->status == 'approve'){
                        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
                            $user = Auth::user();
                            $userRole = strtolower($user->roles()->first()->title);

                            if($userRole == $request->user_type){
                                $token = auth()->user()->createToken('API Token')->accessToken;
                                $this->setAuthResponse($user);
                                return ResponseBuilder::successWithToken($token, $this->response, 'Login Successfully');
                            }else{
                                return ResponseBuilder::error( __("These credentials do not match our records"), $this->badRequest);
                            }
                            
                        }
                        else{
                           return ResponseBuilder::error( __("These credentials do not match our records"), $this->badRequest);
                        }
                    }else{
                        return ResponseBuilder::success( __("Your account is not approved"), $this->badRequest);
                    }
                }else{
                    return ResponseBuilder::success( __("Please verify your email address"), $this->badRequest);
                }
            }else{
                return ResponseBuilder::error( __("User Not Registered"), $this->badRequest);
            }
        } catch (\Exception $e) {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }

    }
    
    
    public function forget_password_otp(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'email'  => 'required|email|exists:users',
            ]);

            if ($validator->fails()) {
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
            }

            $otp = Helper::generateOtp();
            $user = User::where('email', '=', $request->email)->first();
            if(empty($user)){
                return ResponseBuilder::error(__("User Not Registered"), $this->badRequest);
            }
            else
            {
                $user->update([
                    'otp' => Helper::generateOtp(),
                    'otp_created_at' => now()->addMinutes(3),
                    'deleted_at' => null
                ]);
                    
                $mail_data = Mails::where('user_category', 'user')->where('mail_category', 'forgetpassword')->first();
                $basicinfo = [
                    '{otp}'=>$otp,
                    
                ];

                $msg = $mail_data->message;
                foreach($basicinfo as $key=> $info){
                    $msg = str_replace($key,$info,$msg);
                }
                
                $config = ['from_email' => $mail_data->mail_from,
                    "reply_email" => $mail_data->reply_email,
                    'subject' => $mail_data->subject, 
                    'name' => $mail_data->name,
                    'message' => $msg,
                ];
             
                Mail::to($request->email)->send(new SendMail($config));
                $this->response->otp = $user->otp;
                return ResponseBuilder::success($this->response,"OTP Sent Successfully on you Email address");
            }
        } catch (\Exception $e) {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    }

     public function verifyForgotPasswordOtp(Request $request): Response
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users',
                'otp'   => 'required|digits:6'
            ]);

            if ($validator->fails()) {
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
            }

            $user = User::where('email', $request->get('email'))->withTrashed()->first();

            if ($user->otp != $request->otp) {
                return ResponseBuilder::error(__("Your OTP is Invalid"), $this->badRequest);
            }

            // check otp is expired or not
            if(strtotime($user->otp_created_at) < strtotime(now())) 
            {
                return ResponseBuilder::error(__("Your OTP is Expired "), $this->badRequest);
            }
            
            if (!$user->email_verified_at) {
                $user->email_verified_at = now();
            }

            $user->otp_created_at = $user->otp = null;
            $user->save();
            
            $this->response->email  = $user->email;
            
            return ResponseBuilder::success($this->response, "OTP Verify Successfully ");
        } catch (\Exception $e) {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }

    }

    public function reset_password(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email'             => 'required|email|exists:users',
                'password'          => 'required|min:8',
                'confirm_password'  => 'required|same:password'
            ]);

            if ($validator->fails()) {
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
            }

            $user = User::where('email', $request->get('email'))->withTrashed()->first();
            if ($user) {
                $user = User::where('email', $request->get('email'))->first();
                $user->password = Hash::make($request->get('password'));
                $user->otp_created_at = $user->otp = null;
                $user->save();
                $token = $user->createToken('Token')->accessToken;
                $this->setAuthResponse($user);
                return ResponseBuilder::successWithToken($token, $this->response,"Password Reset Successfully");
            } else {
                return ResponseBuilder::error(__("User Not Registered"), $this->badRequest);
            }
            
        } catch (\Exception $e) {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    }   

    public function changePassword(Request $request){
        try {
            if (Auth::guard('api')->check()) {
                $singleuser = Auth::guard('api')->user();
                $user_id = $singleuser->id;
            } 
            else{
                return ResponseBuilder::error(__("User not found"), $this->unauthorized);
            }
            $validator = Validator::make($request->all(), [
                'old_password'  =>  'required|min:6',
                'new_password'   => 'required|min:6',
                'confirm_password'  => 'required|min:6|same:new_password'
            ]);

            if ($validator->fails()) {
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
            }

            $user = User::where('id', $user_id)->withTrashed()->first();
            if ($user) {
                if(Hash::check($request->old_password, $user->password)){
                    $user->password = Hash::make($request->new_password);
                    $user->save();
                    return ResponseBuilder::successMessage(__("Password changed Succcessfully"),$this->success);  
                }
                else{
                    return ResponseBuilder::error(__("Old Password did not match"), $this->badRequest);
                }
            } else {
                return ResponseBuilder::error(__("User Not Registered"), $this->badRequest);
            }
            
        } catch (\Exception $e) {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }

    }
    
    public function setAuthResponse($user)
    {
        $this->response->user = new UserResource($user);
    }

}
