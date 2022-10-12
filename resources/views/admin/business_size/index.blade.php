@extends('layouts.master') @section('content')
<style type="text/css">
    button.btn.filter_btn {
        background-color: #696cff;
        color: white;
    }
   
    .search-btn {
    border: 1px solid #d7cbcb;
    padding: 2px 9px 2px 11px;
    border-radius: 8px;
}

</style>
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">


          
            <div style="margin-bottom: 10px;" class="row p-0">
                <div class="col-lg-6 col-md-6  col-sm-12 d-flex">
                      @can('project_create')
                    <a class="btn-sm btn-success text-center pt-2" style="height: 37px; font-size: 13px;" href="{{ route("admin.business_size.create") }}">
                     Add
                 </a>
                 @endcan
              
              <button id="btnExport" style="margin-left: 5px;  height: 38px; font-size: 13px; border:none;" onClick="fnExcelReport()" class="btn-sm btn-secondary clearfix">Excel</button> 

          </div>
        
           <div class="col-lg-6 col-md-6 col-sm-12 pl-2">
         
      </div>

  </div>
<div class="card">
    <div class="card-header">
     <div class="row">
        <div class="col-xl-6">
          <h5 class="m-0 mb-1">Business size</h5>
         
        </div>
      
        <div class="col-xl-6 ">
        <div class="row" style="float:right">
         
         <div class="col-xl-12 d-flex" style="float: right">
         <div class="items" style="margin-left: 1px; width: 75px !important;">

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

     </div>
 </div>

 <div class="card-body">
    <div class="table-responsive card-datatable">
       
      <table id="exportToTable" class=" datatables-basic table border-top table table-bordered table-striped table-hover datatable datatable-Project">
        <thead>
            <tr>
                <th>S No.</th>
                <th>Title</th>
                <th>Employees size</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="">
        @if(count($Business_size)>0)
        @php $i=1 @endphp
        @foreach($Business_size as $key => $item)
        <tr data-entry-id="{{ $item->id }}">
        <td>{{$i++}}</td>
            <td>
                {{ $item->title ?? '' }}
            </td>
            <td>
                {{ isset($item->min_employee) ? $item->min_employee : '' }}  {{ isset($item->max_employee) ? '-'.$item->max_employee : ''}}
            </td>
        <td>


            @can('project_edit')
            <a  href="{{ route('admin.business_size.edit', $item->id) }}">
            <i class="bx bx-edit"  data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Edit</span>"></i>
            </a>
            @endcan

            @can('project_delete')
          
                
               <a href="business-delete/{{$item->id}}"><i class="bx bx-trash"data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Delete</span>" onclick="return confirm('{{ trans('global.areYouSure') }}');"></i></a>

            @endcan

        </td>

        </tr>
        @endforeach 
        @else
        <tr class="mb-3">

        No Record Found

        </tr>
        @endif

        </tbody>
</table>
          
{!! $Business_size->links() !!}
</div>
</div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        @can('project_delete')
        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.projects.massDestroy') }}",
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
    $('.datatable-Project:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
        .columns.adjust();
    });
})

</script> 
@endsection
