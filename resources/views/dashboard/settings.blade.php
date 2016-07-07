@extends('layouts.dashboard')
<?php  
if($setting==null)
{
$setting['contact-request']="Friends of friends";
$setting['friend-request'] ="Friends of friends";
}
?>
@section('title', 'Settings - ')
@section('content')

	<div class="page-data dashboard-body">
		<div class="container">
			<div class="row">

			@include('panels.left')

			<div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
				{!! Form::open(array()) !!}
					<div class="page-title">
						<i class="flaticon-tool"></i>Privacy Setting
					</div>
					@if (Session::has('error'))
						<div class="alert alert-danger">{!! Session::get('error') !!}</div>
					@endif
					@if (Session::has('success'))
						<div class="alert alert-success">{!! Session::get('success') !!}</div>
					@endif
					<div class="row">
						<div class="col-md-8 col-md-offset-3">
						
							<h4>Who can contact me?</h4>
							<div class="radio-outer-full">
								<div class="row">
									<div class="col-sm-8 col-sm-offset-3">
										<div class="radio-cont radio-label-left">
											<input type="radio" name="contact-request" id="radio1" class="css-checkbox" value="Friends of friends" <?php echo $setting['contact-request'] == 'Friends of friends'? 'checked':''; ?>/>
											<!-- {!! Form::radio('contact-request', 'Friends of friends', true,[
												'class'=>'css-checkbox',
												'id'=>'radio1',
											]) !!} -->
											<label for="radio1" class="css-label radGroup1">Friends of friends</label>
										</div>
										<div class="radio-cont radio-label-left">
											<input type="radio" name="contact-request" id="radio2" class="css-checkbox" value="Nearby app user" <?php echo $setting['contact-request'] == 'Nearby app user'? 'checked':''; ?>/>
<!-- 											{!! Form::radio('contact-request', 'Nearby app user', false,[
												'class'=>'css-checkbox',
												'id'=>'radio2', 
											]) !!} -->
											<label for="radio2" class="css-label radGroup1">Nearby app user</label>
										</div>
										<div class="radio-cont radio-label-left">
											<input type="radio" name="contact-request" id="radio3" class="css-checkbox" value="All" <?php echo $setting['contact-request'] == 'All'? 'checked':''; ?>/>
										<!-- 	{!! Form::radio('contact-request', 'All', false,[
												'class'=>'css-checkbox',
												'id'=>'radio3',
											]) !!} -->
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
											<input type="radio" name="friend-request" id="radior1" class="css-checkbox" value="Friends of friends" <?php echo $setting['friend-request'] == 'Friends of friends'? 'checked':'' ?>/>
											<!-- {!! Form::radio('friend-request', 'Friends of friends', true, [
												'class' => 'css-checkbox',
												'id' => 'radior1',
											])!!} -->
											<label for="radior1" class="css-label radGroup1">Friends of friends</label>
										</div>
										<div class="radio-cont radio-label-left">
											<input type="radio" name="friend-request" id="radior2" class="css-checkbox" value="Nearby app user" <?php echo $setting['friend-request'] == 'Nearby app user'? 'checked':'' ?>/>
											<!-- {!! Form::radio('friend-request', 'Nearby app user', false, [
												'class' => 'css-checkbox',
												'id' => 'radior2',
											])!!} -->
											<label for="radior2" class="css-label radGroup1">Nearby app user</label>
										</div>
										<div class="radio-cont radio-label-left">
											<input type="radio" name="friend-request" id="radior3" class="css-checkbox" value="All" <?php echo $setting['friend-request'] == 'All'? 'checked':'' ?>/>
											<!-- {!! Form::radio('friend-request', 'All', false, [
												'class' => 'css-checkbox',
												'id' => 'radior3',
											])!!} -->
											<label for="radior3" class="css-label radGroup1">All</label>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
					<div class="btn-cont text-center">
						<button type="submit" class="btn btn-primary btn-lg">Save</button>
					</div>
				{!! Form::close() !!}
				</div><!--/page center data-->
				<div class="shadow-box bottom-ad"><img src="/images/bottom-ad.jpg" alt="" class="img-responsive"></div>
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
