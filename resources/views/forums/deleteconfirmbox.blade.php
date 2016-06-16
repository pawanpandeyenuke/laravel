    
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <b><font size="4"> {{$data['heading']}} </font></b>
            </div>
            <div class="modal-body">
               {{$data['message']}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary btn-ok {{$data['class']}}" value="{{$data['id']}}" data-forumpostid = "{{$data['reply_post_id']}}" data-breadcrum = "{{$data['breadcrum']}}">Delete</button>
            </div>
        </div>
    </div>
