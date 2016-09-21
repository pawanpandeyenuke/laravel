<?php

namespace App;

//~ use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Feed, App\Like, App\Comment;

class User extends Authenticatable
{
	
	protected $table = 'users';

	protected $primaryKey = 'id';

	public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'gender', 'confirmation_code', 'birthday', 'phone_no', 'country', 'state', 'city', 'fb_id', 'linked_id', 'twitter_id', 'google_id', 'push_token', 'device_type', 'xmpp_username', 'xmpp_password', 'is_email_verified', 'status','marital_status', 'country_code', 'access_token' ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    	
	public $messages = array(
		'email.required' => 'Please enter email address',
		'email.email' => 'Please enter valid email',
		'email.unique' => 'Email already exist',
		'password.required' => 'Please enter password',
		'id.required'=>'Please enter user id',
		'id.numeric'=>'User id should be numeric'
	);
	
	public $apiRules = array(
		'email' => 'required|email',
		'password' => 'required|min:6'
	);
	
	public $socialApiRules = array(
		'email' => 'required|email'
	);

	public $apiViewRules = array(
			'id'=>'numeric|required'
	);
	
  
	public function country()
	{
		return $this->hasOne('App\Country','country_id','country');
	}


	public function education()
	{
		return $this->hasMany('App\EducationDetails','user_id','id');
	}

	public function broadcastmembers()
	{
		return $this->hasMany('App\BroadcastMembers');
	}

	public function searchfriend()
	{
		return $this->hasMany('App\Friend', 'user_id', 'id');
	}

	public function searchUserFriend()
	{
		return $this->hasMany('App\Friend', 'user_id', 'id');
	}

	public function friends()
	{
        return $this->belongsTo('App\Friend','friend_id','id')->select(['id','first_name', 'last_name', 'picture','xmpp_username']);
	}

/*	public function settingtype()
	{
        return $this->belongsTo('App\Setting','user_id','id');
	}
*/
}
