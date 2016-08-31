@extends('layouts.dashboard')
@section('title', 'User Profile')
<?php

	$gender = isset($user->gender) ? $user->gender : '';
	// echo $gender;die;
	if(empty($gender)){
		$gender = 'NA';
	}
	$maritalstatus = isset($user->marital_status) ? $user->marital_status : ''; 
	$currentlystudy = isset($user)?$user->currently_studying:'';

	if(!empty($user->country)){

		$countryid = \App\Country::where('country_name', '=', $user->country)->value('country_id'); 
		$all_states = \App\State::where('country_id', '=', $countryid)->pluck('state_name','state_id'); 

		$stateid 	= \App\City::where('city_name', '=', $user->city)->value('state_id'); 
	 	$all_cities = \App\City::where('state_id', '=', $stateid)->pluck('city_name', 'city_id'); 	
	
	}

	$categoryid = \App\JobArea::where('job_area',$user->job_area)->value('job_area_id');
	$all_job_cat = \App\JobCategory::where('job_area_id',$categoryid)->pluck('job_category');

	if($user->city == null)	
	$cls="btnview";
	else					
	$cls="btnview";

?>
@section('content')
@include('panels.deluserimg')
<div class="page-data dashboard-body">
	<div class="container">
		<div class="row">   			 
			@include('panels.left')
   			<div class="col-sm-6">
					<div class="shadow-box page-center-data no-margin-top">
						<div class="p-header-outer">
							<?php $userId = Auth::User()->id; ?>
							@if( $userId == $user->id )
								<a href="/editprofile/{{$userId}}" class="edit-profile"><i class="fa fa-pencil"></i></a>
							@endif
							<div class="profile-header">

								<div class="profile-img" style="background: url('<?php echo userImage($user) ?>');">
								</div><!--Profile-img-->
								@if(!empty($user->picture))
									<div class="remove-profile">
									<p><a href="#" data-toggle="modal" class="imgremove-btn" data-target="#remove-image-area">Remove Image</a></p>
									</div>
								@endif

								<div class="pr-field">
									<span class="user-name" style="font-size: xx-large;">
										{{ $user->first_name.' '.$user->last_name }}
									</span>
								</div>

								<div class="pr-field">
							 		<span style="font-size: large;">{{$user->city}}</span>

										@if( $userId != $user->id )
										<?php
											$status1=\App\Friend::where('user_id',$user->id)->where('friend_id',$userId)->value('status');
											$status2=\App\Friend::where('user_id',$userId)->where('friend_id',$user->id)->value('status');							 ?>
										<div class="get_id acs-btns" data-userid="{{$user->id}}" data-friendid="	{{$userId}}">

											@if($status1=="Accepted" || $status2=="Accepted")
											<div class="text-right">
												<button class="btn btn-default btnview remove abc" type="button" id="remove">Remove</button>
												<button type="button" class="btn btn-primary btnview invite" id='invite' style="display: none;">Add Friend</button>
												<button class="spanmsg btnview sent" type="button" id="sent"style="display: none;">Cancel Request</button>
											</div>
											@endif

											@if($status1=="Pending")
											<div class="text-right">
												<div class="row">
													<div class="col-sm-6">
														<button class="btn btn-primary  accept abc" type="button" id="accept" >Confirm</button>
													</div>
													<div class="col-sm-6">
														<button class="btn btn-default   abc decline" type="button"  id="decline">Delete </button>
													</div>
													<div class="col-sm-12">
														<div class="text-right">
															<button class="btn btn-default btnview remove abc" type="button" id="remove" style="display: none;">Remove</button>
															<button type="button" class="btn btn-primary btnview invite" id='invite' style="display: none;">Add Friend</button>
															<button class="spanmsg btnview sent" type="button" id="sent"style="display: none;">Cancel Request</button>
														</div>
													</div>
												</div>
											</div>
											@endif

											@if($status2=="Pending")
											<div class="text-right">
												<button class="spanmsg btnview sent" type="button" id="sent">Cancel Request</button>
												<button type="button" class="btn btn-primary btnview invite" id='invite' style="display: none;">Add Friend</button>
											</div>
											@endif

											@if($status2=="Rejected")
											<div class="text-right">
												<button type="button" class="btn btn-primary btnview resend" id='resend'>Re-Send</button>
												<button class="spanmsg btnview sent" type="button" id="sent"style="display: none;">Cancel Request</button>
												<button type="button" class="btn btn-primary btnview invite" id='invite' style="display: none;">Add Friend</button>			
											</div>
											@endif

											@if($status1=='Rejected'||($status1==null)&&($status2==null))
											<div class="text-right">
												<button type="button" class="btn btn-primary btnview invite" id='invite'>Add Friend</button>
												<button class="spanmsg btnview sent" type="button" id="sent"style="display: none;">Cancel Request</button>
											</div>
											@endif

										</div><!--acs btns-->
										@endif
								</div><!--/pr-field-2-->
							</div><!--/profile header-->
						</div><!--profile header outer-->

						<div class="profile-detail">
							<div class="row">
								<div class="col-md-11 col-md-offset-1">
									<div class="profile-data-table">
										<div class="table-responsive">
											<table class="table">
												<tr>
													<td><div class="p-data-title"></div></td>
													<td>
														<span></span>
													</td>
												</tr>
												<tr>
													<td><div class="p-data-title"><i class="flaticon-web-1"></i>Country</div></td>
													<td> 
														<span style="font-weight:500">{{ !empty($user->country)?$user->country:'NA'}}</span>
													</td>
												</tr>
												<tr>
													<td><div class="p-data-title"><i class="flaticon-gps"></i>State</div></td>
													<td>
														<span style="font-weight:500">{{!empty($user->state)?$user->state:'NA'}}</span>
													</td>
												</tr>
												<tr>
													<td><div class="p-data-title"><i class="flaticon-city"></i>City</div></td>
													<td>
														<span style="font-weight:500">{{!empty($user->city)?$user->city:'NA'}}</span>
													</td>
												</tr>
												<tr>
													<td><div class="p-data-title"><i class="flaticon-technology"></i>Mobile Contact</div></td>
													<td>
														@if(!empty($user->phone_no) && !empty($user->country_code))
															<span style="font-weight:500">{{!empty($user->country_code)?'+ '.$user->country_code.' -':'NA'}}</span>
															<span style="font-weight:500">{{!empty($user->phone_no)?$user->phone_no:'NA'}}</span>
														@else
															<span style="font-weight:500">NA</span>
														@endif
													</td>
												</tr>
												<tr>
													<td><div class="p-data-title"><i class="flaticon-calendar"></i>Date of Birth <span style="">[it's confidential] </span> </div></td>
													<td>
														<span style="font-weight:500">{{!empty($user->birthday)?date('d F Y',strtotime($user->birthday)):'NA'}}</span>
													</td>
												</tr>
												<tr>
													<td><div class="p-data-title"><i class="flaticon-black"></i>I am</div></td>
													<td>
														<span style="font-weight:500">{{$gender}}</span>
													</td>
												</tr>
												<tr>
													<td><div class="p-data-title"><i class="flaticon-circle"></i>Status</div></td>
													<td>
														<span style="font-weight:500">{{$maritalstatus}}</span>
													</td>
												</tr>

												<?php $customcounter = 1; ?>
												<?php //echo '<pre>';print_r($education->toArray());die; ?>
												@foreach($education as $value)
												<tr>
													<td>
														@if($customcounter == 1)
															<div class="p-data-title"><i class="flaticon-education"></i>Qualification</div>
														@endif
													</td>
													<td>
														<div class="slt-cont">
															<span style="font-weight:500">{{$value->education_level}}</span> in <span style="font-weight:500">{{$value->specialization}}</span>  
															<br/>
															@if($value->graduation_year != 0)
																<span>Batch of </span><span style="font-weight:500">{{$value->graduation_year}}</span> 
																<br/>
															@endif
															@if($value->education_establishment != "")
																<span>from </span><span style="font-weight:500">{{$value->education_establishment}}</span> 
																<br/>
															@endif
															<span style="font-weight:500">

															<?php 
																$educountry = $value->country_of_establishment;
																if($educountry == "")
																	$location  = 'NA';
																else{
																	$edustate = $value->state_of_establishment;
																	if($edustate == "")
																		$location = $educountry;
																	else{
																		$educity = $value->city_of_establishment;
																		if($educity == "")
																			$location = $educountry.", ".$edustate;
																		else
																			$location = $educountry.", ".$edustate.", ".$educity;
																	}
																}
															?>
																{{$location}}
															</span> 
														</div>
													</td>
												</tr>

												<?php $customcounter++; ?>
												@endforeach
												<tr>
													<td><div class="p-data-title"><i class="flaticon-graduation"></i>Currently Studying</div></td>
													<td>
														<span style="font-weight:500">{{$currentlystudy}}</span>
													</td>
												</tr>
												<tr>
													<td><div class="p-data-title"><i class="flaticon-vintage"></i>Current Profession Industry</div></td>
													<td>
														@if(!empty($user->job_category) && !empty($user->job_area))
															<span style="font-weight:500">{{$user->job_category}},</span>
												   		<br><span style="font-weight:500">{{$user->job_area}}</span>
												  	@else
												  		<span style="font-weight:500">NA</span>
												  	@endif
													</td>
												</tr>
												<tr>
													<td><div class="p-data-title"><i class="flaticon-vintage"></i>Job Title</div></td>
													<td>
													@if(!empty($user->job_title))
														<span style="font-weight:500">{{$user->job_title}}</span>
												  	@else
												  		<span style="font-weight:500">NA</span>
												  	@endif
													</td>
												</tr>
												<tr>
													<td><div class="p-data-title"><i class="flaticon-vintage"></i>Company</div></td>
													<td>
													@if(!empty($user->company))
														<span style="font-weight:500">{{$user->company}}</span>
												  	@else
												  		<span style="font-weight:500">NA</span>
												  	@endif
													</td>
												</tr>
												<tr>
													<td><div class="p-data-title"><i class="flaticon-vintage"></i>Subscribed to forum notifications</div></td>
													<td>
														<span style="font-weight:500">{{$user->subscribe ? 'Yes': 'No'}}</span>
													</td>
												</tr>
											</table> 
										</div>
									</div>
								</div>
							</div>
						</div><!--/profile detail-->

						
					</div><!--/page center data-->
					<div class="shadow-box bottom-ad"><img src="/images/bottom-ad.jpg" alt="" class="img-responsive"></div>
				</div><!--/col-6-center data-->
			<!-- 		</div>
				</div> -->
			@include('panels.right')
		</div><!--/main row-->
	</div>
</div>
@endsection
 

