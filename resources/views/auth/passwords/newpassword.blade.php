@extends('layouts.app')

<!-- Main Content -->
@section('content')

<!-- Login Popup -->
<div class="modal fade" id="LoginPop" tabindex="-1" role="dialog" aria-labelledby="LoginPopLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="flaticon-close"></span></button>

        <div class="login-form">
        {!! Form::open(array('url' => '/ajax/login', 'id' => 'loginform')) !!}
                    <h3 class="text-center">Login with Accounts</h3>
                    <div class="row field-row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input type="text" name="email" class="form-control icon-field emailid" placeholder="Email ID">
                                    <span class="help-block">
                                            <strong class = "erroremail"></strong>
                                        </span>
                                <span class="field-icon flaticon-letter133"></span>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control icon-field password" placeholder="Password" id="showpassword">
                                <span class="help-block">
                                            <strong class = "errorpassword"></strong>
                                        </span>
                                <span class="field-icon flaticon-padlock50"></span>
                                <div class="check-cont show-pw">
                                    <input type="checkbox" onchange="document.getElementById('showpassword').type = this.checked ? 'text' : 'password'" name="checkboxG2" id="checkboxG2" class="css-checkbox">
                                    <label for="checkboxG2" class="css-label">show</label>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row field-row">
                        <div class="col-md-6 border-right">
                            <div class="checkbox-cont">
                                <input type="checkbox" name="log" id="checkboxG3" class="css-checkbox">
                                <label for="checkboxG3" class="css-label">Keep me logged in</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <a  href="{{ url('password/reset') }}" title="" class="fg-pw-link">Forgot Password?</a>
                        </div>
                    </div>

                    <div class="row field-row">
                        <div class="col-md-12">
                            <div class="btn-cont text-center">
                                <button type="submit" id="login" class="btn btn-primary login" value="Login"> Login</button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

      </div>
    </div>
  </div>
</div>

<!--- Popup Login End  -->

<div class="container" style="margin-top:5%">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Password Reset Complete</div>
                <div class="panel-body">

                        <div class="alert alert-success" style="text-align: center;">
                            Congratulations your password has been changed succesfully.<br>
                            <div class="already-member">Please <a href="#" title="" data-toggle="modal" data-target="#LoginPop">Login</a> here to get started!</div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script type="text/javascript" src="{{url('/js/jquery-1.11.3.min.js')}}"></script>
<script src="http://malsup.github.com/jquery.form.js"></script> 
<script type="text/javascript" >
    
$("#loginform").ajaxForm(function(response) { 
    if(response){

                            if(response == "These credentials do not match our records.")
                            {
                                $('.help-block').find('.errorpassword').text(response).css('color','#a94442');
                                $('.emailid').css('border-color','#333333');
                                $('.password').css('border-color','#333333');
                                $('.help-block').find('.erroremail').text("");
                            }
        
                            else if(response == "success"){
                                window.location = '/dashboard';
                            }else{
                                var res = response.split(',');

                                    if(res[0] == "email")
                                    {
                                    $('.help-block').find('.erroremail').text(res[1]).css('color','#a94442');
                                    $('.emailid').css('border-color','#a94442');
                                    $('.password').css('border-color','#333333');
                                    $('.help-block').find('.errorpassword').text("");
                                    }
                                    if(res[0] == "password"){
                                    $('.help-block').find('.erroremail').text("");
                                    $('.help-block').find('.errorpassword').text(res[1]).css('color','#a94442');
                                    $('.password').css('border-color','#a94442');
                                    $('.emailid').css('border-color','#333333');    
                                    }
                            }
                            
                            }

}); 

</script>

