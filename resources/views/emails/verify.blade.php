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
  <body style="font-family: 'Open Sans', sans-serif;background-color:#e9faf8;width:100%;float:left;">
    <div style="width:590px;margin:30px auto;">
      <div style="background-color:#fff;padding:10px 15px;">
      <a href="#"><img src="{{url('/images/logo.jpg')}}" alt="" title=""/></a>
      </div>
      <div style="background-color:#5df7e3;height:8px; margin-bottom:12px;"></div>
      <div style="background-color:#fff;padding:10px 15px;margin-bottom:10px;">
      <p style="font-size:16px;font-weight:600;">Verfiy Your Email Account</p>
      <p style="font-size:14px;line-height:32px;">Thanks for creating an account with FriendzSquare. Please <a href="{{ URL::to('register/verify/'.$confirmation_code) }}">click here</a> to verify your email address.</p>
      
      
      <h4 style="text-align:center;margin:10px 0 8px;font-size:20px;">OR</h4>
      
      <p style="font-size:14px;line-height:32px;">You can paste the following link into your browser : {{URL::to('register/verify/'.$confirmation_code)}}</p>
      
      
      </div>
      <div style="background-color:#e9faf8;padding:10px 20px;text-align:center;margin-bottom:20px;">
      
      <p style="font-size:16px;margin-top:20px;margin-bottom:0;">This email has been sent to <a href="#" style="color:#000;font-weight:bold;">{{ $email }}</a></p>
      

      </div>
      <div style="background-color:#fff;padding:10px 15px;text-align:center;">
      <p style="font-size:12px;margin-top:15px;margin-bottom:15px;">If you have any questions, then please contact at <a href="mailto:contact@friendzsquare.com" style="color:#000;font-weight:bold;">contact@friendzsquare.com </a>
      </div>
      <div style="background-color:#5df7e3;height:8px; margin-bottom:15px;font-size:0px;">Copyright</div>   
    </div>
  </body>
</html>