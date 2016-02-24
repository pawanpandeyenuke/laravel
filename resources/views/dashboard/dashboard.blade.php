@extends('layouts.dashboard')

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
					  <ul class="nav nav-tabs" role="tablist">
					    <li role="presentation" class="active"><a href="#StatusUpdate" aria-controls="StatusUpdate" role="tab" data-toggle="tab">Status Update</a></li>
					    <li role="presentation"><a href="#AddPhotos" aria-controls="AddPhotos" role="tab" data-toggle="tab">Add Photos</a></li>
					  </ul>

					  <!-- Tab panes -->
					  <div class="tab-content">
					    <div role="tabpanel" class="tab-pane active statusupd-cont" id="StatusUpdate">
								{!! Form::open(array('url' => 'ajax/posts', 'id' => 'postform')) !!}
									<div class="row">
										<div class="col-md-9">
											{!! Form::text('message', null, array(
													'id' => 'newsfeed', 
													'class' => 'form-control round-textbox', 
													'placeholder' => 'What’s on your mind?'
												))
											!!}
										</div>
										<div class="col-md-3">
											{!! Form::submit('Post', array(
													'id' => 'submit-btn', 
													'class' => 'btn btn-primary btn-full'
												))
											!!}
										</div>
									</div>
					    </div>
					    <div role="tabpanel" class="tab-pane" id="AddPhotos">
								<div class="upload-photos">
									<div class="form-group">
										<!-- <input type="text" class="form-control" placeholder="Some text here.."> -->
										{!! Form::text('message', null, array(
												'id' => 'newsfeedimage', 
												'class' => 'form-control', 
												'placeholder' => 'Some text here..'
											))
										!!}
									</div>
									<div class="form-group">
										<div id="wrapper">
										   <!-- <input id="fileUpload" type="file"/><br /> -->
										   <div id="image-holder" class="img-cont clearfix fileinput"> </div>

										   <div class="fileinput fileinput-new" data-provides="fileinput">
											    <span class="btn btn-primary btn-file"><span>Choose file</span>
											    {!! Form::file('image', array(
											    	'id' => 'fileUpload',
											    )) !!}
											    </span>
											    <span class="fileinput-filename"></span><span class="fileinput-new">No file chosen</span>
											</div>
										</div>
					                </div>
									<div class="col-md-3">
										{!! Form::submit('Post', array(
												'id' => 'submit-btn', 
												'class' => 'btn btn-primary btn-full'
											))
										!!}
									</div>
								</div>	
							{!! Form::close() !!}
					    </div>
					  </div>
					</div><!--/status tab-->
					<div class="post-list">
						
						<div class="single-post">
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
							<div class="post-data">
								<p>If you live long enough, you'll make mistakes. But if you learn from them, you'll be a better person. It's how you handle adversity, not how it affects you. The main thing is never quit, never quit, never quit.</p>
								<div class="post-img-cont">
									<img src="images/post-img.jpg" class="post-img img-responsive">
								</div>
							</div><!--/post data-->
							<div class="post-footer">
								<div class="post-actions">
									<ul>
										<li><a href="#" title=""><span class="icon flaticon-web"></span> 55 <span>Likes</span></a></li>
										<li><span class="icon flaticon-interface-1"></span> 20 <span>Comments</span></li>
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