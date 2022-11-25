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
            <div class="col-lg-6 col-md-6 col-sm-12 d-flex">
               @can('project_create')
               <a class="btn-sm btn-success text-center pt-2" style="height: 37px; font-size: 12px;" href="{{ route('admin.mail.create') }}">
               Add Mail
               </a>
               @endcan
               {{-- <a class="btn-sm btn-info" style="margin-left: 1px; height: 30px; font-size: smaller;" href="{{url('/admin/projects-pdf')}}">
               Export to Pdf
               </a> --}}
               <!-- <button id="btnExport" style="margin-left: 5px;  height: 38px; font-size: 9px; border:none;" onClick="fnExcelReport()" class="btn-sm btn-secondary clearfix"><span class="fa fa-file-excel-o"></span> Export to Excel</button>  -->
            </div>
            
            <div class="col-lg-6 col-md-6 col-sm-12 pl-2">
               <!-- <form action="" method="GET" class="d-flex">
                  <div class="mb-3">
                     <div class="input-group input-daterange" class="daterange">
                        <input type="text" name="start_date"  id="Startdate" onfocus="(this.type='date')" value="{{Request::get('start_date') ?? ''}}" placeholder ="Start Date" class="form-control" />
                        <span class="input-group-text">To</span>
                        <input type="text" name="end_date" id="Enddate" value="{{Request::get('end_date') ?? ''}}" onfocus="(this.type='date')" placeholder ="End Date" class="form-control" />
                     </div>
                  </div>
                  <div class="d-flex" style="margin-left: 8px;">
                     <button class="btn-sm search-btn" type="submit" style="height: 37px; margin-right:2px">  <i class="fa fa-search pl-3" aria-hidden="true"></i> </button>
                    
                  </div>
               </form> -->
               <div style="float:right">
               <a href="{{url('admin/mail')}}"><i class="fa fa-refresh pl-3" style="border: 1px solid #beb3b3; padding:10px; border-radius:6px" aria-hidden="true"></i></a>
               </div>
            </div>
         </div>
         <div class="card">
            <div class="card-header">
               <div class="row">
                  <div class="col-xl-6">
                     <h5 class="m-0 mb-1">Mail Template</h5>
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
                         
                              <!-- <div class="selected_delete" style="float:right;">
                                 <button type="submit" class="btn-sm btn-danger">Delete Selected</button>
                                 </div> -->
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="card-body">
            <div class="table-responsive card-datatable">
            <table id="exportToTable" class=" datatables-basic table border-top table table-bordered table-striped table-hover datatable-Project">
            <thead>
            <tr>
            <!--   <th>
               <div class="form-check">
               <input type="checkbox" class="form-check-input" id="materialUnchecked">
                   </div>
               </th> -->
            <th>
            S No.
            </th>
            <th class="wd-15p">Name</th>
            <th class="wd-20p">Subject</th>
            <th class="wd-15p">From</th>
            <!-- <th class="wd-15p">Message Categories</th> -->
            <th class="wd-15p">Actions</th>
            </tr>
            </thead>
            <tbody id="">
            @if(count($all_msg)>0)
            @php $i=1 @endphp
            @foreach($all_msg as $key => $item)
            <tr data-entry-id="{{ $item->id }}">
            <td>{{$i++}}</td>
            <td>{{$item->name ?? '' }}</td>
            <td>{{$item->subject ?? '' }}</td>
            <td>{{$item->from_email ?? '' }}</td>
            <!-- <td>{{$item->msg_category ?? '' }}</td> -->
            <td>
            <a  href="{{ route('admin.mail.edit', $item->id) }}">
             <i class="bx bx-edit"  data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Edit</span>"></i>
             </a>
            

             <form action="{{ route('admin.mail.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure');" style="display: inline-block;">
              <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-sm btn-icon delete-record"><i class="bx bx-trash" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Delete</span>"></i></button>
            </form>
            

            </td>
            </tr>
            @endforeach 
            @endif

            </tbody>
            </table>
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