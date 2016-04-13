<?php

namespace App\Http\Controllers;
use App\State, App\City, App\Like, App\Comment, App\User, App\Friend, DB,App\EducationDetails, App\Country,App\Broadcast
,App\BroadcastMessages,App\Group,App\GroupMembers,App\BroadcastMembers;
use Illuminate\Http\Request;
use Session, Validator, Cookie;
use App\Http\Requests;
use XmppPrebind, App\DefaultGroup;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\Feed, Auth;
use Intervention\Image\Image;
use \Exception;
use App\Library\Converse;

class AjaxController extends Controller
{

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
				<a href="profile/$userid" title="" class="user-link">$username</a>
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
		$model1=array();
		$model=array();

		if($input=='all') {
			$model1=User::where('id','!=',Auth::User()->id)->take(10)->get()->toArray();
		} else {
			$model=Friend::with('user')->with('friends')->with('user')->where( function( $query ) use ( $input ) {
				    self::queryBuilder( $query, $input );
					})->take(10)->get()->toArray();
		}

		return view('dashboard.getfriendslist')->with('model',$model)->with('model1',$model1);
 
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
		$page = Input::get('pageid');
		$type = Input::get('reqType');
		$offset = ($page - 1) * $per_page;
		$user_id = Auth::User()->id;

		switch ($type) {
			case 'all':
				$model = User::where('id', '!=', $user_id)
							->skip($offset)
							->take($per_page)
				            ->get()->toArray();
				break;
			case 'sent':
				$modeldata = Friend::with('user')->with('friends')->with('user')
							->where('user_id', '=', $user_id)
							->where('status', '=', 'Pending')
							->skip($offset)
							->take($per_page)
							->get()->toArray();
				break;
			case 'recieved':
				$modeldata = Friend::with('user')->with('friends')->with('user')
				            ->where('friend_id', '=', $user_id)
				            ->where('status', '=', 'Pending')
							->skip($offset)
							->take($per_page)
				            ->get()->toArray();
				break;
			case 'current':
				$modeldata = Friend::with('user')->with('friends')->with('user')
							->where('friend_id', '=', $user_id)
				            ->where('status', '=', 'Accepted')
							->skip($offset)
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
			return view('dashboard.getfriendslist')
						->with('model',$model)
						->with('model1',$model1)
						->with('modelcount', $modelcount);
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
				->update(['status'=>'Rejected']);

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
				->update(['status'=>'Rejected']); 

		Friend::where(['friend_id'=>$input['user_id']])
				->where(['user_id'=>$input['friend_id']])
				->update(['status'=>'Rejected']);      

		Friend::where(['friend_id'=>$input['user_id']])->where(['status'=>'Rejected'])->delete();
		
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
		
		$newsFeed = Feed::where('id', '=', $postId)->where('user_by', '=', $userId)->delete();
		return $newsFeed; 

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

		$commentid = Input::get('commentid');
		return $commentid;

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
		
		return view('panels.deletebox')
				->with('commentId', $commentId)
				->with('feedId', $feedId)
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

foreach ($friend as $key => $value) 
		
		{

		$name=$value['friends']['first_name']." ".$value['friends']['last_name'];
		$xmpp_username="'".$value['friends']['xmpp_username']."'";
		$first_name="'".$value['friends']['first_name']."'";

		if (stripos($name, $input) !== false) {
			  $data[] = '<li > 
				<a href="#" title="" class="list" onclick="openChatbox('.$xmpp_username.','.$first_name.');">
					<span class="chat-thumb"style="background: url(images/user-thumb.jpg);"></span>
					<span class="title">'.$name.'</span>
				</a>
				</li>';
			}
		}

		$html = implode('',$data);
		echo $html;

	}


	public function searchTabFriend()
	{
		$input=Input::all();

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
						})->get()->toArray();
		}

		if($model!=null){
			foreach ($model as $key => $value) {
				if($type=='current')

					$n=$value['user']['first_name']." ".$value['user']['last_name'];
				else
					$n=$value['friends']['first_name']." ".$value['friends']['last_name'];

				if (stripos($n, $name) !== false) {
					$model2[] =$value;
				}
			}
		}
		//print_r($model2);die;
		return view('dashboard.friendlist2')->with('model',$model2)->with('model1',$model1);
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
                        'created_at'=>date('Y-m-d h:i:s',time()),
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
		$input=Input::get('pid');
		$groupname = DB::table('groups')->where('id',$input)->value('title');
		$groupname=$groupname."_".$input;
		$converse=new Converse;
		$converse->deleteGroup($groupname);

		Group::where('id',$input)->where('owner_id',Auth::User()->id)->delete();
		GroupMembers::where('group_id',$input)->delete();


	}

/**
	 DELETE USER FROM PRIVATE GROUP 
**/

	public function delUser()
	{
		$input=Input::all();

		$groupname = DB::table('groups')->where('id',$input['gid'])->value('title');
		$groupname=$groupname."_".$input['gid'];
		
		$converse=new Converse;
		$xmp=DB::table('users')->where('id',$input['uid'])->value('xmpp_username');            
      
        $converse->removeUserGroup($groupname,$xmp);

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
}
	
