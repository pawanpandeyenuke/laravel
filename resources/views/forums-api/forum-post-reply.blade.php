@extends('layouts.api')

@section('title', 'Forum Post Reply')

@section('content')

	<?php
		$user = $checkpost['user'];

		$likesCount = isset($checkpost->forumPostLikesCount[0]) ? $checkpost->forumPostLikesCount[0]['forumlikescount'] : 0;
	
		$rawCountry = [$user->city, $user->state, $user->country];
		foreach ($rawCountry as $key => $value) {
			if($value == ''){
				unset($rawCountry[$key]);
			}
		}
		$location = implode(', ', $rawCountry);
		$postTitle = !empty($checkpost->title) ? $checkpost->title : '';
		$breadcrumb = !empty($checkpost->forum_category_breadcrum) ? $checkpost->forum_category_breadcrum : '';
		$pic = !empty($user->picture) ? $user->picture : 'images/user-thumb.jpg';

		$likedata = \App\ForumLikes::where(['owner_id' => $user_id, 'post_id' => $checkpost->id])->get(); 

	?>
	<div class="forum-post-list">
		<div class="single-post">
			<div class="post-header">
				<span class="u-img" style="background: url('<?= url($pic) ?>');"></span>
				<span class="title">{{ $user->first_name.' '.$user->last_name }}</span>
				<div class="post-time">
					<span class="date"><img src="{{url('forums-data/images/date-icon.png')}}" alt="">{{ $checkpost->updated_at->format('D jS') }}</span>
					<span class="time"><img src="{{url('forums-data/images/time-icon.png')}}" alt="">{{ $checkpost->updated_at->format('h:i A') }}</span>
				</div>
				<span class="loc">
					<img src="{{url('forums-data/images/location.png')}}" alt="">{{ !empty($location)?$location:'N/A' }}
				</span>
				<div class="breadcrumb-cont">
					<?= $breadcrumb ?>
				</div>
			</div>

			<div class="post-data">
				<p id="forum_post"><?php echo nl2br(forumPostContents($postTitle, '#', 135)); ?></p>
			</div>
			<div class="post-action clearfix">
				<div class="row-cont clearfix">
					<div class="like-cont">
					@if($user_id)
						<input type="checkbox" name="checkboxG1" id="checkboxG1-post-replypage-{{$checkpost->id}}" data-forumpostid="{{$checkpost->id}}" data-userid="{{$user_id}}" class="css-checkbox api-likeforumpost" {{ isset($likedata[0])?'checked':'' }}>
						<label for="checkboxG1-post-replypage-{{$checkpost->id}}" class="css-label">
					@else
						<input type="checkbox" name="checkboxG1" id="guest-{{$checkpost->id}}" data-forumpostid="{{$checkpost->id}}" class="css-checkbox">
						<label for="guest-{{$checkpost->id}}" class="css-label">
					@endif
						<span class="likescount">{{ $likesCount }}</span></label>
					</div>
				</div>
			</div>
		</div>

		<div class="reply-post-cont">

			@foreach($replies as $reply)
				<?php 
					$replyUser = $reply->user;

					$rawReplyCountry = [$replyUser->city, $replyUser->state, $replyUser->country];
					foreach ($rawReplyCountry as $key => $value) {
						if($value == ''){
							unset($rawReplyCountry[$key]);
						}
					}
					$replyLocation = implode(', ', $rawReplyCountry);

					$reply_data = !empty($reply->reply) ? $reply->reply : '';

					$replyLikessCount = isset($reply->replyLikesCount[0]) ? $reply->replyLikesCount[0]['replyLikesCount'] : 0;

					$replyCommentsCount = isset($reply->replyCommentsCount[0]) ? $reply->replyCommentsCount[0]['replyCommentsCount'] : 0;
					$replyUserPic = !empty($replyUser->picture) ? $replyUser->picture : 'images/user-thumb.jpg';

					$likedata = \App\ForumReplyLikes::where(['owner_id' => $user_id, 'reply_id' => $reply->id])->get();
				?>
				<div class="single-post" id="forumreply_{{$reply->id}}">
					<div class="post-header">
					  	@if($user_id)
					  		@if($user_id == $reply->owner_id)
								<div class="dropdown reply-action">
									<button type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
										<img src="{{url('forums-data/images/dd-btn.png')}}" alt="">
									</button>
									<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
										<?php $title = base64_encode(nl2br($reply_data)); ?>
										<li><a href="{{ url("api/get-forum-reply-details?reply_id=$reply->id&user_id=$user->id&reply_data=$title") }}">Edit</a></li>
										<li><a href="#" class="del-confirm-api" data-type="reply" data-forumpostid="{{$checkpost->id}}" data-forumreplyid = "{{$reply->id}}">Delete</a></li>
									</ul>
								</div>
						  	@endif
					  	@endif
						<span class="u-img" style="background: url('<?= url($replyUserPic) ?>');"></span>
						<span class="title">{{ $replyUser->first_name.' '.$replyUser->last_name}}</span>
						<span class="loc">
							<img src="{{url('/forums-data/images/location.png')}}" alt="">{{ !empty($replyLocation)?$replyLocation:'N/A' }}
						</span>
					</div>
					<div class="post-data">
						<p><?php echo nl2br(forumPostContents($reply_data, '#', 135)); ?></p>
					</div>
					<div class="post-action clearfix">
						<div class="row-cont clearfix">
							<div class="like-cont like-bottom">
							@if($user_id)
								<input type="checkbox" name="checkboxG1" id="checkboxG1-reply-{{$reply->id}}" data-forumreplyid="{{$reply->id}}" data-userid = "{{$user_id}}" class="css-checkbox likeforumreply" {{ isset($likedata[0])?'checked':'' }}>
								<label for="checkboxG1-reply-{{$reply->id}}" class="css-label"><span class="replies-like-count">{{ $replyLikessCount }}</span></label>
							@else
								<input type="checkbox" name="checkboxG1" id="guest-reply-{{$reply->id}}" class="css-checkbox">
								<label for="guest-reply-{{$reply->id}}" class="css-label"><span class="replies-like-count">{{ $replyLikessCount }}</span></label>
							@endif

								<div class="rpost-comments">
									<a href="{{ url('api/get-forum-post-reply-comment?reply_id='.$reply->id) }}" title=""><img src="{{url('forums-data/images/comment-icon.png')}}" alt=""><span class="replies-comment-count">{{ $replyCommentsCount }}</span></a>
								</div>
							</div>
							<div class="post-time time-bottom">
								<span class="date"><img src="{{url('forums-data/images/date-icon.png')}}" alt="">{{ $reply->updated_at->format('D jS') }}</span>
								<span class="time"><img src="{{url('forums-data/images/time-icon.png')}}" alt="">{{ $reply->updated_at->format('h:i A') }}</span>
							</div>
						</div>
					</div>
				</div>
			@endforeach

		</div>
		@if($replies->count() >= 5)
			<div class="load-more-btn-cont text-center">
				<button type="button" class="load-more-forumreply loading-btn" data-forumpostid = "{{$checkpost->id}}">View More</button>
			</div>
		@endif

	</div>
	<div class="userid" data-id="{{$user_id}}"></div>

@endsection

<script type="text/javascript">
	
	window.onload = function() {
		
		//$('.morecontent').hide();
	}


</script>

