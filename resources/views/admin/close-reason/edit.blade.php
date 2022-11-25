@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        Update Reason
                    </div>
                
                    <div class="card-body mt-3">
                        <form action="{{ route("admin.close-reason.update", [$reason->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                                <label for="title">Title*</label>
                                <input type="text" id="title" name="title" class="form-control" value="{{ old('title', isset($reason) ? $reason->title : '') }}" required>
                                <input type="hidden" name="id" value="{{$reason->id}}">
                            </div>
                            <div class="form-group mt-3 {{ $errors->has('title') ? 'has-error' : '' }}">
                                <label for="description">Description</label>
                                <input type="text" name="description" class="form-control" value="{{ old('description', isset($reason) ? $reason->description : '') }}" required>
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
