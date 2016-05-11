<?php


 $userfrom = 'one307';
 $userto = 'two308';
 $msg = 'This is development testing on broadcast functionality.';

 $node = 'friendzsquare.com';
 //$result = @exec("sudo ejabberdctl send_message_chat ".$userfrom."@".$node." ".$userto."@".$node." '".$msg."'");

 $result2 = @exec("sudo ejabberdctl send_message chat one307@friendzsquare.com two308@friendzsquare.com chat_subject message");
 echo $result2; 


exit;
///@exec('sudo ejabberdctl register harik friendzsquare.com test123 2>&1', $output, $status);
//echo "<pre>"; print_r($output);
?>
