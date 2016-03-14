<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/**
 * @Ajax Routes..
 *
 **/
Route::post('ajax/getstates', 'AjaxController@getStates');
Route::post('ajax/getcities', 'AjaxController@getCities');

Route::post('ajax/posts', 'AjaxController@posts');
Route::post('ajax/like', 'AjaxController@like');
Route::post('ajax/comments/get', 'AjaxController@getCommentBox');
Route::post('ajax/comments/post', 'AjaxController@postcomment');
Route::post('ajax/getfriendslist', 'AjaxController@getfriendslist');

Route::post('ajax/getxmppuser', 'AjaxController@getxmppuser');
Route::post('ajax/search-friend', 'AjaxController@searchfriend');

Route::post('ajax/webgetlikes', 'AjaxController@webgetlikes');

Route::post('/web/ajax/getposts', 'AjaxController@getAjaxPost');

Route::post('ajax/accept','AjaxController@accept');
Route::post('ajax/reject','AjaxController@reject');
Route::post('ajax/resend','AjaxController@resend');
Route::post('ajax/remove','AjaxController@remove');

/**
 * @Api Routes..
 *
 **/
Route::post('api/signin', 'ApiController@signin');
Route::post('api/signup', 'ApiController@signup');
Route::post('api/forget-Password', 'ApiController@forgetPassword');
Route::post('api/social-login', 'ApiController@getSocialLogin');

Route::post('api/countries', 'ApiController@getCountries');
Route::post('api/states', 'ApiController@getStates');
Route::post('api/cities', 'ApiController@getCities');

Route::post('api/posts', 'ApiController@getPosts');
Route::post('api/post/create', 'ApiController@createPosts');

Route::post('api/likes', 'ApiController@likes');

Route::post('api/comments', 'ApiController@getComments');
Route::post('api/comments/create', 'ApiController@postComments');

Route::post('api/getprofile','ApiController@getProfile');


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
	
Route::get('/redirect/{provider}', 'SocialAuthController@redirect');
Route::get('/callback/{provider}', 'SocialAuthController@callback');

Route::get('home', 'HomeController@index');


Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/home', 'HomeController@index');
	Route::get('dashboard', 'DashboardController@dashboard');
	Route::post('dashboard', 'DashboardController@dashboard');
	Route::get('settings/privacy', 'DashboardController@settings');	
	Route::post('settings/privacy', 'DashboardController@settings');
	Route::get('/', 'DashboardController@dashboard');	
	Route::get('chatroom', 'DashboardController@chatroom');
	Route::get('requests', 'DashboardController@friendRequests');

	Route::get('group', 'DashboardController@group');
	Route::get('subgroup/{parentid}', 'DashboardController@subgroup');
	Route::get('subgroup/{parentid}/{name}', 'DashboardController@subgroup');
	Route::get('groupchat/{parentname}', 'DashboardController@groupchat');
	Route::get('groupchat', 'DashboardController@groupchat');

	Route::get('profile/{id}', 'DashboardController@profile');

});
