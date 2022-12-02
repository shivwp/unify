@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">

                @if(Session::has('error'))
                    <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error') }}</p>
                @endif
                @if(Session::has('success'))
                    <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success') }}</p>
                @endif

                <div class="card">
                    <div class="card-header border-bottom">
                        <h5>Edit User Details </h5>
                    </div>
                
                    <div class="card-body">
                        <form action="{{ route("admin.users.update", [$user->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="form-label mt-3">Profile</label>
                                    <input type="file" class="form-control" name="profile_image" value="">
                                    <input type="hidden" class="form-control" name="profile_image_old" value="{{isset($user->profile_image) ? $user->profile_image : ''}}">
                                </div>
                                <div class="col-md-6">
                                    @if(!empty($user->profile_image))
                                        <div class="even mt-3">
                                            <div class="parc">
                                                <span class="pip" data-title="{{$user->profile_image}}">
                                                    <img src="{{ url('images/profile-image').'/'.$user->profile_image ?? "" }}" alt="" width="100" height="100">
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6 {{ $errors->has('first_name') ? 'has-error' : '' }}mt-2">
                                    <label for="name">First name*</label>
                                    <input type="hidden" class="form-control" name="user_id" value="{{$user->id}}">
                                    <input type="text" id="name" name="first_name" class="form-control" value="{{ old('first_name', isset($user) ? $user->first_name : '') }}" required>
                                </div>

                                <div class="form-group col-md-6 {{ $errors->has('last_name') ? 'has-error' : '' }} mt-2">
                                    <label for="name">Last name</label>
                                    <input type="text" id="name" name="last_name" class="form-control" value="{{ old('name', isset($user) ? $user->last_name : '') }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6 {{ $errors->has('email') ? 'has-error' : '' }} mt-2">
                                    <label for="email">Email *</label>
                                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', isset($user) ? $user->email : '') }}" readonly>
                                </div>

                                <div class="form-group col-md-6 {{ $errors->has('password') ? 'has-error' : '' }} mt-2">
                                    <label for="password">Password</label> <span class="password-info">(Password must be at least 8 characters with symbol & letter.)</span>
                                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6 {{ $errors->has('roles') ? 'has-error' : '' }} mt-2">
                                    <label for="roles">Role *</label>
                                    <select name="roles[]" id="roles" class="form-control form-select select2" multiple required>

                                        @foreach($roles as $id => $role)
                                            <option value="{{ $id }}" {{ (isset($user) && $user->roles->contains($id)) ? 'selected' : '' }}>{{ $role }}</option>
                                        @endforeach
                                        
                                    </select>
                                </div>

                                <div class="form-group col-md-6 {{ $errors->has('referal_code') ? 'has-error' : '' }} mt-2">
                                    <label for="name">Invitation Code</label>
                                    <input type="text" id="name" name="referal_code" class="form-control" value="{{ old('referal_code', isset($user) ? $user->referal_code : '') }}" placeholder="if any referral">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6 {{ $errors->has('roles') ? 'has-error' : '' }} mt-2">
                                    <label for="status">Status *</label>
                                    <select name="status" id="status" class="form-control form-select" required>
                                        <option value="pending" {{isset($user->status) && $user->status == "pending" ? 'selected' : ''}}>Pending</option>
                                        <option value="approve" {{isset($user->status) && $user->status == "approve" ? 'selected' : ''}}>Approve</option>
                                        <option value="reject" {{isset($user->status) && $user->status == "reject" ? 'selected' : ''}}>Reject</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-6 {{ $errors->has('is_verified') ? 'has-error' : '' }} mt-2">
                                    <label for="is_verified">Is verified *</label>
                                    <select name="is_verified" id="is_verified" class="form-control form-select" required>
                                        <option value="pending" {{isset($user->is_verified) && $user->is_verified == "pending" ? 'selected' : ''}}>Pending</option>
                                        <option value="approve" {{isset($user->is_verified) && $user->is_verified == "approve" ? 'selected' : ''}}>Approve</option>
                                        <option value="reject" {{isset($user->is_verified) && $user->is_verified == "reject" ? 'selected' : ''}}>Reject</option>
                                    </select>
                                </div>
                            </div>
                           
                            <div class="mt-3">
                                <input class="btn ad-btn create_btn" type="submit" value="{{ trans('global.save') }}">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
