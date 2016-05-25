						<div class='f-single-post'>
									<div class='p-user'>
										<span class="user-thumb" style="background: url('{{$profileimage}}');"></span>
										<span class='p-date'><i class='flaticon-days'></i> {{$forumpostid->updated_at->format('d M Y')}}</span>
										<span class='p-time'><i class='flaticon-time'></i> {{$forumpostid->updated_at->format('h:i A')}}</span>
										<div class='p-likes'><i class='flaticon-web'></i> <span class='plike-count'>19</span></div>
									</div>
									<div class='f-post-title'>
									<a href="{{url("profile/$user->id")}}" title=''>
										{{$name}}
										<a>
										<div class='fp-action'>
											<button class='editforumpost' value='{{$forumpostid->id}}'  data-toggle='modal' title='Edit' data-target='.edit-forumpost-popup'><i class='flaticon-pencil'></i></button>
											<button class='forumpostdelete' value='{{$forumpostid->id}}'><i class='flaticon-garbage'></i></button>
										</div>
									</div>
									<p>{{$forumpostid->title}} </p>
									<div class='fp-btns text-right'>
										<span class='btn btn-primary'>Replies(8)</span>
										<a href='#' title='' class='btn btn-primary'><span class='glyphicon glyphicon-share-alt'></span>Reply</a>
									</div>
								</div>