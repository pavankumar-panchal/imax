<?php
include("../inc/checklogin.php"); 
include("../inc/userallfields.php"); 

$success = "";
if($_POST['spponline'] <> "" || $_POST['sppdesktop'] <> "")
{
	$requestdate = datetimelocal("Y-m-d"); 
	$requesttime = datetimelocal("H:i");
	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	$toarray = array($name => $emailid);
	$bccarray = array('Bigmail' => 'bigmail@relyonsoft.com', 'Pradeep N' => 'pradeep.n@relyonsoft.com');
	if($_POST['spponline'] <> "")
	{
		$product = "Saral PayPack - with Online Module";
		require_once("../functions/RSLMAIL_MAIL.php");
		$msg = file_get_contents("../inc/mail-quote-spponline.htm");
		$array = array(
			"##DATE##" => changedateformat($requestdate),
			"##NAME##" => $name,
			"##COMPANY##" => $company,
			"##PLACE##" => $place,
			"##DISTRICT##" => $district,
			"##STATE##" => $state,
			"##PHONE##" => $phone,
			"##ID##" => $userid,
			"##PRODINITIAL##" => "SPPON",
			"##EMAILID##" => $emailid
		);
		$filearray = array(
			array('../images/userlogin-relyon-logo.jpg','inline','1234567890'),
			array('../inc/SPP_with_Online_Profile.pdf','attachment','1234567891')
		);
		$msg = replacemailvariable($msg,$array);
		$subject = "Quotation of ".$product." for ".$company;
		$text = "This is a HTML format email. Please enable HTML viewing in your email client.";
		$html = $msg;
	}
	elseif($_POST['sppdesktop'] <> "")
	{
		$product = "Saral PayPack - Offline Package";
		require_once("../functions/RSLMAIL_MAIL.php");
		$msg = file_get_contents("../inc/mail-quote-spp.htm");
		$array = array(
			"##DATE##" => changedateformat($requestdate),
			"##NAME##" => $name,
			"##COMPANY##" => $company,
			"##PLACE##" => $place,
			"##DISTRICT##" => $district,
			"##STATE##" => $state,
			"##PHONE##" => $phone,
			"##ID##" => $userid,
			"##PRODINITIAL##" => "SPPOFF",
			"##EMAILID##" => $emailid
		);
		$filearray = array(
			array('../images/userlogin-relyon-logo.jpg','inline','1234567890'),
			array('../inc/Product-Profile_and_AMC-Updation_Terms.pdf','attachment','1234567891')
		);
		$msg = replacemailvariable($msg,$array);
		$subject = "Quotation of ".$product." for ".$company;
		$text = "This is a HTML format email. Please enable HTML viewing in your email client.";
		$html = $msg;
	}
	$query = "insert into `getquote` (userid, emailid, productname, requestdate, requesttime)values('".$userid."', '".$emailid."', '".$product."', '".$requestdate."', '".$requesttime."')";
	$result = runmysqlquery($query);
	if(rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray))
		$success = "<div style='background-color:#FFCC00; padding:2px; color:#000000'>Quote has been successfully sent to <strong>".$emailid."</strong>.Please check your Inbox for the Quotation of <strong>".$product."<strong>.</div>";
	else
		$success = "We are unable to process the request. Please try after few minutes.";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META HTTP-EQUIV="Content-type" content="UTF-8">
<title>Get Quote for Relyon Products</title>
<meta name="keywords" content="Register with Relyon for free downloads, newsletters and many more..">
<meta name="description" content="Relyon user login pages.">
<link href="../css/style.css" rel="stylesheet" type="text/css">
<meta name="copyright" content="Relyon Softech Ltd. All rights reserved." />
<link rel="shortcut icon" type="image/x-icon" href="../images/favicon.ico" />
<script src="../functions/jsfunctions.js?dummy = <? echo (rand());?>" language="javascript"></script>
</head>
<body>
<table width="771" border="0" cellpadding="0" cellspacing="0" align="center">
<tr valign="top"><td><? include("../inc/header2.php"); ?></td>
</tr>
<tr valign="top">
<td><div>&nbsp;</div>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td colspan="3" style="padding-right:10px"><div align="right"><a href="../logout.php">Logout</a></div></td>
</tr>
<tr>
<td width="550" rowspan="3" valign="top" class="pagebody"><table width="100%" border="0" cellpadding="2" cellspacing="0">
  <tr>
    <td width="100%" class="pagebodyheading"><strong>Get Quote for Relyon Products</strong></td>
  </tr>
  <tr>
    <td valign="top"><table width="100%" border="0" cellpadding="2" cellspacing="0">
      <tr>
        <td><div align="center" style="color:#FF0000"> 
          <? echo($success); ?></div></td>
      </tr>
      <tr>
        <td><div align="justify">Below products are available for quotes. Once you request a quote, a quotation will be processed and reaching you in a few miutes via email.</div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><table width="99%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td width="40%" class="getquotebox"><form action="" method="post" name="sppgetquote" id="sppgetquote"><div align="center">
              <p><span style="font-size:16px; color:#006699; font-weight:bold">Saral PayPack </span><br />
                Desktop Edition</p>
              <p><input name="sppdesktop" type="submit" style="cursor:hand" value="Get Quote" alt="Click here to Place the request" /></p>
            </div></form></td>
            <td width="20%">&nbsp;</td>
            <td width="40%" class="getquotebox"><form action="" method="post" name="sppgetquote1" id="sppgetquote1"><div align="center">
              <p><span style="font-size:16px; color:#FF6600; font-weight:bold">Saral PayPack </span><br />
                With Online Extension</p>
              <p>
                <input name="spponline" type="submit" style="cursor:hand" value="Get Quote" alt="Click here to Place the request" <? echo($tobedisabled);?>/>
              </p>
            </div></form></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="77%"></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr class="content">
    <td><div align="justify"><strong>Note: </strong><br />
    This demo request will be placed to Relyon and will be desiganted to nearest Relyon representative in a short while. The visiting person for demonstration will contact you and fix an appointment before coming.</div></td>
  </tr>
  
</table></td>
<td rowspan="3" class="columnBorder">&nbsp;</td>
<td width="218" height="20" valign="top" style="padding:5px; border-left: #3f7c5f solid 1px; border-right: #3f7c5f solid 1px; border-top: #3f7c5f solid 1px; border-bottom: #3f7c5f solid 2px"><? include("../inc/navigation.php"); ?></td>
</tr>
<tr>
  <td valign="top" style="PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; height: 2px; PADDING-TOP: 0px"></td>
  </tr>
<tr>
  <td width="218" valign="top" style="padding:5px; border-left: #3f7c5f solid 1px; border-right: #3f7c5f solid 1px; border-top: #3f7c5f solid 1px; border-bottom: #3f7c5f solid 2px"><? include("../inc/profilecard.php"); ?></td>
  </tr>
</table>	
<div>&nbsp;</div><BR>
</td>
</tr>
<tr valign="top"><td><? include("../inc/footer.php"); ?></td>
</tr>
</table>
</body>
</html>
