@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-bottom">
                       <h5> Show Freelancer Details</h5>
                    </div>
                    <br>
                    <div class="card-body">
                        <div class="mb-2">
                            <div class="row">
                                <h5 class="ps-4"><strong>Basic Information</strong></h5>

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

                                 <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Experience Level</strong></h6>

                                            <div class="even mt-3">
                                                <div class="parc">
                                                    @if($f_data->freelancer->experience_level !== null)
                                                    <span class="pip">
                                                        {{$f_data->freelancer->experience_level}}
                                                    </span>
                                                    @else
                                                       {{'Not found'}}
                                                    @endif
                                                </div>
                                            </div>
                                    
                                    </div>
                                </div>

                            </div> 
                            <br>
                            <div class="row">
                                <h5 class="ps-4"><strong>Other info</strong></h5>

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
                            <div class="row">
                                <h5 class="ps-4"><strong>Designation info</strong></h5>

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
                            <div class="row">
                                <h5 class="ps-4"><strong>Portfolio info</strong></h5>
                                @php
                                    $i = 1;
                                @endphp
                                @if(count($f_data->freelancer->freelancer_portfolio) > 0)
                                    @foreach($f_data->freelancer->freelancer_portfolio as $value)

                                        <h6 class="ps-4"><strong>{{'Portfolio'.$i}}</strong></h6>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="p-3 listViewclr">
                                                    <h6><strong>Title</strong></h6>
                                                    <p class="mb-0">{{ $value->title ?? '-'}}</p>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="p-3 listViewclr">
                                                    <h6><strong>Description</strong></h6>
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
                                    <p class="mb-0 ps-4">{{"No Data Found"}}</p>
                                @endif
                            </div>
                            <br>
                            <div class="row">
                                <h5 class="ps-4"><strong>Testimonial info</strong></h5>
                                @php
                                    $i = 1;
                                @endphp
                                @if(count($f_data->freelancer->freelancer_testimonial) > 0)
                                    @foreach($f_data->freelancer->freelancer_testimonial as $value)
                                        <div class="row">
                                            <h6 class="ps-4"><strong>{{'Testimonial'.$i}}</strong></h6>

                                            @if($value->status == 1)
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
                                                        <h6><strong>Project Type</strong></h6>
                                                        <p class="mb-0">{{ $value->type ?? '-'}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="p-3 listViewclr">
                                                        <h6><strong>Client Name</strong></h6>
                                                        <p class="mb-0">{{ $value->title ?? '-'}}</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="p-3 listViewclr">
                                                        <h6><strong>Description</strong></h6>
                                                        <p class="mb-0">{{ $value->description_client ?? '-'}}</p>
                                                    </div>
                                                </div>
                                            @else
                                                <p class="mb-0">Your testimonial request is awaiting {{ $value->first_name }} response<span class="testimonial_date">{{ '- '.date_format($value->created_at, 'M d Y') }}</span></p>
                                                <p>{{ ($value->status == 0) ? 'Pending' : 'Rejected' }}</p>
                                            @endif
                                        </div>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                @else
                                    <p class="mb-0 ps-4">No data Found</p>
                                @endif
                            </div>
                            <br>
                            <div class="row">
                                <h5 class="ps-4"><strong>Certifications info</strong></h5>
                                @php
                                    $i = 1;
                                @endphp
                                @if(count($f_data->freelancer->freelancer_certificates) > 0)
                                    @foreach($f_data->freelancer->freelancer_certificates as $value)
                                        <div class="row">
                                            <h6 class="ps-4"><strong>{{'Certificate'.$i}}</strong></h6>

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
                                                    <h6><strong>Issue Date</strong></h6>
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
                                    <p class="mb-0 ps-4">No data Found</p>
                                @endif
                            </div>
                            <br>
                            <div class="row">
                                <h5 class="ps-4"><strong>Experience info</strong></h5>
                                @php
                                    $i = 1;
                                @endphp
                                @if(count($f_data->freelancer->freelancer_experiences) > 0)
                                    @foreach($f_data->freelancer->freelancer_experiences as $value)
                                        <div class="row">
                                            <h6 class="ps-4"><strong>{{'Experience'.$i}}</strong></h6>

                                            <div class="col-md-6">
                                                <div class="p-3 listViewclr">
                                                    <h6><strong>Subject</strong></h6>
                                                    <p class="mb-0">{{ $value->subject ?? '-'}}</p>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="p-3 listViewclr">
                                                    <h6><strong>Description</strong></h6>
                                                    <p class="mb-0">{{ $value->description ?? '-'}}</p>
                                                </div>
                                            </div>
                                        </div>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                @else
                                    <p class="mb-0 ps-4">No data Found</p>
                                @endif
                            </div>
                            <br>
                            <div class="row">
                                <h5 class="ps-4"><strong>Education info</strong></h5>
                                @php
                                    $i = 1;
                                @endphp
                                @if(count($f_data->freelancer->freelancer_education) > 0)
                                    @foreach($f_data->freelancer->freelancer_education as $value)
                                        <div class="row">
                                            <h6 class="ps-4"><strong>{{'Education'.$i}}</strong></h6>

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
                                                    <h6><strong>Description</strong></h6>
                                                    <p class="mb-0">{{ $value->description ?? '-'}}</p>
                                                </div>
                                            </div>
                                        </div>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                @else
                                    <p class="mb-0 ps-4">No data Found</p>
                                @endif
                            </div>
                            <br>
                            <!-- skills level -->
                                <div class="row">
                                <h5 class="ps-4"><strong>Skills info</strong></h5>
                                    <div class="col-md-12">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Skills</strong></h6>
                                    @foreach($f_data->freelancer->freelancer_skills as $value)
                                            <span class="btn-xs text-capitalize btn-dark">{{ $value->skill_name ?? ''}}</span>
                                        

                                    @endforeach

                                    </div>
                                </div>
                               
                            </div>

                            <br>
                            <div class="row">
                                <h5 class="ps-4"><strong>Languages info</strong></h5>
                                @if(isset($languages))
                                    @foreach($languages as $key => $value)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="p-3 listViewclr">
                                                    <h6><strong>Language</strong></h6>
                                                    <p class="mb-0">{{ $key ?? '-'}}</p>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="p-3 listViewclr">
                                                    <h6><strong>Proficiency level</strong></h6>
                                                    <p class="mb-0">{{ $value ?? '-'}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="mb-0 ps-4">No data Found</p>
                                @endif
                            </div>
                            <br>
                            <div class="row">
                                <h5 class="ps-4"><strong>Working hour info</strong></h5>
                                @if(isset($work_time))
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="p-3 listViewclr">
                                                <h6><strong>Hours Per Week</strong></h6>
                                                <p class="mb-0">{{ $work_time ?? '-'}}</p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <p class="mb-0 ps-4">No data Found</p>
                                @endif
                            </div>
                            <br>
                            <div class="row">
                                <h5 class="ps-4"><strong>Video info</strong></h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="p-3 listViewclr">
                                            <h6><strong>Video Type</strong></h6>
                                            @if(isset($video_type))
                                                <p class="mb-0">{{ $video_type ?? '-'}}</p>
                                            @else
                                                <p class="mb-0">No data Found</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="p-3 listViewclr">
                                            <h6><strong>Video Link</strong></h6>
                                            @if(isset($video_link))
                                            <p class="mb-0">
                                                {{ $video_link ?? '-' }}
                                                <!-- <iframe width="420" height="345" src="https://www.youtube.com/embed/5Peo-ivmupE" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
                                            </p>
                                            @else
                                                <p class="mb-0">No data Found</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a class="btn btn-warning btn_back" href="{{ url()->previous() }}">
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
