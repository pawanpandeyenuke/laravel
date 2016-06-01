  <div class="modal-dialog modal-md">
	    <div class="modal-content" data-value="{{$forumreply->id}}">
		    {!! Form::open(array('url' => '/ajax/editnewforumreply', 'id' => 'postform3', 'files' => true)) !!}
		    	<div class="modal-body text-center">
		        <div class="" id="">
		        	@if(!empty($forumreply->reply))
			        	<div class="edit-post-textarea" data-feed="{{$forumreply->id}}">
							{!! Form::textarea('forumreply', $forumreply->reply, array(
								'id' => 'forumreply', 
								'class' => 'form-control',
								'data-emojiable' => true,
								'placeholder' => 'Type here..',
								'data-emojiable' => 'true',
							)) !!}
			        	</div>
		        	@endif
		        </div>
		      </div>
		      <input type="hidden" value="{{$forumreply->id}}" name="id"></input>
		      <div class="modal-footer">
				{!! Form::button('Cancel', array('id' => 'submit-btn', 'class' => 'btn btn-default', 'data-dismiss' => 'modal')) !!}
				{!! Form::submit('Submit', array('id' => 'submit-btn', 'class' => 'subcomment btn btn-primary')) !!}
		      </div>
	      	{!! Form::close() !!}
	    </div>
	  </div>

<script type="text/javascript" src="/js/bootstrap-filestyle.min.js"></script>
<script src="/lib/js/nanoscroller.min.js"></script>
<script src="/lib/js/tether.min.js"></script>
<script src="/lib/js/config.js"></script>
<script src="/lib/js/util.js"></script>
<script src="/lib/js/jquery.emojiarea.js"></script>
<script src="/lib/js/emoji-picker.js"></script>
<script src="/js/jquery.nicescroll.min.js"></script>

<script>

$(document).ready(function(){

	var myReader = new FileReader();
 $('.subcomment').click(function(){
  $('#edit-modal').modal('hide');
 });
	// Post status updates via ajax call.
	$("#postform3").ajaxForm(function(response) { 
 		var current = $("#postform3");
 		var feedId = current.closest('.edit-post-textarea').data('feed');

		if(response){

			if(response != 'Post something to update.'){

			var data = jQuery.parseJSON(response);
			var comment = data.reply;
			var commentid=data.id;

			if(comment!='')
			{
			jQuery('.forumreplylist').find('#forumreply_'+commentid).find('p').text(comment);
			var original =jQuery('.forumreplylist').find('#forumreply_'+commentid).find('p').html();
			var converted = emojione.toImage(original);
			jQuery('.forumreplylist').find('#forumreply_'+commentid).find('p').html(converted);
			}

			else
			{
				alert("Comment not updated.Comment field can't eb empty");
			}
			 
			 $('#forumreply-edit-modal').modal('hide');

			}

				
		} 
    }); 
});

	//Emoji Picker
	$(function() {
      // Initializes and creates emoji set from sprite sheet
      window.emojiPicker = new EmojiPicker({
        emojiable_selector: '[data-emojiable=true]',
        assetsPath: '/lib/img/',
        popupButtonClasses: 'fa fa-smile-o'
      });
      // Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
      // You may want to delay this step if you have dynamically created input fields that appear later in the loading process
      // It can be called as many times as necessary; previously converted input fields will not be converted again
      window.emojiPicker.discover();
    });


</script>
