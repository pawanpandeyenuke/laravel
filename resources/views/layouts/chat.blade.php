<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Friendz Square</title>
<link href="{{url('/css/bootstrap.css')}}" rel="stylesheet">
<link href="{{url('/css/font-awesome.min.css')}}" rel="stylesheet" media="all">
<link href="{{url('/fancybox/jquery.fancybox.css')}}" rel="stylesheet" media="all">
<link href="{{url('/css/flat-icon/flaticon.css')}}" rel="stylesheet" media="all">
<link href="{{url('/css/fileinput.min.css')}}" rel="stylesheet" media="all">

<link href="{{url('/css/bootstrap-datepicker.css')}}" rel="stylesheet" media="all">
<!-- <link href="/converse/converse.min.css" rel="stylesheet" type="text/css" media="screen" > -->

<link href="{{url('/lib/css/nanoscroller.css')}}" rel="stylesheet">
<link href="{{url('/lib/css/emoji.css')}}" rel="stylesheet">
<link href="{{url('/css/style.css')}}" rel="stylesheet">
<link href="{{url('/css/responsive.css')}}" rel="stylesheet" media="all">

<!-- <script type="text/javascript" src="/js/jquery-1.11.3.min.js"></script> -->
</head>

<body class="dashboard">
 <header>
	<div class="container">
		<div class="row">
			<div class="col-sm-2">
				<a href="/dashboard" title="" class="logo"><img src="/images/logo.png" alt="Friendz Square"></a>
			</div>
			<div class="col-sm-7">
				<div class="top-search-cont">
					<div class="row">
						<div class="col-sm-6">
							<div class="search-field">
							{!! Form::open(array('url' => '/searchfriends', 'id' => 'searchform','method' => 'post')) !!}
								<input type="text" name="searchfriends" id="searchfriends" value="" placeholder="Enter Name" class="form-control">
								<button type="submit" class="btn btn-primary btn-srch-top search-btn search" disabled>Search Friends</button>
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
				<div class="dashboard-header-menu text-right">
					<ul class="list-inline">
						<li class="user-info-top">
							<?php $user_picture = !empty(Auth::User()->picture) ? Auth::User()->picture : '/images/user-icon.png'; ?>
							<a href="{{url("profile/".Auth::User()->id)}}"><span class="user-thumb" style="background: url('{{$user_picture}}');"></span>
							{{Auth::User()->first_name}}</a>
						</li>
						<li><div class="logout"><a href="{{ url('/logout') }}" title="">Logout</a></div></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</header><!--/header-->
	
	@yield('content')

<input type="hidden" id="user_id" value="<?php echo Auth::User()->id; ?>">
<script type="text/javascript">
$(document).on('keyup','#searchfriends',function(){
			
				if($('#searchfriends').val() != "")
					$('.search').prop('disabled',false);
				else
					$('.search').prop('disabled',true);		
				});
</script>
</body>
</html>


{!! Session::forget('error') !!}
{!! Session::forget('success') !!}