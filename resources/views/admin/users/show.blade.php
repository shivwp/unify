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
                                        <p class="mb-0">{{ $user->first_name ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Last Name</strong></h6>
                                        <p class="mb-0">{{ $user->last_name ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>User Role</strong></h6>
                                        @foreach($user->roles as $id => $roles)
                                        <p class="mb-0">{{ $roles->title}}</p>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Email</strong></h6>
                                        <p class="mb-0">{{ $user->email ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">  
                                        <h6><strong>Status</strong></h6>
                                        <p class="mb-0">{{ $user->status ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Referal Code</strong></h6>
                                        <p class="mb-0">{{ $user->referal_code ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Profile Image</strong></h6>
                                        @if(!empty($user->profile_image))
                                            <div class="even mt-3">
                                                
                                                <div class="parc">
                                                    <span class="pip" data-title="{{$user->profile_image}}">
                                                        <img src="{{ url('/images/profile-image').'/'.$user->profile_image ?? "" }}" alt="" width="150" height="100">
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
                                <h5><strong>Documents</strong></h5>
                                <div class="col-md-4 ">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Document Type</strong></h6>
                                        <p class="mb-0">{{ $document->type ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <h6><strong>Front Side</strong></h6>
                                            </div>
                                            @if(!empty($document->document_front))
                                                <div class="col-md-2">
                                                    <a href="{{ url('/images/user-document').'/'.$document->document_front }}" download><i class="fa fa-download"></i></a>
                                                </div>
                                            @endif
                                        </div>
                                        @if(!empty($document->document_front))
                                            <div class="even mt-3">
                                                
                                                <div class="parc">
                                                    <span class="pip" data-title="{{$document->document_front}}">
                                                        <img src="{{ url('/images/user-document').'/'.$document->document_front ?? "" }}" alt="" width="260" height="150">
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
                                        <div class="row">
                                            <div class="col-md-10">
                                                <h6><strong>Back Side</strong></h6>
                                            </div>
                                            @if(!empty($document->document_back))
                                                <div class="col-md-2">
                                                    <a href="{{ url('/images/user-document').'/'.$document->document_back }}" download><i class="fa fa-download"></i></a>
                                                </div>
                                            @endif
                                        </div>
                                        @if(!empty($document->document_back))
                                            <div class="even mt-3">
                                                
                                                <div class="parc">
                                                    <span class="pip" data-title="{{$document->document_back}}">
                                                        <img src="{{ url('/images/user-document').'/'.$document->document_back ?? "" }}" alt="" width="260" height="150">
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
                            {{--<div class="row">
                                <h5><strong>Other Information</strong></h5>
                                <div class="col-md-4 ">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>First Name</strong></h6>
                                        <p class="mb-0">{{ $user->first_name ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Last Name</strong></h6>
                                        <p class="mb-0">{{ $user->last_name ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Phone</strong></h6>
                                        <p class="mb-0">{{ $user->phone ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Email</strong></h6>
                                        <p class="mb-0">{{ $user->email ?? '-'}}</p>
                                    </div>
                                </div>
                                
                            </div>--}}
                            <a class="btn btn-success btn_back" href="{{ url()->previous() }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                
                
                    </div>
                </div>

@endsection
