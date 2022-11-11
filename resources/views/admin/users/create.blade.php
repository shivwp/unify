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
                        {{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}
                    </div>
                    @php
                        $date = date('Y-m-d H:i:s');
                    @endphp
                    <div class="card-body">
                        <form action="{{ route("admin.users.store") }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="status" value="approve">
                            <input type="hidden" name="email_verified_at" value="{{$date}}">
                            <div class="form-group">
                                <label class="form-label mt-3">Profile Image</label>
                                <input type="file" class="form-control" name="profile_image" value="">
                            </div>
                            <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }} mt-2">
                                <label for="name" class="mt-2"> First name *</label>
                                <input type="text" id="name" name="first_name" class="form-control" value="{{ old('first_name', isset($user) ? $user->first_name : '') }}" required>
                                @if($errors->has('first_name'))
                                    <p class="help-block">
                                        {{ $errors->first('first_name') }}
                                    </p>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.user.fields.name_helper') }}
                                </p>
                            </div>
                            <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
                                <label for="name">Last name *</label>
                                <input type="text" id="name" name="last_name" class="form-control" value="{{ old('name', isset($user) ? $user->last_name : '') }}" required>
                                @if($errors->has('name'))
                                    <p class="help-block">
                                        {{ $errors->first('last_name') }}
                                    </p>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.user.fields.name_helper') }}
                                </p>
                            </div>
                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                <label for="email">Email *</label>
                                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', isset($user) ? $user->email : '') }}" required>
                                @if($errors->has('email'))
                                    <p class="help-block">
                                        {{ $errors->first('email') }}
                                    </p>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.user.fields.email_helper') }}
                                </p>
                            </div>
                            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                <label for="password">Password *</label>
                                <input type="password" id="password" name="password" min="8" class="form-control" required>
                                @if($errors->has('password'))
                                    <p class="help-block">
                                        {{ $errors->first('password') }}
                                    </p>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.user.fields.password_helper') }}
                                </p>
                            </div>
                            <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                                <label for="roles">Role *
                                   </label>
                                <select name="roles[]" id="roles" class="form-control form-select select2" multiple required>
                                    <option value="">Select Role</option>
                                    @foreach($roles as $id => $roles)
                                        <option value="{{ $id }}">{{ $roles }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('roles'))
                                    <p class="help-block">
                                        {{ $errors->first('roles') }}
                                    </p>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.user.fields.roles_helper') }}
                                </p>
                            </div>
                           <div class="form-group {{ $errors->has('referal_code') ? 'has-error' : '' }}">
                                <label for="name">Invitation Code</label>
                                <input type="text" id="name" name="referal_code" class="form-control" value="{{ old('referal_code', isset($user) ? $user->referal_code : '') }}" placeholder="if any referral">
                                @if($errors->has('name'))
                                    <p class="help-block">
                                        {{ $errors->first('referal_code') }}
                                    </p>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.user.fields.name_helper') }}
                                </p>
                            </div>
                            
                            <div>
                                <input class="btn btn-success btn_back" type="submit" value="{{ trans('global.save') }}">
                            </div>
                        </form>
                    </div>
                </div>

@endsection
