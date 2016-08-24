@extends('layouts.dashboard')
@section('title', 'Private Group - ')
<?php 

$title1 = strtolower($groupdetail[0]['title']);
$title1 = str_replace(" ","-",$title1);
$group_picture = url('/images/post-img-big.jpg');
if(isset($groupdetail[0]) && !empty($groupdetail[0]['picture'])){
	$group_picture = url('/uploads/'.$groupdetail[0]['picture']);
}

// echo '<pre>';print_r($group_picture);die;
// $group_picture = !empty($groupdetail[0]) ? url('/uploads/'.$groupdetail[0]['picture']) : url('/images/post-img-big.jpg');

 ?>
@section('content')
<div class="page-data dashboard-body">
	<div class="container">
		<div class="row">
   			 
   			 @include('panels.left')

   			 
			<div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="page-title no-left-padding">Detail</div>

 		{{Form::open(array('url'=>'/private-group-detail/ajax/groupimage','id'=>'uploadgroupimage','files'=>true))}}
					<div class="group-img" id="groupimageholder">
						<img src="{{$group_picture}}" class="g-img">
						<div class="grp-img-outer">
						<input type="hidden" name="groupid" value={{$groupid}}></input>
							<input name="groupimage" type="file" id="groupimage" class="filestyle" data-input="false" data-icon="true" data-iconName="glyphicon glyphicon-picture" data-buttonText=""  data-buttonName="btn-upload-icon" data-groupid="{{$groupid}}">
						</div>
					</div>
					{{Form::close()}}
			<!-- <button type="submit" class="btn btn-primary savegroupimage" style="display: none;">Change Image</button> -->
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
							<div class="edit-grp-name">
								<b><input type="text" name="privategroupname" class="pr-edit pr-gname" disabled="disabled"  value="{{$groupdetail[0]['title']}}"></b>
								<?php if($ownerid == Auth::user()->id){ ?>
									<div id='friendsContainer'>
									<select id='friends' class='multiple-slt' multiple>
										@foreach($friends as $data)
											<?php 
												$friendName = $data['user']['first_name']." ".$data['user']['last_name'];
												$id=$data['user']['id'];
											?>
											<option value="{{$id}}">{{$friendName}}</option>
										@endforeach
									</select>
									</div>
								<?php } ?>
								<button type="button" class="edit-profile editgroupname" title="Edit Profile"><i class="fa fa-pencil"></i></button>
								<button type="button" class="save-profile-changes savegroupname" title="Save Profile" value="{{$groupid}}"><i class="fa fa-check-circle"></i></button>
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
											$user_picture = !empty($data['picture']) ? url('/uploads/user_img/'.$data['picture']) : url('/images/user-thumb.jpg');
											 ?>
											<div class="single-list private-member-{{$data['id']}}">
												<div class="row" data-gid="{{$groupid}}">
													<div class="col-sm-9">
														<div class="bclist-detail">
															<div class="bc-img" style="background: url('{{$user_picture}}');"></div>
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
															<button type="button" value="{{$data['id']}}" data-forumtype = "del-private-member" data-gid="{{$groupid}}" class="bl-del-btn del-confirm-forum"><i class="fa fa-trash"></i></button>
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
								<?php } else { ?>
								<li><button value="{{ $groupid }}" class="btn btn-primary del-confirm-forum" data-forumtype="private-leave">Leave Group</button></li>
								<?php } ?>
								<li><a href="{{url("groupchat/pg/".$groupid)}}" title=""  class="btn btn-primary startchat">Start Chat</a></li>
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
				<div class="shadow-box bottom-ad"><img src="{{ url('images/bottom-ad.jpg') }}" alt="" class="img-responsive"></div>
			</div>
   		@include('panels.right')
		</div>
	</div>
</div>

<style>
.select2-container {
	width:200px;
	display:block;
}
#friendsContainer {
	width:89.5%;
	display:none;
}
</style>
@endsection