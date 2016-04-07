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