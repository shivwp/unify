@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
            
<style type="text/css">
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #787878 !important;
    border: 1px solid #787878 !important;
}
</style>
<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.project.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.projects.store") }}" method="POST" enctype="multipart/form-data" id="formId">
            @csrf
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Project Name*</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($project) ? $project->name : '') }}" required>
                @if($errors->has('name'))
                    <p class="help-block">
                        {{ $errors->first('name') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.project.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                <label for="description">Project description*</label>
                <textarea id="project_description" name="description" class="form-control ">{{ old('description', isset($project) ? $project->description : '') }}</textarea>
                @if($errors->has('description'))
                    <p class="help-block">
                        {{ $errors->first('description') }}
                    </p>
                @endif
                <span id="project_description_error" style="color:red;"></span>
                <p class="helper-block">
                    {{ trans('cruds.project.fields.description_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('client_id') ? 'has-error' : '' }}">
                <label for="client">Client Name*</label>
                <select name="client_id" id="client" class="form-control " required>
                    @if(isset($clients))
                        @foreach($clients as  $client)
                            <option value="{{ $client->id }}" >{{ $client->name }}</option>
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
                <label class="mb-2">Select Categories *</label>
                <select name="category[]" class="form-control select2" id="select-category" multiple required>
                    <option value="" disabled>Select</option>
                    @if(count($category) > 0)
                        @foreach($category as $key => $cate)
                        <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group mt-3">
                <label class="mb-2">Select Skills</label>
                <select name="skills[]" class="form-control select2" id="select-skills" multiple required>
                    <option value="" disabled>Select</option>
                    @if(count($skill) > 0)
                    @foreach($skill as $key => $val)
                    <option value="{{ $val->id }}">{{ $val->name }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group mt-3">
                <label class="mb-2">Scope</label>
                <select name="scop" class="form-control " id="select-category"  required>
                    <option value="">Select Scope</option>
                       <option value="large">Large</option>
                       <option value="medium">Medium</option>
                       <option value="small">Small</option>
                     </select>
            </div>
            <div class="form-group mt-3">
                <label class="mb-2">Select Listing Type *</label>
                <select name="listing[]" class="form-control select2" id="select-listing" multiple required>
                    <option value="" disabled>Select</option>
                    @if(count($listing) > 0)
                    @foreach($listing as $key => $list)
                    <option value="{{ $list->id }}">{{ $list->name }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            
            
            <div class="form-group mt-3">
                <label for="start_date">Start Date </label>
                <input type="date" id="start_date" name="start_date" class="form-control date" value="{{ old('start_date', isset($project) ? $project->start_date : '') }}">
               
            </div>
            <div class="form-group mt-3">
                <label for="start_date">End Date </label>
                <input type="date" id="end_date" name="end_date" class="form-control date" value="{{ old('end_date', isset($project) ? $project->start_date : '') }}">
               
            </div>
            <div class="form-group mt-3">
                <label for="project_duration ">Project Duration *(In days)</label>
                <input type="text" name="project_duration" id="project_duration" class="form-control" value="" disabled>
            </div>
            <!-- <div class="form-group">
                <label for="freelancer_type">Freelancer Type *</label>
                <input type="text" name="freelancer_type" class="form-control" value="" required>
            </div> -->
            <div class="form-group mt-3">
                <label for="payment_base">How do you want to pay*</label>
                <select name="payment_base" id="paymethod" class="form-control " required>
                    <option value="">Please select</option>
                    <option value="hourly">Hourly Based</option>
                    <option value="fixed">Fixed Price</option>
                </select>
            </div>
            
            <div class="form-group {{ $errors->has('budget') ? 'has-error' : '' }} mt-3 total_budget" style="display: none;">
                <label for="budget">Budget (Total)</label>
                <input type="number" id="budget" name="total_budget" class="form-control" value="{{ old('budget', isset($project) ? $project->budget : '') }}" step="0.01">
                @if($errors->has('budget'))
                    <p class="help-block">
                        {{ $errors->first('budget') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.project.fields.budget_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('budget') ? 'has-error' : '' }} mt-3 per_hour_budget" style="display: none;">
                <label for="budget">Budget (Per Hour)</label>
                <input type="number" id="budget" name="per_hour_budget" class="form-control" value="{{ old('budget', isset($project) ? $project->budget : '') }}" step="0.01">
                @if($errors->has('budget'))
                    <p class="help-block">
                        {{ $errors->first('budget') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.project.fields.budget_helper') }}
                </p>
            </div>
            <div class="form-group mt-3">
                <label for="level">What level of experience will it need?</label>
                <select name="level" id="client" class="form-control " required>
                    <option value="">Please select</option>
                    <option value="entry">Entry</option>
                    <option value="Intermediate">Intermediate</option>
                    <option value="expert">Expert</option>
                </select>
            </div>
            <div class="form-group mt-3">
                <label for="english_level">English Level *</label>
                <select name="english_level" id="client" class="form-control " required>
                    <option value="">Please select</option>
                    <option value="beginner">Native</option>
                    <option value="mediator">Fluent</option>
                </select>
            </div>
            <div class="form-group {{ $errors->has('status_id') ? 'has-error' : '' }} mt-3">
                <label for="status">Project Status *</label>
                <select name="status_id" id="status" class="form-control ">
                    @if($statuses)
                        @foreach($statuses as $id => $status)
                            <option value="{{ $id }}" {{ (isset($project) && $project->status ? $project->status->id : old('status_id')) == $id ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    @endif
                </select>
                @if($errors->has('status_id'))
                    <p class="help-block">
                        {{ $errors->first('status_id') }}
                    </p>
                @endif
            </div>
            <hr class="mt-5 mb-2">
            <div class="col-md-12 mt-3">
              <div class="row">
                <div class="col-md-8 mt-3">
                Add Questions
                </div>
               
                <div class="col-md-4 mt-3">
                    <div class="mr-0 ml-auto" style="float: right;">
                        <a href="javascript:void(0)" class="btn btn-success ml-5 mr-0  addMore add_btnnn"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a>
                    </div>
                </div>
              </div>
            </div>
            <div class="fieldGroup"> 
                          <div class="col-md-12 pr-1 pl-1">
                         <div class="form-group">
                            <input type="hidden" id="Qus_no_replace" value="1" id="">
                       <label class="form-label" >Qus</label><input type="text" class="form-control" name="slider_title[]" placeholder="Title" value=""></div>
                      </div>
                   </div>
                                                
               
             
            <div class="fieldGroupCopy" style="display: none; margin-top: 12px;">
                <div class="col-md-12 pr-1 pl-1 mt-3">

                    <div class="form-group">

                        <label class="form-label Qus_no">Qus</label>

                        <input type="text" class="form-control" name="slider_title[]" placeholder="Title" value="">

                    </div>
                </div>
                   
              <div class="row">
                <div class="col-12">
                     <div class="col-2 pl-0">
                        <div class="mr-0 ml-auto">
                            <a href="javascript:void(0)" class="btn btn-success ml-0 mt-2 mr-0  remove add_btnnn"><span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span>Remove</a>
                        </div>
                    </div>
                  </div>
              </div>
             </div>       
             <hr class="mt-5 mb-2">                      
            {{--@if(!empty($property->gallery_image))
                @php
                $value = json_decode($property->gallery_image);
                @endphp
                @if(!empty($value))
                <div class="even" style="display: flex; flex-wrap: wrap; justify-content: flex-start;">
                    @foreach($value as $multidata)
                    <div class="parc">
                        <span class="pip" data-title="{{$multidata}}">
                            <img src="{{ url('/project-files').'/'.$multidata ?? "" }}" alt="" width="100" height="100">
                            <a class="btn"><i class="fa fa-times remove" onclick="removeImage('{{$multidata}}')"></i></a>
                        </span>
                    </div>
                    @endforeach
                </div>
                @endif
                <input type="hidden" name="image1" id="gallery_img" value="{{$property->gallery_image}}">
            @endif--}}
            <label class="form-label mt-3">Add Multiple Images/Files </label>
            <input type="file" class="form-control" name="image[]" value="" multiple>
            <div class="mt-3">
                <input class="btn btn-danger" type="submit" id="formsubmit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>  
    </div>
</div> 
@endsection
