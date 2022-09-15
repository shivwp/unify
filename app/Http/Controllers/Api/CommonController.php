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

     
}
