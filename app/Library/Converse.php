<?php namespace App\Library;

use Validator, Input, Redirect, Request, Session, Hash, DB;

class Converse{

	/**
	*	Register at ejabberd.
	*
	**/
    static function register($username, $password) {

		$server = Request::server('HTTP_HOST'); 
 
		// if($server != 'fs.yiipro.com')
		// 	return true;
		//$node = config('app.xmppHost');

//$response = @exec('sudo -u ejabberd /usr/sbin/ejabberdctl register hemant1 fs.yiipro.com test123 2>&1', $output, $status);
//$response = @exec('sudo /usr/sbin/ejabberdctl register hemant1 fs.yiipro.com test123 2>&1', $output, $status);
//$response = @exec('sudo ejabberdctl register hemant1 fs.yiipro.com test123 2>&1', $output, $status);
//$response = @exec('sudo -u ejabberd ejabberdctl register hemant1 fs.yiipro.com test123 2>&1', $output, $status);
//$responace = @exec('sudo -u ejabberd /usr/sbin/ejabberdctl register '.$username.' '.$node.' '.$password.' 2>&1', $output, $status);

//echo "UsrName= $username and Host= $node"; exit;

//		$response = @exec('sudo ejabberdctl register '.$username.' '.$node.' '.$password.' 2>&1', $output, $status);
//		echo 'XMPP responce: '; print_r($output);exit;

		$node = config('app.xmppHost');
//		$response = @exec('sudo ejabberdctl register '.$username.' '.$node.' '.$password.' 2>&1', $output, $status);

//		dd($response);exit;
		$response = @exec('sudo ejabberdctl register '.$username.' '.$node.' '.$password.' 2>&1', $output, $status);

//		dd($response);exit;

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
