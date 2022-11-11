<?php

namespace App\Http\Controllers\Admin;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Degree_list;
use Illuminate\Support\Str;
use Validator;

class DegreeController extends Controller
{
    public function index(Request $request)
    {
        $q = Degree_list::query();
    
        $d['pagination']='10';
        if($request->search){
          
            $q->where('title', 'like', "%$request->search%")->paginate(10);
        }
        $d['degree']=$q->paginate($d['pagination']);

        return view('admin.degree.index', $d);
    }

    public function create()
    {
        return view('admin.degree.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required | string',
        ]);

        if(!isset($request->id)){
            if(Degree_list::where('title', '=', $request->title)->exists())
            {
                session()->flash('error', 'Degree Already Exists!');
                return redirect()->back();
            }
        }
        $data = Degree_list::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'title' => $request->title,
            ]
        );

        $result = $data->update();

        if($result)
        {
            if($request->id)
            {
                session()->flash('success','Degree Updated successfully');
                return redirect()->route('admin.degree.index');
            }
            else
            {
                session()->flash('success','Degree Created successfully');
                return redirect()->route('admin.degree.index');
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
        $data['degree'] = Degree_list::where('id', $id)->first();
        return view('admin.degree.create', $data);
    }

    public function update(Request $request)
    {
        
    }

    public function show($id)
    {
       
    }

    public function destroy($id)
    {
        $degree = Degree_list::find($id);
        $result = $degree->delete();
        if($result)
        {
            return back()->with('success', 'Degree Deleted Successfully');    
        }
        else
        {
            return back()->with('error', 'Something went Wrong, Please try again!');
        }
    }
   
}
