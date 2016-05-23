<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{
    protected $table = 'forums_post';

	protected $primaryKey = 'id';

	public $fillable = ['title', 'owner_id', 'category_id'];

	public $timestamps = true;

	public $messages = array(
		'owner_id.required' => 'User id is a required field',
		'owner_id.numeric' => 'User id must be numeric'
	);

	public $rules = array(
		'owner_id' => 'required|numeric'
	);

	public function likes()
	{
		return $this->hasMany('App\ForumLikes');
	}

	public function likesCount()
	{
		return $this->likes()
		    ->selectRaw('post_id, count(*) as likescount')
		    ->groupBy('post_id');
	}

	// public function reply()
	// {
	// 	return $this->hasMany('App\ForumReply');//->orderBy('comments.id','DESC');
	// }

	// public function replyCount()
	// {
	// 	return $this->reply()
	// 		->selectRaw('post_id, count(*) as commentscount')
	// 		->groupBy('post_id');
	// }

	public function user()
	{
       return $this->belongsTo('App\User','owner_id','id')->select(['id','first_name', 'last_name', 'picture']);
	}

	public function likedornot()
	{
          return $this->hasOne('App\ForumLikes','post_id','id');
	}

}
