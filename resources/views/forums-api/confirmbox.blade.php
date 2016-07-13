 <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
               {{$data['message']}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary btn-ok {{$data['class']}} loading-btn" data-postid="{{$data['postid']}}" data-breadcrum = "{{$data['breadcrum']}}" data-forumpostid="{{$data['forumpostid']}}" data-forumreplyid="{{$data['forumreplyid']}}">Delete</button>
            </div>
        </div>
    </div>