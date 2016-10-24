@extends('layouts.dashboard')

@include('panels.meta-data')
@section('title', 'Forums')
@section('content')
	<div class="page-data dashboard-body">
	        <div class="container">
	          <div class="row">
	          @if(Auth::check())
	            @include('panels.left')
	           @else
	            @include('panels.leftguest')
	           @endif
				<div class="col-sm-6">
				<div class="shadow-box page-center-data blue-bg no-margin-top">
					<div class="page-title">
						<i class="flaticon-user-profile"></i><h1>Forums</h1>
					</div>

					<div class="category-outer forum-list-cont">
						<div class="row">
							@foreach($forums as $data)
					        	<?php
						 
					   				$image = url("forums-data/forum_icons/".$data['img_url']);
					            ?>
								<div class="col-sm-4">
									<div class="cat-btn-outer">
										<a href="{{url("forums/$data->forum_slug")}}" class="cat-btn" title="">
											<img src="{{$image}}" alt="{{ $data->title }}"><br>
											<span>{{ $data->title }}</span>
										</a>
									</div>
								</div>
							@endforeach
						</div>
					</div>
				</div><!--/page center data-->
				<div class="shadow-box bottom-ad"><img src="{{url('images/bottom-ad.jpg')}}" alt="" class="img-responsive"></div>
			</div>
 				@include('panels.right')

            </div>
        </div>
    </div><!--/pagedata-->
 
@endsection