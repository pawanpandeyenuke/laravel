
					<li id="forum-li-comment-{{$comment->id}}">
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
						<div class="comment-text replycomment"><?php echo nl2br($comment->reply_comment); ?></div>
					</li>