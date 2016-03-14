@extends('layouts.dashboard')

@section('content')

    <div class="profile-content">
     <section>
       <div class="container ">

        <div class="row">
    	<div style=""> <h1>Normal group</h1></div>
            <div class="col-sm-3"> 
          
            <input type="text" id="search" />	
                <input type="button" value="Search" id="search-btn"/>
                <div  id="friends">     </div>			
            </div>
            <div class="col-sm-6" id="chat-system"> Search your friend and start chat.
            </div>
        </div>
    </div>
    </section>
    </div>

<script type="text/javascript">

    var conObj; 
    $.noConflict();

    jQuery(document).ready(function(){

        jQuery.ajax({
            'url' : 'ajax/getxmppuser',
            'type' : 'post',
            'success' : function(data){
                if(data.status==1){

            require(['converse'], function (converse) {
             (function () {
                /* XXX: This function initializes jquery.easing for the https://conversejs.org
                * website. This code is only useful in the context of the converse.js
                * website and converse.js itself is NOT dependent on it.
                */
                var $ = converse.env.jQuery;
                $.extend( $.easing, {
                    easeInOutExpo: function (x, t, b, c, d) {
                        if (t==0) return b;
                        if (t==d) return b+c;
                        if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
                        return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
                    },
                });

                $(window).scroll(function() {
                    if ($(".navbar").offset().top > 50) {
                        $(".navbar-fixed-top").addClass("top-nav-collapse");
                    } else {
                        $(".navbar-fixed-top").removeClass("top-nav-collapse");
                    }
                });
                //jQuery for page scrolling feature - requires jQuery Easing plugin
                $('.page-scroll a').bind('click', function(event) {
                    var $anchor = $(this);
                    $('html, body').stop().animate({
                        scrollTop: $($anchor.attr('href')).offset().top
                    }, 700, 'easeInOutExpo');
                    event.preventDefault();
                });
            })();           
            
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
                         hide_muc_server: true,
                          debug: false ,
                          allow_otr: false,
                         auto_list_rooms: true,
                         auto_subscribe: true,
                         roster_groups:true,
                         allow_logout: false,
                         allow_chat_pending_contacts:true
                    });
                    jQuery('.chatroom .icon-minus','.chatbox .icon-minus').click();
                    jQuery('.minimized-chats-flyout .chat-head:first .restore-chat').click();

                   });

                }
            }
        });

        jQuery('#search-btn').click(function(){

            var val = jQuery('#search').val();
            if(val.length>2)
            {
                jQuery.ajax({            
                    'url' : 'ajax/search-friend',
                    'data' : { 'xmpp_username' : val },
                    'type' : 'post',
                    'success' : function(response){
                         if(response!='')
                         {
                            jQuery('#friends').html(response);
                         }                       
                            
                    }           
                }); 
            }else{
                alert("Please enter more than 2 char");
            }
  
        });

    });

     function openChatbox(xmpusername,username)
     {
         conObj=converse;
        // xmpusername='appuser_8119';
        console.log(xmpusername);   
        console.log(username);   
        var ss=conObj.contacts.get(xmpusername+'@fs.yiipro.com');
         if(ss==null)
         {  
      console.log(ss);   
             conObj.contacts.add(xmpusername+'@fs.yiipro.com', username);             
         }
        conObj.chats.open(xmpusername+'@fs.yiipro.com');
     }

</script>

@endsection