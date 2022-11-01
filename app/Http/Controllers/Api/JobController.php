<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Admin\ProjectResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Helper\ResponseBuilder;
use App\Models\ProjectProjectSkill;
use App\Models\ProjectCategory;
use App\Models\FreelancerSkill;
use App\Models\SendProposal;
use App\Models\SavedProject;
use App\Models\Project;
use App\Models\IncomeSource;
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
            'job_type'     => 'nullable|in:short_term,long_term',
            'job_category' => 'nullable|exists:project_category,id',
            'project_duration'=> 'nullable|in:1,3,6',
            'budget_type'  => 'nullable|in:hourly,fixed',
            'min_price'    => 'nullable',
            'status'       => 'nullable|in:publish,draft',
         ]);
         if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
         }

         $project = new Project;
         $project->client_id  = $user_id;
         $project->name  = $request->job_title;
         $project->description  = $request->description;
         $project->project_category  = $request->job_category;
         $project->project_duration  = $request->project_duration;
         $project->experience_level  = $request->experience_level;
         $project->budget_type  = $request->budget_type;
         $project->min_price  = $request->min_price;
         $project->price  = $request->price;
         $project->status  = $request->status;
         $project->save();

         if(!empty($request->skills)){   
            $project_skills= explode(',', $request->skills);
            foreach($project_skills as $skl){
               $skil = ProjectProjectSkill::create([
                  'project_id'=>$project->id,
                  'project_skill_id'=>$skl,
               ]);
               $skil->save();
            }
         }
         if ($request->hasfile('image')) {
            $file = $request->image;
            $name =$file->getClientOriginalName();
            $destinationPath = 'images/jobs';
            $file->move($destinationPath, $name);
            Project::where('id',$project->id)->update([
               'project_images' => $name
            ]);
         }
         return ResponseBuilder::successMessage("Post Job Sucessfully", $this->success);

      }catch(\Exception $e){
         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
      }
   }

   public function jobsList()
   {
      try
      {
         $job_list = Project::where('status','publish')->with('skills','categories')->get();
         // dd($job_list);
         $this->response = new ProjectResource($job_list);
         return ResponseBuilder::success($this->response, "Jobs List Data");
      }
      catch(\Exception $e){
         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
      }
   }

   public function recentJobsList()
   {
      try
      {
         $job_list = Project::where('status','publish')->orderBy('created_at','DESC')->with('skills','categories')->get();
         // dd($job_list);
         $this->response = new ProjectResource($job_list);
         return ResponseBuilder::success($this->response, "Recent Jobs List");
      }
      catch(\Exception $e){
         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
      }
   }
   public function bestMatchJobsList()
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
         $user_skills = FreelancerSkill::where('user_id',$user_id)->pluck('skill_id')->toArray();
         
         $project_skills = ProjectProjectSkill::whereIn('project_skill_id',$user_skills)->pluck('project_id')->toArray();
         
         $job_list = Project::whereIn('id',$project_skills)->where('status','publish')->orderBy('created_at','DESC')->with('skills','categories')->get();
         
         $this->response = new ProjectResource($job_list);
         return ResponseBuilder::success($this->response, "Best Match Jobs List");
      }
      catch(\Exception $e){
         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
      }
   }

   public function savedJobs(Request $request)
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
            'job_id'       => 'required|exists:projects,id',
         ]);
         if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
         }
         $CheckJob = SavedProject::where('project_id', $request->job_id)->where('user_id', $user_id)->first();
         if(!empty($CheckJob))
         {
            return ResponseBuilder::error(__("Already Saved Job"), $this->badRequest);
         }
         else{
            $savejob = SavedProject::updateOrCreate([
               'id' => $request->id
            ], [
               'user_id' => $user_id,
               'project_id' => $request->job_id
            ]);
            return ResponseBuilder::successMessage("Save Job Sucessfully",$this->success);
         }
      }
      catch(\Exception $e)
      {
         return ResponseBuilder::error($e->getMessage(), $this->serverError);
      }
   }
   public function removeSavedJobs(Request $request)
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
            'job_id'       => 'required|exists:projects,id',
         ]);
         if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
         }
         $CheckJob = SavedProject::where('project_id', $request->job_id)->where('user_id', $user_id)->first();
         if(!empty($CheckJob))
         {
            $CheckJob->delete();
            return ResponseBuilder::error(__("Removed Sucessfully"), $this->success);
         }
         else{
            return ResponseBuilder::error(__("No Data found"), $this->badRequest);
         }
      }
      catch(\Exception $e)
      {
         return ResponseBuilder::error($e->getMessage(), $this->serverError);
      }
   }

   public function sendProposal(Request $request)
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
            'job_id'       => 'required|exists:projects,id',
            'bid_amount'   => 'required',
            'cover_letter' => 'required',
            'project_duration'=>'required',
            'image'        => 'nullable|mimes:png,jpeg,gif,svg,jpg',
         ]);
         if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
         }
         $send_proposal = SendProposal::where('job_id',$request->job_id)->where('user_id',$user_id)->first();
         if(!empty($send_proposal))
         {
            return ResponseBuilder::error(__("Already Send Proposal"), $this->badRequest);
         }
         else{
            $get_fee = IncomeSource::where('name','Unify')->first();
            $platform_fee = $get_fee->fee_percent;

            $receive_amount = ($request->bid_amount - $platform_fee);
            
            $savejob = SendProposal::updateOrCreate([
               'id' => $request->id
            ], [
               'user_id' => $user_id,
               'job_id' => $request->job_id,
               'status' => 'pending',
               'bid_amount' => $request->bid_amount,
               'platform_fee' => $platform_fee,
               'receive_amount' => $receive_amount,
               'project_duration' => $request->project_duration,
               'cover_letter' => $request->cover_letter,
               'image' => !empty($request->file('image')) ? $this->proposalImage($request->file('image')  ) : '',
            ]);
            return ResponseBuilder::successMessage("Sent Proposal Sucessfully",$this->success);
         }
      }catch(\Exception $e)
      {
         return ResponseBuilder::error($e->getMessage(),$this->serverError);
      }
   }
}

