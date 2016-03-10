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
				$arguments['user_by'] = $user->id;
	
				if( empty($arguments['message']) && empty($arguments['image']))
					throw new Exception('Post something to update.');

				$file = Input::file('image');

				if( isset($arguments['image']) && $file != null ){

					$image_name = time()."_POST_".strtoupper($file->getClientOriginalName());
					$arguments['image'] = $image_name;
					$file->move('uploads', $image_name);

				}

				$feed = $model->create( $arguments );
				
				if( !$feed )
					throw new Exception('Something went wrong.');

				$name = Auth::User()->first_name.' '.Auth::User()->last_name;
				$time = $feed->updated_at->diffForHumans();
				$picture = $feed->image;
				$message = $feed->message;

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
				<div class="post-header">
					<div class="row">
						<div class="col-md-7">
							<a href="#" title="" class="user-thumb-link">
								<span class="small-thumb" style="background: url('images/user-thumb.jpg');"></span>
								$name
							</a>
						</div>
						<div class="col-md-5">
							<div class="post-time text-right">
								<ul>
									<li>
										<span class="icon flaticon-time">
											$time
										</span>
									</li>
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
										<span class="countspan"></span>
										<span>Like</span>
									</label>
								</div>
							</li>
							<li>
								<a href="#" class="popups">
									<span class="icon flaticon-interface-1"></span> 
									<span class="commentcount">Comment</span>
								</a>
							</li>
						</ul>
					</div>
					<div class="post-comment-cont">
						<div class="post-comment">
							<div class="row">
								<div class="col-md-10">
									<textarea type="text" class="form-control comment-field" placeholder="Type here..."></textarea>
								</div>
								<div class="col-md-2">
									<button type="button" class="btn btn-primary btn-full comment">Post</button>
								</div>
							</div>
						</div>
						<div class="comments-list">
							<ul>
							</ul>
						</div>
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

		try{

			$likes = Like::where('feed_id', '=', $arguments['feed_id'])->get();
			$comments = Comment::where('feed_id', '=', $arguments['feed_id'])->get();
			$feed = Feed::where('id', '=', $arguments['feed_id'])->get();

			$user = User::find($feed[0]->user_by);
			$feedPostUserName = $user->first_name.' '.$user->last_name;
 
			$image = $feed[0]->image;
			$message = $feed[0]->message;
			$time = $feed->updated_at->format('h:i A');

			$likedata = Like::where(['user_id' => Auth::User()->id, 'feed_id' => $arguments['feed_id']])->get(); 
			$checked = isset($likedata[0]) ? 'checked' : '';

			$likescountData = Like::where(['feed_id' => $arguments['feed_id']])->get();
			$likescount = count($likescountData);
			if($likescount > 0){
				$likespan = "<span>$likescount Likes</span>";
			}else{
				$likespan = "<span>Like</span>";
			}

			$commentscountData = Comment::where(['feed_id' => $arguments['feed_id']])->get();
			$commentscount = count($commentscountData);
			if($commentscount > 0){
				$commentspan = "<span>$commentscount Comments</span>";
			}else{
				$commentspan = "<span>Comment</span>";
			}

			// echo '<pre>';print_r();die('pawan');

$commentshtml = "";
foreach($comments as $data){

	$commentedBy = $data->commented_by;
	$username = User::find($commentedBy);
	$name = $username->first_name.' '.$username->last_name;

$commentshtml .= '
<li>
	<span class="user-thumb" style="background: url(images/user-thumb.jpg)"></span>
	<div class="comment-title-cont">
		<div class="row">
			<div class="col-sm-6">
				<a href="#" title="" class="user-link">'.$name.'</a>
			</div>
			<div class="col-sm-6">
				<div class="comment-time text-right">'.$time.'</div>
			</div>
		</div>
	</div>
	<div class="comment-text">'.$data->comments.'</div>
</li>';
}

$getcomment = <<<getcomment

<div id="AllComment" class="post-list">
	<div class="container">
		<div class="row">
			<div class="col-sm-8 pop-post-left-side">
				<div class="single-post">
					<div class="pop-post-header">
						<div class="post-header">
							<div class="row">
								<div class="col-md-7">
									<a href="#" title="" class="user-thumb-link">
										<span class="small-thumb" style="background: url('images/user-thumb.jpg');"></span>
										$feedPostUserName
									</a>
								</div>
								<div class="col-md-5">
									<div class="post-time text-right">
										<ul>
											<li><span class="icon flaticon-time">$time</span></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<div class="pop-post-text clearfix postsajax">
							<p class="postsonajax">$message</p>
						</div>
					</div>
					
					<div class="post-data pop-post-img">
						<img src="images/user-thumb.jpg" class="pop-img">
					</div>
					<div class="post-footer pop-post-footer">
						<div class="post-actions">
							<ul>
								<li>
									<div class="like-cont">
										<input type="checkbox" name="checkboxG4" id="checkboxG4" class="css-checkbox" $checked/>
										<label for="checkboxG4" class="css-label">
											$likespan
										</label>
									</div>
								</li>
								<li>
									<span class="icon flaticon-interface-1">
									</span>
								 	$commentspan
								 </li>
							</ul>
						</div><!--/post actions-->
					</div><!--pop post footer-->
				</div><!--/single post-->
			</div>
			<div class="col-sm-4 pop-comment-side-outer">
				<div class="pop-comment-side">
					<div class="post-comment-cont">
						<div class="comments-list">
							<ul>
							$commentshtml
							</ul>
						</div>
					</div>
				</div>

			<div class="pop-post-comment post-comment">
				<div class="emoji-field-cont cmnt-field-cont">
					<textarea type="text" class="form-control comment-field" data-emojiable="true" placeholder="Type here..."></textarea>
					<input type="file" class="filestyle" data-input="false" data-iconName="flaticon-clip"  data-buttonName="btn-icon btn-cmnt-attach" multiple="multiple">
					<!-- <button type="button" class="btn-icon btn-cmnt-attach"><i class="flaticon-clip"></i></button> -->
					<button type="button" class="btn-icon btn-cmnt"><i class="flaticon-letter"></i></button>
				</div>
			</div>

			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="/js/bootstrap-filestyle.min.js"></script>
<script src="/lib/js/nanoscroller.min.js"></script>
<script src="/lib/js/tether.min.js"></script>
<script src="/lib/js/config.js"></script>
<script src="/lib/js/util.js"></script>
<script src="/lib/js/jquery.emojiarea.js"></script>
<script src="/lib/js/emoji-picker.js"></script>
<script src="/js/jquery.nicescroll.min.js"></script>
<script>
$('.pop-comment-side .post-comment-cont').niceScroll();
var postsonajax = $('.postsonajax').html();
if(postsonajax == ''){
	$('.postsajax').remove();
}

	//Emoji Picker
	$(function() {
      // Initializes and creates emoji set from sprite sheet
      window.emojiPicker = new EmojiPicker({
        emojiable_selector: '[data-emojiable=true]',
        assetsPath: 'lib/img/',
        popupButtonClasses: 'fa fa-smile-o'
      });
      // Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
      // You may want to delay this step if you have dynamically created input fields that appear later in the loading process
      // It can be called as many times as necessary; previously converted input fields will not be converted again
      window.emojiPicker.discover();
    });
</script>
getcomment;

			// print_r($arguments['feed_id']);die;		
			echo $getcomment;

		}catch(Exception $e){
			return $e->getMessage();
		}

		exit;

 	}



	public function postcomment()
	{
			try
			{
				$arguments = Input::all();
				// print_r($arguments);die('pawan');
				$comments = new Comment;

				$validator = Validator::make($arguments, $comments->rules, $comments->messages);

				if($validator->fails()) {
					
					throw new Exception($this->getError($validator));

				}else{

					$user = User::find($arguments['commented_by']);
					if( !$user )
						throw new Exception('No record found of the user.');						

					$feed = Feed::find($arguments['feed_id']);
					if(!$feed)
						throw new Exception('The post may have expired or does not exist.');
					
					$comments = new Comment;
					$model = $comments->create($arguments);

					$userid = Auth::User()->id;
					$username = Auth::User()->first_name.' '.Auth::User()->last_name;
					$comment = $model->comments;
					$time = $model->updated_at->format('h:i A');

$variable = array();				
$variable['comment'] = <<<comments
<li>
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

					// echo $comment;
				}

			}catch(Exception $e){

				return $e->getMessage();

			}

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


	/**
	*	Get friend lists ajax call handling.
	*	Ajaxcontroller@getfriendslist
	*/
	public function getfriendslist()
	{

		$input = Input::get('type');;

		$model = Friend::with('user')->with('friends')->where( function( $query ) use ( $input ) {
					self::queryBuilder( $query, $input );
				})->get()->toArray();
 
		$litag = array();
		foreach ($model as $key => $value) {

			if($value['friend_id'] == Auth::User()->id)
				$name = $value['user']['first_name'].' '.$value['user']['last_name'];
			else
				$name = $value['friends']['first_name'].' '.$value['friends']['last_name'];


			if($input == 'sent'){
				$permissionbutton = '<div class="text-right">
								<button class="btn btn-primary btn-full" type="button">Sent Request</button>
							</div>';
			}elseif($input == 'recieved'){
				$permissionbutton = '<div class="row">
								<div class="col-sm-6">
									<button class="btn btn-primary btn-full" type="button" class="accept">Accept</button>
								</div>
								<div class="col-sm-6">
									<button class="btn btn-default btn-full" type="button" class="decline">Decline</button>
								</div>
							</div>';
			}elseif($input == 'current'){
				$permissionbutton = '<div class="text-right">
								<button class="btn btn-default btn-full" type="button" class="remove">Remove</button>
							</div>';
			}elseif($input == 'all'){

				if(($value['status'] == 'Pending') && ($value['user_id'] == Auth::User()->id)){
					$permissionbutton = '<div class="row">
							<div class="col-sm-6">
								<button class="btn btn-primary btn-full" type="button" class="accept">Accept</button>
							</div>
							<div class="col-sm-6">
								<button class="btn btn-default btn-full" type="button" class="decline">Decline</button>
							</div>
						</div>';
				}elseif(($value['status'] == 'Pending') && ($value['friend_id'] == Auth::User()->id)){ 
					$permissionbutton = '<div class="text-right">
							<button class="btn btn-primary btn-full" type="button">Sent Request</button>
						</div>';
				}elseif(($value['status'] == 'Accepted') && ($value['user_id'] == Auth::User()->id)){ 
					$permissionbutton = '<div class="text-right">
						<button class="btn btn-default btn-full" type="button" class="remove">Remove</button>
					</div>';
				}

			}

			$litag[] = '<li>
							<div class="row">
								<div class="col-sm-6">
									<div class="user-cont">
										<a title="" href="#">
											<span style="background: url(images/user-thumb.jpg);" class="user-thumb">
											</span>
											'.$name.'
										</a>
									</div>
								</div>
								<div class="col-sm-6">
									'.$permissionbutton.'
								</div>
							</div>
						</li>';

		}

		$lisdata = array();
		$lisdata['data'] = implode(' ', $litag);
		$lisdata['type'] = ucwords($input);
		// print_r($model);exit;
		return $lisdata;
 
	}


	/**
	*	Query builderfor friend lists ajax call handling.
	*	Ajaxcontroller@queryBuilder
	*/
	public function queryBuilder( &$query, $input ){
		if($input == 'all'){
            $query->where('user_id', '=', Auth::User()->id);
            $query->orWhere('friend_id', '=', Auth::User()->id);
        }elseif($input == 'sent'){
            $query->where('user_id', '=', Auth::User()->id);
            $query->where('status', '=', 'Pending');
        }elseif($input == 'recieved'){
            $query->where('friend_id', '=', Auth::User()->id);
            $query->where('status', '=', 'Pending');
        }elseif($input == 'current'){
            $query->where('user_id', '=', Auth::User()->id)->where('status', '=', 'Accepted');
            $query->orWhere('friend_id', '=', Auth::User()->id)->where('status', '=', 'Accepted');
        } 
	}


	/**
	*	Group chatrooms ajax call handling.
	*	Ajaxcontroller@groupchatrooms
	*/
	public function groupchatrooms()
	{
		return view('dashboard.groupchatrooms');
	}


	/**
	*	Group sub chatrooms ajax call handling.
	*	Ajaxcontroller@groupchatrooms
	*/
	public function subgroupchats()
	{

		$input = Input::get('groupid');
		$dataval = Input::get('dataval');
		
		if(!empty($input)){
			$data = DB::table('categories')->where(['parent_id' => $input])->where(['status' => 'Active'])->get();

			if( !empty( $data ) ){
				$subgroups = $data;
			}
		}
		
		return view('dashboard.subgroupchats')
				->with('subgroups', $subgroups)
				->with('dataval', $dataval);
	}


	/**
	*	Enter chatrooms ajax call handling.
	*	Ajaxcontroller@enterchatroom
	*/
	public function enterchatroom()
	{

		$arg = Input::get('dataval');

		$defGroup = array();
		$defGroup['group_name'] = $arg;
		$defGroup['group_by'] = Auth::User()->id;
		
		$model = new DefaultGroup;

		$updatecheck = $model->where('group_name', $arg)
					->where('group_by', Auth::User()->id)
					->get()->toArray();
		
		if(empty($updatecheck)){
			$model = new DefaultGroup;
			$response = $model->create($defGroup);
		}else{
			$id = $updatecheck[0]['id'];
			$response = $model->find($id);
		}
		
		//Get users of this group
		$usersData = $model->with('user')->where('group_name', $arg)->get()->toArray();
		// echo '<pre>';print_r($userids);die;

		return view('dashboard.enterchatroom')
					->with('groupname', $response)
					->with('userdata', $usersData);
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


}
