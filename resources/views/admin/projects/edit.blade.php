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
                       <h5> {{ trans('global.edit') }} {{ trans('cruds.project.title_singular') }}</h5>
                    </div>
                    <hr class="m-0">
                    <div class="card-body">
                        <form action="{{ route("admin.projects.update", [$project->id]) }}" method="POST" enctype="multipart/form-data" id="formId">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="form-group col-md-6 {{ $errors->has('name') ? 'has-error' : '' }}">
                                    <label for="name">Project Name *</label>
                                    <input type="hidden" name="project_id" value="{{$project->id}}">
                                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', isset($project) ? $project->name : '') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            
                                <div class="form-group col-md-6">
                                    <label for="project_type">Project Type *</label>
                                    <select name="project_type" id="project_type" class="form-control @error('project_type') is-invalid @enderror" required>
                                        <option value="long_term" {{ old('project_type') ? (old('project_type') == 'long_term' ? "selected" : '' ) : (isset($project) ? (($project->type == 'long_term') ? 'selected' : '' ) : '' ) }}>Long Term</option>
                                        <option value="short_term" {{ old('project_type') ? (old('project_type') == 'short_term' ? "selected" : '' ) : (isset($project) ? (($project->type == 'short_term') ? 'selected' : '' ) : '' ) }}>Short Term</option>
                                    </select>
                                    @error('project_type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 mt-3">
                                    <label class="">Select Categories *</label>
                                    <select name="category" class="form-control @error('category') is-invalid @enderror" id="select-category" required>
                                        @if(count($category) > 0)
                                            @foreach($category as $key => $cate)
                                            <option value="{{ $cate->id }}" {{ old('category') ? (old('category') == $cate->id ? "selected" : '' ) : (isset($project) ? (($project->project_category == $cate->id) ? 'selected' : '' ) : '' ) }}>{{ $cate->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('category')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            
                                <div class="form-group col-md-6 mt-3">
                                    <label class="">Select Skills *</label>
                                    <select name="skills[]" class="form-control select2 @error('skills') is-invalid @enderror" id="select-skills" multiple required>
                                        <option value="" disabled>Select</option>
                                        @if(count($skill) > 0)
                                            @foreach($skill as $key => $val)
                                            <option value="{{ $val->id }}" {{ (isset($project) && $project->skills->contains($val->id)) ? 'selected' : '' }}>{{ $val->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('skills')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 {{ $errors->has('client_id') ? 'has-error' : '' }} mt-3">
                                    <label for="client">Client Name *</label>
                                    <select name="client_id" id="client" class="form-control @error('client_id') is-invalid @enderror" required>
                                        @if(isset($clients)) 
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}" {{ old('client_id') ? (old('client_id') == $client->id ? "selected" : '' ) : (isset($project) ? (($project->client_id == $client->id) ? 'selected' : '' ) : '' ) }} >{{ $client->name .' ('.$client->email.')' }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('client_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            
                                <div class="form-group col-md-6 mt-3">
                                    <label class="">Scope *</label>
                                    <select name="scope" class="form-control @error('scope') is-invalid @enderror" id="select-category"  required>
                                        <option value="large" {{ old('scope') ? (old('scope') == 'large' ? "selected" : '' ) : (isset($project) ? (($project->scop == 'large') ? 'selected' : '' ) : '' ) }}>Large</option>
                                        <option value="medium" {{ old('scope') ? (old('scope') == 'medium' ? "selected" : '' ) : (isset($project) ? (($project->scop == 'medium') ? 'selected' : '' ) : '' ) }}>Medium</option>
                                        <option value="small"  {{ old('scope') ? (old('scope') == 'small' ? "selected" : '' ) : (isset($project) ? (($project->scop == 'small') ? 'selected' : '' ) : '' ) }}>Small</option>
                                    </select>
                                    @error('scope')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 mt-3">
                                    <label class="">How long will your work take? *</label>
                                    <select name="project_duration" class="form-control @error('project_duration') is-invalid @enderror" id="select-project_duration"  required>
                                        <option value="6" {{ old('project_duration') ? (old('project_duration') == '6' ? "selected" : '' ) : (isset($project) ? (($project->project_duration == '6') ? 'selected' : '' ) : '' ) }}>More than 6 months</option>
                                        <option value="3" {{ old('project_duration') ? (old('project_duration') == '3' ? "selected" : '' ) : (isset($project) ? (($project->project_duration == '3') ? 'selected' : '' ) : '' ) }}>3 to 6 months</option>
                                        <option value="1" {{ old('project_duration') ? (old('project_duration') == '1' ? "selected" : '' ) : (isset($project) ? (($project->project_duration == '1') ? 'selected' : '' ) : '' ) }}>1 to 3 months</option>
                                    </select>
                                    @error('project_duration')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            
                                <div class="form-group col-md-6 mt-3">
                                    <label for="level">What level of experience will it need? *</label>
                                    <select name="level" id="client" class="form-control @error('level') is-invalid @enderror" required>
                                        <option value="entry" {{ old('level') ? (old('level') == 'entry' ? "selected" : '' ) : (isset($project) ? (($project->experience_level == 'entry') ? 'selected' : '' ) : '' ) }}>entry</option>
                                        <option value="intermediate" {{ old('level') ? (old('level') == 'intermediate' ? "selected" : '' ) : (isset($project) ? (($project->experience_level == 'intermediate') ? 'selected' : '' ) : '' ) }}>Intermediate</option>
                                        <option value="expert" {{ old('level') ? (old('level') == 'expert' ? "selected" : '' ) : (isset($project) ? (($project->experience_level == 'expert') ? 'selected' : '' ) : '' ) }}>Expert</option>
                                    </select>
                                    @error('level')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 mt-3">
                                    <label for="payment_base">Budget *</label>
                                    <select name="budget" id="paymethod" class="form-control @error('budget') is-invalid @enderror" required>
                                        <option value="hourly" {{ old('budget') ? (old('budget') == 'hourly' ? "selected" : '' ) : (isset($project) ? (($project->budget_type == 'hourly') ? 'selected' : '' ) : '' ) }}>Hourly Based</option>
                                        <option value="fixed" {{ old('budget') ? (old('budget') == 'fixed' ? "selected" : '' ) : (isset($project) ? (($project->budget_type == 'fixed') ? 'selected' : '' ) : '' ) }}>Fixed Price</option>
                                    </select>
                                    @error('budget')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            

                            <div class="form-group col-md-6 {{ $errors->has('budget') ? 'has-error' : '' }} mt-3 per_hour_budget" id="per_hour_budget" {{ old('budget') ? (old('budget') == 'hourly' ? '' : 'style=display:none;' ) : (isset($project) ? (($project->budget_type == 'hourly') ? '' : 'style=display:none;' ) : 'style="display: none;"' ) }} >
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="budget">Minimum in ($)</label>
                                        <input type="number" id="min_budget" name="min_budget" class="form-control @error('min_budget') is-invalid @enderror" value="{{ old('min_budget', isset($project) ? $project->min_price : '') }}" step="0.01" required>
                                        @error('min_budget')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="budget">Maximum in ($)</label>
                                        <input type="number" id="max_budget_hourly" name="max_budget_hourly" class="form-control @error('max_budget_hourly') is-invalid @enderror" value="{{ old('max_budget_hourly', isset($project) ? $project->price : '') }}" step="0.01" required>
                                        @error('max_budget_hourly')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6 {{ $errors->has('budget') ? 'has-error' : '' }} mt-3 total_budget" id="total_budget" {{ old('budget') ? (old('budget') == 'fixed' ? '' : 'style=display:none;' ) : (isset($project) ? (($project->budget_type == 'fixed') ? '' : 'style=display:none;' ) : 'style="display: none;"' ) }}>
                                <label for="budget">Maximum in ($)</label>
                                <input type="number" id="max_budget" name="max_budget" class="form-control @error('max_budget') is-invalid @enderror" value="{{ old('max_budget', isset($project) ? $project->price : '') }}" step="0.01" required>
                                @error('max_budget')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 {{ $errors->has('status_id') ? 'has-error' : '' }} mt-3">
                                <label for="status">Project Status *</label>
                                <select name="status_id" id="status" class="form-control @error('status_id') is-invalid @enderror">
                                    @if($statuses)
                                        @foreach($statuses as $id => $status)
                                            <option value="{{ strtolower($status) }}" {{ old('status_id') ? (old('status_id') == strtolower($status) ? "selected" : '' ) : (isset($project) ? (($project->status == strtolower($status)) ? 'selected' : '' ) : '' ) }}>{{ $status }}</option>
                                        @endforeach
                                        <!-- <option value="completed" {{ old('status_id') ? (old('status_id') == 'completed' ? "selected" : '' ) : (isset($project) ? (($project->status == 'completed') ? 'selected' : '' ) : '' ) }}>Completed</option> -->
                                    @endif
                                </select>
                                @error('status_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            </div>
                            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }} mt-3">
                                <label for="description">Project description </label>
                                <textarea id="project_description" name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', isset($project) ? $project->description : '') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <span id="project_description_error" style="color:red;"></span>
                                <p class="helper-block">
                                    {{ trans('cruds.project.fields.description_helper') }}
                                </p>
                            </div>
                            @if(!empty($project->project_images))
                                <div class="parc">
                                    <img src="{{ url('/images/jobs').'/'.$project->project_images ?? "" }}" alt="" width="100" height="100">
                                </div>
                            @endif
                            <label class="form-label mt-0">Add Images/Files </label>
                            <input type="file" class="form-control" name="image" value="">
                            <br>
                            <div>
                                <input class="btn ad-btn create_btn" type="submit" id="formsubmit" value="{{ trans('global.save') }}">
                            </div>
                        </form>


                    </div>
                </div> 
            </div>
        </div>
    </div>
    <!-- proposal , contract and Hire section -->
    <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
            <div class="col-lg-12">
                <div class="">
                    <div class="nav-align-top mb-4">
                        <ul class="nav nav-tabs nav-fill" role="tablist">
                          <li class="nav-item">
                            <button
                              type="button"
                              class="nav-link active"
                              role="tab"
                              data-bs-toggle="tab"
                              data-bs-target="#navs-justified-home"
                              aria-controls="navs-justified-home"
                              aria-selected="true"
                            >
                              Proposals
                              <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-danger">{{ $proposals->total() }}</span>
                            </button>
                          </li>
                          <li class="nav-item">
                            <button
                              type="button"
                              class="nav-link"
                              role="tab"
                              data-bs-toggle="tab"
                              data-bs-target="#navs-justified-profile"
                              aria-controls="navs-justified-profile"
                              aria-selected="false"
                            >
                             Messages
                            </button>
                          </li>
                          <li class="nav-item">
                            <button
                              type="button"
                              class="nav-link"
                              role="tab"
                              data-bs-toggle="tab"
                              data-bs-target="#navs-justified-messages"
                              aria-controls="navs-justified-messages"
                              aria-selected="false"
                            >
                              Hired
                            </button>
                          </li>
                        </ul>
                        <div class="tab-content">
                          <div class="tab-pane fade show active" id="navs-justified-home" role="tabpanel">
                            <h5 class="card-header">Proposals/Bids</h5>
                            <div class="table-responsive text-nowrap">
                            @if(count($proposals)>0)
                              <table class="table">
                                <thead>
                                  <tr class="ml-3">
                                    
                                    <th>Freelancer Name</th>
                                    <th>Email</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Submit Date</th>
                                    <th>Action</th>
                                  </tr>
                                </thead>

                                <tbody class="table-border-bottom-0">

                                  @foreach($proposals as $item)

                                  @foreach($item->users as $user)
                                  

                                  <tr>
                                    <td>
                                        @if(!empty($user->name)) {{$user->first_name}} @endif
                                    </td>
                                    <td>
                                        @if(!empty($user->email)) {{$user->email}} @endif
                                    </td>
                                    
                                    <td>
                                        {{ number_format((float)$item->bid_amount, 2, '.', '') }}$
                                    </td>
                                    <td>
                                        @if($item->status=="pending") <span class="badge bg-label-warning me-1">Pending</span>
                                        @elseif($item->status=="approve") <span class="badge bg-label-success me-1">Approve</span> 
                                        @elseif($item->status=="reject") <span class="badge bg-label-danger me-1">Reject</span> 
                                        @elseif($item->status=="hold") <span class="badge bg-label-primary me-1">On-Hold</span> @endif
                                    </td>
                                    <td>
                                        {{$item->created_at->toFormattedDateString()}}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.project-proposal',$item->id) }}">
                                            <button class="btn btn-sm btn-icon me-2" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title=" <span>View</span>"><i class="bx bx-show mx-1"></i></button>
                                        </a>
                                    </td>
                                  </tr>
                                  @endforeach
                                  @endforeach
                                 
                                </tbody>
                                
                              </table>
                              {!! $proposals->links() !!}
                              @else
                              <h6 class="card-header">No Proposal On This Job</h6>
                              
                              @endif
                            </div>
                          </div>
                          <div class="tab-pane fade" id="navs-justified-profile" role="tabpanel">
                            <p>
                                You don't have any Messages yet
                             </p>
                            <p class="mb-0">
                              
                           

                            </p>
                          </div>
                          <div class="tab-pane fade" id="navs-justified-messages" role="tabpanel">
                            <p>
                               You don't have any offers yet
                            </p>
                          </div>
                        </div>
                      </div>
                   
                  </div>
            </div>
            </div>
        
          </div>
</div>
@endsection
