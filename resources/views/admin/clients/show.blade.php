@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        {{ trans('global.show') }} {{ trans('cruds.client.title') }}
                    </div>
                
                    <div class="card-body">
                        <div class="mb-2">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.client.fields.id') }}
                                        </th>
                                        <td>
                                            {{ $clients->id }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                          Name
                                        </th>
                                        <td>
                                            {{ $clients->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                           Email
                                        </th>
                                        <td>
                                        {{ $clients->email }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                           Total Post Projects/Jobs
                                        </th>
                                        <td>
                                        {{ count($Projects) }}
                                        </td>
                                    </tr>
                                 
                                 
                                </tbody>
                            </table>
                            @if(count($Projects)>0)
                            <div class="card-header p-0 mt-4 mb-2">
                    Post Projects/Jobs
                    </div>
 <div class="card-body p-0 mt-4">
      <div class="table-responsive">
        <table class=" table table-bordered table-striped table-hover datatable datatable-Project">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.project.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.project.fields.name') }}
                    </th>
                  
                    <th>
                        {{ trans('cruds.project.fields.description') }}
                    </th>
                    <th>
                        {{ trans('cruds.project.fields.start_date') }}
                    </th>
                    <th>
                        End Date
                    </th>
                    <th>
                        {{ trans('cruds.project.fields.budget') }}
                    </th>
                    <th>
                        {{ trans('cruds.project.fields.status') }}
                    </th>
                    <th>
                       Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($Projects as $key => $project)
                    <tr data-entry-id="{{ $project->id }}">
                        <td>

                        </td>
                        <td>
                            {{ $project->id ?? '' }}
                        </td>
                        <td>
                            {{ $project->name ?? '' }}
                        </td>
                      
                        <td>
                           {!! \Illuminate\Support\Str::limit($project->description, 40) !!}
                        </td>
                        <td>
                            {{ $project->start_date ?? '' }}
                        </td>
                        <td>
                            {{ $project->end_date ?? '' }}
                        </td>
                        <td>
                        @if($project->payment_base=='fixed')
                        {{ $project->total_budget }}
                    
                    @endif
                    @if($project->payment_base=='hourly')
                       {{ $project->per_hour_budget }}
                    
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
                                <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                </form>
                            @endcan

                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
                            @endif
                            <a style="margin-top:20px;" class="btn btn-success" href="{{ url()->previous() }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                
                        <nav class="mb-3">
                            <div class="nav nav-tabs">
                
                            </div>
                        </nav>
                        <div class="tab-content">
                
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