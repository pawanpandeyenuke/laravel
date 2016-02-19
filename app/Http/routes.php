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
Route::post('api/likes', 'ApiController@likes');
Route::post('api/comments', 'ApiController@comments');
Route::post('api/post/create', 'ApiController@createPosts');


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
	Route::get('/', 'DashboardController@dashboard');	
});
