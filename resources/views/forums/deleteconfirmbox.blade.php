<?php
if($data['class'] == 'userleave')
	$text = "Leave";
else
  $text = "Delete";
?>    
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
               {{$data['message']}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary btn-ok {{$data['class']}}" data-gid="{{$data['gid']}}" value="{{$data['id']}}" data-forumpostid = "{{$data['reply_post_id']}}" data-breadcrum = "{{$data['breadcrum']}}">{{$text}}</button>
            </div>
        </div>
    </div>

