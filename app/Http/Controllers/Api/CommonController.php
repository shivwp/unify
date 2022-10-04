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
use App\Models\ProjectCategory;
use App\Helper\ResponseBuilder;
use Illuminate\Http\Request;
use App\Models\ProjectSkill;
use App\Models\TimeZone;
use Carbon\Carbon;
use Validator;
use Config;
use Str;
use DB;

class CommonController extends Controller
{
      public $success = 200;

      public function countrylist(){
         $countrylist =  DB::table('country_list')->select('name')->get();
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

      public function skillList()
      {
         try{
            $skills = ProjectSkill::select('id','name')->get();
            if(!empty($skills)){
               return ResponseBuilder::success($skills, "Skills List");
            }else{
               return ResponseBuilder::success(__("No Data found"), $this->success);
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
               return ResponseBuilder::success($reason, "Skills List");
            }else{
               return ResponseBuilder::success(__("No Data found"), $this->success);
            }
         }catch(\Exception $e){
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
         }
      }
}
