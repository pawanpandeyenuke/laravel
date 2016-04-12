@extends('layouts.dashboard')

@section('content')

<?php

	$gender = isset($user->gender) ? $user->gender : '';
	$maritalstatus = isset($user->marital_status) ? $user->marital_status : ''; 
	$currentlystudy = isset($user)?$user->currently_studying:'';

 		$categoryid = DB::table('job_area')->where('job_area',$user->job_area)->value('job_area_id');
 		$all_job_cat = DB::table('job_category')->where('job_area_id',$categoryid)->pluck('job_category');
 		// echo '<pre>';print_r($data);die;
 		// $all_job_cat = DB::table('job_category')->where('job_area_id', '=', $education->job_area)->pluck('job_category', 'job_category_id'); 

 	// }

?>
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
								
							<!-- <button type="submit" class="save-profile-changes" title="Save Profile"><i class="fa fa-check-circle"></i></button> -->
							<div class="profile-header">
								<div class="profile-img" style="background: url('/images/user-thumb.jpg');">
									<button type="button" class="edit-pr-img" title="Edit Image"><i class="glyphicon glyphicon-camera"></i></button>
								</div><!--Profile-img-->
								<div class="pr-field">
									<input type="text" name="username" class="pr-edit pr-name" disabled="disabled" value="{{ $user->first_name.' '.$user->last_name }}">
								</div>
								<div class="pr-field">
									<span>{{$user->city}}</span>
								</div>
							</div>
						</div>

						<div class="profile-detail">
							<div class="row">
								<div class="col-md-11 col-md-offset-1">
									<div class="profile-data-table">
										<div class="table-responsive">
											<table class="table">
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
																<span style="font-weight:500">{{$value->education_level}}</span> 
																<br/>
																&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Specialization - </span><span>{{$value->specialization}}</span> 
																<br/>
																&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Graduation Year - </span><span>{{$value->graduation_year}}</span> 
																<br/>
																&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Name of Education Establishment - </span><span>{{$value->education_establishment}}</span> 
																<br/>
																&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Estblishment Country - </span><span>{{$value->country_of_establishment}}</span> 
																<br/>
																&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Estblishment State - </span><span>{{$value->state_of_establishment}}</span> 
																<br/>
																&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Estblishment City - </span><span>{{$value->city_of_establishment}}</span> 
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
														<br/><br/>
														<span>Job Area - </span><span>{{$user->job_area}}</span> 
														<br/><br/>
														<span>Job Category - </span><span>{{$user->job_category}}</span> 
													</td>
												</tr>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
				</div>
				<div class="shadow-box bottom-ad"><img src="/images/bottom-ad.jpg" alt="" class="img-responsive"></div>
			</div>
			@include('panels.right')

			</div>
		</div>
	</div>
 
@endsection
