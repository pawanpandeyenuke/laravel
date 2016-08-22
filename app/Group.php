<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
	protected $table = 'groups';

	protected $primaryKey = 'id';

	public $fillable = ['title', 'status', 'owner_id', 'group_jid', 'picture'];

	public $timestamps = true;

	public function members()
	{
		return $this->hasMany('App\GroupMembers','group_id','id')->where('status', '!=', 'Left')->select(['group_id','member_id','status']);
	}

   	public function groupMembers()
	{
		return $this->hasMany('App\GroupMembers', 'group_id', 'id')->join('users', 'users.id', '=', 'members.member_id')->select('members.group_id','members.status', 'members.member_id', 'users.first_name','users.last_name','users.xmpp_username','users.picture');
	}

}
