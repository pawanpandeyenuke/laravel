@extends('layouts.dashboard')

@include('panels.meta-data')
@section('title', 'Group Chat')
@section('content')
	<div class="page-data dashboard-body">
	        <div class="container">
	            <div class="row">

	            @include('panels.left')

					<div class="col-sm-6">
						<div class="shadow-box page-center-data blue-bg no-margin-top">
							<div class="page-title">
								<i class="flaticon-balloon"></i><h1>Chat Room</h1>
							</div>

							<div class="category-outer">
								<div class="row">

								    @foreach($parent_category as $data)

							        <?php 
							            

							            $nameexp = explode(' ', $data->title);
							            $catname = implode('-', $nameexp);
							            $name = strtolower($catname);

							            $image = url("/category_images/".$data['img_url']);
							            ?>

											<div class="col-sm-4">
												<div class="cat-btn-outer">
													<a href="{{url("chat/$data->category_slug")}}" title="" class="cat-btn">
													<img src="{{$image}}"><br>
													{{ $data->title }}</a>
												</div>
											</div>

									@endforeach

								</div>
							</div>

						</div><!--/page center data-->
						@include('panels.footer-advertisement')
					</div>

 				@include('panels.right')

            </div>
        </div>
    </div><!--/pagedata-->
 
@endsection