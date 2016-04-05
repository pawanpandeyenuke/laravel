<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
	protected $table = 'groups';

	protected $primaryKey = 'id';

	public $fillable = ['title', 'status', 'owner_id'];

	public $timestamps = true;

	public function members()
	{
		return $this->hasMany('App\GroupMembers','group_id','id')->select(['group_id','member_id']);
	}
}
