@extends('layouts.api')

@section('title', 'Forum Posts')

@section('content')
	<div class="forum-post-list">

		@foreach($posts as $post)
			<?php 
				$user = $post['user'];
				$likesCount = isset($post->forumPostLikesCount[0]) ? $post->forumPostLikesCount[0]['forumlikescount'] : 0;
				// $repliesCount = isset($post->replyCount[0]) ? $post->replyCount[0]['replyCount'] : 0;
				
				if( $user_id )
				{
					$spamids = \App\ReplySpams::where(['post_id' => $post->id, 'user_id' => $user_id])->select('reply_id')->pluck('reply_id');
					$repliesCount = DB::table('forums_reply')->where('post_id','=',$post->id)->whereNotIn('id', $spamids)->count();
				} else {
					$repliesCount = DB::table('forums_reply')->where('post_id','=',$post->id)->count();
				}

				$rawCountry = [$user->city, $user->state, $user->country];
				foreach ($rawCountry as $key => $value) {
					if($value == ''){
						unset($rawCountry[$key]);
					}
				}
				$location = implode(', ', $rawCountry);
				$postTitle = !empty($post->title) ? $post->title : '';

				$breadcrumb = !empty($post->forum_category_breadcrum) ? $post->forum_category_breadcrum : '';
				// $pic = !empty($user->picture) ? $user->picture : 'images/user-thumb.jpg';
				$likedata = \App\ForumLikes::where(['owner_id' => $user_id, 'post_id' => $post->id])->get(); 
			?>
			<div class="single-post" id="forumpost_{{$post->id}}">
				<div class="post-header">
				  	@if($user_id)
						<div class="dropdown reply-action">
							<button type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
								<img src="{{url('forums-data/images/dd-btn.png')}}" alt="">
							</button>
							<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
							@if($user_id == $user->id)
								@if($repliesCount == 0)
									<?php $title = base64_encode(nl2br($postTitle)); ?>
									<li><a href="{{ url("api/get-forum-post-details?post_id=$post->id&user_id=$user->id&post_data=$title") }}">Edit</a></li>
								@endif
									<li><a href="#" class="del-confirm-api" data-type="post" data-postid="{{$post->id}}" data-breadcrum = "{{$post->forum_category_breadcrum}}">Delete</a></li>
							@else
								<li><a href="#" class="spamModal" data-postid="{{$post->id}}">Report as spam</a></li>
							@endif
							</ul>
						</div>
				  	@endif
					<span class="u-img" style="background: url('<?php echo userImage($user) ?>');"></span>
					<span class="title">{{ $user->first_name.' '.$user->last_name }}</span>
					<div class="post-time">
						<span class="date"><img src="{{url('/forums-data/images/date-icon.png')}}" alt="">{{ $post->updated_at->format('d M Y') }}</span>
						<span class="time"><img src="{{url('/forums-data/images/time-icon.png')}}" alt="">{{ $post->updated_at->format('h:i A').' (UTC)' }}</span>
					</div>
					<span class="loc">
						<img src="{{url('/forums-data/images/location.png')}}" alt="">{{ !empty($location)?$location:'N/A' }}
					</span>
					<div class="breadcrumb-cont">
						<?= $breadcrumb ?>
					</div>
				</div>

				<div class="post-data">
					<p class='readmore'><?php echo nl2br(forumPostContents($postTitle, '#', 135)); ?></p>
				</div>
				<div class="post-action clearfix">
					<div class="row-cont clearfix">
						<div class="like-cont">
						@if($user_id)
							<input type="checkbox" name="checkboxG1" id="checkboxG1-post-{{$post->id}}" data-forumpostid="{{$post->id}}" data-userid="{{$user_id}}" class="css-checkbox api-likeforumpost" {{ isset($likedata[0])?'checked':'' }}>
							<label for="checkboxG1-post-{{$post->id}}" class="css-label"><span class="likescount">{{ $likesCount }}</span></label>
						@else
							<input type="checkbox" disabled="disabled" name="checkboxG1" id="checkboxG1-guest-post-{{$post->id}}" class="css-checkbox">
							<label for="checkboxG1-guest-post-{{$post->id}}" class="css-label"><span class="likescount">
							{{ $likesCount }}</span></label>
						@endif
						</div>
						<div class="btn-cont text-right">
							<span class="reply-count">
								<span class="repliescount">{{ $repliesCount }}</span>
								Replies
							</span>
							<a href="{{ url('api/get-forum-post-reply?post_id='.$post->id) }}" class="btn-reply">Reply</a>
						</div>
					</div>
				</div>
			</div>
		@endforeach
	</div>

	@if($totalRecords > 5)
		<div class="load-more-btn-cont text-center">
			<button type="button" class="load-more-forumpost loading-btn" data-breadcrum="{{$searchBreadcrumb}}" data-keyword="{{$keyword}}">View More</button>
		</div>
	@endif
<div class="userid" data-id="{{$user_id}}"></div>

@if($user_id)
<!-- Spam Modal -->
<div id="spamModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Report as spam</h4>
      </div>
      <div class="modal-body">
      	<p>By reporting, you will no longer see this post.</p>
        <p><b>Select reason: </b></p>
        <select name='reason' id='reason' class='form-control'>
        	<option value=''>---Select---</option>
        	<option>This post is spamming</option>
        	<option>This post is sexually explicit</option>
        	<option>This post is harassing me</option>
        </select>
        <input type="hidden" id="post_id">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-reply" id='submitSpam'>Submit</button>
      </div>
    </div>
  </div>
</div>

<script>
jQuery(function($){
	$(document).on('click', '.spamModal', function(e){
		e.preventDefault();
		$('#spamModal').modal({
			'keyboard': false,
			'backdrop': 'static',
			'show': true
		});
		$('#reason').val('');
		$('#post_id').val($(this).data('postid'));
	});

	$('#submitSpam').click(function(){
		var user_id = $('.userid').data('id');
		var post_id = $('#post_id').val();
		var reason = $('#reason').val();
		if(!reason){
			alert('Select some reason to proceed.');
		} else {
			$(this).prop('disabled', true);
			$.post('/ajax/spam/post', {user_id:user_id, post_id:post_id, reason:reason}, function(response){
				$('#spamModal').modal('hide');
				$('#submitSpam').prop('disabled', false);
				$('#forumpost_'+post_id).fadeOut(500);
			});
		}
	});
});
</script>

@endif
@endsection