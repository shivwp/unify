<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

use App\Models\Services;
use App\Models\Project;
use App\Models\ProjectStatus;
use App\Models\ProjectCategory;
use App\Models\ProjectListingType;
use App\Models\ProjectSkill;
use App\Models\ProjectProjectSkill;
use App\Models\ProjectProjectCategory;
use App\Models\ProjectProjectListingType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Intervention\Image\ImageManagerStatic as Image;
use DateTime;
class ServicesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('project_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $serv = Services::query();
        if(!empty($request->search)){
            $serv = $serv->where('service_name', 'LIKE', '%'.$request->search.'%');
        }
         if(!empty($request->pagination)){
            $n = $request->pagination;
        }
        else{
            $n = 10;
        }

        $d['pagination']= $n;
        $d['Services'] = $serv->paginate($d['pagination']);
        return view('admin.service.index', $d);
    }

    public function create()
    {
        abort_if(Gate::denies('project_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        return view('admin.service.create');
    }

    public function store(Request $request)
    {
        $Services=new Services;
        $Services->service_name=$request->service_name;
        $Services->description=$request->description;
        $Services->save();
       

        return redirect()->route('admin.service.index');
    }

    public function edit($id)
    {

     $d['service']=Services::where('id',$id)->first();
  
     
        return view('admin.service.edit',$d);
    }

    public function update(Request $request, $project)
    {
        $Services=Services::where('id',$project)->first();
        $Services->service_name=$request->service_name;
        $Services->description=$request->description;
        $Services->save();
        return redirect()->route('admin.service.index');
    }

    public function show(Project $project)
    {
        abort_if(Gate::denies('project_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $project->load('client', 'status','categories','skills','listingtypes');

        return view('admin.service.show', compact('project'));
    }

    public function destroy(Request $id)
    { abort_if(Gate::denies('project_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
       
     
        $service=Services::where('id',$id->id)->first();
        $service->delete(); 

        return back();
    }

    public function massDestroy(MassDestroyProjectRequest $request)
    {
        Service::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
