<?php namespace App\Library;

use Validator, Input, Redirect, Request, Session, Hash, DB;

class Converse{

    static function register($username, $password) {

		$server = Request::server('HTTP_HOST'); 
 
		// if($server != 'fs.yiipro.com')
		// 	return true;

		$node = config('app.xmppHost');
$response = @exec('sudo -u ejabberd /usr/sbin/ejabberdctl register hemant1 fs.yiipro.com test123 2>&1', $output, $status);
//$response = @exec('sudo /usr/sbin/ejabberdctl register hemant1 fs.yiipro.com test123 2>&1', $output, $status);
//$response = @exec('sudo ejabberdctl register hemant1 fs.yiipro.com test123 2>&1', $output, $status);
//$response = @exec('sudo -u ejabberd ejabberdctl register hemant1 fs.yiipro.com test123 2>&1', $output, $status);

//$responace = @exec('sudo -u ejabberd /usr/sbin/ejabberdctl register '.$username.' '.$node.' '.$password.' 2>&1', $output, $status);
//		$response = @exec('sudo /usr/sbin/ejabberdctl register '.$username.' '.$node.' '.$password.' 2>&1', $output, $status);
		echo 'XMPP responce: '; print_r($output);exit;

		return true;
		
	}

}
	



?>
