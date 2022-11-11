@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
            
<style type="text/css">
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #787878 !important;
    border: 1px solid #787878 !important;
}
</style>
@if(Session::has('error'))
    <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error') }}</p>
@endif
<div class="card">
    <div class="card-header">
        Create Contract
    </div>
    <div class="card-body">
        {{ $errors }}
        <form action="{{ route('admin.contracts.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Project Name *</label>
                <select id="name" name="name" class="form-control @error('name') is-invalid @enderror select2" required>
                    <option value="" {{ old('name') ? (old('name') == "" ? 'selected' : '') : '' }}>Please Select</option>
                    @if(count($projects) > 0)
                        @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ old('name') ? (old('name') == $project->id ? 'selected' : '') : '' }}>{{ $project->name }}</option>
                        @endforeach
                    @endif
                </select>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label for="name">Client Name *</label>
                <select id="client_name" name="client_name" class="form-control @error('client_name') is-invalid @enderror select2" required>
                    <option value="" {{ old('client_name') ? (old('client_name') == "" ? 'selected' : '') : '' }}>Please Select</option>
                    @if(count($clients) > 0)
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_name') ? (old('client_name') == $client->id ? 'selected' : '') : '' }}>{{ $client->first_name }} ({{ $client->email }})</option>
                        @endforeach
                    @endif
                </select>
                @error('client_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label for="name">Freelancer Name *</label>
                <select id="freelancer_name" name="freelancer_name" class="form-control @error('freelancer_name') is-invalid @enderror select2" required>
                    <option value="" {{ old('freelancer_name') ? (old('freelancer_name') == "" ? 'selected' : '') : '' }}>Please Select</option>
                    @if(count($freelancers) > 0)
                        @foreach($freelancers as $freelancer)
                        <option value="{{ $freelancer->id }}" {{ old('freelancer_name') ? (old('freelancer_name') == $freelancer->id ? 'selected' : '') : '' }}>{{ $freelancer->first_name }} ({{ $freelancer->email }})</option>
                        @endforeach
                    @endif
                </select>
                @error('freelancer_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label for="name">Project Type *</label>
                <select id="project_type" name="project_type" class="form-control @error('project_type') is-invalid @enderror" required>
                    <option value="" {{ old('project_type') ? (old('project_type') == "" ? 'selected' : '') : '' }}>Please Select</option>
                    <option value="project" {{ old('project_type') ? (old('project_type') == "project" ? 'selected' : '') : '' }}>By Project</option>
                    <option value="milestone" {{ old('project_type') ? (old('project_type') == "milestone" ? 'selected' : '') : '' }}>By Milestone</option>
                </select>
                @error('project_type')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mt-3 by_project" id="by_project" style="display: none;">
                <div class="row">
                    <div class="col-md-6">
                        <label for="budget">Bid Amount</label>
                        <input type="number" id="bid_amount" name="bid_amount" class="form-control @error('bid_amount') is-invalid @enderror" value="{{ old('bid_amount', '') }}" step="0.01">
                        @error('bid_amount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="budget">Service Fee</label>
                        <input type="number" id="service_fee" name="service_fee" class="form-control @error('service_fee') is-invalid @enderror" value="{{ old('service_fee', '') }}" readonly>
                        @error('service_fee')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group mt-3 by_milestone" id="by_milestone" style="display: none;">

                <label for="budget">Description</label>
                <textarea name="description[]" class="form-control @error('description') is-invalid @enderror">{{ old('description', '') }}</textarea>
                @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                <div class="row">
                    <div class="form-group mt-3 col-md-6">
                        <label for="name">Due Date *</label>
                        <input type="date" name="due_date[]" value="" class="form-control @error('due_date') is-invalid @enderror">
                        @error('due_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group mt-3 col-md-6">
                        <label for="name">Amount *</label>
                        <input type="number" name="milestone_amount[]" value="" class="form-control @error('milestone_amount') is-invalid @enderror" step="0.01">
                        @error('milestone_amount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <button class="btn btn-danger mt-3 remove_milestone_btn" id="remove_milestone_btn">Remove</button>
                <hr>
            </div>
            <div class="form-group mt-3 new_by_milestone" id="new_by_milestone"></div>
            <div class="add_milestone" id="add_milestone" style="display:none;">
                <button class="btn btn-primary mt-3 add_milestone_btn" id="add_milestone_btn">Add Milestone</button>
            </div>
            <div class="form-group mt-3">
                <label for="name">Cover Letter *</label>
                <textarea id="cover_letter" name="cover_letter" class="form-control @error('cover_letter') is-invalid @enderror" required></textarea>
                @error('cover_letter')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label for="name">Status *</label>
                <select id="status" name="status" class="form-control @error('status') is-invalid @enderror" required>
                    <option value="" {{ old('status') ? (old('status') == "" ? 'selected' : '') : '' }}>Please Select</option>
                    <option value="pending" {{ old('status') ? (old('status') == "pending" ? 'selected' : '') : '' }}>Pending</option>
                    <option value="approve" {{ old('status') ? (old('status') == "approve" ? 'selected' : '') : '' }}>Approve</option>
                    <option value="close" {{ old('status') ? (old('status') == "close" ? 'selected' : '') : '' }}>Close</option>
                </select>
                @error('status')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="mt-3">
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>  
    </div>
</div> 
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script>

    $(document).ready(function(){
        $('#project_type').change(function(){
            var type = document.getElementById('project_type').value;
            sessionStorage.setItem('type', type);

            if(type == 'project'){
                document.getElementById('by_milestone').style.display = 'none';
                document.getElementById('add_milestone').style.display = 'none';
                document.getElementById('by_project').style.removeProperty('display');
            }
            else if(type == 'milestone'){
                document.getElementById('by_project').style.display = 'none';
                document.getElementById('by_milestone').style.removeProperty('display');
                document.getElementById('add_milestone').style.removeProperty('display');
            }
            else if(type == ''){
                document.getElementById('by_milestone').style.display = 'none';
                document.getElementById('add_milestone').style.display = 'none';
                document.getElementById('by_project').style.display = 'none';
            }
        });
        var type_value = sessionStorage.getItem("type");
        
        if(type_value == 'project'){
            document.getElementById('by_project').style.removeProperty('display');
        }
        else if(type_value == 'milestone'){
            document.getElementById('by_milestone').style.removeProperty('display');
            document.getElementById('add_milestone').style.removeProperty('display');
        }
        if(document.getElementById('project_type').value == '')
        {
            sessionStorage.removeItem("type");
        }


        $('#add_milestone_btn').click(function(){

            data = '<div class="form-group mt-3 by_milestone" id="by_milestone">'

                + '<label for="budget">Description</label>'
                + '<textarea name="description[]" class="form-control @error('description[]') is-invalid @enderror">'
                    + '{{ old('description[]', '') }}'
                + '</textarea>'
                + '@error('description[]')'
                    + '<span class="invalid-feedback" role="alert">'
                        + '<strong>{{ $message }}</strong>'
                    + '</span>'
                + '@enderror'

                + '<div class="row">'
                    + '<div class="form-group mt-3 col-md-6">'
                        + '<label for="name">Due Date *</label>'
                        + '<input type="date" name="due_date[]" value="" class="form-control">'
                        + '@error('due_date')'
                            + '<span class="invalid-feedback" role="alert">'
                                + '<strong>{{ $message }}</strong>'
                            + '</span>'
                        + '@enderror'
                    + '</div>'
                    + '<div class="form-group mt-3 col-md-6">'
                        + '<label for="name">Amount *</label>'
                        + '<input type="number" name="milestone_amount[]" value="" class="form-control" step="0.01">'
                        + '@error('milestone_amount')'
                            + '<span class="invalid-feedback" role="alert">'
                                + '<strong>{{ $message }}</strong>'
                            + '</span>'
                        + '@enderror'
                    + '</div>'
                + '</div>'
                + '<button class="btn btn-danger mt-3 remove_milestone_btn" id="remove_milestone_btn">Remove</button>'
                + '<hr>'
            + '</div>';

            $('#new_by_milestone').append(data);
            // $('#by_milestone:first').clone().insertAfter('#by_milestone:last');
        });

        $("body").on("click", "#remove_milestone_btn", function () {
            $(this).parents("#by_milestone").remove();
        })

        $('#remove_milestone_btn').first().css('display', 'none');
        
    });
</script>
@endsection
