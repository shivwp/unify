<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProjectStatusRequest;
use App\Http\Requests\StoreProjectStatusRequest;
use App\Http\Requests\UpdateProjectStatusRequest;
use App\Models\ProjectSkill;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectSkillController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('project_skills_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projectSkill = ProjectSkill::paginate(10);

        return view('admin.projectSkill.index', compact('projectSkill'));
    }

    public function create()
    {
       
        return view('admin.projectSkill.create');
    }

    public function store(Request $request)
    {
        $projectSkill = ProjectSkill::create($request->all());

        return redirect()->route('admin.project-skill.index');
    }

    public function edit(projectSkill $projectSkill)
    {
        abort_if(Gate::denies('project_skills_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.projectSkill.edit', compact('projectSkill'));
    }

    public function update(Request $request, projectSkill $projectSkill)
    {
        $projectSkill->update($request->all());

        return redirect()->route('admin.project-skill.index');
    }

    public function show(projectSkill $projectSkill)
    {
        abort_if(Gate::denies('project_skills_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.projectSkill.show', compact('projectSkill'));
    }

    public function destroy(projectSkill $projectSkill)
    {
        abort_if(Gate::denies('project_skills_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projectSkill->delete();

        return back();
    }

    public function massDestroy(MassDestroyProjectSkillRequest $request)
    {
        ProjectSkill::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
