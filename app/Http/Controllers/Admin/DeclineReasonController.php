<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeclineReason;
use Illuminate\Http\Request;

class DeclineReasonController extends Controller
{
    public function index(Request $request){

        $reason = DeclineReason::orderBy('created_at','DESC')->select('*');
        // dd($reason);
        if($request->search){
          
            $reason->where('title', 'like', "%$request->search%");
        }
        $d['reason'] = $reason->paginate('10')->withQueryString();
        return view('admin.decline-reason.index',$d);
    }

    public function create(){

        return view('admin.decline-reason.create');
    }

    public function store(Request $request){
        
        // dd($request->all());
        $reason = DeclineReason::updateOrCreate(['id' => $request->id],
            ['title' => $request->title,
            'type' => $request->type,]
    );
        if(!$request->id){

            return redirect()->route('admin.decline-reason.index')->with('success', 'Reason Added successfully');
        }
        return redirect()->route('admin.decline-reason.index')->with('success', 'Reason updated successfully');
    }

    public function edit($id){
        // dd($id);
        $reason = DeclineReason::where('id',$id)->first();
        // dd($reason);
        return view('admin.decline-reason.create',compact('reason'));
    }

    public function update(Request $request){

    }

    public function destroy($id){

        $reason = DeclineReason::find($id);
        $reason->delete();
        return redirect()->back()->with('delete', 'Reason Deleted successfully');

    }
}
