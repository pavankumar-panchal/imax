<?php
error_reporting(E_ALL);
require_once("../inc/RSLMAIL_MAIL.php");
echo time() ."<br>";
$fromname="Relyon";
$fromemail="imax@relyon.co.in";
$toarray=array('Manjunath' => 'mattikoppa@yahoo.com','Manjunath sm' => 'manjunath.sm@relyonsoft.com');
$subject="test mail";
$text="test mail from imax";
$html="test mail from imax"; 

echo rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,null); 

echo "<br>end of test";
?>