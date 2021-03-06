  <div class="modal-dialog modal-md">
	    <div class="modal-content" data-value="{{$forumpost->id}}">
		    {!! Form::open(array('url' => '/ajax/editnewforumpost', 'id' => 'postform3', 'files' => true)) !!}
		    	<div class="modal-body text-center">
		        <div class="" id="">
		        	@if(!empty($forumpost->title))
			        	<div class="edit-post-textarea" data-feed="{{$forumpost->id}}">
			        		<?php $titledata = nl2br($forumpost->title); ?>
							{!! Form::textarea('forumtitle', $titledata, array(
								'id' => 'forumpost', 
								'class' => 'form-control',
								'data-emojiable' => true,
								'placeholder' => 'Type here..',
								'data-emojiable' => 'true',
							)) !!}
			        	</div>
		        	@endif
		        </div>
		      </div>
		      <input type="hidden" value="{{$forumpost->id}}" name="id"></input>
		      <div class="modal-footer">
				{!! Form::button('Cancel', array('id' => 'submit-btn', 'class' => 'btn btn-default', 'data-dismiss' => 'modal')) !!}
				{!! Form::submit('Submit', array('id' => 'submit-btn', 'class' => 'subcomment btn btn-primary')) !!}
		      </div>
	      	{!! Form::close() !!}
	    </div>
	  </div>

<!-- <script type="text/javascript" src="{{url('/js/bootstrap-filestyle.min.js')}}"></script>
<script src="{{url('/lib/js/nanoscroller.min.js')}}"></script>
<script src="{{url('/lib/js/tether.min.js')}}"></script>
<script src="{{url('/lib/js/config.js')}}"></script>
<script src="{{url('/lib/js/util.js')}}"></script>
<script src="{{url('/lib/js/jquery.emojiarea.js')}}"></script>
<script src="{{url('/lib/js/emoji-picker.js')}}"></script>
<script src="{{url('/js/jquery.nicescroll.min.js')}}"></script> -->

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
 		var post_id = '<?= $forumpost->id ?>';
		if(response){

			if(response != 'Post something to update.' && response!="rep"){

			var data = jQuery.parseJSON(response);
			var comment = data.title;
			var commentid=data.id;

			if(comment!='')
			{
				var obj = jQuery('.forumpostlist').find('#forumpost_'+commentid);
				obj.find('p').html(comment);
				obj.find('p').html( emojione.toImage(obj.find('p').html()) );
				activateReadmore(obj.find('p'));
				obj.find('.p-date').html('<i class="flaticon-days"></i> '+data.date);
				obj.find('.p-time').html('<i class="flaticon-time"></i> '+data.time);
			}
			else
			{
				alert("Forum Post not updated.Post field can't eb empty");
			}
			 $('#forumpost-edit-modal').modal('hide');
			}
			 else if(response == "rep"){ 
			 	$('#forumpost-edit-modal').find('.modal-footer .subcomment').hide();

			 	$('#forumpost-edit-modal').find('.modal-body').html("<div class ='alert alert-danger'>You can't edit the post because you have recieved a reply.</div>");

			 	jQuery('.forumpostlist').find('#forumpost_'+post_id).find('.f-post-title .fp-action .editforumpost').remove();

			 	jQuery('.forumpostlist').find('#forumpost_'+post_id+' .reply-count').html('Replies (1)'); 
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