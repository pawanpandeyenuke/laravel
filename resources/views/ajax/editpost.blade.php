<?php 
	// echo '<pre>';print_r($posts->image);die;
?>

	<div class="status-up-cont">
		{!! Form::open(array('url' => 'ajax/posts', 'id' => 'postform', 'files' => true)) !!}
			<div class="row">
				@if($posts->image)
						<div style="max-height:200px;max-width:200px;">
							<img src="uploads/{{$posts->image}}" style="max-height:200px;max-width:200px;">
						</div>
						<div class="form-group">
						    {!! Form::file('image', array(
						    	'id' => 'fileUpload',
						    	'class' => 'filestyle',
						    	'data-iconName' => 'glyphicon glyphicon-camera',
						    	'data-input' => 'false',
						    	'data-buttonName' => 'btn-primary',
						    	//'multiple' => 'multiple'
						    )) !!}
						</div>
				@endif
				@if($posts->message)
					<div class="col-md-6">
						<div class="emoji-field-cont form-group">
							{!! Form::textarea('message', $posts->message, array(
									'id' => 'newsfeed', 
									'class' => 'form-control',
									'data-emojiable' => true,
									'placeholder' => 'What’s on your mind?',
									'data-emojiable' => 'true',
								)) !!}
						</div>
					</div>
				@endif

				<ul style="list-style: none">
					<li>
						{!! Form::submit('Save', array(
								'id' => 'submit-btn', 
								'class' => 'btn btn-primary btn-post'
							))
						!!}
					</li>
					<li>
						{!! Form::button('Cancel', array(
								'id' => 'submit-btn', 
								'class' => 'btn btn-primary btn-post'
							))
						!!}
					</li>
				</ul>
 
			</div>
		{!! Form::close() !!}
	</div>

<script type="text/javascript" src="/js/custom.js"></script>