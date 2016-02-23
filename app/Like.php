<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
	protected $table = 'likes';

	protected $primaryKey = 'id';

	public $fillable = ['liked', 'user_id', 'feed_id'];

	public $timestamps = true;

	public $messages = array(
		'liked.required' => 'Invalid value for like field',
		'user_id.required' => 'User id is a required field',
		'user_id.numeric' => 'User id must be numeric',
		'feed_id.required' => 'Feed id is a required field',
		'feed_id.numeric' => 'Feed id must be numeric',
	);

	public $rules = array(
		'liked' => 'required',
		'user_id' => 'required|numeric',
		'feed_id' => 'required|numeric'
	);


}
