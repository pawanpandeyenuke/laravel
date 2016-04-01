<?php

namespace App\Http\Controllers;


use Auth, App\Feed, DB, App\Setting, App\Group, App\Friend, App\DefaultGroup, App\User, App\Country, App\State, App\EducationDetails,App\JobArea,App\JobCategory;
use App\Library\Converse;
use Request, Session, Validator, Input, Cookie;

use App\Http\Requests;
use App\Http\Controllers\Controller;
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
                // print_r($response);die;
            }
  
            DB::table('default_groups')->where('group_by',Auth::User()->id)->delete();


            // echo '<pre>';print_r($friends);die;

            $per_page = 5;

            $feeds = Feed::with('likesCount')->with('commentsCount')->with('user')->with('likes')->with('comments')
                ->whereIn('user_by', Friend::where('user_id', '=', Auth::User()->id)
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
        $model1=User::where('id','!=',Auth::User()->id)->take(10)->get()->toArray();

        return view('dashboard.requests')
                ->with('model1', $model1);

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
    public function groupchat( $input = '' ){   

    
                $flag2=0;
                        $model = new DefaultGroup;
                      
                        if(empty($input))        

                        $input = Request::all();
                 
                    if($input==null){
                        $flag2=1;
                         
                    }
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

                    if($flag==0 || $flag1==0)
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
elseif(isset($input['country1'])||isset($input['country'])||isset($input['state'])||isset($input['city'])){
    return redirect('group');
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

        $id=Auth::User()->id;
        $friendid=DB::table('friends')->where('user_id',$id)->where('status','Accepted')->pluck('friend_id');
        $pendingfriend=DB::table('friends')->where('user_id',$id)->where('status','Pending')->pluck('friend_id');
        return view('chatroom.groupchat')
                    ->with('groupname', $groupname)
                    ->with('userdata', $usersData)
                    ->with('friendid',$friendid)
                    ->with('authid',$id)
                    ->with('pendingfriend',$pendingfriend)
                    ->with('exception',$input)
                    ->with('flag',$flag2)
                    ;
    }



 public function profile( $id )
    {
        
        $arguments = Request::all();
        $user = new User();

        if(Request::isMethod('post')){

            $getCommonEduArgs = array_intersect_key( $arguments, [
                                    'user_id' => 'null',
                                    'education_level' => 'null',
                                    'specialization' => 'null',
                                    'graduation_year_from' => 'null',
                                    'graduation_year_to' => 'null',
                                    'currently_studying' => 'null',
                                    'education_establishment' => 'null',
                                    'country_of_establishment' => 'null',
                                    'city_of_establishment' => 'null'
                                ]);
            
            if( !empty($getCommonEduArgs) ){

                $getCommonEduArgs['user_id'] = $id;
                $delete = EducationDetails::where('user_id', '=', Auth::User()->id)->delete();
                // $eduExists = EducationDetails::where('user_id', '=', $id)->get()->toArray();

                $time=strtotime($getCommonEduArgs['graduation_year_from']);
                $getCommonEduArgs['graduation_year_from']=date('Y-m-d',$time);

                $time=strtotime($getCommonEduArgs['graduation_year_to']);
                $getCommonEduArgs['graduation_year_to']=date('Y-m-d',$time);
 
                $education = new EducationDetails;
                $education->create($getCommonEduArgs);
 
                foreach ($getCommonEduArgs as $key => $value)
                    unset($arguments[$key]);
            }
            
            // echo '<pre>';print_r($arguments);die;

            $time=strtotime($arguments['birthday']);
            $arguments['birthday']=date('Y-m-d',$time);

            $arguments['state'] = DB::table('state')->where('state_id', $arguments['state'])->value('state_name');
            $arguments['city'] = DB::table('city')->where('city_id', $arguments['city'])->value('city_name');

            if($arguments){

                $temp = explode(' ', $arguments['username']);
                if(is_array($temp)){
                    $arguments['first_name'] = $temp[0];
                    $arguments['last_name'] = $temp[1];
                }else{
                    $arguments['first_name'] = $temp;
                }
                
                unset($arguments['username']);
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
        $education = EducationDetails::where('user_id', $id)->get()->first();
        // echo '<pre>';print_r($education);die;
        
        return view('profile.profile')
                ->with('user', $user)
                ->with('education', $education);

    }

     public function sendImage(){
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



}
