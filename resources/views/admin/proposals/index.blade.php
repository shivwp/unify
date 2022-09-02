@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
            
@can('project_status_create')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href="{{ route("admin.proposal.create") }}">
            Add Proposal
        </a>
    </div>
</div>
@endcan
<div class="card">
<div class="card-header">
    Proposal List
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
                  Project
                    </th>
                    <th>
                  Freelancer
                    </th>
                    <th>
                  Status
                    </th>
                    <th>
                   Bid-Amount
                    </th>
                    <th>
                      Action
                    </th>
                </tr>
            </thead>
            <tbody>
             @foreach($proposal as $key => $item)
                    <tr data-entry-id="{{ $item->id }}">
                      
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
                               
                        @can('project_show')
                                <a class="btn btn-xs btn-primary" href="/admin/proposal-show/{{$item->id}}">
                                    {{ trans('global.view') }}
                                </a>
                            @endcan
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
        {!! $proposal->links() !!}
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
