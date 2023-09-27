<?
/*
##
Created By Bhavesh Patel
Date : 30.07.2013
##
*/
ob_start("ob_gzhandler");
ini_set('memory_limit', -1);

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

$type = $_POST['type'];
$changetype = $_POST['changetype'];
$scratchnumber = $_POST['scratchnumber'];
$remarks = $_POST['remarks'];

switch($type)
{
	
	##Fetching Card List##
	case 'getcardlist':
	{
		$getcardlistarray = array();
		$query = "SELECT cardid,scratchnumber FROM inv_mas_scratchcard 
		where attached ='yes' and blocked ='no' and cancelled ='no' ORDER BY scratchnumber ";
		$result = runmysqlquery($query);
		$grid = '';
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$getcardlistarray[$count] =  $fetch['scratchnumber'].' | '.$fetch['cardid'].'^'.$fetch['cardid'];
			$count++;
		}
		echo(json_encode($getcardlistarray));
	}
	break;
	
	
	## Fetching Card Details to show output into fields##
	case 'scratchdetailstoform':
	{
		$responsearray5 = array();
		$cardid = $_POST['cardid'];
		//if($cardid!= "")
		$query = "SELECT distinct inv_dealercard.cardid , inv_mas_scratchcard.scratchnumber, 
		inv_mas_scratchcard.blocked,inv_mas_scratchcard.cancelled,inv_mas_dealer.businessname as attachedto,
		 inv_mas_dealer.slno as dealerid, inv_mas_product.productcode, inv_mas_product.productname, 
		 inv_dealercard.purchasetype, inv_dealercard.usagetype, inv_dealercard.date as attachdate, 
		 inv_customerproduct.date as registereddate, inv_customerproduct.customerreference as registeredto,
		 inv_dealercard.scheme,inv_mas_scheme.schemename as schemename,inv_dealercard.customerreference as customerid
		 from inv_dealercard 
		 left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid 
		 left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno 
		 left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode 
		 left join (select cardid, customerreference, min(`date`) as `date` from inv_customerproduct where cardid = '".$cardid."') AS inv_customerproduct on  inv_dealercard.cardid = inv_customerproduct.cardid 
		 left join inv_mas_customer on  inv_customerproduct.customerreference = inv_mas_customer.slno 
		 left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme where inv_dealercard.cardid = '".$cardid."';";
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
		$responsearray5['errorcode'] = '1';
		$responsearray5['cardid'] = $fetch['cardid'];
		$responsearray5['scratchnumber'] = $fetch['scratchnumber'];
		$responsearray5['purchasetype'] = $fetch['purchasetype'];
		$responsearray5['usagetype'] = $fetch['usagetype'];
		$responsearray5['attachedto'] = $fetch['attachedto'];
		$responsearray5['dealerid'] = $fetch['dealerid'];
		$responsearray5['attcheddate'] = changedateformat($attcheddate);
		$responsearray5['registereddate'] = $registereddate;
		$responsearray5['registeredto'] = $fetch['registeredto'];
		$responsearray5['cardstatus'] = $cardstatus;
		$responsearray5['schemename'] = $fetch['schemename'];
		$responsearray5['productname'] = $fetch['productname'];
		$responsearray5['productcode'] = $fetch['productcode'];
		$responsearray5['customerid'] = $fetch['customerid'];
		echo(json_encode($responsearray5));
	}
	break;

	//Transfer the PIN Number
	case 'scratchtransfer':
	{
		$responsearray8 = array();
		
		$tfproduct = $_POST['tfproduct'];
		$tfdealer = $_POST['tfdealer'];
		$tfpurchasetype = $_POST['tfpurchasetype'];
		$tfusagetype = $_POST['tfusagetype'];
		$cardid = $_POST['cardid'];
		$transferremarks = $_POST['transferremarks'];
		$transferby = $_POST['transferby'];
		$tfproductname = $_POST['tfproductname'];
		$tfdealername = $_POST['tfdealername'];
		$tfattachedcust = $_POST['tfattachedcust'];
		$pinnumber = $_POST['pinnumber'];
		$moveregisterationcheck = $_POST['moveregisterationcheck'];
		$tfregisteration = $_POST['tfregisteration'];
		$fromcustomername = customername($tfattachedcust);
		
		$dealerid = ($_POST['dealerid'] == "")?($tfdealer):($_POST['dealerid']);
		$productcode = ($_POST['productcode'] == "")?($tfproduct):($_POST['productcode']);
		$usagetype = ($_POST['usagetype'] == "")?($tfusagetype):($_POST['usagetype']);
		$purchasetype = ($_POST['purchasetype'] == "")?($tfpurchasetype):($_POST['purchasetype']);
		$attachcust = ($_POST['attachcust'] == "")?($tfattachedcust):($_POST['attachcust']);
		
		if($moveregisterationcheck == 'yes')
		{ $tfregistered = $tfregisteration; $ttregistered = $attachcust;}
		else{ $tfregistered = ''; $ttregistered = '';}
		/*$dealerid = ($_POST['dealerid'] == "")?('NOT Changed'):($_POST['dealerid']);
		$productcode = ($_POST['productcode'] == "")?('NOT Changed'):($_POST['productcode']);
		$usagetype = ($_POST['usagetype'] == "")?('NOT Changed'):($_POST['usagetype']);
		$purchasetype = ($_POST['purchasetype'] == "")?('NOT Changed'):($_POST['purchasetype']);
		$attachcust = ($_POST['attachcust'] == "")?('NOT Changed'):($_POST['attachcust']);*/
		
		$tocustomername = customername($attachcust);
		
		##Updating Purpose This is Scripted##
		/*if($dealerid == 'NOT Changed')
		{$updatedealer = $tfdealer;}else{$updatedealer = $dealerid;}
		if($productcode == 'NOT Changed')
		{$updateproduct = $tfproduct;}else{$updateproduct = $productcode;}
		if($usagetype == 'NOT Changed')
		{$updateusagetype = $tfusagetype;}else{$updateusagetype = $usagetype;}
		if($purchasetype == 'NOT Changed')
		{$updatepurchasetype = $tfpurchasetype;}else{$updatepurchasetype = $purchasetype;}
		if($attachcust == 'NOT Changed')
		{$updateattachedcust = $tfattachedcust;}else{$updateattachedcust = $attachcust;}*/
		
		
		/*$query = "UPDATE inv_dealercard SET dealerid = '".$updatedealer."', productcode = '".$updateproduct."', 
		purchasetype = '".$updatepurchasetype."', usagetype = '".$updateusagetype."', customerreference = '".$updateattachedcust."'
		 WHERE cardid = '".$cardid."'";
		$result = runmysqlquery($query);*/
		
		$query = "UPDATE inv_dealercard SET dealerid = '".$dealerid."', productcode = '".$productcode."', 
		purchasetype = '".$purchasetype."', usagetype = '".$usagetype."', customerreference = '".$attachcust."'
		 WHERE cardid = '".$cardid."'";
		$result = runmysqlquery($query);
		
		##Updating Registeration Details with checking the permission##
		if($moveregisterationcheck == 'yes')
		{			
			$query1 = "UPDATE inv_customerproduct SET customerreference = '".$ttregistered."'
			 WHERE cardid = '".$cardid."'";
			$result1 = runmysqlquery($query1);
		}
		
		##Inserting into log for transferpin ##
		$query1 = "insert into `inv_logs_transferpin` (cardid,transferfromdealerid, transferfromproduct, transferfromusagetype,transferfrompurchasetype,transferfromattachedcust,transferfromregisteration, transfertodealerid, transfertoproduct,transfertousagetype, transfertopurchasetype,transfertoattachedcust,transfertoregisteration, dateandtime, transferremarks,systemip, transferuserid, module) 
		values('".$cardid."','".$tfdealer."', '".$tfproduct."', '".$tfusagetype."', '".$tfpurchasetype."','".$tfattachedcust."','".$tfregistered."', '".$dealerid."', '".$productcode."', '".$usagetype."', '".$purchasetype."', '".$attachcust."' ,'".$ttregistered."' ,'".datetimelocal('Y-m-d')." ".datetimelocal('H:i:s')."', '".$transferremarks."','".$_SERVER['REMOTE_ADDR']."', '".$userid."', 'user_module')";
		$result1 = runmysqlquery($query1);
		
		## inserting log for event taking place ##
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) 
		values('".$userid."','".$_SERVER['REMOTE_ADDR']."','256','".date('Y-m-d').' '.date('H:i:s')."','".$cardid."')";
		$eventresult = runmysqlquery($eventquery);
		
		##For Mailing Purpose Function##
		transferpinmail();
		
		$responsearray8['errorcode'] = '1';
		$responsearray8['successmessage'] = $cardid.' is transfered';
		
		echo(json_encode($responsearray8));
	}
	break;

	case 'generategrid':
	{
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$cardid = $_POST['cardid'];
		$query1 ="SELECT count(*) as count from inv_logs_transferpin where cardid = '".$cardid."' ";  
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)
		{
			$resultcount = "SELECT inv_logs_transferpin.cardid as cardid,inv_mas_scratchcard.scratchnumber as scratchnumber,
			inv_mas_dealer.businessname as dlrname,inv_mas_product.productname as productname,
			inv_logs_transferpin.transfertopurchasetype as transfertopurchasetype,inv_logs_transferpin.transfertousagetype as transfertousagetype,
			inv_mas_users.fullname as fullname,inv_logs_transferpin.dateandtime,inv_logs_transferpin.transferremarks
			FROM inv_logs_transferpin 
			LEFT JOIN inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_logs_transferpin.cardid 
			LEFT JOIN inv_mas_users on inv_mas_users.slno = inv_logs_transferpin.transferuserid
			LEFT JOIN inv_mas_product on inv_mas_product.productcode = inv_logs_transferpin.transfertoproduct
			LEFT JOIN inv_mas_dealer on inv_mas_dealer.slno = inv_logs_transferpin.transfertodealerid
			WHERE inv_logs_transferpin.cardid = '".$cardid."' order by inv_logs_transferpin.dateandtime desc;";
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
			$startlimit = $slno ;
			$slno = $slno;
		}
		$query2 = "SELECT inv_logs_transferpin.cardid as cardid,inv_mas_scratchcard.scratchnumber as scratchnumber,
			inv_mas_dealer.businessname as dlrname,inv_mas_product.productname as productname,
			inv_logs_transferpin.transfertopurchasetype as purchasetype,inv_logs_transferpin.transfertousagetype as usagetype,
			inv_logs_transferpin.transfertoattachedcust as attachedcust,inv_mas_users.fullname as fullname,
			inv_logs_transferpin.dateandtime,inv_logs_transferpin.transferremarks
			FROM inv_logs_transferpin 
			LEFT JOIN inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_logs_transferpin.cardid 
			LEFT JOIN inv_mas_users on inv_mas_users.slno = inv_logs_transferpin.transferuserid
			LEFT JOIN inv_mas_product on inv_mas_product.productcode = inv_logs_transferpin.transfertoproduct
			LEFT JOIN inv_mas_dealer on inv_mas_dealer.slno = inv_logs_transferpin.transfertodealerid
			WHERE inv_logs_transferpin.cardid = '".$cardid."' order by inv_logs_transferpin.dateandtime desc LIMIT ".$startlimit.",".$limit.";";
 		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header">
			 <td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
			 <td nowrap = "nowrap" class="td-border-grid" align="left">Card ID</td>
			 <td nowrap = "nowrap" class="td-border-grid" align="left">Pin Number</td>
			 <td nowrap = "nowrap" class="td-border-grid" align="left">Dealer Name</td>
			 <td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td>
			 <td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Type</td>
			 <td nowrap = "nowrap" class="td-border-grid" align="left">Usage Type</td>
			 <td nowrap = "nowrap" class="td-border-grid" align="left">Attached Customer</td>
			 <td nowrap = "nowrap" class="td-border-grid" align="left">Transfered By</td>
			 <td nowrap = "nowrap" class="td-border-grid" align="left">Last Transfered Date</td>
			  <td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td>
			 </tr>';
		}
		$i_n = 0;
		$result2 = runmysqlquery($query2);
		while($fetch = mysqli_fetch_array($result2))
		{
			$i_n++;$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow" bgcolor='.$color.' align="left">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slno."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['cardid']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['scratchnumber']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['dlrname']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['productname']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['purchasetype']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['usagetype']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['attachedcust']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['fullname']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch['dateandtime'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['transferremarks']."</td>";
			$grid .= "</tr>";
		}
			$grid .= "</table>";
			$fetchcount = mysqli_num_rows($result2);
			if($slno >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px">
			<tr>
			<td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td>
			</tr></table>';
			else
			$linkgrid .= '<table>
			<tr>
			<td class="resendtext">
			<div align ="left" style="padding-right:10px">
			<a onclick ="cusmoredatagrid(\''.$startlimit.'\',\''.$slno.'\',\'more\');" style="cursor:pointer">Show More Records >></a>
			<a onclick ="cusmoredatagrid(\''.$startlimit.'\',\''.$slno.'\',\'all\');" class="resendtext1"  style="cursor:pointer">
			<font color = "#000000">(Show All Records)</font></a></div>
			</td>
			</tr>
			</table>';
			
				echo('1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid);
			}
				else
			{
				echo('2^No datas found to be displayed.')	;
			}	
		}
	break;

	case 'checkcustomerid':
	{
		$attachcust = $_POST['attachcust'];
		
		if($attachcust <> '')
		{
			$query1 = "SELECT count(customerid) as count from inv_mas_customer where right(customerid,5) = '".$attachcust."';";
			$fetch1 = runmysqlqueryfetch($query1);
			
			if($fetch1['count'] <> 1)
			{
					$responsearray1 = array();
					//echo("4^"."E-Mail Address is available");
					$responsearray1['errorcode'] = "2";
					$responsearray1['errormessage'] = "Customer ID doesnot exist!!";
			}
			else
			{
					$responsearray1 = array();
					//echo("5^"."E-Mail Address is unavailable");
					$responsearray1['errorcode'] = "1";
					$responsearray1['errormessage'] = "";
					
			}
		}
		else
		{
			$responsearray1 = array();
			//echo("5^"."E-Mail Address is unavailable");
			$responsearray1['errorcode'] = "7";
			$responsearray1['errormessage'] = "";
		}
		echo(json_encode($responsearray1));
	}
	break;
}
?>
