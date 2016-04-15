<!doctype html>
<html>
<head>
<link href="/demo_converse.min.css" rel="stylesheet" type="text/css" media="screen" >
<script type="text/javascript" src="/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="/demo_converse.nojquery.min.js"></script>
</head>
<body >
	


</body>
<script type="text/javascript">

    var userImage="{{url('/images/logo.png')}}";
    var defaultImage="{{url('/images/logo.png')}}";
    var image_upload_url="ajax/sendimage";
    var chatserver='@fs.yiipro.com';
    
    var subcategory="<?php echo Request::get('subcategory'); ?>";

    var parent="<?php echo Request::get('parentname'); ?>";

    // alert(subcategory);
    // alert(parent);
  //  var username='<?php DB::table('') ?>';
    var conferencechatserver='@conference.fs.yiipro.com';
    var conObj;
 



    var is_first=true;  

    jQuery(document).ready(function(){

        jQuery.ajax({
            'url' : "{{url('/ajax/getxmppuser')}}",
            'type' : 'post',
            'dataType':'json',
            'success' : function(data){
                if(data.status==1){
                    //console.log('abc');
            require(['converse'], function (converse) {
      
            conObj=converse;
                    converse.initialize({
                            prebind: true,
                            rid: data.rid,
                            sid: data.sid,
                            jid: data.jid,
                            bosh_service_url: '//fs.yiipro.com:5280/http-bind',
                            show_controlbox_by_default: true,
                            allow_contact_requests:true,
                            xhr_user_search: false,
                            i18n: locales.en,
                            //hide_muc_server: true,
                            debug: true ,
                           // allow_otr: false,
                          //  auto_list_rooms: true,
                          //  auto_subscribe: true,
                            auto_join_on_invite:true,
                            roster_groups:true,
                         //   allow_logout: false,
                         //   allow_chat_pending_contacts:true,
                            message_carbons: true
                    });
                    //jQuery('.chatroom .icon-minus','.chatbox .icon-minus').click();
                    //jQuery('.minimized-chats-flyout .chat-head:first .restore-chat').click();
                   });

                }

            }
        });
          });




</script>
