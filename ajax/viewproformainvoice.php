<?php 
	
include('../functions/phpfunctions.php');
$slno=$_GET['fdp'];
$slno=decodevalue($slno);       
vieworgeneratepdfperforminvoice($slno,'view');

?>