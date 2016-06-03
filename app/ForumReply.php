<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForumReply extends Model
{
     protected $table = 'forums_reply';

	protected $primaryKey = 'id';

	public $fillable = ['reply', 'owner_id', 'post_id'];

	public $timestamps = true;

	public function forumreplylikes()
	{
		return $this->hasMany('App\ForumReplyLikes', 'reply_id', 'id');
	}

	public function replyLikesCount()
	{
		return $this->forumreplylikes()
		    ->selectRaw('reply_id, count(*) as replyLikesCount')
		    ->groupBy('reply_id');
	}

	public function replyComments()
	{
		return $this->hasMany('App\ForumReplyComments','reply_id','id');//->orderBy('comments.id','DESC');
	}

	public function replyCommentsCount()
	{
		return $this->replyComments()
			->selectRaw('reply_id, count(*) as replyCommentsCount')
			->groupBy('reply_id');
	}

	public function user()
	{
       return $this->belongsTo('App\User','owner_id','id')->select(['id','first_name', 'last_name', 'picture', 'country', 'state', 'city']);
	}
}
