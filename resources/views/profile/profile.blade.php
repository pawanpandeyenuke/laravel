@extends('layouts.dashboard')

@section('content')

<?php

	if(!empty($user->country)){
		
		$countryid = DB::table('country')->where('country_name', '=', $user->country)->value('country_id'); 
		$all_states = DB::table('state')->where('country_id', '=', $countryid)->pluck('state_name','state_id'); 
		
		$stateid = DB::table('city')->where('city_name', '=', $user->city)->value('state_id'); 
	 	$all_cities = DB::table('city')->where('state_id', '=', $stateid)->pluck('city_name', 'city_id'); 	
	 	// echo '<pre>';print_r($all_cities);die;
	}

	$gender = isset($user->gender) ? $user->gender : '';
	$maritalstatus = isset($user->marital_status) ? $user->marital_status : ''; 

	// if(!empty($user->job_category)){

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
					
					@if (Session::has('success'))
						<div class="alert alert-success">{!! Session::get('success') !!}</div>
					@endif
					@if (Session::has('error'))
						<div class="alert alert-danger">{!! Session::get('error') !!}</div>
					@endif	

					{!! Form::open() !!}

						<div class="p-header-outer">

							@if( Auth::User()->id == $user->id )
								<button type="button" class="edit-profile" title="Edit Profile"><i class="fa fa-pencil"></i></button>
							@endif
							
							<!-- <button type="submit" class="save-profile-changes" title="Save Profile"><i class="fa fa-check-circle"></i></button> -->
							<div class="profile-header">
								<div class="profile-img" style="background: url('/images/user-thumb.jpg');">
									<button type="button" class="edit-pr-img" title="Edit Image"><i class="glyphicon glyphicon-camera"></i></button>
								</div><!--Profile-img-->
								<div class="pr-field">
									<input type="text" class="pr-edit pr-name" disabled="disabled" value="{{ $user->first_name.' '.$user->last_name }}">
								</div>
								<div class="pr-field">
									<select name="city" style="max-width: 180px;" class="pr-edit" disabled="disabled">
										<option value="">City</option>	
										<?php 
										if(isset($all_cities)){
											foreach ($all_cities as $key => $value) { 
											if($value == $user->city)
												$selected = 'Selected'; 
											else
												$selected = ''; 
											?>
												<option value="{{$value}}" <?php echo $selected; ?> >{{$value}}</option>	
										<?php } } ?>
									</select>
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
														<select name="country" style="max-width: 180px;" id="profile_country" class="pr-edit" disabled="disabled">	
															<?php 
															// if(isset($country) && isset($countries)){
																foreach ($countries as $key => $value) { 
																	if($user->country == $value)
																		$selected = 'Selected'; 
																	else
																		$selected = ''; 
																	?>
																	<option value="{{$value}}" {{$selected}} >{{$value}}</option>	
															<?php } // } ?>
														</select>
													</td>
												</tr>
												<tr>
													<td><div class="p-data-title"><i class="flaticon-gps"></i>State</div></td>
													<td>
														<select name="state" style="max-width: 180px;" id="profile_state" class="pr-edit" disabled="disabled">
															<option value="">State</option>	
															<?php 
															if(isset($all_states) && isset($user->state)){
																foreach ($all_states as $key => $value) { 
																if($value == $user->state)
																	$selected = 'Selected'; 
																else
																	$selected = ''; 
																?>
																	<option value="{{$key}}" <?php echo $selected; ?> >{{$value}}</option>	
															<?php } } ?>
														</select>
													</td>
												</tr>
												<tr>
													<td><div class="p-data-title"><i class="flaticon-city"></i>City</div></td>
													<td>
														<select name="city" style="max-width: 180px;" id="profile_city" class="pr-edit" disabled="disabled">
															<option value="">City</option>	
															<?php 
															if(isset($all_cities) && isset($user->city)){
																foreach ($all_cities as $key => $value) { 
																if($value == $user->city)
																	$selected = 'Selected'; 
																else
																	$selected = ''; 
																?>
																	<option value="{{$key}}" <?php echo $selected; ?> >{{$value}}</option>	
															<?php } } ?>
														</select>
													</td>
												</tr>
<!-- 												<tr>
													<td><div class="p-data-title"><i class="flaticon-letter133"></i>Email</div></td>
													<td><input type="text" name="email" class="pr-edit" disabled="disabled" value="amikoehler@gmail.com"></td>
												</tr> -->
												<tr>
													<td><div class="p-data-title"><i class="flaticon-technology"></i>Contact</div></td>
													<td><input type="text" name="phone_no" class="pr-edit" disabled="disabled" value="{{ $user->phone_no }}"></td>
												</tr>
												<tr>
													<td><div class="p-data-title"><i class="flaticon-calendar"></i>Date of Birth</div></td>
													<td><input type="text" name="birthday" class="pr-edit datepicker" disabled="disabled" value="{{ $user->birthday }}"></td>
												</tr>
<!-- 												<tr>
													<td><div class="p-data-title"><i class="flaticon-padlock50"></i>Change Password</div></td>
													<td><input type="password" name="password" class="pr-edit" disabled="disabled" value="123456"></td>
												</tr> -->
												<tr>
													<td><div class="p-data-title"><i class="flaticon-black"></i>I am</div></td>
													<td>
														<div class="clearfix">
															<div class="radio-cont pull-left center-label">
																<input type="radio" name="gender" id="radio1" value="Male" class="css-checkbox" <?php echo ($gender == 'Male')?'Checked':'' ?> >
																<label for="radio1" class="css-label radGroup1">Male</label>
															</div>
															<div class="radio-cont pull-left center-label">
																<input type="radio" name="gender" id="radio2" value="Female" class="css-checkbox" <?php echo ($gender == 'Female')?'Checked':'' ?> >
																<label for="radio2" class="css-label radGroup1">Female</label>
															</div>
														</div>
													</td>
												</tr>
												<tr>
													<td><div class="p-data-title"><i class="flaticon-circle"></i>Status</div></td>
													<td>
														<div class="clearfix">
															<div class="radio-cont pull-left center-label">
																<input type="radio" value="Single" name="marital_status" id="radio3" class="css-checkbox" <?php echo ($maritalstatus == 'Single')?'Checked':'' ?> >
																<label for="radio3" class="css-label radGroup1">Single</label>
															</div>
															<div class="radio-cont pull-left center-label">
																<input type="radio" value="Married" name="marital_status" id="radio4" class="css-checkbox" <?php echo ($maritalstatus == 'Married')?'Checked':'' ?>>
																<label for="radio4" class="css-label radGroup1">Married</label>
															</div>
														</div>
													</td>
												</tr>
												<tr>
													<td><div class="p-data-title"><i class="flaticon-education"></i>Qualification</div></td>
													<td>
														<div class="slt-cont">
															<?php //echo '<pre>';print_r($eduLevelsData);die; ?>
															<select name="education_level" style="max-width: 180px;" class="pr-edit" disabled="disabled">
																<option>Education level</option>
																<?php foreach ($educationLevel as $key => $value) { 
																		if(isset($education->education_level) == $value)
																			$selected = 'Selected';
																		else
																			$selected = '';
																		?>
																		<option value="{{$value}}" <?php echo $selected; ?> >{{ $value }}</option>
																<?php } ?>
															</select>
															<select name="specialization" style="max-width: 180px;" class="pr-edit" disabled="disabled">
																<option >Specialization</option>
																<?php foreach ($specialization as $key => $value) { 
																	if(isset($education->specialization) == $value)
																		$selected = 'Selected';
																	else
																		$selected = '';
																	?>
																		<option value="{{$value}}" <?php echo $selected; ?> >{{$value}}</option>	
																<?php } ?>
															</select>
														</div>
													</td>
												</tr>
												<tr>
													<td><div class="p-data-title"><i class="flaticon-graduation"></i>Graduation Year</div></td>
													<td>
														<table class="inner-table">
															<tr>
																<td><input type="text" name="graduation_year_from" class="pr-edit datepicker" disabled="disabled" value="<?php echo isset($education)?$education->graduation_year_from:''?>"></td>
																<td>To&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
																<td><input type="text" name="graduation_year_to" class="pr-edit datepicker" disabled="disabled" value="<?php echo isset($education)?$education->graduation_year_to:''?>"></td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td><div class="p-data-title"><i class="flaticon-graduation"></i>Currently Studing or Not</div></td>
													<?php $currentlystudy = isset($education)?$education->currently_studying:''; ?>
													<td>
														<div class="clearfix">
															<div class="radio-cont pull-left center-label">
																<input type="radio" value="Yes" name="currently_studying" id="radios1" class="css-checkbox" <?php echo $currentlystudy == 'Yes'?'Checked':''; ?> >
																<label for="radios1" class="css-label radGroup1">Yes</label>
															</div>
															<div class="radio-cont pull-left center-label">
																<input type="radio" value="No" name="currently_studying" id="radios2" class="css-checkbox" <?php echo $currentlystudy == 'No'?'Checked':''; ?>>
																<label for="radios2" class="css-label radGroup1">&nbsp;No&nbsp;</label>
															</div>
														</div>
													</td>
												</tr>
												<tr>
													<td><div class="p-data-title"><i class="flaticon-graduation"></i>Name of Education Establishment</div></td>
													<td><input type="text" style="max-width: 180px;" name="education_establishment" class="pr-edit" disabled="disabled" value="<?php echo isset($education)?$education->education_establishment:''?>"></td>
												</tr>
												<tr>
													<td><div class="p-data-title"><i class="flaticon-web-1"></i>Country of Establishment</div></td>
													<td>
														<!-- <input type="text" style="max-width: 180px;" name="country_of_establishment" class="pr-edit" disabled="disabled" value="<?php echo isset($education)?$education->country_of_establishment:''?>"> -->
														<select name="country_of_establishment" style="max-width: 180px;" id="" class="pr-edit" disabled="disabled">
															<option value="">Country</option>	
															<?php 
																foreach ($countries as $key => $value) { 
																	if(isset($education->country_of_establishment) == $value)
																		$selected = 'Selected'; 
																	else
																		$selected = ''; 
																	?>
																	<option value="{{$value}}" {{$selected}} >{{$value}}</option>	
															<?php } ?>
														</select>
													</td>
												</tr>
<!-- 												<tr>
													<td><div class="p-data-title"><i class="flaticon-city"></i>City of Establishment</div></td>
													<td><input type="text" class="pr-edit" disabled="disabled" value="New Delhi"></td>
												</tr> -->
												<tr>
													<td><div class="p-data-title"><i class="flaticon-vintage"></i>Current profession Industry</div></td>
													<td>
														<div class="slt-cont">
															<select name="job_area" style="max-width: 180px;" class="pr-edit" id="jobarea" disabled="disabled">
																<option>Current Job Area</option>
																<?php 
																	if(isset($jobarea)){
																	foreach ($jobarea as $key => $value) { 
																		if($value == $user->job_area)
																			$selected = 'Selected'; 
																		else
																			$selected = ''; 
																		?>
																		<option value="{{ $value }}" data-value="{{ $key }}" <?php echo $selected; ?> >{{ $value }}</option>";
																<?php } } ?>
															</select>
															<select name="job_category" style="max-width: 180px;" id="jobcategory" class="pr-edit" disabled="disabled">
																<option >Job Category</option>
																<?php 
																if(isset($jobarea) && isset($education)){
																	foreach ($all_job_cat as $key => $value) { 
																		if($value == $user->job_category)
																			$selected = 'Selected'; 
																		else
																			$selected = ''; 
																		?>
																			<option value="{{$value}}" <?php echo $selected; ?> >{{$value}}</option>	
																<?php } } ?>
															</select>
														</div>
													</td>
												</tr>
											</table>
										</div>
									</div><!--/profile-data-table-->
								</div>
							</div>
						</div><!--/profile detail-->
						<div class="btn-cont text-center">
							<button class="btn btn-primary btn-lg subbtn" style="display:none;" type="submit" value="">Save</button>
						</div>
					{!!Form::close()!!}
				</div><!--/page center data-->
				<div class="shadow-box bottom-ad"><img src="/images/bottom-ad.jpg" alt="" class="img-responsive"></div>
			</div>
			@include('panels.right')

			</div>
		</div>
	</div><!--/pagedata-->
 <script type="text/javascript">
	$(document).on('click','.edit-profile',function(){
		$('.pr-edit').prop('disabled', false);
		$(this).hide();
		$('.save-profile-changes').show();
		$('button.edit-pr-img').show();
		$('.subbtn').show();
	});
	$(document).on('click','.save-profile-changes',function(){
		$('.pr-edit').prop('disabled', true);
		$(this).hide();
		$('.edit-profile').show();
		$('button.edit-pr-img').hide();
	});
	$(document).ready(function(){

		/**
		*	Get states ajax call handling.
		*	Ajaxcontroller@enterchatroom
		*/
		$('#profile_country').change(function(){
			var countryId = $(this).val();
			var _token = $('#searchform input[name=_token]').val();
			$.ajax({			
				'url' : '/ajax/getstates',
				'data' : { 'countryId' : countryId, '_token' : _token },
				'type' : 'post',
				'success' : function(response){				
					$('#profile_state').html(response);
				}			
			});	
		});


		/**
		*	Get cities ajax call handling.
		*	Ajaxcontroller@getCities
		*/
		$('#profile_state').change(function(){
			var stateId = $(this).val();
			var _token = $('#searchform input[name=_token]').val();
			$.ajax({			
				'url' : '/ajax/getcities',
				'data' : { 'stateId' : stateId, '_token' : _token },
				'type' : 'post',
				'success' : function(response){
					$('#profile_city').html(response);
				}			
			});	
		});

		$('.datepicker').datepicker();
	
	});
</script>
@endsection