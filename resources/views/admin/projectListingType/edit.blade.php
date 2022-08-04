@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Edit Listing Type List
    </div>

    <div class="card-body">
        <form action="{{ route("admin.project-listing-type.update", [$projectListingType->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Listing Type Name*</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($projectListingType) ? $projectListingType->name : '') }}" required>
                @if($errors->has('name'))
                    <p class="help-block">
                        {{ $errors->first('name') }}
                    </p>
                @endif
                <!-- <p class="helper-block">
                    {{ trans('cruds.projectListingType.fields.name_helper') }}
                </p> -->
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
@endsection