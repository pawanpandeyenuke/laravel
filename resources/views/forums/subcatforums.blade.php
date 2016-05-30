@extends('layouts.dashboard')

<?php
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
	     <div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="page-title green-bg">
						<i class="flaticon-user-profile"></i>Forums
					</div>

					<div class="padding-data-inner">
						<div class="forum-filter">
							<div class="row">
								<div class="col-md-4">
									<select class="form-control">
										<option>School Reviews</option>
									</select>
								</div>
								<div class="col-md-4">
									<select class="form-control">
										<option>City</option>
									</select>
								</div>
								<div class="col-md-4">
									<input type="text" name="" value="" placeholder="Search Keyword" class="form-control">
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<select class="form-control">
										<option>India</option>
									</select>
								</div>
								<div class="col-md-4">
									<select class="form-control">
										<option>Haryana</option>
									</select>
								</div>
								<div class="col-md-4">
									<select class="form-control">
										<option>Gurgaon</option>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4 col-md-offset-4">
									<div class="forum-btn-cont text-center">
										<button type="button" class="btn btn-primary">Search</button>
									</div>
								</div>
							</div>
						</div><!--/forum filter-->

						<div class="forum-srch-list">
							<div class="fs-breadcrumb"><a href="{{url('forums')}}" title="">Home</a> > <a href = "{{url("sub-forums/$parentforumid")}}" title="">{{$parentforum}}</a> >{{$mainforum}}</div>
							<div class="table-responsive">
								<table class="table">
			@if(!empty($subforums))
				@foreach($subforums as $data)
				<?php
				 	$count = \App\ForumPost::where('category_id',$data->id)->get()->count();
					$fieldsdata = \App\Forums::where('parent_id',$data->id)->value('id');
					$forumid = $data->id;
				?>	
							@if($fieldsdata)
									<tr>
										<td>{{ $data->title }}</td>
										<td>{{$data->updated_at->format('d, M h:i a')}}</td>
										<td><div class="count text-center"><span>{{$count}}</span></div></td>
										<td><a href="{{url("sub-cat-forums/$forumid")}}" title=""><i class="flaticon-next"></i></a></td>
									</tr>
							@else
									<tr>
										<td>{{ $data->title }}</td>
										<td>{{$data->updated_at->format('d, M h:i a')}}</td>
										<td><div class="count text-center"><span>{{$count}}</span></div></td>
										<td><a href="{{url("view-forum-posts/$forumid")}}" title=""><i class="flaticon-next"></i></a></td>
									</tr>
							@endif
							@endforeach	
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
 
 <script type="text/javascript">
 	
 $(document).on('click', '.group-radio', function(){

 	if($(this).is(':checked')){

 		// alert('asdfsa');
		$(this).closest('.radio-cont').next().find('.subs').hide();
		$(this).closest('.radio-cont').find('.subs').show();
		$(this).closest('.radio-cont').prev().find('.subs').hide();

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
