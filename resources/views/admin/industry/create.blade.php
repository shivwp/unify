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
                        <form action="{{ route("admin.industry.store") }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mt-2">
                                <label for="name" class="mt-2"> Title*</label>
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
