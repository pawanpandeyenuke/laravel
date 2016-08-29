<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>FriendzSquare</title>
<link href="{{url('css/bootstrap.css')}}" rel="stylesheet">
<link href="{{url('css/font-awesome.min.css')}}" rel="stylesheet" media="all">
<link href="{{url('css/flat-icon/flaticon.css')}}" rel="stylesheet" media="all">
<link href="{{url('css/style.css')}}" rel="stylesheet">
<link href="{{url('css/responsive.css')}}" rel="stylesheet" media="all">
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
            <div class="col-sm-2">
                <a href="{{url('/')}}" title="" class="logo"><img src="{{url('/images/logo.png')}}" alt="Friendz Square"></a>
            </div>
            <div class="col-sm-7">
                <div class="top-search-cont">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="search-field">
                                {!! Form::open(array('url' => '/searchfriends', 'id' => 'searchform','method' => 'post')) !!}
                                <input type="text" name="searchfriends" id="searchfriends" value="" placeholder="Enter Name" class="form-control">
                                <button type="submit" class="btn btn-primary btn-srch-top search-btn search">Search Friends</button>
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
                    <a href="#" title="" class="btn btn-primary btn-header-right" data-toggle="modal" data-target="#myModal">Suggestions</a>
                              <form id="suggestionform" class="form-horizontal" role="form" method="post" action="{{url('/contactus')}}" >
                            <div class="modal fade send-msg-popup" id="myModal" tabindex="-1" role="dialog" aria-labelledby="sendMsgLabel">
                           
                              <div class="modal-dialog modal-sm" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="sendMsgLabel" style="text-align: center;">Suggestion Box</h4>
                                  </div>
                                  <div class="modal-body">
                                   <div class="row">
                                   <div class='alert alert-success successmsg'  style='text-align: center; display: none;'>Thank you for your feedback!<br><a href='#' class='modalshow'>Have another one?</a></div>
                                    <div class="col-md-10 col-md-offset-1 successmsg">
                                        <div class="profile-select-cont form-group">
                                            <textarea name="message_text" class="form-control message_text" placeholder="Enter suggestion" required></textarea>
                                        </div>
                                        <div class="profile-select-cont form-group">
                                            <input name="email" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" placeholder="Enter email" class="form-control useremail" >
                                        </div>
                                    </div>
                                   </div>
                                    
                                  </div>
                                  <div class="modal-footer">
                                    <input id="submit" name="submit" type="submit" value="Send" class="btn btn-primary">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                           </form>
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

<script type="text/javascript" src="{{url('js/jquery-1.11.3.min.js')}}"></script>
<script type="text/javascript" src="{{url('js/bootstrap.min.js')}}"></script>
</body>
</html>

 