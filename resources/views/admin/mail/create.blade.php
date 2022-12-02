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
            <div class="card">
                <div class="card-header">
                  <h5>Create Mail Template</h5>
                </div>
                <hr class="m-0">
                <div class="card-body">
                <form method="POST" action="{{ route('admin.mail.store') }}" enctype="multipart/form-data">
                     @csrf
                     <div class="row">
                        <input type="hidden" name="id" value="{{isset($mail) ? $mail->id : ''}}">
                        <div class="col-md-12 mt-3" >
                           <div class="form-group">
                              <label class="form-label">From Name</label>
                              <input type="text" class="form-control" name="title" placeholder="From Name"
                                 value="{{isset($mail) ? $mail->name : ''}}" Required>
                           </div>
                        </div>
                        <div class="col-md-12 mt-3" >
                           <div class="form-group">
                              <label class="form-label">Subject</label>
                              <input type="text" class="form-control" name="subject" placeholder="Subject"
                                 value="{{isset($mail) ? $mail->subject : ''}}" Required>
                           </div>
                        </div>
                       
                        <div class="col-md-12 mt-3" >
                           <div class="form-group">
                              <label class="form-label">From Email</label>
                              <input type="email" class="form-control" name="from_mail" placeholder="From Email"
                                 value="{{isset($mail) ? $mail->from_email : ''}}" Required>
                           </div>
                        </div>
                        <div class="col-md-12 mt-3" >
                           <div class="form-group">
                              <label class="form-label">User Category</label>
                              <select name="user_category" id="" class="form-control" Required>
                              <option>Select</option>
                                 <option value="user" @if(isset($mail ) && $mail->user_category=="user") Selected @endif > User</option>
                                 <option value="freelancer"  @if(isset($mail ) && $mail->user_category=="freelancer") Selected @endif>Freelancer</option>
                                 <option value="client"  @if(isset($mail ) && $mail->user_category=="client") Selected @endif>Client</option>

                              </select>
                           </div>
                        </div>
                        <div class="col-md-12 mt-3" >
                           <div class="form-group">
                              <label class="form-label">Mail Category</label>
                              <select name="mail_category" id="" class="form-control">
                              <option>Select</option>
                                 <option value="signupverification"  @if(isset($mail ) && $mail->mail_category=="signupverification") Selected @endif>Sign Up</option>
                                 <option value="forgetpassword"  @if(isset($mail ) && $mail->mail_category=="forgetpassword") Selected @endif>Forget Password</option>
                                 <option value="contactus"  @if(isset($mail ) && $mail->mail_category=="contactus") Selected @endif>Contact Us</option>
                                 <option value="resendotp"  @if(isset($mail ) && $mail->mail_category=="resendotp") Selected @endif>Resend OTP</option>
                                 <option value="request_testimonial"  @if(isset($mail ) && $mail->mail_category=="request_testimonial") Selected @endif>Request Testimonial</option>
                                 <option value="social_login"  @if(isset($mail ) && $mail->mail_category=="social_login") Selected @endif>Social Login</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-12 mt-3" >
                           <div class="form-group">
                              <label class="form-label">Reply From Email</label>
                              <input type="email" class="form-control" name="reply_from_mail" placeholder="Replay From Email"
                                 value="{{isset($mail) ? $mail->reply_email : ''}}">
                           </div>
                        </div>
                        <div class="col-md-12 mt-3">
                        <label class="form-label">Message</label>
                        <textarea class="ckeditor form-control" name="message">{{isset($mail) ? $mail->message : ''}}</textarea>
                        </div>
                      
                     </div>
                  
                     @if(isset($mail->id))
                     <button class="btn ad-btn create_btn mt-3 " type="submit">Update</button>
                     @else
                     <button class="btn ad-btn create_btn mt-3 " type="submit">Save</button>
                     @endif
                  </form>
                </div>
            </div> 
         </div>
      </div>
   </div>
</div>
@endsection
