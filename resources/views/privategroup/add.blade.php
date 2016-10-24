@extends('layouts.dashboard')

@include('panels.meta-data')
@section('title', 'Private Group')
@section('content')

<div class="page-data dashboard-body">
	<div class="container">
		<div class="row">
   			 
   			 @include('panels.left')
			
			<div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="page-title no-left-padding"><h1>Create New Private Group</h1></div>
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
						{!! Form::open(array('id' => 'privategroupform')) !!}
							<div class="b-cast-name">
								<h5>Group Name</h5>

								<input type="text" name="groupname" value="" class="form-control bcast-field b-valid">
							</div>
			
							<div class="bcast-list">
								  <h5>Add Friends </h5>
								<select class="multiple-slt form-control b-valid" id="select-multiuser" name="groupmembers[]" multiple="multiple">
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
									<li><input type="submit" title="" class="btn btn-primary privategroupaddform" value="Save"/></li>
									<li><a href="{{url('private-group-list')}}" title="" class="btn btn-primary">Cancel</a></li>
								</ul>
							</div>
					{!! Form::close() !!}
						</div>
					</div>

				</div><!--/page center data-->
				@include('panels.footer-advertisement')
			</div>
			@include('panels.right')
		</div>
	</div>
</div>


<script type="text/javascript" >

$(document).ready(function () {

	$( "#privategroupform" ).submit(function( event ) {
		$('.privategroupaddform').prop('disabled', true);
		var stack = $('.b-valid');
		$.each(stack, function(i,v){
			if($(this).is('input')){
				$(this).closest('.b-cast-name').find('#groupname-error').remove();
				$(this).removeClass('help-inline');
				if($(this).val() === ''){
					$(this).closest('.b-cast-name').append('<span id="groupname-error" class="help-inline">Please enter the name of broadcast.</span>');
					$(this).addClass('help-inline');
					// $(this).focus();
					event.preventDefault();
				}
			}else if($(this).is('select')){	
				$(this).closest('.bcast-list').find('#groupuser-error').remove();
				$('.select2-selection').removeClass('help-inline');
				// alert($(this).val());
				if($(this).val() === null){
					$('#select-multiuser').closest('.bcast-list').append('<span id="groupuser-error" class="help-inline">Please add at least one contact to broadcast list.</span>');
					$('.select2-selection').addClass('help-inline');
					event.preventDefault();
				}
			}
		});
	});
	
	$( ".bcast-field" ).focus(function( ) {
		$(this).closest('.b-cast-name').find('#groupname-error').remove();
		$(this).removeClass('help-inline');
		$('.privategroupaddform').prop('disabled', false);
	});


	$( ".multiple-slt" ).change(function( ) {
		$(this).closest('.bcast-list').find('#groupuser-error').remove();
		$('.select2-selection').removeClass('help-inline');
		$('.privategroupaddform').prop('disabled', false);
	});
 
});
</script>
@endsection