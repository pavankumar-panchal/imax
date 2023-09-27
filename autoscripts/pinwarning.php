<?
include("../functions/phpfunctions.php");
//total count of pins
$query1 = "select count(*) as totalpins from inv_mas_scratchcard";
$resultfetch1 = runmysqlqueryfetch($query1);
$totalpins = formatnumber($resultfetch1 ['totalpins']);

//total count of unused pins
$query = "select count(*) as unusedpins from inv_mas_scratchcard where attached = 'no' and blocked = 'no' and cancelled = 'no';";
$resultfetch = runmysqlqueryfetch($query);
$unusedpins = $resultfetch['unusedpins'];


//total count of Registered pins
$query = "select count(*) as registeredpins from inv_mas_scratchcard where attached = 'yes' and blocked = 'no' and cancelled = 'no' and registered = 'yes';";
$resultfetch = runmysqlqueryfetch($query);
$registeredpins = $resultfetch['registeredpins'];


//total count of Un-Registered pins
$query = "select count(*) as unregisteredpins from inv_mas_scratchcard where attached = 'yes' and blocked = 'no' and cancelled = 'no' and registered = 'no';";
$resultfetch = runmysqlqueryfetch($query);
$unregisteredpins = $resultfetch['unregisteredpins'];

//total count of blocked pins
$query2 = "select count(*) as blockedpins from inv_mas_scratchcard where  blocked = 'yes' ;";
$resultfetch2 = runmysqlqueryfetch($query2);
$blockedpins = $resultfetch2['blockedpins'];

//total count of cancelled pins
$query3 = "select count(*) as cancelledpins from inv_mas_scratchcard where  blocked = 'no' and cancelled = 'yes' ;";
$resultfetch3 = runmysqlqueryfetch($query3);
$cancelledpins = $resultfetch3['cancelledpins'];


//total count of used pins
$query4 = "select count(*) as usedpins from inv_mas_scratchcard where attached = 'yes' and blocked = 'no' and cancelled = 'no' and registered = 'yes';";
$resultfetch4 = runmysqlqueryfetch($query4);
$usedpins = $resultfetch4['usedpins'];

//fetch the last pinno
$query5= "select max(cardid) as cardid from inv_mas_scratchcard;";
$resultfetch5 = runmysqlqueryfetch($query5);
$cardid = $resultfetch5['cardid'];

$query6 =" select scratchnumber from inv_mas_scratchcard where cardid = '".$cardid."'";
$resultfetch6 = runmysqlqueryfetch($query6);
$scratchnumber = $resultfetch6['scratchnumber'];

$lastpin = 'Last PIN number: '.$scratchnumber.' (s/l no: '.$cardid.')';

$fromname = "Relyon";
$fromemail = "imax@relyon.co.in";
$toarray = array('Relyonimax' => 'relyonimax@gmail.com', 'Manjunath' => 'manjunath.sm@relyonsoft.com');

$toarray_critical = array('Relyonimax' => 'relyonimax@gmail.com', 'Manjunath' => 'manjunath.sm@relyonsoft.com','Nitin' => 'nitinall@relyonsoft.com');
//$toarray = array('rashmi.hk@relyonsoft.com');
$filearray = array(
				array('../images/relyon-logo.jpg','inline','1234567890')
			);
$array = array();
require_once("../inc/RSLMAIL_MAIL.php");
$grid = '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
$grid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap" width = "25%" align="center" ><strong>Total PINs</strong></td> <td nowrap="nowrap" width = "25%" align="center" >Registered</td> <td nowrap="nowrap"  align="center" width = "25%">Attached</td><td nowrap="nowrap"  align="center" width = "25%"><strong>Free/Available</strong></td><td nowrap="nowrap"  align="center" width = "25%">Blocked</td><td nowrap="nowrap"  align="center" width = "25%">Cancelled</td></tr>';

$array[] = "##REGISTERED##%^%".$registeredpins;
$array[] = "##UNREGISTERED##%^%".$unregisteredpins;
$array[] = "##TOTALUNUSED##%^%".$unusedpins;
$array[] = "##TOTALUSED##%^%".$usedpins;
$array[] = "##TOTALBLOCKED##%^%".$blockedpins;
$array[] = "##TOTALCANCELLED##%^%".$cancelledpins;
$array[] = "##LASTPIN##%^%".$lastpin;

if($unusedpins < 100)
{
	$subject = 'CRITICAL: PIN numbers are less than 100!!';

	$grid .= '<tr ><td align="center" nowrap="nowrap" >'.formatnumber($totalpins).'</td><td align="center" nowrap="nowrap" >'.formatnumber($registeredpins).'</td><td nowrap="nowrap"  align="center">'.formatnumber($unregisteredpins).'</td><td nowrap="nowrap"  align="center">'.formatnumber($unusedpins).'</td><td nowrap="nowrap"  align="center">'.formatnumber($blockedpins).'</td><td nowrap="nowrap"  align="center">'.formatnumber($cancelledpins).'</td></tr></table>';
	
	$msg = file_get_contents("../mailcontents/pinwarning.htm");
	$textmsg = file_get_contents("../mailcontents/pinwarning.txt");
	$array[] = "##DATE##%^%".$date;
	$array[] = "##GRID##%^%".$grid;
	$array[] = "##SUBJECT##%^%".$subject;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray_critical, $subject, $text, $html,null,null,$filearray); 
}
else if($unusedpins < 500)
{
	$subject = 'CRITICAL: PIN numbers are less than 500!!';

	$grid .= '<tr ><td align="center" nowrap="nowrap" >'.formatnumber($totalpins).'</td><td align="center" nowrap="nowrap" >'.formatnumber($registeredpins).'</td><td nowrap="nowrap"  align="center">'.formatnumber($unregisteredpins).'</td><td nowrap="nowrap"  align="center">'.formatnumber($unusedpins).'</td><td nowrap="nowrap"  align="center">'.formatnumber($blockedpins).'</td><td nowrap="nowrap"  align="center">'.formatnumber($cancelledpins).'</td></tr></table>';
	
	$msg = file_get_contents("../mailcontents/pinwarning.htm");
	$textmsg = file_get_contents("../mailcontents/pinwarning.txt");
	$array[] = "##DATE##%^%".$date;
	$array[] = "##GRID##%^%".$grid;
	$array[] = "##SUBJECT##%^%".$subject;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,null,$filearray); 
}
else if($unusedpins < 1000)
{
	$subject = 'WARNING: PIN numbers are less than 1000!!';
	$grid .= '<tr ><td align="center" nowrap="nowrap" >'.formatnumber($totalpins).'</td><td align="center" nowrap="nowrap" >'.formatnumber($registeredpins).'</td><td nowrap="nowrap"  align="center">'.formatnumber($unregisteredpins).'</td><td nowrap="nowrap"  align="center">'.formatnumber($unusedpins).'</td><td nowrap="nowrap"  align="center">'.formatnumber($blockedpins).'</td><td nowrap="nowrap"  align="center">'.formatnumber($cancelledpins).'</td></tr></table>';
	
	$msg = file_get_contents("../mailcontents/pinwarning.htm");
	$textmsg = file_get_contents("../mailcontents/pinwarning.txt");
	$array[] = "##DATE##%^%".$date;
	$array[] = "##GRID##%^%".$grid;
	$array[] = "##SUBJECT##%^%".$subject;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,null,$filearray); 
}
else if($unusedpins < 2000)
{
	$subject = 'WARNING: PIN numbers are less than 2000!!';
	$grid .= '<tr ><td align="center" nowrap="nowrap" >'.formatnumber($totalpins).'</td><td align="center" nowrap="nowrap" >'.formatnumber($registeredpins).'</td><td nowrap="nowrap"  align="center">'.formatnumber($unregisteredpins).'</td><td nowrap="nowrap"  align="center">'.formatnumber($unusedpins).'</td><td nowrap="nowrap"  align="center">'.formatnumber($blockedpins).'</td><td nowrap="nowrap"  align="center">'.formatnumber($cancelledpins).'</td></tr></table>';
	
	$msg = file_get_contents("../mailcontents/pinwarning.htm");
	$textmsg = file_get_contents("../mailcontents/pinwarning.txt");
	$array[] = "##DATE##%^%".$date;
	$array[] = "##GRID##%^%".$grid;
	$array[] = "##SUBJECT##%^%".$subject;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,null,$filearray); 
}
else
{
	echo('Total Unused: '.$unusedpins);
}


?>