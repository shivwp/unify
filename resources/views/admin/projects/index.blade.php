@extends('layouts.master') @section('content')
    <style type="text/css">
        button.btn.filter_btn {
            background-color: #696cff;
            color: white;
        }
        .from-width{
            width: 700px;
            padding-left: 53px;
        }
        .finput{
            width: 200px;
            margin-right: 8px;
        }
        .input-daterange{
            width: 300px;
        }
        .sform{
            margin-left: 60px;
            text-align: center;
        }
        .sbutton{
            margin-left: 2px;
        }
    </style>

<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
                <div class="row tabelhed" style="height: 90px">
                    <div class="col-lg-2 col-md-2 col-sm-2 d-flex">
                        @can('project_create')
                        <a class="btn-sm ad-btn create_btn text-center pt-2" href="{{ route("admin.projects.create") }}"> Add</a>
                        @endcan
                        <button id="btnExport" onClick="fnExcelReport()" class="btn-sm ad-btn clearfix">Excel</button>
                    </div>
                    <?php if(!empty($_GET['project_status_filter'])){$status_filter= $_GET['project_status_filter'];}else{ $status_filter='';} ?>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <form action="" method="get" id="status_filter_form">
                            <select class="form-control sform" id="project_status_filter" name="project_status_filter">
                                <option value="">Select Status</option>
                                @if($statuses)
                                    @foreach($statuses as $id => $status)
                                        <option value="{{ strtolower($status) }}" {{ isset($statuses) ? (($status_filter == strtolower($status)) ? 'selected' : '') : '' }}>{{ $status }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </form>
                    </div>
                   {{-- <div class="col-lg-3 col-md-3 col-sm-3 "> 
                                               <div class="right-item" >
                            <form class="d-flex" method="GET" action="">
                                <button class="btn-sm search-btn" type="submit"  style="margin-left:6px"> <i class="fa fa-search pl-3" aria-hidden="true"></i> </button>
                                <!-- @if(isset($_GET['page']))<input type="hidden" name="page" value="{{$_GET['page']}}">@endif -->
                                @if(isset($_GET['pagination']))<input type="hidden" name="pagination" value="{{$_GET['pagination']}}">@endif
                                @if(isset($_GET['project_status_filter']))<input type="hidden" name="project_status_filter" value="{{$_GET['project_status_filter']}}">@endif
                                @if(isset($_GET['start_date']))<input type="hidden" name="start_date" value="{{$_GET['start_date']}}">@endif
                                @if(isset($_GET['end_date']))<input type="hidden" name="end_date" value="{{$_GET['end_date']}}">@endif
                            </form>
                        </div>
                    </div>--}}
                    <div class="col-lg-5 col-md-5 col-sm-5 p-0 pl-2">
                         <?php 
                        if(!empty($_GET['search'])){
                            $search= $_GET['search'];
                        }else{ 
                            $search='';
                        }?>

                        <form action="" method="GET" class="d-flex from-width">
                            <input type="text" name="search" class="form-control finput" value="{{$search}}" style="height: 39px;" placeholder="Enter Project Name">

                                <div class="input-group input-daterange">
                                    <input type="text" name="start_date"  id="datepicker" onfocus="(this.type='date')" value="{{Request::get('start_date') ?? ''}}" placeholder ="Start Date" class="form-control"  />
                                    <span class="input-group-text">To</span>
                                    <input type="text" name="end_date" id="Enddate" value="{{Request::get('end_date') ?? ''}}" onfocus="(this.type='date')" placeholder ="End Date" class="form-control"/>
                                </div>
                            <div class="d-flex sbutton">
                                <button class="btn-sm search-btn" type="submit">  
                                    <i class="fa fa-search pl-3" aria-hidden="true"></i> 
                                </button>
                                <a href="{{url('admin/projects')}}">
                                    <i class="fa fa-refresh pl-3 redirect-icon" aria-hidden="true"></i>
                                </a>
                            </div>
                            @if(isset($_GET['search']))<input type="hidden" name="search" value="{{$_GET['search']}}">@endif
                            @if(isset($_GET['pagination']))<input type="hidden" name="pagination" value="{{$_GET['pagination']}}">@endif
                            @if(isset($_GET['project_status_filter']))<input type="hidden" name="project_status_filter" value="{{$_GET['project_status_filter']}}">@endif
                        </form>
                    </div>
                </div>
                @if(Session::has('error'))
                    <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error') }}</p>
                @endif
                @if(Session::has('success'))
                    <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success') }}</p>
                @endif
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-6">
                                <h5 class="m-0 mb-1">Projects</h5>
                            </div>
                            <div class="col-xl-6 ">
                                <div class="row" style="float: right">
                                    <div class="col-xl-12 d-flex" style="float: right">
                                        <div class="items paginatee">
                                            <form id="pagination">
                                                <select class="form-select m-0" name="pagination" style="width: 94%; font-size: 11px;  height: 32px;" id="pagination" aria-label="Default select example">
                                                    <option value="10" @if($pagination=='10') selected @endif>10</option>
                                                    <option  value='20' @if($pagination=='20') selected @endif>20</option>
                                                    <option value='30' @if($pagination=='30') selected @endif>30</option>
                                                    <option value='40' @if($pagination=='40') selected @endif>40</option>
                                                    <option value='50' @if($pagination=='50') selected @endif>50</option>
                                                </select>
                                                <!-- @if(isset($_GET['page']))<input type="hidden" name="page" value="{{$_GET['page']}}">@endif -->
                                                @if(isset($_GET['search']))<input type="hidden" name="search" value="{{$_GET['search']}}">@endif
                                                @if(isset($_GET['start_date']))<input type="hidden" name="start_date" value="{{$_GET['start_date']}}">@endif
                                                @if(isset($_GET['end_date']))<input type="hidden" name="end_date" value="{{$_GET['end_date']}}">@endif
                                                @if(isset($_GET['project_status_filter']))<input type="hidden" name="project_status_filter" value="{{$_GET['project_status_filter']}}">@endif
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top: -20px">
                        <div class="table card-datatable">
                            <table id="exportToTable" class=" datatables-basic table border-top table table-bordered table-striped table-hover datatable datatable-Project example">
                                <thead>
                                    <tr>
                                        <th>S No.</th>
                                        <th>Project Name</th>
                                        <th>Type</th>
                                        <th>Scope</th>
                                        <th>Publish date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="">
                                    @if(count($projects)>0)
                                        @php $i=1 @endphp
                                        @foreach($projects as $key => $project)
                                            <tr data-entry-id="{{ $project->id }}">
                                                <td>{{$i++}}</td>
                                                <td>{{ $project->name ?? '' }}</td>
                                                {{--<td>
                                                   <a href="{{ url("admin/users", [$project->client->id]) }}"><p class="C_nme">{{ $project->client->name ?? '' }}</p></a>
                                                </td>--}}
                                                <td>{{ ($project->type=='long_term') ?'Long Term' :'Short Term' }}</td>
                                                <td>{{ $project->scop ?? '' }}</td>
                                                <td>{{$project->created_at->toFormattedDateString()}}</td>
                                                <!-- <td>
                                                    @if($project->budget_type=='fixed')
                                                       
                                                        ${{ number_format((float)$project->total_budget, 2, '.', '') }}

                                                    @endif
                                                    @if($project->budget_type=='hourly')
                                                       
                                                        ${{ number_format((float)$project->per_hour_budget, 2, '.', '') }}

                                                    @endif
                                                </td> -->
                                                <td>
                                                    @if($project->status == "publish")
                                                        <span class="badge badge-publish">{{ $project->status ?? '' }}</span>
                                                    @elseif($project->status == "draft")
                                                        <span class="badge btn-info">{{ $project->status ?? '' }}</span>
                                                    @elseif($project->status == "complete")
                                                        <span class="badge btn-success">{{ $project->status ?? '' }}</span>
                                                    @elseif($project->status == "close")
                                                        <span class="badge btn-danger">{{ $project->status ?? '' }}</span>
                                                    @elseif($project->status == "active")
                                                        <span class="badge btn-primary">{{ $project->status ?? '' }}</span>

                                                    @endif
                                                </td>
                                                <td>
                                                    @can('project_show')
                                                    <a href="{{ route('admin.projects.show', $project->id) }}">
                                                        <button class="btn btn-sm btn-icon me-2" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>View</span>"><i class="bx bx-show mx-1"></i></button>
                                                    </a>
                                                    @endcan
                                                  
                                                    @can('project_edit')
                                                    <a  href="{{ route('admin.projects.edit', $project->id) }}">
                                                        <button class="btn btn-sm btn-icon me-2" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Edit</span>"><i class="bx bx-edit"></i></button>
                                                    </a>
                                                    @endcan

                                                    @can('project_delete')
                                                    <a href="project-delete/{{$project->id}}"> 
                                                        <button class="btn btn-sm btn-icon delete-record" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Delete</span>" onclick="return confirm('{{ trans('global.areYouSure') }}');"><i class="bx bx-trash"></i></button>
                                                    </a>
                                                    @endcan

                                                </td>
                                            </tr>
                                        @endforeach 
                                    @else
                                        <tr class="mb-3">No Record Found</tr>
                                    @endif
                                </tbody>
                            </table>
                            {{ request()->get('pagination')}}
                            @if((request()->get('search')) || (request()->get('pagination')) || (request()->get('start_date')) || (request()->get('end_date')) || (request()->get('project_status_filter')))
                                {{ $projects->appends(['search' => request()->get('search'), 'pagination' => request()->get('pagination'),'start_date' => request()->get('start_date'), 'end_date' => request()->get('end_date'), 'project_status_filter' => request()->get('project_status_filter')])->links() }}
                            @else
                                {{ $projects->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        @can('project_delete')
        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.projects.massDestroy') }}",
            className: 'btn-danger',
            action: function (e, dt, node, config) {
              var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                  return $(entry).data('entry-id')
              });

              if (ids.length === 0) {
                alert('{{ trans('global.datatables.zero_selected') }}')

                return
            }

            if (confirm('{{ trans('global.areYouSure') }}')) {
                $.ajax({
                  headers: {'x-csrf-token': _token},
                  method: 'POST',
                  url: config.url,
                  data: { ids: ids, _method: 'DELETE' }})
                .done(function () { location.reload() })
            }
        }
    }
    dtButtons.push(deleteButton)
    @endcan

    $.extend(true, $.fn.dataTable.defaults, {
        order: [[ 1, 'desc' ]],
        pageLength: 100,
    });
    $('.datatable-Project:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
        .columns.adjust();
    });
})

</script> 
@endsection
