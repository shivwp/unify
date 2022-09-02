@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
            
@can('project_status_create')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href="{{ route("admin.project-category.create") }}">
            Add Category
        </a>
    </div>
</div>
@endcan
<div class="card">
<div class="card-header">
    Category List
</div>

<div class="card-body">
    <div class="table-responsive">
        <table class=" table table-bordered table-striped table-hover datatable datatable-ProjectStatus">
            <thead>
                <tr>
                  
                    <th>
                        Id
                    </th>
                    <th>
                        Name
                    </th>
                    <th>
                    Parent
                    </th>
                    <th>
                      Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($projectCategory as $key => $projectCate)
                    <tr data-entry-id="{{ $projectCate->id }}">
                       
                        <td>
                            {{ $projectCate->id ?? '' }}
                        </td>
                       
                         <td>
                            {{ $projectCate->name ?? '' }}
                        </td>
                        <td>
                           
                            @if(!empty($projectCate->parentcategory))
                            {{ $projectCate->parentcategory->name ?? '' }}
                            @else
                            No Parent
                            @endif
                        </td>
                        <td>
                            @can('project_category_show')
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.project-category.show', $projectCate->id) }}">
                                    {{ trans('global.view') }}
                                </a>
                            @endcan

                            @can('project_category_edit')
                                <a class="btn btn-xs btn-info" href="{{ route('admin.project-category.edit', $projectCate->id) }}">
                                    {{ trans('global.edit') }}
                                </a>
                            @endcan

                            @can('project_category_delete')
                                <form action="{{ route('admin.project-category.destroy', $projectCate->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
        {!! $projectCategory->links() !!}
    </div>
</div>
</div>
@endsection
@section('scripts')
@parent
<script>
$(function () {
let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('project_status_delete')
let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
let deleteButton = {
text: deleteButtonTrans,
url: "{{ route('admin.project-category.massDestroy') }}",
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
$('.datatable-ProjectStatus:not(.ajaxTable)').DataTable({ buttons: dtButtons })
$('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
    $($.fn.dataTable.tables(true)).DataTable()
        .columns.adjust();
});
})

</script> 
@endsection
