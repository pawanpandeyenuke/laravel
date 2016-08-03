$(document).ready(function(){

	$('.popup').fancybox();

	loadImg();
	   
	//menu dd button
	$('.logout-link').click(function(){
		window.localStorage.setItem('logged_in', false);
	});

	$('.readmore').readmore({
	  	speed: 300,
	  	collapsedHeight: 70,
	  	heightMargin: 0,
	  	moreLink: '<a href="#" class="moreLink">More</a>',
        lessLink: '<a href="#" class="moreLink">Less</a>',
    });

	var moretext = "More";
	var lesstext = "Less";
	$(document).on('click','.morelink',function(){
		if($(this).hasClass('unique_post')){
			i=1;
		}
      	if($(this).hasClass("less")) {
          $(this).removeClass("less");
          $(this).html(moretext);
      	} else {
          $(this).addClass("less");
          $(this).html(lesstext);
      	} 
      	$(this).parent().prev().toggle();
      	$(this).prev().toggle();
      	return false;
	});

    $(document).on('click','.mob-menu-btn',function(){
    	$('.dashboard-sidemenu').slideToggle();
    });
    
	$( "#searchform" ).submit(function( event ) {
		var searchkey = $('#searchfriends').val();
		if(searchkey == ''){
			$('#searchfriends').attr('placeholder', 'Search here..').focus();
			event.preventDefault();
		}
	});


	$('.StyleScroll').niceScroll();
	
	var myReader = new FileReader();
	 $("#fileUpload").on('change', function () {
	 
	     //Get count of selected files
	     var countFiles = $(this)[0].files.length;
	 	
	     var imgPath = $(this)[0].value;
	     // console.log(this.files[0].size);	     
	     if(this.files[0].size < 4194304){

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

	     } else {
	     	$("#fileUpload").val('');
	     	alert("Max upload file size is 4 MB.");
	     	return false;
	     }


	});


	 $('.edit-pr-img').find(".badge").remove();
	//Profile Pic Upload Js
	 $("#profilepicture").on('change', function () {
	 	 
	     //Get count of selected files
	     var countFiles = $(this)[0].files.length;
	 	 
	     var imgPath = $(this)[0].value;

	     if(this.files[0].size < 4194304){
		     var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
		     var image_holder = $("#profile-pic-holder");
		 
		     if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
		         if (typeof (FileReader) != "undefined") {
		 
		             //loop for each file selected for uploaded.
		             for (var i = 0; i < countFiles; i++) {
		 
		                 var reader = new FileReader();
		                 reader.onload = function (e) {
		                 	
		                 	$('.edit-pr-img').find(".badge").remove();
		                 	$('.profile-img').css("background",	"url("+e.target.result+") no-repeat");

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
	     } else {
	     	$('#profilepicture').val('');
	     	alert("Max upload file size is 4 MB.");
	     	return false;
	     }
	});


$('.btn-upload-icon').find(".badge").remove();
	 //Group Image
	 $("#groupimage").on('change', function () {
	     //Get count of selected files
	     var countFiles = $(this)[0].files.length;
	 	 
	     var imgPath = $(this)[0].value;
	     var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
	     var image_holder = $("#groupimageholder");
	     var groupid = $(this).data('groupid');
	
	     if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
	         if (typeof (FileReader) != "undefined") {
	 
	             //loop for each file selected for uploaded.
	             for (var i = 0; i < countFiles; i++) {
	 
	                 var reader = new FileReader();
	                 reader.onload = function (e) {
	                 	$('.btn-upload-icon').find(".badge").remove();
	                 	$('.g-img').prop("src",e.target.result);
	                 	e.ta
	                 	var imagesrc = $('.g-img').attr("src");
	                 	var img = imagesrc.split(',');
	             		var img1 = atob(img[1]);

	                 	$('#uploadgroupimage').trigger('submit');
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


	 $("#uploadgroupimage").ajaxForm(function(response) {});

	// Post status updates via ajax call.
	$("#postform").ajaxForm(function(response) { 
 		var current = $("#postform");
		if(response){
			$('#newsfeed').val('');
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

			 jQuery('.btn-post').prop('disabled',false); 
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
				var parseresponse = jQuery.parseJSON(response);
				 var check="";

				if(parseresponse.count == 0){

					jQuery("#page-"+feedId).html('');
					jQuery("#popup-"+feedId).html('');

				} else{					
					jQuery("#page-"+feedId).html(parseresponse.count);
					jQuery("#popup-"+feedId).html(parseresponse.count);	
	
				}	

				if(parseresponse.likecheck == null)
				{
					check=false;
				}
				else{
					check=true;
				}			
                     var idlike=current.attr('id'); 
         
                     if(idlike!='')    
                     {
                        var id1=idlike.split('-');
                     
					    jQuery('#'+id1).prop('checked', check);

                     	if(id1[0]=='popup1')
                     	{
                     		jQuery('#'+id1[1]).prop('checked', check);
                     	
                     	}
                     }               
			}			
		});	
	});

	$(document).on('click', '.comment', function(){

		var current = $(this);
		
		var _token = $('#postform input[name=_token]').val();
		var feedId = $(this).closest('.post-comment').data('value');
		var commentData = current.closest('.post-comment').find('textarea').val().trim();
		var commented_by = $('#user_id').val();
		var popup = current.closest('.pop-post-comment').data('value');

		current.closest('.post-comment').find('textarea').val('');
		if(commentData == ''){
			var nextdata = current.closest('.post-footer').find('.comment-field').text();
			commentData = nextdata;
		}

		if(commentData){

			current.prop('disabled', true);
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
						$('#post_'+feedId).find('.commentcount').html(count+' Comments'); 
						current.parents('#AllCommentNew').find('.commentcount').html(count+' Comments');
					}else{
						current.parents('.post-footer').find('.commentcount').html('1 Comment'); 
					}	

			current.parents('.pop-comment-side-outer').find('.comments-list ul').append(parseresponse.comment);
			current.parents('.row').find('.comment-field').text('');
			current.parents('#AllCommentNew').find('.comments-list ul').append(parseresponse.comment);
			current.parents('#AllCommentNew').find('.comment-field').text('');
			
			if(jQuery("#pagecomment-"+feedId+" li").length > 3)
				jQuery("#pagecomment-"+feedId+" li").first().remove();

					//Dashboard emoji fix.
					var original =jQuery("#pagecomment-"+feedId+" li .comment-text").last().html();
				    var converted = emojione.toImage(original);
					jQuery("#pagecomment-"+feedId+" li .comment-text").last().html(converted);
			

	 
					if(popup==feedId){
							var original1=jQuery("#popupcomment-"+feedId+" li .comment-text").last().html();
							var converted1 = emojione.toImage(original1);
							jQuery("#popupcomment-"+feedId+" li .comment-text").last().html(converted1);
					}

					current.prop('disabled',false);
				}			
			});	
		}
	});


	$(document).on('click', '.popupajax', function(){    
		showLoading();
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
		        hideLoading();
			}
		});

	});
 

	$(document).on('click', '.postpopupajax', function(){    
		showLoading();
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
		        hideLoading();
			}
		});

	});

		$(document).on('click', '.popupforumreply', function(){    
		showLoading();
		var replyid = $(this).data('replyid');
		$.ajax({
			'url' : '/ajax/forumpostreply/get',
			'data' : { 'replyid' : replyid },
			'type' : 'post',
			'success' : function(response){
				$('#AllCommentNew1').html(response);
		        $.fancybox([
		            { href : '#AllCommentNew1' }
		        ]);
		        hideLoading();
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
		showLoading();
		$.ajax({
			'url' : 'ajax/getfriendslist',
			'data' : {'type' : type},
			'type' : 'post',
			'success' : function(response){
				var jresponse = jQuery.parseJSON(response);
				pageid=2;
				$('.friendcount').find('.count').html(jresponse.friend);
				$('.sentcount').find('.count').html(jresponse.sent);
				$('.recievecount').find('.count').html(jresponse.recieve);



				var itemcount = parseInt(current.closest('.tab-style-no-border').find('.active .count').html());
				if(itemcount <= 10)
				{
					current.closest('.tab-style-no-border').find('.active .load-btn').remove();	
				}
				current.closest('.tab-style-no-border').find('.active').find('.load-btn').addClass('load-more-friend');
				current.closest('.tab-style-no-border').find('.active').find('.load-more-friend').text('View More');
				if(response != '')
				{
					if(response != 0){
					var getelem = current.closest('.tab-style-no-border').find('.active').find('.aftersearch').html(jresponse.view);
					}
					else
					var getelem = current.closest('.tab-style-no-border').find('.active').find('.aftersearch').html("");	
				}
				if(response=='' || response == 0)
				{
				current.closest('.tab-style-no-border').find('.active').find('.load-more-friend').text('No results found');
				current.closest('.tab-style-no-border').find('.active').find('.load-more-friend').prop('disabled',true);
				current.find('.count').html('0');
				}

				if(!(response))
					current.closest('.tab-style-no-border').find('.active').find('.aftersearch').html("");
				 hideLoading();
			}
		});
	});



	/**
	*	Delete posts on ajax call handling.
	*	Ajaxcontroller@deletepost
	*/

	$(document).on('click', '.post-delete', function(){
		showLoading();
		var commentId = $(this).closest('li').data('value'); 
		var feedId = $(this).closest('.single-post').data('value');
		$.ajax({
			'url' : '/ajax/deletebox',
			'data' : {'commentId':commentId, 'feedId' : feedId, 'class' : 'postdelete'},
			'type' : 'post',
			'success' : function(response){
				if(response){
					$("#modal").append(response);
					$("#modal").modal();
					hideLoading();
				}
			}
		});
		$("#modal").html('');
	});
	
	$(document).on('click', '.postdelete', function(){
		var current = $('.postdelete');
		var feedId = current.closest('.modal-content').data('feedid');
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
		showLoading();
		var commentId = $(this).closest('li').data('value'); 
		var feedId = $('.single-post').data('value');
		$.ajax({
			'url' : '/ajax/deletebox',
			'data' : {'commentId':commentId, 'feedId' : feedId, 'class' : 'deletecomment'},
			'type' : 'post',
			'success' : function(response){
				if(response){
					$("#modal").append(response);
					$("#modal").modal();
					hideLoading();
				}
			}
		});
		$("#modal").html('');
	});

		/**
	*	Delete comments on forum reply.
	
	*/	
	$(document).on('click', '.del-forum-reply-comment', function(){
		showLoading();
		var forumReplyCommentId = $(this).val(); 
		var forumReplyID = $(this).data('forumreplyid');
		$.ajax({
			'url' : '/ajax/deletebox',
			'data' : {'forumReplyCommentId':forumReplyCommentId, 'forumReplyID' : forumReplyID, 'class' : 'delete-forum-reply-comment'},
			'type' : 'post',
			'success' : function(response){
				if(response){
					$("#modal").append(response);
					$("#modal").modal();
					hideLoading();
				}
			}
		});
		$("#modal").html('');
	});

	$(document).on('click', '.delete-forum-reply-comment', function(){
		showLoading();
		var current = $('.delete-forum-reply-comment');
		var forumReplyCommentId = current.closest('.modal-content').data('forumreplycommentid');
		var forumReplyId = current.closest('.modal-content').data('forumreplyid');
		$.ajax({
			'url' : '/ajax/del-forum-reply-comment',
			'data' : {'forumReplyCommentId': forumReplyCommentId, 'forumReplyId' : forumReplyId },
			'type' : 'post',
			'success' : function(response){
				if(response){
					$('#forum-li-comment-'+forumReplyCommentId).remove();
					$('#forumreplycomment_'+forumReplyId).html(response);
					$('#forumreplycomment_popup_'+forumReplyId).html(response);
					hideLoading();
				}
			}
		});
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
		showLoading();
		var current = $(this);
		var postid = current.closest('.single-post').data('value'); 
		
		$.ajax({
			'url' : 'ajax/editpost',
			'data' : { 'postid' : postid },
			'type' : 'post',
			'success' : function(response){
				$('#edit-modal').append(response);
				$("#edit-modal").modal();
				hideLoading();
			}
		});
		$('#edit-modal').html('');
	});


	$(document).on('click', '.edit-comment', function(){
		showLoading();
		var commentId = $(this).closest('li').data('value'); 
		var feedId = $('.single-post').data('value');

	
		$.ajax({
			'url' : 'ajax/editcomment',
			'data' : {'commentId':commentId, 'feedId' : feedId},
			'type' : 'post',
			'success' : function(response){
				$.fancybox.close();
				$('#edit-modal').append(response);
				$("#edit-modal").modal();
				hideLoading();
			}
		});
		$('#edit-modal').html('');
	});

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
		
		var recievecount = parseInt($('.recievecount').find('.count').html());
		var newrecievecount = recievecount - 1;
		var friendcount = parseInt($('.friendcount').find('.count').html());
		var newfriendcount = friendcount + 1;
		
		$.ajax({
			'url' : '/ajax/accept',
			'type' : 'post',
			'data' : {'user_id' : user_id,'friend_id':friend_id },
			'success' : function(data){
				$('.recievecount').find('.count').html(newrecievecount);
				$('.friendcount').find('.count').html(newfriendcount);
				current.closest('.get_id').find('.accept').hide(200);
				current.closest('.get_id').find('.decline').hide(200);
				current.closest('.get_id').find('.remove').show(200);
				current.closest('.flist').hide(200);
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
	* Cancel sent request.
	*
	**/
	$(document).on('click','.sent',function(){
		var current = $(this);
		var user_id=current.closest('.get_id').data('userid');
		var friend_id=current.closest('.get_id').data('friendid');

		var count = parseInt($('.sentcount').find('.count').html());
		var newcount = count - 1;

		
		$.ajax({
			'url' : 'ajax/cancelrequest',
			'type' : 'post',
			'data' : {'user_id' : user_id,'friend_id': friend_id},
			'success' : function(data){
				$('.sentcount').find('.count').html(newcount)
				current.closest('.get_id').find('.invite').show(200);
				current.closest('.get_id').find('.sent').hide(500);
				current.closest('.flist').hide(200);
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
		if(name != ""){
			showLoading();	
		$.ajax({
			'url' : 'ajax/searchtabfriend',
			'data' : {'type' : type,'name' : name},
			'type' : 'post',
			'success' : function(response){			
				var getelem = current.closest('.tab-style-no-border').find('.active').find('.aftersearch').html(response);
				hideLoading();
			}
		});
	}else{
		current.closest('.search-box').find('.searchtabtext').focus();
		hideLoading();
	}
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
					var getelem = current.closest('.tab-style-no-border').find('.active').find('.aftersearch').html(response);
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
		
		var recievecount = parseInt($('.recievecount').find('.count').html());
		var newrecievecount = recievecount - 1;

		var count = parseInt(current.parents('.tab-style-no-border').find('.active .count').html());
		var newcount = count - 1;

		$.ajax({
			'url' : '/ajax/reject',
			'type' : 'post',
			'data' : {'user_id' : user_id,'friend_id':friend_id },
			'success' : function(data){
				current.parents('.tab-style-no-border').find('.active .count').html(newcount);
				$('.recievecount').find('.count').html(newrecievecount);
				current.closest('.get_id').find('.accept').hide(200);
				current.closest('.get_id').find('.decline').hide(200);
				current.closest('.get_id').find('.invite').show(200);
				current.closest('.flist').hide(200);
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

		var count = current.parents('.tab-style-no-border').find('.active .count').html();
		var newcount = count - 1;

		$.ajax({
			'url' : '/ajax/remove',
			'type' : 'post',
			'data' : {'user_id' : user_id,'friend_id':friend_id },
			'success' : function(data){
				current.parents('.tab-style-no-border').find('.active .count').html(newcount);
				current.closest('.get_id').find('.remove').hide(200);
				current.closest('.text-right').find('.invite').show(200);
				current.closest('.col-sm-12').find('.invite').show(200);
				current.closest('.flist').hide(200);
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
		$('.load-more-friend').prop('disabled',true);
		$('.load-more-friend').text('Loading...');
		var current = $(this);
		var reqType = current.closest('.friends-list').find('.active').data('value');
		var abc=current.closest('.friends-list').find('ul.counting').children('li').length;

		$.ajax({
			'url' : '/ajax/viewmorefriends',
			'type' : 'post',
			'data' : { 'pageid': pageid, 'reqType': reqType, 'lastid': current.data( 'last-id' ) },
			'success' : function(data){
				$('.load-more-friend').text('View More');
				if(data != 'No more results'){		
					pageid = pageid + 1;
					$('.loading-text').show();
					$('.loading-img').hide();
					current.parent().append(data);
					current.remove();
					
				}else{
					var currentobj = current.find('.loading-text');
					currentobj.text('No more results');
					current.removeClass('load-more-friend');
					$('.load-btn').text('No more results')
				}
				$('.load-more-friend').prop('disabled',false);
			}	
		});
	});

	$(document).on('click','.load-more-friend-search',function(){
		var current = $(this);
		current.prop('disabled',true).text('Loading...');
		var reqType = current.closest('.friends-list').find('.active').data('value');
		$.ajax({
			'url' : '/ajax/view-more-friends-search',
			'type' : 'post',
			'data' : { 'pageid': pageid, 'type': reqType, 'lastid': current.data( 'last-id' ), 'name':current.data( 'keyword' ) },
			'success' : function(data){
				current.text('View More');
				if(data != 'No more results'){		
					pageid = pageid + 1;
					$('.loading-text').show();
					$('.loading-img').hide();
					current.parent().append(data);
					current.remove();
					
				}else{
					var currentobj = current.find('.loading-text');
					currentobj.text('No more results');
					current.removeClass('load-more-friend-search');
					$('.load-btn').text('No more results')
				}
				current.prop('disabled',false);
			}	
		});
	});

	$(document).on('click','.load-more-all',function(){
		var current = $(this);
		var keyword = $(this).data('key');
		current.prop('disabled',true).text('Loading...');
		var abc=current.closest('.friends-list').find('ul.counting').children('li').length;
		$.ajax({
			'url' : '/ajax/viewMoreForAll',
			'type' : 'post',
			'dataType' : 'json',
			'data' : { 'pageid': pageid , 'keyword' : keyword },
			'success' : function(data){
				current.text('View More').prop('disabled', false);
				pageid = pageid + 1;
				current.closest('.friends-list').find('.active').find('ul').append(data.html);
				if(data.existmore == 0) {
					current.remove();
				}
			}	
		});
	});

	// load more forum posts
	$(document).on('click','.load-more-forumpost',function(){
		var current = $(this);
		var breadcrum = $(this).data('breadcrum');
		current.prop('disabled',true).text('Loading...');
		var abc=current.closest('.friends-list').find('ul.counting').children('li').length;
		$.ajax({
			'url' : '/ajax/view-more-forum-post',
			'type' : 'post',
			'dataType' : 'json',
			'data' : { 'pageid': pageid ,'breadcrum' : breadcrum, 'call_type': 'web' },
			'success' : function(data){
				current.text('View More').prop('disabled', false);
				pageid = pageid + 1;
				$('.forumpostlist').append(data.html);
				if(data.existmore == 0) {
					current.parent().remove();
				}
				activateReadmore();
			}	
		});
	});

	// load more forum post replies
	$(document).on('click', '.load-more-forumreply',function(){
		var current = $(this);
		var forumpostid = current.data('forumpostid');
		current.text('Loading...').prop('disabled', true);
		$.ajax({
			'url' : '/ajax/view-more-forum-reply',
			'type' : 'post',
			'dataType' : 'json',
			'data' : { 'pageid': pageid ,'forumpostid' : forumpostid, 'call_type': 'web' },
			'success' : function(data){
				current.text('View More').prop('disabled', false);
				pageid = pageid + 1;
				$('.forumreplylist').append(data.html);
				if(data.existmore == 0) {
					current.parent().remove();
				}
				activateReadmore();
			}
		});
	});

	// load more search forum posts
	$(document).on('click','.load-more-search-forum',function(){
		var current = $(this);
		current.text('Loading...').prop('disabled',true);
		var breadcrum = $(this).data('breadcrum');
		var keyword = $(this).data('keyword');
		$.ajax({
			'url' : '/ajax/view-more-search-forum',
			'type' : 'post',
			'dataType' : 'json',
			'data' : { 'pageid': pageid,'breadcrum': breadcrum ,'keyword' : keyword },
			'success' : function(data){
				current.text('View More').prop('disabled', false);
				pageid = pageid + 1;
				$('.forumpostlist').append(data.html);
				if(data.existmore == 0) {
					current.parent().remove();
				}
				activateReadmore();
			}	
		});
	});
	
	$("#up_imgs").fileinput({
	    uploadUrl: "/file-upload-batch/2",
	    allowedFileExtensions: ["jpg", "png", "gif"],
	    minImageWidth: 30,
	    minImageHeight: 30,
	    showCaption: false
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

		$('.glyphicon-download').hide();
		$('.loading-img').show();
		var current = $(this);

		$.ajax({
			'url' : '/ajax/viewmoreposts',
			'type' : 'post',
			'dataType' : 'json',
			'data' : { 'pageid': pageid },
			'success' : function(data){
				if(data.html){
					$('.glyphicon-download').show();
					$('.loading-img').hide();
					pageid = pageid + 1;
					$('#postlist').last('.single-post').append(data.html);
					loadImg();
					loadOrgionalImogi();
				}

				if(data.existmore == 0){
					current.remove();
				}else if(data.existmore == 1 && data.html == ""){
					current.remove();
				}
			}	
		});
	});

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
		 		$('.broadcast_'+id).remove();
				$('#forum-confirm-modal').modal('hide');
			}
		});
	});

	/*
	 Broadcast Message Button.
	*/
	$(document).on('click','.broadcastbtn',function(){
		var current = $(this);
		$('.broadcastbtn').prop('disabled',true);
		var bid=current.val();
		var msg=$('.broadcastmsg').val();
		if(msg!=""){
			$.ajax({
				'url' : '/ajax/sendbroadcast',
				'type' : 'post',
				'data' : {'msg':msg,'bid':bid},
				'success' : function(data){
					$("#bmsg").append(data);
					$('.broadcastmsg').val('');
					$('.broadcastbtn').prop('disabled',false);
				}
			});
		}else{
			$('.broadcastmsg').focus();
			$('.broadcastbtn').prop('disabled',false);
		}
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
		 		$('.private-group_'+id).remove();
		 		$('#forum-confirm-modal').modal('hide');
			}
		});
	});

	$(document).on('click','.userleave',function()
	{
		var current = $(this);
		var id=current.val();
	
		$.ajax({
			'url' : '/ajax/leaveprivategroup',
			'type' : 'post',
			'data' : {'pid' : id},
			'success' : function(data){
		 		$('.private-group_'+id).remove();
		 		$('#forum-confirm-modal').modal('hide');
			}
		});
	});

	$(document).on('click','.deluser',function()
	{
		var current = $(this);
		var id=current.val();
		var gid=current.data('gid');

		$.ajax({
			'url' : '/ajax/deluser',
			'type' : 'post',
			'data' : {'uid' : id,'gid':gid},
			'success' : function(data){
				$('.private-member-'+id).remove();
				$('#forum-confirm-modal').modal('hide');
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

	$(document).on('click','.savegroupname',function(){
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

	/***** Forum Delete Confirmation Box****/

	$(document).on('click', '.del-confirm-forum', function(){
		// showLoading();
		var type = $(this).data('forumtype'); 
		var type_id = $(this).val();
		var breadcrum = $(this).data('breadcrum');
		var reply_post_id = $(this).data('forumpostid');
		var gid = $(this).data('gid');
		$.ajax({
			'url' : '/ajax/forum-del-confirm',
			'data' : {'type':type, 'type_id' : type_id, 'breadcrum' : breadcrum, 'reply_post_id' : reply_post_id, 'gid' : gid},
			'type' : 'post',
			'success' : function(response){
				if(response){
					$("#forum-confirm-modal").append(response);
					$("#forum-confirm-modal").modal();
					// hideLoading();
				}
			}
		});
		$("#forum-confirm-modal").html('');
	});
	
	
	/***** Forum Post Delete ****/
	$(document).on('click','.forumpostdelete',function(){
		var current = $(this);
		var forumpostid = $(this).val();
		var breadcrum = $(this).data('breadcrum');
	
			var scount = parseInt($('.search-forum-count').find('.count').html());
			var newcount = scount - 1;

			$.ajax({
			'url' : '/ajax/delforumpost',
			'type' : 'post',
			'data' : {'forumpostid' : forumpostid , 'breadcrum' : breadcrum},
			'success' : function(response){
				$('.posts-count').find('.count').html(' '+response);
				$('#forumpost_'+forumpostid).hide();
				$('.search-forum-count').find('.count').html(newcount);
				$('#forum-confirm-modal').modal('hide');
			}
		});

	});

	/***** Forum Reply Delete ****/

	$(document).on('click','.forumreplydelete',function(){
		var current = $(this);
		var forumreplyid = $(this).val();
		var forumpostid = $(this).data('forumpostid');
			$.ajax({
			'url' : '/ajax/delforumreply',
			'type' : 'post',
			'data' : {'forumreplyid' : forumreplyid , 'forumpostid' : forumpostid},
			'success' : function(response){
				$('.posts-count').find('.forumreplycount').html(' '+response);
				$('#forumreply_'+forumreplyid).hide();
				$('#forum-confirm-modal').modal('hide');
			}
		});

	});

	/***** Add new Forum Post ****/
	$(document).on('click','.addforumpost',function(){
		if($('.fix-header').hasClass("stick")){
			window.scrollTo(0,100);
		}	
		var current = $(this);
		var breadcrum = $(this).val();
		var post = $('.forumpost').val().trim();
		var postcount = parseInt($('.posts-count').find('.count').html());
		var newpostcount = postcount + 1;
		if(post)
		{
		  	current.prop('disabled',true);
			$.ajax({
				'url' : '/ajax/addnewforumpost',
				'type' : 'post',
				'data' : {'breadcrum' : breadcrum,'topic' : post},
				'success' : function(response){	
					$('.posts-count').find('.count').html(' '+newpostcount);
			  		$('.forumpost').val('');
			  		$('.emoji-wysiwyg-editor').text('');
					$('.forumpostlist').prepend(response);
					var original =jQuery('.f-single-post').first().find('p').html();
				   	var converted = emojione.toImage(original);
					jQuery('.f-single-post').first().find('p').html(converted);
					$('.addforumpost').prop('disabled',false);
					activateReadmore($('.forumpostlist .readmore:first'));
				}
			});
		}
	});

	/***** Forum Post Edit ****/

		$(document).on('click','.editforumpost',function(){
		var forumpostid = $(this).val(); 
		showLoading();

		$.ajax({
			'url' : '/ajax/editforumpost',
			'data' : {'forumpostid':forumpostid},
			'type' : 'post',
			'success' : function(response){
				$.fancybox.close();
				$('#forumpost-edit-modal').append(response);
				$("#forumpost-edit-modal").modal();
				hideLoading();
			}
		});
		$('#forumpost-edit-modal').html('');

	});

	/***** Forum Post Edit ****/
	$(document).on('click','.editforumreply',function(){
		var forumreplyid = $(this).val(); 
		showLoading();

		$.ajax({
			'url' : '/ajax/editforumreply',
			'data' : {'forumreplyid':forumreplyid},
			'type' : 'post',
			'success' : function(response){
				$.fancybox.close();
				$('#forumreply-edit-modal').append(response);
				$("#forumreply-edit-modal").modal();
				hideLoading();
			}
		});
		$('#forumreply-edit-modal').html('');

	});

	$(document).on('click', '.likeforumpost', function(){		
		var forumPostID = $(this).data('forumpostid');
		var current = $(this);

		$.ajax({			
			'url' : '/ajax/likeforumpost',
			'data' : { 'forumpostid':forumPostID },
			'type' : 'post',
			'success' : function(response){
				if(response == "no"){
				$('#forumpost_'+forumPostID).html("<div class ='alert alert-danger'>You can't like the post as it doesn't exist anymore.</div>");
				setInterval(function(){ $('#forumpost_'+forumPostID).fadeOut(200);}, 5000);
				$('#forum-post-reply_'+forumPostID).html("<div class ='alert alert-danger'>You can't like the post as it doesn't exist anymore.</div>");
				}
				else{
					if(current.is(':checked')){
						$('#checkbox_forumpost_'+forumPostID).prop('checked',true);
						$('#checkbox_forumpost_replypage_'+forumPostID).prop('checked',true);
					}
					else{
						$('#checkbox_forumpost_'+forumPostID).prop('checked',false);
						$('#checkbox_forumpost_replypage_'+forumPostID).prop('checked',false);

					}
					current.parents('.p-likes').find('.plike-count').html(response);
					current.parents('.fp-likes').find('.plike-count').html(response);
			}
			}			
		});	
	});

	$(document).on('click', '.likeforumreply', function(){		
		var forumreplyid = $(this).data('forumreplyid');
		var current = $(this);

		$.ajax({			
			'url' : '/ajax/likeforumreply',
			'data' : { 'forumreplyid':forumreplyid },
			'type' : 'post',
			'success' : function(response){
				var newresponse = jQuery.parseJSON(response);
					$('#checkbox_forumreply_'+forumreplyid).parents('.p-likes').find('.forumreplylike').html(newresponse.likecount);
					current.parents('.like-cont').find('.forumreplylike').html(newresponse.likecount);
					if(newresponse.check == "unchecked")
						$('#checkbox_forumreply_'+forumreplyid).prop('checked',false);
					else
						$('#checkbox_forumreply_'+forumreplyid).prop('checked',true);
			}			
		});	
	});

	$(document).on('click', '.forumpostreply', function(){
		if($('.fix-header').hasClass("stick")){
			window.scrollTo(0,100);
		}	
		var forumPostID = $(this).data('forumpostid');
		var reply = $('.forumreply').val().trim();
		var current = $(this);	
		var postcount = parseInt($('.posts-count').find('.forumreplycount').html());
		var newpostcount = postcount + 1;
		if(reply){	
			current.prop('disabled',true);	
			$.ajax({			
				'url' : '/ajax/addnewforumreply',
				'data' : { 'forumpostid':forumPostID ,'reply' : reply},
				'type' : 'post',
				'success' : function(response){
					if(response == "no"){
					$('#forum-post-reply_'+forumPostID).html("<div class ='alert alert-danger'>You can't reply to the post as it doesn't exist anymore.</div>");	
					}else{
						$('.posts-count').find('.forumreplycount').html(' '+newpostcount);
						$('.forumreplylist').prepend(response);
						$('.forumreply').val('');
					    $('.emoji-wysiwyg-editor').text('');
						var original =jQuery('.f-single-post').first().find('p').html();
					   	var converted = emojione.toImage(original);
						jQuery('.f-single-post').first().find('p').html(converted);
						activateReadmore($('.forumreplylist .readmore:first'));
					}
					$('.forumpostreply').prop('disabled',false);
				}			
			});	
		}
	});


	$(document).on('click', '.replycomment', function(){
		var replyid = $(this).val();
		var comment = $('.reply-comment-text').val().trim();	
		var commentcount = parseInt($('#forumreplycomment_'+replyid).html());
		var newcount = commentcount + 1;

		if(comment){
			showLoading();
			$.ajax({			
				'url' : '/ajax/forumreplycomment',
				'data' : { 'replyid':replyid, 'comment':comment },
				'type' : 'post',
				'success' : function(response){
					$('.reply-comment-text').val('');
					$('.emoji-wysiwyg-editor').text('');				
					$('.forumreplycommentlist').append(response);
					$('#forumreplycomment_'+replyid).html(newcount);
					$('#forumreplycomment_popup_'+replyid).html(newcount);
					var original =jQuery('.forumreplycommentlist li:last-child').find('.replycomment').html();
				   	var converted = emojione.toImage(original);
					jQuery('.forumreplycommentlist li:last-child').find('.replycomment').html(converted);					
					hideLoading();
				}			
			});
		}	
	});
});

function loadImg()
{
	window.emojiPicker = new EmojiPicker({
		emojiable_selector: '[data-emojiable=true]',
		assetsPath: '/lib/img/',
		popupButtonClasses: 'fa fa-smile-o'
  	});
  window.emojiPicker.discover();
}

/*********** To display emoji onload of a page******************/
function loadOrgionalImogi()
{
	$(".single-post .post-data p, .single-post .comment-text, .f-single-post p, .forum-srch-list p, .f-single-post .more .morecontent span").each(function() {
	var original = $(this).html();
	// use .shortnameToImage if only converting shortnames (for slightly better performance)
	var converted = emojione.toImage(original);
	$(this).html(converted);
});
}

function showLoading(){
	$('.page-loading').show();
}

function hideLoading(){
	$('.page-loading').hide();
}

function storageChange(event) {
	if(event.key == 'logged_in' && event.newValue == 'false') {
		setInterval(function(){ location.reload(true); }, 2000); 
	}
}

window.addEventListener('storage', storageChange, false);
window.localStorage.setItem('logged_in', true);

// Activate read more feature
function activateReadmore(obj)
{
	obj = obj ? obj : $('.readmore');
	obj.readmore({
	  	speed: 300,
	  	collapsedHeight: 70,
	  	heightMargin: 0,
	  	moreLink: '<a href="#" class="moreLink">More</a>',
        lessLink: '<a href="#" class="moreLink">Less</a>',
    });
}