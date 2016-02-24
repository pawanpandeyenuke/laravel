$(document).ready(function(){

	var myReader = new FileReader();

	$("#fileUpload").on('change', function () {
 
        if (typeof (FileReader) != "undefined") {
 
            var image_holder = $("#image-holder");
            image_holder.empty();
 
            var reader = new FileReader();
            reader.onload = function (e) {
                $("<img />", {
                    "src": e.target.result,
                    "class": "thumb-image"
                }).appendTo(image_holder);
 
            }
            image_holder.show();
            reader.readAsDataURL($(this)[0].files[0]);
        } else {
            alert("This browser does not support FileReader.");
        }
    });


	// Post status updates via ajax call.
	$("#postform").ajaxForm(function($response) { 

            console.log($response);
            
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
 
});

/*$("#up_imgs").fileinput({
	uploadUrl: "/ajax/posts",
	allowedFileExtensions: ["jpg", "png", "gif"],
	minImageWidth: 30,
	minImageHeight: 30,
	showCaption: false,
});*/
