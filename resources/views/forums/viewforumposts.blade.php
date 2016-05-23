@extends('layouts.dashboard')

<?php
//print_r($posts);die;
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
						<?php
								$subparent = \App\Forums::where('id',$categoryid)->value('parent_id');
								if($subparent == 0){
						 ?>

							<div class="fs-breadcrumb"><a href="{{url('forums')}}" title="">Home</a> > {{$categoryname}}</div>
						
						<?php } else{
									$subparentname = \App\Forums::where('id',$subparent)->value('title');
									$parent = \App\Forums::where('id',$subparent)->value('parent_id');
									if($parent == 0)
									{
									?>
								<div class="fs-breadcrumb"><a href="{{url('forums')}}" title="">Home</a> > <a href="{{url("subforums/$subparent")}}" title=""> {{$subparentname}} </a> > {{$categoryname}}</div>
							<?php }else{
								$parentname = \App\Forums::where('id',$parent)->value('title');
							 ?>	
							 	<div class="fs-breadcrumb"><a href="{{url('forums')}}" title="">Home</a> > <a href="{{url("subforums/$parent")}}" title=""> {{$parentname}} </a> > <a href="{{url("subcatforums/$subparent")}}" title=""> {{$subparentname}} </a> > {{$categoryname}}</div>
							 	<?php } 
							 	} ?>
							<div class="forum-post-cont">
								<div class="posts-count"><i class="flaticon-two-post-it"></i> {{$postscount}} Posts</div>
							</div><!--/forum post cont-->

							<!---New Forum Post-->
						{!! Form::open(array('url' => '/addnewforumpost', 'id' => 'addnewpost','method' => 'post')) !!}
							<div class="f-post-form">
							<input type="hidden" name="category_id" value="{{$categoryid}}">
								<textarea name="topic" class="form-control" data-emojiable="true"></textarea>
								<button type="submit" class="btn btn-primary">Submit</button>
							</div>
						{!! Form::close() !!}
							<!---END New Forum Post-->

							<div class="f-post-list-outer">
							@foreach($posts as $data)
								<div class="f-single-post">
									<div class="p-user">
									<?php 
									$userid = $data->user->id;
									$profileimage = !empty($data->user->picture) ? $data->user->picture : '/images/user-thumb.jpg';
									?>
										<span class="user-thumb" style="background: url('{{$profileimage}}');"></span>
										<span class="p-date"><i class="flaticon-days"></i> {{$data->updated_at->format('d M Y')}}</span>
										<span class="p-time"><i class="flaticon-time"></i> {{$data->updated_at->format('h:i A')}}</span>
										<div class="p-likes"><i class="flaticon-web"></i> <span class="plike-count">19</span></div>
									</div>

									<div class="f-post-title">
									<a href="{{url("profile/$userid")}}" title="">
										{{$data->user->first_name." ".$data->user->last_name}}
										@if($data->user->id == Auth::user()->id)
										<a>
										<div class="fp-action">
											<button class="editforumpost" value="{{$data->id}}"  data-toggle="modal" title="Edit" data-target=".edit-forumpost-popup"><i class="flaticon-pencil"></i></button>
											<button class="forumpostdelete" value="{{$data->id}}"><i class="flaticon-garbage"></i></button>
										</div>
										@endif
									</div>

									<p>{{$data->title}} </p>

									<div class="fp-btns text-right">
										<span class="btn btn-primary">Replies(8)</span>
										<a href="#" title="" class="btn btn-primary"><span class="glyphicon glyphicon-share-alt"></span>Reply</a>
									</div>

								</div><!--/single post-->
								@endforeach

							</div>

							<div class="load-more-btn-cont text-center">
								<button type="button" class="btn btn-primary btn-smbtn-sm">Load More</button>
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

@endsection
{!! Session::forget('error') !!}
<script type="text/javascript" src="{{url('/js/jquery-1.11.3.min.js')}}"></script>
<script type="text/javascript">
	


</script>
