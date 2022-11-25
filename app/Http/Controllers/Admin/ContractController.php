<?php

namespace App\Http\Controllers\Admin;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contracts;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Str;
use DB;
use Validator;
use App\Models\SendProposal;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        $q = SendProposal::query();
    
        $d['pagination']='10';
        if($request->search){
          
            $q->where('project_title', 'like', "%$request->search%")->paginate(10);
        }

        $q->join('projects', 'send_proposals.project_id', '=', 'projects.id')->select('send_proposals.*', 'projects.name as project_name');

        $q->where('send_proposals.type', 'offer');

        $d['contracts']=$q->paginate($d['pagination']);

        // print_r($d['contracts']);
        return view('admin.contracts.index', $d);
    }

    public function create()
    {
        // $d['projects'] = Project::all();
        // // $d['user'] = User::where()get(['id','name','email']);

        // $d['clients'] = DB::table('users')
        // ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
        // ->where('role_user.role_id', '=', 3)
        // ->where('users.deleted_at', '=', null)
        // ->get(['id','name','first_name','email']);

        // $d['freelancers'] =DB::table('users')
        // ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
        // ->where('role_user.role_id', '=', 2)
        // ->where('users.deleted_at', '=', null)
        // ->get(['id','name','first_name','email']);

        // return view('admin.contracts.create',$d);
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'name' => 'required',
        //     'freelancer_name' => 'required',
        //     'client_name' => 'required',
        //     'project_type' => 'required | string',
        //     'status' => 'required | string',
        // ]);
        // if($request->project_type == 'project'){
        //     $request->validate([
        //         'bid_amount' => 'required'
        //     ]);
        // }
        // if($request->project_type == 'milestone'){
        //     $request->validate([
        //         'description' => 'required',
        //         'due_date' => 'required',
        //         'milestone_amount' => 'required'
        //     ]);
        // }
        // dd($request->all());

        // // if(!isset($request->id)){
        // //     if(Contracts::where('name', '=', $request->title)->exists())
        // //     {
        // //         session()->flash('error', 'Certificate Already Exists!');
        // //         return redirect()->back();
        // //     }
        // // }
        // $project_name = Project::where('id', $request->name)->first('name');
        
        // $data = Contracts::updateOrCreate(
        //     [
        //         'id' => $request->id,
        //     ],
        //     [
        //         'project_id' => $request->name,
        //         'project_title' => $project_name->name,
        //         'freelancer_id' => $request->freelancer_name,
        //         'client_id' => $request->client_name,
        //         'type' => $request->project_type,
        //         'amount' => $request->amount,
        //         'start_time' => $request->start_time,
        //         'end_time' => $request->end_time,
        //         'status' => $request->status,
        //     ]
        // );

        // $result = $data->update();

        // if($result)
        // {
        //     if($request->id)
        //     {
        //         session()->flash('success','Contract Updated successfully');
        //         return redirect()->route('admin.contracts.index');
        //     }
        //     else
        //     {
        //         session()->flash('success','Contract Created successfully');
        //         return redirect()->route('admin.contracts.index');
        //     }
        // }
        // else
        // {
        //     session()->flash('error', 'Something went Wrong, Please try again!');
        //     return redirect()->back();
        // }
    }

    public function edit($id)
    {
        // $data['certificate'] = Contracts::where('id', $id)->first();
        // return view('admin.contracts.create', $data);
    }

    public function update(Request $request)
    {
        
    }

    public function show($id)
    {
        $d['contract'] = SendProposal::where('id', $id)->with(['client', 'users', 'projects'])->first();

        return view('admin.contracts.show',$d);
    }

    public function destroy($id)
    {
        $contract = SendProposal::find($id);
        $result = $contract->delete();
        if($result)
        {
            return back()->with('success', 'Contract Deleted Successfully');    
        }
        else
        {
            return back()->with('error', 'Something went Wrong, Please try again!');
        }
    }
   
}
