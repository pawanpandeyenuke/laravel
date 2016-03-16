@extends('layouts.dashboard')

@section('content')

<?php

$countryname = App\Country::where( 'country_id', '=', $model['country'])->value('country_name');
// echo '<pre>';print_r($countryname);die; 
?>
<div class="page-data dashboard-body">
		<div class="container">
			<div class="row">

			@include('panels.left')

			<div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
				
					<div class="p-header-outer">
						
<!-- 						<button type="button" class="edit-profile" title="Edit Profile"><i class="fa fa-pencil"></i></button>
						<button type="button" class="save-profile-changes" title="Save Profile"><i class="fa fa-check-circle"></i></button> -->
						<div class="profile-header">
							<div class="profile-img" style="background: url('/images/user-thumb.jpg');">
								<button type="button" class="edit-pr-img" title="Edit Image"><i class="glyphicon glyphicon-camera"></i></button>

							</div><!--Profile-img-->
							<div class="pr-field">
 
										 {!! Form::model($model, array($model->id)) !!} 							
								{!! Form::text('first_name', null, array(
										'class'=>'pr-edit pr-name name1',
										'disabled'=>'disabled',
										)) !!}

											{!! Form::text('last_name', null, array(
										'class'=>'pr-edit pr-name name2',
										'disabled'=>'disabled',
										)) !!}
							</div>
							<div class="pr-field">
								<!-- <input type="text" class="pr-edit pr-location" disabled="disabled" value=""> -->
								{!! Form::text('gender', null, array(
										'class'=>'pr-edit pr-location',
										'disabled'=>'disabled',
										)) !!}

							</div>
						</div><!--/profile header-->
					</div>

					<div class="profile-detail">
						<div class="row">
							<div class="col-md-10 col-md-offset-2">
								<div class="profile-data-table">
									<div class="table-responsive">
										<table class="table">
											<tr>
												<td><div class="p-data-title"><i class="flaticon-web-1"></i>Country</div></td>
												<td>
												
												{{ Form::text('country', $countryname, ['class' => 'pr-edit country','disabled'=>'disabled']) }}
												</td>
											</tr>
											<tr>
												<td><div class="p-data-title"><i class="flaticon-gps"></i>State</div></td>
												<td><!-- <input type="text" class="pr-edit state" disabled="disabled" value=""> -->
									{!! Form::text('state', null, array(
										'class'=>'pr-edit pr-location state',
										'disabled'=>'disabled',
										)) !!}
																						
												</td>
											</tr>
											<tr>
												<td><div class="p-data-title"><i class="flaticon-city"></i>City</div></td>
												<td><!-- <input type="text" class="pr-edit city" disabled="disabled" value=""> -->
												{!! Form::text('city', null, array(
										'class'=>'pr-edit pr-location city',
										'disabled'=>'disabled',
										)) !!}
													

												</td>
											</tr>
											<tr>
												<td><div class="p-data-title"><i class="flaticon-letter133"></i>Email</div></td>
												<td><!-- <input type="text" class="pr-edit email" disabled="disabled" value=""> -->
															{!! Form::text('email', null, array(
																'class'=>'pr-edit email',
																'disabled'=>'disabled',
															)) !!}

												</td>
											</tr>
											<tr>
												<td><div class="p-data-title"><i class="flaticon-technology"></i>Contact</div></td>
												<td><!-- <input type="text" class="pr-edit contact" disabled="disabled" value=""> -->
												{!! Form::text('phone_no', null, array(
																'class'=>'pr-edit contact',
																'disabled'=>'disabled',
															)) !!}

												</td>
											</tr>
											<tr>
												<td><div class="p-data-title"><i class="flaticon-calendar"></i>Date of Birth</div></td>
												<td><time><!-- <input type="text" class="pr-edit dob" disabled="disabled" value=""> -->
														{!! Form::text('birthday', null, array(
																'class'=>'pr-edit dob',
																'disabled'=>'disabled',
															)) !!}

												</time></td>
											</tr>
<!-- 											<tr>
												<td><div class="p-data-title"><i class="flaticon-padlock50"></i>Change Password</div></td>
												<td><input type="password" class="pr-edit" disabled="disabled" value=""></td>
											</tr>
											<tr>
												<td><div class="p-data-title"><i class="flaticon-black"></i>I am</div></td>
												<td>
													<div class="clearfix">
														<div class="radio-cont pull-left center-label">
															<input type="radio" name="iam" id="radio1" checked="checked" class="css-checkbox" >
															<label for="radio1" class="css-label radGroup1" >Male</label>
														</div>
														<div class="radio-cont pull-left center-label">
															<input type="radio" name="iam" id="radio2" class="css-checkbox" value="Female">
															<label for="radio2" class="css-label radGroup1" >Female</label>
														</div>
													</div>

												</td>
											</tr> -->
										</table>
									</div>
								</div><!--/profile-data-table-->
							</div>
						</div>
					</div><!--/profile detail-->
					{!!Form::close()!!}
					</div><!--/page center data-->
				<div class="shadow-box bottom-ad"><img class="img-responsive" alt="" src="/images/bottom-ad.jpg"></div>
			</div>

			@include('panels.right')

			</div>
		</div>
	</div><!--/pagedata-->
 
@endsection