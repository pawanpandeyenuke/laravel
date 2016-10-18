<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

use App\Http\Requests,Config;
use Response, Request, Session, Validator, Input, Cookie, URL;
use App\User, Auth,Mail,App\Forums,DB,App\ForumPost,App\Friend,App\ForumLikes,App\ForumReply,App\ForumsDoctor;
use App\Setting, App\UnsubscribedUsers, App\Country, App\State, App\City;
use App\Library\Functions;

class SearchController extends Controller
{
    public function searchFromUsers()
    {
        if(Request::isMethod('post'))
        {
            $input = Request::all();
            $keyword = trim($input['searchfriends']);
            if($keyword == "") {
                return redirect('/');
            }

            $page = 1; $perPage = 10;
            $authUserId = Auth::check() ? Auth::User()->id : 0;
            
            // Search users
            $users = Functions::searchUsers($keyword, $authUserId, $page, $perPage);

            $auth = ($authUserId != '') ? 1 : 0;
            return view('dashboard.allusers')
                ->with('model1',$users['records'])
                ->with('count',$users['total'])
                ->with('keyword',$input['searchfriends'])
                ->with('auth',$auth);
        }
    }

    public function contactUs()
    {
        $arguments = Request::all();
        $feedbackid = "feedback@friendzsquare.com";
        if($arguments['email'] == "") {
            $arguments['email'] = "";
        }

        self::suggestionMail($feedbackid,$arguments['message_text'],'Suggestion',$arguments['email']);
        return 'success';
    }

    // Send suggestion mail
    public function suggestionMail($email = '', $message_text, $subject,$usermail) 
    {
        $data = array(
            'message_text' => $message_text,
            'subject' => $subject,
            'usermail'=>$usermail
        );
            
        $email_const = Config::get('constants.feedback_email');
        if($email != '')
        {
            Mail::send('emails.suggestion', $data, function($message) use($email, $subject,$email_const){
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
                    $emaildata = array('confirmation_code' => $confirmation_code, 'email' => $useremail, 'fullname' => $username );

                        Mail::send('emails.verify',$emaildata, function($message) use($useremail, $username){
                            $message->from('contact@friendzsquare.com', 'FriendzSquare');
                            $message->to($useremail,$username)->subject($username.'.. Please authenticate your email Address');
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

    public function terms()
    {
        return view('terms');
    }
    
    public function aboutUs()
    {
        return view('about-us');
    }
    
    public function privacyPolicy()
    {
        return view('privacy-policy');
    }

    /******* FORUMS ********/

    public function forumsManage($hierarchy='')
    {
        $categories = explode('/', $hierarchy);
        $categories = array_filter($categories, function($value) { return $value !== ''; });
        $parameterCount = count( $categories );

        $parentCat    = reset($categories);
        $ParentForums = Forums::select( ['id','title','selection'] )->where(['forum_slug' => $parentCat,'status' => 'Active' ,'parent_id' => 0])->first();
        
        if( $ParentForums ) {
            switch($parameterCount){
                case 1: 
                    $SubForum = Forums::select( 'id' )->where(['parent_id' => $ParentForums->id,'status' => 'Active'])->count();
                    if( $SubForum ){
                        return $this->subForums( $ParentForums->id );
                    } else {
                        return $this->viewForumPosts($ParentForums->id);
                    }
                    break;
                case 2:
                    $LastPara = last($categories);
                    
                    $Forums = Forums::select( 'id' )->where(['forum_slug' => $LastPara,'status' => 'Active' ,'parent_id' => $ParentForums->id])->first();

                    if( !empty($Forums) && $ParentForums->selection != 'Y' ){
                        $SubForum = Forums::select( 'id' )->where(['parent_id' => $Forums->id,'status' => 'Active'])->count();
                        if( $SubForum ){
                            return $this->subCatForums( $Forums->id );
                        } else {
                            return $this->viewForumPosts($Forums->id);
                        }

                    } else if( isset($ParentForums->selection) && $ParentForums->selection == 'Y' ) {
                        
                        if( $LastPara == 'international' ){

                            return $this->viewForumPostsOpt( ['mainforum' => $ParentForums->title,'subcategory' => 'international','idiseases' => ''] );
                        } else {
                            $Country = Country::select( 'country_name' )->where(['country_slug' => $LastPara])->first();
                            if($Country){
                                return $this->viewForumPostsOpt( ['mainforum' => $ParentForums->title,'subcategory' => 'country','country1' => $Country->country_name ] );
                            } else {
                                return Response::view('errors.404',[],404);
                            }
                        }
                    } else {
                        return Response::view('errors.404',[],404);
                    }
                    break;
                case 3:
                    $subParentCat   = $categories[1];
                    $LastPara       = last($categories);
                    $subParentForums = Forums::select( 'id' ,'selection','title' )->where(['forum_slug' => $subParentCat,'status' => 'Active' ,'parent_id' => $ParentForums->id])->first();
                    $currentForums = Forums::select( 'id' )->where(['forum_slug' => $LastPara,'status' => 'Active' ,'parent_id' => ( isset($subParentForums->id)?$subParentForums->id:0)])->first();
                    if( $currentForums ){
                        return $this->viewForumPosts( $currentForums->id );
                    } else {
                        if( $parentCat == 'doctor' ){
                            $ForumsDoctor = ForumsDoctor::select( 'title' )->where(['doctor_slug' => $LastPara])->first();
                            if( $subParentCat == 'international' ){
                                return $this->viewForumPostsOpt( ['mainforum' => $ParentForums->title,'subcategory' => 'international','idiseases' => (isset($ForumsDoctor->title)?$ForumsDoctor->title:'') ] );
                            } else {
                                $Country = Country::select( 'country_name' )->where(['country_slug' => $subParentCat ])->first();
                                if( $Country ){
                                    return $this->viewForumPostsOpt( ['mainforum' => $ParentForums->title,'subcategory' => 'country', 'country1' => $Country->country_name, 'cdiseases' => (isset($ForumsDoctor->title)?$ForumsDoctor->title:'')] );
                                } else {
                                    return Response::view('errors.404',[],404);     
                                }
                            }
                        } else {
                            return Response::view('errors.404',[],404);
                        }
                    }
                    break;
                case 4:
                    $parentCat      = $categories[0];
                    $countrySlug    = $categories[1];
                    $stateSlug      = $categories[2];
                    $citySlug       = last($categories);

                        $Country = Country::select( ['country_name','country_id'] )->where(['country_slug' => $countrySlug ])->first();
                        $State = State::select( ['state_id','state_name'] )->where(['state_slug' => $stateSlug, 'country_id' => (isset($Country->country_id)?$Country->country_id:0) ])->first();
                        $City = City::select( 'city_name' )->where(['city_slug' => $citySlug, 'state_id' => (isset($State->state_id)?$State->state_id:0) ])->first();
                        if( $City ){
                            return $this->viewForumPostsOpt( ['mainforum' => $ParentForums->title,'subcategory' => 'country,state,city', 'country' => $Country->country_name, 'state' => $State->state_name, 'city' => $City->city_name, 'cscdiseases' => ''] );
                        } else {
                            return Response::view('errors.404',[],404); 
                        }

                    break;
                case 5:
                    $countrySlug    = $categories[1];
                    $stateSlug      = $categories[2];
                    $citySlug       = $categories[3];
                    $diseasesSlug   = last($categories);

                        $Country = Country::select( ['country_name','country_id'] )->where(['country_slug' => $countrySlug ])->first();

                        $State = State::select( ['state_id','state_name'] )->where(['state_slug' => $stateSlug, 'country_id' => (isset($Country->country_id)?$Country->country_id:0) ])->first();
                        $City = City::select( 'city_name' )->where(['city_slug' => $citySlug, 'state_id' => (isset($State->state_id)?$State->state_id:0) ])->first();

                        $ForumsDoctor = ForumsDoctor::select( 'title' )->where(['doctor_slug' => $diseasesSlug])->first();

                        if( $City && $ForumsDoctor ){
                            return $this->viewForumPostsOpt( ['mainforum' => $ParentForums->title,'subcategory' => 'country,state,city', 'country' => $Country->country_name, 'state' => $State->state_name, 'city' => $City->city_name, 'cscdiseases' => $ForumsDoctor->title] );
                        } else {
                            return Response::view('errors.404',[],404); 
                        }
          
                break;
                default:
                    return Response::view('errors.404',[],404);
                break;
            }
        }
        return Response::view('errors.404',[],404);
    }

     public function forumsList()
    {
        $mainforums = Forums::where('parent_id',0)->where('status', 'Active')->orderBy('display_order')->get();
        return view('forums.mainforums')
            ->with('forums',$mainforums);
    }

    public function subForums($parentid='')
    {
        if($parentid)
        {
            $r1 = Forums::where('id',$parentid)->where('parent_id',0)->first();
            if($r1 == "")
                return redirect('forums');
        
               $mainforum = Forums::where('id',$parentid)->first();
               $subforums = Forums::where('parent_id',$parentid)->where('status', 'Active')->get();
               if($subforums->isEmpty())
                        return redirect()->back();
        }else{
            return redirect('forums');   
        }


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
        $subforums = Forums::where('parent_id',$parentid)->where('status', 'Active')->get();
        $mainforumSlug = Forums::where('id',$parentid)->value('forum_slug');
        $parentforumSlug = Forums::where('id',$parentforumid)->value('forum_slug');

        return view('forums.subcatforums')
                ->with('mainforum',$mainforum)
                ->with('mainforumid',$parentid)
                ->with('subforums',$subforums)
                ->with('parentforumid',$parentforumid)
                ->with('parentforum',$parentforum)
                ->with('parentforumslug',$parentforumSlug)
                ->with('mainforumslug',$mainforumSlug);

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
                        ;

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

        $postscount = $posts->get()->count();
        $posts = $posts->paginate(10);

        $currentPage =$posts->currentPage();
        $pageCount = $posts->lastPage();

        $firstItem = $posts->firstItem();
        $lastItem = $posts->lastItem();

        $lastURL = URL::previous();
        $currentURL = URL::current();
        $lastURL = $lastURL==$currentURL ? url('/forums') : $lastURL;
        
        return view('forums.viewforumposts')
                ->with('posts',$posts)
                ->with('postscount',$postscount)
                ->with('lastURL', $lastURL)
                ->with('firstitem',$firstItem)
                ->with('lastitem',$lastItem)
                ->with('currentpage',$currentPage)
                ->with('pagecount', $pageCount)
                ->with('breadcrum',$forum_category_breadcrum);
    }

    public function forumPostReply($forumpostid = "")
    {
        
        $postId = explode( '-', $forumpostid );
        
        $postslugStr = substr($forumpostid, 0, - (strlen(last($postId)) + 1));
        $forumpostid = last($postId);
        $checkpost = ForumPost::with('user')
                        ->with('forumPostLikesCount')
                        ->where('id',$forumpostid)
                        ->first();

    	if(empty($checkpost))
            return redirect()->back();

        if( !validateForumReply($checkpost, $postslugStr) ){
            return Response::view('errors.404',[], 301 );
        }

        $reply = ForumReply::with('user')
                ->with('replyLikesCount')
                ->with('replyCommentsCount')
                ->where('post_id',$forumpostid)
                ->orderBy('updated_at','DESC')->paginate(10);

        
        $replycount = $reply->total();


        $checkarr = array();

        $lastURL = URL::previous();
        $currentURL = URL::current();
        $lastURL = $lastURL==$currentURL ? url('/forums') : $lastURL;

        return view('forums.forumpostreply')
                    ->with('post',$checkpost)
                    ->with('lastURL', $lastURL)
                    ->with('replycount',$replycount)
                    ->with('reply',$reply);
    }

    public function viewForumPostsOpt( $data = array() )
    {
        
        $input = Request::all();
        if( !empty($data) ){
            $input = $data;
        }


        // Restore from session
        if( !isset($input['mainforum']) || !$input['mainforum'] )
        {
            $session = Session::get('forum_post_request');
            if( isset($session['mainforum']) )
            {
                foreach($session as $key => $val){
                    $input[$key] = $val;
                }
            }
        }

        if( ! $input['mainforum'] ) {
            return redirect('');
        }

        $breadcrum = $input['mainforum']." > ";
        if($input['mainforum'] == "Doctor"){
         
            if($input['subcategory'] == "international")
                $breadcrum = $breadcrum."International > ".$input['idiseases'];
            else if($input['subcategory'] == "country")
                $breadcrum = $breadcrum.$input['country1']." > ".$input['cdiseases'];
            else if($input['subcategory'] == 'country,state,city') {
                $breadcrum = $breadcrum.$input['country']." > ".$input['state'];
                if( $input['city'] ) {
                    $breadcrum .= " > ".$input['city'];
                }
                $breadcrum .= " > ".$input['cscdiseases'];
            }
         
         }else{

            if($input['subcategory'] == "international")
                $breadcrum = $breadcrum."International";
            else if($input['subcategory'] == "country")
                $breadcrum = $breadcrum.$input['country1'];
            else if($input['subcategory'] == 'country,state,city'){
                $breadcrum = $breadcrum.$input['country']." > ".$input['state'];
                if( $input['city'] ) {
                    $breadcrum .= " > ".$input['city'];
                }
            }
         }

         $posts = ForumPost::with('user')
                        ->with('forumPostLikesCount')
                        ->with('replyCount')
                        ->where('forum_category_breadcrum',$breadcrum)
                        ->orderBy('updated_at','DESC')
                        ->get();
         
        $postscount = $posts->count();
        $posts = $posts->take(10);

        $lastURL = URL::previous();
        $currentURL = URL::current();
        $lastURL = $lastURL==$currentURL ? url('/forums') : $lastURL;
        
        Session::put('forum_post_request', $input);
        
        return view('forums.viewforumposts')
                ->with('posts',$posts)
                ->with('lastURL',$lastURL)
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
        
        $keyword = strtolower(trim($input['forum-keyword']));

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
                        ->orderBy('updated_at','DESC');
                        

        $count   = $results->get()->count();
        $results = $results->paginate(10);

        $currentPage = $results->currentPage();
        $pageCount   = $results->lastPage();

        $firstItem = $results->firstItem();
        $lastItem  = $results->lastItem();

        
        $lastURL = URL::previous();
        $currentURL = URL::current();
        $lastURL = $lastURL==$currentURL ? url('/forums') : $lastURL;

        return view('forums.searchresultforum')
                ->with('postscount',$count)
                ->with('lastURL',$lastURL)
                ->with('keyword',$keyword)
                ->with('breadcrum',$breadcrum)
                ->with('posts',$results->appends(Request::except('page')))
                ->with('firstitem',$firstItem)
                ->with('lastitem',$lastItem)
                ->with('currentpage',$currentPage)
                ->with('pagecount',$pageCount)
                ->with('old',$input);

    }

    public function searchForumGet()
    {
        $count = 0;
        $keyword = '';
        $breadcrum = '';
        $results = [];
        $input=[];

        $lastURL = URL::previous();
        $currentURL = URL::current();
        $lastURL = $lastURL==$currentURL ? url('/forums') : $lastURL;

        return view('forums.searchresultforum')
            ->with('postscount',$count)
            ->with('keyword',$keyword)
            ->with('breadcrum',$breadcrum)
            ->with('posts',$results)
            ->with('lastURL',$lastURL)
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
  


    public function unsubscribe()
    {
        $request = Request::all();
        $validator = Validator::make($request, ['email' => 'required|email']);

        if($validator->fails())            
           return view('errors.404');

        $email = Request::get('email');

        $exists = UnsubscribedUsers::whereEmail($email)->first();
        if(!$exists){
            if(Request::isMethod('post')){
                if($email){
                    $unsubscribed = new UnsubscribedUsers;
                    $unsubscribed->email = $email;
                    $unsubscribed->save();                    
                }
                return redirect('unsubscribe?email='.$email.'&success=1');
            }
        }elseif(isset($request['success']) && $request['success'] == 1){
            Session::put('success', 'Your E-mail Notification Settings have been saved. <br> You will receive an e-mail confirming your new choices.');

            Mail::send('emails.unsubscribed-mail', ['email' => $email], function($message) use($email) {
                $message->from('contact@friendzsquare.com', 'FriendzSquare');
                $message->to($email)->subject('FriendzSquare Unsubscription');
            });

        }else{
            Session::put('success', 'You are already unsubscribed.');
        }
        return view('emails.unsubscribe')->with('email', $request['email']);
    }


    public function subscribe()
    {
        $request = Request::all();
        $validator = Validator::make($request, ['email' => 'required|email']);

        if($validator->fails())            
           return view('errors.404');

        $email = Request::get('email');

        if($email){
            $exists = UnsubscribedUsers::whereEmail($email)->delete();
            Session::put('success', 'You have been subscribed successfully.');
        }

        return view('emails.subscribe')->with('email', $email);

    }

}
