<?php

namespace App\Http\Controllers\Admin;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Certificate;
use Illuminate\Support\Str;
use Validator;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $q = Certificate::query();
    
        $d['pagination']='10';
        if($request->search){
          
            $q->where('name', 'like', "%$request->search%")->paginate(10);
        }
        $d['certificate']=$q->paginate($d['pagination']);

        return view('admin.certificate.index', $d);
    }

    public function create()
    {
        return view('admin.certificate.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required | string',
        ]);

        if(!isset($request->id)){
            if(Certificate::where('name', '=', $request->title)->exists())
            {
                session()->flash('error', 'Certificate Already Exists!');
                return redirect()->back();
            }
        }
        $data = Certificate::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'name' => $request->title,
            ]
        );

        $result = $data->update();

        if($result)
        {
            if($request->id)
            {
                session()->flash('success','Certificate Updated successfully');
                return redirect()->route('admin.certificate.index');
            }
            else
            {
                session()->flash('success','Certificate Created successfully');
                return redirect()->route('admin.certificate.index');
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
        $data['certificate'] = Certificate::where('id', $id)->first();
        return view('admin.certificate.create', $data);
    }

    public function update(Request $request)
    {
        
    }

    public function show($id)
    {
       
    }

    public function destroy($id)
    {
        $certificate = Certificate::find($id);
        $result = $certificate->delete();
        if($result)
        {
            return back()->with('success', 'Certificate Deleted Successfully');    
        }
        else
        {
            return back()->with('error', 'Something went Wrong, Please try again!');
        }
    }
   
}
