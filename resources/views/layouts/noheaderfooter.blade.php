<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<link rel="shortcut icon" href="{{ url('images/favicon.png') }}" type="image/x-icon" />
<title>@yield('title', 'FriendzSquare')</title>
<link href="{{url('/css/bootstrap.css')}}" rel="stylesheet">
<link href="{{url('/css/style.css')}}" rel="stylesheet">
<link href="{{url('/css/responsive.css')}}" rel="stylesheet" media="all">
@include('panels.google-analytics')
</head>
<body>
@yield('content')
</body>
</html>