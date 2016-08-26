<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnsubscribedUsers extends Model
{
	protected $table = 'unsubscribed_users';

	protected $primaryKey = 'id';

	public $timestamps = true;

	protected $fillable = ['email'];

}
