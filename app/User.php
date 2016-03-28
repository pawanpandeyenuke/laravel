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
    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'gender', 'dob', 'mobile_no', 'country', 'state', 'city', 'fb_id', 'linked_id', 'twitter_id', 'google_id', 'push_token', 'device_type', 'xmpp_username', 'xmpp_password', 'is_email_verified', 'status' ];

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
	);
	
	public $apiRules = array(
		'email' => 'required|email|unique:users,email',
		'password' => 'required'
	);
	
	public $socialApiRules = array(
		'email' => 'required|email'
	);

	public function country()
	{
		return $this->hasOne('App\Country','country_id','country');
	}

	public function education()
	{
		return $this->hasOne('App\EducationDetails','id','id');
	}

}
