<?php
//echo "okokokokokok"; exit;
@exec('sudo ejabberdctl register harik friendzsquare.com test123 2>&1', $output, $status);
echo "<pre>"; print_r($output);
?>
