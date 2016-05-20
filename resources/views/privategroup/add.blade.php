@extends('layouts.dashboard')
<?php //echo '<pre>';	print_r($friends);die; ?>
@section('content')

<div class="page-data dashboard-body">
	<div class="container">
		<div class="row">
   			 
   			 @include('panels.left')
			
			<div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="page-title no-left-padding">Create New Private Group</div>
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
									<li><input type="submit" title="" class="btn btn-primary" value="Save"/></li>
									<li><a href="{{url('private-group-list')}}" title="" class="btn btn-primary">Cancel</a></li>
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


<script type="text/javascript" src="{{url('/js/jquery-1.11.3.min.js')}}"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
<!-- <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/additional-methods.js"></script> -->

<script type="text/javascript" >

$(document).ready(function () {

	$( "#privategroupform" ).submit(function( event ) {
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
	});


	$( ".multiple-slt" ).change(function( ) {
		$(this).closest('.bcast-list').find('#groupuser-error').remove();
		$('.select2-selection').removeClass('help-inline');
	});

/*    $("#privategroupform").validate({ 
        errorElement: 'span',
        errorClass: 'help-inline',
        rules: {
            groupname: { required: true, number: false },
            // groupmembers:  { selectcheck: true }
        },
        messages:{
            groupname:{
                required: "Please enter the name of broadcast.",
                number: "Please enter a valid broadcast name."
            },
            groupmembers:{
                selectcheck: "Please add at least one contact to broadcast list."
            }
        }
    });


	$( "#privategroupform" ).submit(function( event ) {
		var multiuser = $('#select-multiuser').val();
		if(multiuser === null){
			$('#select-multiuser').closest('.bcast-list').append('<span id="groupuser-error" class="help-inline">Please add at least one contact to broadcast list.</span>');
			$('.select2-selection').addClass('help-inline');
			event.preventDefault();
		}
	});

	$( "#select-multiuser" ).change(function( event ) {
		$('#select-multiuser').closest('.bcast-list').find('#groupuser-error').remove();
		$('.select2-selection').removeClass('help-inline');
		var multiuser = $('#select-multiuser').val();
		if(multiuser === null){		
			$('#select-multiuser').closest('.bcast-list').append('<span id="groupuser-error" class="help-inline">Please add at least one contact to broadcast list.</span>');
			$('.select2-selection').addClass('help-inline');
		}
	});*/


/*$("#select-multiuser").select2({ maximumSelectionLength: 15}).on( 'select2:select', function(e) {
	alert($("#select-multiuser").val());
});*/


 
});
</script>
