<?php

namespace App\Http\Controllers;


use Auth, App\Feed, DB, App\Setting, App\Category,App\Group, App\Friend, App\DefaultGroup, App\User, App\Country, App\State, App\EducationDetails,App\JobArea,App\JobCategory,App\Broadcast,App\BroadcastMessages,App\GroupMembers,App\BroadcastMembers,App\Forums;

use App\Library\Converse, Google_Client, Mail;

use Request, Session, Validator, Input, Cookie, Hash;

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
            // print_r($response);die;
        }

        DB::table('default_groups')->where('group_by',Auth::User()->id)->delete();

        return view('chatroom.groups');

    }


    public function subgroup( $parentid = '')
    {
        $subgroups = '';
         if($parentid){
            $data = Category::where(['parent_id' => $parentid])->where(['status' => 'Active'])->get();
            $name_check = Category::where('id',$parentid)->value('title');;
            if($data->isEmpty()){
                if($name_check == "")
                    return redirect('group');
                else
                    return redirect('groupchat/'.$parentid);
            }
            else
                $subgroups = $data;

        }

        return view('chatroom.subgroups') 
                ->with('subgroups', $subgroups)
                ->with('group_name', $name_check);

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
            // print_r($breadcrumb);die;
                    $last_id = end($id_arr);
                    $sub_groups = Category::where('parent_id',$last_id)->get();

                    if($sub_groups->isEmpty())
                        return redirect()->back();

                    return view('chatroom.subcatgroups')
                            ->with('parent_id',$parentid)
                            ->with('breadcrumb',$breadcrumb)
                            ->with('subgroup',$sub_groups);
            }
            else
                return redirect('group');


        }
    

    /**
    *   Group sub chatrooms ajax call handling.
    *   Ajaxcontroller@groupchatrooms
    */

    public function groupchat($groupid = "")
    {
        $private_group_check = "pub" ;
        $id=Auth::User()->id;
        $replace_array =  [' ', '/', ',', '(', ')', "'", '.', ':', ';','&'];
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
                            //return redirect()->back();
                         else{
                            $breadcrumb .= '_'.$cat->title;
                            $check_name .= ' '.$cat->title;
                         }
                             
                    }
                } else if($cat == "")
                    return redirect()->back();
                
            }

            $check_end = Category::where('parent_id',end($id_arr))->value('id');
            if($check_end != null)
                return redirect()->back();
        //Get users of this group

        //$group_jid = strtolower(str_replace($replace_array, '-', $breadcrumb));
        $group_jid = preg_replace('/\s+/', '_',$breadcrumb);
        $group_jid = strtolower($group_jid);
        //print_r($group_jid);die;

        }else{
        $input = Request::all();
        $parent_name = strtolower(str_replace($replace_array, '-', $input['parentname']));

                if($input['subcategory']=='International'){
                    $check_name = $input['parentname'].' > '.$input['subcategory'];
                    $input['subcategory'] = str_replace(' ', '-', $input['subcategory']);
                    $sub_name = $input['subcategory'];
                }
                   

                 elseif($input['subcategory']=='Professional Course'){
                    $check_name = $input['parentname'].' > '.$input['subcategory'].' > '.$input['coursedata1'];
                     $sub_name = $input['subcategory'].'_'.$input['coursedata1'];
                 }
                   

                 elseif($input['subcategory']=='Subjects'){
                    $check_name = $input['parentname'].' '.$input['subcategory'].' '.$input['coursedata'];
                    $sub_name = $input['subcategory'].'_'.$input['coursedata'];
                 }
                    

                 elseif($input['subcategory']=='Country, State, City'){
                    $check_name = $input['parentname'].' '.$input['country'].', '.$input['state'].', '.$input['city'];
                    $input['subcategory'] = str_replace(' ', '-', $input['subcategory']);
                    $sub_name = 'csc'.'_'.$input['country'].'_'.$input['state'].'_'.$input['city'];
                 }
                    

                 elseif ( $input['subcategory']=='Country' ){
                    $check_name = $input['parentname'].' '.$input['country1'];
                    $sub_name = 'c'.'_'.$input['country1'];
                 }

                 else{
                    $check_name = $input['parentname'].' '.$input['subcategory'];
                    $sub_name = $input['subcategory'];
                 }
               

                $sub_name = str_replace($replace_array,'-',$sub_name);           
                $group_jid = preg_replace('/\s+/', '_',$parent_name.'_'.$sub_name);
                $group_jid = strtolower($group_jid);

        }

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
            
            $private_group_array = GroupMembers::where('member_id',$id)->pluck('group_id');
            
            $privategroup = Group::with('members')->whereIn('id',$private_group_array)->orderBy('id','DESC')->get()->toArray();

                 return view('chatroom.groupchat')
                    ->with('groupname', $check_name)
                    ->with('group_jid',$group_jid)
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
                $group_check = Group::where('id',$groupid)->value('title');
                if($group_check == null)
                    return redirect('private-group-list');
                else{
					
					$group_jid = preg_replace('/\s+/', '_',$group_check);
                    $group_jid = strtolower($group_jid).'_'.$groupid;

                    $friendid = DB::table('friends')->where('user_id',$id)->where('status','Accepted')->pluck('friend_id');

                    $pendingfriend = DB::table('friends')->where('user_id',$id)->where('status','Pending')->pluck('friend_id');
                    
                    $private_group_array = GroupMembers::where('member_id',Auth::User()->id)->pluck('group_id');
            
                    $privategroup = Group::with('members')->whereIn('id',$private_group_array)->orderBy('id','DESC')->get()->toArray();
                }

        }

                return view('chatroom.groupchat')
                    ->with('groupname', $group_check)
                    ->with('group_jid',$group_jid)
                    ->with('userdata', $usersData)
                    ->with('friendid',$friendid)
                    ->with('authid',$id)
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
        $id = Auth::User()->id;
        $friendid = DB::table('friends')->where('user_id',$id)->where('status','Accepted')->pluck('friend_id');

        $pendingfriend = DB::table('friends')->where('user_id',$id)->where('status','Pending')->pluck('friend_id');
        
        $private_group_array = GroupMembers::where('member_id',$id)->pluck('group_id');
        
        $privategroup = Group::with('members')->whereIn('id',$private_group_array)->orderBy('id','DESC')->get()->toArray();

         return view('chatroom.groupchat')
                    ->with('groupname', $check_name)
                    ->with('group_jid',$group_jid)
                    ->with('userdata', $usersData)
                    ->with('friendid',$friendid)
                    ->with('authid',$id)
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
            $arguments['birthday'] = date('Y-m-d',$time);
 
            if($arguments){

                unset($arguments['_token']);

                //Check for image upload.
                $file = Request::file('picture');
                if( isset($arguments['picture']) && $file != null ){
                    $image_name = time()."_POST_".strtoupper($file->getClientOriginalName());
                    $arguments['picture'] = '/uploads/user_img/'.$image_name;
                    $file->move(public_path('uploads/user_img'), $image_name);
                }
                
                $min = countryMobileLength($arguments['country_code']);
                $len = strlen($arguments['phone_no']);
                 if($len > $min[$arguments['country_code']]['max'] || $len < $min[$arguments['country_code']]['min'])
                    $arguments['phone_no'] = "";
                $arguments['country_code'] = empty($arguments['phone_no']) ? '' : $arguments['country_code'];
                // echo '<pre>';print_r($arguments);die;
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
        $privategroup=Group::with('members')->orderBy('id','DESC')->get()->toArray();

        return view('privategroup.list')->with('privategroup',$privategroup);
    }

    public function privateGroupAdd() {

		  if( Request::isMethod('post') ){
				$userid = Auth::User()->id;
				$userXamp = Auth::User()->xmpp_username;
				$input = Request::all();
	 
				if( isset($input['groupmembers']) && $input['groupname'] != null ){
					array_push($input['groupmembers'],$userid);
					$members=implode(",",$input['groupmembers']);
					$data = array(
							'title'=>$input['groupname'],
							'status'=>'Active',
							'owner_id'=>$userid,
						   );  

					$groupid   = preg_replace('/\s+/', '_', $input['groupname']);
					$groupid   = strtolower($groupid);
					$converse  = new Converse;
					$groupdata = Group::create($data);
					$groupname = $groupid."_".$groupdata->id;
					
					$converse->createGroup($groupid,$groupname);
					
					foreach ($input['groupmembers'] as $data) {
						$data1 = array(
									'group_id'=>$groupdata->id,
									'member_id'=>$data,
									'status'=>'Joined',
								);
						 GroupMembers::insert($data1);  
					}

		   
					$xmp = DB::table('users')->whereIn('id',$input['groupmembers'])->pluck('xmpp_username');
					$Message = json_encode( array( 'type' => 'privatechat' , 'chatgroup' => $groupname.'@conference.'.Config::get('constants.xmpp_host_Url'), 'message' => '' ) );
					foreach ($xmp as $key => $value) {
						$converse->addUserGroup( $groupname,$value );
						$converse->broadcast($userXamp,$value,$Message);
					}
				return redirect(url('private-group-list'));       
			}  else {
			  return redirect()->back();
			}
		}

		$friends=Friend::with('user')
                    ->with('user')
                    ->where('friend_id', '=', Auth::User()->id)
                    ->where('status', '=', 'Accepted')
                    ->get()
                    ->toArray();

     return view( 'privategroup.add' )->with( 'friends' ,$friends );
  }

  public function privateGroupDetail( $privategroupid = '' ){
    if( $privategroupid ){
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


    /*
     * @return Response For Push Notification In IOS
     */
    public function pushNotificationIphone()
    {   
        $data = array(
            'message' => "this is message",
            'token' => 'cd967ddac1c1acd00c3fa5d3700afda1dab7d449b8aacdf67c34e64edd6e2262'
        );
        
        $msg = 'Message not delivered';   
        
        if(iphonePushNotification($data))
            $msg='Message successfully delivered';  

        return $msg;
    }


    /*
     * @return Response For Push Notification In Android
     */
    public function pushNotificationAndroid()
    {   
        $data=array('registration_ids'=>array( 'APA91bGsmuvwZ8N0Fhc8JflH_t3agUK_MNQn6mZEvgkBw2hb2_P9yrnLOSAjgtk_vUgj50In5xAvPD5NH4J-gm_MrGYf9JpPJ7qPKo6e9cUa7tdHXEseSaw' ),
            'data'=>array(
                            'message'   => 'Here is a message from Mayank123',
                            'title'     => 'From: Mayank123',
                            'subtitle'  => 'My-subtitle',
                            'tickerText'    => 'My tickerText',
                            'vibrate'   => 1,
                            'sound'     => 1,
                            'largeIcon' => 'large_icon',
                            'smallIcon' => 'small_icon'
                        ));
        $msg='Message not delivered';   
        
        if(androidPushNotification($data)) $msg='Message successfully delivered';
        return $msg;
    }

   

}
