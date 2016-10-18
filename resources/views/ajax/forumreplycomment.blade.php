<li id="forum-li-comment-{{$comment->id}}">
	<span class="user-thumb" style="background: url('<?php echo userImage($profileimage) ?>');"></span>
	<div class="comment-title-cont">
		<div class="row">
			<div class="col-sm-6">
			@if( Auth::check() )
				<a href="{{ url('profile/'.$userid) }}" title = "User Profile">
			@else
				<a href="javascript:void(0)" data-toggle="modal" data-target="#LoginPop" >
			@endif{{$name}}</a>
			</div>
			<div class="col-sm-6">
				<div class="comment-time text-right">{{$comment->created_at->format('h:i A,d M Y').' (UTC)'}}</div>
			</div>
		</div>
	</div>
	<div class="comment-text replycomment"><?php echo nl2br($comment->reply_comment); ?></div>
</li>