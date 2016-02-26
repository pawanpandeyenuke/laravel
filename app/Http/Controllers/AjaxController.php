<?php

namespace App\Http\Controllers;
use App\State, App\City;
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
									<span>Comment</span>
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
