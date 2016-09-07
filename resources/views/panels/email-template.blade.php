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
        <p style="font-size:16px;font-weight:600;">Hi <?= ucwords($user_name) ?>,</p><br>
        <p style='text-align:left;color:#0d0d0d;font-size:14px;font-weight:normal;line-height:19px;'>
          {{ $current_data }}
        </p>
        <p style='text-align:left;color:#0d0d0d;font-size:14px;font-weight:normal;line-height:19px;'>
          {{ $type }} {{ $post_message }}
        </p>
        <br>
        <p style="text-align:left;color:#0d0d0d;font-size:14px;font-weight:normal;line-height:19px;">
          <a title="" href="{{ $post_url }}" style="text-decoration: none;-moz-user-select: none; background-image: none; border: 1px solid transparent; border-radius: 4px; cursor: pointer; display: inline-block; font-size: 14px; font-weight: normal; line-height: 1.42857; margin-bottom: 0; padding: 6px 12px; text-align: center; vertical-align: middle; white-space: nowrap; background: #a0f0e6 none repeat scroll 0 0; color: #000; font-size: 15px;">Go To {{ $linktype }}</a>
          <br>
        </p>

      </div>
      
      <div style="background-color:#5df7e3;height:8px; margin-bottom:15px;font-size:0px;">Copyright</div>  
    </div>
  </body>
</html>