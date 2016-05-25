<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

use App\Http\Requests;
use Request, Session, Validator, Input, Cookie;
use App\User, Auth, Mail, App\Forums, DB, App\ForumPost, App\Friend;

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
                $model = $model->where('id', '!=', $authUserId);

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

        if($email != ''){
        Mail::send('emails.suggestion', $data, function($message) use($email, $subject) {
        $message->from($email, 'User Feedback');
        $message->to('adi490162@gmail.com')->subject($subject);
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
    }


    public function termsConditions()
    {
        return view('terms-conditions');
    }

    /******* FORUMS ********/

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
           $subforums = Forums::where('parent_id',$parentid)->get();

        }
        else
        {
         return redirect('forums');   
        }

        return view('forums.subforums')
                ->with('mainforum',$mainforum)
                ->with('subforums',$subforums);

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
        // $posts = ForumPost::where('category_id',$id)->get();

         $per_page = 5;

        // $posts = ForumPost::with('likesCount')->with('replyCount')->with('user')->with('likes')->with('reply')
        //        ->where('category_id',$id)
        //         //->orderBy('forums_post.id','DESC')
        //         ->take($per_page)
        //         ->get()
        //         ->toSql();

        //         print_r($posts);die;
        $categoryname = Forums::where('id',$id)->value('title');
        $posts = ForumPost::with('user')
                        ->where('category_id',$id)
                        //->take($per_page)
                        ->get();
        $postscount = $posts->count();
        return view('forums.viewforumposts')
                ->with('posts',$posts)
                ->with('postscount',$postscount)
                ->with('categoryname',$categoryname)
                ->with('categoryid',$id);
    }

  
}
