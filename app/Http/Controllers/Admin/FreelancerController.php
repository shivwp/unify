<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Freelancer;
use App\Models\FreelancerMeta;
use App\Models\ClientStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyClientRequest;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Gate;
use App\Models\Project;
use App\Models\Role;
use App\Models\ProjectSkill;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use DB;


class FreelancerController extends Controller
{
    public function index(Request $request)
    {
        $pagination=10;
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
        $q =DB::table('users')
        ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
        ->where('role_user.role_id', '=', 2)
        ->where('users.deleted_at','=',null)
        ->orderBy('users.id','DESC');

        if($request->search){
            $q->where('users.name', 'like', "%$request->search%");
        }
        $d['freelance']=$q->paginate($pagination)->withQueryString();
        return view('admin.freelancer.index',$d);
    }

    public function create()
    {
       
        return view('admin.freelancer.create');
    }

    public function store(StoreClientRequest $request)
    {

        return redirect()->route('admin.freelancer.index');
    }

    public function edit(User $freelancer)
    { 
        
        return view('admin.freelancer.edit');
    }

    public function update(UpdateClientRequest $request, Client $client)
    {

        return redirect()->route('admin.freelancer.index');
    }

    public function show($id)
    {
        $d['f_data'] = $this->getFreelancerInfo($id);
        $lang_data = $this->getFreelancerMeta($id,'language');
        $d['languages'] = json_decode($lang_data['language']);
        return view('admin.freelancer.show',$d);
    }

    public function destroy(User $freelancer)
    {
        Freelancer::where('user_id',$freelancer->id)->delete();
        User::where('id',$freelancer->id)->delete();
        
        session()->flash('success','User Deleted successfully');
        return back();
    }

}
