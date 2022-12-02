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
                        <h5>Show Skill Detail</h5>
                    </div>
                    <br>
                    <div class="card-body">
                        <div class="mb-2">
                            <div class="row">
                                <!-- <h5><strong>Basic Information</strong></h5> -->
                                <div class="col-md-6 ">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Category Name</strong></h6>
                                        <p class="mb-0">{{ isset($category->name) ? $category->name : '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 listViewclr">  
                                        <h6><strong>Skill Name</strong></h6>
                                        <p class="mb-0">{{ isset($projectSkill->name) ? $projectSkill->name : '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Skill Image</strong></h6>
                                        @if(!empty($projectSkill->image))
                                            <div class="even mt-3">
                                                
                                                <div class="parc">
                                                    <span class="pip" data-title="{{$projectSkill->image}}">
                                                        <img src="{{ url('/images/skills').'/'.$projectSkill->image ?? "" }}" alt="" width="150" height="100">
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
                                <div class="d-flex">
                                    <h5><strong>Banner Content </strong></h5> <span class="px-2"><i>(These details for showing content on category details sections)</i></span>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Banner Title</strong></h6>
                                        <p class="mb-0">{{ $projectSkill->short_description ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 listViewclr">  
                                        <h6><strong>Banner Description</strong></h6>
                                        <p class="mb-0">{{ $projectSkill->long_description ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 listViewclr">
                                        <h6><strong>Banner Image</strong></h6>
                                        @if(!empty($projectSkill->banner_image))
                                            <div class="even mt-3">
                                                
                                                <div class="parc">
                                                    <span class="pip" data-title="{{$projectSkill->banner_image}}">
                                                        <img src="{{ url('/images/skills').'/'.$projectSkill->banner_image ?? "" }}" alt="" width="150" height="100">
                                                    </span>
                                                </div>
                                            </div>
                                        @else
                                        <p class="mb-0"> No Image Found </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <a class="btn btn-warning btn_back" href="{{ url()->previous() }}">
                                {{ 'Back' }}
                            </a>
                        </div>
                
                
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
