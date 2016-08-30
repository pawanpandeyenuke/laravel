<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<link rel="shortcut icon" href="{{ url('images/favicon.png') }}" type="image/x-icon" />
<title>@yield('title', 'FriendzSquare')</title>
<link href="{{url('/css/bootstrap.css')}}" rel="stylesheet">
<link href="{{url('/css/font-awesome.min.css')}}" rel="stylesheet" media="all">
<link href="{{url('/fancybox/jquery.fancybox.css')}}" rel="stylesheet" media="all">
<link href="{{url('/css/flat-icon/flaticon.css')}}" rel="stylesheet" media="all">
<link href="{{url('/css/fileinput.min.css')}}" rel="stylesheet" media="all">
<link href="{{url('/css/select2.min.css')}}" rel="stylesheet" media="all">
<link href="{{url('/css/bootstrap-datepicker.css')}}" rel="stylesheet" media="all">
<link href="{{url('/lib/css/nanoscroller.css')}}" rel="stylesheet">
<link href="{{url('/lib/css/emoji.css')}}" rel="stylesheet">
<link href="{{url('/css/style.css')}}" rel="stylesheet">
<link href="{{url('/css/responsive.css')}}" rel="stylesheet" media="all">

<script type="text/javascript" src="{{url('/js/jquery-1.11.3.min.js')}}"></script>
<script type="text/javascript" src="{{url('/js/readmore.min.js')}}"></script>
<script type="text/javascript" src="{{url('/js/bootstrap.min.js')}}"></script>
<script src="{{url('/js/jquery.validate.js')}}"></script>
<script type="text/javascript" src="{{url('/js/jquery.form.js')}}"></script>
<script type="text/javascript" src="{{url('/fancybox/jquery.fancybox.js')}}"></script>
<script type="text/javascript" src="{{url('/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{url('/js/custom.js')}}"></script>
<script type="text/javascript" src="{{url('/js/bootstrap-filestyle.min.js')}}"></script>
<script type="text/javascript" src="{{url('/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{url('/c-lib/lib/js/emojione.js')}}"></script>
<script src="{{url('/js/select2.min.js')}}"></script>
<!--Emoji libraries-->
<script src="{{url('/lib/js/nanoscroller.min.js')}}"></script>
<script src="{{url('/lib/js/tether.min.js')}}"></script>
<script src="{{url('/lib/js/config.js')}}"></script>
<script src="{{url('/lib/js/util.js')}}"></script>
<script src="{{url('/lib/js/jquery.emojiarea.js')}}"></script>
<script src="{{url('/lib/js/emoji-picker.js')}}"></script>
<script src="{{url('/js/jquery.nicescroll.min.js')}}"></script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-77777490-1', 'auto');
  ga('send', 'pageview');

</script>
</head>
@include('panels.loginpopup')
@include('panels.deletebox')
@include('forums.deleteconfirmbox')

<body class="dashboard">
<div class="page-loading" style="display:none"><img src="{{url('/images/full-loading.gif')}}" alt=""></div>
 <header>
	<div class="container">
		<div class="row header-row-full">
			<div class="col-sm-2 col-xs-12">
				<a href="{{url('dashboard')}}" title="" class="logo"><img src="{{url('/images/logo.png')}}" alt="Friendz Square"></a>
			</div>
			<div class="col-sm-7 col-xs-12 bPadding">
				<div class="top-search-cont">
					<div class="row">
						<div class="col-sm-6 col-xs-12">
							<div class="search-field">
							{!! Form::open(array('url' => '/searchfriends', 'id' => 'searchform','method' => 'post')) !!}
								<input type="text" name="searchfriends" id="searchfriends" value="{{ Request::get('searchfriends')}}" placeholder="Enter Name" class="form-control">
								<button type="submit" class="btn btn-primary btn-srch-top search-btn search" >Search Friends</button>
									{!! Form::close() !!}
							</div>
						</div>
            {!! Form::open(array('url' => 'search-forum', 'id' => 'search-forum-dashboard', 'method' => 'post')) !!}
						<div class="col-sm-6 col-xs-12">
							<div class="search-field">
              <input type = "hidden" name = "mainforum" value = "Forum">
              <input type = "hidden" name = "check" value = "">
								<input type="text" name="forum-keyword" value="{{ Request::get('forum-keyword')}}" placeholder="Enter Keyword" class="form-control forum-keyword-app">
								<button type="submit" class="btn btn-primary btn-srch-top">Search Forum</button>
							</div>
						</div>
            {!! Form::close() !!}
					</div>
				</div>
			</div>
			<div class="col-sm-3 col-xs-12">
				<div class="dashboard-header-menu text-right">
          @if(Auth::check())
					<ul class="list-inline">
						<li class="user-info-top">

							<?php $user = Auth::User(); ?>
              
							<a href="{{url("profile/".Auth::User()->id)}}"><span class="user-thumb" style="background: url('<?php echo userImage($user) ?>');"></span><span class="user-name" title="{{Auth::User()->first_name}}">
							{{Auth::User()->first_name}}</span></a>
						</li>
						<li><div class="logout"><a class = "logout-link" href="{{ url('/logout') }}" title="">Logout</a></div></li>
					</ul>
            @else
            <a href="#" title="" class="btn btn-primary" data-toggle="modal" data-target="#LoginPop">Login</a>
            @endif
				</div>
        <div class="innr_hdr">
          <div id="google_translate_element" name="Select Language"></div>
        </div>
			</div>
		</div>
	</div>


	<!--- Suggestion Popup -->

  <form id="suggestionform1" class="form-horizontal" role="form" method="post" action="{{url('/contactus')}}" data-backdrop="static" data-keyboard="false">
    <div class="modal fade send-msg-popup" id="myModal" tabindex="-1" role="dialog" aria-labelledby="sendMsgLabel">
   
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="sendMsgLabel" style="text-align: center;">Suggestion box</h4>
          </div>
          <div class="modal-body">
           <div class="row">
           <div class='alert alert-success successmsg'  style='text-align: center; display: none;'>Thank you for your feedback!<br><a href='#' class='modalshow'>Have another one?</a></div>
            <div class="col-md-10 col-md-offset-1 successmsg">
                <div class="profile-select-cont form-group">
                    <textarea name="message_text" class="form-control message_text" placeholder="Enter suggestion" ></textarea>
                </div>
                <div class="profile-select-cont form-group">
                    <input name="email" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" placeholder="Enter email" class="form-control useremail" >
                </div>
            </div>
           </div>
            
          </div>
          <div class="modal-footer">
            <input id="submit" name="submit" type="submit" value="Send" class="btn btn-primary suggest">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
   </form>
                           <!-- Suggestion POPUP END-->
</header><!--/header-->
	
@yield('content')

@include('panels.footer')

<?php if(Auth::Check())
{ ?>
<input type="hidden" id="user_id" value="<?php echo Auth::User()->id; ?>">
<?php } ?>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script type="text/javascript">
	
    if(window.location.pathname == "/" || window.location.pathname == "/register"){
        $('.login-footer').hide();
    }else{
        $('.login-footer').show();
    }

    $( "#search-forum-dashboard" ).submit(function( event ) {
      var searchkey = $('.forum-keyword-app').val();
      if(searchkey == ''){
        $('.forum-keyword-app').attr('placeholder', 'Enter Keyword').focus();
        event.preventDefault();
      }
    });

    $("#suggestionform1").validate({ 
        errorElement: 'span',
        errorClass: 'help-inline',
        rules: {
            message_text: { required: true },
            email: {email: true}
        },
        messages:{
            message_text:{
                required: "Please write something to send your suggestion."
            },
            email:{
                email: "Please check your email format."
            }
        }
    });

	$("#suggestionform1").ajaxForm(function(response) {
      if(response == "success")
      {
        $('.modal-title').hide();
        $('.modal-footer').hide();
        $('.successmsg').toggle();  
      }
    });

    $('.modalshow').click(function(){
      $('.modal-title').show();
      $('.modal-footer').show();
      $('.successmsg').toggle();
      $('.message_text').val('');
      $('.useremail').val('');
    });

    function googleTranslateElementInit() {
      new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
    }

</script>

<!-- <script src="{{ url('js/idle-timer.min.js') }}"></script>
<script>
jQuery(function($){
  $( document ).idleTimer( 60000 );
  $( document ).on( "idle.idleTimer", function(event, elem, obj){
    window.location.href = "/";
  }); 
})
</script> -->
{!! Session::forget('error') !!}
{!! Session::forget('success') !!}

</body>
</html>