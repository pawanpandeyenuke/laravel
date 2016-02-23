<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
	protected $table = 'news_feed';

	protected $primaryKey = 'id';

	public $fillable = ['message', 'user_by', 'image'];

	public $timestamps = true;

	public $messages = array(
		'user_by.required' => 'User id is a required field',
		'user_by.numeric' => 'User id must be numeric'
	);

	public $rules = array(
		'user_by' => 'required|numeric'
	);

	public function likes()
	{
		return $this->hasMany('App\Like');
	}

	public function likesCount()
	{
	  return $this->likes()
	    ->selectRaw('feed_id, count(*) as likescount')
	    ->groupBy('feed_id');
	}

}
