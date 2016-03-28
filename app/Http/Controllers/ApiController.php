<?php

namespace App\Http\Controllers;

use Mail;
use App\User, App\Feed, App\Like, App\Comment, Auth, App\EducationDetails, App\Friend;
use App\Http\Controllers\Controller;
use App\Country, App\State, App\City;
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
				$userData = $user->create($input);

				$tempEmail = explode('@', $input['email']);
				$tempId = ( isset( $userData->id ) && $userData->id != "" ) ? $userData->id : $userData->user_id;

				// Storing xmpp username and password.				
				$user = User::find($userData->id);
				$user->xmpp_username = $tempEmail[0].'_'.$tempId;
				$user->xmpp_password = md5($tempEmail[0]);
				$user->save();

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

				
				$controller = app()->make('App\Http\Controllers\SocialAuthController')->socialLogin($arguments);
				
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
								->where('user_by', '=', $arguments['user_by'])
								->skip($offset)
								->take($per_page)
								->with('likesCount')
								->with('commentsCount')
								->with('user')
								->with('likedornot')
								->get()
								->toArray();

					$postscount = Feed::orderBy('updated_at', 'desc')
								->where('user_by', '=', $arguments['user_by'])
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

				$userProfile = User::with('education')->with('country')->where(['id'=>$arguments['id']])->get()->toArray();

				// print_r($userProfile);die;

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
	 *  Edit users profile.
	 */
 	public function updateProfile()
 	{

		try{
			$arguments = Request::all();
			$user = new User;
			$id = $arguments['id'];
			//multiple image uploading
			$getCommonEduArgs = array_intersect_key( $arguments, [
									'id' => 'null',
									'education_level' => 'null',
									'specialization' => 'null',
									'graduation_year_from' => 'null',
									'graduation_year_to' => 'null',
									'currently_studying' => 'null',
									'education_establishment' => 'null',
									'country_of_establishment' => 'null',
									'city_of_establishment' => 'null',
									'job_area' => 'null',
									'job_category' => 'null'
								]);

			if( !empty($getCommonEduArgs) ){

			$eduExists = EducationDetails::where('id', '=', $getCommonEduArgs['id'])->get()->toArray();

				if(!empty($eduExists)){

					$eduExists = EducationDetails::find($getCommonEduArgs['id']);
					$eduExists->fill($getCommonEduArgs);
					$saved = $eduExists->push();

				}else{

					$education = new EducationDetails;
					$education->create($getCommonEduArgs);

				}

				foreach ($getCommonEduArgs as $key => $value)
					unset($arguments[$key]);

			}
			
			$arguments['id'] = $id;

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

			$friends = Friend::where('user_id', '=', $arguments['id'])
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
