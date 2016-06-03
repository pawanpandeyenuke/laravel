@extends('layouts.api')

@section('title', 'Reply Comments')

@section('content')
	<?php 
		$replyUser = $reply->user;
		// echo '<pre>';print_r($reply);die;

		$rawReplyCountry = [$replyUser->city, $replyUser->state, $replyUser->country];
		foreach ($rawReplyCountry as $key => $value) {
			if($value == ''){
				unset($rawReplyCountry[$key]);
			}
		}
		$replyLocation = implode(', ', $rawReplyCountry);

		$reply_data = !empty($reply->reply) ? $reply->reply : '';

		$rawBreadcrumbData = \App\ForumPost::where('id', \App\ForumReply::where('id', $reply->id)->value('post_id'))->value('forum_category_breadcrum');
		$breadcrumb = !empty($rawBreadcrumbData) ? $rawBreadcrumbData : '';

		$replyLikesCount = isset($reply->replyLikesCount[0]) ? $reply->replyLikesCount[0]['replyLikesCount'] : 0;

	?>
	<div class="forum-post-list">
		<div class="single-post">
			<div class="post-header">
				<span class="u-img" style="background: url('<?= url($replyUser->picture) ?>');"></span>
				<span class="title">{{ $replyUser->first_name.' '.$replyUser->last_name }}</span>
				<div class="post-time">
					<span class="date"><img src="{{url('forums-data/images/date-icon.png')}}" alt="">28-03-2016</span>
					<span class="time"><img src="{{url('forums-data/images/time-icon.png')}}" alt="">09:45 AM</span>
				</div>
				<span class="loc">
					<img src="{{url('forums-data/images/location.png')}}" alt="">{{ $replyLocation }}
				</span>
				<div class="breadcrumb-cont">
					{{ $breadcrumb }}
				</div>
			</div>

			<div class="post-data">
				<p>{{ $reply_data }}</p>
			</div>
			<div class="post-action clearfix">
				<div class="row-cont clearfix">
					<div class="like-cont">
						<input type="checkbox" name="checkboxG1" id="checkboxG1" class="css-checkbox">
						<label for="checkboxG1" class="css-label"><span class="replies-like-count">{{ $replyLikesCount }}</span></label>
					</div>
				</div>
			</div>
		</div>

		<div class="reply-post-cont">
			@foreach($replyComments as $comment)
				<?php
					$commentUser = $comment->user;
					// echo '<pre>';print_r($comment->reply_comment);die;

					$rawCommentCountry = [$commentUser->city, $commentUser->state, $commentUser->country];
					foreach ($rawCommentCountry as $key => $value) {
						if($value == ''){
							unset($rawCommentCountry[$key]);
						}
					}
					$commentLocation = implode(', ', $rawCommentCountry);

					$replyComment = !empty($comment->reply_comment) ? $comment->reply_comment : '';
				?>
				<div class="single-post">
					<div class="post-header">

						<span class="u-img" style="background: url('<?= url($commentUser->picture)?>');"></span>
						<span class="title">{{ $commentUser->first_name.' '.$commentUser->last_name }}</span>
						<span class="loc">
							<img src="{{url('forums-data/images/location.png')}}" alt="">{{ $commentLocation }}
						</span>
					</div>

					<div class="post-data no-bottom-padding">
						<p>{{ $replyComment }}</p>
					</div>
					<div class="post-action clearfix">
						<div class="time-comment-bottom text-right">
							<?php echo $comment->updated_at->format('D jS').' '.$comment->updated_at->format('h:i A') ?>
						</div>
					</div>
				</div>
			@endforeach

			<!-- <div class="view-more-cont">
				<button class="view-more" type="button">View More</button>
			</div> -->
		</div>

		@if($replyComments->count() >= 5)
			<div class="load-more-btn-cont text-center">
				<button type="button" class="btn btn-primary btn-smbtn-sm load-more-forumcommets" data-forumreplyid = "{{$reply->id}}">View More</button>
			</div>
		@endif

	</div>
@endsection