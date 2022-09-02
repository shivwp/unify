@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
            
<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.project.title') }}
    </div>

    <div class="card-body ">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>ID</th>
                        <td>{{ $project->id }}</td>
                    </tr>
                    <tr>
                        <th>Project Name</th>
                        <td>{{ $project->name }}</td>
                    </tr>
                    <tr>
                        <th>Project Description</th>
                        <td>{!! $project->description !!}</td>
                    </tr>
                    <tr>
                        <th>Project Client</th>
                        <td>{{ $project->client->first_name ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Start Date</th>
                        <td> {{ date('j \\ F Y', strtotime($project->start_date)) }}</td>
                    </tr>
                    @php
                    $date1 = $project->end_date;
                    $date2 = $project->start_date;
                    $datetime1 = new DateTime($date1);
                    $datetime2 = new DateTime($date2);
                    $interval = $datetime1->diff($datetime2);
                    $days = $interval->format('%a')
                    @endphp
                    <tr>
                        <th>End Date</th>
                        <td>{{ date('j \\ F Y', strtotime($project->end_date)) }}</td>
                    </tr>
                    <tr>
                        <th>Project Duration</th>
                        <td>{{ $days }}</td>
                    </tr>
                    
                    <tr>
                        <th>Categories</th>
                        <td>
                            @foreach($project->categories as $vlu)
                            {{$vlu->name.','}}
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>Skills</th>
                        <td>@foreach($project->skills as $skill)
                            {{$skill->name.','}}
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>Listing Type</th>
                        <td>@foreach($project->listingtypes as $list)
                            {{$list->name.','}}
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>Payment Base</th>
                        <td>{{ $project->payment_base }}</td>
                    </tr>
                    @if($project->payment_base=='fixed')
                        <tr>
                        <th>Total Budget</th>
                        <td>${{ $project->total_budget }}</td>
                    </tr>
                    @endif
                    @if($project->payment_base=='hourly')
                        <tr>
                        <th>Per Hour Budget</th>
                        <td>${{ $project->per_hour_budget }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>Level</th>
                        <td>{{ $project->level }}</td>
                    </tr>
                    <tr>
                        <th>English Level</th>
                        <td>{{ $project->english_level }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $project->status->name ?? '' }}</td>
                    </tr>
                    <tr>
                    <tr>
                        <th>Total bid/proposal</th>
                        <td>{{ count($proposals)}}</td>
                    </tr>
                    <tr>
                        <th>Project Images</th>
                        <td>@if(!empty($project->project_images))
                                @php
                                $value = json_decode($project->project_images);
                                @endphp
                                @if(!empty($value))
                                <div class="even" style="display: flex; flex-wrap: wrap; justify-content: flex-start;">
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
                                <input type="hidden" name="image1" id="gallery_img" value="{{$project->project_images}}">
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            @if(count($proposals)>0)
            <div class="card-header p-0 mt-4 mb-2">
       Bid/Proposals
    </div>

    <div class="card-body p-0 mt-3">
    <div class="table-responsive">
        <table class=" table table-bordered table-striped table-hover datatable datatable-ProjectStatus">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                      Id
                    </th>
                    <th>
                  Project
                    </th>
                    <th>
                  Freelancer
                    </th>
                    <th>
                  Status
                    </th>
                    <th>
                   Bid-Amount
                    </th>
                    <th>
                      Action
                    </th>
                </tr>
            </thead>
            <tbody>
             @foreach($proposals as $key => $item)
                    <tr data-entry-id="{{ $item->id }}">
                        <td>

                        </td>
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
            <a style="margin-top:20px;" class="btn btn-success" href="{{ url()->previous() }}">
                Back to list
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
@endsection
