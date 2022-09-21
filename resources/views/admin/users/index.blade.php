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
            <div class="col-lg-12">
               
                <!-- <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12"> 
                        <a class="btn-sm btn-info" style="height: 30px; font-size: smaller; padding: 6px 7px 7px 8px; margin-left: 11px;" href="{{url('/admin/users')}}">Back
                        </a>
                        @can('user_create')
                        <a class="btn-sm btn-success" style="height: 30px; margin-left: 8px; font-size: smaller; padding: 6px 7px 7px 8px;" href="{{ route("admin.users.create") }}">
                            {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}
                        </a>
                        @endcan
                    </div>
                </div> -->
                <div style="margin-bottom: 10px;" class="row p-0">
                    <div class="col-lg-6 col-md-6 col-sm-12 d-flex">
                        @can('user_create')
                        <a class="btn-sm btn-success pt-2" style="height: 38px; margin-left: 8px; font-size: smaller; padding: 9px 9px 10px 12px;" href="{{ route("admin.users.create") }}">
                           Add
                        </a>
                        @endcan
                      <button id="btnExport" style="margin-left: 5px;  height: 38px; font-size: 13px; border:none;" onClick="fnExcelReport()" class="btn-sm btn-secondary clearfix"><span class="fa fa-file-excel-o"></span>Excel</button>

                      </div>
                  
                      <div class="col-lg-6 col-md-6 col-sm-12 pl-2">
                        <div style="float:right;">
                            <a href="{{url('/admin/users')}}"><i class="fa fa-refresh pl-3" style="border: 1px solid #beb3b3; padding:10px; border-radius:6px" aria-hidden="true"></i></a>
                        </div>
                         
                     
                  </div>

              </div>
            <div class="card">
                <div class="card-header">
              <div class="row">
                <div class="col-xl-6">
                   User List
            </div>
                <div class="col-xl-6">
                    <?php if(!empty($_GET['user_filter'])){$filter= $_GET['user_filter'];}else{ $filter='';} 
                    if(!empty($_GET['search'])){$search= $_GET['search'];}else{ $search='';}
                    ?>
                  
                    <div class="right-item" style="float:right;">
                    <div class="row">
                        <div class="col-xl-6 p-0">
                            <form action="" method="get" id="filter_form">
                            <select class="form-control" id="user_filter" name="user_filter">
                            <option value="" class="text-center">Select Role</option>
                                <option value="Client" class="text-center" @if($filter=="Client") selected @endif>Client</option>
                                <option value="Freelancer" class="text-center"  @if($filter=="Freelancer") selected @endif>Freelancer</option>
                                </select>
                            </form> </div>
                        <div class="col-xl-6">
                            <form action="" class="d-flex" method="get">
                                <input type="text" name="search" class="form-control" value="{{$search}}" placeholder="Search user">
                              <button class="search-btn" style="margin-left:8px;" type="submit"> <i class="fa fa-search pl-3" aria-hidden="true"></i> </button>
                            </form>
                        </div>
                    </div>
                </div>
                </div>
              </div>
                </div>
            
                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-User example">
                            <thead>
                                <tr>
                                  
                                    <th>
                                        S No.
                                    </th>
                                    <th>
                                        {{ trans('cruds.user.fields.name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.user.fields.email') }}
                                    </th>
                                   
                                    <th>
                                        {{ trans('cruds.user.fields.roles') }}
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
                                @foreach($users as $key => $user)
                                    <tr data-entry-id="{{ $user->id }}">
                                      
                                        <td>
                                            {{$i++}}
                                        </td>
                                        <td>
                                            {{ $user->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $user->email ?? '' }}
                                        </td>

                                        <td>
                                            @foreach($user->roles as $key => $item)
                                                <span class="badge badge-info">{{ $item->title }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a href="{{url('admin/user/statusupdate',$user->id)}}"><span class="badge badge-info">{{ $user->status ?? '' }}</span></a>
                                            
                                        </td>
                                        <td>
                                            @can('user_show')
                                                <a href="{{ route('admin.users.show', $user->id) }}">
                                                    <button class="btn btn-sm btn-icon me-2"><i class="bx bx-show mx-1" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>View</span>"></i></button>
                                                </a>
                                            @endcan
            
                                            @can('user_edit')
                                                <a href="{{ route('admin.users.edit', $user->id) }}">
                                                    <button class="btn btn-sm btn-icon me-2"><i class="bx bx-edit" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Edit</span>"></i></button>
                                                </a>
                                            @endcan
            
                                            @can('user_delete')
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
                        {!! $users->links() !!}
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
