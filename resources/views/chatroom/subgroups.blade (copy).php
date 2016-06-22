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

							{{ Form::open(array('url' => 'groupchat', 'method' => 'post', 'id' => 'chatsubgroupsvalidate')) }}
							<div class="page-title">

								<i class="flaticon-balloon"></i>{{$groupnamestr}}

							</div>
							@if (Session::has('error'))
								<div class="alert alert-danger">{!! Session::get('error') !!}</div>
							@endif	
							<div class="row">
								<div class="col-md-8 col-md-offset-3">
									<div class="radio-outer-full">
										<div class="row">
											<div class="col-sm-8 col-sm-offset-3">

												@if(!empty($subgroups))
													@foreach($subgroups as $data)
								                        <?php  
								                            $titledata = explode(' ', $data->title);
								                            if(is_array($titledata)){
								                                $title = strtolower(implode('', $titledata));

								                            }
								                            if($data->title == "Country,State,City")
								                            	$data->title = "Country, State, City";
								                        ?>

														<div class="radio-cont radio-label-left">
															<input class="group-radio" type="radio" name="subcategory" value="{{ $data->title }}" id="{{ $title }}"></input>
															<label for="{{ $title }}">{{ $data->title }}</label>

															@if($title == 'country')
																<div class="subs" style="display:none">
																<select name="country1" class="search-field boxsize pr-edit" id="country">
																		@foreach($countries as $data)					
																			<option value="{{$data}}">{{$data}}</option>
																		@endforeach
																	</select>
																</div>
															@elseif($title == 'country,state,city')
																<div class="subs" style="display:none">
																	<select name="country" class="search-field boxsize pr-edit" id="subcountry">
																		<option value="">Country</option>
																		@foreach($countries as $data)					
																			<option value="{{$data}}">{{$data}}</option>
																		@endforeach
																	</select>

																	<select name="state" class="search-field boxsize pr-edit" id="substate">
																		<option value="">State</option>
																	</select>
																	
																	<select name="city" class="search-field boxsize pr-edit" id="subcity">
																		<option value="">City</option>
																	</select>
																</div>

															@elseif($title == 'professionalcourse')

																<div class="subs" style="display:none">
																	<?php $courses = DB::table('categories')->where(['parent_id' => $data->id])->where(['status' => 'Active'])->pluck('title');
																	 
																	?>
																	<select name="coursedata1" class="boxsize pr-edit">
																		<option value="">Select</option>
																		@foreach($courses as $data)					
																			<option value="{{$data}}">{{$data}}</option>
																		@endforeach
																	</select>
																</div>
															@elseif($title == 'subjects')
																<div class="subs" style="display:none">
																	<?php $courses = DB::table('categories')->where(['parent_id' => $data->id])->where(['status' => 'Active'])->pluck('title'); 
																	
																	
																	?>
																	<select name="coursedata" class="boxsize pr-edit">
																		<option value="">Select</option>
																		@foreach($courses as $data)					
																			<option value="{{$data}}">{{$data}}</option>
																		@endforeach
																	</select>
																</div>

															@endif
														</div>

													@endforeach
												@endif

											</div>
										</div>
									</div>
									<input type="hidden" name="parentname" value="{{$group_name}}"></input>
								</div>
							</div>
							<div class="btn-cont text-center">
								<button type="submit" class="btn btn-primary btn-lg">Enter Chat</button>
							</div>
							{{ Form::close() }}
						</div><!--/page center data-->
						<div class="shadow-box bottom-ad"><img src="{{url("/images/bottom-ad.jpg")}}" alt="" class="img-responsive"></div>
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

 		// alert('asdfsa');
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
