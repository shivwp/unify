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
                        <!-- <button id="btnExport" onClick="fnExcelReport()" class="btn-sm ad-btn clearfix">Excel</button> -->
                    </div>

                    <div class="col-lg-10 col-md-10"> 

                        <div class="right-item d-flex justify-content-end" >

                            <div class="p-0">
                                <select class="form-control" id="user_filter" name="user_filter">
                                    <option value="">Select Role</option>
                                    <option value="Agency" {{ isset($role) ? ($role == "Agency" ? 'selected' : '' ) : '' }}>Agency</option>
                                    <option value="Client" {{ isset($role) ? ($role == "Client" ? 'selected' : '' ) : '' }}>Client</option>
                                    <option value="Freelancer" {{ isset($role) ? ($role == "Freelancer" ? 'selected' : '' ) : '' }}>Freelancer</option>
                                </select>
                            </div>

                            <div class="p-0 mx-1">
                                <select class="form-control" id="user_status_filter" name="user_status_filter">
                                    <option value="">Select Status</option>
                                    <option value="pending" {{ isset($status) ? ($status == "pending" ? 'selected' : '' ) : '' }} >Pending</option>
                                    <option value="approve" {{ isset($status) ? ($status == "approve" ? 'selected' : '' ) : '' }} >Approve</option>
                                    <option value="reject" {{ isset($status) ? ($status == "reject" ? 'selected' : '' ) : '' }} >Rejected</option>
                                </select>
                            </div>

                            <div class="d-flex">
                                <input type="text" name="search" id="search_field" class="form-control" value="{{ isset($search) ? $search : '' }}" placeholder="Search User" required>

                                <button class="btn-sm search-btn" type="submit">
                                    <i class="fa fa-search pl-3" aria-hidden="true"></i>
                                </button>

                                <a href="{{url('admin/users')}}">
                                    <i class="fa fa-refresh pl-3 redirect-icon" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @if(Session::has('error'))
                    <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error') }}</p>
                @endif
                @if(Session::has('success'))
                    <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success') }}</p>
                @endif
                @if(Session::has('block'))
                    <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('block') }}</p>
                @endif

                <div class="card">
                    <div class="card-header ">
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <h5>Users List</h5>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="row float-end">
                                    <div class="col-xl-12 d-flex float-end">
                                        <div class="items paginatee">
                                            <select class="form-select m-0 items" name="pagination" id="pagination" aria-label="Default select example">
                                                <option value='10' {{ isset($pagination) ? ($pagination == '10' ? 'selected' : '' ) : '' }}>10</option>
                                                <option value='20' {{ isset($pagination) ? ($pagination == '20' ? 'selected' : '' ) : '' }}>20</option>
                                                <option value='30' {{ isset($pagination) ? ($pagination == '30' ? 'selected' : '' ) : '' }}>30</option>
                                                <option value='40' {{ isset($pagination) ? ($pagination == '40' ? 'selected' : '' ) : '' }}>40</option>
                                                <option value='50' {{ isset($pagination) ? ($pagination == '50' ? 'selected' : '' ) : '' }}>50</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table">
                            <table id="example" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S No.</th>
                                        <th>{{ trans('cruds.user.fields.name') }}</th>
                                        <th>{{ trans('cruds.user.fields.email') }}</th>
                                        <th>User Type</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i=1
                                    @endphp

                                    @if(count($users)>0)

                                        @foreach($users as $key => $user)
                                            <tr data-entry-id="{{ $user->id }}">
                                                <td>{{ $user->id ?? ''}}</td>
                                                <td>{{ $user->name ?? '' }}</td>
                                                <td>{{ $user->email ?? '' }}</td>
                                                <td>
                                                    @foreach($user->roles as $key => $item)
                                                        <span class="btn btn-xs btn-primary">{{ $item->title }}</span>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @if($user->status == 'pending')
                                                        <a href="{{url('admin/user/statusupdate',$user->id)}}"><span class="btn btn-xs btn-danger text-capitalize">{{ $user->status ?? '' }}</span></a>
                                                    @else
                                                        <a href="{{url('admin/user/statusupdate',$user->id)}}"><span class="btn btn-xs btn-info text-capitalize">{{ $user->status ?? '' }}</span></a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @can('user_show')
                                                        <a href="{{ route('admin.users.show', $user->id) }}">
                                                            <button class="btn btn-sm btn-icon me-2"><i class="bx bx-show mx-1" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>View</span>"></i></button>
                                                        </a>
                                                    @endcan
                    
                                                    @can('user_edit')
                                                        <a href="{{ route('admin.users.edit', $user->id) }}">
                                                            <button class="btn btn-sm btn-icon me-2"><i class="bx bx-edit" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Edit</span>"></i></button>
                                                        </a>
                                                    @endcan
                    
                                                    @can('user_delete')
                                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline-block;">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                            <button type="submit" class="btn btn-sm btn-icon delete-record"><i class="bx bx-trash" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Delete</span>"></i></button>
                                                            
                                                        </form>
                                                    @endcan

                                                    @if($user->status == 'approve' || $user->status == 'pending')
                                                        <a href="{{route('admin.users.block', $user->id)}}">
                                                            <button class="btn btn-sm btn-icon me-2"><i class="bx bx-lock" style="color: rgb(196, 45, 102)" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" aria-hidden="true" title="Block"></i></button>
                                                        </a>
                                                    @else
                                                        <a href="{{route('admin.users.block', $user->id)}}">
                                                            <button class="btn btn-sm btn-icon me-2"><i class="bx bx-lock" style="color: green" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Unblock</span>"></i></button>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                    @else
                                        <tr><td colspan="6">No Data Found</td></tr>
                                    @endif

                                </tbody>
                            </table>
                            @if ((request()->get('keyword')) || (request()->get('status')) || (request()->get('role')) || (request()->get('items')))
                                {{ $users->appends(['keyword' => request()->get('keyword'),'status' => request()->get('status'),'role' => request()->get('role'),'items' => request()->get('items')])->links() }}
                            @else
                                {!! $users->links() !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
