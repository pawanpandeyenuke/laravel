<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobArea extends Model
{
	protected $table = 'job_area';

	protected $primaryKey = 'job_area_id';

	public $timestamps = false;

	public function getJobCategories()
	{
		return $this->hasMany('App\JobCategory', 'job_area_id','job_area_id')->where('status', '1')->select(['job_area_id', 'job_category_id as id', 'job_category as name']);
	}
	
}
