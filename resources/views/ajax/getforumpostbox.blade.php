<div class="single-post">
	<span style="display: none;" class="popup_reply_id">{{$reply_id}}</span>
	<div class="pop-post-header">
		<div class="post-header">
			<div class="row">
				<div class="col-md-7">
				<?php
					$replyid = $reply->id;
					if(isset($reply->replyLikesCount[0]))
						$likeCount = $reply->replyLikesCount[0]->replyLikesCount;
					else
						$likeCount = 0;
					if(isset($reply->replyCommentsCount[0]))
						$commentCount = $reply->replyCommentsCount[0]->replyCommentsCount;
					else
						$commentCount = 0;
					$user = $reply->user;
					$profileimage = !empty($user->picture) ? $user->picture : '/images/user-thumb.jpg';
					$name = $user->first_name." ".$user->last_name;
					$userid = $user->id;

					if(Auth::check())
					$likedata = \App\ForumReplyLikes::where(['owner_id' => Auth::User()->id, 'reply_id' => $replyid])->get();								
				 ?>
					<a href="{{url("profile/$userid")}}" title="" class="user-thumb-link">
						<span class="small-thumb" style="background: url('{{$profileimage}}');"></span>
						{{$name}}
					</a>
				</div>
				<div class="col-md-5">
					<div class="post-time text-right">
						<ul>
							<li><span class="icon flaticon-time">{{$reply->updated_at->format('h:i A').' (UTC)'}}</span></li>
							<li><span class="icon flaticon-days">{{$reply->updated_at->format('d M Y')}}</span></li>
						</ul>
					</div>
				</div>
			</div>
		</div><!--/post header-->
		<div class="pop-post-text clearfix">
			<p class='readmore'><?php echo nl2br(forumPostContents($reply->reply,'#')); ?></p>
		</div>
	</div>
	<div class="post-footer pop-post-footer">
		<div class="post-actions">
			<ul>
				<li>
					<div class="like-cont">
					@if(Auth::check())
						<input type="checkbox" name="checkboxG4" id="checkboxG4" class="css-checkbox likeforumreply" data-forumreplyid="{{$replyid}}" {{ isset($likedata[0])?'checked':'' }} />
						<label for="checkboxG4" class="css-label"><span class="forumreplylike">{{$likeCount}}</span> <span>Likes</span></label>
					@else
					   <input type="checkbox" disabled = "disabled" name="guest-popup" id="guest-popup" class="css-checkbox"/>
						<label for="guest-popup" data-toggle="modal" data-target="#LoginPop" class="css-label"><span class="forumreplylike">{{$likeCount}}</span> <span>Likes</span></label>
					@endif
					</div>
				</li>
				<li><span class="icon flaticon-interface-1"></span> <span class="forumreplycomment" id="forumreplycomment_popup_{{$replyid}}">{{$commentCount}}</span> <span>Comments</span></li>
			</ul>
		</div><!--/post actions-->
	</div><!--pop post footer-->
</div><!--/single post-->

<div class="post-comment-cont">
	<div class="comments-list">
		<ul class = "forumreplycommentlist">
			@if(!($replyComments->isEmpty()))
			@foreach($replyComments as $data)
				<?php 
					$commentuser = $data->user;
					$commentuserid = $commentuser->id;
					$profileimage = !empty($commentuser->picture) ? $commentuser->picture : '/images/user-thumb.jpg';
					$name = $commentuser->first_name." ".$commentuser->last_name;
				?>
				<li id="forum-li-comment-{{$data->id}}">

				<?php /*** @if(Auth::check()) 
				@if($commentuserid == Auth::User()->id) ***/ ?>
					<!-- <button type="button" class="p-del-btn del-forum-reply-comment" data-toggle="modal" data-target=".comment-del-confrm" value="{{$data->id}}" data-forumreplyid="{{$replyid}}"><span class="glyphicon glyphicon-remove"></span></button> -->
				<?php  	/*@endif 
						@endif*/ 
				?>

					<span class="user-thumb" style="background: url('{{$profileimage}}');"></span>
					<div class="comment-title-cont">
						<div class="row">
							<div class="col-sm-6">

								<a href="{{url("profile/$commentuserid")}}" title="" class="user-link">{{$name}}</a>

							</div>
							<div class="col-sm-6">
								<div class="comment-time text-right">{{$data->created_at->format('h:i A, d M Y').' (UTC)'}}</div>
							</div>
						</div>
					</div>
					<div class="comment-text"><?php echo nl2br($data->reply_comment); ?></div>
				</li>
			@endforeach
			@endif
		</ul>

		<div class="modal fade comment-del-confrm" id="modal" tabindex="-1" role="dialog" aria-labelledby="DeletePost"></div>
		
	</div>
</div>

<div class="pop-post-comment post-comment">
	@if(Auth::check())
	<div class="emoji-field-cont cmnt-field-cont">
		<textarea type="text" class="form-control comment-field reply-comment-text" data-emojiable="true" placeholder="Type here..."></textarea>
		<button type="button" class="btn-icon btn-cmnt replycomment" value="{{$replyid}}"><i class="flaticon-letter"></i></button>
	</div>
	@else
	<div class="text-right">
	  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#LoginPop">Comment</button>
	</div>
		<!-- <div class="text-center">Please <a data-toggle="modal" data-target="#LoginPop" href="#" title="">click here</a> for login, to create a post.</div> -->
	@endif
</div>

<script>
activateReadmore($('.pop-post-text .readmore:first'));
$('.popup-list-without-img .comments-list').niceScroll();
$(".comment-text").each(function() {
	var original = $(this).html();
	var converted = emojione.toImage(original);
	$(this).html(converted);
});

$('.pop-post-header .readmore').html( emojione.toImage($('.pop-post-header .readmore').html()) );

//Emoji Picker
$(function() {
  // Initializes and creates emoji set from sprite sheet
  window.emojiPicker = new EmojiPicker({
    emojiable_selector: '[data-emojiable=true]',
    assetsPath: '/lib/img/',
    popupButtonClasses: 'fa fa-smile-o'
  });
  window.emojiPicker.discover();
});
</script>