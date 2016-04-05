<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Broadcast extends Model
{
    protected $table = 'broadcast';

	public $primaryKey = 'id';

	public $fillable = ['title', 'user_id','members'];

	public $timestamps = true;
}
