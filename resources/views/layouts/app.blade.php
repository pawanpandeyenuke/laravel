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
			<div class="col-sm-2">
				<a href="{{url('/')}}" title="" class="logo"><img src="/images/logo.png" alt="Friendz Square"></a>
			</div>
			<div class="col-sm-7">
				<div class="top-search-cont">
					<div class="row">
						<div class="col-sm-6">
							<div class="search-field">
								{!! Form::open(array('url' => '/searchfriends', 'id' => 'searchform','method' => 'post')) !!}
								<input type="text" name="searchfriends" value="" placeholder="Enter Name" class="form-control">
								<button type="submit" class="btn btn-primary btn-srch-top search-btn">Search Friends</button>
									{!! Form::close() !!}
							</div>
						</div>
						<div class="col-sm-6">
							<div class="search-field">
								<input type="text" name="" value="" placeholder="Enter Keyword" class="form-control">
								<button type="button" class="btn btn-primary btn-srch-top">Search Forum</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="header-right-menu text-right">
					<a href="#" title="" class="btn btn-primary btn-header-right">Suggestions</a>
				</div>
			</div>
		</div>
	</div>
</header><!--/header-->
		
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
