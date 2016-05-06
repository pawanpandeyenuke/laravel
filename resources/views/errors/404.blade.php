<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Friendz Square</title>
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/font-awesome.min.css" rel="stylesheet" media="all">
<link href="css/flat-icon/flaticon.css" rel="stylesheet" media="all">
<link href="css/style.css" rel="stylesheet">
<link href="css/responsive.css" rel="stylesheet" media="all">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
<header>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <a href="{{url('/')}}" title="" class="logo"><img src="{{url('images/logo.png')}}" alt="Friendz Square"></a>
            </div>
            <div class="col-sm-6">
                <div class="top-search">
                    <ul class="clearfix">
                        <li class="search-textbox">
                            <input type="text" class="search-field" placeholder="Search Friends">
                        </li>
                        <li>
                            <select class="search-field">
                                <option>Country</option>
                            </select>
                        </li>
                        <li>
                            <select class="search-field">
                                <option>State</option>
                            </select>
                        </li>
                        <li>
                            <select class="search-field">
                                <option>City</option>
                            </select>
                        </li>
                        <li class="search-btn-cont">
                            <button type="button" class="search-btn"><i class="glyph-icon flaticon-magnifyingglass138"></i></button>
                        </li>
                    </ul>
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
<div class="page-data error-page">
    <div class="container">
        <div class="error-data-outer">
            <div class="row">
                <div class="col-sm-8 col-md-offset-2">
                    <div class="row">
                        <div class="col-md-5">
                            <img src="{{url('images/404.png')}}" class="img-responsive">
                        </div>
                        <div class="col-md-7">
                            <div class="error-data text-center">
                                <h3>SORRY<br> PAGE NOT FOUND</h3>
                                <p>Page doesn't exist or some other error occured. Go to our <a href="{{url('/')}}" title="">Home Page</a> or go back to <a href="{{url('/')}}" title="">Previous Page</a></p>
                            </div>
                        </div>
                    </div>
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

<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>

 