@extends('layouts.app')

@include('panels.meta-data')
<!-- Main Content -->
@section('content')
         
<div class="container" style="margin-top:5%">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
                 @if (Session::has('success'))
                 <?php
                        $heading =  "If you are facing problems in receiving verification email. Please enter your email address below to resend verification link.";
                  ?>
                    <div class="alert alert-success" style="text-align:center;">{!! Session::get('success') !!}</div>
                    <div style="text-align:center; font-size: 17pt; padding:3%;">OR</div>
                @else
                  <?php $heading = "Enter email address below to send verification link."; ?>
                @endif
                @if (Session::has('error'))
                    <div class="alert alert-danger">{!! Session::get('error') !!}</div>
                @endif
                <div class="panel panel-default">
                <div class="panel-heading"><b>{{$heading}}</b></div>
                <div class="panel-body">

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST" id="verify_email" action="{{ url('send-verification-link') }}">
                        {!! csrf_field() !!}
                                 
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Email Address</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                     @if(Session::has('success'))
                                    <i class="fa fa-btn fa-envelope"></i>&nbsp; Resend Verification Link
                                    @else
                                    <i class="fa fa-btn fa-envelope"></i>&nbsp; Send Email Verification Link
                                    @endif
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

        $("#verify_email").validate({ 
        errorElement: 'span',
        errorClass: 'help-inline',
        rules: {
            email: { required: true, email: true },

        },
        messages:{
            email:{
                required: "Please enter an email address to verify.",
                email: "Please enter a valid email address."
            }
        }
    });
    
</script>
@endsection