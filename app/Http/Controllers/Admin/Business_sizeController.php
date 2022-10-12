<?php

namespace App\Http\Controllers\Admin;
use App\Models\Client;
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
use App\Models\Business_size;
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


class Business_sizeController extends Controller
{
    public function index(Request $request)
    {
        $q = Business_size::query();
    
        $d['pagination']='10';
         $d['Business_size']=$q->paginate($d['pagination']);

        return view('admin.business_size.index', $d);
    }

    public function create()
    {
        return view('admin.business_size.create');
    }

    public function store(Request $request)
    { 
  
      $Business_size = new Business_size;
      $Business_size->title=$request->business_title;
      $Business_size->min_employee=$request->min_business_size;
      $Business_size->max_employee=$request->max_business_size;
      $Business_size->save();

        return redirect()->route('admin.business_size.index');
    }

    public function edit($id)
    { 
        $d['business_size'] =Business_size::where('id',$id)->first();
      return view('admin.business_size.edit',$d);
    }

    public function update(Request $request)
    {
        $Business_size = Business_size::where('id',$request->business_id)->first();

        $Business_size->title=$request->business_title;
        $Business_size->min_employee=$request->min_business_size;
        $Business_size->max_employee=$request->max_business_size;
        $Business_size->save();
    

        return redirect()->route('admin.business_size.index');
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
        $project= Business_size::where('id', $id)->first();
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
