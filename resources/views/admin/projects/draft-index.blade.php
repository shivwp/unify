@extends('layouts.master') @section('content')
    <style type="text/css">
        button.btn.filter_btn {
            background-color: #696cff;
            color: white;
        }
        .search-btn {
            border: 1px solid #d7cbcb;
            padding: 2px 9px 2px 11px;
            border-radius: 8px;
        }
    </style>

<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
                <div class="row tabelhed justify-content-between">
                    <div class="col-lg-2 col-md-2 col-sm-2 d-flex">
                        @can('project_create')
                        <a class="btn-sm ad-btn create_btn text-center pt-2" href="{{ route("admin.projects.create") }}"> Add</a>
                        @endcan
                        <button id="btnExport" onClick="fnExcelReport()" class="btn-sm ad-btn clearfix">Excel</button>
                    </div>
                    <div class="row col-md-10">
                        <div class="col-lg-5 col-md-5 col-sm-5 "> 
                            <?php 
                            if(!empty($_GET['search'])){
                                $search= $_GET['search'];
                            }else{ 
                                $search='';
                            }?>
                            <div class="right-item" >
                                <form class="d-flex">
                                    <input type="text" name="project_search" class="form-control" value="{{$search}}" style="height: 39px;" placeholder="Enter Project Name" required>
                                    <button class="btn-sm search-btn" type="submit"  style="margin-left:6px"> <i class="fa fa-search pl-3" aria-hidden="true"></i> </button>
                                    <!-- @if(isset($_GET['page']))<input type="hidden" name="page" value="{{$_GET['page']}}">@endif -->
                                    @if(isset($_GET['pagination']))<input type="hidden" name="pagination" value="{{$_GET['pagination']}}">@endif
                                    @if(isset($_GET['project_status_filter']))<input type="hidden" name="project_status_filter" value="{{$_GET['project_status_filter']}}">@endif
                                    @if(isset($_GET['start_date']))<input type="hidden" name="start_date" value="{{$_GET['start_date']}}">@endif
                                    @if(isset($_GET['end_date']))<input type="hidden" name="end_date" value="{{$_GET['end_date']}}">@endif
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-7 col-md-7 col-sm-7 p-0 pl-2">
                            <form action="" method="GET" class="d-flex">
                                <div class="mb-3">
                                    <div class="input-group input-daterange" class="daterange">
                                        <input type="text" name="start_date"  id="datepicker" onfocus="(this.type='date')" value="{{Request::get('start_date') ?? ''}}" placeholder ="Start Date" class="form-control"  />
                                        <span class="input-group-text">To</span>
                                        <input type="text" name="end_date" id="Enddate" value="{{Request::get('end_date') ?? ''}}" onfocus="(this.type='date')" placeholder ="End Date" class="form-control"/>
                                    </div>
                                </div>
                                <div class="d-flex" style="margin-left: 8px;">
                                    <button class="btn-sm search-btn" type="submit">  
                                        <i class="fa fa-search pl-3" aria-hidden="true"></i> 
                                    </button>
                                    <a href="{{url('admin/draft-project')}}">
                                        <i class="fa fa-refresh pl-3 redirect-icon" aria-hidden="true"></i>
                                    </a>
                                </div>
                                @if(isset($_GET['search']))<input type="hidden" name="search" value="{{$_GET['search']}}">@endif
                                @if(isset($_GET['pagination']))<input type="hidden" name="pagination" value="{{$_GET['pagination']}}">@endif
                                @if(isset($_GET['project_status_filter']))<input type="hidden" name="project_status_filter" value="{{$_GET['project_status_filter']}}">@endif
                            </form>
                        </div>
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
                                <h5 class="m-0 mb-1">Draft/Complete Projects List</h5>
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
                    <div class="card-body">
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
                                                <td>{{ $project->type ?? '' }}</td>
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
                                                     <span class="badge badge-info">{{ $project->status ?? '' }}</span>
                                                </td>
                                                <td>
                                                    @can('project_show')
                                                    <a href="{{ route('admin.projects.show', $project->id) }}">
                                                        <button class="btn btn-sm btn-icon me-2" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>View</span>"><i class="bx bx-show mx-1"></i></button>
                                                    </a>
                                                    @endcan
                                                  
                                                  <!--   @can('project_edit')
                                                    <a  href="{{ route('admin.projects.edit', $project->id) }}">
                                                        <button class="btn btn-sm btn-icon me-2" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Edit</span>"><i class="bx bx-edit"></i></button>
                                                    </a>
                                                    @endcan -->

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
