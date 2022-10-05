@extends('layouts.master') @section('content')
<style>
</style>
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-header border-bottom">
                        Show Freelancer Details
                    </div>
                    <br>
                    <div class="card-body">
                        <div class="mb-2">
                            <div class="row">
                                <h5><strong>Basic Information</strong></h5>
                                <div class="col-md-6">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>First Name</strong></h6>
                                        <p class="mb-0">{{ $f_data->first_name ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Last Name</strong></h6>
                                        <p class="mb-0">{{ $f_data->last_name ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Email</strong></h6>
                                        <p class="mb-0">{{ $f_data->email ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">  
                                        <h6><strong>Status</strong></h6>
                                        <p class="mb-0">{{ $f_data->status ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Referal Code</strong></h6>
                                        <p class="mb-0">{{ $f_data->referal_code ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Profile Image</strong></h6>
                                        @if(!empty($f_data->profile_image))
                                            <div class="even mt-3">
                                                
                                                <div class="parc">
                                                    <span class="pip" data-title="{{$f_data->profile_image}}">
                                                        <img src="{{ url('/images/profile-image').'/'.$f_data->profile_image ?? "" }}" alt="" width="150" height="100">
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
                                <h5><strong>Other info</strong></h5>
                                <div class="col-md-3">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Total Earning</strong></h6>
                                        <p class="mb-0">{{ $f_data->freelancer->total_earning ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Total Jobs</strong></h6>
                                        <p class="mb-0">{{ $f_data->freelancer->total_jobs ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Total Hours</strong></h6>
                                        <p class="mb-0">{{ $f_data->freelancer->total_jobs ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Pending Projects</strong></h6>
                                        <p class="mb-0">{{ $f_data->freelancer->pending_project ?? '-'}}</p>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="row">
                                <h5><strong>Designation info</strong></h5>
                                <div class="col-md-12">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Title</strong></h6>
                                        <p class="mb-0">{{ $f_data->freelancer->occcuption ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Desciprion</strong></h6>
                                        <p class="mb-0">{{ $f_data->freelancer->description ?? '-'}}</p>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <h5><strong>Portfolio info</strong></h5>
                            @php
                            $i = 1;
                            @endphp
                            @if(count($f_data->freelancer->freelancer_portfolio) > 0)
                                @foreach($f_data->freelancer->freelancer_portfolio as $value)
                                <h6><strong>{{'Portfolio'.$i}}</strong></h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="p-3 listViewclr">
                                            <h6><strong>Title</strong></h6>
                                            <p class="mb-0">{{ $value->title ?? '-'}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 listViewclr">
                                            <h6><strong>Desciprion</strong></h6>
                                            <p class="mb-0">{{ $value->description ?? '-'}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 listViewclr">
                                            <h6><strong>Image</strong></h6>
                                            @if(!empty($value->image))
                                                <div class="even mt-3">
                                                    <div class="parc">
                                                        <span class="pip" data-title="{{$value->image}}">
                                                            <img src ="{{ url('/images/freelancer-portfolio/'.$value->image) ?? "" }}" alt="" width="150" height="100">
                                                        </span>
                                                    </div>
                                                </div>
                                            @else
                                            <p class="mb-0"> No Image Found </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @php
                                $i++;
                                @endphp
                                @endforeach
                            @else
                            <p class="mb-0">{{"No Data Found"}}</p>
                            @endif
                            <br>
                            <h5><strong>Testimonial info</strong></h5>
                            @php
                            $i = 1;
                            @endphp
                            @if(count($f_data->freelancer->freelancer_testimonial) > 0)
                                @foreach($f_data->freelancer->freelancer_testimonial as $value)
                                    <div class="row">
                                        <h6><strong>{{'Testimonial'.$i}}</strong></h6>
                                        <div class="col-md-6">
                                            <div class="p-3 listViewclr">
                                                <h6><strong>First Name</strong></h6>
                                                <p class="mb-0">{{ $value->first_name ?? '-'}}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-3 listViewclr">
                                                <h6><strong>Last Name</strong></h6>
                                                <p class="mb-0">{{ $value->last_name ?? '-'}}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-3 listViewclr">
                                                <h6><strong>Email</strong></h6>
                                                <p class="mb-0">{{ $value->email ?? '-'}}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-3 listViewclr">
                                                <h6><strong>Linkdin Profile URl</strong></h6>
                                                <p class="mb-0">{{ $value->linkdin_url ?? '-'}}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-3 listViewclr">
                                                <h6><strong>Client Title</strong></h6>
                                                <p class="mb-0">{{ $value->title ?? '-'}}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-3 listViewclr">
                                                <h6><strong>Client Title</strong></h6>
                                                <p class="mb-0">{{ $value->title ?? '-'}}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-3 listViewclr">
                                                <h6><strong>Project Type</strong></h6>
                                                <p class="mb-0">{{ $value->type ?? '-'}}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-3 listViewclr">
                                                <h6><strong>Message to Client</strong></h6>
                                                <p class="mb-0">{{ $value->description ?? '-'}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                    $i++;
                                    @endphp
                                @endforeach
                            @else
                                <p class="mb-0">No data Found</p>
                            @endif
                            <br>
                            <h5><strong>Certifications info</strong></h5>
                            @php
                            $i = 1;
                            @endphp
                            @if(count($f_data->freelancer->freelancer_certificates) > 0)
                                @foreach($f_data->freelancer->freelancer_certificates as $value)
                                <div class="row">
                                    <h6><strong>{{'Certificate'.$i}}</strong></h6>
                                    <div class="col-md-6">
                                        <div class="p-3 listViewclr">
                                            <h6><strong>Certificate Name</strong></h6>
                                            <p class="mb-0">{{ $value->name ?? '-'}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 listViewclr">
                                            <h6><strong>Certificate ID</strong></h6>
                                            <p class="mb-0">{{ $value->certificate_id ?? '-'}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 listViewclr">
                                            <h6><strong>Issues Date</strong></h6>
                                            <p class="mb-0">{{ $value->issue_date ?? '-'}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 listViewclr">
                                            <h6><strong>Expiration Date</strong></h6>
                                            <p class="mb-0">{{ $value->expiry_date ?? '-'}}</p>
                                        </div>
                                    </div>
                                </div>
                                @php
                                $i++;
                                @endphp
                                @endforeach
                                @else
                                <p class="mb-0">No data Found</p>
                            @endif
                            <br>
                            <h5><strong>Experience info</strong></h5>
                            @php
                            $i = 1;
                            @endphp
                            @if(count($f_data->freelancer->freelancer_experiences) > 0)
                                @foreach($f_data->freelancer->freelancer_experiences as $value)
                                <div class="row">
                                    <h6><strong>{{'Experience'.$i}}</strong></h6>
                                    <div class="col-md-12">
                                        <div class="p-3 listViewclr">
                                            <h6><strong>Subject</strong></h6>
                                            <p class="mb-0">{{ $value->subject ?? '-'}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="p-3 listViewclr">
                                            <h6><strong>Desciprion</strong></h6>
                                            <p class="mb-0">{{ $value->description ?? '-'}}</p>
                                        </div>
                                    </div>
                                </div>
                                @php
                                $i++;
                                @endphp
                                @endforeach
                            @else
                                <p class="mb-0">No data Found</p>
                            @endif
                            <br>
                            <h5><strong>Education info</strong></h5>
                            @php
                            $i = 1;
                            @endphp
                            @if(count($f_data->freelancer->freelancer_education) > 0)
                                @foreach($f_data->freelancer->freelancer_education as $value)
                                <div class="row">
                                    <h6><strong>{{'Education'.$i}}</strong></h6>
                                    <div class="col-md-6">
                                        <div class="p-3 listViewclr">
                                            <h6><strong>School</strong></h6>
                                            <p class="mb-0">{{ $value->school ?? '-'}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 listViewclr">
                                            <h6><strong>Date</strong></h6>
                                            <p class="mb-0">{{ $value->date ?? '-'}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 listViewclr">
                                            <h6><strong>Proficiency Level</strong></h6>
                                            <p class="mb-0">{{ $value->level ?? '-'}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 listViewclr">
                                            <h6><strong>Degree</strong></h6>
                                            <p class="mb-0">{{ $value->degree ?? '-'}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 listViewclr">
                                            <h6><strong>Area of Study</strong></h6>
                                            <p class="mb-0">{{ $value->area_study ?? '-'}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 listViewclr">
                                            <h6><strong>Desciprion</strong></h6>
                                            <p class="mb-0">{{ $value->description ?? '-'}}</p>
                                        </div>
                                    </div>
                                </div>
                                @php
                                $i++;
                                @endphp
                                @endforeach
                            @else
                                <p class="mb-0">No data Found</p>
                            @endif
                            <a class="btn btn-success btn_back" href="{{ url()->previous() }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                
                
                    </div>
                </div>

@endsection
