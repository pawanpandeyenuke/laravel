@extends('layouts.chat')

@section('content')
<?php 
$groupid=$groupname;
$groupname = implode(' ', array_map('ucfirst', explode('_', $groupid)));
$groupname = implode(',', array_map('ucfirst', explode(',', $groupname)));
$groupname =preg_replace('/(?<! )(?<!^)[A-Z]/',' $0', $groupname);
$old=array('Moviereview','Schoolreviews','Coachingclass',"It,","Collegereview ","Singlesfemales","Singlesmale","Legalquestions"
            ,"Professionalcourse","Bicyclesandsidecars","suvs","van","Studyquestions","Fortuneteller","Emergencyblooddonation"
            ,"Studyquestions","Seekhelp","-",'Csc','C ');
$new=array('Movie Review','School Reviews','Coaching Class',"IT,","College Review ","Singles Females","Singles Male ",
            "Legal Questions ","Professional Course ","Bicycles and Sidecars","Suvs","Van","Study Questions","Fortune Teller"
            ,"Emergency Blood Donation","Study Questions","Seek Help","","","");

 $groupname=str_replace($old,$new,$groupname);


if($pgid){
   $groupid=$groupid."_".$pgid;
}

?>
<div class="page-data dashboard-body">
        <div class="container">
            <div class="row">

            @include('panels.left')

            <div class="col-sm-6">

                <div class="shadow-box page-center-data no-margin-top no-bottom-padding">
                    <div class="row">
                        <div class="col-sm-4 padding-right-none chat-list-outer">
                <!-- <div class="chat-list-search">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Search">
                                    <button type="button" class="search-btn"><i class="glyph-icon flaticon-magnifyingglass138"></i></button>
                                </div>
                            </div> -->
                            <div class="group-chat-cont">
                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                  <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="gcheadingOne">
                                      <h4 class="panel-title">
                                      <?php
                                      	if($exception==null)
                                      	{
                                      		$pubclass="class=collapsed";
                                      		$pubexpand="false";
                                      		$pubdivid="panel-collapse collapse";

                                      		$priclass="";
                                      		$priexpand="true";
                                      		$pridivid="panel-collapse collapse in";

                                      	}
                                      	else
                                      	{
                                      		$pubclass="";
                                      		$pubexpand="true";
                                      		$pubdivid="panel-collapse collapse in";
                                      	
                                      		$priclass="class=collapsed";
                                      		$priexpand="false";
                                      		$pridivid="panel-collapse collapse";
                                      	}

                                       ?>
                                        <a {{$pubclass}} role="button" data-toggle="collapse" data-parent="#accordion" href="#gccollapseOne" aria-expanded=
                                        "{{$pubexpand}}" aria-controls="gccollapseOne">
                                          Public group chat
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="gccollapseOne" class="{{$pubdivid}}" role="tabpanel" aria-labelledby="gcheadingOne">
                                      <div class="panel-body">
                                        <div class="chat-user-list StyleScroll">
                                         <i class="flaticon-people"></i> <b>{{$groupname}}</b>
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
                                                                <button type="button" class="time invite">Invite</button>
                                                                <span class='time sentinvite' style="display: none;">Sent</span>
                                                                <?php 
                                                                }
                                                                } ?>
                                                       
                                        </a>
                                    </li>
                                    @endforeach
                                    </ul>
                                                </div><!--/chat user list-->
                                      </div>
                                    </div>
                                  </div>
                                  <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="gcheadingTwo">
                                      <h4 class="panel-title">
                                        <a {{$priclass}} role="button" data-toggle="collapse" data-parent="#accordion" href="#gccollapseTwo" aria-expanded="{{$priexpand}}" aria-controls="gccollapseTwo">
                                          Chat with friends
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="gccollapseTwo" class="{{$pridivid}}" role="tabpanel" aria-labelledby="gcheadingTwo">
                                      <div class="panel-body">
                                        <div class="chat-list-search">
                                            <div class="form-group">
                                               <input type="text" class="form-control searchtxt" placeholder="Search Friends">
                                        <button type="button" class="search-btn" id="search"><i class="glyph-icon flaticon-magnifyingglass138"></i></button>
                                            </div>
                                        </div>
                                        
                                        <div class="chat-user-list StyleScroll">
                                        <ul id="userslist"></ul>
                                            </div><!--/chat user list-->
                                      </div>
                                    </div>
                                  </div>
                                  <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="gcheadingThree">
                                      <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#gccollapseThree" aria-expanded="false" aria-controls="gccollapseThree">
                                          Private group chat
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="gccollapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="gcheadingThree">
                                      <div class="panel-body">
                                        <div class="chat-user-list StyleScroll">
                                <ul>
                    @foreach($privategroup as $data)
                    <?php  

                        $privategroupname=$data['title'];
                        $privategroupid=strtolower($privategroupname);
                        $privategroupid=str_replace(" ","_",$privategroupid); 
                            $namestr='';
                            $name=array();
                            $count=0;
                        foreach ($data['members'] as $mem) {
                                if($mem['member_id']==Auth::User()->id)
                                {
                                    $name[]="You";
                                    $count++;
                                }
                                else{
                                $name[]=DB::table('users')->where('id',$mem['member_id'])->value('first_name');
                                }
                            }

                            $namestr=implode(",",$name);

                            if(!($count==0) || $data['owner_id']==Auth::User()->id)
                            {          
                            ?>
                               
                                        <li>                           
                                                <a href="#" class="chat-user-outer"  title="" onclick="openChatGroup(<?php echo "'".$privategroupname."', '".$privategroupid."'"?>);">
                                                <span class="chat-thumb" style="background: url('/images/user-thumb.jpg');"></span>
                                                <span class="title">
                                                    {{$data['title']}} 
                                                </span>
                                                   </a>
                                          
                                                  
                                                </li>
                                                <?php } ?>
                                        @endforeach
                                        </ul>
                         
                                     </div><!--/chat user list-->
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            
                        </div>                        <div class="col-sm-8">
                            <div id="chat-system"></div>
                        </div>
                    </div>
                </div>

<!--END-->

                <div class="shadow-box bottom-ad"><img src="/images/bottom-ad.jpg" alt="" class="img-responsive"></div>


               </div>

               <div class="col-sm-3">
                <div class="side-btn">
                    <a href="#" title="" class="btn btn-lg btn-full btn-primary">Suggestions</a>
                </div><!--/side btn-->
                <div class="side-widget-cont">
                    <img src="/images/side-ad.jpg" alt="" class="img-responsive side-ad">
                </div>
            </div>

 
            </div>
        </div>
    </div><!--/pagedata-->
  
 
@endsection

<link href="{{url('/converse/converse.min.css')}}" rel="stylesheet" type="text/css" media="screen" >
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script type="text/javascript" src="/converse/jquery.form.js"></script>
<script type="text/javascript" src="/converse/converse.nojquery.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>



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
    var groupname="{{$groupname}}";
    var groupid="{{$groupid}}";
    var pgid="{{$pgid}}";



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

        if(groupname != '' || groupid != '')
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


    // Send image over chat.


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

        $(document).on('keypress', '.searchtxt', function(e){
            var key = e.which;
            if(key == 13){
                var name=$('.searchtxt').val();
                   $.ajax({
                    'url' : 'ajax/searchfriend',
                    'type' : 'post',
                    'data' : {'name':name},
                    'success' : function(data){
                        $("#userslist").html(data);
                    }       
                });
            }
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
       }

    $('.status-r-btn').on('click',function(){
        if ( $('#status_img_up').is(':checked') ) { 
        $('.status-img-up').show();
      }
      else{
        $('.status-img-up').hide();
      }
    });

    $('.dropdown.keep-open').on({
    "shown.bs.dropdown": function() { this.closable = false; },
    "click":             function() { this.closable = true; },
    "hide.bs.dropdown":  function() { return this.closable; }
    });

</script>

