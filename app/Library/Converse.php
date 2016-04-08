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

		$msg=str_replace(" ","_",$msg);
		
	       $result=@exec('sudo ejabberdctl send_message_chat '.$userfrom.'@'.$node.' '.$userto.'@'.$node.' '.$msg);
		

	}


}	



?>
