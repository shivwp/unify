@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                       <h5>Edit Ticket</h5>
                    </div>
                    <hr class="m-0">
                    <div class="card-body">
                        <form action="{{ route("admin.support.update", [$support->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                          <input type="hidden" name="support_id" value="{{$support->id}}">
                            <!--   <div class="form-group {{ $errors->has('project_id') ? 'has-error' : '' }} ">
                                <label for="project"  class="mb-2">{{ trans('cruds.transaction.fields.project') }}*</label>
                                <select name="project_id" id="project" class="form-control " required>
                                    @foreach($projects as $id => $project)
                                        <option value="{{ $id }}" @if($support->project_id==$id) selected  @endif>{{ $project }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('project_id'))
                                    <p class="help-block" style="color:red;">
                                        {{ $errors->first('project_id') }}
                                    </p>
                                @endif
                            </div> -->
                            <div class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }} mt-3">
                                <label for="project"  class="mb-2">User Name</label>
                                <select name="user_id" id="project" class="form-control " required>
                                <option value="">Select</option>
                                    @foreach($user as $id => $item)
                                        <option value="{{ $id }}" @if($support->user_id==$id) selected  @endif>{{ $item }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('user_id'))
                                    <p class="help-block" style="color:red;">
                                        {{ $errors->first('user_id') }}
                                    </p>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('source') ? 'has-error' : '' }} mt-3">
                                <label for="project" class="mb-2">Job Link</label>
                               <input type="url" name="jobLink" class="form-control" value="{{$support->job_link}}" required>
                            </div>
                        
                            <div class="form-group mt-3">
                                <label for="project"  class="mb-2">Status</label>
                                <select name="status" id="project" class="form-control " required>
                                  <option value="">Select</option>
                                  <option value="pending" @if($support->status=="pending") selected  @endif>Pending</option>
                                  <option value="active" @if($support->status=="active") selected  @endif>Active</option>
                                  <option value="closed" @if($support->status=="closed") selected  @endif>Closed</option>
                                  <option value="hold" @if($support->status=="hold") selected  @endif>On-Hold</option>
                                  </select>
                                @if($errors->has('status'))
                                    <p class="help-block" style="color:red;">
                                        {{ $errors->first('status') }}
                                    </p>
                                @endif
                            </div>
                        
                            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }} mt-3">
                                <label for="description"  class="mb-2">{{ trans('cruds.transaction.fields.description') }}</label>
                                <textarea id="description" name="description" class="form-control " required>{{ old('description', isset($support) ? $support->description : '') }}</textarea>
                                @if($errors->has('description'))
                                    <p class="help-block" style="color:red;">
                                        {{ $errors->first('description') }}
                                    </p>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.transaction.fields.description_helper') }}
                                </p>
                            </div>
                            <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }} mt-3 mb-3">
                                <label for="description"  class="mb-2">Attachment</label>
                                <input type="file" class="form-control"  name="image[]" multiple>
                                @if($errors->has('image'))
                                    <p class="help-block" style="color:red;">
                                        {{ $errors->first('image') }}
                                    </p>
                                @endif
                            </div>
                            @if(!empty($support->image))
                                @php
                                    $value = json_decode($support->image);
                                @endphp
                                @if(!empty($value))
                                    <div class="even mt-3 mb-5" style="display: flex; flex-wrap: wrap; justify-content: flex-start;">
                                        @foreach($value as $multidata)
                                            <div class="parc">
                                                <span class="pip" data-title="{{$multidata}}">
                                                    <img src="{{ url('/support-image').'/'.$multidata ?? "" }}" alt="" width="100" height="100">
                                                    <!-- <a class="btn"><i class="fa fa-times remove" onclick="removeImage('{{$multidata}}')"></i></a> -->
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <input type="hidden" name="image1" id="gallery_img" value="{{$support->image}}">
                            @endif
                            <hr >
                           <!--  <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }} mt-4">
                                <label for="description"  class="mb-2">Solution</label>
                                <textarea id="solution" name="solution" class="form-control " >{{ old('description', isset($support) ? $support->solution : '') }}</textarea>
                               
                                <p class="helper-block">
                                    {{ trans('cruds.transaction.fields.description_helper') }}
                                </p>
                            </div>
                 -->            <div class="mt-3">
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
