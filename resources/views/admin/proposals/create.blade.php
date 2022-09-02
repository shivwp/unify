@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
            
<div class="card">
    <div class="card-header">
        Create Proposal
    </div>

    <div class="card-body">
        <form action="{{ route("admin.proposal.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="project_id" name="project_id" value="">
            <input type="hidden" id="servicefee" value="{{$servicefee->value}}">
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Project/job</label>
               <select  id="Project"  class="form-control mt-2" required>
               <option >Select Project/Job</option>
                @foreach($Project as $item)
                  <option value="{{$item->id}}" data="{{$item->payment_base}}">{{$item->name}}</option>
                  @endforeach
                </select>
               
            </div>
            
            
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} mt-3">
                <label for="name" id="amount_label">Amount</label>
              
               <input type="text" name="amount" class="form-control mt-2 amount" id="amount" >
                @if($errors->has('amount'))
                    <p class="help-block">
                        {{ $errors->first('amount') }}
                    </p>
                @endif
            </div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} mt-3">
                <label for="name">{{$servicefee->value}}% Unify Service Fee</label>
              
               <input type="text"  class="form-control mt-2 " id="unify_service_fee" value=""    disabled >
              
            </div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} mt-3">
                <label for="name">You'll receive</label>
              
               <input type="text" class="form-control mt-2 " id="freelancr_amount" value=""    disabled >
              
            </div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} mt-3">
                <label for="name">Freelancer</label>
               <select name="freelancer" id="Project"  class="form-control mt-2" required>
               <option >Select Freelancer</option>
                @foreach($freelancer as $item)
                  <option value="{{$item->id}}">{{$item->name}}</option>
                  @endforeach
                </select>
               
            </div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} mt-3">
                <label for="name">Status</label>
               <select name="status" id="Project"  class="form-control mt-2" required>
                  <option value="pending">Pending</option>
                  <option value="hold">On Hold</option>
                  <option value="approved">Approved</option>
                  <option value="rejected">Rejected</option>
                    </select>
               
            </div>
            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }} mt-3">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control mt-2">{{ old('description', isset($project) ? $project->description : '') }}</textarea>
                @if($errors->has('description'))
                    <p class="help-block">
                        {{ $errors->first('description') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.project.fields.description_helper') }}
                </p>
            </div>

            <label class="form-label mt-3">Add Multiple Images/Files </label>
            <input type="file" class="form-control mt-2" name="image[]" value="" multiple>

            <div class="mt-3">
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
            </div>

            
        </form>
    </div>
</div> 
@endsection
