@extends('layouts.dashboard')

@section('content')
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
											<li><a href="{{isset($googleImportUrl)}}" title=""><img src="/images/gmail-btn.png" alt=""></li>
											<li><a href="#" title=""><img src="/images/yahoomail-btn.png" alt=""></li>
											<li><a href="#" id="import"><img src="/images/hotmail-btn.png" alt=""></li>
											<li><a href="#try" onclick="FacebookInviteFriends();"><img src="/images/facebook-btn.png" alt=""></li>
											<li><a href="#" title=""><img src="/images/linkedin-btn.png" alt=""></li>
										</ul>
									</div>
								</div>
								<!-- <a href="#" id="import">Import contacts</a> -->
							</div>
						</div>
<table class="table table-striped" id="table-contacts">
</table>
					</div><!--/page center data-->
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
<script type="text/javascript">
	WL.init({
	    client_id: '0000000044183F60',
	    redirect_uri: 'http://development.laravel.com/hotmail/client/callback',
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
		            	console.log(response.data);
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
