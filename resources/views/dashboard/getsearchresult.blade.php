<?php
if(Auth::check())
	$id1=Auth::User()->id;
else
	$id1 = "";

$count=0;	

?>
	
@foreach($model as $data)
	<?php 
		$data = (array) $data;
		$name = $data['first_name'].' '.$data['last_name'];
		$location = array($data['country'], $data['state'], $data['city']);

		foreach ($location as $key => $value) {
			if(empty($value)){
				unset($location[$key]);
			}
		}
		// echo '<pre>';print_r($location);die;
		$location = implode(', ', $location);
	?>
	<li  class="get_id" data-userid="{{$data['id']}}" data-friendid="{{$id1}}" >
		<div class="row">
			<div class="col-sm-7 col-md-7 col-xs-12" value="hello">
				<div class="user-cont">
					<a title="" href="profile/{{$data['id']}}">
						<?php $profileimage = !empty($data['picture']) ? url('/uploads/user_img/'.$data['picture']) : url('/images/user-thumb.jpg'); ?>
						<span class="hello user-thumb" value="hello" style="background: url('{{$profileimage}}');" class="user-thumb"></span>
					{{ $name }}
					</a>
					@if($location)
						<ul class="list-inline">
							<li><i class="fa fa-map-marker"></i> {{ $location }} </li>					
						</ul>
					@endif
				</div>
			</div>
			<div class="col-sm-5 col-md-5 col-xs-12">
		@if(Auth::check())
	<?php
		$count++;
		if(Auth::check())
		{
			$status1=\App\Friend::where('user_id',$data['id'])->where('friend_id',Auth::User()->id)->value('status');
			$status2=\App\Friend::where('friend_id',$data['id'])->where('user_id',Auth::User()->id)->value('status');
		} else {
			$status1 = null;
			$status2 = null;
		}

		if($status1 == 'Pending'){ ?>
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
				</div>

					<?php }elseif($status1=='Rejected'||($status1==null)&&($status2==null) && $data['id']!=Auth::User()->id){

					?>	
					<div class="text-right">
						<button type="button" class="btn btn-primary btn-full invite" id='invite'>Add Friend</button>
					</div>
					<div class="text-right">
					<button class="btn btn-primary btn-full sent" type="button" id="sent"style="display: none;">Undo</button>
					</div>


				<?php } ?>
				@else
				<div class="text-right">
						<a type="button" class="btn btn-primary btn-full" href="{{url('/register')}}">Add Friend</a>
					</div>
				@endif
			</div>
		</div>
	</li>
@endforeach