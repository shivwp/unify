<?php

namespace App\Http\Controllers\Admin;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Industries;
use Validator;

class IndustryController extends Controller
{
    public function index(Request $request)
    {
        $q = Industries::query();
    
        if(!empty($request->pagination)){
            $n = $request->pagination;
        }
        else{
            $n = 10;
        }

        $d['pagination']= $n;
        if($request->search){
          
            $q->where('title', 'like', "%$request->search%")->paginate(10);
        }
        $d['industry']=$q->orderBy('title', 'ASC')->paginate($d['pagination']);

        return view('admin.industry.index', $d);
    }

    public function create()
    {
        return view('admin.industry.create');
    }

    public function store(Request $request)
    { 
        //validation
        $validator = Validator::make($request->all(), [
            'title' => 'unique:industries,title',
        ]);
       
        if ($validator->fails()) {  
            $error = $validator->errors()->first();
            return redirect()->back()->with('error',$error);   
        } 

        $indust = new Industries;
        $indust->title=$request->title;
        $indust->description=$request->description;
        $indust->save();

        session()->flash('success','New Industry Created successfully');
        return redirect()->route('admin.industry.index');
    }

    public function edit($id)
    { 
        $d['industry'] =Industries::where('id',$id)->first();
        return view('admin.industry.edit',$d);
    }

    public function update(Request $request)
    {
        //validation
        $validator = Validator::make($request->all(), [
            'title' => 'unique:industries,title',
        ]);
       
        if ($validator->fails()) {  
            $error = $validator->errors()->first();
            return redirect()->back()->with('error',$error);   
        } 

        $indust = Industries::where('id',$request->id)->first();
        $indust->title=$request->title;
        $indust->description=$request->description;
        $indust->save();

        session()->flash('success',' Industry Update successfully');
        return redirect()->route('admin.industry.index');
    }

    public function show($id)
    {
       
    }

    public function destroy($id)
    {
        $industry= Industries::where('id', $id)->first();
        $industry->delete();
        session()->flash('success','Deleted successfully');
        return back();
    }
   
}
