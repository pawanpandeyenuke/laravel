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

#conversejs #minimized-chats{
  top: 515px !important;
  left: 3%;
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
                                          Public Group Chat
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="gccollapseOne" class="{{$pubdivid}}" role="tabpanel" aria-labelledby="gcheadingOne">
                                      <div class="panel-body">
                                        <div class="chat-header-small">
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
                                          Chat with Friends
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
                                          Private Group Chat
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="gccollapseThree" class="{{$pridivid}}" role="tabpanel" aria-labelledby="gcheadingThree">
                                      <div class="panel-body">
                                        <div class="chat-user-list StyleScroll">

                                <ul>
                    @foreach($privategroup as $data)
                    <?php  
						/**
						$privategroupname = preg_replace('/[^A-Za-z0-9\-]/', '_',$data['title']);
                        $privategroupname = strtolower($privategroupname);
                        $privategroupid   = $privategroupname.'_'.$data['id'];
                        **/ 
                        $group_picture = !empty($data['picture']) ? $data['picture'] : '/images/post-img-big.jpg';
			
                            $namestr='';
                            $name=array();
                            $count=0;
							foreach ($data['members'] as $mem) {
                                if( $mem['member_id']==Auth::User()->id ){
                                    $name[]="You";
                                    $count++;
                                } else {
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
										<button onclick="return openChatGroup('<?php echo $data['group_jid']; ?>', '<?php echo $data['title']; ?>');" class="time">Chat</button>
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
	var encoderoomid = '';
    var userImage="{{$userpic}}";
 
    var defaultImage="{{url('/images/user-thumb.jpg')}}";

    var image_upload_url="ajax/sendimage";
    var chatserver='@<?= Config::get('constants.xmpp_host_Url') ?>';
   
    var subcategory="<?php echo Request::get('subcategory'); ?>";

    var parent="<?php echo Request::get('parentname'); ?>";

    var Base64 = {_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}

    var conferencechatserver = '@conference.<?= Config::get('constants.xmpp_host_Url') ?>';
    var conObj;
    var groupname = "{{$groupname}}";
    var groupid = "{{$groupid}}";
    var exception = "{{$exception}}";
	var is_first = true;  
	
	var waitProfile = 0;
	
    jQuery(document).ready(function(){
		setTimeout( function(){ 
			waitProfile = 1;
		}  , 8000 );
      require(['converse'], function (converse) {
               conObj = converse;
               conObj.listen.on('initialized', function (event) {
					if( groupname != '' || groupid != '' ) {
						setTimeout( function(){ 
							openChatGroup( groupid, groupname);
						}  , 3000 );
					}
				});
				
				conObj.listen.on('chatBoxOpened', function (event, chatbox) {
					if( waitProfile == 1 ){
						var jidStr = chatbox.model.get('jid');
						hideOpendBox( jidStr );
					}
				});
				
				conObj.listen.on('chatRoomOpened', function (event, chatbox) { 
					var jidStr = chatbox.model.get('jid');
					var xmpp = jidStr.replace( '@conference.<?= Config::get('constants.xmpp_host_Url') ?>' , '' );
					if( waitProfile == 1 ){
						hideOpendBox( jidStr );
					}
					if( xmpp ==  groupid ){
						chatbox.$el.find( '.chat-title' ).html( '<?php echo $groupname; ?>' );
						<?php if( isset( $group_image ) && !empty($group_image) ) { ?>
							chatbox.$content.find( '.profileavatar' ).attr( "style", "background: url('<?php echo $group_image; ?>');" );
						<?php } ?>
					} else {
						$.ajax({
							'url' : "/ajax/getgroupdeatils",
							'type' : 'post',
							'async' : false,
							'dataType' : 'json',
							'data' : { group_jid: xmpp },
							'success' : function(data){
								if( data.title !== undefined ){
									chatbox.$el.find( '.profileavatar' ).attr( "style", "background: url("+data.image+");" );
									chatbox.$el.find( '.chat-title' ).html( data.title );
								}	
							}
						});
					}	
				});
				
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
                  // auto_join_rooms: [{'jid': groupid+'@<?= Config::get('constants.xmpp_host_Url') ?>', 'nick': groupname }]
                });
                
				
                // jQuery('.chatroom .icon-minus','.chatbox .icon-minus').click();
                // jQuery('.minimized-chats-flyout .chat-head:first .restore-chat').click();

                /* pawanpandey Code */
                


                  

/*                  $('.icon-minus').each(function(){
                    $(this).trigger('click');
                  });*/
                  // jQuery('.minimized-chats-flyout .chat-head:first .restore-chat').click();


                /* pawanpandey Code */
				/**
                $(".chatroom:visible").each(function()  {
                  checkChatboxAndChatRoom(this);
                });                     
    
                $(".chatbox:visible").each(function()  {
                  checkChatboxAndChatRoom(this);
                });

                if(is_first){
                  jQuery('.minimized-chats-flyout .chat-head:first .restore-chat').click(); 
                }
				**/
				
				
            
              

      });


        $(document).on('click','.invite',function(){
          var current = $(this);
          var user_id = current.closest('.info').data('id');
          
          $.ajax({
            'url' : "{{url('/ajax/sendrequest')}}",
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
                'url' : "{{url('/ajax/searchfriend')}}",
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
                    'url' : "{{url('/ajax/searchfriend')}}",
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
         if( ss==null ){  
             conObj.contacts.add(xmpusername+'@<?= Config::get('constants.xmpp_host_Url') ?>', username);             
         }
         if( hideOpendBox( xmpusername+'@<?= Config::get('constants.xmpp_host_Url') ?>' ) ){
			conObj.chats.open(xmpusername+'@<?= Config::get('constants.xmpp_host_Url') ?>');
		}
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


}
*/
$(document).ready(function() {
	$( document ).on( 'click' , '.restore-chat.chatgroup' , function(){
		var jid = Base64.decode($(this).data( 'bid' ));
		hideOpendBox( jid );
	});
	$( document ).on( 'click' , '.restore-chat.singlechat' , function(){
		var jid = Base64.decode($(this).data( 'bid' ));
		hideOpendBox( jid );
	});
});

function hideOpendBox( grpname ){
	var resultreturn = true;
	$( '.privatechat' ).each( function(){
		var jid = Base64.decode($(this).data( 'bid' ));
		var getChat = conObj.chats.get(jid);
		if( jid == grpname ){
			if( $(this).css('display') == 'none' ){
				getChat.maximize();
			}
			resultreturn = false;
		} else {
			getChat.minimize();
		}
	});
	
	$( '.chatroom' ).each( function(){
		var jid = Base64.decode($(this).data( 'bid' ));
		var getRooms = conObj.rooms.get(jid);
		if( jid == grpname ){
			if( $(this).css('display') == 'none' ){
				getRooms.maximize();
			}
			resultreturn = false;
		} else {
			getRooms.minimize();
		}
	});
	return resultreturn;
}

function openChatGroup( grpjid,grpname ){
	if( hideOpendBox( grpjid+conferencechatserver ) ){
		conObj.rooms.open( grpjid+conferencechatserver );
	}
}

$('.status-r-btn').on('click',function(){
	if ( $('#status_img_up').is(':checked') ) {
		$('.status-img-up').show();
	} else {
		$('.status-img-up').hide();
	}
});

$('.dropdown.keep-open').on({
	"shown.bs.dropdown": function() { this.closable = false; },
	"click":             function() { this.closable = true; },
	"hide.bs.dropdown":  function() { return this.closable; }
});



</script>

