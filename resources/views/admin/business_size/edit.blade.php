@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
            
<style type="text/css">
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #787878 !important;
    border: 1px solid #787878 !important;
}
</style>
<div class="card">
    <div class="card-header">
       Edit Business 
    </div>

    <div class="card-body">
        <form action="{{ route("admin.business_size.update", [$business_size->id]) }}" method="POST" enctype="multipart/form-data" id="formId">
            @csrf
            @method('PUT')
            <div class="form-group mt-3">
                <label for="client ">Title</label>
                <input type="text" class="form-control" value="{{$business_size->title}}" name="business_title" />
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-group mt-3">
                    <label for="client ">Min Employees Size</label>
                    <input type="number" min="1" value="{{$business_size->min_employee}}" class="form-control" name="min_business_size"/>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group mt-3">
                    <label for="client ">Max Employees Size</label>
                    <input type="number"  value="{{$business_size->max_employee}}" class="form-control" name="max_business_size"/>
                  </div>
                </div>
              </div>
              
        <input type="hidden" name="business_id"  value="{{$business_size->id}}">
            <div class="mt-3">
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div> 
@endsection
