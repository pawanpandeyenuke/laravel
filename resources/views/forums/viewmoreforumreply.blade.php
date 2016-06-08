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
											$profileimage = !empty($user->picture) ? $user->picture : '/images/user-thumb.jpg';
											$name = $user->first_name." ".$user->last_name;
											if(Auth::check())
											$likedata = \App\ForumReplyLikes::where(['owner_id' => Auth::User()->id, 'reply_id' => $forumreply->id])->get();
											else
											 $likedata = "";
										?>
										<a href = "{{url("profile/$userid")}}" title = "User Profile">
											<span class="user-thumb" style="background: url('{{$profileimage}}');"></span>
										</a>
											<div class="p-likes ml">
											<div class="like-cont">
												<input type="checkbox" name="" id="checkbox_forumreply_{{$forumreply->id}}" class="css-checkbox likeforumreply" data-forumreplyid="{{$forumreply->id}}" {{ isset($likedata[0])?'checked':'' }}/>
												<label for="checkbox_forumreply_{{$forumreply->id}}" title="Like Reply" class="css-label"></label>
											</div>
											<span class="plike-count forumreplylike" title="Likes">{{$likeCount}}</span>
											<div class="p-likes ml">
												<a href="#" class="popupforumreply" title="Open Comments" data-replyid = "{{$forumreply->id}}">
													<i class="fa fa-comment" aria-hidden="true"></i> 
													<span class="plike-count" id="forumreplycomment_{{$forumreply->id}}" title="Comments">{{$commentCount}}</span>
												</a>
											</div>
										</div>
										</div>

										<div class="f-post-title">
											<a href = "{{url("profile/$userid")}}" title = "User Profile">{{$name}}</a>
											<div class="fp-meta">
												<span class="p-date"><i class="flaticon-days"></i> {{$forumreply->updated_at->format('d M Y')}}</span>
												<span class="p-time"><i class="flaticon-time"></i> {{$forumreply->updated_at->format('h:i A')}}</span>
											</div>
											@if(Auth::check())
											@if($userid == Auth::User()->id)
											<div class="fp-action">
											<button class='editforumreply' title="Edit Reply" value='{{$forumreply->id}}'data-forumpostid = "{{$forumpostid}}"><i class='flaticon-pencil'></i></button>
											<button class='forumreplydelete' value='{{$forumreply->id}}' data-forumpostid = "{{$forumpostid}}" title="Delete Reply"><i class='flaticon-garbage'></i></button>
											</div>
											@endif
											@endif
										</div>
										<p class="more3"><?php echo nl2br($forumreply->reply); ?></p>
									</div><!--/single post-->								
								@endforeach

<script type="text/javascript">
// More Less Text



	$(document).ready(function() {
		 loadOrgionalImogi();
	  var showChar = 300;
	  var ellipsestext = "...";
	  var moretext = "more";
	  var lesstext = "less";
	  $('.more3').each(function() {
	      var content = $(this).html();

	      if(content.length > showChar) {

	          var c = content.substr(0, showChar);
	          var h = content.substr(showChar-1, content.length - showChar);

	          var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

	          $(this).html(html);
	      }

	  });
	  // 		$(document).on('click','.morelink3',function(){
	  //     if($(this).hasClass("less")) {
	  //         $(this).removeClass("less");
	  //         $(this).html(moretext);
	  //     } else {
	  //         $(this).addClass("less");
	  //         $(this).html(lesstext);
	  //     } 
	  //     $(this).parent().prev().toggle();
	  //     $(this).prev().toggle();
	  //     return false;
	  // });
	});

</script>
