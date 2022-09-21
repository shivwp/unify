@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-header">
                        {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
                    </div>
                
                    <div class="card-body">
                        <form action="{{ route("admin.users.update", [$user->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            @if(!empty($user->profileimage))
                                <div class="even mt-3" style="display: flex; flex-wrap: wrap; justify-content: flex-start;">
                                    
                                    <div class="parc">
                                        <span class="pip" data-title="{{$user->profileimage}}">
                                            <img src="{{ url('/profileimage').'/'.$user->profileimage ?? "" }}" alt="" width="100" height="100">
                                        </span>
                                    </div>
                                    
                                </div>
                            @endif

                            <div class="form-group">
                                <label class="form-label mt-3">Profile</label>
                                <input type="file" class="form-control" name="profileimage" value="">
                            </div>
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}mt-2">
                                <label for="name">First name*</label>
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
                            <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }} mt-2">
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
                                <input type="password" id="password" name="password" class="form-control">
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

                            <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }} mt-2">
                                <label for="roles">Skills
                         </label>
                        
                                <select name="skills[]" id="roles" class="form-control select2" multiple="multiple" required>
                                    @foreach($skill as $id => $skill)
                                        <option value="{{ $id }}" {{ (in_array($id, old('roles', [])) || isset($user) && $user->skills->contains($id)) ? 'selected' : '' }}>{{ $skill }}</option>
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
                            <div class="mt-3">
                                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
                            </div>
                        </form>
                
                
                    </div>
                </div>

@endsection
