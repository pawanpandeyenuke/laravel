@extends('layouts.api')

@section('title', 'Forum Posts')

@section('content')
	<div class="forum-post-list">

		@foreach($posts as $post)
			<?php 
				$user = $post['user'];
				$likesCount = isset($post->forumPostLikesCount[0]) ? $post->forumPostLikesCount[0]['forumlikescount'] : 0;
				$repliesCount = isset($post->replyCount[0]) ? $post->replyCount[0]['replyCount'] : 0;
				// echo '<pre>';print_r($post->id);//die; 
				// $repliesCount = $post['replyCount'];
				
				$rawCountry = [$user->city, $user->state, $user->country];
				foreach ($rawCountry as $key => $value) {
					if($value == ''){
						unset($rawCountry[$key]);
					}
				}
				$location = implode(', ', $rawCountry);

				$postTitle = !empty($post->title) ? $post->title : '';

				$breadcrumb = !empty($post->forum_category_breadcrum) ? $post->forum_category_breadcrum : '';
			?>
			<div class="single-post">
				<div class="post-header">
					<span class="u-img" style="background: url('<?= url($user->picture) ?>');"></span>
					<span class="title">{{ $user->first_name }}</span>
					<div class="post-time">
						<span class="date"><img src="{{url('/forums-data/images/date-icon.png')}}" alt="">{{ $post->updated_at->format('D jS') }}</span>
						<span class="time"><img src="{{url('/forums-data/images/time-icon.png')}}" alt="">{{ $post->updated_at->format('h:i A') }}</span>
					</div>
					<span class="loc">
						<img src="{{url('/forums-data/images/location.png')}}" alt="">{{ $location }}
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
							<input type="checkbox" name="checkboxG1" id="checkboxG1-post-{{$post->id}}" data-forumpostid="{{$post->id}}" class="css-checkbox api-likeforumpost">
							<label for="checkboxG1-post-{{$post->id}}" class="css-label"><span class="likescount">{{ $likesCount }}</span></label>
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

		@if($posts->count() >= 5)
			<div class="load-more-btn-cont text-center">
				<button type="button" class="btn btn-primary btn-smbtn-sm load-more-forumpost" data-breadcrum = "{{$breadcrumb}}">View More</button>
			</div>
		@endif

@endsection
