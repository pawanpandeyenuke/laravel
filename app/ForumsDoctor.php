<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForumsDoctor extends Model
{
    protected $table = "forums_doctor";

   	protected $primaryKey = 'id';

	public $timestamps = true;

	public $fillable = ['title'];
}
