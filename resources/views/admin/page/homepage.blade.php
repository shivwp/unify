@extends('layouts.master') @section('content')
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        @if(Session::has('error'))
            <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error') }}</p>
        @endif
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-header border-bottom">
                        Edit
                    </div>
                    <div class="card-body">
                        @php
                            $content = json_decode($page->content,true);
                        @endphp
                        <form action="{{ route("admin.page.store") }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row mt-3">
                                <h6><strong>Hero Section</strong></h6>
                                <input type="hidden" name="id" value="{{ isset($page) ? $page->id : '' }}">
                                <input type="hidden" name="slug" value="{{ isset($page) ? $page->slug : '' }}">
                                <div class="form-group">
                                    <label for="name" class=""> Title *</label>
                                    <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ empty(old('title')) ? ((isset($page) && !empty($content['hero'])) ? $content['hero']['title'] : '') : old('title') }}" required>
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mt-2">
                                    <label for="description">Description </label>
                                    <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" required>{{ empty(old('description')) ? (!empty($content['hero']['description']) ? $content['hero']['description'] : '') : old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                <div class="row">
                                    <div class="form-group col-md-6 mt-2">
                                        <label for="name" class=""> Button Text *</label>
                                        <input type="text" id="button_text" name="button_text" class="form-control @error('button_text') is-invalid @enderror" value="{{ empty(old('button_text')) ? ((isset($page) && !empty($content['hero'])) ? $content['hero']['button_text'] : '') : old('button_text') }}" required>
                                        @error('button_text')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6 mt-2">
                                        <label for="name" class=""> Button Link *</label>
                                        <input type="url" id="button_link" name="button_link" class="form-control @error('button_link') is-invalid @enderror" value="{{ empty(old('button_link')) ? ((isset($page) && !empty($content['hero'])) ? $content['hero']['button_link'] : '') : old('button_link') }}" required>
                                        @error('button_link')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                </div>
                                <div class="form-group mt-2">
                                    @if(!empty($content['hero']['image']))
                                        <div class="parc">
                                            <img src="{{ url('/images/home').'/'.$content['hero']['image'] ?? "" }}" alt="" width="100" height="100">
                                        </div>
                                    @endif
                                    <label class="mt-2">Add Images </label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" value="">
                                    <input type="hidden" name="image_old" value="{{isset($page) && !empty($content['hero']['image']) ? $content['hero']['image'] : ''}}">
                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="row used_by_section">
                                    <div class="col-md-11">
                                        <h6><strong>Used By Section</strong></h6>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="add_image" id="add_image">
                                            <!-- <a class="btn btn-primary mt-3 add_image_btn" id="add_image_btn">Add Image</a> -->
                                            <a class="btn btn-primary add_image_btn" id="add_image_btn">
                                                <i class="bx bx-plus mx-1" data-bs-html="true" title="Add"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                @if(isset($page) && !empty($content['used_by']))
                                @foreach($content['used_by']['used_by_section_image'] as $images)
                                <input type="hidden" name="used_by_image_count" id="used_by_image_count" value="{{ count($content['used_by']['used_by_section_image']) }}">
                                <div class="form-group mt-2 used_by_image" id="used_by_image">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="">Add Images </label>
                                            <input type="file" class="form-control @error('used_by_section_image') is-invalid @enderror" name="used_by_section_image[]" value="">
                                            <input type="hidden" name="used_by_section_image_old[]" value="{{ $images ?? ''}}">
                                            @error('used_by_section_image')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <!-- @if(!empty($content['used_by']['used_by_section_image'])) -->
                                                        <div class="parc">
                                                            <img src="{{ url('/images/home').'/'.$images ?? "" }}" alt="" width="100" height="100">
                                                        </div>
                                                    <!-- @endif -->
                                                </div>
                                                <div class="col-md-2">
                                                    <a class="mt-3 remove_image_btn" id="remove_image_btn">
                                                        <i class="bx bx-trash mx-1" data-bs-html="true" title="Remove"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                                <div class="form-group mt-2 new_used_by_image" id="new_used_by_image"></div>
                                <!-- <div class="add_image mt-2" id="add_image">
                                    <a class="btn btn-primary mt-3 add_image_btn" id="add_image_btn">Add Image</a>
                                    <a class="mt-3 add_image_btn" id="add_image_btn">
                                        <i class="bx bx-plus mx-1" data-bs-html="true" title="Add"></i>
                                    </a>
                                </div> -->
                            </div>
                            <hr>
                            <div class="row">
                                <div class="row trusted_brands_section">
                                    <div class="col-md-11">
                                        <h6><strong>Trusted Brand Section</strong></h6>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="add_trusted_brands" id="add_trusted_brands">
                                            <!-- <a class="btn btn-primary mt-3 add_image_btn" id="add_image_btn">Add Image</a> -->
                                            <a class=" btn btn-primary add_trusted_brands_btn" id="add_trusted_brands_btn">
                                                <i class="bx bx-plus mx-1" data-bs-html="true" title="Add"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-group mt-2">
                                        <label for="name" class="">Trusted Brand Title *</label>
                                        <input type="text" id="trusted_brand_title" name="trusted_brand_title" class="form-control @error('trusted_brand_title') is-invalid @enderror" value="{{ empty(old('trusted_brand_title')) ? (!empty($content['trusted_brand_title']) ? $content['trusted_brand_title'] : '') : old('trusted_brand_title') }}" required>
                                        @error('trusted_brand_title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="trusted_brand_description">Trusted Brand Description *</label>
                                        <textarea id="trusted_brand_description" name="trusted_brand_description" class="form-control @error('trusted_brand_description') is-invalid @enderror" required>{{ empty(old('trusted_brand_description')) ? (!empty($content['trusted_brand_description']) ? $content['trusted_brand_description'] : '') : old('trusted_brand_description') }}</textarea>
                                        @error('trusted_brand_description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                @if(isset($page) && !empty($content['trusted_brands']))
                                @php $count=0; @endphp
                                    @foreach($content['trusted_brands'] as $brands)
                                        {{-- $brands['logo'] --}}
                                        
                                <input type="hidden" name="brand_count" id="brand_count" value="{{ count($content['trusted_brands']) }}">

                                <div class="form-group mt-5 trusted_brands" id="trusted_brands">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="">Logo </label>
                                            <input type="file" class="form-control @error('trusted_brands[{{$count}}][logo]') is-invalid @enderror" name="trusted_brands[{{$count}}][logo]" value="">
                                            <input type="hidden" name="trusted_brands[{{$count}}][logo_old]" value="{{ !empty($brands['logo']) ? $brands['logo'] : ''}}">
                                            @error('trusted_brands[{{$count}}][logo]')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    @if(!empty($brands['logo']))
                                                        <div class="parc">
                                                            {{--@foreach($content['trusted_brands']['logo'] as $logo)--}}
                                                            <img src="{{ url('/images/home').'/'.$brands['logo'] ?? "" }}" alt="" width="100" height="100">
                                                            {{--@endforeach--}}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-2">
                                                    <a class="mt-3 remove_trusted_brands_btn" id="remove_trusted_brands_btn">
                                                        <i class="bx bx-trash mx-1" data-bs-html="true" title="Remove"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <!-- {{ old('brand_description', $brands['brand_description']) }} -->
                                    <div class="form-group mt-2">
                                        <label for="brand_description">Description </label>
                                        <textarea id="brand_description" name="trusted_brands[{{$count}}][brand_description]" class="form-control @error('trusted_brands['.$count.'][brand_description]') is-invalid @enderror" required>{{ empty(old('trusted_brands[$count][brand_description]')) ? (!empty($brands['brand_description']) ? $brands['brand_description'] : '') : old('trusted_brands[$count][brand_description]') }}</textarea>
                                        @error('trusted_brands['.$count.'][brand_description]')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mt-2">
                                                    <label for="name" class="">Name *</label>
                                                    <input type="text" id="brand_name" name="trusted_brands[{{$count}}][brand_name]" class="form-control @error('brand_name') is-invalid @enderror" value="{{ empty(old('brand_name')) ? (!empty($brands['brand_name']) ? $brands['brand_name'] : '') : old('brand_name') }}" required>
                                                    @error('brand_name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mt-2">
                                                    <label for="designation" class="">Designation *</label>
                                                    <input type="text" id="designation" name="trusted_brands[{{$count}}][designation]" class="form-control @error('designation') is-invalid @enderror" value="{{ empty(old('designation')) ? (!empty($brands['designation']) ? $brands['designation'] : '') : old('designation') }}" required>
                                                    @error('designation')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mt-2">
                                                    <label for="name" class="">Total Projects *</label>
                                                    <input type="number" id="total_projects" name="trusted_brands[{{$count}}][total_projects]" class="form-control @error('total_projects') is-invalid @enderror" value="{{ empty(old('total_projects')) ? (!empty($brands['total_projects']) ? $brands['total_projects'] : '') : old('total_projects') }}" required>
                                                    @error('total_projects')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mt-2">
                                                    <label for="name" class="">Launch Projects *</label>
                                                    <input type="text" id="launch_projects" name="trusted_brands[{{$count}}][launch_projects]" class="form-control @error('launch_projects') is-invalid @enderror" value="{{ empty(old('launch_projects')) ? (!empty($brands['launch_projects']) ? $brands['launch_projects'] : '') : old('launch_projects') }}" required>
                                                    @error('launch_projects')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php $count++; @endphp
                                @endforeach
                                @endif

                                <div class="form-group new_trusted_brands" id="new_trusted_brands"></div>
                                <!-- <div class="add_trusted_brands mt-2" id="add_trusted_brands">
                                    <a class="btn btn-primary mt-3 add_image_btn" id="add_image_btn">Add Image</a>
                                    <a class="mt-3 add_trusted_brands_btn" id="add_trusted_brands_btn">
                                        <i class="bx bx-plus mx-1" data-bs-html="true" title="Add"></i>
                                    </a>
                                </div> -->
                            </div>
                            <hr>
                            <div class="row">
                                <h6><strong>For Clients Section</strong></h6>
                                <div class="form-group mt-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="">Banner </label>
                                            <input type="file" class="form-control @error('client_banner') is-invalid @enderror" name="client_banner" value="">
                                            <input type="hidden" name="client_banner_old" value="{{isset($page) && !empty($content['for_client']['client_banner']) ? $content['for_client']['client_banner'] : ''}}">
                                            @error('client_banner')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            @if(!empty($content['for_client']['client_banner']))
                                                <div class="parc">
                                                    <img src="{{ url('/images/home').'/'.$content['for_client']['client_banner'] ?? "" }}" alt="" width="100" height="100">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <label for="name" class="">Title *</label>
                                    <input type="text" id="client_title" name="client_title" class="form-control @error('client_title') is-invalid @enderror" value="{{ empty(old('client_title')) ? ((isset($page) && !empty($content['for_client'])) ? $content['for_client']['client_title'] : '') : old('client_title') }}" required>
                                    @error('client_title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group mt-2">
                                    <label for="description">Description </label>
                                    <textarea id="client_description" name="client_description" class="form-control @error('client_description') is-invalid @enderror" required>{{ empty(old('client_description')) ? (!empty($content['for_client']['client_description']) ? $content['for_client']['client_description'] : '') : old('client_description') }}</textarea>
                                    @error('client_description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group mt-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="name" class="">Title 1*</label>
                                            <input type="text" id="title_1" name="title_1" class="form-control @error('title_1') is-invalid @enderror" value="{{ empty(old('title_1')) ? ((isset($page) && !empty($content['for_client'])) ? $content['for_client']['title_1'] : '') : old('title_1') }}" required>
                                            @error('title_1')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="name" class="">Description 1*</label>
                                            <input type="text" id="description_1" name="description_1" class="form-control @error('description_1') is-invalid @enderror" value="{{ empty(old('description_1')) ? ((isset($page) && !empty($content['for_client'])) ? $content['for_client']['description_1'] : '') : old('description_1') }}" required>
                                            @error('description_1')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="name" class="">Title 2*</label>
                                            <input type="text" id="title_2" name="title_2" class="form-control @error('title_2') is-invalid @enderror" value="{{ empty(old('title_2')) ? ((isset($page) && !empty($content['for_client'])) ? $content['for_client']['title_2'] : '') : old('title_2') }}" required>
                                            @error('title_2')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="name" class="">Description 2*</label>
                                            <input type="text" id="description_2" name="description_2" class="form-control @error('description_2') is-invalid @enderror" value="{{ empty(old('description_2')) ? ((isset($page) && !empty($content['for_client'])) ? $content['for_client']['description_2'] : '') : old('description_2') }}" required>
                                            @error('description_2')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="name" class="">Title 3*</label>
                                            <input type="text" id="title_3" name="title_3" class="form-control @error('title_3') is-invalid @enderror" value="{{ empty(old('title_3')) ? ((isset($page) && !empty($content['for_client'])) ? $content['for_client']['title_3'] : '') : old('title_3') }}" required>
                                            @error('title_3')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="name" class="">Description 3*</label>
                                            <input type="text" id="description_3" name="description_3" class="form-control @error('description_3') is-invalid @enderror" value="{{ empty(old('description_3')) ? ((isset($page) && !empty($content['for_client'])) ? $content['for_client']['description_3'] : '') : old('description_3') }}" required>
                                            @error('description_3')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="popular_service_section">
                                    <h6><strong>Popular Service Section</strong></h6>
                                </div>
                                <div class="form-group mt-2">
                                    <label for="name" class="">Popular Service Title *</label>
                                    <input type="text" id="popular_service" name="popular_service" class="form-control @error('popular_service') is-invalid @enderror" value="{{ empty(old('popular_service')) ? (!empty($content['popular_service']) ? $content['popular_service'] : '') : old('popular_service') }}" required>
                                    @error('popular_service')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            

                            <div>
                                <input class="btn ad-btn create_btn mt-3" type="submit" value="{{ trans('global.save') }}">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script>
    $(document).ready(function(){
        
        used_by_image_count = document.getElementById('used_by_image_count').value;
        $('#add_image_btn').click(function(){

            data = '<div class="form-group used_by_image" id="used_by_image">'
                + '<div class="row">'
                    + '<div class="col-md-6">'
                        + '<label class="mt-2">Add Images </label>'
                        + '<input type="file" class="form-control" name="used_by_section_image[]" value="">'
                    + '</div>'
                    + '<div class="col-md-6">'
                        + '<div class="row">'
                            + '<div class="col-md-10">'
                            + '</div>'
                            + '<div class="col-md-2">'
                                + '<a class="mt-3 remove_image_btn" id="remove_image_btn">'
                                    + '<i class="bx bx-trash mx-1" data-bs-html="true" title="Remove"></i>'
                                + '</a>'
                            + '</div>'
                        + '</div>'
                    + '</div>'
                + '</div>'
            + '</div>';
            
            if(used_by_image_count<=4)
            {
                $('#new_used_by_image').append(data);
                document.getElementById('remove_image_btn').style.removeProperty('display');
            }

            else
            {
                used_by_image_count--;
                $('#add_image_btn').css('display', 'none');
            }

            used_by_image_count++;

        });
        

        $("body").on("click", "#remove_image_btn", function () {
            used_by_image_count--;

            $(this).parents("#used_by_image").remove();
            document.getElementById('add_image_btn').style.removeProperty('display');

            if(used_by_image_count == 1)
            {
                $('#remove_image_btn').first().css('display', 'none');
            }
            else
            {
                document.getElementById('remove_image_btn').style.removeProperty('display');
            }

        });

        if(used_by_image_count>1)
        {
            document.getElementById('remove_image_btn').style.removeProperty('display');
        }
        else
        {
            $('#remove_image_btn').first().css('display', 'none');
        }

        brand_count = document.getElementById('brand_count').value;
        $('#add_trusted_brands_btn').click(function(){
                // console.log(brand_count);

            data = '<div class="form-group mt-5 trusted_brands" id="trusted_brands">'
                + '<div class="row">'
                    + '<div class="col-md-6">'
                        + '<label class="">Logo </label>'
                        + '<input type="file" class="form-control" name="trusted_brands['+brand_count+'][logo]" value="">'
                    + '</div>'
                    + '<div class="col-md-6">'
                        + '<div class="row">'
                            + '<div class="col-md-10">'
                            + '</div>'
                            + '<div class="col-md-2">'
                                + '<a class="mt-3 remove_trusted_brands_btn" id="remove_trusted_brands_btn">'
                                    + '<i class="bx bx-trash mx-1" data-bs-html="true" title="Remove"></i>'
                                + '</a>'
                            + '</div>'
                        + '</div>'
                    + '</div>'
                + '</div>'
            
                + '<div class="form-group mt-2">'
                    + '<label for="brand_description">Description </label>'
                    + '<textarea id="brand_description" name="trusted_brands['+brand_count+'][brand_description]" class="form-control @error('brand_description') is-invalid @enderror" required>{{ old('brand_description', isset($project) ? $project->brand_description : '') }}</textarea>'
                    + '@error('brand_description')'
                        + '<span class="invalid-feedback" role="alert">'
                            + '<strong>{{ $message }}</strong>'
                        + '</span>'
                    + '@enderror'
                + '</div>'
                + '<div class="form-group">'
                    + '<div class="row">'
                        + '<div class="col-md-6">'
                            + '<div class="form-group mt-2">'
                                + '<label for="name" class="">Name *</label>'
                                + '<input type="text" id="brand_name" name="trusted_brands['+brand_count+'][brand_name]" class="form-control @error('brand_name') is-invalid @enderror" value="{{ empty(old('brand_name')) ? (isset($page) ? $page->brand_name : '') : old('brand_name') }}" required>'
                                + '@error('brand_name')'
                                    + '<span class="invalid-feedback" role="alert">'
                                        + '<strong>{{ $message }}</strong>'
                                    + '</span>'
                                + '@enderror'
                            + '</div>'
                        + '</div>'
                        + '<div class="col-md-6">'
                            + '<div class="form-group mt-2">'
                                + '<label for="designation" class="">Designation *</label>'
                                + '<input type="text" id="designation" name="trusted_brands['+brand_count+'][designation]" class="form-control @error('designation') is-invalid @enderror" value="{{ empty(old('designation')) ? (isset($page) ? $page->designation : '') : old('designation') }}" required>'
                                + '@error('designation')'
                                    + '<span class="invalid-feedback" role="alert">'
                                        + '<strong>{{ $message }}</strong>'
                                    + '</span>'
                                + '@enderror'
                            + '</div>'
                        + '</div>'
                    + '</div>'
                + '</div>'
                + '<div class="form-group">'
                    + '<div class="row">'
                        + '<div class="col-md-6">'
                            + '<div class="form-group mt-2">'
                                + '<label for="name" class="">Total Projects *</label>'
                                + '<input type="number" id="total_projects" name="trusted_brands['+brand_count+'][total_projects]" class="form-control @error('total_projects') is-invalid @enderror" value="{{ empty(old('total_projects')) ? (isset($page) ? $page->total_projects : '') : old('total_projects') }}" required>'
                                + '@error('total_projects')'
                                    + '<span class="invalid-feedback" role="alert">'
                                        + '<strong>{{ $message }}</strong>'
                                    + '</span>'
                                + '@enderror'
                            + '</div>'
                        + '</div>'
                        + '<div class="col-md-6">'
                            + '<div class="form-group mt-2">'
                                + '<label for="name" class="">Launch Projects *</label>'
                                + '<input type="text" id="launch_projects" name="trusted_brands['+brand_count+'][launch_projects]" class="form-control @error('launch_projects') is-invalid @enderror" value="{{ empty(old('launch_projects')) ? (isset($page) ? $page->launch_projects : '') : old('launch_projects') }}" required>'
                                + '@error('launch_projects')'
                                    + '<span class="invalid-feedback" role="alert">'
                                        + '<strong>{{ $message }}</strong>'
                                    + '</span>'
                                + '@enderror'
                            + '</div>'
                        + '</div>'
                    + '</div>'
                + '</div>'
            + '</div>';
            // + '<hr>';

            if(brand_count<=4)
            {
                $('#new_trusted_brands').append(data);
                document.getElementById('remove_trusted_brands_btn').style.removeProperty('display');
            }

            else
            {
                brand_count--;
                $('#add_trusted_brands_btn').css('display', 'none');
            }
            brand_count++;
        });
        

        $("body").on("click", "#remove_trusted_brands_btn", function () {
            brand_count--;

            $(this).parents("#trusted_brands").remove();
            document.getElementById('add_trusted_brands_btn').style.removeProperty('display');

            if(brand_count == 1)
            {
                $('#remove_trusted_brands_btn').first().css('display', 'none');
            }
            else
            {
                document.getElementById('remove_trusted_brands_btn').style.removeProperty('display');
            }

        });

        if(brand_count>1){
            document.getElementById('remove_trusted_brands_btn').style.removeProperty('display');
        }
        else{
            $('#remove_trusted_brands_btn').first().css('display', 'none');
        }

    });
</script>

@endsection
