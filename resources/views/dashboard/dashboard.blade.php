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
								<form>
									<div class="row">
										<div class="col-md-12">
											<div class="emoji-field-cont form-group">
												<input type="text" class="form-control" data-emojiable="true" placeholder="What’s on your mind?">
											</div>
										</div>
										<div class="col-md-12">
											<div class="status-img-up">
												<div class="form-group">
													<input type="file" class="filestyle" data-iconName="glyphicon glyphicon-camera" data-input="false" id="fileUpload" data-buttonName="btn-primary" multiple="multiple">
												</div>
											</div>
											<div id="image-holder" class="img-cont clearfix fileinput"> </div>
										</div>
										<div class="col-md-12">
											<button type="button" class="btn btn-primary">Post</button>
										</div>
									</div>
								</form>
							</div>
					    </div><!--/status tab-->
					<div class="post-list">
						@foreach($feeds as $data)
							<?php //echo '<pre>';print_r($data['user']['first_name']);die; ?>
							<div class="single-post">
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
												<a href="#" title=""><span class="icon flaticon-web"></span> 
													@if(count($data['likesCount']) > 0)
														<span>{{ count($data['likesCount']) }} Likes</span>
													@else
														<span>Like</span>
													@endif
												</a>
											</li>
											<li>
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
													<button type="button" class="btn btn-primary btn-full">Post</button>
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