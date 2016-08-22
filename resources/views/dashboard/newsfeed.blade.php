
@foreach($feeds as $data)
		<div class="single-post" data-value="{{ $data['id'] }}" id="post_{{ $data['id'] }}">

			<div class="post-header" data-value="{{ $data['id'] }}" id="post_{{ $data['id'] }}">
				@if($data->user->id == Auth::User()->id)

				<button type="button" class="p-edit-btn edit-post" title="Edit" ><i class="fa fa-pencil"></i></button>

				<button type="button" class="p-del-btn post-delete" data-toggle="modal" data-target=".post-del-confrm"><span class="glyphicon glyphicon-remove"></span></button>
				@endif


				<div class="row">
					<div class="col-md-7">
						<?php $user = $data->user; ?>
						<a href="profile/{{$data->user->id}}" title="" class="user-thumb-link">
							<span class="small-thumb" style="background: url('<?php echo userImage($user) ?>');"></span>
							{{ $data->user->first_name.' '.$data->user->last_name }}
						</a>
					</div>
					<div class="col-md-5">
						<div class="post-time text-right">
							<ul>
								<li><span class="icon flaticon-days">{{ $data->updated_at->format('d M Y') }}</span></li>
								<li><span class="icon flaticon-time">{{ $data->updated_at->format('h:i A').' (UTC)' }}</span></li>
							</ul>
						</div>
					</div>
				</div>
			</div><!--/post header-->
			<div class="post-data">
				<p>{{ $data['message'] }}</p>

				@if($data['image'])
					<div class="post-img-cont">
						<a href="<?php echo dashboardImg( $data['image'], 'link' ) ?>" class="popup">
						<img src="<?php echo dashboardImg( $data['image'], 'thumb' ) ?>" class="post-img">
						</a>
					</div>
				@endif
			</div><!--/post data-->
			<div class="post-footer">
				<div class="post-actions">
					<ul>
						<li>
							<div class="like-cont">
							<?php 
								$likedata = \App\Like::where(['user_id' => Auth::User()->id, 'feed_id' => $data['id']])->get(); 

								$likecountdata = \App\Like::where(['feed_id' => $data->id])->get()->count();
								$commentscountdata = \App\Comment::where(['feed_id' => $data->id])->get()->count();  
							?>
								<input type="checkbox" name="" id="checkbox{{$data['id']}}" class="css-checkbox like" {{ isset($likedata[0])?'checked':'' }}/>
								<label for="checkbox{{$data['id']}}" class="css-label">
									@if($likecountdata > 0)
										<span class="countspan" id="page-{{$data['id']}}">
									 		{{ $likecountdata }}
										</span>
										<span>Likes</span>			
									@else
										<span class="countspan" id="page-{{$data['id']}}"></span>
										<span class="firstlike">Like</span>
									@endif
								</label>
							</div>
						</li>
						<li>
							<?php 
									if($data['message'] && empty($data['image'])){
										$popupclass = 'postpopupajax';
									}elseif($data['image'] && empty($data['message'])){
										$popupclass = 'popupajax';
									}else{
										$popupclass = 'popupajax';
									} 													
							?>
							<a class="{{$popupclass}}" style="cursor:pointer">
								<span class="icon flaticon-interface-1"></span> 
								@if($commentscountdata > 0)
									<span class="commentcount">{{ $commentscountdata }} Comments</span>
								@else
									<span class="commentcount">Comment</span>
								@endif
							</a>
						</li>
					</ul>
				</div><!--/post actions-->

				<div class="post-comment-cont">
					<div class="post-comment" data-value="{{ $data['id'] }}" id="post_{{ $data['id'] }}">

						<div class="row">
							<div class="col-md-10">
								<div class="emoji-field-cont cmnt-field-cont">
									<textarea data-emojiable="true" type="text" class="form-control comment-field" placeholder="Type here..."></textarea>
								</div>
							</div>
							<div class="col-md-2">
								<button type="button" class="btn btn-primary btn-full comment">Post</button>
							</div>
						</div>
					</div><!--/post comment-->
					<div class="comments-list">
		<ul id="pagecomment-{{$data->id}}" data-id="pagecomment-{{$data->id}}">

							@if(!empty($data['comments']))

								<?php 
									$counter = 1;
									$offset = count($data['comments']) - 3;
								foreach($data['comments'] as $commentsData){
								
									$username = \App\User::where('id', $commentsData['commented_by'])->get(['first_name', 'last_name', 'picture']);

									$userId = \App\User::where('id', $commentsData['commented_by'])->get(['id']);

									if(!empty($username)){

										$name = $username[0]->first_name.' '.$username[0]->last_name; 
										
										$user_picture = !empty($username[0]->picture) ? $username[0]->picture : 'images/user-thumb.jpg';

										if($counter > $offset){ ?>
											<li data-value="{{ $commentsData['id'] }}" id="post_{{ $commentsData['id'] }}">
				<?php if($commentsData['commented_by']==Auth::User()->id){ ?>
											<button type="button" class="p-edit-btn edit-comment" data-toggle="modal" title="Edit" data-target=".edit-comment-popup"><i class="fa fa-pencil"></i></button>	

											<button type="button" class="p-del-btn comment-delete" data-toggle="modal" data-target=".comment-del-confrm"><span class="glyphicon glyphicon-remove"></span></button>

								<?php } ?>
											<span class="user-thumb" style="background: url('<?php echo userImage($username[0]) ?>');"></span>
											<div class="comment-title-cont">
												<div class="row">
													<div class="col-sm-6">
														<a href="profile/{{$commentsData['commented_by']}}" title="" class="user-link">{{$name}}</a>
													</div>
													<div class="col-sm-6">
														<div class="text-right">
															<div class="date-time-list">
																<span><div class="comment-time text-right">{{ $commentsData->updated_at->format('d M Y') }}</div></span>
																<span><div class="comment-time text-right">{{ $commentsData->updated_at->format('h:i A').' (UTC)' }}</div></span>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="comment-text"><?= nl2br($commentsData['comments']) ?></div>
										</li>
										<?php 
										}
										$counter++; 
									}
								}
								?>
								
							@endif
						</ul>

					</div><!--/comments list-->
				</div>
			</div><!--/post-footer-->
		</div><!--/single post-->
	@endforeach

