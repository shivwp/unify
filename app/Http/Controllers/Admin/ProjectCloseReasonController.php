<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectCloseReason;
use Illuminate\Http\Request;

class ProjectCloseReasonController extends Controller
{
    public function index(Request $request){

        $reason = ProjectCloseReason::orderBy('created_at','DESC')->select('*');

        if($request->search){
          
            $reason->where('name', 'like', "%$request->search%");
        }
        $d['reason'] = $reason->paginate('10')->withQueryString();
        return view('admin.project-close-reason.index',$d);
    }

    public function create(){

        return view('admin.project-close-reason.create');
    }

    public function store(Request $request){
        
        $request->validate([
            'title' => 'required | string',
        ]);

        if(!isset($request->id)){
            if(ProjectCloseReason::where('name', '=', $request->title)->exists())
            {
                session()->flash('error', 'Project Close Reason Already Exists!');
                return redirect()->back();
            }
        }
        $reason = ProjectCloseReason::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'name' => $request->title,
            ]
        );
        $result = $reason->update();

        if($result)
        {
            if($request->id)
            {
                session()->flash('success','Project Close Reason Updated successfully');
                return redirect()->route('admin.project-close-reason.index');
            }
            else
            {
                session()->flash('success','Project Close Reason Created successfully');
                return redirect()->route('admin.project-close-reason.index');
            }
        }
        else
        {
            session()->flash('error', 'Something went Wrong, Please try again!');
            return redirect()->back();
        }
    }

    public function edit($id){

        $d['reason'] = ProjectCloseReason::where('id',$id)->first();
        return view('admin.project-close-reason.create',$d);
    }

    public function update(Request $request){

    }

    public function destroy($id){

        ProjectCloseReason::where('id',$id)->delete();
        session()->flash('success',' Deleted successfully');
        return redirect()->back();

    }
}
