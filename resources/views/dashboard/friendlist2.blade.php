		<?php 

		if($model1!=null)
		{
			$id1 = Auth::User()->id;

		?>

@foreach($model1 as $data) 
	<?php 
			
			$name = $data['first_name'].' '.$data['last_name'];

	?>
	<li  class="get_id" data-userid="{{$data['id']}}" data-friendid="{{$id1}}">
		<div class="row">
			<div class="col-sm-6">
				<div class="user-cont">
				<?php $profileimage = !empty($data['picture']) ? $data['picture'] : '/images/user-thumb.jpg'; ?>
					<a title="" href="profile/{{$data['id']}}">
						<span style="background: url('{{$profileimage}}');" class="user-thumb"></span>
					{{ $name }}
					</a>
				</div>
			</div>
			<div class="col-sm-6">
			<?php 
$status1=DB::table('friends')->where('user_id',$data['id'])->where('friend_id',Auth::User()->id)->value('status');
$status2=DB::table('friends')->where('friend_id',$data['id'])->where('user_id',Auth::User()->id)->value('status');


				if($status1 == 'Pending'){
			?>
				<div class="row">
					<div class="col-sm-6">
						<button class="btn btn-primary btn-full accept abc" type="button" id="accept" >Accept</button>
					</div>
					<div class="col-sm-6">
						<button class="btn btn-default btn-full abc decline" type="button"  id="decline">Decline</button>
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
					<button class="btn btn-primary btn-full sent" type="button" id="sent">Cancel Request</button>
							<button type="button" class="btn btn-primary btn-full invite" id='invite' style="display: none;">Add as a friend</button>
				</div>

			<?php }elseif($status1=='Accepted' || $status2=='Accepted'){ 
			?>
				<div class="text-right">
					<button class="btn btn-default btn-full remove abc" type="button" id="remove">Remove</button>
				</div>
				<?php }elseif($status2=='Rejected'){ 
					?>
					<div class="text-right">
						<button type="button" class="btn btn-primary btn-full resend" id='resend'>Re-Send</button>
					</div>
					<div class="text-right">
					<button class="btn btn-primary btn-full sent" type="button" id="sent"style="display: none;">Cancel Request</button>
							<button type="button" class="btn btn-primary btn-full invite" id='invite' style="display: none;">Add as a friend</button>
				</div>

					<?php }elseif($status1=='Rejected'||($status1==null)&&($status2==null)){

					?>	
					<div class="text-right">
						<button type="button" class="btn btn-primary btn-full invite" id='invite'>Add as a friend</button>
					</div>
					<div class="text-right">
					<button class="btn btn-primary btn-full sent" type="button" id="sent"style="display: none;">Cancel Request</button>
						<button type="button" class="btn btn-primary btn-full invite" id='invite' style="display: none;">Add as a friend</button>
					</div>

				<?php } ?>
			</div>
		</div>
	</li>
	@endforeach


	<?php } else
		{
         ?>
@foreach($model as $data) 
	<?php 
//print_r($model);die;
		if($data['friend_id'] == Auth::User()->id){
			$name = $data['user']['first_name'].' '.$data['user']['last_name'];
		$profileimage = !empty($data['user']['picture']) ? $data['user']['picture'] : '/images/user-thumb.jpg';
		$viewid=$data['user']['id'];
		}
		else{
			$name = $data['friends']['first_name'].' '.$data['friends']['last_name'];
			$profileimage = !empty($data['friends']['picture']) ? $data['friends']['picture'] : '/images/user-thumb.jpg';
			$viewid=$data['friends']['id'];

		}
		if(!(($data['user_id']==Auth::User()->id && $data['status']=="Accepted")||($data['friend_id']==Auth::User()->id && $data['status']=='Rejected')))
		{
	?>
	<li  class="get_id" data-userid="{{$data['user']['id']}}" data-friendid="{{$data['friends']['id']}}">
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
						<button class="btn btn-primary btn-full accept abc" type="button" id="accept" >Accept</button>
					</div>
					<div class="col-sm-6">
						<button class="btn btn-default btn-full abc decline" type="button"  id="decline">Decline</button>
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
					<button class="btn btn-primary btn-full sent" type="button" id="sent">Cancel Request</button>
						<button type="button" class="btn btn-primary btn-full invite" id='invite' style="display: none;">Add as a friend</button>
				</div>

			<?php }elseif(($data['status'] == 'Accepted') && ($data['user_id'] == Auth::User()->id) || ($data['friend_id'] == Auth::User()->id)){ 
			?>
				<div class="text-right">
					<button class="btn btn-default btn-full remove abc" type="button" id="remove">Remove</button>
				</div>
				<?php }elseif(($data['status'] == 'Rejected') && ($data['user_id'] == Auth::User()->id)){ 
					?>
					<div class="text-right">
						<button type="button" class="btn btn-primary btn-full resend" id='resend'>Re-Send</button>
					</div>
					<div class="text-right">
					<button class="btn btn-primary btn-full sent" type="button" id="sent"style="display: none;">Cancel Request</button>
							<button type="button" class="btn btn-primary btn-full invite" id='invite' style="display: none;">Add as a friend</button>
				</div>
					<?php }?>
			
			</div>
		</div>
	</li>
	<?php } ?>
	@endforeach

	<?php } ?>
