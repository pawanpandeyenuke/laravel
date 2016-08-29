<?php
namespace App\Http\Controllers;

use Mail, Config;
use App\Library\Converse;
use App\User, App\Feed, App\Like, App\Comment, Auth, App\EducationDetails, App\Friend, App\Broadcast, App\BroadcastMembers, App\BroadcastMessages, App\ReportUser;
use App\Http\Controllers\Controller;
use App\Country, App\State, App\City, App\Category, App\DefaultGroup, App\Group, App\GroupMembers, App\JobArea, App\JobCategory,App\Forums,App\ForumPost,App\ForumLikes,App\ForumReply,App\ForumReplyLikes,App\ForumReplyComments,App\ForumsDoctor, App\Setting;
use Validator, Redirect, Request, Session, Hash, DB, File;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;
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
				$confirmation_code = str_random(30);
				$input['confirmation_code'] = $confirmation_code;
				$userdata = $user->create($input);
				$full_name = $userdata->first_name.' '.$userdata->last_name;
				//Saving xmpp-username and xmpp-pasword into database.
		        $xmpp_username = $userdata->first_name.$userdata->id;
		        $xmpp_password = 'enuke'; //substr(md5($userdata->id),0,10);

		        $raw_token = $userdata->first_name.date('Y-m-d H:i:s',time()).$userdata->last_name.$userdata->email;
	        	$access_token = Hash::make($raw_token);

		        $user = User::find($userdata->id);
		        $user->xmpp_username = strtolower($xmpp_username);
		        $user->xmpp_password = $xmpp_password;
		        $user->confirmation_code = $confirmation_code;
		        $user->is_email_verified = 'N';
		        $user->access_token = $access_token;
		        $user->save();

		        // Save default settings
		        DB::table('settings')->insert(['setting_title'=>'contact-request','setting_value'=>'all','user_id'=>$user->id]);
        		DB::table('settings')->insert(['setting_title'=>'friend-request','setting_value'=>'all','user_id'=>$user->id]);
        		
		        $useremail = $userdata->email;
		        $emaildata = array(
		            'confirmation_code' => $confirmation_code,
		        );

		        Mail::send('emails.verify',$emaildata, function($message) use($useremail, $full_name){
			        $message->from('no-reply@friendzsquare.com', 'Verify Friendzsquare Account');
			        $message->to($useremail,$full_name)->subject('Verify your email address');
		        });

		        $converse = new Converse;
		        $response = $converse->register($xmpp_username, $xmpp_password);
				$name = $converse->setNameVcard($user->xmpp_username, 'FN', $full_name);

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
		try
		{
			$input = Request::all();
			$validator = Validator::make($input, ['email' => 'required|email']);
			if($validator->fails()) {
				throw new Exception( $this->getError($validator) );
			} 
			else 
			{
				$userEmailCheck = User::whereEmail($input['email'])->first();
				if( !$userEmailCheck ) {
					throw new Exception('No profile was found with this Email.');
				}
				
				$response = app()->make('App\Http\Controllers\Auth\PasswordController')->sendResetPasswordLink( Request::only('email') );
				if( !is_bool( $response ) ) {
					throw new Exception( $response );
				}

				$this->status = 'success';
				$this->message = 'Reset password link sent successfully.';
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

				if( isset( $arguments['id'] ) &&  $arguments['type'] == 'facebook' ){
					$arguments['fb_id'] = $arguments['id'];
					$arguments['src'] = 'fb';
				}
				elseif( isset( $arguments['id'] ) &&  $arguments['type'] == 'twitter' ){
					$arguments['twitter_id'] = $arguments['id'];
					$arguments['src'] = 'twitter';
				}
				elseif( isset( $arguments['id'] ) &&  $arguments['type'] == 'google' ){
					$arguments['google_id'] = $arguments['id'];
					$arguments['src'] = 'google';
				}
				elseif( isset( $arguments['id'] ) &&  $arguments['type'] == 'linkedin' ){
					$arguments['linked_id'] = $arguments['id'];
					$arguments['src'] = 'linked';
				}
				
				$controller = app()->make('App\Http\Controllers\SocialController')->socialLogin($arguments);
				if( $controller && is_object($controller) )
				{
	                // Saving xmpp-username and xmpp-pasword into database.
	                if( !$controller->xmpp_username )
	                {
		                $controller->xmpp_username = strtolower($controller->first_name.$controller->id);
		                $controller->xmpp_password = 'enuke'; //substr(md5($userdata->id),0,10);
		                $controller->save();

				        $converse = new Converse;
				        $response = $converse->register($controller->xmpp_username, $controller->xmpp_password);
				    }

					$this->message = 'Successfully logged in';
					$this->status = 'success';
					$this->data = $controller;
				} elseif($controller && $controller=='verification') {
					$this->message = 'Verification link has been sent to your registered email. Please check your inbox and verify email.';
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
			if( $arguments )
			{
				if( !$arguments['user_by'] ) {
					throw new Exception('User id is required.');
				}

				if( !is_numeric($arguments['user_by']) ) {
					throw new Exception('Invalid user id.');
				}

				if( ( isset($arguments['message']) && $arguments['message'] == null ) && ( $arguments['image'] == null ) ){
					throw new Exception('Please provide a message or image.');
				}


				if(Request::hasFile('image'))
				{
					ini_set('upload_max_filesize', '8M');
					$maxsize = Config::get('constants.max_upload_filesize');

					$file = Request::file('image');
					$bytes = File::size($file);

					if($bytes < $maxsize){
						$image_name = time()."_POST_".strtoupper($file->getClientOriginalName());
						
						$imageRealPath 	= 	$file->getRealPath();
						$img = Image::make($imageRealPath);
						$img->save( public_path('uploads/'). $image_name );

						/** resize image **/
						list($ImageWidth, $ImageHeight) = getimagesize( public_path('uploads/'.$image_name ) );

						if( $ImageHeight > 200 ){
							$SmallSize = 200;
						} else {
							$SmallSize = $ImageHeight;
						}
						$this->resizeImage( Request::file('image'), $SmallSize ,public_path('uploads/thumb-small/') , $image_name ); 

						if( $ImageHeight > 500 ){
							$LargeSize = 500;	
						} else{
							$LargeSize = $ImageHeight;
						}
						$this->resizeImage( Request::file('image'), $LargeSize ,public_path('uploads/thumb-large/') , $image_name );
						
						$arguments['image'] = $image_name;
					}else{
						throw new Exception("Too large image.", 1);						
					}
				}
			}
			 
			$success = $feeds->create( $arguments );

			$this->message = 'Post updated successfully.';
			$this->status = 'success';
			$this->data = $success;

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


// *********** *********** Previous query before [12-aug-2016] *********** *********** //

					/*  $posts = Feed::orderBy('updated_at', 'desc')
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
								->toArray();*/
								
// *********** *********** Previous query before [12-aug-2016] *********** *********** //

					$userBy = $arguments['user_by'];

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
								->with(['likedornot' => function ($query) use($userBy){
										    $query->where('user_id', $userBy);
										}])
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

					if( $posts ){

						$this->status = 'success';
						$this->data['feed'] = $posts;
						$this->data['page_no'] = $arguments['page'];
						$this->data['page_size'] = $arguments['page_size'];

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
 				
	            if( isset($arguments['picture']) && $file != null )
	            {
	                $image_name = time()."_POST_".strtoupper($file->getClientOriginalName());
	                $arguments['picture'] = $image_name;

	                // Resize pic
                    $path = public_path('uploads/user_img/'.$image_name);
                    Image::make($file->getRealPath())->resize(100, 100)->save($path);

                    // upload real pic
	                $file->move(public_path('uploads/user_img'), 'original_'.$image_name);

				 	$ImageData = file_get_contents($path);
				 	$ImageType = pathinfo($path, PATHINFO_EXTENSION);
					$ImageData = base64_encode($ImageData);
				 	$image_name = Converse::setVcard($userfind->xmpp_username, $ImageData, $ImageType);
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

			if(isset($arguments['education'])){

				$delete = EducationDetails::where('user_id', '=', $arguments['id'])->delete();

				$data = array();

				$educationdata = $arguments['education'];

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
				
				$userdata = User::find($arguments['id']);
				$full_name = $userdata->first_name.' '.$userdata->last_name;

 				Converse::setNameVcard($userdata->xmpp_username, 'FN', $full_name);

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
				else{
					unset($arguments['image']);
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

				// $deleteFeed = Feed::where('id', $arguments['id'])->delete();
				// $deleteFeed = onDeletePosts();
				$deletePosts = new Converse;
				$deleteFeed = $deletePosts->onDeletePosts($arguments['id'], $newsFeed->user_by);
				
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
					->get();

			$this->data = $friends;
			$this->status = 'success';
			$this->message = count($friends).' friends found.';

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();
		
	}
 

	/*
	 * Get user's list i have sent a request.
	 */ 
	public function getSentUsersList()
	{
		try{
			$arguments = Request::all();
			$user = User::find($arguments['id']);

			if(empty($user))
				throw new Exception("This user does not exist", 1);

			$friends = Friend::with('user')
					->with('friends')
					->where('user_id', '=', $arguments['id'])
					// ->orWhere('friend_id', '=', $arguments['id'])
					->where('status', '=', 'Pending')
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


	public function removeFriend()
	{
		try{
			$arguments = Request::all();
			$user = User::find($arguments['user_id']);
			$friend = User::find($arguments['friend_id']);

			if(empty($user))
				throw new Exception("This user does not exist", 1);
			if(empty($friend))
				throw new Exception("This friend does not exist", 1);
			
			
			$f1 = Friend::where('user_id', '=', $arguments['user_id'])
					->where('friend_id', '=', $arguments['friend_id'])
					->where('status', '=', 'Accepted')->value('id');
			
			if($f1 == null)
			{
				throw new Exception("These users are not friends.", 1);	
			}
			else
			{
			$friends = Friend::with('user')
					->with('friends')
					->where('user_id', '=', $arguments['user_id'])
					->where('friend_id', '=', $arguments['friend_id'])
					->where('status', '=', 'Accepted')
					->delete();
			
			$friends = Friend::with('user')
					->with('friends')
					->where('friend_id', '=', $arguments['user_id'])
					->where('user_id', '=', $arguments['friend_id'])
					->where('status', '=', 'Accepted')
					->delete();
			// print_r($friends);exit;


				$Message = json_encode( array( 'type' => 'unfriend','message' => 'You removed from friend list.' ) );
				Converse::broadcast($user->xmpp_username,$friend->xmpp_username,$Message);
				Converse::broadcast($friend->xmpp_username,$user->xmpp_username,$Message);
			}
			$this->data = true;
			$this->status = 'success';
			$this->message = 'Friend removed successfully.';

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

			$user = User::where('id', '=', $arguments['user_id'])->get()->toArray();
			$friend = User::where('id', '=', $arguments['friend_id'])->get()->toArray();

			if(empty($user))
				throw new Exception("This user does not exist", 1);

			if(empty($friend))
				throw new Exception("This user does not exist", 1);

			if( $arguments['user_id'] === $arguments['friend_id'] )
				throw new Exception("You cannot send request to yourself.", 1);

			$friendcheck = Friend::where('user_id', '=', $arguments['user_id'])
							->where('friend_id', '=', $arguments['friend_id'])
							->get()
							->toArray();

			$friendcheck1 = Friend::where('user_id', '=', $arguments['friend_id'])
							->where('friend_id', '=', $arguments['user_id'])
							->get()
							->toArray();

			if( !empty($friendcheck) || !empty($friendcheck1)){
				if(!empty($friendcheck))
				{
				if(	$friendcheck[0]['status']== 'Rejected')
				{
					Friend::where('user_id', '=', $arguments['user_id'])
							->where('friend_id', '=', $arguments['friend_id'])
							->update(['status'=>'Pending']);
					$friendcheck[0]['status']='Pending';
					$msg = "Friend request sent";	
				}else{
					$msg = "Friend request has already been sent.";
				}

				$this->data = $friendcheck;
				$this->status = 'success';
				$this->message = $msg;
			}
			if(!empty($friendcheck1))
			{
				if(	$friendcheck1[0]['status']== 'Rejected')
				{
					Friend::where('user_id', '=', $arguments['user_id'])
							->where('friend_id', '=', $arguments['friend_id'])
							->update(['status'=>'Pending']);
					$friendcheck1[0]['status']='Pending';
					$msg = "Friend request sent";	
				}else{
					$msg = "Already recieved a friend request from the user.";
				}
				$this->data = $friendcheck;
				$this->status = 'success';
				$this->message = $msg;
			}

			}else{

				$friend = new Friend;
				$friend->status = 'Pending';
				$request = $friend->create($arguments);
				//print_r($request);exit;
				$this->data = $request;
				$this->status = 'success';
				$this->message = 'Friend request sent.';

			}

            // @ Send push notification on send request action

			$response = Converse::notifyMe( $arguments['user_id'], $arguments['friend_id'], 'request' );

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

			if( $arguments['user_id'] === $arguments['friend_id'] )
				throw new Exception("You cannot add yourself as a friend.", 1);

			$friendcheck = Friend::where('user_id', '=', $arguments['friend_id'])
							->where('friend_id', '=', $arguments['user_id'])
							->where('status', '=', 'Pending')
							->get()
							->toArray();

			$friendcheck2 = Friend::where('user_id', '=', $arguments['user_id'])
							->where('friend_id', '=', $arguments['friend_id'])
							->where('status', '=', 'Pending')
							->get()
							->toArray();

			if(!empty($friendcheck2)){
				throw new Exception("You can't accept request", 1);
			}
				
			if(!empty($friendcheck)){

				DB::table('friends')
					->where('user_id', '=', $arguments['friend_id'])
					->where('friend_id', '=', $arguments['user_id'])
					->update(['status' => 'Accepted']);

				$friend = new Friend;
				$friend->status = 'Accepted';
				$friend->friend_id = $arguments['friend_id'];
				$friend->user_id = $arguments['user_id'];
				$request = $friend->save();

				// Add friends to roster in API.
				$arrayOfIds = array($arguments['user_id'], $arguments['friend_id']);
				$udetail = User::whereIn('id', $arrayOfIds)->get()->toArray();
				$converse = new Converse;
				$converse->addFriend($udetail[0]['xmpp_username'], $udetail[1]['xmpp_username'], $udetail[1]['first_name'], $udetail[0]['first_name']);   
				// Add friends to roster in API.

				$this->data = $request;
				$this->status = 'success';
				$this->message = 'Friend request accepted.';

                // @ Send push notification on request accept action
				$response = Converse::notifyMe( $arguments['user_id'], $arguments['friend_id'], 'accept' );

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

			$friendcheck = Friend::where('user_id', '=', $arguments['user_id'])
							->where('friend_id', '=', $arguments['friend_id'])
							->where('status', '=', 'Pending')
							->get();

			$friendcheck1 = Friend::where('user_id', '=', $arguments['friend_id'])
							->where('friend_id', '=', $arguments['user_id'])
							->where('status', '=', 'Pending')
							->get();

			
			
			if( !empty($friendcheck) || !empty($friendcheck1)){
				if(!empty($friendcheck))
				{
					DB::table('friends')
						->where('user_id', '=', $arguments['user_id'])
						->where('friend_id', '=', $arguments['friend_id'])
						->delete();
					$this->status = 'success';
					$this->message = 'Request cancelled.';		
				}
				if(!empty($friendcheck1))
				{
					$request = DB::table('friends')
					->where('user_id', '=', $arguments['friend_id'])
					->where('friend_id', '=', $arguments['user_id'])
					->update(['status' => 'Rejected']);
					$this->data = $request;
					$this->status = 'success';
					$this->message = 'Friend request declined.';
				}
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
		$path = public_path().''.'/uploads/media/chat_images/';
		
		$uploadedfile = $_FILES['chatsendimage']['tmp_name'];
		$name = $_FILES['chatsendimage']['name'];
		$size = $_FILES['chatsendimage']['size'];
		$valid_formats = array("jpg", "JPG", "jpeg", "JPEG", "png", "PNG", "gif", "GIF");

		if (strlen($name)) {
			list($txt, $ext) = explode(".", $name);
			if (in_array($ext, $valid_formats)) {
				$actual_image_name = "chatimg_" . time() . substr(str_replace(" ", "_", $txt), 5) . "." . $ext;
				
				$this->resizeImage( Request::file('chatsendimage'), '300' , $path.'thumb/' , $actual_image_name );
				$tmp = $uploadedfile;

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

	private function  resizeImage ($image, $size , $path, $imagename = '')
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
	    	$img->resize(null, intval($size) , function($constraint) {
	    		 $constraint->aspectRatio();
	    	});
	    	
	    	return $img->save($path. $thumbName);
    	}
    	catch(Exception $e)
    	{
    		return false;
    	}
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
			$groupby = Request::get('group_by');
			if( !empty( $groupname ) ){				

				$groupcheck = DefaultGroup::where('group_name', '=', $groupname)
									->where('group_by', '=', $groupby)
									->get()->toArray();
				
				if(empty($groupcheck)){

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
 
				}else{ 
					$groupby = Request::get('group_by');

					if(!isset($groupby))
						throw new Exception("Group by is a required field.", 1);

					$finduser = User::find($groupby);
					
					if(empty($finduser))
						throw new Exception("This user does not exist.", 1);
 
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
	 * update braodcasts.
	 */
    public function broadcastUpdate()
    {
 	 	try
 	 	{
 	 		$id = Request::get('id');
 	 		$user_id = Request::get('user_id');
 	 		$broadcast_name = Request::get('broadcast_name');
 	 		$broadcast_members = Request::get('broadcast_members');

 	 		if( empty( $id ) )
 	 			throw new Exception("Broadcast id is required.", 1);
 	 			
			if( empty( $user_id ) )
				throw new Exception("User id is required.", 1);

			$user = User::find($user_id)->toArray();

 	 		if( empty( $user ) )
 	 			throw new Exception("User does not exist.", 1);

 	 		$broadcast = Broadcast::where(['id' => $id, 'user_id' => $user_id])->get()->toArray();

 	 		if( empty( $broadcast ) )
 	 			throw new Exception("Broadcast does not exist.", 1);

 	 		if( !empty( $broadcast_name ) ){
 	 			Broadcast::where('id', $id)->update(['title' => $broadcast_name]);      	
 	 			// echo '<pre>';print_r($broadcast_name);die;
 	 		}

 	 		if( !empty( $broadcast_members ) ){

 	 			BroadcastMembers::where('broadcast_id', $id)->delete();

 	 			$invalid_users = array();
 	 			foreach ($broadcast_members as $key => $value) {

 	 				$existingUser = User::find($value);
 	 				$notAFriend = Friend::where('user_id', $user_id)
 	 										->where('friend_id', $value)
 	 										->where('status','Accepted')
 	 										->get();

 	 				if( !$existingUser ){
 	 					$invalid_users[] = $value;
 	 				}else{
 	 					if(!$notAFriend->isEmpty()){
		 	 				$bMemberObj = new BroadcastMembers;
		 	 				$bMemberObj->broadcast_id = $id;
		 	 				$bMemberObj->member_id = $value;
		 	 				$bMemberObj->save();
 	 					}else{
 	 						$invalid_users[] = $value;
 	 					}
 	 				}

 	 			}
 	 			// echo '<pre>';print_r($saved);die;
 	 		}

 			if($invalid_users){
 				$users = implode(',', $invalid_users);
 				$message = '{'.$users.'} are invalid user ids or might not be friend with you.';
 			}else{
 				$message = "Broadcast updated.";
 			}

            $this->status = "success";
            $this->message = $message;
            $this->data = Broadcast::find($id)->toArray();

		}catch(Exception $e){
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

 					 $xmppusername = User::where('id',$arguments['group_by'])->value('xmpp_username');
               				 $converse = new Converse;
                			 $response = $converse->removeUserGroup($arguments['group_name'], $xmppusername);

					 //$xmppusername = User::where('id',$arguments['group_by'])->value('xmpp_username');
	        		         //$converse = new Converse;
        			         //$response = $converse->removeUserGroup($arguments['group_name'], $xmppusername);

					$group = DefaultGroup::where([
									'group_name' => $arguments['group_name'],
									'group_by' => $arguments['group_by']
									])
								->delete();

					$this->data = $arguments;
					$this->message = $arguments['group_name'].' successfully deleted.' ;
					$this->status = 'success';

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

	            		$groupsDataCount = GroupMembers::where(['member_id' => $input['owner_id'],'status' => 'Joined'] )->get();

	            		if($groupsDataCount->count() >= Config::get('constants.private_group_limit'))
	            			throw new Exception("You have reached the limit of creating private groups.", 1);

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

	            		if($error!=0){
	            			throw new Exception($error." is not a friend and can't be added to group");	
	            		}else {
	            			array_push($input['member_id'],$input['owner_id']);
	            			$data = array(
				                        'title'=>$input['title'],
				                        'owner_id'=>$input['owner_id'],
				                        'group_jid' =>$input['group_jid'],
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
	 * Upload private groups picture.
	 */
	public function privateGroupImageUpload()
	{
		try{
			
			if(Request::hasFile('picture')){
				$file = Request::file('picture');
				$image_name = time()."_pvt_grp_".strtoupper($file->getClientOriginalName());
				$status = $file->move('uploads', $image_name);

				$this->message = 'Image uploaded successfully.';
				$this->data = $image_name;
				$this->status = 'success';
				
			}else{
				throw new Exception("Image is required.", 1);				
			}

		}catch(Exception $e){
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
					
				$groupowner = GroupMembers::where('member_id', $arguments['owner_id'])->get()->toArray();

				if(empty($groupowner)){
					$this->status = 'success';
					throw new Exception("No private groups found.", 1);
				}

				$groupIdsData = Group::with('groupMembers')
									->whereIn('id', GroupMembers::where('member_id', $arguments['owner_id'] )->pluck('group_id')->toArray() )
									->get()
									->toArray();

				$JoinedGroupsCount = GroupMembers::where('member_id',$arguments['owner_id'])->where('status', 'Joined')->get()->count();

	            $MaxGroupLimit = Config::get('constants.private_group_limit');
				
				$this->data = array( 'max_group_limit' =>$MaxGroupLimit,'joined_groups_count'=>$JoinedGroupsCount,'data' => $groupIdsData );
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
			
			$group_jid = Request::get('group_jid');
			$user_id = Request::get('user_id');

			if(empty($group_jid))
				throw new Exception("Group jid is required.", 1);

			if(empty($user_id) || !is_numeric($user_id))
				throw new Exception("User id is required.", 1);

			$authuser = User::find($user_id);

			if(empty($authuser))
				throw new Exception("User does not exist.", 1);
				
			$findgroup = Group::where('group_jid', $group_jid)->first();

			if(empty($findgroup))
				throw new Exception("Group does not exist.", 1);

			// Send hint on remove group.
				$converse 		= new Converse;
				$userJid 		= $authuser->xmpp_username; // current user jid for chat message
				$name 			= $authuser->first_name.' '.$authuser->last_name; // current user full name
				$message 		= json_encode( array( 'type' => 'hint', 'action'=>'group_delete', 'sender_jid' => $userJid, 'groupname'=> $findgroup->title, 'groupjid' => $findgroup->group_jid, 'message' => webEncode($findgroup->title.' has been removed.') ) ); // hint message to send every group member
				$xmp 			= GroupMembers::leftJoin('users', 'members.member_id', '=', 'users.id')->where('members.group_id', $findgroup->id)->pluck('xmpp_username');		
				foreach ($xmp as $key => $value) {
					$converse->broadcastchatroom( $findgroup->group_jid, $name, $value, $userJid, $message ); // message broadcast per group member
				}
				$converse->deleteGroup($findgroup->group_jid); // Delete group from chat server
			// Send hint on remove group.			

			$findgroup = Group::find($findgroup->id)->delete();

			$this->data = $findgroup;
			$this->message = 'Group successfully deleted.' ;
			$this->status = 'success';

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();

	}
 

	/*
	 * Join Private Group API.
	 */
	public function joinPrivateGroup()
	{
		try
		{
			$group_jid = Request::get('group_jid');
			$member_id = Request::get('member_id');

			if( empty( $group_jid ) )
				throw new Exception("Group jid is required.", 1);				

			$group = Group::where('group_jid', $group_jid)->first();

			if( !$group )
				throw new Exception("Group does not exist.", 1);

			$user = User::find($member_id);

			if( !$user )
				throw new Exception("User does not exist.", 1);

			$group_members = GroupMembers::where(['group_id' => $group->id, 'member_id' => $member_id])->count();

			if( $group_members > 0 ){

				$status = GroupMembers::where(['group_id' => $group->id, 'member_id' => $member_id])
												->update(['status' => 'Joined']);

				// Broadcast message
                //$members = GroupMembers::where(['group_id' => $group->id])->get();
                $members = GroupMembers::leftJoin('users', 'members.member_id', '=', 'users.id')->where('members.group_id', $group->id)->pluck('xmpp_username');
               	
                $name = $user->first_name.' '.$user->last_name;
                $message = json_encode( array( 'type' => 'hint', 'action'=>'join', 'sender_jid' => $user->xmpp_username,'xmpp_userid' => $user->xmpp_username,'user_id' => $user->id, 'user_image' => $user->picture, 'user_name'=>$name, 'message' => $name.' joined the group') );
                foreach($members as $memberxmpp) {
                    Converse::broadcastchatroom($group->group_jid, $name, $memberxmpp, $user->xmpp_username, $message);
                };

				if( $status ){
					$this->data = $status;
					$this->message = 'Joined private group successfully.' ;
					$this->status = 'success';
				}
			}

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();

	}


	/*
	 * Add members private groups.
	 */
	public function addMembersPrivateGroup()
	{
		try{
			$group_jid = Request::get('group_jid');
			$members = Request::get('members');
			$owner_id = Request::get('user_id');

			if( empty( $group_jid ) )
				throw new Exception("Group jid is required.", 1);	

			$group = Group::where('group_jid', $group_jid)->first();
			if( !$group )
				throw new Exception("Group does not exist.", 1);

			if( empty( $members ) )
				throw new Exception("No members found.", 1);

			$old_member = User::whereIn('id', GroupMembers::where('group_id', $group->id)->pluck('member_id')->toArray())->get()->toArray();

			$invalid_users = array();
			$alreadyMemberArray = array();
			$new_members = array();

			foreach ($members as $key => $value) {
	 			
	 			$existingUser = User::find($value);
	 			$AFriend = Friend::where('user_id', $owner_id)
	 										->where('friend_id', $value)
	 										->where('status','Accepted')
	 										->get()->toArray();

	 			if( !empty($existingUser) && $AFriend ){
		 			$alreadyMember = GroupMembers::where(['group_id' => $group->id, 'member_id' => $value])->get()->toArray();
		 			if( empty($alreadyMember) ){
	 						$new_members[] = $existingUser;
	 	 					$privateGroupMemberObj = new GroupMembers;
		 	 				$privateGroupMemberObj->group_id = $group->id;
		 	 				$privateGroupMemberObj->member_id = $value;
		 	 				$privateGroupMemberObj->status = 'Pending';
		 	 				$privateGroupMemberObj->save();

	 				} else if( isset($alreadyMember['status']) && $alreadyMember['status'] == 'Left' ){
	 					$new_members[] = $existingUser;
	 					$data = GroupMembers::where(['group_id' => $group->id, 'member_id' => $value])
												->update(['status' => 'Pending']);
	 				}
				}
			}


			// Send Message
				$owner_data = User::find($owner_id);
				$name = $owner_data->first_name.' '.$owner_data->last_name;
				$members = User::whereIn('id', GroupMembers::where('group_id', $group->id)->pluck('member_id')->toArray())->select('id as user_id', DB::raw('CONCAT(first_name, " ", last_name) AS username'), 'xmpp_username as xmpp_userid','picture as user_image')->get()->toArray();

				$message = json_encode( array( 'user_id' => $owner_data->id, 'user_image'=> $owner_data->picture ,'type' => 'room', 'groupname' => $group->title, 'sender_jid' => $owner_data->xmpp_username, 'groupjid'=>$group_jid, 'group_image' => $group->picture, 'created_by'=>$name,'message' => 'This invitation is for joining the '.$group->title.' group.', 'users' => $members) );
                
                foreach($new_members as $val){
                    Converse::broadcast($owner_data->xmpp_username, $val->xmpp_username, $message);
                }


	            foreach($new_members as $key1 => $val1) 
	            {
	            	// die('kill');
	            	$message = json_encode( array( 'type' => 'hint', 'sender_jid' => $owner_data->xmpp_username, 'action'=>'add','user_id' => $val1->id, 'user_image' => $val1->picture,'message' => $val1->first_name.' '.$val1->last_name.' has invited for joining the group', 'xmpp_userid' => $val1->xmpp_username, 'user_name' => $val1->first_name.' '.$val1->last_name, 'user_id' => $val1->id) );

	            	foreach($old_member as $key => $val) 
	                {
	                    Converse::broadcastchatroom($group->group_jid, $name, $val['xmpp_username'], $owner_data->xmpp_username, $message);
	                }
	            }
			// Send Message

            $this->status = "success";
            $this->message = "Members updated successfully.";
            $this->data = '';//GroupMembers::where('group_id', $group->id)->get()->toArray();

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();

	}


	/*
	 * Leave/Delete Private Group API.
	 */
	public function leavePrivateGroup()
	{
		try
		{
			$group_jid = Request::get('group_jid');
			$owner_id = Request::get('owner_id');
			$member_id = Request::get('member_id');

			$owner = User::find($owner_id);

			if( !$owner )
				throw new Exception("User does not exist.", 1);				

			$member = User::find($member_id);

			if( !$member )
				throw new Exception("Member does not exist.", 1);		

			$group = Group::where(['group_jid' => $group_jid])->first();

			if( !$group )
				throw new Exception("Group does not exist.", 1);

			if( $group->owner_id == $member_id)
				throw new Exception("You can't leave the group.", 1);
				
			$member_name = $member->first_name.' '.$member->last_name;

			$group_members = GroupMembers::where(['group_id' => $group->id, 'member_id' => $member_id])->count();
			if( $group_members > 0 )
			{
				// Broadcast message
				$action = ($owner_id == $member_id) ? 'leave' : 'delete';
				$name = $owner->first_name.' '.$owner->last_name;
				$msg = ($owner_id == $member_id) ? $name.' left the group' : $name.' removed from the group';
				
				$members = User::whereIn('id', GroupMembers::where('group_id', $group->id)->where('status', '!=', 'Left')->pluck('member_id')->toArray())->get()->toArray();

				$data = GroupMembers::where(['group_id' => $group->id, 'member_id' => $member_id])
												->update(['status' => 'Left']);

				$message = json_encode( array( 'type' => 'hint', 'sender_jid' => $owner->xmpp_username,'action'=>$action, 'xmpp_userid' => $member->xmpp_username, 'user_name'=>$member_name, 'message' => $msg) );
                foreach($members as $key => $val) {
                	
                    Converse::broadcastchatroom($group->group_jid, $name, $val['xmpp_username'], $owner->xmpp_username, $message);
                }

				$this->message = 'Successfully left the group.';
			}

			$this->data = $data;
			$this->status = 'success';
			

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();

	}



	/*
	 * Update Private Group API.
	 */
	public function updatePrivateGroup()
	{
		try
		{
			$group_jid = Request::get('group_jid');
			$title = Request::get('title');
			$picture = Request::get('picture');
			$user_id = Request::get('user_id');

			if( empty( $group_jid ) )
				throw new Exception("Group jid is required.", 1);

			if( empty( $user_id ) )
				throw new Exception("User id is required.", 1);

			$group = Group::where(['group_jid' => $group_jid])->first();

			if( !$group )
				throw new Exception("Group does not exist.", 1);
				
			$user = User::find($user_id);

			if( !$user )
				throw new Exception("User does not exist.", 1);

			$arguments = array('title' => $title, 'picture' => $picture);

			foreach ($arguments as $key => $value) {
				if( empty( $value ) )
					unset( $arguments[$key] );
			}


			$changed = array();
			$nameChanged = $imageChanged = false;
			// Check if group nam has changed or not
		    if($title != $group->title){
		        $nameChanged = true;
		        $changed[] = 'group name';
		    }

		    // Check if group image has changed or not
		    if($picture != $group->picture){
		        $imageChanged = true;
		        $changed[] = 'group icon';
		    }

			$group->fill($arguments);
			$saved = $group->push();

			// Send hint message

			    
			    // Broadcast message
			    if($changed)
			    {
			    	// echo '<pre>';print_r($group->title);die;
			        // $members = GroupMembers::where(['group_jid' => $group_jid])->pluck('user_jid');
			        $members = User::whereIn('id', GroupMembers::where('group_id', $group->id)->pluck('member_id')->toArray())->get()->toArray();

			        $ChatUser = $user->xmpp_username;
			        $name = $user->first_name.' '.$user->last_name;
			        foreach($members as $key => $val) 
			        {
			            $message = array( 'type' => 'hint', 'sender_jid' => $ChatUser, 'action'=>'group_info_change','message' => $name.' changed '.implode(' and ', $changed), 'changeBy' => $name, 'group_jid'=>$group_jid);
			            if($imageChanged){
			                $message['group_image'] = $picture;
			            }
			            if($nameChanged){
			                $message['groupname'] = $title;
			            }
			            Converse::broadcastchatroom($group_jid, $name, $val['xmpp_username'], $ChatUser, json_encode($message));
			        }
			    }
			// Send hint message

			$this->data = $saved;
			$this->status = 'success';
			$this->message = 'Updated group successfully';

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
	 * Search friends on site.
	 */
	public function searchSiteFriends()
	{
		try{
			$input = Request::all();

			$per_page = $input['page_size'];
			$page = $input['page'];
			$offset = ($page - 1) * $per_page;

			$model = new User;

            // Search for the following people.
          	if(trim($input['keyword']) != ''){

	          	$model = $model->where( function( $query ) use ( $input ) {	          		
	          		$expVal = explode(' ', $input['keyword']);
	          		foreach( $expVal as $key => $value ) {  			        	
			           	$query->orWhere( 'last_name', 'LIKE', '%'. $value.'%' )
			           	 	->orWhere( 'first_name', 'LIKE', '%'. $value.'%' );  
			        }
				});

	        }

           	if( isset( $input['user_id'] ) ){
				
				// User cannot search himself.
            	$model = $model->where('id', '!=', $input['user_id']);

            	// Search for user's who are not friends with me.
	        	$model = $model->whereNotIn('id', Friend::where('user_id', '=', $input['user_id'])
	                            ->where('status', '=', 'Accepted')
	                            ->pluck('friend_id')
	                            ->toArray() );

	        }

	        // Gather all the results from the queries and paginate it.
	     	$model = $model->select('id', 'first_name', 'last_name', 'email', 'picture');
	     	$model = $model->orderBy('id','desc');
	     	$result = $model->skip($offset)->take($per_page)->get()->toArray();

			$this->status = 'success';
			$this->data = $result;
			$this->message = count($result).' users found.';

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();

	}


	/*
	 * Return Non Existing Email Ids. 
	 */
	public function returnNonExistingEmails()
	{
		try{
			$arguments = Request::get('emails');

			// User's email check
			$nonExistingUsers = [];
			foreach ($arguments as $key => $email) {
				$userEmailCheck = User::where('email', $email)->get();
				if($userEmailCheck->isEmpty()){
					$nonExistingUsers[] = $email;
				}
			}

			$this->status = 'success';			
			$this->message = count($nonExistingUsers).' Users found.';
			$this->data = $nonExistingUsers;

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();
	}	


	/*
	 * Invitation by Email. 
	 */
	public function inviteByEmail()
	{
		try{
			$arguments = Request::all();
			if($arguments){

				$user = User::find($arguments['user_id']);
				$emailsArray = $arguments['emails'];

				if(empty($user))
					throw new Exception("User does not exist", 1);

				if(empty($emailsArray))
					throw new Exception("Atleast one email id is required", 1);

				if($emailsArray){
					foreach ($emailsArray as $key => $value) {

						$validator=null;
						$validator = Validator::make($emailsArray, [
							$key => 'required|email'
						]); 
						$validator->each($key, ['required', 'email']);
								               
						if($validator->fails()) {
							throw new Exception("Please check email address entered and try again.", 1);
						}else{

							foreach ($emailsArray as $value) {
								if($value != User::where('id',$arguments['user_id'])->pluck('email')){
									$message = 'Hi, Take a look at this cool social site "FriendzSquare!"';
									self::mail($value, $message, 'Invitation', 'Friend',$arguments['user_id']); 
								}
							}
						}
					}
					
					$this->status = "success";
					$this->message = "Invitation emails sent successfully!";
					$this->data = true;

				}
			}
		}
		catch(Exception $e)
		{
			$this->message = $e->getMessage();
		}

		return $this->output();
	}	


	/*
	 * Get Job Area Category.
	 */
	public function getJobCategories()
	{

		$categories = JobArea::with('getJobCategories')->get()->toArray();

		foreach($categories as $key => $val)
		{
			foreach($val['get_job_categories'] as $key1 => $val1) {
				$this->data[$val['job_area']][] = $val1['name'];
			}
		}
		$this->status = 'success';
		$this->message = null;
		return $this->output();
	}


	/*
	 * Get Job Area Category.
	 */
	public function getUserByJID()
	{
		try{

			$userJID = Request::get('user_jid');

			$data = User::where('xmpp_username', $userJID)->select('id', 'first_name', 'last_name', 'email', 'xmpp_username', 'picture')->get();

			if($data->isEmpty())
				throw new Exception("No user exists with ".$userJID." JID.", 1);
			
			$this->status = 'success';
			$this->message = null;
			$this->data = $data;

		}catch(Exception $e){

			$this->message = $e->getMessage();

		}

		return $this->output();

	}	


	/*
	 * @ Forums api starts from here.
	 * @ Get forum posts API.
	 */
	public function getForumPosts()
	{
		try{

			$breadcrumb = Request::get('breadcrumb');
			$keyword = Request::get('keyword');
			$access_token = Request::get('access_token');
			$user_id = Request::get('user_id');

			$breadcrumb = urldecode($breadcrumb);

			$posts = ForumPost::with('user')->with('forumPostLikesCount')->with('replyCount');

			if($keyword){
				$posts = $posts->whereRaw( 'title like ?', array("%".$keyword."%"));
				// $posts = $posts->whereRaw( 'LOWER(`title`) like ?', array("%".$keyword."%"));
			}
			
			if($breadcrumb){
				$posts = $posts->where('forum_category_breadcrum', 'like', $breadcrumb."%");
			}

			$posts = $posts->orderBy('updated_at','DESC')->get(); //->toArray();
			// echo '<pre>';print_r($posts);die;

	        if($user_id != ""){
				$user_check = User::where('id',$user_id)->first();

				if($user_check == ""){
					return view('forums-api.forum-not-found')->with('message', 'No such user exist.')->render();
				}else{
					if($access_token != $user_check->access_token)
					return view('forums-api.forum-not-found')->with('message', 'Unauthorized user.')->render();	
				}
			}

			if($posts->isEmpty()){
				return view('forums-api.forum-not-found')->with('message', 'Post does not exist.')->render();
			}

			return view('forums-api.forum-posts')
					->with('posts', $posts->take(5))
					->with('user_id', $user_id)
					->render();

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();
	}


	/*
	 * @ Get forum posts replies API.
	 */
	public function getForumPostsReply()
	{
		try{

			$post_id = Request::get('post_id');
			$user_id = Request::get('user_id');
			$access_token = Request::get('access_token');
		        $checkpost = ForumPost::with('user')
	                        ->with('forumPostLikesCount')
	                        ->where('id',$post_id)
	                        ->first();

//print_r($checkpost);die;
			if(empty($checkpost)){
				return view('forums-api.forum-not-found')->with('message', 'Reply does not exist.')->render();
			}
			if($user_id != ""){      
			$user_check = User::where('id',$user_id)->first();
			  // print_r($user_check->access_token);die;

			if($user_check == "")
				return view('forums-api.forum-not-found')->with('message', 'No such user exist.')->render();
			else{
				if($access_token != $user_check->access_token)
				 return view('forums-api.forum-not-found')->with('message', 'Unauthorized user.')->render();	
			}
			}


	        $replies = ForumReply::with('user')
	                ->with('replyLikesCount')
	                ->with('replyCommentsCount')
	                ->where('post_id',$post_id)
	                ->orderBy('updated_at','DESC')
	                ->get();

			return view('forums-api.forum-post-reply')
					->with('replies', $replies->take(10))
					->with('checkpost', $checkpost)
					->with('user_id', $user_id)
					->render();

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}
		
	}


	/*
	 * @ Get forum posts reply comment API.
	 */
	public function getForumPostsReplyComment()
	{
		try{

			$reply_id = Request::get('reply_id');
			$user_id = Request::get('user_id');
			$access_token = Request::get('access_token');

		    $reply = ForumReply::with('user')
					    ->with('replyLikesCount')
					    ->with('replyCommentsCount')
					    ->where('id', $reply_id)
					    ->first();

			if(empty($reply)){
				return view('forums-api.forum-not-found')->with('message', 'Post does not exist.')->render();
			}
			
			if($user_id != ""){      
			$user_check = User::where('id',$user_id)->first();
			  // print_r($user_check->access_token);die;

			if($user_check == "")
				return view('forums-api.forum-not-found')->with('message', 'No such user exist.')->render();
			else{
				if($access_token != $user_check->access_token)
				 return view('forums-api.forum-not-found')->with('message', 'Unauthorized user.')->render();	
			}
			}


		    $replyComments = ForumReplyComments::with('user')->where('reply_id', $reply_id)->get();
 
			// echo '<pre>';print_r($replyComments);die;
			return view('forums-api.forum-post-reply-comments')
					->with('reply', $reply)
					->with('replyComments', $replyComments)
					->with('user_id',$user_id)
					->render();

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}
		
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


	public function mail($email = '', $message, $subject, $type,$userid) {

		$userdata = User::find($userid);
		$username = $userdata->first_name.' '.$userdata->last_name;

		$data = array(
				'message' => $message,
				'subject' => $subject,
				'id' => $userid,
				'type' => $type,
				'username' => $username,
			);

	        if($email != ''){
				Mail::send('emails.invite', $data, function($message) use($email, $subject) {
					$message->from('no-reply@friendzsquare.com', 'Friend Square');
					$message->to($email)->subject($subject);
				});
	        }
	    }


	/*
	* @Forum web api's starts from here.
	*
	*/
	public function getForumCategories()
	{
		$this->data = Forums::orderBy('display_order')->get();
		$this->status = 'success';
		$this->message = null;
		
		return $this->output();
	}


	public function getDoctorCategories()
	{
		$this->data = ForumsDoctor::all();
		$this->status = 'success';
		$this->message = null;
		
		return $this->output();
	}


	public function postForum()
	{
		try{
			$args = Request::all();
			$user = User::where('id',$args['user_id'])->get();
			if($user->isEmpty())
				throw new Exception("No matching record for the user.", 1);
			else{

				if($args['post'] == "")
					throw new Exception("Post can't be empty.", 1);
					
					$forum_category_breadcrum = $args['breadcrumb'];
					$id_array = explode(" > ", $forum_category_breadcrum);

					foreach ($id_array as $key => $value) {
					$id_array[$key] = Forums::where('title',$value)->value('id');
					Forums::where('id',$id_array[$key])->update(['updated_at'=>date('Y-m-d H:i:s',time())]);      	
					$cat_id = $id_array[$key];
					}
					$forum_category_id = implode(",", $id_array);

					if($cat_id == null)
					$cat_id = "opt";

					$data = ['title'=>$args['post'],
					'owner_id'=>$args['user_id'],
					'category_id'=>$cat_id,
					'forum_category_id'=>$forum_category_id,
					'forum_category_breadcrum'=>$forum_category_breadcrum,
					'created_at'=>date('Y-m-d H:i:s',time()),
					'updated_at'=>date('Y-m-d H:i:s',time())];

					$forumpost = new Forumpost;
					$this->message = 'Your forum post has been saved successfully.';
        			$this->status = 'success';
					$post = $forumpost->create($data);
					$this->data = ForumPost::with('user')
						                    ->with('forumPostLikesCount')
						                    ->with('replyCount')
						                    ->where('id',$post->id)
						                    ->get();
			}
				
		}
		catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();		
	}


	public function editForumPost()
	{
		try{
			$args = Request::all();
			$user = User::where('id',$args['user_id'])->get();
			if($user->isEmpty())
				throw new Exception("No matching record for the user.", 1);
			else{
				$post_check = ForumPost::where('id',$args['post_id'])->first();
				//print_r($post_check);die;
				if(!isset($post_check->id))
					throw new Exception("No such forum post exist.", 1);
				else{
					$reply_count = ForumReply::where('post_id',$post_check->id)->get()->count();
					if($post_check->owner_id != $args['user_id'])
						throw new Exception("This user is not the owner of the forum post.", 1);
					else if($reply_count > 0)
						throw new Exception("This post can't be edited because the post has a reply on it.", 1);
					else{
						ForumPost::where('id',$args['post_id'])->update(['title' => $args['post']]);
						$this->status = "success";
						$this->message = "Post updated successfully.";
						$this->data = ForumPost::with('user')
				                    ->with('forumPostLikesCount')
				                    ->with('replyCount')->where('id',$args['post_id'])->get();
					}

				}
					  
			}
				
		}
		catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();	
	}


	public function postForumReply()
	{
		try{
			$args = Request::all();
			$user = User::where('id',$args['user_id'])->get();
			if($user->isEmpty())
				throw new Exception("No matching record for the user.", 1);
			else{
				$post_check = ForumPost::where('id',$args['post_id'])->value('id');
				if($post_check == null)
					throw new Exception("No such forum post exist.", 1);
				else{
					$data = ['reply'=>$args['reply'],
                        'owner_id'=>$args['user_id'],
                        'post_id'=>$args['post_id'],
                        'created_at'=>date('Y-m-d H:i:s',time()),
                        'updated_at'=>date('Y-m-d H:i:s',time())];

			        // @ Send notification mail.
			        $parameters = array('user_id' => $args['user_id'], 'current_data' => $args['reply'], 'object_id' => $args['post_id'], 'type' => 'reply');
			        $notify = Converse::notifyOnReplyComment( $parameters );

	        		$forumreply = new ForumReply;
	        		$this->message = 'Your reply has been saved successfully.';
	        		$this->status = 'success';
	        		$reply  = $forumreply->create($data);

					$this->data = ForumReply::with('user')
				                   ->with('replyLikesCount')
                				   ->with('replyCommentsCount')
				                    ->where('id',$reply->id)
				                    ->get();

				}
					  
			}
				
		}
		catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();		
	}


	public function editForumReply()
	{
		try{
			$args = Request::all();
			$user = User::where('id',$args['user_id'])->get();
			if($user->isEmpty())
				throw new Exception("No matching record for the user.", 1);
			else{
				$reply_check = ForumReply::where('id',$args['reply_id'])->first();
				//print_r($post_check);die;
				if(!isset($reply_check->id))
					throw new Exception("No such forum reply exist.", 1);
				else{
					if($reply_check->owner_id != $args['user_id'])
						throw new Exception("This user is not the owner of the forum reply.", 1);
					else{
						ForumReply::where('id',$args['reply_id'])->update(['reply' => $args['reply']]);
						$this->status = "success";
						$this->message = "Post updated successfully.";
						$this->data = ForumReply::with('user')
					                    ->with('replyLikesCount')
		            					->with('replyCommentsCount')
					                    ->where('id',$args['reply_id'])
					                    ->get();
					}

				}
					  
			}
				
		}
		catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();	
	}


	public function postForumComment()
	{
		try{
			$args = Request::all();
			$user = User::where('id',$args['user_id'])->get();
			if($user->isEmpty())
				throw new Exception("No matching record for the user.", 1);
			else{
				$reply_check = ForumReply::where('id',$args['reply_id'])->value('id');
				if($reply_check == null)
					throw new Exception("No such forum reply exist.", 1);
				else{
					$arr = ['reply_comment'=>$args['comment'],
							'owner_id'=>$args['user_id'],
							'reply_id'=>$args['reply_id']];

			        // @ Send notification mail.
			        $parameters = array('user_id' => $args['user_id'], 'current_data' => $args['comment'], 'object_id' => $args['reply_id'], 'type' => 'comment');
			        $notify = Converse::notifyOnReplyComment( $parameters );

	        		$forumcomment = new ForumReplyComments;
	        		$this->message = 'Your comment has been saved successfully.';
	        		$this->status = 'success';
	        		$comment = $forumcomment->create($arr);
					$this->data = ForumReplyComments::with('user')
									->where('id', $comment->id)
									->get();
				}
					  
			}
				
		}
		catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();			
	}


	public function uploadChatImage()
	{		
		try{
	        $status="Failed";
	        $message="";
	        $url1="";
	            $input = Request::all();
	            if( $input )
	            {
	                if( Request::hasFile('chatsendimage') )
	                {
	                                                                // Upload file
	                    $fileToBeUploaded = Request::file('chatsendimage');
	                    $url = time().'--'.implode('_',explode(' ',$fileToBeUploaded->getClientOriginalName()));
	                    $fileToBeUploaded->move('uploads/media/chat_images/', $url);
	                    $url1=url('uploads/media/chat_images/'.$url);
	                    // Add entry

	                    $status = 'success';
	                    $message = 'Image uploaded successfuly.';
	                }
	            }

	        }catch(Exception $e)
	        {
	                $message = $e->getMessage();//'Image not uploaded.';//
	                $status='Failed';
	        }

          return  json_encode(array('status'=>$status,'message'=>$message,'url'=>$url1,'name'=>$url,'type'=>'image'));
    }


	public function chatImagePage()
	{
		return view('chat_image');die;
	}


	public function confirmBox()
	{
		$input = Input::all();
		if($input['type'] == "post"){

			$data = ['class' => "forumpostdelete",
					 'postid' => $input['postid'],
					 'breadcrum'=> $input['breadcrum'],
					 'forumpostid' => "",
					 'forumreplyid' => "",
					 'message' => "All the replies and comments related to this post will be deleted. Are you sure you want to delete this post?"];
		
		}else if($input['type'] == "reply"){

			$data = ['class' => "forumreplydelete",
					 'postid' => "",
					 'breadcrum'=> "",
					 'forumpostid' => $input['forumpostid'],
					 'forumreplyid' => $input['forumreplyid'],
					 'message' => "All the comments related to this reply will be deleted. Are you sure you want to delete this reply?"];
		}

		return view('forums-api.confirmbox')
			   		->with('data',$data);
	}


	public function emailVerification()
	{

		try{
			$email = Request::get('email');
			$user = User::where('email',$email)->first();
			if(empty($user))
				throw new Exception("No matching record for the user.", 1);
			else{
				if($user->is_email_verified == "N"){

					$emaildata = array('confirmation_code' => $user->confirmation_code);
					$username = $user->first_name." ".$user->last_name;
					$useremail = $user->email;
					
					Mail::send('emails.verify',$emaildata, function($message) use($useremail, $username){
						$message->from('no-reply@friendzsquare.com', 'Verify Friendzsquare Account');
						$message->to($useremail,$username)->subject('Verify your email address');

					$this->status = "Success";
					$this->message = "Verification link has been sent to your registered email address. Please check your inbox and verify your email address.";
					});
				}else{
					throw new Exception("This email id is already verified.", 1);					
				}
			}
					  
			}catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();	

	}


	public function changePassword()
	{
		try{
			$input = Request::all();
			$user = User::where('id',$input['user_id'])->first();
			if(empty($user))
				throw new Exception("No matching record for the user.", 1);
			else{
				if(Hash::check($input['old_password'], $user->password)){
					if(Hash::check($input['old_password'], bcrypt($input['new_password']))) {
                        throw new Exception("New password can't be same as old password.", 1);
                    }else{
                    	if(strlen($input['new_password']) < 8){
                    		throw new Exception("New password should be atleast 8 characters long.", 1);
                    	}else{
		                    $user->password = bcrypt($input['new_password']);
		                    $user->save();
		                    $this->status = "Success";
		                    $this->message = "Password changed successfully";
		                    $this->data = $user;
		                }
                    }
				}else{
					throw new Exception("Password doesn't match our records.", 1);	
				}
			}		  
			}catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();	
	}


	public function getPrivacySettings()
	{
		try
		{
			$user_id = Request::get('user_id');

			if(empty($user_id) || !is_numeric($user_id))
				throw new Exception("User id is required.", 1);

			$user = User::find($user_id);

			if(empty($user))
				throw new Exception("User does not exist.", 1);
			
	        $setting = Setting::where('user_id', $user_id)->get()->toArray();
			// echo '<pre>';print_r($setting);die;
            $this->status = "Success";
            $this->message = "Your privacy settings.";
            $this->data = $setting;

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();	
	}


	public function setPrivacySettings()
	{
		try
		{
			$user_id = Request::get('user_id');
			$arguments = Request::all();
			unset($arguments['user_id']);
			
			if(empty($user_id) || !is_numeric($user_id))
				throw new Exception("User id is required.", 1);

			$user = User::find($user_id);

			if(empty($user))
				throw new Exception("User does not exist.", 1);

            foreach($arguments as $key => $data)
            {
                $affectedRows = Setting::where(['setting_title' =>  $key, 'user_id' =>  $user_id])->update(['setting_value' => $data]);
                if( !$affectedRows )
                {
                    $setting = new Setting;
                    $setting->setting_title = $key;
                    $setting->setting_value = $data;
                    $setting->user_id = $user_id; 
                    $setting->save();
                }

            }

            $this->status = "Success";
            $this->message = "Your privacy settings.";

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();	
	}


	public function reportUser()
	{
		try
		{
			$req = Request::all();

			$validator = Validator::make($req, [
					'user_id' => 'required|numeric|exists:users,id',
					'user_jid' => 'required',
					'blocked_user_id' => 'required|numeric|exists:users,id',
					'blocked_user_jid' => 'required',
					'message' => 'required',
				]);
			
			if($validator->fails()) {
				$this->message = $this->getError($validator);
			}else{

				if( $req['user_id'] === $req['blocked_user_id'] )
					throw new Exception("You cannot report about yourself.", 1);

				$check_if_exists = ReportUser::where(['user_id' => $req['user_id'], 'blocked_user_id' => $req['blocked_user_id'], ])->first();

				if( !$check_if_exists ){

					$report_user = new ReportUser;
					$report_user->user_id = $req['user_id'];
					$report_user->user_jid = $req['user_jid'];
					$report_user->blocked_user_id = $req['blocked_user_id'];
					$report_user->blocked_user_jid = $req['blocked_user_jid'];
					$report_user->reason = $req['reason'];
					$report_user->message = $req['message'];
					$report_user->save();

					$this->message = 'Thanks for reporting! You will no longer receive messages from the blocked user.';

				}else{
					$this->message = 'You have already spammed this user.';

				}
				
				$this->status = "success";
			}

		}catch(Exception $e){
			$this->message = $e->getMessage();
		}

		return $this->output();	
	}


}