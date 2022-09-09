@extends('layouts.master') @section('content')
<style>
    .search-btn {
    border: 1px solid #d7cbcb;
    padding: 8px 10px 6px 11px;
    border-radius: 8px;
}
</style>
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">

          
            <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
              <div class="row">
                <div class="col-xl-6">
                   Notification List
            </div>
                <div class="col-xl-6">
                    <?php 
                    if(!empty($_GET['search'])){$search= $_GET['search'];}else{ $search='';}
                    ?>
                    
                    <div class="right-item" style="float:right;">
                        <div class="row">
                            <div class="col-xl-4">
                                </div>
                            <div class="col-xl-8">
                                <form action="" class="d-flex" method="get">
                                    <input type="text" name="search" class="form-control" value="{{$search}}">
                                  <button class="search-btn" type="submit"> <i class="fa fa-search pl-3" aria-hidden="true"></i> </button>
                                </form>
                            </div>
                        </div>
                    </div>
             </div>

                    <div class="card-body">
                    <div class="table-responsive">
                            <table class=" table table-bordered table-striped table-hover datatable datatable-Client">
                                <thead>
                                    <tr>
                                     <th>
                                        Name
                                        </th>
                                        <th>
                                        Email
                                        </th>
                                      
                                        <th>
                                        Description 
                                        </th>
                                        <!-- <th>
                                            
                                        </th> -->
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                @foreach($Notification as  $item)
                                    <tr>
                                       
                                        
                                        <td>
                                            {{ $item->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $item->email ?? '' }}
                                        </td>
                                       
                                        <td>
                                        {{ $item->description ?? '' }}
                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $Notification->links() !!}
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