@foreach($forumPosts as $posts)
	<?php 
		// echo '<pre>';print_r($posts->count());die;

		$user = $posts->user;

		$likesCount = isset($posts->forumPostLikesCount[0]) ? $posts->forumPostLikesCount[0]['forumlikescount'] : 0;
		$repliesCount = isset($posts->replyCount[0]) ? $posts->replyCount[0]['replyCount'] : 0;
		// echo '<pre>';print_r($post->id);//die; 
		// $repliesCount = $post['replyCount'];
		
		$rawCountry = [$user->city, $user->state, $user->country];
		foreach ($rawCountry as $key => $value) {
			if($value == ''){
				unset($rawCountry[$key]);
			}
		}
		$location = implode(', ', $rawCountry);

		$postTitle = !empty($posts->title) ? $posts->title : '';

		$breadcrumb = !empty($posts->forum_category_breadcrum) ? $posts->forum_category_breadcrum : '';
		// $pic = !empty($user->picture) ? $user->picture : url('images/user-thumb.jpg');
		$likedata = \App\ForumLikes::where(['owner_id' => $user_id, 'post_id' => $posts->id])->get(); 
	?>

	<div class="single-post" id="forumpost_{{$posts->id}}">
		<div class="post-header">
		  	@if($user_id)
		  		@if($user_id == $user->id)
					<div class="dropdown reply-action">
						<button type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							<img src="{{url('forums-data/images/dd-btn.png')}}" alt="">
						</button>
						<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
						 @if($repliesCount == 0)
						 	<?php $title = base64_encode(nl2br($postTitle)); ?>
							<li><a href="{{ url("api/get-forum-post-details?post_id=$posts->id&user_id=$user->id&post_data=$title") }}">Edit</a></li>
						 @endif
							<li><a href="#" class="del-confirm-api" data-type="post" data-postid="{{$posts->id}}" data-breadcrum = "{{$posts->forum_category_breadcrum}}">Delete</a></li>
						</ul>
					</div>
			  	@endif
		  	@endif
			<span class="u-img" style="background: url('<?php echo userImage($user) ?>');"></span>
			<span class="title">{{ $user->first_name.' '.$user->first_name }}</span>
			<div class="post-time">
				<span class="date"><img src="{{url('/forums-data/images/date-icon.png')}}" alt="">{{ $posts->updated_at->format('d M Y') }}</span>
				<span class="time"><img src="{{url('/forums-data/images/time-icon.png')}}" alt="">{{ $posts->updated_at->format('h:i A') }}</span>
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
							<input type="checkbox" name="checkboxG1" id="checkboxG1-post-{{$posts->id}}" data-forumpostid="{{$posts->id}}" data-userid="{{$user_id}}" class="css-checkbox api-likeforumpost" {{ isset($likedata[0])?'checked':'' }}>
							<label for="checkboxG1-post-{{$posts->id}}" class="css-label"><span class="likescount">{{ $likesCount }}</span></label>
						@else
							<input type="checkbox" name="checkboxG1" id="checkboxG1-guest-post-{{$posts->id}}" class="css-checkbox">
							<label for="checkboxG1-guest-post-{{$posts->id}}" class="css-label"><span class="likescount">
							{{ $likesCount }}</span></label>
						@endif
				</div>
				<div class="btn-cont text-right">
					<span class="reply-count">
						<span class="repliescount">{{ $repliesCount }}</span>
						Replies
					</span>
					<a href="{{ url('api/get-forum-post-reply?post_id='.$posts->id) }}" class="btn-reply">Reply</a>
				</div>
			</div>
		</div>
	</div>
@endforeach

<script type="text/javascript">
loadOrgionalImogi();
</script>