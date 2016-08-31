<?php namespace App\Library;

use Validator, Input, Redirect, Request, Session, Hash, DB, Config;
use App\Feed, App\Comment, App\Like, App\User, XmppPrebind, Mail;
use App\ForumReply, App\ForumPost;

class Converse
{

	// @ Register at ejabberd.
    static function register($username, $password) {

		$server = Request::server('HTTP_HOST'); 

		$node = Config::get('constants.xmpp_host_Url');
		$response = @exec('sudo ejabberdctl register '.$username.' '.$node.' '.$password.' 2>&1', $output, $status);

		return true;
	}


	// @ Add friend at ejabberd.
	public static function addFriend($localuser,$user,$nic1,$nic2, $group = 'general', $subscription = 'both', $update = 0) {

		$node = Config::get('constants.xmpp_host_Url');
		$data_from = $localuser .' '. $node .' '. $user .' '. $node .' "'. $nic1 .'" '. $group .' '. $subscription;
		$data_to = $user .' '. $node .' '. $localuser .' '. $node .' "'. $nic2 .'" '. $group .' '. $subscription;

		@exec('sudo ejabberdctl add_rosteritem '. $data_from .' 2>&1', $output1, $status1);
		@exec('sudo ejabberdctl add_rosteritem '. $data_to .' 2>&1', $output2, $status2);       
		return true;
    
    }


	// @ Create group at ejabberd.
	public static function createGroup($roomid,$roomname) {

		$node 		= Config::get('constants.xmpp_host_Url');
		$node 		= 'conference.'.$node;
		$roomname 	= str_replace(" ","_",$roomname);
		@exec('sudo ejabberdctl srg_create '.$roomname.' '.$node.' '.$roomid.' Private_Group My_Group');

		//	srg-create group host name description display  
	}


	// @ Delete group at ejabberd.
	public static function deleteGroup($roomname){

		$node = Config::get('constants.xmpp_host_Url');
		$node 		= 'conference.'.$node;
		$roomname	=	str_replace(" ","_",$roomname);
		$response	=	@exec('sudo  ejabberdctl srg_delete ' .$roomname.' ' .$node);
		// srg-delete group host  
	}


	// @ Add user from a group.
	public static function addUserGroup($roomname,$username){

		$node = Config::get('constants.xmpp_host_Url');
		//$node='conference.'.$node;
		$roomname=str_replace(" ","_",$roomname);
		$response=@exec('sudo  ejabberdctl srg_user_add '.$username.' '.$node.' '.$roomname.' conference.'.$node);
		//srg-user-add user server group host                   Adds user@server to group on host

	}


	// @ Remove user from a group.
	public static function removeUserGroup($roomname,$username){

		$node = Config::get('constants.xmpp_host_Url');
		$roomname = str_replace(" ","_",$roomname);
		$response = @exec('sudo  ejabberdctl srg_user_del '.$username.' '.$node.' '.$roomname.' '.$node);
		//srg-user-del user server group host                   Removes user@server from group on host

	}


	// @ (Broadcast) Send message in chat to single user.
	public static function broadcast($userfrom,$userto,$msg){

		$node = Config::get('constants.xmpp_host_Url');

		$subject = "Broadcast Subject";

		/*$userto = $userto.'@'.$node;
		print_r($userfrom.'@'.$node);die;
		$msg=str_replace(" ","_",$msg);
		$msg = ['type'=>'text','message'=>$msg];
		$enmsg = json_encode($msg);
		$result=@exec("sudo ejabberdctl send_message chat ".$userfrom."@".$node." ".$userto."@".$node." '".$msg."'");*/

		$result2 = @exec( "sudo ejabberdctl send_message chat ".$userfrom."@".$node." ".$userto."@".$node." '".$subject."' '".$msg."'" );
		//echo $result2;exit;
	}

	// @ (Broadcast) Send message in chatroom.
	public static function broadcastchatroom($groupfrom,$userfrom,$userto,$userjid,$msg){

		$node = Config::get('constants.xmpp_host_Url');
		$subject = "";
		$result2 = @exec( "sudo ejabberdctl send_message groupchat '".$groupfrom."@conference.".$node."/".$userfrom."' ".$userto."@".$node." '".$subject."' '".$msg."'");
		//echo $result2;exit;
	}

	// @ Set users vCard.
	public static function setVcard($username, $fieldValue, $ImageType){

		$node = Config::get('constants.xmpp_host_Url');
		$fieldName = 'BINVAL';
		$fieldType = 'TYPE';
		@exec('sudo ejabberdctl set-vcard '.$username.' '.$node.' '.$fieldName.' "'.$fieldValue.'" 2>&1', $output, $status);
		@exec('sudo ejabberdctl set-vcard '.$username.' '.$node.' '.$fieldType.' "'.$ImageType.'" 2>&1', $output, $status);

	}

	// @ Set users vCard.
	public static function setNameVcard($username, $fieldName, $fieldValue){
		$node = Config::get('constants.xmppHost');
		return @exec('sudo ejabberdctl set-vcard '.$username.' '.$node.' '.$fieldName.' "'.$fieldValue.'" 2>&1', $output, $status);
		
	}


    // @ On delete posts
    function onDeletePosts($postId, $userId) {

    	$post = Feed::where('id', '=', $postId)->where('user_by', '=', $userId)->first();
    	// print_r($post);die(' kill');
    	// echo $postId;
    	if($post){
	    	if(!empty($post->image)){
		    	$img_url = 'uploads/'.$post->image;
		    	$url = public_path($img_url); 
				if(file_exists($url)){
					unlink($url);
				}
	    	}
	    	$post->delete();
    	}
		Comment::where('feed_id', '=', $postId)->where('commented_by', '=', $userId)->delete();
		Like::where('feed_id', '=', $postId)->where('user_id', '=', $userId)->delete();

    	return true;	
        
    }

    // @ Create xmpp credentials for authenticated user
    static function createUserXmppDetails( $userdata ){

        $xmpp_username = $userdata->first_name.$userdata->id;
        $xmpp_password = 'enuke'; //substr(md5($userdata->id),0,10);

       	$user = User::find($userdata->id);
        $user->xmpp_username = strtolower($xmpp_username);
        $user->xmpp_password = $xmpp_password;
        $user->save();

        return $user;

    }

    // @ Connect to xmppserver using authenticated users credentials
 	static function ejabberdConnect( $authuser )
 	{
 		try
 		{ 			
			$node = Config::get('constants.xmpp_host_Url');

			// @Connect ejabberd with xmpp credentials
			$xmppPrebind = new XmppPrebind($node, 'http://'.$node.':5280/http-bind', uniqid(), false, false);
			$xmppPrebind->connect($authuser->xmpp_username, $authuser->xmpp_password);
			$xmppPrebind->auth();
			$sessionInfo = $xmppPrebind->getSessionInfo();

			return $sessionInfo;

 		}catch(Exception $e){
 			return $e->getMessage();
 		}

 	}



 	// @ Send notifications across devices
 	static function notifyMe( $userId, $friendId, $type )
 	{
		$user = User::find($userId);
		$friend = User::find($friendId);
 		$subjectName = $user->first_name.' '.$user->last_name;
 		$data_array = array();

 		if($type == 'accept'){
 			$data_array['message'] = "$subjectName has accepted your friend request";
 			$data_array['notification_type'] = "Friend Request Accepted";
 		}
 		elseif($type == 'request'){
  			$data_array['message'] = "$subjectName wants to be your friend";
 			$data_array['notification_type'] = "Friend Request Received";
		}

 		// $response = 'Message was not delivered';
 		if( $friend->device_type == 'IPHONE' ){
 			// @ Call IOS function for push notification
 			self::pushNotificationIphone( $data_array, $friend->push_token );

 		}elseif( $friend->device_type == 'ANDROID' ){
 			// @ Call Android function for push notification
 			self::pushNotificationAndroid( $data_array, $friend->push_token );

 		}
 		//return $response;
 	}



    // @ Return Response For Push Notification In IOS
    static function pushNotificationIphone( $data_array, $token )
    {
        $response = 'Message not delivered';



        $data = array(
            'message' => $data_array['message'],
            'notification_type' => $data_array['notification_type'],
            'token' => $token
        );
        //previous token  -- cd967ddac1c1acd00c3fa5d3700afda1dab7d449b8aacdf67c34e64edd6e2262
        //current token [iphone 6 white]  -- 432dd3aa54c9b387ab53fe809069fc9c22b8fdf5a8e45a2fd15cd58124a9acfa
		iphonePushNotification($data);
/*        if(iphonePushNotification($data))
            $response = 'Message successfully delivered';  

        return $response; */

    }
	
	// @ Return Response For Push Notification In Android
    static function pushNotificationAndroid( $data_array, $token )
    {   
        $data = array(
        			'registration_ids' => array( $token ),
            		'data'=>array(
                        'message'   => $data_array['message'],
                        'title'     => 'From: Mayank123',
                        'subtitle'  => 'My-subtitle',
                        'tickerText'=> 'My tickerText',
                        'vibrate'   => 1,
                        'sound'     => 1,
                        'largeIcon' => 'large_icon',
                        'notification_type' => $data_array['notification_type'],
                        'smallIcon' => 'small_icon'
                    )                    
            	);

        $msg = 'Message not delivered';   
        
        if(androidPushNotification($data)) $msg='Message successfully delivered';
        return $msg;
    }


    // @ Notify user via mail when someone replies on their post or comments on their replies.
 	static function notifyOnReplyComment( $parameters )
 	{
 		try
 		{
 			if(!empty($parameters['object_id']) && !empty($parameters['user_id']) && !empty($parameters['type']) && !empty($parameters['current_data']))
 			{
 				$data = array();
 				$subject = User::find($parameters['user_id']);
 				$name = $subject->first_name.' '.$subject->last_name;

	 			if( $parameters['type'] === 'reply' ){

	 				$object = ForumPost::find($parameters['object_id']);
	 				$result = self::viewLessMore($object->title);

	 				$data['current_data'] = $name.' replied on your post "'.$result.'".';
	 				$data['post_message'] = $parameters['current_data'];
	 				$data['type'] = 'Reply: ';
	 				$data['linktype'] = 'Post';
	 				$data['post_url'] = url('forum-post-reply/'.$object->id);
	 				$from_name = 'FriendzSquare Reply';
	 				$subject = $name.' has replied on your post';

	 			} elseif ( $parameters['type'] === 'comment' ) {

	 				$object = ForumReply::find($parameters['object_id']);
	 				$result = self::viewLessMore($object->reply);

	 				$data['current_data'] = $name.' commented on your reply "'.$result.'".';
	 				$data['post_message'] = $parameters['current_data'];
	 				$data['type'] = 'Comment: ';
	 				$data['linktype'] = 'Reply';
	 				$data['post_url'] = url('forum-post-reply/'.$object->id);
	 				$from_name = 'FriendzSquare Comment';
	 				$subject = $name.' has commented on your reply';
	 			}

	 			if( $parameters['user_id'] == $object->owner_id ) {
 					return;
 				}
 				
 				// Get post owner
 				$userObj = User::find($object->owner_id);
 				if( !$userObj->subscribe ) {
 					return;
 				}

 				$userObj = User::find($object->owner_id);
 				$data['user_name'] = $userObj->name;
 				$data['post_type'] = $parameters['type'];
 				$data['access_token'] = urlencode($userObj->access_token);
 				$user_email = $userObj->email;
 				$user_name = $userObj->first_name.' '.$userObj->last_name;

 				// Send email
				Mail::send('panels.email-template', $data, function( $message ) use( $user_email, $user_name, $from_name, $subject ){
					$message->from('no-reply@friendzsquare.com', $from_name);
					$message->to( $user_email, $user_name )->subject( $subject );
				});
 			}
 		} catch(Exception $e) {
 			return $e->getMessage();
 		}
 	}

	public static function viewLessMore( $parameter ){
	    
	    $length = strlen($parameter);

		if($length < 60){
		    $result = $parameter;
		}else{
		    $result = mb_substr($parameter, 0, 60, 'UTF-8');
		    $result = $result.'...';
		}

		return $result;
	}



    // Remove Files
    public static function removeFile( $request ) 
    {
    	if( $request ){

			$validator = Validator::make($request, [
					'user_id' => 'required|numeric|exists:users,id'
				]);
			
			if($validator->fails()) {
				return $validator->errors()->first();
			}else{

				$user = User::find($request['user_id']);
		        if($user){
		            if(!empty($user->picture)){
		                $img_url = '/uploads/user_img/'.$user->picture;
		                $img_url_original = '/uploads/user_img/original_'.$user->picture;

		                $url = public_path($img_url); 
		                $url_original = public_path($img_url_original); 
		                
		                if(file_exists($url)){
		                    unlink($url);
		                }

		                if(file_exists($url_original)){
		                    unlink($url_original);
		                }
		               
		                $user->picture = NULL;
		                $user->save();

		                /** Set Default Image **/
		                $path = public_path('uploads/user_img/user-thumb.jpg');

						$ImageData = file_get_contents($path);
						$ImageType = pathinfo($path, PATHINFO_EXTENSION);
						$ImageData = base64_encode($ImageData);
						Converse::setVcard($user->xmpp_username, $ImageData, $ImageType);
		            }
		        }
		        return true;
			}
			
    	}

    }


}


?>
