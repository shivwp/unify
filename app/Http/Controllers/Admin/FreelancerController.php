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
        if($request->items){
            $d['pagination'] = $request->items;
        }
        else{
            $d['pagination'] = 10;
        }

        $q =DB::table('users')
            ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->where('role_user.role_id', '=', 2)
            ->where('users.deleted_at','=',null)
            ->orderBy('users.id','DESC');

        if($request->keyword){
            $d['search'] = $request->keyword;

            $q->where(function($query) use ($d){
                $query->where('users.name', 'like', '%'.$d['search'].'%')
                    ->orwhere('users.email', 'like', '%'.$d['search'].'%');
            });

        }

        $d['freelance']=$q->paginate($d['pagination'])->withQueryString();

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
        if(!empty($lang_data)){
            $d['languages'] = json_decode($lang_data['language']);
        }

        $work_time = $this->getFreelancerMeta($id, 'hours_per_week');
        if(!empty($work_time)){
            $d['work_time'] = $work_time['hours_per_week'];
        }

        $video_data = $this->getFreelancerMeta($id, 'freelancer_video');
        if(!empty($video_data)){
            $d['video_link'] = $video_data['freelancer_video'];
        }

        $video_type = $this->getFreelancerMeta($id, 'freelancer_video_type');
        if(!empty($video_type)){
            $d['video_type'] = $video_type['freelancer_video_type'];
        }

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
