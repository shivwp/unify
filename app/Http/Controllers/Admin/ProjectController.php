<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\ProjectStatus;
use App\Exports\ProjectExport;
use Maatwebsite\Excel\Facades\Excel;    
use App\Models\ProjectCategory;
use App\Models\ProjectListingType;
use App\Models\ProjectSkill;
use App\Models\ProjectProjectSkill;
use App\Models\ProjectProjectCategory;
use App\Models\ProjectProjectListingType;
use Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Intervention\Image\ImageManagerStatic as Image;
use DateTime;
use PDF;
use DB;
use App\Models\Project_proposals;
use App\Models\SendProposal;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('project_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // $dt = new DateTime();
        // $d['d']= $dt->format('d');
        // $d['m']= $dt->format('m');
        // $d['y']= $dt->format('Y');
        $q = Project::query();
    
        // $d['year']='';
        // $d['month']='';
        // $d['day']='';
        $d['pagination']='10';
            if($request->pagination){
                $d['pagination']=$request->pagination;
            }

        // if (isset($request->day) || isset($request->month) || isset($request->year)) {

        //    if ($request->day=='all' && $request->month=='all') {
        //     $q->whereYear('created_at', '=', $request->year);
        //     $d['day']='all';
        //     $d['month']='all';
        //     $d['year']=$request->year;

        //    }elseif($request->day=='all'){

        //     $q->whereYear('created_at', '=', $request->year)->whereMonth('created_at', '=', $request->month);
        //     $d['day']='all';
        //     $d['month']=$request->month;
        //     $d['year']=$request->year;
        //    }else{
        //     $q->whereDate('created_at', '=', date(''.$request->year.'-'.$request->month.'-'.$request->day.''));
        //     $d['day']=$request->day;
        //     $d['month']=$request->month;
        //     $d['year']=$request->year;
        //    }

           
         

        // }else


        if(isset($request->start_date)){
            $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date)->startOfDay();
        }
        if(isset($request->end_date)){
            $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date)->endOfDay();
        }
        // echo "<pre>";
        // print_r($startDate);
        // print_r($endDate);

        if(isset($request->start_date) && isset($request->end_date)){
            $q->whereBetween('created_at',[$startDate,$endDate]);
        }elseif(isset($request->start_date)){
            $q->where('created_at', '>=', $startDate );        
        }
        elseif(isset($request->end_date)){
            $q->where('created_at', '<=', $endDate);
        }

        
        if($request->search){
        
            $q->where('name', 'like', "%$request->search%");
        }

        if($request->project_status_filter){
            $q->where('status', '=', $request->project_status_filter);
        }

        $d['statuses'] = ProjectStatus::all()->pluck('name', 'id');

        $d['projects']=$q->orderBy('id', 'DESC')->paginate($d['pagination']);

        // dd($d);
        return view('admin.projects.index', $d);
    }

    public function create()
    {
        abort_if(Gate::denies('project_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $d['clients']  =DB::table('users')
        ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
        ->where('role_user.role_id', '=', 3)
        ->where('users.deleted_at','=',null)->get();

        $d['statuses'] = ProjectStatus::all()->pluck('name', 'id');
        $d['category'] = ProjectCategory::all();
        $d['skill'] = ProjectSkill::all();
        $d['listing'] = ProjectListingType::all();

        return view('admin.projects.create', $d);
    }

    public function store(StoreProjectRequest $request)
    {
        $request->validate([
            'project_type' => 'required',
            'category' => 'required',
            'skills' => 'required',
            'scope' => 'required',
            'project_duration' => 'required',
            'level' => 'required',
            'budget' => 'required',
            'status_id' => 'required',
        ]);
        if($request->budget == 'hourly'){
            $request->validate([
                'min_budget' => 'required',
                'max_budget_hourly' => 'required',
            ]);
            $max_budget = $request->max_budget_hourly;
        }
        else{
            $request->validate([
                'max_budget' => 'required',
            ]);
            $max_budget = $request->max_budget;
        }

        // dd($request->all());

        $request['slug'] = Str::slug($request->name);

        $project = Project::create([
            'name' =>$request->name,
            'slug' => $request['slug'],
            'description' =>$request->description,
            'type' => $request->project_type,
            'project_category' => $request->category,
            'budget_type' => $request->budget,
            'min_price' => $request->min_budget,
            'price' => $max_budget,
            'client_id' =>$request->client_id,
            'status' =>$request->status_id,
            'project_duration' =>$request->project_duration,
            'experience_level' =>$request->level,
            'scop' =>$request->scope,
        ]);
        $project_result = $project->save();
    
        if ($request->hasfile('image')) {
            $image = ($request->file('image'));
            $image_name = $this->uploadProjectImage($image);

            Project::where('id', '=', $project->id)->update([
                'project_images' => $image_name
            ]);
        }
        
        if(!empty($request->skills)){   
            $project_skills = $request->skills;
            
                foreach($project_skills as $skl){
                    $skil = ProjectProjectSkill::create([
                        'project_id'=>$project->id,
                        'project_skill_id'=>$skl,
                    ]);
                    $skil_result = $skil->save();
                }
        }

        if($project_result && $skil_result)
        {
            return redirect()->route('admin.projects.index')->with('success', 'Project Created Successfully');
        }
        else
        {
            return back()->with('error', 'Something went Wrong, Please try again!');
        }
    }

    public function edit(Project $project)
    {
        abort_if(Gate::denies('project_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $d['clients'] =DB::table('users')
        ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
        ->where('role_user.role_id', '=', 3)
        ->where('users.deleted_at','=',null)->get();
        $proposals=SendProposal::where('job_id',$project->id)->with(['users' => function($query){$query->where('deleted_at', '=', null);}])->orderby('id','desc');
        $d['statuses'] = ProjectStatus::all()->pluck('name', 'id');
        $d['category'] = ProjectCategory::all();
        $d['skill'] = ProjectSkill::all();
        $d['listing'] = ProjectListingType::all();
        $project->load('client', 'status','categories','skills','listingtypes');
        $d['proposals']=$proposals->paginate(5);
        $d['project'] = $project;

        
        
     
        return view('admin.projects.edit',$d);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $request->validate([
            'project_type' => 'required',
            'category' => 'required',
            'skills' => 'required',
            'scope' => 'required',
            'project_duration' => 'required',
            'level' => 'required',
            'budget' => 'required',
            'status_id' => 'required',
        ]);
        if($request->budget == 'hourly'){
            $request->validate([
                'min_budget' => 'required',
                'max_budget_hourly' => 'required',
            ]);
            $max_budget = $request->max_budget_hourly;
        }
        else{
            $request->validate([
                'max_budget' => 'required',
            ]);
            $max_budget = $request->max_budget;
        }

        $request['slug'] = Str::slug($request->name);

        $project = Project::where('id',$request->project_id)->update([
            'name' =>$request->name,
            'slug' => $request['slug'],
            'description' =>$request->description,
            'type' => $request->project_type,
            'project_category' => $request->category,
            'budget_type' => $request->budget,
            'min_price' => $request->min_budget,
            'price' => $max_budget,
            'client_id' =>$request->client_id,
            'status' =>$request->status_id,
            'project_duration' =>$request->project_duration,
            'experience_level' =>$request->level,
            'scop' =>$request->scope,
        ]);

        if ($request->hasfile('image')) {
            $image = ($request->file('image'));
            $image_name = $this->uploadProjectImage($image);

            Project::where('id', '=', $request->project_id)->update([
                'project_images' => $image_name
            ]);
        }

        
        // if ($request->hasfile('image')) {
        //   $file1 = $request->file('image');
        //   $image_name = [];
        //   foreach ($file1 as $image) {
            
        //     $name = $image->getClientOriginalName();
        //     $filename = time() . '_' . $name;
        //     $image_resize = Image::make($image->getRealPath());
        //     $image_resize->save('project-files/' . $filename);
        //     $image_name[] =  $filename;
        //   }
        // }
        // $result = [];
        // $varimg = json_decode($request->image1);

        // if (!empty($image_name) && !empty($varimg)) {

        //   $result = array_merge($image_name, $varimg);
        // } else if (!empty($image_name)) {

        //   $result = $image_name;
        // } else {
        //   $result = $varimg;
        // }
        
        // Project::where('id', '=', $request->project_id)->update([
        //   'project_images' => json_encode($result)
        // ]);

        // if(!empty($request->skills)){   
        //     $project_skills = $request->skills;
        //         foreach($project_skills as $skl){
        //             $skil = ProjectProjectSkill::updateOrCreate([
        //                 'project_id'=>$request->project_id,
        //                 'project_skill_id'=>$skl,
        //             ]);
                    
        //         }
        // }

            $projectSkill = ProjectSkill::all();
            $skills = $request->skills;
            foreach($skills as $val){
                $projectsSkill[] = $val;
            }
            
                foreach($projectSkill as $val){

                    if(in_array($val->id, $projectsSkill)){
                        $save_d[] = ProjectProjectSkill::updateOrCreate([
                            'project_id'   => $request->project_id,
                            'project_skill_id'   => $val->id,
                        ],
                        [
                            'project_skill_id'   => $val->id,
                        ]);
                    }
                    else{
                        $skilll_id = ProjectProjectSkill::where('project_id',$request->project_id)->where('project_skill_id',$val->id)->first();
                        if($skilll_id)
                        $skilll_id->delete();
                    }
                }
            

        if($project)
        {
            return redirect()->route('admin.projects.index')->with('success', 'Project Updated Successfully');
        }
        else
        {
            return back()->with('error', 'Something went Wrong, Please try again!');
        }
    }

    public function show(Project $project)
    {
        abort_if(Gate::denies('project_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $proposals=Project_proposals::where('project_id',$project->id)->orderby('id','desc')->get();
        $project->load('client', 'status','categories','skills','listingtypes');

        return view('admin.projects.show', compact('project','proposals'));
    }

    public function destroy( $id)
    {
      
        abort_if(Gate::denies('project_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $project= Project::where('id', $id)->first();
        $result = $project->delete();

        if($result)
        {
            return back()->with('success', 'Project Deleted Successfully');
        }
        else
        {
            return back()->with('error', 'Something went wrong, Please try again!');
        }
    }

    public function massDestroy(MassDestroyProjectRequest $request)
    {
        Project::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
    //   public function createPDF() {
      
    //   $data = Project::all();

    //   view()->share('employee',$data);
    //   $pdf = PDF::loadView('pdf_view', $data);
    
    //   return $pdf->download('pdf_file.pdf');
    // }
     public function createPDF()
    {
      $projects=Project::all();
      $pdf = PDF::loadView('admin/pdf/projects', compact('projects'));
     return $pdf->download('download.pdf');
    }
    // public function export_in_excel() 
    // {
    //     return Excel::download(new ProjectExport, 'users.xlsx');
    // }
    public function project_multi_delete(Request $request) 
    {
        
       if(!empty($request->multi_delete)){
        foreach($request->multi_delete as $item){
            $project= Project::where('id', $item)->first();
            $project->delete();
        }
        return back();
       }
        return back();
      
    }

    public function project_proposal($id)
    {

        $d['proposal'] = SendProposal::join('users', 'send_proposals.user_id', '=', 'users.id')->join('projects', 'send_proposals.job_id', '=', 'projects.id')->where('send_proposals.id', $id)->select('send_proposals.*', 'users.first_name', 'users.email', 'projects.name as project_name')->first();
        return view('admin.projects.project-proposal', $d);
    }
   
}
