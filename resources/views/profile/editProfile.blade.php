@extends('layouts.dashboard')
@section('title', 'User Profile - ')
@section('content')

<?php
	// echo '<pre>';print_r($user);die;
	if(!empty($user->country)){

		$countryid = \App\Country::where('country_name', '=', $user->country)->value('country_id'); 
		$all_states = \App\State::where('country_id', '=', $countryid)->pluck('state_name','state_id'); 

		$stateid = \App\City::where('city_name', '=', $user->city)->value('state_id'); 
	 	$all_cities = \App\City::where('state_id', '=', $stateid)->pluck('city_name', 'city_id'); 	

	}


	$gender = isset($user->gender) ? $user->gender : '';
	$maritalstatus = isset($user->marital_status) ? $user->marital_status : ''; 
	$currentlystudying = isset($user->currently_studying) ? $user->currently_studying : ''; 

 		$categoryid = \App\JobArea::where('job_area',$user->job_area)->value('job_area_id');
 		$all_job_cat = \App\JobCategory::where('job_area_id',$categoryid)->pluck('job_category');
?>

<div class="page-data dashboard-body">
		<div class="container">
			<div class="row">

			@include('panels.left')

			<div class="col-sm-6">
				{!! Form::open(array('files' => true,'id'=>'edit_profile')) !!}
				<div class="shadow-box page-center-data no-margin-top">
					@if (Session::has('success'))
						<div class="alert alert-success">{!! Session::get('success') !!}</div>
					@endif
					@if (Session::has('error'))
						<div class="alert alert-danger">{!! Session::get('error') !!}</div>
					@endif

					
			<div class="p-header-outer">
				<div class="profile-header">

					<div id="profile-pic-holder" class="profile-img" style="background: url('<?php echo userImage($user) ?>');">
						<input type="file" id="profilepicture" name="picture" class="filestyle" data-input="false" data-iconName="glyphicon glyphicon-camera" data-buttonText="" data-buttonName="edit-pr-img">
					</div><!--Profile-img-->
					<div class="pr-field">
						<input type="text" class="pr-edit pr-name" value="{{ $user->first_name }}" name="first_name">
						<input type="text" class="pr-edit pr-name" value="{{ $user->last_name }}" name="last_name">
					</div>
					<div class="pr-field">
						<span>{{$user->city}}</span>
						
					</div>
				</div><!--/profile header-->
			</div>

					<div class="profile-detail">
						<div class="row">
							<div class="col-md-11 col-md-offset-1">
								<div class="profile-data-table">
									<div class="pe-row">
										<div class="row">
											<div class="col-sm-5 col-xs-12">
												<div class="p-data-title"><i class="flaticon-web-1"></i>Country</div>
											</div>
											<div class="col-sm-7 col-xs-12">
												<select name="country" class="pr-edit" id="profile_country" >
													<option value="">Select Country</option>
													<?php 
														foreach ($countries as $key => $value) { 
															if($user->country == $value)
																$selected = 'Selected'; 
															else
																$selected = ''; 

															if($value == 'Country')
																$foption = '';
															else
																$foption = $value;
															?>
															<option value="{{$foption}}" {{$selected}} >{{$value}}</option>	
													<?php } // } ?>
												</select>
											</div>
										</div>
									</div>
									<div class="pe-row">
										<div class="row">
											<div class="col-sm-5 col-xs-12">
												<div class="p-data-title"><i class="flaticon-gps"></i>State</div>
											</div>
											<div class="col-sm-7 col-xs-12">
												<select name="state"  class="pr-edit" id="profile_state">
													<option value="">Select State</option>	
													<?php 
													if(!empty($all_states)){
														foreach ($all_states as $key => $value) { 
														if($value == $user->state)
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
									<div class="pe-row">
										<div class="row">
											<div class="col-sm-5 col-xs-12">
												<div class="p-data-title"><i class="flaticon-city"></i>City</div>
											</div>
											<div class="col-sm-7 col-xs-12">
												<select name="city"  class="pr-edit" id="profile_city" >
													<option value="">Select City</option>	
													<?php 
													if(isset($all_cities) && isset($user->city)){
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
									<div class="pe-row">
										<div class="row">
											<div class="col-sm-5 col-xs-12">
												<div class="p-data-title"><i class="flaticon-technology"></i>Contact</div>
											</div>
											<div class="col-sm-7 col-xs-12 ph-field">
												<!-- <input type="text" name="phone_no" class="pr-edit numeric" maxlength="15" value="{{ $user->phone_no }}"> -->

											    <span name="country_code" class="country-code-field-span country-code-field numeric" value="{{ $user->country_code }}" placeholder="000" >{{ $user->country_code }}</span>
											    <input type="hidden" name="country_code" class="country-code-field" value="{{ $user->country_code }}" />
											    <input type="text" class="ph-input numeric" name = "phone_no" id="mobileContact" value="{{ $user->phone_no }}">
											</div>
										</div>
									</div>
									<div class="pe-row">
										<div class="row">
											<div class="col-sm-5 col-xs-12">
												<div class="p-data-title"><i class="flaticon-calendar"></i>Date of Birth</div>
											</div>
											<div class="col-sm-7 col-xs-12">
												<input type="text" name="birthday" class="pr-edit datepicker" value="{{ $user->birthday }}">
											</div>
										</div>
									</div>
									<div class="pe-row">
										<div class="row">
											<div class="col-sm-5 col-xs-12">
												<div class="p-data-title"><i class="flaticon-black"></i>I am</div>
											</div>
											<div class="col-sm-7 col-xs-12">
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
											</div>
										</div>
									</div>
									<div class="pe-row">
										<div class="row">
											<div class="col-sm-5 col-xs-12">
												<div class="p-data-title"><i class="flaticon-circle"></i>Status</div>
											</div>
											<div class="col-sm-7 col-xs-12">
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
											</div>
										</div>
									</div>
									<div class="pe-row">
										<div class="row">
											<div class="col-sm-5 col-xs-12">
												<div class="p-data-title"><i class="flaticon-graduation"></i>Currently Studing or Not</div>
											</div>
											<div class="col-sm-7 col-xs-12">
												<div class="clearfix">
													<div class="radio-cont pull-left center-label">
														<input type="radio" name="currently_studying" id="radios1" value="Yes" class="css-checkbox" <?php echo ($currentlystudying == 'Yes')?'Checked':'' ?> >
														<label for="radios1" class="css-label radGroup1">Yes</label>
													</div>
													<div class="radio-cont pull-left center-label">
														<input type="radio" value="No" name="currently_studying" id="radios2" class="css-checkbox" <?php echo ($currentlystudying == 'No')?'Checked':'' ?>>
														<label for="radios2" class="css-label radGroup1">&nbsp;No&nbsp;</label>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="pe-row">
										<div class="row">
											<div class="col-sm-5 col-xs-12">
												<div class="p-data-title"><i class="flaticon-graduation"></i>Subscribe for forum notifications</div>
											</div>
											<div class="col-sm-7 col-xs-12">
												<div class="clearfix">
													<div class="radio-cont pull-left center-label">
														<input type="radio" name="subscribe" id="subscribeYes" value="1" class="css-checkbox" {{ $user->subscribe ? 'checked' : ''}} >
														<label for="subscribeYes" class="css-label radGroup1">Yes</label>
													</div>
													<div class="radio-cont pull-left center-label">
														<input type="radio" value="0" name="subscribe" id="subscribeNo" class="css-checkbox" {{ !$user->subscribe ? 'checked' : ''}} >
														<label for="subscribeNo" class="css-label radGroup1">&nbsp;No&nbsp;</label>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="study-data-outer">
										<div class="study-detail">
											<div class="study-heading">
												<i class="flaticon-education"></i> Qualification
											</div>
											<button type="button" class="btn add-study-btn" onclick="addMoreRows(this.form);"><span class="glyphicon glyphicon-plus"></span></button>
											
											@if(count($education) > 0 )
												<?php $customcount = 1; ?>
												@foreach($education as $data)
												
													<?php 
															$countryidestab = \App\Country::where('country_name', '=', $data->country_of_establishment)->value('country_id'); 
															$all_states_estab = \App\State::where('country_id', '=', $countryidestab)->pluck('state_name','state_id'); 

															$stateidestab = \App\City::where('city_name', '=', $data->city_of_establishment)->value('state_id'); 
														 	$all_cities_estab = \App\City::where('state_id', '=', $stateidestab)->pluck('city_name', 'city_id'); 	
													?>
													<div class="pe-row" data-id="<?php echo $data->id; ?>">
														@if($customcount > 1)
															<button type="button" class="btn add-study-btn removeme"><span class="glyphicon glyphicon-trash"></span></button>
														@endif
															<div class="row">
																<div class="col-sm-6 lPadding">
																	<div class="p-data-title"><i class="flaticon-graduation"></i>Education level</div>
																		<select id="educationlevel" name="education_level[]" style="max-width: 180px;" >
																			<option value="">Education level</option>
																			<?php foreach ($educationLevel as $key => $value) { 
																				if(isset($data->education_level)){
																					if($data->education_level == $value)
																						$selected = 'Selected';
																					else
																						$selected = '';
																				}
																					?>
																					<option value="{{$value}}" <?php echo $selected; ?> >{{ $value }}</option>
																			<?php } ?>
																		</select>
																</div>
																<div class="col-sm-6 lPadding">
																	<div class="p-data-title"><i class="flaticon-graduation"></i>Specialization</div>
																		<select name="specialization[]" id="specialization" style="max-width: 180px;" >
																			<option value="">Specialization</option>
																			<?php foreach ($specialization as $key => $value) { 
																				if(isset($data->specialization)){
																				if($data->specialization == $value)
																					$selected = 'Selected';
																				else
																					$selected = '';
																			}
																				?>
																					<option value="{{$value}}" <?php echo $selected; ?> >{{$value}}</option>	
																			<?php } ?>
																		</select>
																</div>

															</div>
															<div class="row">

																<div class="col-sm-6 lPadding">
																	<div class="p-data-title"><i class="flaticon-graduation"></i>Year</div>
																	<input type="text" class="numeric year-input" name="graduation_year[]" value="{{$data->graduation_year ? $data->graduation_year : ''}}" placeholder="" maxlength="4">
																	<!-- <select id="graduationyears" name="graduation_year[]">
																		<option value="">Year</option>
																		@foreach($gradYear as $valuedata)
																			<?php
																				if($valuedata == $data->graduation_year)
																					$selected = 'selected';
																				else
																					$selected = '';
																			?>
																			<option value="{{$valuedata}}" {{$selected}}>{{ $valuedata }}</option>
																		@endforeach
																	</select> -->
																</div>
															
																<div class="col-sm-6 lPadding">
																	<div class="p-data-title"><i class="flaticon-graduation"></i>Name of Establishment</div>
																	
																	<input type="text" class="year-input" name="education_establishment[]" value="{{$data->education_establishment}}" placeholder="">
																</div>

															</div>
															<div class="row">

																<div class="col-sm-4 lPadding rPadding">
																	<div class="p-data-title"><i class="flaticon-web-1"></i>Country of Establishment</div>
																	
																	<select name="country_of_establishment[]" class="country" id="edu_country" data-put="#state">
																		<option value="">Select Country</option>
																		@foreach($countries as $countrydata)
																			<?php if($data->country_of_establishment == $countrydata)
																					$selected = 'Selected'; 
																				else
																					$selected = ''; ?>
																			<option value="<?php echo $countrydata; ?>" <?php echo $selected; ?>><?php echo $countrydata; ?></option>
																		@endforeach
																	</select>
																</div>
																<div class="col-sm-4 lPadding rPadding">
																	<div class="p-data-title"><i class="flaticon-gps"></i>State of Establishment</div>
																	<select name="state_of_establishment[]" class="state" data-put="#city">
																		<option value="">Select State</option>
																		@foreach($all_states_estab as $value)
																		<?php if($data->state_of_establishment == $value)
																				$selected = 'Selected'; 
																			else
																				$selected = ''; ?>
																		<option value="{{$value}}" <?php echo $selected; ?> >{{$value}}</option>	
																		@endforeach
																	</select>
																</div>
																<div class="col-sm-4 lPadding">
																	<div class="p-data-title"><i class="flaticon-city"></i>City of Establishment</div>
																	<select name="city_of_establishment[]" class="city" >
																		<option value="">Select City</option>
																		@foreach($all_cities_estab as $value)
																		<?php if($data->city_of_establishment == $value)
																				$selected = 'Selected'; 
																			else
																				$selected = ''; ?>
																		<option value="{{$value}}" <?php echo $selected; ?> >{{$value}}</option>	
																		@endforeach
																	</select>
																</div>
															</div>
														<?php $customcount++; ?>
													</div>
												@endforeach	


											@else

												<div class="row">
													<div class="col-sm-4">
														<div class="p-data-title"><i class="flaticon-graduation"></i>Education level</div>
															<select id="educationlevel" name="education_level[]" style="max-width: 180px;" >
																<option value="">Education level</option>
																<?php foreach ($educationLevel as $key => $value) { 
																	if(isset($data->education_level)){
																		if($data->education_level == $value)
																			$selected = 'Selected';
																		else
																			$selected = '';
																	}
																		?>
																		<option value="{{$value}}" <?php echo $selected; ?> >{{ $value }}</option>
																<?php } ?>
															</select>
													</div>
													<div class="col-sm-4">
														<div class="p-data-title"><i class="flaticon-graduation"></i>Specialization</div>
															<select id="specialization" name="specialization[]" style="max-width: 180px;" >
																<option value="">Specialization</option>
																<?php foreach ($specialization as $key => $value) { 
																	if(isset($data->specialization)){
																	if($data->specialization == $value)
																		$selected = 'Selected';
																	else
																		$selected = '';
																}
																	?>
																		<option value="{{$value}}" <?php echo $selected; ?> >{{$value}}</option>	
																<?php } ?>
															</select>
													</div>
													<div class="col-sm-4 lPadding">
														<div class="p-data-title"><i class="flaticon-graduation"></i>Year</div>
														<input type="text" class="numeric year-input" name="graduation_year[]" value="" placeholder="" maxlength="4">
														<!-- <select class="" id="graduationyears" name="graduation_year[]">
															<option>Year</option>
															@foreach($gradYear as $valuedata)
																<option value="{{$valuedata}}">{{ $valuedata }}</option>
															@endforeach
														</select> -->
													</div>
												</div>
												<div class="row">
													<div class="col-sm-4">
														<div class="p-data-title"><i class="flaticon-graduation"></i>Name of Establishment</div>
														<input type="text" class="year-input" name="education_establishment[]" value="" placeholder="">
													</div>
													<div class="col-sm-4">
														<div class="p-data-title"><i class="flaticon-web-1"></i>Country of Establishment</div>
														<select name="country_of_establishment[]" id="edu_country" class="country" data-put="#state">
															@foreach($countries as $countrydata)
																<option value="<?php echo $countrydata; ?>" <?php echo $selected; ?> ><?php echo $countrydata; ?></option>
															@endforeach
														</select>
													</div>
													<div class="col-sm-4">
														<div class="p-data-title"><i class="flaticon-gps"></i>State of Establishment</div>
														<select name="state_of_establishment[]" class="state" data-put="#city">
															<option value="">Option</option>
														</select>
													</div>
													<div class="col-sm-4">
														<div class="p-data-title"><i class="flaticon-city"></i>City of Establishment</div>
														<select name="city_of_establishment[]" class="city">
															<option value="">Option</option>
														</select>
													</div>
												</div> 

											@endif	
										</div>
										<div id="addedRows"></div>										
									</div>
									
									<div class="pe-row">
										<div class="row">
											<div class="col-sm-5 col-xs-12">
												<div class="p-data-title"><i class="flaticon-vintage"></i>Current profession Industry</div>
											</div>
											<div class="col-sm-7 col-xs-12">
												<div class="slt-cont">
													<select name="job_area" class="pr-edit" id="jobarea" >
														<option value="">Current Job Area</option>
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
 
													<select name="job_category" class="pr-edit" id="jobcategory" >
														<option value="">Job Category</option>
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
											</div>
										</div>
									</div>

									<div class="pe-row">
										<div class="row">
											<div class="col-sm-5 col-xs-12">
												<div class="p-data-title"><i class="flaticon-vintage"></i>Job Title</div>
											</div>
											<div class="col-sm-7 col-xs-12">
												<input type="text" class="job-title-input" name="job_title" value="{{$user->job_title}}" placeholder="">
											</div>
										</div>
									</div>
									<div class="pe-row">
										<div class="row">
											<div class="col-sm-5 col-xs-12">
												<div class="p-data-title"><i class="flaticon-vintage"></i>Company</div>
											</div>
											<div class="col-sm-7 col-xs-12">
												<input type="text" class="job-title-input" name="company" value="{{$user->company}}" placeholder="">
											</div>
										</div>
									</div>

								</div><!--/profile-data-table-->
							</div>
						</div>
					</div><!--/profile detail-->
					<div class="btn-cont text-center">
						<button class="btn btn-primary btn-lg subbtn" type="submit" value="">Save</button>
					</div>
				</div><!--/page center data-->
				<div class="shadow-box bottom-ad"><img src="/images/bottom-ad.jpg" alt="" class="img-responsive"></div>
				{!!Form::close()!!}
			</div>
			@include('panels.right')

			</div>
		</div>
	</div>



<script type="text/javascript">
jQuery(function($){
	$("#edit_profile").validate({ 
    errorElement: 'span',
    errorClass: 'help-inline',
    rules: {
      first_name: { required: true },
     	last_name: {required: true},
      country: {required: true}
    },
    messages:{
      first_name:{
        required: "First name can't be empty."
      },
      last_name:{
        required: "Last name can't be empty."
      },
      country:{
      	required: "Country is required"
      }
    }
  });

	$(document).on('change', '.country', function(){
		var current = $(this);
		var countryId = current.val();
		if( countryId )
		{
			$.ajax({			
				'url' : '/ajax/getstates',
				'data' : { 'countryId' : countryId },
				'type' : 'post',
				'success' : function(response){	
					current.closest('.row').find('.state').html(response);
					$('#profile_city').html('<option value="">Select City</option>');
				}			
			});
		} else {
			$('#profile_state').html('<option value="">Select State</option>');
			$('#profile_city').html('<option value="">Select City</option>');
		}	
	});

	$(document).on('change', '.state', function(){
		var current = $(this);
		var stateId = current.val();
		if( stateId )
		{
			$.ajax({			
				'url' : '/ajax/getcities',
				'data' : { 'stateId' : stateId },
				'type' : 'post',
				'success' : function(response){	
					current.closest('.row').find('.city').html(response);
				}			
			});
		} else {
			$('#profile_city').html('<option value="">Select City</option>');
		}
	});

	/*  -------------------------  country state city  -------------------------  */ 

	$(document).on('change', '#profile_country', function(){
		var countryId = $(this).val();
		var _token = $('#searchform input[name=_token]').val();
		if(countryId != ''){
			$.ajax({			
				'url' : '/ajax/getstates',
				'data' : { 'countryId' : countryId, '_token' : _token },
				'type' : 'post',
				'success' : function(response)
				{
					$('#profile_state').html(response);
					$.ajax({			
						'url' : '/ajax/mob-country-code',
						'data' : { 'countryId' : countryId, '_token' : _token },
						'type' : 'post',
						'success' : function(response){
							var mobile = $('#mobileContact').val();
							if( !mobile )
							{
								var mobCode = response[0].phonecode;
								$('.country-code-field').val(mobCode);
								$('.country-code-field-span').html(mobCode);
								$('.country-code-field').attr('data-value', mobCode);
	              var validArray = getValidationArray(mobCode);
	            }
              $('#profile_city').html('<option value="">Select City</option>');
						}			
					});	
				}			
			});
		}else{
			$('#profile_state').html('<option value="">Select State</option>');
			$('#profile_city').html('<option value="">Select City</option>');
		}
	});

	/* @Mobile code change on country change */
	function getValidationArray(mobCode)
	{
	    var countryMobValidLengthArray = <?php print_r(json_encode(countryMobileLength(),1));?>;
	    var countryMobValidLength = countryMobValidLengthArray[mobCode];
	    if(countryMobValidLength == undefined){
	        return {min: 0, max: 15};
	    }
	    console.log(countryMobValidLength);
	    return {min: countryMobValidLength.min, max: countryMobValidLength.max};
	}

  $(document).on('focus', '#mobileContact', function(){
      var array = $('.country-code-field').data('value');
      var validArray = getValidationArray(array);
      $('#mobileContact').prop('minlength', validArray.min);
      $('#mobileContact').prop('maxlength', validArray.max);
  });

  $(document).on('blur', '#mobileContact', function(){
      $('#mobileContact').parent().find('#groupname-error').remove();
      var array = $('.country-code-field').data('value');
      var validArray = getValidationArray(array);
      var mobileContact = $('#mobileContact').val();
      if(mobileContact.length < validArray.min){
          // alert('invalid value');
          $('#mobileContact').parent().append('<span id="groupname-error" class="help-inline">Minimum length must be greater than '+validArray.min+'.</span>');
      }
  });
  /* @Mobile code change on country change */

	$(document).on('change', '#profile_state', function(){
		var stateId = $(this).val();
		var _token = $('#searchform input[name=_token]').val();

		if(stateId != ''){
			$.ajax({			
				'url' : '/ajax/getcities',
				'data' : { 'stateId' : stateId, '_token' : _token },
				'type' : 'post',
				'success' : function(response){
					$('#profile_city').html(response);
				}			
			});	
		}else{
			$('#profile_city').html('<option value="">Select City</option>');
		}
	});

	var today = new Date();
	$('.datepicker').datepicker({
    'format': 'yyyy-mm-dd',
    'startDate': '1960-01-01',
    'endDate': today
	});

	$(document).on('click', '.removeme', function(){
		var current = $(this); 
		var id = current.closest('.pe-row').data('id');
		if(id)
		{
			$.ajax({			
				'url' : '/ajax/remove-education',
				'data' : { 'educationid' : id },
				'type' : 'post',
				'success' : function(response){
					current.closest('.pe-row').remove();
				}			
			});	
		}else{
			current.closest('.pe-row').remove();
		}
	});

	// Form validation
	$('#edit_profile').submit(function(){
		var found = 0;
		$(document).find('.year-input').each(function(){
			$(this).next('p.red').remove();
			if( $(this).val() && $(this).val().length<4 ){
				found++;
				$(this).after("<p class='red'>Year must be of 4 digits.</p>");
			}
		});

		if( found > 0 ){
			return false;
		}

		return true;
	});
});

var rowCount = 0;
function addMoreRows(frm) 
{
	rowCount ++;

	var educationlevel = $('#educationlevel').html();
	var specialization = $('#specialization').html();
	var graduationyears = $('#graduationyears').html();
	var country = $('#edu_country').html();
	var founderRow = '<div class="study-detail" id="rowCount'+rowCount+'"><div class=pe-row><button type="button" class="btn add-study-btn removeme" onclick="removeRow('+rowCount+');"><span class="glyphicon glyphicon-trash"></span></button><div class=row><div class="col-sm-6 lPadding"><div class=p-data-title><i class=flaticon-graduation></i>Education level</div><select class="" name="education_level[]">'+educationlevel+'</select></div><div class="col-sm-6 rPadding"><div class=p-data-title><i class=flaticon-graduation></i>Specialization</div><select class="" name="specialization[]">'+specialization+'</select></div></div><div class=row><div class="col-sm-6 lPadding"><div class=p-data-title><i class=flaticon-graduation></i>Year</div><input type="text" class="numeric year-input" name="graduation_year[]" value="" placeholder="" maxlength="4"></div><div class="col-sm-6 rPadding"><div class=p-data-title><i class=flaticon-graduation></i>Name of Establishment</div><input class=pr-edit name="education_establishment[]" placeholder=""></div></div><div class=row><div class="col-sm-4 lPadding"><div class=p-data-title><i class=flaticon-web-1></i>Country of Establishment</div><select class="country" data-put="#state" name="country_of_establishment[]">'+country+'</select></div><div class=col-sm-4><div class=p-data-title><i class=flaticon-gps></i>State of Establishment</div><select class="state" data-put="#city" name="state_of_establishment[]"><option value="">Select State</option></select></div><div class=col-sm-4><div class=p-data-title><i class=flaticon-city></i>City of Establishment</div><select name="city_of_establishment[]" class="city"><option value="">Select City</option></select></div></div></div></div>';

		/*var founderRow = '<div class="row row-outer" id="rowCount'+rowCount+'"><div class="col-sm-6"><div class="row"><div class="col-sm-6"><div class="form-group"><input type="text" class="form-control icon-field" placeholder="Name"><span class="icon user-icon"></span></div></div><div class="col-sm-6"><div class="form-group"><textarea name="" class="form-control icon-field"  placeholder="Description"></textarea><span class="icon desc-icon"></span></div></div></div></div><div class="col-sm-6"><div class="row"><div class="col-md-10"><div class="form-group"><select><option>Country drop</option><option>Option 1</option><option>Option 2</option><option>Option 3</option></select><span class="icon globe-icon"></span></div></div><div class="col-md-2"><button type="button" title="Delete row" onclick="removeRow('+rowCount+');" class="btn-icon-round center-btn del-btn"><i class="fa fa-minus"></i></button></div></div></div></div>';*/

	$('#addedRows').each(
	  function() {
	    var row_l = $(this).find('.row-outer').length;
	    if(row_l=='10'){
	    	alert('Sorry! You cannot Add more than 10 rows.');
	    }
	    else{
	    	jQuery('#addedRows').append(founderRow);
	    	jQuery('#addedRows select').val('');
	    }
	  }
	);
}
function removeRow(removeNum) {
	jQuery('#rowCount'+removeNum).remove();
}
</script>
@endsection