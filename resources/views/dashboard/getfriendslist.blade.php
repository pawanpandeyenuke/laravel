<ul>
{{--*/ $LastID = 0 /*--}}
@foreach($model as $data) 
<?php //echo '<pre>';print_r($data['friends']);die; ?>

	<?php
 		$profileimage = !empty($data['user']['picture']) ? $data['user']['picture'] : '/images/user-thumb.jpg'; 

		if($data['friend_id'] == Auth::User()->id){
			$name = $data['user']['first_name'].' '.$data['user']['last_name'];
			$profileimage = !empty($data['user']['picture']) ? $data['user']['picture'] : '/images/user-thumb.jpg'; 
			$userid = $data['user']['id'];
		}else{
			$name = $data['friends']['first_name'].' '.$data['friends']['last_name'];
			$profileimage = !empty($data['friends']['picture']) ? $data['friends']['picture'] : '/images/user-thumb.jpg'; 
		$userid = $data['friends']['id'];
		}

		if(!(($data['user_id']==Auth::User()->id && $data['status']=="Accepted")||($data['friend_id']==Auth::User()->id && $data['status']=='Rejected')))
		{
	?>
	{{--*/ $LastID = $data['id'] /*--}}
	<li data-id="{{$LastID}}" class="get_id flist" data-userid="{{$data['user']['id']}}" data-friendid="{{$data['friends']['id']}}">
		<div class="row">
			<div class="col-sm-6">
				<div class="user-cont">
					<a title="" href="profile/{{$userid}}">
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
	<?php } ?>
	@endforeach
</ul>
@if($count > 9)
<div class="load-btn load-more-friend" data-last-id="{{$LastID}}">
	<span class="loading-text" >View more</span>
	<span class="loading-img" style="display: none"><img src="/images/loading.gif" alt=""></span>
</div>
@endif
