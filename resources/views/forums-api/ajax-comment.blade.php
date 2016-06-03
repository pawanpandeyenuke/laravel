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