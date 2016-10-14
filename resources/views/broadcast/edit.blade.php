@extends('layouts.dashboard')

@include('panels.meta-data')
@section('title', 'Broadcast')
@section('content')
<?php  // echo '<pre>';print_r($broadcast);die; ?>
<div class="page-data dashboard-body">
	<div class="container">
		<div class="row">
   			 
   			 @include('panels.left')
			
			<div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="page-title no-left-padding">Edit Broadcast</div>
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
							{!! Form::open(array('id' => 'broadcastAdd')) !!}
								<div class="b-cast-name">
									<h5>Broadcast Name</h5>

									<input type="text" name="broadcastname" value="<?= isset($broadcast['title']) ? $broadcast['title'] : '' ?>" class="form-control bcast-field b-valid">
								</div>
				
								<div class="bcast-list">
									<h5>Friends</h5>

									<select class="multiple-slt form-control b-valid" id="select-multiuser-broadcast" name="broadcastuser[]" multiple="multiple">
										@foreach($friends as $data)
											<?php 
												$name=$data['user']['first_name']." ".$data['user']['last_name'];
												$id=$data['user']['id'];
												$selected = in_array($id, $broadcast_prev_members) ? 'selected' : '';
											?>
											<option value="{{$id}}" <?= $selected ?> >{{$name}}</option>
										@endforeach
									</select>
								</div>

					
								<div class="btn-cont text-center">
									<ul class="list-inline">
										<li><input type="submit" title="" class="btn btn-primary broadcastadd-btn" value="Update"/></li>
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
<!-- <script type="text/javascript" src="{{url('/js/jquery-1.11.3.min.js')}}"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script> -->
<script type="text/javascript" >

$(document).ready(function () {



/*    $("#broadcastAdd").validate({ 
        errorElement: 'span',
        errorClass: 'help-inline',
        rules: {
            broadcastname: { required: true, number: false },
            // broadcastuser:  { selectcheck: true }
        },
        messages:{
            broadcastname:{
                required: "Please enter the name of broadcast.",
                number: "Please enter a valid broadcast name."
            },
            broadcastuser:{
                required: "Please add at least one contact to broadcast list."
            }
        }
    });*/


	$( "#broadcastAdd" ).submit(function( event ) {
		$('.broadcastadd-btn').prop('disabled', true);
		var stack = $('.b-valid');
		$.each(stack, function(i,v){
			if($(this).is('input')){
				$(this).closest('.b-cast-name').find('#groupname-error').remove();
				$(this).removeClass('help-inline');
				if($(this).val() === ''){
					$(this).closest('.b-cast-name').append('<span id="groupname-error" class="help-inline">Please enter the name of broadcast.</span>');
					$(this).addClass('help-inline');
					event.preventDefault();
				}
			}else if($(this).is('select')){	
				$(this).closest('.bcast-list').find('#groupuser-error').remove();
				$('.select2-selection').removeClass('help-inline');
				if($(this).val() === null){
					$('#select-multiuser-broadcast').closest('.bcast-list').append('<span id="groupuser-error" class="help-inline">Please add at least one contact to broadcast list.</span>');
					$('.select2-selection').addClass('help-inline');
					event.preventDefault();
				}
			}
		});

		$( ".bcast-field" ).focus(function( ) {
			$(this).closest('.b-cast-name').find('#groupname-error').remove();
			$(this).removeClass('help-inline');
			$('.broadcastadd-btn').prop('disabled', false);
		});


		$( ".multiple-slt" ).change(function( ) {
			$(this).closest('.bcast-list').find('#groupuser-error').remove();
			$('.select2-selection').removeClass('help-inline');
			$('.broadcastadd-btn').prop('disabled', false);
		});

	});

});
 
</script>
@endsection