<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForumLikes extends Model
{
    protected $table = 'forums_likes';

	protected $primaryKey = 'id';

	public $fillable = ['liked', 'owner_id', 'post_id'];

	public $timestamps = true;

	public $messages = array(
		'liked.required' => 'Invalid value for like field',
		'owner_id.required' => 'User id is a required field',
		'owner_id.numeric' => 'User id must be numeric',
		'post_id.required' => 'Feed id is a required field',
		'post_id.numeric' => 'Feed id must be numeric',
	);

	public $rules = array(
		'liked' => 'required',
		'owner_id' => 'required|numeric',
		'post_id' => 'required|numeric'
	);
}
