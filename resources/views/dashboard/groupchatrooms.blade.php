	<?php $count = 0; ?>
	@foreach($parent_category as $data)
		@if($count <= 9)
        	<div style="border:solid 1px;height:100px;width:110px;float:left;margin:15px;padding:5px">
    			<a id="dLabel" data-target="#" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
					{{ $data->title }}
				</a>
        		<?php 
        			$subCat = DB::table('categories')->where(['parent_id' => $data->id])->get(); 
        			// echo '<pre>';print_r($subCat);//die;
        			if(!empty($subCat)){ ?>
        				<br/><a style="border:solid 1px;height:50px;width:auto;padding:5px;margin-top:10px;margin-left:5px;margin-right:5px" data-parentid="{{ $data->id }}" class="groupnext">Next >></a>
				<?php }else{ ?> 
						<br/><a style="border:solid 1px;height:50px;width:auto;padding:5px;margin:5px;" class="enterchat" data-parentid="{{ $data->id }}">Enter Chat</a>
				<?php } ?>
        	</div>
    		<?php $count = $count + 1; ?>
    	@endif
    @endforeach