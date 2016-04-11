@extends('layouts.dashboard')
<?php
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
	{!! Form::open() !!}
   			 <div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="p-header-outer">
						<div class="profile-header">
							<div class="profile-img" style="background: url('/images/user-thumb.jpg');">
								<button type="button" class="edit-pr-img" title="Edit Image"><i class="glyphicon glyphicon-camera"></i></button>
							</div><!--Profile-img-->
							<div class="pr-field">
								<input type="text" class="pr-edit pr-name" value="{{$user->first_name}}">
								<input type="text" class="pr-edit pr-name" value="{{$user->last_name}}">
							</div>
							<div class="pr-field">
							<input type="text" class="pr-edit pr-location" value="{{$user->city}}" disabled="disabled">
							</div>
						</div><!--/profile header-->
					</div>

					<div class="profile-detail">
						<div class="row">
							<div class="col-md-11 col-md-offset-1">
								<div class="profile-data-table">
									<div class="pe-row">
										<div class="row">
											<div class="col-sm-6">
												<div class="p-data-title"><i class="flaticon-web-1"></i>Country</div>
											</div>
											<div class="col-sm-6">
												<select name="country" style="max-width: 180px;" id="profile_country" class="pr-edit">	
													<?php 
														foreach ($countries as $key => $value) { 
															if($user->country == $value)
																$selected = 'Selected'; 
															else
																$selected = ''; 
															?>
															<option value="{{$value}}" {{$selected}} >{{$value}}</option>	
													<?php } ?>
												</select>
											</div>
										</div>
									</div>
									<div class="pe-row">
										<div class="row">
											<div class="col-sm-6">
												<div class="p-data-title"><i class="flaticon-gps"></i>State</div>
											</div>
											<div class="col-sm-6">
												<!-- <input type="text" class="pr-edit" value="Delhi"> -->

													<select name="state" style="max-width: 180px;" id="profile_state" class="pr-edit" >
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
											</div>
										</div>
									</div>
									<div class="pe-row">
										<div class="row">
											<div class="col-sm-6">
												<div class="p-data-title"><i class="flaticon-city"></i>City</div>
											</div>
											<div class="col-sm-6">
												<select name="city" style="max-width: 180px;" id="profile_city" class="pr-edit">
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
											</div>
										</div>
									</div>
									<div class="pe-row">
										<div class="row">
											<div class="col-sm-6">
												<div class="p-data-title"><i class="flaticon-technology"></i>Contact</div>
											</div>
											<div class="col-sm-6">
												<input type="text" class="pr-edit" value="{{$user->phone_no}}">
											</div>
										</div>
									</div>
									<div class="pe-row">
										<div class="row">
											<div class="col-sm-6">
												<div class="p-data-title"><i class="flaticon-calendar"></i>Date of Birth</div>
											</div>
											<div class="col-sm-6">
												<input type="text" name="birthday" class="pr-edit datepicker" value="{{$user->birthday}}">
											</div>
										</div>
									</div>
						
									<div class="pe-row">
										<div class="row">
											<div class="col-sm-6">
												<div class="p-data-title"><i class="flaticon-black"></i>I am</div>
											</div>
											<div class="col-sm-6">
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
											<div class="col-sm-6">
												<div class="p-data-title"><i class="flaticon-circle"></i>Status</div>
											</div>
											<div class="col-sm-6">
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
											<div class="col-sm-6">
												<div class="p-data-title"><i class="flaticon-graduation"></i>Currently Studing or Not</div>
													<?php $currentlystudy = isset($education)?$education[0]['currently_studying']:''; ?>
											</div>
											<div class="col-sm-6">
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
											</div>
										</div>
									</div>
									<div class="study-data-outer">
										<div class="study-detail">
											<div class="study-heading">
												<i class="flaticon-education"></i> Qualification
											</div>
												<?php $count=0; ?>
											@foreach($education as $data)
											<?php if($count==0){  ?>

											<div class="pe-row">
												<button type="button" class="btn add-study-btn" onclick="addMoreRows(this.form);"><span class="glyphicon glyphicon-plus"></span></button>
												<div class="row">
													<div class="col-sm-4">
														<div class="p-data-title"><i class="flaticon-graduation"></i>Education level</div>
														<?php  //print_r($educationLevel);die; ?>
															<select name="education_level" style="max-width: 180px;" class="pr-edit">


																<option>Education level</option>
																<?php 

																foreach ($educationLevel as $value) {
															 			// echo $value;die;
													
																		if($value == $data->education_level)
																			$selected = 'Selected';
																		else
																			$selected = '';
														
																
																		?>
																		<option value="{{$value}}" <?php echo $selected; ?> >{{ $value }}</option>

																<?php } ?>
															</select>

													</div>
													<div class="col-sm-4">
														<div class="p-data-title"><i class="flaticon-graduation"></i>Specialization</div>
														<select name="specialization" style="max-width: 180px;" class="pr-edit">
																<option >Specialization</option>
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
													<div class="col-sm-4">
														<div class="p-data-title"><i class="flaticon-graduation"></i>Year</div>
														<select class="pr-edit">
															<option>Year</option>
														</select>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-4">
														<div class="p-data-title"><i class="flaticon-graduation"></i>Name of Establishment</div>
														<input type="text" class="pr-edit" name="" value="" placeholder="">
													</div>
													<div class="col-sm-4">
														<div class="p-data-title"><i class="flaticon-web-1"></i>Country of Establishment</div>
														<select name="country" style="max-width: 180px;" id="est_country{{$count}}" class="pr-edit">	
															<?php 
																foreach ($countries as $key => $value) { 
																	if($user->country == $value)
																		$selected = 'Selected'; 
																	else
																		$selected = ''; 
																	?>
																	<option value="{{$value}}" {{$selected}} >{{$value}}</option>	
															<?php } ?>
														</select>
													</div>
													<div class="col-sm-4">
														<div class="p-data-title"><i class="flaticon-gps"></i>State of Establishment</div>
														<select name="state" style="max-width: 180px;" id="est_state{{$count}}" class="pr-edit" >
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
													</div>
													<div class="col-sm-4">
														<div class="p-data-title"><i class="flaticon-city"></i>City of Establishment</div>
														<select name="city" style="max-width: 180px;" id="est_city{{$count}}" class="pr-edit">
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
													</div>
												</div> 	 	
											</div>
										</div>

									<?php } if($count>0){ 
										   $divid="rowCount".$count;
												?>
								<div id="addedRows">
								<div id="{{$divid}}" class="study-detail">
								<div class="pe-row">
						<button type="button" class="btn add-study-btn" onclick="removeRow({{$count}});"><span class="glyphicon glyphicon-trash"></span></button>
												<div class="row">
													<div class="col-sm-4">
														<div class="p-data-title"><i class="flaticon-graduation"></i>Education level</div>
															<select name="education_level" style="max-width: 180px;" class="pr-edit">
																<option>Education level</option>
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
														<select name="specialization" style="max-width: 180px;" class="pr-edit">
																<option >Specialization</option>
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
													<div class="col-sm-4">
														<div class="p-data-title"><i class="flaticon-graduation"></i>Year</div>
														<select class="pr-edit">
															<option>Year</option>
														</select>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-4">
														<div class="p-data-title"><i class="flaticon-graduation"></i>Name of Establishment</div>
														<input type="text" class="pr-edit" name="" value="" placeholder="">
													</div>
													<div class="col-sm-4">
														<div class="p-data-title"><i class="flaticon-web-1"></i>Country of Establishment</div>
														<select name="country" style="max-width: 180px;" id="est_country{{$count}}" class="pr-edit">	
															<?php 
																foreach ($countries as $key => $value) { 
																	if($user->country == $value)
																		$selected = 'Selected'; 
																	else
																		$selected = ''; 
																	?>
																	<option value="{{$value}}" {{$selected}} >{{$value}}</option>	
															<?php } ?>
														</select>
													</div>
													<div class="col-sm-4">
														<div class="p-data-title"><i class="flaticon-gps"></i>State of Establishment</div>
														<select name="state" style="max-width: 180px;" id="est_state{{$count}}" class="pr-edit" >
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
													</div>
													<div class="col-sm-4">
														<div class="p-data-title"><i class="flaticon-city"></i>City of Establishment</div>
														<select name="city" style="max-width: 180px;" id="est_city{{$count}}" class="pr-edit">
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
													</div>
												</div> 	 	
											</div>
										</div>
										</div>
										<?php } $count++;?>
										@endforeach
										
									</div>
									
									<div class="pe-row">
										<div class="row">
											<div class="col-sm-6">
												<div class="p-data-title"><i class="flaticon-vintage"></i>Current profession Industry</div>
											</div>
											<div class="col-sm-6">
												<div class="slt-cont">
												<select name="job_area" style="max-width: 180px;" class="pr-edit" id="jobarea">
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
													<select name="job_category" style="max-width: 180px;" id="jobcategory" class="pr-edit">
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
											</div>
										</div>
									</div>
									<div class="pe-row">
										<div class="row">
											<div class="col-sm-6">
												
											</div>
											<div class="col-sm-6">
												
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
				{!!Form::close()!!}
   			 </div><!--/page center data-->
				<div class="shadow-box bottom-ad"><img src="/images/bottom-ad.jpg" alt="" class="img-responsive"></div>
			</div>
			@include('panels.right')
		</div>
	</div>
</div>
@endsection
<script type="text/javascript" src="/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript">

 var count="<?php echo $count-1; ?>";

	$(document).on('click', '#profile_country', function(){
 
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
		$(document).on('click', '#profile_state', function(){
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
		var i=0;

	
	$(document).on('change', '#est_country'+count, function(){
 
			var countryId = $(this).val();
			alert(i);
			var _token = $('#searchform input[name=_token]').val();
			$.ajax({			
				'url' : '/ajax/getstates',
				'data' : { 'countryId' : countryId, '_token' : _token },
				'type' : 'post',
				'success' : function(response){				
					$('#est_state'+count).html(response);
				}			
			});	
		});


		/**
		*	Get cities ajax call handling.
		*	Ajaxcontroller@getCities
		*/
		$(document).on('change', '#est_state'+count, function(){
			var stateId = $(this).val();
			var _token = $('#searchform input[name=_token]').val();
			$.ajax({			
				'url' : '/ajax/getcities',
				'data' : { 'stateId' : stateId, '_token' : _token },
				'type' : 'post',
				'success' : function(response){
					$('#est_city'+count).html(response);
				}			
			});	
		});





	$(function () {
      $('.datepicker').datepicker({

      });
  });

  var rowCount = count;
	function addMoreRows(frm) {
	
	rowCount ++;

	var founderRow = '<div class="study-detail" id="rowCount'+rowCount+'"><div class=pe-row><button type="button" class="btn add-study-btn" onclick="removeRow('+rowCount+');"><span class="glyphicon glyphicon-trash"></span></button><div class=row><div class=col-sm-4><div class=p-data-title><i class=flaticon-graduation></i>Education level</div><select class=pr-edit><option>Level</option></select></div><div class=col-sm-4><div class=p-data-title><i class=flaticon-graduation></i>Specialization</div><select class=pr-edit><option>Option</option></select></div><div class=col-sm-4><div class=p-data-title><i class=flaticon-graduation></i>Year</div><select class=pr-edit><option>Year</option></select></div></div><div class=row><div class=col-sm-4><div class=p-data-title><i class=flaticon-graduation></i>Name of Establishment</div><input class=pr-edit name=""placeholder=""></div><div class=col-sm-4><div class=p-data-title><i class=flaticon-web-1></i>Country of Establishment</div><select class=pr-edit><option>Option</option></select></div><div class=col-sm-4><div class=p-data-title><i class=flaticon-gps></i>State of Establishment</div><select class=pr-edit><option>Year</option></select></div><div class=col-sm-4><div class=p-data-title><i class=flaticon-city></i>City of Establishment</div><select class=pr-edit><option>Year</option></select></div></div></div></div>';

	
	$('#addedRows').each(
	  function() {
	    var row_l = $(this).find('.row-outer').length;
	    if(row_l=='10'){
	    	alert('Sorry! You cannot Add more than 10 rows.');
	    }
	    else{
	    	jQuery('#addedRows').append(founderRow);
	    }
	  }
	
	);
	}
	function removeRow(removeNum) {
	jQuery('#rowCount'+removeNum).remove();
	}

</script>
