@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
                <!-- @can('role_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn-sm btn-info" style="height: 30px; font-size: smaller; padding: 6px 7px 7px 8px; margin-left: 11px;" href="{{url('admin/roles')}}">Back
                        </a>
                        <a class="btn-sm btn-success" style="height: 30px; margin-left: 10px; font-size: smaller; padding: 6px 7px 7px 8px;" href="{{ route("admin.roles.create") }}">
                            Add Roles
                        </a>
                    </div>
                </div> -->

            @endcan
              
        <div style="margin-bottom: 10px;" class="row p-0">
            <div class="col-lg-6 col-md-6 col-sm-12 d-flex">
                @can('permission_create')
                <a class="btn-sm btn-success pt-2
                " style="margin-left: 10px;height: 38px; font-size: smaller; padding: 6px 7px 7px 8px;" href="{{ route("admin.roles.create") }}">
                 Add
                </a>
                @endcan
             <!-- <a class="btn-sm btn-info" style="margin-left: 1px; height: 30px; font-size: smaller;" href="{{url('/admin/projects-pdf')}}">
              Export to Pdf
          </a> -->
          <button id="btnExport" style="margin-left: 5px;  height: 38px; font-size: 12px; border:none;" onClick="fnExcelReport()" class="btn-sm btn-secondary clearfix">Excel</button>

      </div>
   
      <div class="col-lg-6 col-md-6 col-sm-12 pl-2">
<div style="float: right;">
    <a href="{{url('admin/roles')}}"><i class="fa fa-refresh pl-3" style="border: 1px solid #beb3b3; padding:10px; border-radius:6px" aria-hidden="true"></i></a>

</div>
  </div>

</div>
            <div class="card">
                <div class="card-header">
                    Roles List
                </div>
            
                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Role">
                            <thead>
                                <tr>
                                  
                                    <th>
                                        S No.
                                    </th>
                                    <th>
                                        {{ trans('cruds.role.fields.title') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.role.fields.permissions') }}
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
                                @foreach($roles as $key => $role)
                                    <tr data-entry-id="{{ $role->id }}">
                                       
                                        <td>
                                            {{$i++}}
                                        </td>
                                        <td>
                                            {{ $role->title ?? '' }}
                                        </td>
                                        <td>
                                            @foreach($role->permissions as $key => $item)
                                                <span class="badge badge-info">{{ $item->title }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @can('role_show')
                                                <a href="{{ route('admin.roles.show', $role->id) }}">
                                                    <button class="btn btn-sm btn-icon me-2"><i class="bx bx-show mx-1" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>View</span>"></i></button>
                                                </a>
                                            @endcan
            
                                            @can('role_edit')
                                                <a href="{{ route('admin.roles.edit', $role->id) }}">
                                                    <button class="btn btn-sm btn-icon me-2"><i class="bx bx-edit" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Edit</span>"></i></button>
                                                </a>
                                            @endcan
            
                                            @can('role_delete')
                                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
                    </div>
                </div>
            </div>
            @endsection
            @section('scripts')
            @parent
            <script>
                $(function () {
              let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('role_delete')
              let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
              let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.roles.massDestroy') }}",
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
              $('.datatable-Role:not(.ajaxTable)').DataTable({ buttons: dtButtons })
                $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
                    $($.fn.dataTable.tables(true)).DataTable()
                        .columns.adjust();
                });
            })
            
            </script>

@endsection
