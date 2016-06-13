@extends('layouts.chat')

<style>
.flyout.box-flyout {
  width: 100% !important;
}
.chat-message {
  word-break: break-all;
}
#conversejs .chat-title {
  margin: 0 !important;
}
.chat-message.pic-me .chat-message-content::after, #conversejs .chat-message.pic-them .chat-message-content::after {
  border-bottom: 6px solid transparent;
  border-left: 10px solid #ddd;
  border-top: 6px solid transparent;
  content: "";
  display: block;
  position: absolute;
  right: -9px;
  top: 0;
}
.toggle-otr.unencrypted{
  display: none !important;
}
#conversejs .icon-happy:before {
  font-size: 25px;
  color: #A5A4A4;
}
</style>

@section('content')
<?php 
$groupid = $group_jid;
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
                                      	if($exception==null){
                                      		$pubclass="class=collapsed";
                                      		$pubexpand="false";
                                      		$pubdivid="panel-collapse collapse";

                                          $priclass="class=collapsed";
                                          $priexpand="false";
                                          $pridivid="panel-collapse collapse";

                                      		$friclass="";
                                      		$friexpand="true";
                                      		$fridivid="panel-collapse collapse in";
                                      	}else if($exception == "private"){
                                          $priclass="";
                                          $priexpand="true";
                                          $pridivid="panel-collapse collapse in";

                                          $pubclass="class=collapsed";
                                          $pubexpand="false";
                                          $pubdivid="panel-collapse collapse";

                                          $friclass="class=collapsed";
                                          $friexpand="false";
                                          $fridivid="panel-collapse collapse";
                                        }
                                      	else
                                      	{
                                      		$pubclass="";
                                      		$pubexpand="true";
                                      		$pubdivid="panel-collapse collapse in";

                                          $priclass="class=collapsed";
                                          $priexpand="false";
                                          $pridivid="panel-collapse collapse";
                                      	
                                      		$friclass="class=collapsed";
                                      		$friexpand="false";
                                      		$fridivid="panel-collapse collapse";
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
                                        <div class="chat-header-small text-center">
                                          <i class="flaticon-people"></i> <b><?php echo ($exception == "private")?"":$groupname; ?></b>
                                        </div>
                                        <div class="chat-user-list StyleScroll">
                                          <ul>
                                            @if(!empty($userdata))
                                            @foreach($userdata as $data)

                                              <?php $user_picture = !empty($data['user']['picture']) ? $data['user']['picture'] : '/images/user-thumb.jpg'; ?>

                                              <li>
                                                  <a title="" href="#" class='info' data-id="{{$data['user']['id']}}" >
                                                      <span style="background: url('{{$user_picture}}');" class="chat-thumb"></span>
                                                      <span class="title">{{ $data['user']['first_name'] }}</span>
                                                  
                                                  @if($data['user']['id'] != Auth::User()->id)
                                                    <?php 
                                                      $status = DB::table('friends')->where('user_id',Auth::User()->id)->where('friend_id',$data['user']['id'])->value('status');
                                                      $status1 = DB::table('friends')->where('user_id',$data['user']['id'])->where('friend_id',Auth::User()->id)->value('status'); 
                                                      // echo '<pre>';print_r($status1);die;
                                                      ?>
                                                    @if($status != null || $status1 != null)

                                                        @if($status == 'Accepted')
                                                          <button class='time' onclick="openChatbox(<?php echo "'".$data['user']['xmpp_username']."', '".$data['user']['first_name']."'"?>);">Chat</button>
                                                        @elseif($status=='Pending')
                                                          <span class='time'>Sent</span>                                            
                                                        @elseif($status1=='Pending')                                                  
                                                          <span class='time'></span>
                                                        @endif

                                                    @else 

                                                        <button type="button" class="time btn btn-sm btn-chat btn-primary invite">Invite</button>
                                                        <span class='time sentinvite' style="display: none;">Sent</span>

                                                    @endif
                                                  @endif
                                                                 
                                                  </a>
                                              </li>
                                            @endforeach
                                            @endif
                                          </ul>
                                        </div><!--/chat user list-->
                                      </div>
                                    </div>
                                  </div>
                                  <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="gcheadingTwo">
                                      <h4 class="panel-title">
                                        <a {{$friclass}} role="button" data-toggle="collapse" data-parent="#accordion" href="#gccollapseTwo" aria-expanded="{{$friexpand}}" aria-controls="gccollapseTwo">
                                          Chat with friends
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="gccollapseTwo" class="{{$fridivid}}" role="tabpanel" aria-labelledby="gcheadingTwo">
                                      <div class="panel-body">
                                        <div class="chat-list-search">
                                            <div class="form-group">
                                               <input type="text" class="form-control searchtxt" placeholder="Search Friends">
                                        <button type="button" class="search-btn" id="search"><i class="flaticon-magnifying-glass138"></i></button>
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
                                        <a {{$priclass}} role="button" data-toggle="collapse" data-parent="#accordion" href="#gccollapseThree" aria-expanded="{{$priexpand}}" aria-controls="gccollapseThree">
                                          Private group chat
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="gccollapseThree" class="{{$pridivid}}" role="tabpanel" aria-labelledby="gcheadingThree">
                                      <div class="panel-body">
                                        <div class="chat-user-list StyleScroll">

                                <ul>
                    @foreach($privategroup as $data)
                    <?php  

                        $privategroupname=$data['title'].'_'.$data['id'];
                        $privategroupid=strtolower($privategroupname);
                        $privategroupid=str_replace(" ","-",$privategroupid);
                         
                        $group_picture = !empty($data['picture']) ? $data['picture'] : '/images/post-img-big.jpg';
			
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

                            if(!($count==0) || $data['owner_id']==Auth::User()->id) { 
                            $pri_id = $data['id'];
                              ?>
                               <li>
								   <div	class="pvt-room-list" style="position:relative;" >
										<a href="{{url("private-group-detail/$pri_id")}}" >
											<span class="chat-thumb" style="background: url(<?= $group_picture ?>);"></span>
											<span class="title">{{$data['title']}}</span>
										</a>
										<button onclick="openChatRoom('<?php echo $privategroupid; ?>', '<?php echo $data['title']; ?>');" class="time">Chat</button>
                                   </div>
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
                            
                        </div>
                        <div class="col-sm-8">
                            <div id="chat-system"></div>
                        </div>
                    </div>
                </div>
<!--END-->
                <div class="shadow-box bottom-ad"><img src="/images/bottom-ad.jpg" alt="" class="img-responsive"></div>
               </div>
        @include('panels.right')
             </div>
        </div>
    </div><!--/pagedata-->
  
 
@endsection

<link href="{{url('/converse/converse.min.css')}}" rel="stylesheet" type="text/css" media="screen" >
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script type="text/javascript" src="{{url('/converse/jquery.form.js')}}"></script>
<script type="text/javascript" src="{{url('/converselib/demo_converse.nojquery.min.js')}}"></script>
<!-- <script type="text/javascript" src="{{url('/converse/converse.nojquery.min.js')}}"></script> -->
<script type="text/javascript" src="{{url('/js/bootstrap.min.js')}}"></script>


<?php 
 
  $img = Auth::User()->picture; 
  $userpic = !empty($img)? url($img) : url('/images/user-thumb.png');
?>
 
<script type="text/javascript">

    var userImage="{{$userpic}}";
 
    var defaultImage="{{url('/images/user-thumb.jpg')}}";

    var image_upload_url="ajax/sendimage";
    var chatserver='@<?= Config::get('constants.xmpp_host_Url') ?>';
   
    var subcategory="<?php echo Request::get('subcategory'); ?>";

    var parent="<?php echo Request::get('parentname'); ?>";

    // alert(subcategory);
    // alert(parent);
  //  var username='<?php DB::table('') ?>';
    var conferencechatserver='@conference.<?= Config::get('constants.xmpp_host_Url') ?>';
    var conObj;
    var groupname = "{{$groupname}}";
    var groupid = "{{$groupid}}";




    var is_first = true;  

    jQuery(document).ready(function(){

      require(['converse'], function (converse) {
        
                conObj = converse;
                
                converse.initialize({                           
                  prebind: true,
                  bosh_service_url: '//<?= Config::get('constants.xmpp_host_Url') ?>:5280/http-bind',
                  keepalive: true,
                  jid: '<?= Auth::User()->xmpp_username ?>@<?= Config::get('constants.xmpp_host_Url') ?>',
                  authentication: 'prebind',
                  prebind_url: "{{url('/ajax/getxmppuser')}}",
                  allow_logout: false,
                  debug: false ,
                  message_carbons: true,
                  send_initial_presence:true,
                  roster_groups: true ,
                  forward_messages: true,
                  auto_join_rooms: [{'jid': 'group3_3@friendzsquare.com', 'nick': 'Group3' }]
                });
                // jQuery('.chatroom .icon-minus','.chatbox .icon-minus').click();
                // jQuery('.minimized-chats-flyout .chat-head:first .restore-chat').click();

                /* pawanpandey Code */
                
                  var minimizedIcon = $(".icon-close");
                  $.each(minimizedIcon, function(i,v){
                    v.click();
                  });

                  $('.minimized-chats-flyout .chat-head:first .restore-chat').click();

/*                  $('.icon-minus').each(function(){
                    $(this).trigger('click');
                  });*/
                  // jQuery('.minimized-chats-flyout .chat-head:first .restore-chat').click();


                /* pawanpandey Code */

                $(".chatroom:visible").each(function()  {
                  checkChatboxAndChatRoom(this);
                });                     
    
                $(".chatbox:visible").each(function()  {
                  checkChatboxAndChatRoom(this);
                });

                if(is_first){
                  jQuery('.minimized-chats-flyout .chat-head:first .restore-chat').click(); 
                }

				if( groupname != '' || groupid != '' ){
					console.log(groupid);
					// converse.rooms.open('haeri@conference.friendzsquare.com', 'mycustomnick');
					openChatGroup(groupname, groupid);
					// converse.rooms.open(groupname, groupid);
                }
				conObj.listen.on('chatRoomOpened', function (event, chatbox) { 
					console.log( 'room open' );
				});
				conObj.listen.on('chatBoxOpened', function (event, chatbox) { 
					console.log( 'box open' ); 
				});
				conObj.listen.on('chatBoxToggled', function (event, chatbox) { 
					console.log( 'box total' );
				});
            
              

           });




            // openChatGroup(groupname,groupid);
             //converse.chats.open('hari@muc.friendzsquare.com');


/*        jQuery.ajax({
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
                            bosh_service_url: '//<?= Config::get('constants.xmpp_host_Url') ?>:5280/http-bind',
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
                            allow_chat_pending_contacts:true,
                            send_initial_presence:true,
                            xhr_custom_status:true

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
        });*/


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

        $(document).on('click','.invite',function(){
          var current = $(this);
          var user_id = current.closest('.info').data('id');
          
          $.ajax({
            'url' : 'ajax/sendrequest',
            'type' : 'post',
            'data' : {'user_id' : user_id },
            'success' : function(data){
              current.closest('.info').find('.invite').hide(200);
              current.closest('.info').find('.sentinvite').show(500);
            }
          });
        });


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

     function openChatbox(xmpusername,username)
     {
         conObj =converse;
        var ss=conObj.contacts.get(xmpusername+'@<?= Config::get('constants.xmpp_host_Url') ?>');
         if(ss==null)
         {  
      console.log(ss);   
             conObj.contacts.add(xmpusername+'@<?= Config::get('constants.xmpp_host_Url') ?>', username);             
         }
        conObj.chats.open(xmpusername+'@<?= Config::get('constants.xmpp_host_Url') ?>');
     }


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


/*
function openChatbox( xmpusername,username ){
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


}*/

 function hideOpendBox(){
	 /**
	 $(".chatroom:visible").each(function()    {
		$(this).find('.icon-minus').click();
		});
		$(".chatbox:visible").each(function()   {
		$(this).find('.icon-minus').click();
	});
   **/
}
  function openChatGroup(grpname,grpjid)
       {
        // alert(grpjid);
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
	function openChatRoom( room, roomname ){
		hideOpendBox();
		converse.rooms.open( room+'@conference.<?= Config::get('constants.xmpp_host_Url') ?>', roomname );	
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

