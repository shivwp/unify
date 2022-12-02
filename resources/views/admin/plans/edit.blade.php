@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
            
                <div class="card">
                    <div class="card-header">
                        <h5>Edit Plan</h5>
                    </div>
                    <hr class="m-0">
                    <div class="card-body">
                        <form action="{{ route("admin.plan.update", [$service->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <label for="name">Plan Title</label>
                                <input type="text" id="name" name="plans_title" class="form-control" value="{{$service->plans_title}}" required>
                                @if($errors->has('plans_title'))
                                    <p class="help-block">
                                        {{ $errors->first('service_name') }}
                                    </p>
                                @endif
                            
                            </div>
                            <div class="form-group {{ $errors->has('client_id') ? 'has-error' : '' }} mt-3">
                                <label for="client">Validity</label>
                                <select name="validity" id="client" class="form-control " required>
                                <option value="1 month" @if($service->validity=="1 month") selected @endif >One Month</option>
                                    <option value="3 month" @if($service->validity=="3 month") selected @endif >Three Month</option>
                                    <option value="6 month" @if($service->validity=="6 month") selected @endif >Six Month</option>
                                    <option value="1 year" @if($service->validity=="1 year") selected @endif >One Year</option>
                             </select>
                               
                            </div>
                            <div class="form-group {{ $errors->has('client_id') ? 'has-error' : '' }} mt-3">
                                <label for="client">Description</label>
                                <textarea name="description" id="" cols="20" rows="5" class="form-control" value="">{{$service->description}}</textarea>
                              </div>
                              <div class="form-group {{ $errors->has('client_id') ? 'has-error' : '' }} mt-3">
                                <label for="client">Amount</label>
                                <input type="text" id="name" name="amount" class="form-control" value="{{$service->amount}}" required>
                              </div>
                              <div class="form-group mt-3">
                                <label class="form-label">Select Services</label>
                               
                                <select name="services[]" class="form-control select2" id="select-category" multiple required>
                                 
                                    @if(count($services) > 0)
                                    @foreach($services as $id => $items)
                                
                                     <option value="{{ $id }}" {{isset($service) && $service->services->contains($id) ? 'selected' : '' }}>{{$items}}</option>
                                    @endforeach
                                    @endif
                                </select>
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
