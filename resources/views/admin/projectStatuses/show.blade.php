@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">


                <div class="card">
                    <div class="card-header">
                        {{ trans('global.show') }} {{ trans('cruds.projectStatus.title') }}
                    </div>
                
                    <div class="card-body">
                        <div class="mb-2">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.projectStatus.fields.id') }}
                                        </th>
                                        <td>
                                            {{ $projectStatus->id }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.projectStatus.fields.name') }}
                                        </th>
                                        <td>
                                            {{ $projectStatus->name }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
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

@endsection
