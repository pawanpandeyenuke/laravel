@extends( isset($_GET['mobile']) ? 'layouts.noheaderfooter' : 'layouts.app')

@section('title', 'Privacy Policy')

<!-- Main Content -->
@section('content')
<div class="container">
	<div class="row">
	    <div class="col-md-10 col-md-offset-1 privacy">
	    <div class="privacy-container">
	    	<h3 class='text-center'><b>Privacy Policy</b></h3>
	        FriendzSquare application on any internet enabled device is collectively referred here in this Privacy Policy as "FriendzSquare" ("We"). <br> <br>

			FriendzSquare is committed to protecting your privacy and this Privacy Policy describes how we handle your private and personal information. This privacy applies to FriendzSquare website and applications which are owned and operated by Connect All Pte ltd. By using FriendzSquare website and application, you allow us to use your information in accordance with this Privacy Policy. We will not use or share your personal information with anyoneexcept as described in this Privacy Policy. This Privacy Policy does not apply to any information we collect from other sources.<br><br>
			<ol>
				<li>Collection of information <br>
					The information you provide us maybe through:<br>

					 1) Registration <br>
					 2) Profile creation <br>
					 3) Newsfeed <br>
					 4) Participating on the forums <br>
					 5) Giving suggestions. <br>
					 6) Participating in Chat rooms. <br>
					The information will be made public on our website &amp;/or applications and hence will longer be deemed to be anonymous to FriendzSquare.<br><br>
				</li>
				<li> Posting of pictures <br>
					User can post pictures on newsfeed or forums or chat rooms. Your picture will be public henceforth.<br><br>
				</li>
				<li> Newsfeed <br>
					User can post status message or comment on other user’s posts, which will all be public.<br><br>
				</li>
				<li> Participating in Forums or Chat rooms <br>
					While user needs to be registered to post in forums or chat rooms, the user can view forums posts without registering.<br><br>
				</li>
				<li> Platform for connecting users <br>

					FriendzSquare is a platform for users to interact and discuss on various topics on Forums or Chat rooms.<br><br>
				</li>
				<li> Your responsibility <br>

					You agree to be responsible for all activities done on friendzsquare.com or FriendzSquare application from your registration user id.<br><br>
				</li>
				<li> Accurate information <br>

					You agree that all information you provide on FriendzSquare shall not be incorrect, obscene, abusive, racist, illegal or fraudulent.<br><br>
				</li>
				<li> We don't save user's usage statistics data on the servers <br>

					We only store information you enter on FriendzSquare website or application. We currently do not save user's usage statistics data such as demographic patterns, pages viewed, access times, number of unique visitors, browser software, screen resolution, DOM local storage, plugins access times, IP address, HTTP headers, phone type, geo-location etc.<br><br>
				</li>
				<li> Data Security <br>

					All passwords used will be encrypted to protect the security and integrity of your personal information against unauthorized access and disclosure. We will take reasonable steps to protect your information from misuse.<br><br>
				</li>
				<li> Disclosure <br>
					<ul>
						<li>
							All information input by the user during registration will be treated as strictly confidential and we will not disclose or share such confidential information to /with any external organization, other than to Governmental or law enforcing agencies, but we will only do so under proper authority.
						</li>
						<li>
							We also retain the right to use your personal information in any investigation or judicial process relating to fraud during the period FriendzSquare possess this information.
						</li>
						<li>
							We will not either sell or rent user's personal information to third parties without user's explicit consent.
						</li>
						<li>
							We may also disclose personal information to enforce our policies, respond to claims that a posting or other content violates other's rights, or to protect anyone's rights, property or safety.
						</li>
					</ul>
				</li><br>
				<li> Modification/deletion of profile <br>

					You can see, modify or erase your personal information or your profile by reviewing your profile. Your profile will be immediately be deleted on your submitting the delete button.<br><br>
				</li>
				<li> Modification/deletion of posts <br>

					You can see, modify or erase your posts on forums and it will be deleted immediately on your submitting the delete button.<br><br>
				</li>
				<li> Changes to our Privacy Policy <br>

					FriendzSquare may amend this Privacy Policy anytime without any intimation and the amended Privacy Policy will be effective immediately on posting. If you continue to use the service then it will be deemed as your acceptance to the changed terms. Hence, if you do not agree to the changes to the Privacy Policy, then stop using the service.<br><br>
				</li>
				<li> Governing Law &amp; Jurisdiction <br>

					This Privacy Policy shall be governed in accordance with the laws of Singapore.<br><br>
				</li>
			</ol>
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
	        </ul>
	    </div>
	</footer>
<?php } ?>
@endsection