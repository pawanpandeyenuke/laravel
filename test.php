<?php
die('test');
$userfrom = 'one307';
$userto = 'two308';
$msg = 'This is development testing on broadcast functionality.';
$node = 'friendzsquare.com';

$result = @exec("sudo ejabberdctl send_message_chat ".$userfrom."@".$node." ".$userto."@".$node." '".$msg."'");


?>
