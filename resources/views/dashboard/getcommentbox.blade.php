 <?php  
$user = $feeddata->user;
$comments = $feeddata->comments;
$likes = $feeddata->likes; 
$likedata = DB::table('likes')->where(['user_id' => Auth::User()->id, 'feed_id' => $feeddata->id])->get(); 
$likecountdata = App\Like::where(['feed_id' => $feeddata->id])->get()->count(); 
$commentscountdata = App\Comment::where(['feed_id' => $feeddata->id])->get()->count(); 
?>
<div id="AllComment" class="post-list popup_list_with_img" data-value="{{ $feeddata->id }}" id="post_{{ $feeddata->id }}">
	<div class="container">
		<div class="row">
			<div class="col-sm-8 pop-post-left-side">
				<div class="single-post" data-value="{{ $feeddata->id }}" id="post_{{ $feeddata->id }}">
					<div class="pop-post-header">
						<div class="post-header">
							<div class="row">
								<div class="col-md-7">
									<a href="profile/{{$user->id}}" title="" class="user-thumb-link">
									<?php $userpic = !empty($user->picture) ? $user->picture : '/images/user-thumb.jpg' ?>
										<span class="small-thumb" style="background: url('{{$userpic}}');"></span>
										{{ $user->first_name.' '.$user->last_name }}
									</a>
								</div>
								<div class="col-md-5">
									<div class="post-time text-right">
										<ul>
											<li><span class="icon flaticon-time">{{ $feeddata->updated_at->format('h:i A') }}</span></li>
											<li><span class="icon flaticon-days">{{ $feeddata->updated_at->format('D jS') }}</span></li>
										</ul>
									</div>
								</div>
							</div>
						</div><!--/post header-->
						<div class="pop-post-text clearfix">
							<p>{{ $feeddata->message }}</p>
						</div>
					</div>
					
					@if(!empty($feeddata->image))
						<div class="post-data pop-post-img">
							<img src="{{ url("uploads/$feeddata->image") }}" class="pop-img">
						</div>
					@endif
					
					<div class="post-footer pop-post-footer">
						<div class="post-actions">
							<ul>
								<li>
									<div class="like-cont">

										<input type="checkbox" name="checkboxG4" id="popup1-checkbox{{$feeddata->id}}" class="like css-checkbox" {{ isset($likedata[0])?'checked':'' }}/>
							<label for="popup1-checkbox{{$feeddata->id}}" class="css-label">
											@if($likecountdata > 0)
												<span class="countspan" id="popup-{{$feeddata->id}}">
											 		{{ $likecountdata }}
												</span>
												<span>Likes</span>			
											@else
												<span class="countspan" id="popup-{{$feeddata->id}}"></span>
												<span class="firstlike">Like</span>
											@endif
										</label>
									</div>
								</li>
								<li>
									<span class="icon flaticon-interface-1"></span>
									@if($commentscountdata > 0)
										<span class="commentcount">{{ $commentscountdata }} Comments</span>
									@else
										<span class="commentcount">Comment</span>
									@endif
								</li>
							</ul>
						</div><!--/post actions-->
					</div><!--pop post footer-->
				</div><!--/single post-->
			</div>
			<div class="col-sm-4 pop-comment-side-outer">
				<div class="pop-comment-side">
					<div class="post-comment-cont">
						<div class="comments-list">
							<ul id="popupcomment-{{$feeddata->id}}">
								@foreach($comments as $data)
									<?php $username = App\User::where('id', '=', $data->commented_by)->get()->first(); ?>

									<?php $userpicture = !empty($username->picture) ? $username->picture : '/images/user-thumb.jpg'; ?>

									<li data-value="{{ $data->id }}" id="post_{{ $data->id }}">
						<?php if($data->commented_by==Auth::User()->id)
						{ 
						$divadd="cmt-data"
						?>
							<button type="button" class="p-del-btn comment-delete" data-toggle="modal" data-target=".comment-del-confrm"><span class="glyphicon glyphicon-remove"></span></button>
										<br>
							<button type="button" class="p-edit-btn edit-comment" data-toggle="modal" title="Edit" data-target=".edit-comment-popup"><i class="fa fa-pencil"></i></button>
					<?php } else {
						$divadd="";
						} ?>

										<span class="user-thumb" style="background: url('{{$userpicture}}');"></span>
										<div class="{{$divadd}}">
										<div class="comment-title-cont">
											<div class="row">
												<div class="col-sm-6">
													<a href="profile/{{$username->id}}" title="" class="user-link">{{ $username->first_name.' '.$username->last_name }}</a>
												</div>
												<div class="col-sm-6">
													<div class="text-right">
														<div class="date-time-list">
															<span><div class="comment-time text-right">{{ $data->updated_at->format('h:i A') }}</div></span>
															<span><div class="comment-time text-right">{{ $data->updated_at->format('D jS') }}</div></span>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="comment-text"><?= nl2br($data->comments) ?></div>
									</div>
									</li>
								@endforeach 
							</ul> 
						</div>
					</div>
				</div>

				<div class="pop-post-comment post-comment" data-value="{{ $feeddata->id }}" id="post_{{ $feeddata->id }}">

					<div class="emoji-field-cont cmnt-field-cont">
						<textarea type="text" class="form-control comment-field" data-emojiable="true" placeholder="Type here..."></textarea> 
						<button type="button" class="btn-icon btn-cmnt comment"><i class="flaticon-letter"></i></button>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>


<!-- <script type="text/javascript" src="/js/bootstrap-filestyle.min.js"></script>
<script src="/lib/js/nanoscroller.min.js"></script>
<script src="/lib/js/tether.min.js"></script>
<script src="/lib/js/config.js"></script>
<script src="/lib/js/util.js"></script>
<script src="/lib/js/jquery.emojiarea.js"></script>
<script src="/lib/js/emoji-picker.js"></script>
<script src="/js/jquery.nicescroll.min.js"></script>
<script src="c-lib/lib/js/emojione.js"></script> -->
<script>

	$(document).ready(function(){
		//Popup caption emoji fix.
		var popupcaption = jQuery('#AllComment').find('.pop-post-text p').html();
		// alert(popupcaption);
		var caption = emojione.toImage(popupcaption);
		jQuery('#AllComment').find('.pop-post-text p').html(caption);	
	});

$(".comment-text").each(function() {
	var original = $(this).html();
	// use .shortnameToImage if only converting shortnames (for slightly better performance)
	var converted = emojione.toImage(original);
	$(this).html(converted);
});

$('.pop-comment-side .post-comment-cont').niceScroll();
var postsonajax = $('.pop-post-text').find('p').html();
if(postsonajax == ''){
	$('.pop-post-text').remove();
}

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

</script>

