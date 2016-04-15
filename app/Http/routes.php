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
Route::post('ajax/editpost', 'AjaxController@editpost');
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

Route::post('ajax/searchtabfriend','AjaxController@searchTabFriend');


Route::post('/ajax/delbroadcast','AjaxController@delBroadcast');

Route::post('/ajax/sendbroadcast','AjaxController@sendBroadcast');

Route::post('/ajax/delprivategroup','AjaxController@delPrivateGroup');

Route::post('/ajax/deluser','AjaxController@delUser');

Route::post('/ajax/editgroupname','AjaxController@editGroupName');

Route::post('ajax/viewmorefriends','AjaxController@viewMoreFriends');

Route::post('ajax/viewmoreposts','AjaxController@viewMorePosts');

Route::post('ajax/remove-education','AjaxController@removeEducationDetails');

Route::post('ajax/send-hotmail-invitation','AjaxController@sendHotmailInvitation');


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

Route::post('api/push-notification','ApiController@updatePushNotificationDetails');
Route::post('api/chatsendimage','ApiController@chatSendImage');

Route::post('api/get-chat-category','ApiController@getChatCategories');
Route::post('api/get-public-groups','ApiController@getPublicGroups');

Route::post('api/get-chat-category','ApiController@getChatCategories');
Route::post('api/get-public-groups','ApiController@getPublicGroups');
Route::post('api/exit-group','ApiController@exitGroup');

Route::post('api/broadcast-add','ApiController@broadcastAdd');
Route::post('api/get-broacast-list','ApiController@getBroadcastList');
Route::post('api/delete-broadcast','ApiController@deleteBroadcast');

Route::post('api/private-group-add','ApiController@privateGroupAdd');
Route::post('api/get-group-list','ApiController@getGroupList');
Route::post('api/delete-private-group','ApiController@deletePrivateGroup');


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

	Route::get('groupchat/pg/{groupid}/{groupname}','DashboardController@groupchat');

	Route::get('profile/{id}', 'DashboardController@profile');
	Route::post('profile/{id}', 'DashboardController@profile');

	Route::get('editprofile/{id}', 'DashboardController@editUserProfile');
	Route::post('editprofile/{id}', 'DashboardController@editUserProfile');	


	Route::get('broadcast-list', 'DashboardController@broadcastList');
	Route::post('broadcast-list', 'DashboardController@broadcastList');
	
	Route::get('broadcast-add', 'DashboardController@broadcastAdd');
	Route::post('broadcast-add', 'DashboardController@broadcastAdd');

	Route::get('broadcast-msg/{broadcastid}', 'DashboardController@broadcastMessage');
	
	Route::get('private-group-list/{privategroupid}', 'DashboardController@privateGroupList');
	Route::get('private-group-list', 'DashboardController@privateGroupList');
	Route::post('private-group-list/{privategroupid}', 'DashboardController@privateGroupList');
	
	Route::get('private-group-add', 'DashboardController@privateGroupAdd');
	Route::post('private-group-add', 'DashboardController@privateGroupAdd');

	Route::get('private-group-detail/{privategroupid}', 'DashboardController@privateGroupDetail');
	//Route::post('private-group-detail', 'DashboardController@privateGroupDetail');



	Route::get('google/client', 'ContactImporter@inviteFriends');
	Route::get('google/client/callback', 'ContactImporter@inviteContactList');
	Route::post('google/client/callback', 'ContactImporter@inviteContactList');

	// Route::get('hotmail/client', 'ContactImporter@hotmail');
	Route::get('hotmail/client/callback', 'ContactImporter@hotmailCallback');

	// Route::get('yoauth/client', 'ContactImporter@hotmail');
	// Route::get('yoauth/client/callback', 'ContactImporter@callbackH');

	Route::get('linkedin/client', 'ContactImporter@linkedin');
	Route::get('linkedin/client/callback', 'ContactImporter@linkedinCallback');

		Route::get('/demopage', 'DashboardController@demopage');
		Route::post('/demopage', 'DashboardController@demopage');

});
