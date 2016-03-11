<?php

namespace App\Http\Controllers;

use Auth, App\Feed, DB, App\Setting, App\Group, App\Friend, App\DefaultGroup;
use Request, Session, Validator, Input, Cookie;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

	public function dashboard()
	{
        try{


            $per_page = 15;

            $feeds = Feed::with('likesCount')->with('commentsCount')->with('user')->with('likes')->with('comments')
            ->orderBy('news_feed.id','DESC')
            ->take($per_page)
            ->get();


           // echo '<pre>';print_r($feeds);die;
            /*$feeds = Feed::with('user')
                        ->leftJoin('likes', 'likes.feed_id', '=', 'news_feed.id')
                        ->leftJoin('comments', 'comments.feed_id', '=', 'news_feed.id')
                        ->groupBy('news_feed.id')
                        ->get(['news_feed.*','comments.*',DB::raw('count(likes.id) as likes'),DB::raw('count(comments.id) as commentscount')])
                        ->toArray();*/
            
            
/*            if(Request::isMethod('post'))
            {
                $input = Request::all();
                if($input)
                {
                    $feeds = new Feed;
                    $feeds->message = $input['message'];
                    $feeds->image = isset($input['image']) ? $input['image'] : '';
                    $feeds->user_by = Auth::User()->id;
                    // print_r($feeds->user_by);die;
                    $feeds->save();
                }
                // echo '<pre>';print_r($input);die;
            }*/
        }catch( Exception $e){
            $this->error = $e->getMessage();
        }

		return view('dashboard.dashboard')
            ->with('feeds', $feeds);
	}


    public function settings()
    {
        $arguments = Request::all();
        unset($arguments['_token']);
        if(Request::isMethod('post')){

            foreach($arguments as $key => $data){

                $userSetting = Setting::where([
                                    'setting_title' => $key,
                                    'user_id' => Auth::User()->id,
                                ])->get()->toArray();

                if(!empty($userSetting)){

                    $affectedRows = Setting::where('setting_title', '=', $key)->update(['setting_value' => $data]);
                    Session::put('success', 'Settings updated successfully.');   

                }else{

                    $setting = new Setting;
                    $setting->setting_title = $key;
                    $setting->setting_value = $data;
                    $setting->user_id = Auth::User()->id; 
                    $setting->save();

                    Session::put('success', 'Settings saved successfully.');   
                }

            }
            return redirect()->back();
        }
        
        return view('dashboard.settings');
    }


    public function chatroom()
    {
        $groups = Group::where([
                'owner_id' => Auth::User()->id,
                'status' => 'Active'
            ])->get();
        // echo '<pre>';print_r($groups);die;
        return view('dashboard.chatroom')
            ->with('groups', $groups);

    }

    public function friendRequests()
    {
        $friend = Friend::with('user')
                ->with('friends')
                ->where('user_id', '=', Auth::User()->id)
                ->orWhere('friend_id', '=', Auth::User()->id)
                ->get()
                ->toArray();
        // echo '<pre>';print_r($friend);die;
        return view('dashboard.requests')
                ->with('friends', $friend);

    }



    /**
    *   Group chatrooms ajax call handling.
    *   Ajaxcontroller@groupchatrooms
    */
    public function group()
    {
        // die('asdfsadfa');
        return view('dashboard.groupchatrooms');
    }


    /**
    *   Group sub chatrooms ajax call handling.
    *   Ajaxcontroller@groupchatrooms
    */
    public function subgroup( $parentgroup, $parentid )
    {

         if($parentgroup){
            $data = DB::table('categories')->where(['parent_id' => $parentid])->where(['status' => 'Active'])->get();

            if( !empty( $data ) ){
                $subgroups = $data;
            }
        }
        
        return view('dashboard.subgroupchats')
                ->with('subgroups', $subgroups)
                ->with('parentgroup', $parentgroup);
    }


    /**
    *   Enter chatrooms ajax call handling.
    *   Ajaxcontroller@enterchatroom
    */
    public function groupchat( $parentgroup )
    {
 


        $groupname = $parentgroup;
        if($groupname){
            $grpname = $groupname;
        }else{
            $parentgroup = Request::get('parentgroup');
            $subgroup = Request::get('subgroup');
            if($subgroup)
                $grpname = $parentgroup.'-'.$subgroup;
            else
                $grpname = $parentgroup;
        }

        print_r($grpname);die;
 
        $model = new DefaultGroup;

        $updatecheck = $model->where('group_name', $grpname)
                    ->where('group_by', Auth::User()->id)
                    ->get()->toArray();


        $defGroup = array();
        $defGroup['group_name'] = $grpname;
        $defGroup['group_by'] = Auth::User()->id;

        if(empty($updatecheck)){
            $model = new DefaultGroup;
            $response = $model->create($defGroup);
        }else{
            $id = $updatecheck[0]['id'];
            $response = $model->find($id);
        }
        
        //Get users of this group
        $usersData = $model->with('user')->where('group_name', $grpname)->get()->toArray();
        

        return view('dashboard.enterchatroom')
                    ->with('grpname', $grpname)
                    ->with('groupname', $response)
                    ->with('userdata', $usersData);
    }


}
