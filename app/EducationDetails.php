<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EducationDetails extends Model
{
	
	protected $table = 'education_details';

	protected $primaryKey = 'id';

	public $timestamps = true;


	protected $fillable = ['id', 'education_level', 'specialization', 'graduation_year_from', 'graduation_year_to', 'currently_studying', 'education_establishment', 'country_of_establishment', 'city_of_establishment', 'job_area', 'job_category', 'created_at', 'updated_at'];
	
}

