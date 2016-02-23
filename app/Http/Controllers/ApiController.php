<?php

namespace App\Http\Controllers;

use Mail;
use App\User, App\Feed, App\Like, App\Comment, Auth;
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

				$success = $feeds->create( $arguments );

				if( $success ){
					$this->message = 'Your post has been saved successfully.';
					$this->status = 'success';
					$this->data = $success;					
				}

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
			
			if( $arguments )
			{
				$validator = Validator::make($arguments, $feeds->rules, $feeds->messages);

				if($validator->fails()){
					$this->message = $this->getError($validator);
				}else{

					if(!User::find($arguments['user_by']))
						throw new Exception('The user id does not exists.');						

					$posts = Feed::with('likesCount')->with('commentsCount')->with('user')->with('likedornot')->get();

/*					$posts = DB::table('users')
					            ->join('news_feed', 'news_feed.user_by', '=', 'users.id')
					            ->join('likes', 'news_feed.user_by', '=', 'likes.user_id')
					            ->join('comments', 'news_feed.user_by', '=', 'comments.commented_by')
					            ->select('users.id', 'users.first_name', 'users.last_name', 'users.picture')
					            ->selectRaw('likes.feed_id, count(*) as likescount')->groupBy('likes.feed_id')
					            ->selectRaw('comments.feed_id, count(*) as commentscount')->groupBy('comments.feed_id')
					            ->get();
					            // ->toSql();*/

					if( $posts ){

						$this->status = 'success';
						$this->message = count($posts). ' posts found.';
						$user = User::find($arguments['user_by']);
						$this->data['image'] = $user->picture;
						$this->data['name'] = $user->first_name.' '.$user->last_name;
						$this->data['feeds'] = $posts;

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
			$arguments = Request::all();
			$likes = new Like;

			if( $arguments ){

				$validator = Validator::make($arguments, $likes->rules, $likes->messages);

				if($validator->fails()) {
					
					throw new Exception($this->getError($validator));
					
				}else{

					$feed = Feed::find($arguments['feed_id']);
					if( !$feed )
						throw new Exception( 'Feed does not exists' );
					
					$like = Like::where([
						'feed_id' => $arguments['feed_id'],
						'user_id' => $arguments['user_id'],
					])->get();
					
					$id = '';
					foreach( $like as $key => $val )
						$id = $val['id'];
					
					if( !$like->isEmpty() ){

						$model = Like::find($id);
						$response = $model->update( $arguments );
						$this->message = 'Like updated successfully.';
						$this->status = 'success';
						$model = Like::find($id);
						$this->data = $model;

					}else{

						$model = new Like;
						$response = $model->create( $arguments );
						$this->message = 'Like created successfully.';
						$this->status = 'success';
						$this->data = $response;	

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

			if( empty($arguments['commented_by']) || !isset($arguments['commented_by']) )
				throw new Exception('User id is a required field.');
			
			if( !is_numeric($arguments['commented_by'] ) )
				throw new Exception('User id is invalid.');

			$user = User::find($arguments['commented_by']);

			if( count($user) <= 0 )
				throw new Exception('This user does not exists.');

			$comments = Comment::where('commented_by', $arguments['commented_by'])->get();
			
			$this->status = 'success';
			$this->data = $comments;
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
