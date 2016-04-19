<?php namespace App\Library;

use Validator, Input, Redirect, Request, Session, Hash, DB;

class Converse{

	/**
	*	Register at ejabberd.
	*
	**/
    static function register($username, $password) {

		$server = Request::server('HTTP_HOST'); 

		$node = config('app.xmppHost');
		$response = @exec('sudo ejabberdctl register '.$username.' '.$node.' '.$password.' 2>&1', $output, $status);

		// dd($response);exit;
		return true;
	}

	/**
	*	Add friend at ejabberd.
	*
	**/
	public static function addFriend($localuser,$user,$nic1,$nic2, $group = 'general', $subscription = 'both', $update = 0) {

		$node = config('app.xmppHost');
		$data_from = $localuser .' '. $node .' '. $user .' '. $node .' "'. $nic1 .'" '. $group .' '. $subscription;
		$data_to = $user .' '. $node .' '. $localuser .' '. $node .' "'. $nic2 .'" '. $group .' '. $subscription;

		@exec('sudo ejabberdctl add_rosteritem '. $data_from .' 2>&1', $output1, $status1);
		@exec('sudo ejabberdctl add_rosteritem '. $data_to .' 2>&1', $output2, $status2);       
		return true;
    
    }

	/**
	*	Create group at ejabberd.
	*
	**/
	public static function createGroup($roomid,$roomname) {

		$node=config('app.xmppHost');
	//	$node='conference.'.$node;
		$roomname=str_replace(" ","_",$roomname);
		@exec('sudo  ejabberdctl srg_create '.$roomname.' '.$node.' '.$roomid.' Private_Group My_Group');


			//	srg-create group host name description display  
	}


	/**
	*	Delete group at ejabberd.
	*
	**/
	public static function deleteGroup($roomname){

		$node=config('app.xmppHost');
		$roomname=str_replace(" ","_",$roomname);
		$response=@exec('sudo  ejabberdctl srg_delete ' .$roomname.' ' .$node);
			// srg-delete group host  
	}

	/**
	*	Add user from a group.
	*
	**/
	public static function addUserGroup($roomname,$username){

		$node = config('app.xmppHost');
	//	$node='conference.'.$node;
		$roomname=str_replace(" ","_",$roomname);
		$response=@exec('sudo  ejabberdctl srg_user_add '.$username.' '.$node.' '.$roomname.' '.$node);
		
		
		//srg-user-add user server group host                   Adds user@server to group on host

	}

	/**
	*	Remove user from a group.
	*
	**/
	public static function removeUserGroup($roomname,$username){

		$node = config('app.xmppHost');
		$roomname=str_replace(" ","_",$roomname);
		$response=@exec('sudo  ejabberdctl srg_user_del '.$username.' '.$node.' '.$roomname.' '.$node);
		
		
		//srg-user-del user server group host                   Removes user@server from group on host

	}

	/**
	*   (Broadcast) Send message in chat to single user.
	*
	**/
	public static function broadcast($userfrom,$userto,$msg){

		$node=config('app.xmppHost');

		//$msg=str_replace(" ","_",$msg);
		 //$msg = ['type'=>'text','message'=>$msg];
        	// $enmsg = json_encode($msg);
		 $result=@exec("sudo ejabberdctl send_message_chat ".$userfrom."@".$node." ".$userto."@".$node." '".$msg."'");

	}


	/**
	*   push notification for android.
	*
	**/
	public static function _callPushNotificationAndroid($senderName, $dToken, $msgType, $msgId) {
        if ($msgType == 'location') {
            $message = $senderName . ' has sent ' . $msgType;
        } else
            $message = $senderName . ' has sent you one ' . $msgType . ' message.';
        $headers = array("Content-Type:" . "application/json", "Authorization:" . "key=" . "AIzaSyCschAIPdTeYsAkOjSJCGlTRF1puO-h29M");

        $data = '{';
        $data.='"data"';
        $data.=': {';
        $data.='"message"';
        $data.=":";
        $data.='"' . $message . '"';
        $data.='"msgId"';
        $data.=":";
        $data.='"' . $msgId . '"';
        $data.='},';
        $data.='"registration_ids":';
        $data.='["' . $dToken . '"]';
        $data.='}';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, "https://android.googleapis.com/gcm/send");
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        error_log(json_encode($data));
        $response = curl_exec($ch);
        curl_close($ch);
        $pos = strpos($response, '"success":1');
        if ($pos == true)
            return $response;
        else
            error_log($response);
        return $response;
    }


	/**
	*   push notification for I-phone.
	*
	**/
/*    public static function _callPushNotification($senderName, $dToken, $msgType, $msgId,$chatType) {


        // Put your device token here (without spaces):
        //$deviceToken = '0f744707bebcf74f9b7c25d48e3358945f6aa01da5ddb387462c7eaf61bbad78';
        $deviceToken = $dToken;
        // Put your private key's passphrase here:
        $passphrase = '1234567';
        // $passphrase = 'enuke123';
        // Put your alert message here:
        //$message = 'My first push notification!';
        //  $message = $msgId.'_'.$senderName.' has sent you one '.$msgType.' message.';
        
        if ($chatType == 'video' || $chatType == 'audio') {
            $message = $senderName . ' want to '.$chatType.' calling with you. Enable '.$chatType.'.';
        }
        else if ($msgType == 'location') {
            $message = $senderName . ' has sent ' . $msgType;
        } else
            $message = $senderName . ' has sent you one ' . $msgType . ' message.';
        ////////////////////////////////////////////////////////////////////////////////

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', '1234567');

        // Open a connection to the APNS server
//        $fp = stream_socket_client(
//                'ssl://gateway.sandbox.push.apple.com:2195', $err,
//                $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
        $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

        if (!$fp) {
            // exit("Failed to connect: $err $errstr" . PHP_EOL);
            return false;
        }
        //echo 'Connected to APNS' . PHP_EOL;
        // Create the payload body
        $body['aps'] = array(
            'alert' => $message,
            'sound' => 'default',
            'msgId' => $msgId,
            'chatType' => $chatType,
            'sname' => $senderName
        );
    // $body['aps']['sname']=$senderName;

        // Encode the payload as JSON
        $payload = json_encode($body);

        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));

        // Close the connection to the server
        fclose($fp);
        if (!$result) {
            return false;
            //echo 'Message not delivered' . PHP_EOL;
        } else {
            return true;
            //echo 'Message successfully delivered' . PHP_EOL;
        }
    }
*/

    
}	



?>
