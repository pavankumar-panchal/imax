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

$lastslno = $_POST['lastslno'];
$switchtype = $_POST['switchtype'];
switch($switchtype)
{
	case 'save':
	{
		$customerreference = $_POST['customerreference'];
		$smsactivateddate = datetimelocal('Y-m-d').' '.datetimelocal('H:i:s');
		$remarks = $_POST['remarks'];
		$privatenote = $_POST['privatenote'];
		$quantity = $_POST['quantity'];
		$totalamount = $_POST['totalamount'];
		$taxamount = $_POST['servicetax'];
		$netamount = $_POST['netamount'];
		$smsuserid = $_POST['smsuserid'];
		$sendinvoice = $_POST['sendinvoice'];
		if($lastslno == '')
		{
			if($totalamount == 0)
			{
				$onlineinvoiceno = '';
			}
			else
			{
				//Get the inoice no from online bill table
				$query1 = "select max(slno) + 1 as billref from inv_invoicenumbers";
				$resultfetch1 = runmysqlqueryfetch($query1);
				$onlineinvoiceno = $resultfetch1['billref'];
				
				//Insert Online bill no to bill table
				$query = "Insert into inv_invoicenumbers(slno,createddate) values('".$onlineinvoiceno."','".date('Y-m-d')."');";
				$result = runmysqlquery($query);
			}
			//Get the invoice no from the bill table
			$query = "select max(slno) + 1 as billref from inv_sms_bill;";
			$resultfetch = runmysqlqueryfetch($query);
			$billrefernce = $resultfetch['billref'];
			$invoiceno = 'RSL/SMS/'.$billrefernce;
			//Insert the invoice details to table
			//$query = "INSERT INTO inv_sms_bill(slno,userreference,billdate,remarks,total,userid,taxamount,netamount,invoiceno,privatenote,createdip,lastmodifieddate,lastmodifiedby,lastmodifiedip,onlineinvoiceno) values('".$billrefernce."','".$customerreference."','".datetimelocal('Y-m-d').' '.datetimelocal('H:i:s')."','".$remarks."','".$totalamount."','".$userid."','".$taxamount."','".$netamount."','".$invoiceno."','".$privatenote."','".$_SERVER['REMOTE_ADDR']."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".$onlineinvoiceno."');";
			//$result = runmysqlquery($query);
				
			//Insert credit details to table
			$query = "INSERT INTO inv_smscredits (billref,amount,enteredby,createddate,createdip,lastmodifieddate,lastmodifiedby,lastmodifiedip,remarks,quantity,smsuserid) values('".$billrefernce."','".$netamount."','".$userid."','".datetimelocal('Y-m-d').' '.datetimelocal('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".$remarks."','".$quantity."','".$smsuserid."');";
			$result = runmysqlquery($query);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','64','".date('Y-m-d').' '.date('H:i:s')."')";
			$eventresult = runmysqlquery($eventquery);
			//if($sendinvoice == 'yes')
				//sendinvoice($billrefernce);
		}
		else
		{
			//Update bill details
			$query = "UPDATE inv_sms_bill set remarks = '".$remarks."',privatenote = '".$privatenote."',lastmodifieddate = '".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedby ='".$userid."',	lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."' where slno = '".$lastslno."'; ";
			$result = runmysqlquery($query);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','65','".date('Y-m-d').' '.date('H:i:s')."')";
			$eventresult = runmysqlquery($eventquery);
			//if($sendinvoice == 'yes')
				//sendinvoice($lastslno);
		}

		echo(json_encode("1^"."SMS Credits Saved Successfully"));
	}
	break;
	case 'generatecustomerlist':
	{
		$customerarray = array();
		$limit = $_POST['limit'];
		$startindex = $_POST['startindex'];
		//Get the list of activated customers
		$query = "select distinct inv_mas_customer.slno,inv_mas_customer.businessname from inv_smsactivation 
left join inv_mas_customer on inv_smsactivation.userreference = inv_mas_customer.slno where inv_smsactivation.usertype ='customer' and activatesmsaccount = 'yes' order by inv_mas_customer.businessname LIMIT ".$startindex.",".$limit.";";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$customerarray[$count] = $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
		//echo($grid);
		echo(json_encode($customerarray));
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
	case 'gridtoform':
	{
		$gridtoformarray = array();
		$lastslno = $_POST['lastslno'];
		$query = "select inv_sms_bill.slno,inv_smscredits.quantity,inv_smscredits.billref,
inv_smscredits.amount,inv_smscredits.createddate,inv_mas_users.fullname,inv_smscredits.smsuserid,
inv_sms_bill.remarks,inv_sms_bill.privatenote,inv_sms_bill.invoiceno,inv_sms_bill.total,inv_sms_bill.taxamount,inv_sms_bill.netamount from inv_smscredits left join inv_mas_users on inv_mas_users.slno = inv_smscredits.enteredby left join inv_smsactivation on inv_smsactivation.slno = inv_smscredits.smsuserid 
 where inv_smscredits.slno =  '".$lastslno."';";
		$fetch = runmysqlqueryfetch($query);
		$gridtoformarray['errorcode'] = '1';
		$gridtoformarray['slno'] = $fetch['slno'];
		$gridtoformarray['quantity'] = $fetch['quantity'];
		$gridtoformarray['invoiceno'] = $fetch['invoiceno'];
		$gridtoformarray['amount'] = $fetch['amount'];
		$gridtoformarray['createddate'] = changedateformatwithtime($fetch['createddate']);
		$gridtoformarray['remarks'] = $fetch['remarks'];
		$gridtoformarray['fullname'] = $fetch['fullname'];
		$gridtoformarray['smsuserid'] = $fetch['smsuserid'];
		$gridtoformarray['privatenote'] = $fetch['privatenote'];
		$gridtoformarray['total'] = $fetch['total'];
		$gridtoformarray['taxamount'] = $fetch['taxamount'];
		$gridtoformarray['netamount'] = $fetch['netamount'];
		echo(json_encode($gridtoformarray));
		
	//	echo("1".'^'.$fetch['slno'].'^'.$fetch['quantity'].'^'.$fetch['invoiceno'].'^'.$fetch['amount'].'^'.changedateformatwithtime($fetch['createddate']).'^'.$fetch['remarks'].'^'.$fetch['fullname'].'^'.$fetch['smsuserid'].'^'.$fetch['privatenote'].'^'.$fetch['total'].'^'.$fetch['taxamount'].'^'.$fetch['netamount']);
	}
	break;
	case 'generatesmsgrid':
	{
		//Generate grid
		$customerreference = $_POST['customerreference'];
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$resultcount = "select inv_smscredits.slno,inv_smsactivation.userreference,inv_smsactivation.smsusername,inv_smscredits.quantity,inv_smscredits.billref,inv_smscredits.amount,inv_smscredits.createddate,inv_smscredits.lastmodifieddate,inv_smscredits.remarks,inv_mas_users.fullname,inv_sms_bill.onlineinvoiceno from inv_smscredits left join inv_mas_users on inv_mas_users.slno = inv_smscredits.enteredby left join inv_smsactivation on inv_smsactivation.slno = inv_smscredits.smsuserid left join inv_sms_bill on inv_sms_bill.slno = inv_smscredits.billref where inv_smsactivation.userreference = '".$customerreference."';";
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
		$query = "select inv_smscredits.slno,inv_smsactivation.userreference,inv_smsactivation.smsusername,inv_smscredits.quantity,inv_smscredits.slno as billref,inv_smscredits.amount,inv_smscredits.createddate,inv_smscredits.lastmodifieddate,inv_sms_bill.remarks,inv_sms_bill.privatenote,inv_mas_users.fullname, inv_sms_bill.onlineinvoiceno from inv_smscredits left join inv_mas_users on inv_mas_users.slno = inv_smscredits.enteredby left join inv_smsactivation on inv_smsactivation.slno = inv_smscredits.smsuserid left join inv_sms_bill on inv_sms_bill.slno = inv_smscredits.billref where inv_smsactivation.userreference = '".$customerreference."' order by inv_smscredits.createddate desc LIMIT ".$startlimit.",".$limit." ; ";
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid"  align="left">Customer ID</td><td nowrap = "nowrap" class="td-border-grid"  align="left">SMS Username</td><td nowrap = "nowrap" class="td-border-grid"  align="left">Quanity</td><td nowrap = "nowrap" class="td-border-grid"  align="left">Amount</td><td nowrap = "nowrap" class="td-border-grid"  align="left">Bill Reference</td><td nowrap = "nowrap" class="td-border-grid"  align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid"  align="left">Created Date</td><td nowrap = "nowrap" class="td-border-grid"  align="left">Remarks</td><td nowrap = "nowrap" class="td-border-grid"  align="left">Private Note</td><td nowrap = "nowrap" class="td-border-grid"  align="left">Last Modified Date</td><td nowrap = "nowrap" class="td-border-grid"  align="left">Entered By</td></tr>';
		}
		
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$i_n++;
			$slnocount++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr bgcolor='.$color.' class="gridrow" onclick ="smsgridtoform(\''.$fetch['billref'].'\');" >';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['userreference'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['smsusername'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['quantity'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['amount'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['billref'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['onlineinvoiceno'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch['createddate'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['remarks'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['privatenote'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch['lastmodifieddate'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['fullname'])."</td>";
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoregenerateschemegrid(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoregenerateschemegrid(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
			echo "1^".$grid.'^'.$fetchresultcount.'^'.$linkgrid;	
	}
	break;
	case 'gettotalcredits':
	{
		//Get the total credits available
		$customerreference = $_POST['customerreference'];
		$query = "select * from  inv_smsactivation where userreference = '".$customerreference."' and usertype = 'customer';";
		$result = runmysqlquery($query);
		$balance =0;$totalcredits =0;
		while($fetch = mysqli_fetch_array($result))
		{
			$smsuserid = $fetch['slno'];
			$balance = gettotalsmscredits($smsuserid);
			$totalcredits += $balance;
		}
		echo(json_encode('1^'.$totalcredits));
	}
	break;
	case 'getuseraccountlist':
	{
		//get the list of accounts for the selected customer
		$customerreference = $_POST['customerreference'];
		$query = "select * from inv_smsactivation where userreference = '".$customerreference."' order by smsusername;";
		$result = runmysqlquery($query);
		$grid = '<select name="smsaccount" class="swiftselect-mandatory" id="smsaccount" style="width:200px;" onchange = "gettotalcreditforthataccount();">                      <option value="">Select an Account</option>';
		while($fetch = mysqli_fetch_array($result))
		{
			$grid .='<option value="'.$fetch['slno'].'">'.$fetch['smsusername'].'</option>';
		}
		$grid .= '</select>';
		echo(json_encode('1^'.$grid));
	}
	break;
	case 'gettotalamount':
	{
		//calculate the total amount, tax, net amount
		$quantity = $_POST['quantity'];
		if($quantity <>'')
		{
			$totalamount = calculatesmsamount($quantity);
		}
		else
		{
			$totalamount = $_POST['billamount'];
		}
		$taxamount =  round($totalamount *(12.36/100));// edited by bhavesh round($totalamount *(10.3/100))
		$netamount = $totalamount + $taxamount;
		echo(json_encode('1^'.$totalamount.'^'.$taxamount.'^'.round($netamount)));
	}
	break;
	case 'gettotalcreditforthataccount':
	{
		$smsuserid = $_POST['smsuserid'];
		$creditavailable = gettotalsmscredits($smsuserid);
		echo(json_encode('1^'.$creditavailable));
	}
	break;
}

function sendinvoice($invoiceno)
{
	$type = 'customer';
	$filebasename = generatepdfbill($invoiceno,$type);
	if($filebasename <> '')
	{
		$query = "select inv_mas_customer.slno as slno , inv_sms_bill.total, inv_sms_bill.taxamount, inv_sms_bill.netamount, inv_sms_bill.invoiceno, inv_mas_customer.businessname, 
inv_smsactivation.contactperson,inv_smsactivation.emailid,inv_smsactivation.cell,inv_smscredits.quantity,inv_smscredits.smsuserid, inv_sms_bill.onlineinvoiceno from inv_sms_bill left join inv_smscredits on inv_sms_bill.slno = inv_smscredits.billref left join inv_smsactivation on inv_smsactivation.slno =  inv_smscredits.smsuserid left join inv_mas_customer on inv_mas_customer.slno = inv_smsactivation.userreference 
where inv_sms_bill.slno = '".$invoiceno."';";
		$result = runmysqlqueryfetch($query);
		
		$emailid = $result['emailid'];
		$businessname = $result['businessname'];
		$quantity = $result['quantity'];
		$invoiceno = $result['onlineinvoiceno'];
		$smsuserid = $result['smsuserid'];
		$slno = $result['slno'];
		
		//Dummy line to override To email ID
		//$emailid = 'rashmi.hk@relyonsoft.com';
		if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
			$emailid = 'rashmi.hk@relyonsoft.com';
		else
			$emailid = $result['emailid'];
	
		//Get the total credits available
		$totalcredits = gettotalsmscredits($smsuserid);
		
		//Split multiple email IDs by Comma
		$emailarray = explode(',',$emailid);
		$emailcount = count($emailarray);
		
		for($i = 0; $i < $emailcount; $i++)
		{
			if(checkemailaddress($emailarray[$i]))
			{
					$emailids[$emailarray[$i]] = $emailarray[$i];
			}
		}
	
		$fromname = "Relyon";
		$fromemail = "imax@relyon.co.in";
		require_once("../inc/RSLMAIL_MAIL.php");
		$msg = file_get_contents("../mailcontents/smsbill.htm");
		$textmsg = file_get_contents("../mailcontents/smsbill.txt");
	
		//Create an array of replace parameters
		$array = array();
		$date = datetimelocal('d-m-Y');
		$array[] = "##DATE##%^%".$date;
		$array[] = "##COMPANY##%^%".$businessname;
		$array[] = "##QUANTITY##%^%".$quantity;
		$array[] = "##INVOICENO##%^%".$invoiceno;
		$array[] = "##TOTALQUANTITY##%^%".$totalcredits;
		$array[] = "##EMAILID##%^%".$emailid;
		//Relyon Logo for email Content, as Inline [Not attachment]
		$filearray = array(
			array('../images/relyon-logo.jpg','inline','1234567890'),array('../filecreated/'.$filebasename,'attachment','1234567891')
		);
		$toarray = $emailids;
		if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
		{
			$bccemailids['archanaab'] ='archana.ab@relyonsoft.com';
		}
		else
		{
			$bccemailids['relyonimax'] ='relyonimax@gmail.com';
			$bccemailids['Accounts'] ='accounts@relyonsoft.com';
			$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
			$bccemailids['webmaster'] ='webmaster@relyonsoft.com';
		}
		$bccarray = $bccemailids;
		$msg = replacemailvariable($msg,$array);
		$textmsg = replacemailvariable($textmsg,$array);
		$subject = "Relyon Online SMS Credits Purchase | Invoice: RSL-Online-".$invoiceno."";
		$html = $msg;
		$text = $textmsg;
		rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray);
	
		//$bccmailid = 'vijaykumar@relyonsoft.com,webmaster@relyonsoft.com,bigmail@relyonsoft.com,accounts@relyonsoft.com';
		inserttologs(imaxgetcookie('userid'),$slno,$fromname,$fromemail,$emailid,null,$bccmailid ,$subject);
		fileDelete('../filecreated/',$filebasename) ;
	}
}

?>