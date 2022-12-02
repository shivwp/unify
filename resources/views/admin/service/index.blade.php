@extends('layouts.master') @section('content')
<style>
    .search-btn {
        border: 1px solid #d7cbcb;
        padding: 8px 10px 6px 11px;
        border-radius: 8px;
        margin-left: 6px;
    }
</style>

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
                <div class="row tabelhed d-flex justify-content-between">


                    <div class="col-lg-2 col-md-2 col-sm-2 d-flex">
                        @can('project_status_create')
                            {{--<a class="btn-sm btn-info" style="height: 30px; font-size: smaller; padding: 9px 10px 10px 12px; margin-left: 11px;" href="{{url('admin/service')}}">Back
                                            </a>--}}
                            <a class="btn-sm ad-btn create_btn pt-2" href="{{ route("admin.service.create") }}">
                                Add
                            </a>
                        @endcan
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
                                <input type="text" name="search" class="form-control" value="{{$search}}" style="height: 39px;" placeholder="Search Service" required>
                                <button class="btn-sm search-btn" type="submit"  style="margin-left:6px"> <i class="fa fa-search pl-3" aria-hidden="true"></i> </button>
                                <a href="{{url('admin/service')}}">
                                    <i class="fa fa-refresh pl-3 redirect-icon" aria-hidden="true"></i>
                                </a>
                            </form>
                        </div>
                    </div>


                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-6">
                        <h5 style="margin: 0">Services</h5>
                        </div>
                         <div class="col-lg-6">  
                                <div class="items" style="margin-left: 1px; width: 85px !important; float: right;">
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

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class=" table table-bordered table-striped table-hover datatable datatable-ProjectStatus example">
                                <thead>
                                    <tr>
                                      
                                        <th class="wd-15p">
                                            S No.
                                        </th>
                                        <th class="wd-15p">
                                        Service Name
                                        </th class="wd-15p">
                                        <th>
                                        Description
                                        </th class="wd-15p">
                                        <th class="wd-15p">
                                          Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                    $i=1
                                @endphp
                                 @foreach($Services as $key => $projectCate)
                                        <tr data-entry-id="{{ $projectCate->id }}">
                                            
                                            <td>
                                                {{$i++}}
                                            </td>
                                           
                                             <td>
                                                {{ $projectCate->service_name ?? '' }}
                                            </td>
                                            <td>
                                            {{ $projectCate->description ?? '' }}
                                               
                                            </td>
                                            <td>

                                                @can('project_category_edit')
                                                    <a href="service-update/{{$projectCate->id}}">
                                                        <button class="btn btn-sm btn-icon me-2"><i class="bx bx-edit" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Edit</span>"></i></button>
                                                    </a>
                                                @endcan

                                                @can('project_category_delete')
                                                    <form action="{{ route('admin.service.destroy', $projectCate->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf    
                                                    <input type="hidden" name="_method" value="DELETE">
                                                        
                                                        <input type="hidden" name="id" value="{{$projectCate->id}}">
                                                        <button type="submit" class="btn btn-sm btn-icon delete-record"><i class="bx bx-trash" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Delete</span>"></i></button>
                                                    </form>
                                                @endcan

                                            </td>

                                        </tr>
                                    @endforeach 
                                </tbody>
                            </table>
                            {!! $Services->links() !!}
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
