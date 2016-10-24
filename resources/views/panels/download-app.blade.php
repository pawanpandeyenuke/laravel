<?php
	//Detect special conditions devices
	$iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
	$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
	$iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
	$Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
	$webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");

	//print_r(time() + (20 * 60));die();	

	if( ($iPod || $iPhone || $iPad || $Android ||  $webOS) and !($value = isset($_COOKIE['cookie_name']) ? $_COOKIE['cookie_name'] : false)){

		if(!$value){
			setcookie('cookie_name', 'cookie_value', time() + (20 * 60), "/"); // 86400 = 1 day	
		}

	?>
	<div class="modal fade send-msg-popup" id="sendMsg2" tabindex="-1" role="dialog" aria-labelledby="sendMsgLabel">
	<form class="form-horizontal" role="form" method="post" action="/contactus" id="suggestion_form">
	  <div class="modal-dialog modal-sm" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="sendMsgLabel">
	        	<div class="ap-btns text-center">
					<ul class="list-unstyled">
						
						<li><h5><b>Download This App</b></h5></li>
						
						@if($Android)
							<li><a href="{{ Config::get('constants.android_app_link') }}" title=""><img src="{{url('images/and-btn.png')}}" alt="FriendzSquare Android App"></a></li>
						@endif

						@if($iPhone)
							<li><a href="{{ Config::get('constants.ios_app_link') }}" title=""><img src="{{url('images/ios-btn.png')}}" alt="FriendzSquare Apple App"></a></li>
						@endif
						<li><h5><b>OR <br/><br/></b></h5></li> 
						<li><a href="{{url('/')}}" title="">Continue to web...</a></li>
					</ul>
				</div>
			</h4>
	      </div>							      
	    </div>
	  </div>
	</div>
	</form>
</div>
<?php }  ?>