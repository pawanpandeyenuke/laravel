@extends('layouts.app')

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
								<input type="checkbox" name="checkboxG3" id="checkboxG3" class="css-checkbox">
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

<div class="page-data login-page">
	<div class="container">
		<div class="row">
			<div class="col-sm-8">
				<div class="home-slider-cont">
					<div class="slider-btns">
						<ul class="list-inline">
							<li><a href="#" title="" class="btn btn-primary" data-toggle="modal" data-target="#LoginPop">Enter Chat Room</a></li>
							<li><a href="#" title="" class="btn btn-primary">Enter Forum</a></li>
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
								<li><a href="#" title="" class=""><img src="images/apple-stroe-btn.png" alt=""></a></li>
								<li><a href="#" title="" class=""><img src="images/android-store-btn.png" alt=""></a></li>
							</ul>
						</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="login-form registration-form">
					<div class="already-member">Already have Account? <a href="#" title="" data-toggle="modal" data-target="#LoginPop">Login</a></div>
					<h3 class="text-center">Registration</h3>

					  <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                        {!! csrf_field() !!}

					<div class="row field-row">
						<div class="col-sm-12">

							<div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
								<input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control icon-field" placeholder="First Name">
									
									@if ($errors->has('first_name'))
										<span class="help-block">
											<strong>{{ $errors->first('first_name') }}</strong>
										</span>
									@endif
									
									<span class="field-icon flaticon-profile5"></span>
								</div>

							<div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
									<input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control icon-field" placeholder="Last Name">
									
									@if ($errors->has('last_name'))
										<span class="help-block">
											<strong>{{ $errors->first('last_name') }}</strong>
										</span>
									@endif
									
									<span class="field-icon flaticon-profile5"></span>
								</div>


								<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
									<input type="text" name="email" value="{{ old('email') }}" class="form-control icon-field" placeholder="Email ID">
									
									@if ($errors->has('email'))
										<span class="help-block">
											<strong>{{ $errors->first('email') }}</strong>
										</span>
									@endif
									
									<span class="field-icon flaticon-letter133"></span>
								</div>

							<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
									<input type="password" name="password" class="form-control icon-field" placeholder="Password" id="showpassword">
									
									@if ($errors->has('password'))
										<span class="help-block">
											<strong>{{ $errors->first('password') }}</strong>
										</span>
									@endif
									
									<span class="field-icon flaticon-padlock50"></span>
									<div class="check-cont show-pw">
										<input type="checkbox"  name="checkboxG2" id="checkboxG2" class="css-checkbox password-eye"/>
										<label for="checkboxG2" class="css-label">show</label>
									</div>
								</div>

							<div class="form-group">
								<input type="text" class="form-control icon-field" name = "phone_no" placeholder="Mobile">
								<span class="field-icon flaticon-smartphone-with-blank-screen"></span>
							</div>

							<div class="form-group sex-option">
									<ul>
										<li>I am</li>
										<li>
											<div class="small-radio-cont">
												<input type="radio" name="gender" value="Male" id="radio1" class="css-checkbox" />
												<label for="radio1" class="css-label radGroup1">Male</label>
											</div>
										</li>
										<li>
											<div class="small-radio-cont">
												<input type="radio" name="gender" value="Female" id="radio2" class="css-checkbox" />
												<label for="radio2" class="css-label radGroup1">Female</label>
											</div>
										</li>
									</ul>
								</div>

								<div class="form-groups">
									<div class="btn-cont text-center">
										<input type="submit" class="btn btn-primary" value="Get Started">
									</div>
								</div>
						</div>
					</div>

                    </form>

					<div class="or-divider"><span>Or</span></div>

					
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
	<div class="page-footer">
		<div class="text-center">
			<ul>
				<li><a href="#" title="">Terms Privacy</a></li>
				<li><a href="#" title="">&copy; 2015 friendzsquare</a></li>
			</ul>
		</div>
	</div>
</div><!--/pagedata-->

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