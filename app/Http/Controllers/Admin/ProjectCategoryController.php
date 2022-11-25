<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProjectStatusRequest;
use App\Http\Requests\StoreProjectStatusRequest;
use App\Http\Requests\UpdateProjectStatusRequest;
use App\Models\ProjectCategory;
use Gate;
use App\Models\ProjectProjectCategory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Str;

class ProjectCategoryController extends Controller
{
    public function index(Request $request)
    {
        $d['pagination']='10';
        if($request->pagination){
            $d['pagination']=$request->pagination;
        }
        abort_if(Gate::denies('project_category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $search = "";
        if($request->search){
            $search = $request->search;
        }
        $d['projectCategory'] = ProjectCategory::where('parent_id', 0)->where('name', 'LIKE', '%'.$search.'%')->paginate($d['pagination']);
        // $d['count'] = ProjectCategory::where('parent_id', 7)->get();
        // dd($d['count']);
        return view('admin.projectCategory.index',$d);
    }

    public function create()
    {
        $Category=ProjectCategory::where("parent_id",'0')->get();

        return view('admin.projectCategory.create',compact('Category'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request['slug'] = Str::slug($request->name);
        $request['new_slug'] = $request['slug'];

        $count=1;
        while(ProjectCategory::where('slug', '=', $request['new_slug'])->exists())
        {
            $request['new_slug'] = $request['slug'].'-'.$count;
            $count++;
        }

        $projectCategory = new ProjectCategory;
        $projectCategory->name = $request->name;
        $projectCategory->slug = $request['new_slug'];
        $projectCategory->parent_id = $request->parent_id;
        $projectCategory->short_description = $request->short_description;
        $projectCategory->long_description = $request->long_description;
        $projectCategory->image = !empty($request->file('image')) ? $this->categoryImage($request->file('image')) : '';
        $projectCategory->banner_image = !empty($request->hasfile('banner_image')) ? $this->categoryImage($request->file('banner_image')) : '';
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
        // dd($request->all());
        // if($projectCategory1->getOriginal('name') != $request->name)
        // {
        //     $request['slug'] = Str::slug($request->name);
        //     $request['new_slug'] = $request['slug'];

        //     $count=1;
        //     while(ProjectCategory::where('slug', '=', $request['new_slug'])->exists())
        //     {
        //         $request['new_slug'] = $request['slug'].'-'.$count;
        //         $count++;
        //     }
        // }
        // else
        // {
        //     $request['new_slug'] = $request->slug;
        // }

        $projectCategory1=ProjectCategory::where('id',$projectCategory->id)->first();
        $projectCategory1->name=$request->name;
        $projectCategory->slug = $request['new_slug'];
        $projectCategory1->parent_id=$request->parent_id;
        $projectCategory1->short_description=$request->short_description;
        $projectCategory1->long_description=$request->long_description;
        $projectCategory1->image = ($request->hasfile('image')) ? $this->categoryImage($request->file('image')) : (($request->image_old) ? $request->image_old : '');
        $projectCategory1->banner_image = ($request->hasfile('banner_image')) ? $this->categoryImage($request->file('banner_image')) : (($request->banner_image_old) ? $request->banner_image_old : '');
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

        ProjectCategory::where('parent_id', $projectCategory->id)->update(['parent_id' => 0]);

        return back();
    }

    public function massDestroy(MassDestroyProjectCategoryRequest $request)
    {
        ProjectCategory::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function category_replace(Request $request){
        $ProjectCategory=ProjectCategory::where('id','!=',$request->id)->get();
  
        $online = '';
        $status = false;
        foreach($ProjectCategory as $item){

            $online .= '
            <option value="'.$item->id.'">'.$item->name.'</option>
                 ';

        }
        return response()->json(['status' => 'success','online' => $online]);
    }
    public function category_delete_replace(Request $request){
   
     $project=ProjectProjectCategory::where('project_category_id',$request->delete_id)->get();
       foreach($project as $item){
          $item->project_category_id=$request->replace_id;
          $item->save();
       }
       $ProjectCategory=ProjectCategory::where('id',$request->delete_id)->first();
       $ProjectCategory->delete();
       return back();
    }

    public function sub_category(Request $request,$id){
        $d['pagination']='10';
        if($request->pagination){
            $d['pagination']=$request->pagination;
        }
        abort_if(Gate::denies('project_category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $search = "";
        if($request->search){
            $search = $request->search;
        }
        $d['projectsubCategory'] = ProjectCategory::where('parent_id', $id)->where('name', 'LIKE', '%'.$search.'%')->paginate($d['pagination']);
        $d['parent_id'] = $id;
        return view('admin.projectCategory.sub-category',$d);
    }

    public function sub_category_create(Request $request){
        $parent_id = $request->id;
        $Category=ProjectCategory::where("parent_id",'0')->get();

        return view('admin.projectCategory.create',compact('Category','parent_id'));
    }
}
