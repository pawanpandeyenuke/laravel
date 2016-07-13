<!-- Login Popup -->
        <div class="page-loading" style="display:none"><img src="{{url('/images/full-loading.gif')}}" alt=""></div>

<div class="modal fade" id="LoginPop" tabindex="-1" role="dialog" aria-labelledby="LoginPopLabel" data-backdrop="static" data-keyboard="false">
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
                                        <strong class = "errormsg"></strong>
                                    </span>
                                <span class="field-icon flaticon-letter133"></span>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control icon-field password" placeholder="Password" id="showpassword">
                                <span class="help-block">
                                    <strong class = "errormsg"></strong>
                                    <strong class = "verifymsg" style="display: none; color:#a94442">Please verify your account.<a href="{{url('send-verification-link')}}"> Click here </a>to send verification link again.</strong>
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
      <div class="modal-footer text-center login-footer">
        Not registered yet? <a style="cursor:pointer" href = "{{url('register')}}" title="">Click here</a>
      </div>
    </div>
  </div>
</div>
