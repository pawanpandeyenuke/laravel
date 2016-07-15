@extends('layouts.dashboard')
@section('title', 'Group Chat - ')
@section('content')
	<div class="page-data dashboard-body">
	        <div class="container">
	            <div class="row">

	            @include('panels.left')

					<div class="col-sm-6">
						<div class="shadow-box page-center-data blue-bg no-margin-top">
							<div class="page-title">
								<i class="flaticon-balloon"></i>Chat Room
							</div>

							<div class="category-outer">
								<div class="row">

								    @foreach($parent_category as $data)

							        <?php 
							            $fieldsData = \App\Category::where('parent_id',$data->id)->get(); 

							            $nameexp = explode(' ', $data->title);
							            $catname = implode('-', $nameexp);
							            $name = strtolower($catname);

							            $image = url("/category_images/".$data['img_url']);
							            ?>

		                                @if(!($fieldsData->isEmpty()))
		                                <?php
		                                	if(\App\Category::where('id',$data->id)->value('selection') == "N")
		                                		$next_url = url("sub-cat-group/".$data->id);
		                                	else
		                                		$next_url = url("subgroup/".$data->id);
		                                 ?>
											<div class="col-sm-4">
												<div class="cat-btn-outer">
													<a href="{{$next_url}}" title="" class="cat-btn">
													<img src="{{$image}}"><br>
													{{ $data->title }}</a>
												</div>
											</div>
										@else
											<div class="col-sm-4">
												<div class="cat-btn-outer">
													<a href="{{url("groupchat/$data->id")}}" title="" class="cat-btn">
													<img src="{{$image}}"><br>
													{{ $data->title }}</a>
												</div>
											</div>
										@endif

									@endforeach

								</div>
							</div>

						</div><!--/page center data-->
						<div class="shadow-box bottom-ad"><img src="{{url("images/bottom-ad.jpg")}}" alt="" class="img-responsive"></div>
					</div>

 				@include('panels.right')

            </div>
        </div>
    </div><!--/pagedata-->
 
@endsection