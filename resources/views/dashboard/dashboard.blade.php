@extends('layouts.dashboard')
@section('title', 'Dashboard')
<style type="text/css"> 
	.dashboard-load {
	    background: none repeat scroll 0 0 #fbfbfb;
	    border: 1px solid #ddd;
	    border-radius: 4px;
	    cursor: pointer;
	    font-size: 20px;
	    font-weight: 500;
	    margin: 10px 0 0;
	    padding: 10px 0;
	    text-align: center;
	} 
</style>

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

				<div class="col-sm-6 mid-db-body">
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
												'class' => 'form-control status',
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
																	'class' => 'btn btn-primary btn-post'
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
								</div>
								
							</div>
						{!! Form::close() !!}
					</div>
				</div><!--/status tab-->

					<div class="post-list" id="postlist">
						@foreach($feeds as $data)		
							<div class="single-post" data-value="{{ $data['id'] }}" id="post_{{ $data['id'] }}">

								<div class="post-header" data-value="{{ $data['id'] }}" id="post_{{ $data['id'] }}">
									@if($data->user->id == Auth::User()->id)
									<button type="button" class="p-edit-btn edit-post" title="Edit" ><i class="fa fa-pencil"></i></button>

									<button type="button" class="p-del-btn post-delete" data-toggle="modal" data-target=".post-del-confrm"><span class="glyphicon glyphicon-remove"></span></button>
									@endif

									<div class="row">
										<div class="col-md-7">
											<a href="profile/{{$data->user->id}}" title="" class="user-thumb-link">
												
												<?php $user = $data->user; ?>

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
									<?php $argumentsMessage = nl2br($data['message']); ?>

									@if( $argumentsMessage )
										<p><?= $argumentsMessage ?></p>
									@endif

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

														$username = \App\User::where('id', $commentsData['commented_by'])->get(['first_name', 'last_name', 'picture'])->first();

														$userId = \App\User::where('id', $commentsData['commented_by'])->get(['id']);

														if(!empty($username)){

															$name = $username->first_name.' '.$username->last_name; 

															if($counter > $offset){ ?>
																<li data-value="{{ $commentsData['id'] }}" id="post_{{ $commentsData['id'] }}">
																<?php if($commentsData['commented_by']==Auth::User()->id){ ?>
																	<button type="button" class="p-edit-btn edit-comment" data-toggle="modal" title="Edit" data-target=".edit-comment-popup"><i class="fa fa-pencil"></i></button>	

																	<button type="button" class="p-del-btn comment-delete" ><span class="glyphicon glyphicon-remove"></span></button>
																<?php } ?>
															
																<span class="user-thumb" style="background: url('<?php echo userImage($username) ?>');"></span>
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

						<div class="modal fade edit-post-popup" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="EditPost">	</div>

					<div id="commentajax" style="display: none;">	</div>
					<div id="AllCommentNew" class="post-list popup-list-without-img" style="display: none;"></div>
					</div>

					@if($feeds->count() > 1)
			    	<div class="dashboard-load">
				    	<span class="glyphicon glyphicon-download"></span>
				    	<span class="loading-img" style="display: none"><img src="/images/fs-loading.gif" alt=""></span>
				    </div>
				    @endif
					<div class="shadow-box bottom-ad"><img src="images/bottom-ad.jpg" alt="" class="img-responsive"></div>
				</div>

			@include('panels.right')

			</div>
		</div>
	</div><!--/pagedata-->

<script type="text/javascript">
jQuery(function($){
	$(document).on("click","#status_up_btn",function(){
		$('#image-holder').empty();
		$('#fileUpload').val('');
		$('.badge').html('');
	});
	
	// Post status updates via ajax call.
	$("#postform").ajaxForm({
		beforeSubmit: function(){
			if($('#status_up_btn').is(':checked')){
				if($('.status').val()== ""){
					$('.status').focus();
					return false;
				}	
			}
			if($('#status_img_up').is(':checked')){
				if($('.status').val()== "" && $('#image-holder').is(':empty')){
					$('.status').focus();
					return false;
				}
			}
			$('.btn-post').prop('disabled',true);
		},
		success: function(response) { 
	 		var current = $("#postform");
			if(response)
			{
				$('#newsfeed').val('');
				$('#image-holder img').remove();
				$('#fileUpload').val('');
				$('.group-span-filestyle label .badge').html('');
				if(response != 'Post something to update.')
				{
					$('#postlist').first('.single-post').prepend(response);
					current.parents('.row').find('#newsfeed').text('');
					current.parents('.row').find('.emoji-wysiwyg-editor').text('');
					loadImg();
					var original =$('.single-post .post-data').first('p').html();		
			        var converted = emojione.toImage(original);
			        $('.single-post .post-data').first('p').html(converted);
				}
				jQuery('.btn-post').prop('disabled',false);
			}
		} 
    });
});
</script>
@endsection