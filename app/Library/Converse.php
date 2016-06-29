<?php namespace App\Library;

use Validator, Input, Redirect, Request, Session, Hash, DB, Config;
use App\Feed, App\Comment, App\Like, App\User, XmppPrebind;

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
	public static function broadcastchatroom($groupfrom,$userfrom,$userto,$msg){
		$node = Config::get('constants.xmpp_host_Url');
		$subject = "";
		$result2 = @exec( "ejabberdctl send_message groupchat '".$groupfrom."@conference".$node."/".$userfrom."' ".$userto."@.".$node." '".$subject."' '".$msg."'");
		//echo $result2;exit;
	}

	// @ Set users vCard.
	public static function setVcard($username, $fieldValue){

		$node = Config::get('constants.xmpp_host_Url');
		$fieldName = 'BINVAL';
		$fieldValue = base64_encode( $fieldValue );

		return @exec('sudo ejabberdctl set-vcard '.$username.' '.$node.' '.$fieldName.' "'.$fieldValue.'" 2>&1', $output, $status);

	}


    // @ On delete posts
    function onDeletePosts($postId, $userId) {

    	$post = Feed::where('id', '=', $postId)->where('user_by', '=', $userId)->first();

    	$img_url = 'uploads/'.$post->image;
    	$url = public_path($img_url); 
		$post->delete();
		Comment::where('feed_id', '=', $postId)->where('commented_by', '=', $userId)->delete();
		Like::where('feed_id', '=', $postId)->where('user_id', '=', $userId)->delete();

    	if(!empty($post->image)){
    		unlink($url);
    	}    

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
			$xmppPrebind = new XmppPrebind($node, 'http://'.$node.':5280/http-bind', 'FS', false, false);
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

 		if($type == 'accept')
 			$message = "$subjectName has accepted your friend request";
 		elseif($type == 'request')
 			$message = "$subjectName wants to be your friend";

 		// $response = 'Message was not delivered';
 		if( $friend->device_type == 'IPHONE' ){
 			// @ Call IOS function for push notification
 			self::pushNotificationIphone( $message, $friend->push_token );

 		}elseif( $friend->device_type == 'ANDROID' ){
 			// @ Call Android function for push notification
 			self::pushNotificationAndroid( $message, $friend->push_token );

 		}
 		//return $response;
 	}



    // @ Return Response For Push Notification In IOS
    static function pushNotificationIphone( $message, $token )
    {
        $response = 'Message not delivered';

        $data = array(
            'message' => $message,
            'token' => $token //'cd967ddac1c1acd00c3fa5d3700afda1dab7d449b8aacdf67c34e64edd6e2262'
        );

	iphonePushNotification($data);
/*        if(iphonePushNotification($data))
            $response = 'Message successfully delivered';  

        return $response; */

    }
	
	// @ Return Response For Push Notification In Android
    static function pushNotificationAndroid( $message, $token )
    {   
        $data=array('registration_ids'=>array( 'APA91bGsmuvwZ8N0Fhc8JflH_t3agUK_MNQn6mZEvgkBw2hb2_P9yrnLOSAjgtk_vUgj50In5xAvPD5NH4J-gm_MrGYf9JpPJ7qPKo6e9cUa7tdHXEseSaw' ),
            'data'=>array(
                            'message'   => 'Here is a message from Mayank123',
                            'title'     => 'From: Mayank123',
                            'subtitle'  => 'My-subtitle',
                            'tickerText'    => 'My tickerText',
                            'vibrate'   => 1,
                            'sound'     => 1,
                            'largeIcon' => 'large_icon',
                            'smallIcon' => 'small_icon'
                        ));
        $msg='Message not delivered';   
        
        if(androidPushNotification($data)) $msg='Message successfully delivered';
        return $msg;
    }


}


?>
