@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">

            <!-- @can('client_create')
            <div style="margin-bottom: 10px;" class="row">
                <div class="col-lg-12">
                    <a class="btn btn-success" href="{{ route("admin.clients.create") }}">
                        {{ trans('global.add') }} {{ trans('cruds.client.title_singular') }}
                    </a>
                </div>
            </div>
            @endcan -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        {{ trans('cruds.client.title_singular') }} {{ trans('global.list') }}
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class=" table table-bordered table-striped table-hover datatable datatable-Client">
                                <thead>
                                    <tr>
                                        
                                        <th>
                                            {{ trans('cruds.client.fields.id') }}
                                        </th>
                                        <th>
                                          Name
                                        </th>
                                        <th>
                                        email
                                        </th>
                                      
                                        <th>
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clients as $key => $client)
                                    <tr data-entry-id="{{ $client->id }}">
                                       
                                        <td>
                                            {{ $client->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $client->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $client->email ?? '' }}
                                        </td>
                                       
                                        <td>
                                            @can('client_show')
                                            <a class="btn btn-xs btn-primary"
                                                href="clients-show/{{$client->id}}">
                                                {{ trans('global.view') }}
                                            </a> 
                                            <!-- @endcan @can('client_edit')
                                            <a class="btn btn-xs btn-info"
                                                href="{{ route('admin.clients.edit', $client->id) }}">
                                                {{ trans('global.edit') }}
                                            </a> @endcan @can('client_delete')
                                            <form action="{{ route('admin.clients.destroy', $client->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="submit" class="btn btn-xs btn-danger"
                                                    value="{{ trans('global.delete') }}">
                                            </form>
                                            @endcan -->

                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $clients->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->

<!-- Footer -->



@endsection