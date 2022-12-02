<?php

namespace App\Http\Controllers\Admin;

use App\Models\Client;
use App\Models\Freelancer;
use App\Models\ClientStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyClientRequest;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Gate;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use DB;
class ClientController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('client_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if($request->items){
            $d['pagination'] = $request->items;
        }
        else{
            $d['pagination'] = 10;
        }

        $q =DB::table('users')
            ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->where('role_user.role_id', '=', 3)
            ->where('users.deleted_at','=',null)
            ->orderBy('users.id','DESC');

        if($request->keyword){
            $d['search'] = $request->keyword;

            $q->where(function($query) use ($d){
                $query->where('users.name', 'like', '%'.$d['search'].'%')
                    ->orwhere('users.email', 'like', '%'.$d['search'].'%');
            });

        }

        $d['clients']=$q->paginate($d['pagination'])->withQueryString();

        return view('admin.clients.index', $d);
    }

    public function create()
    {
        abort_if(Gate::denies('client_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statuses = ClientStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.clients.create', compact('statuses'));
    }

    public function store(StoreClientRequest $request)
    {
        $client = Client::create($request->all());

        return redirect()->route('admin.clients.index');
    }

    public function edit(Client $client)
    { 
        
        abort_if(Gate::denies('client_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        
        
        $statuses = ClientStatus::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $client->load('status');

        return view('admin.clients.edit', compact('statuses', 'client'));
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        $client->update($request->all());

        return redirect()->route('admin.clients.index');
    }

    public function show($id)
    {
        abort_if(Gate::denies('client_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $clients = User::with('client')->where('users.id',$id)->first();
        return view('admin.clients.show',compact('clients'));
    }

    public function destroy(User $client)
    {
        abort_if(Gate::denies('client_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        Client::where('user_id', $client->id)->delete();
        User::where('id',$client->id)->delete();

        session()->flash('success','User Deleted successfully');
        return redirect()->back();
    }

    public function massDestroy(MassDestroyClientRequest $request)
    {
        Client::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
