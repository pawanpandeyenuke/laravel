<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>FS - @yield('title')</title>
		<link href="{{ url('forums-data/css/bootstrap.css') }}" rel="stylesheet">
		<link href="{{ url('forums-data/css/style.css?v=1.0') }}" rel="stylesheet">
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<style type="text/css">
			.btn-reply {
			    text-align: center !important;
			}
			.loading-btn{
				color: #000;
				background: #92e7dc;
				border-color: #92e7dc;
			}
		</style>
	</head>

	<body>
		
		<div class = "text-center" id="google_translate_element"></div>
		<div class="modal fade" id="forum-confirm-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		</div>
		
		@yield('content')

<script type="text/javascript" src="{{ url('forums-data/js/jquery-1.11.3.min.js') }}"></script>
<script type="text/javascript" src="{{ url('forums-data/js/bootstrap.min.js') }}"></script>
<script src="{{url('forums-data/js/emojione.js')}}"></script>
<script type="text/javascript" src="{{url('/js/readmore.min.js')}}"></script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script type="text/javascript">
$(document).ready(function(){

	$('.readmore').readmore({
	  	speed: 300,
	  	collapsedHeight: 80,
	  	heightMargin: 0,
	  	moreLink: '<a href="#" class="moreLink">More</a>',
        lessLink: '<a href="#" class="moreLink">Less</a>',
    });

	window.onload = function() {
		loadOrgionalImogi();
		$("#forum_post .morelink").click();
		googleTranslateElementInit();
	}

	$(document).on('click', '.del-confirm-api', function(){//forumpostdelete
		var current = $(this);
		var breadcrum = current.data('breadcrum');
		var postid = current.data('postid');
		var type = current.data('type');
		var forumpostid = current.data('forumpostid');
		var forumreplyid = current.data('forumreplyid');
		$.ajax({
			'url' : '/api/api-del-confirm',
			'data' : {'type':type, 'postid' : postid, 'breadcrum' : breadcrum, 'forumpostid' : forumpostid, 'forumreplyid' : forumreplyid},
			'type' : 'post',
			'success' : function(response){
				if(response){
					$("#forum-confirm-modal").append(response);
					$("#forum-confirm-modal").modal();
				}
			}
		});
		$("#forum-confirm-modal").html('');
	});
	
	var pageid = 2;
	$(document).on('click','.load-more-forumpost',function(){
		var current = $(this);
		var breadcrum = $(this).data('breadcrum');
		var user_id = $('.userid').data('id');
		current.prop('disabled',true).text('Loading...');
		var abc = current.closest('.friends-list').find('ul.counting').children('li').length;
		$.ajax({
			'url' : '/ajax/view-more-forum-post',
			'type' : 'post',
			'dataType' : 'json',
			'data' : { 'pageid': pageid ,'breadcrum' : breadcrum, 'call_type': 'api', 'user_id': user_id },
			'success' : function(data){
				current.text('View More').prop('disabled', false);
				pageid = pageid + 1;
				$('.forum-post-list').append(data.html);
				if(data.existmore == 0) {
					current.parent().remove();
				}
				activateReadmore();
			}
		});
	});

	$(document).on('click','.load-more-forumreply',function(){
		var current = $(this);
		var forumpostid = $(this).data('forumpostid');
		var user_id = $('.userid').data('id');
		current.prop('disabled',true).text('Loading...');
		$.ajax({
			'url' : '/ajax/view-more-forum-reply',
			'type' : 'post',
			'dataType' : 'json',
			'data' : { 'pageid': pageid , 'forumpostid' : forumpostid, 'call_type': 'api', 'user_id': user_id },
			'success' : function(data){
				current.text('View More').prop('disabled', false);
				pageid = pageid + 1;
				$('.reply-post-cont').append(data.html);
				if(data.existmore == 0) {
					current.parent().remove();
				}
				activateReadmore();
			}
		});
	});

	$(document).on('click','.load-more-forumcommets',function(){
		var current = $(this);
		var forumReplyId = $(this).data('forumreplyid');
		var user_id = $('.userid').data('id');
		current.prop('disabled',true).text('Loading...')
		$.ajax({
			'url' : '/ajax/view-more-forum-comment',
			'type' : 'post',
			'data' : { 'pageid': pageid , 'forumreplyid' : forumReplyId, 'call_type': 'api', 'user_id': user_id },
			'success' : function(data){
				$('.load-more-forumcommets').prop('disabled',false);
				if(data != 'No More Results'){		
					pageid = pageid + 1;
					$('.loading-text').show();
					$('.loading-img').hide();
					$('.reply-post-cont').append(data);
					$('.load-more-forumcommets').text('View More');
					activateReadmore();
				}else{
					current.hide();
				}
			}	
		});
	});

	$(document).on('click', '.api-likeforumpost', function(){		
		//var _token = $('#postform input[name=_token]').val();
		var forumPostID = $(this).data('forumpostid');
		var userid = $(this).data('userid');
		// var user_id = $('#user_id').val();
		// var attrId = $(this).attr('id');	
		// alert(attrId);
		var current = $(this);		
		$.ajax({			
			'url' : '/ajax/likeforumpost',
			'data' : { 'forumpostid': forumPostID, 'user_id':userid },
			'type' : 'post',
			'success' : function(response){
				if(current.is(':checked')){
				$('#checkboxG1-post-'+forumPostID).prop('checked',true);
				$('checkboxG1-post-replypage-'+forumPostID).prop('checked',true);
				}
				else{
					$('#checkboxG1-post-'+forumPostID).prop('checked',false);
					$('checkboxG1-post-replypage-'+forumPostID).prop('checked',false);						
				}
				current.closest('.like-cont').find('.likescount').html(response);
			}			
		});	
	});

	$(document).on('click', '.likeforumreply', function(){
		var forumreplyid = $(this).data('forumreplyid');
		var userid = $(this).data('userid');
		var current = $(this);		
		$.ajax({			
			'url' : '/ajax/likeforumreply',
			'data' : { 'forumreplyid':forumreplyid,  'user_id':userid },
			'type' : 'post',
			'success' : function(response){
				var newresponse = jQuery.parseJSON(response);
					current.parent().find('.replies-like-count').html(newresponse.likecount);
					// $('#checkbox_forumreply_'+forumreplyid).parents('.p-likes').find('.forumreplylike').html(newresponse.likecount);
					// current.parents('.like-cont').find('.forumreplylike').html(newresponse.likecount);
					// if(newresponse.check == "unchecked")
					// 	$('#checkbox_forumreply_'+forumreplyid).prop('checked',false);
					// else
					// 	$('#checkbox_forumreply_'+forumreplyid).prop('checked',true);
			}			
		});	
	});

	$(document).on('click', '.forumpostdelete', function(){
		var current = $(this);
		var forumpostid = $(this).data('postid');
		var breadcrum = $(this).data('breadcrum');	
		$.ajax({
		'url' : '/ajax/delforumpost',
		'type' : 'post',
		'data' : {'forumpostid' : forumpostid , 'breadcrum' : breadcrum},
		'success' : function(response){		 
			current.closest('.single-post').hide();
			$('#forumpost_'+forumpostid).remove();
			$('#forum-confirm-modal').modal('hide');
		  }
		});
	});

	$(document).on('click','.forumreplydelete',function(){
		var current = $(this);
		var forumreplyid = $(this).data('forumreplyid');
		var forumpostid = $(this).data('forumpostid');
		$.ajax({
			'url' : '/ajax/delforumreply',
			'type' : 'post',
			'data' : {'forumreplyid' : forumreplyid , 'forumpostid' : forumpostid},
			'success' : function(response){		 
				current.closest('.single-post').hide();
				$('#forumreply_'+forumreplyid).remove();
				$('#forum-confirm-modal').modal('hide');
			}
		});
	});

	var moretext = "More";
	var lesstext = "Less";
	$(document).on('click','.morelinkk',function(){
		if($('.morelink').attr('href') == "javascript:void(0);")
		{
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
	    } else {
			window.location = $('.morelink').attr("href");
		}
	});

	function loadOrgionalImogi()
	{
		$(".single-post .post-data p, .single-post .comment-text, .f-single-post p, .forum-srch-list p, .f-single-post .more .morecontent span").each(function() {
			var original = $(this).html();
			// use .shortnameToImage if only converting shortnames (for slightly better performance)
			var converted = emojione.toImage(original);
			$(this).html(converted);
		});
	}
	
   	function googleTranslateElementInit() {
  	 	new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
	}
});

// Activate read more feature
function activateReadmore(obj)
{
	obj = obj ? obj : $('.readmore');
	obj.readmore({
	  	speed: 300,
	  	collapsedHeight: 80,
	  	heightMargin: 0,
	  	moreLink: '<a href="#" class="moreLink">More</a>',
        lessLink: '<a href="#" class="moreLink">Less</a>',
    });
}
</script>
</body>
</html>