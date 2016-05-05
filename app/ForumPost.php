<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{
    protected $table = 'forums_post';

	protected $primaryKey = 'id';

	public $fillable = ['title', 'owner_id', 'category_id'];

	public $timestamps = true;

}
