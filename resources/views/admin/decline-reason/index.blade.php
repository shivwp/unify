@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
                <div class="row tabelhed d-flex justify-content-between">
                    <div class="col-lg-2 col-md-2 col-sm-2 d-flex">
                        <a class="btn-sm ad-btn create_btn text-center pt-2" href="{{ route("admin.decline-reason.create") }}"> Add</a>
                       {{-- <button id="btnExport" onClick="fnExcelReport()" class="btn-sm ad-btn clearfix">Excel</button>--}}
                    </div>
                </div>
              
                <div class="card">
                      @if (\Session::has('success'))
                    <div class="alert alert-success" id="alert">
                            <span>{!! \Session::get('success') !!}</span>
                    </div>
                @endif
                @if (\Session::has('delete'))
                    <div class="alert alert-danger" id="alertd">
                            <span>{!! \Session::get('delete') !!}</span>
                    </div>
                @endif
                    <div class="card-header" >
                       <h5>Decline Reasons </h5>
                    </div>
        
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class=" table table-bordered table-striped table-hover datatable datatable-Role">
                                <thead>
                                    <tr>
                                        <th>S No.</th>
                                        <th>Title</th>
                                        <th>Type</th>
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
                                            <td>{{ $value->title ?? '' }}</td>
                                            <td>{{ $value->type }}</td>
                                            <td>
                                                
                                                <a href="{{ route('admin.decline-reason.edit', $value->id) }}">
                                                    <button class="btn btn-sm btn-icon me-2"><i class="bx bx-edit" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Edit</span>"></i></button>
                                                </a>
                                                <form action="{{ route('admin.decline-reason.destroy', $value->id) }}" method="POST" style="display: inline-block;">
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
 <script src="{{asset('admin/assets/vendor/libs/jquery/jquery.js') }}"></script>

<script >

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
        $("#alert").slideUp(500);
});

</script>
<script>
    $(document).ready(function() {
    $("#alertd").delay(3000).slideUp(200, function() {
        $(this).alert('close');
});
});
</script>
@endsection
