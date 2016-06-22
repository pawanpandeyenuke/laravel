@extends('layouts.dashboard')

<?php 
// print_r($group_name);die;
$groupnamestr = ucwords($group_name);
unset($countries[0]);
// print_r($countries);die;
 ?>

<style type="text/css">
	.boxsize{width:200px;}
</style>

<?php //echo '<pre>' ;print_r($countries);die;?>
@section('content')
	<div class="page-data dashboard-body">
	        <div class="container">
	            <div class="row">

	            @include('panels.left')

			<div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="page-title">
						<i class="flaticon-balloon"></i>{{$group_name}}
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="btn-tab-cont">

							  <!-- Nav tabs -->
							  <ul class="nav nav-tabs row" role="tablist">
								@foreach($subgroups as $data)
										<?php  
					                            $titledata = explode(' ', $data->title);
					                            if(is_array($titledata)){
					                                $title = strtolower(implode('', $titledata));

					                            }
					                            if($data->title == "Country,State,City"){
					                            	$data->title = "Country, State, City";
					                            	$aria = "csc-tab";
					                            }
					                            else if($data->title == "Country")
					                            	$aria = "country-tab";
					                            else if($data->title == "International")
					                            	$aria = "international";
									       ?>
							    <li role="presentation" class="col-md-4"><a href="#{{$aria}}" aria-controls="{{$aria}}" role="tab" data-toggle="tab">{{$data->title}}</a></li>
							   @endforeach
							  </ul>

							  <!-- Tab panes -->
							  <div class="tab-content">
							    <div role="tabpanel" class="tab-pane" id="international">
							    	{{ Form::open(array('url' => 'groupchat', 'method' => 'post', 'id' => 'internationalform')) }}
							    	<input type="hidden" name="parentname" value="{{$group_name}}" />
							    	<input type="hidden" name="subcategory" value="International" />
										<div class="tab-btn-cont">
											<button type="submit" class="btn btn-primary">Start Chat</button>
										</div>
									{{ Form::close() }}
							    </div>
							    <div role="tabpanel" class="tab-pane" id="country-tab">
							    	{{ Form::open(array('url' => 'groupchat', 'method' => 'post', 'id' => 'countryform')) }}
							    	<input type="hidden" name="parentname" value="{{$group_name}}" />
							    	<input type="hidden" name="subcategory" value="Country" />
										<div class="row">
											<div class="col-md-4 col-md-offset-4">
												<!-- <label>Country</label> -->
												<select name="country1" class="form-control">
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
							    	<input type="hidden" name="parentname" value="{{$group_name}}" />
							    	<input type="hidden" name="subcategory" value="Country, State, City" />
										<div class="row">
											<div class="col-md-4">
												<select name="country" class="form-control" id="subcountry">
													<option value="">Country</option>
													@foreach($countries as $data)					
													<option value="{{$data}}">{{$data}}</option>
													@endforeach
												</select>
											</div>
											<div class="col-md-4">
												<select name="state" class="form-control" id="substate">
													<option>State</option>
												</select>
											</div>
											<div class="col-md-4">
												<select name="city" class="form-control" id="subcity">
													<option>City</option>
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
    </div><!--/pagedata-->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> 
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
 <script type="text/javascript">
 	
 $(document).on('click', '.group-radio', function(){

 	if($(this).is(':checked')){

		$(this).closest('.radio-cont').nextAll().find('.subs').hide();
		$(this).closest('.radio-cont').find('.subs').show();
		$(this).closest('.radio-cont').prevAll().find('.subs').hide();

 	}

 });



    $("#chatsubgroupsvalidate").validate({ 
        errorElement: 'span',
        errorClass: 'help-inline',
        rules: {
            subcategory: { required: true },
            country: { required: true },
            state: { required: true },
            city: {required: true}
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
            },
            city:{
                required: "City is required."
            }
        }
    });



	$('#subcountry').change(function(){
		var countryId = $(this).val();
		var _token = $('#searchform input[name=_token]').val();
		$.ajax({			
			'url' : '/ajax/getstates',
			'data' : { 'countryId' : countryId, '_token' : _token },
			'type' : 'post',
			'success' : function(response){				
				$('#substate').html(response);
			}			
		});	
	});

	$('#substate').change(function(){
		var stateId = $(this).val();
		var _token = $('#searchform input[name=_token]').val();
		$.ajax({			
			'url' : '/ajax/getcities',
			'data' : { 'stateId' : stateId, '_token' : _token },
			'type' : 'post',
			'success' : function(response){
				$('#subcity').html(response);
			}			
		});	
	});
 </script>


@endsection

{!! Session::forget('error') !!}
