@extends('layouts.dashboard')

@include('panels.meta-data')
@section('title', 'Group Chat')
@section('content')

	<div class="page-data dashboard-body">
	        <div class="container">
	            <div class="row">

	            @include('panels.left')
<?php
	$icon_url = url('category_images/'.$icon_url);
?>
	            <div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="page-title">
						<img src="{{$icon_url}}" alt="<?= $breadcrumb ?>" class="img-icon">
							<h1>
								<a href="{{url('group')}}">Chat Room</a> 
									<span><?= $breadcrumb ?></span>
							</h1>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="sub-cat-list">
								<ul>
								@foreach($subgroup as $data)
								<?php 
									$next_id = $parent_slug.$data->category_slug;
								?>
									<li>
										<a href="{{url("chat/$next_id")}}" title="">{{$data->title}}</a>
									</li>
								@endforeach					
								</ul>
							</div>
						</div>
					</div>

				</div><!--/page center data-->
				<div class="shadow-box bottom-ad"><img src="{{url('images/bottom-ad.jpg')}}" alt="Shop By Temperature" class="img-responsive"></div>
			</div>
			@include('panels.right')

            </div>
        </div>
    </div><!--/pagedata-->
    @endsection