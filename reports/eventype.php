<?php
include('../functions/phpfunctions.php');
$username = $_POST['username'];
$userid = imaxgetcookie('userid');

$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','258','".date('Y-m-d').' '.date('H:i:s')."','excel_transferredpins_report".'-'.strtolower($username)."')";
$eventresult = runmysqlquery($eventquery);

?>
