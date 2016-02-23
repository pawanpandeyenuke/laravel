$(document).ready(function(){
	
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

	$("#up_imgs").fileinput({
	    uploadUrl: "/file-upload-batch/2",
	    allowedFileExtensions: ["jpg", "png", "gif"],
	    minImageWidth: 30,
	    minImageHeight: 30,
	    showCaption: false,
	});
	
});
