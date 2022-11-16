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
                                        <h6><strong>Client Name</strong></h6>
                                        <p class="mb-0">{{ $project->client->name ?? '-' }}</p>
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

                            </div>
                            @if(count($proposals)>0)
                            <div class="card-header p-0 mt-4 mb-2">
                               Bid/Proposals
                            </div>

                            <div class="card-body p-0 mt-3">
                                <div class="table-responsive">
                                    <table class=" table table-bordered table-striped table-hover datatable datatable-ProjectStatus">
                                        <thead>
                                            <tr>
                                                <th width="10"></th>
                                                <th>Id</th>
                                                <th>Project</th>
                                                <th>Freelancer</th>
                                                <th>Status</th>
                                                <th>Bid-Amount</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($proposals as $key => $item)
                                            <tr data-entry-id="{{ $item->id }}">
                                                <td></td>
                                                <td>
                                                    {{ $item->id ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $item->project->name ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $item->freelancer->name ?? '' }}   
                                                </td>
                                                <td>
                                                    {{ $item->status ?? '' }} 
                                                </td>
                                                <td>
                                                    ${{ $item->amount ?? '' }}   
                                                </td>
                                                <td>
                                                    @can('project_category_edit')
                                                        <a class="btn btn-xs btn-info" href="proposal-update/{{$item->id}}">
                                                            {{ trans('global.edit') }}
                                                        </a>
                                                    @endcan

                                                    @can('project_category_delete')
                                                        <form action="{{ route('admin.proposal.destroy', $item->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                            @csrf    
                                                            <input type="hidden" name="_method" value="DELETE">
                                                                
                                                            <input type="hidden" name="id" value="{{$item->id}}">
                                                            <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                                        </form>
                                                    @endcan

                                                </td>

                                            </tr>
                                        @endforeach 
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif
                            <a class="btn btn-success btn_back" href="{{ url()->previous() }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                
                        <nav class="mb-3">
                            <div class="nav nav-tabs">
                            </div>
                        </nav>
                        <div class="tab-content">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
