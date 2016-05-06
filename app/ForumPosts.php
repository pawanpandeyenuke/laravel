<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForumPosts extends Model
{
    protected $table = 'forum_post';

	protected $primaryKey = 'id';

	public $fillable = ['title', 'owner_id', 'category_id'];

	public $timestamps = true;

}
