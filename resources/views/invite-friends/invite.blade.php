@extends('layouts.dashboard')

@section('content')
<div class="page-data dashboard-body">
	<div class="container">
		<div class="row">
			@include('panels.left')

				<div class="col-sm-6">
					<div class="shadow-box page-center-data no-margin-top">
						<div class="page-title no-left-padding">Invite Contacts</div>
					@if (Session::has('error'))
						<div class="alert alert-danger">{!! Session::get('error') !!}</div>
					@endif
					@if (Session::has('success'))
						<div class="alert alert-success">{!! Session::get('success') !!}</div>
					@endif
						<div class="row">


							<div class="col-md-10 col-md-offset-1">
								{{Form::open()}}
								<div class="b-cast-name">
									<input type="text" name="emails" value="" class="form-control bcast-field" placeholder="Enter email address">
									<span class="field-info">*Enter multiple Email addresses by separating them with comma.</span>
									<div class="btn-cont text-center">
										<!-- <a href="#" title="" class="btn btn-primary">Invite</a> -->
										<button class="btn btn-primary btn-lg" type="submit">Invite</button>
									</div>
								</div>
								{{Form::close()}}

								<div class="bcast-list social-invite text-center">
									<h5>Choose a service provider to invite your contacts</h5>
									<div class="social-btns">
										<ul class="list-inline">
											<li><a href="{{$googleImportUrl}}" title=""><img src="/images/gmail-btn.png" alt=""></li>
											<li><a href="#" title=""><img src="/images/yahoomail-btn.png" alt=""></li>
											<li><a href="#" title=""><img src="/images/hotmail-btn.png" alt=""></li>
										</ul>
									</div>
								</div>
							</div>
						</div>

					</div><!--/page center data-->
					<div class="shadow-box bottom-ad"><img src="images/bottom-ad.jpg" alt="" class="img-responsive"></div>
				</div>

			@include('panels.right')
		</div>
	</div>
</div>
@endsection