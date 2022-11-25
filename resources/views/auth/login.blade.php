<!DOCTYPE html>
<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ URL::asset('admin/assets/') }}"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Unify Login</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ URL::asset('admin/assets/img/favicon/favicon.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />


    <link rel="stylesheet" href="{{ URL::asset('admin/assets/vendor/fonts/boxicons.css') }}" />


    <link rel="stylesheet" href="{{ URL::asset('admin/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ URL::asset('admin/assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ URL::asset('admin/assets/css/demo.css') }}" />

   
    <link rel="stylesheet" href="{{ URL::asset('admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

   
    <link rel="stylesheet" href="{{ URL::asset('admin/assets/vendor/css/pages/page-auth.css') }}" />
  
    <script src="{{ URL::asset('admin/assets/vendor/js/helpers.js') }}"></script>

    <script src="{{ URL::asset('admin/assets/js/config.js') }}"></script>
  </head>

  <body>
   
    <div class="container pr-5 pl-5">
        <div class="container pr-5 pl-5">
            <div class="row">
   <div class="col-12 mr-5 ml-5">
    <div class="container">
        
   <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
        
          <div class="card">
            <div class="card-body">
            
              <div class="app-brand justify-content-center">
                <a href="index.html" class="app-brand-link gap-2">
                  <span class="app-brand-logo demo">
                  <img width="80" height="40" src="{{url('/admin/assets/img/unify_branding.png')}}" alt="logo">
                  </span>
                 
                </a>
              </div>
              
              <h4 class="mb-2">Welcome to Unify! 👋</h4>
              <p class="mb-4">Please sign-in to your account</p>

              <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
              <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input
                    type="email"
                    class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                    id="email"
                    name="email"
                    placeholder="Enter your email or username"
                    autofocus
                  />
                  @if($errors->has('email'))
                        <div class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>
                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Password</label>
                    <a href="auth-forgot-password-basic.html">
                      <!-- <small>Forgot Password?</small> -->
                    </a>
                  </div>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="password"
                      class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                      name="password"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      aria-describedby="password"
                    />
                    <span class="input-group-text cursor-pointer" id="eye-icon"><i class="bx bx-hide"></i></span>
                    @if($errors->has('password'))
                        <div class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                  </div>
                </div>
                 <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                </div>
              </form>

              <!-- <p class="text-center">
                <span>New on our platform?</span>
                <a href="auth-register-basic.html">
                  <span>Create an account</span>
                </a>
              </p> -->
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
   </div>
   
        </div>
        </div>
      </div>
    </div>

  




    <script src="{{ URL::asset('admin/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ URL::asset('admin/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ URL::asset('admin/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ URL::asset('admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ URL::asset('admin/assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="{{ URL::asset('admin/assets/js/main.js') }}"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>























