<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BroadcastMembers extends Model
{
    protected $table = 'broadcast_members';

	protected $primaryKey = 'id';

	public $fillable = ['broadcast_id', 'member_id'];

	public $timestamps = true;

   	public function broadcast()
	{
	return $this->belongsTo('App\User', 'member_id');
	}

	
}
