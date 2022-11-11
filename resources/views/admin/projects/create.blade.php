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
@if(Session::has('error'))
    <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error') }}</p>
@endif
<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.project.title_singular') }}
    </div>
    <div class="card-body">
        <form action="{{ route("admin.projects.store") }}" method="POST" enctype="multipart/form-data" id="formId">
            @csrf
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Project Name *</label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', isset($project) ? $project->name : '') }}" required>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="form-group mt-3">
                <label for="project_type">Project Type *</label>
                <select name="project_type" id="project_type" class="form-control @error('project_type') is-invalid @enderror" required>
                    <option value="" {{ old('project_type') ? (old('project_type') == "" ? 'selected' : '') : '' }}>Please select</option>
                    <option value="long_term" {{ old('project_type') ? (old('project_type') == "long_term" ? 'selected' : '') : '' }}>Long Term</option>
                    <option value="short_term" {{ old('project_type') ? (old('project_type') == "short_term" ? 'selected' : '') : '' }}>Short Term</option>
                </select>
                @error('project_type')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label class="mb-2">Select Categories *</label>
                <select name="category" class="form-control @error('category') is-invalid @enderror" id="select-category" required>
                    <option value="" {{ old('category') ? (old('category') == "" ? 'selected' : '') : '' }}>Please Select</option>
                    @if(count($category) > 0)
                        @foreach($category as $key => $cate)
                        <option value="{{ $cate->id }}" {{ old('category') ? (old('category') == $cate->id ? 'selected' : '') : '' }}>{{ $cate->name }}</option>
                        @endforeach
                    @endif
                </select>
                @error('category')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label class="mb-2">Select Skills *</label>
                <select name="skills[]" class="form-control select2 @error('skills') is-invalid @enderror" id="select-skills" multiple required>
                    <option value="" disabled>Select</option>
                    @if(count($skill) > 0)
                    @foreach($skill as $key => $val)
                    <option value="{{ $val->id }}">{{ $val->name }}</option>
                    @endforeach
                    @endif
                </select>
                @error('skills')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group {{ $errors->has('client_id') ? 'has-error' : '' }} mt-3">
                <label for="client">Client Name*</label>
                <select name="client_id" id="client" class="form-control @error('client_id') is-invalid @enderror" required>
                    @if(isset($clients))
                        <option value="" {{ old('client_id') ? (old('client_id') == "" ? 'selected' : '') : '' }}>Please Select</option>
                        @foreach($clients as  $client)
                            <option value="{{ $client->id }}" {{ old('client_id') ? (old('client_id') == $client->id ? 'selected' : '') : '' }}>{{ $client->name .' ('.$client->email.')' }}</option>
                        @endforeach
                    @endif
                </select>
                @error('client_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label class="mb-2">Scope *</label>
                <select name="scope" class="form-control @error('scope') is-invalid @enderror" id="select-category"  required>
                    <option value="" {{ old('scope') ? (old('scope') == "" ? 'selected' : '') : '' }}>Please Select</option>
                    <option value="large" {{ old('scope') ? (old('scope') == "large" ? 'selected' : '') : '' }}>Large</option>
                    <option value="medium" {{ old('scope') ? (old('scope') == "medium" ? 'selected' : '') : '' }}>Medium</option>
                    <option value="small" {{ old('scope') ? (old('scope') == "small" ? 'selected' : '') : '' }}>Small</option>
                </select>
                @error('scope')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label class="mb-2">How long will your work take? *</label>
                <select name="project_duration" class="form-control @error('project_duration') is-invalid @enderror" id="select-project_duration"  required>
                    <option value="" {{ old('project_duration') ? (old('project_duration') == "" ? 'selected' : '') : '' }}>Please Select</option>
                    <option value="6" {{ old('project_duration') ? (old('project_duration') == "6" ? 'selected' : '') : '' }}>More than 6 months</option>
                    <option value="3" {{ old('project_duration') ? (old('project_duration') == "3" ? 'selected' : '') : '' }}>3 to 6 months</option>
                    <option value="1" {{ old('project_duration') ? (old('project_duration') == "1" ? 'selected' : '') : '' }}>1 to 3 months</option>
                </select>
                @error('project_duration')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label for="level">What level of experience will it need? *</label>
                <select name="level" id="client" class="form-control @error('level') is-invalid @enderror" required>
                    <option value="" {{ old('level') ? (old('level') == "" ? 'selected' : '') : '' }}>Please select</option>
                    <option value="entry" {{ old('level') ? (old('level') == "entry" ? 'selected' : '') : '' }}>Entry</option>
                    <option value="intermediate" {{ old('level') ? (old('level') == "intermediate" ? 'selected' : '') : '' }}>Intermediate</option>
                    <option value="expert" {{ old('level') ? (old('level') == "expert" ? 'selected' : '') : '' }}>Expert</option>
                </select>
                @error('level')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label for="payment_base">Budget *</label>
                <select name="budget" id="paymethod" class="form-control @error('budget') is-invalid @enderror" required>
                    <option value="" {{ old('budget') ? (old('budget') == "" ? 'selected' : '') : '' }}>Please select</option>
                    <option value="hourly" {{ old('budget') ? (old('budget') == "hourly" ? 'selected' : '') : '' }}>Hourly Based</option>
                    <option value="fixed" {{ old('budget') ? (old('budget') == "fixed" ? 'selected' : '') : '' }}>Fixed Price</option>
                </select>
                @error('budget')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="form-group {{ $errors->has('budget') ? 'has-error' : '' }} mt-3 per_hour_budget" id="per_hour_budget" style="display: none;">
                <div class="row">
                    <div class="col-md-6">
                        <label for="budget">Minimum</label>
                        <input type="number" id="min_budget" name="min_budget" class="form-control @error('min_budget') is-invalid @enderror" value="{{ old('min_budget', '') }}" step="0.01" required>
                        @error('min_budget')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="budget">Maximum</label>
                        <input type="number" id="max_budget_hourly" name="max_budget_hourly" class="form-control @error('max_budget_hourly') is-invalid @enderror" value="{{ old('max_budget_hourly', '') }}" step="0.01" required>
                        @error('max_budget_hourly')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group {{ $errors->has('budget') ? 'has-error' : '' }} mt-3 total_budget" id="total_budget" style="display: none;">
                <label for="budget">Maximum</label>
                <input type="number" id="max_budget" name="max_budget" class="form-control @error('max_budget') is-invalid @enderror" value="{{ old('max_budget', '') }}" step="0.01" required>
                @error('max_budget')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group {{ $errors->has('status_id') ? 'has-error' : '' }} mt-3">
                <label for="status">Project Status *</label>
                <select name="status_id" id="status" class="form-control @error('status_id') is-invalid @enderror">
                    @if($statuses)
                        <option value="" {{ old('status_id') ? (old('status_id') == "" ? 'selected' : '') : '' }}>Please Select</option>
                        @foreach($statuses as $id => $status)
                            <option value="{{ strtolower($status) }}" {{ old('status_id') ? (old('status_id') == "$id" ? 'selected' : '') : '' }}>{{ $status }}</option>
                        @endforeach
                        <option value="completed">Completed</option>
                    @endif
                </select>
                @error('status_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
             <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }} mt-3">
                <label for="description">Project Description *</label>
                <textarea id="project_description" name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', isset($project) ? $project->description : '') }}</textarea>
                @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <span id="project_description_error" style="color:red;"></span>
                <p class="helper-block">
                    {{ trans('cruds.project.fields.description_helper') }}
                </p>
            </div>
            <label class="form-label mt-3">Add Images/Files </label>
            <input type="file" class="form-control" name="image" value="">
            <div class="mt-3">
                <input class="btn btn-danger" type="submit" id="formsubmit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>  
    </div>
</div> 
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script>

    $(document).ready(function(){
        $('#paymethod').change(function(){
            var budget = document.getElementById('paymethod').value;
            sessionStorage.setItem('budget', budget);
        });
        var budget_value = sessionStorage.getItem("budget");
        
        if(budget_value == 'hourly'){
            document.getElementById('per_hour_budget').style.removeProperty('display');
        }
        else if(budget_value == 'fixed'){
            document.getElementById('total_budget').style.removeProperty('display');
        }
        if(document.getElementById('paymethod').value == '')
        {
            sessionStorage.removeItem("budget");
        }
    });
</script>
@endsection
