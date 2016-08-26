<div class="col-sm-3 sidebar-menu-col">
	<button type="button" class="mob-menu-btn">Menu</button>
	<div class="dashboard-sidemenu">
		<div class="panel-group" id="side_acc_menu" role="tablist" aria-multiselectable="true">
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingOne">
		      <h4 class="panel-title">
		        <a href="{{ url('/') }}" class="without-arrow">
		          <i class="flaticon-interface"></i>News/Friends Feed
		        </a>
		      </h4>
		    </div>
		  </div>
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingfour">
		      <h4 class="panel-title">
		        <a class="without-arrow" href="{{ url('friends')}}">
		          <i class="flaticon-people"></i>Friends
		        </a>
		      </h4>
		    </div>
		  </div>
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingSeven">
		      <h4 class="panel-title">
		        <a class="without-arrow" href="{{ url('invite-friends') }}">
		          <i class="flaticon-icon-88206"></i>Invite Friends
		        </a>
		      </h4>
		    </div>
		  </div>
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingThree">
		      <h4 class="panel-title">
		        <a href="{{url('friends-chat')}}" class="without-arrow">
		          <i class="flaticon-balloon"></i>Chat with Friends
		        </a>
		      </h4>
		    </div>
		  </div>
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingTwo">
		      <h4 class="panel-title">
		        <a style="cursor:pointer" href="{{ url('group') }}" class="without-arrow">
		          <i class="flaticon-balloon"></i>Chat Room
		        </a>
		      </h4>
		    </div>
		  </div>
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingSeven">
		      <h4 class="panel-title">
		        <a class="without-arrow" href="{{url('private-group-list')}}">
		          <i class="flaticon-icon-98732"></i>Private Chat
		        </a>
		      </h4>
		    </div>
		  </div>
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingSeven">
		      <h4 class="panel-title">
		        <a class="without-arrow" href="{{ url('broadcast-list')}}">
		          <i class="flaticon-icon-89571"></i>Broadcast
		        </a>
		      </h4>
		    </div>
		  </div>
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingSeven">
		      <h4 class="panel-title">
		        <a class="without-arrow" href="{{ url('/forums') }}">
		          <i class="flaticon-user-profile"></i>Forums
		        </a>
		      </h4>
		    </div>
		  </div>
		 <?php if(Auth::check()){ ?>
			  <div class="panel panel-default">
			    <div class="panel-heading" role="tab" id="headingFive">
			      <h4 class="panel-title">
			      	<?php $uid = Auth::User()->id; ?>
			        <a class="without-arrow" href="{{ url('profile/'.$uid) }}">
			          <i class="flaticon-social"></i>Profile
			        </a>
			      </h4>
			    </div>
			  </div>
			   <div class="panel panel-default">
			    <div class="panel-heading" role="tab" id="headingSix">
			      <h4 class="panel-title">
			        <a href="{{ url('change-password') }}" class="without-arrow">
			          <i class="flaticon-tool"></i>Change Password
			        </a>
			      </h4>
			    </div>
			  </div>
		<?php } ?>
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingSix">
		      <h4 class="panel-title">
		        <a href="{{ url('settings/privacy') }}" class="without-arrow">
		          <i class="flaticon-tool"></i>Privacy Settings
		        </a>
		      </h4>
		    </div>
		  </div>
		</div>
	</div><!--/dashboard sidemenu-->
</div>