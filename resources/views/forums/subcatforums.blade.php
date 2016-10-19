@extends('layouts.dashboard')

@include('panels.meta-data')
@section('title', 'Forums')
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

	           	@if(Auth::check())
	            @include('panels.left')
	           @else
	            @include('panels.leftguest')
	           @endif
	     <div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="page-title green-bg">
						<i class="flaticon-user-profile"></i><h1>Forums</h1>
					</div>

					<div class="padding-data-inner">
						@include('forums.searchforum')

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
										$forumslug= $data->forum_slug;
										if($data->updated_at->format('Y-m-d H:i:s') == "-0001-11-30 00:00:00")
												$date = "No Posts";
										else
											$date = $data->updated_at->format('d, M h:i a');
									?>	
						
									<tr onclick="document.location = '/forums/{{$parentforumslug}}/{{$mainforumslug}}/{{$forumslug}}'" style="cursor:pointer">
										<td>{{ $data->title }}</td>
										<td>{{$date}}</td>
										<td><div class="count text-center"><span>{{$count}}</span></div></td>
										<td><a href="{{url("forums/$parentforumslug/$mainforumslug/$forumslug")}}" title=""><i class="flaticon-next"></i></a></td>
									</tr>
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
