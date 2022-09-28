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
use App\Models\Jobs;
use App\Models\User;
use App\Models\Project_proposals;
class JobController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('project_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $dt = new DateTime();
        $d['d']= $dt->format('Y');
        $d['m']= $dt->format('m');
        $d['y']= $dt->format('d');
        $q = Jobs::query();
    
        $d['year']='';
        $d['month']='';
        $d['day']='';
        $d['pagination']='10';
            if($request->pagination){
                $d['pagination']=$request->pagination;
            }
        if (isset($request->day) || isset($request->month) || isset($request->year)) {

           if ($request->day=='all' && $request->month=='all') {
            $q->whereYear('created_at', '=', $request->year);
            $d['day']='all';
            $d['month']='all';
            $d['year']=$request->year;

           }elseif($request->day=='all'){

            $q->whereYear('created_at', '=', $request->year)->whereMonth('created_at', '=', $request->month);
            $d['day']='all';
            $d['month']=$request->month;
            $d['year']=$request->year;
           }else{
            $q->whereDate('created_at', '=', date(''.$request->year.'-'.$request->month.'-'.$request->day.''));
            $d['day']=$request->day;
            $d['month']=$request->month;
            $d['year']=$request->year;
           }

           
         

        }elseif(isset($request->start_date) && isset($request->end_date)){
            $q->whereBetween('created_at',[$request->start_date,$request->end_date]);
        }
        if($request->search){
        
            $q->where('name', 'like', "%$request->search%");
        }
       
         $d['jobs']=$q->paginate($d['pagination']);

        return view('admin.jobs.index', $d);
    }

    public function create()
    {
        abort_if(Gate::denies('project_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $d['user'] =User::all();
        $d['project'] =Project::all();
      
        $d['statuses'] = ProjectStatus::all();
        return view('admin.jobs.create', $d);
    }

    public function store(Request $request)
    { 
  
     
   
      $project = new Jobs;
      $project->user_id=$request->user_id;
      $project->project_id=$request->project_id;
      $project->status_id=$request->status_id1;
      
      $project->save();

        return redirect()->route('admin.jobs.index');
    }

    public function edit($id)
    {
   
       
        $d['job'] =Jobs::where('id',$id)->first();

        $d['user'] =User::all();
        $d['project'] =Project::all();
      
        $d['statuses'] = ProjectStatus::all();
        
       

        
        
    
        return view('admin.jobs.edit',$d);
    }

    public function update(Request $request)
    {
       
        $project = Jobs::where('id',$request->job_id)->first();
      $project->user_id=$request->user_id;
      $project->project_id=$request->project_id;
      $project->status_id=$request->status_id1;
      
      $project->save();

        return redirect()->route('admin.jobs.index');
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
        $project= Jobs::where('id', $id)->first();
        $project->delete();

        return back();
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
   
}
