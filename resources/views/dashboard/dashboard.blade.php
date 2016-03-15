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
								<div class="col-md-3 status-img-cont">
									<div class="status-img-outer" id="image-holder"></div>
								</div>
								<div class="col-md-9">
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
								<div class="col-md-3 status-btn-cont">
									<!-- <button type="button" class="btn btn-primary btn-post">Post</button> -->
									{!! Form::submit('Post', array(
											'id' => 'submit-btn', 
											'class' => 'btn btn-primary btn-post'
										))
									!!}
								</div>
								<div class="col-md-12">
									<div class="status-img-up">
										<div class="row">
											<div class="col-sm-3 text-center">
												<div class="form-group">
													<!-- <input type="file" class="filestyle" data-input="false" data-icon="false" data-buttonText="Browse"  data-buttonName="btn-primary" multiple="multiple"> -->
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
											<div class="col-sm-9">
												<div class="btn-list">
													<ul>
														<li>
															<!-- <button type="button" class="btn btn-primary">Upload</button> -->
															{!! Form::submit('Upload', array(
																	'id' => 'submit-btn', 
																	'class' => 'btn btn-primary'
																))
															!!}
														</li>
														<li>
															<button type="button" id="cancel-btn" class="btn btn-gray">Cancel</button>
														</li>
													</ul>
												</div>
											</div>
										</div>
										</div>
									<!-- <div class="status-img-up">
										<div class="form-group">
											<input type="file" class="filestyle" data-input="false" data-iconName="glyphicon glyphicon-camera"  data-buttonName="btn-primary" multiple="multiple">
										</div>
									</div> -->
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
											<a href="profile/{{$data->user->id}}" title="" class="user-thumb-link">
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

													$likecountdata = App\Like::where(['feed_id' => $data->id])->get()->count();
													$commentscountdata = App\Comment::where(['feed_id' => $data->id])->get()->count();  
												?>
													<input type="checkbox" name="" id="checkbox{{$data['id']}}" class="css-checkbox like" {{ isset($likedata[0])?'checked':'' }}/>
													<label for="checkbox{{$data['id']}}" class="css-label">
														@if($likecountdata > 0)
															<span class="countspan">
														 		{{ $likecountdata }}
															</span>
															<span>Likes</span>			
														@else
															<span class="firstlike">Like</span>
														@endif
													</label>
												</div>
											</li>
											<li>
												<a class="popupajax" style="cursor:pointer">
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

<script type="text/javascript" src="/js/jquery-1.11.3.min.js"></script>


@endsection

