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
use App\Models\ProjectCategory;
use Str;
use Config;
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


     
}
