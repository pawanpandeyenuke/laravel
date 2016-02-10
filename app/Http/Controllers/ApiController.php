<?php

namespace App\Http\Controllers;

use App\User, Auth;
use App\Http\Controllers\Controller;
use Validator, Input, Redirect, Request, Session, Hash;
use \Exception;

class ApiController extends Controller
{

	private $status = 'error'; 

	private $message = 'Something went wrong.';

	private $data = array();

	/*
	 * Login response.
	 */
	public function signin()
	{
		$input = Request::all();
		
		if( $input )
		{
			$user = Auth::attempt($input);

			if( !empty($user) ) {

				$this->status = 'success';
				$this->message = 'User logged in successfully.';
				$this->data = Auth::user()->toArray();
				
			}else {
				
				$this->message = 'Invalid username or password.';
				
			}
			
		}

		return $this->output();
	}


	/*
	 * Register response.
	 */
	public function signup()
	{
		$input = Request::all();		

		if( $input )
		{
			$user = new User;
			
			$validator = Validator::make($input, $user->apiRules, $user->messages);
			
			if($validator->fails()) {
				
				$this->message = $this->getError($validator);
				
			}else{
				
				$input['password'] = Hash::make($input['password']);
				$userData = $user->create($input);

				$tempEmail = explode('@', $input['email']);
				$tempId = ( isset( $userData->id ) && $userData->id != "" ) ? $userData->id : $userData->user_id;

				// Storing xmpp username and password.				
				$user = User::find($userData->id);
				$user->xmpp_username = $tempEmail[0].'_'.$tempId;
				$user->xmpp_password = md5($tempEmail[0]);
				$user->save();

				$this->status = 'success';
				$this->message = 'User registered successfully';				
				$this->data = $user->toArray();
				
			}
 
		}

		return $this->output();
	}
	

	/*
	 * Returns error if occuers.
	 */
	protected function getError( $validator )
	{
		$messages = [];

		if( $validator->errors() ){

			foreach($validator->errors()->getMessages() as $key => $val) {

				$messages[] = implode(' and ', $val);

			}

			$message = implode(' and ', $messages);			
			return $message ? $message.'.' : null;

		}

		return null;

	}

	/*
	 * Return output of the request.
	 */
	protected function output()
	{
		$this->data = empty($this->data) ? null : $this->data;

		return json_encode(array(
			'status' => $this->status, 
			'message' => $this->message,
			'data' => $this->data
		));

	}
	

}
