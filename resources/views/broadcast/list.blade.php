@extends('layouts.dashboard')
<?php //print_r($broadcast);die; ?>
@section('content')
<div class="page-data dashboard-body">
	<div class="container">
		<div class="row">
   			 
   			 @include('panels.left')
			<div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="page-title no-left-padding">Create New Broadcast List</div>
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
							<div class="bcast-list no-margin">
								
							@foreach($broadcast as $data)
							<?php
							$namestr='';
							$name=array();
							foreach ($data['members'] as $mem) {
								$name[]=DB::table('users')->where('id',$mem['member_id'])->value('first_name');
							}
								$namestr=implode(",",$name);

							 ?>
								<div class="single-list broadcast_{{$data['id']}}" data-broadcastid="{{$data['id']}}">
									<div class="row">
										<div class="col-sm-9">
											<div class="bclist-detail bclist1">
												<div class="bc-img" style="background: url('images/user-thumb.jpg');"></div>
												<div class="list-name">
													<a href="broadcast-msg/{{$data['id']}}" class="bc-name"  title="">{{$data['title']}}</a>
												</div>
												<div class="bl-mem">{{$namestr}}</div>
											</div>
											<div class="bclist-detail bclist2" style="display: none;">
													<span class="bc-name"  title="" style="font-size: 15pt"><b>Broadcast "{{$data['title']}}" deleted.</b></span>
											</div>

										</div>
										<div class="col-sm-3">
											<div class="bl-del text-right">
												<button type="button" value="{{$data['id']}}" data-forumtype = "broadcast" class="bl-del-btn del-confirm-forum"><i class="fa fa-trash"></i></button>
											</div>
										</div>
									</div>
								</div>
							@endforeach
							</div>
							<div class="add-blist text-center">
								<a href="{{url('broadcast-add')}}" title="" class="add-blist-btn"><i class="fa fa-plus"></i></a>
							</div>
						</div>
					</div>

				</div><!--/page center data-->
				<div class="shadow-box bottom-ad"><img src="images/bottom-ad.jpg" alt="" class="img-responsive"></div>
			</div>
			@include('panels.right')
		</div>
	</div>
</div>
@endsection