<?php

namespace App\Http\Controllers;
use App\State, App\City, App\Like, App\Comment, App\User;
use Illuminate\Http\Request;
use Session, Validator, Cookie;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\Feed, Auth;
use \Exception;

class AjaxController extends Controller
{

	//Handling posts
	public function posts()
	{
		try
		{
			$arguments = Input::all();
			$model = new Feed;

			if( $arguments ){

				$user = Auth::User();				
				$arguments['user_by'] = $user->id;
	
				if( empty($arguments['message']) && empty($arguments['image']))
					throw new Exception('Post something to update.');

				$file = Input::file('image');

				if( isset($arguments['image']) && $file != null ){

					$image_name = time()."_POST_".strtoupper($file->getClientOriginalName());
					$arguments['image'] = $image_name;
					$file->move('uploads', $image_name);

				}

				$feed = $model->create( $arguments );
				
				if( !$feed )
					throw new Exception('Something went wrong.');

				$name = Auth::User()->first_name.' '.Auth::User()->last_name;
				$time = $feed->updated_at->diffForHumans();
				$picture = $feed->image;
				$message = $feed->message;

if(!empty($feed->message)){ 
$message = <<<message
<p>$message</p>
message;
}else{
$message = '';
}

if(!empty($feed->image)){ 
$picture = <<<image
	<div class="post-img-cont">
		<img src="uploads/$picture" class="post-img img-responsive">
	</div>
image;
}else{
$picture = '';
}

$postHtml = <<<postHtml

			<div class="single-post" data-value="$feed->id" id="post_$feed->id">
				<div class="post-header">
					<div class="row">
						<div class="col-md-7">
							<a href="#" title="" class="user-thumb-link">
								<span class="small-thumb" style="background: url('uploads/1456394309_POST_XZY0484L(1.JPG');"></span>
								$name
							</a>
						</div>
						<div class="col-md-5">
							<div class="post-time text-right">
								<ul>
									<li>
										<span class="icon flaticon-time">
											$time
										</span>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="post-data">
					$message
					$picture
				</div>
				<div class="post-footer">
					<div class="post-actions">
						<ul>
							<li>
								<div class="like-cont">
									<input type="checkbox" name="" id="checkbox<?php echo $feed->id ?>" class="css-checkbox like"/>
									<label for="checkbox<?php echo $feed->id ?>" class="css-label">
										<span>Like</span>
									</label>
								</div>
							</li>
							<li>
								<a href="#AllComment" class="popup">
									<span class="icon flaticon-interface-1"></span> 
									<span class="commentcount">Comment</span>
								</a>
							</li>
						</ul>
					</div>
					<div class="post-comment-cont">
						<div class="post-comment">
							<div class="row">
								<div class="col-md-10">
									<textarea type="text" class="form-control comment-field" placeholder="Type here..."></textarea>
								</div>
								<div class="col-md-2">
									<button type="button" class="btn btn-primary btn-full comment">Post</button>
								</div>
							</div>
						</div>
						<div class="comments-list">
							<ul>
							</ul>
						</div>
					</div>
				</div>
			</div>
postHtml;

echo $postHtml;

			}

		}catch( Exception $e ){

			return $e->getMessage();

		}		

		exit;
	}


	//Get comment box
	public function getCommentBox()
	{
		$arguments = Input::all();

		try{

			$likes = Like::where('feed_id', '=', $arguments['feed_id'])->get();
			$comments = Comment::where('feed_id', '=', $arguments['feed_id'])->get();
			$feed = Feed::where('id', '=', $arguments['feed_id'])->get();

			$user = User::find($feed[0]->user_by);
			$feedPostUserName = $user->first_name.' '.$user->last_name;
 
			$image = $feed[0]->image;
			$message = $feed[0]->message;
			$time = $feed[0]->updated_at->diffForHumans();


			$likedata = Like::where(['user_id' => Auth::User()->id, 'feed_id' => $arguments['feed_id']])->get(); 
			$checked = isset($likedata[0]) ? 'checked' : '';

			$likescountData = Like::where(['feed_id' => $arguments['feed_id']])->get();
			$likescount = count($likescountData);
			if($likescount > 0){
				$likespan = "<span>$likescount Likes</span>";
			}else{
				$likespan = "<span>Like</span>";
			}

			$commentscountData = Comment::where(['feed_id' => $arguments['feed_id']])->get();
			$commentscount = count($commentscountData);
			if($commentscount > 0){
				$commentspan = "<span>$commentscount Comments</span>";
			}else{
				$commentspan = "<span>Comment</span>";
			}

			// echo '<pre>';print_r();die('pawan');

$commentshtml = "";
foreach($comments as $data){

	$commentedBy = $data->commented_by;
	$username = User::find($commentedBy);
	$name = $username->first_name.' '.$username->last_name;

$commentshtml .= '
<li>
	<span class="user-thumb" style="background: url(images/user-thumb.jpg)"></span>
	<div class="comment-title-cont">
		<div class="row">
			<div class="col-sm-6">
				<a href="#" title="" class="user-link">'.$name.'</a>
			</div>
			<div class="col-sm-6">
				<div class="comment-time text-right">'.$time.'</div>
			</div>
		</div>
	</div>
	<div class="comment-text">'.$data->comments.'</div>
</li>';
}

$getcomment = <<<getcomment

<div id="AllComment" class="post-list">
	<div class="container">
		<div class="row">
			<div class="col-sm-8 pop-post-left-side">
				<div class="single-post">
					<div class="pop-post-header">
						<div class="post-header">
							<div class="row">
								<div class="col-md-7">
									<a href="#" title="" class="user-thumb-link">
										<span class="small-thumb" style="background: url('images/user-thumb.jpg');"></span>
										$feedPostUserName
									</a>
								</div>
								<div class="col-md-5">
									<div class="post-time text-right">
										<ul>
											<li><span class="icon flaticon-time">$time</span></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<div class="pop-post-text clearfix postsajax">
							<p class="postsonajax">$message</p>
						</div>
					</div>
					
					<div class="post-data pop-post-img">
						<img src="uploads/$image" class="pop-img">
					</div>
					<div class="post-footer pop-post-footer">
						<div class="post-actions">
							<ul>
								<li>
									<div class="like-cont">
										<input type="checkbox" name="checkboxG4" id="checkboxG4" class="css-checkbox" $checked/>
										<label for="checkboxG4" class="css-label">
											$likespan
										</label>
									</div>
								</li>
								<li>
									<span class="icon flaticon-interface-1">
									</span>
								 	$commentspan
								 </li>
							</ul>
						</div><!--/post actions-->
					</div><!--pop post footer-->
				</div><!--/single post-->
			</div>
			<div class="col-sm-4 pop-comment-side-outer">
				<div class="pop-comment-side">
					<div class="post-comment-cont">
						<div class="comments-list">
							<ul>
							$commentshtml
							</ul>
						</div>
					</div>
				</div>

				<div class="pop-post-comment post-comment">
					<div class="emoji-field-cont">
						<textarea type="text" class="form-control comment-field" data-emojiable="true" placeholder="Type here..."></textarea>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
<script src="js/jquery.nicescroll.min.js"></script>
<script>
$('.pop-comment-side .post-comment-cont').niceScroll();
var postsonajax = $('.postsonajax').html();
if(postsonajax == ''){
	$('.postsajax').remove();
}
</script>
getcomment;

			// print_r($arguments['feed_id']);die;		
			echo $getcomment;

		}catch(Exception $e){
			return $e->getMessage();
		}

		exit;

 	}



	public function postcomment()
	{
			try
			{
				$arguments = Input::all();
				// print_r($arguments);die('pawan');
				$comments = new Comment;

				$validator = Validator::make($arguments, $comments->rules, $comments->messages);

				if($validator->fails()) {
					
					throw new Exception($this->getError($validator));

				}else{

					$user = User::find($arguments['commented_by']);
					if( !$user )
						throw new Exception('No record found of the user.');						

					$feed = Feed::find($arguments['feed_id']);
					if(!$feed)
						throw new Exception('The post may have expired or does not exist.');
					
					$comments = new Comment;
					$model = $comments->create($arguments);

					$userid = Auth::User()->id;
					$username = Auth::User()->first_name.' '.Auth::User()->last_name;
					$comment = $model->comments;
$comment = <<<comments
<li>
	<span style="background: url('images/user-thumb.jpg');" class="user-thumb"></span>
	<a class="user-link" title="" href="profile/$userid">$username</a>
	<div class="comment-text">$comment</div>
</li>
comments;

					echo $comment;
				}

			}catch(Exception $e){

				return $e->getMessage();

			}

		exit;
	}



	public function loadposts()
	{

/*		$input = Input::all();
        $per_page = 5;

        $feeds = Feed::with('likesCount')->with('commentsCount')->with('user')->with('likes')->with('comments')
        ->orderBy('news_feed.id','DESC')
        ->take($per_page)
        ->get();

		return view('dashboard.newsfeed')->with(['feeds' => $feeds, 'page' => $input['page']]);*/

	}


	//Get states
	public function getStates()
	{
		$input = Input::all();
		$statequeries = State::where(['country_id' => $input['countryId']])->get();		
		$states = array('<option value="">State</option>');
		foreach($statequeries as $query){			
			$states[] = '<option value="'.$query->state_id.'">'.$query->state_name.'</option>';
		}		
		echo implode('',$states);
	}


	//Get cities
	public function getCities()
	{
		$input = Input::all();
		$cityqueries = City::where(['state_id' => $input['stateId']])->get();
		$city = array('<option value="">City</option>');
		foreach($cityqueries as $query){			
			$city[] = '<option value="'.$query->city_id.'">'.$query->city_name.'</option>';
		}		
		echo implode('',$city);
	}


}
