@extends('layouts.app')

<!-- Main Content -->
@section('content')
<div class="container">
	<div class="row">
	    <div class="col-md-10 col-md-offset-1 terms">
	    	<h3 class='text-center'><b>Terms of Use</b></h3>
	        FriendzSquare application on any internet enabled device is collectively referred here in this Terms of Use as "FriendzSquare" ("We"). <br> <br>

			This Terms of Use is applicable to FriendzSquare and by accessing FriendzSquare you consent to accept the following terms as specified below. Furthermore, by accepting these Terms of Use, you also agree to be bound by the FriendzSquare’s Privacy Policy. <br>

			<ol>
				<li>FriendzSquare as a platform <br>
					<ul>
						<li>By using FriendzSquare through a website www.friendzsquare.com &amp;/or application on any

						internet enabled device, its deemed to be considered that you understand that Friendz

						Square is merely platform that facilitates communication between users on FriendzSquare

						and any interaction between users is solely between them and FriendzSquare don't take

						any part in that.
						</li>
						<li>
						FriendzSquare a mere platform for users to connect with other users and we have no active

						role in any subsequent communication. </li>
						<li>
						For all the information you share on FriendzSquare, we will consider it our non-exclusive,

						worldwide, perpetual, irrevocable, royalty-free right, to display such content worldwide.</li>
					</ul>
				</li>
				<li> The information you provide us <br> <br>

				The information you provide us maybe through: 1) Registration 2) Profile creation 3) Newsfeed 4) Participating on the forums 5) Giving suggestions. 6) Participating in Chat rooms. 
				</li>
				
				<li> No Liability on FriendzSquare <br>
					<ul>
						<li>This platform does not vouch the accuracy of the information of the user and hence we call

						for you to exercise reasonable due diligence while interacting with other users on our site,

						with respect to any information exchanged.
						</li>
						<li>
							You further agree that we are not liable for any loss of money, goodwill, reputation, or

							consequential damages arising out of your use of the site.
						</li>
						<li>
							By accepting these Terms of Use, you agree not to hold FriendzSquare responsible or

							accountable for any misuse of your information by other users if any. This includes liability

							arising out of any attorney fees and court costs, made by any third party.
						</li>
					</ul>
				</li>
				<li> You have sole right to the information you share on FriendzSquare <br>
					<br>

					You hereby agree that you have the sole rights and title to the information that you provide and that hence is solely responsible and accountable for posting the information and that FriendzSquare is just platform to make your information public online.
				</li>
				<li> Posting of Content: <br><br>

					Shall not post any data which is incorrect, obscene, abusive, racist, illegal, defamatory or fraudulent.
				</li>
				<li> Your responsibility <br><br>

					You agree to be responsible for all activities done on FriendzSquare.com or FriendzSquare application from your registration user id.
				</li>
				<li> Your conduct: <br><br>

					You take the responsibility of your posts while using the FriendzSquare and any subsequent

					consequences due to your use of FriendzSquare. While posting on forums or chatting with other

					users in chatrooms you agree to the following points.

					You will not:
					<ul>
						<li>Post anything that is incorrect, obscene, abusive, racist, illegal, defamatory or fraudulent.</li>

						<li>Encroach on intellectual property of any third party.</li>

						<li>Use the information of the users to send them spam messages or phishing.</li>

						<li>Defame, harass, stalk, threaten or otherwise violate the legal rights and privacy of others</li>

						<li>Distribute any viruses, worms, defects, Trojan horses, or any items of a destructive nature

						that may harm FriendzSquare or its users.</li>

						<li>Use any robot, spider, site search/retrieval application, or other device to retrieve or index any portion of the Service. </li>

						<li>Submit content that falsely expresses or implies that such content is sponsored or endorsed by FriendzSquare;

						While FriendzSquare prohibits such conduct and posts, you understand and agree that you

						nonetheless may be exposed to such conduct and/or posts and that you use FriendzSquare at your own risk.</li>
					</ul>
				</li>
				<li> Sole right to refuse registration/delete profile <br><br>

					We are at sole discretion to refuse the registration to anyone or delete profile at any point in time in

					case the user engages in incorrect, fraudulent, illegal, obscene, abusive language or racist or engages

					in anything which is against the Terms of Use.
				</li>
				<li> Modification/deletion of posts <br><br>

					You can see, modify or erase your posts on forums and it will be deleted immediately on your submitting the delete button.
				</li>
				<li> Sole right to add/delete categories <br><br>

					We are at sole discretion to delete/add/edit a category and subcategories of forums and chat rooms, as we want to at any point in time.
				</li>
				<li> We have right to delete user's post <br><br>

					If user posts anything inconsistent with the terms of use, then we have the right to delete user's post.
				</li>
				<li> FriendzSquare is available worldwide <br><br>

					FriendzSquare website &amp;/or application is available worldwide for users of any age.
				</li>
				<li> Website links <br><br>

					FriendzSquare may have its users posting links of other websites. If the user views these links it will

					be at sole risk of the user and it will be user’s responsibility to take all protective measures to

					safeguard themselves against any viruses they may encounter by visiting those websites. FriendzSquare don’t endorse any website.
				</li>
				<li> Intellectual property rights <br><br>

					Connect All Pte Ltd. is the sole owner or lawful licensee of all the rights to FriendzSquare. By accepting the terms users agree that that they will not copy any information, structure database etc. of FriendzSquare.
				</li>
				<li> Governing Law &amp; Jurisdiction <br><br>
					This Terms of Use shall be governed in accordance with the laws of Singapore.
				</li>
				<li> Amendment to Terms of Use <br><br>

					FriendzSquare may amend this Terms of Use anytime without any intimation and the amended Terms of Use will be effective immediately on posting. If you continue to use the service then it will be deemed as your acceptance to the changed terms. Hence, if you do not agree to the changes to the Terms of Use, then stop using the service.
				</li>
			</ol>
	    </div>
  	</div>
</div>

<footer>
 	<div class="container">
        <ul class="f-links text-center list-inline">
            <li>&copy; 2016 FriendzSquare</li>       
            <li><a href="{{ url('terms') }}" title="">Terms of Use</a></li>
            <li><a href="{{ url('privacy-policy') }}" title="">Privacy Policy</a></li>
        </ul>
    </div>
</footer>
@endsection
