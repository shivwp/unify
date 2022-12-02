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
                        <h5>Edit Industry Details</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route("admin.industry.update", [$industry->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group mt-2">
                                <label for="name" class="mt-2"> Title*</label>
                                <input type="hidden" class="form-control" name="id" value="{{$industry->id}}">
                                <input type="text" id="name" name="title" class="form-control" value="{{ old('title', isset($industry) ? $industry->title : '') }}" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="name">Description *</label>
                                <input type="text" id="description" name="description" class="form-control" value="{{ old('description', isset($industry) ? $industry->description : '') }}">
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
