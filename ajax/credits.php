<?php
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');

if(imaxgetcookie('userid')<> '') 
$userid = imaxgetcookie('userid');
else
{ 
	echo(json_encode('Thinking to redirect'));
	exit;
}

include('../inc/checksession.php');
include('../inc/checkpermission.php');
$type = $_POST['type'];
$lastslno = $_POST['lastslno'];
switch($type)
{
	case 'save':
	{
		$responsearray = array();
		$credittype = $_POST['credittype'];
		$creditamount = $_POST['creditamount'];
		$dealerid = $_POST['dealerid'];
		$createddate = datetimelocal('d-m-Y')." ".datetimelocal('H:i');
		$remarks = $_POST['remarks'];
		$date = substr($createddate,0,10);
		if($lastslno == '')
		{
			$query = "INSERT INTO inv_credits(dealerid,credittype,creditamount,createddate,remarks,createdby,lastmodifieddate,lastmodifiedby,createdip,lastmodifiedip) VALUES('".$dealerid."','".$credittype."','".$creditamount."','".changedateformatwithtime($createddate)."','".$remarks."','".$userid."','".changedateformatwithtime($createddate)."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".$_SERVER['REMOTE_ADDR']."');";
			$result = runmysqlquery($query);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','32','".date('Y-m-d').' '.date('H:i:s')."','".$dealerid."')";
			$eventresult = runmysqlquery($eventquery);

			$query1 = "select sum(netamount) as totalpurchaseamount from inv_bill where dealerid = '".$dealerid."';";
			$fetch1 = runmysqlqueryfetch($query1);
			$totalpurchaseamount = $fetch1['totalpurchaseamount'];
			$query2 = "SELECT MAX(slno) as creditid, SUM(creditamount) as totalcreditamount FROM inv_credits WHERE dealerid = '".$dealerid."'";
			$fetch = runmysqlqueryfetch($query2);
			$creditid = $fetch['creditid'];
			$totalcreditamount = $fetch['totalcreditamount'];
			$totalcredit = $totalcreditamount - $totalpurchaseamount;
			$query3 = "SELECT businessname as businessname,place as place,contactperson as contactperson,emailid FROM inv_mas_dealer WHERE slno = '".$dealerid."'";
			$fetch3 = runmysqlqueryfetch($query3);
			$contactperson = $fetch3['contactperson'];
			$businessname = $fetch3['businessname'];
			$place = $fetch3['place'];
			$emailid = $fetch3['emailid'];
			#########  Mailing Starts -----------------------------------
			
			if(($_SERVER['HTTP_HOST'] == "localhost") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
			{
				$emailid = 'bhumika.p@relyonsoft.com';
			}
			else
			{
				$emailid = $emailid;
			}
			$emailarray = explode(',',$emailid);
			$emailcount = count($emailarray);
			
			for($i = 0; $i < $emailcount; $i++)
			{
				if(checkemailaddress($emailarray[$i]))
				{
						$emailids[$contactperson] = $emailarray[$i];
				}
			}
			$fromname = "Relyon";
			$fromemail = "imax@relyon.co.in";
			$subject = "Testing";
			//require_once("../inc/RSLMAIL_MAIL.php");
			//$msg = file_get_contents("../mailcontents/newcredit.htm");
			//$textmsg = file_get_contents("../mailcontents/newcredit.txt");
			$array = array();
			$array[] = "##DATE##%^%".$date;
			$array[] = "##NAME##%^%".$contactperson;
			$array[] = "##COMPANY##%^%".$businessname;
			$array[] = "##PLACE##%^%".$place;
			$array[] = "##AMOUNT##%^%".$creditamount;
			$array[] = "##TOTALCREDIT##%^%".$totalcredit;
			$array[] = "##CREDITID##%^%".$creditid;
			$array[] = "##PRODUCTNAME##%^%".$productname;
			$array[] = "##EMAILID##%^%".$emailid;
			$filearray = array(
				array('../images/relyon-logo.jpg','inline','8888888888'),
			);
			$toarray = $emailids;
			if(($_SERVER['HTTP_HOST'] == "localhost") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
			{
				$bccemailids['bhumika'] ='bhumika.p@relyonsoft.com';
			}
			else
			{
				$bccemailids['relyonimax'] ='relyonimax@gmail.com';
				$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
			}

			$bccarray = $bccemailids;
			$msg = "test";
			$textmsg = "test";
			$subject = 'Credit enhanced for "'.$businessname.'" on '.$date;
			$html = $msg;
			$text = $textmsg;
			$replyto = 'bhumika.p@relyonsoft.com';
			//rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray,$replyto); 
			
			//Insert the mail forwarded details to the logs table
			$bccmailid = 'bhumika.p@relyonsoft.com'; 
			inserttologs(imaxgetcookie('userid'),$dealerid,$fromname,$fromemail,$emailid,null,$bccmailid,$subject);
			#########  Mailing Ends ----------------------------------------
		}
		else
		{
			$query = "UPDATE inv_credits SET dealerid = '".$dealerid."',credittype = '".$credittype."',creditamount = '".$creditamount."',remarks = '".$remarks."',
lastmodifieddate ='".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedby ='".$userid."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."' WHERE slno = '".$lastslno."'";
			$result = runmysqlquery($query);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','33','".date('Y-m-d').' '.date('H:i:s')."','".$dealerid."')";
			$eventresult = runmysqlquery($eventquery);
		}
		//echo($query);
		$responsearray['errormessage'] = "1^ Credits Saved Successfully.";
		//echo("1^ Credits Saved Successfully.");
		echo(json_encode($responsearray));
	}
	break;
	
	case 'delete':
	{
		$responsearray = array();
		$query = "DELETE FROM inv_credits WHERE slno = '".$lastslno."'";
		$result = runmysqlquery($query);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','34','".date('Y-m-d').' '.date('H:i:s')."','".$dealerid."')";
		$eventresult = runmysqlquery($eventquery);
		$responsearray['errormessage'] = "2^ Record Deleted Successfully.";
		//echo("2^ Record Deleted Successfully.");
		echo(json_encode($responsearray));
	}
	break;
	
	case 'generatedealerlist':
	{
		$generatedealerlistarray = array();
		$relyonexcecutive_type = $_POST['relyonexcecutive_type'];
		$login_type = $_POST['login_type'];
		$dealerregion = $_POST['dealerregion'];
		$relyonexcecutive_typepiece = ($relyonexcecutive_type == "")?(""):(" AND inv_mas_dealer.relyonexecutive = '".$relyonexcecutive_type."' ");
		$login_typepiece = ($login_type == "")?(""):(" AND inv_mas_dealer.disablelogin = '".$login_type."' ");
		$dealerregionpiece = ($dealerregion == "")?(""):(" AND inv_mas_dealer.region = '".$dealerregion."' ");
		$query = "SELECT slno,businessname FROM inv_mas_dealer where slno <> '532568855' ".$relyonexcecutive_typepiece.$login_typepiece.$dealerregionpiece." ORDER BY businessname";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$generatedealerlistarray[$count] = $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
		echo(json_encode($generatedealerlistarray));
	}
	break;
	
	case 'searchbyrecordno':
	{
		$searchbyrecordnoarray = array();
		$recordno = $_POST['recordno'];
		$fetch = runmysqlqueryfetch("SELECT COUNT(*) AS count FROM inv_credits WHERE slno = '".$recordno."'");
		if($fetch['count'] > 0)
		{
			$query = "SELECT inv_credits.slno,inv_credits.dealerid,inv_credits.credittype,inv_credits.creditamount,inv_credits.createddate,inv_credits.remarks,inv_mas_dealer.businessname FROM inv_credits LEFT JOIN inv_mas_dealer ON inv_mas_dealer.slno = inv_credits.dealerid WHERE inv_credits.slno = '".$recordno."';";
			$fetchresult = runmysqlqueryfetch($query);
			
			$searchbyrecordnoarray['errorcode'] = '1';
			$searchbyrecordnoarray['slno'] = $fetchresult['slno'];
			$searchbyrecordnoarray['dealerid'] = $fetchresult['dealerid'];
			$searchbyrecordnoarray['credittype'] = $fetchresult['credittype'];
			$searchbyrecordnoarray['creditamount'] = $fetchresult['creditamount'];
			$searchbyrecordnoarray['createddate'] = changedateformatwithtime($fetchresult['createddate']);
			$searchbyrecordnoarray['remarks'] = $fetchresult['remarks'];
			$searchbyrecordnoarray['businessname'] = $fetchresult['businessname'];

			//echo('1^'.$fetchresult['slno'].'^'.$fetchresult['dealerid'].'^'.$fetchresult['credittype'].'^'.$fetchresult['creditamount'].'^'.changedateformatwithtime($fetchresult['createddate']).'^'.$fetchresult['remarks'].'^'.$fetchresult['businessname']);
		}
		else
		{
			$searchbyrecordnoarray['errorcode'] = '2';
			$searchbyrecordnoarray['slno'] = '';
			$searchbyrecordnoarray['dealerid'] = '';
			$searchbyrecordnoarray['credittype'] = '';
			$searchbyrecordnoarray['creditamount'] = '';
			$searchbyrecordnoarray['createddate'] = '';
			$searchbyrecordnoarray['remarks'] = '';
			$searchbyrecordnoarray['businessname'] = '';
			//echo('2^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
		}
		echo(json_encode($searchbyrecordnoarray));
	}
	break;
	
	case 'getdealername':
	{
		$getdealernamearray = array();
		$dealerid = $_POST['dealerid'];
		$query = "SELECT businessname FROM inv_mas_dealer WHERE slno = '".$dealerid."'";
		$fetch = runmysqlqueryfetch($query);
		//echo('1^'.$fetch['businessname'].'^');
		
		$query0 = "SELECT sum(creditamount) as totalcredit FROM inv_credits WHERE dealerid = '".$dealerid."'";
		$resultfetch = runmysqlqueryfetch($query0);
		$totalcredit = $resultfetch['totalcredit'];
		$query1 = "SELECT sum(netamount) as billedamount FROM inv_bill WHERE dealerid = '".$dealerid."' AND billstatus ='successful'";
		$resultfetch1 = runmysqlqueryfetch($query1);
		$billedamount =$resultfetch1['billedamount'];
		$totalcredit = $totalcredit - $billedamount;
		$getdealernamearray['errorcode'] = '1';
		$getdealernamearray['businessname'] = $fetch['businessname'];
		$getdealernamearray['totalcredit'] = $totalcredit;
		echo(json_encode($getdealernamearray));
		//echo($totalcredit);
	}
	break;
	
	case 'generategrid':
	{
		$dealerid = $_POST['dealerid'];
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];	
		$showtype = $_POST['showtype'];
		$resultcount = "SELECT inv_credits.slno,inv_credits.dealerid,inv_credits.credittype,inv_credits.creditamount,inv_credits.createddate,inv_credits.remarks, inv_mas_users.fullname,inv_credits.lastmodifieddate FROM inv_credits LEFT JOIN inv_mas_users ON inv_credits.createdby = inv_mas_users.slno WHERE dealerid = '".$dealerid."' order by createddate ASC ";
		$fetch10 = runmysqlquery($resultcount);
		$fetchresultcount = mysqli_num_rows($fetch10);
		if($showtype == 'all')
		$limit = 100000;
		else
		$limit = 10;
		if($startlimit == '')
		{
			$startlimit = 0;
			$slno = 0;
		}
		else
		{
			$startlimit = $slno;
			$slno = $slno;
		}
		
		$query = "SELECT inv_credits.slno,inv_credits.dealerid,inv_credits.credittype,inv_credits.creditamount,inv_credits.createddate,inv_credits.remarks, inv_mas_users.fullname,inv_credits.lastmodifieddate FROM inv_credits LEFT JOIN inv_mas_users ON inv_credits.createdby = inv_mas_users.slno WHERE dealerid = '".$dealerid."' order by createddate DESC LIMIT ".$startlimit.",".$limit.";";
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header" align ="left"><td nowrap = "nowrap" class="td-border-grid" >Sl No</td><td nowrap = "nowrap" class="td-border-grid">Dealer ID</td><td nowrap = "nowrap" class="td-border-grid">Credit Type</td><td nowrap = "nowrap" class="td-border-grid">Credit Amount</td><td nowrap = "nowrap" class="td-border-grid">Credit Date</td><td nowrap = "nowrap" class="td-border-grid">Remarks</td><td nowrap = "nowrap" class="td-border-grid">Entered By</td><td nowrap = "nowrap" class="td-border-grid">Last Modified Date</td></tr>';
		}
		$i_n = 0;
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_row($result))
		{
			$i_n++;
			$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow" bgcolor='.$color.' onclick="gridtoform(\''.$fetch[0].'\'); " align ="left">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$slno."</td>";
			for($i = 1; $i < count($fetch); $i++)
			{
				if($i == 4)
				$grid .= "<td nowrap='nowrap' class='td-border-grid'>".changedateformatwithtime($fetch[$i])."</td>";
				else
				if($i == 7)
				$grid .= "<td nowrap='nowrap' class='td-border-grid'>".changedateformatwithtime($fetch[$i])."</td>";
				else
				$grid .= "<td nowrap='nowrap' class='td-border-grid'>".gridtrim($fetch[$i])."</td>";
			}
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		if($slno >= $fetchresultcount)
		
		$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
		$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="getmoredatagrid(\''.$startlimit.'\',\''.$slno.'\',\'more\'); " style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmoredatagrid(\''.$startlimit.'\',\''.$slno.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
		
		echo '1^'.$grid.'^'.$linkgrid.'^'.$fetchresultcount;
	}
	break;
		
	
	case 'gridtoform':
	{
		$gridtoformarray = array();
		$query = "SELECT inv_credits.slno,inv_credits.dealerid,inv_credits.credittype,inv_credits.creditamount,inv_credits.createddate,inv_credits.remarks,inv_mas_dealer.businessname, inv_mas_users.fullname FROM inv_credits LEFT JOIN inv_mas_users ON inv_credits.createdby = inv_mas_users.slno LEFT JOIN inv_mas_dealer ON inv_credits.dealerid = inv_mas_dealer.slno WHERE inv_credits.slno = '".$lastslno."'";
		$fetch = runmysqlqueryfetch($query);
		$gridtoformarray['errorcode'] = '1';
		$gridtoformarray['slno'] = $fetch['slno'];
		$gridtoformarray['dealerid'] = $fetch['dealerid'];
		$gridtoformarray['credittype'] = $fetch['credittype'];
		$gridtoformarray['creditamount'] = $fetch['creditamount'];
		$gridtoformarray['createddate'] = changedateformatwithtime($fetch['createddate']);
		$gridtoformarray['remarks'] = $fetch['remarks'];
		$gridtoformarray['businessname'] = $fetch['businessname'];
		$gridtoformarray['fullname'] = $fetch['fullname'];
		$gridtoformarray['createdby'] = $createdby;
		echo(json_encode($gridtoformarray));
		//echo('1^'.$fetch['slno'].'^'.$fetch['dealerid'].'^'.$fetch['credittype'].'^'.$fetch['creditamount'].'^'.changedateformatwithtime($fetch['createddate']).'^'.$fetch['remarks'].'^'.$fetch['businessname'].'^'.$fetch['fullname'].'^'.$createdby);
	}
	break;
}


?>
