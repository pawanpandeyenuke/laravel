@extends('layouts.dashboard')
<head>
	<meta property="og:url" content="http://fs.yiipro.com/" />
	<meta property="og:type" content="Friendz Square" />
	<meta property="og:title" content="get connected" />
	<meta property="og:description" content="Friendz Square is a social networking site." />
	<meta property="og:image" content="http://fs.yiipro.com/images/post-img-big.jpg" />
</head>

@section('content')

<?php
	$servername = Request::root();
	// echo '<pre>';print_r($servername);die;
?>

<div class="page-data dashboard-body">
	<div class="container">
		<div class="row">
			@include('panels.left')

				<div class="col-sm-6">
					<div class="shadow-box page-center-data no-margin-top">
						<div class="page-title no-left-padding">Invite Contacts</div>
					@if (Session::has('error'))
						<div class="alert alert-danger">{!! Session::get('error') !!}</div>
					@endif
					@if (Session::has('success'))
						<div class="alert alert-success">{!! Session::get('success') !!}</div>
					@endif
						<div class="row">


							<div class="col-md-10 col-md-offset-1">
								{{Form::open()}}
								<div class="b-cast-name">
									<input type="text" name="emails" value="{{ old('emails') }}" class="form-control bcast-field" placeholder="Enter email address">
									<span class="field-info">*Enter multiple Email addresses by separating them with comma.</span>
									<div class="btn-cont text-center">
										<!-- <a href="#" title="" class="btn btn-primary">Invite</a> -->
										<button class="btn btn-primary btn-lg" type="submit">Invite</button>
									</div>
								</div>
								{{Form::close()}}

								<div class="bcast-list social-invite text-center">
									<h5>Choose a service provider to invite your contacts</h5>
									<div class="social-btns">
										<ul class="list-inline">
											<?php $googleurl = isset($googleImportUrl) ? $googleImportUrl : ''; ?>
											<li><a href="<?php echo $googleurl; ?>" title=""><img src="/images/gmail-btn.png" alt=""></li>
											<!-- <li><a href="#" title=""><img src="/images/yahoomail-btn.png" alt=""></li> -->
											<li><a href="#" id="import"><img src="/images/hotmail-btn.png" alt=""></li>
											<!-- <li><a href="#try" onclick="FacebookInviteFriends();"><img src="/images/facebook-btn.png" alt=""></li>
											<li><a href="" title="" onclick="myFunction()"><img src="/images/linkedin-btn.png" alt=""></li> -->
										</ul>
									</div>
								</div>
								<div class="bcast-list social-invite text-center">
									<h5>Choose a service provider to share a post</h5>
									<div class="social-btns">
										<ul class="list-inline"> 
											<li><div class="fb-share-button" data-href="{{$servername}}" data-layout="button_count" data-mobile-iframe="true"></div></li>
											<li>
												<script src="//platform.linkedin.com/in.js" type="text/javascript"> lang: en_US</script>
												<script type="IN/Share" data-url="http://fs.yiipro.com/" data-counter="right"></script>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="shadow-box bottom-ad"><img src="images/bottom-ad.jpg" alt="" class="img-responsive"></div>
				</div>
			@include('panels.right')
		</div>
	</div>
</div>
 
@endsection

<script src="http://connect.facebook.net/en_US/all.js"></script>
<script type="text/javascript" src="/js/jquery-1.11.3.min.js"></script>
<script src="//js.live.net/v5.0/wl.js"></script>
 
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.6";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
 
<script type="text/javascript">
	WL.init({
	    client_id: '0000000044183F60',
	    redirect_uri: 'http://fs.yiipro.com/hotmail/client/callback',
	    scope: ["wl.basic", "wl.contacts_emails"],
	    response_type: "token"
	});

	jQuery( document ).ready(function() {
		//live.com api
		// alert('import');
		jQuery('#import').click(function(e) {
		    e.preventDefault();
		    WL.login({
		        scope: ["wl.basic", "wl.contacts_emails"]
		    }).then(function (response) 
		    {
				WL.api({
		            path: "me/contacts",
		            method: "GET"
		        }).then(
		            function (response) {

	                    //your response data with contacts 
		            	
		            	var people = response.data, emailsObj = [];

		            	$.each(people, function(index, value){
		            		emailsObj.push(value.emails.preferred);		            		
		            	});

		            	if(emailsObj.length > 0){
		            		// alert('have something');
		            		$.ajax({
		            			'url': 'ajax/send-hotmail-invitation',
		            			'type': 'post',
		            			'data': {'emails': emailsObj},
		            			'success': function(response){
		            				if(response == '')
		            					alert('An invitation is sent to all your contacts!');
		            				else
		            					alert(response);
		            			}
		            		});
		            	}
		            	console.log(emailsObj);
		            },
		            function (responseFailed) {
		            	//console.log(responseFailed);
		            }
		        );		        
		    },
		    function (responseFailed) 		    {
		        //console.log("Error signing in: " + responseFailed.error_description);
		    });
		});
	});
</script>
 
<script>
	FB.init({
		appId:'254486034889306',
		cookie:true,
		status:true,
		xfbml:true
	});

	function FacebookInviteFriends()
	{
		FB.ui({
			method: 'apprequests',
			message: 'Welcome to Friendzsquare',
		});
	}
</script>
