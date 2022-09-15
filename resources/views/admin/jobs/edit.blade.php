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
       Edit Job
    </div>

    <div class="card-body">
        <form action="{{ route("admin.jobs.update", [$job->id]) }}" method="POST" enctype="multipart/form-data" id="formId">
            @csrf
            @method('PUT')
          
            <div class="form-group ">
                <label for="client ">Select User</label>
                <select name="user_id" class="form-control mt-2" required>
                    <option value="" >Select</option>
                    @if(isset($user))
                        @foreach($user as  $item)
                            <option value="{{ $item->id }}" @if ($item->id==$job->user_id) selected @endif >{{ $item->name }}</option>
                        @endforeach
                    @endif
                </select>
                {{-- @if($errors->has('client_id'))
                    <p class="help-block">
                        {{ $errors->first('client_id') }}
                    </p>
                @endif --}}
            </div>
            <div class="form-group mt-3">
                <label for="client ">Select Project</label>
                <select name="project_id" class="form-control mt-2" required>
                    <option value="" >Select</option>
                    @if(isset($project))
                        @foreach($project as  $item)
                            <option value="{{ $item->id }}"  @if ($item->id==$job->project_id) selected @endif>{{ $item->name }}</option>
                        @endforeach
                    @endif
                </select>
                {{-- @if($errors->has('client_id'))
                    <p class="help-block">
                        {{ $errors->first('client_id') }}
                    </p>
                @endif --}}
            </div>
            <div class="form-group  mt-3">
                <label for="client ">Status</label>
                <select name="status_id1"  class="form-control mt-2" required>
                    
                    @if(isset($statuses))
                        @foreach($statuses as  $item)
                            <option value="{{ $item->id }}" @if ($item->id==$job->status_id1) selected @endif>{{ $item->name }}</option>
                        @endforeach
                    @endif
                </select>
              
            </div>
        <input type="hidden" name="job_id"  value="{{$job->id}}">
            <div class="mt-3">
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div> 
@endsection
