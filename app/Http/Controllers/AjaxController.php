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

			$validate = Validator::make($arguments, [
				'message' => 'required',
				'_token' => 'required'
			]);
			

			if(isset($arguments['image'])){

				return 'thanks!';

			}


			if( $validate->fails() ){
				throw new Exception('Write something to post..');
			}else{
				
				$user = Auth::User();
				
				$arguments['user_by'] = $user->id;
				$feed = $model->create( $arguments );

				if( !$feed )
					throw new Exception('Something went wrong.');
					
				return $feed;

			}


		}catch( Exception $e ){

			return $e->getMessage();

		}
		
		return $input;
		
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
