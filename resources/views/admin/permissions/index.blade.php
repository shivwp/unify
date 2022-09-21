@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
               

<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <!-- <a class="btn-sm btn-info" style="height: 30px; font-size: smaller; padding: 6px 7px 7px 8px; margin-left: 11px;" href="{{url('admin/permissions')}}">Back
                        </a> -->
       
        <div style="margin-bottom: 10px;" class="row p-0">
            <div class="col-lg-6 col-md-6 col-sm-12 d-flex">
                @can('permission_create')
                <a class="btn-sm btn-success pt-2
                " style="margin-left: 10px;height: 38px; font-size: smaller; padding: 9px 9px 10px 12px;" href="{{ route("admin.permissions.create") }}">
                  Add
                </a>
                @endcan
             <!-- <a class="btn-sm btn-info" style="margin-left: 1px; height: 30px; font-size: smaller;" href="{{url('/admin/projects-pdf')}}">
              Export to Pdf
          </a> -->
          <button id="btnExport" style="margin-left: 5px;  height: 38px; font-size: 13px; border:none;" onClick="fnExcelReport()" class="btn-sm btn-secondary clearfix">Excel</button>

      </div>
   
      <div class="col-lg-6 col-md-6 col-sm-12 pl-2">
<div style="float: right;">
    <a href="{{url('admin/permissions')}}"><i class="fa fa-refresh pl-3" style="border: 1px solid #beb3b3; padding:10px; border-radius:6px" aria-hidden="true"></i></a>

</div>
  </div>

</div>
    </div>
</div>

<div class="card">
<div class="card-header">
    {{ trans('cruds.permission.title_singular') }} {{ trans('global.list') }}
</div>

<div class="card-body">
    <div class="table-responsive">
        <table class=" table table-bordered table-striped table-hover datatable datatable-Permission">
            <thead>
                <tr>
                   
                    <th class="text-center">
                        S No.
                    </th>
                    <th class="text-center">
                        {{ trans('cruds.permission.fields.title') }}
                    </th>
                    <th class="text-center">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i=1
                @endphp
                @foreach($permissions as $key => $permission)
                    <tr data-entry-id="{{ $permission->id }}">
                     
                        <td class="text-center">
                            {{$i++}}
                        </td>
                        <td class="text-center">
                            {{ $permission->title ?? '' }}
                        </td>
                        <td class="text-center">
                            @can('permission_show')
                                <a href="{{ route('admin.permissions.show', $permission->id) }}">
                                    <button class="btn btn-sm btn-icon me-2"><i class="bx bx-show mx-1" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>View</span>"></i></button>
                                </a>
                            @endcan

                            @can('permission_edit')
                                <a href="{{ route('admin.permissions.edit', $permission->id) }}">
                                   <button class="btn btn-sm btn-icon me-2"><i class="bx bx-edit" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Edit</span>"></i></button>
                                </a>
                            @endcan

                            @can('permission_delete')
                                <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                     <button type="submit" class="btn btn-sm btn-icon delete-record"><i class="bx bx-trash" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Delete</span>"></i></button>
                                </form>
                            @endcan

                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
        {!! $permissions->links() !!}
    </div>
</div>
</div>
@endsection
@section('scripts')
@parent
<script>
$(function () {
let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('permission_delete')
let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
let deleteButton = {
text: deleteButtonTrans,
url: "{{ route('admin.permissions.massDestroy') }}",
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
$('.datatable-Permission:not(.ajaxTable)').DataTable({ buttons: dtButtons })
$('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
    $($.fn.dataTable.tables(true)).DataTable()
        .columns.adjust();
});
})

</script>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->

<!-- Footer -->



@endsection