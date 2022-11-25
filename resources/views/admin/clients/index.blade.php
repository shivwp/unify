@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
                 <div class="row tabelhed d-flex justify-content-between">
                    <div class="col-lg-2 col-md-2 col-sm-2 d-flex">
                        @can('user_create')
                        <a class="btn-sm ad-btn create_btn text-center pt-2" href="{{ route("admin.users.create") }}"> Add</a>
                        @endcan
                        <button id="btnExport" onClick="fnExcelReport()" class="btn-sm ad-btn clearfix">Excel</button>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-5 "> 
                        <?php 
                        if(!empty($_GET['search'])){
                            $search= $_GET['search'];
                        }else{ 
                            $search='';
                        }?>
                        <div class="right-item" >
                            <form action="" class="d-flex" method="get">
                                <input type="text" name="search" class="form-control" value="{{$search}}" style="height: 39px;" placeholder="Search..." required>
                                <button class="btn-sm search-btn" type="submit"  style="margin-left:6px"> <i class="fa fa-search pl-3" aria-hidden="true"></i> </button>
                                <a href="{{url('admin/clients')}}">
                                    <i class="fa fa-refresh pl-3 redirect-icon" aria-hidden="true"></i>
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
                @if(Session::has('error'))
                    <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error') }}</p>
                @endif
                @if(Session::has('success'))
                    <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success') }}</p>
                @endif
                <div class="card">
                    <div class="card-header">
                        {{ trans('cruds.client.title_singular') }} {{ trans('global.list') }}
                    </div>
                    <div class="card-body">
                        <div class="table">
                            <table class=" table table-bordered table-striped table-hover datatable datatable-Client example">
                                <thead>
                                    <tr>
                                        <th>S No.</th>
                                        <th>Name</th>
                                        <th>email</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i=1
                                    @endphp
                                    @foreach($clients as $key => $client)
                                    <tr data-entry-id="{{ $client->id }}">
                                        <td>{{  $client->id ?? ''}}</td>
                                        <td>{{ $client->name ?? '' }}</td>
                                        <td>{{ $client->email ?? '' }}</td>
                                        <td>
                                             @if($client->status == 'pending')
                                                <a href="{{url('admin/user/statusupdate',$client->id)}}"><span class="btn btn-xs btn-danger text-capitalize">{{ $client->status ?? '' }}</span></a>
                                                @else
                                                <a href="{{url('admin/user/statusupdate',$client->id)}}"><span class="btn btn-xs btn-info text-capitalize">{{ $client->status ?? '' }}</span></a>
                                                @endif
                                        </td>
                                        <td>
                                            @can('client_show')
                                            <a href="{{ route('admin.clients.show', $client->user_id) }}">
                                                <button class="btn btn-sm btn-icon me-2"><i class="bx bx-show mx-1" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>View</span>"></i></button>
                                            </a> 
                                            @endcan 
                                            @can('client_edit')
                                            <a class="btn btn-xs"
                                                href="{{ route('admin.users.edit', $client->user_id) }}">
                                                <button class="btn btn-sm btn-icon me-2" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="" data-bs-original-title=" <span>Edit</span>" aria-label=" <span>Edit</span>"><i class="bx bx-edit"></i></button>
                                            </a> 
                                            @endcan 
                                            @can('client_delete')
                                            <form action="{{ route('admin.clients.destroy', $client->user_id) }}" method="POST" style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <button type="submit" class="btn btn-sm btn-icon delete-record"><i class="bx bx-trash" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Delete</span>"></i></button>
                                            </form>
                                            @endcan
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $clients->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->

<!-- Footer -->



@endsection