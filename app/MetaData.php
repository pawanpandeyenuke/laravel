<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MetaData extends Model
{
    protected $table = 'meta_data';

    protected $primaryKey = 'id';

    public $timestamps = true;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url', 'page_name', 'meta_title', 'meta_description', 'meta_keyword', 'status'
    ];
}
