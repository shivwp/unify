<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProjectStatusRequest;
use App\Http\Requests\StoreProjectStatusRequest;
use App\Http\Requests\UpdateProjectStatusRequest;
use App\Models\ProjectSkill;
use App\Models\ProjectCategory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectSkillController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('project_skills_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $skillpage = ProjectSkill::orderBy('name', 'ASC');
        // $projectSkill = ProjectSkill::where(function($query){
        //     if(isset($request->search)){
        //         $query->where('name','LIKE','%$request->search%');

        //     }
        // })->orderBy('name', 'ASC')->paginate(10);
        // dd($request->search);
        if($request->search){
            $skillpage = $skillpage->where('name', 'like', "%$request->search%");
        }
        if (!empty($request->pagination)) {
            $pagination = $request->pagination;
        }
        else{
            $pagination = 10;
        }

        $projectSkill = $skillpage->paginate($pagination);

        return view('admin.projectSkill.index', compact('projectSkill'));
    }

    public function create()
    {
        $category=ProjectCategory::where("parent_id",'0')->select('id','name')->get();
        return view('admin.projectSkill.create',compact('category'));
    }

    public function store(Request $request)
    {

        $projectSkill = new ProjectSkill;
        $projectSkill->cate_id = $request->cate_id;
        $projectSkill->name = $request->name;
        $projectSkill->short_description = $request->short_description;
        $projectSkill->long_description = $request->long_description;
        $projectSkill->image = !empty($request->hasfile('image')) ? $this->skillsImage($request->file('image')) : 'dummy.png';
        $projectSkill->banner_image = !empty($request->hasfile('banner_image')) ? $this->skillsImage($request->file('banner_image')) : 'dummy.png';
        $projectSkill->save();

        return redirect()->route('admin.project-skill.index');
    }

    public function edit(projectSkill $projectSkill)
    {
        abort_if(Gate::denies('project_skills_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $category=ProjectCategory::where("parent_id",'0')->select('id','name')->get();
        return view('admin.projectSkill.edit', compact('projectSkill','category'));
    }

    public function update(Request $request, projectSkill $projectSkill)
    {   
        // dd($request->image);
        $ProjectSkills = ProjectSkill::where('id',$projectSkill->id)->first();
        $ProjectSkills->cate_id = isset($request->cate_id) ? $request->cate_id : $ProjectSkills->cate_id;
        $ProjectSkills->name = isset($request->name) ? $request->name : $ProjectSkills->name;
        $ProjectSkills->short_description = isset($request->short_description) ? $request->short_description : $ProjectSkills->short_description;
        $ProjectSkills->long_description = isset($request->long_description) ? $request->long_description : $ProjectSkills->long_description;
        $ProjectSkills->image = !empty($request->hasfile('image')) ? $this->skillsImage($request->file('image')) : $ProjectSkills->image;
        $ProjectSkills->banner_image = !empty($request->hasfile('banner_image')) ? $this->skillsImage($request->file('banner_image')) : $ProjectSkills->banner_image;
        $ProjectSkills->save();
        return redirect()->route('admin.project-skill.index');
    }

    public function show(projectSkill $projectSkill)
    {
        abort_if(Gate::denies('project_skills_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $category=ProjectCategory::where("id",$projectSkill->cate_id)->select('id','name')->first();
        return view('admin.projectSkill.show', compact('projectSkill','category'));
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
