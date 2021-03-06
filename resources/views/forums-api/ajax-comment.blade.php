@foreach($replyComments as $comment)
	<?php
		// echo '<pre>';print_r($replyComments);die;
		$commentUser = $comment->user;

		$rawCommentCountry = [$commentUser->city, $commentUser->state, $commentUser->country];
		foreach ($rawCommentCountry as $key => $value) {
			if($value == ''){
				unset($rawCommentCountry[$key]);
			}
		}
		$commentLocation = implode(', ', $rawCommentCountry);
		// $pic = !empty($commentUser->picture) ? $commentUser->picture : url('images/user-thumb.jpg');
		$replyComment = !empty($comment->reply_comment) ? $comment->reply_comment : '';
	?>
	<div class="single-post">
		<div class="post-header">

			<span class="u-img" style="background: url('<?php echo userImage($commentUser) ?>');"></span>
			<span class="title">{{ $commentUser->first_name.' '.$commentUser->last_name }}</span>
			<span class="loc">
				<img src="{{url('forums-data/images/location.png')}}" alt="Location">{{ !empty($commentLocation)?$commentLocation:'N/A' }}
			</span>
		</div>

		<div class="post-data no-bottom-padding">
			<p><?php echo nl2br(forumPostContents($replyComment, '#', 135)); ?></p>
		</div>
		<div class="post-action clearfix">
			<div class="time-comment-bottom text-right">
				<?php echo $comment->updated_at->format('d M Y').' '.$comment->updated_at->format('h:i A').' (UTC)' ?>
			</div>
		</div>
	</div>
@endforeach

<script type="text/javascript">
loadOrgionalImogi();
</script>