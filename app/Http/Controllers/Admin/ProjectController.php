<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Project;
use App\ProjectStatus;
use App\ProjectCategory;
use App\ProjectListingType;
use App\ProjectSkill;
use App\ProjectProjectSkill;
use App\ProjectProjectCategory;
use App\ProjectProjectListingType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Intervention\Image\ImageManagerStatic as Image;

class ProjectController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('project_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $d['projects'] = Project::all();

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
            'budget' =>$request->budget,
            'client_id' =>$request->client_id,
            'status_id' =>$request->status_id,
            'freelancer_type' =>$request->freelancer_type,
            'project_duration' =>$request->project_duration,
            'payment_base' =>$request->payment_base,
            'level' =>$request->level,
            'english_level' =>$request->english_level,
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
                        'category_id'=>$vlu,
                    ]);
                    $cat->save();
                }
        }
        if(!empty($request->skills)){   
            $project_skills = $request->skills;
                foreach($project_skills as $skl){
                    $skil = ProjectProjectSkill::create([
                        'project_id'=>$project->id,
                        'skill_id'=>$skl,
                    ]);
                    $skil->save();
                }
        }
        if(!empty($request->listing)){   
            $project_listing = $request->listing;
                foreach($project_listing as $list){
                    $listingg = ProjectProjectListingType::create([
                        'project_id'=>$project->id,
                        'listing_type_id'=>$list,
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
        // dd($project);
        $project = Project::where('id',$request->project_id)->update([
            'name' =>$request->name,
            'description' =>$request->description,
            'start_date' =>$request->start_date,
            'budget' =>$request->budget,
            'client_id' =>$request->client_id,
            'status_id' =>$request->status_id,
            'freelancer_type' =>$request->freelancer_type,
            'project_duration' =>$request->project_duration,
            'payment_base' =>$request->payment_base,
            'level' =>$request->level,
            'english_level' =>$request->english_level,
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

        $project->load('client', 'status','categories','skills','listingtypes');

        return view('admin.projects.show', compact('project'));
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
}
