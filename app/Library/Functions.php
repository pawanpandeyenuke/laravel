<?php

namespace App\Library;

use Validator, Input, Redirect, Request, Session, Hash, DB, Config;
use App\User, App\Setting, App\Friend;

class Functions
{
	// Search users
	public static function searchUsers($keyword, $authUserId = 0, $page = 1, $perPage = 10)
	{
	    $offset = ($page - 1) * $perPage;
	    if( !$authUserId )
	    {
	        $model = DB::table('users')
	            ->select('users.*')
	            ->join('settings as s', 'users.id', '=', 's.user_id')
	            ->where('s.setting_title', 'friend-request')
	            ->where('s.setting_value', 'all')
	            ->where( function( $query ) use ( $keyword ) {
	                $expVal = explode(' ', $keyword);
	                foreach( $expVal as $key => $value ) {                          
	                    $query->orWhere( 'users.last_name', 'LIKE', '%'. $value.'%' )
	                        ->orWhere( 'users.first_name', 'LIKE', '%'. $value.'%' );  
	                }
	            });
	    }
	    else
	    {
	        $user = User::where(['id' => $authUserId])->first();
	        $mySetting = Setting::where(['user_id' => $authUserId, 'setting_title' => 'contact-request'])->value('setting_value');
	        
	        // Get friends of friends
	        $arr = $myFriends = Friend::where('user_id',$authUserId)->where('status', 'Accepted')->pluck('friend_id')->toArray();
	        $arr[] = $authUserId;
	        $fof = Friend::whereIn('user_id', $myFriends)->whereNotIn('friend_id', $arr)->where('status', 'Accepted')->pluck('friend_id')->toArray();
	        
	        if( $mySetting == 'friends-of-friends' )
	        {
	            $model = DB::table('users')
	                ->select('users.*')
	                ->join('settings as s', 'users.id', '=', 's.user_id')
	                ->where( function( $query ) use ( $keyword ) {
	                    $expVal = explode(' ', $keyword);
	                    foreach( $expVal as $key => $value ) {                          
	                        $query->orWhere( 'users.last_name', 'LIKE', '%'. $value.'%' )
	                            ->orWhere( 'users.first_name', 'LIKE', '%'. $value.'%' );
	                    }
	                })
	                ->whereIn('users.id', $fof)
	                ->where('users.id', '!=', $authUserId)
	                ->where(function($q) use ( $fof, $user ) {
	                    $q->where(function($query) use ( $fof, $user ){
	                        $query->where('s.setting_title', 'friend-request')
	                            ->where('s.setting_value', 'nearby-app-user')
	                            ->where('users.country', $user->country)
	                            ->where('users.state', $user->state)
	                            ->where('users.city', $user->city);
	                    })->orWhere(function($query){
	                        $query->where('s.setting_title', 'friend-request')
	                            ->where(function($query1){
	                                $query1->where('s.setting_value', 'friends-of-friends')
	                                    ->orWhere('s.setting_value', 'all');
	                            });
	                    });
	                });
	        }
	        elseif( $mySetting == 'nearby-app-user' )
	        {
	            $model = DB::table('users')
	                ->select('users.*')
	                ->join('settings as s', 'users.id', '=', 's.user_id')
	                ->where( function( $query ) use ( $keyword ) {
	                    $expVal = explode(' ', $keyword);
	                    foreach( $expVal as $key => $value ) {                          
	                        $query->orWhere( 'users.last_name', 'LIKE', '%'. $value.'%' )
	                            ->orWhere( 'users.first_name', 'LIKE', '%'. $value.'%' );
	                    }
	                })
	                ->where('users.id', '!=', $authUserId)
	                ->where('users.country', $user->country)
	                ->where('users.state', $user->state)
	                ->where('users.city', $user->city)
	                ->where(function($q) use ( $fof ) {
	                    $q->where(function($query) use ( $fof ){
	                        $query->where('s.setting_title', 'friend-request')
	                            ->where('s.setting_value', 'friends-of-friends')
	                            ->whereIn('users.id', $fof);
	                    })->orWhere(function($query){
	                        $query->where('s.setting_title', 'friend-request')
	                            ->where(function($query1){
	                                $query1->where('s.setting_value', 'nearby-app-user')
	                                    ->orWhere('s.setting_value', 'all');
	                            });
	                    });
	                });
	        }
	        else
	        {
	            $model = DB::table('users')
	                ->select('users.*')
	                ->join('settings as s', 'users.id', '=', 's.user_id')
	                ->where('users.id', '!=', $authUserId)
	                ->where( function( $query ) use ( $keyword ) {
	                    $expVal = explode(' ', $keyword);
	                    foreach( $expVal as $key => $value ) {                          
	                        $query->orWhere( 'users.last_name', 'LIKE', '%'. $value.'%' )
	                            ->orWhere( 'users.first_name', 'LIKE', '%'. $value.'%' );
	                    }
	                })
	                ->where(function($q) use ( $fof, $user ) {
	                    $q->where(function($query) use ( $fof ){
	                        $query->where('s.setting_title', 'friend-request')
	                            ->where('s.setting_value', 'friends-of-friends')
	                            ->whereIn('users.id', $fof);
	                    })->orWhere(function($query) use($user) {
	                        $query->where('s.setting_title', 'friend-request')
	                            ->where('s.setting_value', 'nearby-app-user')
	                            ->where('users.country', $user->country)
	                            ->where('users.state', $user->state)
	                            ->where('users.city', $user->city);
	                    })->orWhere(function($query){
	                        $query->where('s.setting_title', 'friend-request')
	                            ->where('s.setting_value', 'all');
	                    });
	                });
	        }
	    }
	    
	    $count = $model->count();
	    $result = $model->skip($offset)->take($perPage)->get();
	    $pages = ceil($count/$perPage);
	    
	    return array(
	        'total' => $count,
	        'pages' => $pages,
	        'records' => $result
	    );
	}
}
?>