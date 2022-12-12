<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Admin\SubscriptionResource;
use App\Http\Resources\Admin\TimeZoneResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\AccountCloseReason;
use App\Models\ProjectCloseReason;
use App\Models\ProjectCategory;
use App\Models\SpecializeProfile;
use App\Models\FreelancerSkill;
use App\Models\HoursPerWeek;
use App\Helper\ResponseBuilder;
use Illuminate\Http\Request;
use App\Models\ProjectSkill;
use App\Models\DislikeReason;
use App\Models\TimeZone;
use App\Models\Plans;
use App\Models\Business_size;
use App\Models\Certificate;
use App\Models\DeclineReason;
use App\Models\Industries;
use App\Models\Project;
use App\Models\User;
use App\Models\Freelancer;
use App\Models\Page;
use Carbon\Carbon;
use Validator;
use Config;
use Str;
use DB;

class CommonController extends Controller
{
      public $success = 200;

      public function countrylist(){
         $countrylist =  DB::table('country_list')->select('id','name','country_code')->get();
         if(!empty($countrylist)){
            return response()->json(['countrylist'=>$countrylist,'status'=>true,'message'=>'country List'], $this->success); 
         }else{
            return response()->json(['countrylist'=>[],'status'=>false,'message'=>'No country List'], $this->success); 
         }
      }

      public function categorylist(){
         $categorylist =  ProjectCategory::where('parent_id','0')->get();
         if(!empty($categorylist)){
            return response()->json(['categorylist'=>$categorylist,'status'=>true,'message'=>'Category list'], $this->success); 
         }else{
            return response()->json(['categorylist'=>[],'status'=>false,'message'=>'No category found'], $this->success); 
         }
      }
      public function subcategorylist(Request $request){
         $validator = Validator::make($request->all(), [
            'category_id' => 'required',
         ]);

         if($validator->fails()) {   
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
         }

         $subcategorylist =  ProjectCategory::where('parent_id',$request->category_id)->get();
         if(!empty($subcategorylist)){
            return response()->json(['subcategorylist'=>$subcategorylist,'status'=>true,'message'=>'Sub category list'], $this->success); 
         }else{
            return response()->json(['subcategorylist'=>[],'status'=>false,'message'=>'No sub category found'], $this->success); 
         }
      }

      public function TimeZone()
      {
         try{
            $allTimezone = TimeZone::select('country_code','timezone')->get();
            return ResponseBuilder::success($allTimezone, "TimeZone List");

         }catch(\Exception $e){
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
         }
      }

      public function skillList(Request $request)
      {
         try{
            $skill = ProjectSkill::select('id','name');
            if(!empty($request->skill)){
               $skill->where('name', 'LIKE', "%$request->skill%");
            }
            if(!empty($skill)){
               $skills = $skill->get();
               return ResponseBuilder::success($skills, "Skills list");
            }else{
               return ResponseBuilder::successMessage(__("No data found"), $this->success);
            }
         }catch(\Exception $e){
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
         }
      }

      public function accountCloseReasonList()
      {
         try{
            $reason = AccountCloseReason::select('id','title')->get();
            if(!empty($reason)){
               return ResponseBuilder::success($reason, "Close account reasons list");
            }else{
               return ResponseBuilder::successMessage(__("No data found"), $this->success);
            }
         }catch(\Exception $e){
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
         }
      }

      public function hoursPerWeek()
      {
         try{
            $hours = HoursPerWeek::select('id','title')->get();
            if(!empty($hours)){
               return ResponseBuilder::success($hours, "Hours per week list");
            }else{
               return ResponseBuilder::successMessage(__("No data found"), $this->success);
            }
         }catch(\Exception $e){
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
         }
      }

      public function industriesList()
      {
         try{
            $industry = Industries::select('id','title')->get();
            if(!empty($industry)){
               return ResponseBuilder::success($industry, "Industries list");
            }else{
               return ResponseBuilder::successMessage(__("No data found"), $this->success);
            }
         }catch(\Exception $e){
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
         }
      }

      public function languageslist()
      {
         try{
            $language_list =  DB::table('languages')->select('id','name')->get();
            if(!empty($language_list)){
               return ResponseBuilder::success($language_list, "Languages list");
            }else{
               return ResponseBuilder::successMessage(__("No data found"), $this->success);
            }
         }catch(\Exception $e){
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
         }
      }

      public function degreelist()
      {
         try{
            $degree_list =  DB::table('degree_list')->select('id','title')->get();
            if(!empty($degree_list)){
               return ResponseBuilder::success($degree_list, "Degree's list");
            }else{
               return ResponseBuilder::successMessage(__("No data found"), $this->success);
            }
         }catch(\Exception $e){
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
         }
      }

      public function page($slug)
      {
         try
         {
            $degree_list =  DB::table('pages')->where('slug',$slug)->select('id','title','slug','content')->first();
            if(!empty($degree_list)){
               return ResponseBuilder::success($degree_list, "Degree's list");
            }else{
               return ResponseBuilder::successMessage(__("No data found"), $this->success);
            }
         }
         catch(\Exception $e)
         {
            return ResponseBuilder::error($e->getMessage(),$this->serverError);
         }
      }

      public function specializationlist(Request $request)
      {
         try{
            $specializ = SpecializeProfile::select('id','title');
            if(!empty($request->specialization)){
               $specializ->where('title', 'LIKE', "%$request->specialization%");
            }
            if(!empty($specializ)){
               $all_specialProfile = $specializ->get();
               return ResponseBuilder::success($all_specialProfile, "Specialize profile list");
            }else{
               return ResponseBuilder::successMessage(__("No data found"), $this->success);
            }
         }catch(\Exception $e){
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
         }
      }

      public function certificatelist(Request $request)
      {
         try
         {
            $certificate_list =  Certificate::select('id','name')->get();
            if(count($certificate_list) > 0){
               return ResponseBuilder::success($certificate_list, "Certificates list");
            }else{
               return ResponseBuilder::successMessage(__("No data found"), $this->success);
            }
         }
         catch(\Exception $e)
         {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
         }
      }

      public function dislike_reasons(Request $request)
      {
         try
         {
            $dislikeReaons_list =  DislikeReason::select('id','name')->get();
            if(count($dislikeReaons_list) > 0){
               return ResponseBuilder::success($dislikeReaons_list, "Dislike reasons list");
            }else{
               return ResponseBuilder::error(__("No data found"), $this->notFound);
            }
         }
         catch(\Exception $e)
         {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
         }
      }

      public function decline_reasons($type)
      {
         try
         {
            $decline_reasons =  DeclineReason::where('type',$type)->select('id','title')->get();
            if(count($decline_reasons) > 0){
               return ResponseBuilder::success($decline_reasons, "Decline seasons list");
            }else{
               return ResponseBuilder::error(__("No data found"), $this->notFound);
            }
         }
         catch(\Exception $e)
         {
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
         }
      }

      public function jobCloseReasonList()
      {
         try{
            $projectCloseReason = ProjectCloseReason::select('id','name')->get();
            if(count($projectCloseReason) > 0){
               return ResponseBuilder::success($projectCloseReason, "Job close reasons list");
            }else{
               return ResponseBuilder::error("No data found", $this->notFound);
            }
         }
         catch(\Exception $e)
         {
            return ResponseBuilder::error($e->getMessage(), $this->serverError);
         }
      }

      public function homeData()
      {
         try
         {
            $dummyicon = "1668774637-photo-editing.png";
            $home_data = Page::where('slug','home')->select('content')->first();
            $category = ProjectCategory::where('parent_id',0)->limit(9)->select('id','name','image')->get();
            foreach ($category as $value) {
               $skill_count = ProjectSkill::where('cate_id',$value->id)->count();
               $cat_data[] = [
                  'category_id'=> $value->id,
                  'category_name'=> $value->name,
                  'category_image'=> isset($value->image) ? url('images/category/'.$value->image) : url('images/category/'.$dummyicon),
                  'skills'=>$skill_count,
                  'rating'=>'4.8/5',
               ];
            }
            if(!empty($home_data)){
               $home_Alldata = json_decode($home_data->content,true);
               $home_Alldata['hero']['image'] = url('images/home/'.$home_Alldata['hero']['image']);
               
               foreach($home_Alldata['used_by']['used_by_section_image'] as $value)
               {
                  $usedImages[] = url('images/home/'.$value);
               }
               foreach($home_Alldata['trusted_brands'] as $valu)
               {
                  $trustedBrand[] = [
                     'brand_description'=>$valu['brand_description'],
                     'brand_name'=>$valu['brand_name'],
                     'designation'=>$valu['designation'],
                     'total_projects'=>$valu['total_projects'],
                     'launch_projects'=>$valu['launch_projects'],   
                     'logo'=>url('images/home/'.$valu['logo'])
                  ];
               }

               $home_Alldata['for_client']['client_banner'] = url('images/home/'.$home_Alldata['for_client']['client_banner']);

               $home_Alldata['used_by']['used_by_section_image'] = $usedImages;
               $home_Alldata['trusted_brands'] = $trustedBrand;
               $home_Alldata['category'] = $cat_data;
               $this->response = $home_Alldata;
            }
            return ResponseBuilder::success($this->response, "Home page data");
         }
         catch(\Exception $e)
         {
            return ResponseBuilder::error($e->getMessage(),$this->serverError);
         }
      }

      public function categorySkills(Request $request)
      {
         try
         {
            $validator = Validator::make($request->all(),[
               'category_id'  => 'required|exists:project_category,id'
            ]);
            if($validator->fails()){
               return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
            }
            $category = ProjectCategory::where('id',$request->category_id)->select('id','name','short_description','long_description','banner_image')->first();
            // dd($category->banner_image);
            $skils = ProjectSkill::where('cate_id',$category->id)->select('id','name','image')->get();
            if(count($skils) > 0)
            {  
               foreach($skils as $value)
               {
                  $cateSkills[] = [
                     'id' => $value->id,
                     'name' => $value->name,
                     'image' => isset($value->image) ? url('images/skills'.$value->image) : url('images/dummy.png'),
                     'rating' => '4.5/5',
                  ];
               }
               $category->banner_image = !empty($category->banner_image) ? url('images/category/'.$category->banner_image) : '';
               $this->response->category_data =  $category;
               $this->response->category_skills =  $cateSkills;
               return ResponseBuilder::success($this->response, "Skills list based on category");
            }else
            {
               return ResponseBuilder::error('No data found',$this->serverError);

            }
         }
         catch(\Exception $e)
         {
            return ResponseBuilder::error($e->getMessage(),$this->serverError);
         }
      }

      public function skillfreelaner(Request $request)
      {
         try
         {
            $validator = Validator::make($request->all(),[
               'skill_id'  => 'required|exists:project_skill,id'
            ]);
            $parameters = $request->all();
               extract($parameters);

            if($validator->fails()){
               return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
            }
            $singleskill = ProjectSkill::where('id',$request->skill_id)->select('id','name','image','cate_id','short_description','long_description','banner_image')->first();
            $freelanceSkill = FreelancerSkill::where('skill_id',$request->skill_id)->pluck('user_id')->toArray();
            // dd($freelanceSkill);
            $freelaceData = User::join('freelancer','users.id','freelancer.user_id')->whereIn('freelancer.user_id',$freelanceSkill)->select('users.id','users.profile_image','users.first_name','users.last_name','freelancer.occcuption','freelancer.amount','freelancer.rating')->get();

            if(count($freelaceData) > 0)
            {  foreach($freelaceData as $vlu)
               {  $skils = FreelancerSkill::where('user_id',$vlu->id)->select('skill_id','skill_name')->get();
                  $freelancData[] = [
                     'user_id' =>$vlu->id,
                     'profile_image' =>!empty($vlu->profile_image) ? url('images/profile-image/'.$vlu->profile_image) : '',
                     'first_name' => (string)$vlu->first_name,
                     'last_name' => (string)$vlu->last_name,
                     'occcuption' => (string)$vlu->freelancer->occcuption,
                     'rating' => (float)$vlu->freelancer->rating,
                     'amount' => (float)$vlu->freelancer->amount,
                     'skills' => $skils,
                  ];  
               }
               $singleskill->rating = "4.5/5";
               $singleskill->total_client = "50";
               $singleskill->image = !empty($singleskill->image) ? url('images/skills/'.$singleskill->image) : '';
               $singleskill->banner_image = !empty($singleskill->banner_image) ? url('images/skills/'.$singleskill->banner_image) : '';
               $this->response->skill_data =  $singleskill;
               $this->response->freelancer_data =  $freelancData;
               return ResponseBuilder::success($this->response, "freelancer list based on category");
            }else
            {
               return ResponseBuilder::error('No data found',$this->serverError);
            }
         }
         catch(\Exception $e)
         {
            return ResponseBuilder::error($e->getMessage(),$this->serverError);
         }
      }

      public function subscriptionList(Request $request)
      {
         try
         {
            $plan = Plans::with('services:service_name,description')->select('id','plans_title','validity','amount','description')->get();
            if(count($plan) > 0)
            {
               $this->response = new SubscriptionResource($plan);
               return ResponseBuilder::success($this->response, "Subscriptions plan list");
            }else{
               return ResponseBuilder::error('No data found',$this->badRequest);
            }
         }
         catch(\Exception $e)
         {
            return ResponseBuilder::error($e->getMessage(),$this->serverError);
         }
      }

}
