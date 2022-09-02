@extends('layouts.master') @section('content')
<style type="text/css">
    button.btn.filter_btn {
        background-color: #696cff;
        color: white;
    }
</style>
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">


            @can('project_create')
            <div style="margin-bottom: 10px;" class="row">
                <div class="col-lg-6 d-flex">
                    <a class="btn btn-success" href="{{ route("admin.projects.create") }}">
                     Add Project
                 </a>

                 <a class="btn btn-info" style="margin-left: 11px;" href="{{url('/admin/projects-pdf')}}">
                  Export to Pdf
              </a>
              <button id="btnExport" style="margin-left: 11px;" onClick="fnExcelReport()" class="btn btn-secondary clearfix"><span class="fa fa-file-excel-o"></span> Export to Excel</button>

          </div>
          <div class="col-xl-6">

            <div class="row d-flex">
                <form action="" method="GET" class="d-flex">
                    <div class="col-xl-3" style="margin-left: 11px;">

                        <select class="form-select" name="day"  aria-label="Default select example"> <option value="all" @if($day=='all') selected @endif>All</option>
                            @for ($i = 1; $i < 32; $i++)
                            <option value="{{$i}}" @if($day==$i) selected @endif>{{$i}}</option>
                            @endfor



                        </select>
                    </div>
                    <div class="col-xl-3" style="margin-left: 11px;">

                        <select class="form-select" name="month" aria-label="Default select example">
                            <option value="all" @if($month=='all') selected @endif>All</option>
                            <option  value='1' @if($month=='1') selected @endif>Janaury</option>
                            <option value='2' @if($month=='2') selected @endif>February</option>
                            <option value='3' @if($month=='3') selected @endif>March</option>
                            <option value='4' @if($month=='4') selected @endif>April</option>
                            <option value='5' @if($month=='5') selected @endif>May</option>
                            <option value='6' @if($month=='6') selected @endif>June</option>
                            <option value='7' @if($month=='7') selected @endif>July</option>
                            <option value='8' @if($month=='8') selected @endif>August</option>
                            <option value='9' @if($month=='9') selected @endif>September</option>
                            <option value='10' @if($month=='10') selected @endif>October</option>
                            <option value='11' @if($month=='11') selected @endif>November</option>
                            <option value='12' @if($month=='12') selected @endif>December</option>
                        </select>
                    </div>
                    <div class="col-xl-3" style="margin-left: 11px;">

                        <select class="form-select" name="year" aria-label="Default select example">
                          @for ($i = 2000; $i < 2050; $i++)
                          <option value="{{$i}}" @if($year==$i) selected @endif>{{$i}}</option>
                          @endfor
                      </select>
                  </div>
                  <div class="col-xl-3" style="margin-left: 11px;">
                      <button class="btn filter_btn">Submit</button>
                  </div>
              </form>
          </div>
      </div>

  </div>
  @endcan
  <div class="card">
    <div class="card-header">
     Project list
 </div>

 <div class="card-body">
    <div class="table-responsive">
        <table id="" class=" table table-bordered table-striped table-hover datatable datatable-Project">
            <thead>
                <tr>

                    <th>
                     #ID
                 </th>
                 <th>
                     Project Name
                 </th>
                 <th>
                     Client
                 </th>
                 <th>
                  Description
              </th>
              <th>
                  Start Date
              </th>
              <th>
                End Date
            </th>
            <th>
              Budget
          </th>
          <th>
              Status
          </th>
          <th>
             Action
         </th>
     </tr>
 </thead>
 <tbody id="exportToTable">
    @foreach($projects as $key => $project)
    <tr data-entry-id="{{ $project->id }}">

        <td>
            {{ $project->id ?? '' }}
        </td>
        <td>
            {{ $project->name ?? '' }}
        </td>
        <td>
            {{ $project->client->first_name ?? '' }}
        </td>
        <td>
            {{ $project->description ?? '' }}
        </td>
        <td>
          {{ date('j \\ F Y', strtotime($project->start_date)) }}
      </td>
      <td>
        {{ date('j \\ F Y', strtotime($project->end_date)) }} 
    </td>
    <td>
        @if($project->payment_base=='fixed')
        ${{ $project->total_budget }}

        @endif
        @if($project->payment_base=='hourly')
        ${{ $project->per_hour_budget }}

        @endif
    </td>
    <td>
        {{ $project->status->name ?? '' }}
    </td>
    <td>
        @can('project_show')
        <a class="btn btn-xs btn-primary" href="{{ route('admin.projects.show', $project->id) }}">
            {{ trans('global.view') }}
        </a>
        @endcan

        @can('project_edit')
        <a class="btn btn-xs btn-info" href="{{ route('admin.projects.edit', $project->id) }}">
            {{ trans('global.edit') }}
        </a>
        @endcan

        @can('project_delete')
        <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
        </form>
        @endcan

    </td>

</tr>
@endforeach
</tbody>
</table>
{!! $projects->links() !!}
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
