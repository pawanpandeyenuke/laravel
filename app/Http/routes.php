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
Route::post('api/forget-Password', 'ApiController@forgetPassword');
Route::post('api/countries', 'ApiController@getCountries');
Route::post('api/states', 'ApiController@getStates');
Route::post('api/cities', 'ApiController@getCities');
Route::post('api/social-login', 'ApiController@getSocialLogin');


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

Route::group(['middleware' => ['web']], function () {
    //
});

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/home', 'HomeController@index');
});
