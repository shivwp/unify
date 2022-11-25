@extends('layouts.master') @section('content')
<style type="text/css">
    button.btn.filter_btn {
        background-color: #696cff;
        color: white;
    }
    .Ticket_status {
    background-color: #ff3131 !important;
}
</style>
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
                <div class="row tabelhed d-flex justify-content-between">
                    <div class="col-lg-2 col-md-2 col-sm-12 d-flex">
                              @can('project_create')
                            <a class="btn-sm ad-btn create_btn text-center pt-2" href="{{ route("admin.support.create") }}">
                             Add
                         </a>
                         @endcan
                         <!-- <a class="btn-sm btn-info" style="margin-left: 1px; height: 30px; font-size: smaller;" href="{{url('/admin/projects-pdf')}}">
                          Export to Pdf
                      </a> -->
                      <button id="btnExport" onClick="fnExcelReport()" class="btn-sm ad-btn clearfix">Excel</button>
                        <!-- <span class="fa fa-file-excel-o"></span> -->
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-12 pl-2 row d-flex justify-content-end">
                        <div class="col-md-3">
                            <?php 
                            if(!empty($_GET['project_status_filter'])){
                              $status_filter= $_GET['project_status_filter'];
                            }else{
                             $status_filter='Select Status';
                           } 
                           ?>
                            <form action="" method="get" id="status_filter_form">
                                <select class="form-control" id="project_status_filter" name="project_status_filter">
                                    <option value="">{{$status_filter}}</option>
                                    <option value="active">active</option>
                                    <option value="hold">hold</option>
                                    <option value="pending">pending</option>
                                    <option value="closed">closed</option>
                                </select>
                            </form>
                        </div>
                        <div class="col-md-5">
                            <form action="" method="GET" style="float: right;" class="d-flex">
                              <?php
                                if(!empty($_GET['keyword'])){
                                  $Search = $_GET['keyword'];
                                }else{
                                  $Search = '';
                                }
                              ?>

                                <div class="mb-3">
                                  <input type="text" value="{{$Search}}" name="keyword" class="form-control" placeholder="Name or Email">
                                </div>
                                <div class="d-flex" style="margin-left: 8px;">
                                    <button class="btn-sm search-btn" type="submit" style="height: 37px; margin-right:6px;border: 1px solid #c7adadad;border-radius: 6px">  <i class="fa fa-search pl-3" aria-hidden="true"></i> </button>
                                    <a href="{{url('/admin/support')}}"><i class="fa fa-refresh pl-3" style="border: 1px solid #beb3b3; padding:10px; border-radius:6px" aria-hidden="true"></i></a>
                               
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

    {{--<div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-6 d-flex">
            <a class="btn-sm btn-info" style="height: 30px; font-size: smaller; padding: 6px 7px 7px 8px; margin-left: 11px;" href="{{url('/admin/support')}}">
                Clear
            </a>
        @can('transaction_create')
            <a class="btn-sm btn-success" style="height: 30px; font-size: smaller; padding: 6px 7px 7px 8px;margin-left: 5px;" href="{{ route("admin.support.create") }}">
             Add Ticket
         </a>
         @endcan
         <a class="btn-sm btn-info" style="height: 30px; font-size: smaller; padding: 6px 7px 7px 8px; margin-left: 11px;" href="{{url('/admin/support-pdf')}}">
          Export to Pdf
      </a>
      <!-- <button id="btnExport" style="margin-left: 11px;" onClick="fnExcelReport()" class="btn btn-secondary clearfix"><span class="fa fa-file-excel-o"></span> Export to Excel</button> -->

    </div>
    <div class="col-xl-6">

    <div class="row d-flex">
        <form action="" method="GET" class="d-flex">
            <div class="col-xl-3" >

                <select class="form-select" name="day"  style="font-size: 11px;  height: 32px;"  aria-label="Default select example"> <option value="all" @if($day=='all') selected @endif>All</option>
                    @for ($i = 1; $i < 32; $i++)
                    <option value="{{$i}}" @if($day==$i) selected @endif>{{$i}}</option>
                    @endfor



                </select>
            </div>
            <div class="col-xl-3" style="margin-left: 8px;">

                <select class="form-select"  style="font-size: 11px;  height: 32px;" name="month" aria-label="Default select example">
                    <option value="all" @if($month=='all') selected @endif>All</option>
                    <option  value='1' @if($month=='1') selected @endif>Janaury</option>
                    <option value='2' @if($month=='2') selected @endif>February</option>
                    <option value='3' @if($month=='3') selected @endif>March</option>
                    <option value='4' @if($month=='4') selected @endif>April</option>
                    <option value='5' @if($month=='5') selected @endif>May</option>
                    <option value='6' @if($month=='6') selected @endif>June</option>
                    <option value='7' @if($month=='7') selected @endif>July</option>
                    <option value='8' @if($month=='8') selected @endif>August</option>
                    <option value='9' @if($month=='9') selected @endif>September</option>
                    <option value='10' @if($month=='10') selected @endif>October</option>
                    <option value='11' @if($month=='11') selected @endif>November</option>
                    <option value='12' @if($month=='12') selected @endif>December</option>
                </select>
            </div>
            <div class="col-xl-3" style="margin-left: 8px;">

                <select class="form-select"  style="font-size: 11px;  height: 32px;" name="year" aria-label="Default select example">
                  @for ($i = 2000; $i < 2050; $i++)
                  <option value="{{$i}}" @if($year==$i) selected @endif>{{$i}}</option>
                  @endfor
              </select>
          </div>
          <div class="col-xl-3" style="margin-left: 8px;">
              <button class="btn-sm btn-info filter_btn" style="height: 30px; font-size: smaller; padding: 6px 7px 7px 8px; border:none;">Submit</button>
          </div>
      </form>
    </div>


    <div class="row d-flex">
        <form action="" method="GET" class="d-flex">
            
            <div class="col-xl-4 pr-5 mt-2" style="margin-right: 9px;">
                
                <input type="text" name="start_date" id="Startdate" onfocus="(this.type='date')" value="{{Request::get('start_date') ?? ''}}" placeholder ="Start Date">
            </div>
            <div class="col-xl-4  mt-2 " style="margin-left: 33px;">

              <input type="text" name="end_date" id="Enddate" value="{{Request::get('end_date') ?? ''}}" onfocus="(this.type='date')" placeholder ="End Date">
          </div>
          <div class="col-xl-4  mt-4" style="margin-left: 40px;">
              <button class="btn-sm btn-info filter_btn" style="height: 30px; font-size: smaller; padding: 6px 7px 7px 8px; border:none" >Search</button>
          </div>
      </form>
    </div>


    </div>

    </div>--}}

    <div class="card">
      <div class="card-header">
          Support Tickets List
      </div>

    <div class="card-body">
        <div class="table-responsive">
           @if (Session::has('delete'))
                <div class="alert alert-danger" id="danger-alert">
                    {{Session::get('delete')}}
              </div>
            @endif
             @if (Session::has('added'))
                <div class="alert alert-success" id="success-alert">
                    {{Session::get('added')}}
              </div>
            @endif
             @if (Session::has('update'))
                <div class="alert alert-success" id="success-alert">
                    {{Session::get('update')}}
              </div>
             @endif
            <table class=" table table-bordered table-striped table-hover datatable datatable-Transaction example" >
                <thead>
                    <tr>
                         
                        <th>
                            S No.
                        </th>
                        <th>
                            User Name
                        </th>
                        <th>
                            User Email
                        </th>
                        <th>
                         Status
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
                    @foreach($support as $key => $item)
                        <tr data-entry-id="{{ $item->id }}">
                            <td>
                               {{$i++}}
                            </td>
                           
                            <td>
                                {{ $item->name ?? '' }}
                            </td>
                            <td>
                                {{ $item->email ?? '' }}
                            </td>
                           <td>
                               
                                                @if($item->status == 'pending')
                                                <span class="btn-xs btn-warning text-capitalize">{{ $item->status ?? '' }}</span>
                                                @endif
                                                @if($item->status == 'closed')
                                                <span class="btn-xs btn-danger text-capitalize">Closed</span>
                                                @endif
                                                   @if($item->status == 'active')
                                                <span class="btn-xs btn-success text-capitalize">Active</span>
                                                @endif
                                                @if($item->status == 'hold')
                                                <span class="btn-xs btn-info text-capitalize">Hold</span>
                                                @endif
                                            
                           </td>
                            <td>
                                @can('transaction_show')
                                    <a href="{{ route('admin.support.show', $item->id) }}">
                                       <button class="btn btn-sm btn-icon me-2"><i class="bx bx-show mx-1"
                                        data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>View</span>"></i></button>
                                    </a>
                                @endcan

                                @can('transaction_edit')
                                    <a href="/admin/support-edit/{{$item->id}}">
                                         <button class="btn btn-sm btn-icon me-2"><i class="bx bx-edit" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Edit</span>"></i></button>
                                    </a>
                                   
                                @endcan

                                @can('transaction_delete')
                                    <form action="/admin/support-delete/{{$item->id}}" method="get" style="display: inline-block;">
                                      
                                        
                                        <button type="submit" class="btn btn-sm btn-icon delete-record"><i class="bx bx-trash" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Delete</span>"></i></button>
                                    </form>
                                @endcan
                              <!--  @if($item->status!="closed")
                               <a onclick="return confirm('{{ trans('Are You Sure To Closed This Ticket') }}');" href="/admin/support-closed/{{$item->id}}">
                                       <button class="btn btn-sm btn-icon me-2"><i class="bx bx-check-double" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Closed</span>"></i></button>
                                    </a>
                               @endif -->
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $support->links() !!}
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
@can('transaction_delete')
let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
let deleteButton = {
text: deleteButtonTrans,
url: "{{ route('admin.transactions.massDestroy') }}",
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
$('.datatable-Transaction:not(.ajaxTable)').DataTable({ buttons: dtButtons })
$('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
    $($.fn.dataTable.tables(true)).DataTable()
        .columns.adjust();
});
})

// For auto alert

$("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
    $("#success-alert").slideUp(500);
});

$(document).ready(function() {
  $("#success-alert").hide();
  $("#myWish").click(function showAlert() {
    $("#success-alert").fadeTo(2000, 500).slideUp(500, function() {
      $("#success-alert").slideUp(500);
    });
  });
});

</script> 
@endsection
