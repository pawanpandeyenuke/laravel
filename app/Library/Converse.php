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
		$response = @exec('sudo /usr/sbin/ejabberdctl register '.$username.' '.$node.' '.$password.' 2>&1', $output, $status);

		// dd($response);exit;
		return true;
	}


	/**
	*	Create group at ejabberd.
	*
	**/
	static function createGroup($roomname) {

		$node=config('app.xmppHost');
		$response=@exec('sudo/usr/sbin/ejabberdctl create_room' .$roomname.'muc_service' .$node);

	}


	/**
	*	Delete group at ejabberd.
	*
	**/
	static function deleteGroup($roomname){

		$node=config('app.xmppHost');
		$response=@exec('sudo/usr/sbin/ejabberdctl destroy_room' .$roomname.'muc_service' .$node);

	}


	/**
	*	Add friend at ejabberd.
	*
	**/
	public static function addFriend($localuser,$user,$nic1,$nic2, $group = 'general', $subscription = 'both', $update = 0) {

		$node = config('app.xmppHost');
		$data_from = $localuser .' '. $node .' '. $user .' '. $node .' "'. $nic1 .'" '. $group .' '. $subscription;
		$data_to = $user .' '. $node .' '. $localuser .' '. $node .' "'. $nic2 .'" '. $group .' '. $subscription;

		@exec('sudo/usr/sbin/ejabberdctl add_rosteritem'. $data_from .' 2>&1', $output1, $status1);
		@exec('sudo/usr/sbin/ejabberdctl add_rosteritem'. $data_to .' 2>&1', $output2, $status2);       
		return true;
    
    }


	/**
	*	Remove user from a group.
	*
	**/
	public static function removeUserGroup($roomname,$username){

		$node = config('app.xmppHost');

		//@exec('sudo/usr/sbin/ejabberdctl set_room_affiliation'.$roomname.''.$node.''.$username.' outcast');

		//ejabberdctl set_room_affiliation room conference.localhost user123@localhost outcast	

		//srg-user-add user server group host                   Adds user@server to group on host
		//srg-user-del user server group host                   Removes user@server from group on host

	}


}	



?>