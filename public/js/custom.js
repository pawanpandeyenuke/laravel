$(document).ready(function(){

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
 
		// alert(response);
		if(response){
			$('#newsfeed').val('');
			$('#image-holder').hide();
			$('.group-span-filestyle label .badge').hide();

			$('#postlist').first('.single-post').prepend(response);

			// $('#postlist .single-post').first().hide();
 
		} 

    }); 


	$(document).on('click', '.like', function(){		
		var _token = $('#postform input[name=_token]').val();
		var feedId = $(this).closest('.single-post').data('value');
		var user_id = $('#user_id').val();
		// alert(id);
		$.ajax({			
			'url' : 'api/likes',
			'data' : { '_token' : _token, 'feed_id' : feedId, 'user_id' : user_id, 'liked' : 'Yes' },
			'type' : 'post',
			'success' : function(response){				
				// alert(response);				
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
				'url' : 'api/comments/create',
				'data' : { '_token' : _token, 'feed_id' : feedId, 'commented_by' : commented_by, 'comments' : commentData, 'ajaxrequest' : 'true' },
				'type' : 'post',
				'success' : function(response){				
					current.closest('.row').find('textarea').val('');
					var responsedata = jQuery.parseJSON(response);
					// console.log(responsedata.data);
					current.parents('.post-comment-cont').find('.comments-list ul').append(responsedata.data);
					// current.closest('.comments-list ul').append(responsedata.data);
					
				}			
			});	
		}
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

/*$("#up_imgs").fileinput({
	uploadUrl: "/ajax/posts",
	allowedFileExtensions: ["jpg", "png", "gif"],
	minImageWidth: 30,
	minImageHeight: 30,
	showCaption: false,
});*/
