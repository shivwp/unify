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
                        Show Contract Details
                    </div>
                    <br>
                    <div class="card-body">
                        <div class="mb-2">
                            <div class="row">
                                <!-- <h5><strong>Basic Information</strong></h5> -->
                                <div class="col-md-4 ">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Contract Title</strong></h6>
                                        <p class="mb-0">{{ $contract->title ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4 ">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Project Title</strong></h6>
                                        @if(isset($contract))
                                            @if(count($contract->projects)>0)
                                                @foreach($contract->projects as $project)
                                                    <p class="mb-0">{{ $project->name }}</p>
                                                @endforeach
                                            @else
                                                <p class="mb-0">{{ '-' }}</p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Freelancer Name</strong></h6>
                                        @if(isset($contract))
                                            @if(count($contract->users)>0)
                                                @foreach($contract->users as $freelancer)
                                                    <p class="mb-0">{{ $freelancer->first_name }}</p>
                                                @endforeach
                                            @else
                                                <p class="mb-0">{{ '-' }}</p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Freelancer Email</strong></h6>
                                        @if(isset($contract))
                                            @if(count($contract->users)>0)
                                                @foreach($contract->users as $freelancer)
                                                    <p class="mb-0">{{ $freelancer->email }}</p>
                                                @endforeach
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
                                            @if(count($contract->client)>0)
                                                @foreach($contract->client as $client)
                                                    <p class="mb-0">{{ $client->first_name }}</p>
                                                @endforeach
                                            @else
                                                <p class="mb-0">{{ '-' }}</p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Client Email</strong></h6>
                                        @if(isset($contract))
                                            @if(count($contract->client)>0)
                                                @foreach($contract->client as $client)
                                                    <p class="mb-0">{{ $client->email }}</p>
                                                @endforeach
                                            @else
                                                <p class="mb-0">{{ '-' }}</p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Budget Type</strong></h6>
                                        <p class="mb-0">{{ ucfirst($contract->budget_type) ?? '-' }}</p>
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
                                        <h6><strong>Weekly Limit</strong></h6>
                                        <p class="mb-0">{{ $contract->weekly_limit.' hours' ?? '-' }}</p>
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
                                        <h6><strong>Date</strong></h6>
                                        <p class="mb-0">{{ $contract->date ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">  
                                        <h6><strong>Contract Image</strong></h6>
                                        @if(!empty($contract->image))
                                            <div class="even mt-3">
                                                
                                                <div class="parc">
                                                    <span class="pip" data-title="{{$contract->image}}">
                                                        <img src="{{ url('/images/proposals').'/'.$contract->image ?? "" }}" alt="" width="150" height="100">
                                                    </span>
                                                </div>
                                            </div>
                                        @else
                                        <p class="mb-0"> No Image Found </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 listViewclr">  
                                        <h6><strong>Cover Letter</strong></h6>
                                        <p class="mb-0">{{ $contract->cover_letter ?? '-' }}</p>
                                    </div>
                                </div> 

                            </div> 
                            <a class="btn btn-warning btn_back" href="{{ url()->previous() }}">
                                {{ 'Back' }}
                            </a>
                        </div>
                
                
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
