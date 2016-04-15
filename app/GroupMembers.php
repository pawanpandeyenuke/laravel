<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupMembers extends Model
{
    protected $table = 'members';

	protected $primaryKey = 'id';

	public $fillable = ['group_id', 'member_id', 'status','joined_at','left_at', 'created_at', 'updated_at'];

	public $timestamps = true;
}
