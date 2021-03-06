@extends('layouts.dashboard')

@include('panels.meta-data')
@section('title', 'Broadcast')
@section('content')

<div class="page-data dashboard-body">
	<div class="container">
		<div class="row">
   			 
   			 @include('panels.left')
			<div class="col-sm-6 col-xs-12">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="page-title no-left-padding broadcast-heading"><h1>Broadcast List</h1></div>
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
							<div class="bcast-list no-margin">
								@foreach($broadcast as $data)
								<?php
								$namestr='';
								$name=array();
								foreach ($data['members'] as $mem) {
									$name[]=\App\User::where('id',$mem['member_id'])->value('first_name');
								}
									$namestr=implode(",",$name);

								 ?>
									<div class="single-list broadcast_{{$data['id']}}" data-broadcastid="{{$data['id']}}">
										<div class="row">
											<div class="col-sm-9 col-xs-12">
												<div class="bclist-detail bclist1">
													<div class="bc-img" style="background: url('images/post-img-big.jpg');"></div>
													<div class="list-name">
														<a href="broadcast-msg/{{$data['id']}}" class="bc-name"  title="">{{$data['title']}}</a>
													</div>
													<div class="bl-mem">{{$namestr}}</div>
												</div>
												<div class="bclist-detail bclist2" style="display: none;">
														<span class="bc-name"  title="" style="font-size: 15pt"><b>Broadcast "{{$data['title']}}" deleted.</b></span>
												</div>

											</div>
											<div class="col-sm-3 col-sx-12">
												<div class="bl-del text-right">
													<a href="{{ url('broadcast-edit/'.$data['id']) }}" title="" class="bl-del-btn"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;&nbsp;
													<button type="button" value="{{$data['id']}}" data-forumtype = "broadcast" class="bl-del-btn del-confirm-forum"><i class="fa fa-trash"></i></button>
												</div>
											</div>
										</div>
									</div>
								@endforeach
							</div>
							@if (Session::has('error'))
								<div class="alert alert-danger">{!! Session::get('error') !!}</div>
							@endif
							@if (Session::has('success'))
								<div class="alert alert-success">{!! Session::get('success') !!}</div>
							@endif
							<div class="alert alert-danger" id="bajaxmsg" style="display:none;"><?php echo "Sorry, you can only add upto ".Config::get('constants.broadcast_limit')." broadcasts."; ?>
							</div>
							<div class="add-blist text-center">
								<a href="#create-broadcast" title="" class="add-blist-btn"><i class="fa fa-plus"></i></a>
							</div>
						</div>
					</div>

				</div><!--/page center data-->
				@include('panels.footer-advertisement')
			</div>
			@include('panels.right')
		</div>
	</div>
</div>
<script type="text/javascript">

		$(document).on('click','.add-blist-btn',function(){
			var len = $(".bcast-list > div").length;
			if(len >= <?php echo Config::get('constants.broadcast_limit'); ?>)
				$('#bajaxmsg').show();
			else
				window.location = "broadcast-add";
	});
</script>
@endsection
