@extends('layouts.dashboard')
<?php //echo '<pre>';	print_r($friends);die; ?>
@section('content')

<div class="page-data dashboard-body">
	<div class="container">
		<div class="row">
   			 
   			 @include('panels.left')
			
			<div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="page-title no-left-padding">Create New Broadcast List</div>
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
						{!! Form::open() !!}
							<div class="b-cast-name">
								<h5>Broadcast Name</h5>

								<input type="text" name="broadcastname" value="" class="form-control bcast-field">
							</div>
			
							<div class="bcast-list">
								<h5>Add Friends</h5>
								<select class="multiple-slt form-control" name="broadcastuser[]" multiple="multiple">
						@foreach($friends as $data)
							<?php 
								$name=$data['user']['first_name']." ".$data['user']['last_name'];
								$id=$data['user']['id'];
							?>
									<option value="{{$id}}">{{$name}}</option>
						@endforeach
								</select>
							</div>

				
							<div class="btn-cont text-center">
								<ul class="list-inline">
									<li><input type="submit" title="" class="btn btn-primary" value="Save"/></li>
									<li><a href="{{url('broadcast-list')}}" title="" class="btn btn-primary">Cancel</a></li>
								</ul>
							</div>
			{!! Form::close() !!}
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
