@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
            
                <div class="card">
                    <div class="card-header border-bottom">
                        Create Category
                    </div>

                    <div class="card-body">
                        <form action="{{ route("admin.project-category.store") }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mt-3 {{ $errors->has('name') ? 'has-error' : '' }}">
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
                                    <option value="0">No Parent</option>
                                    @if(isset($Category))
                                        @foreach($Category as $id => $item)
                                            <option value="{{ $item->id }}" {{ isset($parent_id) && $item->id == $parent_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @if($errors->has('client_id'))
                                    <p class="help-block">
                                        {{ $errors->first('client_id') }}
                                    </p>
                                @endif
                            </div>
                            <div class="form-group mt-3">
                                <label>Image</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                            <hr>
                            <div class="d-flex">
                                <h6><strong>Banner Content </strong></h6> <span class="px-2"><i>(These details for showing content on category details sections)</i></span>
                            </div>                            
                            <div class="form-group mt-3">
                                <label for="short_description">Banner Title *</label>
                                <input type="text" id="short_description" name="short_description" class="form-control @error('short_description') is-invalid @enderror" value="{{ old('short_description', '') }}" required>
                                @error('short_description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mt-3">
                                <label for="long_description">Banner Description *</label>
                                <textarea id="long_description" name="long_description" class="form-control @error('long_description') is-invalid @enderror" required>{{ old('long_description', '') }}</textarea>
                                @error('long_description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <span id="project_description_error" style="color:red;"></span>
                                <p class="helper-block">
                                    {{ trans('cruds.project.fields.description_helper') }}
                                </p>
                            </div>
                            <div class="form-group mt-3">
                                <label>Banner Image</label>
                                <input type="file" name="banner_image" class="form-control" required>
                            </div>
                            <div class="mt-3">
                                <input class="btn ad-btn create_btn" type="submit" value="{{ trans('global.save') }}">
                            </div>
                        </form>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>
@endsection
