    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>[SUBJECT]</title>
      <style type="text/css">
      body {
       padding-top: 0 !important;
       padding-bottom: 0 !important;
       padding-top: 0 !important;
       padding-bottom: 0 !important;
       margin:0 !important;
       width: 100% !important;
       -webkit-text-size-adjust: 100% !important;
       -ms-text-size-adjust: 100% !important;
       -webkit-font-smoothing: antialiased !important;
     }
     .tableContent img {
       border: 0 !important;
       display: block !important;
       outline: none !important;
     }
     a{
      color:#382F2E;
    }

    p, h1{
      color:#382F2E;
      margin:0;
    }
 p{
      text-align:left;
      color:#999999;
      font-size:14px;
      font-weight:normal;
      line-height:19px;
    }

    a.link1{
      color:#382F2E;
    }
    a.link2{
      font-size:16px;
      text-decoration:none;
      color:#ffffff;
    }

    h2{
      text-align:left;
       color:#222222; 
       font-size:19px;
      font-weight:normal;
    }
    div,p,ul,h1{
      margin:0;
    }

    .bgBody{
      background: #ffffff;
    }
    .bgItem{
      background: #ffffff;
    }

    </style>
<script type="colorScheme" class="swatch active">
{
    "name":"Default",
    "bgBody":"ffffff",
    "link":"382F2E",
    "color":"999999",
    "bgItem":"ffffff",
    "title":"222222"
}
</script>
  </head>
  <body paddingwidth="0" paddingheight="0"   style="padding-top: 0; padding-bottom: 0; padding-top: 0; padding-bottom: 0; background-repeat: repeat; width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased;" offset="0" toppadding="0" leftpadding="0">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableContent bgBody"  style='font-family:Helvetica, Arial,serif;'>

      
      <tr><td height='35'></td></tr>

      <tr>
        <td>
          <table width="600" border="0" cellspacing="0" cellpadding="0" class='bgItem'>
            <tr>
              <td width='40'></td>
              <td width='520'>
                <table width="520" border="0" cellspacing="0" cellpadding="0">

<!-- =============================== Header ====================================== -->           
                  <tr><td><h3>Subject: Request for password change on FriendzSquare</h3></td></tr>
                  <tr>
                    <td height='75' style="padding-bottom: 10px; border-bottom: 3px solid #A0F0E8;">
                      <a href="{{url('/')}}" title="FriendzSquare" target="_blank">
                        <img src="{{url('/images/logo.png')}}" alt="FriendzSquare">
                      </a>
                    </td>
                  </tr>

<!-- =============================== Body ====================================== -->

                  <tr>
                    <td class='movableContentContainer' valign='top' style="padding-top: 20px;">

                      <div class='movableContent'>
                        <table width="520" border="0" cellspacing="0" cellpadding="0" align="left">
                          <tr>
                            <td valign='top'>
                              <div class="contentEditableContainer contentTextEditable">
                                <div class="contentEditable">

                                  <p style='font-family:Georgia,Time,sans-serif;font-size:20px;color:#0d0d0d;line-height: 24px;'>Hi {{ $user->first_name.' '.$user->last_name }},</p>
                                  <br>
                                 
                                   <p style='font-family:Georgia,Time,sans-serif;font-size:16px;color:#0d0d0d;margin-bottom:10px;margin-top:10px;'> We recently received a password change request from you.</p>
                                   <br>
                                
                                   <p style='margin:0;font-family:Georgia,Time,sans-serif;font-size:16px;margin-bottom:10px;margin-top:10px;color:#0d0d0d;'> We recently To change your FriendzSquare password click below:</p>
                                   <br>
                                 
                                  
                                   <p style='margin-top:20px;font-family:Georgia,Time,sans-serif;font-size:14px;margin-bottom:10px;color:#0d0d0d;'><a href="{{ $link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"   target="_blank" style="background: #008BD0;color: #fff;height:32px;padding:10px 12px;text-decoration: none;border-radius: 5px;">Change Password</a></p>
                                   <br>
                                  </div>
                              </div>
                            </td>
                          </tr>
                        </table>
                      </div>

                      <div class='movableContent'>
                        <table width="520" border="0" cellspacing="0" cellpadding="0" align="left">
                          <tr>
                            <td valign='top'>
                              <div class="contentEditableContainer contentImageEditable">
                                <p style='margin:0;font-family:Georgia,Time,sans-serif;font-size:17px;color:#0d0d0d;margin-bottom:8px;margin-top:8px;line-height:28px;'> Or you can paste the following link into your browser: <a href="{{ $link }}" target="_blank"> {{ $link }} </a> </p>
                                <br>
                              </div>
                            </td>
                          </tr>
                        </table>
                      </div>

                      <div class='movableContent'>
                        <table width="520" border="0" cellspacing="0" cellpadding="0" align="left">
                        
                          <tr>
                            <td align='left'>
                              <div class="contentEditableContainer contentTextEditable">
                                <div class="contentEditable" align='left'>
                                    <p style='margin:0;font-family:Georgia,Time,sans-serif;font-size:16px;color:#0d0d0d;margin-bottom:8px;margin-top:8px;'> The link will expire in 24 hours.</p>
                                    <br>
                                     <p style='margin:0;font-family:Georgia,Time,sans-serif;font-size:16px;color:#0d0d0d;margin-bottom:8px;margin-top:8px;line-height:22px;'> If you did not request the password change, please immediately email to <a href="mailto:contact@friendzsquare.com" style="font-weight:700">contact@friendzsquare.com</a> </p>
                                     <br>
                                </div>
                              </div>
                            </td>
                          </tr>

                          <tr>
                            <td align='left'>
                              <div class="contentEditableContainer contentTextEditable">
                                <div class="contentEditable" align='left'>
                                  <p style='margin:0;font-family:Georgia,Time,sans-serif;font-size:16px;color:#0d0d0d;margin-bottom:8px;margin-top:8px;'>  To keep your account secure, please don’t forward this email or link to anyone. </p>
                                  <br>
                                  <p style='margin:0;font-family:Georgia,Time,sans-serif;font-size:16px;color:#0d0d0d;margin-bottom:8px;margin-top:8px;'>     Thank you for using FriendzSquare! </p>
                            
                                  <p style='text-align:left;color:#0d0d0d;font-size:14px;font-weight:700;line-height:19px;'>FriendzSquare Team <br>
                                  </p>
                                </div>
                              </div>
                            </td>
                          </tr>
                        </table>
                      </div>
                    </td>
                  </tr>
                </table>
              </td>
              <td width='40'></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>