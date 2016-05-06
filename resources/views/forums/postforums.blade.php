@extends('layouts.dashboard')

@section('content')
	<div class="page-data dashboard-body">
	        <div class="container">
	            <div class="row">

	    
	            @include('panels.left')

	            
					<div class="col-sm-6">
						<div class="shadow-box page-center-data no-margin-top">
						{!! Form::open(array('url' => '/addnewforumpost', 'id' => 'addnewpost','method' => 'post')) !!}
							<div class="page-title">
								{{ $forumpost['parentname']}}
								<i class="flaticon-balloon"></i>

							</div>
				<input type="text" name="topic"> </input>
				<input type="hidden" name="category_id" value="{{$category_id}}"></input>				
							<div class="row">
								<div class="col-md-8 col-md-offset-3">
									<div class="radio-outer-full">
										<div class="row">
											<div class="col-sm-8 col-sm-offset-3">

											{{$forumpost['subcategory']}}
														<div class="radio-cont radio-label-left">
															<input class="group-radio" type="radio" name="subcategory" value="" id=""></input>
															<label for=""></label>

									<input type="hidden" name="parentname" value=""></input>
								</div>
							</div>
							<div class="btn-cont text-center">
							<?php
								 ?>
								
								<button type="submit" value="" class="btn btn-primary btn-lg">New Post</button>
							</div>
							{!! Form::close() !!}
						</div><!--/page center data-->
						<div class="shadow-box bottom-ad"><img src="/images/bottom-ad.jpg" alt="" class="img-responsive"></div>
					</div>

 				@include('panels.right')

            </div>
        </div>
    </div><!--/pagedata-->