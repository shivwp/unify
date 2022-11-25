@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
            

                <div class="card">
                    <div class="card-header border-bottom">
                        Show Skill List
                    </div>

                    <div class="card-body mt-4">
                        <div class="mb-2">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>
                                            Id
                                        </th>
                                        <td>
                                            {{ $projectSkill->id }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Name
                                        </th>
                                        <td>
                                            {{ $projectSkill->name }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <a style="margin-top:20px;" class="btn btn-success" href="{{ url()->previous() }}">
                                Back to list
                            </a>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>
@endsection
