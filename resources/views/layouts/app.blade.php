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

<script type="text/javascript" src="{{url('/js/jquery-1.11.3.min.js')}}"></script>	
<script type="text/javascript" src="{{url('/js/bootstrap.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="http://malsup.github.com/jquery.form.js"></script> 
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>

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
	<body>
		<header>
	<div class="container">
		<div class="row">
			<div class="col-sm-2">
				<a href="{{url('/')}}" title="" class="logo"><img src="/images/logo.png" alt="Friendz Square"></a>
			</div>
			<div class="col-sm-6">
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
            		{!! Form::open(array('url' => 'search-forum', 'id' => 'search-forum', 'method' => 'post')) !!}
						<div class="col-sm-6">
							<div class="search-field">
              					<input type = "hidden" name = "mainforum" value = "Forum">
              					<input type = "hidden" name = "check" value = "">
								<input type="text" name="forum-keyword" value="" id = "forum-keyword-app" placeholder="Enter Keyword" class="form-control">
								<button type="submit" class="btn btn-primary btn-srch-top">Search Forum</button>
							</div>
						</div>
            		{!! Form::close() !!}
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="header-right-menu">

				<div class="suggestn_blk">
					<a href="#" title="" class="btn btn-primary btn-header-right" data-toggle="modal" data-target="#myModal">Suggestions</a>
				</div>
					<div class="log_out"><div id="google_translate_element" name="Select Language"></div></div>

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
                                            <textarea name="message_text" class="form-control message_text" placeholder="Enter suggestion"></textarea>
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
		
		@yield('content')

		{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script type="text/javascript" >

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
         
    if(response)
    {
    	response = $.trim(response);
    	// console.log(response.length);return;
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

        if(response === "verification"){
            window.location = 'send-verification-link';
        }
        else if(response == "success")
        {
           	var url_c = window.location.pathname;
           	if(url_c == "/newpassword"){
				window.location = "/";
			}
			else if(url_c.indexOf("email-verified") > -1 || url_c == "/send-verification-link"){
				window.location = "/invite-friends";
			}
	  		else{
				window.location = url_c;
			}
        }else{
        	console.log('c');
            var obj = $.parseJSON( response );
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

 
		$( "#searchform" ).submit(function( event ) {
			var searchkey = $('#searchfriends').val();
			if(searchkey == ''){
				$('#searchfriends').attr('placeholder', 'Search here..').focus();
				event.preventDefault();
			}
		});

			$( "#search-forum" ).submit(function( event ) {
			var searchkey = $('#forum-keyword-app').val();
			if(searchkey == ''){
				$('#forum-keyword-app').attr('placeholder', 'Enter Keyword').focus();
				event.preventDefault();
			}
		});

		  $("#suggestionform").validate({ 
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
 
		$("#suggestionform").ajaxForm(function(response) {
			if(response == "success")
			{
				$('.modal-footer').hide();
				$('.successmsg').toggle();
			}
		});

		$('.modalshow').click(function(){
			$('.modal-footer').show();
			$('.successmsg').toggle();
			$('.message_text').val('');
			$('.useremail').val('');
		});

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


		    function googleTranslateElementInit() {
		      new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
		    }
			
		</script>
	</body>
</html>

{!! Session::forget('error') !!}
{!! Session::forget('success') !!}