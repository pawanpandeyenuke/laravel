<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>FS Mailer</title>

   <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body style="font-family: 'Open Sans', sans-serif;background-color:#e9faf8;">
		<div style="width:571px;margin:30px auto;">
			<div style="background-color:#fff;padding:10px 15px;">
			<a href="#"><img src="{{url('/images/logo.png')}}" alt="" title=""/></a>
			</div>
			<div style="background-color:#5df7e3;height:8px; margin-bottom:12px;"></div>
			<div style="background-color:#fff;padding:10px 15px;margin-bottom:10px;">
			<p style="font-size:16px;">Hi,</p>
			<p style="font-size:16px;">{{ $username }} has joined FriendzSquare. On FriendzSquare you can make new friends by joining <strong>Chat rooms</strong>, participate in <strong>Forums</strong>, post comments on <strong>Newsfeed</strong> and much more.</p>
			<p style="font-size:16px;margin-bottom:40px;">Explore and grow your social network.</p>
				<div style="margin-bottom:20px;text-align:center;padding:10px;">
				<a href="{{url('profile/'.$id)}}" style="background-color:#5df7e3;color:#000;padding:10px 40px;margin-bottom:20px;text-decoration:none;border-radius:3px;font-weight:600;">Accept Invite</a>
				</div>	
			</div>
			<div style="background-color:#e9faf8;padding:10px 20px;text-align:center;margin-bottom:20px;">
			<p style="font-size:16px;margin-top:20px;margin-bottom:0;">This email has been sent to <a href="mailto:{{$email}}" style="color:#000;font-weight:bold;">{{ $email }}</a></p>
			<p style="font-weight:bold;">It is an invitation to connect</p>
			<a href="{{ url('unsubscribe?email='.$email) }}" style="background-color:#5df7e3;color:#000;padding:10px 20px;margin-bottom:25px;text-decoration:none;font-weight:bold;">Unsubscribe</a>
			</div>
			<div style="background-color:#fff;padding:10px 15px;text-align:center;">
			<p style="font-size:12px;margin-top:15px;margin-bottom:15px;">If you have any questions, then please contact at <a href="mailto:contact@friendzsquare.com" style="color:#000;font-weight:bold;">contact@friendzsquare.com </a>
			</div>
			<div style="background-color:#5df7e3;height:8px; margin-bottom:15px;"></div>	
		</div>
  </body>
</html>