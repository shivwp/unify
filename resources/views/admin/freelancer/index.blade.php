@extends('layouts.master') @section('content')
<style>
    .search-btn {
    border: 1px solid #d7cbcb;
    padding: 8px 10px 6px 11px;
    border-radius: 8px;
}
</style>
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row">
            
            <div class="row tabelhed d-flex justify-content-between">
                <div class="col-lg-2 col-md-2 col-sm-2 d-flex">
                    @can('user_create')
                    <a class="btn-sm ad-btn text-center pt-2" href="{{ route("admin.users.create") }}"> Add</a>
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
                            <input type="text" name="search" class="form-control" value="{{$search}}" style="height: 39px;" placeholder="Search User" required>
                            <button class="btn-sm search-btn" type="submit"  style="margin-left:6px"> <i class="fa fa-search pl-3" aria-hidden="true"></i> </button>
                            <a href="{{url('admin/freelancer')}}">
                                <i class="fa fa-refresh pl-3 redirect-icon" aria-hidden="true"></i>
                            </a>
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
                            <h5>Freelancer's List</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table">
                        <table id="example" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>S No.</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($freelance as $key => $item)
                                    <tr data-entry-id="{{ $item->id }}">
                                        <td>{{ $item->id ?? ''}}</td>
                                        <td>{{ $item->name ?? '' }}</td>
                                        <td>{{ $item->email ?? '' }}</td>
                                        
                                        <td>
                                             @if($item->status == 'pending')
                                            <a href="{{url('admin/user/statusupdate',$item->id)}}"><span class="btn btn-xs btn-danger text-capitalize">{{ $item->status ?? '' }}</span></a>
                                            @else
                                            <a href="{{url('admin/user/statusupdate',$item->id)}}"><span class="btn btn-xs btn-info text-capitalize">{{ $item->status ?? '' }}</span></a>
                                            @endif
                                        </td>
                                        <td>
                                            @can('user_show')
                                                <a href="{{ route('admin.freelancer.show', $item->user_id) }}">
                                                    <button class="btn btn-sm btn-icon me-2"><i class="bx bx-show mx-1" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>View</span>"></i></button>
                                                </a>
                                            @endcan
            
                                            @can('user_edit')
                                                <a href="{{ route('admin.users.edit', $item->user_id) }}">
                                                    <button class="btn btn-sm btn-icon me-2"><i class="bx bx-edit" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Edit</span>"></i></button>
                                                </a>
                                            @endcan
            
                                            @can('user_delete')
                                                <form action="{{ route('admin.freelancer.destroy', $item->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
                        {!! $freelance->links() !!}
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
@can('user_delete')
let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
let deleteButton = {
text: deleteButtonTrans,
url: "{{ route('admin.users.massDestroy') }}",
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
$('.datatable-User:not(.ajaxTable)').DataTable({ buttons: dtButtons })
$('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
    $($.fn.dataTable.tables(true)).DataTable()
        .columns.adjust();
});
})

</script>

@endsection
