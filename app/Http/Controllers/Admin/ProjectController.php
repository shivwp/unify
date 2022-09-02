<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Project;
use App\ProjectStatus;
use App\Exports\ProjectExport;
use Maatwebsite\Excel\Facades\Excel;    
use App\ProjectCategory;
use App\ProjectListingType;
use App\ProjectSkill;
use App\ProjectProjectSkill;
use App\ProjectProjectCategory;
use App\ProjectProjectListingType;
use Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Intervention\Image\ImageManagerStatic as Image;
use DateTime;
use PDF;
use App\Project_proposals;
class ProjectController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('project_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $dt = new DateTime();
        
        $q = Project::query();

        $d['year']=$dt->format('Y');
        $d['month']=$dt->format('m');
        $d['day']=$dt->format('d');

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

           
         

        }

        
         $d['projects']=$q->paginate(10);

        return view('admin.projects.index', $d);
    }

    public function create()
    {
        abort_if(Gate::denies('project_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $d['clients'] = Client::all()->pluck('first_name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $d['statuses'] = ProjectStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $d['category'] = ProjectCategory::all();
        $d['skill'] = ProjectSkill::all();
        $d['listing'] = ProjectListingType::all();

        return view('admin.projects.create', $d);
    }

    public function store(StoreProjectRequest $request)
    { 
      
        // dd($request);
        $project = Project::create([
            'name' =>$request->name,
            'description' =>$request->description,
            'start_date' =>$request->start_date,
            'end_date' =>$request->end_date,
            'total_budget' =>$request->total_budget,
            'per_hour_budget' =>$request->per_hour_budget,
            'client_id' =>$request->client_id,
            'status_id' =>$request->status_id,
            'freelancer_type' =>$request->freelancer_type,
            'project_duration' =>$request->project_duration,
            'payment_base' =>$request->payment_base,
            'level' =>$request->level,
            'english_level' =>$request->english_level,
            'scop' =>$request->scop,
        ]);
      
        if ($request->hasfile('image')) {
          $file1 = $request->file('image');
          $image_name = [];
          foreach ($file1 as $image) {
            $name = $image->getClientOriginalName();
            $filename = time() . '_' . $name;
            $image_resize = Image::make($image->getRealPath());
            $image_resize->save('project-files/' . $filename);
            $image_name[] =  $filename;
          }
        }
        $result = [];
        $varimg = json_decode($request->image1);

        if (!empty($image_name) && !empty($varimg)) {

          $result = array_merge($image_name, $varimg);
        } else if (!empty($image_name)) {

          $result = $image_name;
        } else {
          $result = $varimg;
        }
        
        Project::where('id', '=', $project->id)->update([
          'project_images' => json_encode($result)
        ]);
        if(!empty($request->category)){   
            $project_category = $request->category;
                foreach($project_category as $vlu){
                    $cat = ProjectProjectCategory::create([
                        'project_id'=>$project->id,
                        'project_category_id'=>$vlu,
                    ]);
                    $cat->save();
                }
        }
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
        if(!empty($request->listing)){   
            $project_listing = $request->listing;
                foreach($project_listing as $list){
                    $listingg = ProjectProjectListingType::create([
                        'project_id'=>$project->id,
                        'project_listing_type_id'=>$list,
                    ]);
                    $listingg->save();
                }
        }

        return redirect()->route('admin.projects.index');
    }

    public function edit(Project $project)
    {
        abort_if(Gate::denies('project_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $d['clients'] = Client::all()->pluck('first_name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $d['statuses'] = ProjectStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $d['category'] = ProjectCategory::all();
        $d['skill'] = ProjectSkill::all();
        $d['listing'] = ProjectListingType::all();
        $project->load('client', 'status','categories','skills','listingtypes');
        
        $d['project'] = $project;

        
        
     
        return view('admin.projects.edit',$d);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project = Project::where('id',$request->project_id)->update([
            'name' =>$request->name,
            'description' =>$request->description,
            'start_date' =>$request->start_date,
            'end_date' =>$request->end_date,
            'total_budget' =>$request->total_budget,
            'per_hour_budget' =>$request->per_hour_budget,
            'client_id' =>$request->client_id,
            'status_id' =>$request->status_id,
            'freelancer_type' =>$request->freelancer_type,
            'project_duration' =>$request->project_duration,
            'payment_base' =>$request->payment_base,
            'level' =>$request->level,
            'english_level' =>$request->english_level,
            'scop' =>$request->scop,
        ]);
        if ($request->hasfile('image')) {
          $file1 = $request->file('image');
          $image_name = [];
          foreach ($file1 as $image) {
            
            $name = $image->getClientOriginalName();
            $filename = time() . '_' . $name;
            $image_resize = Image::make($image->getRealPath());
            $image_resize->save('project-files/' . $filename);
            $image_name[] =  $filename;
          }
        }
        $result = [];
        $varimg = json_decode($request->image1);

        if (!empty($image_name) && !empty($varimg)) {

          $result = array_merge($image_name, $varimg);
        } else if (!empty($image_name)) {

          $result = $image_name;
        } else {
          $result = $varimg;
        }
        
        Project::where('id', '=', $request->project_id)->update([
          'project_images' => json_encode($result)
        ]);
        if(!empty($request->category)){   
            $project_category = $request->category;
                foreach($project_category as $vlu){
                    $cat = ProjectProjectCategory::updateOrCreate([
                        'project_id'=>$request->project_id,
                        'project_category_id'=>$vlu,
                    ]);
                }
        }
        if(!empty($request->skills)){   
            $project_skills = $request->skills;
                foreach($project_skills as $skl){
                    $skil = ProjectProjectSkill::updateOrCreate([
                        'project_id'=>$request->project_id,
                        'project_skill_id'=>$skl,
                    ]);
                }
        }
        if(!empty($request->listing)){   
            $project_listing = $request->listing;
                foreach($project_listing as $list){
                    $listingg = ProjectProjectListingType::updateOrCreate([
                        'project_id'=>$request->project_id,
                        'project_listing_type_id'=>$list,
                    ]);
                }
        }

        return redirect()->route('admin.projects.index');
    }

    public function show(Project $project)
    {
        abort_if(Gate::denies('project_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $proposals=Project_proposals::where('project_id',$project->id)->orderby('id','desc')->get();
        $project->load('client', 'status','categories','skills','listingtypes');

        return view('admin.projects.show', compact('project','proposals'));
    }

    public function destroy(Project $project)
    {
        abort_if(Gate::denies('project_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
    public function export_in_excel() 
    {
        return Excel::download(new ProjectExport, 'users.xlsx');
    }
}
