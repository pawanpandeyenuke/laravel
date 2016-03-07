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
		    <div class="panel-heading" role="tab" id="headingThree">
		      <h4 class="panel-title">
		        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#side_acc_menu" href="{{ url('chatroom') }}" aria-expanded="false" aria-controls="collapseThree">
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
		        	@foreach($parent_category as $data)
			        	<li class="dropdown keep-open">
		        			<a id="dLabel" data-target="#" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								{{ $data->title }}
							</a>
			        		<?php 
			        			$subCat = DB::table('categories')->where(['parent_id' => $data->id])->get(); 
			        			if(!empty($subCat)){ ?>
								<ul class="dropdown-menu side-dd-menu" aria-labelledby="dLabel">
				        			@foreach($subCat as $data1)
				        				<li><a href="">{{ $data1->title }}</a></li>
				        			@endforeach
				        		</ul>
							<?php } ?>
			        	</li>
			        @endforeach
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
		        <a href="{{ url('settings/privacy') }}" class="without-arrow">
		          <i class="flaticon-tool"></i>Privacy Setting
		        </a>
		      </h4>
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