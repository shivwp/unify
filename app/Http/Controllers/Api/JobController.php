<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Helper\ResponseBuilder;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;
use Validator;

class JobController extends Controller
{
   public function categoryList()
   {
      try{
         $category = ProjectCategory::where('parent_id','0')->select('id','name')->get();
         if(!empty($category) && count($category) > 0){
            return ResponseBuilder::success($category, "Category List");
         }else{
            return ResponseBuilder::error("No Data found", $this->serverError);
         }
      }catch(\Exception $e)
      {
         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
      }
   }

   public function subCategoryList(Request $request)
   {
      try{
         $validator = Validator::make($request->all(), [
            'category_id'  => 'required|exists:project_category,id',
         ]);

         if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
         }

         $subcategory = ProjectCategory::where('parent_id',$request->category_id)->select('id','name')->get();
         if(!empty($subcategory) && count($subcategory) > 0){
            return ResponseBuilder::success($subcategory, "Category List");
         }else{
            return ResponseBuilder::error("No Data found", $this->badRequest);
         }
      }catch(\Exception $e)
      {
         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
      }
   }
   
}
