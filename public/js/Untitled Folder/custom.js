$(document).ready(function(){

	// $('.post-list').show();
	
	var myReader = new FileReader();

	 $("#fileUpload").on('change', function () {
	 
	     //Get count of selected files
	     var countFiles = $(this)[0].files.length;
	 
	     var imgPath = $(this)[0].value;
	     var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
	     var image_holder = $("#image-holder");
	     image_holder.empty();
	 
	     if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
	         if (typeof (FileReader) != "undefined") {
	 
	             //loop for each file selected for uploaded.
	             for (var i = 0; i < countFiles; i++) {
	 
	                 var reader = new FileReader();
	                 reader.onload = function (e) {
	                     $("<img />", {
	                         "src": e.target.result,
	                             "class": "thumb-image"
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
	$("#postform").ajaxForm(function(response) { 
 
		if(response){
			$('#newsfeed').val('');
			$('#image-holder').hide();
			$('.group-span-filestyle label .badge').hide();

			if(response != 'Post something to update.'){
				$('#postlist').first('.single-post').prepend(response);
			}

		} 

    }); 


	$(document).on('click', '.like', function(){		
		var _token = $('#postform input[name=_token]').val();
		var feedId = $(this).closest('.single-post').data('value');
		var user_id = $('#user_id').val();
		var current = $(this);
		// alert(id);
		$.ajax({			
			'url' : 'api/likes',
			'data' : { '_token' : _token, 'feed_id' : feedId, 'user_id' : user_id, 'liked' : 'Yes' },
			'type' : 'post',
			'success' : function(response){
				var responsedata = jQuery.parseJSON(response);
				if(responsedata.data.status == 'liked'){
					var prev = current.parents('.like-cont').find('span').html();
					var value = prev.split(' ');
					if(value[0] != '' && Number.isInteger(value[0])){
						count = value[0];
						likecount = ++count;
						current.parents('.like-cont').find('span').html(likecount+' Likes');
					}else{
						current.parents('.like-cont').find('span').html('1 Like');
					}
				}else{
					var prev = current.parents('.like-cont').find('span').html();
					var value = prev.split(' ');
					if(value != '' && Number.isInteger(value)){
						count = value[0];
						likecount = --count;
						// alert(likecount);
						current.parents('.like-cont').find('span').html(likecount+' Likes');
					}else{
						current.parents('.like-cont').find('span').html('Like');
					}
				}
			}			
		});	
	});

	$(document).on('click', '.comment', function(){
		var current = $(this);
		var _token = $('#postform input[name=_token]').val();
		var feedId = $(this).closest('.single-post').data('value');
		var commentData = $(this).closest('.row').find('textarea').val();
		var commented_by = $('#user_id').val();
		if(commentData){
			$.ajax({			
				'url' : 'ajax/comments/post',
				'data' : { '_token' : _token, 'feed_id' : feedId, 'commented_by' : commented_by, 'comments' : commentData },
				'type' : 'post',
				'success' : function(response){				
					
					current.closest('.row').find('textarea').val('');

					var prev = current.parents('.post-footer').find('.commentcount').html();
					var value = prev.split(' ');

					count = value[0];
					if(!count.trim()){
						commentcount = ++count;
						current.parents('.post-footer').find('.commentcount').html(commentcount+' Comments');
					}else{
						current.parents('.post-footer').find('.commentcount').html('1 Comment');
					}

					current.parents('.post-comment-cont').find('.comments-list ul').append(response);
					
				}			
			});	
		}
	});


	$(document).on('click', '.popupajax', function(){    

		var feedId = $(this).closest('.single-post').data('value');
		var _token = $('#postform input[name=_token]').val();
		$.ajax({
			'url' : 'ajax/comments/get',
			'data' : { 'feed_id' : feedId, '_token' : _token },
			'type' : 'post',
			'success' : function(response){
				$('#commentajax').html(response);
		        $.fancybox([
		            { href : '#commentajax' }
		        ]);
			}
		});

	});


	$('#state').html('<option value="">State</option>');
	$('#city').html('<option value="">City</option>');

	//Get states ajax call.
	$('#country').change(function(){
		var countryId = $(this).val();
		var _token = $('#searchform input[name=_token]').val();
		$.ajax({			
			'url' : '/ajax/getstates',
			'data' : { 'countryId' : countryId, '_token' : _token },
			'type' : 'post',
			'success' : function(response){				
				$('#state').html(response);
			}			
		});	
	});

	//Get cities ajax call.
	$('#state').change(function(){
		var stateId = $(this).val();
		var _token = $('#searchform input[name=_token]').val();
		$.ajax({			
			'url' : '/ajax/getcities',
			'data' : { 'stateId' : stateId, '_token' : _token },
			'type' : 'post',
			'success' : function(response){
				$('#city').html(response);
			}			
		});	
	});
 
	$('.status-r-btn').on('click',function(){
		if ( $('#status_img_up').is(':checked') ) { 
	    $('.status-img-up').show();
	  }
	  else{
	  	$('.status-img-up').hide();
	  }
	});
 
});

	//Emoji Picker
/*	$(function() {
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
    });*/
	