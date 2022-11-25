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
                     Project Details
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

                                <!-- Tab goes Here -->
                                 <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
            <div class="col-lg-12">
                <div class="">
                    <div class="nav-align-top mb-4">
                        <ul class="nav nav-tabs nav-fill" role="tablist">
                          <li class="nav-item">
                            <button
                              type="button"
                              class="nav-link active"
                              role="tab"
                              data-bs-toggle="tab"
                              data-bs-target="#navs-justified-home"
                              aria-controls="navs-justified-home"
                              aria-selected="true"
                            >
                              Proposals
                              <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-danger"></span>
                            </button>
                          </li>
                          <li class="nav-item">
                            <button
                              type="button"
                              class="nav-link"
                              role="tab"
                              data-bs-toggle="tab"
                              data-bs-target="#navs-justified-contract"
                              aria-controls="navs-justified-contract"
                              aria-selected="false"
                            >
                              Contract
                              <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-danger">@if(!empty($contract)) {{ '1' }} @endif</span>
                            </button>
                          </li>        
                          <li class="nav-item">
                            <button
                              type="button"
                              class="nav-link"
                              role="tab"
                              data-bs-toggle="tab"
                              data-bs-target="#navs-justified-messages"
                              aria-controls="navs-justified-messages"
                              aria-selected="false"
                            >
                              Hired
                            </button>
                          </li>
                        </ul>
                        <div class="tab-content">
                          <div class="tab-pane fade show active" id="navs-justified-home" role="tabpanel">
                            <h5 class="card-header">Proposals/Bids</h5>
                            <div class="table-responsive text-nowrap">
                            @if(count($proposals)>0)
                              <table class="table">
                                <thead>
                                  <tr class="ml-3">
                                    
                                    <th>Freelancer Name</th>
                                    <th>Email</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Submit Date</th>
                                    <th>Action</th>
                                  </tr>
                                </thead>

                                <tbody class="table-border-bottom-0">

                                  @foreach($proposals as $item)

                                  @foreach($item->users as $user)
                                  

                                  <tr>
                                    <td>
                                        @if(!empty($user->name)) {{$user->first_name}} @endif
                                    </td>
                                    <td>
                                        @if(!empty($user->email)) {{$user->email}} @endif
                                    </td>
                                    
                                    <td>
                                        {{ number_format((float)$item->bid_amount, 2, '.', '') }}$
                                    </td>
                                    <td>
                                        @if($item->status=="pending") <span class="badge bg-label-warning me-1">Pending</span>
                                        @elseif($item->status=="approve") <span class="badge bg-label-success me-1">Approve</span> 
                                        @elseif($item->status=="reject") <span class="badge bg-label-danger me-1">Reject</span> 
                                        @elseif($item->status=="hold") <span class="badge bg-label-primary me-1">On-Hold</span> @endif
                                    </td>
                                    <td>
                                        {{$item->created_at->toFormattedDateString()}}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.project-proposal',$item->id) }}">
                                            <button class="btn btn-sm btn-icon me-2" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>View</span>"><i class="bx bx-show mx-1"></i></button>
                                        </a>
                                    </td>
                                  </tr>
                                  @endforeach
                                  @endforeach
                                 
                                </tbody>
                                
                              </table>
                              {!! $proposals->links() !!}
                              @else
                              <h6 class="card-header">No Proposal On This Job</h6>
                              @endif
                            </div>
                          </div>
                          <div class="tab-pane fade" id="navs-justified-contract" role="tabpanel">
                            <h5 class="card-header">Contract</h5>
                            <div class="table-responsive text-nowrap">
                            @if(!empty($contract))
                              <table class="table">
                                <thead>
                                  <tr class="ml-3">
                                    
                                    <th>Client Name</th>
                                    <th>Freelancer Name</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Contract Date</th>
                                    <th>Action</th>
                                  </tr>
                                </thead>

                                <tbody class="table-border-bottom-0">                         

                                  <tr>
                                    <td>
                                        <!-- @if(!empty($user->name)) {{$user->first_name}} @endif -->
                                        @if(isset($contract))
                                            @if(count($contract->client)>0)
                                                @foreach($contract->client as $client)
                                                    <p class="mb-0">{{ $client->first_name }}</p>
                                                @endforeach
                                            @else
                                                <p class="mb-0">{{ '-' }}</p>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <!-- @if(!empty($user->email)) {{$user->email}} @endif -->
                                        @if(isset($contract))
                                            @if(count($contract->users)>0)
                                                @foreach($contract->users as $user)
                                                    <p class="mb-0">{{ $user->first_name }}</p>
                                                @endforeach
                                            @else
                                                <p class="mb-0">{{ '-' }}</p>
                                            @endif
                                        @endif
                                    </td>
                                    
                                    <td>
                                        {{ number_format((float)$contract->amount, 2, '.', '') }}$
                                    </td>
                                    <td>
                                        @if($contract->status=="pending") <span class="badge bg-label-warning me-1">Pending</span>
                                        @elseif($contract->status=="approve") <span class="badge bg-label-success me-1">Approve</span> 
                                        @elseif($contract->status=="reject") <span class="badge bg-label-danger me-1">Reject</span> 
                                        @elseif($contract->status=="hold") <span class="badge bg-label-primary me-1">On-Hold</span> @endif
                                    </td>
                                    <td>
                                        {{$contract->date}}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.project-contract',$contract->id) }}">
                                            <button class="btn btn-sm btn-icon me-2" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>View</span>"><i class="bx bx-show mx-1"></i></button>
                                        </a>
                                    </td>
                                  </tr>
                                 
                                </tbody>
                                
                              </table>
                              @else
                              <h6 class="card-header">No Contract On This Job</h6>
                              @endif
                            </div>
                          </div>
                          <div class="tab-pane fade" id="navs-justified-messages" role="tabpanel">
                            <p>
                               You don't have any offers yet
                            </p>
                          </div>
                        </div>
                      </div>
                   
                  </div>
            </div>
            </div>
        
          </div>



                            </div>
                         <!--    @if(count($proposals)>0)
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
                            @endif -->
                            <a class="btn btn-warning btn_back" href="{{ url()->previous() }}">
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
