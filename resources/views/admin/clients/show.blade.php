@extends('layouts.master') @section('content')
<style>
    h5 strong{
        padding-left: 10px;
    }
</style>
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-header border-bottom">
                        Show Details
                    </div>
                    <br>
                    <div class="card-body">
                        <div class="mb-2">
                            <div class="row">
                                <h5><strong>Basic Information</strong></h5>
                                <div class="col-md-4 ">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>First Name</strong></h6>
                                        <p class="mb-0">{{ $clients->first_name ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Last Name</strong></h6>
                                        <p class="mb-0">{{ $clients->last_name ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Email</strong></h6>
                                        <p class="mb-0">{{ $clients->email ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Email</strong></h6>
                                        <p class="mb-0">{{ $clients->phone ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">  
                                        <h6><strong>Status</strong></h6>
                                        <p class="mb-0">{{ $clients->status ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Referal Code</strong></h6>
                                        <p class="mb-0">{{ $clients->referal_code ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Profile Image</strong></h6>
                                        @if(!empty($clients->profile_image))
                                            <div class="even mt-3">
                                                
                                                <div class="parc">
                                                    <span class="pip" data-title="{{$clients->profile_image}}">
                                                        <img src="{{ url('/images/profile-image').'/'.$clients->profile_image ?? "" }}" alt="" width="100" height="100">
                                                    </span>
                                                </div>
                                            </div>
                                        @else
                                        <p class="mb-0"> No Image Found </p>
                                        @endif
                                    </div>
                                </div>

                            </div> 
                            <br>
                            <div class="row">
                                <h5><strong>Other Information</strong></h5>
                                <div class="col-md-4 ">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Address</strong></h6>
                                        <p class="mb-0">{{ $clients->address ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Country</strong></h6>
                                        <p class="mb-0">{{ $clients->country ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>State</strong></h6>
                                        <p class="mb-0">{{ $clients->state ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>City</strong></h6>
                                        <p class="mb-0">{{ $clients->city ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Zip Code</strong></h6>
                                        <p class="mb-0">{{ $clients->zip_code ?? '-'}}</p>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <h5><strong>Company Information</strong></h5>
                                <div class="col-md-4 ">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Company Name</strong></h6>
                                        <p class="mb-0">{{ $clients->client->company_name ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Company Email</strong></h6>
                                        <p class="mb-0">{{ $clients->client->company_email ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Company Phone</strong></h6>
                                        <p class="mb-0">{{ $clients->client->company_phone ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Company Address</strong></h6>
                                        <p class="mb-0">{{ $clients->client->company_address ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Website</strong></h6>
                                        <p class="mb-0">{{ $clients->client->website ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Industry</strong></h6>
                                        <p class="mb-0">{{ $clients->client->industry ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Tagline</strong></h6>
                                        <p class="mb-0">{{ $clients->client->tagline ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Employee No</strong></h6>
                                        <p class="mb-0">{{ $clients->client->employee_no ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Vat ID</strong></h6>
                                        <p class="mb-0">{{ $clients->client->vat_id ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Timezone</strong></h6>
                                        <p class="mb-0">{{ $clients->client->timezone ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Description</strong></h6>
                                        <p class="mb-0">{{ $clients->client->description ?? '-'}}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <a class="btn btn-success btn_back" href="{{ url()->previous() }}">
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
