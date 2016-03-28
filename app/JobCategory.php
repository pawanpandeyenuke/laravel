<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
	protected $table = 'job_category';

	protected $primaryKey = 'job_category_id';

	public $timestamps = false;
}
