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
                            <div class="d-flex">
                                <input type="text" name="search" id="search_field" class="form-control" value="{{ isset($search) ? $search : '' }}" placeholder="Search User" required>

                                <button class="btn-sm search-btn" type="submit">
                                    <i class="fa fa-search pl-3" aria-hidden="true"></i>
                                </button>

                                <a href="{{url('admin/freelancer')}}">
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

                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <h5>Freelancers</h5>
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
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($freelance)>0)

                                        @foreach($freelance as $key => $item)
                                            <tr data-entry-id="{{ $item->id }}">
                                                <td>{{ $item->id ?? ''}}</td>
                                                <td>{{ $item->name ?? '' }}</td>
                                                <td>{{ $item->email ?? '' }}</td>
                                                
                                                <td>
                                                    @if($item->status == 'pending')
                                                        <a href="{{url('admin/user/statusupdate',$item->id)}}"><span class="btn btn-xs btn-danger text-capitalize">{{ $item->status ?? '' }}</span></a>
                                                    @else
                                                        <a href="{{url('admin/user/statusupdate',$item->id)}}"><span class="btn btn-xs btn-info text-capitalize">{{ $item->status ?? '' }}</span></a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @can('user_show')
                                                        <a href="{{ route('admin.freelancer.show', $item->user_id) }}">
                                                            <button class="btn btn-sm btn-icon me-2"><i class="bx bx-show mx-1" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>View</span>"></i></button>
                                                        </a>
                                                    @endcan
                    
                                                    @can('user_edit')
                                                        <a href="{{ route('admin.users.edit', $item->user_id) }}">
                                                            <button class="btn btn-sm btn-icon me-2"><i class="bx bx-edit" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Edit</span>"></i></button>
                                                        </a>
                                                    @endcan
                    
                                                    @can('user_delete')
                                                        <form action="{{ route('admin.freelancer.destroy', $item->id) }}" method="POST" style="display: inline-block;">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                            <button type="submit" class="btn btn-sm btn-icon delete-record"><i class="bx bx-trash" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Delete</span>"></i></button>
                                                            
                                                        </form>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr><td colspan="5">No Data Found</td></tr>
                                    @endif
                                </tbody>
                            </table>
                            {!! $freelance->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
