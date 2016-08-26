<?php

namespace App\Http\Controllers;
use App\State, App\City, App\Like, App\Comment, App\User, App\Friend, DB,App\EducationDetails, App\Country,App\Broadcast, App\JobArea, App\JobCategory
,App\BroadcastMessages,App\Group,App\GroupMembers,App\BroadcastMembers,App\Forums,App\ForumPost,App\ForumLikes,App\ForumReply,App\ForumReplyLikes,App\ForumReplyComments;

// use Illuminate\Http\Request;
use Session, Validator, Cookie;
use App\Http\Requests;
use App\DefaultGroup;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\Feed, Auth, Mail, File;

use \Exception,Route;
use App\Library\Converse, App\Library\Functions, Config;
use Illuminate\Support\Facades\Request, Intervention\Image\Facades\Image;

class AjaxController extends Controller
{

	public function login()
	{
		
		$arguments = Input::all();
		$email = Input::get('email');
		$password = Input::get('password');
		$user = new User();
		$data = '';
		if(isset($arguments['log']))
			$log = true;
		else
			$log = false;

		$validator = Validator::make($arguments, 
							['email' => 'required|email',
							'password' => 'required'],
							
							['email.required' => 'Please enter email address',
							'email.email' => 'Please enter valid email',
							'password.required' => 'Please enter password']
						);

		if($validator->fails()) {					
			$error = $validator->errors()->getMessages();	

			$emailValidate = isset($error['email'][0]) ? $error['email'][0] : '';
			$passwordValidate = isset($error['password'][0]) ? $error['password'][0] : '';

			if( $emailValidate != null && $passwordValidate != null ) {

				$err = array(
						'email' => $emailValidate,
						'password' => $passwordValidate
					);

			}elseif ( $emailValidate != null ) {
				
				$err = array(
						'email' => $emailValidate
					);

			}else{

				$err = array(
						'password' => $passwordValidate
					);

			}

			$data = json_encode($err);

		} else {

			if(Auth::attempt(['email' => $email, 'password'=>$password , 'is_email_verified'=>
				'Y'], $log)) {
				$data = json_encode( array('status' => 'success') );
			}	
			else
			{
				$verified = User::where('email',$email)->value('is_email_verified');
				if($verified == 'N') {
					$data = json_encode( array('status' => 'verification') );
				} else {
					$data = json_encode( array('status' => 'invalid') );
				}
			}

		}
		return $data;
	}


	//Handling posts
	public function posts()
	{
		try
		{
			$arguments = Input::all();
			// $maxFileSize = Config::get('constants.max_upload_filesize');
			// print_r($arguments);exit;
			$user = Auth::User();
			$model = new Feed;

			if( $arguments ){

				$user = Auth::User();				
				$userid = $user->id;
				$arguments['user_by'] = $user->id;
	
				if( empty(trim($arguments['message'])) && empty($arguments['image']))
					throw new Exception('Post something to update.');

				$file = Input::file('image');
				$bytes = File::size($file);
				$maxFileSize = 4194304;

				if($bytes < $maxFileSize){
					if( isset($arguments['image']) && $file != null){

						$image_name = time()."_POST_".strtoupper($file->getClientOriginalName());
						$arguments['image'] = $image_name;
						
						$imageRealPath 	= 	$file->getRealPath();
						$img = Image::make($imageRealPath);
						$img->save( public_path('uploads/'). $image_name );

						/** resize image **/
						list($ImageWidth, $ImageHeight) = getimagesize( public_path('uploads/'.$image_name ) );

						$NeedImage = 350 / 200;
						$ImageHave = $ImageWidth / $ImageHeight;
						if( $ImageHeight <= 200 && $ImageWidth <= 350 ){
							$SmallSize = $ImageHeight;
							$ByResize = 'height';
						} else if( $ImageHave < $NeedImage ){
							$SmallSize = 200;
							$ByResize = 'height';
						} else {
							$SmallSize = 350;
							$ByResize = 'width';
						}
						$this->resizeImageByCompare( Input::file('image'), $SmallSize, $ByResize, public_path('uploads/thumb-small/') , $image_name ); 

						$NeedImage = 680 / 300;
						$ImageHave = $ImageWidth / $ImageHeight;
						if( $ImageHeight <= 300 && $ImageWidth <= 680 ){
							$LargeSize = $ImageHeight;
							$ByLargeResize = 'height';
						} else if( $ImageHave < $NeedImage ){
							$LargeSize = 300;
							$ByLargeResize = 'height';
						} else {
							$LargeSize = 680;
							$ByLargeResize = 'width';
						}
						$this->resizeImageByCompare( Input::file('image'), $LargeSize, $ByLargeResize, public_path('uploads/thumb-large/') , $image_name );
						
					}
				}else{
					throw new Exception("Max upload size is 4 MB.", 1);
				}
				// $arguments['message'] = nl2br($arguments['message']);

				$feed = $model->create( $arguments );
				
				if( !$feed )
					throw new Exception('Something went wrong.');

				if( $arguments['message'] && empty( $arguments['image'] ) )
					$popupclass = 'postpopupajax';
				elseif( $arguments['image'] && empty( $arguments['message'] ) )
					$popupclass = 'popupajax';
				else
					$popupclass = 'popupajax';
		 
				return view('ajax.returnpost')
						->with('postdata', $feed)
						->with('user', $user)
						->with('popupclass', $popupclass);

			}

		}catch( Exception $e ){

			return $e->getMessage();

		}		

		exit;
	}

	private function resizeImageByCompare($image, $size , $By ,$path, $imagename = '')
    {
    	try 
    	{
    		$extension 		= 	$image->getClientOriginalExtension();
    		$imageRealPath 	= 	$image->getRealPath();
    		if( empty($imagename) && $imagename == '' ){
    			$thumbName 		= 	$image->getClientOriginalName();
	    	} else {
	    		$thumbName = $imagename;
	    	}
	    
	    	$img = Image::make($imageRealPath); // use this if you want facade style code
	    	
	    	if($By == 'width' ){
	    		$img->resize(intval($size),null ,function($constraint) {
		    		 $constraint->aspectRatio();
		    	});
	    	} else {
		    	$img->resize(null, intval($size),function($constraint) {
		    		 $constraint->aspectRatio();
		    	});
	    	}
	    	return $img->save($path. $thumbName);
    	}
    	catch(Exception $e)
    	{
    		return false;
    	}
    }

	public function editposts()
	{
		$arguments = Input::all();
		$user = Auth::User();
		$file = Input::file('image');


		if(!(isset($arguments['imagecheck'])) && $file==null && $arguments['message']==''){
			exit;
		}
		if( isset($arguments['image']) && $file != null ){

			$image_name = time()."_POST_".strtoupper($file->getClientOriginalName());
			$arguments['image'] = $image_name;
			$file->move('uploads', $image_name);

		}else{
			unset($arguments['image']);
		}
		
		$arguments['message'] = trim($arguments['message']);

		$newsFeed = Feed::find($arguments['id']);
		$newsFeed->fill($arguments);
		$saved = $newsFeed->push();

		$postdata = Feed::where('id', $arguments['id'])->select('image', 'message', 'id')->get()->first()->toArray();
		$postdata['message'] = nl2br($postdata['message']);

		echo json_encode($postdata);

		exit;
		
	}

	public function editcomments()
	{
		$arguments = Input::all();
		$user = Auth::User();
		if($arguments['comments']!='')
		{

			$arguments['comments'] = trim($arguments['comments']);

			$comments = Comment::find($arguments['id']);
			$comments->fill($arguments);
			$saved = $comments->push();

			$commentdata = Comment::where('id', $arguments['id'])->get()->first()->toArray();
			$commentdata['comments'] = nl2br($commentdata['comments']);

			echo json_encode($commentdata);

			exit;
		}
	}


	//Get comment box
	public function getCommentBox()
	{

		$arguments = Input::all();

		$feeddata = Feed::with('comments')->with('likes')->with('user')->where('id', '=', $arguments['feed_id'])->get()->first();
// print_r($feeddata);die;
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
		$user_picture = userImage(Auth::User());
		$username = Auth::User()->first_name.' '.Auth::User()->last_name;
		$comment = nl2br($model->comments);
		$time = $model->updated_at->format('h:i A').' (UTC)';
		$date = $model->updated_at->format('d M Y');
		$id = $model->id;

$variable = array();				
$variable['comment'] = <<<comments
<li data-value="$id" id="post_$id">
	<button type="button" class="p-del-btn comment-delete" ><span class="glyphicon glyphicon-remove"></span></button>
		<br>
		<button type="button" class="p-edit-btn edit-comment" data-toggle="modal" title="Edit" data-target=".edit-comment-popup"><i class="fa fa-pencil"></i></button>

	<span class="user-thumb" style="background: url($user_picture);"></span>
	<div class="cmt-data">
	<div class="comment-title-cont">
		<div class="row">
			<div class="col-sm-6">
				<a href="profile/$userid" title="" class="user-link">$username</a>
			</div>
			<div class="col-sm-6">
				<div class="text-right">
					<div class="date-time-list">
						<span><div class="comment-time text-right">$date</div></span>
						<span><div class="comment-time text-right">$time</div></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="comment-text">$comment</div>
	</div>
</li>
comments;

		$count = Comment::where(['feed_id' => $arguments['feed_id']])->get();	
		$variable['count'] = count($count);
		$data = json_encode($variable);
		echo $data;

		exit;
	}

	
	public function getGroupDetail(){
		$arguments = Input::all();
		$Image = '';
		if( isset($arguments['group_jid'])  ) {
			$xmpp = $arguments['group_jid'];
			$Group = Group::where('group_jid', $xmpp)->first();
			$Image = '/images/post-img-big.jpg';
			$Title = $xmpp;
			if( $Group ){
				$Status = 1;
				if( isset($Group->title) && !empty($Group->title) ) {
					$Title = $Group->title;
				}
				if( isset($Group->picture) && !empty($Group->picture) ) {
					$Image = '/uploads/'.$Group->picture;
				}
			} else {
				$Status = 0;
			}
		}
		echo json_encode( array( 'image' => $Image , 'title' => $Title, 'status' => $Status ) );
		exit();
	}


	public function getxmppuser(){

		try{
			$status=0;
			$authuser = Auth::User();
			// echo '<pre>';print_r($authuser);die;
			if ( !empty($authuser->xmpp_username) && !empty($authuser->xmpp_password) ) 
			{
				$response = Converse::ejabberdConnect( $authuser );				
	               
			}else{
				$xmppUserDetails = Converse::createUserXmppDetails($authuser);
	            $responseConverse = Converse::register($xmppUserDetails->xmpp_username, $xmppUserDetails->xmpp_password);
				$response = Converse::ejabberdConnect( $xmppUserDetails );
			}

			
		}catch(Exception $e){
			 $responseConverse = Converse::register($authuser->xmpp_username, $authuser->xmpp_password);
					$response = Converse::ejabberdConnect( $authuser );

		}

		if(is_array($response) && count($response) > 0)
		{
			$status = 1;
		}

		$response['status']=$status;	  
		// echo json_encode($sessionInfo); 
		// exit;
		return $response;

 	}
	/** get user profile image and name by user jid **/
	public function profileNameImage(){
		$input = Input::all();
		if( isset($input['user_jid']) && !empty($input['user_jid']) ){
			$UserJid = $input['user_jid'];
			$UserDetails = User::where('xmpp_username',$UserJid)->select('picture','first_name', 'last_name')->first();
			if( $UserDetails ){
				if( isset($UserDetails->picture) && !empty($UserDetails->picture) ){
					$Image = $UserDetails->picture;
				} else {
					$Image = 'user-thumb.jpg';
				}
				if( isset($UserDetails->first_name) && !empty($UserDetails->first_name) ){
					$Name = $UserDetails->first_name.' '.$UserDetails->last_name;
				} else {
					$Name = $input['user_jid'];
				}
			} else {
				$Image = 'user-thumb.jpg';
				$Name = $input['user_jid'];
			}
			echo json_encode(array( 'image'=>$Image, 'name' => $Name ));
		}
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
		$model=array();


		$model=Friend::with('user')->with('friends')->with('user')->where( function( $query ) use ( $input ) {
				    self::queryBuilder( $query, $input );
					})->orderby('id','ASC')->take(10)->get();
		$count = Friend::with('user')->with('friends')->with('user')->where( function( $query ) use ( $input ) {
				    self::queryBuilder( $query, $input );
					})->get()->count();
		$model = $model->toArray();
		$authid = Auth::User()->id;

		$friendcount = Friend::where('user_id',$authid)->where('status','Accepted')->get()->count();
		$recievecount = Friend::where('friend_id',$authid)->where('status','Pending')->get()->count();
		$sentcount = Friend::where('user_id',$authid)->where('status','Pending')->get()->count();

		$returnHTML = view('dashboard.getfriendslist')
					->with('model',$model)
					->with('count',$count)
					->render();

		$countarr = ['friend'=>$friendcount,
					 'recieve'=>$recievecount,
					 'sent'=>$sentcount,
					 'view'=>$returnHTML];

		// return response()->json(array('success' => true, 'html'=>$returnHTML));

		print_r(json_encode($countarr));
		// 			 //echo $count;
		// return view('dashboard.getfriendslist')
		// 			->with('model',$model)
		// 			->with('count',$count);

 
	}


	/**
	*	View more posts ajax call handling.
	*	Ajaxcontroller@viewMorePosts
	*/
	public function viewMorePosts()
	{
        $per_page = 5;
        $page = Input::get('pageid');
        $offset = ($page - 1) * $per_page;
		
		// Get total pages
		$totalRecords = Feed::with('likesCount')->with('commentsCount')->with('user')->with('likes')->with('comments')
            ->whereIn('user_by', Friend::where('user_id', '=', Auth::User()->id)
                    ->where('status', '=', 'Accepted')
                    ->pluck('friend_id')
                    ->toArray())
            ->orWhere('user_by', '=', Auth::User()->id)
            ->count();
        $pages = ceil($totalRecords / $per_page);
        $existmore = ($page == $pages) ? 0 : 1;

        $feeds = Feed::with('likesCount')->with('commentsCount')->with('user')->with('likes')->with('comments')
            ->whereIn('user_by', Friend::where('user_id', '=', Auth::User()->id)
                    ->where('status', '=', 'Accepted')
                    ->pluck('friend_id')
                    ->toArray())
            ->orWhere('user_by', '=', Auth::User()->id)
            ->orderBy('news_feed.id','DESC')
            ->skip($offset)
            ->take($per_page)
            ->get();

		$html = view('dashboard.newsfeed')->with('feeds', $feeds)->render();
		return response()->json(['existmore' => $existmore, 'html' => $html]);

    }


	/**
	*	View more friends ajax call handling.
	*	Ajaxcontroller@viewMoreFriends
	*/
	public function viewMoreFriends()
	{
		$per_page = 10;
		//print_r(Input::all());
		$page 	= Input::get('pageid');
		$type 	= Input::get('reqType');
		$LastID = Input::get('lastid');
		$offset = ($page - 1) * $per_page;
		$user_id = Auth::User()->id;

		switch ($type) {
			case 'all':
				$model = User::where('id', '!=', $user_id)
							->where('id', '>', $LastID)
							//->skip($offset)
							->take($per_page)
							->orderby('id','ASC')
				            ->get()->toArray();
				break;
			case 'sent':
				$modeldata = Friend::with('user')->with('friends')->with('user')
							->where('user_id', '=', $user_id)
							->where('status', '=', 'Pending')
							->where('id', '>', $LastID)
							->orderby('id','ASC')
							//->skip($offset)
							->take($per_page)		
							->get()->toArray();
				break;
			case 'recieved':
				$modeldata = Friend::with('user')->with('friends')->with('user')
				            ->where('friend_id', '=', $user_id)
				            ->where('status', '=', 'Pending')
				            ->where('id', '>', $LastID)
				            ->orderby('id','ASC')
							//->skip($offset)
							->take($per_page)
				            ->get()->toArray();
				break;
			case 'current':
				$modeldata = Friend::with('user')->with('friends')->with('user')
							->where('friend_id', '=', $user_id)
				            ->where('status', '=', 'Accepted')
				            ->where('id', '>', $LastID)
				            ->orderby('id','ASC')
							//->skip($offset)
							->take($per_page)
				            ->get()->toArray();
				break;
		}

		// echo '<pre>';print_r(count($model));die;

		if($type == 'all') {
			$model1 = $model;
			$modelcount = count($model);
		}else{
			$model = $modeldata;
			$modelcount = count($modeldata);
			$model1 = '';
		}

		if($model || $model1)
			return view('dashboard.getfriendslist' )
						->with('model',$model)
						->with('count', $modelcount);
		else
			echo 'No more results';
 
	}



	/**
	*	Query builderfor friend lists ajax call handling.
	*	Ajaxcontroller@queryBuilder
	*/
	public function queryBuilder( &$query, $input ){
		$user_id=Auth::User()->id;
		if($input == 'sent'){
            $query->where('user_id', '=', $user_id);
            $query->where('status', '=', 'Pending');
        }elseif($input == 'recieved'){
            $query->where('friend_id', '=', $user_id);
            $query->where('status', '=', 'Pending');
        }elseif($input == 'current'){

            $query->where('friend_id', '=', $user_id);
            $query->where('status', '=', 'Accepted');

            // $query->where('user_id', '=', $user_id)->where('status', '=', 'Accepted');
           // $query->where('friend_id', '=', $user_id)->where('status', '=', 'Accepted');

        } 
	}



	/**
	*	Remove education details on ajax call handling.
	*	Ajaxcontroller@removeEducationDetails
	*/
	public function removeEducationDetails()
	{
		
		$educationid = Input::get('educationid');
		
		if($educationid)
			$del = EducationDetails::find($educationid)->delete();
/*		$countryid = Country::where(['country_name' => $input['countryId']])->value('country_id');		
		$statequeries = State::where(['country_id' => $countryid])->get();		
		$states = array('<option value="">State</option>');
		foreach($statequeries as $query){			
			$states[] = '<option value="'.$query->state_name.'">'.$query->state_name.'</option>';
		}		
		echo implode('',$states);*/
	}

 
	/**
	*	Get states ajax call handling.
	*	Ajaxcontroller@getStates
	*/
	public function getStates()
	{
		$input = Input::all();
		
		$countryid = Country::where(['country_name' => $input['countryId']])->value('country_id');		
		$statequeries = State::where(['country_id' => $countryid])->get();		
		$states = array('<option value="">Select State</option>');
		foreach($statequeries as $query){			
			$states[] = '<option value="'.$query->state_name.'">'.$query->state_name.'</option>';
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
		// echo $input['stateId'];die;
		$cityid = State::where(['state_name' => $input['stateId']])->value('state_id');
		$cityqueries = City::where(['state_id' => $cityid])->get();
		$city = array('<option value="">Select City</option>');
		foreach($cityqueries as $query){			
			$city[] = '<option value="'.$query->city_name.'">'.$query->city_name.'</option>';
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
				$count = Like::where(['feed_id' => $arguments['feed_id']])->get();
				// print_r();die;
				// $likes[] = count($count);
// 
				$likecheck = Like::where('feed_id',$arguments['feed_id'])->where('user_id',Auth::User()->id)->value('id');

				$likes['count'] = count($count);
				$likes['likecheck'] = $likecheck;
				echo $likes;
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
		$input = Input::all();

     	$data = array(
			'friend_id'=>$input['user_id'],
			'user_id'=>$input['friend_id'],
			'status'=>'Accepted'
        );	
	
     	$fsearch = Friend::where(['friend_id' => $input['user_id'], 'user_id' => $input['friend_id'], 'status' => 'Accepted'])->first();

		if( !$fsearch )
			Friend::insert($data);

        Friend::where(['friend_id'=>$input['friend_id']])
        			->where(['user_id'=>$input['user_id']])
        			->update(['status'=>'Accepted']);

   		$udetail = User::whereIn('id',$input)->get()->toArray();
   		// echo '<pre>';print_r($udetail);die;
		if(count($udetail)==2){
			$converse = new Converse;
			$converse->addFriend($udetail[0]['xmpp_username'],$udetail[1]['xmpp_username'],
								$udetail[1]['first_name'],$udetail[0]['first_name']);       
		}

		// @ Send push notification on request accept action
		$response = Converse::notifyMe( $input['friend_id'], $input['user_id'], 'accept' );

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
				->delete();

	}


	/*
	* Resend request to user.
	*
	**/
	public function resend() {

		$input=Input::all();

		Friend::where(['friend_id'=>$input['user_id']])
			->where(['user_id'=>$input['friend_id']])
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
				->where(['status'=>'Accepted'])
				->delete(); 

		Friend::where(['friend_id'=>$input['user_id']])
				->where(['user_id'=>$input['friend_id']])
				->where(['status'=>'Accepted'])
				->delete();      
		
		$MyDetails = User::find($input['friend_id']);
		$FriendDetails = User::find($input['user_id']);
		$Message = json_encode( array( 'type' => 'unfriend' , 'message' => 'You removed from friend list.' ) );
		Converse::broadcast($MyDetails->xmpp_username,$FriendDetails->xmpp_username,$Message);
		Converse::broadcast($FriendDetails->xmpp_username, $MyDetails->xmpp_username,$Message);

	}

	/**
	*	Check Friend by Friend xmpp jid.
	*/
	public function isFriendByJid()
	{
		$arguments = Input::all();
		$Status = 0;
		if( !empty($arguments['user_jid']) ){
			$FriendJid = $arguments['user_jid'];
			$userId = Auth::User()->id;
			$FriendID =User::where('xmpp_username',$FriendJid)->value('id');
			$IsFriend = Friend::where(['user_id' => $userId, 'friend_id'=> $FriendID, 'status' => 'Accepted'])->get()->count();
			if( $IsFriend ){
				$Status = 1;
			}
		}
		echo json_encode(array('status'=>$Status));
      	die(); 
	}
	
	/**
	*	Get postbox on ajax call handling.
	*	Ajaxcontroller@getPostBox
	*/
	public function getPostBox()
	{

		$arguments = Input::all();

		$feeddata = Feed::with('comments')->with('likes')->with('user')->where('id', '=', $arguments['feed_id'])->get()->first();
 
		return view('dashboard.getpostbox')
					->with('feeddata', $feeddata);

 	}


	/**
	*	Delete posts on ajax call handling.
	*	Ajaxcontroller@deletepost
	*/
	public function deletepost()
	{

		$postId = Input::get('postId');
		$userId = Auth::User()->id;
		
		$deletePosts = new Converse;
		$newsFeed = $deletePosts->onDeletePosts($postId, $userId);
 
		// return $newsFeed; 

 	}


	/**
	*	Delete comments on ajax call handling.
	*	Ajaxcontroller@deletecomments
	*/
	public function deletecomments()
	{

		$commentId = Input::get('commentId');
		$feedId = Input::get('feedId');
		$userId = Auth::User()->id;

		$newsFeed = Comment::where('id', '=', $commentId)->where('commented_by', '=', $userId)->delete();

		$count = Comment::where('feed_id', '=', $feedId)->count();

		return $count; 

 	}

 	//edit profile
 	public function editProfile()
 	{
 		$input=Input::all();
 		$id=Auth::User()->id;
     //print_r($input);die;
 		$input['state']=State::where('state_id',$input['state'])->value('state_name');
 		$input['city']=City::where('city_id',$input['city'])->value('city_name');
 			
 			$profile = User::find($id);
			$profile->fill($input);
		   	$profile->push();
       if(EducationDetails::find($id))
       		{
    	    $edu=EducationDetails::find($id);
   			$edu->fill($input);
   			$edu->push();

       		}
       else
       		{
       			EducationDetails::insert([
       			 'id' => $id,
       			 'education_level'=>$input['education_level'],
       			 'specialization'=>$input['specialization'],
       			 'graduation_year_from'=>$input['graduation_year_from'],
       			 'graduation_year_to'=>$input['graduation_year_to'],
       			 'currently_studying'=>$input['currently_studying'],
       			 'education_establishment'=>$input['education_establishment'],
       			 'country_of_establishment'=>$input['country_of_establishment'],
       			 'job_area'=>$input['job_area'],
       			 'job_category'=>$input['job_category']
       			 ]);

       		}

 	}
 	
 	public function getJobcategory()
 	{
 		$input=Input::all();
 		// print_r($input);die;

 		$categoryid = JobArea::where('job_area',$input['jobarea'])->value('job_area_id');
 		$data = JobCategory::where(['job_area_id' => $categoryid, 'status' => 1])->pluck('job_category');

		$jcategory = array('<option value="">Category</option>');
		foreach($data as $query){			
			$jcategory[] = '<option value="'.$query.'">'.$query.'</option>';
		}		
		echo implode('',$jcategory);

 	}

	/**
	*	Edit comments on ajax call handling.
	*	Ajaxcontroller@editcomment
	*/
	public function editcomment()
	{	

		$commentid = Input::get('commentId');
		$comment=Comment::where('id',$commentid)->get()->first();

		return view('ajax.editcomment')->with('comment', $comment);

	}


	/**
	*	Edit posts on ajax call handling.
	*	Ajaxcontroller@editpost
	*/
	public function editpost()
	{	

		$postid = Input::get('postid');
		$posts = Feed::where('id', $postid)->get()->first();

		return view('ajax.editpost')->with('posts', $posts);

	}


	/**
	*	Delete confirmation box on ajax call handling.
	*	Ajaxcontroller@editcomment
	*/
	public function deletebox()
	{	

		$commentId = Input::get('commentId');
		$feedId = Input::get('feedId');
		$class = Input::get('class');
		$forumReplyCommentId = Input::get('forumReplyCommentId');
		$replyid = Input::get('forumReplyID');
		
		return view('panels.deletebox')
				->with('commentId', $commentId)
				->with('feedId', $feedId)
				->with('forumReplyCommentId', $forumReplyCommentId)
				->with('replyid',$replyid)
				->with('class', $class);

	}



	public function sendRequest()
	{
		$input=Input::all();
		$id = Auth::User()->id;
		$friend = $input['user_id'];
	
		$status1 = Friend::where('user_id',$id)->where('friend_id',$friend)->value('status');
		$status2 = Friend::where('user_id',$friend)->where('friend_id',$id)->value('status');
		
		if($status1==null && $status2==null){
			Friend::insert(['user_id'=>$id,'friend_id'=>$friend,'status'=>'Pending']);
		}elseif($status1==null){
			Friend::where('user_id',$friend)->where('friend_id',$id)->update(['status'=>'Pending','user_id'=>$id,'friend_id'=>$friend]);
		}elseif($status2==null){
			Friend::where('user_id',$id)->where('friend_id',$friend)->update(['status'=>'Pending','user_id'=>$friend,'friend_id'=>$id]);	
		}

        // @ Send push notification on send request action
		$response = Converse::notifyMe( $id, $friend, 'request' );

	}
	/** Cancel Sent Friend Request **/
	public function cancelRequest()
	{
		$input=Input::all();
		//print_r($input);die;
		if($input['user_id'] == Auth::User()->id)
			Friend::where('user_id',$input['user_id'])->where('friend_id',$input['friend_id'])->delete();
		
		if($input['friend_id'] == Auth::User()->id)
			Friend::where('user_id',$input['friend_id'])->where('friend_id',$input['user_id'])->delete();	

	}

 
public function sendImage(Request $request){
     $status=0;
     $message="";

      $image = $_FILES["chatsendimage"]["name"];
      
      $path=public_path().''.'/uploads/media/chat_images/';

			$uploadedfile = $_FILES['chatsendimage']['tmp_name'];
			$name = $_FILES['chatsendimage']['name'];
			$size = $_FILES['chatsendimage']['size'];
			$valid_formats = array("jpg", "JPG", "jpeg", "JPEG", "png", "PNG", "gif", "GIF");
				if (strlen($name)) {
			list($txt, $ext) = explode(".", $name);
				if (in_array($ext, $valid_formats)) {
			$actual_image_name = "chatimg_" . time() . substr(str_replace(" ", "_", $txt), 5) . "." . $ext;
			$tmp = $uploadedfile;
			$this->resizeImage( Request::file('chatsendimage'), '300' , $path.'thumb/' , $actual_image_name );

			if (move_uploaded_file($tmp, $path . $actual_image_name)) {
		        $data='/uploads/media/chat_images/'.$actual_image_name;
	            $message = $actual_image_name;
	   			$status=1;                
	        } else
	        	$message= "Failed to send try again.";    
       } else
        $message= "Invalid file format.";
      }else {
       $message="Please select an image to send.";
       }
    	echo json_encode(array('status'=>$status,'message'=>$message,'type'=>'image'));
      	die(); 
       }

	private function resizeImage($image, $size , $path, $imagename = '')
    {
    	try 
    	{
    		$extension 		= 	$image->getClientOriginalExtension();
    		$imageRealPath 	= 	$image->getRealPath();
    		if( empty($imagename) && $imagename == '' ){
    			$thumbName 		= 	$image->getClientOriginalName();
	    	} else {
	    		$thumbName = $imagename;
	    	}
	    
	    	$img = Image::make($imageRealPath); // use this if you want facade style code
	    	$img->resize(null, intval($size),function($constraint) {
	    		 $constraint->aspectRatio();
	    	});
	    	
	    	return $img->save($path. $thumbName);
    	}
    	catch(Exception $e)
    	{
    		return false;
    	}
    }


       public function searchfriendlist()
       {
  			$input=Input::get('name');
  			$Format = 'html';
			if( Input::get('format') ){	
				$Format = Input::get('format');
			}
			$friend = Friend::with('friends')->with('user')
					->where('user_id', '=', Auth::User()->id)
					->where('status','Accepted')
					->get()
 					->toArray();
          
            $data=array();
			$count= count( $friend );
			$Status = 0;
			$msg="Sorry, no such friend found.";
			if( $count == 0 ) {
				$data[] = '<li > 
				<span style="color:black;font-weight:bold">'.$msg.'</span>
				</li>';
			} else {
				$Status = 1;
				foreach ($friend as $key => $value) {

					$name=$value['friends']['first_name']." ".$value['friends']['last_name'];
					$xmpp_username="'".$value['friends']['xmpp_username']."'";
					$first_name="'".$value['friends']['first_name']."'";
					
					$msg="No friend found!";

					if (stripos($name, $input) !== false) {
						if( $Format == 'json' ){
						   $user_picture = !empty($value['friends']['picture']) ?$value['friends']['picture'] :'user-thumb.jpg';
						   $data[] = array( 'xmpp' => $value['friends']['xmpp_username'], 'name' => $name, 'image' => $user_picture );
						} else {
							$user_picture = !empty($value['friends']['picture']) ? url('uploads/user_img/'.$value['friends']['picture']) : url('/images/user-thumb.jpg');
							 $data[] = '<li > 
							<a href="javascript:void(0)" title="" class="list" onclick="openChatbox('.$xmpp_username.','.$first_name.');">
								<span class="chat-thumb"style="background: url('.$user_picture.');"></span>
								<span class="title">'.$name.'</span>
							</a>
							</li>';
						}
					}
				}
			}

			if( $Format == 'json' ){
				echo json_encode(array('status'=>$Status,'data'=>$data));
       			die(); 
			} else {
				$html = implode('',$data);
			}
		echo $html;

	}


	public function searchTabFriend()
	{
		$input=Input::all();
		$count = 0;
		$type=$input['type'];
		$name=$input['name'];
		$model1=array();
		$model= '';
		$model2=array();

		if($type=='all'){
			$model1=User::where('id','!=',Auth::User()->id)->where('first_name','LIKE','%'.$name.'%')->take(10)->get()->toArray();
		}else{
			$model=Friend::with('user')->with('friends')->with('user')->where( function( $query ) use ( $type ) {
							self::queryBuilder( $query, $type );
						})->get();
			//$model=$model->take(10);
			$model = $model->toArray();
		}

		if($model!=null){
			foreach ($model as $key => $value) {

				if($type == 'current'|| $type == 'recieved')
					$n=$value['user']['first_name']." ".$value['user']['last_name'];
				else
					$n=$value['friends']['first_name']." ".$value['friends']['last_name'];

				if (stripos($n, $name) !== false) {
					$model2[] =$value;
					
				}
			}
		}
		//print_r($model2);die;
		$count = count($model2);
		$model2 = array_slice($model2, 0, 10);
		return view('dashboard.friendlist2')
					->with('model',$model2)
					->with('model1',$model1)
					->with('count',$count)
					->with('keyword',$name);
	}

	public function searchTabFriendMore()
	{
		$input = Input::all();
		$page = $input['pageid'];
		$type=$input['type'];
		$name=$input['name'];

		$per_page = 10;
		$offset = ($page - 1) * $per_page;

		$model1=array();
		$model= '';
		$model2=array();

		if($type=='all'){
			$model1=User::where('id','!=',Auth::User()->id)->where('first_name','LIKE','%'.$name.'%')->take(10)->get()->toArray();
		}else{
			$model=Friend::with('user')->with('friends')->with('user')->where( function( $query ) use ( $type ) {
							self::queryBuilder( $query, $type );
						})->get();
			$model = $model->toArray();
		}

		if($model!=null){
			foreach ($model as $key => $value) {

				if($type == 'current'|| $type == 'recieved')
					$n=$value['user']['first_name']." ".$value['user']['last_name'];
				else
					$n=$value['friends']['first_name']." ".$value['friends']['last_name'];

				if (stripos($n, $name) !== false) {
					$model2[] =$value;
					
				}
			}
		}
		$model2 = array_slice($model2, $offset, $offset+$per_page);
		return view('dashboard.friendlist2More')
					->with('model',$model2)
					->with('model1',$model1)
					//->with('count',$count)
					->with('keyword',$name);

	}

/**
	BROADCAST DELETE AND SEND
**/
	public function delBroadcast()
	{
		$input=Input::get('bid');
		Broadcast::where('id', '=', $input)->delete();
		BroadcastMembers::where('broadcast_id',$input)->delete();
		BroadcastMessages::where('broadcast_id',$input)->where('broadcast_by',Auth::User()->id)->delete();
	}

	public function sendBroadcast()
	{
		$input = Input::all();
		$msg = nl2br($input['msg']);
		$uid = Auth::User()->id;
		$members = BroadcastMembers::where('broadcast_id',$input['bid'])->pluck('member_id');

        $xmpu1 = User::where('id',$uid)->value('xmpp_username');
        $converse = new Converse;
        $xmpu2 = User::whereIn('id',$members)->pluck('xmpp_username');

        foreach ($xmpu2 as $key => $value) {
        	$Message = json_encode( array( 'type' => 'text', 'message' => $input['msg'] ) );
        	$converse->broadcast($xmpu1,$value,$Message);
        }

		$date = date('d M Y,h:i a', time());
		     $data = array(
		     			'broadcast_message'=>$input['msg'],
                        'broadcast_id'=>$input['bid'],
                        'broadcast_by'=>Auth::User()->id,
                        'created_at'=>date('Y-m-d H:i:s',time()),
                            );  
                
                BroadcastMessages::insert($data);
				$model = new BroadcastMessages;

						$data1 = '<div class="single-message">
										<div class="clearfix">
											<div class="bcast-msg">
												'.$msg.'
											</div>
										</div>
										<div class="bcast-msg-time">
											'.$date.'
										</div>
									</div>';


		echo $data1;
	}


/**
	PRIVATE GROUP DELETE 
**/

	public function delPrivateGroup()
	{
		$input = Input::get('pid');
		$userXamp = Auth::User()->xmpp_username;
		try{
			$GroupDetails  	= Group::where('id',$input)->select('title','group_jid')->first();
			$GroupJid	  	= $GroupDetails->group_jid;
			$GroupTitle  	= $GroupDetails->title;

			$converse = new Converse;
			$userJid 		= Auth::User()->xmpp_username; // current user jid for chat message
			$name 			= Auth::User()->first_name.' '.Auth::User()->last_name; // current user full name
			$message 		= json_encode( array( 'type' => 'hint', 'action'=>'group_delete', 'sender_jid' => $userJid, 'groupname'=>$GroupTitle, 'groupjid' => $GroupJid, 'message' => webEncode($GroupTitle.' has been removed.') ) ); // hint message to send every group member
			$xmp 			= GroupMembers::leftJoin('users', 'members.member_id', '=', 'users.id')->where('members.group_id',$input)->pluck('xmpp_username');		
			foreach ($xmp as $key => $value) {
				$converse->broadcastchatroom( $GroupJid, $name, $value, $userJid, $message ); // message broadcast per group member
			}
			$converse->deleteGroup($GroupJid); // Delete group from chat server
			
			Group::where('id',$input)->where('owner_id',Auth::User()->id)->delete();
			GroupMembers::where('group_id',$input)->delete();
		} catch( Exception $e) {
	         echo $e->getMessage();
	         exit();
        }

	}

	/**
		 DELETE USER FROM PRIVATE GROUP 
	**/
	public function delUser()
	{
		$input=Input::all();
		$memberDetails = User::find($input['uid']); // delete member details
		try{
			/** 
			 * sending hint chat message all group member
			 **/
			$converse	= new Converse;
			$GroupDetail 	= Group::where('id',$input['gid'])->select('title','group_jid')->first(); // group details for message
			$GroupJID  		= $GroupDetail->group_jid;
			$GroupTitle  	= $GroupDetail->title;
			$userJid 		= Auth::User()->xmpp_username; // current user jid for chat message
			$name 			= Auth::User()->first_name.' '.Auth::User()->last_name; // current user full name
			$message = json_encode( array( 'type' => 'hint', 'action'=>'delete', 'sender_jid' => $userJid, 'xmpp_userid' => $memberDetails->xmpp_username, 'user_id'=> $memberDetails->id , 'message' => webEncode( $memberDetails->first_name.' '.$memberDetails->last_name.' remove from group chat') ) );
			
			$xmp = GroupMembers::leftJoin('users', 'members.member_id', '=', 'users.id')->where('members.group_id',$input['gid'])->pluck('xmpp_username');	// list of all members in group
			foreach ($xmp as $key => $value) {
				$converse->broadcastchatroom( $GroupJID, $name,$value, $userJid,$message );
			}
			$converse->removeUserGroup( $GroupJID,$memberDetails->xmpp_username ); // chat server, delete member from group 
			// member delete query execute
			GroupMembers::where('group_id',$input['gid'])->where( 'member_id', $input['uid'] )->update(['status' => 'Left']); 
		} catch( Exception $e) {
          $e->getMessage();
        }
	}
/**
	for chat member status
**/	
	public function getNewChatGroup(){
		$Request = Input::all();
		$GroupJid = $Request['group_jid'];
		$UserId   = Auth::User()->id;
		$Result   = [ 'limit' => 0 , 'status' => 0 ];
		try {
			$CheckStatus = Group::leftJoin('members','members.group_id','=','groups.id')->where( ['groups.group_jid' => $GroupJid, 'members.member_id' => $UserId, 'groups.status' => 'Active' ] )->select( 'members.status' )->first();
			if( isset( $CheckStatus->status ) && $CheckStatus->status == 'Pending' ) {
				$Result['status']	= 1;
				$TotalCount = GroupMembers::where(['member_id' => $UserId,'status' => 'Joined'] )->get()->count();
				if( $TotalCount < Config::get('constants.private_group_limit') ){
					$Result['limit'] = 1;
				}
			}
		} catch( Exception $e) {
			echo  $e->getMessage();
			exit();
		}
		echo json_encode( $Result );
	}
/**
	for s chat list
**/	
	public function getChatGroupList(){
		$Request = Input::all();
		$GroupJid = $Request['group_jid'];
		$UserId   = Auth::User()->id;	
		$Result   = ['data' => '' ];
		$MemberGroup = Group::leftJoin('members','members.group_id','=','groups.id')->where( ['groups.group_jid' => $GroupJid, 'members.member_id' => $UserId, 'groups.status' => 'Active','members.status' => 'Pending'] )->select( 'members.id' )->first();		
		if( isset($MemberGroup->id) && !empty($MemberGroup->id) ){
			$MemberID = $MemberGroup->id;
			$PrivateGroupMember = GroupMembers::find($MemberID);
			$PrivateGroupMember->status = 'Joined';
			$PrivateGroupMember->update();
		}
		$UserGroup = Group::leftJoin('members','members.group_id','=','groups.id')->where( ['members.member_id' => $UserId,'members.status' => 'Joined'] )->select( 'groups.group_jid','groups.title','groups.picture','groups.id' )->orderBy('groups.id', 'desc')->get();
		$Result['data']  = $UserGroup;
		echo json_encode( $Result );
	}

/**
	 EDIT PRIVATE GROUP NAME 
**/
	public function editGroupName()
	{
		$input		=	Input::all();
		$GroupId 	=	$input['gid'];
		$GroupName 	=	$input['gname'];
		$GroupDetail = 	Group::where('id',$GroupId)->select( 'group_jid' )->first();
		
		$userJid 		= Auth::User()->xmpp_username; // current user jid for chat message
		$name 			= Auth::User()->first_name.' '.Auth::User()->last_name; // current user full name
		
		$message 		= json_encode(array( 'type' => 'hint',  'action'=>'group_info_change','sender_jid' => $userJid, 'groupname' => $GroupName, 'message' => webEncode($name.' changed group name'), 'changeBy' => $name, 'group_jid'=>$GroupDetail->group_jid) );
		
		$xmp = GroupMembers::leftJoin('users', 'members.member_id', '=', 'users.id')->where('members.group_id',$GroupId)->select('users.id as user_id', DB::raw('CONCAT(users.first_name, " ", users.last_name) AS username'), 'users.xmpp_username as xmpp_userid','users.picture as user_image')->get();
		
		// Update group name
		if( $GroupName != $GroupDetail->title )
		{
			Group::where('id',$GroupId)->update(['title'=>$input['gname']]);
			
			/** 
			 * sending hint chat message all group member
			 * */
			foreach ($xmp as $key => $value) {
				Converse::broadcastchatroom( $GroupDetail->group_jid, $name, $value->xmpp_username, $userJid, $message ); // message broadcast per group member
			}
		}
		
		// Add new members
		if( isset($input['members']) && $input['members'] )
		{
			foreach($input['members'] as $user_id)
			{
				$exist = GroupMembers::where(['group_id' => $GroupId, 'member_id' => $user_id])->first();
				if( $exist )
				{
					$exist->left_at = null;
					$exist->status = 'Pending';
					$exist->save();
				}
				else
				{
					$member = new GroupMembers;
					$member->group_id = $GroupId;
					$member->member_id = $user_id;
					$member->status = 'Pending';
					$member->save();
				}

				// Send hint
				$user = User::where('id', $user_id)->select(['first_name', 'last_name', 'xmpp_username','id','picture'])->first();
				$inviteeName = $user->first_name.' '.$user->last_name;
				$addMessage = json_encode(array( 'type' => 'hint', 'action'=>'add','sender_jid' => $userJid, 'user_id' => $user->id, 'user_image' => $user->picture,'groupname' => $GroupName, 'message' => webEncode($inviteeName.' is invited for joining the group.'), 'group_jid'=>$GroupDetail->group_jid) );

				foreach ($xmp as $key => $value) {
				
					Converse::broadcastchatroom( $GroupDetail->group_jid, $name, $value->xmpp_username, $userJid, $addMessage );
				}

				$converse  = new Converse;
				$Message = json_encode( array( 'type' => 'room', 'groupname' => $GroupName, 'sender_jid' => $userJid, 'groupjid'=>$GroupDetail->group_jid, 'group_image' => $GroupDetail->picture, 'created_by'=>$name,'message' => webEncode('This invitation is for joining the '.$GroupName.' group.'), 'users' => $xmp) );
				// $converse->addUserGroup( $GroupJid,$value->xmpp_userid );
				$converse->broadcast(Auth::user()->xmpp_username, $user->xmpp_username, $Message);
			}
		}
		json_encode(array( 'status' => '1' ));
		exit();
	}
	
	/**
	*  Send mails to hotmail contacts.
	*/
	public function sendHotmailInvitation()
	{
		$emailIds = Input::get('emails');

		$invalid = array();
		$valid = array();
		foreach ($emailIds as $key => $value) {

			$validator = Validator::make($emailIds, [
				$key => 'email'
			]); 

			$validator->each($key, 'email');
			
           if($validator->fails()) {
                $invalid[] = $value;
            }else{
            	$valid[] = $value;
				$message = 'Hi, Take a look at this cool social site "FriendzSquare!"';
				$subject = 'FriendzSquare Invitation';

				$username = Auth::User()->first_name.' '.Auth::User()->last_name;

				$data = array(
					'message' => $message,
					'subject' => $subject,
					'id' => Auth::User()->id,
					//'type' => $type,
					'username' => $username,
				);
				
				Mail::send('emails.invite', $data, function($message) use($value, $subject) {
					$message->from('no-reply@friendzsquare.com', 'Friend Square');
					$message->to($value)->subject($subject);
	            });
			}

		}

		if(!empty($invalid)){
			if(count($invalid) > 1){
				$emails = implode(',', $invalid);
				echo $emails.' are the invalid email addresses and counld not be invited.';
			}else{
				$emails = $invalid[0];
				echo $emails.' is an invalid email address and counld not be invited.';
			}
		}	
	}

	/** 
	 * 	goup image update or add 
	 * */
	public function groupImage()
	{
		$input 		= Input::all();
		$file 		= Input::file('groupimage');
		$groupId 	= $input['groupid'];
		
		if( isset($input['groupimage']) && $file != null ){
			$image_name = time()."_GI_".strtoupper($file->getClientOriginalName());
			$input['groupimage'] = $image_name;
			$file->move(public_path('uploads'), $image_name);
			$img = $input['groupimage'];
		}
		if( isset($img) && !empty( $img ) ){
			Group::where('id',$groupId)->update(['picture' => $img]); // updating group image base on group id
			/** 
			 * sending hint chat message all group member
			 * */
			$groupDetail = 	Group::where('id',$groupId)->select( 'group_jid' )->first(); // group details 
			$userJid 		= Auth::User()->xmpp_username; // current user jid for chat message
			$name 			= Auth::User()->first_name.' '.Auth::User()->last_name; // current user full name
			$message 		= json_encode(array( 'type' => 'hint',  'action'=>'group_info_change','sender_jid' => $userJid, 'group_image' => $img, 'message' => webEncode( $name.' changed group image' ), 'changeBy' => $name, 'group_jid'=>$groupDetail->group_jid));
			$xmp 			= GroupMembers::leftJoin('users', 'members.member_id', '=', 'users.id')->where('members.group_id',$groupId)->pluck('xmpp_username');		
			foreach ($xmp as $key => $value) {
				Converse::broadcastchatroom( $groupDetail->group_jid, $name, $value, $userJid, $message ); // message broadcast per group member
			}
		}
	}

	public function viewMoreForAll()
	{
		$per_page = 10;
		$page = Input::get('pageid');
		$keyword = Input::get('keyword');
		$user_id = Auth::check() ? Auth::User()->id : 0;
		
		// Search users
        $users = Functions::searchUsers($keyword, $user_id, $page, $per_page);
        
        $existmore = $users['pages'] == $page ? 0 : 1;
		$auth = ($user_id != '') ? 1 : 0;
		if( $users['records'] )
		{
			$html = view('dashboard.getsearchresult')
				->with('model', $users['records'])
				->with('modelcount', count($users['records']))
				->with('auth',$auth)
				->render();
			return response()->json(['html' => $html, 'existmore' => $existmore]);          
		} else {
			echo "No more results";
		}
	}

	public function delForumPost()
	{
		if(Auth::check()){
			$args = Input::all();
			ForumPost::where('id',$args['forumpostid'])->delete();
			ForumLikes::where('post_id',$args['forumpostid'])->delete();
			$reply_id_arr = ForumReply::where('post_id',$args['forumpostid'])->pluck('id')->toArray();
			ForumReply::where('post_id',$args['forumpostid'])->delete();
			ForumReplyComments::whereIn('reply_id',$reply_id_arr)->delete();
			ForumReplyLikes::whereIn('reply_id',$reply_id_arr)->delete();
			$count = ForumPost::where('forum_category_breadcrum',$args['breadcrum'])->get()->count();
			echo $count;
	  }
	}

	public function editForumPost()
	{
		if(Auth::check()){
			$forumpostid = Input::get('forumpostid');
			$forumpost = ForumPost::where('id',$forumpostid)->first();

			return view('ajax.editforumpost')->with('forumpost', $forumpost);
		}
	}

	public function editNewForumPost()
	{
		$arguments = Input::all();
		if(Auth::check()){
				if($arguments['forumtitle'] != ""){
					$check = ForumReply::where('post_id',$arguments['id'])->get()->count();
					if($check == 0){
					
					ForumPost::where('id',$arguments['id'])->update(['title'=>$arguments['forumtitle']]);
					$data = [
						'id'=>$arguments['id'],
						'title'=>forumPostContents(nl2br($arguments['forumtitle'])),
						'date' => date('d M Y'),
						'time' => date('h:i A').' (UTC)'
					];

					echo json_encode($data);
				   }
				   else
				   	echo "rep";

			   }else
					echo "Post something to update.";
		}

	}

	public function addNewForumPost()
    {
    	$user = Auth::User();
        $input = Input::all();
        if(Auth::check()){
		       	$name = $user->first_name." ".$user->last_name;

		        $forum_category_breadcrum = $input['breadcrum'];
		        $id_array = explode(" > ", $forum_category_breadcrum);

		        foreach ($id_array as $key => $value) {
		        	$id_array[$key] = Forums::where('title',$value)->value('id');
		  			Forums::where('id',$id_array[$key])->update(['updated_at'=>date('Y-m-d H:i:s',time())]);      	
		        	$cat_id = $id_array[$key];
		        }
		 		$forum_category_id = implode(",", $id_array);

		 		if($cat_id == null)
		 			$cat_id = "opt";
		 		
		            $data = ['title'=>$input['topic'],
		                    'owner_id'=>$user->id,
		                    'category_id'=>$cat_id,
		                    'forum_category_id'=>$forum_category_id,
		                    'forum_category_breadcrum'=>$forum_category_breadcrum,
		                    'created_at'=>date('Y-m-d H:i:s',time()),
		                    'updated_at'=>date('Y-m-d H:i:s',time())];
		         
		        $forumpost = new Forumpost;
		        $forumpostid = $forumpost->create($data);
		        // $profileimage = !empty($user->picture) ? $user->picture : '/images/user-thumb.jpg';
		        
		        return view('ajax.forumpost')
		        		->with('forumpostid',$forumpostid)
		        		->with('profileimage',$user)
		        		->with('breadcrum',$forum_category_breadcrum)
		        		->with('user',$user)
		        		->with('name',$name);

				echo $forumpostdata;
		}
    }

    
	/*
	 * Get country on request.
	 */
	public function mobCountryCode()
	{	
		$countryId = Input::get('countryId');

		if(is_numeric($countryId)){
			$country = Country::where('country_id', $countryId)->get();	
		}else{
			$country = Country::where('country_name', $countryId)->get();	
		}
		
		return $country;	
	}

	/*
	 * Like on Forum Posts.
	 */
	public function likeForumPost()
	{
		$forumpost = Input::get('forumpostid');
		$user_id = Input::get('user_id');
		if($user_id == "")
			$userid = Auth::User()->id;
		else{
			$user = User::where('id',$user_id)->get();
			if($user->isEmpty()){
				print_r("No such user in database.");die;
			}
			else
				$userid = $user_id;
		}
		
		$check = ForumPost::where('id',$forumpost)->get();
		if($check->isEmpty()){
			echo "no";
		}else{
		$likecheck = ForumLikes::where('owner_id',$userid)->where('post_id',$forumpost)->value('id');
		if($likecheck == null)
		{
			$likedata = ['liked'=>'Yes',
						 'owner_id'=>$userid,
						 'post_id'=>$forumpost];

		   $forumlike = new ForumLikes;
		   $forumlike->create($likedata);
		   $likecount = ForumLikes::where('post_id',$forumpost)->get()->count();

		   echo $likecount;
		}
		else{
			ForumLikes::where('owner_id',$userid)->where('post_id',$forumpost)->delete();
			$likecount = ForumLikes::where('post_id',$forumpost)->get()->count();

		   	echo $likecount; 
		}
	 }

	}

	public function viewMoreForumPost()
	{
		$per_page = 10;
		$page = Input::get('pageid');
		$call_type = Input::get('call_type');
		$breadcrum = Input::get('breadcrum');
		$offset = ($page - 1) * $per_page;

		// Get total pages
		$totalRecords = ForumPost::with('user')->with('forumPostLikesCount')
	     	->with('replyCount')
	        ->where('forum_category_breadcrum',$breadcrum)
            ->count();
        $pages = ceil($totalRecords / $per_page);
        $existmore = $page == $pages ? 0 : 1;

	    $posts = ForumPost::with('user')->with('forumPostLikesCount')
	     	->with('replyCount')
	        ->where('forum_category_breadcrum',$breadcrum)
	        ->skip($offset)
	        ->take($per_page)
	        ->orderBy('updated_at','DESC')
	        ->get();

		$str  = "No More Results";

		if($call_type == 'web')
		{
			if(!($posts->isEmpty()))
			{
				$html = view('forums.viewmoreforumposts')->with('posts',$posts)->with('breadcrum',$breadcrum)->render();
				return response()->json(['html' => $html, 'existmore'=>$existmore]);
			} else {
				echo $str;
			}
		}
		elseif($call_type == 'api')
		{
			if(!($posts->isEmpty()))
			{
				$html = view('forums-api.ajax-post')
							->with('forumPosts',$posts)
							->with('breadcrum',$breadcrum)
							->with('user_id', Input::get('user_id'))
							->render();
				return response()->json(['html' => $html, 'existmore'=>$existmore]);
			}
			else{
				echo $str;
			}
		}
	}
	
	public function addNewForumReply()
	{
	    $user = Auth::User();
        $input = Input::all();
       	$check = ForumPost::where('id',$input['forumpostid'])->get();
       	if($check->isEmpty())
       		echo "no";
       	else{
                $data = ['reply'=>$input['reply'],
                        'owner_id'=>$user->id,
                        'post_id'=>$input['forumpostid'],
                        'created_at'=>date('Y-m-d H:i:s',time()),
                        'updated_at'=>date('Y-m-d H:i:s',time())];
		               
        $forumreply = new ForumReply;
        $forumpostreply = $forumreply->create($data);

        $name = $user->first_name." ".$user->last_name;
        // $profileimage = !empty($user->picture) ? $user->picture : '/images/user-thumb.jpg';

        // @ Send notification mail.
        $parameters = array('user_id' => $user->id, 'current_data' => $input['reply'], 'object_id' => $input['forumpostid'], 'type' => 'reply');
        $notify = Converse::notifyOnReplyComment( $parameters );
        
        return view('ajax.forumpostreply')
        		->with('forumreply',$forumpostreply)
        		->with('profileimage',$user)
        		->with('forumpostid',$input['forumpostid'])
        		->with('user',$user)
        		->with('name',$name);
        }
	}

	public function delForumReply()
	{
		$args = Input::all();
		ForumReply::where('id',$args['forumreplyid'])->delete();
		ForumReplyComments::where('reply_id',$args['forumreplyid'])->delete();
		ForumReplyLikes::where('reply_id',$args['forumreplyid'])->delete();
		$count = ForumReply::where('post_id',$args['forumpostid'])->get()->count();
		echo $count;
	}
	
	public function editForumReply()
	{
		$forumreplyid = Input::get('forumreplyid');
		$forumreply = ForumReply::where('id',$forumreplyid)->first();
		
		return view('ajax.editforumreply')
				->with('forumreply', $forumreply);
	}

	public function editNewForumReply()
	{
		$arguments = Input::all();

		if($arguments['forumreply'] != "")
		{
			ForumReply::where('id',$arguments['id'])->update(['reply'=>$arguments['forumreply']]);
			$data = [
				'id' => $arguments['id'],
				'reply'=> forumPostContents(nl2br($arguments['forumreply'])),
				'date' => date('d M Y'),
				'time' => date('h:i A').' (UTC)'
			];
			echo json_encode($data);
		}
		else
			echo "Post something to update.";

	}

	public function likeForumReply()
	{
		$forumreplyid = Input::get('forumreplyid');
		$user_id = Input::get('user_id');
		if($user_id == "")
			$userid = Auth::User()->id;
		else{
			$user = User::where('id',$user_id)->get();
			if($user->isEmpty()){
				print_r("No such user in database.");die;
			}
			else
				$userid = $user_id;
		}

		$likecheck = ForumReplyLikes::where('owner_id',$userid)->where('reply_id',$forumreplyid)->value('id');
		if($likecheck == null)
		{
			$likedata = ['liked'=>'Yes',
						 'owner_id'=>$userid,
						 'reply_id'=>$forumreplyid];

		   $forumreplylike = new ForumReplyLikes;
		   $forumreplylike->create($likedata);
		   $likecount = ForumReplyLikes::where('reply_id',$forumreplyid)->get()->count();
		   $likearr = ['likecount'=>$likecount,
		   				'check'=>'checked'];
		   print_r(json_encode($likearr));
		}
		else{
			ForumReplyLikes::where('owner_id',$userid)->where('reply_id',$forumreplyid)->delete();
			$likecount = ForumReplyLikes::where('reply_id',$forumreplyid)->get()->count();
			$likearr = ['likecount'=>$likecount,
		   				'check'=>'unchecked'];
		   print_r(json_encode($likearr)); 
		}
	}

	public function getForumPostBox()
	{
		$reply_id = Input::get('replyid');

	    $reply = ForumReply::with('user')
	    ->with('replyLikesCount')
	    ->with('replyCommentsCount')
	    ->where('id',$reply_id)
	    ->first();

	    $replyComments = ForumReplyComments::where('reply_id',$reply_id)->get();

		return view('ajax.getforumpostbox')
				->with('reply',$reply)
				->with('reply_id',$reply_id)
				->with('replyComments',$replyComments);
	}

	public function forumReplyComment()
	{
		$replyid = Input::get('replyid');
		$replycomment = Input::get('comment');
		$user  = Auth::User();

		$arr = ['reply_comment'=>$replycomment,
				'owner_id'=>$user->id,
				'reply_id'=>$replyid];

		$replycomment = new ForumReplyComments;
		$comment = $replycomment->create($arr);

		$name = $user->first_name." ".$user->last_name;
        // $profileimage = !empty($user->picture) ? $user->picture : '/images/user-thumb.jpg';

        // @ Send notification mail.
        $parameters = array('user_id' => $user->id, 'object_id' => $replyid, 'current_data' => $comment->reply_comment, 'type' => 'comment');
        $notify = Converse::notifyOnReplyComment( $parameters );

		return view('ajax.forumreplycomment')
				->with('comment',$comment)
				->with('name',$name)
				->with('userid',$user->id)
				->with('replyid',$replyid)
				->with('profileimage',$user);
		

	}

	public function delForumReplyComment()
	{
		$commentID = Input::get('forumReplyCommentId');
		$forumReplyId = Input::get('forumReplyId');
		ForumReplyComments::where('id',$commentID)->delete();
		$count = ForumReplyComments::where('reply_id',$forumReplyId)->get()->count();
		echo $count;
	}
	
	public function viewMoreForumReply()
	{
		$per_page = 10;
		$page = Input::get('pageid');
		$call_type = Input::get('call_type');
		$forumpostid = Input::get('forumpostid');
		$offset = ($page - 1) * $per_page;

		// Get total pages
		$totalRecords = ForumReply::with('user')
            ->with('replyLikesCount')
            ->with('replyCommentsCount')
            ->where('post_id',$forumpostid)
            ->count();
        $pages = ceil($totalRecords / $per_page);
        $existmore = $page == $pages ? 0 : 1;

	    $reply = ForumReply::with('user')
            ->with('replyLikesCount')
            ->with('replyCommentsCount')
            ->where('post_id',$forumpostid)
            ->skip($offset)
	        ->take($per_page)
            ->orderBy('updated_at','DESC')
            ->get();
            
		$str  = "No More Results";
		
		if($call_type === 'web')
		{
			if(!($reply->isEmpty()))
			{
				$html = view('forums.viewmoreforumreply')->with('reply',$reply)->with('forumpostid',$forumpostid)->render();
				return response()->json(['html' => $html, 'existmore'=>$existmore]);
			} else {
				echo $str;
			}
		}
		elseif($call_type === 'api')
		{
			if(!($reply->isEmpty()))
			{
				$html = view('forums-api.ajax-reply')
						->with('replies', $reply)
						->with('user_id', Input::get('user_id'))
						->with('forumpostid', $forumpostid)->render();
				return response()->json(['html' => $html, 'existmore'=>$existmore]);
			}
			else{
				echo $str;
			}
		}
	}

	public function viewMoreForumComment()
	{
		$per_page = 5;
		$page = Input::get('pageid');
		$call_type = Input::get('call_type');
		$forumpostid = Input::get('forumpostid');
		$offset = ($page - 1) * $per_page;

		$reply_id = Input::get('forumreplyid');

	    $reply = ForumReply::with('user')
				    ->with('replyLikesCount')
				    ->with('replyCommentsCount')
				    ->where('id', $reply_id)
				    ->first();

		if(empty($reply)){
			return view('forums-api.forum-not-found')->with('message', 'Post does not exist.')->render();
		}

	    $replyComments = ForumReplyComments::with('user')
	    					->where('reply_id', $reply_id)
				            ->skip($offset)
					        ->take($per_page)
	    					->get();

	   	$str = 'No More Results';

	   	if(!($replyComments->isEmpty())){
			return view('forums-api.ajax-comment')
					->with('reply', $reply)
					->with('replyComments', $replyComments)
					->render();	   		
	   	}else{
	   		echo $str;
	   	}


	}

	
	public function getSubForums()
	{
		$input = Input::all();
		 if($input['forumid'] == "Forum")
		  	{ echo"No"; exit; }
		$subforums = Forums::where('parent_id',$input['forumid'])->get();
		$mainforum = Forums::where('id',$input['forumid'])->value('selection');

		$forums = array('<option value=""></option>');
		if($subforums->isEmpty())
			echo 'No';
		else{
			if($mainforum == "Y"){
				$subforumArr[] = "<option value='sub-opt'>Select Option</option>";
			}		
			else{
				$subforumArr[] = "<option value='sub-opt'>Select Sub Category</option>";
			}
		foreach($subforums as $query){
		if($query->title == "Country,State,City")
		 	$query->title = "City";			
			$subforumArr[] = '<option value="'.$query->id.'">'.$query->title.'</option>';
		}		
		echo implode('',$subforumArr);
		}
	}

	public function getSubForums2()
	{
		$input = Input::all();
		$title = Forums::where('id',$input['forumid'])->value('title');
		$countries = Country::get();

		if($title == "Country"){
			$country[] = "<option value='Country'>Select Country</option>";
			foreach($countries as $data){
			$country[] = '<option value="'.$data->country_name.'">'.$data->country_name.'</option>';
			}
			$country1 = implode('',$country);
			$arr = ['msg' => 'c',
					'data'=>$country1];
			print_r(json_encode($arr));
		}

		else if($title == "Country,State,City"){
			$country[] = "<option value='Country'>Select Country</option>";
			foreach($countries as $data){
				$country[] = '<option value="'.$data->country_name.'">'.$data->country_name.'</option>';
			}
			$country1 = implode('',$country);
			$arr = ['msg' => 'csc',
					'data'=>$country1];
			print_r(json_encode($arr));
		}

		else if($title == "International"){
			echo "hide";
		}
		else if($title == "Professional Course" || $title == "Subjects"){
			$subforumArr[] = "<option >Select Option</option>";
			$subforums = Forums::where('parent_id',$input['forumid'])->get();
			foreach($subforums as $query){			
			$subforumArr[] = '<option value="'.$query->id.'">'.$query->title.'</option>';
			}	
			$subforumArr1 = implode('',$subforumArr);	
			$arr = ['msg' => 'subfor',
					'data'=>$subforumArr1];
			print_r(json_encode($arr));
		}
		else{
			echo "hide";
		}

	}

	public function viewMoreSearchForum()
	{
		$per_page = 10;
		$page = Input::get('pageid');
		$breadcrum = Input::get('breadcrum');
		$keyword = Input::get('keyword');
		$offset = ($page - 1) * $per_page;

		// Get total pages
		$totalRecords = ForumPost::with('user')
                ->with('forumPostLikesCount')
                ->with('replyCount')
                ->where('forum_category_breadcrum', 'LIKE', $breadcrum.'%')
                ->whereRaw( 'LOWER(`title`) like ?', array("%".$keyword."%"))
            	->count();
        $pages = ceil($totalRecords / $per_page);
        $existmore = $page == $pages ? 0 : 1;

       	$posts = ForumPost::with('user')
                ->with('forumPostLikesCount')
                ->with('replyCount')
                ->where('forum_category_breadcrum', 'LIKE', $breadcrum.'%')
                ->whereRaw( 'LOWER(`title`) like ?', array("%".$keyword."%"))
                ->skip($offset)
        		->take($per_page)
                ->orderBy('updated_at','DESC')
                ->get();

		$str  = "No More Results";

		if(!($posts->isEmpty()))
		{
			$html = view('forums.viewmoresearchforum')->with('posts',$posts)->with('breadcrum',$breadcrum)->with('keyword',$keyword)->render();
			return response()->json(['html' => $html, 'existmore'=>$existmore]);    
		} else {
			echo $str;
		}
	}
	
	public function leavePrivateGroup(){

		$groupId = Input::get('pid');
		$converse		= new Converse;
		/** 
		 * sending hint chat message all group member
		 * */
		$groupDetails 	= Group::where('id',$groupId)->select('title','group_jid')->first();
		$groupJid	  	= $groupDetails->group_jid;
		$groupTitle 	= $groupDetails->title;
		
		$userJid 		= Auth::User()->xmpp_username; // current user jid for chat message
		$name 			= Auth::User()->first_name.' '.Auth::User()->last_name; // current user full name          
		$message 		= json_encode( array( 'type' => 'hint', 'action'=>'leave', 'sender_jid' => $userJid, 'xmpp_userid' => $userJid, 'user_id'=>Auth::User()->id, 'message' => webEncode( $name.' left the group '.$groupTitle) ) );
		
		$xmp 			= GroupMembers::leftJoin('users', 'members.member_id', '=', 'users.id')->where('members.group_id',$groupId)->pluck('xmpp_username');	// list of all group member 
		foreach ($xmp as $key => $value) {
			$converse->broadcastchatroom($groupJid, $name, $value, $userJid, $message); // sending chat message to group memebr
		}
		$converse->removeUserGroup($groupJid,$userJid); // remove member from group
		GroupMembers::where('group_id',$groupId)->where('member_id',Auth::User()->id)->delete();

    }
	
/*	public function forumDelConfirm()
	{
		$input = Input::all();

		if($input['type'] == "post"){

			$data = ['class' => "forumpostdelete",
					 'id' => $input['type_id'],
					 'breadcrum'=> $input['breadcrum'],
					 'reply_post_id' => "",
					 'gid' => "", 
					 'message' => "All the replies and comments related to this post will be deleted. Are you sure you want to delete this post?"];
		
		}else if($input['type'] == "reply"){

			$data = ['class' => "forumreplydelete",
					 'id' => $input['type_id'],
					 'breadcrum'=> "",
					 'reply_post_id' => $input['reply_post_id'],
					 'gid' => "",
					 'message' => "All the comments related to this reply will be deleted. Are you sure you want to delete this reply?"];
		}else if($input['type'] == "broadcast"){
				$data = ['class' => "broadcastdel",
					 'id' => $input['type_id'],
					 'breadcrum'=> "",
					 'reply_post_id' => "",
					 'gid' => "",
					 'message' => "Are you sure you want to delete this broadcast?"];

		}else if($input['type'] == "private"){
				$data = ['class' => "delprivategroup",
					 'id' => $input['type_id'],
					 'breadcrum'=> "",
					 'reply_post_id' => "",
					 'gid' => "",
					 'message' => "Are you sure you want to delete this group?"];

		}else if($input['type'] == "private-leave"){
				$data = ['class' => "userleave",
					 'id' => $input['type_id'],
					 'breadcrum'=> "",
					 'reply_post_id' => "",
					 'gid' => "",
					 'message' => "Are you sure you want to leave this group?"];
		}else if($input['type'] == "del-private-member"){
				$data = ['class' => "deluser",
					 'id' => $input['type_id'],
					 'breadcrum'=> "",
					 'reply_post_id' => "",
					 'gid' => $input['gid'],
					 'message' => "Are you sure you want to delete this user from  the group?"];
		}


		return view('forums.deleteconfirmbox')
			   		->with('data',$data);
	}*/
	
	public function isMemberActive() 
    {
        $post = $input = Input::all();
        $GroupJid = $post['user_jid'];
        $UserId = $post['group_jid'];
        
        $group = Group::leftJoin('members','members.group_id','=','groups.id')->where( ['groups.group_jid' => $GroupJid, 'members.member_id' => $UserId, 'groups.status' => 'Active' ] )->select( 'members.status' )->first();
		$TotalCount = Group::leftJoin('members','members.group_id','=','groups.id')->where( ['members.member_id' => $UserId,'members.status' => 'Joined'] )->count();
		
		$Status = isset($group->status)?$group->status:'Left';
		
		$CanAdd = $TotalCount < 15 ? 1 : 0;
        return json_encode(array('limit' => $CanAdd, 'status' => $Status));
    }
	
	// Leave group 
	public function leaveGroup()
	{
		if( Auth::check() ){
			echo 0; 
		} 
		else 
		{
			$input = Request::all();
			DefaultGroups::where(['user_id' => Auth::user()->id, 'group_jid' => $input['group_jid']])->delete();
			echo 1;
		}
		
		exit;
	}

	/*
	 * Join Private Group API.
	 */
	public function joinPrivateGroup()
	{
		try
		{
			$group_id = Request::get('group_id');
			$member_id = Auth::User()->id;
			$user = Auth::User();
			$Status = 0;
			if( empty( $group_id ) )
				throw new Exception("Group is required.", 1);				

			$group = Group::where('id', $group_id)->first();

			if( !$group )
				throw new Exception("Group does not exist.", 1);

			$TotalCount = GroupMembers::where(['member_id' => $member_id,'status' => 'Joined'] )->get()->count();
			
			if( $TotalCount < Config::get('constants.private_group_limit') ){
				$group_members = GroupMembers::where(['group_id' => $group->id, 'member_id' => $member_id])->count();

				if( $group_members > 0 ){

					$update = GroupMembers::where(['group_id' => $group->id, 'member_id' => $member_id])->update(['status' => 'Joined']);

					// Broadcast message

	               $members = GroupMembers::leftJoin('users', 'members.member_id', '=', 'users.id')->where('members.group_id',$group->id)->pluck('xmpp_username');
	                $name = $user->first_name.' '.$user->last_name;
	                $message = json_encode( array( 'type' => 'hint', 'action'=>'join', 'sender_jid' => $user->xmpp_username,'user_id' => $user->id, 'user_image' => $user->picture,'xmpp_userid' => $user->xmpp_username, 'user_name'=>$name, 'message' => $name.' joined the group') );

	                foreach($members as $key => $val) {
	                    Converse::broadcastchatroom($group->group_jid, $name, $val, $user->xmpp_username, $message);
	                };

					$Status = 1;
				}
			} else {
				throw new Exception("Sorry, you can be member only upto ".Config::get('constants.private_group_limit')." private groups.", 1);
			}
		}catch(Exception $e){
			$e->getMessage();
		}

		return json_encode(array('status' => $Status));

	}
}