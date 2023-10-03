<?php
//ini_set('display_errors', 1);
//echo "test";
#########  Mailing Starts -----------------------------------

$fromname = "Relyon";
$fromemail = "saralgst@relyon.co.in";
require_once("RSLMAIL_MAIL_test.php");
//$msg = 'Your Email OTP :'." ".$email_OTP;
//$textmsg = 'Your Email OTP :'." " .$email_OTP;

//$msg = file_get_contents("../mailcontents/emailverification.htm");
//$textmsg = file_get_contents("../mailcontents/emailverification.txt");

$textmsg = "test";
$emailid = "bhumika.p@relyonsoft.com";

$array = array();
$array[] = "##EMAILOTP##%^%" . "000";
$array[] = "##EMAILID##%^%" . $emailid;


//Relyon Logo for email Content, as Inline [Not attachment]


$emailarray = explode(',', $emailid);
$emailcount = count($emailarray);

//Convert email IDs to an array. First email ID will eb assigned with Contact person name. Others will be assigned with email IDs itself as Name.
for ($i = 0; $i < $emailcount; $i++) {
	if (checkemailaddress($emailarray[$i])) {
		$emailids[$emailarray[$i]] = $emailarray[$i];
	}
}


//Mail to customer
$toarray = $emailids;

//$bccarray = array('bhumika' => 'bhumika.p@relyonsoft.com');

//$msg = replacemailvariable($msg,$array);
$textmsg = replacemailvariable($textmsg, $array);
$subject = "Email Verification";
$html = "hi";
$text = $textmsg;
$replyto = "bhumika.p@relyonsoft.com";
rslmail($fromname, $fromemail, $toarray, $subject, $text, $html, null, null, null, $replyto);



function checkemailaddress($email)
{
	// First, we check that there's one @ symbol, and that the lengths are right
	if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
		// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
		return false;
	}
	// Split it into sections to make life easier
	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);
	for ($i = 0; $i < sizeof($local_array); $i++) {
		if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
			return false;
		}
	}
	if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
		// Check if domain is IP. If not, it should be valid domain name
		$domain_array = explode(".", $email_array[1]);
		if (sizeof($domain_array) < 2) {
			return false; // Not enough parts to domain
		}
		for ($i = 0; $i < sizeof($domain_array); $i++) {
			if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
				return false;
			}
		}
	}
	return true;
}

function replacemailvariable($content, $array)
{
	$arraylength = count($array);
	for ($i = 0; $i < $arraylength; $i++) {
		$splitvalue = explode('%^%', $array[$i]);
		$oldvalue = $splitvalue[0];
		$newvalue = $splitvalue[1];
		$content = str_replace($oldvalue, $newvalue, $content);
	}
	return $content;
}
?>