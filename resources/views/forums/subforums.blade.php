@extends('layouts.dashboard')
@section('title', 'Forums')
<?php
if($mainforum->title == "Doctor") {
	$diseases = \App\ForumsDoctor::select(['doctor_slug','title'])->get();
}
?>

<style type="text/css">
.boxsize{width:200px;}
</style>

@section('content')
	<div class="page-data dashboard-body">
	   <div class="container">
	    <div class="row">

				@if(Auth::check())
			      @include('panels.left')
			  @else
			      @include('panels.leftguest')
			  @endif
<?php 
			$icon_url = url('forums-data/forum_icons/'.$mainforum->img_url);
?>
	     <div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="page-title green-bg">
						<img src="{{$icon_url}}" alt="" class="img-icon"> Forums
					</div>

					<div class="padding-data-inner">
						@include('forums.searchforum')
						@if($flag == 0)
						<div class="forum-srch-list">
							<div class="fs-breadcrumb margin-bottom"><a href="{{url('forums')}}" title="">Home</a> > {{$mainforum->title}}</div>
							<div class="table-responsive">
								<table class="table">
									@if(!empty($subforums))
										@foreach($subforums as $data)
										<?php
										 	$count = \App\ForumPost::where('category_id',$data->id)->get()->count();
											$fieldsdata = \App\Forums::where('parent_id',$data->id)->value('id');
											$forumid = $data->id;
											$forumSlug = $data->forum_slug;
											if($data->updated_at->format('Y-m-d H:i:s') == "-0001-11-30 00:00:00")
													$date = "No Posts";
											else
												$date = $data->updated_at->format('d, M h:i a');
										?>	@if($fieldsdata)
											<?php $subid1 = \App\Forums::where('parent_id',$data->id)->pluck('id');
															$count = \App\ForumPost::whereIn('category_id',$subid1)->get()->count(); ?>
											@endif
											
											<tr>
												<td>{{ $data->title }}</td>
												<td>{{$date}}</td>
												<td><div class="count text-center"><span>{{$count}}</span></div></td>
												<td><a href="{{url("forums/$mainforum->forum_slug/$forumSlug")}}" title=""><i class="flaticon-next"></i></a></td>
											</tr>
											
										@endforeach	
										@endif
								</table>
							</div>
						</div><!--/forum search list-->
						@else
				 			<div class="fs-breadcrumb margin-bottom">
									Home > {{$mainforum->title}}
							</div>

							<div class="row">
								<div class="col-md-12">
									<div class="btn-tab-cont margin-top">
				 						<ul class="nav nav-tabs row" role="tablist">
												@foreach($subforums as $data)
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
												 <li role="presentation" class="col-md-4 col-xs-12"><a href="#{{$aria}}" aria-controls="{{$aria}}" role="tab" data-toggle="tab">{{$view_title}}</a></li>
												@endforeach
											</ul>
											<div class="tab-content">
												  <div role="tabpanel" class="tab-pane" id="international">
													    {!! Form::open(array('url' => 'view-forum-posts', 'method' => 'post', 'id' => 'forum_select_form_int')) !!}
														    	<input type="hidden" name="mainforum" value="{{$mainforum->title}}" />
														    	<input type="hidden" name="subcategory" value="international" />
													    	@if($mainforum->title == "Doctor")
													    		<div class="row">
													    			<div class="col-md-4 col-xs-12 col-md-offset-4">
													    				<select name="idiseases" class="form-control sel-diseases">
														    				<option data-value="" value="">Select Option</option>
															    			@foreach($diseases as $doc)					
																				<option data-value="{{$doc->doctor_slug}}" value="{{$doc->title}}">{{$doc->title}}</option>
																			@endforeach
																		</select>
													    			</div>
													    		</div>
													    	@endif
																	<div class="tab-btn-cont">
																		<button type="submit" class="btn btn-primary">Enter Forum</button>
																	</div>
														  {{ Form::close() }}
												  </div>

												  <div role="tabpanel" class="tab-pane" id="country-tab">
															{!! Form::open(array('url' => 'view-forum-posts', 'method' => 'post', 'id' => 'forum_select_form_country')) !!}
													    		<input type="hidden" name="mainforum" value="{{$mainforum->title}}" />
													    		<input type="hidden" name="subcategory" value="country" />
																<div class="row">
																	<?php 
																		if($mainforum->title == "Doctor")
																			$cls = "col-md-offset-2";
																		else
																			$cls = "col-md-offset-4";
																	?>
																	<div class="col-md-4 col-xs-12 {{$cls}}">
																		<select name="country1" class="form-control sel-country">
																			<option data-value="" value="">Select Country</option>
																				@foreach($forumcountries as $data)					
																					<option data-value="{{$data->country_slug}}" value="{{$data->country_name}}">{{$data->country_name}}</option>
																				@endforeach
																		</select>
																	</div>
																	@if($mainforum->title == "Doctor")
																	<div class="col-md-4 col-xs-12">
													    				<select name="cdiseases" class="form-control sel-diseases">
														    					<option data-value="" value="">Select Option</option>
															    			@foreach($diseases as $doc)					
																				<option data-value="{{$doc->doctor_slug}}" value="{{$doc->title}}">{{$doc->title}}</option>
																			@endforeach
																			</select>
																	</div>
																	@endif
																</div>
																<div class="tab-btn-cont">
																	<button type="submit" class="btn btn-primary">Enter Forum</button>
																</div>
															{{ Form::close() }}
												  </div>

												  <div role="tabpanel" class="tab-pane" id="csc-tab">
													   	{!! Form::open(array('url' => 'view-forum-posts', 'method' => 'post', 'id' => 'forum_select_form')) !!}
														    	<input type="hidden" name="mainforum" value="{{$mainforum->title}}" />
														    	<input type="hidden" name="subcategory" value="country,state,city" />
																<div class="row">
																		<div class="col-md-4 col-xs-12">
																			<select name="country" class="form-control sel-country" id="subcountry-forum">
																				<option data-value="" value="">Select Country</option>
																				@foreach($forumcountries as $data)					
																					<option data-value="{{$data->country_slug}}" value="{{$data->country_name}}">{{$data->country_name}}</option>
																				@endforeach
																			</select>
																		</div>
																		<div class="col-md-4 col-xs-12">
																				<select name="state" class="form-control sel-state" id="substate-forum">
																						<option>Select State</option>
																				</select>
																		</div>
																		<div class="col-md-4 col-xs-12">
																				<select name="city" class="form-control sel-city" id="subcity-forum">
																						<option>Select City</option>
																				</select>
																		</div>
																		@if($mainforum->title == "Doctor")
																		<div class="col-md-4 col-xs-12 col-md-offset-4 margin-top20">
													    					<select name="cscdiseases" class="form-control sel-diseases">
													    						<option data-value="" value="">Select Option</option>
																    			@foreach($diseases as $doc)					
																					<option data-value="{{$doc->doctor_slug}}" value="{{$doc->title}}">{{$doc->title}}</option>
																				@endforeach
																				</select>
																		</div>
													    			@endif
																</div>
																<div class="tab-btn-cont">
																		<button type="submit" class="btn btn-primary csc">Enter Forum</button>
																</div>
															{{ Form::close() }}
												  </div>
											</div>
									 	</div>
								</div>
						  </div>									 
						@endif
					</div>
				</div><!--/page center data-->
				<div class="shadow-box bottom-ad"><img src="{{url('images/bottom-ad.jpg')}}" alt="" class="img-responsive"></div>
			</div>
 		@include('panels.right')
            </div>
        </div>
    </div><!--/pagedata-->

<script type="text/javascript">
jQuery(function($){
    $("#forum_select_form").validate({ 
        errorElement: 'span',
        errorClass: 'help-inline',
        rules: {
            subcategory: { required: true },
            country: { required: true },
            state: { required: true },
            // city: {required: true},
            cscdiseases: {required: true}
        },
        messages:{
            subcategory:{
                required: "Please select a sub category."
            },
            country:{
                required: "Please select a country."
            },
            state:{
                required: "Please select a state."
            },
            city:{
                required: "Please select a city."
            },
            cscdiseases:{
            		required: "Please select an option."
            }
        }
    });

    $("#forum_select_form_int").validate({ 
        errorElement: 'span',
        errorClass: 'help-inline',
        rules: {
            subcategory: { required: true },
            idiseases: {required: true}
        },
        messages:{
            subcategory:{
                required: "Please select a sub category."
            },
            idiseases:{
            		required: "Please select an option."
            }
        }
    });

	$("#forum_select_form_country").validate({ 
        errorElement: 'span',
        errorClass: 'help-inline',
        rules: {
            subcategory: { required: true },
           	country1: {required: true},
            cdiseases: {required: true}
        },
        messages:{
            subcategory:{
                required: "Please select a sub category."
            },
            country1:{
                required: "Please select a country."
            },
            cdiseases:{
            		required: "Please select an option."
            }
        }
    });

	$( "#forum_select_form_int" ).submit( function(){

 		var diseases 	= $(this).find( ".sel-diseases :selected" ).data( 'value' );
 		if( typeof diseases == 'undefined' ){
 			diseases = '';
 		} else {
 			diseases = '/'+diseases;
 		}
 		window.location.href = "<?php echo url( '/forums/'.$mainforum->forum_slug ); ?>/international"+diseases;
 		return false;
 	});


 	$( "#forum_select_form_country" ).submit( function(){
 		var country 	= $(this).find( ".sel-country :selected" ).data( 'value' );
 		var diseases 	= $(this).find( ".sel-diseases :selected" ).data( 'value' );
 		if( typeof diseases == 'undefined' ){
 			diseases = '';
 		} else {
 			diseases = '/'+diseases;
 		}
 		if( typeof country != 'undefined' && country != '' ){
 			window.location.href = "<?php echo url( '/forums/'.$mainforum->forum_slug ); ?>/"+country+diseases;
 		}
 		return false;
 	});

 	$( "#forum_select_form" ).submit( function(){
 		var country  = $(this).find( ".sel-country :selected" ).data( 'value' );
 		var state 	 = $(this).find( ".sel-state :selected" ).data( 'value' );
 		var city 	 = $(this).find( ".sel-city :selected" ).data( 'value' );
 		var diseases = $(this).find( ".sel-diseases :selected" ).data( 'value' );
 		if( typeof diseases == 'undefined' ){
 			diseases = '';
 		} else {
 			diseases = '/'+diseases;
 		}
 		if( typeof country != 'undefined' && typeof state != 'undefined' && typeof city != 'undefined' && country != '' && state != '' && city != '' ){
 			window.location.href = "<?php echo url( '/forums/'.$mainforum->forum_slug ); ?>/"+country+'/'+state+'/'+city+diseases;
 		}
 		return false;
 	});

	$('#subcountry-forum').change(function(){
		var countryId = $(this).val();
		if( countryId )
		{
			var _token = $('#searchform input[name=_token]').val();
			$.ajax({			
				'url' : '/ajax/getstates',
				'data' : { 'countryId' : countryId, '_token' : _token },
				'type' : 'post',
				'success' : function(response){				
					$('#substate-forum').html(response);
					$('#subcity-forum').html('<option value="">Select City</option>');
				}			
			});
		} else {
			$('#substate-forum').html('<option value="">Select State</option>');
			$('#subcity-forum').html('<option value="">Select City</option>');
		}
	});

	$('#substate-forum').change(function(){
		var stateId = $(this).val();
		if( stateId )
		{
			var _token = $('#searchform input[name=_token]').val();
			$.ajax({			
				'url' : '/ajax/getcities',
				'data' : { 'stateId' : stateId, '_token' : _token },
				'type' : 'post',
				'success' : function(response){
					$('#subcity-forum').html(response);
				}			
			});
		} else {
			$('#subcity-forum').html('<option value="">Select City</option>');
		}
	});
});
</script>
{!! Session::forget('error') !!}
@endsection