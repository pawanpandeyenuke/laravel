	
		<?php 

		if($model1!=null)
		{
			$id1 = Auth::User()->id;

		?>
@foreach($model1 as $data) 
	<?php 
			
			$name = $data['first_name'].' '.$data['last_name'];

	?>
	<li  class="get_id flist" data-userid="{{$data['id']}}" data-friendid="{{$id1}}">
		<div class="row">
			<div class="col-sm-6">
				<div class="user-cont">
				<?php $profileimage = !empty($data['picture']) ? url('/uploads/user_img/'.$data['picture']) : url('/images/user-thumb.jpg'); ?>
					<a title="" href="profile/{{$data['id']}}">
						<span style="background: url('{{$profileimage}}');" class="user-thumb"></span>
					{{ $name }}
					</a>
				</div>
			</div>
			<div class="col-sm-6">
			<?php 
$status1=\App\Friend::where('user_id',$data['id'])->where('friend_id',Auth::User()->id)->value('status');
$status2=\App\Friend::where('friend_id',$data['id'])->where('user_id',Auth::User()->id)->value('status');


				if($status1 == 'Pending'){
			?>
				<div class="row">
					<div class="col-sm-6">
						<button class="btn btn-primary btn-full accept abc" type="button" id="accept" >Confirm</button>
					</div>
					<div class="col-sm-6">
						<button class="btn btn-default btn-full abc decline" type="button"  id="decline">Delete</button>
					</div>

					<span class="btn btn-default btn-full msg" id='msg' style="display: none;">Request Rejected</span>

					<span class="btn btn-default btn-full msg2" id='msg2' style="display: none;">Friend Removed</span>

					<div class="text-right">

					<button class="btn btn-default btn-full remove abc" type="button" id="remove" style="display: none;">Remove</button>
				</div>
				</div>
			<?php }elseif($status2 == 'Pending'){ 
			?>
				<div class="text-right">
					<button class="btn btn-primary btn-full sent" type="button" id="sent">Undo</button>
							<button type="button" class="btn btn-primary btn-full invite" id='invite' style="display: none;">Add Friend</button>
				</div>

			<?php }elseif($status1=='Accepted' || $status2=='Accepted'){ 
			?>
				<div class="text-right">
					<button class="btn btn-default btn-full remove abc" type="button" id="remove">Remove</button>
				</div>
				<?php }elseif($status2=='Rejected'){ 
					?>
					<div class="text-right">
						<button type="button" class="btn btn-primary btn-full resend" id='resend'>Add Friend</button>
					</div>
					<div class="text-right">
					<button class="btn btn-primary btn-full sent" type="button" id="sent"style="display: none;">Undo</button>
							<button type="button" class="btn btn-primary btn-full invite" id='invite' style="display: none;">Add Friend</button>
				</div>

					<?php }elseif($status1=='Rejected'||($status1==null)&&($status2==null)){

					?>	
					<div class="text-right">
						<button type="button" class="btn btn-primary btn-full invite" id='invite'>Add Friend</button>
					</div>
					<div class="text-right">
					<button class="btn btn-primary btn-full sent" type="button" id="sent"style="display: none;">Undo</button>
						<button type="button" class="btn btn-primary btn-full invite" id='invite' style="display: none;">Add Friend</button>
					</div>

				<?php }
				$last_id = $data['id'];
				 ?>
			</div>
		</div>
	</li>
	@endforeach


	<?php } else
		{
		
         ?>
 
@if(empty($model))
		<div class="no-result">No friends found.</div>
@else
<ul>
@foreach($model as $data) 
	<?php 
		if($data['friend_id'] == Auth::User()->id){
			$name = $data['user']['first_name'].' '.$data['user']['last_name'];
		$profileimage = !empty($data['user']['picture']) ? $data['user']['picture'] : '/images/user-thumb.jpg';
		$viewid=$data['user']['id'];
		}
		else{
			$name = $data['friends']['first_name'].' '.$data['friends']['last_name'];
			$profileimage = !empty($data['friends']['picture']) ? url('/uploads/user_img/'.$data['friends']['picture']) : url('/images/user-thumb.jpg');
			$viewid=$data['friends']['id'];

		}
		if(!(($data['user_id']==Auth::User()->id && $data['status']=="Accepted")||($data['friend_id']==Auth::User()->id && $data['status']=='Rejected')))
		{
	?>
	<li  class="get_id flist" data-userid="{{$data['user']['id']}}" data-friendid="{{$data['friends']['id']}}">
		<div class="row">
			<div class="col-sm-6">
				<div class="user-cont">
					<a title="" href="profile/{{$viewid}}">
						<?php  ?>						
						<span style="background: url('{{$profileimage}}');" class="user-thumb"></span>
					{{ $name }}
					</a>
				</div>
			</div>
			<div class="col-sm-6">
			<?php 
				if(($data['status'] == 'Pending') && ($data['friend_id'] == Auth::User()->id)){
			?>
				<div class="row">
					<div class="col-sm-6">
						<button class="btn btn-primary btn-full accept abc" type="button" id="accept" >Confirm</button>
					</div>
					<div class="col-sm-6">
						<button class="btn btn-default btn-full abc decline" type="button"  id="decline">Delete</button>
					</div>

					<span class="btn btn-default btn-full msg" id='msg' style="display: none;">Request Rejected</span>

					<span class="btn btn-default btn-full msg2" id='msg2' style="display: none;">Friend Removed</span>

					<div class="text-right">

					<button class="btn btn-default btn-full remove abc" type="button" id="remove" style="display: none;">Remove</button>
				</div>
				</div>
			<?php }elseif(($data['status'] == 'Pending') && ($data['user_id'] == Auth::User()->id)){ 
			?>
				<div class="text-right">
					<button class="btn btn-primary btn-full sent" type="button" id="sent">Undo</button>
						<button type="button" class="btn btn-primary btn-full invite" id='invite' style="display: none;">Add Friend</button>
				</div>

			<?php }elseif(($data['status'] == 'Accepted') && ($data['user_id'] == Auth::User()->id) || ($data['friend_id'] == Auth::User()->id)){ 
			?>
				<div class="text-right">
					<button class="btn btn-default btn-full remove abc" type="button" id="remove">Remove</button>
				</div>
				<?php }elseif(($data['status'] == 'Rejected') && ($data['user_id'] == Auth::User()->id)){ 
					?>
					<div class="text-right">
						<button type="button" class="btn btn-primary btn-full resend" id='resend'>Add Friend</button>
					</div>
					<div class="text-right">
					<button class="btn btn-primary btn-full sent" type="button" id="sent"style="display: none;">Undo</button>
							<button type="button" class="btn btn-primary btn-full invite" id='invite' style="display: none;">Add Friend</button>
				</div>
					<?php }?>
			
			</div>
		</div>
	</li>
	<?php }
		$last_id = $data['id'];
	 ?>

	@endforeach
		</ul>
		<?php if($count > 10){ 
			//print_r("die here");die;
			?>
			<div class="load-btn load-more-friend-search" data-last-id = "{{$last_id}}" data-keyword="{{$keyword}}">
			<span class="loading-text">View more</span>
			<span class="loading-img" style="display: none"><img src="{{url('/images/loading.gif')}}" alt="Shop By Temperature"></span>
		</div>
		<?php } ?>
@endif
	<?php } ?>

