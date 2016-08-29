<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>FriendzSquare</title>
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/font-awesome.min.css" rel="stylesheet" media="all">
<link href="css/flat-icon/flaticon.css" rel="stylesheet" media="all">
<link href="css/fileinput.min.css" rel="stylesheet" media="all">
<link href="fancybox/jquery.fancybox.css" rel="stylesheet" media="all">
<link href="css/select2.min.css" rel="stylesheet" media="all">
<!--Emoji-->
<link href="lib/css/nanoscroller.css" rel="stylesheet">
<link href="lib/css/emoji.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/responsive.css" rel="stylesheet" media="all">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="dashboard">
<header>
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				<a href="#" title="" class="logo"><img src="images/logo.png" alt="Friendz Square"></a>
			</div>
			<div class="col-sm-6">
				<div class="top-search">
					<ul class="clearfix">
						<li class="search-textbox">
							<input type="text" class="search-field" placeholder="Search Friends">
						</li>
						<li>
							<select class="search-field">
								<option>Country</option>
							</select>
						</li>
						<li>
							<select class="search-field">
								<option>State</option>
							</select>
						</li>
						<li>
							<select class="search-field">
								<option>City</option>
							</select>
						</li>
						<li class="search-btn-cont">
							<button type="button" class="search-btn"><i class="glyph-icon flaticon-magnifyingglass138"></i></button>
						</li>
					</ul>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="dashboard-header-menu text-right">
					<ul class="list-inline">
						<li class="user-info-top">
							<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
							Ami Koehler
						</li>
						<li><div class="logout"><a href="#" title="">Logout</a></div></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</header><!--/header-->
<div class="page-data dashboard-body">
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				<div class="dashboard-sidemenu">
					<div class="panel-group" id="side_acc_menu" role="tablist" aria-multiselectable="true">
					  <div class="panel panel-default">
					    <div class="panel-heading" role="tab" id="headingOne">
					      <h4 class="panel-title">
					        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#side_acc_menu" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
					          <i class="flaticon-interface"></i>News/Friends Feed
					        </a>
					      </h4>
					    </div>
					    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
					      <div class="panel-body">
					        <ul>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        </ul>
					      </div>
					    </div>
					  </div>
					  <div class="panel panel-default">
					    <div class="panel-heading" role="tab" id="headingTwo">
					      <h4 class="panel-title">
					        <a role="button" data-toggle="collapse" data-parent="#side_acc_menu" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
					          <i class="flaticon-balloon"></i>Chat Room
					        </a>
					      </h4>
					    </div>
					    <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
					      <div class="panel-body">
					        <ul>
					        	<li><a href="">Teenagers</a></li>
					        	<li><a href="">Adults</a></li>
					        	<li><a href="">Retired</a></li>
					        	<li><a href="">Politics</a></li>
					        	<li><a href="">Products</a></li>
					        	<li><a href="">Auto</a></li>
					        	<li><a href="">Travel</a></li>
					        	<li><a href="">Movie Review</a></li>
					        </ul>
					      </div>
					    </div>
					  </div>
					  <div class="panel panel-default">
					    <div class="panel-heading" role="tab" id="headingThree">
					      <h4 class="panel-title">
					        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#side_acc_menu" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
					          <i class="flaticon-balloon"></i>Chat with Friends
					        </a>
					      </h4>
					    </div>
					    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
					      <div class="panel-body">
					        <ul>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        </ul>
					      </div>
					    </div>
					  </div>
					  <div class="panel panel-default">
					    <div class="panel-heading" role="tab" id="headingfour">
					      <h4 class="panel-title">
					        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#side_acc_menu" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
					          <i class="flaticon-people"></i>Friends Request
					        </a>
					      </h4>
					    </div>
					    <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingfour">
					      <div class="panel-body">
					        <ul>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        </ul>
					      </div>
					    </div>
					  </div>
					  <div class="panel panel-default">
					    <div class="panel-heading" role="tab" id="headingFive">
					      <h4 class="panel-title">
					        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#side_acc_menu" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
					          <i class="flaticon-avatar83"></i>Profile
					        </a>
					      </h4>
					    </div>
					    <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
					      <div class="panel-body">
					        <ul>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        </ul>
					      </div>
					    </div>
					  </div>
					  <div class="panel panel-default">
					    <div class="panel-heading" role="tab" id="headingSix">
					      <h4 class="panel-title">
					        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#side_acc_menu" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
					          <i class="flaticon-tool"></i>Privacy Setting
					        </a>
					      </h4>
					    </div>
					    <div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">
					      <div class="panel-body">
					        <ul>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        </ul>
					      </div>
					    </div>
					  </div>
					  <div class="panel panel-default">
					    <div class="panel-heading" role="tab" id="headingSeven">
					      <h4 class="panel-title">
					        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#side_acc_menu" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
					          <i class="flaticon-round"></i>More
					        </a>
					      </h4>
					    </div>
					    <div id="collapseSeven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven">
					      <div class="panel-body">
					        <ul>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        	<li><a href="">link title here</a></li>
					        </ul>
					      </div>
					    </div>
					  </div>
					</div>
				</div><!--/dashboard sidemenu-->
			</div>
			<div class="col-sm-6">
				<div class="shadow-box page-center-data no-margin-top">
					<div class="page-title green-bg">
						<i class="flaticon-user-profile"></i>Forums
					</div>

					<div class="padding-data-inner">
						<div class="forum-filter">
							<div class="row">
								<div class="col-md-4">
									<select class="form-control">
										<option>School Reviews</option>
									</select>
								</div>
								<div class="col-md-4">
									<select class="form-control">
										<option>City</option>
									</select>
								</div>
								<div class="col-md-4">
									<input type="text" name="" value="" placeholder="Search Keyword" class="form-control">
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<select class="form-control">
										<option>India</option>
									</select>
								</div>
								<div class="col-md-4">
									<select class="form-control">
										<option>Haryana</option>
									</select>
								</div>
								<div class="col-md-4">
									<select class="form-control">
										<option>Gurgaon</option>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4 col-md-offset-4">
									<div class="forum-btn-cont text-center">
										<button type="button" class="btn btn-primary">Search</button>
									</div>
								</div>
							</div>
						</div><!--/forum filter-->

						<div class="forum-srch-list">
							<div class="fs-breadcrumb"><a href="#" title="">Home</a> > <a href="#" title="">School Reviews</a> > Gurgaon, Haryana, India</div>

							<div class="forum-master-post">
								<div class="fp-master-header">
									<div class="row">
										<div class="col-md-6">
											<div class="ut-name">
												<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
												Ami Koehler
											</div>
										</div>
										<div class="col-md-6">
											<div class="fp-master-header-right">
												<div class="fp-likes pull-left"><i class="flaticon-web"></i> <span class="plike-count">19</span></div>
												<span class="p-date pull-left"><i class="flaticon-days"></i> 01 Jan 2016</span>
												<span class="p-time pull-left"><i class="flaticon-time"></i> 02:00 pm</span>
											</div>
										</div>
									</div>
								</div>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
								<div class="text-right">
									<button type="button" class="btn btn-primary mpost-rply-btn">Reply</button>
								</div>
							</div>

							<div class="forum-post-replies">
							
								<div class="forum-post-cont">
									<div class="posts-count"><i class="flaticon-two-post-it"></i> 456 Posts</div>
								</div><!--/forum post cont-->

								<div class="f-post-form f-post-reply-form">
									<textarea name="" class="form-control" data-emojiable="true"></textarea>
									<button type="button" class="btn btn-primary">Submit</button>
								</div>

								<div class="f-post-list-outer clearfix">
									<div class="f-single-post">
										<div class="p-user">
											<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
											<div class="p-likes ml"><i class="flaticon-web"></i> <span class="plike-count">19</span></div>
											<div class="p-likes ml"><a href="#AllCommentNew" class="popup"><i class="fa fa-comment" aria-hidden="true"></i> <span class="plike-count">15</span></a></div>
										</div>

										<div class="f-post-title">
											Author Name
											<div class="fp-meta">
												<span class="p-date"><i class="flaticon-days"></i> 01 Jan 2016</span>
												<span class="p-time"><i class="flaticon-time"></i> 02:00 pm</span>
											</div>
											<div class="fp-action">
												<a href="#" title=""><i class="flaticon-pencil"></i></a>
												<a href="#" title=""><i class="flaticon-garbage"></i></a>
											</div>
										</div>

										<p class="more">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation </p>

									</div><!--/single post-->

									<div id="AllCommentNew" class="post-list popup-list-without-img" style="display: none;">
										<div class="single-post">
											<div class="pop-post-header">
												<div class="post-header">
													<div class="row">
														<div class="col-md-7">
															<a href="#" title="" class="user-thumb-link">
																<span class="small-thumb" style="background: url('images/user-thumb.jpg');"></span>
																Ami Koehler
															</a>
														</div>
														<div class="col-md-5">
															<div class="post-time text-right">
																<ul>
																	<li><span class="icon flaticon-time">4:15 PM</span></li>
																	<li><span class="icon flaticon-days">7 WED</span></li>
																</ul>
															</div>
														</div>
													</div>
												</div><!--/post header-->
												<div class="pop-post-text clearfix">
													<p>If you live long enough, you'll make mistakes. But if you learn from them, you'll be a better person. It's how you handle adversity, not how it affects you. The main thing is never quit, never quit, never quit.</p>
												</div>
											</div>
											<div class="post-footer pop-post-footer">
												<div class="post-actions">
													<ul>
														<li>
															<div class="like-cont">
																<input type="checkbox" name="checkboxG4" id="checkboxG4" class="css-checkbox" />
																<label for="checkboxG4" class="css-label">55 <span>Likes</span></label>
															</div>
														</li>
														<li><span class="icon flaticon-interface-1"></span> 25 <span>Comments</span></li>
													</ul>
												</div><!--/post actions-->
											</div><!--pop post footer-->
										</div><!--/single post-->

										<div class="post-comment-cont">
											<div class="comments-list">
												<ul>
													<li>
														<button type="button" class="p-del-btn comment-delete" data-toggle="modal" data-target=".comment-del-confrm"><span class="glyphicon glyphicon-remove"></span></button>

														<div class="modal fade comment-del-confrm" tabindex="-1" role="dialog" aria-labelledby="DeletePost">
														  <div class="modal-dialog modal-sm">
														    <div class="modal-content">
														    	<div class="modal-body text-center">
														        <h5>Are you sure to delete this post?</h5>
														      </div>
														      <div class="modal-footer text-center">
														        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
														        <button type="button" class="btn btn-primary">Delete</button>
														      </div>
														    </div>
														  </div>
														</div>


														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">Yesterday</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Some comment text here...</div>
													</li>
													<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">2.45 PM</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Nice comment...</div>
													</li>
													<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">2.45 PM</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Nice comment...</div>
													</li>
													<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">2.45 PM</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Nice comment...</div>
													</li>
													<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">2.45 PM</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Nice comment...</div>
													</li>
													<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">2.45 PM</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Nice comment...</div>
													</li>
													<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">2.45 PM</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Nice comment...</div>
													</li>
													<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">2.45 PM</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Nice comment...</div>
													</li>
													<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">2.45 PM</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Nice comment...</div>
													</li>
													<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">2.45 PM</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Nice comment...</div>
													</li>
													<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">2.45 PM</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Nice comment...</div>
													</li>
													<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">2.45 PM</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Nice comment...</div>
													</li>
													<li>
														<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
														<div class="comment-title-cont">
															<div class="row">
																<div class="col-sm-6">
																	<a href="#" title="" class="user-link">Navi Sappal</a>
																</div>
																<div class="col-sm-6">
																	<div class="comment-time text-right">2.45 PM</div>
																</div>
															</div>
														</div>
														<div class="comment-text">Nice comment...</div>
													</li>
												</ul>
											</div>
										</div>
										<div class="pop-post-comment post-comment">
											<div class="emoji-field-cont cmnt-field-cont">
												<textarea type="text" class="form-control comment-field" data-emojiable="true" placeholder="Type here..."></textarea>
												<button type="button" class="btn-icon btn-cmnt"><i class="flaticon-letter"></i></button>
											</div>
										</div>
									</div>

									<div class="f-single-post">
										<div class="p-user">
											<span class="user-thumb" style="background: url('images/user-thumb.jpg');"></span>
											<div class="p-likes ml"><i class="flaticon-web"></i> <span class="plike-count">19</span></div>
											<div class="p-likes ml"><i class="fa fa-comment" aria-hidden="true"></i> <span class="plike-count">15</span></div>
										</div>

										<div class="f-post-title">
											Author Name
											<div class="fp-meta">
												<span class="p-date"><i class="flaticon-days"></i> 01 Jan 2016</span>
												<span class="p-time"><i class="flaticon-time"></i> 02:00 pm</span>
											</div>
											<div class="fp-action">
												<a href="#" title=""><i class="flaticon-pencil"></i></a>
												<a href="#" title=""><i class="flaticon-garbage"></i></a>
											</div>
										</div>

										<p class="more">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation </p>

									</div><!--/single post-->




								</div>

							</div>

						</div><!--/forum search list-->
					</div>

				</div><!--/page center data-->
				<div class="shadow-box bottom-ad"><img src="images/bottom-ad.jpg" alt="" class="img-responsive"></div>
			</div>
			<div class="col-sm-3">
				<div class="side-btn">
					<a href="#" title="" class="btn btn-lg btn-full btn-primary">Suggestions</a>
				</div><!--/side btn-->
				<div class="side-widget-cont">
					<img src="images/side-ad.jpg" alt="" class="img-responsive side-ad">
				</div>
			</div>
		</div>
	</div>
</div><!--/pagedata-->
<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/fileinput.min.js"></script>
<script type="text/javascript" src="fancybox/jquery.fancybox.js"></script>
<script type="text/javascript" src="js/bootstrap-filestyle.min.js"></script>
<script src="js/select2.min.js"></script>
<!--Emoji libraries-->
<script src="lib/js/nanoscroller.min.js"></script>
<script src="lib/js/tether.min.js"></script>
<script src="lib/js/config.js"></script>
<script src="lib/js/util.js"></script>
<script src="lib/js/jquery.emojiarea.js"></script>
<script src="lib/js/emoji-picker.js"></script>
<script src="js/jquery.nicescroll.min.js"></script>
<!--/Emoji-->
<script type="text/javascript">
$(".multiple-slt").select2();

	$("#up_imgs").fileinput({
    uploadUrl: "/file-upload-batch/2",
    allowedFileExtensions: ["jpg", "png", "gif"],
    minImageWidth: 30,
    minImageHeight: 30,
    showCaption: false,
	});
	$('.popup').fancybox();

	$('.popup-list-without-img .comments-list').niceScroll();

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
	$('.pop-comment-side .post-comment-cont').niceScroll();

	$(document).on('click','.mpost-rply-btn',function(){
		$('.f-post-reply-form').slideToggle();
	});


	// More Less Text

	$(document).ready(function() {
	  var showChar = 300;
	  var ellipsestext = "...";
	  var moretext = "more";
	  var lesstext = "less";
	  $('.more').each(function() {
	      var content = $(this).html();

	      if(content.length > showChar) {

	          var c = content.substr(0, showChar);
	          var h = content.substr(showChar-1, content.length - showChar);

	          var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

	          $(this).html(html);
	      }

	  });

	  $(".morelink").click(function(){
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
	});

</script>
</body>
</html>
