<?php 
  $userid = Auth::check() ? Auth::User()->id : 0;
?>
<!-- Delete user image box -->
  <div class="modal fade" data-backdrop="static" data-keyboard="false" id="remove-image-area" tabindex="-1" role="dialog" aria-labelledby="">
    <div class="modal-dialog modal-sm">
      <div class="modal-content" id="" data-value="">
        <div class="modal-body text-center">

          <div class="alert alert-success success-msg" style="display:none">
            <strong>Success!</strong> Image removed successfully.
          </div>

          <h5>Are you sure you want to delete this picture?</h5>
        </div>
        <div class="modal-footer text-center">
          <input type="hidden"/>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary removeimg" data-userid="{{ $userid }}">Delete</button>
        </div>
      </div>
    </div>
  </div>
<!-- Delete user image box -->