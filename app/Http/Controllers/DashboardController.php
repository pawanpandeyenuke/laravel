<?php

namespace App\Http\Controllers;


use Auth, App\Feed, DB, App\Setting, App\Group, App\Friend, App\DefaultGroup, App\User, App\Country, App\State, App\EducationDetails,App\JobArea,App\JobCategory,App\Broadcast,App\BroadcastMessages,App\GroupMembers,App\BroadcastMembers,App\Forums;

use App\Library\Converse, Google_Client, Mail;

use Request, Session, Validator, Input, Cookie;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\MessageBag, Config;
// use Illuminate\Support\Facades\Input;

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
            
            $xmppusername = User::where('id',Auth::User()->id)->value('xmpp_username');

            $defGroup = DefaultGroup::where('group_by',Auth::User()->id)->lists('group_name');

            foreach ($defGroup as $value) {
                $converse = new Converse;
                $response = $converse->removeUserGroup($value, $xmppusername);    
            }
  
            DB::table('default_groups')->where('group_by',Auth::User()->id)->delete();


            // echo '<pre>';print_r($friends);die;

            $per_page = 5;

            $feeds = Feed::with('likesCount')->with('commentsCount')->with('user')->with('likes')->with('comments')
                ->whereIn('user_by', Friend::where('user_id', '=', Auth::User()->id)
                        // ->where('friend_id', '=', Auth::User()->id)
                        ->where('status', '=', 'Accepted')
                        ->pluck('friend_id')
                        ->toArray())
                ->orWhere('user_by', '=', Auth::User()->id)
                ->orderBy('news_feed.id','DESC')
                ->take($per_page)
                ->get();

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
        
        $settinguser = Setting::where('user_id', '=', Auth::User()->id)->pluck('setting_value', 'setting_title')->toArray();
        // echo '<pre>';print_r($settinguser);die;
        return view('dashboard.settings')
                ->with('setting', $settinguser);
    }


    public function chatroom()
    {
        $groups = Group::where([
                'owner_id' => Auth::User()->id,
                'status' => 'Active'
            ])->get();
        


        return view('chatroom.chatroom')
            ->with('groups', $groups) ;

    }

    public function friendRequests()
    {
        // $model1=User::where('id','!=',Auth::User()->id)->take(10)->orderBy('id','desc')->get()->toArray();
        $model1 = Friend::
                    with('user')
                    ->where('friend_id', '=', Auth::User()->id)
                    ->where('status', '=', 'Pending')
                    ->take(10)
                    ->get()
                    ->toArray();

        $recievedcount = Friend::where('friend_id', '=', Auth::User()->id)
                    ->where('status', '=', 'Pending')
                    ->get()
                    ->count();
        $sentcount = Friend::where('user_id', '=', Auth::User()->id)
                    ->where('status', '=', 'Pending')
                    ->get()
                    ->count();
        $friendscount = Friend::where('user_id', '=', Auth::User()->id)
                    ->where('status', '=', 'Accepted')
                    ->get()
                    ->count();

        return view('dashboard.requests')
                ->with('model1', $model1)
                ->with('recievedcount', $recievedcount)
                ->with('sentcount', $sentcount)
                ->with('friendscount', $friendscount);

    }



    /**
    *   Group chatrooms ajax call handling.
    *   Ajaxcontroller@groupchatrooms
    */
    public function group()
    {

        $xmppusername = User::where('id',Auth::User()->id)->value('xmpp_username');

        $defGroup = DefaultGroup::where('group_by',Auth::User()->id)->lists('group_name');

        foreach ($defGroup as $value) {
            $converse = new Converse;
            $response = $converse->removeUserGroup($value, $xmppusername);    
            // print_r($response);die;
        }

        DB::table('default_groups')->where('group_by',Auth::User()->id)->delete();

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
           $varexp =explode('-', $name);
           $name =implode(' ', $varexp);
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
    public function groupchat( $input = '' ,$gname=''){   
        $groupid=null;
        if($input)
        {
    if($input!=null && $gname!=null)
    {
        $groupid=$input;
        $checkname=DB::table('groups')->where('id',$input)->value('title');
        $checkname=strtolower($checkname);
        $checkname=str_replace(" ","-",$checkname);
        if($checkname!=$gname)
        {
            return redirect('private-group-list');           
        }
        else if($checkname==$gname)
        {
           $privategroup=Group::with('members')->where('id',$input)->get()->toArray();

                $count=0;
                foreach ($privategroup[0]['members'] as $mem){
                    if($mem['member_id']==Auth::User()->id)
                         $count++;
                }
          if(($count==0) && $privategroup[0]['owner_id']!=Auth::User()->id){
            return redirect('private-group-list');
          }
            
        }
        
    }
    else{
$groupid=$input;
$groupname = implode('-', array_map('ucfirst', explode('-', $groupid)));
$groupname = implode(',', array_map('ucfirst', explode(',', $groupname)));
$groupname =preg_replace('/(?<! )(?<!^)[A-Z]/',' $0', $groupname);
$groupname=str_replace(', ',',',$groupname);
$groupname=str_replace('-','',$groupname);
$groupname=str_replace('It','IT',$groupname);
//print_r($groupname);die;
$result=DB::table('categories')->where('title',$groupname)->value('id');

if($result==null)
{
return redirect('group');
}
}
}
                        $model = new DefaultGroup;
                      
                        if(empty($input))        

                        $input = Request::all();
                 
                 
               
                if(isset($input['subcategory']))
                {

                    $res=DB::table('categories')->where('parent_id','!=',0)->pluck('title');
                    $res1=DB::table('categories')->where('parent_id','=',0)->pluck('title');
 
                    $par=array_unique($res1);
                    $res1=array_map('strtolower',$par);
                    $par=$res1;

                    

                    $sub=array_unique($res);
                    $res=array_map('strtolower',$sub);
                    $sub=$res;
                 
                    $flag=0;
                    $flag1=0; 
          // print_r($sub);die;
                    foreach ($sub as $key) {
                    $key = str_replace(" ","", $key);
                    if($input['subcategory']==$key)
                    {
                    $flag=1;
                    }
                    }

                    foreach ($par as $key) {
                      $key = str_replace("-", " ", $key);
                    if($input['parentname']==$key)
                    {
                    $flag1=1;
                    }
                    }

                    if(($flag==0 || $flag1==0) && $result==null)
                    {
                return redirect('group');
            }
else {
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
                    'country'=>DB::table('country')->where('country_name',$input['country'])->value('country_name'),
                    'state'=>DB::table('state')->where('state_id',$input['state'])->value('state_name'),
                    'city'=>DB::table('city')->where('city_id',$input['city'])->value('city_name')]);       
               
                }elseif($input['subcategory']=='country'){
                
                    $newinput=(['parentname'=>$input['parentname'],'subcategory'=>'c','country'=>DB::table('country')->where('country_id',$input['country1'])->value('country_name')]);

                }else{
                   
                    $newinput=(['parentname'=>$input['parentname'],'subcategory'=>$input['subcategory']]);       
                
                }

                $input=$newinput;
            // echo '<pre>';print_r($input);die;
 }      
}
elseif(isset($input['country'])||isset($input['country'])||isset($input['state'])||isset($input['city'])){
    return redirect('group');
}

if($input!=null && $gname!=null)
{
    $input=$gname;
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

            $groupname = implode('_', $groupnamedata); 
            $groupname=strtolower($groupname);
        }else{
            $groupname = $input;
        }

            
        if(Request::isMethod('get')){

            if(is_array($groupname)){
                
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
                    // print_r($defGroup);die;
                    if($defGroup['group_name'] != ''){
                        if(empty($updatecheck)){
                            $model = new DefaultGroup;
                            $response = $model->create($defGroup);
                        }else{
                            $id = $updatecheck[0]['id'];
                            $response = $model->find($id);
                        }
                    }
                    //Get users of this group
                    $usersData = $model->with('user')->where('group_name', $groupname)->get()->toArray();  
            }


        }
        $counter=0;
        if($groupid!=null)
        {
        	    $members=DB::table('members')->where('group_id',$groupid)->pluck('member_id');
        	    foreach ($members as $key => $value) {
        	    	if($value==Auth::User()->id)
        	    	{
        	    		$counter++;
        	    	}
        	    }
       
      	if($counter==0 && $input==null)

        {
        	return redirect('private-group-list');
        }
	 }

       

        $id=Auth::User()->id;
        $friendid=DB::table('friends')->where('user_id',$id)->where('status','Accepted')->pluck('friend_id');
        $pendingfriend=DB::table('friends')->where('user_id',$id)->where('status','Pending')->pluck('friend_id');
        $privategroup=Group::with('members')->orderBy('id','DESC')->get()->toArray();
        return view('chatroom.groupchat')
                    ->with('groupname', $groupname)
                    ->with('userdata', $usersData)
                    ->with('friendid',$friendid)
                    ->with('authid',$id)
                    ->with('pendingfriend',$pendingfriend)
                    ->with('exception',$input)
                    ->with('pgid',$groupid)
                    ->with('privategroup',$privategroup)
                    ;
   }

 

    public function profile( $id )
    {

        $user = User::where('id', $id)->get()->first();
        $education = EducationDetails::where('user_id', $id)->get();
                    
        // echo '<pre>';print_r($education);die;

        return view('profile.profile')
                ->with('user', $user)
                ->with('education', $education);  

    }


	public function editUserProfile( $id )
	{	        
        $arguments = Request::all();
        $user = new User();
        
        if(Request::isMethod('post')){
            
            $getCommonEduArgs = array_intersect_key( $arguments, [
                                    'user_id' => 'null',
                                    'education_level' => 'null',
                                    'specialization' => 'null',
                                    'graduation_year' => 'null',
                                    'education_establishment' => 'null',
                                    'country_of_establishment' => 'null',
                                    'state_of_establishment' => 'null',
                                    'city_of_establishment' => 'null'
                                ]);

            if( !empty($getCommonEduArgs) ){
            	$delete = EducationDetails::where('user_id', '=', Auth::User()->id)->delete();
            	foreach ($getCommonEduArgs['education_level'] as $key => $value) {
 
            		$education = new EducationDetails;

            		$education->user_id = Auth::User()->id;
            		$education->education_level = $value;
            		$education->specialization = $getCommonEduArgs['specialization'][$key];
            		$education->graduation_year = $getCommonEduArgs['graduation_year'][$key];
            		$education->education_establishment = $getCommonEduArgs['education_establishment'][$key];
            		$education->country_of_establishment = $getCommonEduArgs['country_of_establishment'][$key];
            		$education->state_of_establishment = $getCommonEduArgs['state_of_establishment'][$key];
            		$education->city_of_establishment = $getCommonEduArgs['city_of_establishment'][$key];

            		$education->save(); 
            	}

            }

            $unsetarray = [ 'user_id' => 'null',
                            'education_level' => 'null',
                            'specialization' => 'null',
                            'graduation_year' => 'null',
                            'education_establishment' => 'null',
                            'country_of_establishment' => 'null',
                            'state_of_establishment' => 'null',
                            'city_of_establishment' => 'null'
                        ];
 
            foreach ($unsetarray as $key => $value) {
            	unset($arguments[$key]);
            } 

            $time=strtotime($arguments['birthday']);
            $arguments['birthday']=date('Y-m-d',$time);
 
            if($arguments){

                unset($arguments['_token']);

                //Check for image upload.
                $file = Request::file('picture');
                if( isset($arguments['picture']) && $file != null ){
                    $image_name = time()."_POST_".strtoupper($file->getClientOriginalName());
                    $arguments['picture'] = '/uploads/user_img/'.$image_name;
                    $file->move(public_path('uploads/user_img'), $image_name);
                }
                // echo '<pre>';print_r($arguments);die;
                // $arguments['picture'] = 'uploads/user_img/'.$arguments['picture'];
                foreach ($arguments as $key => $value) {
                    if( $key != 'email' && $key != 'password' ){
                        User::where([ 'id' => $id ])
                            ->update([ $key => $value ]);
                    }
                }
                Session::put('success', 'Profile saved successfully');
            }

            return redirect("/profile/$id");
        }

        $user = User::where('id', $id)->get()->first();
        $education = EducationDetails::where('user_id', $id)->get();

        return view('profile.editProfile')
                ->with('user', $user)
                ->with('education', $education);  

	}


     public function sendImage()
     {
         $status=0;
         $message="";
         //$url=url();
        // echo '<pre>'; print_r($_FILES);die;

          $image = $_FILES["chatsendimage"]["name"];
          //$path = $rootFolder=dirname(Yii::$app->basePath).'/frontend/web/images/media/chat_images/';
          
          $path=public_path().''.'/images/media/chat_images';

         $uploadedfile = $_FILES['chatsendimage']['tmp_name'];
          $name = $_FILES['chatsendimage']['name'];
          $size = $_FILES['chatsendimage']['size'];
          $valid_formats = array("jpg", "JPG", "jpeg", "JPEG", "png", "PNG", "gif", "GIF");
          if (strlen($name)) {
           list($txt, $ext) = explode(".", $name);
           if (in_array($ext, $valid_formats)) {
            $actual_image_name = "chatimg_" . time() . substr(str_replace(" ", "_", $txt), 5) . "." . $ext;
            $tmp = $uploadedfile;
            if (move_uploaded_file($tmp, $path . $actual_image_name)) {           
                //$rootFolder=base_path();
                // $image = Yii::$app->image->load($path.$actual_image_name);
               // $image->resize(140, 100);
               // $image->save();
      
            //   ========== $data = Yii::$app->request->baseUrl.'/images/media/chat_images/'. $actual_image_name;
               
                $data=public_path().''.'/images/media/chat_images'.$actual_image_name;
               
                $chatType=isset($_POST["chatType"])?$_POST["chatType"]:'';
                if ($chatType == "group"){}//chat type check
                else{           
                 $message=$_SERVER['HTTP_HOST'].$data;
        $status=1;
                }                              
            } else
             $message= "Failed to send try again.";    
           } else
            $message= "Invalid file format.";
          }else {
           $message="Please select an image to send.";
           }
        echo json_encode(array('status'=>$status,'message'=>$message,'type'=>'image'));
           die(); 
       }



    public function broadcastList()
    {

        $broadcast=Broadcast::with('members')->where('user_id',Auth::User()->id)->orderBy('id','DESC')->get()->toArray();
        return view('broadcast.list')->with('broadcast',$broadcast);
    }

    public function broadcastAdd()
    {

/*        $array = [
                    "user_id"=> 88,
                    "members"=> [12,32,35,46,57,989,809],
                    "broadcast_message"=> "Hey! this is a sample broadcast message."
                ];

        echo '<pre>';print_r(json_encode($array));die;*/

        if(Request::isMethod('post'))
        {

            $userid=Auth::User()->id;
            $input=Request::all();
           
        
        if(isset($input['broadcastuser'])&&$input['broadcastname']!=null)
            {

                $members=implode(",",$input['broadcastuser']);
                
                $data = array(
                        'title'=>$input['broadcastname'],
                        'user_id'=>$userid
                            );  


                $br=Broadcast::create($data);
                
                foreach ($input['broadcastuser'] as $key => $value) {
                   
                $data1 = array(
                        'broadcast_id'=>$br['id'],
                        'member_id'=>$value
                            );  
                    BroadcastMembers::create($data1);
                }
              return redirect(url('broadcast-list'));  
                
            }
            else
            {
                return redirect()->back();
            }


        }


    $friends=Friend::with('user')
                    ->with('user')
                    ->where('friend_id', '=', Auth::User()->id)
                    ->where('status', '=', 'Accepted')
                    ->get()
                    ->toArray();

                return view('broadcast.add')->with('friends',$friends);   
    }

    public function broadcastMessage($broadcastid='')
    {   
        if($broadcastid)
        {   
            $broadcastdetail=Broadcast::with('members')->where('id',$broadcastid)->get()->toArray();
           
            $broadcastmessages=BroadcastMessages::where('broadcast_id',$broadcastid)->where('broadcast_by',Auth::User()->id)->get();
           
                $namestr='';
                $name=array();
                foreach ($broadcastdetail[0]['members'] as $mem) {
                 $name[]=DB::table('users')->where('id',$mem['member_id'])->value('first_name');
                }
                  $namestr=implode(",",$name);
                return view('broadcast.message')
                        ->with('name',$namestr)
                        ->with('title',$broadcastdetail[0]['title'])
                        ->with('id',$broadcastid)
                        ->with('messages',$broadcastmessages);
        }
    }

    
    
    public function privateGroupList($privategroupid='')
    {

        if($privategroupid)
        {
			$groupname = DB::table('groups')->where('id',$privategroupid)->value('title');
			$groupname=$groupname."_".$privategroupid;

			$converse=new Converse;
			$xmp=DB::table('users')->where('id',Auth::User()->id)->value('xmpp_username');            

			$converse->removeUserGroup($groupname,$xmp);

            GroupMembers::where('group_id',$privategroupid)->where('member_id',Auth::User()->id)->delete();
        }
        $privategroup=Group::with('members')->orderBy('id','DESC')->get()->toArray();

        return view('privategroup.list')->with('privategroup',$privategroup);
    }

    public function privateGroupAdd()
    {

          if(Request::isMethod('post'))
        {

            $userid=Auth::User()->id;
            $input=Request::all();
       
 
        if(isset($input['groupmembers'])&&$input['groupname']!=null)
            {
                array_push($input['groupmembers'],$userid);
                
                $members=implode(",",$input['groupmembers']);
    
                $data = array(
                        'title'=>$input['groupname'],
                        'status'=>'Active',
                        'owner_id'=>$userid,
                            );  



                $groupid=str_replace(' ','_',$input['groupname']);

                $groupid=strtolower($groupid);

                    $converse=new Converse;

                
                $groupdata = Group::create($data);
				
                 $groupname=$input['groupname']."_".$groupdata->id;
          
                    $converse->createGroup($groupid,$groupname);

                foreach ($input['groupmembers'] as $data) {
                    
                
                 $data1 = array(
                        'group_id'=>$groupdata->id,
                        'member_id'=>$data,
                        'status'=>'Joined',
                            );
               
                        GroupMembers::insert($data1);  
                }

       
        $xmp=DB::table('users')->whereIn('id',$input['groupmembers'])->pluck('xmpp_username');
       
        foreach ($xmp as $key => $value) {
            
            $converse->addUserGroup($groupname,$value);

        }

                return redirect(url('private-group-list'));       
            }
            else
            {
                return redirect()->back();
            }
    }

     $friends=Friend::with('user')
                    ->with('user')
                    ->where('friend_id', '=', Auth::User()->id)
                    ->where('status', '=', 'Accepted')
                    ->get()
                    ->toArray();

                return view('privategroup.add')->with('friends',$friends);   

  }

  public function privateGroupDetail($privategroupid='')
  {
    if($privategroupid)
    {
        //$title=DB::table('groups')->where('id',$privategroupid)->value('title');
        $groupdetail = Group::where('id',$privategroupid)->get()->toArray();
        $ownerid=DB::table('groups')->where('id',$privategroupid)->value('owner_id');
        $members=DB::table('members')->where('group_id',$privategroupid)->pluck('member_id');
        $name=User::whereIn('id',$members)->orWhere('id',$ownerid)->get()->toArray();

        $friends=Friend::with('user')
                    ->with('user')
                    ->where('friend_id', '=', Auth::User()->id)
                    ->where('status', '=', 'Accepted')
                    ->get()
                    ->toArray();

        return view('privategroup.detail')
               ->with('groupdetail',$groupdetail)
               ->with('name',$name)
               ->with('groupid',$privategroupid)
               ->with('ownerid',$ownerid)
               ->with('friends',$friends);   
    }

  }


    public function demopage()
    {
        return view('dashboard.demopage');
    }

    
    public function forumsList()
    {
        $mainforums = Forums::where('parent_id',0)->get();
     
        return view('forums.mainforums')
            ->with('forums',$mainforums);
    }

    public function subForums($parentid='')
    {
   


        if($parentid)
        {
        /* Redirecting for wrong parent id in URL */
        $r1 = DB::table('forums')->where('id',$parentid)->where('parent_id','!=',0)->value('title');
        $r2=DB::table('forums')->where('id',$parentid)->value('id');
        if($r1!=null || $r2==null || $r2==3 || $r2==7 || $r2==9 || $r2==14 || $r2==19 || $r2==21)
        {
            return redirect('forums');
        }
        /******************END************************/

           $mainforum=Forums::where('id',$parentid)->value('title');
           $subforums = Forums::where('parent_id',$parentid)->get()->toArray();
        }
        else
        {
         return redirect('forums');   
        }

        return view('forums.subforums')
                ->with('mainforum',$mainforum)
                ->with('subforums',$subforums);

    }

    public function forumPost($name='')
    {
      $input=Request::all();

      if($name)
      {
        if($name == "superstitious-" || $name == "pet-products" || $name == "it,coding-language"
            || $name == "ayurvedic,herbal" || $name == "face,hair,body-care" || $name == "eye-lasik"
            || $name == "cosmetics")
        {
       
            //$str = implode('-', array_map('ucfirst', explode('-', $name)));
            $str=str_replace("-"," ",$name);
            $str=ucwords($str);
            $str = implode(',', array_map('ucfirst', explode(',', $str)));
            $str=str_replace("It","IT","$str");
           
            $subforum=(['parentname'=>$str,
                'subcategory'=>'']);
            $category_id = DB::table('forums')->where('title',$subforum['parentname'])->value('id');
            return view('forums.postforums')
                ->with('forumpost',$subforum)
                ->with('category_id',$category_id);

        }
        else{
            return redirect('forums');
        }
      }
    if(Request::isMethod('post') && $input['buttontype']!="Continue")
    {
                $forumpost = Request::all();

              //print_r($forumpost);die; 
           $sub = explode('_', $forumpost['subcategory']);
           //print_r($sub[1]);die;
         $subforum=(['parentname'=>$forumpost['parentname'],
                'subcategory'=>$sub[0]]);      
                

        if($sub[0]== "Country,State,City")
        {
            if($input['city']!=null)
                    $city=$input['country'].", ".$input['state'].", ".$input['city'];
                else
                    $city=$input['country'].", ".$input['state'];
            $subforum=(['parentname'=>$forumpost['parentname'],
                'subcategory'=>$city]);      
        }
        if($sub[0]== "Country")
        {
            $country = DB::table('country')->where('country_id',$forumpost['country1'])->value('country_name');
            $subforum=(['parentname'=>$forumpost['parentname'],
                'subcategory'=>$country]);      
        }
        if($sub[0]== "International")
        {
            $subforum=(['parentname'=>$forumpost['parentname'],
                'subcategory'=>"International"]);      
        }
        if($sub[0]== "Professional Course")
        {
            $course = explode('_',$forumpost['coursedata1']);
            $subforum=(['parentname'=>$sub[0],
                'subcategory'=>$course[0]]);
                 $sub[1] = $course[1];        
        }
        if($sub[0]== "Subjects")
        {
            $course = explode('_',$forumpost['coursedata']);
            $subforum=(['parentname'=>$sub[0],
                'subcategory'=>$course[0]]);
                $sub[1] = $course[1];      
        }

                return view('forums.postforums')
                ->with('forumpost',$subforum)
                ->with('category_id',$sub[1]);
    }

       /* For Doctor's sub-sub category */
    if($input['parentname'] == "Study Questions" || $input['parentname']=="Doctor")
      {
        if($input['parentname']=="Doctor")
        {
            
            $sub = explode('_',$input['subcategory']);
            //print_r($sub);die;
             $subname="";
            if($sub[0]=="Country,State,City")
            {
                $subforums = Forums::where('parent_id',$sub[1])->get();
                if($input['city']!=null)
                    $subname=$input['country'].", ".$input['state'].", ".$input['city'];
                else
                    $subname=$input['country'].", ".$input['state'];
            }
            
            if($sub[0]=="Country"){
            $subname=DB::table('country')->where('country_id',$input['country1'])->value('country_name');
             $subforums = Forums::where('parent_id',$sub[1])->get();
            }
            
            if($sub[0]=="International"){
            $subname="International";
          $subforums = Forums::where('parent_id',$sub[1])->get();
            }

           $mainforum="Doctor ".$subname;

           return view('forums.subforums')
                ->with('mainforum',$mainforum)
                ->with('subforums',$subforums)
                ;
        }

        if($input['parentname'] == "Study Questions")
        {
             $sub = explode('_',$input['subcategory']);
             $subforums = Forums::where('parent_id',$sub[1])->get();
             $mainforum = $sub[0];
             return view('forums.subforums')
                ->with('mainforum',$mainforum)
                ->with('subforums',$subforums)
                ;

        }
    }

    } 
    

    public function addNewForumPost()
    {
      if(Request::isMethod('post')){
        $input = Request::all();
       // print_r($input);die;
        DB::table('forums_post')
            ->insert(['title'=>$input['topic'],
                    'owner_id'=>Auth::User()->id,
                    'category_id'=>$input['category_id']]);
            return redirect('forums');
    }
    }

   

}
