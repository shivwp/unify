<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountCloseReason;
use Illuminate\Http\Request;

class CloseReasonController extends Controller
{
    public function index(Request $request){

        $reason = AccountCloseReason::orderBy('created_at','DESC')->select('*');

        if($request->search){
          
            $reason->where('name', 'like', "%$request->search%");
        }
        $d['reason'] = $reason->paginate('10')->withQueryString();
        return view('admin.close-reason.index',$d);
    }

    public function create(){

        return view('admin.close-reason.create');
    }

    public function store(Request $request){
        // dd($request);
        $reason = AccountCloseReason::create($request->all());
        session()->flash('success','Create successfully');
        return redirect()->route('admin.close-reason.index');
    }

    public function edit($id){

        $d['reason'] = AccountCloseReason::where('id',$id)->first();
        return view('admin.close-reason.edit',$d);
    }

    public function update(Request $request){

        $reason = AccountCloseReason::where('id',$request->id)->first();
        $reason->title = $request->title;
        $reason->description = $request->description;
        $reason->save();

        session()->flash('success',' Update successfully');
        return redirect()->route('admin.close-reason.index');
    }

    public function destroy($id){

        AccountCloseReason::where('id',$id)->delete();
        session()->flash('success',' Deleted successfully');
        return redirect()->back();

    }
}
