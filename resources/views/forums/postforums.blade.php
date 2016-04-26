@extends('layouts.dashboard')
<?php //print_r($forumpost);die; ?>
@section('content')
	<div class="page-data dashboard-body">
	        <div class="container">
	            <div class="row">

	      @section('content')
	<div class="page-data dashboard-body">
	        <div class="container">
	            <div class="row">

	            @include('panels.left')

					<div class="col-sm-6">
						<div class="shadow-box page-center-data no-margin-top">

							<div class="page-title">
								{{ $forumpost['parentname']}}
								<i class="flaticon-balloon"></i>

							</div>
							
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
								
								<button type="submit" value="" class="btn btn-primary btn-lg"></button>
							</div>
							
						</div><!--/page center data-->
						<div class="shadow-box bottom-ad"><img src="/images/bottom-ad.jpg" alt="" class="img-responsive"></div>
					</div>

 				@include('panels.right')

            </div>
        </div>
    </div><!--/pagedata-->