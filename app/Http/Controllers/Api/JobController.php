<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Admin\ProjectResource;
use App\Http\Resources\Admin\ClientResource;
use App\Http\Resources\Admin\ProjectSingleResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Helper\ResponseBuilder;
use App\Models\ProjectProjectSkill;
use App\Models\ProjectCategory;
use App\Models\FreelancerSkill;
use App\Models\SendProposal;
use App\Models\DislikeJob;
use App\Models\SavedProject;
use App\Models\Project;
use App\Models\User;
use App\Models\IncomeSource;
use Illuminate\Http\Request;
use App\Models\ProjectMilestone;
use Validator;
use DB;

class JobController extends Controller
{
   public function categoryList()
   {
      try{
         $category = ProjectCategory::where('parent_id','0')->select('id','name')->get();
         if(!empty($category) && count($category) > 0){
            return ResponseBuilder::success($category, "Category List");
         }else{
            return ResponseBuilder::error("No Data found", $this->badRequest);
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
            'project_duration'=> 'nullable',
            'budget_type'  => 'nullable|in:hourly,fixed',
            'min_price'    => 'nullable',
            'english_level'=> 'nullable|in:fluent,conversational,native',
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
         $project->english_level  = $request->english_level;
         $project->scop  = $request->scop;
         $project->type  = $request->job_type;
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

   public function UpdateJob(Request $request)
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
            'project_id'   => 'required|exists:projects,id',
            'job_type'     => 'nullable|in:short_term,long_term',
            'job_category' => 'nullable|exists:project_category,id',
            'budget_type'  => 'nullable|in:hourly,fixed',
            'min_price'    => 'nullable',
            'status'       => 'nullable|in:publish,draft',
         ]);

         if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
         }

         $project = Project::where('id',$request->project_id)->first();
         $project->client_id  = $user_id;
         $project->name  = isset($request->job_title) ? $request->job_title : $project->name;
         $project->description  = isset($request->description) ? $request->description : $project->description;
         $project->project_category  = isset($request->job_category) ? $request->job_category : $project->project_category;
         $project->project_duration  = isset($request->project_duration) ? $request->project_duration : $project->project_duration;
         $project->experience_level  = isset($request->experience_level) ? $request->experience_level : $project->experience_level;
         $project->budget_type  = isset($request->budget_type) ? $request->budget_type : $project->budget_type;
         $project->min_price  = isset($request->min_price) ? $request->min_price : $project->min_price;
         $project->price  = isset($request->price) ? $request->price : $project->price;
         $project->status  = isset($request->status) ? $request->status : $project->status;
         $project->scop  = isset($request->scop) ? $request->scop : $project->scop;
         $project->type  = isset($request->job_type) ? $request->job_type : $project->type;
         $project->save();

         if(!empty($request->skills)){   
            $project_skills= explode(',', $request->skills);
            foreach($project_skills as $skl){
               $skil = ProjectProjectSkill::updateOrCreate([
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
         return ResponseBuilder::successMessage("Update Job Sucessfully", $this->success);
      }
      catch(\Exception $e)
      {
         return ResponseBuilder::error($e->getMessage(), $this->serverError);
      }
   }

   public function jobsList(Request $request)
   {
      try
      {
         $user_id = '';
         if (Auth::guard('api')->check()) {
            $singleuser = Auth::guard('api')->user();
            $user_id = $singleuser->id;
         }

         $page = !empty($request->pagination) ? $request->pagination : 10; 
         
         $query = Project::where('status','!=','close')->with('skills','categories');

         // Filter Project Category
         if(isset($request->project_category) ) {
            $categories = explode(',', $request->project_category);
            $query->whereIn('project_category', $categories);
         }
         
         // Filter Project Search
         if(isset($request->search) ) {
            $query->where('name', 'like', "%$request->search%");
            $query->orWhere('description', 'like', "%$request->search%");
         }
         
         // Filter Project skills
         if(isset($request->skills) ) {
            // 
            $skills = explode(',', $request->skills);
            $projectsIds = ProjectProjectSkill::whereIn('project_skill_id', $skills)->pluck('project_id')->toArray();
            $DislikeJob = DislikeJob::where('user_id', $user_id)->pluck('job_id')->toArray();
            $projectIds = array_diff($projectsIds, $DislikeJob);
            $query->whereIn('id', $projectIds);
         } else {
            $DislikeJob = DislikeJob::where('user_id', $user_id)->pluck('job_id')->toArray();
            $query->whereNotIn('id', $DislikeJob);
         }

         // Filter Project Type
         if(isset($request->type) ) {
            $query->where('type', $request->type);
         }

         // Filter Project budget type
         if(isset($request->budget_type) ) {
            $query->where('budget_type', $request->budget_type);
         }

         // Filter Project EnglishLevel
         if(isset($request->english_level) ) {
            $query->where('english_level', $request->english_level);
         }

         // Filter Project Language
         if(isset($request->language) ) {
            $languages = explode(',', $request->language);
            $query->whereIn('language', $languages);
         }
         
         // Filter Project Price - MIN
         if(isset($request->min_price) ) {
            $query->where('min_price', '>=', $request->min_price);
         }
         // Filter Project Price - MAX
         if(isset($request->max_price) ) {
            $query->where('price', '<=', $request->max_price);
         }

         $jobdata = $query->paginate($page);
         $jobdata->user_id = $user_id;

         $this->response = new ProjectResource($jobdata);
         
         return ResponseBuilder::successWithPagination($jobdata,$this->response, "Jobs List Data",$this->success);
      }
      catch(\Exception $e){
         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
      }
   }

   public function recentJobsList(Request $request)
   {
      try
      {
         $user_id = '';
         if (Auth::guard('api')->check()) {
            $singleuser = Auth::guard('api')->user();
            $user_id = $singleuser->id;
         }
         $page = !empty($request->pagination) ? $request->pagination : 10; 
         $query = Project::where('status','publish')->orderBy('created_at','DESC')->with('skills','categories');
         
         // 
         $DislikeJob = DislikeJob::where('user_id', $user_id)->pluck('job_id')->toArray();
         if(count($DislikeJob) > 0) {
            $query->whereNotIn('id', $DislikeJob);
         }

         $jobdata = $query->paginate($page);
         $jobdata->user_id = $user_id;

         $this->response = new ProjectResource($jobdata);
         return ResponseBuilder::successWithPagination($jobdata,$this->response, "Recent Jobs List Data",$this->success);
         // return ResponseBuilder::success($this->response, "Recent Jobs List");
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
         } else {
            return ResponseBuilder::error(__("User not found"), $this->unauthorized);
         }
         $user_skills = FreelancerSkill::where('user_id',$user_id)->pluck('skill_id')->toArray();
         
         $project_skills = ProjectProjectSkill::whereIn('project_skill_id', $user_skills)->pluck('project_id')->toArray();
         
         $query = Project::whereIn('id',$project_skills)->where('status','publish')->orderBy('created_at','DESC')->with('skills','categories');
         

         $DislikeJob = DislikeJob::where('user_id', $user_id)->pluck('job_id')->toArray();
         if($DislikeJob){
            $query->whereNotIn('id', $DislikeJob);
         }
         
         $job_list = $query->get();

         $job_list->user_id = $user_id;
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
            'client_id'       => 'required|exists:client,user_id',
            'job_id'   	      =>	'required|exists:projects,id',
            'budget_type'	   =>	'required|in:fixed,hourly',
            'weekly_limit'	   =>	'required_if:budget_type,hourly|integer',
            'milestone_type'  =>	'required_if:budget_type,fixed|in:single,multiple',
         ]);

         if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
         }
         
         $send_proposal = SendProposal::where('project_id',$request->job_id)->where('freelancer_id',$user_id)->first();
         if(!empty($send_proposal))
         {
            return ResponseBuilder::error(__("Already Send Proposal"), $this->badRequest);
         }
         else {
            DB::beginTransaction();
            $get_fee = IncomeSource::where('name','Unify')->first();
            $platform_fee = $get_fee->fee_percent;

            $bidAmount = (isset($request->bid_amount))?($request->bid_amount - $platform_fee):'0';
            
            $proposal = SendProposal::updateOrCreate([
               'id' => $request->id
            ], [
               'client_id'       => $request->client_id,
               'freelancer_id'   => $user_id,
               'project_id'      => $request->job_id,
               'budget_type'     => $request->budget_type,
               'status'          => 'pending',
               'amount'          => $bidAmount,
               'platform_fee'    => $platform_fee,
               'weekly_limit'    => ($request->weekly_limit)??null,
               'title'           => ($request->title)??'',
               'date'            => ($request->date)??null,
               'cover_letter'    => $request->cover_letter,
               'image'           => !empty($request->file('image')) ? $this->proposalImage($request->file('image')  ) : '',
            ]);

            if($request->budget_type == 'fixed') {
               // 
               if($request->milestone_type == 'single') {
                  $mileStones = [
                     'proposal_id' => $proposal->id,
                     'project_id' => $request->job_id,
                     'client_id' => $request->client_id,
                     'freelancer_id' => $user_id,
                     'description' => $request->milestone_description[0],
                     'amount' => $request->milestone_amount[0],
                     'due_date' => $request->milestone_due_date[0],
                     'status' => 'created',
                     'note' => '',
                     'type' => $request->milestone_type
                  ];
                  ProjectMilestone::create($mileStones);
                  // 
               } else {
                  
                  $mileStonesDescription = $request->milestone_description;
                  $mileStonesAmount = $request->milestone_amount;
                  $mileStonesDueDate = $request->milestone_due_date;

                  foreach ($mileStonesDescription as $key => $value) {
                     # code...
                     $mileStones[] = [
                        'proposal_id' => $proposal->id,
                        'project_id' => $request->job_id,
                        'client_id' => $request->client_id,
                        'freelancer_id' => $user_id,
                        'description' => $value,
                        'amount' => $mileStonesAmount[$key],
                        'due_date' => $mileStonesDueDate[$key],
                        'status' => 'created',
                        'note' => '',
                        'type' => $request->milestone_type
                     ];
                  }

                  ProjectMilestone::insert($mileStones);
               }
            }

            // if($request->budget_type == 'hourly') { 
               
            // }

            DB::commit();
            return ResponseBuilder::successMessage("Sent Proposal Sucessfully",$this->success);
         }
      }catch(\Exception $e)
      {
         DB::rollback();
         return ResponseBuilder::error($e->getMessage(),$this->serverError);
      }
   }

   public function singleJob(Request $request)
   {
      try
      {
         $validator = Validator::make($request->all(), [
            'job_id'       => 'required|exists:projects,id',
         ]);
         if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
         }
         $job_list = Project::where('id',$request->job_id)->with('skills','categories')->first();
         $proposalss= SendProposal::join('users','send_proposals.freelancer_id','users.id')->where('project_id',$job_list->id)->select('users.id as freelancer_id','send_proposals.id as send_proposals_id','send_proposals.cover_letter','send_proposals.status','send_proposals.amount','send_proposals.created_at','users.first_name','users.profile_image')->get();
         $proposalList = [];
         if(count($proposalss) > 0){
            foreach($proposalss as $value)
            {
               $proposalList[] = [
                  'freelancer_id'         =>(string)$value->user_id,
                  'freelancer_name'       =>(string)$value->first_name,
                  'profile_image'         =>isset($value->profile_image) ? url('/images/profile-image',$value->profile_image) : '',
                  'proposal_id'           =>(string)$value->send_proposals_id,
                  'proposal_description'  =>(string)$value->cover_letter,
                  'status'                =>(string)$value->status,
                  'amount'                =>(string)$value->amount,
                  'time'                  =>$value->created_at->diffForHumans()
               ];
            }
         }
         $job_list['proposal_list'] = $proposalList;
         $job_list['cdata'] = $this->getClientInfo($job_list->client_id);

         if (Auth::guard('api')->check()) {
            $singleuser = Auth::guard('api')->user();
            $user_id = $singleuser->id;
         } else {
            $user_id = 0;
         }
         $job_list['user_id'] = $user_id;

         $job_list['service_fee'] = $user_id;
         
         $this->response = new ProjectSingleResource($job_list);

         return ResponseBuilder::success($this->response, "Single Jobs Data");
      }
      catch(\Exception $e){
         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
      }   
   }

   public function dislikeJob(Request $request)
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
            'reason_id'    => 'required|exists:dislike_reasons,id',
         ]);
         if ($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
         }
         $CheckJob = DislikeJob::where('job_id', $request->job_id)->where('user_id', $user_id)->first();
         if(!empty($CheckJob))
         {
            return ResponseBuilder::error(__("Already Dislike Job"), $this->badRequest);
         }
         else{
            $savejob = DislikeJob::updateOrCreate([
               'id' => $request->id
            ], [
               'user_id'   => $user_id,
               'job_id'    => $request->job_id,
               'reason_id' => $request->reason_id,
            ]);
            return ResponseBuilder::successMessage("Dislike Job Sucessfully",$this->success);
         }
      }
      catch(\Exception $e)
      {
         return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
      }
   }

   public function removeDislikeJob(Request $request)
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
         $CheckJob = DislikeJob::where('job_id', $request->job_id)->where('user_id', $user_id)->first();
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

}

