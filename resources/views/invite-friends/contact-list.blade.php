@extends('layouts.dashboard')

@include('panels.meta-data')
@section('title', 'Invite Friends')
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
														<td  title="{{ $value['name'] }}">
														<div class="user-name">{{ $value['name'] }}</div></td>
														<td title="{{ $value['email'] }}">
														<div class="email-text">{{ $value['email'] }}</div></td>
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
									<button class="btn btn-primary btn-lg" id="sent-invitation-btn" disabled="disabled" type="submit">Invite</button>
								</div>
								{{Form::close()}}
							</div>

						</div>
					</div>

				</div><!--/page center data-->
				@include('panels.footer-advertisement')
			</div>

			@include('panels.right')
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#checkboxG1").change(function () {
	    $("input:checkbox").prop('checked', $(this).prop("checked"));

	    if($(this).is(':checked')){
	    	$("#sent-invitation-btn").attr("disabled", false);	
	    }else{
	    	$("#sent-invitation-btn").attr("disabled", true);	
	    }
	});

	$(document).on('change', '.checkbox', function(){
		if($('.checkbox').is(':checked')){
			$("#sent-invitation-btn").removeAttr('disabled');
		}

		if(!$(this).is(':checked')){
			var checkbox =  $("input:checkbox").is(':checked');
			if(checkbox == false)
				$("#sent-invitation-btn").attr("disabled", true);
			// else{
			// 	alert('enable please');
			// }
			// console.log(checkbox);
			// $("#sent-invitation-btn").prop('disabled');
			// $("#sent-invitation-btn").attr("disabled", true);
		}
	});

</script>
@endsection