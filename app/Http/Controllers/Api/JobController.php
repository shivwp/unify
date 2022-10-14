<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Helper\ResponseBuilder;
use App\Models\ProjectCategory;
use App\Models\Project;
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
   
   public function post_job(Request $request)
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
            'job_type'     => 'required|in:short_term,long_term',
            'job_title'    => 'required',
            'job_category' => 'required|exists:project_category,id',
            'skills'       => 'required',
            'scop'         => 'required',
            'project_duration'=> 'required',
            'experience_level'   => 'required',
            'budget_type'  => 'required|in:hourly,fixed',
            'price'        => 'required',
            'description'  => 'required',
            'status'  => 'required',
         ]);
         if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
         }

         $project = new Project;
         $project->client_id  = $user_id;
         $project->name  = $request->job_title;
         $project->description  = $request->description;
         $project->project_category  = $request->job_category;
         $project->project_duration  = $request->job_project_duration;
         $project->experience_level  = $request->experience_level;
         $project->budget_type  = $request->budget_type;
         $project->price  = $request->price;
         $project->status  = $request->status;
         $project->save();

         if(!empty($request->skills)){   
            $project_skills = $request->skills;
            foreach($project_skills as $skl){
               $skil = ProjectProjectSkill::create([
                  'project_id'=>$project->id,
                  'project_skill_id'=>$skl,
               ]);
               $skil->save();
            }
         }
         return ResponseBuilder::successMessage("Pst Job Sucessfulyy", $this->success);

      }catch(\Exception $e){
         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
      }
   }
}
