<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
	
	protected $table = 'state';

	protected $primaryKey = 'state_id';

	public $timestamps = true;
	
}
