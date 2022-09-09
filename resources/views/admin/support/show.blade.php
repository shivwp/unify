@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
            
<div class="card">
    <div class="card-header">
        Ticket
    </div>

    <div class="card-body ">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>ID</th>
                        <td>{{ $support->id }}</td>
                    </tr>
                    <tr>
                        <th>Project Name</th>
                        <td>{{ $support->project->name }}</td>
                    </tr>
                    <tr>
                        <th>Ticket #ID</th>
                        <td>{!! $support->ticket !!}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{!! $support->description !!}</td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td>{{ $support->user->name ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td> {{ date('j \\ F Y', strtotime($support->created_at)) }}</td>
                    </tr>
                   
                    <tr>
                        <th>Ticket Status</th>
                        <td> <span class="badge badge-info">{{ $support->status }}</span></td>
                    </tr>
                    
                    <tr>
                        <th>Ticket Images</th>
                        <td>@if(!empty($support->image))
                                @php
                                $value = json_decode($support->image);
                                @endphp
                                @if(!empty($value))
                                <div class="even" style="display: flex; flex-wrap: wrap; justify-content: flex-start;">
                                    @foreach($value as $multidata)
                                    <div class="parc">
                                        <span class="pip" data-title="{{$multidata}}">
                                            <img src="{{ url('/support-image').'/'.$multidata ?? "" }}" alt="" width="100" height="100">
                                            <!-- <a class="btn"><i class="fa fa-times remove" onclick="removeImage('{{$multidata}}')"></i></a> -->
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                               
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            
    </div>
        @if(!empty($support->solution))
        <div class="card">
<div class="card-header p-0 mt-2 mb-2">
      Solution
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered table-striped">
            <tbody>
                
                <tr>
               
                    <th>
                     {!! $support->solution !!}
                    </th>
                </tr>
            </tbody>
        </table>
    </div>
</div>

        @endif
            <a style="margin-top:20px;" class="btn btn-success" href="{{ url()->previous() }}">
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
