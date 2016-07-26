@extends('layouts.dashboard')
@section('title', 'Private Group - ')
@section('content')

<?php 
 	$private_group_limit = Config::get('constants.private_group_limit'); 
?>


<div class="page-data dashboard-body">
	<div class="container">
		<div class="row">
   			 
   			 @include('panels.left')
			<div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="page-title no-left-padding">Group List</div>
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
							<div class="bcast-list no-margin">
								
					@foreach($privategroup as $data)
					<?php   
							$namestr='';
							$name=array();
							$count=0;
						foreach ($data['members'] as $mem) {
								if($mem['member_id']==Auth::User()->id)
								{
									$name[]="You";
									$count++;
								}
								else{
								$name[]=\App\User::where('id',$mem['member_id'])->value('first_name');
								}
							}

							$group_picture = !empty($data['picture']) ? url('uploads/'.$data['picture']) : url('/images/post-img-big.jpg');
							// echo '<pre>';print_r($group_picture);die;
							$namestr=implode(",",$name);

							if(!($count==0) || $data['owner_id']==Auth::User()->id)
							{          
							?>
								<div class="single-list private-group_{{$data['id']}}" data-groupid="">
									<div class="row">
										<div class="col-sm-9">
											<div class="bclist-detail bclist1">
												<div class="bc-img" style="background: url(<?= $group_picture ?>);"></div>
												<div class="list-name">

													<a href="{{url("private-group-detail/".$data['id'])}}" class="bc-name"  title="">{{$data['title']}}</a>
												</div>
												<div class="bl-mem">{{$namestr}}</div>
											</div>
										</div>
										
										<div class="col-sm-3">
											<div class="bl-del text-right">
											<?php if($data['owner_id']==Auth::User()->id){ ?>
												<button type="button" value="{{$data['id']}}" class="bl-del-btn del-confirm-forum" data-forumtype = "private"><i class="fa fa-trash"></i></button>
												<?php } else{ ?>
												<button value="{{$data['id']}}" title=""  class="btn btn-primary del-confirm-forum" data-forumtype = "private-leave">Leave Group</button>
												<?php }?>
											</div>
										</div>
									</div>
								</div>
								<?php } ?>
						@endforeach
							</div>
							@if (Session::has('error'))
								<div class="alert alert-danger">{!! Session::get('error') !!}</div>
							@endif
							@if (Session::has('success'))
								<div class="alert alert-success">{!! Session::get('success') !!}</div>
							@endif
							<div class="alert alert-danger" id="ajaxmsg" style="display:none;"><?php echo "Sorry, you can only create upto {{$private_group_limit}} private groups."; ?></div>
							<div class="add-blist text-center">
								<a href="#create-group" title="" class="add-blist-btn add-pglist-btn"><i class="fa fa-plus"></i></a>
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
<script type="text/javascript">
	
	$(document).on('click','.add-pglist-btn',function(){
			var len = $(".bcast-list > div").length;
			if(len >= <?= $private_group_limit ?>)
				$('#ajaxmsg').show();
			else
				window.location = "private-group-add";
	});
</script>
@endsection