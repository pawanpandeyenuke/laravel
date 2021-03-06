<!-- Login Popup -->
        <div class="page-loading" style="display:none"><img src="{{url('/images/full-loading.gif')}}" alt="Loading Icon"></div>

<div class="modal fade" id="LoginPop" tabindex="-1" role="dialog" aria-labelledby="LoginPopLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="flaticon-close"></span></button>

        <div class="login-form">
        {!! Form::open(array('url' => '/ajax/login', 'id' => 'loginform')) !!}
                    <h1 class="text-center">Login with Accounts</h1>
                    <div class="row field-row">
                        <div class="col-sm-12 col-xs-12">
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
                        <div class="col-md-6 col-xs-12 border-right">
                            <div class="checkbox-cont">
                                <input type="checkbox" name="log" id="checkboxG3" class="css-checkbox">
                                <label for="checkboxG3" class="css-label">Keep me logged in</label>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
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

<script type="text/javascript">
jQuery(function($){
    $("#loginform").submit(function(event){
      $('.login').text('Please Wait..').prop('disabled',true);
    });
    
    $("#loginform").ajaxForm(function(response){     
        if(response)
        {
            var obj = $.parseJSON(response);
            console.log(obj.status);
            $('.password').next('.help-block').find('.verifymsg').hide();
            if(obj.status == "success")
            {
                var url_c = window.location.pathname;
                if(url_c == "/newpassword"){
                    window.location = "/";
                }
                else if(url_c.indexOf("email-verified") > -1 || url_c == "/send-verification-link"){
                    window.location = "/invite-friends";
                } else {
                    window.location = url_c;
                }
            }

            if(obj.status == "invalid")
            {
                var current = $('.password');
                current.next('.help-block').find('.verifymsg').hide();
                current.css('border-color','#a94442');
                current.next('.help-block').find('.errormsg').text('These credentials do not match our records.').css('color','#a94442');
                $('.emailid').css('border-color','#a94442');
                $('.emailid').next('.help-block').find('.errormsg').text("").css('color','#333333');
                $('.login').text('Login').prop('disabled',false);
            } else if(obj.status === "verification"){
                window.location = 'send-verification-link';
            }
            else
            {
                if( obj.email != null )
                {
                    var current = $('.emailid');
                    current.next('.help-block').find('.verifymsg').hide();
                    current.css('border-color','#a94442');
                    current.next('.help-block').find('.errormsg').text(obj.email).css('color','#a94442');

                    if( obj.password == null ){
                        $('.password').next('.help-block').find('.errormsg').text("").css('color','#333333');
                        current.next('.help-block').find('.verifymsg').hide();
                        $('.password').css('border-color','#333333');
                    }
                }
                if( obj.password != null ){     
                    var current = $('.password');
                    current.next('.help-block').find('.verifymsg').hide();              
                    current.css('border-color','#a94442');
                    current.next('.help-block').find('.errormsg').text(obj.password).css('color','#a94442');

                    if( obj.email == null ){
                        $('.emailid').next('.help-block').find('.errormsg').text("").css('color','#333333');
                        current.next('.help-block').find('.verifymsg').hide();
                        $('.emailid').css('border-color','#333333');
                    }
                }
                $('.login').text('Login').prop('disabled',false);
            }
        }
    });
});
</script>