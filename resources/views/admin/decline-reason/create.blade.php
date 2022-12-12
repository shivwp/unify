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
                         @php
                            if(isset($reason->id)){
                            $msg = "Edit Decline Resion";
                        }else{
                        $msg = "Create Decline Resion";
                    }
                        @endphp
                        <h5>{{$msg}}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route("admin.decline-reason.store") }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ isset($reason) ? $reason->id : '' }}">
                            <div class="row">
                            	<div class="col-6">
		                            <div class="form-group mt-2">
		                                <label for="name" class="mt-2"> Title *</label>
		                                <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ empty(old('title')) ? (isset($reason) ? $reason->title : '') : old('title') }}" required>
		                                @error('title')
		                                    <span class="invalid-feedback" role="alert">
		                                        <strong>{{ $message }}</strong>
		                                    </span>
		                                @enderror
		                            </div>
                            	</div>	
                              <div class="col-6">
  								<label for="name" class="mt-3"> Type *</label>
	                            <select class="form-select" name="type" required>
	                                <option value="">--select--</option>
	                                <option value="invite" {{ (in_array('invite', old('type', [])) || isset($reason) && $reason->type=='invite') ? 'selected' : '' }}>Invite</option>
	                                <option value="offer" {{ (in_array('offer', old('type', [])) || isset($reason) && $reason->type=='offer') ? 'selected' : '' }}>Offer</option>
	                                <option value="withdraw" {{ (in_array('withdraw', old('type', [])) || isset($reason) && $reason->type=='withdraw') ? 'selected' : '' }}>Withdraw</option>
	                                <option value="proposal" {{ (in_array('proposal', old('type', [])) || isset($reason) && $reason->type=='proposal') ? 'selected' : '' }}>Proposal</option>
	                            </select>
                        	</div>
                            <div>
                                <input class="btn ad-btn create_btn mt-3" type="submit" value="{{ trans('global.save') }}">
                            </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
