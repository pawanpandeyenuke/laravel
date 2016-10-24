@extends('layouts.dashboard')

@include('panels.meta-data')
@section('title', 'User Search')
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
						<i class="flaticon-people"></i> <h1>{{$count}} results found for "{{$keyword}}"</h1>
					</div>
	
					<div class="tab-style-no-border">
						@if (Session::has('success'))
								<div class="alert alert-success">{!! Session::get('success') !!}</div>
						@endif
						  <!-- Tab panes -->
						  	<div class="row">
						  		<div class="col-md-10 col-md-offset-1">
						  			<div class="tab-content friends-list">
							  			<div id="All" class="tab-pane active" data-value="all" data-pageid="2" role="tabpanel">

@if($auth == 1)						

						<ul class="counting">
														
@foreach($model1 as $data) 
	<?php $data = (array) $data;
		// echo '<pre>';print_r($data);die;
		$user_picture = !empty($data['picture']) ? url('/uploads/user_img/'.$data['picture']) : url('images/user-thumb.jpg');
		$id1 = Auth::User()->id;
		$name = $data['first_name'].' '.$data['last_name'];
		$location = array($data['country'], $data['state'], $data['city']);

		foreach ($location as $key => $value) {
			if(empty($value)){
				unset($location[$key]);
			}
		}
		// echo '<pre>';print_r($location);die;
		$location = implode(', ', $location);
	?>
	<li  class="get_id" data-userid="{{$data['id']}}" data-friendid="{{$id1}}">
		<div class="row">
			<div class="col-sm-7 col-md-7 col-xs-12">
				<div class="user-cont">
					<a title="" href="profile/{{$data['id']}}">
						<span style="background: url('{{$user_picture}}');" class="user-thumb"></span>
					{{ $name }}
					</a>
					@if($location)
						<ul class="list-inline">
							<li><i class="fa fa-map-marker"></i> {{ $location }} </li>					
						</ul>
					@endif
				</div>
			</div>
			<div class="col-sm-5 col-md-5 col-xs-12">
				<?php 
					$status1= \App\Friend::where('user_id',$data['id'])
								->where('friend_id',Auth::User()->id)
								->value('status');

					$status2=\App\Friend::where('friend_id',$data['id'])
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

					<?php }elseif($status1=='Rejected'||($status1==null)&&($status2==null) && $data['id']!=Auth::User()->id){

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
	<?php $data = (array) $data;
			$user_picture = !empty($data['picture']) ? url('/uploads/user_img/'.$data['picture']) : url('images/user-thumb.jpg');
			$name = $data['first_name'].' '.$data['last_name'];
	?>
				<li  class="get_id" data-userid="{{$data['id']}}" data-friendid="">
					<div class="row">
						<div class="col-sm-7 col-md-7 col-xs-12">
							<div class="user-cont">
								<a title="" href="profile/{{$data['id']}}">
									<span style="background: url('{{$user_picture}}');" class="user-thumb"></span>
											{{ $name }}
											</a>
										</div>
									</div>
									<div class="col-sm-5 col-md-5 col-xs-12">
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
											    	<span class="loading-img" style="display: none"><img src="{{url('/images/loading.gif')}}" alt="Loading"></span>
											    </div>
											<?php } ?>

							  			</div>
								    </div>
						  		</div>						    
						  	</div>
						</div>
				</div><!--/page center data-->
				@include('panels.footer-advertisement')
			</div>

			@include('panels.right')

			</div>
		</div>
	</div><!--/pagedata-->
 
@endsection