<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobArea extends Model
{
	protected $table = 'job_area';

	protected $primaryKey = 'job_area_id';

	public $timestamps = false;
}
