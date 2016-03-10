@extends('layouts.dashboard')

@section('content')

<div class="page-data dashboard-body">
		<div class="container">
			<div class="row">

			@include('panels.left')

			<div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="page-title">
						<i class="flaticon-people"></i>Friends Request
						<div class="search-box">
							<input type="text" placeholder="Search" class="form-control">
							<button class="search-btn-small" type="button"><i class="glyph-icon flaticon-magnifyingglass138"></i></button>
						</div>
					</div>

						<div class="tab-style-no-border">

						  <!-- Nav tabs -->
						  <ul role="tablist" class="nav nav-tabs">
						    <li class="active" role="presentation"><a class="friendstabs" data-reqtype="all" data-toggle="tab" role="tab" aria-controls="All" href="#All">All</a></li>
						    <li role="presentation"><a class="friendstabs" data-reqtype="sent" data-toggle="tab" role="tab" aria-controls="Send" href="#Send">Sent</a></li>
						    <li role="presentation"><a class="friendstabs" data-reqtype="recieved" data-toggle="tab" role="tab" aria-controls="Recieved" href="#Recieved">Recieved</a></li>
						    <li role="presentation"><a class="friendstabs" data-reqtype="current" data-toggle="tab" role="tab" aria-controls="Current" href="#Current">Current</a></li>
						  </ul>

						  <!-- Tab panes -->
						  	<div class="row">
						  		<div class="col-md-10 col-md-offset-1">
						  			<div class="tab-content friends-list">
							  			<div id="All" class="tab-pane active" role="tabpanel">
												<ul>
													@foreach($friends as $data) 
													<?php 
														if($data['friend_id'] == Auth::User()->id)
															$name = $data['user']['first_name'].' '.$data['user']['last_name'];
														else
															$name = $data['friends']['first_name'].' '.$data['friends']['last_name'];
													?>
													<li>
														<div class="row">
															<div class="col-sm-6">
																<div class="user-cont">
																	<a title="" href="#">
																		<span style="background: url('images/user-thumb.jpg');" class="user-thumb"></span>
																	{{ $name }}
																	</a>
																</div>
															</div>
															<div class="col-sm-6">
															<?php 
																if(($data['status'] == 'Pending') && ($data['user_id'] == Auth::User()->id)){
															?>
																<div class="row">
																	<div class="col-sm-6">
																		<button class="btn btn-primary btn-full" type="button" class="accept">Accept</button>
																	</div>
																	<div class="col-sm-6">
																		<button class="btn btn-default btn-full" type="button" class="decline">Decline</button>
																	</div>
																</div>
															<?php }elseif(($data['status'] == 'Pending') && ($data['friend_id'] == Auth::User()->id)){ 
															?>
																<div class="text-right">
																	<button class="btn btn-primary btn-full" type="button">Sent Request</button>
																</div>
															<?php }elseif(($data['status'] == 'Accepted') && ($data['user_id'] == Auth::User()->id) || ($data['friend_id'] == Auth::User()->id)){ 
															?>
																<div class="text-right">
																	<button class="btn btn-default btn-full" type="button" class="remove">Remove</button>
																</div>
															<?php } ?>
															</div>
														</div>
													</li>
													@endforeach
												</ul>
							  			</div>
					    				<div id="Send" class="tab-pane" role="tabpanel">
											<ul>

											</ul>
					    				</div>
									    <div id="Recieved" class="tab-pane" role="tabpanel">
											<ul>

											</ul>
									    </div>
									    <div id="Current" class="tab-pane" role="tabpanel">
											<ul>

											</ul>
									    </div>	
								    </div>
						  		</div>						    
						  	</div>
						</div>
				</div><!--/page center data-->
				<div class="shadow-box bottom-ad"><img class="img-responsive" alt="" src="images/bottom-ad.jpg"></div>
			</div>

			@include('panels.right')

			</div>
		</div>
	</div><!--/pagedata-->
 
@endsection