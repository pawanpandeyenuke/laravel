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
											<div class="p-likes ml">
												<a href="#" class="popupforumreply" title="Open Comments" data-replyid = "{{$forumreply->id}}">
													<i class="fa fa-comment" aria-hidden="true"></i> 
													<span class="plike-count" id="forumreplycomment_{{$forumreply->id}}" title="Comments">0</span>
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
											<div class="fp-action">
											<button class='editforumreply' value='{{$forumreply->id}}' data-forumpostid = "{{$forumpostid}}" title="Edit Reply"><i class='flaticon-pencil'></i></button>
											<button class='forumreplydelete' title="Delete Reply" value='{{$forumreply->id}}' data-forumpostid = "{{$forumpostid}}"><i class='flaticon-garbage'></i></button>
											</div>
										</div>

										<p class="more"><?php echo nl2br($forumreply->reply); ?></p>

									</div><!--/single post-->

<script type="text/javascript">
// More Less Text

	/*$(document).ready(function() {
	  var showChar = 100;
	  var ellipsestext = "...";
	   var moretext = "more";
	  var lesstext = "less";
	  $('.more1').each(function() {
	      var content = $(this).html();

	      if(content.length > showChar) {

	          var c = content.substr(0, showChar);
	          var h = content.substr(showChar-1, content.length - showChar);

	          var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink1">' + moretext + '</a></span>';

	          $(this).html(html);
	      }

	  });

	  		$(document).on('click','.morelink1',function(){
	      if($(this).hasClass("less")) {
	          $(this).removeClass("less");
	          $(this).html(moretext);
	      } else {
	          $(this).addClass("less");
	          $(this).html(lesstext);
	      } 
	      $(this).parent().prev().toggle();
	      $(this).prev().toggle();
	      return false;
	  });
	});
*/
</script>