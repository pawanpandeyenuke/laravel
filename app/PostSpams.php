<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostSpams extends Model
{
    protected $table = 'post_spams';
    
	protected $primaryKey = 'spam_id';
	
	public $timestamps = false;
	
	protected $fillable = ['user_id', 'post_id', 'reason'];

	public static function boot()
    {
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }
}