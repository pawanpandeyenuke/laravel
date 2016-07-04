@extends('layouts.dashboard')

<?php
unset($countries[0]);
// print_r($mainforum);die;
if($mainforum->title == "Doctor")
	$diseases = \App\ForumsDoctor::pluck('title')->toArray();


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
											// print_r($data->updated_at->format('Y-m-d H:i:s') );die;
											if($data->updated_at->format('Y-m-d H:i:s') == "-0001-11-30 00:00:00")
													$date = "No Posts";
											else
												$date = $data->updated_at->format('d, M h:i a');
										?>	@if($fieldsdata)
													 <?php $subid1 = \App\Forums::where('parent_id',$data->id)->pluck('id');
															$count1 = \App\ForumPost::whereIn('category_id',$subid1)->get()->count(); ?>
															<tr>
																<td>{{ $data->title }}</td>
																<td>{{$date}}</td>
																<td><div class="count text-center"><span>{{$count1}}</span></div></td>
																<td><a href="{{url("sub-cat-forums/$forumid")}}" title=""><i class="flaticon-next"></i></a></td>
															</tr>
														@else
															<tr>
																<td>{{ $data->title }}</td>
																<td>{{$date}}</td>
																<td><div class="count text-center"><span>{{$count}}</span></div></td>
																<td><a href="{{url("view-forum-posts/$forumid")}}" title=""><i class="flaticon-next"></i></a></td>
															</tr>
													@endif
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
											<div class="tab-content">
												  <div role="tabpanel" class="tab-pane" id="international">
													    {!! Form::open(array('url' => 'view-forum-posts', 'method' => 'post', 'id' => 'forum_select_form_int')) !!}
														    	<input type="hidden" name="mainforum" value="{{$mainforum->title}}" />
														    	<input type="hidden" name="subcategory" value="international" />
													    	@if($mainforum->title == "Doctor")
													    		<div class="row">
													    			<div class="col-md-4 col-md-offset-4">
													    				<select name="idiseases" class="form-control">
														    					<option value="">Select Option</option>
															    				@foreach($diseases as $doc)					
																					<option value="{{$doc}}">{{$doc}}</option>
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
																	<div class="col-md-4 {{$cls}}">
																		<select name="country1" class="form-control">
																				<option value="">Select Country</option>
																				@foreach($countries as $data)					
																				<option value="{{$data}}">{{$data}}</option>
																				@endforeach
																		</select>
																	</div>
																	@if($mainforum->title == "Doctor")
																	<div class="col-md-4">
													    				<select name="cdiseases" class="form-control">
														    					<option value="">Select Option</option>
														    					@foreach($diseases as $doc)					
																					<option value="{{$doc}}">{{$doc}}</option>
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
																		<div class="col-md-4">
																			<select name="country" class="form-control" id="subcountry-forum">
																					<option value="">Select Country</option>
																					@foreach($countries as $data)					
																					<option value="{{$data}}">{{$data}}</option>
																					@endforeach
																			</select>
																		</div>
																		<div class="col-md-4">
																				<select name="state" class="form-control" id="substate-forum">
																						<option>Select State</option>
																				</select>
																		</div>
																		<div class="col-md-4">
																				<select name="city" class="form-control" id="subcity-forum">
																						<option>Select City</option>
																				</select>
																		</div>
																		@if($mainforum->title == "Doctor")
																		<div class="col-md-4 col-md-offset-4 margin-top20">
													    					<select name="cscdiseases" class="form-control">
													    							<option value="">Select Option</option>
													    							@foreach($diseases as $doc)					
																						<option value="{{$doc}}">{{$doc}}</option>
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
<!-- <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script> -->

 <script type="text/javascript">

    $("#forum_select_form").validate({ 
        errorElement: 'span',
        errorClass: 'help-inline',
        rules: {
            subcategory: { required: true },
            country: { required: true },
            state: { required: true },
            city: {required: true},
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

	$('#subcountry-forum').change(function(){
		var countryId = $(this).val();
		var _token = $('#searchform input[name=_token]').val();
		$.ajax({			
			'url' : '/ajax/getstates',
			'data' : { 'countryId' : countryId, '_token' : _token },
			'type' : 'post',
			'success' : function(response){				
				$('#substate-forum').html(response);
			}			
		});	
	});

	$('#substate-forum').change(function(){
		var stateId = $(this).val();
		var _token = $('#searchform input[name=_token]').val();
		$.ajax({			
			'url' : '/ajax/getcities',
			'data' : { 'stateId' : stateId, '_token' : _token },
			'type' : 'post',
			'success' : function(response){
				$('#subcity-forum').html(response);
			}			
		});	
	});
 </script>

@endsection
{!! Session::forget('error') !!}
