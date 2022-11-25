@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        Edit Capabilities
                    </div>
                
                    <div class="card-body">
                        <form action="{{ route("admin.roles.update", [$role->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                                <label for="title">{{ trans('cruds.role.fields.title') }}*</label>
                                <input type="text" id="title" name="title" class="form-control" value="{{ old('title', isset($role) ? $role->title : '') }}" required>
                                @if($errors->has('title'))
                                    <p class="help-block">
                                        {{ $errors->first('title') }}
                                    </p>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.role.fields.title_helper') }}
                                </p>
                            </div>
                            <div class="form-group {{ $errors->has('permissions') ? 'has-error' : '' }}">

                               {{-- <label for="permissions">{{ trans('cruds.role.fields.permissions') }}*
                                                                                                  <span class="btn btn-info btn-xs select-all">{{ trans('global.select_all') }}</span>
                                                                                                  <span class="btn btn-info btn-xs deselect-all">{{ trans('global.deselect_all') }}</span></label>--}}

                                <select name="permissions[]" id="permissions" class="form-control select2" multiple="multiple" required>
                                    @foreach($permissions as $id => $permissions)
                                        <option value="{{ $id }}" {{ (in_array($id, old('permissions', [])) || isset($role) && $role->permissions->contains($id)) ? 'selected' : '' }}>{{ $permissions }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('permissions'))
                                    <p class="help-block">
                                        {{ $errors->first('permissions') }}
                                    </p>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.role.fields.permissions_helper') }}
                                </p>
                            </div>
                            <div>
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
