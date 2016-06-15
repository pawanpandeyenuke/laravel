<?php

namespace App\Http\Controllers;
use App\State, App\City, App\Like, App\Comment, App\User, App\Friend, DB,App\EducationDetails, App\Country,App\Broadcast
,App\BroadcastMessages,App\Group,App\GroupMembers,App\BroadcastMembers,App\Forums,App\ForumPost,App\ForumLikes,App\ForumReply,App\ForumReplyLikes,App\ForumReplyComments;

use Illuminate\Http\Request;
use Session, Validator, Cookie;
use App\Http\Requests;
use App\DefaultGroup;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\Feed, Auth, Mail;
use XmppPrebind;
use \Exception;
use App\Library\Converse, Config;

class AjaxController extends Controller
{

	public function login()
	{
		
		$arguments = Input::all();
		$email = Input::get('email');
		$password = Input::get('password');
		//print_r($arguments);die;
		if(isset($arguments['log']))
			$log = true;
		else
			$log = false;

		$user = new User();
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

			echo json_encode($err);

		}else{

			if(Auth::attempt(['email' => $email, 'password'=>$password , 'is_email_verified'=>
				'Y'], $log))
				echo 'success';
			else{
				$verified = User::where('email',$email)->value('is_email_verified');
				if($verified == 'N')
					echo 'verification';
				else
					echo 'These credentials do not match our records.';
			}

		}

	}


	//Handling posts
	public function posts()
	{
		try
		{
			$arguments = Input::all();
			// print_r($arguments);exit;
			$user = Auth::User();
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


	public function editposts()
	{
		$arguments = Input::all();
		$user = Auth::User();
		$file = Input::file('image');


		if(!(isset($arguments['imagecheck'])) && $file==null && $arguments['message']=='')


		{
			exit;
		}
		if( isset($arguments['image']) && $file != null ){

       /* $file = Request::file('file');
        $image_name = time()."-".$file->getClientOriginalName();
        $file->move('uploads', $image_name);
        $image = Image::make(sprintf('uploads/%s', $image_name))->resize(200, 200)->save();*/
			$image_name = time()."_POST_".strtoupper($file->getClientOriginalName());
			$arguments['image'] = $image_name;
			$file->move('uploads', $image_name);

		}else{
			unset($arguments['image']);
		}
		
		$newsFeed = Feed::find($arguments['id']);
		$newsFeed->fill($arguments);
		$saved = $newsFeed->push();

		$postdata = Feed::where('id', $arguments['id'])->select('image', 'message', 'id')->get();

		echo $postdata;

		exit;
		
	}

	public function editcomments()
	{
		$arguments = Input::all();
		$user = Auth::User();
		if($arguments['comments']!='')
		{
		$comments = Comment::find($arguments['id']);
		$comments->fill($arguments);
		$saved = $comments->push();

		$commentdata = Comment::where('id', $arguments['id'])->get();

		echo $commentdata;

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
		$user_picture = !empty(Auth::User()->picture) ? Auth::User()->picture : 'images/user-thumb.jpg';
		$username = Auth::User()->first_name.' '.Auth::User()->last_name;
		$comment = nl2br($model->comments);
		$time = $model->updated_at->format('h:i A');
		$date = $model->updated_at->format('D jS');
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

		$count = DB::table('comments')->where(['feed_id' => $arguments['feed_id']])->get();	
		$variable['count'] = count($count);
		$data = json_encode($variable);
		echo $data;

		exit;
	}


	public function getxmppuser(){

		$status=0;
		$user_id = Auth::User()->id;
		$node = Config::get('constants.xmpp_host_Url');

		$user = User::find($user_id);
		if ( !empty($user['xmpp_username']) && !empty($user['xmpp_username']) ) 
		{
			// print_r('$xmppPrebind');die;
			$xmppPrebind = new XmppPrebind($node, 'http://'.$node.':5280/http-bind', 'FS', false, false);
			//print_r($xmppPrebind);die;
			$username = $user->xmpp_username;
			$password = $user->xmpp_password;
			$xmppPrebind->connect($username, $password);
			$xmppPrebind->auth();
			$sessionInfo = $xmppPrebind->getSessionInfo();
			$status = 1;
		}

		// $sessionInfo['status']=$status;	  
		// echo json_encode($sessionInfo); 
		// exit;
		return $sessionInfo;
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
		// echo $page;
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

		return view('dashboard.newsfeed')->with('feeds', $feeds);

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
		//$user_id1 = DB::table('users')->pluck('id');
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
		$states = array('<option value="">State</option>');
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
		$city = array('<option value="">City</option>');
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
				$count = DB::table('likes')->where(['feed_id' => $arguments['feed_id']])->get();
				// print_r();die;
				// $likes[] = count($count);
// 
				$likecheck = DB::table('likes')->where('feed_id',$arguments['feed_id'])->where('user_id',Auth::User()->id)->value('id');

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

   		$udetail=User::whereIn('id',$input)->get()->toArray();
   		// echo '<pre>';print_r($udetail);die;
		  if(count($udetail)==2)
			{
			$converse = new Converse;
			$converse->addFriend($udetail[0]['xmpp_username'],$udetail[1]['xmpp_username'],
								$udetail[1]['first_name'],$udetail[0]['first_name']);       
			}

			
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
 		$input['state']=DB::table('state')->where('state_id',$input['state'])->value('state_name');
 		$input['city']=DB::table('city')->where('city_id',$input['city'])->value('city_name');
 			
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
       			DB::table('education_details')
       			->insert([
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

 		$categoryid = DB::table('job_area')->where('job_area',$input['jobarea'])->value('job_area_id');
 		$data = DB::table('job_category')->where('job_area_id',$categoryid)->pluck('job_category');

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
		$id=Auth::User()->id;
		$friend=$input['user_id'];
	
		$status1=DB::table('friends')->where('user_id',$id)->where('friend_id',$friend)->value('status');
		$status2=DB::table('friends')->where('user_id',$friend)->where('friend_id',$id)->value('status');
		
		if($status1==null && $status2==null){
			DB::table('friends')->insert(['user_id'=>$id,'friend_id'=>$friend,'status'=>'Pending']);
		}elseif($status1==null){
			DB::table('friends')->where('user_id',$friend)->where('friend_id',$id)->update(['status'=>'Pending','user_id'=>$id,'friend_id'=>$friend]);
		}elseif($status2==null){
			DB::table('friends')->where('user_id',$id)->where('friend_id',$friend)->update(['status'=>'Pending','user_id'=>$friend,'friend_id'=>$id]);	
		}
	
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
 

	public function sendImage(){
     $status=0;
     $message="";
     //$url=url();


      $image = $_FILES["chatsendimage"]["name"];
      //$path = $rootFolder=dirname(Yii::$app->basePath).'/frontend/web/images/media/chat_images/';
      
      $path=public_path().''.'/uploads/media/chat_images/';
// echo '<pre>'; print_r($path);die;

			$uploadedfile = $_FILES['chatsendimage']['tmp_name'];
			$name = $_FILES['chatsendimage']['name'];
			$size = $_FILES['chatsendimage']['size'];
			$valid_formats = array("jpg", "JPG", "jpeg", "JPEG", "png", "PNG", "gif", "GIF");
				if (strlen($name)) {
			list($txt, $ext) = explode(".", $name);
				if (in_array($ext, $valid_formats)) {
			$actual_image_name = "chatimg_" . time() . substr(str_replace(" ", "_", $txt), 5) . "." . $ext;
			$tmp = $uploadedfile;
				if (move_uploaded_file($tmp, $path . $actual_image_name)) {           
            //$rootFolder=base_path();
            // $image = Yii::$app->image->load($path.$actual_image_name);
            
        	
            //$image->resize(200, 200);
            //$image->save();


            // $image= Image::make($path.$actual_image_name);
            // $image->resize(200,200);
            // $image->save();

        //   ========== $data = Yii::$app->request->baseUrl.'/images/media/chat_images/'. $actual_image_name;
           
            $data='/uploads/media/chat_images/'.$actual_image_name;
           

            $chatType=isset($_POST["chatType"])?$_POST["chatType"]:'';
            if ($chatType == "group"){}//chat type check
            else{           
             $message=$_SERVER['HTTP_HOST'].$data;
    $status=1;
            }                              
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


       public function searchfriendlist()
       {

  			    $input=Input::get('name');

				$friend = Friend::with('friends')->with('user')
						->where('user_id', '=', Auth::User()->id)
						->where('status','Accepted')
						->get()
     					->toArray();
              
                $data=array();
	$count=0;

	$msg="Sorry, no such friend found.";
	foreach ($friend as $key => $value) 
		
		{

		$name=$value['friends']['first_name']." ".$value['friends']['last_name'];
		$xmpp_username="'".$value['friends']['xmpp_username']."'";
		$first_name="'".$value['friends']['first_name']."'";
		$user_picture = !empty($value['friends']['picture']) ? $value['friends']['picture'] : '/images/user-thumb.jpg';
		$msg="No friend found!";

		if (stripos($name, $input) !== false) {
			  $data[] = '<li > 
				<a href="#" title="" class="list" onclick="openChatbox('.$xmpp_username.','.$first_name.');">
					<span class="chat-thumb"style="background: url('.$user_picture.');"></span>
					<span class="title">'.$name.'</span>
				</a>
				</li>';

			$count++;

			}

		}
			if($count==0) {
				$data[] = '<li > 
				<span style="color:black;font-weight:bold">'.$msg.'</span>
				</li>';
			}

	
		$html = implode('',$data);
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
			$model1=User::where('id','!=',Auth::User()->id)->where('first_name','LIKE','%'.$name.'%')->get()->toArray();
		}else{
			$model=Friend::with('user')->with('friends')->with('user')->where( function( $query ) use ( $type ) {
							self::queryBuilder( $query, $type );
						})->get();
			//$count = $model->count();
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
					$count ++;
				}
			}
		}
		//print_r($model2);die;
		return view('dashboard.friendlist2')
					->with('model',$model2)
					->with('model1',$model1)
					->with('count',$count);
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
		$input=Input::all();
		$msg=$input['msg'];
		$uid=Auth::User()->id;
		$members=DB::table('broadcast_members')->where('broadcast_id',$input['bid'])->pluck('member_id');
        //$mem=explode(",",$members);

        $xmpu1=DB::table('users')->where('id',$uid)->value('xmpp_username');
        $converse = new Converse;
        $xmpu2=DB::table('users')->whereIn('id',$members)->pluck('xmpp_username');

        foreach ($xmpu2 as $key => $value) {
        	
        	$converse->broadcast($xmpu1,$value,$input['msg']);

        }
   //      	
			// addFriend

		$date = date('d M Y,h:i a', time());
		     $data = array(
		     			'broadcast_message'=>$input['msg'],
                        'broadcast_id'=>$input['bid'],
                        'broadcast_by'=>Auth::User()->id,
                        'created_at'=>date('Y-m-d H:i:s',time()),
                            );  
                
                BroadcastMessages::insert($data);
				$model=new BroadcastMessages;

			
							

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
		
		$groupname  = DB::table('groups')->where('id',$input)->value('title');
		$groupname  = preg_replace('/\s+/', '_', $groupname);
		$groupname  = strtolower($groupname);
		$groupname  = $groupname."_".$input;
		$converse = new Converse;
		$converse->deleteGroup($groupname);
		
		$Message = json_encode( array( 'type' => 'privatechatdelete', 'chatgroup' => $groupname.'@conference.'.Config::get('constants.xmpp_host_Url'), 'message' => '' ) );
		$xmp = GroupMembers::leftJoin('users', 'members.member_id', '=', 'users.id')->where('members.group_id',$input)->pluck('xmpp_username');		
		foreach ($xmp as $key => $value) {
			$converse->broadcast($userXamp,$value,$Message);
		}
		
		Group::where('id',$input)->where('owner_id',Auth::User()->id)->delete();
		GroupMembers::where('group_id',$input)->delete();
		

	}

/**
	 DELETE USER FROM PRIVATE GROUP 
**/

	public function delUser()
	{
		$input=Input::all();
		$userXamp  = Auth::User()->xmpp_username;
		$groupname = DB::table('groups')->where('id',$input['gid'])->value('title');
		$groupname = preg_replace('/\s+/', '_', $groupname);
		$groupname = strtolower($groupname);
		$groupname = $groupname."_".$input['gid'];
		
		$converse	= new Converse;
		$xmp		= DB::table('users')->where('id',$input['uid'])->value('xmpp_username');            
		$converse->removeUserGroup($groupname,$xmp);
        $Message = json_encode( array( 'type' => 'privatechatremove', 'removejid' => $xmp.'@'.Config::get('constants.xmpp_host_Url'), 'chatgroup' => $groupname.'@conference.'.Config::get('constants.xmpp_host_Url'), 'message' => '' ) );
		$converse->broadcast($userXamp,$xmp,$Message);
		
		GroupMembers::where('group_id',$input['gid'])->where('member_id',$input['uid'])->delete();
	}

/**
	 EDIT PRIVATE GROUP NAME 
**/
	public function editGroupName()
	{
		$input=Input::all();
		Group::where('id',$input['gid'])->update(['title'=>$input['gname']]);
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

/*			 Mail::raw($message,function ($m)  use($value, $subject){
                	$m->from('no-reply@fs.yiipro.com', 'FriendzSquare!');
                    	$m->to($value,"Friend")->subject($subject);
*/
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
		// echo '<pre>';print_r($invalid);
		// echo '<pre>';print_r($valid);die;		
	}

	public function groupImage()
	{
		$input = Input::all();

		$file = Input::file('groupimage');
		if( isset($input['groupimage']) && $file != null ){

			$image_name = time()."_GI_".strtoupper($file->getClientOriginalName());
			$input['groupimage'] = $image_name;
			$file->move(public_path('uploads'), $image_name);
			$img = "/uploads/".$input['groupimage'];
		}

		DB::table('groups')->where('id',$input['groupid'])->update(['picture' => $img]);
	}

	public function viewMoreForAll()
	{
		$per_page = 10;
		$page = Input::get('pageid');
		$keyword = Input::get('keyword');
		$offset = ($page - 1) * $per_page;
		if(Auth::check())
			$user_id = Auth::User()->id;
		else
			$user_id="";
				$model = User::where('id','!=',$user_id)
                            ->where('first_name','LIKE','%'. $keyword.'%')
                            ->orWhere('last_name','LIKE','%'. $keyword.'%')
                            ->skip($offset)
                            ->take($per_page)
                            ->orderBy('id','desc')
                            ->get()
                            ->toArray();
		
			$modelcount = count($model);

			$auth = ($user_id != '') ? 1 : 0;
			
			if($model){
			return view('dashboard.getsearchresult')
					->with('model',$model)
					->with('modelcount',$modelcount)
					->with('auth',$auth);          
			}
			else{
				echo "No more results";
			}
	}

	public function delForumPost()
	{
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

	public function editForumPost()
	{
		$forumpostid = Input::get('forumpostid');
		$forumpost = ForumPost::where('id',$forumpostid)->first();

		return view('ajax.editforumpost')->with('forumpost', $forumpost);
	}

	public function editNewForumPost()
	{
		$arguments = Input::all();
		//print_r($arguments);die;
		if($arguments['forumtitle'] != ""){
			$check = ForumReply::where('post_id',$arguments['id'])->get()->count();
			if($check == 0){
			
			ForumPost::where('id',$arguments['id'])->update(['title'=>$arguments['forumtitle']]);
			$data = ['id'=>$arguments['id'],
					 'title'=>$arguments['forumtitle']];

			echo json_encode($data);
		   }
		   else
		   	echo "rep";

	   }else
			echo "Post something to update.";

	}

	public function addNewForumPost()
    {
    	$user = Auth::User();
        $input = Input::all();
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
        $profileimage = !empty($user->picture) ? $user->picture : '/images/user-thumb.jpg';

        
        return view('ajax.forumpost')
        		->with('forumpostid',$forumpostid)
        		// ->with('categoryid',$input['category_id'])
        		->with('profileimage',$profileimage)
        		->with('breadcrum',$forum_category_breadcrum)
        		->with('user',$user)
        		->with('name',$name);

		echo $forumpostdata;
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

		     $posts = ForumPost::with('user')->with('forumPostLikesCount')
		     	->with('replyCount')
		        ->where('forum_category_breadcrum',$breadcrum)
		        ->skip($offset)
		        ->take($per_page)
		        ->orderBy('updated_at','DESC')
		        ->get();   

		$str  = "No More Results";

		if($call_type == 'web'){
			if(!($posts->isEmpty())){
				return view('forums.viewmoreforumposts')
							->with('posts',$posts)
							->with('breadcrum',$breadcrum);       
			}
			else{
				echo $str;
			}
		}elseif($call_type == 'api'){
			if(!($posts->isEmpty())){
				$per_page = 5;
				return view('forums-api.ajax-post')
							->with('forumPosts',$posts)
							->with('breadcrum',$breadcrum)
							->with('user_id', Input::get('user_id'))
							->render();
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
        $profileimage = !empty($user->picture) ? $user->picture : '/images/user-thumb.jpg';

        
        return view('ajax.forumpostreply')
        		->with('forumreply',$forumpostreply)
        		->with('profileimage',$profileimage)
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
		//print_r($arguments);die;
		if($arguments['forumreply'] != "")
		{
			ForumReply::where('id',$arguments['id'])->update(['reply'=>$arguments['forumreply']]);
			$data = ['id'=>$arguments['id'],
					 'reply'=>$arguments['forumreply']];

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
        $profileimage = !empty($user->picture) ? $user->picture : '/images/user-thumb.jpg';

		return view('ajax.forumreplycomment')
				->with('comment',$comment)
				->with('name',$name)
				->with('userid',$user->id)
				->with('replyid',$replyid)
				->with('profileimage',$profileimage);
		

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

	     $reply = ForumReply::with('user')
            ->with('replyLikesCount')
            ->with('replyCommentsCount')
            ->where('post_id',$forumpostid)
            ->skip($offset)
	        ->take($per_page)
            ->orderBy('updated_at','DESC')
            ->get();

		$str  = "No More Results";

		if($call_type === 'web'){
			if(!($reply->isEmpty())){
				return view('forums.viewmoreforumreply')
						->with('reply',$reply)
						->with('forumpostid',$forumpostid);       
			}
			else{
				echo $str;
			}
		}elseif($call_type === 'api'){
			$per_page = 5;
			if(!($reply->isEmpty())){
				return view('forums-api.ajax-reply')
						->with('replies', $reply)
						->with('user_id', Input::get('user_id'))
						->with('forumpostid', $forumpostid);       
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
			// echo '<pre>';print_r($replyComments);die;
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

		$forums = array('<option value=""></option>');
		if($subforums->isEmpty())
			echo 'No';
		else{
			$subforumArr[] = "<option>SubCategory</option>";
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
			foreach($countries as $data){
			$country[] = '<option value="'.$data->country_name.'">'.$data->country_name.'</option>';
			}
			$country1 = implode('',$country);
			$arr = ['msg' => 'c',
					'data'=>$country1];
			print_r(json_encode($arr));
		}

		else if($title == "Country,State,City"){
			$country[] = "<option>Country</option>";
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

		if(!($posts->isEmpty())){
			return view('forums.viewmoresearchforum')
						->with('posts',$posts)
						->with('breadcrum',$breadcrum)
						->with('keyword',$keyword);       
			}
			else{
				echo $str;
			}	
	}

}
	
