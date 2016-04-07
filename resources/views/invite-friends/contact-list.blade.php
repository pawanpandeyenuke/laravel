@extends('layouts.dashboard')

@section('content')
<div class="page-data dashboard-body">
	<div class="container">
		<div class="row">
			@include('panels.left')

			<div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="page-title no-left-padding">Invite Contacts</div>
					<div class="row">
						<div class="col-md-12">
							<div class="b-cast-name">
								{{Form::open()}}
								<h5 class="text-center">Stay in touch with contacts. Invite them to FriendzSquare</h5>
								<div class="invite-list-table">
									<div class="table-responsive">
										<table class="table table-hover">
											<tr>
												<th>Name</th>
												<th>Email</th>
												<th>
													<div class="checkbox-cont label-right">
														<input type="checkbox" name="selectall" id="checkboxG1" class="css-checkbox" />
														<label for="checkboxG1" class="css-label">Select All</label>
													</div>
												</th>
											</tr>
											@if(!empty($contacts))
												<?php $count = 1; ?>
												@foreach($contacts as $value)
													<tr>
														<td>{{ $value['name'] }}</td>
														<td>{{ $value['email'] }}</td>
														<td>
															<div class="checkbox-cont label-right">
																<input type="checkbox" name="checkboxG2-{{ $count }}" id="checkboxG2-{{ $count }}" class="css-checkbox checkbox" value="{{$value['email']}}"/>
																<label for="checkboxG2-{{ $count }}" class="css-label"></label>
															</div>
														</td>
													</tr>
													<?php $count = $count + 1; ?>
												@endforeach
											@else
												<tr>
													<td><span>No contacts to display.</span></td>	
													<td></td>
													<td></td>
												</tr>
											@endif
										</table>
									</div>
								</div>
								<div class="btn-cont text-center">
									<!-- <a href="#" title="" class="btn btn-primary">Invite</a> -->
									<button class="btn btn-primary btn-lg" type="submit">Invite</button>
								</div>
								{{Form::close()}}
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
<script type="text/javascript">
	$("#checkboxG1").change(function () {
	    $("input:checkbox").prop('checked', $(this).prop("checked"));
	});
</script>
@endsection