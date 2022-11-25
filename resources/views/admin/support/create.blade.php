@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        Create Ticket
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.support.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <!-- <div class="form-group {{ $errors->has('project_id') ? 'has-error' : '' }} ">
                                <label for="project"  class="mb-2">{{ trans('cruds.transaction.fields.project') }}*</label>
                                <select name="project_id" id="project" class="form-control " required>
                                    @foreach($projects as $id => $project)
                                        <option value="{{ $id }}" {{ (isset($transaction) && $transaction->project ? $transaction->project->id : old('project_id')) == $id ? 'selected' : '' }}>{{ $project }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('project_id'))
                                    <p class="help-block" style="color:red;">
                                        {{ $errors->first('project_id') }}
                                    </p>
                                @endif
                            </div> -->
                            <div class="form-group mt-3">
                                <label for="project"  class="mb-2">User Name</label>
                                <select name="user_id" id="project" class="form-control " required>
                                <option value="">Select</option>
                                    @foreach($user as $id => $item)
                                        <option value="{{ $item->id }}" >{{ $item->name }}</option>
                                    @endforeach
                                </select>
                               <!--  @if($errors->has('user_id'))
                                    <p class="help-block" style="color:red;">
                                        {{ $errors->first('user_id') }}
                                    </p>
                                @endif -->
                            </div>

                            <div class="form-group mt-3">
                                <label for="project" class="mb-2">Job Link</label>
                                <input type="url" name="jobLink" class="form-control" required>
                                  @error('jobLink')
                                    <div class="alert alert-danger" role="alert">
                                    {{$message}}
                                  </div>
                                  @enderror
                                <!-- <select name="source" id="project" class="form-control " required>
                                <option value="">Select</option>
                                  <option value="website" >Website</option>
                                  <option value="Whatsapp" >Whatsapp</option>
                                  <option value="email" >Email</option>
                                  <option value="application" >Application</option>
                                  </select> -->
                               <!--  @if($errors->has('source'))
                                    <p class="help-block" style="color:red;">
                                        {{ $errors->first('source') }}
                                    </p>
                                @endif -->
                            </div>
                        
                            <div class="form-group mt-3">
                                <label for="project"  class="mb-2">Status</label>
                                <select name="status" id="project" class="form-control " required>
                                  <option value="">Select</option>
                                  <option value="pending" >Pending</option>
                                  <option value="active" >Active</option>
                                  <option value="closed" >Closed</option>
                                  <option value="hold">On-Hold</option>
                                  </select>
                                     @error('status')
                                    <div class="alert alert-danger" role="alert">
                                    {{$message}}
                                  </div>
                                  @enderror
                               <!--  @if($errors->has('status'))
                                    <p class="help-block" style="color:red;">
                                        {{ $errors->first('status') }}
                                    </p>
                                @endif -->
                            </div>
                        
                            <div class="form-group mt-3">
                                <label for="description"  class="mb-2">{{ trans('cruds.transaction.fields.description') }}</label>
                                <textarea id="description" name="description" class="form-control " required>{{ old('description', isset($transaction) ? $transaction->description : '') }}</textarea>
                              <!--   @if($errors->has('description'))
                                    <p class="help-block" style="color:red;">
                                        {{ $errors->first('description') }}
                                    </p>
                                @endif -->
                                <p class="helper-block">
                                    {{ trans('cruds.transaction.fields.description_helper') }}
                                </p>
                                   @error('description')
                                    <div class="alert alert-danger" role="alert">
                                    {{$message}}
                                  </div>
                                  @enderror
                            </div>
                            <div class="form-group mt-3 mb-3">
                                <label for="description"  class="mb-2">Attachment</label>
                                <input type="file" class="form-control"  name="image[]" multiple>
                               <!--  @if($errors->has('image'))
                                    <p class="help-block" style="color:red;">
                                        {{ $errors->first('image') }}
                                    </p>
                                @endif -->
                                   @error('image[]')
                                    <div class="alert alert-danger" role="alert">
                                    {{$message}}
                                  </div>
                                  @enderror
                            </div>
                            <div>
                                <input class="btn ad-btn create_btn " type="submit" value="{{ trans('global.save') }}">
                            </div>
                        </form>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>
@endsection
