@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row">
            <div class="col-lg-12">
                <div class="row tabelhed d-flex justify-content-end">                   
                    <div class="col-lg-10 col-md-10"> 
                        <div class="right-item d-flex justify-content-end" >
                            <div class="d-flex">
                                <input type="text" name="search" id="search_field" class="form-control" value="{{ isset($search) ? $search : '' }}" placeholder="Search User" required>

                                <button class="btn-sm search-btn" type="submit"> 
                                    <i class="fa fa-search pl-3" aria-hidden="true"></i> 
                                </button>

                                <a href="{{url('admin/indexrefrals')}}">
                                    <i class="fa fa-refresh pl-3 redirect-icon" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
             
                <div class="card">
                    <div class="card-header ">
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <h5>Referal Users</h5>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="row float-end">
                                    <div class="col-xl-12 d-flex float-end">
                                        <div class="items paginatee">
                                            <select class="form-select m-0 items" name="pagination" id="pagination" aria-label="Default select example">
                                                <option value='10' {{ isset($pagination) ? ($pagination == '10' ? 'selected' : '' ) : '' }}>10</option>
                                                <option value='20' {{ isset($pagination) ? ($pagination == '20' ? 'selected' : '' ) : '' }}>20</option>
                                                <option value='30' {{ isset($pagination) ? ($pagination == '30' ? 'selected' : '' ) : '' }}>30</option>
                                                <option value='40' {{ isset($pagination) ? ($pagination == '40' ? 'selected' : '' ) : '' }}>40</option>
                                                <option value='50' {{ isset($pagination) ? ($pagination == '50' ? 'selected' : '' ) : '' }}>50</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table">
                            <table id="example" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S No.</th>
                                        <th>User</th>
                                        <th>Refered By</th>
                                        <th>Referal Code</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @if(count($data)>0)
                                        @foreach($data as $ref)
                                            @php
                                                $database_data = strtotime($ref->created_at);
                                                $only_data = date("d-m-Y",$database_data);

                                            @endphp

                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $ref->refer_to_name }}</td>
                                                <td>{{ $ref->refer_name }}</td>
                                                <td>{{ $ref->referal_code }}</td>
                                                <td>{{ $only_data }}</td>

                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <th colspan="5">No Data Found</th>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            @if ((request()->get('keyword')) || (request()->get('items')))
                                {{ $data->appends(['keyword' => request()->get('keyword'),'items' => request()->get('items')])->links() }}
                            @else
                                {!! $data->links() !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

