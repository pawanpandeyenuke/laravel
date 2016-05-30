							@foreach($posts as $data)
								<div class="f-single-post" id="forumpost_{{$data->id}}">
									<div class="p-user">
									<?php 
										$user = $data->user;
											if(isset($data->forumPostLikesCount[0]))
												$likeCount = $data->forumPostLikesCount[0]->forumlikescount;
											else
												$likeCount = 0;
									$userid = $user->id;
									$profileimage = !empty($data->user->picture) ? $user->picture : '/images/user-thumb.jpg';

									$likedata = \App\ForumLikes::where(['owner_id' => Auth::User()->id, 'post_id' => $data->id])->get(); 
									?>
										<span class="user-thumb" style="background: url('{{$profileimage}}');"></span>
										<span class="p-date"><i class="flaticon-days"></i> {{$data->updated_at->format('d M Y')}}</span>
										<span class="p-time"><i class="flaticon-time"></i> {{$data->updated_at->format('h:i A')}}</span>

										<div class="p-likes">
											<div class="like-cont">
												<input type="checkbox" name="" id="checkbox{{$data->id}}" class="css-checkbox likeforumpost" data-forumpostid="{{$data->id}}" {{ isset($likedata[0])?'checked':'' }}/>	
												<label for="checkbox{{$data->id}}" class="css-label"></label>
											</div>
											<span class="plike-count">{{$likeCount}}</span>
										</div>

									</div>

									<div class="f-post-title">
									<a href="{{url("profile/$userid")}}" title="">
										{{$data->user->first_name." ".$data->user->last_name}}
									</a>
									@if($data->user->id == Auth::user()->id)
										<div class="fp-action">
											<button class="editforumpost" value="{{$data->id}}" title="Edit" ><i class="flaticon-pencil" ></i></button>
											<button class="forumpostdelete" value="{{$data->id}}" data-categoryid = "{{$categoryid}}"><i class="flaticon-garbage" ></i></button>
										</div>
									@endif
									</div>

									<p> {{$data->title}} </p>

									<div class="fp-btns text-right">
										<span class="reply-count">Replies (0)</span>
										<a href="#" title="" class="btn btn-primary"><span class="glyphicon glyphicon-share-alt"></span>Reply</a>
									</div>

								</div><!--/single post-->
							@endforeach
