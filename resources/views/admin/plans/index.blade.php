@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
            
@can('project_status_create')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        {{--<a class="btn-sm btn-info" style="height: 30px; font-size: smaller; padding: 6px 7px 7px 8px; margin-left: 11px;" href="{{url('admin/plan')}}">Back
                        </a>--}}
        <a class="btn-sm btn-success" style="margin-left: 10px;height: 30px; font-size: smaller; padding: 6px 7px 7px 8px;" href="{{ route("admin.plan.create") }}">
            Add Plan
        </a>
    </div>
</div>
@endcan
<div class="card">
<div class="card-header">
    Plan List
</div>

<div class="card-body">
    <div class="table-responsive">
        <table class=" table table-bordered table-striped table-hover datatable datatable-ProjectStatus">
            <thead>
                <tr>
                  
                    <th>
                        S No.
                    </th>
                    <th>
                    Plans Name
                    </th>
                    <th>
                   Services
                    </th>
                    <th>
                    Validity
                    </th>
                    <th>
                    Amount
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
             @foreach($plans as $key => $item)
                    <tr data-entry-id="{{ $item->id }}">
                       
                        <td>
                           {{$i++}}
                        </td>
                       
                         <td>
                            {{ $item->plans_title ?? '' }}
                        </td>
                        <td>
                        @foreach($item->services as $key => $service)
                                                <span class="badge badge-info">{{ $service->service_name }}</span>
                                            @endforeach
                           
                        </td>
                        <td>
                            @if($item->validity=="one_month")
                            One Month
                            @elseif($item->validity=="three_month")
                            Three Month
                            @else
                            @if($item->validity=="six_month")
                            Six Month
                            @else
                            @if($item->validity=="one_year")
                            One Year
                            @endif
                            @endif
                            @endif
                        </td>
                        <td>
                            @if($item->amount<=0)
                            FREE
                            @else
                            {{$item->amount}}
                            @endif
                        </td>
                        <td>
                                <!-- @can('project_category_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.project-category.show', $item->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan -->

                            @can('project_category_edit')
                                <a href="plan-update/{{$item->id}}">
                                    <button class="btn btn-sm btn-icon me-2"><i class="bx bx-edit" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Edit</span>"></i></button>
                                </a>
                            @endcan

                            @can('project_category_delete')
                            
                                <form action="{{ route('admin.plan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                @csrf    
                                <input type="hidden" name="_method" value="DELETE">
                                    
                                    <input type="hidden" name="id" value="{{$item->id}}">
                                     <button type="submit" class="btn btn-sm btn-icon delete-record"><i class="bx bx-trash" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Delete</span>"></i></button>
                                </form>
                            @endcan

                        </td>

                    </tr>
                @endforeach 
            </tbody>
        </table>
        {!! $plans->links() !!}
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
