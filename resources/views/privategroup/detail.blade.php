@extends('layouts.dashboard')

@include('panels.meta-data')
@section('title', 'Private Group')
<?php 

$title1 = strtolower($groupdetail['title']);
$title1 = str_replace(" ","-",$title1);
$group_picture = url('/images/post-img-big.jpg');
if(isset($groupdetail) && !empty($groupdetail['picture'])){
	$group_picture = url('/uploads/'.$groupdetail['picture']);
}

?>
@section('content')
<div class="page-data dashboard-body">
	<div class="container">
		<div class="row">
   			 
   			 @include('panels.left')

   			 
			<div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="page-title no-left-padding"><h1>Detail</h1></div>

 		{{Form::open(array('url'=>'/private-group-detail/ajax/groupimage','id'=>'uploadgroupimage','files'=>true))}}
					<div class="group-img" id="groupimageholder">
						<img src="{{$group_picture}}" alt="Private Group's Image" class="g-img">
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
								<b><input type="text" name="privategroupname" class="pr-edit pr-gname" disabled="disabled"  value="{{$groupdetail['title']}}"></b>
								<?php if($ownerid == Auth::user()->id){ ?>
									<div id='friendsContainer'>
									<select id='friends' class='multiple-slt' multiple data-placeholder="Add members">
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
								<li><button data-forumtype="private" class="btn btn-primary del-confirm-forum" value="{{ $groupid }}" >Delete Group</button></li>
								<?php } else { ?>
								<li><button value="{{ $groupid }}" class="btn btn-primary del-confirm-forum" data-forumtype="private-leave">Leave Group</button></li>
								<?php } ?>
								<li><a href="{{url("chat/".$groupdetail['group_jid'])}}" title=""  class="btn btn-primary startchat">Start Chat</a></li>
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
				@include('panels.footer-advertisement')
			</div>
   		@include('panels.right')
		</div>
	</div>
</div>
<div class="modal fade" id="limitModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Message</h4>
        </div>
        <div class="modal-body">
          <p>Sorry, you can only create upto <?php echo Config::get('constants.private_group_limit'); ?> private groups.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
</div>
<script>
var redirectToGroupList = '<?php echo url( 'private-group-list' ); ?>';
</script>
<style>
.select2-container {
	width:200px;
	display:block;
}
#friendsContainer {
	width:498px;
	display:none;
}
</style>
@endsection