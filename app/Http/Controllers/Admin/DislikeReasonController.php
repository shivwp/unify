<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DislikeReason;
use Illuminate\Http\Request;

class DislikeReasonController extends Controller
{
    public function index(Request $request){

        $reason = DislikeReason::orderBy('created_at','DESC')->select('*');

        if($request->search){
          
            $reason->where('name', 'like', "%$request->search%");
        }
        $d['reason'] = $reason->paginate('10')->withQueryString();
        return view('admin.dislike-reason.index',$d);
    }

    public function create(){

        return view('admin.dislike-reason.create');
    }

    public function store(Request $request){
        
        $request->validate([
            'title' => 'required | string',
        ]);

        if(!isset($request->id)){
            if(DislikeReason::where('name', '=', $request->title)->exists())
            {
                session()->flash('error', 'Dislike Reason Already Exists!');
                return redirect()->back();
            }
        }
        $reason = DislikeReason::updateOrCreate(
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
                session()->flash('success','Dislike Reason Updated successfully');
                return redirect()->route('admin.dislike-reason.index');
            }
            else
            {
                session()->flash('success','Dislike Reason Created successfully');
                return redirect()->route('admin.dislike-reason.index');
            }
        }
        else
        {
            session()->flash('error', 'Something went Wrong, Please try again!');
            return redirect()->back();
        }
    }

    public function edit($id){

        $d['reason'] = DislikeReason::where('id',$id)->first();
        return view('admin.dislike-reason.create',$d);
    }

    public function update(Request $request){

    }

    public function destroy($id){

        DislikeReason::where('id',$id)->delete();
        session()->flash('success',' Deleted successfully');
        return redirect()->back();

    }
}
