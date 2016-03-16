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

	public function comments()
	{
		return $this->hasMany('App\Comment')->orderBy('comments.id','DESC');
	}

	public function commentsCount()
	{
	
		return $this->comments()
			->selectRaw('feed_id, count(*) as commentscount')
			->groupBy('feed_id');

	}

	public function user()
	{
          return $this->belongsTo('App\User','user_by','id')->select(['id','first_name', 'last_name', 'picture']);
	}

	public function likedornot()
	{
          return $this->hasOne('App\Like','feed_id','id');
	}
}
