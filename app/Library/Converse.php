<?php namespace App\Library;

use Validator, Input, Redirect, Request, Session, Hash, DB;

class Converse{

    static function register($username, $password) {

		$server = Request::server('HTTP_HOST'); 
 
		// if($server != 'fs.yiipro.com')
		// 	return true;
		// dd($username);
		// dd($password);
		$node = config('app.xmppHost');
		$response = @exec('sudo -u ejabberd /usr/sbin/ejabberdctl register '.$username.' '.$node.' '.$password.' 2>&1', $output, $status);
		// dd($response);exit;
		return true;
		
	}

}
	



?>