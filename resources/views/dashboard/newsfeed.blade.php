	@foreach($feeds as $data)		
		<?php //echo '<pre>';print_r($data);die;  ?>					
		<div class="single-post" data-value="{{ $data->id }}" id="post_{{ $data->id }}">
			<div class="post-header">
				<div class="row">
					<div class="col-md-7">
						<a href="#" title="" class="user-thumb-link">
							<span class="small-thumb" style="background: url('images/user-thumb.jpg');"></span>
							{{ $data['user']['first_name'].' '.$data['user']['last_name'] }}
						</a>
					</div>
					<div class="col-md-5">
						<div class="post-time text-right">
							<ul>
								<li><span class="icon flaticon-time">{{ $data->updated_at->diffForHumans() }}</span></li>
								<!-- <li><span class="icon flaticon-days">{{ $data->updated_at }}</span></li> -->
							</ul>
						</div>
					</div>
				</div>
			</div><!--/post header-->
			<div class="post-data">
				<p>{{ $data->message }}</p>
				@if($data->image)
					<div class="post-img-cont">
						<img src="{{ url('uploads/'.$data->image) }}" class="post-img img-responsive">
					</div>
				@endif
			</div><!--/post data-->
			<div class="post-footer">
				<div class="post-actions">
					<ul>
						<li>
							<div class="like-cont">
								<?php 
									$likedata = DB::table('likes')->where(['user_id' => Auth::User()->id, 'feed_id' => $data->id])->get(); 
									// echo '<pre>';print_r($likedata[0]);die;
								?>
								<input type="checkbox" name="" id="checkbox{{$data->id}}" class="css-checkbox like" {{isset($likedata[0]) ? 'checked' : ''}}/>
								<label for="checkbox{{$data->id}}" class="css-label">
									@if(count($data['likesCount']) > 0)
										<span class="countspan" id="page-{{$data['id']}}">
											{{ count($data['likesCount']) }}
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
							<a href="#AllComment" class="{{$popupclass}}">
								<span class="icon flaticon-interface-1"></span> 
								@if(isset($data->commentsCount[0]))
									@if($data->commentsCount[0]->commentscount > 0)
										<span class="commentcount">{{ $data->commentsCount[0]->commentscount }} Comments</span>
									@else
										<span class="commentcount">Comment</span>
									@endif
								@else
									<span class="commentcount">Comment</span>
								@endif
							</a>
						</li>
					</ul>
				</div><!--/post actions-->
				<div class="post-comment-cont">
					<div class="post-comment">
						<div class="row">
							<div class="col-md-10">
								<textarea type="text" class="form-control comment-field" placeholder="Type here..."></textarea>
							</div>
							<div class="col-md-2">
								<button type="button" class="btn btn-primary btn-full comment">Post</button>
							</div>
						</div>
					</div><!--/post comment-->
					<div class="comments-list">
						<ul>
							<?php $counter = 1; ?>
							@foreach($data->comments as $commentsData)
							<?php 
								$username = DB::table('users')->where('id', $commentsData->commented_by)->get(['first_name', 'last_name']);
								if(!empty($username)){

								$name = $username[0]->first_name.' '.$username[0]->last_name; 

							if($counter < 4){ ?>
								<li>
									<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
									<a href="<?php echo 'profile/'.$commentsData->commented_by ?>" title="" class="user-link">{{$name}}</a>
									<div class="comment-text">{{$commentsData->comments}}</div>
								</li>
							<?php }$counter++; }?>
							@endforeach
						</ul>
					</div><!--/comments list-->
				</div>
			</div><!--/post-footer-->
		</div><!--/single post-->
	@endforeach