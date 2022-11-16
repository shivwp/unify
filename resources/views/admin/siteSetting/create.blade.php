@extends('layouts.master') @section('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
      @if(Session::has('message'))
      <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
      @endif
        <div class="row">
            <div class="col-lg-12">

                <!-- PAGE-HEADER -->
                <div class="card-header">
                  Site Setting
                </div>
                <!-- PAGE-HEADER END -->
                <div class="card">
                  <div class="card-body" id="add_space">
                    <form action="{{ route("admin.site-setting.store") }}" method="post" enctype="multipart/form-data">
                      @csrf
                      <div class="row">
                         <div class="col-md-2">
                          <div class="form-group">
                            <label class="control-label ">Business logo 1 </label>
                           
                            <input type="file" class="form-control" name="business_logo1" value="">
                          </div>
                        </div>
                        <div class="col-md-4 mt-3">
                        @if(!empty($settings['business_logo1']))
                       
                          <div class="form-group">
                            <img src="{{ url('/images/logo').'/'.$settings['business_logo1'] ?? "" }}" style="height:50px;width:120px;" alt="logo">
                          
                          @endif
                          </div>
                        </div>
                        <div class="col-md-2">
                       
                          <div class="form-group">
                            <label class="control-label">Business logo 2</label>
                        
                            <input type="file" class="form-control" name="business_logo2" value="">
                          </div>
                       
                        </div>
                        <div class="col-md-4 mt-3">
                          @if(!empty($settings['business_logo2']))
                          <div class="form-group">
                          <img src="{{ url('/images/logo').'/'.$settings['business_logo2'] ?? "" }}" style="height:50px;width:120px;" alt="logo">
                          </div> 
                          @endif
                        </div>
                        <div class="col-md-12 mt-3"><div class="form-group">
                            <label class="control-label ">Unify Service Fee </label>
                            <input type="hidden" name="setting[100][name]" value="servicefee">
                            <input type="text" name="setting[100][value]" class="form-control" value="{{isset($settings['servicefee']) ? $settings['servicefee'] : ''}}">
                          </div></div>
                        
                      </div>
                      <div class="row">
                        <div class="col-md-6 mt-3">
                          <div class="form-group">
                            <label class="control-label ">Country </label>
                            <input type="hidden" name="setting[1][name]" value="country">
                            <input type="text" name="setting[1][value]" class="form-control" value="{{isset($settings['country']) ? $settings['country'] : ''}}">
                          </div>
                        </div>
                        <div class="col-md-6 mt-3">
                          <div class="form-group">
                            <label class="control-label ">State </label>
                            <input type="hidden" name="setting[2][name]" value="state">
                            <input type="text" name="setting[2][value]" class="form-control" value="{{isset($settings['state']) ? $settings['state'] : ''}}">
                          </div>
                        </div>
                        <div class="col-md-6 mt-3">
                          <div class="form-group">
                            <label class="control-label ">City </label>
                            <input type="hidden" name="setting[3][name]" value="city">
                            <input type="text" name="setting[3][value]" class="form-control" value="{{isset($settings['city']) ? $settings['city'] : ''}}">
                          </div>
                        </div>
                        <div class="col-md-6 mt-3">
                          <div class="form-group">
                            <label class="control-label ">Postcode </label>
                            <input type="hidden" name="setting[4][name]" value="postcode">
                            <input type="number" name="setting[4][value]" class="form-control" placeholder="postcode" value="{{isset($settings['postcode']) ? $settings['postcode'] : ''}}">
                          </div>
                        </div>
                        <div class="col-md-6 mt-3">
                          <div class="form-group">
                            <label class="control-label ">Business Name </label>
                            <input type="hidden" name="setting[5][name]" value="business_name">
                            <input type="text" name="setting[5][value]" class="form-control" value="{{isset($settings['business_name']) ? $settings['business_name'] : ''}}">
                          </div>
                        </div>
                        
                        <div class="col-md-6 mt-3">
                          <div class="form-group">
                            <label class="control-label ">Email </label>
                            <input type="hidden" name="setting[6][name]" value="email">
                            <input type="email" class="form-control" placeholder="Email" name="setting[6][value]" value="{{isset($settings['email']) ? $settings['email'] : ''}}">
                          </div>
                        </div>
                        <div class="col-md-6 mt-3">
                          <div class="form-group">
                            <label class="control-label ">PAN Number </label>
                            <input type="hidden" name="setting[7][name]" value="pan_number">
                            <input type="text" class="form-control" placeholder="PAN NUMBER" name="setting[7][value]" value="{{isset($settings['pan_number']) ? $settings['pan_number'] : ''}}">
                          </div>
                        </div>
                        <div class="col-md-6 mt-3">
                          <div class="form-group">
                            <label class="control-label ">CIN Number </label>
                            <input type="hidden" name="setting[8][name]" value="cin_number">
                            <input type="text" class="form-control" placeholder="CIN NUMBER" name="setting[8][value]" value="{{isset($settings['cin_number']) ? $settings['cin_number'] : ''}}">
                          </div>
                        </div>
                        <div class="col-md-6 mt-3">
                          <div class="form-group">
                            <label class="control-label ">Site Url </label>
                            <input type="hidden" name="setting[9][name]" value="site_url">
                            <input type="url" class="form-control" name="setting[9][value]" placeholder="www.example.com" value="{{isset($settings['site_url']) ? $settings['site_url'] : ''}}">
                          </div>
                        </div>
                        <div class="col-md-6 mt-3">
                          <div class="form-group">
                            <label class="control-label ">Helpline Number </label>
                            <input type="hidden" name="setting[10][name]" value="helpline_number">
                            <input type="number" class="form-control" placeholder="Helpline Number" name="setting[10][value]" value="{{isset($settings['helpline_number']) ? $settings['helpline_number'] : ''}}">
                          </div>
                        </div>
                        
                        <div class="col-md-12 mt-3">
                          <div class="form-group">
                            <label class="control-label ">Address </label>
                            <input type="hidden" name="setting[11][name]" value="address">
                            <textarea name="setting[11][value]" class="form-control">{{isset($settings['address']) ? $settings['address'] : ''}}</textarea>
                          </div>
                        </div>
                      </div>

                      <!-- footer links -->
                      {{--<hr class="light-grey-hr" />
                      <div class="row">
                        <div class="col-sm-12">
                          <h4>Social Setting</h4>
                        </div>
                      </div>
                      @php $social=isset($setting[15]->value) ? json_decode($setting[15]->value,true):'' @endphp
                      <div class="row">
                        <div class="col-sm-6 mt-3">
                          <div class="form-group">
                            <label class="control-label ">Facebook</label>
                            <input type="hidden" name="id_15" value="{{ isset($setting[15]->id)?$setting[15]->id:""}}">
                            <input type="hidden" name="name_15" value="Social Info">
                            <input type="text" class="form-control" id="exampleInputuname_1" placeholder="Facebook" name="facebook" value="{{ isset($social['facebook']) ? $social['facebook']:""}}">
                          </div>
                        </div>
                        <div class="col-sm-6 mt-3">
                          <div class="form-group">
                            <label class="control-label ">Twitter</label>
                            <input type="text" class="form-control" id="exampleInputuname_1" placeholder="Twitter" name="twitter" value="{{ isset($social['twitter']) ? $social['twitter']:""}}">
                          </div>
                        </div>
                        <div class="col-sm-6 mt-3">
                          <div class="form-group">
                            <label class="control-label ">Instagram</label>
                            <input type="text" class="form-control" id="exampleInputuname_1" placeholder="Instagram" name="instagram" value="{{ isset($social['instagram']) ? $social['instagram']:""}}">
                          </div>
                        </div>
                        <div class="col-sm-6 mt-3">
                          <div class="form-group">
                            <label class="control-label">Linkedin</label>
                            <input type="text" class="form-control" id="exampleInputuname_1" placeholder="Linkedin" name="linkedin" value="{{ isset($social['linkedin']) ? $social['linkedin']:""}}">
                          </div>
                        </div>
                      </div>--}}
                      <div class="form-actions mt-3" id="add_space">
                        <button class="btn btn-danger">Save & update</button>
                      </div>
                    </form>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
