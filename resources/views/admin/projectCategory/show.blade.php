@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
            
<div class="card">
    <div class="card-header border-bottom">
        Show Category List
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
                            {{ $projectCategory->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Category Name
                        </th>
                        <td>
                            {{ $projectCategory->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Parent Category
                        </th>
                        <td>
                           @if(!empty($projectCategory->parentcategory))
                           {{ $projectCategory->parentcategory->name }}
                           @else
                           No Parent @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <a style="margin-top:20px;" class="btn btn-success" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>

    </div>
</div> 
</div>
</div>
</div>
</div>
@endsection
