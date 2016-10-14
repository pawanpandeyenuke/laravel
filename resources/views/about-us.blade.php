@extends( isset($_GET['mobile']) ? 'layouts.noheaderfooter' : 'layouts.app')

@include('panels.meta-data')

@section('title', 'About Us')

<!-- Main Content -->
@section('content')
<div class="container">
	<div class="row">
	    <div class="col-md-10 col-md-offset-1 privacy">
	    <div class="privacy-container about-us">
	    	<h3 class='text-center'><b>About Us</b></h3>
	        <p>FriendzSquare is a unique platform to meet &amp; interact with other users of common interests in predefined chat rooms or forums. You can add users from chat rooms and engage in one-to-one chat or private group chat on FriendzSquare. You can keep up with your friends’ updates through newsfeed section where users can post pictures, comments or status messages on what they are up to.</p> 
            
            <br><h4>How it works?</h4>
            
            <p>A user can enter into any of the 26 predefined chatrooms such as Singles, politics, stock market, study questions etc. and exchange views on variety of topics as well as make new friends. Users can grow their social network by adding users from chat room into their friend list. There are also 26 predefined Forum and a user can choose to write their views/suggestions in any of these forums. Forums are open to all so there is exchange of information across different regions of world, with users coming with difference experience, backgrounds &amp; views.</p>		
	    </div>
	    </div>
  	</div>
</div>

<?php if( !isset($_GET['mobile']) ){ ?>
	<footer>
	 	<div class="container">
	        <ul class="f-links text-center list-inline">
	            <li>Copyright 2016 Connect All Pte Ltd.</li>
	            <li><a href="{{ url('terms') }}" title="Terms of Use">Terms of Use</a></li>
	            <li><a href="{{ url('privacy-policy') }}" title="Privacy Policy">Privacy Policy</a></li>
	            <li><a href="{{ url('about-us') }}" title="About Us">About Us</a></li>
	        </ul>
	    </div>
	</footer>
<?php } ?>
@endsection
