@extends('layouts.dashboard')

@section('content')

<style type="text/css">
	.no-more-results {
		text-align: center;
		cursor: pointer;
		background: #A0F0E6;
		padding: 10px 0;
		border: 1px solid #4DE2D0;
		border-radius: 4px;
		margin: 10px 0 0;
		font-weight: 500;
	}
</style>

<div class="page-data dashboard-body">
		<div class="container">
			<div class="row">

			@include('panels.left')

			<div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="page-title">
						<i class="flaticon-people"></i>Friends Request
					</div>

						<div class="tab-style-no-border">

						  <!-- Nav tabs -->
						  <ul role="tablist" class="nav nav-tabs">
						    <li class="active" role="presentation">
						    	<a class="friendstabs" data-reqtype="all" data-name="All" data-toggle="tab" role="tab" aria-controls="All" href="#All">All Users</a>
						    </li>
						    <li role="presentation">
						    	<a class="friendstabs" data-reqtype="sent" data-name="Sent" data-toggle="tab" role="tab" aria-controls="Send" href="#Send">Sent</a>
						    </li>
						    <li role="presentation">
						    	<a class="friendstabs" data-reqtype="recieved" data-name="Recieved" data-toggle="tab" role="tab" aria-controls="Recieved" href="#Recieved">Recieved</a>
						    </li>
						    <li role="presentation">
						    	<a class="friendstabs" data-reqtype="current" data-name="Current" data-toggle="tab" role="tab" aria-controls="Current" href="#Current">Friends</a>
						    </li>
						  </ul>

						  <!-- Tab panes -->
						  	<div class="row">
						  		<div class="col-md-10 col-md-offset-1">
						  			<div class="tab-content friends-list">
							  			<div id="All" class="tab-pane active" data-value="all" data-pageid="2" role="tabpanel">

							  <div class="page-title req-search">
							<div class="search-box">
								<input type="text" placeholder="Search" class="form-control searchtabtext">
								<button data-reqtype="all" class="search-btn-small" type="button"><i class="glyph-icon flaticon-magnifyingglass138"></i></button>

							</div>
						</div>
							<ul class="counting">
														
@foreach($model1 as $data) 
	<?php 
	// print_r();die;
	$id1=Auth::User()->id;
			$name = $data['first_name'].' '.$data['last_name'];
		
	?>
	<li  class="get_id" data-userid="{{$data['id']}}" data-friendid="{{$id1}}">
		<div class="row">
			<div class="col-sm-6">
				<div class="user-cont">
					<a title="" href="profile/{{$data['id']}}">
						<span style="background: url('images/user-thumb.jpg');" class="user-thumb"></span>
					{{ $name }}
					</a>
				</div>
			</div>
			<div class="col-sm-6">
			<?php 
$status1=DB::table('friends')->where('user_id',$data['id'])->where('friend_id',Auth::User()->id)->value('status');
$status2=DB::table('friends')->where('friend_id',$data['id'])->where('user_id',Auth::User()->id)->value('status');


				if($status1 == 'Pending'){
			?>
				<div class="row">
					<div class="col-sm-6">
						<button class="btn btn-primary btn-full accept abc" type="button" id="accept" >Accept</button>
					</div>
					<div class="col-sm-6">
						<button class="btn btn-default btn-full abc decline" type="button"  id="decline">Decline</button>
					</div>

					<span class="btn btn-default btn-full msg" disabled="disabled" id='msg' style="display: none;">Request Rejected</span>

					<span class="btn btn-default btn-full msg2" disabled="disabled" id='msg2' style="display: none;">Friend Removed</span>

					<div class="text-right">

					<button class="btn btn-default btn-full remove abc" type="button" id="remove" style="display: none;">Remove</button>
				</div>
				</div>
			<?php }elseif($status2 == 'Pending'){ 
			?>
				<div class="text-right">
					<button class="btn btn-primary btn-full" type="button" id="sent">Sent Request</button>
				</div>
			<?php }elseif($status1=='Accepted' || $status2=='Accepted'){ 
			?>
				<div class="text-right">
					<button class="btn btn-default btn-full remove abc" type="button" id="remove">Remove</button>
				</div>
				<?php }elseif($status2=='Rejected'){ 
					?>
					<div class="text-right">
						<button type="button" class="btn btn-primary btn-full resend" disabled="disabled" id='resend'>Re-Send</button>
					</div>
					<div class="text-right">
					<button class="btn btn-primary btn-full sent" type="button" id="sent"style="display: none;">Sent Request</button>
				</div>

					<?php }elseif($status1=='Rejected'||($status1==null)&&($status2==null)){

					?>	
					<div class="text-right">
						<button type="button" class="btn btn-primary btn-full invite" id='invite'>Add as a friend</button>
					</div>
					<div class="text-right">
					<button class="btn btn-primary btn-full sent" type="button" id="sent"style="display: none;">Sent Request</button>
					</div>


				<?php } ?>
			</div>
		</div>
	</li>
	@endforeach


												</ul>
	
										    	<div class="load-btn load-more-friend">
											    	<span class="loading-text">View more</span>
											    	<span class="loading-img" style="display: none"><img src="/images/loading.gif" alt=""></span>
											    </div>

							  			</div>
					    				<div id="Send" class="tab-pane" data-value="sent" data-pageid="2" role="tabpanel">
					    				 <div class="page-title req-search">
							  <div class="search-box">
							<input type="text" placeholder="Search" class="form-control searchtabtext">
							<button data-reqtype="sent" class="search-btn-small" type="button"><i class="glyph-icon flaticon-magnifyingglass138"></i></button>
						</div>
						</div>
											<ul>

											</ul>
									    	<div class="load-btn load-more-friend">
										    	<span class="loading-text">View more</span>
										    	<span class="loading-img" style="display: none"><img src="/images/loading.gif" alt=""></span>
										    </div>
					    				</div>
									    <div id="Recieved" class="tab-pane" data-value="recieved" data-pageid="2" role="tabpanel">
									    <div class="page-title req-search">
							  <div class="search-box">
							<input type="text" placeholder="Search" class="form-control searchtabtext">
							<button data-reqtype="recieved" class="search-btn-small" type="button"><i class="glyph-icon flaticon-magnifyingglass138"></i></button>
						</div>
						</div>
											<ul>

											</ul>
									    	<div class="load-btn load-more-friend">
										    	<span class="loading-text">View more</span>
										    	<span class="loading-img" style="display: none"><img src="/images/loading.gif" alt=""></span>
										    </div>
									    </div>
									    <div id="Current" class="tab-pane" data-value="current" data-pageid="2" role="tabpanel">
									    <div class="page-title req-search">
							  <div class="search-box">
							<input type="text" placeholder="Search" class="form-control searchtabtext">
							<button data-reqtype="current" class="search-btn-small" type="button"><i class="glyph-icon flaticon-magnifyingglass138"></i></button>
						</div>
						</div>
											<ul>

											</ul>
									    	<!-- <div class="load-btn load-more-friend">
										    	<span class="loading-text">View more</span>
										    	<span class="loading-img" style="display: none"><img src="/images/loading.gif" alt=""></span>
										    </div> -->
									    </div>

								    </div>
						  		</div>						    
						  	</div>
						</div>
				</div><!--/page center data-->
				<div class="shadow-box bottom-ad"><img class="img-responsive" alt="" src="/images/bottom-ad.jpg"></div>
			</div>

			@include('panels.right')

			</div>
		</div>
	</div><!--/pagedata-->
 
@endsection
