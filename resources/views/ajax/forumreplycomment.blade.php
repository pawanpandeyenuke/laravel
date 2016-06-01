<?php //print_r($comment->id."  ".$replyid);die;
					?>
					<li id="forum-li-comment-{{$comment->id}}">
						<!-- <button type="button" class="p-del-btn del-forum-reply-comment" data-toggle="modal" data-target=".comment-del-confrm" value="{{$comment->id}}" data-forumreplyid="{{$replyid}}"><span class="glyphicon glyphicon-remove" ></span></button> -->
<!-- 
						<div class="modal fade comment-del-confrm" tabindex="-1" role="dialog" aria-labelledby="DeletePost">
						  <div class="modal-dialog modal-sm">
						    <div class="modal-content">
						    	<div class="modal-body text-center">
						        <h5>Are you sure to delete this post?</h5>
						      </div>
						      <div class="modal-footer text-center">
						        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						        <button type="button" class="btn btn-primary">Delete</button>
						      </div>
						    </div>
						  </div>
						</div> -->


						<span class="user-thumb" style="background: url('{{$profileimage}}');"></span>
						<div class="comment-title-cont">
							<div class="row">
								<div class="col-sm-6">
									<a href="{{url("profile/$userid")}}" title="" class="user-link">{{$name}}</a>
								</div>
								<div class="col-sm-6">
									<div class="comment-time text-right">{{$comment->created_at->format('h:i A,d M')}}</div>
								</div>
							</div>
						</div>
						<div class="comment-text replycomment">{{$comment->reply_comment}}</div>
					</li>
