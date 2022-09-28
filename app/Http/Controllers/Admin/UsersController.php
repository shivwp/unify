<?php

namespace App\Http\Controllers\Admin;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectSkill;
use App\Models\Role;
use App\Models\User;
use App\Models\Client;
use App\Models\Freelancer;
use Carbon\Carbon;
use Validator;
use Gate;
use Hash;
use DB;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::orderBy('created_at','DESC')->paginate(10);

        if($request->search){
          
            $users = User::where('name', 'like', "%$request->search%")->paginate(10);
        }
        if($request->user_filter){
            if($request->user_filter=='Client'){
                $users = User::whereHas('roles', function($q){
                    $q->where('title', '=', 'Client');})->paginate(10);
            }
            if($request->user_filter=='Freelancer'){
                $users = User::whereHas('roles', function($q){
                    $q->where('title', '=', 'Freelancer');})->paginate(10);
            }
        }
        
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        //validation
        $validator = Validator::make($request->all(), [
            'email' => 'unique:users,email|email',
            'password' => 'min:8',
        ]);
       
        if ($validator->fails()) {  
            $error = $validator->errors()->first();
            return redirect()->back()->with('error',$error);   
        } 

        // entry in user table 
        $user = new User;
        $user->name = $request->first_name.' '.$request->last_name;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->email_verified_at =  Carbon::now()->toDateTimeString();
        $user->status = $request->status;
        $user->referal_code = isset($request->referal_code) ? $request->referal_code : null;
        if(!empty($request->file('profile_image'))){
            $file = $request->file('profile_image');
            $name =$file->getClientOriginalName();
            $destinationPath = 'profile-image';
            $file->move($destinationPath, $name);
            $user->profile_image = $name;
        }
        $user->save();
        $user_role = $user->roles()->sync($request->roles);

        //entry in client table
        if($request->roles == '3'){
            $client = new Client;
            $client->user_id = $user->id;
            $client->save();
        }

        //entry in freelancer table
        if($request->roles == '2'){
            $freelancer = new Freelancer;
            $freelancer->user_id = $user->id;
            $freelancer->save();
        }
        session()->flash('success','User Created successfully');
        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');
        $user->load('roles');

        return view('admin.users.edit', compact('roles', 'user'));
    }

    public function update(Request $request)
    {
        // dd($request);
        $user = User::where('id',$request->user_id)->first();
        $user->name = $request->first_name.' '.$request->last_name;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->status = $request->status;
        $user->referal_code = isset($request->referal_code) ? $request->referal_code : null;
        if(!empty($request->password)){
            $user->password = Hash::make($request->password);
        }
        if(!empty($request->file('profile_image'))){
            $file = $request->file('profile_image');
            $name =$file->getClientOriginalName();
            $destinationPath = 'profile-image';
            $file->move($destinationPath, $name);
            $user->profile_image = $name;
        }
        $user->save();

        session()->flash('success','User Update successfully');
        return redirect()->back();
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles');

        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user->delete();
        Client::where('user_id',$user->id)->delete();
        Freelancer::where('user_id',$user->id)->delete();

        session()->flash('success','User Deleted successfully');
        return redirect()->back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function statusupdate(Request $request, $id){
        $data = User::where('id',$id)->first();
        $status =  $data->status;
        if($status == 'approve'){
            $data->status = 'pending';
        }else{
            $data->status = 'approve';
        }
        $data->save();

        session()->flash('success','User Status Update successfully');
        return redirect()->back();
    }
}
