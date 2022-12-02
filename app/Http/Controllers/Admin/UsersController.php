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
use App\Models\UserDocument;
use App\Models\Client;
use App\Models\Freelancer;
use App\Models\SocialAccount;
use App\Models\Agency;
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

        $q = User::query();

        if($request->keyword){
            $d['search'] = $request->keyword;

            $q->where(function($query) use ($d){
                $query->where('name', 'like', '%'.$d['search'].'%')
                    ->orwhere('email', 'like', '%'.$d['search'].'%');
            });
        }

        if($request->role){
            $d['role'] = $request->role;
            $q->whereHas('roles', function($query) use ($d){
                    $query->where('title', '=', $d['role']);});
        }

        if($request->status){
            $d['status'] = $request->status;
            $q->where('status', '=', $d['status']);
        }

        if($request->items){
            $d['pagination'] = $request->items;
        }
        else{
            $d['pagination'] = 10;
        }

        $d['users'] = $q->orderBy('created_at','DESC')->paginate($d['pagination']);
        
        return view('admin.users.index', $d);
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'unique:users,email|email',
            'password' => 'min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[\d\x])(?=.*[!$#%@]).*$/',
        ]);

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
            $name = time().'-'.$file->getClientOriginalName();
            $destinationPath = 'images/profile-image';
            $file->move($destinationPath, $name);
            $user->profile_image = $name;
        }

        $user->save();
        $user_role = $user->roles()->sync($request->roles);

        foreach($request->roles as $role)
        {
           //entry in client table
            if($role == '3'){
                $client = new Client;
                $client->user_id = $user->id;
                $client->save();
            }

            //entry in freelancer table
            if($role == '2'){
                $freelancer = new Freelancer;
                $freelancer->user_id = $user->id;
                $freelancer->save();
            } 

            //entry in agencies table
            if($role == '4'){
                $agency = new Agency;
                $agency->user_id = $user->id;
                $agency->save();
            } 
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
        if(!empty($request->password))
        {
            $request->validate([
                'password' => 'min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[\d\x])(?=.*[!$#%@]).*$/',
            ]);
        }

        $user = User::where('id',$request->user_id)->first();
        $user->name = $request->first_name.' '.$request->last_name;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->status = $request->status;
        $user->is_verified = $request->is_verified;
        $user->referal_code = isset($request->referal_code) ? $request->referal_code : null;

        if(!empty($request->password)){
            $user->password = Hash::make($request->password);
        }

        if(!empty($request->file('profile_image'))){
            $file = $request->file('profile_image');
            $name =time().'-'.$file->getClientOriginalName();
            $destinationPath = 'images/profile-image';
            $file->move($destinationPath, $name);
            $user->profile_image = $name;
        }

        $user->save();

        $all_roles = DB::table('role_user')->where('user_id',$request->user_id)->get();

        $role = $request->roles;

        foreach ($role as $value) {
            $roles[]= $value;
        }

        foreach ($all_roles as $value) {
            if(!in_array($value->role_id, $roles)){

                //entry in client table
                if($value->role_id == '3'){
                    Client::where('user_id',$request->user_id)->delete();
                }

                //entry in freelancer table
                if($value->role_id == '2'){
                    Freelancer::where('user_id',$request->user_id)->delete();
                } 

                //entry in agencies table
                if($value->role_id == '4'){
                    Agency::where('user_id',$request->user_id)->delete();
                } 
            }
        }

        $user_role = $user->roles()->sync($request->roles);

        foreach($request->roles as $role)
        {
            //entry in client table
            if($role == '3'){
                $client = Client::firstOrCreate(
                    ['user_id' => $request->user_id],
                    ['user_id' => $request->user_id]
                );
            }

            //entry in freelancer table
            if($role == '2'){
                $freelancer = Freelancer::firstOrCreate(
                    ['user_id' => $request->user_id],
                    ['user_id' => $request->user_id]
                );
            } 

            //entry in agencies table
            if($role == '4'){
                $agency = Agency::firstOrCreate(
                    ['user_id' => $request->user_id],
                    ['user_id' => $request->user_id]
                );
            } 
        }

        session()->flash('success','User Update successfully');
        return redirect()->back();
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles');
        $document = UserDocument::where('user_id',$user->id)->first();
        
        if($user->social_id !== 'NULL'){
            $p = DB::table('social_accounts')->where('provider_user_id','=', $user->social_id)->select('provider')->first();
        }

        return view('admin.users.show', compact('user','document','p'));
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

    // public function massDestroy(MassDestroyUserRequest $request)
    // {
    //     User::whereIn('id', request('ids'))->delete();

    //     return response(null, Response::HTTP_NO_CONTENT);
    // }
    public function statusupdate(Request $request, $id)
    {

        $data = User::where('id',$id)->first();

        $status =  $data->status;

        if($status == 'approve'){
            $data->status = 'pending';
        }
        else{
            $data->status = 'approve';
        }

        $data->save();

        session()->flash('success','User Status Update successfully');
        return redirect()->back();
    }
    public function statusBlock(Request $request, $id)
    {
       
        $data = User::where('id',$id)->first();

        $status =  $data->status;

        if($status == 'approve'){
            $data->status = 'reject';
            $msg = "User Blocked successfully";
        }
        else{
            $data->status = 'approve';
            $msg = "User Unblocked successfully";
        }

        $data->save();
        return redirect()->back()->with('block', $msg);
    }
    public function usersRefrals(Request $request){
        
        if($request->items){
            $d['pagination'] = $request->items;
        }
        else{
            $d['pagination'] = 10;
        }

        $referalData = DB::table('user_referals')
                ->join('users as ref_by','user_referals.refered_user_id','=','ref_by.id')
                ->join('users as ref_to','user_referals.user_id','=','ref_to.id')
                ->select('ref_by.referal_code', 'ref_by.name as refer_name', 'ref_to.name as refer_to_name','ref_to.created_at');
        
        if($request->keyword){
            $d['search'] = $request->keyword;

            $referalData->where(function($query) use ($d){
                $query->where('ref_to.name','LIKE','%'.$d['search'].'%')
                ->orwhere('ref_by.name','LIKE','%'.$d['search'].'%');
            });
            // $referalData->where('ref_to.name','LIKE','%'.$d['search'].'%');
        }
        
        $d['data'] =$referalData->paginate($d['pagination']);

        return view('admin.users.indexrefrals',$d);
    }
}
