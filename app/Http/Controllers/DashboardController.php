<?php

namespace App\Http\Controllers;

use Auth, App\Feed, DB;
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

	public function index()
	{
/*		return view('dashboard.dashboard');
		return 'hello';*/
	}

	public function dashboard()
	{
        try{

            // $feeds = Feed::where('user_by', '=', Auth::User()->id)->get();
            $feeds = Feed::with('likesCount')->with('commentsCount')->with('user')->with('likes')->with('comments')
            ->orderBy('news_feed.id','DESC')
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

 

}
