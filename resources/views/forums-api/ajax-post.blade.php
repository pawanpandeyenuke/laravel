
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
		$pic = !empty($user->picture) ? $user->picture : url('images/user-thumb.jpg');
	?>

	<div class="single-post">
		<div class="post-header">
			<span class="u-img" style="background: url('<?= url($pic) ?>');"></span>
			<span class="title">{{ $user->first_name }}</span>
			<div class="post-time">
				<span class="date"><img src="{{url('/forums-data/images/date-icon.png')}}" alt="">{{ $posts->updated_at->format('D jS') }}</span>
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
			<p>{{ $postTitle }}</p>
		</div>
		<div class="post-action clearfix">
			<div class="row-cont clearfix">
				<div class="like-cont">
					<input type="checkbox" name="checkboxG1" id="checkboxG1-post-{{$posts->id}}" data-forumpostid="{{$posts->id}}" class="css-checkbox api-likeforumpost">
					<label for="checkboxG1-post-{{$posts->id}}" class="css-label"><span class="likescount">{{ $likesCount }}</span></label>
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
 
