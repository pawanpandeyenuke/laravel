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

											$likedata = \App\ForumReplyLikes::where(['owner_id' => Auth::User()->id, 'reply_id' => $forumreply->id])->get();
										?>
											<span class="user-thumb" style="background: url('{{$profileimage}}');"></span>
											<div class="p-likes ml">
											<div class="like-cont">
												<input type="checkbox" name="" id="checkbox_forumreply_{{$forumreply->id}}" class="css-checkbox likeforumreply" data-forumreplyid="{{$forumreply->id}}" {{ isset($likedata[0])?'checked':'' }}/>
												<label for="checkbox_forumreply_{{$forumreply->id}}" class="css-label"></label>
											</div>
											<span class="plike-count forumreplylike">{{$likeCount}}</span>
											<div class="p-likes ml">
												<a href="#" class="popupforumreply" data-replyid = "{{$forumreply->id}}">
													<i class="fa fa-comment" aria-hidden="true"></i> 
													<span class="plike-count" id="forumreplycomment_{{$forumreply->id}}">{{$commentCount}}</span>
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
											@if($userid == Auth::User()->id)
											<div class="fp-action">
											<button class='editforumreply' value='{{$forumreply->id}}'data-forumpostid = "{{$forumpostid}}"><i class='flaticon-pencil'></i></button>
											<button class='forumreplydelete' value='{{$forumreply->id}}' data-forumpostid = "{{$forumpostid}}"><i class='flaticon-garbage'></i></button>
											</div>
											@endif
										</div>
										<p class="more">{{ $forumreply->reply }} </p>
									</div><!--/single post-->								
								@endforeach