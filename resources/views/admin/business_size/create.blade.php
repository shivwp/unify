@extends('layouts.master') @section('content')

<div class="content-wrapper">
  <!-- Content -->

  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-lg-12">
        <style type="text/css">
          .select2-container--default
            .select2-selection--multiple
            .select2-selection__choice {
            background-color: #787878 !important;
            border: 1px solid #787878 !important;
          }
        </style>
        <div class="card">
          <div class="card-header">Create Business</div>

          <div class="card-body">
            <form
              action="{{ route('admin.business_size.store') }}"
              method="POST"
              enctype="multipart/form-data"
            >
              @csrf

              <div class="form-group mt-3">
                <label for="client ">Title</label>
                <input type="text" class="form-control" name="business_title" Required>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-group mt-3">
                    <label for="client ">Min Employees Size</label>
                    <input
                      type="number"
                      min="1"
                      value="1"
                      class="form-control"
                      name="min_business_size"
                     Required>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group mt-3">
                    <label for="client ">Max Employees Size</label>
                    <input
                      type="number"
                      min="1"
                      value=""
                      class="form-control"
                      name="max_business_size"
                      Required>
                  </div>
                </div>
              </div>
              <div class="form-group mt-3">
                <label for="client ">Icon</label>
                <input type="file" value="" class="form-control" name="image" Required>
              </div>
              <div class="mt-3">
                <button type="submit" class="btn btn-danger">Save</button>
              </div>
            </form>
          </div>
        </div>
        @endsection
      </div>
    </div>
  </div>
</div>
