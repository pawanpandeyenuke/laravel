<div class="col-sm-3">
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
		    <div class="panel-heading" role="tab" id="headingThree">
		      <h4 class="panel-title">
		        <a href="/groupchat" class="without-arrow">
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
		    <div class="panel-heading" role="tab" id="headingfour">
		      <h4 class="panel-title">
		        <a class="without-arrow" href="{{ url('requests')}}">
		          <i class="flaticon-people"></i>Friends
		        </a>
		      </h4>
		    </div>
		  </div>
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingFive">
		      <h4 class="panel-title">
		      	<?php $uid = Auth::User()->id; ?>
		        <a class="without-arrow" href="{{ url("profile/$uid") }}">
		          <i class="flaticon-social"></i>Profile
		        </a>
		      </h4>
		    </div>
		  </div>
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingSix">
		      <h4 class="panel-title">
		        <a href="{{ url('settings/privacy') }}" class="without-arrow">
		          <i class="flaticon-tool"></i>Privacy Setting
		        </a>
		      </h4>
		    </div>
		  </div>
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingSeven">
		      <h4 class="panel-title">
		        <a class="without-arrow" href="{{ url('broadcast-list')}}">
		          <i class="flaticon-balloon"></i>Broadcast
		        </a>
		      </h4>
		    </div>
		  </div>
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingSeven">
		      <h4 class="panel-title">
		        <a class="without-arrow" href="{{url('private-group-list')}}">
		          <i class="flaticon-balloon"></i>Private Chat
		        </a>
		      </h4>
		    </div>
		  </div>
		</div>
	</div><!--/dashboard sidemenu-->
</div>