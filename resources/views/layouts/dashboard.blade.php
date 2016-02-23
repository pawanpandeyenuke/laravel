<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Friendz Square</title>
<link href="/css/bootstrap.css" rel="stylesheet">
<link href="/css/font-awesome.min.css" rel="stylesheet" media="all">
<link href="/css/flat-icon/flaticon.css" rel="stylesheet" media="all">
<link href="/css/fileinput.min.css" rel="stylesheet" media="all">
<link href="/css/style.css" rel="stylesheet">
<link href="/css/responsive.css" rel="stylesheet" media="all">
<script type="text/javascript" src="/js/jquery-1.11.3.min.js"></script>
</head>

<body class="dashboard">
<header>
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				<a href="{{ url('/') }}" title="" class="logo"><img src="images/logo.png" alt="Friendz Square"></a>
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
									{!! Form::select('state', [], null, array(
										'class' => 'search-field',
										'id' => 'state',
									)); !!}
								</li>
								<li>
									{!! Form::select('city', [], null, array(
										'class' => 'search-field',
										'id' => 'city',
									)); !!}
								</li>
								<li class="search-btn-cont">
									<button type="button" class="search-btn"><i class="glyph-icon flaticon-magnifyingglass138"></i></button>
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
				<div class="dashboard-header-menu text-right">
					<ul class="list-inline">
						<li class="user-info-top">
							<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
							{{Auth::User()->first_name}}
						</li>
						<li><div class="logout"><a href="{{ url('/logout') }}" title="">Logout</a></div></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</header><!--/header-->
	
	@yield('content')

<script src="http://malsup.github.com/jquery.form.js"></script> 
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/fileinput.min.js"></script>
<script type="text/javascript" src="/js/custom.js"></script>
</body>
</html>
