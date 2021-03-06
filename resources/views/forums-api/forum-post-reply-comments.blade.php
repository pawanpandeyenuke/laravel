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
		// $pic = !empty($replyUser->picture) ? $replyUser->picture : 'images/user-thumb.jpg';
		$likedata = \App\ForumReplyLikes::where(['owner_id' => $user_id, 'reply_id' => $reply->id])->get();
	?>
	<div class="forum-post-list">
		<div class="single-post">
			<div class="post-header">
				<span class="u-img" style="background: url('<?php echo userImage($replyUser) ?>');"></span>
				<span class="title">{{ $replyUser->first_name.' '.$replyUser->last_name }}</span>
				<div class="post-time">
					<span class="date"><img src="{{url('forums-data/images/date-icon.png')}}" alt="Date Icon">{{ $reply->updated_at->format('d M Y') }}</span>
					<span class="time"><img src="{{url('forums-data/images/time-icon.png')}}" alt="Time Icon">{{ $reply->updated_at->format('h:i A').' (UTC)' }}</span>
				</div>
				<span class="loc">
					<img src="{{url('forums-data/images/location.png')}}" alt="Location Icon">{{ !empty($replyLocati)?$replyLocation:'N/A' }}
				</span>
				<div class="breadcrumb-cont">
					{{ $breadcrumb }}
				</div>
			</div>

			<div class="post-data">
				<p class='readmore'><?php echo nl2br(forumPostContents($reply_data, '#', 135)) ?></p>
			</div>
			<div class="post-action clearfix">
				<div class="row-cont clearfix">
					<div class="like-cont">
					@if($user_id)
						<input type="checkbox" name="checkboxG1" id="checkboxG1-reply-commentspage-{{$reply->id}}" data-forumreplyid="{{$reply->id}}" data-userid="{{$user_id}}"class="css-checkbox likeforumreply" {{ isset($likedata[0])?'checked':'' }}>
						<label for="checkboxG1-reply-commentspage-{{$reply->id}}" class="css-label"><span class="replies-like-count">{{ $replyLikesCount }}</span></label>
					@else
						<input type="checkbox" name="checkboxG1" id="guest-reply-commentspage-{{$reply->id}}" class="css-checkbox" >
						<label for="guest-reply-commentspage-{{$reply->id}}" class="css-label"><span class="replies-like-count">{{ $replyLikesCount }}</span></label>
					@endif
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
					// $commentUserPic = !empty($commentUser->picture) ? $commentUser->picture : 'images/user-thumb.jpg';
				?>
				<div class="single-post">
					<div class="post-header">

						<span class="u-img" style="background: url('<?php echo userImage($commentUser) ?>');"></span>
						<span class="title">{{ $commentUser->first_name.' '.$commentUser->last_name }}</span>
						<span class="loc">
							<img src="{{url('forums-data/images/location.png')}}" alt="Location Icon">{{ !empty($commentLocation)?$commentLocation:'N/A' }}
						</span>
					</div>

					<div class="post-data no-bottom-padding">
						<p class='readmore'><?php echo nl2br(forumPostContents($replyComment, '#', 135)) ?></p>
					</div>
					<div class="post-action clearfix">
						<div class="time-comment-bottom text-right">
							<?php echo $comment->updated_at->format('d M Y').' '.$comment->updated_at->format('h:i A').' (UTC)' ?>
						</div>
					</div>
				</div>
			@endforeach

			<!-- <div class="view-more-cont">
				<button class="view-more" type="button">View More</button>
			</div> -->
		</div>


<?php /*
		@if($replyComments->count() >= 5)
			<div class="load-more-btn-cont text-center">
				<button type="button" class="btn btn-primary btn-smbtn-sm load-more-forumcommets loading-btn" data-forumreplyid = "{{$reply->id}}">View More</button>
			</div>
		@endif
*/ ?>
	</div>
@endsection