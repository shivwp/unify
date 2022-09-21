@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-header">
                        {{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}
                    </div>
                    @php
                        $date = date('Y-m-d H:i:s');
                    @endphp
                    <div class="card-body">
                        <form action="{{ route("admin.users.store") }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="status" value="accept">
                            <input type="hidden" name="email_verified_at" value="{{$date}}">
                            <div class="form-group">
                                <label class="form-label mt-3">Profile</label>
                                <input type="file" class="form-control" name="profileimage" value="">
                            </div>
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} mt-2">
                                <label for="name" class="mt-2"> First name</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($user) ? $user->name : '') }}" required>
                                @if($errors->has('name'))
                                    <p class="help-block">
                                        {{ $errors->first('name') }}
                                    </p>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.user.fields.name_helper') }}
                                </p>
                            </div>
                            <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
                                <label for="name">Last name</label>
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
                                <label for="email">{{ trans('cruds.user.fields.email') }}*</label>
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
                                <label for="password">{{ trans('cruds.user.fields.password') }}</label>
                                <input type="password" id="password" name="password" class="form-control" required>
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
                                <label for="roles">{{ trans('cruds.user.fields.roles') }}*
                                   </label>
                                <select name="roles[]" id="roles" class="form-control select2" multiple="multiple" required>
                                    @foreach($roles as $id => $roles)
                                        <option value="{{ $id }}" {{ (in_array($id, old('roles', [])) || isset($user) && $user->roles->contains($id)) ? 'selected' : '' }}>{{ $roles }}</option>
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
                            

                            <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                                <label for="roles">Skills
                                   </label>
                                <select name="skills[]" id="roles" class="form-control select2" multiple="multiple" required>
                                    @foreach($ProjectSkill as $item)
                                        <option value="{{ $item->id }}" >{{ $item->name }}</option>
                                    @endforeach
                                </select>
                               
                            </div>
                            
                            <div>
                                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
                            </div>
                        </form>
                    </div>
                </div>

@endsection
