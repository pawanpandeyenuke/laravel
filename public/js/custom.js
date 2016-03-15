

$(document).ready(function(){
	
	$('.StyleScroll').niceScroll();
	
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
 		var current = $("#postform")
		if(response){
			$('#newsfeed').val('');
			// $('#image-holder').hide();
			$('#image-holder img').remove();
			$('#fileUpload').val('');
			
			$('.group-span-filestyle label .badge').html('');

			if(response != 'Post something to update.'){
				$('#postlist').first('.single-post').prepend(response);
				current.parents('.row').find('#newsfeed').text('');
				current.parents('.row').find('.emoji-wysiwyg-editor').text('');
			}
		} 
    }); 


	$(document).on('click', '#cancel-btn', function(){
		$('#newsfeed').val('');
		$('.emoji-wysiwyg-editor').text('');
		$('.badge').hide();
		
		$('#image-holder img').remove();
		$('#fileUpload').val('');
	});

	
	$(document).on('click', '.group-radio', function(){		

		var current = $(this);
		current.closest('.groupcat').find('.selectbox').show();
		var nextcurrent = current.closest('.groupcat').next().find('.selectbox').hide();
		var prevcurrent = current.closest('.groupcat').prev().find('.selectbox').hide();

	});


	$(".post-list .single-post p").each(function() {
		var original = $(this).html();
		// use .shortnameToImage if only converting shortnames (for slightly better performance)
		var converted = emojione.toImage(original);
		$(this).html(converted);
	});


	$(document).on('click', '.like', function(){		
		var _token = $('#postform input[name=_token]').val();
		var feedId = $(this).closest('.single-post').data('value');
		var user_id = $('#user_id').val();
		var current = $(this);

		$.ajax({			
			'url' : 'ajax/webgetlikes',
			'data' : { '_token' : _token, 'feed_id' : feedId, 'user_id' : user_id, 'liked' : 'Yes' },
			'type' : 'post',
			'success' : function(response){

				current.next('label.css-label').find('.countspan').html(response);

				if(response == 0){
					current.next().html('');
					current.next().append('<span class="firstlike">Like</span>');
				}else if(response >= 0){
					current.next('label.css-label').find('.firstlike').html(response+' Likes');
				}

			}			
		});	
	});

	$(document).on('click', '.comment', function(){
		var current = $(this);
		var _token = $('#postform input[name=_token]').val();
		var feedId = $(this).closest('.post-list').data('value');
		var commentData = $(this).closest('.row').find('textarea').val();
		var commented_by = $('#user_id').val();

		// alert(feedId);
		// alert(commentData);
		// alert(commented_by);

		if(commentData){
			$.ajax({			
				'url' : 'ajax/comments/post',
				'data' : { '_token' : _token, 'feed_id' : feedId, 'commented_by' : commented_by, 'comments' : commentData },
				'type' : 'post',
				'success' : function(response){
					var parseresponse = jQuery.parseJSON(response);
					// console.log(response);
					current.closest('.row').find('textarea').val('');

					count = parseresponse.count;
					if(count != 0){
						current.parents('.post-list').find('.pop-post-footer').find('.commentcount').html(count+' Comments');
					}else{
						current.parents('.post-footer').find('.commentcount').html('1 Comment');
					}

					current.parents('.pop-comment-side-outer').find('.comments-list ul').append(parseresponse.comment);
					current.parents('.row').find('.comment-field').text('');
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


	/**
	* Friend request tabs ajax call handling.
	* Ajaxcontroller@getfriendslist
	**/
	$(document).on('click', '.friendstabs', function(){    
		var type = $(this).data('reqtype');
		var current = $(this);
		$.ajax({
			'url' : 'ajax/getfriendslist',
			'data' : {'type' : type},
			'type' : 'post',
			'success' : function(response){
				//alert(response);
				//var type = response.type;
				var getelem = current.closest('.tab-style-no-border').find('.active').find('ul').html(response);
			}
		});
	});


/*	$(document).on('click', '.friendstabs', function(){    
		var type = $(this).data('reqtype');
		var current = $(this);
		$.ajax({
			'url' : 'ajax/getfriendslist',
			'data' : {'type' : type},
			'type' : 'post',
			'success' : function(response){
				var type = response.type;
				var getelem = current.closest('.tab-style-no-border').find('.active').find('ul').html(response.data);
			}
		});
	});*/


	/**
	*	Group chatrooms ajax call handling.
	*	Ajaxcontroller@groupchatrooms
	*/
/*	$(document).on('click', '#groupchatrooms', function(){
		var current = $(this);
		$.ajax({
			'url' : 'ajax/groupchatrooms',
			'type' : 'post',
			// 'data' : {},
			'success' : function(response){
				var html = current.closest('.dashboard-body').find('.col-sm-6').html(response);
				// alert(html);
			}
		});
	});*/


	/**
	*	Group sub chatrooms ajax call handling.
	*	Ajaxcontroller@groupchatrooms
	*/
/*	$(document).on('click', '.groupnext', function(){
		var current = $(this);
		var groupid = $(this).data('parentid');

		var dataval = $(this).data('value');
		// alert(dataval);
		$.ajax({
			'url' : 'ajax/subgroupchats',
			'type' : 'post',
			'data' : { 'groupid' : groupid, 'dataval' : dataval },
			'success' : function(response){
				var html = current.closest('.dashboard-body').find('.col-sm-6').html(response);
				// alert(html);
			}
		});
	});*/
	

	/**
	*	Enter chatrooms ajax call handling.
	*	Ajaxcontroller@enterchatroom
	*/
/*	$(document).on('click', '.enterchat', function(){
		var current = $(this);
		var groupid = $(this).data('parentid');
		var dataval = $(this).data('value');
		console.log(dataval);
        if(dataval.length){
			$.ajax({
				'url' : 'ajax/enterchatroom',
				'type' : 'post',
				'data' : { 'dataval' : dataval },
				'success' : function(response){
					var html = current.closest('.dashboard-body').find('.col-sm-6').html(response);
					// alert(dataval);
				}
			});
        }
	});*/


	$('#state').html('<option value="">State</option>');
	$('#city').html('<option value="">City</option>');


	/**
	*	Get states ajax call handling.
	*	Ajaxcontroller@enterchatroom
	*/
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


	/**
	*	Get cities ajax call handling.
	*	Ajaxcontroller@getCities
	*/
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
 
	/*$('.status-r-btn').on('click',function(){
		if ( $('#status_img_up').is(':checked') ) { 
	    $('.status-img-up').show();
	  }
	  else{
	  	$('.status-img-up').hide();
	  }
	});*/

	$('.status-r-btn').on('click',function(){
		if ( $('#status_img_up').is(':checked') ) { 
	    $('.status-img-up').show();
	    $('.status-btn-cont').hide();
	    $('.status-img-cont').show();
	  }
	  else{
	  	$('.status-img-up').hide();
	  	$('.status-img-cont').hide();
	  	$('.status-btn-cont').show();
	  }
	});
 

	/*
	* Accept request from another user.
	*
	**/
	$(document).on('click','.accept',function()
	{
		var current = $(this);
		var user_id=current.closest('.get_id').data('userid');
		var friend_id=current.closest('.get_id').data('friendid');
		
		$.ajax({
			'url' : '/ajax/accept',
			'type' : 'post',
			'data' : {'user_id' : user_id,'friend_id':friend_id },
			'success' : function(data){
				current.closest('.get_id').find('.accept').hide(200);
				current.closest('.get_id').find('.decline').hide(200);
				current.closest('.get_id').find('.remove').show(500);
			}
		});
	});


	/*
	* Reject request from another user.
	*
	**/
	$(document).on('click','.decline',function()
	{
		var current = $(this);
		var user_id=current.closest('.get_id').data('userid');
		var friend_id=current.closest('.get_id').data('friendid');
		
		$.ajax({
			'url' : '/ajax/reject',
			'type' : 'post',
			'data' : {'user_id' : user_id,'friend_id':friend_id },
			'success' : function(data){
				current.closest('.get_id').find('.accept').hide(200);
				current.closest('.get_id').find('.decline').hide(200);
				current.closest('.get_id').find('.msg').show(500);
			}
		});
	});


	/*
	* Resend request to user.
	*
	**/
	$(document).on('click','.resend',function()
	{
		var current = $(this);
		var user_id=current.closest('.get_id').data('userid');
		var friend_id=current.closest('.get_id').data('friendid');
		
		$.ajax({
			'url' : '/ajax/resend',
			'type' : 'post',
			'data' : {'user_id' : user_id,'friend_id':friend_id },
			'success' : function(data){
				current.closest('.get_id').find('.resend').hide(200);
				current.closest('.get_id').find('.sent').show(500);
			}
		});
	});


	/*
	* Remove request from another user.
	*
	**/
	$(document).on('click','.remove',function()
	{
		var current = $(this);
		var user_id=current.closest('.get_id').data('userid');
		var friend_id=current.closest('.get_id').data('friendid');

		$.ajax({
			'url' : '/ajax/remove',
			'type' : 'post',
			'data' : {'user_id' : user_id,'friend_id':friend_id },
			'success' : function(data){
				current.closest('.get_id').find('.remove').hide(200);
				current.closest('.get_id').find('.msg2').show(500);
			}
		});
	});

});

	$('.popup').fancybox();

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
	