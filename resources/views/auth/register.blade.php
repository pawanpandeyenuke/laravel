@extends('layouts.app')

@section('content')

<div class="page-data login-page">
	<div class="container">
		<div class="row">
			<div class="col-sm-8">
				<div class="login-banner">
					<img src="images/login-banner.png" alt="" class="img-responsive">
				</div>
				<div class="banner-botom-data text-center">
					<h2>Register yourself to find friends</h2>
					<p> join a group chat room to discuss and share your views and opinions with likeminded people</p>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="login-form registration-form">
					<div class="already-member">Already have Account? <a href="{{ url('/login') }}" title="">Login</a></div>
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
									
									<span class="field-icon flaticon-avatar83"></span>
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

					<div class="or-divider"><span>or</span></div>

					<div class="row field-row">
						<div class="col-md-6 border-right">
							<div class="checkbox-cont">
								<input type="checkbox" name="checkboxG3" id="checkboxG3" class="css-checkbox" />
								<label for="checkboxG3" class="css-label">Keep me logged in</label>
							</div>
						</div>
						<div class="col-md-6">
							<a href="#" title="">Forgot Password?</a>
						</div>
					</div>

					
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
</div><!--/pagedata-->

@endsection
