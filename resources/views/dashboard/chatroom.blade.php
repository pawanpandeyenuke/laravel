@extends('layouts.dashboard')

@include('panels.meta-data')

@section('content')
<div class="page-data dashboard-body">
	<div class="container">
		<div class="row">
			@include('panels.left')
			<div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top no-bottom-padding">
					<div class="row">
						<div class="col-sm-4 padding-right-none chat-list-outer">
							<div class="chat-list-search">
								<div class="form-group">
									<input type="text" class="form-control" placeholder="Search">
									<button type="button" class="search-btn"><i class="glyph-icon flaticon-magnifyingglass138"></i></button>
								</div>
							</div>
							<div class="chat-user-list StyleScroll">
								<ul>
									@foreach($groups as $data)
									<li>
										<a href="#" title="">
											<span class="chat-thumb"style="background: url('images/user-thumb.jpg');"></span>
											<span class="title">{{ $data->title }}</span>
											<span class="time">{{ $data->updated_at->diffForHumans() }}</span>
										</a>
									</li>
									@endforeach
								</ul>
							</div><!--/chat user list-->
							<div class="dropdown all-contact">
							  <button id="dLabel" class="all-contact-btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							    All Contacts
							  </button>
							  <ul class="dropdown-menu user-list-with-thumb StyleScroll" aria-labelledby="dLabel">
							    <li>
										<a href="#" title="">
											<span class="chat-thumb"style="background: url('images/user-thumb.jpg');"></span>
											<span class="title">Username</span>
											<span class="time">02:50 am</span>
										</a>
									</li>
									<li>
										<a href="#" title="">
											<span class="chat-thumb"style="background: url('images/user-thumb.jpg');"></span>
											<span class="title">Username</span>
											<span class="time">02:50 am</span>
										</a>
									</li>
									<li>
										<a href="#" title="">
											<span class="chat-thumb"style="background: url('images/user-thumb.jpg');"></span>
											<span class="title">Username</span>
											<span class="time">02:50 am</span>
										</a>
									</li>
									<li>
										<a href="#" title="">
											<span class="chat-thumb"style="background: url('images/user-thumb.jpg');"></span>
											<span class="title">Username</span>
											<span class="time">02:50 am</span>
										</a>
									</li>
									<li>
										<a href="#" title="">
											<span class="chat-thumb"style="background: url('images/user-thumb.jpg');"></span>
											<span class="title">Username</span>
											<span class="time">02:50 am</span>
										</a>
									</li>
									<li>
										<a href="#" title="">
											<span class="chat-thumb"style="background: url('images/user-thumb.jpg');"></span>
											<span class="title">Username</span>
											<span class="time">02:50 am</span>
										</a>
									</li>
									<li>
										<a href="#" title="">
											<span class="chat-thumb"style="background: url('images/user-thumb.jpg');"></span>
											<span class="title">Username</span>
											<span class="time">02:50 am</span>
										</a>
									</li>
									<li>
										<a href="#" title="">
											<span class="chat-thumb"style="background: url('images/user-thumb.jpg');"></span>
											<span class="title">Username</span>
											<span class="time">02:50 am</span>
										</a>
									</li>

							  </ul>
							</div>
						</div>
						<div class="col-sm-8">
							<div class="chatting-outer">
								<div class="chat-header">
									<div class="row">
										<div class="col-sm-6">
											<div class="user online"> <!--offline/online-->
												<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
												Endrew
												<span class="status"></span>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="dropdown setting-btn text-right">
											  <button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="flaticon-tool"></i></button>
											  <ul class="dropdown-menu" aria-labelledby="dLabel">
											    <li><a href="#" title="">Menu title here</a></li>
											    <li><a href="#" title="">Menu title here</a></li>
											    <li><a href="#" title="">Menu title here</a></li>
											    <li><a href="#" title="">Menu title here</a></li>
											  </ul>
											</div>
										</div>
									</div>
								</div>
								<div class="chat-thread StyleScroll">
									<div class="msg msg-receive clearfix">
										<span class="msg-thumb" style="background: url('images/user-thumb.jpg');"></span>
										<div class="msg-text">Hi!!!</div>
										<span class="time">02:56 am</span>
									</div><!--/msg receive-->

									<div class="msg msg-send clearfix">
										<span class="msg-thumb" style="background: url('images/user-thumb.jpg');"></span>
										<div class="msg-text">Hi, How r u?</div>
										<span class="time">02:56 am</span>
									</div><!--/msg send-->

									<div class="msg msg-receive clearfix">
										<span class="msg-thumb" style="background: url('images/user-thumb.jpg');"></span>
										<div class="msg-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div>
										<span class="time">02:56 am</span>
									</div><!--/msg receive-->

									<div class="msg msg-send clearfix">
										<span class="msg-thumb" style="background: url('images/user-thumb.jpg');"></span>
										<div class="msg-text">Lorem ipsum dolor sit amet.</div>
										<span class="time">02:56 am</span>
									</div><!--/msg send-->

									<div class="msg msg-receive clearfix">
										<span class="msg-thumb" style="background: url('images/user-thumb.jpg');"></span>
										<div class="msg-text">consectetur adipiscing elit</div>
										<span class="time">02:56 am</span>
									</div><!--/msg receive-->

									<div class="msg msg-send clearfix">
										<span class="msg-thumb" style="background: url('images/user-thumb.jpg');"></span>
										<div class="msg-text">Labore et dolore magna aliqua.</div>
										<span class="time">02:56 am</span>
									</div><!--/msg send-->

									<div class="msg msg-receive clearfix">
										<span class="msg-thumb" style="background: url('images/user-thumb.jpg');"></span>
										<div class="msg-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div>
										<span class="time">02:56 am</span>
									</div><!--/msg receive-->

									<div class="msg msg-send clearfix">
										<span class="msg-thumb" style="background: url('images/user-thumb.jpg');"></span>
										<div class="msg-text">Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div>
										<span class="time">02:56 am</span>
									</div><!--/msg send-->

									<div class="msg msg-receive clearfix">
										<span class="msg-thumb" style="background: url('images/user-thumb.jpg');"></span>
										<div class="msg-text">Adipiscing elit.</div>
										<span class="time">02:56 am</span>
									</div><!--/msg receive-->

									<div class="msg msg-send clearfix">
										<span class="msg-thumb" style="background: url('images/user-thumb.jpg');"></span>
										<div class="msg-text">Ok Bye.</div>
										<span class="time">02:56 am</span>
									</div><!--/msg send-->

								</div><!--/chat thread-->
								
								<div class="chat-field-cont">
									<div class="pop-post-comment chat-field">
										<div class="emoji-field-cont cmnt-field-cont">
											<textarea type="text" class="form-control comment-field" data-emojiable="true" placeholder="Type here..."></textarea>
											<input type="file" class="filestyle" data-input="false" data-iconName="flaticon-clip"  data-buttonName="btn-icon btn-cmnt-attach" multiple="multiple">
											<!-- <button type="button" class="btn-icon btn-cmnt-attach"><i class="flaticon-clip"></i></button> -->
											<button type="button" class="btn-icon btn-cmnt"><i class="flaticon-letter"></i></button>
										</div>
									</div>
								</div>
							</div><!--/chatting-outer-->
						</div>
					</div>
				</div>
				@include('panels.footer-advertisement')
			</div>
			@include('panels.right')
		</div>
	</div>
</div><!--/pagedata-->

@endsection