<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

use App\Http\Requests;
use Request, Session, Validator, Input, Cookie;
use App\User, Auth,Mail,App\Forums,DB,App\ForumPost;
class SearchController extends Controller
{
    
     public function searchFromUsers()
    {
        if(Request::isMethod('post')){

            $input = Request::all();
            $name = $input['searchfriends'];

            if($name == "")
                return redirect('/');

            if(Auth::Check())

            {   
                $auth = 1;
                $pregMatch = preg_match('/\s/',$name); 

                if($pregMatch){
                    $name = explode(' ', $name);
                    $fname = $name[0];
                    $lname = $name[1];
                    $result = self::searchUsersFromSite($auth, $fname, $lname);
                }else{
                    $result = self::searchUsersFromSite($auth, $name);
                }

                $model1 = $result->toArray(); 
                $count = $result->count();
                $auth = 1;

            }else{
                $auth = 0;
                $pregMatch = preg_match('/\s/',$name); 

                if($pregMatch){
                    $name = explode(' ', $name);
                    $fname = $name[0];
                    $lname = $name[1];
                    $result = self::searchUsersFromSite($auth, $fname, $lname);
                }else{
                    $result = self::searchUsersFromSite($auth, $name);
                }
                // echo '<pre>';print_r($result->toArray());die;
                $model1 = $result->toArray(); 
                $count = $result->count();
                $auth = 0;
             }

        return view('dashboard.allusers')
                ->with('model1',$model1)
                ->with('count',$count)
                ->with('keyword',$input['searchfriends'])
                ->with('auth',$auth);    
        
        }
        
    }


    public function searchUsersFromSite($auth, $firstname, $lastname = ''){

        if($auth){
            if( !empty( $firstname ) && !empty( $lastname ) ) {
                return User::where('id','!=',Auth::User()->id)
                        ->where(function($query) use ( $firstname, $lastname ){
                            $query->where('first_name','LIKE','%'. $firstname.'%');
                            $query->orWhere('last_name','LIKE','%'. $lastname.'%');
                        })
                        ->orderBy('id','desc')
                        ->get();
            }elseif( !empty($firstname ) ) {
                return User::where('id','!=',Auth::User()->id)
                        ->where(function($query) use ( $firstname ){
                            $query->where('first_name','LIKE','%'. $firstname.'%');
                            $query->orWhere('last_name','LIKE','%'. $firstname.'%');
                        })
                        ->orderBy('id','desc')
                        ->get();
            }
        }else{
            if( !empty( $firstname ) && !empty( $lastname ) ) {
                return User::where(function($query) use ( $firstname, $lastname ){
                            $query->where('first_name','LIKE','%'. $firstname.'%');
                            $query->orWhere('last_name','LIKE','%'. $lastname.'%');
                        })
                        ->orderBy('id','desc')
                        ->get();
            }elseif( !empty($firstname ) ) {
                return User::where(function($query) use ( $firstname ){
                            $query->where('first_name','LIKE','%'. $firstname.'%');
                            $query->orWhere('last_name','LIKE','%'. $firstname.'%');
                        })
                        ->orderBy('id','desc')
                        ->get();
            }
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
                        ->take($per_page)
                        ->get();
        $postscount = $posts->count();
        return view('forums.viewforumposts')
                ->with('posts',$posts)
                ->with('postscount',$postscount)
                ->with('categoryname',$categoryname)
                ->with('categoryid',$id);
    }

    public function addNewForumPost()
    {
        if(Request::isMethod('post')){
            $input = Request::all();
            // print_r($input);die;
        $date = date('d M Y,h:i a', time());
            DB::table('forums_post')
                ->insert(['title'=>$input['topic'],
                        'owner_id'=>Auth::User()->id,
                        'category_id'=>$input['category_id'],
                        'created_at'=>date('Y-m-d H:i:s',time()),
                        'updated_at'=>date('Y-m-d H:i:s',time())]);
                return redirect('viewforumposts/'.$input['category_id']);
        }
    }
}
