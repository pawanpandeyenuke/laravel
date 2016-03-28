<?php

namespace App\Http\Controllers;

use Auth, App\Feed, DB, App\Setting, App\Group, App\Friend, App\DefaultGroup, App\User, App\Country, App\State, App\EducationDetails;
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
            ->where('user_by', '=', Auth::User()->id)
            ->orderBy('news_feed.id','DESC')
            // ->take($per_page)
            ->get();

           // echo '<pre>';print_r($feeds[0]->comments);die;
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
        return view('chatroom.chatroom')
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
        return view('chatroom.groups');
    }


    /**
    *   Group sub chatrooms ajax call handling.
    *   Ajaxcontroller@groupchatrooms
    */
    public function subgroup( $parentid = '', $name = '' )
    {

        // print_r();die;
        $subgroups = '';
         if($parentid){
            $data = DB::table('categories')->where(['parent_id' => $parentid])->where(['status' => 'Active'])->get();

            if( !empty( $data ) ){
                $subgroups = $data;
            }
        }
        
        if($name){
           $varexp =  explode('-', $name);
           $name =  implode(' ', $varexp);
        }

        // print_r($name);die;

        return view('chatroom.subgroups') 
                ->with('subgroups', $subgroups)
                ->with('group_name', $name);

    }


    /**
    *   Enter chatrooms ajax call handling.
    *   Ajaxcontroller@enterchatroom
    */
    public function groupchat( $input = '' ){   

        $model = new DefaultGroup;

        if(empty($input))        
        $input = Request::all();

        if(is_array($input)) {
            
            $validator = Validator::make($input, ['subcategory' => 'required']); 
                
            if($validator->fails())
            {
                $error = $validator->messages()->first();
                Session::put('error', $error);
                return redirect()->back();
            }
            else{

                if($input['subcategory']=='international'){
                
                    unset($input['country']); 
                    $newinput=(['parentname'=>$input['parentname'],'subcategory'=>$input['subcategory']]);
                
                }elseif($input['subcategory']=='professionalcourse'){
                
                    $newinput=(['parentname'=>$input['parentname'],'subcategory'=>$input['subcategory'],'coursedata'=>$input['coursedata1']]);

                }elseif($input['subcategory']=='subjects'){

                    $newinput=(['parentname'=>$input['parentname'],'subcategory'=>$input['subcategory'],'coursedata'=>$input['coursedata']]);

                }elseif($input['subcategory']=='country,state,city'){
               
                    $newinput=(['parentname'=>$input['parentname'],
                    'subcategory'=>'csc',
                    'country'=>DB::table('country')->where('country_id',$input['country'])->value('country_name'),
                    'state'=>DB::table('state')->where('state_id',$input['state'])->value('state_name'),
                    'city'=>DB::table('city')->where('city_id',$input['city'])->value('city_name')]);       
               
                }elseif($input['subcategory']=='country'){
                
                    $newinput=(['parentname'=>$input['parentname'],'subcategory'=>'c','country'=>DB::table('country')->where('country_id',$input['country1'])->value('country_name')]);

                }else{
                
                    $newinput=(['parentname'=>$input['parentname'],'subcategory'=>$input['subcategory']]);       
                
                }

                $input=$newinput;
            }   
       }

        if(is_array($input)){
            $groupnamedata = array();
            foreach ($input as $key => $value){
                $rawdata = explode(' ', $value);
                if(is_array($rawdata)){
                    $data = implode('', $rawdata);
                    $groupnamedata[] = $data;
                }else{
                    $groupnamedata[] = $value;
                }
            }

            $groupname = implode('-', $groupnamedata); 
        }else{
            $groupname = $input;
        }

        
        
        if(Request::isMethod('get')){

            if(is_array($input)){
                
                    $updatecheck = $model->where('group_name', $groupname)
                                ->where('group_by', Auth::User()->id)
                                ->get()->toArray();

                    $defGroup = array();
                    $defGroup['group_name'] = $groupname;
                    $defGroup['group_by'] = Auth::User()->id;

                    if(empty($updatecheck)){
                        $model = new DefaultGroup;
                        $response = $model->create($defGroup);
                    }else{
                        $id = $updatecheck[0]['id'];
                        $response = $model->find($id);
                    }

                    //Get users of this group
                    $usersData = $model->with('user')->where('group_name', $groupname)->get()->toArray();          
                
            }
            else{
                    $updatecheck = $model->where('group_name', $groupname)
                                ->where('group_by', Auth::User()->id)
                                ->get()->toArray();

                    $defGroup = array();
                    $defGroup['group_name'] = $groupname;
                    $defGroup['group_by'] = Auth::User()->id;

                    if(empty($updatecheck)){
                        $model = new DefaultGroup;
                        $response = $model->create($defGroup);
                    }else{
                        $id = $updatecheck[0]['id'];
                        $response = $model->find($id);
                    }

                    //Get users of this group
                    $usersData = $model->with('user')->where('group_name', $groupname)->get()->toArray();  
            }

        }

        return view('chatroom.groupchat')
                    ->with('groupname', $groupname)
                    ->with('userdata', $usersData);
    }



    public function profile( $id )
    {
        
        $arguments = Request::all();
        $user = new User();
// print_r($id);die;
        if(Request::isMethod('post')){

            $getCommonEduArgs = array_intersect_key( $arguments, [
                                    'id' => 'null',
                                    'education_level' => 'null',
                                    'specialization' => 'null',
                                    'graduation_year_from' => 'null',
                                    'graduation_year_to' => 'null',
                                    'currently_studying' => 'null',
                                    'education_establishment' => 'null',
                                    'country_of_establishment' => 'null',
                                    'city_of_establishment' => 'null',
                                    'job_area' => 'null',
                                    'job_category' => 'null'
                                ]);
            
            if( !empty($getCommonEduArgs) ){
                $getCommonEduArgs['id'] = $id;
                $eduExists = EducationDetails::where('id', '=', $id)->get()->toArray();
                // print_r($eduExists);die;

                if(!empty($eduExists)){

                    $eduExists = EducationDetails::find($id);

                    $eduExists->fill($getCommonEduArgs);
                    $saved = $eduExists->push();
                }else{
                    $education = new EducationDetails;
                    $education->create($getCommonEduArgs);
                }
                foreach ($getCommonEduArgs as $key => $value)
                    unset($arguments[$key]);
            }
// echo '<pre>';print_r($arguments);die;
            if($arguments){
                unset($arguments['_token']);
                foreach ($arguments as $key => $value) {
                    if( $key != 'email' && $key != 'password' ){
                        User::where([ 'id' => $id ])
                            ->update([ $key => $value ]);
                    }
                }
                Session::put('success', 'Profile saved successfully');
            }

            return redirect()->back();
        }


        $user = User::where('id', $id)->get()->first();
        $education = EducationDetails::where('id', $id)->get()->first();
        // echo '<pre>';print_r($education);die;

        return view('profile.profile')
                ->with('user', $user)
                ->with('education', $education);

    }

 

}
