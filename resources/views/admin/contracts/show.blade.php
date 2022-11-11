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
                                        <h6><strong>Project Title</strong></h6>
                                        <p class="mb-0">{{ $contract->project_title ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Freelancer Name</strong></h6>
                                        @if(isset($contract))
                                            @if(!empty($contract->freelancer->first_name))
                                                <p class="mb-0">{{ $contract->freelancer->first_name }}</p>
                                            @else
                                                <p class="mb-0">{{ '-' }}</p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Client Name</strong></h6>
                                        @if(isset($contract))
                                            @if(!empty($contract->client->first_name))
                                                <p class="mb-0">{{ $contract->client->first_name }}</p>
                                            @else
                                                <p class="mb-0">{{ '-' }}</p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Project Type</strong></h6>
                                        <p class="mb-0">{{ ucfirst($contract->type) ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">  
                                        <h6><strong>Amount</strong></h6>
                                        <p class="mb-0">{{ number_format((float)$contract->amount, 2, '.', '') }}$</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Status</strong></h6>
                                        <p class="mb-0">{{ $contract->status ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Start Date</strong></h6>
                                        <p class="mb-0">{{ $contract->start_time ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">  
                                        <h6><strong>End Date</strong></h6>
                                        <p class="mb-0">{{ $contract->end_time ?? '-' }}</p>
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
