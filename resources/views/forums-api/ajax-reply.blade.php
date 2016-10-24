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

		$likedata = \App\ForumReplyLikes::where(['owner_id' => $user_id, 'reply_id' => $reply->id])->get();
	?>
	<div class="single-post" id="forumreply_{{$reply->id}}">
		<div class="post-header">
		  	@if($user_id)
					<div class="dropdown reply-action">
						<button type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							<img src="{{url('forums-data/images/dd-btn.png')}}" alt="Dropdown">
						</button>
						<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
							@if($user_id == $reply->owner_id)
								<?php $title = base64_encode(nl2br($reply_data)); ?>
								<li><a href="{{ url('api/get-forum-reply-details?reply_id='.$reply->id.'&user_id='.$replyUser->id.'&reply_data='.$title) }}">Edit</a></li>
								<li><a href="#" class="del-confirm-api" data-type="reply" data-forumpostid="{{$forumpostid}}" data-forumreplyid = "{{$reply->id}}">Delete</a></li>
							@else
								<li><a href="#" class="spamModal" data-replyid="{{$reply->id}}">Report as spam</a></li>
							@endif
						</ul>
					</div>
		  	@endif
		  	
			<span class="u-img" style="background: url('<?php echo userImage($replyUser) ?>');"></span>
			<span class="title">{{ $replyUser->first_name.' '.$replyUser->last_name }}</span>
			<span class="loc">
				<img src="{{url('forums-data/images/location.png')}}" alt="Location Icon">{{ !empty($replyLocation)?$replyLocation:'N/A' }}
			</span>
		</div>

		<div class="post-data">
			<p class='readmore'><?php echo nl2br(forumPostContents($reply_data, '#', 135)); ?></p>
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
						<a href="{{ url('api/get-forum-post-reply-comment?reply_id='.$reply->id) }}" title=""><img src="{{url('forums-data/images/comment-icon.png')}}" alt="Comment"><span class="replies-comment-count">{{ $replyCommentsCount }}</span></a>
					</div>
				</div>
				<div class="post-time">
					<span class="date"><img src="{{url('forums-data/images/date-icon.png')}}" alt="Date Icon">{{ $reply->updated_at->format('d M Y') }}</span>
					<span class="time"><img src="{{url('forums-data/images/time-icon.png')}}" alt="Time Icon">{{ $reply->updated_at->format('h:i A').' (UTC)' }}</span>
				</div>
			</div>
		</div>
	</div>
@endforeach

<script type="text/javascript">
loadOrgionalImogi();
</script>