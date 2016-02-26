@extends('layouts.dashboard')
<?php  
// echo '<pre>';print_r($feeds);die('view');
?>
@section('content')

	@if (Session::has('error'))
		<div class="alert alert-danger">{!! Session::get('error') !!}</div>
	@endif
	@if (Session::has('success'))
		<div class="alert alert-success">{!! Session::get('success') !!}</div>
	@endif


	<div class="page-data dashboard-body">
		<div class="container">
			<div class="row">

			@include('panels.left')

			<div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="page-title">
						<i class="flaticon-tool"></i>Privacy Setting
					</div>
					<div class="row">
						<div class="col-md-8 col-md-offset-3">
							<h4>Who can contact me?</h4>
							<div class="radio-outer-full">
								<div class="row">
									<div class="col-sm-8 col-sm-offset-3">
										<div class="radio-cont radio-label-left">
											<input type="radio" name="contact-req" id="radio1" checked="checked" class="css-checkbox" />
											<label for="radio1" class="css-label radGroup1">Friends of friends</label>
										</div>
										<div class="radio-cont radio-label-left">
											<input type="radio" name="contact-req" id="radio2" class="css-checkbox" />
											<label for="radio2" class="css-label radGroup1">Nearby app user</label>
										</div>
										<div class="radio-cont radio-label-left">
											<input type="radio" name="contact-req" id="radio3" class="css-checkbox" />
											<label for="radio3" class="css-label radGroup1">All</label>
										</div>
									</div>
								</div>
							</div>

							<h4>Who can send me friends requests?</h4>
							<div class="radio-outer-full">
								<div class="row">
									<div class="col-sm-8 col-sm-offset-3">
										<div class="radio-cont radio-label-left">
											<input type="radio" name="frnd-req" id="radior1" checked="checked" class="css-checkbox" />
											<label for="radior1" class="css-label radGroup1">Friends of friends</label>
										</div>
										<div class="radio-cont radio-label-left">
											<input type="radio" name="frnd-req" id="radior2" class="css-checkbox" />
											<label for="radior2" class="css-label radGroup1">Nearby app user</label>
										</div>
										<div class="radio-cont radio-label-left">
											<input type="radio" name="frnd-req" id="radior3" class="css-checkbox" />
											<label for="radior3" class="css-label radGroup1">All</label>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
					<div class="btn-cont text-center">
						<button type="button" class="btn btn-primary btn-lg">Save</button>
					</div>
				</div><!--/page center data-->
				<div class="shadow-box bottom-ad"><img src="images/bottom-ad.jpg" alt="" class="img-responsive"></div>
			</div>

			@include('panels.right')

			</div>
		</div>
	</div><!--/pagedata-->

<style>
	.file-error-message{
		display:none !important;
	}	
</style>
@endsection