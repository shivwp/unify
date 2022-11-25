@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-header">
                        {{ trans('global.create') }} {{ trans('cruds.projectStatus.title_singular') }}
                    </div>
                
                    <div class="card-body">
                        <form action="{{ route("admin.project-statuses.store") }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <label for="name">{{ trans('cruds.projectStatus.fields.name') }}*</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($projectStatus) ? $projectStatus->name : '') }}" required>
                                @if($errors->has('name'))
                                    <p class="help-block">
                                        {{ $errors->first('name') }}
                                    </p>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.projectStatus.fields.name_helper') }}
                                </p>
                            </div>
                            <div class="mt-3">
                                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
