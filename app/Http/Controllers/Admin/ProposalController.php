<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\SiteSetting;
use App\Services;
use App\Project;
use App\ProjectStatus;
use App\ProjectCategory;
use App\ProjectListingType;
use App\ProjectSkill;
use App\ProjectProjectSkill;
use App\ProjectProjectCategory;
use App\ProjectProjectListingType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Intervention\Image\ImageManagerStatic as Image;
use DateTime;
use App\User;
use DB;
use App\Project_proposals;
class ProposalController extends Controller
{
    public function index(Request $request)
    {
        
    
       $q =Project_proposals::query();
       $d['pagination']='10';
       if($request->pagination){
        $d['pagination']=$request->pagination;
    }
        $d['project']=Project::all();
        $d['freelancer']= DB::table('users')
        ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
        ->where('role_user.role_id', '=', 2)
        ->where('users.deleted_at','=',null)->get();
        if($request->freelancer){
          $q->where('freelancer_id',$request->freelancer);
        }

        if($request->project){
          $q->where('project_id',$request->project);
        }
        
        $d['proposal']=$q->paginate( $d['pagination']);
        return view('admin.proposals.index', $d);
    }

    public function create()
    {
        abort_if(Gate::denies('project_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $servicefee = SiteSetting::where('name','servicefee')->first();
        $freelancer=DB::table('users')
        ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
        ->where('role_user.role_id', '=', 2)
        ->where('users.deleted_at','=',null)->get();

        $Project=Project::all();
      
         
        return view('admin.proposals.create',compact('Project','servicefee','freelancer'));
    }

    public function store(Request $request)
    {
        if ($request->hasfile('image')) {
            $file1 = $request->file('image');
            $image_name = [];
            foreach ($file1 as $image) {
              $name = $image->getClientOriginalName();
              $filename = time() . '_' . $name;
              $image_resize = Image::make($image->getRealPath());
              $image_resize->save('project-files/' . $filename);
              $image_name[] =  $filename;
            }
          }
          $result = [];
          $varimg = json_decode($request->image1);
  
          if (!empty($image_name) && !empty($varimg)) {
  
            $result = array_merge($image_name, $varimg);
          } else if (!empty($image_name)) {
  
            $result = $image_name;
          } else {
            $result = $varimg;
          }
        $Proposal=new Project_proposals;
        $Proposal->project_id=$request->project_id;
        $Proposal->freelancer_id=$request->freelancer;
        $Proposal->amount=$request->amount;
        $Proposal->status=$request->status;
        $Proposal->description=$request->description;
        $Proposal->images=json_encode($result);
        $Proposal->save();
       

        return redirect()->route('admin.proposal.index');
    }

    public function edit($id)
    {
      $proposals=Project_proposals::where('id',$id)->first();
     
      $servicefee = SiteSetting::where('name','servicefee')->first();
      $freelancer=DB::table('users')
      ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
      ->where('role_user.role_id', '=', 2)
      ->where('users.deleted_at','=',null)->get();

      $Project=Project::all();

  
     
        return view('admin.proposals.edit',compact('servicefee','freelancer','Project','proposals'));
    }

    public function update(Request $request, $project)
    {
      if ($request->hasfile('image')) {
        $file1 = $request->file('image');
        $image_name = [];
        foreach ($file1 as $image) {
          $name = $image->getClientOriginalName();
          $filename = time() . '_' . $name;
          $image_resize = Image::make($image->getRealPath());
          $image_resize->save('project-files/' . $filename);
          $image_name[] =  $filename;
        }
      }
      $result = [];
      $varimg = json_decode($request->image1);

      if (!empty($image_name) && !empty($varimg)) {

        $result = array_merge($image_name, $varimg);
      } else if (!empty($image_name)) {

        $result = $image_name;
      } else {
        $result = $varimg;
      }
        $Proposal=Project_proposals::where('id',$project)->first();
       
        // $Proposal->project_id=$request->project_id;
        // $Proposal->freelancer_id=$request->freelancer;
        $Proposal->amount=$request->amount;
        $Proposal->status=$request->status;
        $Proposal->description=$request->description;
        $Proposal->images=json_encode($result);
        $Proposal->save();
        return redirect()->route('admin.proposal.index');
    }

    public function show($id)
    {
    
      $Proposal=Project_proposals::where('id',$id)->first();
        abort_if(Gate::denies('project_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    

        return view('admin.proposals.show', compact('Proposal'));
    }

    public function destroy(Request $id)
    { abort_if(Gate::denies('project_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
       
     
        $service=Project_proposals::where('id',$id->id)->first();
        $service->delete(); 

        return back();
    }

    public function massDestroy(MassDestroyProjectRequest $request)
    {
        Service::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
