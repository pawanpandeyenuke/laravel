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
  <body style="font-family: 'Open Sans', sans-serif;background-color:#e9faf8;">
    <div style="background-color:#e9faf8;width:100%;float:left;">
		<div style="width:680px;margin:0 auto;">
			<div style="background-color:#e9e9e9;height:7px;width:680px;margin-bottom:3px;"></div>
			
			<div style="background-color:#A6FFED;padding:0 10px;height:78px;">
			<img src="{{url('/images/logo.jpg')}}" alt="" title=""/>
			</div>
			
			<div style="background-color:#e9e9e9;height:7px;width:680px; margin-bottom:20px;"></div>
			
			<div style="background-color:#e9e9e9;height:370px;padding:10px 10px 0 10px;">
				
				<div style="background-color:#fff;padding:10px 0px 20px 15px;margin-bottom:25px;">
				<p style="font-size:18px;">Hi,</p><br>
				
				<p style="font-size:18px;line-height: 1.42857;">You have been unsubscribed from FriendzSquare. If you want to subscribe again, please <a href="{{ url('subscribe?email='.$email) }}" target="_blank">click here</a>.</p>
			
				</div>

				<p style="font-size:15px;margin-top:0;margin-bottom:15px;">If you have any questions, then please contact at <a href="mailto:contact@friendzsquare.com" style="color:#0561C1;">contact@friendzsquare.com </a>	
				</p>

				<a href="#" style="text-decoration:none;font-size:15px;color:#0561C1;">You may see this email in browser</a>
				<a href="#" style="float:right;text-decoration:none;color:#0561C1;font-size:15px;">Forward to you friend</a>
				
			</div>
			
			
		</div>
   	</div>
  </body>
</html>