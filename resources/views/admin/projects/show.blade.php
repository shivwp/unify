@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.project.title') }}
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>ID</th>
                        <td>{{ $project->id }}</td>
                    </tr>
                    <tr>
                        <th>Project Name</th>
                        <td>{{ $project->name }}</td>
                    </tr>
                    <tr>
                        <th>Project Description</th>
                        <td>{!! $project->description !!}</td>
                    </tr>
                    <tr>
                        <th>Project Client</th>
                        <td>{{ $project->client->first_name ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Start Date</th>
                        <td>{{ $project->start_date }}</td>
                    </tr>
                    <tr>
                        <th>Project Duration</th>
                        <td>{{ $project->project_duration }}</td>
                    </tr>
                    <tr>
                        <th>Freelancer Type</th>
                        <td>{{ $project->freelancer_type }}</td>
                    </tr>
                    <tr>
                        <th>Categories</th>
                        <td>
                            @foreach($project->categories as $vlu)
                            {{$vlu->name.','}}
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>Skills</th>
                        <td>@foreach($project->skills as $skill)
                            {{$skill->name.','}}
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>Listing Type</th>
                        <td>@foreach($project->listingtypes as $list)
                            {{$list->name.','}}
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>Payment Base</th>
                        <td>{{ $project->payment_base }}</td>
                    </tr>
                    <tr>
                        <th>Budget</th>
                        <td>{{ $project->budget }}</td>
                    </tr>
                    <tr>
                        <th>Level</th>
                        <td>{{ $project->level }}</td>
                    </tr>
                    <tr>
                        <th>English Level</th>
                        <td>{{ $project->english_level }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $project->status->name ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Project Images</th>
                        <td>@if(!empty($project->project_images))
                                @php
                                $value = json_decode($project->project_images);
                                @endphp
                                @if(!empty($value))
                                <div class="even" style="display: flex; flex-wrap: wrap; justify-content: flex-start;">
                                    @foreach($value as $multidata)
                                    <div class="parc">
                                        <span class="pip" data-title="{{$multidata}}">
                                            <img src="{{ url('/project-files').'/'.$multidata ?? "" }}" alt="" width="100" height="100">
                                            <!-- <a class="btn"><i class="fa fa-times remove" onclick="removeImage('{{$multidata}}')"></i></a> -->
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                                <input type="hidden" name="image1" id="gallery_img" value="{{$project->project_images}}">
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                Back to list
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