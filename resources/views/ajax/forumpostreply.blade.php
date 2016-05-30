							<div class="f-single-post" id="forumreply_{{$forumreply->id}}">
										<div class="p-user">
											<span class="user-thumb" style="background: url('{{$profileimage}}');"></span>
											<div class="p-likes ml">
											<div class="like-cont">
												<input type="checkbox" name="" id="checkbox_forumreply_{{$forumreply->id}}" class="css-checkbox likeforumreply" data-forumreplyid="{{$forumreply->id}}" />
												<label for="checkbox_forumreply_{{$forumreply->id}}" class="css-label"></label>
											</div>
											<span class="plike-count forumreplylike">0</span>
											<div class="p-likes ml">
												<a href="#" class="popupforumreply" data-replyid = "{{$forumreply->id}}">
													<i class="fa fa-comment" aria-hidden="true"></i> 
													<span class="plike-count" id="forumreplycomment_{{$forumreply->id}}">0</span>
												</a>
											</div>
										</div>
										</div>

										<div class="f-post-title">
											{{$name}}
											<div class="fp-meta">
												<span class="p-date"><i class="flaticon-days"></i> {{$forumreply->updated_at->format('d M Y')}}</span>
												<span class="p-time"><i class="flaticon-time"></i> {{$forumreply->updated_at->format('h:i A')}}</span>
											</div>
											<div class="fp-action">
											<button class='editforumreply' value='{{$forumreply->id}}' data-forumpostid = "{{$forumpostid}}"><i class='flaticon-pencil'></i></button>
											<button class='forumreplydelete' value='{{$forumreply->id}}' data-forumpostid = "{{$forumpostid}}"><i class='flaticon-garbage'></i></button>
											</div>
										</div>

										<p class="more">{{ $forumreply->reply }} </p>

									</div><!--/single post-->
									