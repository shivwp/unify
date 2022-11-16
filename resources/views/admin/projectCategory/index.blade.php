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
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row ">
                @can('project_status_create')
                <div class="row tabelhed d-flex justify-content-between">
                    <div class="col-lg-2 col-md-2 col-sm-2 d-flex">
                        @can('user_create')
                        <a class="btn-sm ad-btn text-center pt-2" href="{{ route("admin.project-category.create") }}"> Add</a>
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
                                <input type="text" name="search" class="form-control" value="{{$search}}" style="height: 39px;" placeholder="Search..." required>
                                <button class="btn-sm search-btn" type="submit"  style="margin-left:6px"> <i class="fa fa-search pl-3" aria-hidden="true"></i> </button>
                                <a href="{{url('admin/project-category')}}">
                                    <i class="fa fa-refresh pl-3 redirect-icon" aria-hidden="true"></i>
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
                @endcan
                @if(Session::has('error'))
                <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error') }}</p>
                @endif
                @if(Session::has('success'))
                    <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success') }}</p>
                @endif
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-6">  
                                <h5 class="m-0 mb-1">Category List</h5>
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
                        <div class="table">
                            <table class=" table table-bordered table-striped table-hover datatable datatable-ProjectStatus example">
                                <thead>
                                    <tr>
                                        <th class="wd-15p">S No.</th>
                                        <th class="wd-15p">Title</th>
                                        <!-- <th class="wd-15p">Parent</th> -->
                                        <!-- <th class="wd-15p">Number of projects going on</th> -->
                                        <th class="wd-15p">Number of Sub Categories</th>
                                        <th class="wd-15p">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php $i=1 @endphp
                                    @foreach($projectCategory as $key => $projectCate)

                                        @php
                                            $record = App\Models\ProjectProjectCategory::where('project_category_id',$projectCate->id)->count();

                                            $count = App\Models\ProjectCategory::where('parent_id', $projectCate->id)->count();
                                        @endphp
                                        <tr data-entry-id="{{ $projectCate->id }}">
                                           
                                        <td>{{$i++}}</td>
                       
                                           
                                             <td>
                                                <a href="{{ route('admin.project-category.sub_category', $projectCate->id) }}">{{ $projectCate->name ?? '' }}</a>
                                            </td>
                                            <td>{{ $count ?? '' }}</td>
                                            <td>
                                                @can('project_category_show')
                                                    <a href="{{ route('admin.project-category.show', $projectCate->id) }}">
                                                    <button class="btn btn-sm btn-icon me-2" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>View</span>" ><i class="bx bx-show mx-1"></i></button>
                                                    </a>
                                                @endcan

                                                @can('project_category_edit')
                                                    <a  href="{{ route('admin.project-category.edit', $projectCate->id) }}">
                                                    <button class="btn btn-sm btn-icon me-2" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Edit</span>" ><i class="bx bx-edit"></i></button>
                                                    </a>
                                                @endcan
                                                @if($record <= 0)
                                                    @can('project_category_delete')
                                                    
                                                        <form action="{{ route('admin.project-category.destroy', $projectCate->id) }}" method="POST" style="display: inline-block;">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                            <button type="submit" class="btn btn-sm  btn-icon delete-record" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Delete</span>" onclick="return confirm('{{ trans('global.areYouSure') }}');"><i class="bx bx-trash"></i></button>
                                                            
                                                        </form>
                                                    @endcan
                                                    @else
                                                    <button type="submit" data-bs-toggle="modal" data-bs-target="#basicModal" value="{{$projectCate->id}}"  class="btn btn-sm category_re btn-icon delete-record" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Delete</span>" ><i class="bx bx-trash"></i></button>
                                                    <!-- <button data-bs-toggle="modal" data-bs-target="#basicModal" value="{{$projectCate->id}}" class="category_re "><i class="bx bx-trash"></button> -->
                                                @endif

                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $projectCategory->links() !!}
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel1">Selected category used in other projects/jobs. need to replace with other category</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col mb-3 m-0">
                                        <label for="nameBasic" class="form-label">Category</label>
                                        <form action="{{url('admin/category-delete-replace')}}" method="POST">
                                        @csrf
                                        <input type="hidden" id="nameBasic" class="form-control" value="" name="delete_id">
                                        <div class="mb-3">
                                            <select id="select2Basic" name="replace_id" class=" form-select form-select-lg" data-allow-clear="true">
                                   
                                            </select>
                                        </div>
                                    </div>
                                </div>
                      
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Delete And Replace</button>
                            </form>
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
