<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>FS Mailer</title>

   <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body style="font-family: 'Roboto', sans-serif;">
		<div style="width:680px;margin:0 auto;">
			<div style="background-color:#EDEDED;height:7px;width:680px;margin-bottom:3px;"></div>
			
			<div style="background-color:#A6FFED;padding:0 15px;height:78px;">
			<img src="{{url('/images/logo.jpg')}}" alt="" title=""/>
			</div>
			
			<div style="background-color:#EDEDED;height:7px;width:680px; margin-bottom:20px;"></div>
			
			<div style="background-color:#EDEDED;height:410px;padding:10px 10px 0 10px;">
				
				<div style="background-color:#fff;padding:10px 0px 20px 15px;margin-bottom:25px;">
				<p style="font-size:18px;">Hi,</p>
				
				<p style="font-size:18px;line-height: 1.42857;">{{ $username }} has joined FriendzSquare. On FriendzSquare you can make new friends by joining <strong>Chat rooms</strong>, participate in <strong>Forums</strong>, post comments on <strong>Newsfeed</strong> and much more. </p>
				
				<p style="font-size:18px;margin-bottom:30px;">Explore and grow your social network.</p>
				
				<a href="{{url('profile/'.$id)}}" style="background-color:#A6FFED;color:#000;padding:10px 20px;margin-bottom:20px;text-decoration:none;">Accept Invite<a/>
				
				</div>
				
				<!-- <p style="font-size:15px;margin:0;">This email has been sent to <a href="#" style="color:#0561C1;">{{ $userobj->email }}</a></p> -->
				<p style="font-size:15px;margin:0;">It is an invitation to connect. <a href="#" style="color:#0561C1;">Unsubscribe</a></p>
				<p style="font-size:15px;margin-top:0;margin-bottom:15px;">If you have any questions, then please contact at <a href="#" style="color:#0561C1;">contact@friendzsquare.com </a>	
				</p>

				<a href="#" style="text-decoration:none;font-size:15px;color:#0561C1;">You may see this email in browser</a>
				<a href="#" style="float:right;text-decoration:none;color:#0561C1;font-size:15px;">Forward to you friend</a>
				
			</div>
			
			
		</div>
   
  </body>
</html>