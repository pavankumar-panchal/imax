<?php
include('./functions/phpfunctions.php');
if (imaxgetcookie('userid') <> '' || imaxgetcookie('userid') <> false) {
	$userid = imaxgetcookie('userid');
	$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('" . $userid . "','" . $_SERVER['REMOTE_ADDR'] . "','111','" . date('Y-m-d') . ' ' . date('H:i:s') . "')";
	$eventresult = runmysqlquery($eventquery);
}
imaxuserlogout();
header('Location:./index.php');
?>