@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 ">

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-6 mt-2 col-md-6 col-sm-6 col-6">
            
            <a class="btn-sm btn-success" style="height: 30px; font-size: smaller; margin-left: 10px;padding: 8px 10px 10px 11px;" href="{{ route("admin.proposal.create") }}">
                Add
            </a>
        </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-3 mt-2 p-0">
    <form action="" method="GET" id="project_filter">
    <?php 
            if(!empty($_GET['project'])){$project_filter= $_GET['project'];}else{ $project_filter='';}
            if(!empty($_GET['freelancer'])){$freelancer_filter= $_GET['freelancer'];}else{ $freelancer_filter='';}
            ?>
    <select class="form-select" name="project" ud="project_filter" style="width: 99%; font-size: 11px;  height: 38px;" aria-label="Default select example">
                        <option value="">Select Project</option> 
                          @foreach($project as $item)
                            <option value="{{$item->id}}" @if($project_filter==$item->id) selected @endif>{{$item->name}}</option>
                            @endforeach
                        </select>
    </form>
            </div>
    <div class="col-lg-3 col-md-3 col-sm-3 mt-2 col-3  d-flex">
                      <form action="" method="GET" id="freelancer_filter" style="margin-right: 6px;width: 100%;">
                      <select class="form-select" name="freelancer" id="freelancer_filter" style="width: 99%; font-size: 11px;  height: 38px;" aria-label="Default select example">
                        <option value=""> Select Freelancer</option> 
                        @foreach($freelancer as $item)
                            <option value="{{$item->id}}" @if(!empty($freelancer_filter==$item->id)) selected @endif>{{$item->name}}</option>
                            @endforeach
                        </select>
                      </form>
                      <a href="{{url('/admin/proposal')}}"><i class="fa fa-refresh pl-3" style="border: 1px solid #beb3b3; padding:10px; border-radius:6px" aria-hidden="true"></i></a>
     </div>
</div>

<div class="card">
<div class="card-header">
   <div class="row">
   <div class="col-lg-6">  <h5 class="m-0 mb-1">Proposal</h5>
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
                    
                    <th>
                      S No.
                    </th>
                    <th>
                  Projects
                    </th>
                    <th>
                  Freelancers
                    </th>
                    <th>
                  Status
                    </th>
                    <th>
                   Bid-Amount
                    </th>
                    <th>
                      Action
                    </th>
                </tr>
            </thead>
            <tbody>
            @php $i=1 @endphp
             @foreach($proposal as $key => $item)
                    <tr data-entry-id="{{ $item->id }}">
                      
                    <td>{{$i++}}</td>
   
                       
                         <td>
                            {{ $item->project->name ?? '' }}
                        </td>
                        <td>
                        {{ $item->freelancer->name ?? '' }}
                           
                        </td>
                        <td>
                        {{ $item->status ?? '' }}
                           
                        </td>
                        <td>
                        ${{ $item->amount ?? '' }}
                           
                        </td>
                        <td>
                               
                        @can('project_show')
                                <a  href="/admin/proposal-show/{{$item->id}}">
                                <button class="btn btn-sm btn-icon me-2" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>View</span>"><i class="bx bx-show mx-1"></i></button>
                                </a>
                            @endcan
                            @can('project_category_edit')
                                <a  href="proposal-update/{{$item->id}}">
                                <button class="btn btn-sm btn-icon me-2" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Edit</span>"><i class="bx bx-edit"></i></button>
                                </a>
                            @endcan

                            @can('project_category_delete')
                                <form action="{{ route('admin.proposal.destroy', $item->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                @csrf    
                                <input type="hidden" name="_method" value="DELETE">
                                    
                                    <input type="hidden" name="id" value="{{$item->id}}">
                                    <button  type="submit" class="btn btn-sm btn-icon delete-record" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Delete</span>"><i class="bx bx-trash"></i></button>
                                </form>
                            @endcan

                        </td>

                    </tr>
                @endforeach 
            </tbody>
        </table>
        {!! $proposal->links() !!}
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
