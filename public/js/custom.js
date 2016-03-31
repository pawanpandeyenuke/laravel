

$(document).ready(function(){
	
	// $('.StyleScroll').niceScroll();
	
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
 		var current = $("#postform");
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
				loadImg();
			}
			
		} 
    }); 


/*	// Update profile fields via ajax call.
	$("#profilesave").ajaxForm(function(response) { 
 		var current = $("#profilesave");
		if(response){
			// alert('custom.js');
		} 
    }); */

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

	$(".post-list .single-post div").each(function() {
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

				if(response == 0){
					// current.prop('checked', 'false');
					$(this).prop('checked', 'false');
					jQuery("#page-"+feedId).html('');
					jQuery("#popup-"+feedId).html('');
				}else{
					// current.prop('checked', 'false');
					$(this).prop('checked', 'true');
					jQuery("#page-"+feedId).html(response);
					jQuery("#popup-"+feedId).html(response);					
				}

				//current.next('label.css-label').find('.countspan').html(response);

/*				if(response == 0){
					current.next().html('');
					current.next().append('<span class="firstlike">Like</span>');
				}else if(response >= 0){
					current.next('label.css-label').find('.firstlike').html(response+' Likes');
				}*/

			}			
		});	
	});

	$(document).on('click', '.comment', function(){

		var current = $(this);
		var _token = $('#postform input[name=_token]').val();
		var feedId = $(this).closest('.post-comment').data('value');
		var commentData = $(this).closest('.post-comment').find('textarea').val();
		var commented_by = $('#user_id').val();
		
		
 
		if(commentData){
			$.ajax({			
				'url' : 'ajax/comments/post',
				'data' : { '_token' : _token, 'feed_id' : feedId, 'commented_by' : commented_by, 'comments' : commentData },
				'type' : 'post',
				'success' : function(response){

					var parseresponse = jQuery.parseJSON(response);
					
					
					jQuery("#pagecomment-"+feedId).append(parseresponse.comment);
					current.closest('.row').find('textarea').val('');

					count = parseresponse.count;
					if(count != 0){
						current.parents('.post-list').find('.pop-post-footer').find('.commentcount').html(count+' Comments');
						current.parents('.post-footer').find('.commentcount').html(count+' Comments');
						current.parents('#AllCommentNew').find('.commentcount').html(count+' Comments');
					}else{
						current.parents('.post-footer').find('.commentcount').html('1 Comment'); 
					}
	
					// var commCount = current.parents('.pop-comment-side-outer').find('.comments-list ul li').length;
					// console.log(commCount);
					// current.parents('.post-comment-cont').find('.comments-list ul').append(parseresponse.comment);

					current.parents('.pop-comment-side-outer').find('.comments-list ul').append(parseresponse.comment);
					current.parents('.row').find('.comment-field').text('');
					current.parents('#AllCommentNew').find('.comments-list ul').append(parseresponse.comment);
					current.parents('#AllCommentNew').find('.comment-field').text('');
					loadImg();
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
 

	$(document).on('click', '.postpopupajax', function(){    

		var feedId = $(this).closest('.single-post').data('value');
		var _token = $('#postform input[name=_token]').val();
		$.ajax({
			'url' : 'ajax/post/get',
			'data' : { 'feed_id' : feedId, '_token' : _token },
			'type' : 'post',
			'success' : function(response){
				$('#AllCommentNew').html(response);
		        $.fancybox([
		            { href : '#AllCommentNew' }
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



	/**
	*	Delete posts on ajax call handling.
	*	Ajaxcontroller@deletepost
	*/

	$(document).on('click', '.post-delete', function(){
		var commentId = $(this).closest('li').data('value'); 
		var feedId = $(this).closest('.single-post').data('value');
		$.ajax({
			'url' : 'ajax/deletebox',
			'data' : {'commentId':commentId, 'feedId' : feedId, 'class' : 'postdelete'},
			'type' : 'post',
			'success' : function(response){
				if(response){
					$("#modal").append(response);
					$("#modal").modal();
				}
			}
		});
		$("#modal").html('');
	});
	
	$(document).on('click', '.postdelete', function(){
		var current = $('.postdelete');
		var feedId = current.closest('.modal-content').data('feedid');
		// alert(feedId);
		$.ajax({
			'url' : 'ajax/deletepost',
			'data' : { 'postId' : feedId },
			'type' : 'post',
			'success' : function(response){
				jQuery("#post_"+feedId).hide(200);
			}
		});
	});


	/**
	*	Delete comments on ajax call handling.
	*	Ajaxcontroller@deletecomments
	*/	
	$(document).on('click', '.comment-delete', function(){
		var commentId = $(this).closest('li').data('value'); 
		var feedId = $('.single-post').data('value');
		// alert(feedId);
		$.ajax({
			'url' : 'ajax/deletebox',
			'data' : {'commentId':commentId, 'feedId' : feedId, 'class' : 'deletecomment'},
			'type' : 'post',
			'success' : function(response){
				if(response){
					$("#modal").append(response);
					$("#modal").modal();
				}
			}
		});
		$("#modal").html('');
	});

	$(document).on('click', '.deletecomment', function(){		
		var current = $('.deletecomment');
		var commentId = current.closest('.modal-content').data('value');
		var feedId = current.closest('.modal-content').data('feedid');
		$.ajax({
			'url' : 'ajax/deletecomments',
			'data' : { 'commentId' : commentId, 'feedId' : feedId },
			'type' : 'post',
			'success' : function(response){
				jQuery("#post_"+commentId).remove();
				jQuery("#AllCommentNew").find("#post_"+commentId).remove();
				jQuery("#AllComment").find("#post_"+commentId).remove();				

				if(response != 0){
					jQuery("#AllCommentNew").find(".commentcount").html(response+' Comments');
					jQuery("#AllComment").find(".commentcount").html(response+' Comments');
					jQuery("#post_"+feedId).find('.commentcount').html(response+' Comments');
				}else{
					jQuery("#post_"+feedId).find('.commentcount').html('Comment'); 
					jQuery("#AllCommentNew").find(".commentcount").html('Comment');
					jQuery("#AllComment").find(".commentcount").html('Comment');
				}
			}
		});
	});


	/**
	*	Edit posts on ajax call handling.
	*	Ajaxcontroller@editpost
	*/
	$(document).on('click', '.edit-post', function(){
		var current = $(this);
		var postid = current.closest('.single-post').data('value'); 
		
		$.ajax({
			'url' : 'ajax/editpost',
			'data' : { 'postid' : postid },
			'type' : 'post',
			'success' : function(response){
				$('#edit-modal').append(response);
				$("#edit-modal").modal();
			}
		});
		$('#edit-modal').html('');
	});

/*	$(document).on('click', '#editpostdata', function(){
		var current = $(this);
		var postid = current.closest('.modal-content').data('value'); 
		var postid = current.closest('.modal-content').find('#imageholder img').data('value'); 
		
		$.ajax({
			'url' : 'ajax/editpost',
			'data' : { 'postid' : postid,  },
			'type' : 'post',
			'success' : function(response){
				$('#edit-modal').append(response);
				$("#edit-modal").modal();
			}
		});
		$('#edit-modal').html('');
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
	* Invite user to chat by sending friend request.
	*
	**/
	$(document).on('click','.invite',function(){
		var current = $(this);
		var user_id=current.closest('.get_id').data('userid');
		//var friend_id=current.closest('.get_id').data('friendid');
		
		$.ajax({
			'url' : 'ajax/sendrequest',
			'type' : 'post',
			'data' : {'user_id' : user_id },
			'success' : function(data){
				current.closest('.get_id').find('.invite').hide(200);
				current.closest('.get_id').find('.sent').show(500);
			}
		});
	});


	/*
	* Search friends tab wise.
	*
	**/
	$(document).on('click', '.search-btn-small', function(){    
			
		var type = $(this).data('reqtype');
		var current = $(this);
		var name=current.closest('.search-box').find('.searchtabtext').val();

		//alert('Hello');
		
		$.ajax({
			'url' : 'ajax/searchtabfriend',
			'data' : {'type' : type,'name' : name},
			'type' : 'post',
			'success' : function(response){
				//alert(response);
				//var type = response.type;
				var getelem = current.closest('.tab-style-no-border').find('.active').find('ul').html(response);
			}
		});
	});


	$(document).on('keypress', '.searchtabtext', function(e){
		var key = e.which;
		if(key == 13){
			var type = $(this).next('.search-btn-small').data('reqtype');
			var current = $(this);
			var name=current.val();
	
			$.ajax({
				'url' : 'ajax/searchtabfriend',
				'data' : {'type' : type,'name' : name},
				'type' : 'post',
				'success' : function(response){
					var getelem = current.closest('.tab-style-no-border').find('.active').find('ul').html(response);
				}
			});
		}
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



	/*
	* Profile data save on edit of profile.
	*
	**/

$(document).on('click','.save-profile-changes',function()
	{
		var current = $(this);
		var first_name=$('.name1').val();
		var last_name=$('.name2').val();
		var country=$('.country').val();
		var state=$('.state').val();
		var city=$('.city').val();
		var birthday=$('.birthday').val();
		var phone_no=$('.contact').val();
var gender;
		if ( $('#radio1').is(':checked') )
		{
			gender=$('#radio1').val();
		}
		if ( $('#radio2').is(':checked') )
		{
			gender=$('#radio2').val();
		}
		
var marital_status;	
			
		if ( $('#radio3').is(':checked') )
		{
		   marital_status=$('#radio3').val();
		}
		if ( $('#radio4').is(':checked') )
		{
			marital_status=$('#radio4').val();
		}
		
		var education_level=$('.educationlevel').val();//array index
//alert(education_level);
		var specialization=$('.specialization').val();//array index
		var graduation_year_from=$('.gradyearfrom').val();//array index
		var graduation_year_to=$('.gradyearto').val();//array index
var currently_studying;//name
		if ( $('#radios1').is(':checked') )
		{
		   currently_studying=$('#radios1').val();
		}
		if ( $('#radios2').is(':checked') )
		{
			currently_studying=$('#radios2').val();
		}
		var education_establishment=$('.educationestablishment').val();//name
		var country_of_establishment=$('.educationcountry').val();//id
		var job_area=$('.jobarea').val();//id
		var job_category=$('.jobcategory').val();//name

			
		$.ajax({
			'url' : '/ajax/profilesave',
			'type' : 'post',
			'data' : {'first_name':first_name,'last_name':last_name,'country':country,'state':state,'city':city,'birthday':birthday,
                                 'gender':gender,'phone_no':phone_no,'marital_status':marital_status,'education_level':education_level,
				'specialization':specialization,'graduation_year_from':graduation_year_from,'graduation_year_to':graduation_year_to,
				'currently_studying':currently_studying,'education_establishment':education_establishment,
				'country_of_establishment':country_of_establishment,
				'job_area':job_area,
				'job_category':job_category},
			'success' : function(data){
				
			}
		});
	});


$(document).on('change','#jobarea',function()
	{
		var current = $(this);
		var jobarea = current.val();

		$.ajax({
			'url' : '/ajax/jobcategory',
			'type' : 'post',
			'data' : {'jobarea':jobarea },
			'success' : function(data){
				$('#jobcategory').html(data);
			}	
		});
	});


	//disabling texts for mobile fields
	$(document).on('keypress','.numeric,input[type="number"]', function(evt){
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode == 46) {
			return true;
		}
		
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	});
	
	$('.numeric,input[type="number"]').bind('paste drop',function(e){
		e.preventDefault();
	});






});

	$('.popup').fancybox();

	$(function() {
		loadImg();
    });
	
	function loadImg()
	{
		window.emojiPicker = new EmojiPicker({
			emojiable_selector: '[data-emojiable=true]',
			assetsPath: 'lib/img/',
			popupButtonClasses: 'fa fa-smile-o'
      	});
      window.emojiPicker.discover();
      //alert(6);
	}

