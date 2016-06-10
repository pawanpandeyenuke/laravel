@extends('layouts.dashboard')

@section('content')

<?php

 //print_r($count);die; ?>


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
	@if($auth == 1)

			@include('panels.left')
		@else
	 		@include('panels.leftguest')
	@endif	
			<div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="page-title">
						<i class="flaticon-people"></i> {{$count}} results found for "{{$keyword}}"
					</div>
	
						<div class="tab-style-no-border">
@if (Session::has('success'))
		<div class="alert alert-success">{!! Session::get('success') !!}</div>
	@endif

					  <!-- Nav tabs -->
						  <!-- <ul role="tablist" class="nav nav-tabs"> -->
						    <!-- <li class="active" role="presentation"> -->
						    	<!-- <a class="friendstabs" data-reqtype="all" data-name="All" data-toggle="tab" role="tab" aria-controls="All" href="#All">All Users</a> -->
						    <!-- </li> -->
						     <!-- <li role="presentation" class="active"> -->
						    	<!-- <a class="friendstabs" data-reqtype="recieved" data-name="Recieved" data-toggle="tab" role="tab" aria-controls="Recieved"  aria-expanded="true" href="#Recieved">Recieved (<span class = "count"></span>)</a>  -->
						    <!-- </li> -->
						    <!-- <li role="presentation"> -->
						    	<!-- <a class="friendstabs" data-reqtype="sent" data-name="Sent" data-toggle="tab" role="tab" aria-controls="Send" href="#Sent">Sent (<span class = "count"></span>)</a> -->
						    <!-- </li> -->
						    <!-- <li role="presentation"> -->
						    	<!-- <a class="friendstabs" data-reqtype="current" data-name="Current" data-toggle="tab" role="tab" aria-controls="Current" href="#Friends">Friends (<span class = "count"></span>)</a> -->
						    <!-- </li> -->
						  <!-- </ul> -->

						  <!-- Tab panes -->
						  	<div class="row">
						  		<div class="col-md-10 col-md-offset-1">
						  			<div class="tab-content friends-list">
							  			<div id="All" class="tab-pane active" data-value="all" data-pageid="2" role="tabpanel">

@if($auth == 1)						

						<ul class="counting">
														
@foreach($model1 as $data) 
	<?php 
	$user_picture = !empty($data['picture']) ? $data['picture'] : 'images/user-thumb.jpg';
	$id1=Auth::User()->id;
			$name = $data['first_name'].' '.$data['last_name'];
		
	?>
	<li  class="get_id" data-userid="{{$data['id']}}" data-friendid="{{$id1}}">
		<div class="row">
			<div class="col-sm-6">
				<div class="user-cont">
					<a title="" href="profile/{{$data['id']}}">
						<span style="background: url('{{$user_picture}}');" class="user-thumb"></span>
					{{ $name }}
					</a>
				</div>
			</div>
			<div class="col-sm-6">
			<?php 
				$status1=DB::table('friends')
							->where('user_id',$data['id'])
							->where('friend_id',Auth::User()->id)
							->value('status');

				$status2=DB::table('friends')
							->where('friend_id',$data['id'])
							->where('user_id',Auth::User()->id)
							->value('status');

				if($status1 == 'Pending'){
			?>
				<div class="row">
					<div class="col-sm-6">
						<button class="btn btn-primary btn-full accept abc" type="button" id="accept" >Confirm</button>
					</div>
					<div class="col-sm-6">
						<button class="btn btn-default btn-full abc decline" type="button"  id="decline">Delete</button>
					</div>
					<div class="col-sm-12">
					<button class="btn btn-default btn-full remove abc" type="button" id="remove" style="display: none;">Remove</button>
					<button type="button" class="btn btn-primary btn-full invite" id='invite' style="display: none;">Add Friend</button>
					<button class="spanmsg btn-full sent" type="button" id="sent"style="display: none;">Undo</button>
				</div>
				</div>
			<?php }elseif($status2 == 'Pending'){ 
			?>
				<div class="text-right">
					<button class="spanmsg btn-full sent" type="button" id="sent">Undo</button>
							<button type="button" class="btn btn-primary btn-full invite" id='invite' style="display: none;">Add Friend</button>
				</div>

				
			<?php }elseif($status1=='Accepted' || $status2=='Accepted'){ 
			?>
				<div class="text-right">
					<button class="btn btn-default btn-full remove abc" type="button" id="remove">Remove</button>
						<button type="button" class="btn btn-primary btn-full invite" id='invite' style="display: none;">Add Friend</button>
					<button class="spanmsg btn-full sent" type="button" id="sent"style="display: none;">Undo</button>
				</div>
				<?php }elseif($status2=='Rejected'){ 
					?>
					<div class="text-right">
						<button type="button" class="btn btn-primary btn-full resend"  id='resend'>Add Friend</button>
							<button class="spanmsg btn-full sent" type="button" id="sent"style="display: none;">Undo</button>
							<button type="button" class="btn btn-primary btn-full invite" id='invite' style="display: none;">Add Friend</button>
					</div>	

					<?php }elseif($status1=='Rejected'||($status1==null)&&($status2==null)){

					?>	
					<div class="text-right">
						<button type="button" class="btn btn-primary btn-full invite" id='invite'>Add Friend</button>
						<button class="spanmsg btn-full sent" type="button" id="sent"style="display: none;">Undo</button>
					</div>
				<?php } ?>
			</div>
		</div>
	</li>
	@endforeach
</ul>
@else


					<ul class="counting">
														
				@foreach($model1 as $data) 
	<?php
			$user_picture = !empty($data['picture']) ? $data['picture'] : 'images/user-thumb.jpg';
			$name = $data['first_name'].' '.$data['last_name'];
	?>
				<li  class="get_id" data-userid="{{$data['id']}}" data-friendid="">
					<div class="row">
						<div class="col-sm-6">
							<div class="user-cont">
								<a title="" href="profile/{{$data['id']}}">
									<span style="background: url('{{$user_picture}}');" class="user-thumb"></span>
											{{ $name }}
											</a>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="text-right">
												<a href="{{url('/register')}}" class="btn btn-primary btn-full" id='invite'>Add Friend</a>
										</div>
									</div>
								</div>
							</li>
					@endforeach
				</ul>
				@endif <?php	
												if($count > 10)
												{
											 ?>

 										<div class="load-btn load-more-all" data-key="{{$keyword}}">
											    	<span class="loading-text">View More</span>
											    	<span class="loading-img" style="display: none"><img src="/images/loading.gif" alt=""></span>
											    </div>
											<?php } ?>

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