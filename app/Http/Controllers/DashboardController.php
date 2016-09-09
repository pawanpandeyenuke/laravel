<?php

namespace App\Http\Controllers;


use Auth, App\Feed, DB, App\Setting, App\Category,App\Group, App\Friend, App\DefaultGroup, App\User, App\Country, App\State, App\EducationDetails,App\JobArea,App\JobCategory,App\Broadcast,App\BroadcastMessages,App\GroupMembers,App\BroadcastMembers,App\Forums;

use App\Library\Converse, Google_Client, Mail;

use Request, Session, Validator, Input, Cookie, Hash;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\MessageBag, Config;
use Intervention\Image\Facades\Image;
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

        try
        { 
            $xmppusername = User::where('id',Auth::User()->id)->value('xmpp_username');

            $defGroup = DefaultGroup::where('group_by',Auth::User()->id)->lists('group_name');

            foreach ($defGroup as $value) {
                $converse = new Converse;
                $response = $converse->removeUserGroup($value, $xmppusername);    
            }
  
            DefaultGroup::where('group_by',Auth::User()->id)->delete();

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

            foreach($arguments as $key => $data)
            {
                $affectedRows = Setting::where(['setting_title' =>  $key, 'user_id' =>  Auth::User()->id])->update(['setting_value' => $data]);
                if( !$affectedRows )
                {
                    $setting = new Setting;
                    $setting->setting_title = $key;
                    $setting->setting_value = $data;
                    $setting->user_id = Auth::User()->id; 
                    $setting->save();
                }

            }
            Session::put('success', 'Settings saved successfully.');
            return redirect()->back();
        }
        
        $settinguser = Setting::where('user_id', '=', Auth::User()->id)->pluck('setting_value', 'setting_title')->toArray();
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
        $model1 = Friend::
                    with('user')
                    ->where('friend_id', '=', Auth::User()->id)
                    ->where('status', '=', 'Pending')
                    ->orderby('id','ASC')
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
        }

        DefaultGroup::where('group_by',Auth::User()->id)->delete();

        return view('chatroom.groups');

    }


    public function subgroup( $parentid = '')
    {
        $subgroups = '';
         if($parentid){
            $data = Category::where(['parent_id' => $parentid])->where(['status' => 'Active'])->get();
            $name_check = Category::where('id',$parentid)->first();
            if($data->isEmpty()){
                if($name_check->title == "")
                    return redirect('group');
                else
                    return redirect('groupchat/'.$parentid);
            }
            else
                $subgroups = $data;

        }

        return view('chatroom.subgroups') 
                ->with('subgroups', $subgroups)
                ->with('p_group', $name_check);

    }

    public function subCatGroup($parentid = "")
    {
        if($parentid){
            $breadcrumb = "";
            $store_id = "";
            $id_arr = explode('-',$parentid);

            foreach ($id_arr as $key => $value) {
              $cat = Category::where('id',$value)->first();
                if($cat){
                    if($key == 0){
                        if($cat->parent_id != 0)
                            return redirect()->back();
                        else{
                            $store_id = $cat->id;
                            $next_url = url('sub-cat-group/'.$store_id);
                            if(sizeof($id_arr) == 1)
                                $title_id = $cat->title;
                            else
                                $title_id = "<a href='$next_url'>$cat->title</a>";
                            $breadcrumb .= ' > '.$title_id;
                        }
                    }else{
                        $check = Category::where('parent_id',$id_arr[$key-1])->first();
                        if($check->id="" || $check->selection == "Y")
                            return redirect()->back();
                        else{
                            $store_id .= '-'.$cat->id;
                            $next_url = url('sub-cat-group/'.$store_id);   
                            if(end($id_arr))
                               $title_id = $cat->title; 
                            else
                                $title_id = "<a href='$next_url'>$cat->title</a>";
                            $breadcrumb .= ' > '.$title_id;
                        }
                             
                    }

                } else if($cat == "")
                    return redirect()->back();
 
            }
                    $last_id = end($id_arr);
                    $img_icon = Category::where('id',$id_arr[0])->value('img_url');
                    $sub_groups = Category::where('parent_id',$last_id)->get();

                    if($sub_groups->isEmpty())
                        return redirect()->back();

                    return view('chatroom.subcatgroups')
                            ->with('parent_id',$parentid)
                            ->with('breadcrumb',$breadcrumb)
                            ->with('subgroup',$sub_groups)
                            ->with('icon_url',$img_icon);
            }
            else
                return redirect('group');


        }
    

    /**
    *   Group sub chatrooms ajax call handling.
    *   Ajaxcontroller@groupchatrooms
    */

    public function groupchat( $groupid = "" ){
        $private_group_check = "pub" ;
        $id=Auth::User()->id;
        if($groupid){
            $breadcrumb="";
            $check_name = "";
  
            $id_arr = explode('-',$groupid);
            foreach ($id_arr as $key => $value) {//10-46-147
              $cat = Category::where('id',$value)->first();
                if($cat){
                    if($key == 0){
                        if($cat->parent_id != 0)
                            return redirect()->back();
                        else{
        
                            $breadcrumb .= $cat->title;
                            $check_name .= $cat->title;
                        }
                    }else{
                        
                        $check = Category::where('parent_id',$id_arr[$key-1])->value('title');
                        if($check == null){  
                        }
                         else{
                            $breadcrumb .= '_'.$cat->title;
                            $check_name .= ' > '.$cat->title;
                         }
                             
                    }
                } else if($cat == "")
                    return redirect()->back();
                
            }

            $check_end = Category::where('parent_id',end($id_arr))->value('id');
            if($check_end != null)
                return redirect()->back();

			//Get users of this group
			$group_jid = preg_replace('/[^A-Za-z0-9\-]/', '_',$breadcrumb);
			$group_jid = strtolower($group_jid);
			
			$GroupImage = Category::where('id',$id_arr[0])->value( 'img_url' );

        } else {
			$input = Request::all();
			$parent_name = $input['parentname'];

                if($input['subcategory']=='International'){
                    $check_name = $input['parentname'].' > '.$input['subcategory'];
                    $input['subcategory'] = preg_replace('/[^A-Za-z0-9\-]/', '_',$input['subcategory']);
                    $sub_name = $input['subcategory'];
                }
                   

                 elseif($input['subcategory']=='Professional Course'){
                    $check_name = $input['parentname'].' > '.$input['subcategory'].' > '.$input['coursedata1'];
                     $sub_name = $input['subcategory'].'_'.$input['coursedata1'];
                 }
                   

                 elseif($input['subcategory']=='Subjects'){
                    $check_name = $input['parentname'].' > '.$input['subcategory'].' > '.$input['coursedata'];
                    $sub_name = $input['subcategory'].'_'.$input['coursedata'];
                 }
                    

                 elseif($input['subcategory']=='Country, State, City'){

                    $check_name = $input['parentname'].' > '.$input['country'].', '.$input['state'];
                    $input['subcategory'] = preg_replace('/[^A-Za-z0-9\-]/', '_',$input['subcategory']);

                    $sub_name = 'csc'.'_'.$input['country'].'_'.$input['state'].'_'.$input['city'];
                    if( $input['city'] ) {
                        $check_name .= ', '.$input['city'];
                    }
                 }
                    

                 elseif ( $input['subcategory']=='Country' ){
                    $check_name = $input['parentname'].' > '.$input['country1'];
                    $sub_name = 'c'.'_'.$input['country1'];
                 }

                 else{
                    $check_name = $input['parentname'].' > '.$input['subcategory'];
                    $sub_name = $input['subcategory'];
                 }
                         
                $group_jid = preg_replace('/[^A-Za-z0-9\-]/', '_',$parent_name.'_'.$sub_name);
                $group_jid = strtolower($group_jid);
                
                $GroupImage = Category::where('title',$input['parentname'])->value( 'img_url' );
        }
		$group_jid = strtolower($group_jid.'_pub');
        $group_jid =  preg_replace('/[^a-z0-9]/', '_' ,$group_jid);
		$model = new DefaultGroup;
		$updatecheck = $model->where('group_name', $group_jid)
							->where('group_by', Auth::User()->id)
							->get()->toArray();
		$defGroup = array();
		$defGroup['group_name'] = $group_jid;
		$defGroup['group_by'] = Auth::User()->id;
		if(empty($updatecheck))
			$model->create($defGroup);

		$usersData = DefaultGroup::with('user')->where('group_name', $group_jid)->get()->toArray();
		$friendid = Friend::where('user_id',$id)->where('status','Accepted')->pluck('friend_id');
		$pendingfriend = Friend::where('user_id',$id)->where('status','Pending')->pluck('friend_id');
		$private_group_array = GroupMembers::where(['member_id' => $id, 'status' => 'Joined'])->pluck('group_id');
      
		$privategroup = Group::whereIn('id',$private_group_array)->orderBy('id','DESC')->get()->toArray();

        $friendObj = Friend::with('friends')->where('user_id',$id)->where('status','Accepted')->get();

		 return view('chatroom.groupchat')
			->with('groupname', $check_name)
			->with('group_jid',$group_jid)
			->with('group_image',$GroupImage)
            ->with('friendObj',$friendObj)
			->with('userdata', $usersData)
			->with('friendid',$friendid)
			->with('authid',$id)
			->with('pendingfriend',$pendingfriend)
			->with('exception',$private_group_check)
			->with('privategroup',$privategroup);
    } 
	
    public function privateGroupChat($groupid = "")
    {
        $private_group_check = "private";
        $usersData = "";
        $id = Auth::User()->id;
            if($groupid){
                $group_check = Group::where('id',$groupid)->select('group_jid','title','picture')->first();
                if(empty($group_check))
                    return redirect('private-group-list');
                else{
					
					$group_jid = $group_check->group_jid;
                    $group_name = $group_check->title;
					$GroupImage = $group_check->picture;
					$friendid = Friend::where('user_id',$id)->where('status','Accepted')->pluck('friend_id');

                    $pendingfriend = Friend::where('user_id',$id)->where('status','Pending')->pluck('friend_id');
                    
                    $private_group_array = GroupMembers::where( ['member_id' => Auth::User()->id, 'status' => 'Joined'] )->pluck('group_id');
            
                    $privategroup = Group::with('members')->whereIn('id',$private_group_array)->orderBy('id','DESC')->get()->toArray();
                }

        }

        $friendObj = Friend::with('friends')->where('user_id',$id)->where('status','Accepted')->get();

		return view('chatroom.groupchat')
			->with('groupname', $group_name)
			->with('group_jid',$group_jid)
			->with('userdata', $usersData)
			->with('group_image',$GroupImage)
			->with('friendid',$friendid)
			->with('authid',$id)
            ->with('friendObj',$friendObj)
			->with('pendingfriend',$pendingfriend)
			->with('exception',$private_group_check)
			->with('privategroup',$privategroup);
    }

    public function friendsChat()
    {
        $private_group_check = null ;
        $check_name = "";
        $group_jid = "";
        $usersData = "";
        $GroupImage="";
        $id = Auth::User()->id;

        $friendObj = Friend::with('friends')->where('user_id',$id)->where('status','Accepted')->get();
        // echo '<pre>';print_r($friendObj->toArray());die;
        $pendingfriend = Friend::where('user_id',$id)->where('status','Pending')->pluck('friend_id');
        
        $private_group_array = GroupMembers::where(['member_id' => $id, 'status' => 'Joined'])->pluck('group_id');
        
        $privategroup = Group::with('members')->whereIn('id',$private_group_array)->orderBy('id','DESC')->get()->toArray();

         return view('chatroom.groupchat')
                    ->with('groupname', $check_name)
                    ->with('group_jid',$group_jid)
                    ->with('userdata', $usersData)
                    ->with('friendObj', $friendObj)
                    ->with('authid',$id)
                    ->with('group_image',$GroupImage)
                    ->with('pendingfriend',$pendingfriend)
                    ->with('exception',$private_group_check)
                    ->with('privategroup',$privategroup);
    }

    public function profile( $id )
    {

        $user = User::where('id', $id)->get()->first();
        if($user == null)
            return redirect('/');
        $education = EducationDetails::where('user_id', $id)->get();

        foreach($education as $key => $value) {
            if($value->education_level == "")
                unset($education[$key]);
        }        

        return view('profile.profile')
                ->with('user', $user)
                ->with('education', $education);  

    }


	public function editUserProfile( $id )
	{	        
        $arguments = Request::all();

        if(Auth::User()->id != $id)
            return redirect('/');

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
            		$education->state_of_establishment = isset($getCommonEduArgs['state_of_establishment'][$key]) ? $getCommonEduArgs['state_of_establishment'][$key] : '';
            		$education->city_of_establishment = isset($getCommonEduArgs['city_of_establishment'][$key]) ? $getCommonEduArgs['city_of_establishment'][$key] : '';

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

            if( ! $arguments['birthday'] ) {
                $arguments['birthday'] = null;
            }
            
            if($arguments)
            {
                unset($arguments['_token']);
                
                //Check for image upload.
                $file = Request::file('picture');
                if( isset($arguments['picture']) && $file != null )
                {
                    $image_name = time()."_POST_".strtoupper($file->getClientOriginalName());
                    $arguments['picture'] = $image_name;

                    // Resize pic
                    $path = public_path('uploads/user_img/'.$image_name);
                    Image::make($file->getRealPath())->resize(100, 100)->save($path);

                    // upload real pic
                    $file->move(public_path('uploads/user_img'), 'original_'.$image_name);
                    
                    // $path = public_path('uploads/user_img').'/'.$image_name;
					$ImageData 	= file_get_contents($path);
					$ImageType 	= pathinfo($path, PATHINFO_EXTENSION);
					$ImageData 	= base64_encode($ImageData);
					Converse::setVcard(Auth::User()->xmpp_username, $ImageData, $ImageType);
                    
                }
                
                if($arguments['country_code'] != 0 && $arguments['phone_no'] != null){
                    $min = countryMobileLength($arguments['country_code']);
                    $len = strlen($arguments['phone_no']);
                    
                    if(array_key_exists($arguments['country_code'], $min)){
                        if($len > $min[$arguments['country_code']]['max'] || $len < $min[$arguments['country_code']]['min']){
                            $arguments['phone_no'] = "";                        
                        }
                    }
                    $arguments['country_code'] = empty($arguments['phone_no']) ? '' : $arguments['country_code'];
                }


                if(empty($arguments['state'])){
                    $arguments['city'] = '';
                }
                $arguments['first_name']    = trim($arguments['first_name']);
                $arguments['last_name']     = trim($arguments['last_name']);
                $full_name = $arguments['first_name'].' '.$arguments['last_name'];
                Converse::setNameVcard(Auth::User()->xmpp_username, 'FN', $full_name);
                unset($arguments['email'], $arguments['password']);
                
                // Update user
                User::where([ 'id' => $id ])->update($arguments);
                
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

          $image = $_FILES["chatsendimage"]["name"];
          
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
        $userid = Auth::User()->id;

        if(Request::isMethod('post'))
        { 
            $input = Request::all();
                
            if(isset($input['broadcastuser']) && $input['broadcastname'] != null)
            {
                $members=implode(",",$input['broadcastuser']);
                
                $data = array(
                            'title' => $input['broadcastname'],
                            'user_id' => $userid
                        );  

                $br = Broadcast::create($data);
                
                foreach ($input['broadcastuser'] as $key => $value) {
                    $data1 = array(
                                'broadcast_id' => $br['id'],
                                'member_id' => $value
                            );

                    BroadcastMembers::create($data1);
                }

                return redirect(url('broadcast-list'));  
                
            }else{

                return redirect()->back();

            }

        }

        $broadcast_count = Broadcast::where('user_id',$userid)->get()->count();
        if($broadcast_count > Config::get('constants.broadcast_limit')){
            Session::put('error', "Sorry, you can only add upto ".Config::get('constants.broadcast_limit')." broadcasts.");
            return redirect()->back();
        }

        $friends=Friend::with('user')
                        ->with('user')
                        ->where('friend_id', '=', Auth::User()->id)
                        ->where('status', '=', 'Accepted')
                        ->get()
                        ->toArray();

        return view('broadcast.add')->with('friends',$friends);   

    }


    public function broadcastEdit( $broadcast_id )
    {

        $userid = Auth::User()->id;

        $broadcast = Broadcast::with('broadcastMembers')->where('id', $broadcast_id)->first()->toArray();

        $broadcast_prev_members = BroadcastMembers::where('broadcast_id', $broadcast_id)
                        ->pluck('member_id')
                        ->toArray();

        $friends = Friend::with('user')
                        ->where('friend_id', '=', $userid)
                        ->where('status', '=', 'Accepted')
                        ->get()
                        ->toArray();


        if(Request::isMethod('post'))
        { 
            $input = Request::all();
                
            if( isset( $input['broadcastuser'] ) && $input['broadcastname'] != null )
            {

                // Update broadcast details
                $b_cast = Broadcast::find($broadcast_id);
                $b_cast->title = $input['broadcastname'];
                $b_cast->save();


                // Update broadcast members
                $broadcastmembers = BroadcastMembers::where('broadcast_id', $broadcast_id)->delete();
                // echo '<pre>';print_r($input['broadcastuser']);die;
                if( !empty( $input['broadcastuser'] ) ){

                    foreach ( $input['broadcastuser'] as $key => $value ) {

                        $update_data = array(
                                    'broadcast_id' => $broadcast_id,
                                    'member_id' => $value
                                );

                        BroadcastMembers::create($update_data);

                    }

                }

                return redirect(url('broadcast-list'));  
                
            }else{

                return redirect()->back();

            }

        }

        return view('broadcast.edit')->with([
                    'friends' => $friends, 
                    'broadcast' => $broadcast,
                    'broadcast_prev_members' => $broadcast_prev_members,
                ]);


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
                 $name[]= User::where('id',$mem['member_id'])->value('first_name');
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
        $userid = Auth::User()->id;
        $privategroup = Group::with('members')->orderBy('groups.id','DESC')->get()->toArray();

        return view('privategroup.list')->with('privategroup',$privategroup);
    }
	/** 
	 * create private chat room/xmpp open chat room as private
	 **/
    public function privateGroupAdd() {

		$userid = Auth::User()->id;
        if( Request::isMethod('post') ){
				$userXamp = Auth::User()->xmpp_username;
				$name 	= Auth::User()->first_name.' '.Auth::User()->last_name;
				$input = Request::all();
	 
				if( isset($input['groupmembers']) && $input['groupname'] != null ){
					
					$members=implode(",",$input['groupmembers']);
					$data = array(
								'title'=>$input['groupname'],
								'status'=>'Active',
								'owner_id'=>$userid,
						    );  
					$GroupTitle = trim($input['groupname']);	
					$groupid   = preg_replace('/[^A-Za-z0-9\-]/', '_', $GroupTitle);
					$groupid   = strtolower($groupid);
					$converse  = new Converse;
					$groupdata = Group::create($data);
					$GroupJid = $groupid."_".$groupdata->id.'_pvt';
					
					$PrivateGroup = Group::find($groupdata->id);
					$PrivateGroup->group_jid = $GroupJid;
					$PrivateGroup->update();
					
					$converse->createGroup($groupid,$GroupJid);
					$SelfInsertMember = array(
								'group_id'	=> $groupdata->id,
								'member_id'	=> $userid,
								'status'	=> 'Joined'
							);
					GroupMembers::insert($SelfInsertMember); 
					foreach ($input['groupmembers'] as $data) {
						$data1 = array(
									'group_id'=>$groupdata->id,
									'member_id'=>$data,
									'status'=>'Pending',
								);
						 GroupMembers::insert($data1);  
					}
					array_push($input['groupmembers'],$userid);
					$xmp = User::whereIn('id',$input['groupmembers'])->select('id as user_id', DB::raw('CONCAT(first_name, " ", last_name) AS username'), 'xmpp_username as xmpp_userid','picture as user_image')->get();
					$Message = json_encode( array( 'type' => 'room', 'groupname' => $GroupTitle, 'sender_jid' => $userXamp, 'groupjid'=>$GroupJid, 'group_image' => '', 'created_by'=>$name,'message' => webEncode('Invitation to join "'.$GroupTitle.'" group.'), 'users' => $xmp) );

					foreach ($xmp as $key => $value) {
						$converse->addUserGroup( $GroupJid,$value->xmpp_userid );
						$converse->broadcast($userXamp,$value->xmpp_userid,$Message);
					}
				return redirect( url('groupchat/pg/'.$groupdata->id) );
			}  else {
			     return redirect()->back();
			}
		}

        $group_count = GroupMembers::where(['member_id' => $userid, 'status' => 'Joined'])->get()->count();
        if($group_count >= Config::get('constants.private_group_limit')){
            Session::put('error', "Sorry, you can only create upto ".Config::get('constants.private_group_limit')." private groups.");
            return redirect('private-group-list');
        }
		$friends=Friend::with('user')
                    ->where('friend_id', '=', $userid)
                    ->where('status', '=', 'Accepted')
                    ->get()
                    ->toArray();

     return view( 'privategroup.add' )->with( 'friends' ,$friends );
  }
    
    public function privateGroupDetail( $privategroupid = '' )
    {
        if( $privategroupid )
        {
            $groupdetail = Group::where('id',$privategroupid)->get()->toArray();

            if( !$groupdetail ){
                return redirect('private-group-list')->with('error','This private group does not exist.');
            }

            $ownerid = Group::where('id',$privategroupid)->value('owner_id');
            $members = GroupMembers::where('group_id',$privategroupid)->where('status', '!=', 'Left')->pluck('member_id');
            $name = User::whereIn('id',$members)->orWhere('id',$ownerid)->get()->toArray();
            
            $friends = Friend::with('user')
                        ->where('friend_id', '=', Auth::User()->id)
                        ->where('status', '=', 'Accepted')
                        ->whereNotIn('user_id', $members->toArray())
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

    public function changePassword()
    {
        if(Request::isMethod('post')){
            $input = Request::all();
            if(Auth::check()){
                if(Hash::check($input['old_password'], Auth::User()->password)){
                    if(Hash::check($input['old_password'], bcrypt($input['new_password']))) {
                        return redirect()->back()->with('error',"New password can't be same as old password.");
                    }else{
                        if(strlen($input['new_password']) < 8){
                            return redirect()->back()->with('error',"New password should be atleast 8 characters long.");
                        }else{
                                User::where('id',Auth::User()->id)->update(['password' => bcrypt($input['new_password'])]);
                             return redirect()->back()->with('success',"Password changed succesfully.");
                         }
                    }
                }else{
                    return redirect()->back()->with('error',"Password doesn't match our records.");
                }
            }
            else
                return redirect()->back();
        }   
        return view('auth.passwords.change');
    }

}
