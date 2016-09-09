<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FriendzSquare Account Verification Link</title>
   <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
  </head>
  <body style="font-family: 'Open Sans', sans-serif;background-color:#e9faf8;">
    <div style="background-color:#e9faf8;width:100%;float:left;">
      <div style="width:590px;margin:30px auto;">
        <div style="background-color:#fff;padding:10px 15px;">
          <a href="{{URL('/')}}" title="Go to FriendzSquare" ><img src="{{url('/images/logo.jpg')}}" alt="FriendzSquare" /></a>
        </div>
        <div style="background-color:#5df7e3;height:8px; margin-bottom:12px;"></div>
        <div style="background-color:#fff;padding:10px 15px;margin-bottom:10px;">
          <p style="font-size:15px;margin-bottom:10px;">Hi {{$fullname}},</p>
          <p style="font-size:14px;line-height:20px;margin-bottom:10px;">Thanks for creating an account with FriendzSquare. Please <a title="Verify Now" href="{{ URL::to('register/verify/'.$confirmation_code) }}">click here</a> to verify your email address.</p>
          <p style="text-align:center;margin:10px 0 8px;font-size:20px;">OR</p>
        
          <p style="font-size:14px;line-height:20px;">You can paste the following link into your browser : {{URL::to('register/verify/'.$confirmation_code)}}</p>

        </div>
        <div style="background-color:#e9faf8;padding:10px 20px;text-align:center;margin-bottom:20px;">
          <p style="font-size:16px;margin-top:20px;margin-bottom:0;">This email has been sent to <a href="{{$email}}" title="you registred email" style="color:#000;font-weight:bold;">{{ $email }}</a></p>
        </div>
        <div style="background-color:#fff;padding:10px 15px;text-align:center;">
          <p style="font-size:12px;margin-top:15px;margin-bottom:15px;">If you have any questions, then please contact at <a href="mailto:contact@friendzsquare.com" style="color:#000;font-weight:bold;">contact@friendzsquare.com </a></p>
        </div>
        <div style="background-color:#5df7e3;height:8px; margin-bottom:15px;font-size:0px;"></div>   
      </div>
    </div>
  </body>
</html>