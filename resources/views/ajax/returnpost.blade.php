<div class="single-post" data-value="{{$postdata->id}}" id="post_{{$postdata->id}}">
	<div class="post-header" data-value="{{$postdata->id}}" id="post_{{$postdata->id}}">
		<button type="button" class="p-edit-btn edit-post" data-toggle="modal" title="Edit" data-target=".edit-post-popup"><i class="fa fa-pencil"></i></button>
		<button type="button" class="p-del-btn post-delete" data-toggle="modal" data-target=".post-del-confrm"><span class="glyphicon glyphicon-remove"></span></button>
		<div class="row">
			<div class="col-md-7">
				<a href="profile/{{$user->id}}" title="" class="user-thumb-link">
					<span class="small-thumb" style="background: url('/images/user-thumb.jpg');"></span>
					{{ $user->first_name.' '.$user->last_name }}
				</a>
			</div>
			<div class="col-md-5">
				<div class="post-time text-right">
					<ul>
						<li>
							<span class="icon flaticon-time">
								{{ $postdata->updated_at->format('h:i A') }}
							</span>
						</li>
						<li>
							<span class="icon flaticon-days">
								{{ $postdata->updated_at->format('D jS') }}
							</span>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="post-data">
		
		@if( !empty($postdata->message) )

			<p>{{$postdata->message}}</p>

		@endif

		@if( !empty($postdata->image) )
			<div class="post-img-cont">
				<img src="uploads/{{$postdata->image}}" class="post-img img-responsive">
			</div>
		@endif

	</div>
	<div class="post-footer">
		<div class="post-actions">
			<ul>
				<li>
					<div class="like-cont">
						<input type="checkbox" name="" id="checkbox{{$postdata->id}}" class="css-checkbox like"/>
						<label for="checkbox{{$postdata->id}}" class="css-label">
							<span class="countspan" id="page-{{$postdata->id}}"></span>
							<span class="firstlike">Like</span>
						</label>
					</div>
				</li>
				<li>
					<a class="{{ $popupclass }}" style="cursor:pointer">
						<span class="icon flaticon-interface-1"></span> 
						<span class="commentcount">Comment</span>
					</a>
				</li>
			</ul>
		</div>
		<div class="post-comment-cont">
			<div class="post-comment" data-value="{{$postdata->id}}" id="post_{{$postdata->id}}">
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
				<ul id="pagecomment-{{$postdata->id}}">

				</ul>
			</div><!--/comments list-->
		</div>
	</div>
</div>