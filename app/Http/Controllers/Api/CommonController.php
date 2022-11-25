<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;
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
use App\Models\HoursPerWeek;
use App\Helper\ResponseBuilder;
use Illuminate\Http\Request;
use App\Models\ProjectSkill;
use App\Models\DislikeReason;
use App\Models\TimeZone;
use App\Models\Business_size;
use App\Models\Certificate;
use App\Models\Industries;
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
            return response()->json(['categorylist'=>$categorylist,'status'=>true,'message'=>'Category List'], $this->success); 
         }else{
            return response()->json(['categorylist'=>[],'status'=>false,'message'=>'No Category Found'], $this->success); 
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
            return response()->json(['subcategorylist'=>$subcategorylist,'status'=>true,'message'=>'Sub Category List'], $this->success); 
         }else{
            return response()->json(['subcategorylist'=>[],'status'=>false,'message'=>'No Sub Category Found'], $this->success); 
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
               return ResponseBuilder::success($skills, "Skills List");
            }else{
               return ResponseBuilder::successMessage(__("No Data found"), $this->success);
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
               return ResponseBuilder::success($reason, "Close Account Reasons List");
            }else{
               return ResponseBuilder::successMessage(__("No Data found"), $this->success);
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
               return ResponseBuilder::success($hours, "Hours Per Week List");
            }else{
               return ResponseBuilder::successMessage(__("No Data found"), $this->success);
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
               return ResponseBuilder::success($industry, "Industries List");
            }else{
               return ResponseBuilder::successMessage(__("No Data found"), $this->success);
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
               return ResponseBuilder::success($language_list, "Languages List");
            }else{
               return ResponseBuilder::successMessage(__("No Data found"), $this->success);
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
               return ResponseBuilder::success($degree_list, "Degree's List");
            }else{
               return ResponseBuilder::successMessage(__("No Data found"), $this->success);
            }
         }catch(\Exception $e){
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
         }
      }

      public function page(Request $request)
      {
         try
         {
            $validator = Validator::make($request->all(), [
               'slug'  =>'required|exists:pages,slug',
            ]);

           if ($validator->fails()) {
               return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
           }
            $degree_list =  DB::table('pages')->where('slug',$request->slug)->select('id','title','slug','content')->get();
            if(!empty($degree_list)){
               return ResponseBuilder::success($degree_list, "Degree's List");
            }else{
               return ResponseBuilder::successMessage(__("No Data found"), $this->success);
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
               return ResponseBuilder::success($all_specialProfile, "Specialize Profile List");
            }else{
               return ResponseBuilder::successMessage(__("No Data found"), $this->success);
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
               return ResponseBuilder::success($certificate_list, "Certificates List");
            }else{
               return ResponseBuilder::successMessage(__("No Data found"), $this->success);
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
               return ResponseBuilder::success($dislikeReaons_list, "Dislike Reasons List");
            }else{
               return ResponseBuilder::error(__("No Data found"), $this->notFound);
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
               return ResponseBuilder::success($projectCloseReason, "Job Close Reasons List");
            }else{
               return ResponseBuilder::error("No data found", $this->notFound);
            }
         }
         catch(\Exception $e)
         {
            return ResponseBuilder::error($e->getMessage(), $this->serverError);
         }
      }

}
