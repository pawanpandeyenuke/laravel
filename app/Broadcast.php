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

   	public function broadcastMembers()
	{
		return $this->hasMany('App\BroadcastMembers', 'broadcast_id', 'id')->join('users', 'users.id', '=', 'broadcast_members.member_id')->select('broadcast_members.broadcast_id', 'broadcast_members.member_id', 'users.first_name','users.last_name','users.xmpp_username');
	}

}
