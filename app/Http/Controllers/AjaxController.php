<?php

namespace App\Http\Controllers;
use App\State, App\City, App\Like, App\Comment, App\User, App\Friend, DB,App\EducationDetails, App\Country;
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
		
		$countryid = Country::where(['country_name' => $input['countryId']])->value('country_id');		
		$statequeries = State::where(['country_id' => $countryid])->get();		
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
		$cityid = State::where(['state_id' => $input['stateId']])->value('state_id');
		$cityqueries = City::where(['state_id' => $cityid])->get();
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
		$abc=DB::table('friends')->where('user_id',$id)->where('friend_id',$friend)->value('status');
		$xyz=DB::table('friends')->where('user_id',$friend)->where('friend_id',$id)->value('status');
		if($abc==null && $xyz==null)
		{
			DB::table('friends')->insert(['user_id'=>$id,'friend_id'=>$friend,'status'=>'Pending']);
		}
	
	}

	   public function sendImage(){
     $status=0;
     $message="";
     //$url=url();
// echo '<pre>'; print_r($_FILES);die;

      $image = $_FILES["chatsendimage"]["name"];
      //$path = $rootFolder=dirname(Yii::$app->basePath).'/frontend/web/images/media/chat_images/';
      
      $path=public_path().''.'/images/media/chat_images/';


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


            $image= Image::make($path.$actual_image_name);
            $image->resize(200,200);
            $image->save();

        //   ========== $data = Yii::$app->request->baseUrl.'/images/media/chat_images/'. $actual_image_name;
           
            $data='/images/media/chat_images/'.$actual_image_name;
           

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

 	

}
	
