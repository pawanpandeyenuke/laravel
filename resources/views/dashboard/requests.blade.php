@extends('layouts.dashboard')

@section('content')
<?php  //print_r($model1);die; ?>
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
						    <!-- <li class="active" role="presentation"> -->
						    	<!-- <a class="friendstabs" data-reqtype="all" data-name="All" data-toggle="tab" role="tab" aria-controls="All" href="#All">All Users</a> -->
						    <!-- </li> -->
						       <li role="presentation">
						    	<a class="friendstabs recievecount" data-reqtype="recieved" data-name="Recieved" data-toggle="tab" role="tab" aria-controls="Recieved" href="#Recieved">Recieved (<span class = "count">{{$recievedcount}}</span>)</a>
						    </li>
						    <li role="presentation">
						    	<a class="friendstabs sentcount" data-reqtype="sent" data-name="Sent" data-toggle="tab" role="tab" aria-controls="Send" href="#Send">Sent (<span class = "count">{{$sentcount}}</span>)</a>
						    </li>
						 
						    <li role="presentation">
						    	<a class="friendstabs friendcount" data-reqtype="current" data-name="Current" data-toggle="tab" role="tab" aria-controls="Current" href="#Current">Friends (<span class = "count">{{$friendscount}}</span>)</a>
						    </li>
						  </ul>

						  <!-- Tab panes -->
						  	<div class="row">
						  		<div class="col-md-10 col-md-offset-1">
						  			<div class="tab-content friends-list">

						  			<div id="Recieved" class="tab-pane active" data-value="recieved" data-pageid="2" role="tabpanel">
									    <div class="page-title req-search">
							  <div class="search-box">
							<input type="text" placeholder="Search" class="form-control searchtabtext">
							<button data-reqtype="recieved" class="search-btn-small" type="button"><i class="flaticon-magnifying-glass138"></i></button>
						</div>
						</div>
						<ul>
							@foreach($model1 as $data)
							<?php
								$name = $data['user']['first_name'].' '.$data['user']['last_name'];

								$user_picture = !empty($data['user']['picture']) ? $data['user']['picture'] : 'images/user-thumb.jpg';
							 $id1=Auth::User()->id; 
							 ?>
							<li  class="get_id flist" data-userid="{{$data['user']['id']}}" data-friendid="{{$id1}}">
								<div class="row">
								<div class="col-sm-6">
									<div class="user-cont">
										<a title="" href="profile/{{$data['user']['id']}}">
											<span style="background: url('{{$user_picture}}');" class="user-thumb"></span>
										{{ $name }}
										</a>
									</div>
								</div>
								<div class="col-sm-6">
								<div class="row">
									<div class="col-sm-6">
										<button class="btn btn-primary btn-full accept abc" type="button" id="accept" >Confirm</button>
									</div>
									<div class="col-sm-6">
										<button class="btn btn-default btn-full abc decline" type="button"  id="decline">Delete</button>
									</div>

									<span class="btn btn-default btn-full msg" disabled="disabled" id='msg' style="display: none;">Request Rejected</span>

									<span class="btn btn-default btn-full msg2" disabled="disabled" id='msg2' style="display: none;">Friend Removed</span>

									<div class="text-right">

									<button class="btn btn-default btn-full remove abc" type="button" id="remove" style="display: none;">Remove</button>
									</div>
								</div>
							</div>
						</div>
					</li>

							
						@endforeach
						</ul>
											<?php if($recievedcount == 0){
									    			$text = "No recieved request";
									    			$class1 = "";
									    			$class2 = "";
									    			} else{
									    			  $text = "View More";
									    			  $class1 = "load-btn";
									    			  $class2 = "load-more-friend";
									    			}
									    	 ?>
									    	<div class="load-btn {{$class2}}">
									    	
										    	<span class="loading-text">{{$text}}</span>
										    	<span class="loading-img" style="display: none"><img src="/images/loading.gif" alt=""></span>
										    </div>
									    </div>

					    			<div id="Send" class="tab-pane" data-value="sent" data-pageid="2" role="tabpanel">
					    				 <div class="page-title req-search">
										    <div class="search-box">
												<input type="text" placeholder="Search" class="form-control searchtabtext">
												<button data-reqtype="sent" class="search-btn-small" type="button"><i class="flaticon-magnifying-glass138"></i></button>
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
							<button data-reqtype="current" class="search-btn-small" type="button"><i class="flaticon-magnifying-glass138"></i></button>
						</div>
						</div>
											<ul>

											</ul>
									    	<div class="load-btn load-more-friend">
											    	<span class="loading-text">View more</span>
											    	<span class="loading-img" style="display: none"><img src="/images/loading.gif" alt=""></span>
											    </div>
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
