<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProjectStatusRequest;
use App\Http\Requests\StoreProjectStatusRequest;
use App\Http\Requests\UpdateProjectStatusRequest;
use App\Models\ProjectListingType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectListingTypeController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('project_listing_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $d['projectListingType'] = ProjectListingType::paginate(10);

        return view('admin.projectListingType.index',$d);
    }

    public function create()
    {
        return view('admin.projectListingType.create');
    }

    public function store(Request $request)
    {
        $projectListingType = ProjectListingType::create($request->all());

        return redirect()->route('admin.project-listing-type.index');
    }

    public function edit(ProjectListingType $projectListingType)
    {
        abort_if(Gate::denies('project_listing_type_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.projectListingType.edit', compact('projectListingType'));
    }

    public function update(Request $request, ProjectListingType $projectListingType)
    {
        $projectListingType->update($request->all());

        return redirect()->route('admin.project-listing-type.index');
    }

    public function show(ProjectListingType $projectListingType)
    {
        abort_if(Gate::denies('project_listing_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.projectListingType.show', compact('projectListingType'));
    }

    public function destroy(ProjectListingType $projectListingType)
    {
        abort_if(Gate::denies('project_listing_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projectListingType->delete();

        return back();
    }

    public function massDestroy(MassDestroyProjectListingTypeRequest $request)
    {
        ProjectListingType::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
