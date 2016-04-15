<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

// use App\Http\Requests;
use App\Library\ContactsImporter;
use Google_Client, Auth, App\User, App\Friend;
use Request, Session, Validator, Input, Cookie;

class ContactImporter extends Controller
{

    public $google_client_id = '401736044025-5jdpu98eqgvb1h0g60s21u6o5sofb9e3.apps.googleusercontent.com';

    public $google_client_secret = 'wUWM9ObLfOZVkR7-nXQvtb6V';

    public $google_redirect_uri = 'http://development.laravel.com/google/client/callback';

    public function inviteFriends()
    {
 
        $client = new Google_Client();
        $client -> setApplicationName('FriendzSquare');
        $client -> setClientid($this->google_client_id);
        $client -> setClientSecret($this->google_client_secret);
        $client -> setRedirectUri($this->google_redirect_uri);
        $client -> setAccessType('offline');
        $client -> setScopes('https://www.google.com/m8/feeds');

        $googleImportUrl = $client -> createAuthUrl();

        if(Request::isMethod('post')){

            $requestemails = Request::get('emails');

            if($requestemails){

                $emailsarray = explode(',', $requestemails);
            
                foreach ($emailsarray as $key => $value) {
                        $validator=null;
                    $validator = Validator::make($emailsarray, [
                        $key => 'required|email'
                    ]); 
                   $validator->each($key, ['required', 'email']);
                   
                   if($validator->fails()) {
                           return redirect()->back()->withInput()->with('error', 'Please check email addresses entered and try again.');   
                 
                    }
                }
                    
                $existingUser = array();
                $nonExistingUser = array();
                foreach ($emailsarray as $value) {                
                    if($value != Auth::User()->email){
                        $userData = User::where('email', '=', $value)->get()->toArray();
                        if(!empty($userData))
                            $existingUser[] = $value;
                        else
                            // $nonExistingUser[] = $value;
                            $message = 'Hi, Take a look at this cool social site "FriendzSquare!"';
                            self::mail($value, $message, 'Invitation', 'emails.invite');
                    }
                }

                $friends = array();
                foreach ($existingUser as $value) {

                    $id = User::where('email', '=', $value)->value('id');
                    $frienddata = Friend::where('user_id', '=', Auth::User()->id)
                                        ->where('friend_id', '=', $id)
                                        ->where('status', '=', 'Accepted')
                                        ->get()
                                        ->toArray();

                    if(empty($frienddata)){
                        $message = 'Please add me on FriendzSquare!';
                        self::mail($value, $message, 'Friend Request', 'emails.friend');
                    }
                }

                Session::put('success', 'Invitation sent successfully.'); 
            }else{
                return redirect()->back()->with('error', 'Please enter an email address.');  
            }
        }

        return view('invite-friends.invite')
                ->with('googleImportUrl', $googleImportUrl);

    }

 
    public function inviteContactList(){

        $request = Request::all();

        //google response with contact. We set a session and redirect back
        if (isset($request['code'])) {
            $auth_code = $request["code"];
            Session::set('google_code', $auth_code);
            $googlecode = Session::get('google_code');
            // header('Location: ' . $google_redirect_uri);
        }

        if(isset($googlecode)) {
            $auth_code  = Session::get('google_code');
            $max_results = 200;
            $fields = array(
                'code'=>  urlencode($auth_code),
                'client_id'=>  urlencode($this->google_client_id),
                'client_secret'=>  urlencode($this->google_client_secret),
                'redirect_uri'=>  urlencode($this->google_redirect_uri),
                'grant_type'=>  urlencode('authorization_code')
            );

            $post = '';
            foreach($fields as $key=>$value)
                $post .= $key.'='.$value.'&';

            $post = rtrim($post,'&');
            $result = self::curl('https://accounts.google.com/o/oauth2/token',$post);
            $response =  json_decode($result);
            // echo '<pre>';print_r($response);die;
            $google_contacts = '';
            if(isset($response->access_token))
            {
                $accesstoken = $response->access_token;
                $url = 'https://www.google.com/m8/feeds/contacts/default/full?max-results='.$max_results.'&alt=json&v=3.0&oauth_token='.$accesstoken;
                $xmlresponse =  self::curl($url);           
                $contacts = json_decode($xmlresponse,true);
                $return = array();
                // echo '<pre>';print_r($contacts);die;
                if (!empty($contacts['feed']['entry'])) {
                    foreach($contacts['feed']['entry'] as $contact) {
                       //retrieve Name and email address  
                        $return[] = array (
                            'name'=> $contact['title']['$t'],
                            'email' => $contact['gd$email'][0]['address'],
                        );
                    }               
                }           
                $google_contacts = $return;
                Session::forget('google_code');     
            }    
        }

        if(Request::isMethod('post')){

            $request = Request::all();

            unset($request['_token']);
            unset($request['selectall']);

            // echo '<pre>';print_r($request);die; 
            if($request){

                $existingUser = array();
                $nonExistingUser = array();
                foreach ($request as $value) {                
                    if($value != Auth::User()->email){
                        $userData = User::where('email', '=', $value)->get()->toArray();
                        if(!empty($userData))
                            $existingUser[] = $value;
                        else
                            // $nonExistingUser[] = $value;
                            $message = 'Hi, Take a look at this cool social site "FriendzSquare!"';
                            self::mail($value, $message, 'Invitation', 'emails.invite');
                    }
                }

                $friends = array();
                foreach ($existingUser as $value) {
                    // $friends[$value]
                    $id = User::where('email', '=', $value)->value('id');

                    $frienddata = Friend::where('user_id', '=', Auth::User()->id)
                                        ->where('friend_id', '=', $id)
                                        ->where('status', '=', 'Accepted')
                                        ->get()
                                        ->toArray();

                    if(empty($frienddata))
                        // $friends[] = $value;
                        $message = 'Please add me on FriendzSquare!';
                        self::mail($value, $message, 'Friend Request', 'emails.friend');

                }

                return redirect('invite-friends')->with('success', 'Invitation sent successfully!');
            }else{
                return redirect()->back()->withInput()->with('error', 'No contact was selected. please try again!');
            }
        }

        return view('invite-friends.contact-list')
                ->with('contacts', $google_contacts);

    }


    public function mail($email = '', $message, $subject, $type) {
  
        $mailsent = mail($email, $subject, $message);

/*        if($email != ''){
            Mail::send($type, ['email'=>$email,'message_text'=> $message], function ($m)  {
                $m->from('no-reply@friendzsquare.com', 'FriendzSquare!');
                $m->to($email)->subject($subject);
            });
        }*/
    }

    public function curl($url, $post = "") {
        $curl = curl_init();
        $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
        curl_setopt($curl, CURLOPT_URL, $url);
        //The URL to fetch. This can also be set when initializing a session with curl_init().
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        //The number of seconds to wait while trying to connect.
        if ($post != "") {
            curl_setopt($curl, CURLOPT_POST, 5);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        }
        curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
        //The contents of the "User-Agent: " header to be used in a HTTP request.
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
        //To follow any "Location: " header that the server sends as part of the HTTP header.
        curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);
        //To automatically set the Referer: field in requests where it follows a Location: redirect.
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        //The maximum number of seconds to allow cURL functions to execute.
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        //To stop cURL from verifying the peer's certificate.
        $contents = curl_exec($curl);
        curl_close($curl);
        return $contents;
    }


    public function hotmailCallback()
    {

        // $request = Request::get('access_token');
        $request = Request::all();

        return view('invite-friends.invite');
        // echo '<pre>';print_r($request);die; 

    }


}
