@extends('layouts.dashboard')
<?php  
// echo '<pre>';print_r($feeds);die('view');
?>
@section('content')

	@if (Session::has('error'))
		<div class="alert alert-danger">{!! Session::get('error') !!}</div>
	@endif
	@if (Session::has('success'))
		<div class="alert alert-success">{!! Session::get('success') !!}</div>
	@endif


	<div class="page-data dashboard-body">
		<div class="container">
			<div class="row">

			@include('panels.left')

				<div class="col-sm-6">
					<div class="status-tab">
							<!-- Nav tabs -->
							<ul class="list-inline">
								<li>
									<div class="status-action-outer">
										<input type="radio" name="status_up" id="status_up_btn" checked="checked" class="status-r-btn css-checkbox" />
										<label for="status_up_btn" class="css-label radGroup1">Status Update</label>
									</div>
								</li>
								<li>
									<div class="status-action-outer">
										<input type="radio" name="status_up" id="status_img_up" class="status-r-btn css-checkbox" />
										<label for="status_img_up" class="css-label radGroup1">Add Photos</label>
									</div>
								</li>
							</ul>

							<!--Status Data-->
							<div class="status-up-cont">
								{!! Form::open(array('url' => 'ajax/posts', 'id' => 'postform', 'files' => true)) !!}
									<div class="row">
										<div class="col-md-12">
											<div class="emoji-field-cont form-group">
												<!-- <input type="text" class="form-control" data-emojiable="true" placeholder="What’s on your mind?"> -->
											{!! Form::textarea('message', null, array(
													'id' => 'newsfeed', 
													'class' => 'form-control',
													'data-emojiable' => true,
													'placeholder' => 'What’s on your mind?',
													'data-emojiable' => 'true',
												)) !!}
											</div>
										</div>
										<div class="col-md-12">
											<div class="status-img-up">
												<div class="form-group">
													<!-- <input type="file" class="filestyle" data-iconName="glyphicon glyphicon-camera" data-input="false" id="fileUpload" data-buttonName="btn-primary" multiple="multiple"> -->
												    {!! Form::file('image', array(
												    	'id' => 'fileUpload',
												    	'class' => 'filestyle',
												    	'data-iconName' => 'glyphicon glyphicon-camera',
												    	'data-input' => 'false',
												    	'data-buttonName' => 'btn-primary',
												    	//'multiple' => 'multiple'
												    )) !!}
												</div>
											</div>
											<div id="image-holder" class="img-cont clearfix fileinput"> </div>
										</div>
										<div class="col-md-12">
											<!-- <button type="button" class="btn btn-primary">Post</button> -->
											{!! Form::submit('Post', array(
													'id' => 'submit-btn', 
													'class' => 'btn btn-primary'
												))
											!!}
										</div>
									</div>
								{!! Form::close() !!}
							</div>
					    </div><!--/status tab-->
					<div class="post-list" id="postlist">
						@foreach($feeds as $data)		
							<?php //echo '<pre>';print_r($data->updated_at->format('l jS'));die;  ?>					
							<div class="single-post" data-value="{{ $data['id'] }}" id="post_{{ $data['id'] }}">
								<div class="post-header">
									<div class="row">
										<div class="col-md-7">
											<a href="#" title="" class="user-thumb-link">
												<span class="small-thumb" style="background: url('images/user-thumb.jpg');"></span>
												{{ $data->user->first_name.' '.$data->user->last_name }}
											</a>
										</div>
										<div class="col-md-5">
											<div class="post-time text-right">
												<ul>
													<li><span class="icon flaticon-time">{{ $data->updated_at->format('h:i A') }}</span></li>
													<li><span class="icon flaticon-days">{{ $data->updated_at->format('D jS') }}</span></li>
												</ul>
											</div>
										</div>
									</div>
								</div><!--/post header-->
								<div class="post-data">
									<p>{{ $data['message'] }}</p>
									@if($data['image'])
										<div class="post-img-cont">
											<a href="{{ url('uploads/'.$data['image']) }}" class="popup">
											<img src="{{ url('uploads/'.$data['image']) }}" class="post-img img-responsive">
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
														$likedata = DB::table('likes')->where(['user_id' => Auth::User()->id, 'feed_id' => $data['id']])->get(); 
														// echo '<pre>';print_r($data);die;
													?>
													<input type="checkbox" name="" id="checkbox{{$data['id']}}" class="css-checkbox like" {{isset($likedata[0]) ? 'checked' : ''}}/>
													<label for="checkbox{{$data['id']}}" class="css-label">
														@if(isset($data['likes_count'][0]))
															@if($data['likes_count'][0]['likescount'] > 0)
																<span class="countspan">
																	{{ $data['likes_count'][0]['likescount'] }}
																</span>
																<span>Likes</span>			
															@endif
														@else
															<span class="firstlike">Like</span>
														@endif
													</label>
												</div>
											</li>
											<li>
												<a href="#AllComment" class="popup popupajax">
													<span class="icon flaticon-interface-1"></span> 
													@if(!empty($data['comments_count'][0]))
														@if($data['comments_count'][0]['commentscount'] > 0)
															<span class="commentcount">{{ $data['comments_count'][0]['commentscount'] }} Comments</span>
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
											<ul>
												@if(!empty($data['comments']))
													<?php $counter = 1; ?>
													@foreach($data['comments'] as $commentsData)
													<?php 
														$username = DB::table('users')->where('id', $commentsData['commented_by'])->get(['first_name', 'last_name']);

														// print_r($username);die;
														if(!empty($username)){

														$name = $username[0]->first_name.' '.$username[0]->last_name; 

													if($counter < 4){ ?>

														<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="<?php echo 'profile/'.$commentsData['commented_by'] ?>" title="" class="user-link">{{$name}}</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">2.45 PM</div>
																</div>
															</div>
														</div>
														<div class="comment-text">{{$commentsData['comments']}}</div>
													</li>
													<?php }$counter++; }?>
													@endforeach
												@endif
											</ul>
										</div><!--/comments list-->
									</div>
								</div><!--/post-footer-->
							</div><!--/single post-->
						@endforeach
					<div id="commentajax" style="display: none;">	</div>
					</div>
					<div class="shadow-box bottom-ad"><img src="images/bottom-ad.jpg" alt="" class="img-responsive"></div>
				</div>

			@include('panels.right')

			</div>
		</div>
	</div><!--/pagedata-->

<style>
	.file-error-message{
		display:none !important;
	}	
</style>

<script type="text/javascript" src="/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript">
/*var page = 1, ajax = 0, pages = 5;
$(window).on('scroll', function() {
   if($(window).scrollTop() + $(window).height() == $(document).height() && ajax == 0 && page<=pages)
   {
   		ajax = 1;
   		console.log(page);
        $.ajax({
       		'url' : 'ajax/loadposts',
       		'type' : 'post',
       		'data' : {'page' : page},
       		'success' : function(response){
       			page++;
       			if(page != pages) {
       				ajax = 0;
       			}
       		}
        });
   }
});*/

</script>
@endsection