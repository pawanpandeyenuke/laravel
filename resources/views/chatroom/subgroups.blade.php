@extends('layouts.dashboard')
@section('title', 'Group Chat')
<?php 
$groupnamestr = ucwords($p_group->title);
unset($countries[0]);
 ?>

<style type="text/css">
	.boxsize{width:200px;}
</style>
@section('content')
	<div class="page-data dashboard-body">
	        <div class="container">
	            <div class="row">

	            @include('panels.left')
				<?php 
					$icon_url = url('category_images/'.$p_group->img_url);
				?>
			<div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="page-title">
						<img src="{{$icon_url}}" alt="" class="img-icon"> {{$p_group->title}}
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="btn-tab-cont">

							  <!-- Nav tabs -->
							  <ul class="nav nav-tabs row" role="tablist">
							  	<?php 
							  		//print_r($subgroups->count());die;
							  	?>
							  	@if($subgroups->count() == 1)
							  		<?php $li_class = "col-md-offset-4" ?>
							  	@else
							  		<?php $li_class = ""; ?>
							  	@endif
								@foreach($subgroups as $data)
										<?php  
					                            $titledata = explode(' ', $data->title);
					                            if(is_array($titledata)){
					                                $title = strtolower(implode('', $titledata));

					                            }
					                            if($data->title == "Country,State,City"){
					                            	$view_title = "City";
					                            	$data->title = "Country, State, City";
					                            	$aria = "csc-tab";
					                            }
					                            else if($data->title == "Country"){
					                            	$view_title = $data->title;
					                            	$aria = "country-tab";
					                            }
					                            else if($data->title == "International"){
					                            	$view_title = $data->title;
					                            	$aria = "international";
					                            }
									       ?>
							    <li role="presentation" class="col-md-4 {{$li_class}}"><a href="#{{$aria}}" aria-controls="{{$aria}}" role="tab" data-toggle="tab">{{$view_title}}</a></li>
							   @endforeach
							  </ul>

							  <!-- Tab panes -->
							  <div class="tab-content">
							    <div role="tabpanel" class="tab-pane" id="international">
							    	{{ Form::open(array('url' => 'groupchat', 'method' => 'post', 'id' => 'internationalform')) }}
							    	<input type="hidden" name="parentname" value="{{$p_group->title}}" />
							    	<input type="hidden" name="subcategory" value="International" />
										<div class="tab-btn-cont">
											<button type="submit" class="btn btn-primary">Start Chat</button>
										</div>
									{{ Form::close() }}
							    </div>
							    <div role="tabpanel" class="tab-pane" id="country-tab">
							    	{{ Form::open(array('url' => 'groupchat', 'method' => 'post', 'id' => 'countryform')) }}
							    	<input type="hidden" name="parentname" value="{{$p_group->title}}" />
							    	<input type="hidden" name="subcategory" value="Country" />
										<div class="row">
											<div class="col-md-4 col-md-offset-4">
												<!-- <label>Country</label> -->
												<select name="country1" class="form-control">
													<option value="">Select Country</option>
													@foreach($countries as $data)					
														<option value="{{$data}}">{{$data}}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="tab-btn-cont">
											<button type="submit" class="btn btn-primary">Start Chat</button>
										</div>
									{{ Form::close() }}
							    </div>
							    <div role="tabpanel" class="tab-pane" id="csc-tab">
							    	{{ Form::open(array('url' => 'groupchat', 'method' => 'post', 'id' => 'chatsubgroupsvalidate')) }}
							    	<input type="hidden" name="parentname" value="{{$p_group->title}}" />
							    	<input type="hidden" name="subcategory" value="Country, State, City" />
										<div class="row">
											<div class="col-md-4">
												<select name="country" class="form-control" id="subcountry">
													<option value="">Select Country</option>
													@foreach($countries as $data)					
													<option value="{{$data}}">{{$data}}</option>
													@endforeach
												</select>
											</div>
											<div class="col-md-4">
												<select name="state" class="form-control" id="substate">
													<option value="">Select State</option>
												</select>
											</div>
											<div class="col-md-4">
												<select name="city" class="form-control" id="subcity">
													<option value="">Select City</option>
												</select>
											</div>
										</div>
										<div class="tab-btn-cont">
											<button type="submit" class="btn btn-primary csc">Start Chat</button>
										</div>
									{{ Form::close() }}
							    </div>
							  </div>

							</div>
						</div>
					</div>

				</div><!--/page center data-->
				<div class="shadow-box bottom-ad"><img src="{{url('images/bottom-ad.jpg')}}" alt="" class="img-responsive"></div>
			</div>

 				@include('panels.right')

            </div>
        </div>
    </div>
<script type="text/javascript">
jQuery(function($){
	$('select').val('');

    $("#chatsubgroupsvalidate").validate({ 
        errorElement: 'span',
        errorClass: 'help-inline',
        rules: {
            subcategory: { required: true },
            country: { required: true },
            state: { required: true }
        },
        messages:{
            subcategory:{
                required: "Please select a sub category."
            },
            country:{
                required: "Country is required."
            },
            state:{
                required: "State is required."
            }
        }
    });
    
    $("#countryform").validate({ 
        errorElement: 'span',
        errorClass: 'help-inline',
        rules: {
            country1: { required: true }
        },
        messages:{
            country1:{
                required: "Country is required."
            }
        }
    });
    
	$('#subcountry').change(function(){
		var countryId = $(this).val();
		if(countryId){
			var _token = $('#searchform input[name=_token]').val();
			$.ajax({			
				'url' : '/ajax/getstates',
				'data' : { 'countryId' : countryId, '_token' : _token },
				'type' : 'post',
				'success' : function(response){				
					$('#substate').html(response);
					$('#subcity').html('<option value="">Select City</option>');
				}			
			});	
		}else{
			$('#substate').html('<option value="">Select State</option>');
			$('#subcity').html('<option value="">Select City</option>');
		}
	});

	$('#substate').change(function(){
		var stateId = $(this).val();
		if(stateId){
			var _token = $('#searchform input[name=_token]').val();
			$.ajax({			
				'url' : '/ajax/getcities',
				'data' : { 'stateId' : stateId, '_token' : _token },
				'type' : 'post',
				'success' : function(response){
					$('#subcity').html(response);
				}			
			});	
		}else{
			$('#subcity').html('<option value="">Select City</option>');
		}
	});
});
</script>
{!! Session::forget('error') !!}
@endsection