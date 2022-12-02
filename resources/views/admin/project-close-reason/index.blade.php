@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
                <div class="row tabelhed d-flex justify-content-between">
                    <div class="col-lg-2 col-md-2 col-sm-2 d-flex">
                        <a class="btn-sm ad-btn create_btn text-center pt-2" href="{{ route("admin.project-close-reason.create") }}"> Add</a>
                        <button id="btnExport" onClick="fnExcelReport()" class="btn-sm ad-btn clearfix">Excel</button>
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
                        <h5>Project Close Reasons</h5>
                    </div>
        
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class=" table table-bordered table-striped table-hover datatable datatable-Role">
                                <thead>
                                    <tr>
                                        <th>S No.</th>
                                        <th>Title</th>
                                        <!-- <th>Discription</th> -->
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i=1
                                    @endphp
                                    @foreach($reason as $key => $value)
                                        <tr data-entry-id="{{ $value->id }}">
                                           
                                            <td>{{$i++}}</td>
                                            <td>{{ $value->name ?? '' }}</td>
                                            <!-- <td>{{ $value->description }}</td> -->
                                            <td>
                                                
                                                <a href="{{ route('admin.project-close-reason.edit', $value->id) }}">
                                                    <button class="btn btn-sm btn-icon me-2"><i class="bx bx-edit" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Edit</span>"></i></button>
                                                </a>
                                                <form action="{{ route('admin.project-close-reason.destroy', $value->id) }}" method="POST" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <button type="submit" class="btn btn-sm btn-icon delete-record"><i class="bx bx-trash" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Delete</span>"></i></button>
                                                </form>
                
                                            </td>
                
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                
@endsection
