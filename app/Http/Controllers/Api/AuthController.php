<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Admin\UserResource;
// use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Helper\ResponseBuilder;
use Illuminate\Http\Request;
use App\Models\UserReferal;
use App\Models\UserDocument;
use App\Models\Freelancer;
use App\Helper\Helper;
use App\Models\Client;
use App\Mail\SendMail;
use App\Models\SocialAccount;
use App\Models\Agency;
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
                'user_type' => 'in:freelancer,client',
                'country'   =>'required',
                'agree_terms'   =>'in:0,1',
                'send_email'   =>'in:0,1',
                    
            ]);
            if ($validator->fails()) {   
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);  
            }   
            $otpp =Helper::generateOtp();
            $user = User::where('email',$request->email)->first();

            if($user){
                if($user->deleted_at == null){
                    if($user->email_verified_at){
                        return ResponseBuilder::error(__("User Already Exist with this email"), $this->badRequest);
                    }else{
                        // generate OTP
                        $user->update([
                            'otp' => $otpp,
                            'otp_created_at' => now()->addMinutes(3),
                            'deleted_at' => null
                        ]);
                        $this->response->email = $user->email;
                        $this->response->otp = $user->otp;

                        return ResponseBuilder::success($this->response, 'Please verify your email');
                    }
                }else{
                    $user->name=$request->first_name.' '.$request->last_name;
                    $user->first_name=$request->first_name;
                    $user->last_name=$request->last_name;
                    $user->email=$request->email;
                    $user->password= Hash::make($request->password);
                    $user->country = $request->country;
                    $user->status ='approve';
                    $user->referal_code = $request->referal_code;
                    $user->agree_terms = $request->agree_terms;
                    $user->deleted_at = null;
                    $user->otp = $otpp;
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
                    'status' => 'approve',
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
                        '{otp}'=>$otpp,
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
                'otp' => $otpp,
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
                'email' => 'email|required|exists:users',
                'otp' => 'required|digits:4'
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

            $otpp = Helper::generateOtp();
            $user = User::where('email', $email)->first();

            if($user){
                $user->update([
                    'otp' => $otpp,
                    'otp_created_at' => now()->addMinutes(3),
                    'deleted_at' => null
                ]);
            }

            $this->response->email = $email;
            $this->response->otp = $user->otp;

            //mail to user
                   
            $mail_data = Mails::where('user_category', 'user')->where('mail_category', 'resendotp')->first();
            // dd($mail_data);
            $basicinfo = [
                '{otp}'=>$otpp,
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
                 'user_type' => 'in:freelancer,client',
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
                        return ResponseBuilder::successMessage( __("Your account is not approved"), $this->badRequest);
                    }
                }else{
                    return ResponseBuilder::successMessage( __("Please verify your email address"), $this->badRequest);
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
                    'otp' => $otp,
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
                'otp'   => 'required|digits:4'
            ]);

            if ($validator->fails()) {
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
            }

            $user = User::where('email', $request->get('email'))->first();

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

            $user = User::where('email', $request->get('email'))->first();
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

            $user = User::where('id', $user_id)->first();
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

    public function onlineStatus(Request $request)
    {
        try {
            if (Auth::guard('api')->check()) {
                $singleuser = Auth::guard('api')->user();
                $user_id = $singleuser->id;
            } 
            else{
                return ResponseBuilder::error(__("User not found"), $this->unauthorized);
            }
            $validator = Validator::make($request->all(), [
                'online_status'  =>  'required|in:online,invisible',
            ]);

            if ($validator->fails()) {
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
            }

            $user = User::where('id', $user_id)->first();
            $user->online_status = $request->online_status;
            $user->save();

            return ResponseBuilder::successMessage(__("Status Update Successfully"), $this->success);
                        
        } catch (\Exception $e) {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    }

    public function social(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'provider'  => 'required|in:google,apple',
                'token'     => 'required',
                'user_type' =>  'required|in:client,freelancer'
            ]);
            if ($validator->fails()) {
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
            }
            $social_user = Socialite::driver($request->provider)->stateless()->userFromToken($request->input('token'));

            if (!$social_user) {
                throw new Error(Str::replaceArray('?', [$request->provider], __('messages.invalid_social')));
            }

            $token = Str::random(80);
            $account = SocialAccount::where("provider_user_id", $social_user->getId())
                ->where("provider", $request->provider)
                ->with('user')->first();
            if ($account) {
                $user = User::where(["id" => $account->user->id])->first();
                $user->api_token = hash('sha256', $token);
                $user->device_id = $request->input('device_id') ? $request->input('device_id') : "";
                $user->device_token = $request->input('device_token') ? $request->input('device_token') : "";
                $user->save();
                $data = new \stdClass();
                $data->token = $user->createToken(env('APP_NAME'))->accessToken;
                $this->setAuthResponse($user);
                return ResponseBuilder::successWithToken($data->token, $this->response, 'Login Successfully');
                // return response()->json(['data' => $data, 'status' => true, 'message' => 'verify_success', 'details' => $user]);
            } else {
                $fname = $social_user->getName() ? $social_user->getName() : "";
                $lname = $social_user->getNickname() ? $social_user->getNickname() : "";
                $loginEmail = $social_user->getEmail() ? $social_user->getEmail() : $social_user->getId() . '@' . $request->provider . '.com';
                $loginName =  $fname . $lname;
                // create new user and social login if user with social id not found.
                $user = User::where("email", $loginEmail)->first();
                if (!$user) {
                    $user = new User;
                    $user->email = $loginEmail;
                    $user->first_name = $loginName;
                    $user->social_id = $social_user->getId();
                    $user->password = Hash::make('social');
                    $user->api_token = hash('sha256', $token);
                    $user->device_id = $request->input('device_id') ? $request->input('device_id') : "";
                    $user->device_token = $request->input('device_token') ? $request->input('device_token') : "";
                    $user->save();
                }

                
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

                $social_account = new SocialAccount;
                $social_account->provider = $request->provider;
                $social_account->provider_user_id = $social_user->getId();
                $social_account->user_id = $user->id;
                $social_account->save();
                $data = new \stdClass();
                $data->token = $user->createToken(env('APP_NAME'))->accessToken;
                $this->setAuthResponse($user);
                return ResponseBuilder::successWithToken($data->token, $this->response, 'Login Successfully');
                // return response()->json(['data' => $data, 'status' => true, 'message' => 'Your account logged in successfully', 'details' => $user]);
            }
        } 
        catch (\Exception $e) {
           return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    }

    public function additional_account(Request $request)
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
                'user_type'  =>  'required|in:client,agency',
            ]);

            if ($validator->fails()) {
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
            }
            if($request->user_type == "client"){
                $clientCheck = Client::where('user_id',$user_id)->first();
                if(!empty($clientCheck))
                {
                    return ResponseBuilder::successMessage(__("Already Registered as a Client"),$this->badRequest);
                }else{
                    $clientCreate = new Client;
                    $clientCreate->user_id = $user_id;
                    $clientCreate->save();
                    return ResponseBuilder::successMessage(__("Successfully Registered as a Client"),$this->success);
                }
            }
            if($request->user_type == "agency"){
                $agencyCheck = Agency::where('user_id',$user_id)->first();
                if(!empty($agencyCheck))
                {
                    return ResponseBuilder::successMessage(__("Already Registered as an Agency"),$this->success);
                }else{
                    $agencyCreate = new Agency;
                    $agencyCreate->user_id = $user_id;
                    $agencyCreate->save();
                    return ResponseBuilder::successMessage(__("Successfully Registered as a Agency"),$this->success);
                }
            }
        }
        catch(\Exception $e)
        {
            return ResponseBuilder::error($e->getMessage(),$this->serverError);
        }
    }

    public function userDocumentVerify(Request $request)
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
                'type'  =>  'required|in:passport,driving_license,other',
                'document_front'  =>  'required|image',
                'document_back'  =>  'required|image',
            ]);

            if ($validator->fails()) {
                return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
            }

            $user_document = UserDocument::updateOrCreate([
                'user_id'=> $user_id,
            ],
            [
                'type'=> $request->type,
                'document_front'=> $this->uploadUserDocument($request->document_front),
                'document_back'=> $this->uploadUserDocument($request->document_back),
            ]);

            return ResponseBuilder::successMessage("Upload Document Successfully", $this->success);

        }catch(\Exception $e)
        {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    }
}
