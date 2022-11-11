@extends('layouts.master') @section('content')
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        @if(Session::has('error'))
            <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error') }}</p>
        @endif
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-header border-bottom">
                        Create
                    </div>
                    <div class="card-body">
                        <form action="{{ route("admin.certificate.store") }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ isset($certificate) ? $certificate->id : '' }}">
                            <div class="form-group mt-2">
                                <label for="name" class="mt-2"> Title *</label>
                                <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ empty(old('title')) ? (isset($certificate) ? $certificate->name : '') : old('title') }}" required>
                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div>
                                <input class="btn btn-success btn_back" type="submit" value="{{ trans('global.save') }}">
                            </div>
                        </form>
                    </div>
                </div>

@endsection
