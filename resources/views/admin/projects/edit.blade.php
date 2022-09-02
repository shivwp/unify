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
        {{ trans('global.edit') }} {{ trans('cruds.project.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.projects.update", [$project->id]) }}" method="POST" enctype="multipart/form-data" id="formId">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Project Name*</label>
                <input type="hidden" name="project_id" value="{{$project->id}}">
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
            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }} mt-3">
                <label for="description">Project description </label>
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
            <div class="form-group {{ $errors->has('client_id') ? 'has-error' : '' }} mt-3">
                <label for="client">Client Name*</label>
                <select name="client_id" id="client" class="form-control " required>
                    @if(isset($clients)) 
                        @foreach($clients as $id => $client)
                            <option value="{{ $id }}" {{ (isset($project) && $project->client ? $project->client->id : old('client_id')) == $id ? 'selected' : '' }}>{{ $client }}</option>
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
                        <option value="{{ $cate->id }}" {{ (isset($project) && $project->categories->contains($cate->id)) ? 'selected' : '' }}>{{ $cate->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group mt-3">
                <label class="mb-2">Select Skills *</label>
                <select name="skills[]" class="form-control select2" id="select-skills" multiple required>
                    <option value="" disabled>Select</option>
                    @if(count($skill) > 0)
                        @foreach($skill as $key => $val)
                        <option value="{{ $val->id }}" {{ (isset($project) && $project->skills->contains($val->id)) ? 'selected' : '' }}>{{ $val->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group mt-3">
                <label class="mb-2">Scope</label>
                <select name="scop" class="form-control " id="select-category"  required>
                    <option value="" >Select Scope</option>
                       <option value="large" {{ isset($project) && $project->scop == 'large' ? 'selected' : '' }}>Large</option>
                       <option value="medium" {{ isset($project) && $project->scop == 'medium' ? 'selected' : '' }}>Medium</option>
                       <option value="small"  {{ isset($project) && $project->scop == 'small' ? 'selected' : '' }}>Small</option>
                     </select>
            </div>
            <div class="form-group mt-3">
                <label class="mb-2">Select Listing Type </label>
                <select name="listing[]" class="form-control select2" id="select-listing" multiple required>
                    <option value="" disabled>Select</option>
                    @if(count($listing) > 0)
                        @foreach($listing as $key => $list)
                        <option value="{{ $list->id }}" {{ (isset($project) && $project->listingtypes->contains($list->id)) ? 'selected' : '' }}>{{ $list->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group {{ $errors->has('start_date') ? 'has-error' : '' }} mt-3">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" data-date="" data-date-format="DD MMMM YYYY" name="start_date" class="form-control date" value="{{ old('start_date', isset($project)?$project->start_date : '') }}">
                @if($errors->has('start_date'))
                    <p class="help-block">
                        {{ $errors->first('start_date') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.project.fields.start_date_helper') }}
                </p>
            </div>
            <div class="form-group mt-3">
                <label for="start_date">End Date </label>
                <input type="date" id="end_date" name="end_date" class="form-control date" value="{{ old('end_date', isset($project)?$project->end_date : '') }}">
               
            </div>
            @php
        
            $date1 = $project->end_date;
            $date2 = $project->start_date;
            $datetime1 = new DateTime($date1);
            $datetime2 = new DateTime($date2);
            $interval = $datetime1->diff($datetime2);
            $days = $interval->format('%a')
           
        @endphp
            <div class="form-group mt-3">
                <label for="project_duration ">Project Duration *(In days)</label>
                <input type="text" name="project_duration" id="project_duration" class="form-control" value="{{$days}}" disabled>
            </div>
            <!-- <div class="form-group mt-3">
                <label for="freelancer_type">Freelancer Type *</label>
                <input type="text" name="freelancer_type" class="form-control" value="{{ old('freelancer_type', isset($project) ? $project->freelancer_type : '') }}" required>
            </div> -->
            <div class="form-group mt-3">
                <label for="payment_base">How do you want to pay*</label>
                <select name="payment_base" id="paymethod" class="form-control " required>
                    <option value="">Please select</option>
                    <option value="hourly" {{ isset($project) && $project->payment_base == 'hourly' ? 'selected' : '' }}>Hourly Based</option>
                    <option value="fixed" {{ isset($project) && $project->payment_base == 'fixed' ? 'selected' : '' }}>Fixed Price</option>
                </select>
            </div>
            <div class="form-group {{ $errors->has('budget') ? 'has-error' : '' }} mt-3 total_budget" @if($project->payment_base == 'fixed')  @else style="display: none; @endif ">
                <label for="budget">Budget (Total)</label>
                <input type="number" id="budget" name="total_budget" class="form-control" value="{{ old('budget', isset($project) ? $project->total_budget : '') }}" step="0.01">
                @if($errors->has('budget'))
                    <p class="help-block">
                        {{ $errors->first('budget') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.project.fields.budget_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('budget') ? 'has-error' : '' }} mt-3 per_hour_budget" @if($project->payment_base == 'hourly')  @else style="display: none; @endif>
                <label for="budget">Budget (Per Hour)</label>
                <input type="number" id="budget" name="per_hour_budget" class="form-control" value="{{ old('budget', isset($project) ? $project->per_hour_budget : '') }}" step="0.01">
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
                    <option value="entry"{{ isset($project) && $project->level == 'entry' ? 'selected' : '' }}>entry</option>
                    <option value="Intermediate"{{ isset($project) && $project->level == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                    <option value="expert"{{ isset($project) && $project->level == 'expert' ? 'selected' : '' }}>Expert</option>
                </select>
            </div>
            <div class="form-group mt-3">
                <label for="english_level">English Level *</label>
                <select name="english_level" id="client" class="form-control " required>
                    <option value="">Please select</option>
                    <option value="beginner" {{ isset($project) && $project->english_level == 'beginner' ? 'selected' : '' }}>Native</option>
                    <option value="mediator" {{ isset($project) && $project->english_level == 'mediator' ? 'selected' : '' }}>Fluent</option>
                </select>
            </div>
            <div class="form-group {{ $errors->has('status_id') ? 'has-error' : '' }} mt-3">
                <label for="status">{{ trans('cruds.project.fields.status') }}</label>
                <select name="status_id" id="status" class="form-control ">
                    @if(isset($statuses))
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
            @if(!empty($project->project_images))
                @php
                    $value = json_decode($project->project_images);
                @endphp
                @if(!empty($value))
                    <div class="even mt-3" style="display: flex; flex-wrap: wrap; justify-content: flex-start;">
                        @foreach($value as $multidata)
                            <div class="parc">
                                <span class="pip" data-title="{{$multidata}}">
                                    <img src="{{ url('/project-files').'/'.$multidata ?? "" }}" alt="" width="100" height="100">
                                    <!-- <a class="btn"><i class="fa fa-times remove" onclick="removeImage('{{$multidata}}')"></i></a> -->
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
                <input type="hidden" name="image1" id="gallery_img" value="{{$project->project_images}}">
            @endif
            <label class="form-label mt-0">Add Multiple Images/Files </label>
            <input type="file" class="form-control" name="image[]" value="" multiple>
            <br>
            <div>
                <input class="btn btn-danger" type="submit" id="formsubmit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div> 
@endsection
