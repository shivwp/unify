<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Services;
use App\Project;
use App\Plans;
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
use DateTime;
class SubscriptionPlansController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('project_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $d['plans']=Plans::paginate(10);

        return view('admin.plans.index', $d);
    }

    public function create()
    { abort_if(Gate::denies('project_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

       $services=Services::all();

        return view('admin.plans.create',compact('services'));
    }

    public function store(Request $request)
    {
    
        $Plans=new Plans;
        $Plans->plans_title=$request->plans_title;
        $Plans->validity=$request->validity;
        $Plans->amount=$request->amount;
        $Plans->description=$request->description;
         $Plans->save();
         $Plans->services()->sync($request->input('services', []));

        return redirect()->route('admin.plan.index');
    }

    public function edit($id)
    {

     $d['service']=Plans::where('id',$id)->first();
     $d['services']= Services::all()->pluck('service_name', 'id');
   
        return view('admin.plans.edit',$d);
    }

    public function update(Request $request, $project)
    {
        $Plans=Plans::where('id',$project)->first();
        $Plans->plans_title=$request->plans_title;
        $Plans->validity=$request->validity;
        $Plans->amount=$request->amount;
        $Plans->description=$request->description;
     
         $Plans->save();
         $Plans->services()->sync($request->input('services', []));
         return redirect()->route('admin.plan.index');
    }

    public function show(Project $project)
    {
        abort_if(Gate::denies('project_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $project->load('client', 'status','categories','skills','listingtypes');

        return view('admin.service.show', compact('project'));
    }

    public function destroy(Request $id)
    { abort_if(Gate::denies('project_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
       
     
        $service=Plans::where('id',$id->id)->first();
        $service->delete(); 

        return back();
    }

    public function massDestroy(MassDestroyProjectRequest $request)
    {
        Service::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
