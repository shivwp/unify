@extends('layouts.master') @section('content')
<style type="text/css">
    button.btn.filter_btn {
        background-color: #696cff;
        color: white;
    }
</style>
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
            

            <div style="margin-bottom: 10px;" class="row p-0">
                <div class="col-lg-2 col-md-2 col-sm-12 d-flex">
                      {{--@can('project_create')
                    <a class="btn-sm btn-success text-center" style="height: 37px; font-size: 10px;" href="{{ route("admin.projects.create") }}">
                     Add    
                 </a>
                 @endcan--}}
                 <!-- <a class="btn-sm btn-info" style="margin-left: 1px; height: 30px; font-size: smaller;" href="{{url('/admin/projects-pdf')}}">
                  Export to Pdf
              </a> -->
              <button id="btnExport" style="margin-left: 5px;  height: 38px; font-size: 13px; border:none;" onClick="fnExcelReport()" class="btn-sm btn-secondary clearfix">Excel</button>

          </div>
          <div class="col-lg-5 col-md-5 col-sm-12 "> 
            {{--<?php 
            if(!empty($_GET['search'])){$search= $_GET['search'];}else{ $search='';}
            ?>
            
            <div class="right-item" >
                
                <form action="" class="d-flex" method="get">
                    <input type="text" name="search" class="form-control" value="{{$search}}" style="height: 39px;" placeholder="Enter project/job name">
                  <button class="btn-sm search-btn" type="submit"> <i class="fa fa-search pl-3" aria-hidden="true"></i> </button>
                </form>
             </div>--}}
            </div>
          <div class="col-lg-5 col-md-5 col-sm-12 pl-2">

              <form action="" method="GET" class="d-flex">
                <div class="mb-3">
                   
                    <div class="input-group input-daterange" class="daterange">
                      <input type="text" name="start_date"  id="Startdate" onfocus="(this.type='date')" value="{{Request::get('start_date') ?? ''}}" placeholder ="Start Date" class="form-control" />
                      <span class="input-group-text">To</span>
                      <input type="text" name="end_date" id="Enddate" value="{{Request::get('end_date') ?? ''}}" onfocus="(this.type='date')" placeholder ="End Date" class="form-control" />
                    </div>
                    </div>
              <div class="d-flex" style="margin-left: 8px;">
                <button class="btn-sm search-btn" type="submit" style="height: 37px; border: 1px solid #beb3b3;
                margin-right:6px">  <i class="fa fa-search pl-3" aria-hidden="true"></i> </button>
                <a href="{{url('/admin/transactions')}}"><i class="fa fa-refresh pl-3" style="border: 1px solid #beb3b3; padding:10px; border-radius:6px" aria-hidden="true"></i></a>
               
            </div>
          </form>
         
      </div>

  </div>





{{--<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-6 d-flex">
    @can('transaction_create')
        <a class="btn-sm btn-success" style="height: 30px; font-size: smaller; padding: 6px 7px 7px 8px;" href="{{ route("admin.transactions.create") }}">
         Add Transactions
     </a>
     @endcan
        <a class="btn-sm btn-info" style="height: 30px; font-size: smaller; padding: 6px 7px 7px 8px; margin-left: 11px;" href="{{url('/admin/transactions')}}">
            Clear
        </a>
     <a class="btn-sm btn-info" style="height: 30px; font-size: smaller; padding: 6px 7px 7px 8px; margin-left: 11px;" href="{{url('/admin/transaction-pdf')}}">
      Export to Pdf
  </a>
  <!-- <button id="btnExport" style="margin-left: 11px;" onClick="fnExcelReport()" class="btn btn-secondary clearfix"><span class="fa fa-file-excel-o"></span> Export to Excel</button> -->

</div>
<div class="col-xl-6">

<div class="row d-flex">
    <form action="" method="GET" class="d-flex">
        <div class="col-xl-3" style="">

            <select class="form-select" name="day" style="font-size: 11px;  height: 32px;" aria-label="Default select example"> <option value="all" @if($day=='all') selected @endif>All</option>
                @for ($i = 1; $i < 32; $i++)
                <option value="{{$i}}" @if($day==$i) selected @endif>{{$i}}</option>
                @endfor



            </select>
        </div>
        <div class="col-xl-3" style="margin-left: 8px;">

            <select class="form-select" name="month" style="font-size: 11px;  height: 32px;" aria-label="Default select example">
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

            <select class="form-select" name="year" style="font-size: 11px;  height: 32px;" aria-label="Default select example">
              @for ($i = 2000; $i < 2050; $i++)
              <option value="{{$i}}" @if($year==$i) selected @endif>{{$i}}</option>
              @endfor
          </select>
      </div>
      <div class="col-xl-3" style="margin-left: 8px;">
          <button class="btn-sm btn-info filter_btn" style="height: 30px; font-size: smaller; padding: 6px 7px 7px 8px; border:none" >Submit</button>
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
    {{ trans('cruds.transaction.title_singular') }} {{ trans('global.list') }}
</div>

<div class="card-body">
    <div class="table-responsive">
        <table class=" table table-bordered table-striped table-hover datatable datatable-Transaction example">
            <thead>
                <tr>
                    
                    <th>
                        S No.
                    </th>
                    <th>
                        {{ trans('cruds.transaction.fields.project') }}
                    </th>
                    <th>
                   {{ trans('cruds.transaction.fields.name') }}
                    </th>
                    <th>
                   Email
                    </th>
                    
                    <th>
                        {{ trans('cruds.transaction.fields.amount') }}
                    </th>
                   
                    <th>
                      {{ trans('cruds.transaction.fields.transaction_type') }}
                    </th>
                    <th>
                    {{ trans('cruds.transaction.fields.transaction_date') }}
                    </th>
                    <th>
                       Short Description
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
                @foreach($transactions as $key => $transaction)
                    @php
                        $userdata = App\Models\User::where('id',$transaction->user_id)->first();
                        if($userdata){
                            $username = $userdata->name;
                            $useremail = $userdata->email;
                        }else{
                            $username = '';
                            $useremail = '';
                        }
                    @endphp
                    <tr data-entry-id="{{ $transaction->id }}">
                       
                        <td>
                            {{$i++}}
                        </td>
                        <td>
                            {{ $transaction->project->name ?? '' }}
                        </td>
                       
                        <td>
                            {{ $username ?? '' }}
                        </td>
                        <td>
                            {{ $useremail ?? '' }}
                        </td>
                        <td>
                            {{ $transaction->amount ?? '' }}$
                        </td>
                        <td>
                        {{ $transaction->transaction_type->name ?? '' }}
                        </td>
                        <td>
                            {{ $transaction->created_at->toFormattedDateString() }}
                            
                        </td>
                       
                        <td>
                            {{ $transaction->description ?? '' }}
                        </td>
                        <td>
                            @can('transaction_show')
                                <a href="{{ route('admin.transactions.show', $transaction->id) }}">
                                     <button class="btn btn-sm btn-icon me-2"><i class="bx bx-show mx-1" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>View</span>"></i></button>
                                </a>
                            @endcan

                            @can('transaction_edit')
                                <a href="{{ route('admin.transactions.edit', $transaction->id) }}">
                                   <button class="btn btn-sm btn-icon me-2"><i class="bx bx-edit" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Edit</span>"></i></button>
                                </a>
                            @endcan

                            @can('transaction_delete')
                                <form action="{{ route('admin.transactions.destroy', $transaction->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
        {!! $transactions->links() !!}
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

</script>

@endsection
