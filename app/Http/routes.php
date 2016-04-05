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
Route::post('ajax/editposts', 'AjaxController@editposts');
Route::post('ajax/like', 'AjaxController@like');

Route::post('ajax/comments/get', 'AjaxController@getCommentBox');
Route::post('ajax/comments/post', 'AjaxController@postcomment');
Route::post('ajax/post/get', 'AjaxController@getPostBox');

Route::post('ajax/getfriendslist', 'AjaxController@getfriendslist');

Route::post('ajax/getxmppuser', 'AjaxController@getxmppuser');
Route::post('ajax/search-friend', 'AjaxController@searchfriend');

Route::post('ajax/webgetlikes', 'AjaxController@webgetlikes');

Route::post('/web/ajax/getposts', 'AjaxController@getAjaxPost');

Route::post('ajax/accept','AjaxController@accept');
Route::post('ajax/reject','AjaxController@reject');
Route::post('ajax/resend','AjaxController@resend');
Route::post('ajax/remove','AjaxController@remove');

Route::post('ajax/deletepost','AjaxController@deletepost');
Route::post('ajax/deletecomments','AjaxController@deletecomments');


Route::post('ajax/deletebox','AjaxController@deletebox');
//<<<<<<< HEAD
Route::post('/ajax/jobcategory','AjaxController@getJobcategory');



Route::post('ajax/sendrequest','AjaxController@sendRequest');

Route::post('ajax/sendimage','AjaxController@sendImage');

Route::post('ajax/searchfriend','AjaxController@searchfriendlist');

//Route::post('ajax/profilesave','AjaxController@editProfile');

Route::post('ajax/searchtabfriend','AjaxController@searchTabFriend');

Route::post('ajax/viewmorefriends','AjaxController@viewMoreFriends');
Route::post('ajax/viewmoreposts','AjaxController@viewMorePosts');


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
Route::post('api/updateprofile','ApiController@updateProfile');


Route::post('api/editpost','ApiController@editPost');
Route::post('api/deletepost','ApiController@deletePost');
Route::post('api/editcomment','ApiController@editComment');
Route::post('api/deletecomment','ApiController@deleteComment');

Route::post('api/getfriends','ApiController@getFriends');
Route::post('api/getusers','ApiController@getUsers');
Route::post('api/addfriend','ApiController@addFriend');
Route::post('api/acceptrequest','ApiController@acceptRequest');
Route::post('api/declinerequest','ApiController@declineRequest');


Route::post('api/chatsendimage','ApiController@chatSendImage');


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
	
Route::get('/redirect/{provider}', 'SocialController@redirect');
Route::get('/callback/{provider}', 'SocialController@callback');

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
	Route::get('friends', 'DashboardController@friendRequests');
	Route::get('invite-friends', 'ContactImporter@inviteFriends');
	Route::post('invite-friends', 'ContactImporter@inviteFriends');
	Route::get('invite-contacts', 'ContactImporter@inviteContactList');

	Route::get('group', 'DashboardController@group');
	Route::get('subgroup/{parentid}', 'DashboardController@subgroup');
	Route::get('subgroup/{parentid}/{name}', 'DashboardController@subgroup');
	Route::get('groupchat/{parentname}', 'DashboardController@groupchat');
	Route::get('groupchat', 'DashboardController@groupchat');

	Route::get('profile/{id}', 'DashboardController@profile');
	Route::post('profile/{id}', 'DashboardController@profile');

	Route::get('google/client', 'ContactImporter@inviteFriends');
	Route::get('google/client/callback', 'ContactImporter@inviteContactList');
	Route::post('google/client/callback', 'ContactImporter@inviteContactList');

	// Route::get('hotmail/client', 'ContactImporter@hotmail');
	// Route::get('hotmail/client/callback', 'ContactImporter@callbackH');

});
