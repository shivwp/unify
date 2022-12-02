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
        <div class="row ">
            <div class="col-lg-12">
                <div class="row tabelhed d-flex justify-content-between">
                        <div class="col-lg-2 col-md-2 col-sm-2 d-flex">
                            @can('user_create')
                            <a class="btn-sm ad-btn create_btn text-center pt-2" href="{{ route("admin.project-skill.create") }}"> Add</a>
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
                                    <input type="text" name="search" class="form-control" value="{{$search}}" style="height: 39px;" placeholder="Search skills" required>
                                    <button class="btn-sm search-btn" type="submit"  style="margin-left:6px"> <i class="fa fa-search pl-3" aria-hidden="true"></i> </button>
                                    <a href="{{url('admin/project-skill')}}">
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
                        <h5>Skills</h5>
                        </div>
                    <div class="col-lg-6">  
                                <div class="items" style="margin-left: 1px; width: 85px !important; float: right;">
                                    @php
                                        if(isset($_GET['pagination'])){
                                        $num = $_GET['pagination'];
                                    }else{
                                    $num = 10;
                                }

                                    @endphp
                                    <form action="" id="pagination" method="get">
                                        <select class="form-select m-0" name="pagination" style="width: 94%; font-size: 11px;  height: 32px;" id="pagination" aria-label="Default select example">
                                            <option value="10"  >{{$num}}</option>
                                            <option  value='20' >20</option>
                                            <option value='30' >30</option>
                                            <option value='40' >40</option>
                                            <option value='50' >50</option>
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
                                       
                                        <th>
                                          S No.
                                        </th>
                                        <th>
                                            Skills
                                        </th>
                                        <th>
                                           Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php $i=1 @endphp
                                    @foreach($projectSkill as $key => $projectSkil)
                                        <tr data-entry-id="{{ $projectSkil->id }}">
                                           
                                        <td>{{$i++}}</td>
                                            <td>
                                                {{ $projectSkil->name ?? '' }}
                                            </td>
                                            <td>
                                                @can('project_status_show')
                                                    <a  href="{{ route('admin.project-skill.show', $projectSkil->id) }}">
                                                    <button class="btn btn-sm btn-icon me-2" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>View</span>"><i class="bx bx-show mx-1"></i></button>
                                                    </a>
                                                @endcan

                                                @can('project_status_edit')
                                                    <a href="{{ route('admin.project-skill.edit', $projectSkil->id) }}">
                                                    <button class="btn btn-sm btn-icon me-2" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Edit</span>"><i class="bx bx-edit"></i></button>
                                                    </a>
                                                @endcan

                                                @can('project_status_delete')
                                                    <form action="{{ route('admin.project-skill.destroy', $projectSkil->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <button type="submit" class="btn btn-sm btn-icon delete-record" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Delete</span>"><i class="bx bx-trash"></i></button>
                                                    </form>
                                                @endcan

                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $projectSkill->links() !!}
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
url: "{{ route('admin.project-skill.massDestroy') }}",
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
