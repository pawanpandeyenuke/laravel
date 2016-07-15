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
	        
	        <h4 class="modal-title" id="sendMsgLabel">
	        	<div class="ap-btns text-center">
					<ul class="list-inline">
						
						<li><h5><b>Download This App</b></h5>
						
						@if($Android)
							<a href="#" title=""><img src="{{url('images/and-btn.png')}}" alt=""></a>
						@endif

						@if($iPhone)
							<a href="{{ url('https://itunes.apple.com/us/app/friendzsquare/id1076919346?ls=1&mt=8') }}" title=""><img src="{{url('images/ios-btn.png')}}" alt=""></a>
						@endif
						<h5><b>OR <br/><br/> <a href="{{url('/')}}" title="" class="close" data-dismiss="modal" aria-label="Close">Continue to web...</a></b></h5></li>
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