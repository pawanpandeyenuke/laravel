<div class="col-sm-3">
	<div class="side-btn">
		<a href="#" title="" data-toggle="modal" data-target="#myModal" class="btn btn-lg btn-full btn-primary">Suggestions</a>
	  <form id="suggestionform" class="form-horizontal" role="form" method="post" action="{{url('/contactus')}}" >
                            <div class="modal fade send-msg-popup" id="myModal" tabindex="-1" role="dialog" aria-labelledby="sendMsgLabel">
                           
                              <div class="modal-dialog modal-sm" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="sendMsgLabel" style="text-align: center;">Suggestion box</h4>
                                  </div>
                                  <div class="modal-body">
                                   <div class="row">
                                   <div class='alert alert-success successmsg'  style='text-align: center; display: none;'>Thank you for your feedback!<br><a href='#' class='modalshow'>Have another one?</a></div>
                                    <div class="col-md-10 col-md-offset-1 successmsg">
                                        <div class="profile-select-cont form-group">
                                            <textarea name="message_text" class="form-control message_text" placeholder="Enter suggestion" required></textarea>
                                        </div>
                                        <div class="profile-select-cont form-group">
                                            <input name="email" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" placeholder="Enter email" class="form-control useremail" >
                                        </div>
                                    </div>
                                   </div>
                                    
                                  </div>
                                  <div class="modal-footer">
                                    <input id="submit" name="submit" type="submit" value="Send" class="btn btn-primary">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                           </form>
	</div><!--/side btn-->
	<div class="side-widget-cont">
		<img src="/images/side-ad.jpg" alt="" class="img-responsive side-ad">
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="http://malsup.github.com/jquery.form.js"></script> 
<script type="text/javascript">
  
  $("#suggestionform").ajaxForm(function(response) {
      if(response == "success")
      {
        $('.modal-title').hide();
        $('.modal-footer').hide();
        $('.successmsg').toggle();
        //setTimeout(function(){
          //$('#myModal').modal('hide');
          //$(document).find('.modal-backdrop').remove();
        //}, 2000);
             
      }
    });

    $('.modalshow').click(function(){
      $('.modal-title').show();
      $('.modal-footer').show();
      $('.successmsg').toggle();
      $('.message_text').val('');
      $('.useremail').val('');
    });


</script>

