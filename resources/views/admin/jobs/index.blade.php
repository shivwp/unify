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
                <div class="col-lg-2 col-md-2 col-sm-12 d-flex">
                    <a class="btn-sm ad-btn text-center pt-2" href="{{ route("admin.jobs.create") }}">Add</a>
                    <button id="btnExport" onClick="fnExcelReport()" class="btn-sm ad-btn clearfix">Excel</button> 

          </div>
          <div class="col-lg-5 col-md-5 col-sm-12 "> <?php 
            //  if(!empty($_GET['search'])){$search= $_GET['search'];}else{ $search='';}
            //  ?>
            
              {{-- <div class="right-item" >
                
                  <form action="" class="d-flex" method="get">
                      <input type="text" name="search" class="form-control" value="{{$search}}" style="height: 39px;" placeholder="Enter project/job name">
                    <button class="btn-sm search-btn" type="submit"> <i class="fa fa-search pl-3" aria-hidden="true"></i> </button>
                  </form>
               </div>--}}
              </div> 
            <div class="col-lg-5 col-md-5 col-sm-12 pl-2">

            <form action="" method="GET" class="d-flex">
                <div class="mb-3">
                    <div class="input-group input-daterange" class="daterange">
                      <input type="text" name="start_date"  id="Startdate" onfocus="(this.type='date')" value="{{Request::get('start_date') ?? ''}}" placeholder ="Start Date" class="form-control" />
                      <span class="input-group-text">To</span>
                      <input type="text" name="end_date" id="Enddate" value="{{Request::get('end_date') ?? ''}}" onfocus="(this.type='date')" placeholder ="End Date" class="form-control" />
                    </div>
                </div>
                <div class="d-flex" style="margin-left: 8px;">
                <button class="btn-sm search-btn" type="submit" >  <i class="fa fa-search pl-3" aria-hidden="true"></i> </button>
                <a href="{{url('admin/jobs')}}"><i class="fa fa-refresh pl-3 redirect-icon"  aria-hidden="true"></i></a>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-xl-6">
                    <h5 class="m-0 mb-1">Jobs</h5>
                </div>
                <div class="col-xl-6 ">
                    <div class="row" style="float:right">
                        <div class="col-xl-12 d-flex" style="float: right">
                            <div class="items" style="margin-left: 1px; width: 75px !important;">
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
            <div class="table-responsive card-datatable">
                <table id="exportToTable" class=" datatables-basic table border-top table table-bordered table-striped table-hover datatable datatable-Project">
                    <thead>
                        <tr>
                            <th>S No.</th>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Project</th>
                            <th>Publish date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="">
                        @if(count($jobs)>0)
                            @php $i=1 @endphp
                            @foreach($jobs as $key => $item)
                            <tr data-entry-id="{{ $item->id }}">
                                <td>{{$i++}}</td>
                                <td>{{ $item->user->name ?? '' }}</td>
                                <td>{{ $item->user->email ?? '' }}</td>
                                <td>{{ $item->project->name ?? '' }}</td>
                                <td>{{$item->created_at->toFormattedDateString()}}</td>
                                <td>{{ $item->status->name ?? '' }}</td>
                                <td>
                                    @if($item->status->name == "Publish")
                                        <span class="badge badge-publish">{{ $item->status->name ?? '' }}</span>
                                    @elseif($item->status->name == "Unpublish")
                                        <span class="badge badge-info">{{ $item->status->name ?? '' }}</span>
                                    @elseif($item->status->name == "On-Hold")
                                        <span class="badge badge-hold">{{ $item->status->name ?? '' }}</span>
                                    @endif
                                </td>
                                <td>
                                    <!-- <a  href="{{ route('admin.jobs.show', $item->id) }}">
                                    <i class="bx bx-show mx-1"  data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>View</span>"></i>
                                    </a> -->
                                    <a  href="{{ route('admin.jobs.edit', $item->id) }}">
                                        <i class="bx bx-edit"  data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Edit</span>"></i>
                                    </a>
                                    <a href="jobs-delete/{{$item->id}}">
                                        <i class="bx bx-trash"data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Delete</span>" onclick="return confirm('{{ trans('global.areYouSure') }}');"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach 
                        @else
                        <tr class="mb-3">

                        No Record Found

                        </tr>
                        @endif
                        </form>
                        </tbody>
                        </table>
          
{!! $jobs->links() !!}
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
