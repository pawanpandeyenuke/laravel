@extends('layouts.dashboard')
<?php

	$gender = isset($user->gender) ? $user->gender : '';
	$maritalstatus = isset($user->marital_status) ? $user->marital_status : ''; 
	$currentlystudy = isset($user)?$user->currently_studying:'';

	if(!empty($user->country)){

		$countryid = DB::table('country')->where('country_name', '=', $user->country)->value('country_id'); 
		$all_states = DB::table('state')->where('country_id', '=', $countryid)->pluck('state_name','state_id'); 

		$stateid = DB::table('city')->where('city_name', '=', $user->city)->value('state_id'); 
	 	$all_cities = DB::table('city')->where('state_id', '=', $stateid)->pluck('city_name', 'city_id'); 	
	
	}

	$gender = isset($user->gender) ? $user->gender : '';
	$maritalstatus = isset($user->marital_status) ? $user->marital_status : ''; 


	$categoryid = DB::table('job_area')->where('job_area',$user->job_area)->value('job_area_id');
	$all_job_cat = DB::table('job_category')->where('job_area_id',$categoryid)->pluck('job_category');



?>
@section('content')

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
								<?php $userpic = !empty($user->picture) ? $user->picture : '/images/user-thumb.jpg'; ?>
								<div class="profile-img" style="background: url('{{ $userpic }}');">
								</div><!--Profile-img-->
								<div class="pr-field">
										<span style="font-size: xx-large;">{{ $user->first_name.' '.$user->last_name }}</span>
								</div>

							<div class="pr-field">
							 <span style="font-size: large;">{{$user->city}}</span>
							<!-- </div> -->

								<!-- <div class="pr-field"> -->
										@if( $userId != $user->id )
							<?php
								$status1=DB::table('friends')->where('user_id',$user->id)->where('friend_id',$userId)->value('status');
								$status2=DB::table('friends')->where('user_id',$userId)->where('friend_id',$user->id)->value('status');							 ?>
								<div class="get_id" data-userid="{{$user->id}}" data-friendid="{{$userId}}">
							@if($status1=="Accepted" || $status2=="Accepted")
							<div class="text-right">
					<button class="btn btn-default btnview remove abc" type="button" id="remove">Remove</button>
						</div>
						@endif
						@if($status1=="Pending")
						<div class="row">
					<div class="col-sm-6">
						<button class="btn btn-primary rbtnview btnview accept abc" type="button" id="accept" >Accept</button>
					</div>
					<div class="col-sm-6">
						<button class="btn btn-default lbtnview btnview abc decline" type="button"  id="decline">Decline</button>
					</div>

					<span class="btn btn-default fremoved btnview msg" id='msg' style="display: none;">Request Rejected</span>

					<span class="btn btn-default fremoved btnview msg2" id='msg2' style="display: none;">Friend Removed</span>

					<div class="text-right">

					<button class="btn btn-default btnview remove abc" type="button" id="remove" style="display: none;">Remove</button>
				</div>
				</div>
						@endif
						@if($status2=="Pending")
						<div class="text-right">
					<button class="btn btn-primary btnview" type="button" id="sent">Sent Request</button>
				</div>
						@endif
						@if($status2=="Rejected")
						<div class="text-right">
						<button type="button" class="btn btn-primary btnview resend" id='resend'>Re-Send</button>
					</div>
					<div class="text-right">
					<button class="btn btn-primary btnview sent" type="button" id="sent"style="display: none;">Sent Request</button>
				</div>

						@endif
						@if($status1=='Rejected'||($status1==null)&&($status2==null))
						<div class="text-right">
						<button type="button" class="btn btn-primary btnview invite" id='invite'>Add as a friend</button>
					</div>
					<div class="text-right">
					<button class="btn btn-primary btnview sent" type="button" id="sent"style="display: none;">Sent Request</button>
					</div>
					</div>
						@endif
							@endif
								</div>

							</div>
								<div class="profile-detail">
									<div class="row">
										<div class="col-md-12">
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
																<span style="font-weight:500">{{$user->country}}</span>
															</td>
														</tr>
														<tr>
															<td><div class="p-data-title"><i class="flaticon-gps"></i>State</div></td>
															<td>
																<span style="font-weight:500">{{$user->state}}</span>
															</td>
														</tr>
														<tr>
															<td><div class="p-data-title"><i class="flaticon-city"></i>City</div></td>
															<td>
																<span style="font-weight:500">{{$user->city}}</span>
															</td>
														</tr>
														<tr>
															<td><div class="p-data-title"><i class="flaticon-technology"></i>Contact</div></td>
															<td>
																<span style="font-weight:500">{{$user->phone_no}}</span>
															</td>
														</tr>
														<tr>
															<td><div class="p-data-title"><i class="flaticon-calendar"></i>Date of Birth</div></td>
															<td>
																<span style="font-weight:500">{{$user->birthday}}</span>
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
																	<span>Batch of </span><span style="font-weight:500">{{$value->graduation_year}}</span> 
																	<br/>
																	<span>from </span><span style="font-weight:500">{{$value->education_establishment}}</span> 
																	<br/>
																	<span style="font-weight:500">
												{{$value->country_of_establishment}}, {{$value->state_of_establishment}}, 
												{{$value->city_of_establishment}}
												</span> 

																</div>
																</td>
															</tr>
															<?php $customcounter++; ?>
														@endforeach
														<tr>
															<td><div class="p-data-title"><i class="flaticon-graduation"></i>Currently Studing</div></td>
															<td>
																<span style="font-weight:500">{{$currentlystudy}}</span>
															</td>
														</tr>
														<tr>
															<td><div class="p-data-title"><i class="flaticon-vintage"></i>Current profession Industry</div></td>

														<td>

															<span style="font-weight:500">{{$user->job_category}},</span>
													   </br><span style="font-weight:500">{{$user->job_area}}</span> 
															</td>
														</tr>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							
							<div class="shadow-box bottom-ad"><img src="/images/bottom-ad.jpg" alt="" class="img-responsive"></div>
						</div>
					</div>
				</div>
			@include('panels.right')
		</div>
	</div>
</div>
@endsection
 
