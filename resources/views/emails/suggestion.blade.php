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
    <div style="background-color:#e9faf8;width:100%;float:left;">
    <div style="width:590px;margin:30px auto;">
      <div style="background-color:#fff;padding:10px 15px;">
      <a href="#"><img src="{{url('/images/logo.jpg')}}" alt="" title=""/></a>
      </div>
      <div style="background-color:#5df7e3;height:8px; margin-bottom:12px;"></div>
      <div style="background-color:#fff;padding:10px 15px;margin-bottom:10px;">
        <p style="font-size:16px;font-weight:600;">Suggestion from a user!</p><br>
        <p style='text-align:left;color:#0d0d0d;font-size:14px;font-weight:normal;line-height:19px;'>
          {{ $message_text }}
        </p>
        <p style='text-align:left;color:#0d0d0d;font-size:14px;font-weight:normal;line-height:19px;'>
          @if($usermail)
            From : {{$usermail}}
          @endif
        </p>
      </div>
      <div style="background-color:#e9faf8;text-align:center;margin-bottom:12px;">

      </div>

      <div style="background-color:#fff;padding:10px 15px;text-align:center;">
      <p style="font-size:12px;margin-top:15px;margin-bottom:15px;">If you have any questions, then please contact at <a href="mailto:contact@friendzsquare.com" style="color:#000;font-weight:bold;">contact@friendzsquare.com </a></p>
      </div>
      <div style="background-color:#5df7e3;height:8px; margin-bottom:15px;font-size:0px;">Copyright</div>  
    </div>
    </div>
  </body>
</html>