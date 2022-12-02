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
                       <h5> Show Contract </h5>
                    </div>
                    <br>
                    <div class="card-body">
                        <div class="mb-2">
                            <div class="row">
                                <h5><strong>Contract Information</strong></h5>
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
                            <br>
                            <div class="row">
                                <h5><strong>Project Information</strong></h5>
                                @if(isset($contract))
                                    @if(count($contract->projects)>0)
                                        @foreach($contract->projects as $project)
                                            <div class="col-md-4 ">
                                                <div class="p-3 listViewclr">
                                                    <h6><strong>Project Name</strong></h6>
                                                    <p class="mb-0">{{ $project->name ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="p-3 listViewclr">
                                                    <h6><strong>Project Type</strong></h6>
                                                    <p class="mb-0">{{ $project->type ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="p-3 listViewclr">
                                                    <h6><strong>Category</strong></h6>
                                                    <p class="mb-0">{{ $project->categories->name ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="p-3 listViewclr">
                                                    <h6><strong>Scope</strong></h6>
                                                    <p class="mb-0">{{$project->scop ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="p-3 listViewclr">
                                                    <h6><strong>How long will your work take?</strong></h6>
                                                    <p class="mb-0">
                                                        @if($project->project_duration == '6')
                                                            More than 6 months
                                                        @elseif($project->project_duration == '3')
                                                            3 to 6 month
                                                        @elseif($project->project_duration == '1')
                                                            1 to 3 months
                                                        @else
                                                            -
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="p-3 listViewclr">  
                                                    <h6><strong>Skills</strong></h6>
                                                    <p class="mb-0">
                                                        @if(count($project->skills)>0)
                                                            @foreach($project->skills as $skill)
                                                                {{ $skill->name.',' }}
                                                            @endforeach
                                                        @else
                                                            {{ '-' }}
                                                        @endif

                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="p-3 listViewclr">  
                                                    <h6><strong>What level of experience will it need?</strong></h6>
                                                    <p class="mb-0">{{ ucfirst($project->experience_level) ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="p-3 listViewclr">  
                                                    <h6><strong>Budget</strong></h6>
                                                    <p class="mb-0">{{ ucfirst($project->budget_type) ?? '-' }}</p>
                                                </div>
                                            </div>
                                            @if($project->budget_type=='fixed')
                                            <div class="col-md-4">
                                                <div class="p-3 listViewclr">  
                                                    <h6><strong>Maximun Budget</strong></h6>
                                                    <p class="mb-0">${{ number_format((float)$project->price, 2, '.', '') }}</p>
                                                </div>
                                            </div>
                                            @elseif($project->budget_type=='hourly')
                                            <div class="col-md-4">
                                                <div class="p-3 listViewclr">  
                                                    <h6><strong>Minimum Budget</strong></h6>
                                                    <p class="mb-0">${{ number_format((float)$project->min_price, 2, '.', '') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="p-3 listViewclr">  
                                                    <h6><strong>Maximun Budget</strong></h6>
                                                    <p class="mb-0">${{ number_format((float)$project->price, 2, '.', '') }}</p>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="col-md-4">
                                                <div class="p-3 listViewclr">  
                                                    <h6><strong>Status</strong></h6>
                                                    <p class="mb-0">{{ ucfirst($project->status) ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="p-3 listViewclr">
                                                    <h6><strong>Project Images</strong></h6>
                                                    @if(!empty($project->project_images))
                                                        <div class="even mt-3">
                                                            <div class="parc">
                                                                <span class="pip" data-title="{{$project->project_images}}">
                                                                    <img src="{{ url('images/jobs').'/'.$project->project_images ?? '-' }}" alt="" width="150" height="100">
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
                                                    <h6><strong>Project Description</strong></h6>
                                                    <p class="mb-0">{{ $project->description ?? '-' }}</p>
                                                </div>
                                            </div>  
                                        @endforeach
                                    @endif
                                @endif
                            </div> 
                            <a class="btn btn-warning btn_back" href="{{ url()->previous() }}">
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
