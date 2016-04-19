  <div class="modal-dialog modal-md">
	    <div class="modal-content" data-value="{{$posts->id}}">
		    {!! Form::open(array('url' => 'ajax/editposts', 'id' => 'postform1', 'files' => true)) !!}
		    	<div class="modal-body text-center">
		        <div class="" id="">
		        	@if(!empty($posts->message))
			        	<div class="edit-post-textarea">
							{!! Form::textarea('message', $posts->message, array(
								'id' => 'newsfeed', 
								'class' => 'form-control',
								'data-emojiable' => true,
								'placeholder' => 'What’s on your mind?',
								'data-emojiable' => 'true',
							)) !!}
			        	</div>
		        	@endif
	        	
		        	<div class="edit-img-outer">
		        		@if(!empty($posts->image))
			        		<div id="imageholder">
			        			<img src="uploads/{{$posts->image}}" class="img-responsive">
			        		</div>
			        	@endif
		        		<div class="img-update-cont">
						    {!! Form::file('image', array(
						    	'id' => 'fileUpload1',
						    	'class' => 'filestyle',
						    	'data-iconName' => 'glyphicon glyphicon-picture',
						    	'data-input' => 'false',
						    	'data-icon' => 'true',
						    	'data-buttonText' => '',
						    	'data-buttonName' => 'btn-primary'
						    )) !!}
		        		</div>
		        	</div>
	        	
		        </div>
		      </div>
		      <input type="hidden" value="{{$posts->id}}" name="id"></input>
		      <div class="modal-footer">
				{!! Form::button('Cancel', array('id' => 'submit-btn', 'class' => 'btn btn-default', 'data-dismiss' => 'modal')) !!}
				{!! Form::submit('Upload', array('id' => 'submit-btn', 'class' => 'btn btn-primary')) !!}
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

	 $("#fileUpload1").on('change', function () {

	     //Get count of selected files
	     var countFiles = $(this)[0].files.length;
	 
	     var imgPath = $(this)[0].value;
	     var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
	     var image_holder = $("#imageholder");
	     image_holder.empty();
	 
	     if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
	         if (typeof (FileReader) != "undefined") {
	 
	             //loop for each file selected for uploaded.
	             for (var i = 0; i < countFiles; i++) {
	 
	                 var reader = new FileReader();
	                 reader.onload = function (e) {
	                     $("<img />", {
	                         "src": e.target.result,
	                             "class": "thumb-image img-responsive"
	                     }).appendTo(image_holder);
	                 }
	 
	                 image_holder.show();
	                 reader.readAsDataURL($(this)[0].files[i]);
	             }
	 
	         } else {
	             alert("This browser does not support FileReader.");
	         }
	     } else {
	         alert("Please select only images");
	     }
	});


	// Post status updates via ajax call.
	$("#postform1").ajaxForm(function(response) { 
 		var current = $("#postform1")
		if(response){

			if(response != 'Post something to update.'){
				var data = jQuery.parseJSON(response);
				var message = data[0].message;
				var image = data[0].image;
				var postid = data[0].id;
				// console.log(data[0].message);
				if(message)
				{
					jQuery('#postlist').find('#post_'+postid).find('.post-data p').text(message);

						var original =$('#post_'+postid+' .post-data').first('p').html();		
						var converted = emojione.toImage(original);
						$('#post_'+postid+' .post-data').first('p').html(converted);	
				}

				if(image){
					var url = document.location.origin+'/uploads/'+image;
					jQuery('#postlist').find('#post_'+postid).find('.post-data img').prop('src', url);
				}
				$('#edit-modal').modal('hide');
				// 	alert('asdfas');
/*				var postid = current.closest('.modal-content').data('value'); 
				jQuery('#postlist').find('#post_'+postid).find('.post-data p').text(response);
				// $('#postlist').first('.single-post').prepend(response);
				$('#edit-modal').modal('hide');*/

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