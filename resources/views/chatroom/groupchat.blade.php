@extends('layouts.chat')

@section('content')
<?php 

$groupid=$groupname;
$groupname = implode('-', array_map('ucfirst', explode('-', $groupid)));
$groupname = implode(',', array_map('ucfirst', explode(',', $groupname)));
$groupname =preg_replace('/(?<! )(?<!^)[A-Z]/',' $0', $groupname);
$old=array('Moviereview','Schoolreviews','Coachingclass',"It,","Collegereview ","Singlesfemales","Singlesmale","Legalquestions"
            ,"Professionalcourse","Bicyclesandsidecars","suvs","van","Studyquestions","Fortuneteller","Emergencyblooddonation"
            ,"Studyquestions","Seekhelp","-",'Csc','C ');
$new=array('Movie Review','School Reviews','Coaching Class',"IT,","College Review ","Singles Females","Singles Male ",
            "Legal Questions ","Professional Course ","Bicycles and Sidecars","Suvs","Van","Study Questions","Fortune Teller"
            ,"Emergency Blood Donation","Study Questions","Seek Help","","","");

 $groupname=str_replace($old,$new,$groupname);

$groupid=strtolower($groupid);
$groupid=str_replace('-','_',$groupid);
?>
<div class="page-data dashboard-body">
        <div class="container">
            <div class="row">

            @include('panels.left')

            <div class="col-sm-6">

                <div class="shadow-box page-center-data no-margin-top no-bottom-padding">
                    <div class="row">
                        <div class="col-sm-4 padding-right-none chat-list-outer">
                            
                            <div class="chat-list-search">
                                <div class="form-group">
<?php if($exception==null){ ?>
                                    <input type="text" class="form-control searchtxt" placeholder="Search Friends">
                                    <button type="button" class="search-btn" id="search"><i class="glyph-icon flaticon-magnifyingglass138"></i></button>
                                    <?php } else { ?>
                         
                                            <i class="flaticon-people"></i> <b>{{$groupname}}</b>

                                          
                                            <?php } ?>
                                </div>
                            </div>

                            <div class="chat-user-list StyleScroll" id="friends" style="overflow: hidden;" tabindex="0">
                             
                                
                                   <?php if($exception!=null) { ?>
                                   <ul>
                                    @foreach($userdata as $data)
                                    <?php //echo '<pre>';print_r($data['user']['id']);die;

                                    ?>
                                    <li>
                                        <a title="" href="#" class='info' data-id="{{$data['user']['id']}}" >
                                            <span style="background: url('/images/user-thumb.jpg');" class="chat-thumb"></span>
                                            <span class="title">{{ $data['user']['first_name'].' '.$data['user']['last_name'] }}</span>
                        

                                        <?php 

                                        if($data['user']['id']!=Auth::User()->id)
                                        {
                                           $status=DB::table('friends')->where('user_id',Auth::User()->id)->where('friend_id',$data['user']['id'])->value('status');
                                           $status1=DB::table('friends')->where('user_id',$data['user']['id'])->where('friend_id',Auth::User()->id)->value('status'); 

                                        if($status!=null || $status1!=null){
                                            if($status=='Accepted'){
                                         ?>
                                          <button class='time' onclick="openChatbox(<?php echo "'".$data['user']['xmpp_username']."', '".$data['user']['first_name']."'"?>);">Chat</button>          
                                                <?php } 
                                            elseif($status=='Pending')
                                            {
                                                ?>                           
                                                                <span class='time'>Sent</span>
                                                      <?php  } 
                                                      elseif($status1=='Pending') {
                                                      ?>
                                                                <span class='time'></span>

                                                            <?php 
                                                            }
                                                            } 
                                                            else 
                                                            { 
                                                                ?>
                                                                <button class='time invite' >Invite</button>
                                                                <span class='time sentinvite' style="display: none;">Sent</span>
                                                                <?php 
                                                                }
                                                                } ?>
                                                       
                                        </a>
                                    </li>
                                    @endforeach
                                    </ul>
                                    <?php } else {?>
                                    <ul id="userslist">

                                    <ul>
                                    <?php } ?>
                                
                            </div><!--/chat user list-->
                        </div>
                        <div class="col-sm-8">
                            <div id="chat-system"></div>
                        </div>
                    </div>
                </div>

                <div class="shadow-box bottom-ad"><img src="images/bottom-ad.jpg" alt="" class="img-responsive"></div>


               </div>

               <div class="col-sm-3">
                <div class="side-btn">
                    <a href="#" title="" class="btn btn-lg btn-full btn-primary">Suggestions</a>
                </div><!--/side btn-->
                <div class="side-widget-cont">
                    <img src="images/side-ad.jpg" alt="" class="img-responsive side-ad">
                </div>
            </div>

 
            </div>
        </div>
    </div><!--/pagedata-->
  
 
@endsection

<link href="{{url('/converse/converse.min.css')}}" rel="stylesheet" type="text/css" media="screen" >
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script type="text/javascript" src="/converse/converse.nojquery.min.js"></script>
<script type="text/javascript" src="/converse/jquery.form.js"></script>

<script type="text/javascript">

    var userImage="{{url('images/logo.png')}}";
    var defaultImage="{{url('images/logo.png')}}";
    var image_upload_url="ajax/sendimage";
    var chatserver='@fs.yiipro.com';
    
    var subcategory="<?php echo Request::get('subcategory'); ?>";

    var parent="<?php echo Request::get('parentname'); ?>";

    // alert(subcategory);
    // alert(parent);
  //  var username='<?php DB::table('') ?>';
    var conferencechatserver='@conference.fs.yiipro.com';
    var conObj;
    var groupname="{{$groupname}}";
    var groupid="{{$groupid}}";
  var exception="{{$flag}}";



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
                            hide_muc_server: true,
                            debug: false ,
                            allow_otr: false,
                            auto_list_rooms: true,
                            auto_subscribe: true,
                            auto_join_on_invite:true,
                            roster_groups:true,
                            allow_logout: false,
                            allow_chat_pending_contacts:true
                    });
                    //jQuery('.chatroom .icon-minus','.chatbox .icon-minus').click();
                    //jQuery('.minimized-chats-flyout .chat-head:first .restore-chat').click();


        if( groupname != '' || groupid != '' )
        {         
             openChatGroup(groupname,groupid);
        }
                    $(".chatroom:visible").each(function()  {
                          checkChatboxAndChatRoom(this);
                     });                     
                     $(".chatbox:visible").each(function()  {
                          checkChatboxAndChatRoom(this);
                     });
                
                     if(is_first)
                      jQuery('.minimized-chats-flyout .chat-head:first .restore-chat').click(); 


                   });

                }

            }
        });

/*        jQuery('#search-btn').click(function(){

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
  
        });*/


            $(document).on('click','#search',function() {

                var current=$(this);
                
                var name=$('.searchtxt').val();

                   $.ajax({
                    'url' : 'ajax/searchfriend',
                    'type' : 'post',
                    'data' : {'name':name},
                    'success' : function(data){
                        $("#userslist").html(data);
                    }       
                });
            });

    });

     // function openChatbox(xmpusername,username)
     // {
     //     conObj=converse;
     //    var ss=conObj.contacts.get(xmpusername+'@fs.yiipro.com');
     //     if(ss==null)
     //     {  
     //  console.log(ss);   
     //         conObj.contacts.add(xmpusername+'@fs.yiipro.com', username);             
     //     }
     //    conObj.chats.open(xmpusername+'@fs.yiipro.com');
     // }


    function checkChatboxAndChatRoom(obj)
     {
         var id=$(obj).attr('id');
         if(id!='controlbox')
         {
            if(!is_first)
            {                       
            $(obj).find('.icon-minus').click();
            }
            is_first=false; 
         }
     }




function openChatbox(xmpusername,username)
     {
   //var chatbox=conObj.chats.get(xmpusername+chatserver);
   //console.log(chatbox);
   var minbox=$("#min-"+xmpusername);
   //console.log(minbox.length);
      var ss=conObj.contacts.get(xmpusername+chatserver);
      if(ss==null)
   { 
    // conObj.contacts.add(xmpusername+chatserver, username);
     //conObj.chats.add(xmpusername+chatserver,username)
     // alert(username+' is not your friend.We are adding to your friend list. So please wait.');
                   
   }
   else if(minbox.length>0)
   {
    hideOpendBox();
    minbox.click();
   }
   else
   {  hideOpendBox();  
    conObj.chats.open(xmpusername+chatserver);
   }   


    
     }
 function hideOpendBox()
       {
              $(".chatroom:visible").each(function()    {
                $(this).find('.icon-minus').click();
                });
                $(".chatbox:visible").each(function()   {
                $(this).find('.icon-minus').click();
                });
       } 
  function openChatGroup(grpname,grpjid)
       {
           var minbox=$("#min-"+grpjid);
            hideOpendBox();
            if(minbox.length>0)
            {           
            minbox.click();
            }
            else{
                conObj.rooms.open(grpjid+conferencechatserver);
            }
           //var chatView=conObj.rooms.open(grpjid+conferencechatserver);
       }

    //     function openChatGroup(grpname,grpjid)
    // {
    //  var chatView=conObj.rooms.open(grpjid+chatserver,grpname);
    // }

</script>
