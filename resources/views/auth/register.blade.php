@extends('layouts.app')

@section('content')
<style>
.small-text{
    color: #717272;
    font-size: 12px;
    text-align: center;
    padding: 5px 0;
}
</style>
<?php
$prev_url = URL::previous();
$userdata = session('userdata');
?>

@if (Session::has('success'))
    <div class="alert alert-success">{!! Session::get('success') !!}</div>
@endif

@if (Session::has('error'))
    <div class="alert alert-danger">{!! Session::get('error') !!}</div>
@endif

@include('panels.download-app')

<div class="page-data login-page">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-xs-12">
                <div class="home-slider-cont">
                    <div class="slider-btns">
                        <ul class="list-inline">
                            <li><a href="{{url('groupchat')}}" title="" class="btn btn-primary" data-toggle="modal" data-target="#LoginPop">Enter Chat Room</a></li>
                            <li><a href="{{url('forums')}}" title="" class="btn btn-primary">Enter Forum</a></li>
                        </ul>
                    </div>
                    <div id="hSlider" class="carousel slide" data-ride="carousel">
                        <!-- Indicators -->
                  <ol class="carousel-indicators">
                    <li data-target="#hSlider" data-slide-to="0" class="active"></li>
                    <li data-target="#hSlider" data-slide-to="1"></li>
                    <li data-target="#hSlider" data-slide-to="2"></li>
                  </ol>
                      <!-- Wrapper for slides -->
                      <div class="carousel-inner" role="listbox">
                        <div class="item active">
                          <img src="images/slide1.jpg" alt="">
                        </div>
                        <div class="item">
                          <img src="images/slide1.jpg" alt="">
                        </div>
                        <div class="item">
                          <img src="images/slide1.jpg" alt="">
                        </div>
                      </div>
                      
                    </div>
                    <!-- Controls -->
                      <a class="left carousel-control" href="#hSlider" role="button" data-slide="prev">
                        <i class="fa fa-angle-left" aria-hidden="true"></i>
                        <span class="sr-only">Previous</span>
                      </a>
                      <a class="right carousel-control" href="#hSlider" role="button" data-slide="next">
                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                        <span class="sr-only">Next</span>
                      </a>
                      
                        <div class="social-btns-cont">
                            <ul class="list-inline">
                                <li><a href="{{ Config::get('constants.ios_app_link') }}" title="" target='_blank'><img src="images/apple-stroe-btn.png" alt="iTunes"></a></li>
                                <li><a href="{{ Config::get('constants.android_app_link') }}" title="" target='_blank'><img src="images/android-store-btn.png" alt="Google Play Store"></a></li>
                            </ul>
                        </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="login-form registration-form">
                    <div class="already-member">Already have Account? <a href="#" title="" data-toggle="modal" data-target="#LoginPop">Login</a></div>
                    <h3 class="text-center">Registration</h3>
                      <form class="form-horizontal" id="registerForm" role="form" method="POST" action="{{ url('/') }}">
                        {!! csrf_field() !!}

                    <div class="row field-row">
                        <div class="col-sm-12">
                            @if (Session::has('email_error'))
                                <div class="form-group"> 
                                    <div class="alert alert-danger">{!! Session::get('email_error') !!}</div>
                                </div>
                            @endif
                            <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                <input type="text" name="first_name" value="{{ Request::get('first_name') ? Request::get('first_name') : session('first_name') }}" class="form-control icon-field" placeholder="First Name">
                                    
                                    @if ($errors->has('first_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                                    @endif
                                    
                                    <span class="field-icon flaticon-profile5"></span>
                                </div>

                            <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                    <input type="text" name="last_name" value="{{ Request::get('last_name') ? Request::get('last_name') : session('last_name') }}" class="form-control icon-field" placeholder="Last Name">
                                    
                                    @if ($errors->has('last_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                                    @endif
                                    
                                    <span class="field-icon flaticon-profile5"></span>
                                </div>


                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <input type="email" name="email" value="{{ session('email') }}" class="form-control icon-field" placeholder="Email ID" >
                                    
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                    
                                    <span class="field-icon flaticon-letter133"></span>
                                </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <input type="password" name="password" class="form-control icon-field" placeholder="Password" id="showpassword1">
                                    
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                    
                                    <span class="field-icon flaticon-padlock50"></span>
                                    <div class="check-cont show-pw">
                                        <input type="checkbox"  name="checkboxG2" id="checkboxG21" class="css-checkbox password-eye" onchange="document.getElementById('showpassword1').type = this.checked ? 'text' : 'password'"/>
                                        <label for="checkboxG21" class="css-label">show</label>
                                    </div>
                                </div>

                                <?php //echo '<pre>';print_r($countries);die;?>

                            <div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
                                <select class="form-control icon-field" name ="country" id="mob-country">
                                    <?php unset($countries[0]); ?>
                                    <option value="">Country</option>
                                    @foreach($countries as $key => $country)
                                    <?php
                                        if(session('country')!="" && session('country')==$key)
                                            $selected = "selected";
                                        else
                                            $selected = "";
                                     ?>
                                        <option value="{{ $key }}" {{$selected}}>{{ $country }}</option>
                                    @endforeach
                                </select>
                                    @if ($errors->has('country'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('country') }}</strong>
                                        </span>
                                    @endif
                                <span class="field-icon flaticon-web-1"></span>                                 
                            </div>

                            <div class="form-group ph-field {{ ($errors->has('mobile_unique') || $errors->has('invalid_country_code')) ? ' has-error' : '' }}">
                                <?php 
                                    if(session('country_code') != "")
                                        $font = "";
                                    else
                                        $font = "#999";
                                ?>
                                <span class="country-code-field">
                                    <input type="text" name="country_code" class="country-code-field numeric register-country-code" placeholder="000" value="{{ session('country_code') }}" maxlength="4"/>    
                                </span> 
                                
                                <input type="text" class="form-control icon-field numeric register-mobile" name = "phone_no" value="{{ session('phone_no') }}" placeholder="Mobile" id="mobileContact">
                                <span class="field-icon flaticon-smartphone-with-blank-screen"></span>
                                @if ($errors->has('mobile_unique'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('mobile_unique') }}</strong>
                                    </span>
                                @endif

                                @if ($errors->has('invalid_country_code'))
                                    <span class="help-block c_code_err_msg">
                                        <strong>{{ $errors->first('invalid_country_code') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group sex-option">
                                    <ul>
                                        <li>I am</li>
                                        <?php
                                            if(session('gender') != ""){
                                                if(session('gender') == "Male"){
                                                    $male = "checked";
                                                    $female = "";
                                                }else{
                                                    $male = "";
                                                    $female = "checked";
                                                }
                                            }else{
                                                $male = "";
                                                $female = "";
                                            }
                                        ?>
                                        <li>
                                            <div class="small-radio-cont">
                                                <input type="radio" {{$male}} name="gender" value="Male" id="radio1" class="css-checkbox" />
                                                <label for="radio1" class="css-label radGroup1">Male</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="small-radio-cont">
                                                <input type="radio" {{$female}} name="gender" value="Female" id="radio2" class="css-checkbox" />
                                                <label for="radio2" class="css-label radGroup1">Female</label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                    <div class="form-groups">
                        <div class="form-group">
                        <div class = "checkbox-cont">
                        <?php 
                                if(session('terms')!="")
                                    $terms = "checked";
                                else
                                    $terms = "";
                        ?>
                        <input type="checkbox" name="terms" id="terms" {{$terms}} class="css-checkbox">
                        <label for="terms" class="css-label" style="color: #0c0c0c" >I agree to the following<a href="{{url('terms')}}" style="color:#3ab29f "> Terms and Conditions</a>.</label>
                        </div>
                     </div>
                    <input type="hidden" name="url" value="{{$prev_url}}"/>
                    
                    <?php if(Request::get('src')){ ?>
                        <input type="hidden" name="{{ Request::get('src') }}_id" value="<?php echo session(Request::get('src').'_id');?>"/>
                    <?php } ?>

                        </div>
                            <div class="form-groups">
                                <div class="btn-cont text-center">
                                    <input type="submit" class="btn btn-primary register" value="Get Started">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                    <div class="or-divider"><span>Or</span></div>
                 <div class="small-text">Your social networking login details will be kept confidential.</div>                 
                    <div class="social-login top-margin">
                        <ul>
                            <li><a href="{{ url('redirect/facebook') }}" class="fb"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="{{ url('redirect/twitter') }}" class="tw"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="{{ url('redirect/google') }}" class="gp"><i class="fa fa-google-plus"></i></a></li>
                            <li><a href="{{ url('redirect/linkedin') }}" class="lin"><i class="fa fa-linkedin"></i></a></li>
                        </ul>
                    </div><!--/social login-->

                </div>
            </div>
        </div>
    </div>

    @include('panels.guest-footer')

</div><!--/pagedata-->

<script type="text/javascript">
function getValidationArray(mobCode)
{
    var countryMobValidLengthArray = <?php print_r(json_encode(countryMobileLength(),1));?>;
    var countryMobValidLength = countryMobValidLengthArray[mobCode];
    if(countryMobValidLength == undefined){
        return {min: "0", max: "15"};
    }
    return {min: countryMobValidLength.min, max: countryMobValidLength.max};
}

jQuery(function($) {
    $(document).on('change', '#mob-country', function(){
        $('#mobileContact').val('');
        var countryId = $(this).val();
        $.ajax({
            'url': 'ajax/mob-country-code',
            'data': { 'countryId': countryId },
            'type': 'post',
            'success': function(response){
                var mobCode = response[0].phonecode;
                $('.country-code-field').val(mobCode);
                $('.country-code-field').attr('data-value', mobCode);
            }
        });
    });

    $(document).on('blur', '#mobileContact', function(){
        var array = $('.country-code-field').val();
        var validArray = getValidationArray(array);
        $('#mobileContact').prop('minlength', validArray.min);
        $('#mobileContact').prop('maxlength', validArray.max);

        $('#mobileContact').parent().find('#groupname-error').remove();
        
        var mobileContact = $('#mobileContact').val();
        if(mobileContact.length > 0 && mobileContact.length < validArray.min){
            $('#mobileContact').parent().append('<span id="groupname-error" class="help-inline">Minimum length must be greater than '+validArray.min+'.</span>');
        }
    });
    
    $("#registerForm").validate({ 
        errorElement: 'span',
        errorClass: 'help-inline',
        rules: {
            first_name: { required: true },
            last_name:  { required: true },
            email:  { required: true, email: true },
            password:  { required: true, minlength: 6 },
            terms:  { required: true },
            phone_no: { maxlength: 15 },
            country: {required:true}
        },
        messages:{
            first_name:{
                required: "Please enter your first name."
            },
            last_name:{
                required: "Please enter your last name."
            },
            email:{
                required: "Please enter your Email id.",
                email: "Please enter a valid email address."
            },
            password:{
                required: "Please enter a password.",
                minlength: "Password should be at least 6 characters long."
            },
            terms:{
                required: "Please agree to the terms.",
            },
            phone_no:{
                maxlength: "Contact number cannot have more than 15 digits."
            },
            country:{
                required: "Please select your country."
            }
        }
    });

    //disabling texts for mobile fields
    $(document).on('keypress','.numeric,input[type="number"]', function(evt){
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode == 46) {
            return true;
        }
        
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    });
    
    $('.numeric,input[type="number"]').bind('paste drop',function(e){
        e.preventDefault();
    });
    
    // Opens popup for app download links
    $('#sendMsg2').modal('show');

    $('.country-code-field').focus(function(){
        $('.c_code_err_msg').hide();
    })

});
</script>
{{ Session::forget('userdata') }}
@endsection