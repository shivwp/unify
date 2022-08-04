@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Edit Category List
    </div>

    <div class="card-body">
        <form action="{{ route("admin.project-category.update", [$projectCategory->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Category Name*</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($projectCategory) ? $projectCategory->name : '') }}" required>
                @if($errors->has('name'))
                    <p class="help-block">
                        {{ $errors->first('name') }}
                    </p>
                @endif
                <!-- <p class="helper-block">
                    {{ trans('cruds.projectCategory.fields.name_helper') }}
                </p> -->
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
@endsection