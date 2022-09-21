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
            <div class="row tabelhed">
                <div class="col-lg-2 col-md-2 col-sm-2 d-flex">
                    @can('project_create')
                    <a class="btn-sm ad-btn text-center pt-2" href="{{ route("admin.projects.create") }}"> Add</a>
                    @endcan
                    <button id="btnExport" onClick="fnExcelReport()" class="btn-sm ad-btn clearfix">Excel</button>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-5 "> 
                    <?php 
                    if(!empty($_GET['search'])){
                        $search= $_GET['search'];
                    }else{ 
                        $search='';
                    }?>
                    <div class="right-item" >
                        <form action="" class="d-flex" method="get">
                            <input type="text" name="search" class="form-control" value="{{$search}}" style="height: 39px;" placeholder="Enter Job Name" required>
                          <button class="btn-sm search-btn" type="submit"  style="margin-left:6px"> <i class="fa fa-search pl-3" aria-hidden="true"></i> </button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-5 p-0 pl-2">
                <form action="" method="GET" class="d-flex">
                    <div class="mb-3">
                        <div class="input-group input-daterange" class="daterange">
                            <input type="text" name="start_date"  id="datepicker" onfocus="(this.type='date')" value="{{Request::get('start_date') ?? ''}}" placeholder ="Start Date" class="form-control"  required />
                            <span class="input-group-text">To</span>
                            <input type="text" name="end_date" id="Enddate" value="{{Request::get('end_date') ?? ''}}" onfocus="(this.type='date')" placeholder ="End Date" class="form-control"/>
                        </div>
                    </div>
                    <div class="d-flex" style="margin-left: 8px;">
                        <button class="btn-sm search-btn" type="submit">  
                            <i class="fa fa-search pl-3" aria-hidden="true"></i> 
                        </button>
                        <a href="{{url('admin/projects')}}">
                            <i class="fa fa-refresh pl-3 redirect-icon" aria-hidden="true"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-xl-6">
                        <h5 class="m-0 mb-1">Project List</h5>
                    </div>
                    <div class="col-xl-6 ">
                        <div class="row" style="float: right">
                            <div class="col-xl-12 d-flex" style="float: right">
                                <div class="items paginatee">
                                    <form action="" id="pagination" method="get">
                                        <select class="form-select m-0" name="pagination" style="width: 94%; font-size: 11px;  height: 32px;" id="pagination" aria-label="Default select example">
                                            <option value="10" @if($pagination=='10') selected @endif>10</option>
                                            <option  value='20' @if($pagination=='20') selected @endif>20</option>
                                            <option value='30' @if($pagination=='30') selected @endif>30</option>
                                            <option value='40' @if($pagination=='40') selected @endif>40</option>
                                            <option value='50' @if($pagination=='50') selected @endif>50</option>
                                        </select>
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
                                <th>Client</th>
                                <th>Publish date</th>
                                <th>Budget</th>
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
                                        <td>
                                           <a href="{{ url("admin/users", [$project->client->id]) }}"><p class="C_nme">{{ $project->client->name ?? '' }}</p></a>
                                        </td>
                                        <td>{{$project->created_at->toFormattedDateString()}}</td>
                                        <td>
                                            @if($project->payment_base=='fixed')
                                               
                                                ${{ number_format((float)$project->total_budget, 2, '.', '') }}

                                            @endif
                                            @if($project->payment_base=='hourly')
                                               
                                                ${{ number_format((float)$project->per_hour_budget, 2, '.', '') }}

                                            @endif
                                        </td>
                                        <td>
                                            @if($project->status->name == "Publish")
                                                <span class="badge badge-publish">{{ $project->status->name ?? '' }}</span>
                                            @else
                                                <span class="badge badge-info">{{ $project->status->name ?? '' }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @can('project_show')
                                            <a href="#">
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
                      
                    {!! $projects->links() !!}
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
