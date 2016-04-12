<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Broadcast extends Model
{
    protected $table = 'broadcast';

	public $primaryKey = 'id';

	public $fillable = ['title', 'user_id'];

	public $timestamps = true;

 	public function members()
	{
		
	return $this->hasMany('App\BroadcastMembers','broadcast_id','id')->select(['broadcast_id','member_id']);
	}
}
