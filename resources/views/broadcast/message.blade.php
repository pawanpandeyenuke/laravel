@extends('layouts.dashboard')

@include('panels.meta-data')
@section('title', 'Broadcast')
@section('content')

<div class="page-data dashboard-body">
	<div class="container">
		<div class="row">
			@include('panels.left')
			<div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="page-title no-left-padding">
						<div class="bclist-detail bcast-title">
							<div class="bc-img" style="background: url('/images/post-img-big.jpg');"></div>
							<div class="list-name">
								<a href="#" class="bc-name" title="">{{$title}}</a>
							</div>
							<div class="bl-mem">{{$name}}</div>
						</div>
					</div>
					<div class="bcast-msg-list-cont">
						<div class="row">
							<div class="col-md-12">
								<div class="bcast-message-list" id='bmsg'>
								<?php if($messages!=null) { ?>
								@foreach($messages as $data)
								<?php
									//$date=date('d M Y,h:i a',);
								 ?>
				 					<div class="single-message">
										<div class="clearfix">
											<div class="bcast-msg">
												<?= nl2br($data->broadcast_message) ?>
											</div>
										</div>
										<div class="bcast-msg-time">
											{{$data->created_at->format('d M Y,h:i a')}}
										</div>
									</div>
									@endforeach
									<?php }?>
								</div>
							</div>
						</div>
					</div>
					<div class="send-bcast-msg">
						<div class="row">
							<div class="col-sm-9">
								<textarea class="form-control broadcastmsg" name="" placeholder="Type your message here"></textarea>
							</div>
							<div class="col-sm-3">
								<button type="button" value="{{$id}}" class="btn btn-full btn-primary btn-bcast broadcastbtn">Broadcast</button>
							</div>
						</div>
					</div>
					
				</div><!--/page center data-->
				<div class="shadow-box bottom-ad"><img src="/images/bottom-ad.jpg" alt="" class="img-responsive"></div>
			</div>
			@include('panels.right')
		</div>
	</div>
</div>

@endsection
