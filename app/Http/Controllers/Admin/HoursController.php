<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HoursPerWeek;
use Illuminate\Http\Request;

class HoursController extends Controller
{
    public function index(Request $request){

        $hours = HoursPerWeek::orderBy('created_at','DESC')->select('*');

        if($request->search){
          
            $hours->where('title', 'like', "%$request->search%");
        }
        $d['hours'] = $hours->paginate('10')->withQueryString();
        return view('admin.hoursPerWeek.index',$d);
    }

    public function create(){

        return view('admin.hoursPerWeek.create');
    }

    public function store(Request $request){
        // dd($request);
        $hours = HoursPerWeek::create($request->all());
        session()->flash('success','Create successfully');
        return redirect()->route('admin.hours-per-week.index');
    }

    public function edit($id){

        $d['hours'] = HoursPerWeek::where('id',$id)->first();
        return view('admin.hoursPerWeek.edit',$d);
    }

    public function update(Request $request){

        $hours = HoursPerWeek::where('id',$request->id)->first();
        $hours->title = $request->title;
        $hours->description = $request->description;
        $hours->save();

        session()->flash('success',' Update successfully');
        return redirect()->route('admin.hours-per-week.index');
    }

    public function destroy($id){

        HoursPerWeek::where('id',$id)->delete();
        session()->flash('success',' Deleted successfully');
        return redirect()->back();

    }
}
