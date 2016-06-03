<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>FS - @yield('title')</title>
		<link href="{{ url('forums-data/css/bootstrap.css') }}" rel="stylesheet">
		<link href="{{ url('forums-data/css/style.css') }}" rel="stylesheet">
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>

	<body>

		@yield('content')

<script type="text/javascript" src="{{ url('forums-data/js/jquery-1.11.3.min.js') }}"></script>
<script type="text/javascript" src="{{ url('forums-data/js/bootstrap.min.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function(){

		var pageid = 2;
		$(document).on('click','.load-more-forumpost',function(){			
			$('.loading-text').hide();
			$('.loading-img').show();
			var current = $(this);
			var breadcrum = $(this).data('breadcrum');
			var abc = current.closest('.friends-list').find('ul.counting').children('li').length;
			$.ajax({
				'url' : '/ajax/view-more-forum-post',
				'type' : 'post',
				'data' : { 'pageid': pageid ,'breadcrum' : breadcrum, call_type: 'api' },
				'success' : function(data){
					if(data != 'No More Results'){
						pageid = pageid + 1;
						$('.loading-text').show();
						$('.loading-img').hide();
						$('.forum-post-list').append(data);
					}else{
						current.hide();
//						current.text('No more results');
//						current.removeClass('.load-more-forumpost');
					}
				}	
			});
		});

		$(document).on('click','.load-more-forumreply',function(){
			$('.loading-text').hide();
			$('.loading-img').show();
			var current = $(this);
			var forumpostid = $(this).data('forumpostid');
			var user_id = $('.userid').data('id');
			$.ajax({
				'url' : '/ajax/view-more-forum-reply',
				'type' : 'post',
				'data' : { 'pageid': pageid , 'forumpostid' : forumpostid, call_type: 'api', 'user_id': user_id },
				'success' : function(data){
					if(data != 'No More Results'){		
						pageid = pageid + 1;
						$('.loading-text').show();
						$('.loading-img').hide();
						$('.reply-post-cont').append(data);
						// current.parents('.forum-srch-list').find('.forumreplylist').append(data);
					}else{
						current.hide();
						//current.text('No more results');
						//current.removeClass('.load-more-forumreply');
						//$('.load-btn').text('No more results')
					}
				}	
			});
		});

		$(document).on('click','.load-more-forumcommets',function(){
			$('.loading-text').hide();
			$('.loading-img').show();
			var current = $(this);
			var forumReplyId = $(this).data('forumreplyid');
			var user_id = $('.userid').data('id');
			$.ajax({
				'url' : '/ajax/view-more-forum-comment',
				'type' : 'post',
				'data' : { 'pageid': pageid , 'forumreplyid' : forumReplyId, call_type: 'api', 'user_id': user_id },
				'success' : function(data){
					if(data != 'No More Results'){		
						pageid = pageid + 1;
						$('.loading-text').show();
						$('.loading-img').hide();
						$('.reply-post-cont').append(data);
					}else{
						current.hide();
//						current.text('No more results');
//						current.removeClass('.load-more-forumcommets');
					}
				}	
			});
		});

		$(document).on('click', '.api-likeforumpost', function(){		
			//var _token = $('#postform input[name=_token]').val();
			var forumPostID = $(this).data('forumpostid');
			// var user_id = $('#user_id').val();
			// var attrId = $(this).attr('id');	
			// alert(attrId);
			var current = $(this);		
			$.ajax({			
				'url' : '/ajax/likeforumpost',
				'data' : { 'forumpostid': forumPostID },
				'type' : 'post',
				'success' : function(response){
					current.closest('.like-cont').find('.likescount').html(response);
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


	});
</script>
	</body>
</html>
