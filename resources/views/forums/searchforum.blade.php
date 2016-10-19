<?php 
	$mainforums = \App\Forums::where('parent_id',0)->orderBy('display_order')->select('id','title')->get();
	$diseases = \App\ForumsDoctor::pluck('title')->toArray();
	if(isset($keyword))
		$key = $keyword;
	else
		$key = "";

$check_val = "direct";

?>				
				{!! Form::open(array('url' => 'search-forum','id' => 'search-forum-layout', 'method' => 'get')) !!}
						<div class="forum-filter">
							<div class="row">
								<div class="col-md-4">
									<select class="form-control getsubcategory" id="getsubcategory" name="mainforum">
									<option value="Forum">Select Category</option>	
									@foreach($mainforums as $data)
								<?php 	
									if(isset($old['mainforum']) && $old['mainforum'] != "" && $old['mainforum'] == $data->id)
										$selected = "selected";
									else
										$selected = "";
								?>
										<option value="{{$data->id}}" {{ $selected }}>{{$data->title}}</option>
									@endforeach
									</select>
								</div>
								<div class="col-md-4">
								<div class="search-subforums">
										<?php 
											if( !empty($old['mainforum']) ){
												$options = \App\Forums::where('parent_id',$old['mainforum'])->get();
											} else{
												$options = "";
											}
											
											if(isset($old['search-subforums']) && !empty($old['search-subforums']) && $old['search-subforums'] != "sub-opt"){
												$check_val = "sub";
											}else{
											 	$sub_id = "";
											 	$sub_title = "Sub Category";
											 	
											}
										?>
									<select class="form-control" id="search-subforums" name="search-subforums">
										<option value="sub-opt">Select Sub Category</option>
										@if(!empty($options))
											@foreach($options as $option)
												<?php 	
												if($option->title == "Country,State,City")
														$option->title = "City";
													if(isset($old['search-subforums']) && $old['search-subforums'] != "" && $old['search-subforums'] == $option->id)
														$selected_opt = "selected";
													else
														$selected_opt = "";
												?>
												<option value = "{{ $option->id }}" {{$selected_opt}}>{{$option->title}}</option> 
											@endforeach
										@endif
									</select>
								</div>
								</div>
								<div class="col-md-4">
									<input type="text" name="forum-keyword" value="{{$key}}" id="forum-keyword-layout" placeholder="Enter Keyword" class="form-control">
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<?php 
										if(isset($old['search-country1']) && $old['search-country1'] != "" && $old['check'] == "c"){
											$disp = "";
											$check_val = "c";
										}else{
											$disp = "display: none;";
										}
									?>
								<div class="search-country1" style="{{ $disp }}">
									<select class="form-control" id="search-country1" name="search-country1">
										@foreach($countries as $country1)
										<?php 
											if(isset($old['search-country1']) && $old['search-country1']!="" && $country1 == $old['search-country1'])
												$country1_select = "selected";
											else
												$country1_select = "";
										?>
										<option value="{{ $country1 }}" {{ $country1_select }}>{{ $country1 }}</option>
										@endforeach
									</select>
								 </div>
								 	<?php 
										if(isset($old['check']) && $old['check'] == "csc"){
											$country_name = $old['search-country'];
											$state_arr = \App\State::where('country_id',\App\Country::where('country_name',$old['search-country'])->value('country_id'))->get();
											$city_arr = \App\City::where('state_id',\App\State::where('state_name',$old['search-state'])->value('state_id'))->get();
											$csc_disp = "";
											$check_val = "csc";
										}else{
											$country_name = "";
											$state_arr = "";
											$city_arr = "";
											$csc_disp = "display: none;";
										}
									?>
								<div class="search-country" style="{{ $csc_disp }}">
									<select class="form-control csc" id="search-country" name="search-country">
										@foreach($countries as $country)
										<?php 
											if(isset($old['check']) && $old['check'] == "csc" && $country == $old['search-country'])
												$country_select = "selected";
											else
												$country_select = "";
										?>
										<option value = "{{ $country }}" {{$country_select}}>{{ $country }}</option>
										@endforeach
									</select>
								 </div>
								</div>
								<div class="col-md-4">
									<?php 
										if(isset($old['check']) && $old['check'] == "subfor"){
											$subject_arr = \App\Forums::where('parent_id',$old['search-subforums'])->get();
											$check_val = "subfor";
											$subject_display = "";
										}else{
											$subject_id = "";
											$subject_arr = "";
											$subject_display = "display: none;";
										}
									?>
								<div class="search-subject1" style="{{ $subject_display }}">
								<select class="form-control" id="search-subject1" name="search-subject1">
									<option>Select Option</option>
									@if(!empty($subject_arr))
										@foreach($subject_arr as $subject)
										<?php 
											if(isset($old['check']) && $old['check'] == "subfor" && $subject->id == $old['search-subject1'])
												$subject_select = "selected";
											else
												$subject_select = "";
										?>
										<option value="{{ $subject->id }}" {{ $subject_select }}>{{ $subject->title }}</option>
										@endforeach
									@endif
								</select>
								</div>
								<div class="search-state" style="{{ $csc_disp }}">
									<select class="form-control csc" id="search-state" name="search-state">
									@if(!empty($state_arr))
										@foreach($state_arr as $state)
										<?php 
											if(isset($old['check']) && $old['check'] == "csc" && $state->state_name == $old['search-state'])
												$state_select = "selected";
											else
												$state_select = "";
										?>
										<option value = "{{ $state->state_name  }}" {{$state_select}}>{{ $state->state_name  }}</option>
										@endforeach
									@endif
									</select>
								</div>
								</div>
								<div class="col-md-4">
								<div class="search-city" style="{{ $csc_disp }}">
									<select class="form-control csc" id="search-city" name="search-city">
									@if(!empty($city_arr))
										@foreach($city_arr as $city)
										<?php 
											if(isset($old['check']) && $old['check'] == "csc" && $city->city_name == $old['search-city'])
												$city_select = "selected";
											else
												$city_select = "";
										?>
										<option value = "{{ $city->city_name }}" {{$city_select}}>{{ $city->city_name  }}</option>
										@endforeach
									@endif
									</select>
								</div>
								</div>
								<?php 
									if(isset($old['search-subforums']) && $old['search-subforums'] != ""){
										if((\App\Forums::where('id',$old['mainforum'])->value('title') == "Doctor") && $old['check'] != "direct")
											$d_disp = "";
										else
											$d_disp = "display: none;";
									}
									else
											$d_disp = "display: none;";
								?>
								<div class="col-md-4 search-diseases" style="{{ $d_disp }}">
									<select class="form-control csc" id="search-diseases" name="search-diseases">
										<option value="">Select Options</option>
									@foreach($diseases as $diseases)
										<?php
												if(isset($old['search-diseases']) && $old['search-diseases'] != "" && $old['search-diseases'] == $diseases)
													$selected = "selected";
												else
													$selected = "";
										?>
										<option value = "{{$diseases}}" {{$selected}}>{{$diseases}}</option>
									@endforeach
									</select>
								</div>
							</div>
							<div class="row">
								<div class="alert alert-danger alert-search-forum" style="display: none;">
									</div>
								<div class="col-md-4 col-md-offset-4">
									<div class="forum-btn-cont text-center">
										<button type="submit" class="btn btn-primary">Search</button>
									</div>
								</div>
							</div>
							<?php
								if(isset($old['mainforum']) && isset($old['search-subforums']) && $old['search-subforums'] == "sub-opt" && $old['mainforum'] == "Forum"){
									$check_val = "direct";
								}
							?>
							<input type="hidden" name="check" class="search-check" value="{{ $check_val }}" />
						{!! Form::close() !!}
						</div><!--/forum filter-->

<script type="text/javascript">

	window.onload = function(){
		var url_c = window.location.pathname;
		var arr = url_c.split('/');
		if(arr[1] == 'sub-forums'){
			$("#getsubcategory").val(arr[2]);
			var forumid = arr[2];
			$.ajax({	
			'url' : '/ajax/getsubforums',
			'data' : { 'forumid' : forumid },
			'type' : 'post',
			'success' : function(response){
				if(response == 'No')
				{
					$('.search-check').val('direct');
					$('.search-subforums').hide();				
      				$('#search-subforums').hide();		
				}else{
					$('.search-subforums').show();				
					$('#search-subforums').show();				
					$('#search-subforums').html(response);
				}
			}			
		});
		}
		
	} 
	

     $( "#search-forum-layout" ).submit(function( event ) {
      
      if($('.search-check').val() == "c" && $('#search-country1').val() == "Country"){
      	$('#search-country1').focus();
      	$('.alert-search-forum').html('Please select country.');
      	$('.alert-search-forum').show();
      	event.preventDefault();
      }

      var searchkey = $('#forum-keyword-layout').val();
      var parent = $('.getsubcategory').val();
      
    if(searchkey == "" && parent == "Forum"){
        $('#forum-keyword-layout').focus();
        event.preventDefault();
   	}

      if(searchkey == '' || $("#search-subforums option:selected").text() == 'City'){
      	
      	if(($('#search-country').val() == "Country" || $('#search-state').val() == "State" || $('#search-city').val() == "City" || $('#search-city').val() == "") &&  $("#search-subforums option:selected").text() == 'City')
      	{
      		if($('#search-country').val() == "Country"){
      			$('#search-country').focus();
      			$('.alert-search-forum').html('Please select country if you want to search city wise.');	
      		}
      		else if($('#search-state').val() == "State" || $('#search-state').val() == ""){
      			$('#search-state').focus();
      			$('.alert-search-forum').html('Please select state if you want to search city wise.');	
      		}
      		else if($('#search-city').val() == "City" || $('#search-city').val() == ""){
      			$('#search-city').focus();
      			$('.alert-search-forum').html('Please select city if you want to search city wise.');	
      		}
      		$('.alert-search-forum').show();	
      		event.preventDefault();
      	}

      }
   	 });
	
		$('.getsubcategory').change(function(){
					$('.search-country1').hide();
					$('.search-country').hide();
					$('.search-state').hide();
					$('.search-city').hide();
					$('.search-subject1').hide();
					$('.search-subject2').hide();
					$('#search-country1').hide();
					$('#search-country').hide();
					$('#search-state').hide();
					$('#search-city').hide();
					$('#search-subject1').hide();
					$('#search-subject2').hide();
					$('.search-diseases').hide();
					$('#search-diseases').hide();
					$('.alert-search-forum').hide();

					$('#search-country').html("");
					$('#search-state').html("");
					$('#search-city').html("");
					$('#search-country1').html("");
					$('.search-check').val('direct');
		var forumid = $(this).val();
		var _token = $('#searchform input[name=_token]').val();
		$('#search-subforums').attr('disabled',true);	
		$.ajax({			
			'url' : '/ajax/getsubforums',
			'data' : { 'forumid' : forumid },
			'type' : 'post',
			'success' : function(response){
				if(response == 'No')
				{
					$('.search-check').val('direct');
					$('.search-subforums').hide();				
      				$('#search-subforums').hide();
      				$('#search-subforums').attr('disabled',false);
				}else{
					$('.search-subforums').show();				
					$('#search-subforums').show();				
					$('#search-subforums').html(response);
					$('#search-subforums').attr('disabled',false);
				}
			}			
		});	
	});		

	$('#search-subforums').change(function(){
		$('.search-country1').hide();
		$('.search-country').hide();
		$('.search-state').hide();
		$('.search-city').hide();
		$('.search-subject1').hide();
		$('.search-subject2').hide();
		$('#search-country1').hide();
		$('#search-country').hide();
		$('#search-state').hide();
		$('#search-city').hide();
		$('#search-subject1').hide();
		$('#search-subject2').hide();
		$('.search-diseases').hide();
		$('#search-diseases').hide();
		$('.alert-search-forum').hide();
		$('#search-country').html("");
		$('#search-state').html("");
		$('#search-city').html("");

		var forumid = $(this).val();
		var _token = $('#searchform input[name=_token]').val();
		if( forumid != 'sub-opt' ){
			if($("#getsubcategory option:selected").text() == "Doctor")
			{
				$('.search-diseases').show();
				$('#search-diseases').show();
			}
			$.ajax({			
				'url' : '/ajax/getsubforums-2',
				'data' : { 'forumid' : forumid },
				'type' : 'post',
				'success' : function(response){
					if(response == 'hide')
					{
					$('.search-check').val('sub');
					}else{
						var jresponse = jQuery.parseJSON(response);
						$('.search-check').val(jresponse.msg);
						if(jresponse.msg == 'c')
						{
							$('.search-country1').show();
							$('#search-country1').show();
							$('#search-country1').html(jresponse.data);
						}
						else if(jresponse.msg == 'csc')
						{
							$('.search-country').show();
							$('#search-country').show();
							$('.search-state').show();
							$('#search-state').show();
							$('.search-city').show();
							$('#search-city').show();
							$('#search-country').html(jresponse.data);
							$('#search-state').html("<option>Select State</option>");
							$('#search-city').html("<option>Select City</option>");
						}
						else if(jresponse.msg == 'subfor')
						{
							$('.search-subject1').show();
							$('#search-subject1').show();
							$('#search-subject1').html(jresponse.data);
						}

					}
				}			
			});	
		} else {
			$('.search-check').val('direct');
		}
	});
		
	$('#search-country').change(function(){
		$('.alert-search-forum').hide();	
		$('#search-city').html("<option value='City'>Select City</option>");
		var countryId = $(this).val();
		var _token = $('#searchform input[name=_token]').val();
		$('#search-state').attr('disabled',true);
		$.ajax({			
			'url' : '/ajax/getstates',
			'data' : { 'countryId' : countryId, '_token' : _token },
			'type' : 'post',
			'success' : function(response){		
				$('#search-state').html(response);
				$('#search-state').attr('disabled',false);
			}			
		});	
	});

	$('#search-state').change(function(){
		$('.alert-search-forum').hide();	
		$('#search-city').html("<option value='City'>Select City</option>");
		var stateId = $(this).val();
		var _token = $('#searchform input[name=_token]').val();
		$('#search-city').attr('disabled',true);
		$.ajax({			
			'url' : '/ajax/getcities',
			'data' : { 'stateId' : stateId, '_token' : _token },
			'type' : 'post',
			'success' : function(response){
				$('#search-city').html(response);
				$('#search-city').attr('disabled',false);
			}			
		});	
	});

	$('#search-city').change(function(){
		$('.alert-search-forum').hide();
	});

</script>