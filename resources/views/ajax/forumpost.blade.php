<div class='f-single-post' id="forumpost_{{$forumpostid->id}}">
	<div class='p-user'>
		<a href="{{url("profile/$user->id")}}" title='User Profile'>
		<span class="user-thumb" style="background: url('{{$profileimage}}');"></span>
		</a>
		<span class='p-date'><i class='flaticon-days'></i> {{$forumpostid->updated_at->format('d M Y')}}</span>
		<span class='p-time'><i class='flaticon-time'></i> {{$forumpostid->updated_at->format('h:i A')}}</span>
		
		<div class="p-likes">
			<div class="like-cont">
				<input type="checkbox" name="" id="checkbox_forumpost_{{$forumpostid->id}}" class="css-checkbox likeforumpost" data-forumpostid="{{$forumpostid->id}}" />
				<label for="checkbox_forumpost_{{$forumpostid->id}}" title="Like Post" class="css-label"></label>
			</div>
			<span class="plike-count" title="Likes">0</span>
		</div>
	</div>
	<div class='f-post-title'>
	<a href="{{url("profile/$user->id")}}" title='User Profile'>
		{{$name}} </a>
		<div class='fp-action'>
			<button class='editforumpost' value='{{$forumpostid->id}}'title="Edit Post"><i class='flaticon-pencil' data-breadcrum = "{{$breadcrum}}"></i></button>
			<button class='del-confirm-forum' value='{{$forumpostid->id}}' data-forumtype = "post" data-breadcrum = "{{$breadcrum}}" title="Delete Post"><i class='flaticon-garbage'></i></button>
		</div>
	</div>
	<p><?php echo forumPostContents(nl2br($forumpostid->title)); ?></p>
	<?php $forumpostid = $forumpostid->id; ?>
	<div class='fp-btns text-right'>
		<span class='reply-count'>Replies(0)</span>
		<a href='{{url("forum-post-reply/$forumpostid")}}' title="Jump to Reply Section" class='btn btn-primary'><span class='glyphicon glyphicon-share-alt'></span>Reply</a>
	</div>
</div>