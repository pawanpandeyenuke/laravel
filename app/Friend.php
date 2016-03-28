<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
	protected $table = 'friends';

	protected $primaryKey = 'id';

	public $timestamps = true;

	public function user()
	{
          return $this->belongsTo('App\User','user_id','id')->select(['id','first_name', 'last_name', 'picture','xmpp_username']);
	}

	public function friends()
	{
          return $this->belongsTo('App\User','friend_id','id')->select(['id','first_name', 'last_name', 'picture','xmpp_username']);
	}

}
