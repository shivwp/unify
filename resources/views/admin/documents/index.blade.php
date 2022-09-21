@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
             
@can('document_create')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-6" >
        <!-- <a class="btn-sm btn-info" style="height: 30px; font-size: smaller; padding: 6px 7px 7px 8px; margin-left: 11px;" href="{{url('admin/documents')}}">Back
                        </a> -->
        <a class="btn-sm btn-success" style="height: 30px; margin-left: 10px;font-size: smaller; padding: 9px 9px 10px 12px;" href="{{ route("admin.documents.create") }}">
            Add
        </a>
    </div>
    <div class="col-lg-6">
    <div style="float:right;">
    <a href="{{url('admin/documents')}}"><i class="fa fa-refresh pl-3" style="border: 1px solid #beb3b3; padding:10px; border-radius:6px" aria-hidden="true"></i></a>
    </div>
       
    </div>
</div>
@endcan
<div class="card">
<div class="card-header">
    {{ trans('cruds.document.title_singular') }} {{ trans('global.list') }}
</div>

<div class="card-body">
    <div class="table-responsive">
        <table class=" table table-bordered table-striped table-hover datatable datatable-Document example">
            <thead>
                <tr>
                  
                    <th>
                         S No.
                    </th>
                    <th>
                        {{ trans('cruds.document.fields.project') }}
                    </th>
                    <!-- <th>
                        {{ trans('cruds.document.fields.document_file') }}
                    </th> -->
                    <th>
                        {{ trans('cruds.document.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.document.fields.description') }}
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i=1
                @endphp
                @foreach($documents as $key => $document)
                    <tr data-entry-id="{{ $document->id }}">
                     
                        <td>
                            {{$i++}}
                        </td>
                        <td>
                            {{ $document->project->name ?? '' }}
                        </td>
                        <!-- <td>
                            @if($document->document_file)
                                <a href="{{ $document->document_file->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td> -->
                        <td>
                            {{ $document->name ?? '' }}
                        </td>
                        <td>
                            {{ $document->description ?? '' }}
                        </td>
                        <td>
                            @can('document_show')
                                <a href="{{ route('admin.documents.show', $document->id) }}">
                                    <button class="btn btn-sm btn-icon me-2"><i class="bx bx-show mx-1" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>View</span>"></i></button>
                                </a>
                            @endcan

                            @can('document_edit')
                                <a href="{{ route('admin.documents.edit', $document->id) }}">
                                    <button class="btn btn-sm btn-icon me-2"><i class="bx bx-edit" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Edit</span>"></i></button>
                                </a>
                            @endcan

                            @can('document_delete')
                                <form action="{{ route('admin.documents.destroy', $document->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
        {!! $documents->links() !!}
    </div>
</div>
</div>
@endsection
@section('scripts')
@parent
<script>
$(function () {
let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('document_delete')
let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
let deleteButton = {
text: deleteButtonTrans,
url: "{{ route('admin.documents.massDestroy') }}",
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
$('.datatable-Document:not(.ajaxTable)').DataTable({ buttons: dtButtons })
$('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
    $($.fn.dataTable.tables(true)).DataTable()
        .columns.adjust();
});
})

</script>
@endsection
