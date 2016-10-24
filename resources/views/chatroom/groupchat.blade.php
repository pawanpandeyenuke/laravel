@extends('layouts.chat')

@include('panels.meta-data')
@section('title', 'Chat')

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
  top: 665px !important;
  left: 3%;
}
#conversejs .minimized-chats-flyout .chat-head, #conversejs .minimized-chats-flyout .chat-head-chatroom {
   font-size: 12px;
    height: 35px !important;
    margin-top: 5px !important;
    padding: 4px !important;
    width: 135px !important;
}
#conversejs a.close-chatbox-button, #conversejs a.configure-chatroom-button, #conversejs a.toggle-chatbox-button
{
  font-size:7px !important;
  line-height: 8px !important;
}

#conversejs .minimized-chats-flyout.flyout {
    float: left;
    height: 45px !important;
    overflow-y: auto !important;
    position: relative;
    top: 8px;
    width: 97%;
}
#conversejs .chatbox, #conversejs .chatroom 
{
  height:auto !important;
}
.load-pub-user{
  cursor: pointer;
  float: right;
}
</style>

<?php 
$groupid = $group_jid;
$GroupsJidList = $SingleChatList = $PublicGroupUser = array();
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
                            <div class="group-chat-cont">
                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                  <div class="panel panel-default publicgroups">
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
                                           <img src="{{$icon_url}}" alt="{{ $groupname }}" class="img-icon">
                                          <?php } ?>
                                         <b><?php echo ($exception == "private")?"":$groupname; ?></b>
                                         <?php if( $groupname && $exception != 'private' ){ ?>
                                            <i class="fa fa-refresh load-pub-user" aria-hidden="true"></i>
                                         <?php } ?>
                                        </div>
                                        <div class="chat-user-list StyleScroll">
                                          <ul>
                                            @if(!empty($userdata))
                                            @foreach($userdata as $data)

                                              <?php 
                                              $PublicGroupUser[] = $data['user']['xmpp_username'];
                                              $user_picture = !empty($data['user']['picture']) ? $data['user']['picture'] : 'user-thumb.jpg'; ?>

                                              <li >
                                                  <div class='info' data-id="{{$data['user']['id']}}" style="position:relative;" >
                                                    <a title="" @if( $data['user']['id'] != Auth::User()->id) href="{{url('/profile/'.$data['user']['id'])}}" @endif  data-id="{{$data['user']['id']}}" >
                                                        <span style="background: url('{{'/uploads/user_img/'.$user_picture}}');" class="chat-thumb userpic-<?php echo $data['user']['xmpp_username']; ?>"></span>
                                                        <span class="title usertitle-<?php echo $data['user']['xmpp_username']; ?>">{{ $data['user']['first_name'] }} {{ $data['user']['last_name'] }}</span>           
                                                    <?php $SingleChatList['name_'.$data['user']['xmpp_username']] = $data['user']['first_name'].' '.$data['user']['last_name'];
                                                      $SingleChatList['img_'.$data['user']['xmpp_username']] = $user_picture;
                                                      $SingleChatList['user_'.$data['user']['xmpp_username']] = $data['user']['id'];
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
                                  <div class="panel panel-default singlechat">
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
                                        <ul id="userslist">
                                          @foreach($friendObj as $friendsList)
                                            <?php 
                                              $friendsListUser = $friendsList->friends;
                                              // echo '<pre>';print_r($friendsListUser->xmpp_username);die; 
                                              $name = $friendsListUser->first_name.' '.$friendsListUser->last_name; 
                                              /** Friend List Data add in Variable **/
                                              $SingleChatList['name_'.$friendsListUser->xmpp_username] = $name;

                                              $SingleChatList['user_'.$friendsListUser->xmpp_username] = $friendsListUser->id;

                                              $SingleChatList['img_'.$friendsListUser->xmpp_username] = !empty($friendsListUser->picture) ? $friendsListUser->picture : 'user-thumb.jpg';
                                                 
                                            ?>
                                            <li > 
                                              <a href="javascript:void(0)" title="" class="list" onclick="openChatbox(<?= "'".$friendsListUser->xmpp_username."'" ?>,<?= "'".$friendsListUser->first_name."'" ?>);">
                                                <span class="chat-thumb userpic-<?php echo $friendsListUser->xmpp_username; ?>" style="background: url('<?= userImage($friendsListUser) ?>');"></span>
                                                <span class="title usertitle-<?php echo $friendsListUser->xmpp_username; ?>"><?= $name ?></span>
                                              </a>
                                            </li>
                                          @endforeach
                                        </ul>
                                            </div><!--/chat user list-->
                                      </div>
                                    </div>
                                  </div>
                                  <div class="panel panel-default privatechat">
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
                              <span class="chat-thumb grouppic-<?php echo $data['group_jid']; ?>" style="background: url('<?= $group_picture ?>');"></span>
                              <span class="title grouptitle-<?php echo $data['group_jid']; ?>" title="{{$data['title']}}"><?php echo truncatePrivateGroupName($data['title']) ?></span>
                            </a>
                            <button id="<?= $data['group_jid'] ?>" data-groupimage="<?= $group_picture ?>" onclick="return openChatGroup('<?php echo $data['group_jid']; ?>', '<?php echo $data['title']; ?>','<?= $group_picture ?>');" class="groupdatapic-<?php echo $data['group_jid']; ?> time">Chat</button>
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
                <div class="shadow-box bottom-ad"><img src="/images/bottom-ad.jpg" alt="Shop By Temperature" class="img-responsive"></div>
               </div>
        @include('panels.right')
             </div>
        </div>
    </div><!--/pagedata-->
  
<div id="leaveModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Close Group</h4>
      </div>
      <div class="modal-body">
        <p class='text-center'>Are you sure you want to close?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-danger" data-jid="<?php echo $groupid;?>" id='leave-group'>Yes</button>
      </div>
    </div>
  </div>
</div>

<div id="leavePvtModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Close Group</h4>
      </div>
      <div class="modal-body">
        <p class='text-center'>Are you sure you want to close?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-danger" data-jid="" id='leave-pvt-group'>Yes</button>
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
    var profiletitles = <?php echo json_encode($SingleChatList); ?>;
    var PublicGroupUser = <?php echo json_encode($PublicGroupUser); ?>;
  var encoderoomid = '';
    var userImage="{{$userpic}}";
    var ChatImageUrl = "{{url('/uploads/media/chat_images/')}}";
    var defaultUserImage = "{{url('/images/user-thumb.jpg')}}";

    var image_upload_url = "{{url('/ajax/sendimage')}}";
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
  var userImagesUrl = "{{url('/uploads/user_img')}}/";
  var waitProfile = 0;
  var defaultImage = "{{url('/images/post-img-big.jpg')}}";
  var baseUrl = '<?= url('/') ?>';
  var checkActiveGroupUrl = '<?= url('/ajax/isactivemember') ?>';
  var GetProfileUrl = '<?= url('/ajax/profilenameimage') ?>';
  var checkFriendUrl = '<?= url('/ajax/isfriend') ?>';
  var profiletitles = {};
  var myFullname = '<?= Auth::User()->first_name ?> <?= Auth::User()->last_name ?>';
  var messageFetched = 0;
  function webEncode( str ){
    //return Base64.encode( str );
    return str;
  }
  function webDecode( str ){
    //return Base64.decode( str );
    return str;
  }
  

  jQuery(document).ready(function($){
      require(['converse'], function (converse) {
        conObj = converse;

        conObj.listen.on('connected', function (event) {
          console.log( 'connected' );
          setTimeout( function(){
            jQuery('.loader_blk').remove();
            closePublic();
          }, 2000 );
          waitProfile = 1;
        });
        
        conObj.listen.on('chatBoxOpened', function (event, chatbox) {
              chatbox.$el.attr('data-bid', Base64.encode(chatbox.model.get('jid')));
          var xmpp = chatbox.model.get('jid');
          var jidStr =  xmpp.substring(0, xmpp.indexOf('@')); //xmpp.replace( conferencechatserver , '' );

                if( typeof profiletitles['img_'+jidStr] == 'undefined' ){
                  jQuery.ajax({
                    'url' : GetProfileUrl,
                    'type' : 'post',
                    'async' : false,
                    'dataType' : 'json',
                    'data' : { user_jid: jidStr },
                    'success' : function(data){
                      if( typeof data.image != 'undefined' ){
                        profiletitles['img_'+jidStr] = data.image;
                      } else {
                        profiletitles['img_'+jidStr] = defaultImage;
                      }
                      if( typeof data.name != 'undefined' ){
                        profiletitles['name_'+jidStr] = data.name;
                      } else {
                        profiletitles['name_'+jidStr] = jidStr;
                      }
                      profiletitles['user_'+jidStr] = data.user_id;
                    }
                  });
                }

                var singleChatimage = profiletitles['img_'+jidStr];
                var singleChattitle = profiletitles['name_'+jidStr];
                chatbox.$el.find( '.profileavatar' ).attr( "style", "background-image: url('"+userImagesUrl+singleChatimage+"');" );
                chatbox.$el.find( '.chat-title' ).html( singleChattitle );

          //Emoji Picker
          renderEmoji( chatbox );
        });
       
        conObj.listen.on('chatRoomOpened', function (event, chatbox) {
          chatbox.$el.attr( 'data-bid', Base64.encode(chatbox.model.get('jid')) );

          var xmpp = chatbox.model.get('jid');
          var jidStr =  xmpp.substring(0, xmpp.indexOf('@')); //xmpp.replace( conferencechatserver , '' );
          var grouptype = jidStr.substr(jidStr.length - 3);
          if( jidStr ==  groupid ){
            GroupName[jidStr] = "<?php echo $groupname; ?>";
            chatbox.$el.find( '.chat-title' ).html( "<?php echo $groupname; ?>" );
                  chatbox.$el.find( '.chat-head-chatroom' ).append( '<a href="javascript:void(0)" data-jid="'+jidStr+'" class="leave-group pull-right" id="leave">Close</a>' );
                  chatbox.$el.addClass( 'pubroom' );
            <?php if( isset( $group_image ) && !empty($group_image) ) { ?>
              if( grouptype == 'pub' ){
               chatbox.$el.find( '.profileavatar' ).attr( "style", "background: url('/category_images/<?php echo $group_image; ?>');" );
              } else {
                chatbox.$el.find( '.profileavatar' ).attr( "style", "background: url('/uploads/<?php echo $group_image; ?>');" );
              } 
            <?php } else { ?>
              chatbox.$el.find( '.profileavatar' ).attr( "style", "background: url('"+defaultImage+"');" );
            <?php } ?>
          } else if( grouptype == 'pub' ){
                //chatbox.close();
            return;
          } else {
            chatbox.$el.find( '.chat-head-chatroom' ).append( '<a href="javascript:void(0)" data-jid="'+jidStr+'" class="leave-pvt-group pull-right">Close</a>' );
           
            if( typeof GroupName[jidStr] != 'undefined' ){
              var groupimage = jQuery('#'+jidStr).data('groupimage');
              chatbox.$el.find( '.profileavatar' ).attr( "style", "background: url('"+groupimage+"');" );
              chatbox.$el.find( '.chat-title' ).html( GroupName[jidStr] );
            }
           
              jQuery.ajax({
                'url' : "{{url('/ajax/getgroupdeatils')}}",
                'type' : 'post',
                'async' : false,
                'dataType' : 'json',
                'data' : { group_jid: jidStr },
                'success' : function(data){
                  if( data.status == 1 ){
                    if( data.title !== undefined ){
                      chatbox.$el.find( '.profileavatar' ).attr( "style", "background: url('"+data.image+"');" );
                      chatbox.$el.find( '.chat-title' ).html( data.title );
                      GroupName[jidStr] = data.title;
                    } 
                  } else {
                    removeGroup( chatbox );
                    if( typeof GroupName[jidStr] == 'undefined' ){
                      chatbox.$el.find( '.chat-title' ).html( '' );
                    }
                    groupChatRefresh( 'refreshgrouplist' );
                  }
                }
              });
     
          }
          renderEmoji( chatbox ); 
        });
       
        conObj.listen.on('chatBoxClosed', function (event, chatbox) {
          OpenFirstMinChat();
        }); 
        
        conObj.listen.on('chatBoxMaximized', function (event, chatbox) {
         // console.log( 'chatBoxMaximized' );
        }); 


      conObj.listen.on('disconnected', function (event) { 
        location.reload();
      });

        conObj.listen.on('message', function (event, messageXML) { 
          var $message = jQuery(messageXML),
              $forwarded = $message.find('forwarded');
            if ($forwarded.length) {
                $message = $forwarded.children('message');
            }
            var From = $message.attr('from');
            var ChatType = $message.attr('type');
            var GroupJid = From.substring(0, From.indexOf('@'));
            var UserJid  = From.substring( From.indexOf('/') );
            GroupType = GroupJid.substr(GroupJid.length - 3);
            if( GroupType == 'pub' && GroupJid == groupid && ChatType == 'groupchat' ){
              if( jQuery.inArray( UserJid, PublicGroupUser ) == -1 ){
                updatePublicGroup()
              }  
            }    
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
          show_controlbox_by_default: true,
          <?php if( !empty($groupid) && $groupid != ' ' ){ ?>
          auto_join_rooms:[{'jid': groupid+conferencechatserver}]
          <?php } ?>
        });
                   

    });

    jQuery(document).on('click','.invite',function(){
        var current = jQuery(this);
        var user_id = current.closest('.info').data('id');
          
      jQuery.ajax({
        'url' : "{{url('/ajax/sendrequest')}}",
        'type' : 'post',
        'data' : {'user_id' : user_id },
        'success' : function(data){
          current.closest('.info').find('.invite').hide(200);
          current.closest('.info').find('.sentinvite').show(500);
        }
      });
    });


        jQuery(document).on('click','#search',function() {
            var name=jQuery('.searchtxt').val();
            jQuery.ajax({
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
                      if( typeof v.image != 'undefined' ){
                        profiletitles['img_'+v.xmpp] = v.image;
                      } else {
                        profiletitles['img_'+v.xmpp] = defaultImage;
                      }
                      if( typeof v.name != 'undefined' ){
                        profiletitles['name_'+v.xmpp] = v.name;
                      } else {
                        profiletitles['name_'+v.xmpp] = v.xmpp;
                      }
                      profiletitles['user_'+v.xmpp] = v.id;
                      friendList +='<li ><a href="javascript:void(0)" title="'+v.name+'" class="list" onclick="openChatbox(\''+v.xmpp+'\',\''+v.name+'\');"><span class="chat-thumb userpic-'+v.xmpp+'" style="background: url(\'/uploads/user_img/'+v.image+'\');"></span><span class="title usertitle-'+v.xmpp+'">'+v.name+'</span></a></li>';

                    });
                  }
                   jQuery("#userslist").html(friendList);
              }       
            });
        });

        jQuery(document).on('keypress', '.searchtxt', function(e){
            var key = e.which;
            if( key == 13 ){
                var name=jQuery('.searchtxt').val();
                 jQuery.ajax({
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
                            if( typeof v.image != 'undefined' ){
                              profiletitles['img_'+v.xmpp] = v.image;
                            } else {
                              profiletitles['img_'+v.xmpp] = defaultImage;
                            }
                            if( typeof v.name != 'undefined' ){
                              profiletitles['name_'+v.xmpp] = v.name;
                            } else {
                              profiletitles['name_'+v.xmpp] = v.xmpp;
                            }

                            profiletitles['user_'+v.xmpp] = v.id;

                            friendList +='<li ><a href="javascript:void(0)" title="'+v.name+'" class="list" onclick="openChatbox(\''+v.xmpp+'\',\''+v.name+'\');"><span class="chat-thumb userpic-'+v.xmpp+'"style="background: url(\'/uploads/user_img/'+v.image+'\');"></span><span class="title usertitle-'+v.xmpp+'">'+v.name+'</span></a></li>';

                          });
                        }
                         jQuery("#userslist").html(friendList);
                    }       
                  });
            }
        }); 

        jQuery(document).on('click', '#leave', function(){
          jQuery('#leaveModal').modal('show');
        });

        jQuery(document).on('click', '#leave-group', function(e){
          jQuery(this).attr('disabled', true);
          jQuery.post('/ajax/leave-group', {group_jid: groupid}, function(response){
            var getRooms = conObj.rooms.get( groupid+conferencechatserver );
            getRooms.close();
            jQuery('#leaveModal').modal('hide');
            jQuery('.publicgroups .panel-body').html('');
          });
        });
        


        jQuery(document).on('click', '.leave-pvt-group', function(e){
            var PvtJid = jQuery(this).data( 'jid' );
            jQuery('#leave-pvt-group').attr('data-jid' , PvtJid);
            jQuery('#leavePvtModal').modal('show');
        });

        jQuery(document).on('click', '#leave-pvt-group', function(e){
            var PvtJid = jQuery(this).attr( 'data-jid' );
            var getRooms = conObj.rooms.get( PvtJid+conferencechatserver );
            getRooms.close();
            jQuery('#leavePvtModal').modal('hide');
        });

        jQuery(document).on('click', '.load-pub-user', function(e){
          var SpinObj = jQuery(this);
          if (!SpinObj.hasClass('fa-spin')) {
              SpinObj.addClass( 'fa-spin' );
              if( updatePublicGroup() ){
                SpinObj.removeClass( 'fa-spin' );
              }
          }
        });
 });
    
    function updatePublicGroup(){
      jQuery.ajax({
        'url' : "{{url('/ajax/default-group-user')}}",
        'type' : 'post',
        'dataType' : 'json',
        'async' : false,
        'data' : {'group_jid':groupid},
        'success' : function(data){
          jQuery('#gccollapseOne .chat-user-list ul').html( $.parseHTML(data.html) );
          PublicGroupUser = [];
          $.each( data.users , function( k, v ){
             if( typeof v.image != 'undefined' ){
                profiletitles['img_'+k] = v.image;
              } else {
                profiletitles['img_'+k] = defaultImage;
              }
              if( typeof v.name != 'undefined' ){
                profiletitles['name_'+k] = v.name;
              } else {
                profiletitles['name_'+k] = k;
              }
              profiletitles['user_'+k] = v.id;
              PublicGroupUser.push(k);
          });
          
        }       
      });
      return true;
    }


     function openChatbox(xmpusername,username)
     {
        var ss=conObj.contacts.get(xmpusername+'@<?= Config::get('constants.xmpp_host_Url') ?>');
        if( ss==null ){  
          conObj.contacts.add(xmpusername+'@<?= Config::get('constants.xmpp_host_Url') ?>', username);             
        }
        var SingleChat = conObj.chats.open(xmpusername+'@<?= Config::get('constants.xmpp_host_Url') ?>');
        SingleChat.maximize();

        if ( typeof SingleChat !== undefined && SingleChat.get('minimized')) {
	        var basejid = Base64.encode(xmpusername+'@<?= Config::get('constants.xmpp_host_Url') ?>');
	        jQuery( "a[data-bid='"+basejid+"']" )[0].click();
	    }

     }

    /**
    // comment by vc
    function checkChatboxAndChatRoom(obj)
    {
         var id=jQuery(obj).attr('id');
         if(id!='controlbox')
         {
            if(!is_first)
            {                       
        jQuery(obj).find('.icon-minus').click();
            }
            is_first=false; 
         }
     }
    **/
  /** 
  * append emoji in chatbox
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
// Comment By VC
function openChatbox( xmpusername,username ){
   //var chatbox=conObj.chats.get(xmpusername+chatserver);
   //console.log(chatbox);
   var minbox=jQuery("#min-"+xmpusername);
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
  jQuery('.panel').each( function(){
    var HeadingID = jQuery(this).find('.panel-heading').attr('id');
    if( HeadingID == collapsedtarget ){
      jQuery( this ).find('.panel-heading a').removeClass( 'collapsed' );
      jQuery( this ).find('.panel-heading a').attr('aria-expanded', 'true' );
      jQuery( this ).find( '.panel-collapse' ).addClass( 'in' );
      jQuery( this ).find('.panel-collapse').attr('aria-expanded', 'true' );
      jQuery( this ).find('.panel-collapse').attr('style', '' );
    } else {
      jQuery( this ).find('.panel-heading a').addClass( 'collapsed' );
      jQuery( this ).find('.panel-heading a').attr('aria-expanded', 'false' );
      jQuery( this ).find( '.panel-collapse' ).removeClass( 'in' );
      jQuery( this ).find('.panel-collapse').attr('aria-expanded', 'false' );
      jQuery( this ).find('.panel-collapse').attr('style', 'height: 0px;' );
    }
  });
}
/**
//comment By VC
jQuery(document).ready(function() {
  jQuery("#gccollapseThree").click();
  jQuery( document ).on( 'click' , '.restore-chat.chatgroup' , function(){
    var jid = Base64.decode(jQuery(this).data( 'bid' ));
    hideOpendBox( jid , 2 );
  });
  jQuery( document ).on( 'click' , '.restore-chat.singlechat' , function(){
    var jid = Base64.decode(jQuery(this).data( 'bid' ));
    hideOpendBox( jid , 2 );
  });
});
**/
function hideOpendBox( grpname , actiontype ){
  var resultreturn = true;
  /**
  // Comment By VC
  jQuery( '.privatechat' ).each( function(){
    var jid = Base64.decode(jQuery(this).data( 'bid' ));
    var getChat = conObj.chats.get(jid);
    if( jid == grpname ){
      if( actiontype == 1 && jQuery(this).css('display') == 'none'){
        getChat.maximize();
      }
      resultreturn = false;
    } else if(jQuery(this).css('display') == 'block'){
      getChat.minimize();
      jQuery(this).css('display', 'none');
    }
  });
  
  jQuery( '.chatroom' ).each( function(){
    var jid = Base64.decode(jQuery(this).data( 'bid' ));
    var getRooms = conObj.rooms.get(jid);
    if( jid == grpname ){
      if( actiontype == 1 && jQuery(this).css('display') == 'none' ){
        getRooms.maximize();
      }
      resultreturn = false;
    } else if( jQuery(this).css('display') == 'block' ){
      getRooms.minimize();
    }
  });
  **/
  return resultreturn;

}

function openChatGroup( grpjid,grpname,groupimage ){
  var openNewGroup = conObj.rooms.open( grpjid+conferencechatserver );
    if (openNewGroup.get('minimized')) {
        var basejid = Base64.encode(grpjid+conferencechatserver);
        jQuery( "a[data-bid='"+basejid+"']" )[0].click();
    }
}
function openFirstChat( grpjid ){
  groupChatRefresh( grpjid );
  var NewGroup = conObj.rooms.open( grpjid+conferencechatserver );
  jQuery( '.chatnotification' ).remove();
}

function groupChatRefresh( grpjid ){
  jQuery.ajax({
    'url' : "/ajax/getchatgroup",
    'type' : 'post',
    'async' : false,
    'dataType' : 'json',
    'data' : { group_jid: grpjid },
    'success' : function(data){
      var ChatHtml = '';
      jQuery.each( data.data , function( i, v){
        GroupName[v.group_jid] = v.title;
        var GroupImage = ((v.picture != '' ) ? v.picture : defaultImage);

        ChatHtml += '<li><div style="position:relative;" class="pvt-room-list">';
          ChatHtml += '<a href="/private-group-detail/'+v.id+'">';
          ChatHtml += '<span style="background: url(\''+GroupImage+'\');" class="chat-thumb grouppic-'+v.group_jid+'"></span>';
          ChatHtml += '<span class="title grouptitle-'+v.group_jid+'">'+v.title+'</span></a>';
          ChatHtml += '<button id="'+v.group_jid+'" data-groupimage="'+GroupImage+'" class="time groupdatapic-'+v.group_jid+'" onclick="return openChatGroup(\''+v.group_jid+'\', \''+v.title+'\', \''+GroupImage+'\' );">Chat</button></div></li>';
      });
      jQuery('#gccollapseThree').find( '.chat-user-list' ).html( '<ul>'+ChatHtml+'</ul>' );
    }
  });
}

/**
** use for remove opened chat group with time limit
**/
function removeGroup( chatbox ){
  chatbox.$el.find('.chat-content').hide();
  chatbox.$el.find('.sendXMPPMessage').hide();
  chatbox.$el.find('.chat-notification').remove();
  chatbox.$el.find('.chat-area').append( '<div class="chat-notification" >You are removed from group<a href="javascript:void(0)" style="float: none;" class="close-chatbox-button" > Close Now</a></div>' );
  setTimeout( function(){
      chatbox.close();
  }  , 5000 );
}

/** 
* show only one public group
**/
function OpenFirstMinChat(  ){
  if(  jQuery('.chatbox:visible').length == 0 ){
      var firstChatObj = jQuery('.minimized-chats-flyout .chat-head:first .restore-chat');
      var firstChat =  firstChatObj.attr( 'data-bid' );
      if( typeof firstChat === "string" ){
        if( firstChatObj.hasClass( "singlechat" ) ) {
          var chatbox = conObj.chats.get( Base64.decode(firstChat) );
        } else {
          var chatbox = conObj.rooms.get( Base64.decode(firstChat) );
        }
        if( typeof chatbox !== undefined ){
          chatbox.maximize();
        }
      }
  }
}

/** 
* show only last chatbox
**/
function OpenLastMinChat(  ){
  if(  jQuery('.chatbox:visible').length == 0 ){
      var firstChatObj = jQuery('.minimized-chats-flyout .chat-head:last .restore-chat');
        firstChatObj[0].click();
  }
}
/** 
* show only one public group
**/
function closePublic( ){
  var openChat = 1;
  jQuery( '.chatroom' ).each( function(){
    var jid = Base64.decode(jQuery(this).data( 'bid' ));
    var xmpp = jid.substring(0, jid.indexOf('@'));
    var grouptype = xmpp.substr(xmpp.length - 3);
    if( grouptype == 'pub' && xmpp !=  groupid  ){
        var publicRoom = conObj.rooms.get(jid);
        publicRoom.close();
    } else if( grouptype == 'pvt'  && typeof GroupName[xmpp] == 'undefined' ){
      var pvtRoom = conObj.rooms.get(jid);
          pvtRoom.close();
    }
  });
  jQuery( '.chatgroup' ).each( function(){
    var jid = Base64.decode(jQuery(this).data( 'bid' ));
    var xmpp = jid.substring(0, jid.indexOf('@'));
    var grouptype = xmpp.substr(xmpp.length - 3);
    if( grouptype == 'pub' && xmpp !=  groupid  ){
        var publicRoom = conObj.rooms.get(jid);
        publicRoom.close();
    } else if( grouptype == 'pvt'  && typeof GroupName[xmpp] == 'undefined' ){
      var pvtRoom = conObj.rooms.get(jid);
          pvtRoom.close();
    }
  });
  OpenLastMinChat();
}

jQuery('.status-r-btn').on('click',function(){
  if ( jQuery('#status_img_up').is(':checked') ) {
    jQuery('.status-img-up').show();
  } else {
    jQuery('.status-img-up').hide();
  }
});

jQuery('.dropdown.keep-open').on({
  "shown.bs.dropdown": function() { this.closable = false; },
  "click":             function() { this.closable = true; },
  "hide.bs.dropdown":  function() { return this.closable; }
});

jQuery( document ).on('keyup', '.emoji-wysiwyg-editor' ,function(event) {
  jQuery(this).change();
});

</script>

<script >
window.localStorage.setItem('FSRefreshOtherTab', true);

function storageChangee(event) {
    if(event.key == 'FSRefreshOtherTab' && event.newValue == 'false') {
    window.location.href="/group";
    }
}
window.addEventListener('storage', storageChangee, false);
window.localStorage.setItem('FSRefreshOtherTab', false);
</script>
@endsection