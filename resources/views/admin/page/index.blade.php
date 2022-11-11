@extends('layouts.master') @section('content')
<style>
    .search-btn {
        border: 1px solid #d7cbcb;
        padding: 8px 10px 6px 11px;
        border-radius: 8px;
        margin-left: 6px;
    }
    a.create_btn:hover {
        color: #fff;
    }
</style>
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row">
            <div class="row tabelhed d-flex justify-content-between">
                <div class="col-lg-2 col-md-2 col-sm-2 d-flex">

                    <a class="btn-sm ad-btn btn text-center pt-2 create_btn" href="{{ route('admin.page.create') }}"> Add</a>
                    
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
                            <a href="{{url('admin/page')}}">
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
                <div class="card-header ">
                    <div class="row">
                        <div class="col-xl-6">
                            <h5>Pages List</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table">
                        <table id="example" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i=1
                                @endphp
                                @foreach($page as $key => $value)
                                    <tr data-entry-id="{{ $value->id }}">
                                        <td>{{ $value->id ?? ''}}</td>
                                        <td>{{ $value->title ?? '' }}</td>
                                        <td>{{ $value->slug ?? '' }}</td>
                                        
                                        <td>
                                            <a href="{{ route('admin.page.edit', $value->id) }}">
                                                <button class="btn btn-sm btn-icon me-2"><i class="bx bx-edit" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Edit</span>"></i></button>
                                            </a>
                                            <form action="{{ route('admin.page.destroy', $value->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <button type="submit" class="btn btn-sm btn-icon delete-record"><i class="bx bx-trash" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Delete</span>"></i></button>
                                                
                                            </form>
            
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {!! $page->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
