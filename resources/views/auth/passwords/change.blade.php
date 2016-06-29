@extends('layouts.dashboard')

@section('content')
<style type="text/css">
    .panel{
        margin-bottom: 16%;
    }
</style>


<div class="page-data dashboard-body">
<div class="container">
    <div class="row">
        @include('panels.left')
        <div class="col-sm-6" >
            <div class="panel panel-default">
                <div class="panel-heading">Change Password</div>  
                <div class="panel-body">
                @if (Session::has('error'))
                        <div class="alert alert-danger">{!! Session::get('error') !!}</div>
                    @endif
                    @if (Session::has('success'))
                        <div class="alert alert-success">{!! Session::get('success') !!}</div>
                    @endif
                    <form class="form-horizontal" role="form" method="POST" id="password_change" action="{{ url('change-password') }}">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label class="col-md-4 control-label">Old Password</label>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="password" class="form-control" name="old_password" id="resetpassword1">
                                    <div class="check-cont show-pw">
                                        <input type="checkbox"  name="checkboxG2" id="checkboxG21" class="css-checkbox password-eye" onchange="document.getElementById('resetpassword1').type = this.checked ? 'text' : 'password'"/>
                                        <label for="checkboxG21" class="css-label">show</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">New Password</label>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="password" class="form-control" name="new_password" id="resetpassword2">
                                    <div class="check-cont show-pw">
                                        <input type="checkbox"  name="checkboxG2" id="checkboxG22" class="css-checkbox password-eye" onchange="document.getElementById('resetpassword2').type = this.checked ? 'text' : 'password'"/>
                                        <label for="checkboxG22" class="css-label">show</label>
                                    </div>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Change Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="shadow-box bottom-ad"><img src="images/bottom-ad.jpg" alt="" class="img-responsive"></div>
        </div>
        @include('panels.right')
    </div>
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> 
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
<script type="text/javascript">

    $("#password_change").validate({ 
        errorElement: 'span',
        errorClass: 'help-inline',
        rules: {
            old_password: { required: true },
            new_password: {required: true,  minlength: 8}
        },
        messages:{
            old_password:{
                required: "Old password can't be empty."
            },
            new_password:{
                    required: "New password can't be empty.",
                    minlength: "Minimum length of password should be 8."
            }
        }
    });

</script>
@endsection
{!! Session::forget('error') !!}