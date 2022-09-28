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
use App\Helper\Helper;
use App\Mail\SendMail;
use App\Models\Mails;
use App\Models\User;
use App\Models\Client;
use App\Models\Freelancer;
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

            return ResponseBuilder::success($this->response);
            
        }
        catch (exception $e) {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    }

    public function verifysignup(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);
       
        if ($validator->fails()) {   
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }   

        $userdata = User::where('email',$request->email)->first();
        if($userdata){
            $userdata->email_verified_at = Carbon::now()->toDateTimeString();
            $userdata->save();
            return response()->json(['status' => true,  'message' => "Your Account succcessfully Verified."]);
        }else{
            return response()->json(['status' => false,  'message' => "Email Incorrect"]);
        }

    }

    public function login(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
             'email' => 'required|email',
             'password' => 'required',
        ]);

        if ($validator->fails()) {
             return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }
        $user = User::where('email', $request->email)->first();
        if($user){
             if($user->email_verified_at){
                if($user->status == 'accept'){
                    if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){

                        $user = Auth::user();

                        // $dtoken = DeviceToken::where('user_id',$user->id)->first();
                        // if($dtoken){
                        //      $dtoken->device_token = $request->fcm_token;
                        //      $dtoken->save();
                        // }else{
                        //     $devicetoken = new DeviceToken();
                        //     $devicetoken->user_id = Auth::user()->id;
                        //     $devicetoken->device_token = $request->fcm_token;
                        //     $devicetoken->save();
                        // }

                        $token = auth()->user()->createToken('API Token')->accessToken;
                        return response()->json(['status' => true,'message' => "Your account logged in successfully",'token'=>$token, 'user' => $user], 200);
                    }
                    else{
                       return response()->json(['status' => false,'message' => '** These credentials do not match our records.', 'user' => Null,'token'=> ""], 200);
                    }
                }else{
                    return response()->json(['status' => false,'message' => 'Your Account not Approved', 'user' => Null,'token'=> ""], 200);
                }
            }else{
                return response()->json(['status' => false,'message' => 'Your Account not verified', 'user' => Null,'token'=> ""], 200);
            }
        }else{
               return response()->json(['status' => false,'message' => 'These credentials do not match our records.', 'user' => Null,'token'=> ""], 200);
        }

    }
    
    
    public function forget_password_otp(Request $request){

        $validator = Validator::make($request->all(), [
            'email'  => 'required|email',
        ]);

        $token = random_int(100000, 999999);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()), 'user' => Null], 200);
        }

        $user = User::where('email', '=', $request->email)->first();
        if(empty($user)){
            return response()->json(['status' => false, 'message' => 'This email is not registered with us , please recheck it.' , 'user' => Null], 200);
        }
        else{

            $mail_data = Mails::where('user_category', 'user')->where('mail_category', 'forgetpassword')->first();
            $basicinfo = [
                '{otp}'=>$token,
                
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
            return response()->json(['status' => true, 'otp' => $token, 'message' => "We have Send You An 6 Digit Password To Your Registred Mail Id."]);
        }
        
     

    }

    public function reset_password(Request $request){
        
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8',
        ]);

        if($validator->fails()) {   
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

            $data = User::where('email',$request->email)->first();

            if(!empty($data)){
                $password = Hash::make($request->password);
                $user_id = $data->id;
                $user = User::findOrFail($user_id);           
                $user->password = $password;
                $user->save();
            }else{
                return response()->json(['status'=>false,'message'=>'invalid email'], 200);
            }
           
        return response()->json(['status' => true,'message' => "Your password has been changed successfully, Please login"], 200);
           
    }   

    public function changePassword(Request $request){
        if(Auth::guard('api')->id()){
            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'new_password' => 'required', 
                'confirm_pass' => 'required|same:new_password',
            ]);
            if ($validator->fails()) {   
                    return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
                }else{
                $user_id = Auth::guard('api')->user()->id;
                $data =  User::where('id',$user_id)->first();                                                  
                if(Hash::check($request->current_password,$data->password)){
                    $data->password = Hash::make($request->new_password);
                    $data->save();
                    
                    return response()->json(['status'=>true,'message'=>'password change succesfully'], 200);
                }else{
                    return response()->json([ 'status'=>false,'message'=>'current password is not match'], 200);
                }                                          
                
            }
        }else{
            return response()->json([ 'status'=>false,'message'=>'Please login'], 200);
        }

    }
    
    // public function userProfile(Request $request){
    //     $user_id = Auth::user()->id;
    //     $data = User::where('id',$user_id)->first();

    //     $detail = [];
          
    //         $detail['first_name'] = $data->first_name??'';
    //         $detail['email']  = $data->email??'';
    //         $detail['mobile'] = $data->mobile??'';
    //         $detail['address'] = $data->address??'';
    //         $detail['user_profile'] = $data->user_profile??'';
    //         $detail['dob'] = $data->dob??'';
            
    //     return response()->json(['data'=>$detail,'status'=>true,'message'=>'user details'], 200);
    // }

    // public function updateUserProfile(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required',
    //         'mobile' => 'required',
    //         //'dob' => 'required',
    //         'address' => 'required',
    //     ]);
    //     if ($validator->fails()) { 
    //         return response()->json(['data'=>'','status'=>false, 'message'=>implode("", $validator->errors()->all())], 200);            
    //     }else{
    //         $user_id = Auth::user()->id;
    //         $data = User::where('id',$user_id)->first();

    //         // if($request->hasfile('image')){

    //         //     $file = $request->file('image');
    //         //     $extention = $file->getClientOriginalExtension();
    //         //     $filename = time().'.'.$extention;
    //         //     $file->move('user_images', $filename);
    //         //     $data->user_profile = $filename;
    //         // }

    //         if($request->image){

    //             //$folderPath = "user_images/";
    //             // $image_parts = explode(";base64,", $img);
    //             // $image_type_aux = explode("image/", $image_parts[0]);
    //             //  $image_type = $image_type_aux[1];
    //             $image_base64 = base64_decode($request->image);
    //             $image_type = '.jpg';
    //             $file = uniqid()."_".$user_id. '_user' . $image_type;
    //             file_put_contents("user_images/".$file, $image_base64);
    //             $data->user_profile = $file;
    //         }
            
    //         $data->first_name = $request->name;
    //         $data->mobile = $request->mobile;
    //         $data->dob = $request->dob;
    //         $data->address = $request->address;

    //         $data->dob = $request->dob;
    //         $data->gender = $request->gender;
    //         $data->country = $request->country;
    //         $data->city = $request->city;

    //         $data->save();
    //         return response()->json(['data'=>$data,'status'=>true,'message'=>'Profile updated successfully'], 200);
    //     }
    // }

   

    // // public function social(Request $request) {
    // //     try{

    // //         $validator = Validator::make($request->all(), [  
    // //             'provider' => 'required',          
                
    // //         ]);
    // //         if ($validator->fails())
                
    // //             return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);

    // //         if($request->provider == 'manual'){
    // //             $validator = Validator::make($request->all(), [
    // //                 'name' => 'required',
    // //                 'mobile' => 'required',
    // //                 'email' => 'required|email|unique:users',
    // //                 'password' => 'required|min:8',
                    
    // //             ]);
    // //         $token = Str::random(6);
    // //         if ($validator->fails()) {   
    // //             return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    // //         }   

    // //         $role = 5;
    // //         $password = Hash::make($request->password);

    // //         $user_data = new User;
    // //         $user_data->first_name=$request->name;
    // //         $user_data->email=$request->email;
    // //         $user_data->mobile=$request->mobile;
    // //         $user_data->password= $password; 
    // //         $user_data->status ='Active';
    // //         $user_data->save();
    // //         $user_data->roles()->sync($role);

    // //         $mail_data = Mails::where('user_type', 'user')->where('msg_category', 'signup')->first();
    // //         $basicinfo = [
    // //             '{verify_link}'=>$token,
    // //         ];

    // //         $msg = $mail_data->message;
    // //         foreach($basicinfo as $key=> $info){
    // //             $msg = str_replace($key,$info,$msg);
    // //         }
            
    // //         $config = ['from_email' => $mail_data->mail_from,
    // //             "reply_email" => $mail_data->reply_email,
    // //             'subject' => $mail_data->subject, 
    // //             'name' => $mail_data->name,
    // //             'message' => $msg,
    // //         ];

    // //         Mail::to($request->email)->send(new SendMail($config));
    
    // //         return response()->json(['status' => true, 'user_data' => $user_data, 'message' => "You Are registered succcessfully."]);
    // //     }else{

    // //         $validator = Validator::make($request->all(), [  
    // //             'provider' => 'in:google,facebook',          
    // //             'access_token' => 'required',
    // //         ]);
    // //         if ($validator->fails())
                
    // //             return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);

    // //         $social_user = Socialite::driver($request->provider)->stateless()->userFromToken($request->input('access_token'));
            
    // //         if(!$social_user){
    // //             throw new Error( Str::replaceArray('?', [$request->provider], __('messages.invalid_social')) );
    // //         }
    // //         $token = Str::random(80);

    // //         $account = SocialAccount::where("provider_user_id",$social_user->getId())
    // //                 ->where("provider",$request->provider)
    // //                 ->with('user')->first();

    // //         if($account){
    // //             if($account->user->status == 0){
    // //                 throw new Error('Your account is deactivated.');
    // //             }

    // //             $user = User::where(["id"=>$account->user->id])->first();
    // //             $user->api_token = hash('sha256', $token);
    // //             $user->device_id = $request->input('device_id') ? $request->input('device_id') : "";
    // //             $user->device_token = $request->input('device_token') ? $request->input('device_token') : "";
    // //             $user->save();

    // //             $data = new \stdClass();
    // //             $data->token = $user->createToken(env('APP_NAME'))->accessToken;
    // //             return response()->json(['data'=>$data,'status'=>true,'message'=>'verify_success'], $this->success);
    // //         } else { 
    // //             $fname = $social_user->getName() ? $social_user->getName(): "";
    // //             $lname = $social_user->getNickname() ? $social_user->getNickname(): "";

    // //             $loginEmail = $social_user->getEmail() ? $social_user->getEmail() : $social_user->getId().'@'.$request->provider.'.com';
                
    // //             $loginName =  $fname. $lname;

    // //             // create new user and social login if user with social id not found.
    // //             $user = User::where("email", $loginEmail)->first();
    // //             $role = 5;
    // //             if(!$user){  
    // //                 $user = new User;
    // //                 $user->role = 2;
    // //                 $user->status ='Active';
    // //                 $user->email = $loginEmail;
    // //                 $user->first_name = $loginName;
    // //                 $user->social_id = $social_user->getId();
    // //                 $user->password = Hash::make('social');
    // //                 $user->api_token = hash('sha256', $token);
    // //                 $user->device_id = $request->input('device_id') ? $request->input('device_id') : "";
    // //                 $user->device_token = $request->input('device_token') ? $request->input('device_token') : "";
    // //                 $user->save();
    // //                 $user->roles()->sync($role);
    // //             }

    // //             $social_account = new SocialAccount;
    // //             $social_account->provider = $request->provider;
    // //             $social_account->provider_user_id = $social_user->getId();
    // //             $social_account->user_id = $user->id;
    // //             $social_account->save();

    // //             $data = new \stdClass();

    // //             $data->token = $user->createToken(env('APP_NAME'))->accessToken;
    // //             return response()->json(['data'=>$data,'status'=>true,'message'=>'verify_success'], $this->success);
                
    // //         }
    // //     }


    // //     } catch(\Thuserowable $th){
    // //         return response()->json([
    // //             "message" => $th->getMessage(),
    // //         ], 400);
    // //     }

    // // } 

    // public function social(Request $request){
    //     $validator = Validator::make($request->all(), [  
    //         'provider' => 'required',
    //         'social_login_id' => 'required',  
    //         //'fcm_token' =>    'required',         
    //     ]);

    //     if ($validator->fails()){
    //         return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    //     }else{

    //         $user = User::where('social_login_id', '=', $request->social_login_id)->first();
    //         if(!empty($user)){
    //             $dtoken = DeviceToken::where('user_id',$user->id)->first();
    //             if($dtoken){
    //                 $dtoken->device_token = $request->fcm_token;
    //                 $dtoken->save();
    //             }else{
    //                 $devicetoken = new DeviceToken();
    //                 $devicetoken->user_id = Auth::user()->id;
    //                 $devicetoken->device_token = $request->fcm_token;
    //                 $devicetoken->save();
    //             }

    //             $token = $user->createToken('API Token')->accessToken;
    //             return response()->json(['status' => true,'message' => "Your account logged in successfully",'token'=>$token, 'user' => $user], 200);
    //         }
    //         else{
    //             return response()->json(['status' => false,'message' => '** These credentials do not match our records.', 'user' => Null,'token'=> ""], 200);
    //         }
    //     }
    // }
    

}
