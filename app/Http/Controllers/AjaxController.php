<?php

namespace App\Http\Controllers;
use App\State, App\City, App\Like, App\Comment, App\User, App\Friend, DB;
use Illuminate\Http\Request;
use Session, Validator, Cookie;
use App\Http\Requests;
use XmppPrebind, App\DefaultGroup;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\Feed, Auth;
use \Exception;

class AjaxController extends Controller
{

	//Handling posts
	public function posts()
	{
		try
		{
			$arguments = Input::all();
			$model = new Feed;

			if( $arguments ){

				$user = Auth::User();				
				$userid = $user->id;
				$arguments['user_by'] = $user->id;
	
				if( empty($arguments['message']) && empty($arguments['image']))
					throw new Exception('Post something to update.');

				$file = Input::file('image');

				if( isset($arguments['image']) && $file != null ){

					$image_name = time()."_POST_".strtoupper($file->getClientOriginalName());
					$arguments['image'] = $image_name;
					$file->move(public_path('uploads'), $image_name);

				}

				$feed = $model->create( $arguments );
				
				if( !$feed )
					throw new Exception('Something went wrong.');

				$name = Auth::User()->first_name.' '.Auth::User()->last_name;
				$time = $feed->updated_at->format('h:i A');
				$time1 = $feed->updated_at->format('D jS');
				$picture = $feed->image;
				$message = $feed->message;
				$feedid = $feed->id;

		
				if($arguments['message'] && empty($arguments['image'])){
					$popupclass = 'postpopupajax';
				}elseif($arguments['image'] && empty($arguments['message'])){
					$popupclass = 'popupajax';
				}else{
					$popupclass = 'popupajax';
				} 													
												

if(!empty($feed->message)){ 
$message = <<<message
<p>$message</p>
message;
}else{
$message = '';
}

if(!empty($feed->image)){ 
$picture = <<<image
	<div class="post-img-cont">
		<img src="uploads/$picture" class="post-img img-responsive">
	</div>
image;
}else{
$picture = '';
}

$postHtml = <<<postHtml

			<div class="single-post" data-value="$feed->id" id="post_$feed->id">
				<div class="post-header" data-value="$feed->id" id="post_$feed->id">
					<button type="button" class="p-del-btn post-delete" data-toggle="modal" data-target=".post-del-confrm"><span class="glyphicon glyphicon-remove"></span></button>
					<div class="row">
						<div class="col-md-7">
							<a href="profile/$userid" title="" class="user-thumb-link">
								<span class="small-thumb" style="background: url('images/user-thumb.jpg');"></span>
								$name
							</a>
						</div>
						<div class="col-md-5">
							<div class="post-time text-right">
								<ul>
									<li><span class="icon flaticon-time">$time</span></li>
									<li><span class="icon flaticon-days">$time1</span></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="post-data">
					$message
					$picture
				</div>
				<div class="post-footer">
					<div class="post-actions">
						<ul>
							<li>
								<div class="like-cont">
									<input type="checkbox" name="" id="checkbox<?php echo $feed->id ?>" class="css-checkbox like"/>
									<label for="checkbox<?php echo $feed->id ?>" class="css-label">
										<span class="countspan" id="page-$feedid"></span>
										<span>Like</span>
									</label>
								</div>
							</li>
							<li>
								<a class="$popupclass" style="cursor:pointer">
									<span class="icon flaticon-interface-1"></span> 
									<span class="commentcount">Comment</span>
								</a>
							</li>
						</ul>
					</div>
					<div class="post-comment-cont">
						<div class="post-comment" data-value="$feedid" id="post_$feedid">
							<div class="row">
								<div class="col-md-10">
									<div class="emoji-field-cont cmnt-field-cont">
										<textarea data-emojiable="true" type="text" class="form-control comment-field" placeholder="Type here..."></textarea>
									</div>
								</div>
								<div class="col-md-2">
									<button type="button" class="btn btn-primary btn-full comment">Post</button>
								</div>
							</div>
						</div><!--/post comment-->
						<div class="comments-list">
							<ul>

							</ul>
						</div><!--/comments list-->
					</div>
				</div>
			</div>
postHtml;

echo $postHtml;


			}

		}catch( Exception $e ){

			return $e->getMessage();

		}		

		exit;
	}


	//Get comment box
	public function getCommentBox()
	{

		$arguments = Input::all();

		$feeddata = Feed::with('comments')->with('likes')->with('user')->where('id', '=', $arguments['feed_id'])->get()->first();
 
		return view('dashboard.getcommentbox')
					->with('feeddata', $feeddata);

 	}



	public function postcomment()
	{

		$arguments = Input::all();

		$comments = new Comment;

		$user = User::find($arguments['commented_by']);
		if( !$user )
			return 'No record found of the user.';

		$feed = Feed::find($arguments['feed_id']);
		if(!$feed)
			return 'The post may have expired or does not exist.';

		$model = $comments->create($arguments);

		$userid = Auth::User()->id;
		$username = Auth::User()->first_name.' '.Auth::User()->last_name;
		$comment = $model->comments;
		$time = $model->updated_at->format('h:i A');
		$id = $model->id;

$variable = array();				
$variable['comment'] = <<<comments
<li data-value="$id" id="post_$id">
	<button type="button" class="p-del-btn comment-delete" data-toggle="modal" data-target=".comment-del-confrm"><span class="glyphicon glyphicon-remove"></span></button>
	<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
	<div class="comment-title-cont">
		<div class="row">
			<div class="col-sm-6">
				<a href="#" title="" class="user-link">$username</a>
			</div>
			<div class="col-sm-6">
				<div class="comment-time text-right">$time</div>
			</div>
		</div>
	</div>
	<div class="comment-text">$comment</div>
</li>
comments;

		$count = DB::table('comments')->where(['feed_id' => $arguments['feed_id']])->get();	
		$variable['count'] = count($count);
		$data = json_encode($variable);
		echo $data;

		exit;
	}


	public function getxmppuser(){

		$status=0;
		$user_id = Auth::User()->id;
		$node = config('app.xmppHost');

		$user = User::find($user_id);
		
		if ( !empty($user['xmpp_username']) && !empty($user['xmpp_username']) ) 
		{

			$xmppPrebind = new XmppPrebind($node, 'http://'.$node.':5280/http-bind', '', false, false);
			$username = $user->xmpp_username;
			$password = $user->xmpp_password;
			$xmppPrebind->connect($username, $password);
			$xmppPrebind->auth();
			$sessionInfo = $xmppPrebind->getSessionInfo();
			$status = 1;
		}

		$sessionInfo['status']=$status;	  
		echo json_encode($sessionInfo); 
		exit;
 	}


	public function searchfriend(){

		$xmppusername = Input::get('xmpp_username');
		$node = config('app.xmppHost');

		$message="No Result Found";
		if( count($xmppusername) && $xmppusername != ''){
 
			$users = User::where('xmpp_username', 'LIKE', $xmppusername)->get();

			if( count($users) > 0 )
			{
				$message="";
				foreach ($users as $value) { 
					$userdata = $value->toArray();
					$name = $userdata['first_name'].' '.$userdata['last_name'];
					$message .= '<div class="row"> <a href="javascript:void(0);" onclick="openChatbox(\''.$userdata['xmpp_username'].'\',\''.$userdata['xmpp_password'].'\');">'.$name.'</a></div>';
				}	
			}
		}
		echo $message; 
		exit;
 	}
 	


	/**
	*	Get friend lists ajax call handling.
	*	Ajaxcontroller@getfriendslist
	*/
	public function getfriendslist()
	{
		
		$input=Input::get('type');

		$model=Friend::with('user')->with('friends')->with('user')->where( function( $query ) use ( $input ) {
			    self::queryBuilder( $query, $input );
				})->get()->toArray();

		return view('dashboard.getfriendslist')->with('model',$model);
 
	}


	/**
	*	Query builderfor friend lists ajax call handling.
	*	Ajaxcontroller@queryBuilder
	*/
	public function queryBuilder( &$query, $input ){
		$user_id = Auth::User()->id;
		if($input == 'all'){
            $query->where('user_id', '=', $user_id);
            $query->orWhere('friend_id', '=', $user_id);
        }elseif($input == 'sent'){
            $query->where('user_id', '=', $user_id);
            $query->where('status', '=', 'Pending');
        }elseif($input == 'recieved'){
            $query->where('friend_id', '=', $user_id);
            $query->where('status', '=', 'Pending');
        }elseif($input == 'current'){
            $query->where('user_id', '=', $user_id)->where('status', '=', 'Accepted');
            $query->orWhere('friend_id', '=', $user_id)->where('status', '=', 'Accepted');
        } 
	}



 

	/**
	*	Get states ajax call handling.
	*	Ajaxcontroller@getStates
	*/
	public function getStates()
	{
		$input = Input::all();
		$statequeries = State::where(['country_id' => $input['countryId']])->get();		
		$states = array('<option value="">State</option>');
		foreach($statequeries as $query){			
			$states[] = '<option value="'.$query->state_id.'">'.$query->state_name.'</option>';
		}		
		echo implode('',$states);
	}


	/**
	*	Get cities ajax call handling.
	*	Ajaxcontroller@getCities
	*/
	public function getCities()
	{
		$input = Input::all();
		$cityqueries = City::where(['state_id' => $input['stateId']])->get();
		$city = array('<option value="">City</option>');
		foreach($cityqueries as $query){			
			$city[] = '<option value="'.$query->city_id.'">'.$query->city_name.'</option>';
		}		
		echo implode('',$city);
	}


	/*
	 * Managing likes on api request.
	 */
	public function webgetlikes()
	{
		try
		{	
			$arguments = Input::all();
			$likes = new Like;

			if( $arguments ){
				$validator = Validator::make($arguments, $likes->rules, $likes->messages);
				if($validator->fails()) {					
					throw new Exception($this->getError($validator));					
				}else{
					$feed = Feed::find($arguments['feed_id']);
					if( !$feed )
						throw new Exception( 'Feed does not exists' );
					
					$like = Like::where([ 'feed_id' => $arguments['feed_id'], 'user_id' => $arguments['user_id'] ])->get()->toArray();

					if( empty($like) ){
						$model = new Like;
						$response = $model->create( $arguments );
					}else{
						$model = Like::where([ 'feed_id' => $arguments['feed_id'], 'user_id' => $arguments['user_id']])->delete();
						// echo '<pre>';print_r($response);exit;
					}
				}
				$count = DB::table('likes')->where(['feed_id' => $arguments['feed_id']])->get();
				// print_r();die;
				echo $likes = count($count);
				
			}
		}catch( Exception $e ){
			return $e->getMessage();
		}
		exit;
	}


	/*
	* Accept request from another user.
	*
	**/
	public function accept()
	{
		$input=Input::all();
		
     	$data = array(
			'friend_id'=>$input['user_id'],
			'user_id'=>$input['friend_id'],
			'status'=>'Accepted'
        );	
	
        Friend::where(['friend_id'=>$input['friend_id']])
        			->where(['user_id'=>$input['user_id']])
        			->update(['status'=>'Accepted']);

		Friend::insert($data);

	}

	/*
	* Reject request from another user.
	*
	**/
	public function reject()
	{

       $input=Input::all();

       Friend::where(['friend_id'=>$input['friend_id']])
				->where(['user_id'=>$input['user_id']])
				->update(['status'=>'Rejected']);

	}


	/*
	* Resend request to user.
	*
	**/
	public function resend()
	{

		$input=Input::all();
		Friend::where(['friend_id'=>$input['friend_id']])
			->where(['user_id'=>$input['user_id']])
			->update(['status'=>'Pending']);       

	}


	/*
	* Remove request from another user.
	*
	**/
	public function remove()
	{

		$input=Input::all();
	
		Friend::where(['friend_id'=>$input['friend_id']])
				->where(['user_id'=>$input['user_id']])
				->update(['status'=>'Rejected']); 

		Friend::where(['friend_id'=>$input['user_id']])
				->where(['user_id'=>$input['friend_id']])
				->update(['status'=>'Rejected']);      

		Friend::where(['friend_id'=>$input['user_id']])->where(['status'=>'Rejected'])->delete();
		
	}


 
	//Get post box
	public function getPostBox()
	{

		$arguments = Input::all();

		$feeddata = Feed::with('comments')->with('likes')->with('user')->where('id', '=', $arguments['feed_id'])->get()->first();
 
		return view('dashboard.getpostbox')
					->with('feeddata', $feeddata);

 	}


	//delete post
	public function deletepost()
	{

		$postId = Input::get('postId');
		$userId = Auth::User()->id;
		
		$newsFeed = Feed::where('id', '=', $postId)->where('user_by', '=', $userId)->delete();
		return $newsFeed; 

 	}


	//delete comments
	public function deletecomments()
	{

		$commentId = Input::get('commentId');
		$userId = Auth::User()->id;

		$newsFeed = Feed::where('id', '=', $commentId)->where('user_by', '=', $userId)->delete();
		return $newsFeed; 

 	}
 	



}
