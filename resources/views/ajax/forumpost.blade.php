<div class='f-single-post' id="forumpost_{{$forumpostid->id}}">
	<div class='p-user'>
		@if( Auth::check() )
			<a href="{{ url('profile/'.$user->id) }}" title = "User Profile">
		@else
			<a href="javascript:void(0)" data-toggle="modal" data-target="#LoginPop" >
		@endif
		<span class="user-thumb" style="background: url('<?php echo userImage($profileimage) ?>');"></span>
		</a>
		<span class='p-date'><i class='flaticon-days'></i> {{$forumpostid->updated_at->format('d M Y')}}</span>
		<span class='p-time'><i class='flaticon-time'></i> {{$forumpostid->updated_at->format('h:i A').' (UTC)'}}</span>
	</div>
	<div class='f-post-title'>
		@if( Auth::check() )
			<a href="{{ url('profile/'.$user->id) }}" title = "User Profile">
		@else
			<a href="javascript:void(0)" data-toggle="modal" data-target="#LoginPop" >
		@endif
		{{$name}} </a>

		<?php $rightClass = 'right'; ?>
		@if(Auth::User()->id == $user->id)
			<?php $rightClass = ''; ?>
		@endif

		<div class="p-likes custm_p_likes <?= $rightClass ?>">
			<div class="like-cont">
				<input type="checkbox" name="" id="checkbox_forumpost_{{$forumpostid->id}}" class="css-checkbox likeforumpost" data-forumpostid="{{$forumpostid->id}}" />
				<label for="checkbox_forumpost_{{$forumpostid->id}}" title="Like Post" class="css-label"></label>
			</div>
			<span class="plike-count" title="Likes">0</span>
		</div>

		<div class='fp-action'>
			<button class='editforumpost' value='{{$forumpostid->id}}'title="Edit Post"><i class='flaticon-pencil' data-breadcrum = "{{$breadcrum}}"></i></button>
			<button class='del-confirm-forum' value='{{$forumpostid->id}}' data-forumtype = "post" data-breadcrum = "{{$breadcrum}}" title="Delete Post"><i class='flaticon-garbage'></i></button>
		</div>
	</div>
	<p class='readmore'><?php echo forumPostContents(nl2br($forumpostid->title)); ?></p>
	<?php //$forumpostid = $forumpostid->id; ?>
	<div class='fp-btns text-right'>
		<span class='reply-count'>Replies(0)</span>
		<a href='{{ forumReplyUrl($forumpostid) }}' title="Jump to Reply Section" class='btn btn-primary'><span class='glyphicon glyphicon-share-alt'></span>Reply</a>
	</div>
</div>