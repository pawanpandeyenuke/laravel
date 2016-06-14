@extends('layouts.dashboard')

<?php
// $userobj = $posts->user;
// print_r($post->id);die;
 ?>

<style type="text/css">
	.boxsize{width:200px;}
</style>
@section('content')
	<div id="AllCommentNew1" class="post-list popup-list-without-img" style="display: none;"></div>
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
						<i class="flaticon-user-profile"></i>Forums
					</div>

					<div class="padding-data-inner">
						@include('forums.searchforum')

						<div class="forum-srch-list" id="forum-post-reply_{{$post->id}}">
							<div class="fs-breadcrumb">{{$post->forum_category_breadcrum}}</div>

							<div class="forum-master-post">
								<div class="fp-master-header">
									<div class="row">
										<div class="col-md-6">
									<?php
										$user = $post->user;
										if(isset($post->forumPostLikesCount[0]))
										$likeCount = $post->forumPostLikesCount[0]->forumlikescount;
										else
										$likeCount = 0;
										$userid = $user->id;
										$profileimage = !empty($user->picture) ? $user->picture : '/images/user-thumb.jpg';
										if(Auth::check())
										$likedata = \App\ForumLikes::where(['owner_id' => Auth::User()->id, 'post_id' => $post->id])->get(); 
									?>
											<div class="ut-name">
												<a href = "{{url("profile/$user->id")}}" title = "User Profile">
												<span class="user-thumb" style="background: url('{{$profileimage}}');"></span>
												{{$user->first_name." ".$user->last_name}}
												</a>
											</div>
										</div>
										<div class="col-md-6">
											<div class="fp-master-header-right">

											<div class="fp-likes pull-left">
											  <div class="like-cont">
											  @if(Auth::check())
												<input type="checkbox" name="" id="checkbox_forumpost_replypage_{{$post->id}}" class="css-checkbox likeforumpost" data-forumpostid="{{$post->id}}" {{ isset($likedata[0])?'checked':'' }}/>	
												<label for="checkbox_forumpost_replypage_{{$post->id}}" title="Like Post" class="css-label"></label>
											  @else
											  <input type="checkbox" name="" id="guest-reply" class="css-checkbox"/>
											  <label for="guest-reply" class="css-label"></label>
											  @endif
											  </div>
											  <span class="plike-count" title="Likes">{{$likeCount}}</span>
											</div>
												<span class="p-date pull-left"><i class="flaticon-days"></i> {{$post->updated_at->format('d M Y')}}</span>
												<span class="p-time pull-left"><i class="flaticon-time"></i>  {{$post->updated_at->format('h:i A')}}</span>
											</div>
										</div>
									</div>
								</div>
								<p> {{$post->title}} </p>
								<div class="text-right">
								@if(Auth::check())
								  <button type="button" class="btn btn-primary mpost-rply-btn" title="Write a reply">Reply</button>
								@endif
								</div>
							</div>

							<div class="forum-post-replies">
							
								<div class="forum-post-cont">
									<div class="posts-count"><i class="flaticon-two-post-it"></i><span class="forumreplycount"> {{$replycount}}</span> Posts</div>
								</div><!--/forum post cont-->

								<div class="f-post-form f-post-reply-form">
									<textarea name="" class="form-control forumreply" data-emojiable="true"></textarea>
									<button type="button" class="btn btn-primary forumpostreply" data-forumpostid = "{{$post->id}}" title="Click to post a Reply">Submit</button>
								</div>

								<div class="modal fade edit-forumpost-popup" id="forumreply-edit-modal" tabindex="-1" role="dialog" aria-labelledby="EditPost"></div>

						<div class="f-post-list-outer clearfix forumreplylist">
							@foreach($reply as $forumreply)
							<div class="f-single-post" id="forumreply_{{$forumreply->id}}">
										<div class="p-user">
										<?php 
											$user = $forumreply->user;
											if(isset($forumreply->replyLikesCount[0]))
												$likeCount = $forumreply->replyLikesCount[0]->replyLikesCount;
											else
												$likeCount = 0;
											if(isset($forumreply->replyCommentsCount[0]))
												$commentCount = $forumreply->replyCommentsCount[0]->replyCommentsCount;
											else
												$commentCount = 0;
										 	$userid = $user->id;
											$profileimage = !empty($user->picture) ? $user->picture : '/images/user-thumb.jpg';
											$name = $user->first_name." ".$user->last_name;
											if(Auth::check()){
											$likedata = \App\ForumReplyLikes::where(['owner_id' => Auth::User()->id, 'reply_id' => $forumreply->id])->get();

										    if($user->id == Auth::User()->id)
												$temp_class = "";
											else
												$temp_class = "without-action-btn";
											}
											else { $temp_class = "without-action-btn"; }
										?>
											<a href = "{{url("profile/$userid")}}" title = "User Profile">
											<span class="user-thumb" style="background: url('{{$profileimage}}');"></span>
											</a>
											<div class="p-likes ml">
											<div class="like-cont">
											@if(Auth::check())
												<input type="checkbox" name="" id="checkbox_forumreply_{{$forumreply->id}}" class="css-checkbox likeforumreply" data-forumreplyid="{{$forumreply->id}}" {{ isset($likedata[0])?'checked':'' }}/>
												<label for="checkbox_forumreply_{{$forumreply->id}}" title="Like Reply" class="css-label"></label>
											@else
												<input type="checkbox" id="guest-reply2" class="css-checkbox"/>
												<label for="guest-reply2" class="css-label"></label>
											@endif
											</div>
											<span class="plike-count forumreplylike"  title="Likes">{{$likeCount}}</span>
											</div>
											<div class="p-likes ml">
												<a href="#" class="popupforumreply" data-replyid = "{{$forumreply->id}}">
													<i class="fa fa-comment" title="Open Comments" aria-hidden="true"></i> 
													<span class="plike-count" id="forumreplycomment_{{$forumreply->id}}" title="Comments">{{$commentCount}}</span>
												</a>
											</div>
										
										</div>

										<div class="f-post-title {{$temp_class}}">
											<a href = "{{url("profile/$userid")}}" title = "User Profile">{{$name}}</a>
											<div class="fp-meta">
												<span class="p-date"><i class="flaticon-days"></i> {{$forumreply->updated_at->format('d M Y')}}</span>
												<span class="p-time"><i class="flaticon-time"></i> {{$forumreply->updated_at->format('h:i A')}}</span>
											</div>
											@if(Auth::check())
											@if($userid == Auth::User()->id)
											<div class="fp-action">
											<button class='editforumreply' value='{{$forumreply->id}}'data-forumpostid = "{{$post->id}}" title="Edit Reply"><i class='flaticon-pencil'></i></button>
											<button class='forumreplydelete' title="Delete Reply" value='{{$forumreply->id}}' data-forumpostid = "{{$post->id}}"><i class='flaticon-garbage'></i></button>
											</div>
											@endif
											@endif
										</div>
										<p class="more"><?php echo nl2br($forumreply->reply); ?></p>
									</div><!--/single post-->								
								@endforeach
							</div>
							 @if($replycount > 10)
							<div class="load-more-btn-cont text-center">
								<button type="button" class="btn btn-primary btn-smbtn-sm load-more-forumreply" data-forumpostid = "{{$post->id}}" title="View More Replies">View More</button>
							</div>
							@endif
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
<script type="text/javascript" src="{{url('/js/jquery-1.11.3.min.js')}}"></script>
<script type="text/javascript" src="{{url('/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{url('/fancybox/jquery.fancybox.js')}}"></script>
<script src="{{url('/js/select2.min.js')}}"></script>
<script type="text/javascript">
$(".multiple-slt").select2();

	$("#up_imgs").fileinput({
    uploadUrl: "/file-upload-batch/2",
    allowedFileExtensions: ["jpg", "png", "gif"],
    minImageWidth: 30,
    minImageHeight: 30,
    showCaption: false,
	});
	//$('.popup').fancybox();

	

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
	//$('.pop-comment-side .post-comment-cont').niceScroll();

	$(document).on('click','.mpost-rply-btn',function(){
		$('.f-post-reply-form').slideToggle();
	});


	// More Less Text

	$(document).ready(function() {
	  var showChar = 300;
	  var ellipsestext = "...";
	  var moretext = "more";
	  var lesstext = "less";
	  $('.more').each(function() {
	      var content = $(this).html();

	      if(content.length > showChar) {

	          var c = content.substr(0, showChar);
	          var h = content.substr(showChar-1, content.length - showChar);

	          var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

	          $(this).html(html);
	      }

	  });
		$(document).on('click','.morelink',function(){
	      if($(this).hasClass("less")) {
	          $(this).removeClass("less");
	          $(this).html(moretext);
	      } else {
	          $(this).addClass("less");
	          $(this).html(lesstext);
	      } 
	      $(this).parent().prev().toggle();
	      $(this).prev().toggle();
	      return false;
	  });

	});


</script>
</body>
</html>
