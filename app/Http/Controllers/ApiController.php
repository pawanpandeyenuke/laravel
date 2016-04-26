<?php

namespace App\Http\Controllers;

use Mail;
use App\Library\Converse;
use App\User, App\Feed, App\Like, App\Comment, Auth, App\EducationDetails, App\Friend,App\Broadcast,App\BroadcastMembers,App\BroadcastMessages;
use App\Http\Controllers\Controller;
use App\Country, App\State, App\City, App\Category, App\DefaultGroup, App\Group, App\GroupMembers;
use Validator, Input, Redirect, Request, Session, Hash, DB;
use \Exception;

class ApiController extends Controller
{

	private $status = 'error'; 

	private $message = 'Something went wrong.';

	private $data = array();

	/*
	 * Login response.
	 */
	public function signin()
	{
		$input = Request::all();
		
		if( $input )
		{
			$user = Auth::attempt($input);

			if( !empty($user) ) {

				$this->status = 'success';
				$this->message = 'User logged in successfully.';
				$this->data = Auth::user()->toArray();
				
			}else {
				
				$this->message = 'Invalid username or password.';
				
			}
			
		}

		return $this->output();
	}


	/*
	 * Register response.
	 */
	public function signup()
	{
		$input = Request::all();		

		if( $input )
		{
			$user = new User;
			
			$validator = Validator::make($input, $user->apiRules, $user->messages);
			
			if($validator->fails()) {
				
				$this->message = $this->getError($validator);
				
			}else{
				
				$input['password'] = Hash::make($input['password']);
				$userdata = $user->create($input);

				//Saving xmpp-username and xmpp-pasword into database.
		        $xmpp_username = $userdata->first_name.$userdata->id;
		        $xmpp_password = 'enuke'; //substr(md5($userdata->id),0,10);

		        $user = User::find($userdata->id);
		        $user->xmpp_username = strtolower($xmpp_username);
		        $user->xmpp_password = $xmpp_password;
		        $user->save();

		        $converse = new Converse;
		        $response = $converse->register($xmpp_username, $xmpp_password);
		        // echo '<pre>';print_r($response);die;
				$this->status = 'success';
				$this->message = 'User registered successfully';				
				$this->data = $user->toArray();
				
			}
 
		}

		return $this->output();
	}
	
	
	/*
	 * Forgot password.
	 */
	public function forgetPassword()
	{
		
		try{			
			$input = Request::all();			
			if( $input['email'] ){
				$userEmailCheck = User::whereEmail($input['email'])->first();
				
				if( !$userEmailCheck )
					throw new Exception('No profile was found with this Email.');
				
				$this->data = $input;
				
				//~ $mail = Mail::send('auth.emails.password', $input, function($message) use ($input)
				//~ {
					//~ $message->from('no-reply@friendsquare.com', "Friend Square");
					//~ $message->subject("Reset Password.");
					//~ $message->to($input['email']);
				//~ });
				
			}
		}catch( Exception $e ){			
			$this->message = $e->getMessage();		
		}
		
		return $this->output();
		
	}
		
		
	/*
	 * Get social login details.
	 */
	public function getSocialLogin()
	{
		
		try{
			$arguments = Request::all();
			$user = new User;			
			$validator = Validator::make($arguments, $user->socialApiRules, $user->messages);
			
			if($validator->fails()){
				$this->message = $this->getError($validator);				
			}else{

				if( isset( $arguments['id'] ) &&  $arguments['type'] == 'facebook' )
					$arguments['fb_id'] = $arguments['id'];
				elseif( isset( $arguments['id'] ) &&  $arguments['type'] == 'twitter' )
					$arguments['twitter_id'] = $arguments['id'];
				elseif( isset( $data['id'] ) &&  $data['type'] == 'google' )
					$arguments['google_id'] = $arguments['id'];
				elseif( isset( $arguments['id'] ) &&  $arguments['type'] == 'linkedin' )
					$arguments['linked_id'] = $arguments['id'];

				
				$controller = app()->make('App\Http\Controllers\SocialController')->socialLogin($arguments);
				
				if( $controller ){
					$this->message = 'Successfully logged in';
					$this->status = 'success';
					$this->data = $controller;
				}
				
			}
			
		}catch( Exception $e ){
			
			$this->message = $e->getMessage();
			
		}
		
		return $this->output();

	}


	/*
	 * Creating posts on api request.
	 */
	public function createPosts()
	{
		try
		{
			$arguments = Request::all();
			$feeds = new Feed;

			if( $arguments ){

				if( !$arguments['user_by'] )
					throw new Exception('User id is required.');

				if( !is_numeric($arguments['user_by']) )
					throw new Exception('Invalid user id.');

				if( ( $arguments['message'] == null ) && ( $arguments['image'] == null ) )
					throw new Exception('Please provide a message or image.');

				if(Request::hasFile('image')){

					$file = Request::file('image');
					$image_name = time()."_POST_".strtoupper($file->getClientOriginalName());
					$arguments['image'] = $image_name;
					$file->move('uploads', $image_name);

				}
			}

				$success = $feeds->create( $arguments );

				if( $success ){
					$this->message = 'Your post has been saved successfully.';
					$this->status = 'success';
					$this->data = $success;
				}


		}catch( Exception $e ){

			$this->message = $e->getMessage();

		}
		return $this->output();
	}


	/*
	 * Fetch posts on api request.
	 */
	public function getPosts()
	{
		try
		{
			$arguments = Request::all();
			$feeds = new Feed;
			// $userId = Auth::User()->id;
			
			if( $arguments )
			{
				$validator = Validator::make($arguments, $feeds->rules, $feeds->messages);

				if($validator->fails()){
					$this->message = $this->getError($validator);
				}else{

					if(!User::find($arguments['user_by']))
						throw new Exception('The user id does not exists.');						

					$per_page = $arguments['page_size'];
					$page = $arguments['page'];
					$offset = ($page - 1) * $per_page;

					$posts = Feed::orderBy('updated_at', 'desc')
								// ->where('user_by', '=', $arguments['user_by'])
				                ->whereIn('user_by', Friend::where('user_id', '=', $arguments['user_by'])
				                        ->where('status', '=', 'Accepted')
				                        ->pluck('friend_id')
				                        ->toArray())
				                ->orWhere('user_by', '=', $arguments['user_by'])
								->skip($offset)
								->take($per_page)
								->with('likesCount')
								->with('commentsCount')
								->with('user')
								->with('likedornot')
								->get()
								->toArray();

					$postscount = Feed::orderBy('updated_at', 'desc')
								// ->where('user_by', '=', $arguments['user_by'])
				                ->whereIn('user_by', Friend::where('user_id', '=', $arguments['user_by'])
				                        ->where('status', '=', 'Accepted')
				                        ->pluck('friend_id')
				                        ->toArray())
				                ->orWhere('user_by', '=', $arguments['user_by'])
								->with('likesCount')
								->with('commentsCount')
								->with('user')
								->with('likedornot')
								->count();

					/*$posts = Feed::where('user_by', $arguments['user_by'])->orderBy('updated_at', 'desc')->skip($offset)->take($per_page)->with('likesCount')->with('commentsCount')->with('user')->with('likedornot')->get()->toArray();*/
//					$recordscount = Feed::where('user_by', $arguments['user_by'])->get();
//					$rcounts = Feed::all();
//					$recordscount = count($rcounts);
//print_r($recordscount);die;


					// $posts = Feed::with('user')->leftJoin('likes', 'likes.feed_id', '=', 'news_feed.id')->leftJoin('comments', 'comments.feed_id', '=', 'news_feed.id')->groupBy('news_feed.id')->get(['news_feed.*',DB::raw('count(likes.id) as likes'),DB::raw('count(comments.id) as comments'),DB::raw('count(likes.id) as likes')]);

/*					$posts = DB::table('users')
					            ->join('news_feed', 'news_feed.user_by', '=', 'users.id')
					            ->join('likes', 'news_feed.user_by', '=', 'likes.user_id')
					            ->join('comments', 'news_feed.user_by', '=', 'comments.commented_by')
					            ->select('users.id', 'users.first_name', 'users.last_name', 'users.picture')
					            ->selectRaw('likes.feed_id, count(*) as likescount')->groupBy('likes.feed_id')
					            ->selectRaw('comments.feed_id, count(*) as commentscount')->groupBy('comments.feed_id')
					            ->get();
					            // ->toSql();*/

/*					$posts = Feed::with('user')
								->leftJoin('likes', 'likes.feed_id', '=', 'news_feed.id')
								->leftJoin('comments', 'comments.feed_id', '=', 'news_feed.id')
								->groupBy('news_feed.id')
								->get(['news_feed.*',DB::raw('count(likes.id) as likes'),DB::raw('count(comments.id) as comments')]);*/

					if( $posts ){

						$this->status = 'success';
						$this->data['feed'] = $posts;
						$this->data['page_no'] = $arguments['page'];
						$this->data['page_size'] = $arguments['page_size'];
/*
						$this->data['records'] = $recordscount;
						$this->data['total_pages'] = ceil($recordscount / $arguments['page_size']);
						$this->message = count($posts). ' posts found.';
						$this->status = 'success';
						$this->message = count($posts). ' posts found.';
						$this->data['feeds'] = $posts;*/

						$this->data['records'] = $postscount;
						$this->data['total_pages'] = ceil($postscount / $arguments['page_size']);
						$this->message = count($postscount). ' posts found.';
					}
					
				}
			}

		}catch( Exception $e ){

			$this->message = $e->getMessage();

		}

		return $this->output();
	}


	/*
	 * Managing likes on api request.
	 */
	public function likes()
	{
		try
		{	
			// return 'pawan';
			$arguments = Request::all();
			$likes = new Like;
			// print_r($arguments);exit;
			if( $arguments ){

				$validator = Validator::make($arguments, $likes->rules, $likes->messages);

				if($validator->fails()) {
					
					throw new Exception($this->getError($validator));
					
				}else{

					$feed = Feed::find($arguments['feed_id']);

					if( !$feed )
						throw new Exception( 'Feed does not exists' );
					
					$like = Like::where([ 'feed_id' => $arguments['feed_id'], 'user_id' => $arguments['user_id'] ])->get()->toArray();

					if( empty($like) )
					{						
						$model = new Like;
						$response = $model->create( $arguments );
						$this->message = 'Like created successfully.';
						$this->status = 'success';
						$this->data = $response;
						$this->data['status'] = 'liked';

					}else{

						$model = Like::where([ 'feed_id' => $arguments['feed_id'], 'user_id' => $arguments['user_id']])->delete();
						$this->message = 'Like updated successfully.';
						$this->status = 'success';
						$this->data = $arguments;
						$this->data['status'] = 'unliked';

						// echo '<pre>';print_r($response);exit;
					}

				}

			}

		}catch( Exception $e ){

			$this->message = $e->getMessage();

		}
		return $this->output();
	}


	/*
	 * Managing comments on api request.
	 */
	public function getComments()
	{
		try
		{
			$arguments = Request::all();

			if( empty($arguments['feed_id']) || !isset($arguments['feed_id']) )
				throw new Exception('Feed id is a required field.');
			
			if( !is_numeric($arguments['feed_id'] ) )
				throw new Exception('Feed id is invalid.');

			$user = Feed::find($arguments['feed_id']);

			if( count($user) <= 0 )
				throw new Exception('This feed does not exists.');


			$per_page = $arguments['page_size'];
			$page = $arguments['page'];
			$offset = ($page - 1) * $per_page;

			$comments = Comment::where('feed_id', $arguments['feed_id'])->orderBy('updated_at', 'desc')->with('user')->skip($offset)->take($per_page)->get()->toArray();

			$recordscount = Comment::where('feed_id', $arguments['feed_id'])->get();

			$this->status = 'success';
			$this->data['comments'] = $comments;
			$this->data['page_no'] = $arguments['page'];
			$this->data['page_size'] = $arguments['page_size'];
			$this->data['records'] = count($recordscount);
			$this->data['total_pages'] = ceil(count($recordscount) / $arguments['page_size']);
			$this->message = count($comments). ' comments found.';

		}catch(Exception $e){

			$this->message = $e->getMessage();

		}

		return $this->output();

	}


	/*
	 * Creating comments on api request.
	 */
	public function postComments()
	{
			try
			{
				$arguments = Request::all();
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

					$this->message = 'Comment successfully posted.';
					$this->status = 'success';
					$this->data = $model;	

				}

			}catch(Exception $e){

				$this->message = $e->getMessage();

			}

		return $this->output();
	}


	/*
	 * Get users profile.
	 */
	public function  getProfile()
	{
		try{ 

			$arguments = Request::all();
			$user = new User;

			if($arguments){

				if( !( User::find( $arguments['id'] ) ) )
					throw new Exception("This user id doesn't exist");

				$userProfile = User::with('education')->where(['id'=>$arguments['id']])->get()->toArray();

				//print_r($userProfile);die;

				$this->status = 'Success';
				$this->data = $userProfile;
				$this->message = 'User profile data';

			}else{
				throw new Exception('Please enter valid user id');
			}
		
		}catch(Exception $e){	

			$this->message=$e->getMessage();

		}

		return $this->output();
	
		}


	/*
	 *  Edit users profile picture.
	 */
 	public function updateProfilePicture()
 	{
		try{

			$arguments = Request::all();
			$user = new User();

			if(!empty($arguments)){

				$userfind = $user->find($arguments['id']);

				if(empty($userfind))
					throw new Exception("User does not exist.", 1);
					
				if(empty($arguments['picture']))
					throw new Exception("Picture is required.", 1);

	            $file = Request::file('picture');
 				
	            if( isset($arguments['picture']) && $file != null ){
	                $image_name = time()."_POST_".strtoupper($file->getClientOriginalName());
	                $arguments['picture'] = '/uploads/user_img/'.$image_name;
	                $file->move(public_path('uploads/user_img'), $image_name);
	            }
 
				User::where([ 'id' => $arguments['id'] ])->update([ 'picture' => $arguments['picture'] ]);
				$userfind = User::find(Request::get('id'));
 
				$this->status = 'Success';
				$this->data = $userfind;
				$this->message = 'Profile picture updated';
			}

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();	

	}


	/*
	 *  Edit users profile.
	 */
 	public function updateProfile()
 	{
		try{
			$arguments = Request::all();
			$user = new User();
//			echo '<pre>';print_r($arguments);die;
			if(isset($arguments['education'])){

				$delete = EducationDetails::where('user_id', '=', $arguments['id'])->delete();

				$data = array();

				$educationdata = $arguments['education'];
				// echo '<pre>';print_r($educationdata);die;
				foreach ($educationdata as $key => $value) {

					$education = new EducationDetails;
					$data[] = $education->create($value);
				}
			}
			
			unset($arguments['education']);

			if($arguments){

				if(!(User::find($arguments['id'])))
					throw new Exception("This user id doesn't exist");

				if(isset($arguments['email']))
					throw new Exception("Email address cannot be changed.");

				if(isset($arguments['password']))
					throw new Exception("Password cannot be changed.");
 
				foreach ($arguments as $key => $value) {

					User::where([ 'id' => $arguments['id'] ])
							->update([ $key => $value ]);

				}

				$changes = User::with('education')->where([ 'id' => $arguments['id'] ])->get()->toArray();

				$this->status = 'Success';
				$this->data = $changes;
				$this->message = 'Profile updated';


			}else{
				throw new Exception('Please enter valid user id.');
			}

		}catch(Exception $e){
			$this->message=$e->getMessage();
		}

		return $this->output();	

 	}


	/*
	 * Edit post on request.
	 */
	public function editPost()
	{
		try{
			$arguments = Request::all();

 			$validator = Validator::make($arguments, [
	 						'id' => 'required|numeric',
	 					]); 

	        if($validator->fails())
	        {

	        	$this->message = $this->getError($validator);

	        }else{

				$newsFeed = Feed::find($arguments['id']);

				if( empty($newsFeed) )
					throw new Exception('Post does not exist.', 1);

				if(Request::hasFile('image')){

					$file = Request::file('image');
					$image_name = time()."_POST_".strtoupper($file->getClientOriginalName());
					$arguments['image'] = $image_name;
					$file->move('uploads', $image_name);

				}

				$newsFeed->fill($arguments);
				$saved = $newsFeed->push();

				if( $saved ){
					$this->status = 'Success';
					$this->message = "Post updated successfully.";
					$this->data = Feed::find($arguments['id']);				
				}

	        }

		}catch(Exception $e){

			$this->message = $e->getMessage();

		}

		return $this->output();	
	}


	/*
	 * Delete post on request.
	 */
	public function deletePost()
	{
		try{
			$arguments = Request::all();

 			$validator = Validator::make($arguments, [
	 						'id' => 'required|numeric',
	 					]); 

	        if($validator->fails()) {

	        	$this->message = $this->getError($validator);

	        }else{

				$newsFeed = Feed::find($arguments['id']);

				if( empty($newsFeed) )
					throw new Exception('Post does not exist.', 1);

				$postdata = array(
								'id' => $arguments['id'], 
								'user_id' => $newsFeed->user_by
							);

				$deleteFeed = Feed::where('id', $arguments['id'])->delete();
				
				if( $deleteFeed ){
					$this->status = 'Success';
					$this->message = "Post deleted successfully.";
					$this->data = $postdata;
				}
			}

		}catch(Exception $e){

			$this->message = $e->getMessage();

		}

		return $this->output();	
	}


	/*
	 * Edit comment on request.
	 */
	public function editComment()
	{
		try{
			$arguments = Request::all();

 			$validator = Validator::make($arguments, [
	 						'id' => 'required|numeric',
	 						'comments' => 'required'
	 					]); 

	        if($validator->fails()) {

	        	$this->message = $this->getError($validator);

	        }else{

				$comment = Comment::find($arguments['id']);

				if( empty($comment) )
					throw new Exception("This comment does not exist", 1);

				$comment->fill($arguments);
				$saved = $comment->push();

				if( $saved ){
					$this->status = 'Success';
					$this->message = "Comment updated successfully.";
					$this->data = Comment::find($arguments['id']);
				}
	        }
		}catch(Exception $e){

			$this->message = $e->getMessage();

		}

		return $this->output();	
	}


	/*
	 * Delete comment on request.
	 */
	public function deleteComment()
	{
		try{
			$arguments = Request::all();

 			$validator = Validator::make($arguments, [
	 						'id' => 'required|numeric',
	 					]); 

	        if($validator->fails()) {

	        	$this->message = $this->getError($validator);

	        }else{

				$comment = Comment::find($arguments['id']);

				if( empty($comment) )
					throw new Exception('Comment does not exist.', 1);

				$commentdata = array(
								'id' => $arguments['id'], 
								'commented_by' => $comment->commented_by
							);

				$deletecomment = Comment::where('id', $arguments['id'])->delete();
				
				if( $deletecomment ){
					$this->status = 'Success';
					$this->message = "Comment deleted successfully.";
					$this->data = $commentdata;
				}
			}

		}catch(Exception $e){

			$this->message = $e->getMessage();

		}

		return $this->output();	
	}


	/*
	 * Get friend list on request.
	 */
	public function getFriends()
	{
		try{
			$arguments = Request::all();
			$user = User::find($arguments['id']);

			if(empty($user))
				throw new Exception("This user does not exist", 1);

			$friends = Friend::with('user')
					->with('friends')
					->where('user_id', '=', $arguments['id'])
					->orWhere('friend_id', '=', $arguments['id'])
					->where('status', '=', 'Accepted')
					->get();
			
			// print_r($friends);exit;

			$this->data = $friends;
			$this->status = 'success';
			$this->message = count($friends).' friends found.';

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();
		
	}


	/*
	 * Get users list to send friend request.
	 */
	public function getUsers()
	{
		try{
			$arguments = Request::all();
			$user = User::find($arguments['id']);

			if(empty($user))
				throw new Exception("This user does not exist", 1);

			$userslist = User::where('id', '!=', $arguments['id'])
					->select('first_name', 'last_name', 'id')
					->get();
			
			// print_r($friends);exit;

			$this->data = $userslist;
			$this->status = 'success';
			$this->message = count($userslist).' users found.';

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();
		
	}


	/*
	 * Add friend on request.
	 */
	public function addFriend()
	{
		try{
			$arguments = Request::all();

			$user = User::where('id', '=', $arguments['user_id'])->get();
			$friend = User::where('id', '=', $arguments['friend_id'])->get();

			if(empty($user))
				throw new Exception("This user does not exist", 1);

			if(empty($friend))
				throw new Exception("This user does not exist", 1);

			$friendcheck = Friend::where('user_id', '=', $arguments['user_id'])
							->where('friend_id', '=', $arguments['friend_id'])
							->get()
							->toArray();

			if( !empty($friendcheck)){
	
				$this->data = $friendcheck;
				$this->status = 'success';
				$this->message = 'Friend request has already been sent.';

			}else{

				$friend = new Friend;
				$friend->status = 'Pending';
				$request = $friend->create($arguments);

				$this->data = $request;
				$this->status = 'success';
				$this->message = 'Friend request sent.';

			}

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();
		
	}


	/*
	 * Accept friend request.
	 */
	public function acceptRequest()
	{
		try{
			$arguments = Request::all();

			$user = User::where('id', '=', $arguments['user_id'])->get();
			$friend = User::where('id', '=', $arguments['friend_id'])->get();

			if(empty($user))
				throw new Exception("This user does not exist", 1);

			if(empty($friend))
				throw new Exception("This user does not exist", 1);

			$friendcheck = Friend::where('user_id', '=', $arguments['friend_id'])
							->where('friend_id', '=', $arguments['user_id'])
							->where('status', '=', 'Pending')
							->get();
			
			if( !empty($friendcheck)){

				DB::table('friends')
					->where('user_id', '=', $arguments['user_id'])
					->where('friend_id', '=', $arguments['friend_id'])
					->update(['status' => 'Accepted']);

				$friend = new Friend;
				$friend->status = 'Accepted';
				$friend->friend_id = $arguments['user_id'];
				$friend->user_id = $arguments['friend_id'];
				$request = $friend->save();

				$this->data = $request;
				$this->status = 'success';
				$this->message = 'Friend request accepted.';

			}

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();
		
	}


	/*
	 * Decline friend request.
	 */
	public function declineRequest()
	{
		try{
			$arguments = Request::all();

			$user = User::where('id', '=', $arguments['user_id'])->get();
			$friend = User::where('id', '=', $arguments['friend_id'])->get();

			if(empty($user))
				throw new Exception("This user does not exist", 1);

			if(empty($friend))
				throw new Exception("This user does not exist", 1);

			$friendcheck = Friend::where('user_id', '=', $arguments['friend_id'])
							->where('friend_id', '=', $arguments['user_id'])
							->where('status', '=', 'Pending')
							->get();
			
			if( !empty($friendcheck)){

				$request = DB::table('friends')
					->where('user_id', '=', $arguments['user_id'])
					->where('friend_id', '=', $arguments['friend_id'])
					->update(['status' => 'Rejected']);

				$this->data = $request;
				$this->status = 'success';
				$this->message = 'Friend request declined.';

			}

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();
		
	}



 	/*
	 * Send image on chat api.
	 */
	public function chatSendImage()
	{
		$status = 0;
		$message = ""; 
		$image = $_FILES["chatsendimage"]["name"];
		$path = public_path().''.'/uploads/media/chat_images';
		
		$uploadedfile = $_FILES['chatsendimage']['tmp_name'];
		$name = $_FILES['chatsendimage']['name'];
		$size = $_FILES['chatsendimage']['size'];
		$valid_formats = array("jpg", "JPG", "jpeg", "JPEG", "png", "PNG", "gif", "GIF");

		if (strlen($name)) {
			list($txt, $ext) = explode(".", $name);
			if (in_array($ext, $valid_formats)) {
				$actual_image_name = "chatimg_" . time() . substr(str_replace(" ", "_", $txt), 5) . "." . $ext;
				$tmp = $uploadedfile;
				// echo '<pre>'; print_r($actual_image_name);die;
				if (move_uploaded_file($tmp, $path . $actual_image_name)) {           
					$data = public_path().''.'/uploads/media/chat_images/'.$actual_image_name;
					$chatType=isset($_POST["chatType"])?$_POST["chatType"]:'';
					if ($chatType == "group"){}//chat type check
					else{           
						$this->message = 'Image saved successfully.'; //$_SERVER['HTTP_HOST'].$data;
						$status=1;
					}                              
				} else
				$this->message= "Failed to send try again.";    
			} else
			$this->message= "Invalid file format.";
		}else {
			$this->message="Please select an image to send.";
		}

		$this->data['filename'] = $actual_image_name;
		$this->status = 'Success';

		return $this->output();

	}


	/*
	 * update push notification details on user table on request.
	 */
	public function updatePushNotificationDetails()
	{
		try{
			
			$arguments = Request::all();
			
			$user = User::where('id', '=', $arguments['id'])->get()->toArray();

			if(empty($user))
				throw new Exception("This user does not exist", 1);

			$user = User::find($arguments['id']);

			//Removing the unique credentials of user from requests.
			unset($arguments['id']);
/*			if($arguments['email'] || $arguments['password']){
				unset($arguments['email']);
				unset($arguments['password']);
			}*/

			if($arguments['device_type'] == 'ANDROID' || $arguments['device_type'] == 'IPHONE' || $arguments['device_type'] == 'NONE'){
				$user->fill($arguments);
				$saved = $user->push();
			}
			
			$this->data = User::find(Request::get('id'));
			$this->status = 'success';
			$this->message = null;

		}catch(Exception $e){

			$this->message = $e->getMessage();

		}

		return $this->output();
	}

 
	/*
	 * Get chat category on request.
	 */
	public function getChatCategories()
	{
		
		$this->data = Category::all();
		$this->status = 'success';
		$this->message = null;
		
		return $this->output();
		
	}


	/*
	 * Get public groups on request.
	 */
	public function getPublicGroups()
	{
		try{
			
			$groupname = Request::get('group_name');

			if( !empty( $groupname ) ){				
			
				$groupcheck = DefaultGroup::where('group_name', '=', $groupname)->get()->toArray();
				
				if(empty($groupcheck)){

					$groupby = Request::get('group_by');

					if(!isset($groupby))
						throw new Exception("Group by is a required field.", 1);

					$finduser = User::find($groupby);
					
					if(empty($finduser))
						throw new Exception("This user does not exist.", 1);
					
					$groupname = Request::get('group_name');

					$arguments = Request::all();
					$defaultgroup = new DefaultGroup;
					$defaultgroup->create($arguments);

					$count = DefaultGroup::where('group_name', '=', $groupname)->count();

					$this->data = DefaultGroup::with('user')->where('group_name', '=', $groupname)->get();
					$this->status = 'success';
					$this->message = $count.' Results were found.';
 
				}else{ 

					$groupby = Request::get('group_by');

					if(!isset($groupby))
						throw new Exception("Group by is a required field.", 1);

					$finduser = User::find($groupby);
					
					if(empty($finduser))
						throw new Exception("This user does not exist.", 1);

					$arguments = Request::all();
					$defaultgroup = new DefaultGroup;
					$defaultgroup->create($arguments);

					$count = DefaultGroup::where('group_name', '=', $groupname)->count();

					$this->data = DefaultGroup::with('user')->where('group_name', '=', $groupname)->get();
					$this->status = 'success';
					$this->message = $count.' Results were found.';

				}
			}else{

				throw new Exception("Groupname is a required field.", 1);

			}

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}
 
		return $this->output();
	}

	
	/*
	 * Add new broadcast.
	 */
    public function broadcastAdd()
    {
 	 	try{
	    	$input = Request::all();

	    	if(isset($input['broadcast_members'])&& isset($input['user_id']) && $input['broadcast_name']!=null )
	            {
	            	$user = User::find($input['user_id']);
	            	if(!($user))
	            		throw new Exception("No user found");					
	            	else {

	            		$error=0;
	            		// $members=explode(',',$input['broadcast_members']);

	            		$row1=DB::table('broadcast')->where('user_id',$input['user_id'])->where('title',$input['broadcast_name'])->value('id');
		            		
			    		if($row1!=null)
			    			throw new Exception("Broadcast Name already exists!");
		            
	            		foreach ($input['broadcast_members'] as $key => $value) {
		            		$row=null;
		            		$row=DB::table('friends')->where('user_id',$input['user_id'])->where('friend_id',$value)->where('status','Accepted')->value('id');
		            		if($row==null) {
		            		 	$error=$value;
		            	    	break;
		            		}

	            		}

	            		if($error!=0)
	            			throw new Exception($error." is not a friend and can't be added to broadcast");	
	            		else {
	            			$data = array(
				                        'title'=>$input['broadcast_name'],
				                        'user_id'=>$input['user_id'],
	                       			);
	                              
			                $bid=Broadcast::create($data);

			                foreach ($input['broadcast_members'] as $key => $value) {
		                		$data1 = array(
					                        'broadcast_id'=>$bid['id'],
					                        'member_id'=>$value
			                            );  
		                    	BroadcastMembers::create($data1);
	               			}

			                $this->status="success";
			                $this->message="Broadcast created.";
			                $this->data=Broadcast::where('id',$bid['id'])->get()->toArray();
	            		}            		
	            	}
	    		}
	    	else
	    	{
	    		throw new Exception("All three fields required.");	
	    	}
		}
		catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();

	}


	/*
	 * Get broadcast list on request.
	 */
	public function getBroadcastList()
	{
		try{
			
			$arguments = Request::all();

			if(isset($arguments['user_id'])){

				$finduser = User::find($arguments['user_id']);

				if(empty($finduser))
					throw new Exception("User does not exist.", 1);
					
				$broadcast = Broadcast::where('user_id', $arguments['user_id'])->get()->toArray();

				if(empty($broadcast))
					throw new Exception("No records found.", 1);
				
/*				$broadcastIds = Broadcast::where('user_id', $arguments['user_id'])->lists('id')->toArray();

				$broadcastMemberIds = BroadcastMembers::whereIn('broadcast_id', $broadcastIds)
										->join('broadcast', 'broadcast.id', '=', 'broadcast_members.broadcast_id')
										->join('users', 'users.id', '=', 'broadcast_members.member_id')
										->select('broadcast.user_id' ,'broadcast.title' ,'member_id', 'broadcast_id', 'users.first_name', 'users.last_name', 'users.xmpp_username')
										->get()
										->toArray();*/


				$broadcastIdsData = Broadcast::with('broadcastMembers')
									->where('user_id', $arguments['user_id'])
									->get()
									->toArray();

				
				$this->data = $broadcastIdsData;
				$this->message = count($broadcastIdsData).' results found.';
				$this->status = 'success';
 
			}else{
				throw new Exception("User id is required.", 1);				
			}

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();

	}


	/*
	 * Exit group api on request.
	 */
	public function exitGroup()
	{
		try{

			$arguments = Request::all();

			if(isset($arguments['group_name']) && isset($arguments['group_by'])){

				if(!empty($arguments['group_name']) && !empty($arguments['group_by'])){

					$groupcheck = DefaultGroup::where([
									'group_name' => $arguments['group_name'],
									'group_by' => $arguments['group_by']
									])
								->get()->toArray();

					if(empty($groupcheck))
						throw new Exception("Group does not exist.", 1);
						
					$group = DefaultGroup::where([
									'group_name' => $arguments['group_name'],
									'group_by' => $arguments['group_by']
									])
								->delete();

					$this->data = $arguments;
					$this->message = $arguments['group_name'].' successfully deleted.' ;
					$this->status = 'success';
					// echo '<pre>';print_r($group);die;
				}else{
					throw new Exception("Group by id or Group name cannot be empty.", 1);
				}
			}else{
				throw new Exception("Either the group name or group by id is missing.", 1);
				
			}

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();

	}


	/*
	 * Delete broadcast api on request.
	 */
	public function deleteBroadcast()
	{
		try{
			
			$broadcastid = Request::get('broadcast_id');

			if(empty($broadcastid))
				throw new Exception("Broadcast id is required.", 1);
				
			$findbroadcast = Broadcast::find($broadcastid);

			if(empty($findbroadcast))
				throw new Exception("Broadcast does not exist.", 1);
				
			$findbroadcast = Broadcast::find($broadcastid)->delete();

			$this->data = $findbroadcast;
			$this->message = 'Broadcast successfully deleted.' ;
			$this->status = 'success';
			// echo '<pre>';print_r($group);die;

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();

	}
 

	/*
	 * Private group add api on request.
	 */
    public function privateGroupAdd()
    {
 	 	try{
	    	$input = Request::all();

	    	if(isset($input['member_id'])&& isset($input['owner_id']) && $input['title']!=null )
	            {
	            	$user = User::find($input['owner_id']);
	            	if(!($user))
	            		throw new Exception("No user found");					
	            	else {

	            		$error=0;
	            		// $members=explode(',',$input['broadcast_members']);

	            		$row1=DB::table('groups')->where('owner_id',$input['owner_id'])->where('title',$input['title'])->value('id');
		            		
			    		if($row1!=null)
			    			throw new Exception("Group Name already exists!");
		            
	            		foreach ($input['member_id'] as $key => $value) {
		            		$row=null;
		            		$row=DB::table('friends')->where('user_id',$input['owner_id'])->where('friend_id',$value)->where('status','Accepted')->value('id');
		            		if($row==null) {
		            		 	$error=$value;
		            	    	break;
		            		}

	            		}

	            		if($error!=0)
	            			throw new Exception($error." is not a friend and can't be added to group");	
	            		else {
	            			array_push($input['member_id'],$input['owner_id']);
	            			$data = array(
				                        'title'=>$input['title'],
				                        'owner_id'=>$input['owner_id'],
	                       			);
	                              
			                $bid = Group::create($data);

			                foreach ($input['member_id'] as $key => $value) {
		                		$data1 = array(
					                        'group_id'=>$bid['id'],
					                        'member_id'=>$value,
					                        'status' => 'Joined'
			                            );  
		                    	GroupMembers::create($data1);
	               			}

			                $this->status="success";
			                $this->message="Group created.";
			                $this->data=Group::where('id',$bid['id'])->get()->toArray();
	            		}            		
	            	}
	    		}
	    	else
	    	{
	    		throw new Exception("All three fields required.");	
	    	}
		}
		catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();

	}


	/*
	 * Get group list on request.
	 */
	public function getGroupList()
	{
		try{
			
			$arguments = Request::all();

			if(isset($arguments['owner_id'])){

				$finduser = User::find($arguments['owner_id']);

				if(empty($finduser))
					throw new Exception("User does not exist.", 1);
					
				$groupowner = Group::where('owner_id', $arguments['owner_id'])->get()->toArray();

				if(empty($groupowner))
					throw new Exception("No records found.", 1);

				$groupIdsData = Group::with('groupMembers')
									->where('owner_id', $arguments['owner_id'])
									->get()
									->toArray();

				
				$this->data = $groupIdsData;
				$this->message = count($groupIdsData).' results found.';
				$this->status = 'success';
 
			}else{
				throw new Exception("User id is required.", 1);				
			}

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();

	}


	/*
	 * Delete private group api on request.
	 */
	public function deletePrivateGroup()
	{
		try{
			
			$group_id = Request::get('group_id');

			if(empty($group_id))
				throw new Exception("Group id is required.", 1);
				
			$findgroup = Group::find($group_id);

			if(empty($findgroup))
				throw new Exception("Group does not exist.", 1);
				
			$findgroup = Group::find($group_id)->delete();

			$this->data = $findgroup;
			$this->message = 'Group successfully deleted.' ;
			$this->status = 'success';
			// echo '<pre>';print_r($group);die;

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();

	}

		/*
	 * Delete public chatroom entry api on request.
	 */
	public function publicGroupGetIds()
	{
		try{
			$groupBy = Request::get('group_by');

			if($groupBy){

				$userdata = User::find($groupBy);
				if(empty($userdata))
					throw new Exception("This user does not exist.", 1);

				$userdata = DefaultGroup::where('group_by', $groupBy)->get()->toArray();
		
				$this->data = $userdata;
				$this->status = 'success';
				$this->message = count($userdata).' groups found.';
			}else{
				throw new Exception("Group user id is required.", 1);				
			}

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}
		return $this->output();
	}

	/*
	 * Get country on request.
	 */
	public function getCountries()
	{
		
		$this->data = Country::all(['country_id as id', 'country_name as name']);
		$this->status = 'success';
		$this->message = null;
		
		return $this->output();
		
	}
	
	
	/*
	 * Get states based on country id.
	 */
	public function getStates()
	{
		
		try{
			
			$input = Request::all();
			if( $input )
			{			
				if( !isset( $input['country_id'] ) )
					throw new Exception('Invalid country id.');
					
				if( !is_numeric( $input['country_id'] ) )
					throw new Exception('Please insert valid country id.');

				$states = State::where(['country_id'=> $input['country_id']])->get(['state_id as id', 'state_name as name']);
				$this->status = 'success';
				$this->message = null;
				$this->data = $states;
				
		}
		}catch( Exception $e ){
		
			$this->message = $e->getMessage();
		
		}
		
		return $this->output();
				
	}
	
	
	/*
	 * Get cities based on state id.
	 */
	public function getCities()
	{
		try{
			
			$input = Request::all();
			if( $input )
			{
				if( !isset( $input['state_id'] ) )
					throw new Exception('Invalid state id.');
					
				if( !is_numeric( $input['state_id'] ) )
					throw new Exception('Please insert valid state id.');
					
				$cities = City::where(['state_id'=> $input['state_id']])->get(['city_id as id', 'city_name as name']);
				$this->status = 'success';
				$this->message = null;
				$this->data = $cities;
			}
		}catch( Exception $e ){
				
			$this->message = $e->getMessage();
				
		}
		
		return $this->output();
		
	}
	
	
	/*
	 * Returns error if occuers.
	 */
	protected function getError( $validator )
	{
		$messages = [];

		if( $validator->errors() ){

			foreach($validator->errors()->getMessages() as $key => $val) {

				$messages[] = implode(' and ', $val);

			}

			$message = implode(' and ', $messages);			
			return $message ? $message.'.' : null;

		}

		return null;

	}

	/*
	 * Return output of the request.
	 */
	protected function output()
	{
		$this->data = empty($this->data) ? null : $this->data;

		return json_encode(array(
			'status' => $this->status, 
			'message' => $this->message,
			'data' => $this->data
		));

	}
	

}
