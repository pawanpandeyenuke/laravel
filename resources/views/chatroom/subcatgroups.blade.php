@extends('layouts.dashboard')
@section('title', 'Group Chat - ')
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
						<img src="{{$icon_url}}" alt="" class="img-icon"><a href="{{url('group')}}">Chat Room</a> <?= $breadcrumb ?>					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="sub-cat-list">
								<ul>
								@foreach($subgroup as $data)
								<?php 
									$fieldsData = \App\Category::where('parent_id',$data->id)->get();
									$next_id = $parent_id.'-'.$data->id;
								?>
								@if(!($fieldsData->isEmpty()))
									<li>
										<a href="{{url("sub-cat-group/$next_id")}}" title="">{{$data->title}}</a>
									</li>
								@else
									<li>
										<a href="{{url("groupchat/$next_id")}}" title="">{{$data->title}}</a>
									</li>
								@endif
								@endforeach					
								</ul>
							</div>
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