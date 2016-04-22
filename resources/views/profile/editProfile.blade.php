@extends('layouts.dashboard')

@section('content')

<?php
// print_r($user->country);die;
	if(!empty($user->country)){

		//$country = DB::table('country')->where('country_id', '=', $user->country)->value('country_name'); 

		//$all_states = DB::table('state')->where('country_id', '=', $user->country)->pluck('state_name','state_id'); 

		//$stateid = DB::table('city')->where('city_name', '=', $user->city)->value('state_id'); 

	 	//$all_cities = DB::table('city')->where('state_id', '=', $stateid)->pluck('city_name', 'city_id'); 

		$countryid = DB::table('country')->where('country_name', '=', $user->country)->value('country_id'); 
		$all_states = DB::table('state')->where('country_id', '=', $countryid)->pluck('state_name','state_id'); 

		$stateid = DB::table('city')->where('city_name', '=', $user->city)->value('state_id'); 
	 	$all_cities = DB::table('city')->where('state_id', '=', $stateid)->pluck('city_name', 'city_id'); 	

		

	}

	$gender = isset($user->gender) ? $user->gender : '';
	$maritalstatus = isset($user->marital_status) ? $user->marital_status : ''; 
	$currentlystudying = isset($user->currently_studying) ? $user->currently_studying : ''; 

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
				{!! Form::open(array('files' => true)) !!}
				<div class="shadow-box page-center-data no-margin-top">
					@if (Session::has('success'))
						<div class="alert alert-success">{!! Session::get('success') !!}</div>
					@endif
					@if (Session::has('error'))
						<div class="alert alert-danger">{!! Session::get('error') !!}</div>
					@endif

					
					<div class="p-header-outer">
						<div class="profile-header">
							<?php $userpic = !empty($user->picture) ? $user->picture : '/images/user-thumb.jpg'; ?>
							<div id="profile-pic-holder" class="profile-img" style="background: url('{{ $userpic }}');">
								<!-- <button type="button" class="edit-pr-img" title="Edit Image"><i class="glyphicon glyphicon-camera"></i></button> -->
							</div><!--Profile-img-->
							<input type="file" id="profilepicture" name="picture" class="filestyle" data-input="false" data-iconName="glyphicon glyphicon-camera" data-buttonText="" data-buttonName="edit-pr-img">
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
											<div class="col-sm-6">
												<div class="p-data-title"><i class="flaticon-web-1"></i>Country</div>
											</div>
											<div class="col-sm-6">
												<select name="country" style="max-width: 180px;" id="profile_country" >	
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
											<div class="col-sm-6">
												<div class="p-data-title"><i class="flaticon-gps"></i>State</div>
											</div>
											<div class="col-sm-6">
												<select name="state" style="max-width: 180px;" id="profile_state">
													<option value="">State</option>	
													<?php 
													if(isset($all_states) && isset($user->state)){
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
											<div class="col-sm-6">
												<div class="p-data-title"><i class="flaticon-city"></i>City</div>
											</div>
											<div class="col-sm-6">
												<select name="city" style="max-width: 180px;" id="profile_city" >
													<option value="">City</option>	
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
											<div class="col-sm-6">
												<div class="p-data-title"><i class="flaticon-technology"></i>Contact</div>
											</div>
											<div class="col-sm-6">
												<input type="text" name="phone_no" class="pr-edit numeric" maxlength="15" value="{{ $user->phone_no }}">
											</div>
										</div>
									</div>
									<div class="pe-row">
										<div class="row">
											<div class="col-sm-6">
												<div class="p-data-title"><i class="flaticon-calendar"></i>Date of Birth</div>
											</div>
											<div class="col-sm-6">
												<input type="text" name="birthday" class="pr-edit datepicker" value="{{ $user->birthday }}">
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
											</div>
											<div class="col-sm-6">
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
		$countryidestab = DB::table('country')->where('country_name', '=', $data->country_of_establishment)->value('country_id'); 
		$all_states_estab = DB::table('state')->where('country_id', '=', $countryidestab)->pluck('state_name','state_id'); 

		$stateidestab = DB::table('city')->where('city_name', '=', $data->city_of_establishment)->value('state_id'); 
	 	$all_cities_estab = DB::table('city')->where('state_id', '=', $stateidestab)->pluck('city_name', 'city_id'); 	
?>
													<div class="pe-row" data-id="<?php echo $data->id; ?>">
														@if($customcount > 1)
															<button type="button" class="btn add-study-btn removeme"><span class="glyphicon glyphicon-trash"></span></button>
														@endif
															<div class="row">
																<div class="col-sm-4">
																	<div class="p-data-title"><i class="flaticon-graduation"></i>Education level</div>
																		<select id="educationlevel" name="education_level[]" style="max-width: 180px;" >
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
																		<select name="specialization[]" id="specialization" style="max-width: 180px;" >
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
																	<select id="graduationyears" name="graduation_year[]">
																		<!-- <option>Year</option> -->
																		@foreach($gradYear as $valuedata)
																			<option value="{{$valuedata}}">{{ $valuedata }}</option>
																		@endforeach
																	</select>
																</div>
															</div>
															<div class="row">
																<div class="col-sm-4">
																	<div class="p-data-title"><i class="flaticon-graduation"></i>Name of Establishment</div>
																	
																	<input type="text" class="" name="education_establishment[]" value="{{$data->education_establishment}}" placeholder="">
																</div>
																<div class="col-sm-4">
																	<div class="p-data-title"><i class="flaticon-web-1"></i>Country of Establishment</div>
																	
																	<select name="country_of_establishment[]" class="country" id="edu_country" data-put="#state">
																		@foreach($countries as $countrydata)
																			<?php if($data->country_of_establishment == $countrydata)
																					$selected = 'Selected'; 
																				else
																					$selected = ''; ?>
																			<option value="<?php echo $countrydata; ?>" <?php echo $selected; ?>><?php echo $countrydata; ?></option>
																		@endforeach
																	</select>
																</div>
																<div class="col-sm-4">
																	<div class="p-data-title"><i class="flaticon-gps"></i>State of Establishment</div>
																	<select name="state_of_establishment[]" class="state" data-put="#city">
																		@foreach($all_states_estab as $value)
																		<?php if($data->state_of_establishment == $value)
																				$selected = 'Selected'; 
																			else
																				$selected = ''; ?>
																		<option value="{{$value}}" <?php echo $selected; ?> >{{$value}}</option>	
																		@endforeach
																	</select>
																</div>
																<div class="col-sm-4">
																	<div class="p-data-title"><i class="flaticon-city"></i>City of Establishment</div>
																	<select name="city_of_establishment[]" class="city" >
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
															<select name="education_level[]" style="max-width: 180px;" >
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
															<select name="specialization[]" style="max-width: 180px;" >
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
														<select class="" name="graduation_year[]">
															<option>Year</option>
															@foreach($gradYear as $valuedata)
																<option value="{{$valuedata}}">{{ $valuedata }}</option>
															@endforeach
														</select>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-4">
														<div class="p-data-title"><i class="flaticon-graduation"></i>Name of Establishment</div>
														<input type="text" name="education_establishment[]" value="" placeholder="">
													</div>
													<div class="col-sm-4">
														<div class="p-data-title"><i class="flaticon-web-1"></i>Country of Establishment</div>
														<select name="country_of_establishment[]" class="country" data-put="#state">
															@foreach($countries as $countrydata)
																<option value="<?php echo $countrydata; ?>" <?php echo $selected; ?> ><?php echo $countrydata; ?></option>
															@endforeach
														</select>
													</div>
													<div class="col-sm-4">
														<div class="p-data-title"><i class="flaticon-gps"></i>State of Establishment</div>
														<select name="state_of_establishment[]" class="state" data-put="#city">
															<option>Option</option>
														</select>
													</div>
													<div class="col-sm-4">
														<div class="p-data-title"><i class="flaticon-city"></i>City of Establishment</div>
														<select name="city_of_establishment[]" class="city">
															<option>Option</option>
														</select>
													</div>
												</div> 
											@endif
										</div>
										<div id="addedRows"></div>
										
									</div>
									
									<div class="pe-row">
										<div class="row">
											<div class="col-sm-6">
												<div class="p-data-title"><i class="flaticon-vintage"></i>Current profession Industry</div>
											</div>
											<div class="col-sm-6">
												<div class="slt-cont">
													<select name="job_area" style="max-width: 180px;" id="jobarea" >
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
 
													<select name="job_category" style="max-width: 180px;" id="jobcategory" >
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
				</div><!--/page center data-->
				<div class="shadow-box bottom-ad"><img src="/images/bottom-ad.jpg" alt="" class="img-responsive"></div>
				{!!Form::close()!!}
			</div>
			@include('panels.right')

			</div>
		</div>
	</div>



<script type="text/javascript">

	$(document).ready(function(){
		$(document).on('change', '.country', function(){
			var current = $(this);
			var countryId = current.val();
			$.ajax({			
				'url' : '/ajax/getstates',
				'data' : { 'countryId' : countryId },
				'type' : 'post',
				'success' : function(response){	
					current.closest('.pe-row').find('.state').html(response);
					// $('.state').html(response);
				}			
			});	
		});
		$(document).on('change', '.state', function(){
			var current = $(this);
			var stateId = current.val();
			$.ajax({			
				'url' : '/ajax/getcities',
				'data' : { 'stateId' : stateId },
				'type' : 'post',
				'success' : function(response){	
					current.closest('.pe-row').find('.city').html(response);			
					// $('.city').html(response);
				}			
			});	
		});

		/*  -------------------------  country state city  -------------------------  */ 

		$(document).on('change', '#profile_country', function(){
			var countryId = $(this).val();
			var _token = $('#searchform input[name=_token]').val();

			if(countryId != ''){
				// alert(countryId);
				$.ajax({			
					'url' : '/ajax/getstates',
					'data' : { 'countryId' : countryId, '_token' : _token },
					'type' : 'post',
					'success' : function(response){				
						$('#profile_state').html(response);
					}			
				});	
			}else{
				$('#profile_state').html('<option>State</option>');
				$('#profile_city').html('<option>City</option>');
			}
		});

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
				$('#profile_city').html('<option>City</option>');
			}
		});

		$('.datepicker').datepicker();
	
	});


 	$(function () {
		$('.datepicker').datepicker({

		});
	});


	$(document).on('click', '.removeme', function(){

		var current = $(this); 
		var id = current.closest('.pe-row').data('id');

		if(id){
			$.ajax({			
				'url' : '/ajax/remove-education',
				'data' : { 'educationid' : id },
				'type' : 'post',
				'success' : function(response){
					// alert(id);
					current.closest('.pe-row').remove();
				}			
			});	
		}else{
			current.closest('.pe-row').remove();
		}
		// alert(id);
	});


  var rowCount = 0;
	function addMoreRows(frm) {

	rowCount ++;

	var educationlevel = $('#educationlevel').html();
	var specialization = $('#specialization').html();
	var graduationyears = $('#graduationyears').html();
	var country = $('#edu_country').html();
	// console.log(country);
	var founderRow = '<div class="study-detail" id="rowCount'+rowCount+'"><div class=pe-row><button type="button" class="btn add-study-btn" onclick="removeRow('+rowCount+');"><span class="glyphicon glyphicon-trash"></span></button><div class=row><div class=col-sm-4><div class=p-data-title><i class=flaticon-graduation></i>Education level</div><select class="" name="education_level[]">'+educationlevel+'</select></div><div class=col-sm-4><div class=p-data-title><i class=flaticon-graduation></i>Specialization</div><select class="" name="specialization[]">'+specialization+'</select></div><div class=col-sm-4><div class=p-data-title><i class=flaticon-graduation></i>Year</div><select class="" name="graduation_year[]">'+graduationyears+'</select></div></div><div class=row><div class=col-sm-4><div class=p-data-title><i class=flaticon-graduation></i>Name of Establishment</div><input class=pr-edit name="education_establishment[]" placeholder=""></div><div class=col-sm-4><div class=p-data-title><i class=flaticon-web-1></i>Country of Establishment</div><select class="country" data-put="#state" name="country_of_establishment[]">'+country+'</select></div><div class=col-sm-4><div class=p-data-title><i class=flaticon-gps></i>State of Establishment</div><select class="state" data-put="#city" name="state_of_establishment[]"><option>Option</option></select></div><div class=col-sm-4><div class=p-data-title><i class=flaticon-city></i>City of Establishment</div><select name="city_of_establishment[]" class="city"><option>Option</option></select></div></div></div></div>';

	/*var founderRow = '<div class="row row-outer" id="rowCount'+rowCount+'"><div class="col-sm-6"><div class="row"><div class="col-sm-6"><div class="form-group"><input type="text" class="form-control icon-field" placeholder="Name"><span class="icon user-icon"></span></div></div><div class="col-sm-6"><div class="form-group"><textarea name="" class="form-control icon-field"  placeholder="Description"></textarea><span class="icon desc-icon"></span></div></div></div></div><div class="col-sm-6"><div class="row"><div class="col-md-10"><div class="form-group"><select><option>Country drop</option><option>Option 1</option><option>Option 2</option><option>Option 3</option></select><span class="icon globe-icon"></span></div></div><div class="col-md-2"><button type="button" title="Delete row" onclick="removeRow('+rowCount+');" class="btn-icon-round center-btn del-btn"><i class="fa fa-minus"></i></button></div></div></div></div>';*/

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
@endsection
