  <div class="modal-dialog modal-md">
  
	    <div class="modal-content" data-value="{{$comment->id}}">
		    {!! Form::open(array('url' => 'ajax/editcomments', 'id' => 'postform2', 'files' => true)) !!}
		    	<div class="modal-body text-center">
		        <div class="" id="">
		        	@if(!empty($comment->comments))
			        	<div class="edit-post-textarea" data-feed="{{$comment->feed_id}}">
							{!! Form::textarea('comments', $comment->comments, array(
								'id' => 'newsfeed', 
								'class' => 'form-control',
								'data-emojiable' => true,
								'placeholder' => 'Type here..',
								'data-emojiable' => 'true',
							)) !!}
			        	</div>
		        	@endif
		        </div>
		      </div>
		      <input type="hidden" value="{{$comment->id}}" name="id"></input>
		      <div class="modal-footer">
				{!! Form::button('Cancel', array('id' => 'submit-btn', 'class' => 'btn btn-default', 'data-dismiss' => 'modal')) !!}
				{!! Form::submit('Upload', array('id' => 'submit-btn', 'class' => 'subcomment btn btn-primary')) !!}
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

// $('.subcomment').click(function(){
//  $('#edit-modal').modal('hide');
// });
	// Post status updates via ajax call.
	$("#postform2").ajaxForm(function(response) { 
 		var current = $("#postform2");
 		var feedId = current.closest('.edit-post-textarea').data('feed');

		if(response){

			if(response != 'Post something to update.'){

			var data = jQuery.parseJSON(response);
			var comment = data[0].comments;
			var commentid=data[0].id;

			if(comment!='')
			{

			jQuery('#postlist').find('#post_'+commentid).find('.comment-text').text(comment);
			var original =jQuery('#postlist').find('#post_'+commentid).find('.comment-text').html();
			var converted = emojione.toImage(original);
			jQuery('#postlist').find('#post_'+commentid).find('.comment-text').html(converted);
			}
			else
			{
				alert("Comment not updated.Comment field can't eb empty");
			}

			 $('#edit-modal').modal('hide');

			}	
		} 
    }); 
});

	//Emoji Picker
	$(function() {
      // Initializes and creates emoji set from sprite sheet
      window.emojiPicker = new EmojiPicker({
        emojiable_selector: '[data-emojiable=true]',
        assetsPath: 'lib/img/',
        popupButtonClasses: 'fa fa-smile-o'
      });
      // Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
      // You may want to delay this step if you have dynamically created input fields that appear later in the loading process
      // It can be called as many times as necessary; previously converted input fields will not be converted again
      window.emojiPicker.discover();
    });
</script>