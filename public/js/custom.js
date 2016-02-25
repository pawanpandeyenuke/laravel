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
	         alert("Pls select only images");
	     }
	});

	// Post status updates via ajax call.
	$("#postform").ajaxForm(function(response) { 

            console.log(response);

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
