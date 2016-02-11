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

Route::get('/', function () {
    return view('welcome');
});


/*
|
| @Api Routes..
|
*/
Route::post('api/signin', 'ApiController@signin');
Route::post('api/signup', 'ApiController@signup');


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
	
Route::get('/redirect', 'SocialAuthController@redirect');
Route::get('/callback', 'SocialAuthController@callback');

Route::get('/redirecttwitter', 'SocialAuthController@redirecttwitter');
Route::get('/callbacktwitter', 'SocialAuthController@callbacktwitter');

Route::get('/redirectgoogle', 'SocialAuthController@redirectgoogle');
Route::get('/callbackgoogle', 'SocialAuthController@callbackgoogle');

Route::get('/redirectlinkedin', 'SocialAuthController@redirectlinkedin');
Route::get('/callbacklinkedin', 'SocialAuthController@callbacklinkedin');

Route::get('home', 'HomeController@index');

Route::group(['middleware' => ['web']], function () {
    //
});

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/home', 'HomeController@index');
});
