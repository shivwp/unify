<?php

namespace App\Http\Controllers\Admin;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SpecializeProfile;
use Illuminate\Support\Str;
use Validator;

class SpecializationController extends Controller
{
    public function index(Request $request)
    {
        $q = SpecializeProfile::query();
    
        $d['pagination']='10';
        if($request->search){
          
            $q->where('title', 'like', "%$request->search%")->paginate(10);
        }
        $d['specialization']=$q->paginate($d['pagination']);

        return view('admin.specialization.index', $d);
    }

    public function create()
    {
        return view('admin.specialization.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required | string',
            'description' => 'required | string',
        ]);

        if(!isset($request->id)){
            if(SpecializeProfile::where('title', '=', $request->title)->exists())
            {
                session()->flash('error', 'Specialize Profile Already Exists!');
                return redirect()->back();
            }
        }
        $data = SpecializeProfile::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'title' => $request->title,
                'description' => $request->description,
            ]
        );

        $result = $data->update();

        if($result)
        {
            if($request->id)
            {
                session()->flash('success','Specialize Profile Updated successfully');
                return redirect()->route('admin.specialization.index');
            }
            else
            {
                session()->flash('success','Specialize Profile Created successfully');
                return redirect()->route('admin.specialization.index');
            }
        }
        else
        {
            session()->flash('error', 'Something went Wrong, Please try again!');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data['specialization'] = SpecializeProfile::where('id', $id)->first();
        return view('admin.specialization.create', $data);
    }

    public function update(Request $request)
    {
        
    }

    public function show($id)
    {
       
    }

    public function destroy($id)
    {
        $specialization = SpecializeProfile::find($id);
        $result = $specialization->delete();
        if($result)
        {
            return back()->with('success', 'Specialize Profile Deleted Successfully');    
        }
        else
        {
            return back()->with('error', 'Something went Wrong, Please try again!');
        }
    }
   
}
