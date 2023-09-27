<?
ini_set("display_errors",0);
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
$cardid = $_POST['cardid'];
$switchtype = $_POST['switchtype'];

switch($switchtype)
{
	case 'attachcard':
	{
		$attacheddate = datetimelocal('Y-m-d').' '.datetimelocal('H:i:s');
		$customerreference = $_POST['customerreference'];
		$remarks = $_POST['remarks'];
		$query = "Update inv_dealercard set customerreference = '".$customerreference."' ,cuscardattacheddate = '".$attacheddate."' ,cuscardremarks = '".$remarks."' ,cuscardattachedby = '".$userid."', usertype = 'user' where cardid = '".$cardid."' ";
		$result = runmysqlquery($query);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','52','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
		$eventresult = runmysqlquery($eventquery);
		sendfreeupdationcardemail($customerreference,$cardid);
		//echo($query5);
		echo(json_encode('1^'.'Card Attached Successfully'));
	
	}
	break;

	case 'detachcard':
	{
		$remarks = $_POST['remarks'];
		## Start Of Editing By Bhavesh ##
		/*$query5 = "Select * from inv_invoicenumbers_dummy_regv2 where cardid ='".$cardid."'";
		$fetch5 = runmysqlqueryfetch($query5);
		$slno = $fetch5 ['slno'];
		$CRDID = $fetch5 ['cardid'];
		if($cardid == $CRDID)
		{*/
			$query4 = "Delete from inv_invoicenumbers_dummy_regv2 where cardid = '".$cardid."'";
			$result4 = runmysqlquery($query4);
		/*}*/
		## End Of Editing By Bhavesh ##
		
		$query = "Update inv_dealercard set customerreference = '',cuscardremarks = '".$remarks."' where cardid = '".$cardid."' ";
		$result = runmysqlquery($query);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','53','".date('Y-m-d').' '.date('H:i:s')."')";
		$eventresult = runmysqlquery($eventquery);
		echo(json_encode('2^'.'Card Detached Successfully.'));
	}
	break;
	
	case 'generatecustomerlist':
	{
		$limit = $_POST['limit'];
		$startindex = $_POST['startindex'];
		$generatecustomerlistarray = array();
		$query = "SELECT slno,businessname,customerid FROM inv_mas_customer ORDER BY businessname LIMIT ".$startindex.",".$limit.";";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$generatecustomerlistarray[$count] = $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
		echo(json_encode($generatecustomerlistarray));
	}
	break;
	case 'getcustomercount':
	{
		$responsearray3 = array();
		$query = "SELECT slno,businessname,customerid FROM inv_mas_customer ORDER BY businessname";
		$result = runmysqlquery($query);
		$count = mysqli_num_rows($result);
		$responsearray3['count'] = $count;
		echo(json_encode($responsearray3));
	}
	break;
	case 'generategrid':
	{
		$lastslno = $_POST['lastslno'];
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$resultcount = "select inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_dealer.businessname,inv_mas_product.productname,inv_dealercard.purchasetype,inv_dealercard.usagetype,inv_mas_scheme.schemename,inv_dealercard.cuscardattacheddate,inv_mas_users.fullname,inv_dealercard.cuscardremarks as remarks from inv_dealercard left join inv_mas_scratchcard on inv_dealercard.cardid =inv_mas_scratchcard.cardid left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme left join inv_mas_users on inv_mas_users.slno = inv_dealercard.cuscardattachedby where customerreference ='".$lastslno."' order by inv_dealercard.cuscardattacheddate;";
		$fetch10 = runmysqlquery($resultcount);
		$fetchresultcount = mysqli_num_rows($fetch10);
		if($showtype == 'all')
		$limit = 100000;
		else
		$limit = 10;
		if($startlimit == '')
		{
			$startlimit = 0;
			$slnocount = 0;
		}
		else
		{
			$startlimit = $slnocount ;
			$slnocount = $slnocount;
		}
		$query = "select inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_dealer.businessname,inv_mas_product.productname,inv_dealercard.purchasetype,inv_dealercard.usagetype,inv_mas_scheme.schemename,inv_dealercard.cuscardattacheddate,inv_mas_users.fullname,inv_dealercard.cuscardremarks as remarks from inv_dealercard left join inv_mas_scratchcard on inv_dealercard.cardid =inv_mas_scratchcard.cardid left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme left join inv_mas_users on inv_mas_users.slno = inv_dealercard.cuscardattachedby where customerreference ='".$lastslno."' order by inv_dealercard.cuscardattacheddate LIMIT ".$startlimit.",".$limit.";";
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header" align="left"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">PIN Serial Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">PIN Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Dealer</td><td nowrap = "nowrap" class="td-border-grid" align="left">product</td><td nowrap = "nowrap" class="td-border-grid" align="left">Usage Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Scheme</td><td nowrap = "nowrap" class="td-border-grid" align="left">Attached Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Attached By</td><td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td></tr>';
		}
		$i_n = 0;
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_row($result))
		{
			$i_n++;$slnocount++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow" bgcolor='.$color.' onclick="gridtoform(\''.$fetch[0].'\')" align="left">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
			for($i = 0; $i < count($fetch); $i++)
			{
				if($i == 8)
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch[$i])."</td>";
				else
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch[$i])."</td>";
				
			}
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		if($slnocount >= $fetchresultcount)
			
		$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
		$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="generatemordatagrid(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer">Show More Records >></a><a onclick ="generatemordatagrid(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
		echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid;	
	}
	break;
	
	case 'gridtoform':
	{
		$customerreference = $_POST['customerreference'];
		$amclastslno = $_POST['amclastslno'];
		$query1 = "SELECT count(*) as count from inv_customeramc where customerreference = '".$customerreference."'";
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)
		{
			$query = "SELECT inv_customeramc.slno,inv_customeramc.customerreference,inv_customeramc.productcode,inv_customeramc.startdate,inv_customeramc.enddate,inv_customeramc.remarks,inv_customeramc.createddate, inv_mas_users.fullname ,inv_customeramc.billno,inv_customeramc.billamount FROM inv_customeramc LEFT JOIN inv_mas_users On inv_customeramc.userid = inv_mas_users.slno WHERE inv_customeramc.customerreference = '".$customerreference."' AND inv_customeramc.slno = '".$amclastslno."';";
			$fetch = runmysqlqueryfetch($query);
			$startdate = $fetch['startdate']; 
			$enddate = $fetch['enddate']; 
			$todays_date = date("Y-m-d"); 
			$today = strtotime($todays_date); 
			$expiration_date1 = strtotime($startdate); 
			$expiration_date2 = strtotime($enddate); 
			if ($expiration_date1 > $today) { $msg = "Future"; } 
			elseif($expiration_date2 < $today) { $msg = "Expired"; }
			else { $msg = "Active"; }
			
			echo($fetch['slno'].'^'.$fetch['customerreference'].'^'.$fetch['productcode'].'^'.changedateformat($fetch['startdate']).'^'.changedateformat($fetch['enddate']).'^'.$fetch['remarks'].'^'.changedateformat($fetch['createddate']).'^'.$fetch['fullname'].'^'.$msg.'^'.$userid.'^'.$fetch['billno'].'^'.$fetch['billamount']);
		}
		else
		{
			echo(''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
		}
	}
	break;
	
	case 'displaycustomerlist':
	{
		$customerreference = $_POST['customerreference'];
		$query = "SELECT businessname from inv_mas_customer where slno = '".$customerreference."';";
		$fetch = runmysqlqueryfetch($query);
		echo($fetch['businessname'].'^'.$customerreference);
	}
	break;
	
	case 'getcardlist':
	{
		## To Enable New Products for Autoregistration and product code like inv_dealercard.productcode != "XXX" #
		/*$query = "SELECT inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber 
		FROM inv_mas_scratchcard left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
		where inv_mas_scratchcard.attached = 'yes' and inv_mas_scratchcard.registered = 'no' and 
		(inv_dealercard.customerreference = '' or inv_dealercard.customerreference is null) and 
		inv_mas_scratchcard.blocked = 'no' and 
		(inv_dealercard.productcode!=353 and inv_dealercard.productcode!=308 and inv_dealercard.productcode!=371 and 
		inv_dealercard.productcode!=216 and inv_dealercard.productcode!=215 and inv_dealercard.productcode!=217 and 
		inv_dealercard.productcode!=515 and inv_dealercard.productcode!=242 and inv_dealercard.productcode!=243 and 
		inv_dealercard.productcode!=881 and inv_dealercard.productcode!=690 and inv_dealercard.productcode!=885
		and inv_dealercard.productcode!=886  and inv_dealercard.productcode!=888 and inv_dealercard.productcode!=887 and inv_dealercard.productcode!=643 and inv_dealercard.productcode!=658 and inv_dealercard.productcode!=659 and inv_dealercard.productcode!=882 and inv_dealercard.productcode!=883 and inv_dealercard.productcode!=884 and inv_dealercard.productcode!=214 and inv_dealercard.productcode!=309 and inv_dealercard.productcode!=001 and inv_dealercard.productcode!=372 and inv_dealercard.productcode!=354 and inv_dealercard.productcode!=660 and inv_dealercard.productcode!=661 and inv_dealercard.productcode!=644 and inv_dealercard.productcode!=484 and inv_dealercard.productcode!=485 and inv_dealercard.productcode!=483 and inv_dealercard.productcode!=482 and inv_dealercard.productcode!=516 and inv_dealercard.productcode!=481 and inv_dealercard.productcode!=244 and inv_dealercard.productcode!=245 and inv_dealercard.productcode!=818 and inv_dealercard.productcode!=691 and inv_dealercard.productcode!=219 and inv_dealercard.productcode!=220 and inv_dealercard.productcode!=221 and inv_dealercard.productcode!=218 and inv_dealercard.productcode!=222 and inv_dealercard.productcode!=223 and inv_dealercard.productcode!=224 and inv_dealercard.productcode!=889 and inv_dealercard.productcode!=890 and inv_dealercard.productcode!=891 and inv_dealercard.productcode!=892 and inv_dealercard.productcode!=662 and inv_dealercard.productcode!=664 and inv_dealercard.productcode!=667 and inv_dealercard.productcode!=246 and inv_dealercard.productcode!=247 and inv_dealercard.productcode!=373 and inv_dealercard.productcode!=310 and inv_dealercard.productcode!=355 and inv_dealercard.productcode!=486 and inv_dealercard.productcode!=517) ORDER BY scratchnumber";*/
		
		$query = "select inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber from inv_mas_scratchcard left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode where inv_mas_scratchcard.attached = 'yes' and inv_mas_scratchcard.registered = 'no' and inv_mas_scratchcard.blocked = 'no' and (inv_dealercard.customerreference = '' or inv_dealercard.customerreference is null)  and inv_mas_product.newproduct!= 1 ORDER BY inv_mas_scratchcard.scratchnumber";
		$result = runmysqlquery($query);
		$grid = '';
		$count = 1;
		while($fetch = mysqli_fetch_array($result))
		{
			if($count > 1)
				$grid .='^*^';
			$grid .=  $fetch['scratchnumber'].' | '.$fetch['cardid'].'^'.$fetch['cardid'];
			$count++;
		}
		echo($grid);
	 }
	break;
	case 'scratchdetailstoform':
	{
		$cardid = $_POST['cardid'];
		$query = "SELECT distinct inv_dealercard.cardid , inv_mas_scratchcard.scratchnumber, inv_mas_scratchcard.blocked,
inv_mas_scratchcard.cancelled,inv_mas_dealer.businessname as attachedto, inv_mas_dealer.slno as dealerid,
 inv_mas_product.productcode, inv_mas_product.productname, inv_dealercard.purchasetype, inv_dealercard.usagetype, 
inv_dealercard.date as attachdate,inv_dealercard.cuscardattacheddate as cuscardattacheddate, 
 inv_mas_customer.businessname as registeredto,inv_dealercard.cuscardremarks as cuscardremarks from inv_dealercard 
left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid 
left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno 
left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode 
left join inv_mas_customer on  inv_dealercard.customerreference = inv_mas_customer.slno where inv_dealercard.cardid = '".$cardid."';";
		$fetch = runmysqlqueryfetch($query);
		
		$attcheddate = substr($fetch['attachdate'] ,0,10);
		$registereddate =$fetch['registereddate'];
		if($registereddate != '')
			$registereddate = changedateformat($registereddate);
			
			
		if($fetch['blocked'] == 'yes')
		$cardstatus = 'Blocked';
		else if($fetch['cancelled'] == 'yes')
		$cardstatus = 'Cancelled';
		else
		{
		$cardstatus = 'Active';
		}
		echo('1^'.$fetch['cardid'].'^'.$fetch['scratchnumber'].'^'.$fetch['purchasetype'].'^'.$fetch['usagetype'].'^'.$fetch['attachedto'].'^'.$fetch['dealerid'].'^'.$fetch['productcode'].'^'.$fetch['productname'].'^'.changedateformat($attcheddate).'^'.''.'^'.''.'^'.$fetch['registeredto'].'^'.$cardstatus.'^'.changedateformatwithtime($fetch['cuscardattacheddate']).'^'.$fetch['cuscardremarks']);
		//echo($query);
	}
	break;
}

function sendfreeupdationcardemail($customerreference,$cardid)
{
	$query5 = "select inv_mas_customer.slno,inv_mas_customer.businessname,inv_mas_customer.customerid, inv_mas_customer.place, inv_mas_product.productname,inv_mas_scratchcard.scratchnumber as pinno,inv_mas_dealer.businessname as dealername from inv_dealercard left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid
left join inv_mas_customer on inv_mas_customer.slno = inv_dealercard.customerreference 
left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode
left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealercard.dealerid
where inv_dealercard.customerreference = '".$customerreference."' and inv_dealercard.cardid = '".$cardid."';";
	$result = runmysqlqueryfetch($query5);
	
	// Fetch Contact Details
	
	$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$result['slno']."'; ";
	$resultfetch = runmysqlquery($query1);
	$valuecount = 0;
	$emailids = array();
	while($fetchres = mysqli_fetch_array($resultfetch))
	{
		if(!in_array($fetchres['emailid'],$emailids))
		{
			if(checkemailaddress($fetchres['emailid']))
			{
				if($fetchres['contactperson'] != '')
					$emailids[$fetchres['contactperson']] = $fetchres['emailid'];
				else
					$emailids[$fetchres['emailid']] = $fetchres['emailid'];
			}
			$contactperson = $fetchres['contactperson'];
			$emailid = $fetchres['emailid'];
			$contactvalues .= $contactperson;
			$contactvalues .= appendcomma($contactperson);
			$phoneres .= $phone;
			$phoneres .= appendcomma($phone);
			$cellres .= $cell;
			$cellres .= appendcomma($cell);
			$emailidres .= $emailid;
			$emailidres .= appendcomma($fetchres['emailid']);
		}
	}
	$date = datetimelocal('d-m-Y');
	$businessname = $result['businessname'];
	$contactperson = trim($contactvalues,',');
	$place = $result['place'];
	$customerid = $result['customerid'];
	$productname = $result['productname'];
	$pinno = $result['pinno'];
	$dealername = $result['dealername'];
	$emailid = trim($emailidres,',');
	//Dummy line to override To email ID
	if(($_SERVER['HTTP_HOST'] == "bhumika") || ($_SERVER['HTTP_HOST'] == "192.168.2.79"))
		$emailid = 'bhumika.p@relyonsoft.com';
	else
		$emailid = $emailid;
		
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);
	
	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
			if(!in_array($emailarray[$i],$emailids))
			{
				$emailids[$emailarray[$i]] = $emailarray[$i];
			}
		}
	}

	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/manualcuscardattach.htm");
	$textmsg = file_get_contents("../mailcontents/manualcuscardattach.txt");
	$date = datetimelocal('d-m-Y');
	$array = array();
	$array[] = "##DATE##%^%".$date;
	$array[] = "##NAME##%^%".$contactperson;
	$array[] = "##COMPANY##%^%".$businessname;
	$array[] = "##PLACE##%^%".$place;
	$array[] = "##CUSTOMERID##%^%".cusidcombine($customerid);
	$array[] = "##PRODUCTNAME##%^%".$productname;
	$array[] = "##SCRATCHCARDNO##%^%".$pinno;
	$array[] = "##CARDID##%^%".$cardid;
	$array[] = "##DEALERNAME##%^%".$dealername;
	$array[] = "##EMAILID##%^%".$emailid;
	$filearray = array(
		array('../images/relyon-logo.jpg','inline','8888888888'),
	);
	$toarray = $emailids;
	if(($_SERVER['HTTP_HOST'] == "bhumika") || ($_SERVER['HTTP_HOST'] == "192.168.2.79"))
	{
		$bccemailids['bhumika'] ='bhumika.p@relyonsoft.com';
	}
	else
	{
		$bccemailids['relyonimax'] ='relyonimax@gmail.com';
		$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
	}
	$bccarray = $bccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = "You have been issued with a PIN Number for ".$productname." registration.";
	$html = $msg;
	$text = $textmsg;
	$replyto = 'support@relyonsoft.com';
	//print_r($toarray);
	//print_r($bccarray);
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray,$replyto); 
	
	//Insert the mail forwarded details to the logs table
	$bccmailid = 'bigmail@relyonsoft.com'; 
	inserttologs(imaxgetcookie('userid'),$customerreference,$fromname,$fromemail,$emailid,null,$bccmailid,$subject);
						
}

?>