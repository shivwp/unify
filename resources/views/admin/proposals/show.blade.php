@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
            
<div class="card">
    <div class="card-header">
        Show Proposal List
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            Id
                        </th>
                        <td>
                            {{ $Proposal->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                        Project Name
                        </th>
                        <td>
                        {{ $Proposal->project->name ?? '' }}
                        </td>
                    </tr>
                    
                    <tr>
                        <th>
                        Freelancer
                        </th>
                        <td>
                        {{ $Proposal->freelancer->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                        Status
                        </th>
                        <td>
                        {{ $Proposal->status ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                        Bid-Amount
                        </th>
                        <td>
                        ${{ $Proposal->amount ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table> 
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
@endsection
