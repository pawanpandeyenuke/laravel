<?php $userid = $forumreply->user->id; ?>
<div class="f-single-post" id="forumreply_{{$forumreply->id}}">
	<div class="p-user">
		<a href = "{{url("profile/$userid")}}" title = "User Profile">
			<span class="user-thumb" style="background: url('{{$profileimage}}');"></span>
		</a>
		<div class="p-likes ml">
			<div class="like-cont">
				<input type="checkbox" name="" id="checkbox_forumreply_{{$forumreply->id}}" class="css-checkbox likeforumreply" data-forumreplyid="{{$forumreply->id}}" />
				<label for="checkbox_forumreply_{{$forumreply->id}}" title="Like Reply" class="css-label"></label>
			</div>
				<span class="plike-count forumreplylike" title="Likes">0</span>
		</div>
		<div class="p-likes ml">
			<a href="#" class="popupforumreply" title="Open Comments" data-replyid = "{{$forumreply->id}}">
				<i class="fa fa-comment" aria-hidden="true"></i> 
				<span class="plike-count" id="forumreplycomment_{{$forumreply->id}}" title="Comments">0</span>
			</a>
		</div>							
	</div>

	<div class="f-post-title">
		<a href = "{{url("profile/$userid")}}" title = "User Profile">{{$name}}</a>
			<div class="fp-meta">
				<span class="p-date"><i class="flaticon-days"></i> {{$forumreply->updated_at->format('d M Y')}}</span>
				<span class="p-time"><i class="flaticon-time"></i> {{$forumreply->updated_at->format('h:i A').' (UTC)'}}</span>
			</div>
			<div class="fp-action">
				<button class='editforumreply' value='{{$forumreply->id}}' data-forumpostid = "{{$forumpostid}}" title="Edit Reply"><i class='flaticon-pencil'></i></button>
				<button class='del-confirm-forum' data-forumtype = "reply" title="Delete Reply" value='{{$forumreply->id}}' data-forumpostid = "{{$forumpostid}}" ><i class='flaticon-garbage'></i></button>
			</div>
	</div>
	<p class="more readmore"><?php echo forumPostContents(nl2br($forumreply->reply)); ?></p>
</div><!--/single post-->