@extends('layouts.dashboard')
<?php
//<<<<<<< HEAD

	$gender = isset($user->gender) ? $user->gender : '';
	$maritalstatus = isset($user->marital_status) ? $user->marital_status : ''; 
	$currentlystudy = isset($user)?$user->currently_studying:'';
//=======
	if(!empty($user->country)){

		$countryid = DB::table('country')->where('country_name', '=', $user->country)->value('country_id'); 
		$all_states = DB::table('state')->where('country_id', '=', $countryid)->pluck('state_name','state_id'); 

		$stateid = DB::table('city')->where('city_name', '=', $user->city)->value('state_id'); 
	 	$all_cities = DB::table('city')->where('state_id', '=', $stateid)->pluck('city_name', 'city_id'); 	
	
	}

	$gender = isset($user->gender) ? $user->gender : '';
	$maritalstatus = isset($user->marital_status) ? $user->marital_status : ''; 

//>>>>>>> 485a7ed2e917c75574d775ffa2d08abc792b413f

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
								<div class="profile-img" style="background: url('/images/user-thumb.jpg');">
									<button type="button" class="edit-pr-img" title="Edit Image"><i class="glyphicon glyphicon-camera"></i></button>
								</div><!--Profile-img-->
								<div class="pr-field">
							<span style="font-size: xx-large;">{{ $user->first_name.' '.$user->last_name }}</span>
								</div>
								<div class="pr-field">

							 <span style="font-size: large;">{{$user->city}}</span>
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
																<span>{{$user->country}}</span>
															</td>
														</tr>
														<tr>
															<td><div class="p-data-title"><i class="flaticon-gps"></i>State</div></td>
															<td>
																<span>{{$user->state}}</span>
															</td>
														</tr>
														<tr>
															<td><div class="p-data-title"><i class="flaticon-city"></i>City</div></td>
															<td>
																<span>{{$user->city}}</span>
															</td>
														</tr>
														<tr>
															<td><div class="p-data-title"><i class="flaticon-technology"></i>Contact</div></td>
															<td>
																<span>{{$user->phone_no}}</span>
															</td>
														</tr>
														<tr>
															<td><div class="p-data-title"><i class="flaticon-calendar"></i>Date of Birth</div></td>
															<td>
																<span>{{$user->birthday}}</span>
															</td>
														</tr>
														<tr>
															<td><div class="p-data-title"><i class="flaticon-black"></i>I am</div></td>
															<td>
																<span>{{$gender}}</span>
															</td>
														</tr>
														<tr>
															<td><div class="p-data-title"><i class="flaticon-circle"></i>Status</div></td>
															<td>
																<span>{{$maritalstatus}}</span>
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
																<span style="font-weight:500">{{$value->country_of_establishment}}, {{$value->state_of_establishment}}, {{$value->city_of_establishment}}</span> 
																</div>
																</td>
															</tr>
															<?php $customcounter++; ?>
														@endforeach
														<tr>
															<td><div class="p-data-title"><i class="flaticon-graduation"></i>Currently Studing</div></td>
															<td>
																<span>{{$currentlystudy}}</span>
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
<!-- <<<<<<< HEAD -->
 
@endsection
 