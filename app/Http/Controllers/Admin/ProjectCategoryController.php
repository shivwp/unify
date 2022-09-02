<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProjectStatusRequest;
use App\Http\Requests\StoreProjectStatusRequest;
use App\Http\Requests\UpdateProjectStatusRequest;
use App\ProjectCategory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectCategoryController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('project_category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $d['projectCategory'] = ProjectCategory::paginate(10);

        return view('admin.projectCategory.index',$d);
    }

    public function create()
    {
        $Category=ProjectCategory::where("parent_id",'0')->get();

        return view('admin.projectCategory.create',compact('Category'));
    }

    public function store(Request $request)
    {
        $projectCategory = new ProjectCategory;
        $projectCategory->name=$request->name;
        $projectCategory->parent_id=$request->parent_id;
        $projectCategory->save();

        return redirect()->route('admin.project-category.index');
    }

    public function edit(ProjectCategory $projectCategory)
    {
      
        abort_if(Gate::denies('project_category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $Category=ProjectCategory::where("parent_id",'0')->get();
        return view('admin.projectCategory.edit', compact('projectCategory','Category'));
    }

    public function update(Request $request, ProjectCategory $projectCategory)
    {
        $projectCategory1=ProjectCategory::where('id',$projectCategory->id)->first();
        $projectCategory1->name=$request->name;
        $projectCategory1->parent_id=$request->parent_id;
        $projectCategory1->save();

        return redirect()->route('admin.project-category.index');
    }

    public function show(ProjectCategory $projectCategory)
    {
        abort_if(Gate::denies('project_category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.projectCategory.show', compact('projectCategory'));
    }

    public function destroy(ProjectCategory $projectCategory)
    {
        abort_if(Gate::denies('project_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projectCategory->delete();

        return back();
    }

    public function massDestroy(MassDestroyProjectCategoryRequest $request)
    {
        ProjectCategory::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
