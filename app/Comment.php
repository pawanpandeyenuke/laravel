<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	protected $table = 'comments';

	protected $primaryKey = 'id';

	public $fillable = ['comments', 'commented_by', 'feed_id'];

	public $timestamps = true;

	public $messages = array(
		'comments.required' => 'This is a required field',
		'commented_by.required' => 'User id is a required field',
		'commented_by.numeric' => 'User id must be numeric',
		'feed_id.required' => 'Feed id is a required field',
		'feed_id.numeric' => 'Feed id must be numeric',
	);

	public $rules = array(
		'comments' => 'required',
		'commented_by' => 'required|numeric',
		'feed_id' => 'required|numeric'
	);

	public function user()
	{
          return $this->belongsTo('App\User','commented_by','id')->select(['id','first_name', 'last_name', 'picture']);
	}
	
}
