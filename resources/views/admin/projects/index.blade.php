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
                <div class="col-lg-4 col-md-4 col-sm-12 mt-2 d-flex">
                      @can('project_create')
                    <a class="btn-sm btn-success" style="height: 30px; font-size: smaller;" href="{{ route("admin.projects.create") }}">
                     Add Project
                 </a>
                 @endcan
                 <a class="btn-sm btn-info" style="margin-left: 1px; height: 30px; font-size: smaller;" href="{{url('/admin/projects-pdf')}}">
                  Export to Pdf
              </a>
              <button id="btnExport" style="margin-left: 1px;  height: 30px; font-size: smaller; border:none;" onClick="fnExcelReport()" class="btn-sm btn-secondary clearfix"><span class="fa fa-file-excel-o"></span> Export to Excel</button>

          </div>
          <div class="col-lg-4 col-md-4 col-sm-12 mt-2"> <?php 
            if(!empty($_GET['search'])){$search= $_GET['search'];}else{ $search='';}
            ?>
            
            <div class="right-item" >
                
                   
                  
                        <form action="" class="d-flex" method="get">
                            <input type="text" name="search" class="form-control" value="{{$search}}" style="height: 32px;" placeholder="Enter project/job name">
                          <button class="btn-sm search-btn" type="submit"> <i class="fa fa-search pl-3" aria-hidden="true"></i> </button>
                        </form>
                    
                
            </div></div>
          <div class="col-lg-4 col-md-4 col-sm-12 pl-2 mt-2">

            <div class="d-flex" >
                <form action="" method="GET" class="d-flex p-0" >
                    <div class="items" style="margin-left: 10px; width: 75px !important;">

                        <select class="form-select" name="day" style="width: 73%; font-size: 11px;  height: 32px;" aria-label="Default select example">
                        <option >Day</option> 

                             <option value="all" @if($day=='all') selected @endif>All</option>
                       
                        @for ($i = 1; $i < 32; $i++)
                            <option value="{{$i}}" @if($day==$i) selected @endif>{{$i}}</option>
                            @endfor



                        </select>
                    </div>
                    <div class="items" style="margin-left: 10px; width: 75px !important;">

                        <select class="form-select m-0" name="month" style="width: 73%; font-size: 11px;  height: 32px;" aria-label="Default select example">
                        <option>Month</option> 
                            <option value="all" @if($month=='all') selected @endif>All</option>
                            <option  value='1' @if($month=='1') selected @endif>Jan</option>
                            <option value='2' @if($month=='2') selected @endif>Feb</option>
                            <option value='3' @if($month=='3') selected @endif>Mar</option>
                            <option value='4' @if($month=='4') selected @endif>Apr</option>
                            <option value='5' @if($month=='5') selected @endif>May</option>
                            <option value='6' @if($month=='6') selected @endif>June</option>
                            <option value='7' @if($month=='7') selected @endif>July</option>
                            <option value='8' @if($month=='8') selected @endif>Aug</option>
                            <option value='9' @if($month=='9') selected @endif>Sept</option>
                            <option value='10' @if($month=='10') selected @endif>Oct</option>
                            <option value='11' @if($month=='11') selected @endif>Nov</option>
                            <option value='12' @if($month=='12') selected @endif>Dec</option>
                        </select>
                    </div>
                    <div class="items" style="margin-left: 1px; width: 75px !important;">

                        <select class="form-select" name="year" style="width: 73%; font-size: 11px;  height: 32px;" aria-label="Default select example">
                        <option>Year</option> 
                          @for ($i = 2000; $i < 2050; $i++)
                          <option value="{{$i}}" @if($year==$i) selected @endif>{{$i}}</option>
                          @endfor
                      </select>
                  </div>
                  <div class="items" style="margin-left:1px width: 75px !important;">
                      <button class="btn-sm btn-info filter_btn" style="margin-left: 1px; height: 28px; font-size: smaller; border:none">Submit</button>
                 
                    </div>
              </form>
              <h4></h4>
          </div>
      </div>

  </div>

 
 
  <div class="card">
    <div class="card-header">
     <div class="row">
        <div class="col-xl-2">
          <h5 class="m-0 mb-1">Project</h5>
          <p class="m-0"><a href="">Project</a> / list</p>
        </div>
        <div class="col-xl-6">
            <form action="" method="GET" class="d-flex">
        
                <div class="col-xl-4 pr-5 " style="margin-right: 9px;">
                    <label>Start date</label>
                    <input type="date" name="start_date" value="{{Request::get('start_date') ?? ''}}">
                </div>
                 <h6 class="mt-4">To</h6>
                <div class="col-xl-4  " style="margin-left: 33px;">
                    <label>End date</label>
                  <input type="date" name="end_date" value="{{Request::get('end_date') ?? ''}}">
              </div>
              <div class="col-xl-4  mt-3" style="margin-left: 8px;">
                  <button class="btn-sm btn-info filter_btn" style="height: 30px; font-size: smaller; padding: 6px 7px 7px 8px; border:none" >Submit</button>
              </div>
          </form>
        </div>
        <div class="col-xl-4 mt-3">
        <div class="row">
         
         <div class="col-xl-12 d-flex">
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
         <form action="{{url('/admin/project-multi-delete')}}" method="POST">
            @csrf
        <div class="selected_delete" style="float:right;">
            <button type="submit" class="btn-sm btn-danger">Delete Selected</button>
        </div>
         </div>
        </div>
        </div>

     </div>
 </div>

 <div class="card-body">
    <div class="table-responsive">
       
      <table id="exportToTable" class=" table table-bordered table-striped table-hover datatable datatable-Project">
        <thead>
            <tr>
                  <th>
                <div class="form-check">
                <input type="checkbox" class="form-check-input" id="materialUnchecked">
                    </div>
                </th>
                <th>
                 #ID
             </th>
             <th>
                 Project Name
             </th>
             <th>
                 Client
             </th>
             <th>
              Description
          </th>
         
          <th>
           Publish date
        </th>
        <th>
          Budget
      </th>
      <th>
          Status
      </th>
      <th>
         Action
     </th>
 </tr>
</thead>
<tbody id="">
@if(count($projects)>0)
@foreach($projects as $key => $project)
<tr data-entry-id="{{ $project->id }}">
<th>

   <div class="form-check">
   <input type="checkbox" class="form-check-input" id="materialUnchecked" name="multi_delete[]" value="{{$project->id}}">
    </div>


</th>
    <td>
        {{ $project->id ?? '' }}
    </td>
    <td>
        {{ $project->name ?? '' }}
    </td>
    <td>
        {{ $project->client->name ?? '' }}
    </td>
    <td>
    {!! \Illuminate\Support\Str::limit($project->description, 30) !!}
    </td>
   
  <td>
   {{$project->created_at->toFormattedDateString()}}
</td>
<td>
    @if($project->payment_base=='fixed')
    {{ $project->total_budget }}$

    @endif
    @if($project->payment_base=='hourly')
    {{ $project->per_hour_budget }}$

    @endif
</td>
<td>
    {{ $project->status->name ?? '' }}
    
</td>
<td>
    @can('project_show')
    <a class="btn btn-xs btn-primary" href="{{ route('admin.projects.show', $project->id) }}">
        {{ trans('global.view') }}
    </a>
    @endcan

    @can('project_edit')
    <a class="btn btn-xs btn-info" href="{{ route('admin.projects.edit', $project->id) }}">
        {{ trans('global.edit') }}
    </a>
    @endcan

    @can('project_delete')
  
        
       <a href="project-delete/{{$project->id}}"> <input onclick="return confirm('{{ trans('global.areYouSure') }}');" type="button" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}"></a>

    @endcan

</td>

</tr>
@endforeach 
@else
<tr class="mb-3">

No Record Found

</tr>
@endif
</form>
</tbody>
</table>
          
{!! $projects->links() !!}
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
