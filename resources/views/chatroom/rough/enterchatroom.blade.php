@extends('layouts.dashboard')

@section('content')

<div class="page-data dashboard-body">
        <div class="container">
            <div class="row">

            @include('panels.left')

            <div class="col-sm-6">
                <div class="shadow-box page-center-data no-margin-top">
                    <div class="page-title">
                        <i class="flaticon-people"></i>{{ $grpname }}
<!--                         <div class="search-box">
                            <input type="text" placeholder="Search" class="form-control">
                            <button class="search-btn-small" type="button"><i class="glyph-icon flaticon-magnifyingglass138"></i></button>
                        </div> -->
                    </div>

                    <div class="container">


<div class="shadow-box page-center-data no-margin-top no-bottom-padding">
                    <div class="row">
                        <div class="col-sm-4 padding-right-none chat-list-outer">
                            <div class="chat-list-search">
                                <div class="form-group">
                                    <input type="text" placeholder="Search" id="search" class="form-control" >
                                    <button class="search-btn" id="search-btn" type="button"><i class="glyph-icon flaticon-magnifyingglass138"></i></button>
                                </div>
                            </div>
                            <div class="chat-user-list StyleScroll" id="friends" style="overflow: hidden;" tabindex="0">
                                <ul>

                                    @foreach($userdata as $data)
                                    <?php //echo '<pre>';print_r($data);die;?>
                                    <li>
                                        <a title="" href="#" onclick="openChatbox(<?php echo "'".$data['user']['xmpp_username']."', '".$data['user']['xmpp_password']."'"?>);" >
                                            <span style="background: url('images/user-thumb.jpg');" class="chat-thumb"></span>
                                            <span class="title">{{ $data['user']['first_name'].' '.$data['user']['last_name'] }}</span>
                                            <span class="time">02:50 am</span>
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div><!--/chat user list-->
                            <div class="dropdown all-contact">
                              <button aria-expanded="false" aria-haspopup="true" data-toggle="dropdown" type="button" class="all-contact-btn" id="dLabel">
                                All Contacts
                              </button>
                              <ul aria-labelledby="dLabel" class="dropdown-menu user-list-with-thumb StyleScroll" style="overflow: hidden;" tabindex="1">
                                    @foreach($userdata as $data)
                                    <li>
                                        <a title="" href="#">
                                            <span style="background: url('images/user-thumb.jpg');" class="chat-thumb"></span>
                                            <span class="title">{{ $data['user']['first_name'].' '.$data['user']['last_name'] }}</span>
                                            <span class="time">02:50 am</span>
                                            <!-- <span class="msg">Hi, How r u?</span> -->
                                        </a>
                                    </li>
                                    @endforeach
                              </ul>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="col-sm-6" id="chat-system"> Search your friend and start chat.</div>
                        </div>
                    </div>
                </div>


                    </div>


           
    <div class="shadow-box bottom-ad"><img class="img-responsive" alt="" src="images/bottom-ad.jpg"></div>
            </div></div>

 @include('panels.right')
            </div>
        </div>
    </div><!--/pagedata-->
  
 
@endsection

 
  
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
