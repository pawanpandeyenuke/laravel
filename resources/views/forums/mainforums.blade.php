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
								<i class="flaticon-balloon"></i>Forums List
							</div>

							<div class="category-outer">
								<div class="row">

								    @foreach($forums as $data)

							        <?php

							            $fieldsData = DB::table('forums')->where(['parent_id' => $data->id])->where(['status' => 'Active'])->select('title', 'id')->get(); 

							            $nameexp = explode(' ', $data->title);
							            $catname = implode('-', $nameexp);
							            $name = strtolower($catname);
							            ?>

		                                @if($fieldsData)
											<div class="col-sm-4">
												<div class="cat-btn-outer">
													<a href="subforums/{{$data->id}}" title="" class="cat-btn">{{ $data->title }}</a>
												</div>
											</div>
										@else
											<div class="col-sm-4">
												<div class="cat-btn-outer">
													<a href="forumpost/{{$name}}" title="" class="cat-btn">{{ $data->title }}</a>
												</div>
											</div>
										@endif

									@endforeach

								</div>
							</div>

						</div><!--/page center data-->
						<div class="shadow-box bottom-ad"><img src="images/bottom-ad.jpg" alt="" class="img-responsive"></div>
					</div>

 				@include('panels.right')

            </div>
        </div>
    </div><!--/pagedata-->
 
@endsection