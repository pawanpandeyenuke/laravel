

	<!-- Delete comment confirmation box -->
	<?php
	 ?>
	  <div class="modal-dialog modal-sm">
	    <div class="modal-content" id="delete-confirm" data-value="{{$commentId}}" data-feedid="{{$feedId}}">
	    	<div class="modal-body text-center">
	        <h5>Are you sure to delete this ?</h5>
	      </div>
	      <div class="modal-footer text-center">
	      	<input type="hidden"></input>
	        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	        <button type="button" class="btn btn-primary {{$class}}" data-dismiss="modal">Delete</button>
	      </div>
	    </div>
	  </div>

	<!-- Delete comment confirmation box -->