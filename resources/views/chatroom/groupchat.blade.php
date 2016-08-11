@extends('layouts.chat')

@section('title', 'Chat - ')

@section('content')
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

<?php 
$groupid = $group_jid;
$GroupsJidList = $SingleChatList = array();
?>
<div class="page-data dashboard-body">
        <div class="container">
            <div class="row">

            @include('panels.left')

            <div class="col-sm-6">
               <div class="loader_blk">
                 <div class="loadr_img">
                   <img src="{{url('images/loading.gif')}}">
                 </div>
               </div>
                <div id="afterload" class="shadow-box page-center-data no-margin-top no-bottom-padding">
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
                                          <?php 
                                             $icon_url = url('category_images/'.$group_image); 
                                          if( isset($group_image) && !empty($group_image) ) { ?>
                                           <img src="{{$icon_url}}" alt="" class="img-icon">
                                          <?php } ?>
                                         <b><?php echo ($exception == "private")?"":$groupname; ?></b>
                                        </div>
                                        <div class="chat-user-list StyleScroll">
                                          <ul>
                                            @if(!empty($userdata))
                                            @foreach($userdata as $data)

                                              <?php $user_picture = !empty($data['user']['picture']) ? $data['user']['picture'] : '/images/user-thumb.jpg'; ?>

                                              <li >
                                                  <div class='info' data-id="{{$data['user']['id']}}" style="position:relative;" >
                                                    <a title="" @if( $data['user']['id'] != Auth::User()->id) href="{{url('/profile/'.$data['user']['id'])}}" @endif  data-id="{{$data['user']['id']}}" >
                                                        <span style="background: url('{{$user_picture}}');" class="chat-thumb"></span>
                                                        <span class="title">{{ $data['user']['first_name'] }}</span>           
                                                    <?php $SingleChatList[$data['user']['xmpp_username']]['title'] = $data['user']['first_name'];
                                                    		$SingleChatList[$data['user']['xmpp_username']]['image'] = $user_picture;
                                                    ?>
                                                    </a>
                                                     @if($data['user']['id'] != Auth::User()->id)
                                                      <?php 
                                                        $status = \App\Friend::where('user_id',Auth::User()->id)->where('friend_id',$data['user']['id'])->value('status');
                                                        $status1 = \App\Friend::where('user_id',$data['user']['id'])->where('friend_id',Auth::User()->id)->value('status'); 
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
                                                      </div>
                                                  @endif
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
											<!-- private group List -->
                                         
											  <?php  $groups=array(); ?>
										@if(!empty($privategroup))
											 <ul>
											@foreach($privategroup as $data) 
											<?php  $group_picture = !empty($data['picture']) ?'/uploads/'.$data['picture'] : '/images/post-img-big.jpg'; ?>	
												  <li>
													 <?php $groups[$data['group_jid']]=$data['title'];  ?> 	
	                         <?php $GroupsJidList[]= $data['group_jid'].'@conference.'.Config::get('constants.xmpp_host_Url'); //array( 'jid' => $data['group_jid'].'@conference.'.Config::get('constants.xmpp_host_Url'), 'nick' => Auth::User()->xmpp_username.'_'.Auth::User()->first_name);  ?> 							  
													 
													<div class="pvt-room-list" style="position:relative;" >
														<a href="<?php echo url("private-group-detail/".$data['id']); ?>" >
															<span class="chat-thumb" style="background: url('<?= $group_picture ?>');"></span>
															<span class="title">{{$data['title']}}</span>
														</a>
														<button id="<?= $data['group_jid'] ?>" data-groupimage="<?= $group_picture ?>" onclick="return openChatGroup('<?php echo $data['group_jid']; ?>', '<?php echo $data['title']; ?>','<?= $group_picture ?>');" class="time">Chat</button>
													 </div>
												 </li>
											@endforeach
											 </ul>
										@else 
											<div class="text-center" ><br/><a class="add-blist-btn title="" href="{{url('private-group-add')}}"><i class="fa fa-plus"></i></a></div>
										@endif
					                      
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
  
<div id="leaveModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Leave Group</h4>
      </div>
      <div class="modal-body">
        <p class='text-center'>Are you sure you want to leave?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" data-jid="<?php echo $groupid;?>" id='leave-group'>Leave</button>
      </div>
    </div>
  </div>
</div>

<div id="leavePvtModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Leave Group</h4>
      </div>
      <div class="modal-body">
        <p class='text-center'>Are you sure you want to leave?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" data-jid="" id='leave-pvt-group'>Leave</button>
      </div>
    </div>
  </div>
</div>

<link href="{{url('/converse/converse.min.css')}}" rel="stylesheet" type="text/css" media="screen" >
<script type="text/javascript" src="{{url('/converselib/demo_converse.nojquery.min.js')}}"></script>

<?php 
 
  $img = Auth::User()->picture; 
  $userpic = !empty($img)? $img : 'user-thumb.jpg';
  
  
?>
 
<script type="text/javascript">
	jQuery.noConflict();
	var GroupName = <?php echo json_encode($groups); ?>;
    var GroupAuto = <?php echo json_encode($GroupsJidList); ?>;
  	var SingleChatName = <?php echo json_encode($SingleChatList); ?>;

	var encoderoomid = '';
    var userImage="{{$userpic}}";
 
    var defaultUserImage = "{{url('/images/user-thumb.jpg')}}";

    var image_upload_url="ajax/sendimage";
    var chatserver='@<?= Config::get('constants.xmpp_host_Url') ?>';
   
    var subcategory="<?php echo Request::get('subcategory'); ?>";

    var parent="<?php echo Request::get('parentname'); ?>";

    var Base64 = {_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(r){var t,e,o,a,h,n,c,d="",C=0;for(r=Base64._utf8_encode(r);C<r.length;)t=r.charCodeAt(C++),e=r.charCodeAt(C++),o=r.charCodeAt(C++),a=t>>2,h=(3&t)<<4|e>>4,n=(15&e)<<2|o>>6,c=63&o,isNaN(e)?n=c=64:isNaN(o)&&(c=64),d=d+this._keyStr.charAt(a)+this._keyStr.charAt(h)+this._keyStr.charAt(n)+this._keyStr.charAt(c);return d},decode:function(r){var t,e,o,a,h,n,c,d="",C=0;for(r=r.replace(/[^A-Za-z0-9\+\/\=]/g,"");C<r.length;)a=this._keyStr.indexOf(r.charAt(C++)),h=this._keyStr.indexOf(r.charAt(C++)),n=this._keyStr.indexOf(r.charAt(C++)),c=this._keyStr.indexOf(r.charAt(C++)),t=a<<2|h>>4,e=(15&h)<<4|n>>2,o=(3&n)<<6|c,d+=String.fromCharCode(t),64!=n&&(d+=String.fromCharCode(e)),64!=c&&(d+=String.fromCharCode(o));return d=Base64._utf8_decode(d)},_utf8_encode:function(r){r=r.replace(/\r\n/g,"\n");for(var t="",e=0;e<r.length;e++){var o=r.charCodeAt(e);128>o?t+=String.fromCharCode(o):o>127&&2048>o?(t+=String.fromCharCode(o>>6|192),t+=String.fromCharCode(63&o|128)):(t+=String.fromCharCode(o>>12|224),t+=String.fromCharCode(o>>6&63|128),t+=String.fromCharCode(63&o|128))}return t},_utf8_decode:function(r){for(var t="",e=0,o=c1=c2=0;e<r.length;)o=r.charCodeAt(e),128>o?(t+=String.fromCharCode(o),e++):o>191&&224>o?(c2=r.charCodeAt(e+1),t+=String.fromCharCode((31&o)<<6|63&c2),e+=2):(c2=r.charCodeAt(e+1),c3=r.charCodeAt(e+2),t+=String.fromCharCode((15&o)<<12|(63&c2)<<6|63&c3),e+=3);return t}};
   
    var conferencechatserver = '@conference.<?= Config::get('constants.xmpp_host_Url') ?>';
    var conObj;
    var groupname = "{{$groupname}}";
    var groupid = "{{$groupid}}";
    var exception = "{{$exception}}";
	var is_first = true;  
	var userImagesUrl = "{{url('/images')}}/";
	var waitProfile = 0;
	var defaultImage = "{{url('/images/post-img-big.jpg')}}";
	var baseUrl = '<?= url('/') ?>';
	var checkActiveGroupUrl = '<?= url('/ajax/isactivemember') ?>';
	var GetProfileUrl = '<?= url('/ajax/profilenameimage') ?>';
	var profiletitles = {};
	var myFullname = '<?= Auth::User()->first_name ?> <?= Auth::User()->last_name ?>';
	
	function webEncode( str ){
		//return Base64.encode( str );
		return str;
	}
	function webDecode( str ){
		//return Base64.decode( str );
		return str;
	}
	

    $(document).ready(function(){
      require(['converse'], function (converse) {
        conObj = converse;

        conObj.listen.on('connected', function (event) {
          $('.loader_blk').remove();

          if( groupname != '' || groupid != '' ) {
						setTimeout( function(){
							closePublic( groupid );
						}  , 2000 );
					}
         
					setTimeout( function(){
						waitProfile = 1;
					}  , 3000 );
				});
				
				conObj.listen.on('chatBoxOpened', function (event, chatbox) {
					chatbox.$el.attr('data-bid', Base64.encode(chatbox.model.get('jid')));
					var xmpp = chatbox.model.get('jid');
					var jidStr =  xmpp.substring(0, xmpp.indexOf('@')); //xmpp.replace( conferencechatserver , '' );
					setTimeout( function(){
            if( typeof SingleChatName[jidStr] != 'undefined' ){
  						var groupimage = SingleChatName[jidStr]['image'];
  						var grouptitle = SingleChatName[jidStr]['title'];
              chatbox.$el.find( '.profileavatar' ).attr( "style", "background: url('"+groupimage+"');" );
  						chatbox.$el.find( '.chat-title' ).html( grouptitle );
  					
            }
          }  , 1000 );
					//Emoji Picker
					if(waitProfile == 1 ){
						setTimeout( function(){
							hideOpendBox( xmpp, 2 );
						}  , 1000 );
					}
					renderEmoji( chatbox );
				});
				
				conObj.listen.on('chatRoomOpened', function (event, chatbox) {
					chatbox.$el.attr( 'data-bid', Base64.encode(chatbox.model.get('jid')) );

					var xmpp = chatbox.model.get('jid');
					if(waitProfile == 1 ){
						setTimeout( function(){
							hideOpendBox( xmpp, 2 );
						}  , 1000 );
					}
					var jidStr =  xmpp.substring(0, xmpp.indexOf('@')); //xmpp.replace( conferencechatserver , '' );

					if( jidStr ==  groupid ){
						GroupName[jidStr] = '<?php echo $groupname; ?>';
						chatbox.$el.find( '.chat-title' ).html( '<?php echo $groupname; ?>' );
            chatbox.$el.find( '.chat-head-chatroom' ).append( '<a href="javascript:void(0)" data-jid="'+jidStr+'" class="leave-group pull-right" id="leave">Leave</a>' );
            chatbox.$el.addClass( 'pubroom' );
						<?php if( isset( $group_image ) && !empty($group_image) ) { ?>
							chatbox.$el.find( '.profileavatar' ).attr( "style", "background: url('/category_images/<?php echo $group_image; ?>');" );
						<?php } else { ?>
							chatbox.$el.find( '.profileavatar' ).attr( "style", "background: url('"+defaultImage+"');" );
						<?php	} ?>
					} else {
						chatbox.$el.find( '.chat-head-chatroom' ).append( '<a href="javascript:void(0)" data-jid="'+jidStr+'" class="leave-pvt-group pull-right">Leave</a>' );
						if( typeof GroupName[jidStr] != 'undefined' ){
							var groupimage = $('#'+jidStr).data('groupimage');
							chatbox.$el.find( '.profileavatar' ).attr( "style", "background: url('"+groupimage+"');" );
							chatbox.$el.find( '.chat-title' ).html( GroupName[jidStr] );
						} else {
							$.ajax({
								'url' : "/ajax/getgroupdeatils",
								'type' : 'post',
								'async' : false,
								'dataType' : 'json',
								'data' : { group_jid: xmpp },
								'success' : function(data){
									if( data.status == 1 ){
										if( data.title !== undefined ){
											chatbox.$el.find( '.profileavatar' ).attr( "style", "background: url('"+data.image+"');" );
											chatbox.$el.find( '.chat-title' ).html( data.title );
											GroupName[jidStr] = data.title;
										}	
									} else {
										chatbox.close();
										groupChatRefresh( '' );
									}
								}
							});
						}
					}
					renderEmoji( chatbox );	
				});
				
				conObj.listen.on('disconnected', function (event) { 
					location.reload();
				});
                conObj.initialize({                           
                  prebind: true,
                  bosh_service_url: '//<?= Config::get('constants.xmpp_host_Url') ?>:5280/http-bind',
                  keepalive: true,
                  show_desktop_notifications: false,
                  jid: '<?= Auth::User()->xmpp_username ?>@<?= Config::get('constants.xmpp_host_Url') ?>',
                  authentication: 'prebind',
                  prebind_url: "{{url('/ajax/getxmppuser')}}",
                  send_initial_presence:true,
                  visible_toolbar_buttons: {'toggle_occupants':false,'clear':false,'emoticons':false,'call': false},
                  auto_reconnect: true,
        				  ping_interval: 5,
        				  message_carbons: true,
        				  forward_messages: true,
        				  allow_logout: false,
        				  debug: false,
        				  auto_subscribe: true,
                  message_archiving: 'always',
                  auto_join_on_invite:true,
                  allow_chat_pending_contacts: true,
                  notify_all_room_messages: true,
                  //auto_join_rooms: GroupAuto
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

	$( document ).on( 'keydown', '.emoji-wysiwyg-editor' , function(e) {
		if(e.which == 13) {
			var obj = $(this);
			var t = $.Event("keypress");
			t.which = 13; //choose the one you want
			obj.parent().find('textarea').focus();
			obj.parent().find('textarea').trigger(t);
			obj.html( "" );
			setTimeout(function() { obj.focus(); }, 200);
			
		}
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
              'dataType' : 'json',
              'async' : false,
              'data' : {'name':name,'format':'json'},
              'success' : function(data){
                  var friendList = '';
                  if( data.status == 0 ){
                   friendList = data.data;
                  } else {
                    $.each( data.data , function( k, v ){
                      SingleChatName[v.xmpp] = JSON.stringify({image:v.image,title:v.name});

                      friendList +='<li ><a href="javascript:void(0)" title="'+v.name+'" class="list" onclick="openChatbox(\''+v.xmpp+'\',\''+v.name+'\');"><span class="chat-thumb"style="background: url(\''+v.image+'\');"></span><span class="title">'+v.name+'</span></a></li>';

                    });
                  }
                   $("#userslist").html(friendList);
              }       
            });
        });

        $(document).on('keypress', '.searchtxt', function(e){
            var key = e.which;
            if( key == 13 ){
                var name=$('.searchtxt').val();
                  $.ajax({
                    'url' : "{{url('/ajax/searchfriend')}}",
                    'type' : 'post',
                    'dataType' : 'json',
                    'async' : false,
                    'data' : {'name':name,'format':'json'},
                    'success' : function(data){
                        var friendList = '';
                        if( data.status == 0 ){
                         friendList = data.data;
                        } else {
                          $.each( data.data , function( k, v ){
                            SingleChatName[v.xmpp] = JSON.stringify({image:v.image,title:v.name});

                            friendList +='<li ><a href="javascript:void(0)" title="'+v.name+'" class="list" onclick="openChatbox(\''+v.xmpp+'\',\''+v.name+'\');"><span class="chat-thumb"style="background: url(\''+v.image+'\');"></span><span class="title">'+v.name+'</span></a></li>';

                          });
                        }
                         $("#userslist").html(friendList);
                    }       
                  });
            }
        }); 

        $(document).on('click', '#leave', function(){
          $('#leaveModal').modal('show');
        });

        $(document).on('click', '#leave-group', function(e){
          $(this).attr('disabled', true);
          $.post('/ajax/leave-group', {group_jid: groupid}, function(response){
            var getRooms = conObj.rooms.get( groupid+conferencechatserver );
            getRooms.close();
            $('#leaveModal').modal('hide');
            var firstChat = $( '.minimized-chats-flyout .chat-head:first .restore-chat' ).data( 'bid' );
            if( typeof firstChat !== undefined ){
              hideOpendBox( Base64.decode(firstChat) , 1 );
            }
          });
        });
        


        $(document).on('click', '.leave-pvt-group', function(e){
            var PvtJid = $(this).data( 'jid' );
            $('#leave-pvt-group').attr('data-jid' , PvtJid);
            $('#leavePvtModal').modal('show');
        });

        $(document).on('click', '#leave-pvt-group', function(e){
            var PvtJid = $(this).attr( 'data-jid' );
            var getRooms = conObj.rooms.get( PvtJid+conferencechatserver );
            getRooms.close();
            $('#leavePvtModal').modal('hide');
            var firstChat = $( '.minimized-chats-flyout .chat-head:first .restore-chat' ).data( 'bid' );
            if( typeof firstChat !== undefined ){
              hideOpendBox( Base64.decode(firstChat) , 1 );
            }
        });

      });

     function openChatbox(xmpusername,username)
     {
         var ss=conObj.contacts.get(xmpusername+'@<?= Config::get('constants.xmpp_host_Url') ?>');
         if( ss==null ){  
             conObj.contacts.add(xmpusername+'@<?= Config::get('constants.xmpp_host_Url') ?>', username);             
         }
         if( hideOpendBox( xmpusername+'@<?= Config::get('constants.xmpp_host_Url') ?>' , 1 ) ){
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
	/** 
	*	append emoji in chatbox
	**/
	function renderEmoji( chatbox ){
		setTimeout( function(){
				// Initializes and creates emoji set from sprite sheet
				window.emojiPicker = new EmojiPicker({
					emojiable_selector: chatbox.$el.find('[data-emojiable=true]'),
					assetsPath: '/lib/img/',
					popupButtonClasses: 'fa fa-smile-o'
				});
				// Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
				// You may want to delay this step if you have dynamically created input fields that appear later in the loading process
				// It can be called as many times as necessary; previously converted input fields will not be converted again
				window.emojiPicker.discover();
		}  , 2000 );
					
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

/** 
* Bootstrap custom collapsed 
**/
function bootstrapCustomCollapse( collapsedtarget ){
	$('.panel').each( function(){
		var HeadingID = $(this).find('.panel-heading').attr('id');
		if( HeadingID == collapsedtarget ){
			$( this ).find('.panel-heading a').removeClass( 'collapsed' );
			$( this ).find('.panel-heading a').attr('aria-expanded', 'true' );
			$( this ).find( '.panel-collapse' ).addClass( 'in' );
			$( this ).find('.panel-collapse').attr('aria-expanded', 'true' );
			$( this ).find('.panel-collapse').attr('style', '' );
		} else {
			$( this ).find('.panel-heading a').addClass( 'collapsed' );
			$( this ).find('.panel-heading a').attr('aria-expanded', 'false' );
			$( this ).find( '.panel-collapse' ).removeClass( 'in' );
			$( this ).find('.panel-collapse').attr('aria-expanded', 'false' );
			$( this ).find('.panel-collapse').attr('style', 'height: 0px;' );
		}
	});
}

$(document).ready(function() {
	$("#gccollapseThree").click();
	$( document ).on( 'click' , '.restore-chat.chatgroup' , function(){
		var jid = Base64.decode($(this).data( 'bid' ));
		hideOpendBox( jid , 2 );
	});
	$( document ).on( 'click' , '.restore-chat.singlechat' , function(){
		var jid = Base64.decode($(this).data( 'bid' ));
		hideOpendBox( jid , 2 );
	});
});

function hideOpendBox( grpname , actiontype ){
	var resultreturn = true;
	$( '.privatechat' ).each( function(){
		var jid = Base64.decode($(this).data( 'bid' ));
		var getChat = conObj.chats.get(jid);
		if( jid == grpname ){
			if( actiontype == 1 && $(this).css('display') == 'none'){
				getChat.maximize();
			}
			resultreturn = false;
		} else if($(this).css('display') == 'block'){
			getChat.minimize();
			$(this).css('display', 'none');
		}
	});
	
	$( '.chatroom' ).each( function(){
		var jid = Base64.decode($(this).data( 'bid' ));
		var getRooms = conObj.rooms.get(jid);
		if( jid == grpname ){
			if( actiontype == 1 && $(this).css('display') == 'none' ){
				getRooms.maximize();
			}
			resultreturn = false;
		} else if( $(this).css('display') == 'block' ){
			getRooms.minimize();
		}
	});
	return resultreturn;
}

function openChatGroup( grpjid,grpname,groupimage ){
	if( hideOpendBox( grpjid+conferencechatserver , 1 ) ){
		conObj.rooms.open( grpjid+conferencechatserver , '<?= Auth::User()->xmpp_username ?>_<?= Auth::User()->first_name ?> <?= Auth::User()->last_name ?>' );
	}
}
function openFirstChat( grpjid ){
	groupChatRefresh( grpjid );
	if( hideOpendBox( grpjid+conferencechatserver, 1 ) ){
		conObj.rooms.open( grpjid+conferencechatserver , '<?= Auth::User()->xmpp_username ?>_<?= Auth::User()->first_name ?> <?= Auth::User()->last_name ?>' );
		$( '.chatnotification' ).remove();
	}
}
function groupChatRefresh( grpjid ){
	$.ajax({
		'url' : "/ajax/getchatgroup",
		'type' : 'post',
		'async' : false,
		'dataType' : 'json',
		'data' : { group_jid: grpjid },
		'success' : function(data){
			var ChatHtml = '';
			$.each( data.data , function( i, v){
				GroupName[v.group_jid] = v.title;
				var GroupImage = ((v.picture != '' ) ? v.picture : defaultImage);
				ChatHtml += '<li><div style="position:relative;" class="pvt-room-list">';
					ChatHtml += '<a href="/private-group-detail/'+v.id+'">';
					ChatHtml += '<span style="background: url(\''+GroupImage+'\');" class="chat-thumb"></span>';
					ChatHtml += '<span class="title">'+v.title+'</span></a>';
					ChatHtml += '<button id="'+v.group_jid+'" data-groupimage="'+GroupImage+'" class="time" onclick="return openChatGroup(\''+v.group_jid+'\', \''+v.title+'\', \''+GroupImage+'\' );">Chat</button></div></li>';
			});
			$('#gccollapseThree').find( '.chat-user-list' ).html( '<ul>'+ChatHtml+'</ul>' );
		}
	});
}

/**
** use for remove opened chat group with time limit
**/
function removeGroup( chatbox ){
	chatbox.$el.find('.chat-content').hide();
	chatbox.$el.find('.sendXMPPMessage').hide();
	chatbox.$el.find('.chat-area').append( '<div class="chat-notification" >You are removed from group</div>' );
	setTimeout( function(){
		chatbox.close();
		var firstChat = $( '.minimized-chats-flyout .chat-head:first .restore-chat' ).data( 'bid' );
		if( typeof firstChat !== undefined ){
			hideOpendBox( Base64.decode(firstChat) , 1 );
		}
	}  , 5000 );
}

/** 
* show only one public group
**/
function closePublic( grpname ){
	var openChat = 1;
	$( '.privatechat' ).each( function(){
		var jid = Base64.decode($(this).data( 'bid' ));
		var getChat = conObj.chats.get(jid);
		if( $(this).css('display') == 'block' ){
			getChat.minimize();
		}
	});
	
	$( '.chatroom' ).each( function(){
		var jid = Base64.decode($(this).data( 'bid' ));
		var getRooms = conObj.rooms.get(jid);
		var xmpp = jid.substring(0, jid.indexOf('@')); //jid.replace( conferencechatserver , '' );
    if( xmpp == grpname ){
			getRooms.maximize();
			openChat = 0;
		} else {
			var grouptype = xmpp.substr(xmpp.length - 3);
			if( grouptype == 'pub' ){
				getRooms.close();
        $('[data-bid="'+jid+'"]').parent('.chat-head').remove();
			} else if( $(this).css('display') == 'block' ){
				getRooms.minimize();
			}
		}
	});

	if( openChat == 1 ){
		conObj.rooms.open( grpname+conferencechatserver, '<?= Auth::User()->xmpp_username ?>_<?= Auth::User()->first_name ?> <?= Auth::User()->last_name ?>' );
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

$( document ).on('keyup', '.emoji-wysiwyg-editor' ,function(event) {
	$(this).change();
});


</script>
@endsection