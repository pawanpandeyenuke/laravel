<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

use App\Http\Requests,Config;
use Request, Session, Validator, Input, Cookie;
use App\User, Auth,Mail,App\Forums,DB,App\ForumPost,App\Friend,App\ForumLikes,App\ForumReply,App\ForumsDoctor;
class SearchController extends Controller
{
    
     public function searchFromUsers()
    {
        if(Request::isMethod('post')){

            $input = Request::all();
            $keyword = $input['searchfriends'];

            if($keyword == "")
                return redirect('/');

            $authUserId = isset(Auth::User()->id) ? Auth::User()->id : '';

            $model = new User;

            // Search for the following people.
            if(trim($keyword) != ''){

                $model = $model->where( function( $query ) use ( $input, $keyword ) {
                    $expVal = explode(' ', $keyword);
                    foreach( $expVal as $key => $value ) {                          
                        $query->orWhere( 'last_name', 'LIKE', '%'. $value.'%' )
                            ->orWhere( 'first_name', 'LIKE', '%'. $value.'%' );  
                    }
                });

            }

            if( $authUserId != '' ){
                
                // User cannot search himself.
               // $model = $model->where('id', '!=', $authUserId);

                // Search for user's who are not friends with me.
                $model = $model->whereNotIn('id', Friend::where('user_id', '=', $authUserId)
                                ->where('status', '=', 'Accepted')
                                ->pluck('friend_id')
                                ->toArray() );

            }

            // Gather all the results from the queries and paginate it.
            $count = $model->get()->count();
            $result = $model->orderBy('id','desc')->take(10)->get();   

            $model1 = $result->toArray(); 
            
            $auth = ($authUserId != '') ? 1 : 0;

            return view('dashboard.allusers')
                ->with('model1',$model1)
                ->with('count',$count)
                ->with('keyword',$input['searchfriends'])
                ->with('auth',$auth);    
        
        }
        
    }


    public function contactUs()
    {
        $arguments = Request::all();
        $feedbackid = "feedback@friendzsquare.com";
        if($arguments['email'] == "")
            $arguments['email'] = "Anonymous User";

        self::suggestionMail($feedbackid,$arguments['message_text'],'Suggestion',$arguments['email']);

        //Session::put('success', 'Thank you for your valuable suggestion!');
        
        return 'success';
        // return redirect()->back()->with('success', 'Thank you for your valuable suggestion!');


    }

    public function suggestionMail($email = '', $message_text, $subject,$usermail) {
  
        $data = array(
            'message_text' => $message_text,
            'subject' => $subject,
            'usermail'=>$usermail
        );
        $email_const = Config::get('constants.feedback_email');
        if($email != ''){
        Mail::send('emails.suggestion', $data, function($message) use($email, $subject,$email_const) {
        $message->from($email, 'User Feedback');
        $message->to($email_const)->subject($subject);
    });
        }
    }

    public function newPassword()
    {
        Auth::logout();
        return view('auth.passwords.newpassword');
    }

    public function verify()
    {
        Auth::logout();
        if(!Auth::check()){
            if(Request::isMethod('post')){
                $arguments = Request::all();
                $user = User::where('email',$arguments['email'])->first();
                //print_r($user);die;
                if($user != null){
                     if($user->is_email_verified == "Y"){
                     Session::put('error', 'This email is already verified!');
                     return redirect()->back();
                 }
                 elseif($user->is_email_verified == "N"){
                     $useremail = $user->email;
                    $username = $user->first_name." ".$user->last_name;
                    $confirmation_code = str_random(30);
                    User::where('email',$arguments['email'])->update(['confirmation_code'=>$confirmation_code]);
                    $emaildata = array('confirmation_code' => $confirmation_code);

                        Mail::send('emails.verify',$emaildata, function($message) use($useremail, $username){
                        $message->from('no-reply@friendzsquare.com', 'Verify Friendzsquare Account');
                        $message->to($useremail,$username)->subject('Verify your email address');
                        });
                    Session::put('success', 'Verification link sent to '.$useremail.' !');
                      return redirect()->back();
                 }
                }
                else{
                     Session::put('error', "We can't find a user with that e-mail address.");
                     return redirect()->back();
                    }
            }
                return view('verifyemail');
        }else{
            return redirect('/');
        }
    }


    public function termsConditions()
    {
        return view('terms-conditions');
    }

    /******* FORUMS ********/

     public function forumsList()
    {
        $mainforums = Forums::where('parent_id',0)->orderBy('display_order')->get();
        return view('forums.mainforums')
            ->with('forums',$mainforums);
    }

    public function subForums($parentid='')
    {
        if($parentid){

        $r1 = Forums::where('id',$parentid)->where('parent_id',0)->first();
        if($r1 == "")
            return redirect('forums');
    
           $mainforum=Forums::where('id',$parentid)->first();
           $subforums = Forums::where('parent_id',$parentid)->get();
           if($subforums->isEmpty())
                    return redirect()->back();
        }
        else
             return redirect('forums');   


        $flag = 0;

        if($r1->selection == "Y")
            $flag = 1;

        return view('forums.subforums')
                ->with('mainforum',$mainforum)
                ->with('subforums',$subforums)
                ->with('flag',$flag);

    }

    public function subCatForums($parentid='')
    {

           $check = Forums::where('id',$parentid)->value('parent_id');
           if($check == 0 || $check == null)
                return redirect('forums');

        $parentforumid = Forums::where('id',$parentid)->value('parent_id');
        $parentforum = Forums::where('id',$parentforumid)->value('title');
        $mainforum=Forums::where('id',$parentid)->value('title');
        $subforums = Forums::where('parent_id',$parentid)->get();

         return view('forums.subcatforums')
                ->with('mainforum',$mainforum)
                ->with('mainforumid',$parentid)
                ->with('subforums',$subforums)
                ->with('parentforumid',$parentforumid)
                ->with('parentforum',$parentforum);

    }

    public function viewForumPosts($id = "")
    {

    	/*************************/

    	$parent = Forums::where('parent_id',$id)->value('id');
    	$parent2 = Forums::where('id',$id)->value('title');
    	if($parent != null || $parent2 == null || $parent2 == "International" || $parent2 == "Country" || $parent2 == "Country,State,City")
    		return redirect('forums');

    	/************************/
        $categoryname = Forums::where('id',$id)->value('title');
        $posts = ForumPost::with('user')
                        ->with('forumPostLikesCount')
                        ->with('replyCount')
                        ->where('category_id',$id)
                        ->orderBy('updated_at','DESC')
                        ->get();

        $forum_category_breadcrum="";
        $parents1 = Forums::where('id',$id)->first();
            if($parents1->parent_id == 0){
                    $forum_category_id = $parents1->id;
                    $forum_category_breadcrum = $parents1->title; 
            }
            else{
                $parents2 = Forums::where('id',$parents1->parent_id)->first();
                if($parents2->parent_id == 0){
                    $forum_category_id = $parents2->id.",".$parents1->id;
                    $forum_category_breadcrum = $parents2->title." > ".$parents1->title;
                }
                else{
                    $parents3 = Forums::where('id',$parents2->parent_id)->first();
                    $forum_category_id = $parents3->id.",".$parents2->id.",".$parents1->id;
                    $forum_category_breadcrum = $parents3->title." > ".$parents2->title." > ".$parents1->title;
                }
            }

        $postscount = $posts->count();
        $posts = $posts->take(10);
        return view('forums.viewforumposts')
                ->with('posts',$posts)
                ->with('postscount',$postscount)
                ->with('breadcrum',$forum_category_breadcrum);
    }

    public function forumPostReply($forumpostid = "")
    {
        $checkpost = ForumPost::with('user')
                        ->with('forumPostLikesCount')
                        ->where('id',$forumpostid)
                        ->first();

    	if(empty($checkpost))
            return redirect()->back();

        $reply = ForumReply::with('user')
                ->with('replyLikesCount')
                ->with('replyCommentsCount')
                ->where('post_id',$forumpostid)
                ->orderBy('updated_at','DESC')
                ->get();

        $replycount = $reply->count();
        $reply = $reply->take(10);
        $checkarr = array();

        return view('forums.forumpostreply')
                    ->with('post',$checkpost)
                    ->with('replycount',$replycount)
                    ->with('reply',$reply);
    }

    public function viewForumPostsOpt()
    {
        $input = Request::all();

        $breadcrum = $input['mainforum']." > ";
        if($input['mainforum'] == "Doctor"){
         
         if($input['subcategory'] == "international")
                $breadcrum = $breadcrum."International > ".$input['idiseases'];
         else if($input['subcategory'] == "country")
                $breadcrum = $breadcrum.$input['country1']." > ".$input['cdiseases'];
          else if($input['subcategory'] == 'country,state,city')
                $breadcrum = $breadcrum.$input['country']." > ".$input['state']." > ".$input['city']." > ".$input['cscdiseases'];  
         
         }else{

            if($input['subcategory'] == "international")
                $breadcrum = $breadcrum."International";
            else if($input['subcategory'] == "country")
                $breadcrum = $breadcrum.$input['country1'];
            else if($input['subcategory'] == 'country,state,city')
                $breadcrum = $breadcrum.$input['country']." > ".$input['state']." > ".$input['city'];   
         }

         $posts = ForumPost::with('user')
                        ->with('forumPostLikesCount')
                        ->with('replyCount')
                        ->where('forum_category_breadcrum',$breadcrum)
                        ->orderBy('updated_at','DESC')
                        ->get();
         
        $postscount = $posts->count();
        $posts = $posts->take(10);

        return view('forums.viewforumposts')
                ->with('posts',$posts)
                ->with('postscount',$postscount)
                ->with('breadcrum',$breadcrum);
    }

    public function searchForum()
    {
  

        $input = Request::all();
        //print_r($input);die;
        $mainforum = Forums::where('id',$input['mainforum'])->value('title');
        $breadcrum = $mainforum;
        if($input['check']=='direct'){
            $breadcrum = $mainforum;
        }
        else if($input['check'] == 'sub'){
            $subforum = Forums::where('id',$input['search-subforums'])->value('title');
            $breadcrum = $breadcrum." > ".$subforum;
        }
        else if($input['check'] == 'subfor'){
            $subforum = Forums::where('id',$input['search-subforums'])->value('title');
            $sub = Forums::where('id',$input['search-subject1'])->value('title');
            $breadcrum = $breadcrum." > ".$subforum." > ".$sub;
        }
        else if($input['check'] == 'c'){
            $breadcrum = $breadcrum." > ".$input['search-country1'];
        }
        else if($input['check'] == 'csc'){
            $breadcrum = $breadcrum." > ".$input['search-country']." > ".$input['search-state']." > ".$input['search-city'];
        }

        if($mainforum == "Doctor" && $input['check']!='direct')
            $breadcrum = $breadcrum." > ".$input['search-diseases'];
        
        $keyword = strtolower($input['forum-keyword']);

        $replyresult = ForumReply::whereRaw( 'LOWER(`reply`) like ?', array("%".$keyword."%"))
                            ->pluck('post_id')
                            ->toArray();


        $results = ForumPost::with('user')
                        ->with('forumPostLikesCount')
                        ->with('replyCount')
                        ->where('forum_category_breadcrum', 'LIKE', $breadcrum.'%')
                        ->whereRaw( 'LOWER(`title`) like ?', array("%".$keyword."%"))
                        ->orWhere( function( $query ) use ( $replyresult, $breadcrum) {
                            $query->whereIn( 'id', $replyresult)
                                  ->where('forum_category_breadcrum', 'LIKE', $breadcrum.'%'); })
                        ->orderBy('updated_at','DESC')
                        ->get();

            $count = $results->count();
            $results = $results->take(10); 
                    return view('forums.searchresultforum')
                ->with('postscount',$count)
                ->with('keyword',$keyword)
                ->with('breadcrum',$breadcrum)
                ->with('posts',$results)
                ->with('old',$input);

     
        

    
    }

    public function searchForumGet()
    {
            $count = 0;
            $keyword = '';
            $breadcrum = '';
            $results = [];
            $input=[];

                return view('forums.searchresultforum')
                ->with('postscount',$count)
                ->with('keyword',$keyword)
                ->with('breadcrum',$breadcrum)
                ->with('posts',$results)
                ->with('old',$input);
            
    }

    public function demo()
    {
    	return view('demo');
    }

    public function confirm($confirmation_code)
    {
      if( ! $confirmation_code)
        {
            Session::put('error',"Wrong confirmation code!");
           return redirect('/');
        }

        $user = User::where('confirmation_code',$confirmation_code)->first();

        if ( ! $user)
        {
             Session::put('error',"No user with matching verification code found!");
             return redirect('/');
        }

        $user->is_email_verified = 'Y';
        //$user->confirmation_code = null;
        $user->save();

        Session::put('success',"Your account has been successfully verified!");
        return redirect('email-verified/'.$user->id.'/'.$confirmation_code);
    }

    public function emailVerified($user_id="",$confirmation_code="")
    {
        if(!Auth::check()){
            if($user_id!="" && $confirmation_code!=""){
                $user = User::find($user_id);
                if($user){
                     if($user->confirmation_code == $confirmation_code){
                            $user->confirmation_code = null;
                            $user->save();
                            return view('email-verified');
                     }
                    else
                        return redirect('/');
                }else{
                    return redirect('/');           
                }
            }
        }else
            return redirect('/');
    }
  

}
