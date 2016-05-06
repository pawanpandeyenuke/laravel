<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForumReply extends Model
{
     protected $table = 'forum_post';

	protected $primaryKey = 'id';

	public $fillable = ['reply', 'owner_id', 'post_id'];

	public $timestamps = true;
}
