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
 * @Push Notifications..
 *
 **/
Route::get('pushnotification-iphone', 'DashboardController@pushNotificationIphone');
Route::get('pushnotification-android', 'DashboardController@pushNotificationAndroid');

/**
 * @Ajax Routes..
 *
 **/
Route::post('ajax/getstates', 'AjaxController@getStates');
Route::post('ajax/getcities', 'AjaxController@getCities');

Route::post('ajax/posts', 'AjaxController@posts');
Route::post('ajax/editpost', 'AjaxController@editpost');
Route::post('ajax/editposts', 'AjaxController@editposts');

Route::post('ajax/editcomment','AjaxController@editcomment');
Route::post('ajax/editcomments','AjaxController@editcomments');

Route::post('ajax/like', 'AjaxController@like');

Route::post('ajax/comments/get', 'AjaxController@getCommentBox');
Route::post('ajax/comments/post', 'AjaxController@postcomment');
Route::post('ajax/post/get', 'AjaxController@getPostBox');

Route::post('/ajax/forumpostreply/get', 'AjaxController@getForumPostBox');


Route::post('ajax/getfriendslist', 'AjaxController@getfriendslist');

// Route::post('ajax/getxmppuser', 'AjaxController@getxmppuser');
Route::get('ajax/getxmppuser', 'AjaxController@getxmppuser');

Route::post('ajax/search-friend', 'AjaxController@searchfriend');

Route::post('ajax/webgetlikes', 'AjaxController@webgetlikes');

Route::post('/web/ajax/getposts', 'AjaxController@getAjaxPost');

Route::post('ajax/accept','AjaxController@accept');
Route::post('ajax/reject','AjaxController@reject');
Route::post('ajax/resend','AjaxController@resend');
Route::post('ajax/remove','AjaxController@remove');
Route::post('ajax/cancelrequest','AjaxController@cancelRequest');
Route::post('profile/ajax/cancelrequest','AjaxController@cancelRequest');

Route::post('ajax/deletepost','AjaxController@deletepost');
Route::post('ajax/deletecomments','AjaxController@deletecomments');


Route::post('/ajax/deletebox','AjaxController@deletebox');

Route::post('/ajax/jobcategory','AjaxController@getJobcategory');



Route::post('/ajax/sendrequest','AjaxController@sendRequest');
Route::post('profile/ajax/sendrequest','AjaxController@sendRequest');


Route::post('ajax/sendimage','AjaxController@sendImage');

Route::post('ajax/searchfriend','AjaxController@searchfriendlist');

Route::post('ajax/searchtabfriend','AjaxController@searchTabFriend');
Route::post('/ajax/view-more-friends-search','AjaxController@searchTabFriendMore');

Route::post('/ajax/delbroadcast','AjaxController@delBroadcast');

Route::post('/ajax/sendbroadcast','AjaxController@sendBroadcast');

Route::post('/ajax/delprivategroup','AjaxController@delPrivateGroup');

Route::post('/ajax/deluser','AjaxController@delUser');

Route::post('/ajax/editgroupname','AjaxController@editGroupName');

Route::post('ajax/viewmorefriends','AjaxController@viewMoreFriends');

Route::post('ajax/viewMoreForAll','AjaxController@viewMoreForAll');

Route::post('ajax/viewmoreposts','AjaxController@viewMorePosts');

Route::post('ajax/remove-education','AjaxController@removeEducationDetails');

Route::post('ajax/send-hotmail-invitation','AjaxController@sendHotmailInvitation');

Route::post('/ajax/forumsubgroup','AjaxController@forumSubGroup');

Route::post('/private-group-detail/ajax/groupimage','AjaxController@groupImage');

Route::post('/ajax/login','AjaxController@login');

Route::post('/ajax/delforumpost','AjaxController@delForumPost');
Route::post('/ajax/editforumpost','AjaxController@editForumPost');
Route::post('/ajax/editnewforumpost','AjaxController@editNewForumPost');
Route::post('/ajax/addnewforumpost','AjaxController@addNewForumPost');
Route::post('/ajax/likeforumpost','AjaxController@likeForumPost');
Route::post('/ajax/addnewforumreply','AjaxController@addNewForumReply');

Route::post('/ajax/view-more-forum-post','AjaxController@viewMoreForumPost');
Route::post('/ajax/view-more-forum-reply','AjaxController@viewMoreForumReply');
Route::post('/ajax/view-more-forum-comment','AjaxController@viewMoreForumComment');

Route::post('/ajax/mob-country-code','AjaxController@mobCountryCode');


Route::post('/ajax/delforumreply','AjaxController@delForumReply');

Route::post('/ajax/editforumreply','AjaxController@editForumReply');
Route::post('/ajax/editnewforumreply','AjaxController@editNewForumReply');

Route::post('/ajax/likeforumreply','AjaxController@likeForumReply');

Route::post('/ajax/forumreplycomment','AjaxController@forumReplyComment');

Route::post('/ajax/del-forum-reply-comment','AjaxController@delForumReplyComment');

Route::post('/ajax/getsubforums','AjaxController@getSubForums');

Route::post('/ajax/getsubforums-2','AjaxController@getSubForums2');

Route::post('/ajax/view-more-search-forum','AjaxController@viewMoreSearchForum');

Route::post('/ajax/get-path','AjaxController@getCurrentPath');

Route::post('/ajax/forum-del-confirm','AjaxController@forumDelConfirm');

Route::post('/ajax/leaveprivategroup','AjaxController@leavePrivateGroup');
Route::post('/ajax/getgroupdeatils','AjaxController@getGroupDetail');

Route::post('/ajax/getnewchatgroup','AjaxController@getNewChatGroup');
Route::post('/ajax/getchatgroup','AjaxController@getChatGroupList');
/**
 * @Api Routes..
 *
 **/
Route::post('v1/upload-chat-image','ApiController@uploadChatImage');

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
Route::post('api/update-picture','ApiController@updateProfilePicture');


Route::post('api/editpost','ApiController@editPost');
Route::post('api/deletepost','ApiController@deletePost');
Route::post('api/editcomment','ApiController@editComment');
Route::post('api/deletecomment','ApiController@deleteComment');

Route::post('api/getfriends','ApiController@getFriends');
Route::post('api/getusers','ApiController@getUsers');
Route::post('api/addfriend','ApiController@addFriend');
Route::post('api/acceptrequest','ApiController@acceptRequest');
Route::post('api/declinerequest','ApiController@declineRequest');



Route::post('api/sent-request-list','ApiController@getSentUsersList');
Route::post('api/remove-friend','ApiController@removeFriend');

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

Route::post('api/get-groups','ApiController@publicGroupGetIds');

Route::post('api/sent-request-list','ApiController@getSentUsersList');
Route::post('api/remove-friend','ApiController@removeFriend');

Route::post('api/search-user','ApiController@searchSiteFriends');

Route::post('api/invite-email','ApiController@inviteByEmail');
Route::post('api/non-existing-emails','ApiController@returnNonExistingEmails');

Route::post('api/get-job-category','ApiController@getJobCategories');

Route::post('api/get-userby-jid','ApiController@getUserByJID');

Route::post('api/get-forum-categories','ApiController@getForumCategories');
Route::post('api/get-doctor-categories','ApiController@getDoctorCategories');

Route::post('api/forum-post','ApiController@postForum');
Route::post('api/edit-forum-post','ApiController@editForumPost');

Route::post('api/forum-post-reply','ApiController@postForumReply');
Route::post('api/edit-forum-reply','ApiController@editForumReply');

Route::post('api/forum-post-comment','ApiController@postForumComment');

Route::match(['get', 'post'], 'api/get-forum-post','ApiController@getForumPosts');
Route::match(['get', 'post'], 'api/get-forum-post-reply','ApiController@getForumPostsReply');
Route::match(['get', 'post'], 'api/get-forum-post-reply-comment','ApiController@getForumPostsReplyComment');

Route::match(['get', 'post'], 'api/get-forum-post-details','ApiController@getForumPostsDetails');

Route::get('api/chat_image_page','ApiController@chatImagePage');

Route::post('/api/api-del-confirm','ApiController@confirmBox');

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
	/*Route::get('test', function()
	{
    	return view('auth.emails.password');
	});*/
	Route::get('/redirect/{provider}', 'SocialController@redirect');
	Route::get('/callback/{provider}', 'SocialController@callback');

	Route::get('home', 'HomeController@index');

	// Route::get('/searchfriends',"SearchController@searchFromUsers");
	Route::get('/searchfriends', function(){
		return redirect('/');
	});
	Route::post('/searchfriends',"SearchController@searchFromUsers");


	Route::post('/contactus','SearchController@contactUs');

	Route::get('/forums', 'SearchController@forumsList');
	Route::post('/forums', 'SearchController@forumsList');

	Route::get('sub-forums/{parentid}', 'SearchController@subForums');

	Route::get('sub-forums', 'SearchController@subForums');
	Route::post('sub-forums', 'SearchController@subForums');

	Route::get('forum-post/{name}', 'SearchController@forumPost');
	Route::post('forum-post', 'SearchController@forumPost');

	Route::get('sub-cat-forums/{id}','SearchController@subCatForums');

	Route::get('view-forum-posts/{id}','SearchController@viewForumPosts');
	Route::post('view-forum-posts','SearchController@viewForumPostsOpt');
	Route::get('view-forum-posts', function(){
		return redirect('forums');
	});

	Route::get('demo', 'SearchController@demo');
    Route::get('forum-post-reply/{forumpostid}', 'SearchController@forumPostReply');

    Route::post('search-forum', 'SearchController@searchForum');
	Route::get('search-forum', 'SearchController@searchForumGet');

	Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/home', 'HomeController@index');
	Route::get('dashboard', 'DashboardController@dashboard');
	Route::post('dashboard', 'DashboardController@dashboard');
	Route::get('settings/privacy', 'DashboardController@settings');	
	Route::post('settings/privacy', 'DashboardController@settings');
	Route::get('chatroom', 'DashboardController@chatroom');
	Route::get('friends', 'DashboardController@friendRequests');
	Route::get('invite-friends', 'ContactImporter@inviteFriends');
	Route::post('invite-friends', 'ContactImporter@inviteFriends');
	Route::get('invite-contacts', 'ContactImporter@inviteContactList');

	Route::get('group', 'DashboardController@group');
	Route::get('subgroup/{parentid}', 'DashboardController@subgroup');
	Route::get('sub-cat-group/{parentid}','DashboardController@subCatGroup');
	Route::get('groupchat/{id}', 'DashboardController@groupchat');
		Route::get('groupchat', function(){
			return redirect('group');
	});
	Route::post('groupchat', 'DashboardController@groupchat');	

	Route::get('groupchat/pg/{groupid}','DashboardController@privateGroupChat');

	Route::get('friends-chat','DashboardController@friendsChat');
	

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
 
	Route::get('newpassword','SearchController@newPassword');
	Route::post('newpassword','SearchController@newPassword');

	Route::get('terms-conditions','SearchController@termsConditions');
	Route::post('terms-conditions','SearchController@termsConditions');

	Route::get('send-verification-link','SearchController@verify');
	Route::post('send-verification-link','SearchController@verify');
	Route::get('email-verified/{user_id}/{confirmation_code}','SearchController@emailVerified');	
 
	Route::get('/', function(){
		if(Auth::check())
			return redirect()->action('DashboardController@dashboard');
		else
			return view('auth.register');
	});

	Route::get('register/verify/{confirmation_code}', [
    'as' => 'confirmation_path',
    'uses' => 'SearchController@confirm'
	]);

	Route::get('change-password','DashboardController@changePassword');
	Route::post('change-password','DashboardController@changePassword');

});
