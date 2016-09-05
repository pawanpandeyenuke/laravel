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
        <p style="font-size:16px;font-weight:600;">Hi {{ $user_name }},</p>
        <p style='text-align:left;color:#0d0d0d;font-size:14px;font-weight:normal;line-height:19px;'>
          {{ $current_data }}
        </p>
        <p style='text-align:left;color:#0d0d0d;font-size:14px;font-weight:normal;line-height:19px;'>
          {{ $type }} {{ $post_message }}
        </p>
        <br>
        <p  style='text-align:left;color:#0d0d0d;font-size:14px;font-weight:normal;line-height:19px;'>
          <a class="btn btn-primary btn-lg icon-btn" title="" href="{{ $post_url }}" style="text-decoration: none;-moz-user-select: none; background-image: none; border: 1px solid transparent; border-radius: 4px; cursor: pointer; display: inline-block; font-size: 14px; font-weight: normal; line-height: 1.42857; margin-bottom: 0; padding: 6px 12px; text-align: center; vertical-align: middle; white-space: nowrap; background: #a0f0e6 none repeat scroll 0 0; color: #000; font-size: 15px;"><i class="flaticon-draw"></i>Go To {{ $linktype }}</a>
          <br>
        </p>

      </div>
      <div style="background-color:#e9faf8;padding:10px 20px;text-align:center;margin-bottom:20px;">
      
        <p style="font-size:16px;margin-top:20px;margin-bottom:0;">This email has been sent to <a href="#" style="color:#000;font-weight:bold;">{{ $user_email }}</a></p>

    <p style="font-weight:bold;">Don’t want forum notification?</p>
      <a href="{{ url('forums/unsubscribe?token='.$access_token) }}" target='_blank' style="background-color:#5df7e3;color:#000;padding:10px 20px;margin-bottom:25px;text-decoration:none;text-transform:capitalize;font-weight:bold;">Unsubscribe</a>
      </div>

      <div style="background-color:#fff;padding:10px 15px;text-align:center;">
      <p style="font-size:12px;margin-top:15px;margin-bottom:15px;">If you have any questions, then please contact at <a href="mailto:contact@friendzsquare.com" style="color:#000;font-weight:bold;">contact@friendzsquare.com </a></p>
      </div>
      <div style="background-color:#5df7e3;height:8px; margin-bottom:15px;"></div>  
    </div>
  </body>
</html>