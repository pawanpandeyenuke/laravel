<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{
    protected $table = 'forums_post';

	protected $primaryKey = 'id';

	public $fillable = ['title', 'owner_id', 'category_id','forum_category_id','forum_category_breadcrum'];

	public $timestamps = true;

	public $messages = array(
		'owner_id.required' => 'User id is a required field',
		'owner_id.numeric' => 'User id must be numeric'
	);

	public $rules = array(
		'owner_id' => 'required|numeric'
	);

	public function forumpostlikes()
	{
		return $this->hasMany('App\ForumLikes', 'post_id', 'id');
	}

	public function forumPostLikesCount()
	{
		return $this->forumpostlikes()
		    ->selectRaw('post_id, count(*) as forumlikescount')
		    ->groupBy('post_id');
	}

	public function reply()
	{
		return $this->hasMany('App\ForumReply','post_id','id');//->orderBy('comments.id','DESC');
	}

	public function replyCount()
	{
		return $this->reply()
			->selectRaw('post_id, count(*) as replyCount')
			->groupBy('post_id');
	}

	public function user()
	{
       return $this->belongsTo('App\User','owner_id','id')->select(['id','first_name', 'last_name', 'picture']);
	}

	public function likedornot()
	{
          return $this->hasOne('App\ForumLikes','post_id','id');
	}

}
