<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BroadcastMessages extends Model
{
    protected $table = 'broadcast_messages';

	protected $primaryKey = 'id';

	public $fillable = ['broadcast_message', 'broadcast_id', 'broadcast_by','created_at'];

	public $timestamps = true;
}
