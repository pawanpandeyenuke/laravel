@extends('layouts.app')

@section('content')

<div class="page-data login-page">
	<div class="container">
		<div class="row">
			<div class="col-sm-8">
				<div class="login-banner">
					<img src="/images/login-banner.png" alt="" class="img-responsive">
				</div>
				<div class="banner-botom-data text-center">
					<h2>Register yourself to find friends</h2>
					<p> join a group chat room to discuss and share your views and opinions with likeminded people</p>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="login-form">
					<h3 class="text-center">Login with Accounts</h3>
					
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {!! csrf_field() !!}
                        
						<div class="row field-row">
							<div class="col-sm-12">
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
							</div>
						</div>

						<div class="row field-row">
							<div class="col-md-6 border-right">
								<div class="checkbox-cont">
									<input type="checkbox" name="checkboxG3" id="checkboxG3" class="css-checkbox" />
									<label for="checkboxG3" class="css-label">Keep me logged in</label>
								</div>
							</div>
							<div class="col-md-6">
								<a class="fg-pw-link" href="{{ url('password/reset') }}" title="">Forgot Password?</a>
							</div>
						</div>

						<div class="row field-row">
							<div class="col-md-6">
								<div class="btn-cont text-center">
									<input type="submit" class="btn btn-primary" value="Login">
									<!-- <a href="#" title="" class="btn btn-primary">Login</a> -->
								</div>
							</div>
							<div class="col-md-6">
								<div class="btn-cont text-center">
									<a href="{{ url('/register') }}" title="" class="btn btn-primary">Signup</a>
								</div>
							</div>
						</div>
						
                    </form>
                    
					<div class="or-divider"><span>or</span></div>
					<div class="social-login">
						<ul>
							<li><a href="{{ url('redirect/facebook') }}" class="fb"><i class="fa fa-facebook"></i></a></li>
							<li><a href="{{ url('redirect/twitter') }}" class="tw"><i class="fa fa-twitter"></i></a></li>
							<li><a href="{{ url('redirect/google') }}" class="gp"><i class="fa fa-google-plus"></i></a></li>
							<li><a href="{{ url('redirect/linkedin') }}" class="lin"><i class="fa fa-linkedin"></i></a></li>
						</ul>

						<div class="social-store">
							<ul>
								<li><a href=""><img src="{{url('/images/apple-stroe-btn.png')}}" alt=""></a></li>
								<li><a href=""><img src="{{url('/images/android-store-btn.png')}}" alt=""></a></li>
							</ul>
						</div>
					</div><!--/social login-->
				</div>
			</div>
		</div>
	</div>
	<div class="page-footer">
		<div class="text-center">
			<ul>
				<li><a href="#" title="">About</a></li>
				<li><a href="#" title="">Terms Privacy</a></li>
				<li><a href="#" title="">&copy; 2015 friendzsquare</a></li>
			</ul>
		</div>
	</div>
</div><!--/pagedata-->
 
@endsection
