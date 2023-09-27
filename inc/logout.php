<?
	$date = datetimelocal('Y-m-d');
	$time = datetimelocal('H:i:s');

	session_start(); session_destroy(); 
	setcookie('userid',''); 
	header('Location:./index.php');
?>