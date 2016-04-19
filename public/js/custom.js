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
				var original =$('.single-post .post-data').first('p').html();		
		        var converted = emojione.toImage(original);
		        $('.single-post .post-data').first('p').html(converted);	

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

	loadOrgionalImogi();

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
				 var check="";
				if(response == 0){
					jQuery("#page-"+feedId).html('');
					jQuery("#popup-"+feedId).html('');
				    check=false;
				}else{					
					jQuery("#page-"+feedId).html(response);
					jQuery("#popup-"+feedId).html(response);	
					check=true;								
				}				
                     var idlike=current.attr('id'); 
                     if(idlike!='')    
                     {
                     	var id1=idlike.split('-');
                     	if(id1[0]=='popup1')
                     	{
                     		jQuery('#'+id1[1]).prop('checked', check);
                     		//alert('#'+id1[1]);
                     	}
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
		var popup=current.closest('.pop-post-comment').data('value');

 
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

					current.parents('.pop-comment-side-outer').find('.comments-list ul').append(parseresponse.comment);
					current.parents('.row').find('.comment-field').text('');
					current.parents('#AllCommentNew').find('.comments-list ul').append(parseresponse.comment);
					current.parents('#AllCommentNew').find('.comment-field').text('');

					
					var original =jQuery("#pagecomment-"+feedId+" li .comment-text").last().html();
				    	var converted = emojione.toImage(original);
					jQuery("#pagecomment-"+feedId+" li .comment-text").last().html(converted);

			if(popup==feedId)

				{
					var original1=jQuery("#popupcomment-"+feedId+" li .comment-text").last().html();
					var converted1 = emojione.toImage(original1);
					jQuery("#popupcomment-"+feedId+" li .comment-text").last().html(converted1);
				}

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
				// var getelem2 = current.closest('.tab-style-no-border').find('.active').find('loading-text');
				// console.log(getelem2);
				// current.closest('.tab-style-no-border').find('.active').find('load-btn').find('.loading-text').show();
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


		$(document).on('click', '.edit-comment', function(){
		var commentId = $(this).closest('li').data('value'); 
		var feedId = $('.single-post').data('value');

	
		$.ajax({
			'url' : 'ajax/editcomment',
			'data' : {'commentId':commentId, 'feedId' : feedId},
			'type' : 'post',
			'success' : function(response){
				$('.fancybox-overlay').hide();
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


	$(document).on('change','#jobarea',function(){

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

/*
 Broadcast contacts add

*/

	$(".multiple-slt").select2();

	/**
	*	View more friends ajax call handling.
	*	Ajaxcontroller@viewMoreFriends
	*/
	var pageid = 2;
	$(document).on('click','.load-more-friend',function(){
		$('.loading-text').hide();
		$('.loading-img').show();
		var current = $(this);
		//var
		var reqType = current.closest('.friends-list').find('.active').data('value');
		var abc=current.closest('.friends-list').find('ul.counting').children('li').length;
		//alert(abc);
		$.ajax({
			'url' : '/ajax/viewmorefriends',
			'type' : 'post',
			'data' : { 'pageid': pageid, 'reqType': reqType },
			'success' : function(data){
				if(data != 'No more results'){
					pageid = pageid + 1;
					$('.loading-text').show();
					$('.loading-img').hide();
					current.closest('.friends-list').find('.active').find('ul').append(data);
				}else{
					var currentobj = current.find('.loading-text');
					currentobj.text('No more results');
					current.removeClass('load-more-friend');
				}
			}	
		});
	});

	$("#up_imgs").fileinput({
    uploadUrl: "/file-upload-batch/2",
    allowedFileExtensions: ["jpg", "png", "gif"],
    minImageWidth: 30,
    minImageHeight: 30,
    showCaption: false,
	});

/*
 Scrollbar

*/
		$('.bcast-message-list').niceScroll();

	/**
	*	View more posts ajax call handling.
	*	Ajaxcontroller@viewMorePosts
	*/
	var pageid = 2;
	$(document).on('click','.dashboard-load',function(){
		var current = $(this);
		$.ajax({
			'url' : '/ajax/viewmoreposts',
			'type' : 'post',
			'data' : { 'pageid': pageid },
			'success' : function(data){
				if(data){
					pageid = pageid + 1;
					$('#postlist').last('.single-post').append(data);
					loadImg();
					loadOrgionalImogi();


				}else{
					current.find('span').remove();
					current.append('<span>No more posts</span>');
				}
			}	
		});
	});



}); 	// Document ready closed..

/*
 Broadcast delete

*/
	$(document).on('click','.broadcastdel',function()
	{
		var current = $(this);
		var id=current.val();

		$.ajax({
			'url' : '/ajax/delbroadcast',
			'type' : 'post',
			'data' : {'bid' : id},
			'success' : function(data){
		 
				current.closest('.single-list').hide();
				
				//current.closest('.get_id').find('.msg2').show(500);
			}
		});
	});

		/*
		 Broadcast Message Button.
		*/
		$(document).on('click','.broadcastbtn',function()
	{
		var current = $(this);
		var bid=current.val();
		var msg=$('.broadcastmsg').val();
		//var friend_id=current.closest('.get_id').data('friendid');

		$.ajax({
			'url' : '/ajax/sendbroadcast',
			'type' : 'post',
			'data' : {'msg':msg,'bid':bid},
			'success' : function(data){
				$("#bmsg").append(data);
				$('.broadcastmsg').val('');
				//current.closest('.get_id').find('.msg2').show(500);
			}
		});
	});



/*
 Private Group delete & Delete user from private group

*/
	$(document).on('click','.delprivategroup',function()
	{
		var current = $(this);
		var id=current.val();
	
		$.ajax({
			'url' : '/ajax/delprivategroup',
			'type' : 'post',
			'data' : {'pid' : id},
			'success' : function(data){
		 
				current.closest('.single-list').hide();
				
			}
		});
	});

	
	$(document).on('click','.deluser',function()
	{
		var current = $(this);
		var id=current.val();
		var gid=current.closest('.row').data('gid');

		$.ajax({
			'url' : '/ajax/deluser',
			'type' : 'post',
			'data' : {'uid' : id,'gid':gid},
			'success' : function(data){
		 
				current.closest('.single-list').hide();
				
			}
		});
	});

/****
	Edit Private Group Name
****/
	$(document).on('click','.editgroupname',function(){
		$('.pr-edit').prop('disabled', false);
		$(this).hide();
		$('.savegroupname').show();
		$('button.edit-pr-img').show();
		$('.subbtn').show();
	});

$(document).on('click','.savegroupname',function()
	{
		var current = $(this);
		var id=current.val();
		var gname=$('.pr-gname').val();
		if(gname==""){
			alert("Group name can't be empty");
		}
		else{
		$.ajax({
			'url' : '/ajax/editgroupname',
			'type' : 'post',
			'data' : {'gid':id,'gname':gname},
			'success' : function(data){
					$('.pr-edit').prop('disabled', true);
					current.hide();
					$('.editgroupname').show();
					$('button.edit-pr-img').hide();

			}
		});
	}
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

	function loadOrgionalImogi()
	{

		$(".single-post .post-data p, .single-post .comment-text").each(function() {
		var original = $(this).html();
		// use .shortnameToImage if only converting shortnames (for slightly better performance)
		var converted = emojione.toImage(original);
		$(this).html(converted);
	});

	/*$(".post-list .single-post div").each(function() {
		var original = $(this).html();
		// use .shortnameToImage if only converting shortnames (for slightly better performance)
		var converted = emojione.toImage(original);
		$(this).html(converted);
	});*/
	}

