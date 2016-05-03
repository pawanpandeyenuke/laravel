<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forums extends Model
{
	protected $table = 'forums';

	protected $primaryKey = 'id';

	public $timestamps = true;
}
?>