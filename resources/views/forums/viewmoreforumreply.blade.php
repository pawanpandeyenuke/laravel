@foreach($reply as $forumreply)
<div class="f-single-post" id="forumreply_{{$forumreply->id}}">
		<div class="p-user">
		<?php 
			$user = $forumreply->user;
			if(isset($forumreply->replyLikesCount[0]))
				$likeCount = $forumreply->replyLikesCount[0]->replyLikesCount;
			else
				$likeCount = 0;
			if(isset($forumreply->replyCommentsCount[0]))
				$commentCount = $forumreply->replyCommentsCount[0]->replyCommentsCount;
			else
				$commentCount = 0;
		 	$userid = $user->id;
			// $profileimage = !empty($user->picture) ? $user->picture : '/images/user-thumb.jpg';
			$name = $user->first_name." ".$user->last_name;
			if(Auth::check()){
			$likedata = \App\ForumLikes::where(['owner_id' => Auth::User()->id, 'post_id' => $forumreply->id])->get(); 
			
			if($user->id == Auth::User()->id)
				$temp_class = "";
			else
				$temp_class = "without-action-btn";
			}else{
				$temp_class = "without-action-btn";
			}
		?>
		<a href = "{{url("profile/$userid")}}" title = "User Profile">
			<span class="user-thumb" style="background: url('<?php echo userImage($user) ?>');"></span>
		</a>
			<div class="p-likes ml">
			<div class="like-cont">
				@if(Auth::check())
				<input type="checkbox" name="" id="checkbox_forumreply_{{$forumreply->id}}" class="css-checkbox likeforumreply" data-forumreplyid="{{$forumreply->id}}" {{ isset($likedata[0])?'checked':'' }}/>
				<label for="checkbox_forumreply_{{$forumreply->id}}" title="Like Reply" class="css-label"></label>
				@else
				<input type="checkbox" disabled="disabled" id="guest-view-more-reply2" class="css-checkbox"/>
				<label for="guest-view-more-reply2" data-toggle="modal" data-target="#LoginPop" class="css-label"></label>
				@endif
			</div>
			<span class="plike-count forumreplylike" title="Likes">{{$likeCount}}</span>
			</div>
			<div class="p-likes ml">
				<a href="#" class="popupforumreply" title="Open Comments" data-replyid = "{{$forumreply->id}}">
					<i class="fa fa-comment" aria-hidden="true"></i> 
					<span class="plike-count" id="forumreplycomment_{{$forumreply->id}}" title="Comments">{{$commentCount}}</span>
				</a>
			</div>
		</div>

		<div class="f-post-title {{$temp_class}}">
			<a href = "{{url("profile/$userid")}}" title = "User Profile">{{$name}}</a>
			<div class="fp-meta">
				<span class="p-date"><i class="flaticon-days"></i> {{$forumreply->updated_at->format('d M Y')}}</span>
				<span class="p-time"><i class="flaticon-time"></i> {{$forumreply->updated_at->format('h:i A').' (UTC)'}}</span>
			</div>
			@if(Auth::check())
			@if($userid == Auth::User()->id)
			<div class="fp-action">
			<button class='editforumreply' title="Edit Reply" value='{{$forumreply->id}}'data-forumpostid = "{{$forumpostid}}"><i class='flaticon-pencil'></i></button>
			<button class='del-confirm-forum' data-forumtype = "reply" value='{{$forumreply->id}}' data-forumpostid = "{{$forumpostid}}" title="Delete Reply"><i class='flaticon-garbage'></i></button>
			</div>
			@endif
			@endif
		</div>
		<p class="more3 readmore"><?php echo nl2br(forumPostContents($forumreply->reply,'#')); ?></p>
	</div><!--/single post-->								
@endforeach

<script type="text/javascript">
	$(document).ready(function() {
		loadOrgionalImogi();
	});
</script>