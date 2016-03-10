<?php // echo '<pre>';print_r($groupname->group_name);die; ?>
 <div class="shadow-box page-center-data no-margin-top no-bottom-padding">
					<div class="row">
						<div class="col-sm-4 padding-right-none chat-list-outer">
							<div class="chat-list-search">
								<div class="form-group">
									<input type="text" placeholder="Search" class="form-control">
									<button class="search-btn" type="button"><i class="glyph-icon flaticon-magnifyingglass138"></i></button>
								</div>
							</div>
							<div class="chat-user-list StyleScroll" style="overflow: hidden;" tabindex="0">
								<ul>

									@foreach($userdata as $data)
									<li>
										<a title="" href="#">
											<span style="background: url('images/user-thumb.jpg');" class="chat-thumb"></span>
											<span class="title">{{ $data['user']['first_name'].' '.$data['user']['last_name'] }}</span>
											<span class="time">02:50 am</span>
											<!-- <span class="msg">Hi, How r u?</span> -->
										</a>
									</li>
									@endforeach
								</ul>
							</div><!--/chat user list-->
							<div class="dropdown all-contact">
							  <button aria-expanded="false" aria-haspopup="true" data-toggle="dropdown" type="button" class="all-contact-btn" id="dLabel">
							    All Contacts
							  </button>
							  <ul aria-labelledby="dLabel" class="dropdown-menu user-list-with-thumb StyleScroll" style="overflow: hidden;" tabindex="1">
									@foreach($userdata as $data)
									<li>
										<a title="" href="#">
											<span style="background: url('images/user-thumb.jpg');" class="chat-thumb"></span>
											<span class="title">{{ $data['user']['first_name'].' '.$data['user']['last_name'] }}</span>
											<span class="time">02:50 am</span>
											<!-- <span class="msg">Hi, How r u?</span> -->
										</a>
									</li>
									@endforeach
							  </ul>
							</div>
						</div>
						<div class="col-sm-8">
							<div class="chatting-outer">
								<div class="chat-header">
									<div class="row">
										<div class="col-sm-6">
											<div class="user online"> <!--offline/online-->
												<span style="background: url('images/user-thumb.jpg');" class="user-thumb"></span>
												 {{$groupname->group_name}}
												<span class="status"></span>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="dropdown setting-btn text-right">
											  <button aria-expanded="false" aria-haspopup="true" data-toggle="dropdown" type="button" id="dLabel"><i class="flaticon-tool"></i></button>
											  <ul aria-labelledby="dLabel" class="dropdown-menu">
											    <li><a title="" href="#">Menu title here</a></li>
											    <li><a title="" href="#">Menu title here</a></li>
											    <li><a title="" href="#">Menu title here</a></li>
											    <li><a title="" href="#">Menu title here</a></li>
											  </ul>
											</div>
										</div>
									</div>
								</div>
								<div class="chat-thread StyleScroll" style="overflow: hidden;" tabindex="2">
									<div class="msg msg-receive clearfix">
										<span style="background: url('images/user-thumb.jpg');" class="msg-thumb"></span>
										<div class="msg-text">Hi!!!</div>
										<span class="time">02:56 am</span>
									</div><!--/msg receive-->

									<div class="msg msg-send clearfix">
										<span style="background: url('images/user-thumb.jpg');" class="msg-thumb"></span>
										<div class="msg-text">Hi, How r u?</div>
										<span class="time">02:56 am</span>
									</div><!--/msg send-->

									<div class="msg msg-receive clearfix">
										<span style="background: url('images/user-thumb.jpg');" class="msg-thumb"></span>
										<div class="msg-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div>
										<span class="time">02:56 am</span>
									</div><!--/msg receive-->

									<div class="msg msg-send clearfix">
										<span style="background: url('images/user-thumb.jpg');" class="msg-thumb"></span>
										<div class="msg-text">Lorem ipsum dolor sit amet.</div>
										<span class="time">02:56 am</span>
									</div><!--/msg send-->

									<div class="msg msg-receive clearfix">
										<span style="background: url('images/user-thumb.jpg');" class="msg-thumb"></span>
										<div class="msg-text">consectetur adipiscing elit</div>
										<span class="time">02:56 am</span>
									</div><!--/msg receive-->

									<div class="msg msg-send clearfix">
										<span style="background: url('images/user-thumb.jpg');" class="msg-thumb"></span>
										<div class="msg-text">Labore et dolore magna aliqua.</div>
										<span class="time">02:56 am</span>
									</div><!--/msg send-->

									<div class="msg msg-receive clearfix">
										<span style="background: url('images/user-thumb.jpg');" class="msg-thumb"></span>
										<div class="msg-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div>
										<span class="time">02:56 am</span>
									</div><!--/msg receive-->

									<div class="msg msg-send clearfix">
										<span style="background: url('images/user-thumb.jpg');" class="msg-thumb"></span>
										<div class="msg-text">Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div>
										<span class="time">02:56 am</span>
									</div><!--/msg send-->

									<div class="msg msg-receive clearfix">
										<span style="background: url('images/user-thumb.jpg');" class="msg-thumb"></span>
										<div class="msg-text">Adipiscing elit.</div>
										<span class="time">02:56 am</span>
									</div><!--/msg receive-->

									<div class="msg msg-send clearfix">
										<span style="background: url('images/user-thumb.jpg');" class="msg-thumb"></span>
										<div class="msg-text">Ok Bye.</div>
										<span class="time">02:56 am</span>
									</div><!--/msg send-->

								</div><!--/chat thread-->
								
								<div class="chat-field-cont">
									<div class="pop-post-comment chat-field">
										<div class="emoji-field-cont cmnt-field-cont">
											<textarea placeholder="Type here..." data-emojiable="converted" class="form-control comment-field" type="text" style="display: none;" data-id="6b7a2a55-7ece-40d4-8005-f9fc007dc82e" data-type="original-input"></textarea><div contenteditable="true" class="emoji-wysiwyg-editor form-control comment-field" data-id="6b7a2a55-7ece-40d4-8005-f9fc007dc82e" data-type="input" placeholder="Type here..."></div><i data-type="picker" data-id="6b7a2a55-7ece-40d4-8005-f9fc007dc82e" class="emoji-picker-icon emoji-picker fa fa-smile-o"></i>
											<input type="file" multiple="multiple" data-buttonname="btn-icon btn-cmnt-attach" data-iconname="flaticon-clip" data-input="false" class="filestyle" id="filestyle-0" style="position: absolute; clip: rect(0px, 0px, 0px, 0px);" tabindex="-1"><div class="bootstrap-filestyle input-group"><span class="group-span-filestyle " tabindex="0"><label class="btn btn-icon btn-cmnt-attach " for="filestyle-0"><span class="icon-span-filestyle flaticon-clip"></span> <span class="buttonText">Choose file</span></label></span></div>
											<!-- <button type="button" class="btn-icon btn-cmnt-attach"><i class="flaticon-clip"></i></button> -->
											<button class="btn-icon btn-cmnt" type="button"><i class="flaticon-letter"></i></button>
										</div>
									</div>
								</div>
							</div><!--/chatting-outer-->
						</div>
					</div>
				</div>
  