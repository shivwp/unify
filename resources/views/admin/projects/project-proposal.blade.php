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
                                        <p class="mb-0">{{ $proposal->name ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Freelancer Name</strong></h6>
                                        @if(count($proposal->users)>0)
                                            @foreach($proposal->users as $user)
                                                @if(!empty($user->first_name))
                                                    <p class="mb-0">{{ $user->first_name }}</p>
                                                @else
                                                    <p class="mb-0">{{ '-' }}</p>
                                                @endif
                                            @endforeach
                                        @else
                                            <p class="mb-0">{{ '-' }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Freelancer Email</strong></h6>
                                        @if(count($proposal->users)>0)
                                            @foreach($proposal->users as $user)
                                                @if(!empty($user->email))
                                                    <p class="mb-0">{{ $user->email }}</p>
                                                @else
                                                    <p class="mb-0">{{ '-' }}</p>
                                                @endif
                                            @endforeach
                                        @else
                                            <p class="mb-0">{{ '-' }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Client Name</strong></h6>
                                        @if(count($proposal->client)>0)
                                            @foreach($proposal->client as $client)
                                                @if(!empty($client->first_name))
                                                    <p class="mb-0">{{ $client->first_name }}</p>
                                                @else
                                                    <p class="mb-0">{{ '-' }}</p>
                                                @endif
                                            @endforeach
                                        @else
                                            <p class="mb-0">{{ '-' }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Client Email</strong></h6>
                                        @if(count($proposal->client)>0)
                                            @foreach($proposal->client as $client)
                                                @if(!empty($client->email))
                                                    <p class="mb-0">{{ $client->email }}</p>
                                                @else
                                                    <p class="mb-0">{{ '-' }}</p>
                                                @endif
                                            @endforeach
                                        @else
                                            <p class="mb-0">{{ '-' }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">  
                                        <h6><strong>Budget Type</strong></h6>
                                        <p class="mb-0">{{ ucfirst($proposal->budget_type) ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Amount</strong></h6>
                                        <p class="mb-0">{{ number_format((float)$proposal->amount, 2, '.', '') }}$</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">  
                                        <h6><strong>Weekly Limit</strong></h6>
                                        <p class="mb-0">{{ $proposal->weekly_limit ? $proposal->weekly_limit .' hour' : '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">  
                                        <h6><strong>Status</strong></h6>
                                        <p class="mb-0">{{ $proposal->send_proposal_status ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Image</strong></h6>
                                        @if(!empty($proposal->image))
                                            <div class="even mt-3">
                                                
                                                <div class="parc">
                                                    <span class="pip" data-title="{{$proposal->image}}">
                                                        <img src="{{ url('/images/proposals').'/'.$proposal->image ?? "" }}" alt="" width="150" height="100">
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
