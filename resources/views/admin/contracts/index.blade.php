@extends('layouts.master') @section('content')
<style type="text/css">
    button.btn.filter_btn {
        background-color: #696cff;
        color: white;
    }
   
    .search-btn {
    border: 1px solid #d7cbcb;
    padding: 2px 9px 2px 11px;
    border-radius: 8px;
}

</style>
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
                <div class="row tabelhed">
                    <div class="col-lg-2 col-md-2 col-sm-12 d-flex">
                        <!-- <a class="btn-sm ad-btn text-center pt-2" href="{{ route("admin.contracts.create") }}">Add</a> -->
                        <button id="btnExport" onClick="fnExcelReport()" class="btn-sm ad-btn clearfix">Excel</button> 

                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-12 "> <?php 
                //  if(!empty($_GET['search'])){$search= $_GET['search'];}else{ $search='';}
                //  ?>
                
                            {{-- <div class="right-item" >
                            
                              <form action="" class="d-flex" method="get">
                                  <input type="text" name="search" class="form-control" value="{{$search}}" style="height: 39px;" placeholder="Enter project/job name">
                                <button class="btn-sm search-btn" type="submit"> <i class="fa fa-search pl-3" aria-hidden="true"></i> </button>
                              </form>
                            </div>--}}
                            </div> 
                        <div class="col-lg-5 col-md-5 col-sm-12 pl-2">

                        {{-- <form action="" method="GET" class="d-flex">
                            <div class="mb-3">
                                <div class="input-group input-daterange" class="daterange">
                                  <input type="text" name="start_date"  id="Startdate" onfocus="(this.type='date')" value="{{Request::get('start_date') ?? ''}}" placeholder ="Start Date" class="form-control" />
                                  <span class="input-group-text">To</span>
                                  <input type="text" name="end_date" id="Enddate" value="{{Request::get('end_date') ?? ''}}" onfocus="(this.type='date')" placeholder ="End Date" class="form-control" />
                                </div>
                            </div>
                            <div class="d-flex" style="margin-left: 8px;">
                            <button class="btn-sm search-btn" type="submit" >  <i class="fa fa-search pl-3" aria-hidden="true"></i> </button>
                            <a href="{{url('admin/contracts')}}"><i class="fa fa-refresh pl-3 redirect-icon"  aria-hidden="true"></i></a>
                            </div>
                        </form> --}}
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-6">
                                <h5 class="m-0 mb-1">Contracts</h5>
                            </div>
                            <div class="col-xl-6 ">
                                <div class="row" style="float:right">
                                    <div class="col-xl-12 d-flex" style="float: right">
                                        <div class="items" style="margin-left: 1px; width: 75px !important;">
                                            <form action="" id="pagination" method="get">
                                                <select class="form-select m-0" name="pagination" style="width: 94%; font-size: 11px;  height: 32px;" id="pagination" aria-label="Default select example">
                                                    <option value="10" @if($pagination=='10') selected @endif>10</option>
                                                    <option  value='20' @if($pagination=='20') selected @endif>20</option>
                                                    <option value='30' @if($pagination=='30') selected @endif>30</option>
                                                    <option value='40' @if($pagination=='40') selected @endif>40</option>
                                                    <option value='50' @if($pagination=='50') selected @endif>50</option>
                                                </select>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive card-datatable">
                            <table id="exportToTable" class=" datatables-basic table border-top table table-bordered table-striped table-hover datatable datatable-Project">
                                <thead>
                                    <tr>
                                        <th>S No.</th>
                                        <th>Project Title</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                
                                <tbody id="">

                                    @if(count($contracts)>0)
                                        @php $i=1 @endphp
                                        @foreach($contracts as $contract)
                                        
                                        <tr data-entry-id="{{ $contract->id }}">
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $contract->project_name ?? '' }}</td>
                                            <td>{{ number_format((float)$contract->amount, 2, '.', '') }}$</td>
                                            <td>{{ $contract->status ?? '' }}</td>
                                            <td>
                                                <a  href="{{ route('admin.contracts.show', $contract->id) }}">
                                                <i class="bx bx-show mx-1"  data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>View</span>"></i>
                                                </a>
                                                <!-- <a  href="{{ route('admin.contracts.edit', $contract->id) }}">
                                                    <i class="bx bx-edit"  data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Edit</span>"></i>
                                                </a> -->
                                                <a href="{{ route('admin.contracts.destroy', $contract->id) }}">
                                                    <i class="bx bx-trash"data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>Delete</span>" onclick="return confirm('{{ trans('global.areYouSure') }}');"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach 
                                    @else

                                        <td>
                                    No Record Found
                                    </td>
                                    @endif
                                </tbody>
                            </table>
                        
                            {!! $contracts->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
