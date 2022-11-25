@extends('layouts.master') @section('content')
<style>
    h5 strong{
        padding-left: 10px;
    }
</style>
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        Show Details
                    </div>
                    <br>
                    <div class="card-body">
                        <div class="mb-2">
                            <div class="row">
                                <h5><strong>Basic Information</strong></h5>
                                <div class="col-md-4 ">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>User Name</strong></h6>
                                        <p class="mb-0">{{ $user->name ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>User Email</strong></h6>
                                        <p class="mb-0">{{ $user->email ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Job Link</strong></h6>
                                        <p class="mb-0">{{ $support->job_link}}</p>
                                    </div>
                                </div>
                                    <div class="col-md-4">
                                    <div class="p-3 listViewclr">  
                                        <h6><strong>Description</strong></h6>
                                        <p class="mb-0">{{ $support->description ?? '-'}}</p>
                                    </div>
                                </div>
                               
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">  
                                        <h6><strong>Status</strong></h6>
                                        <p class="mb-0">{{ $support->status ?? '-'}}</p>
                                    </div>
                                </div>
                               
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Images</strong></h6>
                                        @if(!empty($support->image))
                                @php
                                $value = json_decode($support->image);
                                @endphp
                                @if(!empty($value))
                                <div class="even" style="display: flex; flex-wrap: wrap; justify-content: flex-start;">
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
                               
                            @endif
                                    </div>
                                </div>

                            </div> 
                            <br>
                       
                        
                            <a class="btn btn-warning" href="{{ url()->previous() }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                
                
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
