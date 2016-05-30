@extends('layouts.dashboard')

<?php
// $userobj = $posts->user;
// print_r($categoryid);die;
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
						 <div id="sticky-anchor"></div>
						 	<div class="fix-header">
								 <div class="fs-breadcrumb">{{$breadcrum}}</div>

								<div class="forum-post-cont">
									<div class="posts-count"><i class="flaticon-two-post-it"></i><span class = "count"> {{$postscount}}</span> Posts</div>
								</div><!--/forum post cont-->

								<!---New Forum Post-->
								<div class="f-post-form">
									<textarea name="topic" class="form-control forumpost" data-emojiable="true"></textarea>
									<button type="button" class="btn btn-primary addforumpost" value="{{$categoryid}}">Submit</button>
								</div>
							</div>
							<!---END New Forum Post-->

							<div class="modal fade edit-forumpost-popup" id="forumpost-edit-modal" tabindex="-1" role="dialog" aria-labelledby="EditPost"></div>

							<div class="f-post-list-outer forumpostlist">
							@foreach($posts as $data)
								<div class="f-single-post" id="forumpost_{{$data->id}}">
									<div class="p-user">
									<?php 
										$user = $data->user;
											if(isset($data->forumPostLikesCount[0]))
												$likeCount = $data->forumPostLikesCount[0]->forumlikescount;
											else
												$likeCount = 0;
											if(isset($data->replyCount[0]))
												$replyCount = $data->replyCount[0]->replyCount;
											else
												$replyCount = 0;
									$userid = $user->id;
									$profileimage = !empty($data->user->picture) ? $user->picture : '/images/user-thumb.jpg';

									$likedata = \App\ForumLikes::where(['owner_id' => Auth::User()->id, 'post_id' => $data->id])->get(); 
									?>
										<span class="user-thumb" style="background: url('{{$profileimage}}');"></span>
										<span class="p-date"><i class="flaticon-days"></i> {{$data->updated_at->format('d M Y')}}</span>
										<span class="p-time"><i class="flaticon-time"></i> {{$data->updated_at->format('h:i A')}}</span>

										<div class="p-likes">
											<div class="like-cont">
												<input type="checkbox" name="" id="checkbox{{$data->id}}" class="css-checkbox likeforumpost" data-forumpostid="{{$data->id}}" {{ isset($likedata[0])?'checked':'' }}/>	
												<label for="checkbox{{$data->id}}" class="css-label"></label>
											</div>
											<span class="plike-count">{{$likeCount}}</span>
										</div>

									</div>

									<div class="f-post-title">
									<a href="{{url("profile/$userid")}}" title="">
										{{$data->user->first_name." ".$data->user->last_name}}
									</a>
									@if($data->user->id == Auth::user()->id)
										<div class="fp-action">
											<button class="editforumpost" value="{{$data->id}}" title="Edit" ><i class="flaticon-pencil" ></i></button>
											<button class="forumpostdelete" value="{{$data->id}}" data-categoryid = "{{$categoryid}}"><i class="flaticon-garbage" ></i></button>
										</div>
									@endif
									</div>

									<p> {{$data->title}} </p>

									<div class="fp-btns text-right">
										<span class="reply-count">Replies ({{$replyCount}})</span>
										<a href="{{url("forum-post-reply/$data->id")}}" title="" class="btn btn-primary"><span class="glyphicon glyphicon-share-alt"></span>Reply</a>
									</div>

								</div><!--/single post-->
							@endforeach
							</div>
							 <!-- <div class="pagination">  </div> -->
							 @if($postscount > 10)
							<div class="load-more-btn-cont text-center">
								<button type="button" class="btn btn-primary btn-smbtn-sm load-more-forumpost" data-categoryid = "{{$categoryid}}">View More</button>
							</div>
							@endif
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
<!--<script src="{{url('/lib/js/jquery.emojiarea.js')}}"></script>
<script src="{{url('/lib/js/emoji-picker.js')}}"></script> -->

<script type="text/javascript" src="{{url('/js/jquery-1.11.3.min.js')}}"></script>
<script type="text/javascript">
	
	window.onload = function() {

			window.emojiPicker = new EmojiPicker({
			emojiable_selector: '[data-emojiable=true]',
			assetsPath: '/lib/img/',
			popupButtonClasses: 'fa fa-smile-o'
      	});
      window.emojiPicker.discover();
      loadOrgionalImogi();

      var w = $('#sticky-anchor').width();
		$('.fix-header').css('width',w+60);
	}

	//Fix on Scroll
	function sticky_relocate() {
    var window_top = $(window).scrollTop();
    var div_top = $('#sticky-anchor').offset().top;
    if (window_top > div_top) {
      $('.fix-header').addClass('stick');
    } else {
      $('.fix-header').removeClass('stick');
    }
	}

	 $(function () {
	    $(window).scroll(sticky_relocate);
	    sticky_relocate();
	});


</script>