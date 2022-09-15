<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Validator;
use Str;
use Config;
use DB;
use Socialite;
use App\User;
use App\UserReferal;
class AuthController extends Controller
{

    public function signup(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
                
        ]);
       
        if ($validator->fails()) {   
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);   
        }   
        $token = random_int(100000, 999999);
        if($request->user_type == 'Freelancer'){
            $role = 2;
        }else{
            $role = 3;
        }
        
        $password = Hash::make($request->password);

        $user_data = new User;
        $user_data->name=$request->name;
        $user_data->last_name=$request->last_name;
        $user_data->email=$request->email;
        $user_data->password= $password; 
        $user_data->country = $request->country;
        $user_data->status ='decline';
        $user_data->referal_code = $token;
        //$user_data->social_login_id= $request->social_login_id;
        //$user_data->socail_login_type= $request->provider;
        $user_data->save();
        $user_data->roles()->sync($role);
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
           
            // $mail_data = Mails::where('user_type', 'user')->where('msg_category', 'otp_verify')->first();
            // $basicinfo = [
            //     '{otp}'=>$token,
            //     '{password}'=>$request->password,
            //     '{username}'=>$request->name,
            // ];

            // $msg = $mail_data->message;
            // foreach($basicinfo as $key=> $info){
            //     $msg = str_replace($key,$info,$msg);
            // }
            
            // $config = ['from_email' => $mail_data->mail_from,
            //     "reply_email" => $mail_data->reply_email,
            //     'subject' => $mail_data->subject, 
            //     'name' => $mail_data->name,
            //     'message' => $msg,
            // ];

            // Mail::to($request->email)->send(new SendMail($config));
                
            
                // $udata = Setting::first();
               
                // $adminmail = $udata->email;
               
                // //mail to admin
               
                // $mail_data = Mails::where('user_type', 'admin')->where('msg_category', 'signup')->first();
                // $basicinfo = [
                //     '{username}'=>$request->name,
                //     '{email}'=>$request->email,
                // ];

                // $msg = $mail_data->message;
                // foreach($basicinfo as $key=> $info){
                //     $msg = str_replace($key,$info,$msg);
                // }
                
                // $config = ['from_email' => $mail_data->mail_from,
                //     "reply_email" => $mail_data->reply_email,
                //     'subject' => $mail_data->subject, 
                //     'name' => $mail_data->name,
                //     'message' => $msg,
                // ];

                // Mail::to($adminmail)->send(new SendMail($config));
            

        return response()->json(['status' => true, 'otp' => $token, 'message' => "You Are registered succcessfully."]);
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
             //'fcm_token' => 'required'
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
                    return response()->json(['status' => false,'message' => '** Your Account not Approved', 'user' => Null,'token'=> ""], 200);
                }
            }else{
                return response()->json(['status' => false,'message' => '** Your Account not verified', 'user' => Null,'token'=> ""], 200);
            }
        }else{
               return response()->json(['status' => false,'message' => '** These credentials do not match our records.', 'user' => Null,'token'=> ""], 200);
        }

    }
    
  


    
    
    // public function forget_password_otp(Request $request){

    //      $validator = Validator::make($request->all(), [
    //         'email'  => 'required|email',
    //         ]);
    //     $token      = random_int(100000, 999999);
    //     if ($validator->fails()) {
    //          return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()), 'user' => Null], 200);
    //     }

    //     $user = User::where('email', '=', $request->email)->first();
    //     if ($user == '') {
    //         return response()->json(['status' => false, 'message' => 'This email is not registered with us , please recheck it.' , 'user' => Null], 200);
    //     }else{
    //         $mail_data = Mails::where('user_type', 'user')->where('msg_category', 'forgot password')->first();
    //         $basicinfo = [
    //              '{name}'=>$user->first_name,
    //             '{password}'=>$token,
    //             '{email}'=>$request->email,
    //         ];

    //         $msg = $mail_data->message;
    //         foreach($basicinfo as $key=> $info){
    //             $msg = str_replace($key,$info,$msg);
    //         }
            
    //         $config = ['from_email' => $mail_data->mail_from,
    //             "reply_email" => $mail_data->reply_email,
    //             'subject' => $mail_data->subject, 
    //             'name' => $mail_data->name,
    //             'message' => $msg,
    //         ];

    //         Mail::to($request->email)->send(new SendMail($config));
    //     }
        
    //    return response()->json(['status' => true, 'token' => $token, 'message' => "We have Send You An 6 Digit Password To Your Registred Mail Id."]);

    // }

    // public function reset_password(Request $request){

    //     $validator = Validator::make($request->all(), [
    //         'password' => 'required|min:8',
    //     ]);

    //     if($validator->fails()) {   
    //         return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    //     }

    //         $data3 = User::where('email',$request->email)->first();
    //         if(!empty($data3)){
    //             $password = Hash::make($request->password);
    //             $user_id = $data3->id;
    //             $user = User::findOrFail($user_id);           
    //             $user->password = $password;
    //             $user->save();
    //         }else{
    //             return response()->json(['status'=>false,'message'=>'invalid email'], 200);
    //         }
           
    //     return response()->json(['status' => true,'message' => "Your password has been changed successfully, Please do login"], 200);
           
    // }   
    
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

    // public function changePassword(Request $request){
    //     $user_id = Auth::user()->id;
    //     $validator = Validator::make($request->all(), [
    //         'current_password' => 'required',
    //         'new_password' => 'required', 
    //         'confirm_pass' => 'required|same:new_password',
    //     ]);
    //     if ($validator->fails()) {   
    //             return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    //         }  else{
    //         $user_id = Auth::user()->id;
    //         $data =  User::where('id',$user_id)->first();                                                  
    //         if(Hash::check($request->current_password,$data->password)){
    //             $data->password =  Hash::make($request->new_password);
    //             $data->save();
                
    //             return response()->json(['status'=>true,'message'=>'password change succesfully'], 200);
    //         }else{
    //             return response()->json([ 'status'=>false,'message'=>'current password is not match'], 200);
    //         }                                          
            
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
