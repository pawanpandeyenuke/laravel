<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefaultGroup extends Model
{
	protected $table = 'default_groups';

	public $primaryKey = 'id';

	public $fillable = ['group_name', 'group_by'];

	public $timestamps = true;

	public function user()
	{
          return $this->belongsTo('App\User','group_by','id')->select(['id','first_name', 'last_name', 'picture', 'xmpp_username']);
	}

}
