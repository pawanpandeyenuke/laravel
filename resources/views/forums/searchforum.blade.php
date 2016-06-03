<?php 
	$mainforums = \App\Forums::where('parent_id',0)->select('id','title')->get();
	$diseases = \App\ForumsDoctor::pluck('title')->toArray();
?>				
				{!! Form::open(array('url' => 'search-forum','id' => 'search-forum-layout', 'method' => 'post')) !!}
						<div class="forum-filter">
							<div class="row">
								<div class="col-md-4">
									<select class="form-control getsubcategory" name="mainforum">
									<option value="Forum">Forum</option>	
									@foreach($mainforums as $data)				
										<option value="{{$data->id}}">{{$data->title}}</option>
									@endforeach
									</select>
								</div>
								<!-- <div class="subs" style="display: none;"> -->
								<div class="col-md-4">
								<div class="search-subforums">
									<select class="form-control" id="search-subforums" name="search-subforums">
									<option>SubCategory</option>
									</select>
								</div>
								</div>
								<div class="col-md-4">
									<input type="text" name="forum-keyword" value="" id="forum-keyword-layout" placeholder="Enter Keyword" class="form-control">
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
								<div class="search-country1" style="display: none;">
									<select class="form-control" id="search-country1" name="search-country1">
										<option>India</option>
									</select>
								 </div>
								<div class="search-country" style="display: none;">
									<select class="form-control csc" id="search-country" name="search-country">
										<option>India</option>
									</select>
								 </div>
								</div>
								<div class="col-md-4">
								<div class="search-subject1" style="display: none;">
								<select class="form-control" id="search-subject1" name="search-subject1">
								<option>Options</option>
								</select>
								</div>
								<div class="search-state" style="display: none;">
									<select class="form-control csc" id="search-state" name="search-state">
										<option>State</option>
									</select>
								</div>
								</div>
								<div class="col-md-4">
								<div class="search-city" style="display: none;">
									<select class="form-control csc" id="search-city" name="search-city">
										<option>City</option>
									</select>
								</div>
								</div>
								<div class="col-md-4 search-diseases" style="display: none;">
									<select class="form-control csc" id="search-diseases" name="search-diseases">
									@foreach($diseases as $diseases)
										<option value = "{{$diseases}}">{{$diseases}}</option>
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
							<input type="hidden" name="check" class="search-check" value="" />
						{!! Form::close() !!}
						</div><!--/forum filter-->

<script type="text/javascript" src="{{url('/js/jquery-1.11.3.min.js')}}"></script>
<!-- <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script> -->
<script type="text/javascript">

      $( "#search-forum-layout" ).submit(function( event ) {
      var city = ['56','57','60','72','234','241'];
      var searchkey = $('#forum-keyword-layout').val();
      if(searchkey == '' || city.indexOf($('#search-subforums').val()) != -1 ){
      	if(searchkey == ""){
        $('#forum-keyword-layout').attr('placeholder', 'Enter Keyword').focus();
        event.preventDefault();
   		}
        
      	if(($('#search-country').val() == "Country" || $('#search-state').val() == "State" || $('#search-city').val() == "City" || $('#search-city').val() == "") && city.indexOf($('#search-subforums').val()) != -1 )
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

      	// $('#search-subforums').click(function(){
      		
      	// });
	
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
		$.ajax({			
			'url' : '/ajax/getsubforums',
			'data' : { 'forumid' : forumid },
			'type' : 'post',
			'success' : function(response){
				if(response == 'No')
				{
					$('.search-check').val('direct');
      				$('#search-subforums').html('');				
				}else{
					$('#search-subforums').html(response);
	
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
		if($('.getsubcategory').val() == 12)
		{
				$('.search-diseases').show();
				$('#search-diseases').show();
		}
		var forumid = $(this).val();
		var _token = $('#searchform input[name=_token]').val();
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
						$('#search-state').html("<option>State</option>");
						$('#search-city').html("<option>City</option>");
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
	});
		
	$('#search-country').change(function(){
		$('.alert-search-forum').hide();	
		$('#search-city').html("<option value='City'>City</option>");
		var countryId = $(this).val();
		var _token = $('#searchform input[name=_token]').val();
		$.ajax({			
			'url' : '/ajax/getstates',
			'data' : { 'countryId' : countryId, '_token' : _token },
			'type' : 'post',
			'success' : function(response){		
				$('#search-state').html(response);
			}			
		});	
	});

	$('#search-state').change(function(){
		$('.alert-search-forum').hide();	
		$('#search-city').html("<option value='City'>City</option>");
		var stateId = $(this).val();
		var _token = $('#searchform input[name=_token]').val();
		$.ajax({			
			'url' : '/ajax/getcities',
			'data' : { 'stateId' : stateId, '_token' : _token },
			'type' : 'post',
			'success' : function(response){
				$('#search-city').html(response);
			}			
		});	
	});

	$('#search-city').change(function(){
		$('.alert-search-forum').hide();
	});

</script>