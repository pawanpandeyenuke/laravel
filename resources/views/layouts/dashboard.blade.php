<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title')Friendz Square</title>
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
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>


<script src="http://malsup.github.com/jquery.form.js"></script> 
<script type="text/javascript" src="{{url('/fancybox/jquery.fancybox.js')}}"></script>
<script type="text/javascript" src="{{url('/js/bootstrap.min.js')}}"></script>
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
<body class="dashboard">
<div class="page-loading" style="display:none"><img src="{{url('/images/full-loading.gif')}}" alt=""></div>
 <header>
	<div class="container">
		<div class="row header-row-full">
			<div class="col-sm-2">
				<a href="/dashboard" title="" class="logo"><img src="{{url('/images/logo.png')}}" alt="Friendz Square"></a>
			</div>
			<div class="col-sm-7">
				<div class="top-search-cont">
					<div class="row">
						<div class="col-sm-6">
							<div class="search-field">
							{!! Form::open(array('url' => '/searchfriends', 'id' => 'searchform','method' => 'post')) !!}
								<input type="text" name="searchfriends" id="searchfriends" value="" placeholder="Enter Name" class="form-control">
								<button type="submit" class="btn btn-primary btn-srch-top search-btn search" >Search Friends</button>
									{!! Form::close() !!}
							</div>
						</div>
            {!! Form::open(array('url' => 'search-forum', 'id' => 'search-forum-dashboard', 'method' => 'post')) !!}
						<div class="col-sm-6">
							<div class="search-field">
              <input type = "hidden" name = "mainforum" value = "Forum">
              <input type = "hidden" name = "check" value = "">
								<input type="text" name="forum-keyword" value="" placeholder="Enter Keyword" class="form-control forum-keyword-app">
								<button type="submit" class="btn btn-primary btn-srch-top">Search Forum</button>
							</div>
						</div>
            {!! Form::close() !!}
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="dashboard-header-menu text-right">
          @if(Auth::check())
					<ul class="list-inline">
						<li class="user-info-top">
							<?php $user_picture = !empty(Auth::User()->picture) ? Auth::User()->picture : '/images/user-icon.png';
							 ?>
							<a href="{{url("profile/".Auth::User()->id)}}"><span class="user-thumb" style="background: url('{{$user_picture}}');"></span>
							{{Auth::User()->first_name}}</a>
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

<div class="modal fade" id="forum-confirm-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
<?php if(Auth::Check())
{ ?>
<input type="hidden" id="user_id" value="<?php echo Auth::User()->id; ?>">
<?php } ?>
</body>
</html>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script type="text/javascript">
	
   $("#loginform").submit(function(event){
              $('.login').text('Please Wait..');
              $('.login').prop('disabled',true);
    });

    if(window.location.pathname == "/" || window.location.pathname == "/register"){
        $('.login-footer').hide();
    }else{
        $('.login-footer').show();
    }
    
    $("#loginform").ajaxForm(function(response) { 
         
    if(response){
            $('.password').next('.help-block').find('.verifymsg').hide();
        
        if(response === "These credentials do not match our records.")
        {
            var current = $('.password');
            current.next('.help-block').find('.verifymsg').hide();
            current.css('border-color','#a94442');
            current.next('.help-block').find('.errormsg').text(response).css('color','#a94442');
            $('.emailid').css('border-color','#a94442');
            $('.emailid').next('.help-block').find('.errormsg').text("").css('color','#333333');
            $('.login').text('Login');
            $('.login').prop('disabled',false);

        }

        if(response === "verification")
            window.location = 'send-verification-link';

        else if(response == "success"){
            var url_c = window.location.pathname;
           if(url_c == "/newpassword")
    window.location = "/";
    else
    window.location = url_c;
        }else{
            var obj = jQuery.parseJSON( response );
            if( obj.email != null ){

                var current = $('.emailid');
                current.next('.help-block').find('.verifymsg').hide();
                current.css('border-color','#a94442');
                current.next('.help-block').find('.errormsg').text(obj.email).css('color','#a94442');

                if( obj.password == null ){
                    $('.password').next('.help-block').find('.errormsg').text("").css('color','#333333');
                    current.next('.help-block').find('.verifymsg').hide();
                    $('.password').css('border-color','#333333');
                }
            }
            if( obj.password != null ){     
                var current = $('.password');
                current.next('.help-block').find('.verifymsg').hide();              
                current.css('border-color','#a94442');
                current.next('.help-block').find('.errormsg').text(obj.password).css('color','#a94442');

                if( obj.email == null ){
                    $('.emailid').next('.help-block').find('.errormsg').text("").css('color','#333333');
                    current.next('.help-block').find('.verifymsg').hide();
                    $('.emailid').css('border-color','#333333');
                }
            }
                 $('.login').text('Login');
                 $('.login').prop('disabled',false);
        }
    
    }
    
});

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
      new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
    }

</script>


{!! Session::forget('error') !!}
{!! Session::forget('success') !!}
