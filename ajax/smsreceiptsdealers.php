<?php

ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');
if(imaxgetcookie('userid')<> '') 
$userid = imaxgetcookie('userid');
else
{ 
	echo('Thinking to redirect');
	exit;
}

include('../inc/checkpermission.php');

$lastslno = $_POST['lastslno'];
$switchtype = $_POST['switchtype'];
switch($switchtype)
{
	case 'save':
	{
		$dealerid = $_POST['dealerid'];
		$receiptremarks = $_POST['remarks'];
		$privatenote = $_POST['privatenote'];
		$invoiceno = $_POST['invoivcelist'];
		$invoiceamount = getinvoiceamount($invoiceno);
		$receiptamount = $_POST['receiptamount'];
		$paymentmode = $_POST['paymentmode'];
		if($lastslno == '')
		{
			//Insert Receipt Details 
			$query = "INSERT INTO inv_sms_receipt(invoiceno,invoiceamount,receiptamount,paymentmode,receiptremarks,privatenote,createddate,createdby,createdip,lastmodifieddate,lastmodifiedby,lastmodifiedip) values('".$invoiceno."','".$invoiceamount."','".$receiptamount."','".$paymentmode."','".$receiptremarks."','".$privatenote."','".datetimelocal('Y-m-d').' '.datetimelocal('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".datetimelocal('Y-m-d').' '.datetimelocal('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."');";
			$result = runmysqlquery($query);
		}
		else
		{
			//Update Receipt Details
			$query = "UPDATE inv_sms_receipt set invoiceno = '".$invoiceno."',invoiceamount = '".$invoiceamount."',receiptamount = '".$receiptamount."',paymentmode = '".$paymentmode."',receiptremarks = '".$receiptremarks."',privatenote = '".$privatenote."',lastmodifieddate = '".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedby ='".$userid."',	lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."' where slno = '".$lastslno."'; ";
			$result = runmysqlquery($query);
		}
		echo("1^"."Receipt Saved Successfully");
	}
	break;
	case 'generatedealerlist':
	{
		$query = "select distinct inv_mas_dealer.slno,inv_mas_dealer.businessname from inv_smsactivation 
left join inv_mas_dealer on inv_smsactivation.userreference = inv_mas_dealer.slno where inv_smsactivation.usertype ='dealer' and activatesmsaccount = 'yes';";
		$result = runmysqlquery($query);
		$grid = '';
		$count = 1;
		while($fetch = mysqli_fetch_array($result))
		{
			if($count > 1)
				$grid .='^*^';
			$grid .= $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
		echo($grid);
	}
	break;
	case 'gridtoform':
	{
		$lastslno = $_POST['lastslno'];
		$query = "select inv_sms_receipt.slno,inv_smscredits.smsuserid,inv_sms_receipt.receiptamount,inv_sms_receipt.invoiceno,inv_sms_receipt.receiptremarks,
inv_sms_receipt.privatenote,inv_sms_receipt.paymentmode,inv_sms_receipt.createddate from inv_sms_receipt left join inv_sms_bill on inv_sms_bill.slno = inv_sms_receipt.invoiceno left join inv_smscredits on inv_smscredits.billref = inv_sms_bill.slno where inv_sms_receipt.slno =  '".$lastslno."';";
		$fetch = runmysqlqueryfetch($query);
		echo('1^'.$fetch['slno'].'^'.$fetch['invoiceno'].'^'.$fetch['receiptamount'].'^'.getinvoiceamount($fetch['invoiceno']).'^'.$fetch['receiptremarks'].'^'.$fetch['privatenote'].'^'.$fetch['paymentmode']);
	}
	break;
	case 'generatereceiptgrid':
	{
		$dealerid = $_POST['dealerid'];
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$resultcount = "select inv_mas_dealer.businessname,inv_mas_dealer.slno as dealerid,inv_smsactivation.smsusername,inv_sms_bill.netamount,
inv_sms_receipt.receiptamount,inv_sms_receipt.createddate,inv_mas_users.fullname,inv_sms_receipt.receiptremarks,inv_sms_receipt.privatenote from inv_sms_receipt left join inv_sms_bill on inv_sms_receipt.invoiceno = inv_sms_bill.slno 
left join inv_smscredits on inv_smscredits.billref = inv_sms_bill.slno
 left join inv_smsactivation on inv_smsactivation.slno = inv_smscredits.smsuserid 
left join inv_mas_dealer on inv_mas_dealer.slno = inv_smsactivation.userreference 
left join inv_mas_users on inv_mas_users.slno = inv_sms_receipt.createdby  where inv_smsactivation.userreference = '".$dealerid."';";
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
		$query = "select inv_sms_receipt.slno,inv_mas_dealer.businessname,inv_mas_dealer.slno as dealerid,inv_smsactivation.smsusername,inv_sms_bill.netamount,
inv_sms_receipt.receiptamount,inv_sms_receipt.createddate,inv_mas_users.fullname,inv_sms_receipt.receiptremarks,inv_sms_receipt.privatenote,inv_sms_receipt.lastmodifieddate from inv_sms_receipt left join inv_sms_bill on inv_sms_receipt.invoiceno = inv_sms_bill.slno 
left join inv_smscredits on inv_smscredits.billref = inv_sms_bill.slno left join inv_smsactivation on inv_smsactivation.slno = inv_smscredits.smsuserid left join inv_mas_dealer on inv_mas_dealer.slno = inv_smsactivation.userreference left join inv_mas_users on inv_mas_users.slno = inv_sms_receipt.createdby  where inv_smsactivation.userreference = '".$dealerid."' LIMIT ".$startlimit.",".$limit."; ";
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid">Sl No</td><td nowrap = "nowrap" class="td-border-grid">Company name</td><td nowrap = "nowrap" class="td-border-grid">Customer ID</td><td nowrap = "nowrap" class="td-border-grid">SMS Username</td><td nowrap = "nowrap" class="td-border-grid">Invoice Amount</td><td nowrap = "nowrap" class="td-border-grid">Receipt Amount</td><td nowrap = "nowrap" class="td-border-grid">Remarks</td><td nowrap = "nowrap" class="td-border-grid">Private Note</td><td nowrap = "nowrap" class="td-border-grid">Created Date</td><td nowrap = "nowrap" class="td-border-grid">Last Modified Date</td><td nowrap = "nowrap" class="td-border-grid">Entered By</td></tr>';
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
			$grid .= '<tr bgcolor='.$color.' class="gridrow" onclick ="receiptgridtoform(\''.$fetch['slno'].'\');" >';
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$slnocount."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".gridtrim($fetch['businessname'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".gridtrim($fetch['dealerid'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".gridtrim($fetch['smsusername'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".gridtrim($fetch['netamount'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".gridtrim($fetch['receiptamount'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".gridtrim($fetch['receiptremarks'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".gridtrim($fetch['privatenote'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".changedateformatwithtime($fetch['createddate'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".changedateformatwithtime($fetch['lastmodifieddate'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".gridtrim($fetch['fullname'])."</td>";
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoregenerateschemegrid(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >></a><a onclick ="getmoregeneratereceiptgrid(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
			echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid;	
	}
	break;
	case 'getuserinvoicelist':
	{
		$dealerid = $_POST['dealerid'];
		$query = "select * from inv_sms_bill where userreference = '".$dealerid."'";
		$result = runmysqlquery($query);
		$grid = '<select name="invoivcelist" class="swiftselect-mandatory" id="invoivcelist" style="width:200px;" onchange="getinovoiceamount();">                      <option value="">Select a Invoice</option>';
		while($fetch = mysqli_fetch_array($result))
		{
			$grid .='<option value="'.$fetch['slno'].'">'.$fetch['slno'].'</option>';
		}
		$grid .= '</select>';
		echo('1^'.$grid);
	}
	break;
	case 'getinovoiceamount':
	{
		$invoiceno = $_POST['invoiceno'];
		$netamount = getinvoiceamount($invoiceno);
		echo('1^'.$netamount);

	}
	break;
}

//Function to get the invoice amount
function getinvoiceamount($invoiceno)
{
	$query = "Select * from inv_sms_bill where slno = '".$invoiceno."';";
	$resultfetch= runmysqlqueryfetch($query);
	$netamount = $resultfetch['netamount'];
	$query = "select sum(receiptamount) as receiptamount from inv_sms_receipt where invoiceno = '".$invoiceno."'" ;
	$resultfetch = runmysqlqueryfetch($query);
	$receiptamount = $resultfetch['receiptamount'];
	if($receiptamount == '')
		$receiptamount = 0;
	$totalamount = $netamount - $receiptamount;
	return $totalamount;
}

?>