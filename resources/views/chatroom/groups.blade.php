@extends('layouts.dashboard')

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

							        <?php /*$titledata = explode(',', $data->title);
							            if(is_array($titledata)){
							                $title1 = strtolower(implode('', $titledata));

							                $exp = explode(' ', $title1);
							                if(is_array($exp))
							                    $title = implode('', $exp);
							   	             else
							                    $title = $title1;
							            } */// echo '<pre>';print_r($title);die;

							            $fieldsData = DB::table('categories')->where(['parent_id' => $data->id])->where(['status' => 'Active'])->select('title', 'id')->get(); 

							            $nameexp = explode(' ', $data->title);
							            $catname = implode('-', $nameexp);
							            $name = strtolower($catname);

							            $image = url("/category_images/".$data['img_url']);
							            ?>

		                                @if($fieldsData)
											<div class="col-sm-4">
												<div class="cat-btn-outer">
													<a href="subgroup/{{$data->id}}/{{$name}}" title="" class="cat-btn">
													<img src="{{$image}}"><br>
													{{ $data->title }}</a>
												</div>
											</div>
										@else
											<div class="col-sm-4">
												<div class="cat-btn-outer">
													<a href="groupchat/{{$name}}" title="" class="cat-btn">
													<img src="{{$image}}"><br>
													{{ $data->title }}</a>
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