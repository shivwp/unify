<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Role;
use App\User;
use Gate;
use DB;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::paginate(10);

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

    public function store(StoreUserRequest $request)
    {
        //$user = User::create($request->all());

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->email_verified_at =  Carbon::now()->toDateTimeString();
        $user->status = $request->status;
        $user->email = $request->email;

        if(!empty($request->file('profileimage'))){
            $file = $request->file('profileimage');
            $name =$file->getClientOriginalName();
            $destinationPath = 'profileimage';
            $file->move($destinationPath, $name);
            $user->profileimage = $name;
        }
        $user->save();

        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');

        $user->load('roles');

        return view('admin.users.edit', compact('roles', 'user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
       
        //$user->update($request->all());

        $user = User::where('id',$user->id)->first();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->email = $request->email;

        if(!empty($request->file('profileimage'))){
            $file = $request->file('profileimage');
            $name =$file->getClientOriginalName();
            $destinationPath = 'profileimage';
            $file->move($destinationPath, $name);
            $user->profileimage = $name;
        }
        $user->save();



        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('admin.users.index');
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

        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function statusupdate(Request $request, $id){
        $data = User::where('id',$id)->first();
        $status =  $data->status;
        if($status == 'accept'){
            $data->status = 'decline';
        }else{
            $data->status = 'accept';
        }
        $data->save();
        return redirect()->back();
    }
}
