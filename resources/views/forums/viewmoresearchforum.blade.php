	@foreach($posts as $data)
		<div class="f-single-post" id="forumpost_{{$data->id}}">
			<div class="p-user">
			<?php 
				$user = $data->user;
					if(isset($data->forumPostLikesCount[0]))
						$likeCount = $data->forumPostLikesCount[0]->forumlikescount;
					else
						$likeCount = 0;
					if(isset($data->replyCount[0]))
						$replyCount = $data->replyCount[0]->replyCount;
					else
						$replyCount = 0;
			$userid = $user->id;
			// $profileimage = !empty($data->user->picture) ? $user->picture : '/images/user-thumb.jpg';
			$userdata = $data->user;
			if(Auth::check())
			$likedata = \App\ForumLikes::where(['owner_id' => Auth::User()->id, 'post_id' => $data->id])->get(); 
			?>
			<a href="{{url("profile/$userid")}}" title="User Profile">
				<span class="user-thumb" style="background: url('<?php echo userImage($userdata) ?>');"></span>
			</a>
				<span class="p-date"><i class="flaticon-days"></i> {{$data->updated_at->format('d M Y')}}</span>
				<span class="p-time"><i class="flaticon-time"></i> {{$data->updated_at->format('h:i A').' (UTC)'}}</span>
			</div>

			<div class="f-post-title">
			<a href="{{url("profile/$userid")}}" title="User Profile">
				{{$data->user->first_name." ".$data->user->last_name}}
			</a>

			<?php $rightClass = 'right'; ?>
			@if(Auth::Check() && Auth::User()->id == $userid)
				<?php $rightClass = ''; ?>
			@endif

			<?php $editClass = !empty($replyCount) ? 'editclass' : ''; ?>

			@if(Auth::Check() && Auth::User()->id != $userid)
				<?php 
					$rightClass = 'right'; 
					$editClass = '';
				?>
			@endif

			<div class="p-likes custm_p_likes <?= $rightClass ?> <?= $editClass ?>">
				<div class="like-cont">
					<!-- <input type="checkbox" name="" id="checkbox{{$data->id}}" class="css-checkbox likeforumpost" data-forumpostid="{{$data->id}}" {{ isset($likedata[0])?'checked':'' }}/>	
					<label for="checkbox{{$data->id}}" class="css-label"></label> -->
					@if(Auth::check())
						<input type="checkbox" name="" title="Like Post" id="checkbox_forumpost_{{$data->id}}" class="css-checkbox likeforumpost" data-forumpostid="{{$data->id}}" {{ isset($likedata[0])?'checked':'' }}/>	
						<label for="checkbox_forumpost_{{$data->id}}" title="Like Post" class="css-label"></label>
					@else
					<input type="checkbox" disabled="disabled" name="" id="guest" class="css-checkbox"/>
					  <label for="guest" data-toggle="modal" data-target="#LoginPop" class="css-label"></label>
					@endif
				</div>
				<span class="plike-count">{{$likeCount}}</span>
			</div>

			@if(Auth::check())
			@if($data->user->id == Auth::user()->id)
				<div class="fp-action">
				@if($replyCount == 0)
					<button class="editforumpost" value="{{$data->id}}" title="Edit" ><i class="flaticon-pencil" ></i></button>
				@endif
					<button class="del-confirm-forum" data-forumtype = "post" value="{{$data->id}}" data-breadcrum = "{{$breadcrum}}"><i class="flaticon-garbage" ></i></button>
				</div>
			@endif
			@endif
			</div>

			<p> <b>{{$data->forum_category_breadcrum}}</b></p>
			<p class='readmore'><?php echo forumPostContents(nl2br($data->title)) ?> </p>

			<div class="fp-btns text-right">
				<span class="reply-count">Replies ({{$replyCount}})</span>
				<a href="{{ forumReplyUrl($data) }}" title="" class="btn btn-primary"><span class="glyphicon glyphicon-share-alt"></span>Reply</a>
			</div>

		</div><!--/single post-->
	@endforeach
<script type="text/javascript">
	$(document).ready(function() {
		loadOrgionalImogi();
	});
</script>