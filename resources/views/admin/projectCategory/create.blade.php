@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
            
<div class="card">
    <div class="card-header">
        Create Category
    </div>

    <div class="card-body">
        <form action="{{ route("admin.project-category.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
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
            <div class="form-group {{ $errors->has('client_id') ? 'has-error' : '' }} mt-3">
                <label for="client">Parent</label>
                <select name="parent_id" id="client" class="form-control " required>
                    <option value="0" selected>No Parent</option>
                    @if(isset($Category))
                        @foreach($Category as $id => $item)
                            <option value="{{ $item->id }}" >{{ $item->name }}</option>
                        @endforeach
                    @endif
                </select>
                @if($errors->has('client_id'))
                    <p class="help-block">
                        {{ $errors->first('client_id') }}
                    </p>
                @endif
            </div>
            <div class="mt-3">
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
</div> 
@endsection
