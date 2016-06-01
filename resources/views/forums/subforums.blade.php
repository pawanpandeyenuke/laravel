@extends('layouts.dashboard')

<?php
unset($countries[0]);
// print_r($mainforum);die;
if($mainforum == "Doctor")
	$diseases = \App\ForumsDoctor::pluck('title')->toArray();


 ?>


<style type="text/css">
	.boxsize{width:200px;}
</style>
@section('content')
	<div class="page-data dashboard-body">
	   <div class="container">
	    <div class="row">

	      	@if(Auth::attempt())
	            @include('panels.left')
	           @else
	            @include('panels.leftguest')
	           @endif
	     <div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="page-title green-bg">
						<i class="flaticon-user-profile"></i>Forums
					</div>

					<div class="padding-data-inner">
						@include('forums.searchforum')
						
						<div class="forum-srch-list">
							<div class="fs-breadcrumb"><a href="{{url('forums')}}" title="">Home</a> > {{$mainforum}}</div>
							<div class="table-responsive">
								<table class="table">
			@if(!empty($subforums))
			  @if($flag == 0)
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
				?>	

							@if($fieldsdata)
									<tr>
										<td>{{ $data->title }}</td>
										<td>{{$date}}</td>
				<?php 	$subid1 = \App\Forums::where('parent_id',$data->id)->pluck('id');
						$count1 = \App\ForumPost::whereIn('category_id',$subid1)->get()->count();
						?>
										<td><div class="count text-center"><span>{{$count1}}</span></div></td>
										<td><a href="{{url("sub-cat-forums/$forumid")}}" title=""><i class="flaticon-next"></i></a></td>
									</tr>
								@else
									<tr>
										<td>{{ $data->title }}</td>
										<td>{{$date}}</td>
										<!-- <td>31, Jan 12:00 pm</td> -->
										<td><div class="count text-center"><span>{{$count}}</span></div></td>
										<td><a href="{{url("view-forum-posts/$forumid")}}" title=""><i class="flaticon-next"></i></a></td>
									</tr>
							@endif
				@endforeach	
			  @else
			  	{{ Form::open(array('url' => 'view-forum-posts', 'method' => 'post', 'id' => 'chatsubforumsvalidate')) }}
					@foreach($subforums as $data)
                    <?php  
                        $titledata = explode(' ', $data->title);
                        if(is_array($titledata)){
                            $title = strtolower(implode('', $titledata));
                        }                        
                    ?>
					<div class="radio-cont radio-label-left">
						<input class="group-radio" type="radio" name="subcategory" value="{{ $title }}" id="{{ $title }}">
						<label for="{{ $title }}">{{ $data->title }}</label>
						@if($mainforum == 'Doctor')

						  @if($title == 'international')
						  	<div class="subs" style="display:none">
						  		 <select name="i-diseases" class="search-field boxsize" id="diseases-forum">
									@foreach($diseases as $doc)					
										<option value="{{$doc}}">{{$doc}}</option>
									@endforeach
								</select>
						  	</div>
						  	@endif
						@endif

						@if($title == 'country')
							<div class="subs" style="display:none">
							<select name="country1" class="search-field boxsize" id="country">
								@foreach($countries as $data)					
										<option value="{{$data}}">{{$data}}</option>
								@endforeach
							</select>
							@if($mainforum == 'Doctor')
							  <select name="c-diseases" class="search-field boxsize" id="diseases-forum">
									@foreach($diseases as $doc)					
										<option value="{{$doc}}">{{$doc}}</option>
									@endforeach
								</select>
							  @endif
							 </div>
						@elseif($title == 'country,state,city')
							<div class="subs" style="display:none">
								<select name="country" class="search-field boxsize" id="subcountry-forum">
									<option value="">Country</option>
									@foreach($countries as $data)					
										<option value="{{$data}}">{{$data}}</option>
									@endforeach
								</select>

								<select name="state" class="search-field boxsize" id="substate-forum">
									<option value="">State</option>
								</select>
								
								<select name="city" class="search-field boxsize" id="subcity-forum">
									<option value="">City</option>
								</select>
							 @if($mainforum == 'Doctor')
							  <select name="csc-diseases" class="search-field boxsize" id="diseases-forum">
									@foreach($diseases as $doc)					
										<option value="{{$doc}}">{{$doc}}</option>
									@endforeach
								</select>
							 @endif
							</div>
						@endif
					</div> 
					@endforeach
					<div class="btn-cont text-center">
						<button type="submit" class="btn btn-primary btn-lg">Enter Forum</button>
					</div>
					<input type="hidden" name="mainforum" value=
					"{{$mainforum}}">
		    	{{ Form::close() }}
			  @endif
			@endif
								</table>
							</div>
						</div><!--/forum search list-->
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



    $("#chatsubforumsvalidate").validate({ 
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
