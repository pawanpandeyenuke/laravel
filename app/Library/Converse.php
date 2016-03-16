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
	*	Add friend at ejabberd.
	*
	**/
/*	static function addFriend($localuser,$user,$nic, $group = 'general', $subscription = 'both', $update = 0) {

	    $node = config('app.xmppHost');
	    $data_from = $localuser .' '. $node .' '. $user .' '. $node .' "'. $nic .'" '. $group .' '. $subscription;
	    $data_to = $user .' '. $node .' '. $localuser .' '. $node .' "'. $nic .'" '. $group .' '. $subscription;

	    @exec('sudo -u ejabberd /usr/sbin/ejabberdctl add-rosteritem '. $data_from .' 2>&1', $output, $status);

	    return true;

	}*/

}
	



?>