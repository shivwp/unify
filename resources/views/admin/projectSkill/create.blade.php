@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
            
                <div class="card">
                    <div class="card-header border-bottom">
                        Create skill
                    </div>

                    <div class="card-body mt-3">
                        <form action="{{ route("admin.project-skill.store") }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label>Select Category*</label>
                                <select class="form-control" name="cate_id" required>
                                    <option value="" selected>Select Category</option>
                                    @foreach($category as $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <label for="name">Skill Name*</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($projectSkill) ? $projectSkill->name : '') }}" required>
                                @if($errors->has('name'))
                                    <p class="help-block">
                                        {{ $errors->first('name') }}
                                    </p>
                                @endif
                                <!-- <p class="helper-block">
                                    {{ trans('cruds.projectSkill.fields.name_helper') }}
                                </p> -->
                            </div>
                            <div class="form-group mt-3">
                                <label>Image</label>
                                <input type="file" name="image" class="form-control" required>
                            </div>
                            <hr>
                            <div class="d-flex">
                                <h6><strong>Banner Content </strong></h6> <span class="px-2"><i>(These details for showing content on skill detail sections)</i></span>
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
