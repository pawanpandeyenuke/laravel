@extends('layouts.dashboard')
<?php 
$title1=strtolower($title);
$title1=str_replace(" ","-",$title1);
 ?>
@section('content')
<div class="page-data dashboard-body">
	<div class="container">
		<div class="row">
   			 
   			 @include('panels.left')

   			 
			<div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="page-title no-left-padding">Detail</div>

					<div class="group-img">
						<img src="/images/post-img-big.jpg" class="g-img">
						<div class="grp-img-outer">
							<input type="file" class="filestyle" data-input="false" data-icon="true" data-iconName="glyphicon glyphicon-picture" data-buttonText=""  data-buttonName="btn-upload-icon">
						</div>
					</div>

					<div class="row">
						<div class="col-md-10 col-md-offset-1">
							<div class="edit-grp-name">
								<b><input type="text" name="privategroupname" class="pr-edit pr-gname" disabled="disabled"  value="{{$title}}"></b>
					<button type="button" class="edit-profile editgroupname" title="Edit Profile"><i class="fa fa-pencil"></i></button>
					<button type="button" class="save-profile-changes savegroupname" title="Save Profile" value="{{$groupid}}"><i class="fa fa-check-circle"></i></button>
								<!-- <button type="button" class="editbtn-pencil"><i class="fa fa-pencil"></i></button> -->
							</div>
						</div>
						<div class="col-md-12">
							<div class="participants-list">
								<h5>Participants</h5>
								<div class="row">
									<div class="col-md-10 col-md-offset-1">
										<div class="bcast-list grp-mem-list no-margin">
											@foreach($name as $data)
											<?php 
											if($data['id']==Auth::User()->id)
											{
											$fname="You";
											}else{
											$fname=$data['first_name']." ".$data['last_name'];
											}
											 ?>
											<div class="single-list">
												<div class="row" data-gid={{$groupid}}>
													<div class="col-sm-9">
														<div class="bclist-detail">
															<div class="bc-img" style="background: url('/images/user-thumb.jpg');"></div>
															<div class="list-name">
																<a href="{{url("profile/".$data['id'])}}" class="bc-name" title="" >{{$fname}}</a>

															</div>
														</div>
													</div>
													<div class="col-sm-3">
														<div class="bl-del text-right">
														<?php if($data['id']==$ownerid){ ?>
														<span disabled="disabled" style="font-size: 15px"><br>Admin</span>
														<?php }else if($ownerid==Auth::User()->id){ ?>
															<button type="button" value="{{$data['id']}}" class="bl-del-btn deluser"><i class="fa fa-trash"></i></button>
														<?php } ?>
														</div>
													</div>
												</div>
											</div>
											@endforeach
										</div>
									</div>
								</div>
							</div>

							<div class="btn-cont text-center mem">
								<ul class="list-inline">
								<?php if(Auth::User()->id==$ownerid){ ?>
								<li><a href="{{url("private-group-list")}}" title=""  class="btn btn-primary">Back</a></li>
								<?php }else{ ?>
								<li><a href="{{url("private-group-list/".$groupid)}}" title=""  class="btn btn-primary userleave">Leave Group</a></li>
								<?php } ?>
								<li><a href="{{url("groupchat/pg/".$groupid."/".$title1)}}" title=""  class="btn btn-primary startchat">Start Chat</a></li>
								</ul>

							<div class="bcast-list" style="display: none;">
								<select class="multiple-slt form-control" name="groupmembers[]" multiple="multiple">
						@foreach($friends as $data)
							<?php 
								$name=$data['user']['first_name']." ".$data['user']['last_name'];
								$id=$data['user']['id'];
							?>
									<option value="{{$id}}">{{$name}}</option>
						@endforeach
								</select>
							</div>
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