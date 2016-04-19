<?php
$command = isset($_GET['command'])?$_GET['command']:'';
$param1 = isset($_GET['arg1'])?$_GET['arg1']:'';
$param2 = isset($_GET['arg2'])?$_GET['arg2']:'';
$param3 = isset($_GET['arg3'])?$_GET['arg3']:'';
$help = isset($_GET['help'])?$_GET['help']:'';

if(empty($command)){
	echo "<br><h2>Please pass a command for execution.</h2>";
} else {
	$output = $status = 0;
	if(empty($help)){
		@exec("sudo ejabberdctl $command $param1 $param2 $param3 2>&1", $output, $status);
	} else {
		@exec("sudo ejabberdctl help $command 2>&1", $output, $status);	
	}
	echo "<br>OUTPUT=>> <br><pre>------"; print_r($output);
	echo "<br>STATUS=>> <br><pre>------"; print_r($status); 
}
?>
