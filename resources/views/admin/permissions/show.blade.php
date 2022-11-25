@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-header">
                        {{ trans('global.show') }} {{ trans('cruds.permission.title') }}
                    </div>

                    <div class="card-body">
                        <div class="mb-2">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.permission.fields.id') }}
                                        </th>
                                        <td>
                                            {{ $permission->id }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.permission.fields.title') }}
                                        </th>
                                        <td>
                                            {{ $permission->title }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
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
<!-- / Content -->

<!-- Footer -->



@endsection