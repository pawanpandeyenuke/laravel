@extends('layouts.dashboard')

@section('content')

<div class="page-data dashboard-body">
	<div class="container">
		<div class="row">
			<div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="page-title no-left-padding">Create New Broadcast List</div>
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
							<div class="b-cast-name">
								<h5>Broadcast Name</h5>
								<input type="text" name="" value="" class="form-control bcast-field">
							</div>

							<div class="bcast-list">
								<select class="multiple-slt form-control" multiple="multiple">
									<option>John</option>
									<option>Jack</option>
									<option>Harry</option>
								</select>
							</div>
							<div class="btn-cont text-center">
								<ul class="list-inline">
									<li><a href="#" title="" class="btn btn-primary">Save</a></li>
									<li><a href="#" title="" class="btn btn-primary">Cancel</a></li>
								</ul>
							</div>
						</div>
					</div>

				</div><!--/page center data-->
				<div class="shadow-box bottom-ad"><img src="images/bottom-ad.jpg" alt="" class="img-responsive"></div>
			</div>
		</div>
	</div>
</div>
@endsection