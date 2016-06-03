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

	?>
	<div class="forum-post-list">
		<div class="single-post">
			<div class="post-header">
				<span class="u-img" style="background: url('<?= url($user->picture) ?>');"></span>
				<span class="title">{{ $user->first_name.' '.$user->last_name }}</span>
				<div class="post-time">
					<span class="date"><img src="{{url('forums-data/images/date-icon.png')}}" alt="">{{ $checkpost->updated_at->format('D jS') }}</span>
					<span class="time"><img src="{{url('forums-data/images/time-icon.png')}}" alt="">{{ $checkpost->updated_at->format('h:i A') }}</span>
				</div>
				<span class="loc">
					<img src="images/location.png" alt="">{{$location}}
				</span>
				<div class="breadcrumb-cont">
					<?= $breadcrumb ?>
				</div>
			</div>

			<div class="post-data">
				<p>{{ $postTitle }}</p>
			</div>
			<div class="post-action clearfix">
				<div class="row-cont clearfix">
					<div class="like-cont">
						<input type="checkbox" name="checkboxG1" id="checkboxG1-post-{{$checkpost->id}}" data-forumpostid="{{$checkpost->id}}" class="css-checkbox api-likeforumpost">
						<label for="checkboxG1-post-{{$checkpost->id}}" class="css-label"><span class="likescount">{{ $likesCount }}</span></label>
					</div>
				</div>
			</div>
		</div>

		<div class="reply-post-cont">

			@foreach($replies as $reply)
				<?php 
					// echo '<pre>';print_r($reply->owner_id);die; 
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
				?>
				<div class="single-post">
					<div class="post-header">
					  	@if($user_id)
					  		@if($user_id == $reply->owner_id)
								<div class="dropdown reply-action">
									<button type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
										<img src="{{url('forums-data/images/dd-btn.png')}}" alt="">
									</button>
									<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
										<li><a href="#">Edit</a></li>
										<li><a href="#">Delete</a></li>
									</ul>
								</div>
						  	@endif
					  	@endif

						<span class="u-img" style="background: url('<?= url($replyUser->picture) ?>');"></span>
						<span class="title">{{ $replyUser->first_name.' '.$replyUser->last_name}}</span>
						<span class="loc">
							<img src="{{url('forums-data/images/location.png')}}" alt="">{{ $replyLocation }}
						</span>
					</div>

					<div class="post-data">
						<p>{{ $reply_data }}</p>
					</div>
					<div class="post-action clearfix">
						<div class="row-cont clearfix">
							<div class="like-cont like-bottom">
								<input type="checkbox" name="checkboxG1" id="checkboxG1-reply-{{$reply->id}}" data-forumreplyid="{{$reply->id}}" class="css-checkbox likeforumreply">
								<label for="checkboxG1-reply-{{$reply->id}}" class="css-label"><span class="replies-like-count">{{ $replyLikessCount }}</span></label>

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
				<button type="button" class="btn btn-primary btn-smbtn-sm load-more-forumreply" data-forumpostid = "{{$checkpost->id}}">View More</button>
			</div>
		@endif

	</div>
	<div class="userid" data-id="{{$user_id}}"></div>

@endsection