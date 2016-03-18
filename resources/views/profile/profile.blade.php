@extends('layouts.dashboard')

@section('content')

<?php  
	
$gender = $model->gender;
       $status=$model->marital_status;
       
       $stateid= array_search($model['state'], $states);
       $cityid=array_search($model['city'], $cities);

  $categoryid=array_search($education['job_category'], $job_category); 
       $jarea = array_combine(range(1, count($jobarea)), array_values($jobarea));
     
       $current=$education['currently_studying'];
      
        // echo "<pre>";print_r($job_category);die; 

unset($countries[0]);

?>
<div class="page-data dashboard-body">
		<div class="container">
			<div class="row">

			@include('panels.left')

			<div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					{!! Form::model($model, array($model->id, 'id' => 'profile-save')) !!} 	
					<div class="p-header-outer">
						<button type="button" class="edit-profile" title="Edit Profile"><i class="fa fa-pencil"></i></button>
						<button type="button" class="save-profile-changes" title="Save Profile"><i class="fa fa-check-circle"></i></button>
						<div class="profile-header">
							<div class="profile-img" style="background: url('/images/user-thumb.jpg');">
								<button type="button" class="edit-pr-img" title="Edit Image"><i class="glyphicon glyphicon-camera"></i></button>
							</div><!--Profile-img-->
							<div class="pr-field">
								{!! Form::text('first_name', null, array('class'=>'pr-edit pr-name name1', 'disabled'=>'disabled', )) !!}
								{!! Form::text('last_name', null, array('class'=>'pr-edit pr-name name2','disabled'=>'disabled',)) !!}
							</div>
<!-- 							<div class="pr-field">
								<input type="text" class="pr-edit pr-location" disabled="disabled" value="New Delhi">
							</div> -->
						</div><!--/profile header-->
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
													<!-- <input type="text" class="pr-edit" disabled="disabled" value="India"> -->
													{!! Form::select(null, $countries, $model['country'], array('class' => 'pr-edit search-field country','id' => 'changecountry','disabled'=>'disabled'));!!}								
												</td>
											</tr>
											<tr>
												<td><div class="p-data-title"><i class="flaticon-gps"></i>State</div></td>

												<td>
													<!-- <input type="text" class="pr-edit" disabled="disabled" value="Delhi"> -->
													{!! Form::select(null, $states, $stateid, array('class' => 'pr-edit search-field state','id' => 'changestate','disabled'=>'disabled')); !!}
												</td>
											</tr>
											<tr>
												<td><div class="p-data-title"><i class="flaticon-city"></i>City</div></td>

												<td>
													<!-- <input type="text" class="pr-edit" disabled="disabled" value="New Delhi"> -->
													{!! Form::select(null, $cities, $cityid, array('class' => 'search-field city pr-edit','id' => 'changecity','disabled'=>'disabled')); !!}

											</td>
											</tr>
											<tr>
												<td><div class="p-data-title"><i class="flaticon-letter133"></i>Email</div></td>
												<td>
													<!-- <input type="text" class="pr-edit" disabled="disabled" value="amikoehler@gmail.com"> -->
													{!! Form::text('email', null, array('class'=>'email', 'disabled'=>'disabled',)) !!}
												</td>
											</tr>
											<tr>
												<td><div class="p-data-title"><i class="flaticon-technology"></i>Contact</div></td>
												<td>
													<!-- <input type="text" class="pr-edit" disabled="disabled" value="9955512345"> -->
													{!! Form::text('phone_no', null, array('class'=>'pr-edit contact','disabled'=>'disabled',)) !!}
												</td>
											</tr>
											<tr>
												<td><div class="p-data-title"><i class="flaticon-calendar"></i>Date of Birth</div></td>
												<td>
													<time>
														{!! Form::text('birthday', null, array('class'=>'pr-edit birthday','disabled'=>'disabled',)) !!}
													</time>
													<!-- <input type="text" class="pr-edit" disabled="disabled" value="27 Aug 1989"> -->
												</td>
											</tr>
<!-- 											<tr>
												<td><div class="p-data-title"><i class="flaticon-padlock50"></i>Change Password</div></td>
												<td>
													<input type="password" class="pr-edit" disabled="disabled" value="123456">
												</td>
											</tr> -->
											<tr>
												<td><div class="p-data-title"><i class="flaticon-black"></i>I am</div></td>
												<td> 
												<?php if($gender=='Male') { ?>
													<div class="clearfix">
														<div class="radio-cont pull-left center-label"> 
															<input type="radio" name="iam" id="radio1" class="css-checkbox pr-edit" checked value='Male'>
															<label for="radio1" class="css-label radGroup1">Male</label>
														</div>
														<div class="radio-cont pull-left center-label">
															<input type="radio" name="iam" id="radio2" class="pr-edit css-checkbox" value='Female'>
															<label for="radio2" class="css-label radGroup1" >Female</label>
														</div>
													</div>
													<?php } else { ?>
													<div class="clearfix">
														<div class="radio-cont pull-left center-label"> 
															<input type="radio" name="iam" id="radio1" class="pr-edit css-checkbox"  value='Male'>
															<label for="radio1" class="css-label radGroup1">Male</label>
														</div>
														<div class="radio-cont pull-left center-label">
															<input type="radio" name="iam" id="radio2" class="pr-edit css-checkbox" checked value='Female'>
															<label for="radio2" class="css-label radGroup1" >Female</label>
														</div>
													</div>
													<?php } ?>

												</td>
											</tr>
											<tr>
												<td><div class="p-data-title"><i class="flaticon-circle"></i>Status</div></td>
												<td>
												<?php if($status=='Single') { ?>
													<div class="clearfix">
														<div class="radio-cont pull-left center-label">
															<input type="radio" name="status" id="radio3" checked class="pr-edit css-checkbox" value="Single">
															<label for="radio3" class="css-label radGroup1">Single</label>
														</div>
														<div class="radio-cont pull-left center-label">
															<input type="radio" name="status" id="radio4" class="pr-edit css-checkbox" value="Married">
															<label for="radio4" class="css-label radGroup1">Married</label>
														</div>
													</div>
													<?php }else{ ?>
													 <div class="clearfix">
														<div class="radio-cont pull-left center-label">
															<input type="radio" name="status" id="radio3" class="pr-edit css-checkbox" value="Single">
															<label for="radio3" class="css-label radGroup1">Single</label>
														</div>
														<div class="radio-cont pull-left center-label">
															<input type="radio" name="status" id="radio4" checked class="pr-edit css-checkbox" value="Married">
															<label for="radio4" class="css-label radGroup1">Married</label>
														</div>
													</div>
													<?php } ?>
												</td>
											</tr>
											<tr>
												<td><div class="p-data-title"><i class="flaticon-education"></i>Qualification</div></td>
												<td>
													<!-- <div class="slt-cont">
														<select class="pr-edit" disabled="disabled">
															<option>Education level</option>
														</select> -->
														{!! Form::select('educationlevel',$educationLevel,$education['education_level'],array('class' => 'pr-edit educationlevel','disabled'=>'disabled'));!!}		
														<!-- <select class="pr-edit" disabled="disabled">
															<option >Specialization</option>
														</select> -->
														{!! Form::select('specialization', $specialization,$education['specialization'], array('class' => 'pr-edit specialization','disabled'=>'disabled'));!!}		
													</div>
												</td>
											</tr>
											<tr>
												<td><div class="p-data-title"><i class="flaticon-graduation"></i>Graduation Year</div></td>
												<td>
													<table class="inner-table">
														<tr>
															<td><!-- <input type="text" class="pr-edit datepicker" disabled="disabled" value="21/05/2015"> -->
															{!! Form::select('gradyearfrom', $gradYear,$education['graduation_year_from'], array('class' => 'pr-edit datepicker gradyearfrom','disabled'=>'disabled'));!!}	
															</td>
															<td>To&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
															<td><!-- <input type="text" class="pr-edit datepicker" disabled="disabled" value="21/05/2016"> -->
															{!! Form::select('gradyearto', $gradYear,$education['graduation_year_to'], array('class' => 'pr-edit datepicker gradyearto','disabled'=>'disabled'));!!}
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td><div class="p-data-title"><i class="flaticon-graduation"></i>Currently Studing or Not</div></td>
												<td>
												<?php if($current=="Yes"){ ?>
													<div class="clearfix">
														<div class="radio-cont pull-left center-label">
															<input type="radio" name="study" id="radios1" checked class="css-checkbox pr-edit" value="Yes">
															<label for="radios1" class="css-label radGroup1">Yes</label>
														</div>
														<div class="radio-cont pull-left center-label">
															<input type="radio" name="study" id="radios2" class="css-checkbox pr-edit" value="No">
															<label for="radios2" class="css-label radGroup1">&nbsp;No&nbsp;</label>
														</div>
													</div>

													<?php }else{ ?>
													<div class="clearfix">
														<div class="radio-cont pull-left center-label">
															<input type="radio" name="study" id="radios1" class="css-checkbox pr-edit" value="Yes">
															<label for="radios1" class="css-label radGroup1">Yes</label>
														</div>
														<div class="radio-cont pull-left center-label">
															<input type="radio" name="study" id="radios2" checked class="css-checkbox pr-edit" value="No">
															<label for="radios2" class="css-label radGroup1">&nbsp;No&nbsp;</label>
														</div>
													</div>
													<?php } ?>

												</td>
											</tr>
											<tr>
												<td><div class="p-data-title"><i class="flaticon-graduation"></i>Name of Education Establishment</div></td>
												<td>
												<!-- <input type="text" class="pr-edit" disabled="disabled" value="C.B.S.E"> -->

													{!! Form::text('', $education['education_establishment'], array('class'=>'pr-edit educationestablishment', 'disabled'=>'disabled', )) !!}
												</td>
											</tr>
											<tr>
												<td><div class="p-data-title"><i class="flaticon-web-1"></i>Country of Establishment</div></td>
												<td><!-- <input type="text" class="pr-edit" disabled="disabled" value="India"> -->
												{!! Form::select(null, $countries, $education['country_of_establishment'], array('class' => 'pr-edit search-field educationcountry','id' => 'educationcountry','disabled'=>'disabled'));!!}
												</td>
											</tr>
<!-- 											<tr>
												<td><div class="p-data-title"><i class="flaticon-city"></i>City of Establishment</div></td>
												<td><input type="text" class="pr-edit" disabled="disabled" value="New Delhi"></td>
											</tr> -->
											<tr>
												<td><div class="p-data-title"><i class="flaticon-vintage"></i>Current profession Industry</div></td>
												<td>
													<div class="slt-cont">
														{!! Form::select('jobarea', $jarea,$education['job_area'], array('class' => 'pr-edit jobarea','disabled'=>'disabled'));!!}	
														<!-- <select class="pr-edit" disabled="disabled">
															<option >Job Category</option>
														</select> -->

														<select class="pr-edit jobcategory" id='jobcategory' disabled="disabled">
														<option selected="selected">{{$education['job_category']}}</option>

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
					{!!Form::close()!!}
				</div><!--/page center data-->
				<div class="shadow-box bottom-ad"><img src="images/bottom-ad.jpg" alt="" class="img-responsive"></div>
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
	});
	$(document).on('click','.save-profile-changes',function(){
		$('.pr-edit').prop('disabled', true);
		$(this).hide();
		$('.edit-profile').show();
		$('button.edit-pr-img').hide();
	});

		$('#changecountry').change(function(){
		var countryId = $(this).val();
		var _token = $('#searchform input[name=_token]').val();
		$.ajax({			
			'url' : '/ajax/getstates',
			'data' : { 'countryId' : countryId, '_token' : _token },
			'type' : 'post',
			'success' : function(response){				
				$('#changestate').html(response);
			}			
		});	
	});

	$('#changestate').change(function(){
		var stateId = $(this).val();
		var _token = $('#searchform input[name=_token]').val();
		$.ajax({			
			'url' : '/ajax/getcities',
			'data' : { 'stateId' : stateId, '_token' : _token },
			'type' : 'post',
			'success' : function(response){
				$('#changecity').html(response);
			}			
		});	
	});

	$('#educationcountry').change(function(){
		var countryId = $(this).val();
		var _token = $('#searchform input[name=_token]').val();
		$.ajax({			
			'url' : '/ajax/getstates',
			'data' : { 'countryId' : countryId, '_token' : _token },
			'type' : 'post',
			'success' : function(response){				
				$('#educationstate').html(response);
			}			
		});	
	});
	
</script>
@endsection