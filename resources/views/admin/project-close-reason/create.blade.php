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
                        @php
                            if(isset($reason->id)){
                            $msg = "Edit Project Close Resion";
                        }else{
                        $msg = "Create Project Close Resion";
                    }
                        @endphp
                        <h5>{{$msg}}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route("admin.project-close-reason.store") }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ isset($reason) ? $reason->id : '' }}">
                            <div class="form-group mt-2">
                                <label for="name" class="mt-2"> Title *</label>
                                <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ empty(old('title')) ? (isset($reason) ? $reason->name : '') : old('title') }}" required>
                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div>
                                <input class="btn ad-btn create_btn mt-3" type="submit" value="{{ trans('global.save') }}">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
