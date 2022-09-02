@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
            
<div class="card">
    <div class="card-header">
       Edit Proposal
    </div>

    <div class="card-body">
        <form action="{{ route("admin.proposal.update", [$proposals->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" id="project_id" name="project_id" value="{{$proposals->project_id}}">
            <input type="hidden" id="servicefee" value="{{$servicefee->value}}">
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Project/job</label>
               <select  id="Project"  class="form-control mt-2" required>
               <option >Select Project/Job</option>
                @foreach($Project as $item)
                  <option value="{{$item->id}}" data="{{$item->payment_base}}"  @if($proposals->project_id==$item->id) selected @endif>{{$item->name}}</option>
                  @endforeach
                </select>
               
            </div>
            
            
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} mt-3">
                <label for="name" id="amount_label">Amount</label>
              
               <input type="text" name="amount" value="{{$proposals->amount}}" class="form-control mt-2 amount" id="amount" >
                @if($errors->has('amount'))
                    <p class="help-block">
                        {{ $errors->first('amount') }}
                    </p>
                @endif
            </div>
            @php
            $unify_service_fee=($proposals->amount*$servicefee->value)/100;
           $freelancer_earning=$proposals->amount- $unify_service_fee;
            @endphp
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} mt-3">
                <label for="name">{{$servicefee->value}}% Unify Service Fee</label>
              
               <input type="text"  class="form-control mt-2 " id="unify_service_fee" value="{{$unify_service_fee}}"    disabled >
              
            </div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} mt-3">
                <label for="name">You'll receive</label>
              
               <input type="text" class="form-control mt-2 " id="freelancr_amount" value="{{$freelancer_earning}}"    disabled >
              
            </div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} mt-3">
                <label for="name">Freelancer</label>
               <select name="freelancer" id="Project"  class="form-control mt-2" required>
               <option >Select Freelancer</option>
                @foreach($freelancer as $item)
                  <option value="{{$item->id}}" @if($proposals->freelancer_id==$item->id) selected @endif>{{$item->name}}</option>
                  @endforeach
                </select>
               
            </div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} mt-3">
                <label for="name">Status</label>
               <select name="status" id="Project"  class="form-control mt-2" required>
                  <option value="pending"  @if($proposals->status=="pending") selected @endif>Pending</option>
                  <option value="hold"  @if($proposals->status=="hold") selected @endif>On Hold</option>
                  <option value="approved"  @if($proposals->status=="approved") selected @endif>Approved</option>
                  <option value="rejected"  @if($proposals->status=="rejected") selected @endif>Rejected</option>
                    </select>
               
            </div>
            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }} mt-3">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control mt-2">{{ old('description', isset($proposals) ? $proposals->description : '') }}</textarea>
                @if($errors->has('description'))
                    <p class="help-block">
                        {{ $errors->first('description') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.project.fields.description_helper') }}
                </p>
            </div>
            @if(!empty($proposals->images))
                @php
                    $value = json_decode($proposals->images);
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
                <input type="hidden" name="image1" id="gallery_img" value="{{$proposals->images}}">
            @endif
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
