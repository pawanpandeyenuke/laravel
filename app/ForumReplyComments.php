<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForumReplyComments extends Model
{
     protected $table = 'forums_reply_comments';

	protected $primaryKey = 'id';

	public $fillable = ['reply_comment', 'owner_id', 'reply_id'];

	public $timestamps = true;

	public function user()
	{
       return $this->belongsTo('App\User','owner_id','id')->select(['id','first_name', 'last_name', 'picture']);
	}
}
