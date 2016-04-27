<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Friendz Square</title>
<link href="{{url('/css/bootstrap.css')}}" rel="stylesheet">
<link href="{{url('/css/font-awesome.min.css')}}" rel="stylesheet" media="all">
<link href="{{url('/css/flat-icon/flaticon.css')}}" rel="stylesheet" media="all">
<link href="{{url('/css/style.css')}}" rel="stylesheet">
<link href="{{url('/css/responsive.css')}}" rel="stylesheet" media="all">

</head>
	<body>
		<header>
			<div class="container">
				<div class="row">
					<div class="col-sm-3">
						<a href="{{ url('/dashboard') }}" title="" class="logo"><img src="{{url('/images/logo.png')}}" alt="Friendz Square"></a>
					</div>
					<div class="col-sm-6">
						<div class="top-search">
						{!! Form::open(array('url' => 'dashboard', 'id' => 'searchform')) !!}
							<ul class="clearfix">
								<li class="search-textbox">
									<!-- <input type="text" class="search-field" placeholder="Search Friends"> -->
									{!! Form::text('searchfriends', null, array(
										'class'=>'search-field', 
										'id'=>'searchfriends',
										'placeholder'=>'Search Friends'
									)) !!}
								</li>
								<li>
									{!! Form::select('country', $countries, null, array(
										'class' => 'search-field',
										'id' => 'country',
									)); !!}
								</li>
								<li>
									{!! Form::select('state', ['State'], null, array(
										'class' => 'search-field',
										'id' => 'state',
									)); !!}
								</li>
								<li>
									{!! Form::select('city', ['City'], null, array(
										'class' => 'search-field',
										'id' => 'city',
									)); !!}
								</li>
								<li class="search-btn-cont">
									<button type="button" class="search-btn"><i class="flaticon-magnifying-glass138"></i></button>
									<!-- {!! Form::button('', array(
										'class' => 'search-btn',
										'id' => '',
									)) !!}	 -->								
								</li>
							</ul>
							{!! Form::close() !!}
						</div>
					</div>
					<div class="col-sm-3">
						@if (Auth::guest())
							<div class="header-right-menu text-right">
								<!--<a href="#" title="" class="btn btn-primary btn-header-right">Suggestions</a> -->
								<a href="{{ url('/login') }}" title="" class="btn btn-primary btn-header-right">Login</a>
							</div>
						@else						
							<ul style="list-style:none;margin:15%;">
								<li style="float:left;"><a href="{{ url('/home') }}">Home</a></li>
								<li style="float:right;"><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
							</ul>
						@endif
					</div>
				</div>
			</div>
		</header>
		
		@yield('content')
		<script type="text/javascript" src="{{url('/js/jquery-1.11.3.min.js')}}"></script>	
		<script type="text/javascript" src="{{url('/js/bootstrap.min.js')}}"></script>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
		<script type="text/javascript" >
			$('.password-eye').change(function(){
				 if($('.password-eye').is(':checked')) 
				 	  $('#showpassword').prop('type', 'text');
				 else
				 	  $('#showpassword').prop('type', 'password');
				 $("#showpassword").focus();
				 $('#showpassword').val($('#showpassword').val() + ' ');
				 $('#showpassword').val($.trim($('#showpassword').val()));
			});

			$('#country').change(function(){
				var countryId = $(this).val();
				var _token = $('#searchform input[name=_token]').val();
				$.ajax({			
					'url' : '/ajax/getstates',
					'data' : { 'countryId' : countryId, '_token' : _token },
					'type' : 'post',
					'success' : function(response){				
						$('#state').html(response);
					}			
				});	
			});


			/**
			*	Get cities ajax call handling.
			*	Ajaxcontroller@getCities
			*/
			$('#state').change(function(){
				var stateId = $(this).val();
				var _token = $('#searchform input[name=_token]').val();
				$.ajax({			
					'url' : '/ajax/getcities',
					'data' : { 'stateId' : stateId, '_token' : _token },
					'type' : 'post',
					'success' : function(response){
						$('#city').html(response);
					}			
				});	
			});
			
		</script>
	</body>
</html>
