<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportUser extends Model
{

	protected $table = 'report_users';

	protected $primaryKey = 'id';

	public $timestamps = true;

	protected $fillable = ['user_id', 'user_jid', 'blocked_user_id', 'blocked_user_jid', 'reason', 'message'];

}
