@extends('layouts.dashboard')
<?php //print_r($forums);die; ?>
@section('content')
	<div class="page-data dashboard-body">
	        <div class="container">
	          <div class="row">

	            @include('panels.left')

				<div class="col-sm-6">
				<div class="shadow-box page-center-data blue-bg no-margin-top">
					<div class="page-title">
						<i class="flaticon-user-profile"></i>Forums
					</div>

					<div class="category-outer forum-list-cont">
						<div class="row">
							@foreach($forums as $data)

							        <?php

							            $fieldsData = DB::table('forums')->where(['parent_id' => $data->id])->where(['status' => 'Active'])->select('title', 'id')->get(); 

							   // $count1 = DB::table('forums_post')->where('category_id',$data->id)->count();
							   // $ids1 = DB::table('forums')->where('parent_id',$data->id)->pluck('id');
							   // $count2 = DB::table('forums_post')->whereIn('category_id',$ids1)->count();
							   // $ids2 = DB::table('forums')->whereIn('parent_id',$ids1)->pluck('id');
							   // $count3 = DB::table('forums_post')->whereIn('category_id',$ids2)->count();
							   // $count = $count1 + $count2 + $count3;
							   $image = url("/forum_icons/".$data['img_url']);
							            ?>

		                       @if($fieldsData)
							<div class="col-sm-4">
								<div class="forum-btn">
									<a href="{{url("subforums/$data->id")}}" title="">
										<img src="{{$image}}" alt="">
										<span>{{ $data->title }}</span>
									</a>
								</div>
							</div>
							@else
							<div class="col-sm-4">
								<div class="forum-btn">
									<a href="{{url("viewforumposts/$data->id")}}" title="">
										<img src="{{$image}}" alt="">
										<span>{{ $data->title }}</span>
									</a>
								</div>
							</div>
							@endif
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