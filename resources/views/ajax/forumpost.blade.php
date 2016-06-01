						<div class='f-single-post' id="forumpost_{{$forumpostid->id}}">
									<div class='p-user'>
										<span class="user-thumb" style="background: url('{{$profileimage}}');"></span>
										<span class='p-date'><i class='flaticon-days'></i> {{$forumpostid->updated_at->format('d M Y')}}</span>
										<span class='p-time'><i class='flaticon-time'></i> {{$forumpostid->updated_at->format('h:i A')}}</span>
										
										<div class="p-likes">
											<div class="like-cont">
												<input type="checkbox" name="" id="checkbox{{$forumpostid->id}}" class="css-checkbox likeforumpost" data-forumpostid="{{$forumpostid->id}}" />
												<label for="checkbox{{$forumpostid->id}}" class="css-label"></label>
											</div>
											<span class="plike-count">0</span>
										</div>

									</div>
									<div class='f-post-title'>
									<a href="{{url("profile/$user->id")}}" title=''>
										{{$name}}
										<a>
										<div class='fp-action'>
											<button class='editforumpost' value='{{$forumpostid->id}}'s><i class='flaticon-pencil' data-breadcrum = "{{$breadcrum}}"></i></button>
											<button class='forumpostdelete' value='{{$forumpostid->id}}' data-breadcrum = "{{$breadcrum}}"><i class='flaticon-garbage'></i></button>
										</div>
									</div>
									<p>{{$forumpostid->title}} </p>
									<?php $forumpostid = $forumpostid->id; ?>
									<div class='fp-btns text-right'>
										<span class='reply-count'>Replies(0)</span>
										<a href='{{url("forum-post-reply/$forumpostid")}}' title='' class='btn btn-primary'><span class='glyphicon glyphicon-share-alt'></span>Reply</a>
									</div>
								</div>