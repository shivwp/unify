<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use App\ClientStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyClientRequest;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Gate;
use App\Project;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use DB;
class ClientController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('client_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clients =DB::table('users')
        ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
        ->where('role_user.role_id', '=', 2)
        ->where('users.deleted_at','=',null)->paginate(10);

        return view('admin.clients.index', compact('clients'));
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

        $clients =DB::table('users')
        ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
        ->where('role_user.role_id', '=', 2)
        ->where('users.deleted_at','=',null)
        ->where('users.id',$id)->first();
        $Projects=Project::where('client_id',$id)->get();
     

        return view('admin.clients.show',compact('clients','Projects'));
    }

    public function destroy(Client $client)
    {
        abort_if(Gate::denies('client_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $client->delete();

        return back();
    }

    public function massDestroy(MassDestroyClientRequest $request)
    {
        Client::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
