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
											{!! Form::text('message', null, array(
													'id' => 'newsfeed', 
													'class' => 'form-control',
													'data-emojiable' => true,
													'placeholder' => 'What’s on your mind?'
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
							<?php //echo '<pre>';print_r($data->id);die; ?>
							<div class="single-post" data-value="{{ $data->id }}" id="post_{{ $data->id }}">
								<div class="post-header">
									<div class="row">
										<div class="col-md-7">
											<a href="#" title="" class="user-thumb-link">
												<span class="small-thumb" style="background: url('uploads/1456394309_POST_XZY0484L(1.JPG');"></span>
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

<!-- 										<div class="like-cont">
											<input type="checkbox" name="checkboxG1" id="checkboxG1" class="css-checkbox" />
											<label for="checkboxG1" class="css-label">55 <span>Likes</span></label>
										</div> -->
												<a style="cursor:pointer" title="" class="like"><span class="icon flaticon-web"></span> 
													@if(count($data['likesCount']) > 0)
														<span>{{ count($data['likesCount']) }} Likes</span>
													@else
														<span>Like</span>
													@endif
												</a>
											</li>
											<li>
<a href="#AllComment" class="popup"><span class="icon flaticon-interface-1"></span> 20 <span>Comments</span></a>
												<span class="icon flaticon-interface-1"></span>
												@if(count($data['commentsCount']) > 0)
													<span>{{ count($data['commentsCount']) }} Comments</span>
												@else
													<span>Comment</span>
												@endif
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
												<li>
													<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
													<a href="#" title="" class="user-link">Navi Sappal</a>
													<div class="comment-text">Some comment text here...</div>
												</li>
												<li>
													<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
													<a href="#" title="" class="user-link">Navi Sappal</a>
													<div class="comment-text">Nice comment...</div>
												</li>
											</ul>
										</div><!--/comments list-->
									</div>
								</div><!--/post-footer-->
							</div><!--/single post-->
<div id="AllComment" class="post-list" style="display: none;">
						<div class="container">
							<div class="row">
								<div class="col-sm-8 pop-post-left-side">
									<div class="single-post">
										<div class="pop-post-header">
											<div class="post-header">
												<div class="row">
													<div class="col-md-7">
														<a href="#" title="" class="user-thumb-link">
															<span class="small-thumb" style="background: url('images/user-thumb.jpg');"></span>
															Ami Koehler
														</a>
													</div>
													<div class="col-md-5">
														<div class="post-time text-right">
															<ul>
																<li><span class="icon flaticon-time">4:15 PM</span></li>
																<li><span class="icon flaticon-days">7 WED</span></li>
															</ul>
														</div>
													</div>
												</div>
											</div><!--/post header-->
											<div class="pop-post-text clearfix">
												<p>If you live long enough, you'll make mistakes. But if you learn from them, you'll be a better person. It's how you handle adversity, not how it affects you. The main thing is never quit, never quit, never quit.</p>
											</div>
										</div>
										
										<div class="post-data pop-post-img">
											<img src="images/post-img-big.jpg" class="pop-img">
										</div><!--/post data-->
										<div class="post-footer pop-post-footer">
											<div class="post-actions">
												<ul>
													<li>
														<div class="like-cont">
															<input type="checkbox" name="checkboxG4" id="checkboxG4" class="css-checkbox" />
															<label for="checkboxG4" class="css-label">55 <span>Likes</span></label>
														</div>
													</li>
													<li><span class="icon flaticon-interface-1"></span> 25 <span>Comments</span></li>
												</ul>
											</div><!--/post actions-->
										</div><!--pop post footer-->
									</div><!--/single post-->
								</div>
								<div class="col-sm-4 pop-comment-side-outer">
									<div class="pop-comment-side">
										<div class="post-comment-cont">
											<div class="comments-list">
												<ul>
													<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">Yesterday</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Some comment text here...</div>
													</li>
													<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">2.45 PM</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Nice comment...</div>
													</li>
													<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">2.45 PM</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Nice comment...</div>
													</li>
													<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">2.45 PM</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Nice comment...</div>
													</li>
													<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">2.45 PM</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Nice comment...</div>
													</li>
													<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">2.45 PM</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Nice comment...</div>
													</li>
													<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">2.45 PM</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Nice comment...</div>
													</li>
													<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">2.45 PM</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Nice comment...</div>
													</li>
													<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">2.45 PM</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Nice comment...</div>
													</li>
													<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">2.45 PM</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Nice comment...</div>
													</li>
													<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">2.45 PM</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Nice comment...</div>
													</li>
													<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">2.45 PM</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Nice comment...</div>
													</li>
													<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">2.45 PM</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Nice comment...</div>
													</li>
												</ul>
											</div>
										</div>
									</div>

									<div class="pop-post-comment post-comment">
										<div class="emoji-field-cont">
											<textarea type="text" class="form-control comment-field" data-emojiable="true" placeholder="Type here..."></textarea>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
						@endforeach
					</div><!--/post list-->
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
@endsection