<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BroadcastMembers extends Model
{
    protected $table = 'broadcast_members';

	protected $primaryKey = 'id';

	public $fillable = ['broadcast_id', 'member_id'];

	public $timestamps = true;

   	public function user()
	{
	return $this->hasOne('App\User','id','member_id')->select(['id','first_name','last_name','xmpp_username']);
	}

	public function name()
	{
	return $this->hasMany('App\Broadcast','id','broadcast_id')->select(['id','title']);	
	}
}
