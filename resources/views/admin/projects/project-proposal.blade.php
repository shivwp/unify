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
                                <!-- <h5><strong>Basic Information</strong></h5> -->
                                <div class="col-md-4 ">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Project Name</strong></h6>
                                        <p class="mb-0">{{ $proposal->project_name ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Freelancer Name</strong></h6>
                                        @if(isset($proposal))
                                            @if(!empty($proposal->first_name))
                                                <p class="mb-0">{{ $proposal->first_name }}</p>
                                            @else
                                                <p class="mb-0">{{ '-' }}</p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Freelancer Email</strong></h6>
                                        @if(isset($proposal))
                                            @if(!empty($proposal->email))
                                                <p class="mb-0">{{ $proposal->email }}</p>
                                            @else
                                                <p class="mb-0">{{ '-' }}</p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Bid Amount</strong></h6>
                                        <p class="mb-0">{{ number_format((float)$proposal->bid_amount, 2, '.', '') }}$</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">  
                                        <h6><strong>Platform Fee</strong></h6>
                                        <p class="mb-0">{{ number_format((float)$proposal->platform_fee, 2, '.', '') }}$</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Amount Received</strong></h6>
                                        <p class="mb-0">{{ number_format((float)$proposal->receive_amount, 2, '.', '') }}$</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Project Duration</strong></h6>
                                        <p class="mb-0">{{ $proposal->project_duration ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">  
                                        <h6><strong>Status</strong></h6>
                                        <p class="mb-0">{{ $proposal->status ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Image</strong></h6>
                                        @if(!empty($proposal->image))
                                            <div class="even mt-3">
                                                
                                                <div class="parc">
                                                    <span class="pip" data-title="{{$proposal->image}}">
                                                        <img src="{{ url('/images/profile-image').'/'.$proposal->image ?? "" }}" alt="" width="150" height="100">
                                                    </span>
                                                </div>
                                            </div>
                                        @else
                                        <p class="mb-0"> No Image Found </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Cover Letter</strong></h6>
                                        <p class="mb-0">{{ $proposal->cover_letter ?? '-' }}</p>
                                    </div>
                                </div>

                            </div> 
                            <a class="btn btn-success btn_back" href="{{ url()->previous() }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                
                
                    </div>
                </div>

@endsection
