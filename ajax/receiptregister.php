<?
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');
include('../inc/checksession.php');

if(imaxgetcookie('userid')<> '') 
$userid = imaxgetcookie('userid');
else
{ 
	echo('Thinking to redirect');
	exit;
}


$lastslno = $_POST['lastslno'];
$switchtype = $_POST['switchtype'];

switch($switchtype)
{
	case 'receiptdetails':
	{
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$resultcount = "select count(distinct inv_mas_receipt.slno) as count from inv_mas_receipt 
		left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
		left join inv_dealer_invoicenumbers on inv_dealer_invoicenumbers.slno = inv_mas_receipt.dealerinvoiceno 
		left join inv_matrixinvoicenumbers on inv_matrixinvoicenumbers.slno = inv_mas_receipt.matrixinvoiceno
		where left(inv_mas_receipt.receiptdate,10) = '".date('Y-m-d')."' order by inv_mas_receipt.slno";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];
		
		$invoiceresult = "select distinct inv_mas_receipt.slno,sum(inv_mas_receipt.receiptamount) as receiptamount from inv_mas_receipt 
		left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
		left join inv_dealer_invoicenumbers on inv_dealer_invoicenumbers.slno = inv_mas_receipt.dealerinvoiceno
		left join inv_matrixinvoicenumbers on inv_matrixinvoicenumbers.slno = inv_mas_receipt.matrixinvoiceno
		where  left(inv_mas_receipt.receiptdate,10) = '".date('Y-m-d')."' group by inv_mas_receipt.slno with rollup limit ".($fetchresultcount).",1 ";
		$fetchresult1 = runmysqlquery($invoiceresult);
		if(mysqli_num_rows($fetchresult1) > 0)
		{
			$fetchresult = runmysqlqueryfetch($invoiceresult);
			$totalreceipts = $fetchresultcount;
			$totalamount = $fetchresult['receiptamount'];
		}
		else
		{
			$totalreceipts = '0';
			$totalamount = '0';
		}
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
		$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.dealerinvoiceno,inv_mas_receipt.customerreference as dealerid,inv_mas_receipt.receiptdate,inv_mas_receipt.receiptremarks,inv_invoicenumbers.customerid,
		inv_invoicenumbers.businessname,inv_dealer_invoicenumbers.businessname as dlrbusinessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno,inv_dealer_invoicenumbers.invoiceno as dlrinvoiceno,
		inv_invoicenumbers.dealername,inv_dealer_invoicenumbers.dealername as dlrname,inv_mas_receipt.createdby as createdbyid,inv_invoicenumbers.status,inv_dealer_invoicenumbers.status as dlrstatus,inv_mas_receipt.reconsilation as reconsilation,
		inv_mas_receipt.module ,inv_matrixinvoicenumbers.customerid as mcustid,inv_matrixinvoicenumbers.businessname as mbusinessname,
		inv_matrixinvoicenumbers.invoiceno as minvoiceno,inv_matrixinvoicenumbers.dealername as mdealername,inv_matrixinvoicenumbers.status as mstatus
		from inv_mas_receipt 
		left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
		left join inv_dealer_invoicenumbers on inv_dealer_invoicenumbers.slno = inv_mas_receipt.dealerinvoiceno 
		left join inv_matrixinvoicenumbers on inv_matrixinvoicenumbers.slno = inv_mas_receipt.matrixinvoiceno
		where  left(inv_mas_receipt.receiptdate,10) = '".date('Y-m-d')."'  order by inv_mas_receipt.createddate desc LIMIT ".$startlimit.",".$limit." ; ";
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		//$grid = '<tr><td><table width="100%" cellpadding="3" cellspacing="0">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Receipt No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Receipt Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Status</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Receipt Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Mode</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sales Person</td><td nowrap = "nowrap" class="td-border-grid" align="left">Prepared By</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Reconcile Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Remarks: Payment details</td></tr>';
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
			if($fetch['reconsilation'] == 'notseen')
				$reconciletype = 'NOT SEEN';
			elseif($fetch['reconsilation'] == 'matched')
				$reconciletype = 'MATCHED';
			elseif($fetch['reconsilation'] == 'unmatched')
				$reconciletype = 'UNMATCHED';
			if($fetch['module'] == 'user_module' || $fetch['module'] == 'customer_module' || $fetch['module'] == 'Online')
			{
				$queryfetch = "SELECT inv_mas_users.fullname as createdby from  inv_mas_users where slno = '".$fetch['createdbyid']."';";
				$resultvalue = runmysqlqueryfetch($queryfetch);
			}
			else
			{
				$queryfetch = "SELECT inv_mas_dealer.businessname as createdby from  inv_mas_dealer where slno = '".$fetch['createdbyid']."';";
				$resultvalue = runmysqlqueryfetch($queryfetch);
				
			}
			if($fetch['dealerinvoiceno']!= "")
			{
				$businessname = trim($fetch['dlrbusinessname']);
				$invoiceno = trim($fetch['dlrinvoiceno']);
				$status = trim($fetch['dlrstatus']);
				$dealername = trim($fetch['dlrname']);
			}
			else if($fetch['businessname']!=""){
				$businessname = trim($fetch['businessname']);
				$invoiceno = trim($fetch['invoiceno']);
				$status = trim($fetch['status']);
				$dealername = trim($fetch['dealername']);
			}
			else if($fetch['minvoiceno']!="")
			{
				$businessname = trim($fetch['mbusinessname']);
				$status = trim($fetch['mstatus']);
				$invoiceno = trim($fetch['minvoiceno']);
				$dealername = trim($fetch['mdealername']);
			}
			else
			{
				$query1 = "select businessname from inv_mas_dealer where slno = ".$fetch['dealerid'];
				$fetch1 = runmysqlqueryfetch($query1);
				$businessname = $fetch1['businessname'];
			}

			$grid .= '<tr bgcolor='.$color.' class="gridrow1" align="left">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['slno']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat(trim($fetch['receiptdate']))."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$invoiceno."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$status."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['customerid'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$businessname."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['receiptamount']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".getpaymentmode($fetch['paymentmode'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$dealername."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($resultvalue['createdby'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$reconciletype."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['receiptremarks']."</td>";
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoreinvoicedetails(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoreinvoicedetails(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
		
	
			echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$totalreceipts.'^'.formatnumber($totalamount).'^'.convert_number($totalamount);	
	}
	break;
	case 'searchinvoices':
	{
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$paymentmode = $_POST['paymentmode'];
		$fromdate = changedateformat($_POST['fromdate']);
		$todate = changedateformat($_POST['todate']);
		
		$databasefield = $_POST['databasefield'];
		$textfield = $_POST['textfield'];
		$state = $_POST['state'];
		$region = $_POST['region'];
		$dealer = $_POST['dealer'];
		$branch = $_POST['branch'];
		$generatedby = $_POST['generatedby'];
		$generatedbysplit = explode('^',$generatedby);
		$district = $_POST['district'];
		$productslist = $_POST['productscode'];
		$productlistsplit = explode(',',$productslist);
		$productlistsplitcount = count($productlistsplit);
		$status = $_POST['status'];
		$series = $_POST['series'];
		$receiptstatus = $_POST['receiptstatus'];
		$cancelledinvoice = $_POST['cancelledinvoice'];
		$totalreceipts = '0';
		$totalamount = '0';
		$itemlist = $_POST['itemlist'];
		$reconciletype = $_POST['reconciletype'];
		$itemlistsplit = explode(',',$itemlist);
		$itemlistsplitcount = count($itemlistsplit);
		$alltimecheck = $_POST['alltimecheck'];

		$matrixlist = $_POST['matrixlist'];
		$matrixlistsplit = explode(',',$matrixlist);
		$matrixlistsplitcount = count($matrixlistsplit);
		
		if($productslist != '')
		{
			for($i = 0;$i< $productlistsplitcount; $i++)
			{
				if($i < ($productlistsplitcount-1))
					$appendor = 'or'.' ';
				else
					$appendor = '';
					
				$finalproductlist .= ' inv_invoicenumbers.products'.' '.'like'.' "'.'%'.$productlistsplit[$i].'%'.'" '.$appendor."";
			}
			for($k = 0;$k< $productlistsplitcount; $k++)
			{
				if($k < ($productlistsplitcount-1))
					$appendor = 'or'.' ';
				else
					$appendor = '';
					
				$finaldlrproductlist .= ' inv_dealer_invoicenumbers.products'.' '.'like'.' "'.'%'.$productlistsplit[$k].'%'.'" '.$appendor."";
			}
		}

		if($matrixlist!= '')
		{
			for($i = 0;$i< $matrixlistsplitcount; $i++)
			{
				if($i < ($matrixlistsplitcount-1))
					$appendor = 'or'.' ';
				else
					$appendor = '';
					
				$finalmatrixlist .= ' inv_matrixinvoicenumbers.products'.' '.'like'.' "'.'%'.$matrixlistsplit[$i].'%'.'" '.$appendor."";
			}
		}
		
		
		if($itemlist != '')
		{
			for($j = 0;$j< $itemlistsplitcount; $j++)
			{
				if($j < ($itemlistsplitcount-1))
					$appendor1 = 'or'.' ';
				else
					$appendor1 = '';
					
				$finalitemlist .= ' inv_invoicenumbers.servicedescription'.' '.'like'.' "'.'%'.$itemlistsplit[$j].'%'.'" '.$appendor1."";
			}
			for($m = 0;$m< $itemlistsplitcount; $m++)
			{
				if($m < ($itemlistsplitcount-1))
					$appendor1 = 'or'.' ';
				else
					$appendor1 = '';
					
				$finaldlritemlist .= ' inv_dealer_invoicenumbers.servicedescription'.' '.'like'.' "'.'%'.$itemlistsplit[$m].'%'.'" '.$appendor1."";
			}
		}

		if($productslist != '' && $itemlist != '' && $matrixlist!= '')
			$finallistarray = ' AND ('.$finalproductlist.' '.'OR'.' '.$finalitemlist.' '.'OR'.' '.$finalmatrixlist.')';
		elseif(($itemlist != '') && ($productslist != ''))
			$finallistarray = ' AND ('.$finalproductlist.' '.'OR'.' '.$finalitemlist.')';
		elseif($productslist != '' && $matrixlist!= '')
			$finallistarray = ' AND ('.$finalproductlist.' '.'OR'.' '.$finalmatrixlist.')';
		elseif($productslist == '')
			$finallistarray = ' AND ('.$finalitemlist.')';
		elseif($itemlist == '')
			$finallistarray = ' AND ('.$finalproductlist.')';
		elseif($matrixlist!= '')
			$finallistarray = ' AND ('.$finalmatrixlist.')';
		
		if($matrixlist!= '')
		{
			$mregion = " OR inv_matrixinvoicenumbers.regionid = '".$region."'";
			$mdealer = " OR inv_matrixinvoicenumbers.dealerid= '".$dealer."'";
			$mstatus = " OR inv_matrixinvoicenumbers.status = '".$status."'";
			$mseries = " OR inv_matrixinvoicenumbers.category = '".$series."'";
			$mcancel = " OR inv_matrixinvoicenumbers.status <> 'CANCELLED'";
			$mbranch =" OR inv_matrixinvoicenumbers.branchid = '".$branch."'";
			$mbusiness = " or inv_matrixinvoicenumbers.businessname LIKE '%".$textfield."%'";
			$mcustomer = " or right(inv_matrixinvoicenumbers.customerid,5) like '%".$customerid."%'";
			$minvoice = "  or inv_matrixinvoicenumbers.invoiceno LIKE '%".$textfield."%'";
		}	
		
		// if(($itemlist != '') && ($productslist != '') && $matrixlist!= '')
		// 	$finaldlrlistarray = ' OR ('.$finaldlrproductlist.' '.'OR'.' '.$finaldlritemlist.'OR'.' '.$finalmatrixlist.')';
		// elseif(($itemlist != '') && ($productslist != ''))
		// 	$finaldlrlistarray = ' OR ('.$finaldlrproductlist.' '.'OR'.' '.$finaldlritemlist.')';
		// elseif(($matrixlist != '') && ($productslist != ''))
		// 	$finaldlrlistarray = ' OR ('.$finaldlrproductlist.' '.'OR'.' '.$finalmatrixlist.')';
		// elseif($productslist == '')
		// 	$finaldlrlistarray = ' OR ('.$finaldlritemlist.')';
		// elseif($itemlist == '')
		// 	$finaldlrlistarray = ' OR ('.$finaldlrproductlist.')';
		// elseif($matrixlist!= '')
		// 	$finaldlrlistarray = ' OR ('.$finalmatrixlist.')';
		
		$finalbotharaay = $finallistarray;
		
		
		$state_typepiece = ($state == "")?(""):(" AND inv_mas_district.statecode = '".$state."' ");
		$reconciletype_piece = ($reconciletype == "")?(""):(" AND inv_mas_receipt.reconsilation = '".$reconciletype."' ");
		$generatedpiece = ($generatedby == "")?(""):(" AND inv_mas_receipt.createdby = '".$generatedbysplit[0]."'");
		$paymentmodepiece = ($paymentmode == "")?(""):(" and inv_mas_receipt.paymentmode = '".$paymentmode."' ");
		$receiptstatuspiece = ($receiptstatus == "")?(""):(" and inv_mas_receipt.restatus = '".$receiptstatus."' ");
		$datepiece = ($alltimecheck == 'yes')?(""):(" AND (inv_mas_receipt.receiptdate BETWEEN '".$fromdate."' and '".$todate."') ");
		
		$regionpiece = ($region == "")?(""):(" AND (inv_invoicenumbers.regionid = '".$region."' OR inv_dealer_invoicenumbers.regionid = '".$region."'".$mregion.") ");
		$regiononlinepiece = ($region == "")?(""):(" AND inv_mas_region.slno = '".$region."' ");

		$district_typepiece = ($district == "")?(""):(" AND inv_mas_customer.district = '".$district."' ");
		
		$dealer_typepiece = ($dealer == "")?(""):(" AND (inv_invoicenumbers.dealerid = '".$dealer."' OR inv_dealer_invoicenumbers.dealerid = '".$dealer."'".$mdealer.") ");
		$dealer_oninetypepiece = ($dealer == "")?(""):(" AND inv_mas_receipt.customerreference = '".$dealer."' ");

		$statuspiece = ($status == "")?(""):(" AND (inv_invoicenumbers.status = '".$status."' OR inv_dealer_invoicenumbers.status = '".$status."'".$mstatus.") ");
		$seriespiece = ($series == "")?(""):(" AND (inv_invoicenumbers.category = '".$series."' OR inv_dealer_invoicenumbers.category = '".$series."'".$mseries.") ");
		$cancelledpiece = ($cancelledinvoice == "yes")?(" AND (inv_invoicenumbers.status <> 'CANCELLED' OR inv_dealer_invoicenumbers.status <> 'CANCELLED'".$mcancel.") "):("");
		
		$branchpiece = ($branch == "")?(""):(" AND (inv_invoicenumbers.branchid = '".$branch."' OR inv_dealer_invoicenumbers.branchid = '".$branch."'".$mbranch.") ");	
		$branchonlinepiece = ($branch == "")?(""):(" AND inv_mas_branch.slno = '".$branch."' ");
		
		if($generatedby == "")
		{
			$modulepiece = "";
		}
		else
		{
			$modulepiece = ($generatedbysplit[1] == "[U]")?("user_module"):("dealer_module");
		}

		$receiptslno = array();
		$querycase = "select  inv_mas_receipt.slno as receiptslno 
		from inv_mas_receipt 
		left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_receipt.customerreference
		left join inv_mas_district on inv_mas_district.districtcode = inv_mas_dealer.district
		left join inv_mas_branch on inv_mas_district.branchid = inv_mas_branch.slno
		left join inv_mas_region on inv_mas_district.region = inv_mas_region.slno
		where inv_mas_receipt.slno <> '123456789' and (inv_mas_receipt.dealerinvoiceno is NULL and inv_mas_receipt.matrixinvoiceno is NULL and inv_mas_receipt.invoiceno is NULL and inv_mas_receipt.module = 'Online')".$datepiece.$dealer_oninetypepiece.$regiononlinepiece.$branchonlinepiece.$district_typepiece.$state_typepiece.$generatedpiece.$paymentmodepiece.$receiptstatuspiece.$reconciletype_piece."  ORDER BY inv_mas_receipt.slno;";
		$receiptresult = runmysqlquery($querycase);
		$receiptcount = mysqli_num_rows($receiptresult);
		if($receiptcount > 0 && $status == "" && $seriespiece == "" && $cancelledinvoice == "yes" && $databasefield!= 'invoiceno')
		{
			while($fetchresult = mysqli_fetch_array($receiptresult))
			{
				$receiptslno[] = $fetchresult['receiptslno'];
			}
			$receiptslno = implode(',', $receiptslno);
			$creditamtdetails = " or inv_mas_receipt.slno in($receiptslno) ";
		}

		$resultcount = "select count(distinct inv_mas_receipt.slno) as count from inv_mas_receipt 
		left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
		left join inv_dealer_invoicenumbers on inv_dealer_invoicenumbers.slno = inv_mas_receipt.dealerinvoiceno
		left join inv_matrixinvoicenumbers on inv_matrixinvoicenumbers.slno = inv_mas_receipt.matrixinvoiceno
		left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealer_invoicenumbers.`dealerreference`
		left join inv_mas_customer as cust on cust.slno = right(inv_invoicenumbers.customerid,5)
		left join inv_mas_customer as mcust on mcust.slno = right(inv_matrixinvoicenumbers.customerid,5)
		left join inv_mas_district as dist on dist.districtcode = cust.district
		left join inv_mas_district as ddist on ddist.districtcode = inv_mas_dealer.district
		left join inv_mas_district as mdist on mdist.districtcode = mcust.district
		where inv_mas_receipt.slno <> '123456789'  ".$datepiece.$finalbotharaay.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece.$creditamtdetails." order by inv_mas_receipt.slno;";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];
		$totalreceipts = $fetchresultcount;

		$querycase = "select distinct inv_mas_receipt.slno,inv_mas_receipt.dealerinvoiceno,inv_mas_receipt.dealerinvoiceno,inv_mas_receipt.customerreference as dealerid,inv_mas_receipt.receiptremarks,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,
		inv_invoicenumbers.businessname,inv_dealer_invoicenumbers.businessname as dlrbusinessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,
		inv_invoicenumbers.invoiceno,inv_dealer_invoicenumbers.invoiceno as dlrinvoiceno,inv_invoicenumbers.dealername,inv_dealer_invoicenumbers.dealername as dlrname,inv_mas_receipt.createdby as createdbyid,
		inv_invoicenumbers.status,inv_dealer_invoicenumbers.status as dlrstatus, inv_mas_receipt.reconsilation,inv_mas_receipt.module,inv_matrixinvoicenumbers.customerid as mcustid,inv_matrixinvoicenumbers.businessname as mbusinessname,
		inv_matrixinvoicenumbers.invoiceno as minvoiceno,inv_matrixinvoicenumbers.dealername as mdealername,inv_matrixinvoicenumbers.status as mstatus
		from inv_mas_receipt 
		left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
		left join inv_dealer_invoicenumbers on inv_dealer_invoicenumbers.slno = inv_mas_receipt.dealerinvoiceno
		left join inv_matrixinvoicenumbers on inv_matrixinvoicenumbers.slno = inv_mas_receipt.matrixinvoiceno
		left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealer_invoicenumbers.`dealerreference`
		left join inv_mas_customer as cust on cust.slno = right(inv_invoicenumbers.customerid,5)
		left join inv_mas_customer as mcust on mcust.slno = right(inv_matrixinvoicenumbers.customerid,5)
		left join inv_mas_district as dist on dist.districtcode = cust.district
		left join inv_mas_district as ddist on ddist.districtcode = inv_mas_dealer.district
		left join inv_mas_district as mdist on mdist.districtcode = mcust.district";
		
		switch($databasefield)
		{
			case "slno":
				$cusid = strlen($textfield);
				if($cusid == 17)
					$customerid = substr($textfield,12);
				else if($cusid == 20)
					$customerid = substr($textfield,15);
				else
					$customerid = $textfield;
				// $finalprdaraay	= ' AND '.$finallistarray;
				$finalprdaraay	= $finallistarray;
				$query = $querycase." where (right(inv_invoicenumbers.customerid,5) like '%".$customerid."%'".$mcustomer.")  ".$datepiece.$finalprdaraay.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece."  order by inv_mas_receipt.slno ";
				break;
		
			case "invoiceno":
				$query = $querycase." where (inv_invoicenumbers.invoiceno LIKE '%".$textfield."%' or inv_dealer_invoicenumbers.invoiceno LIKE '%".$textfield."%'".$minvoice.") ".$datepiece.$finalbotharaay.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece."  order by inv_mas_receipt.slno ";
				break;
			case "receiptno":
				$query = $querycase." where inv_mas_receipt.slno LIKE '%".$textfield."%' ".$datepiece.$finalbotharaay.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece.$creditamtdetails."  order by inv_mas_receipt.slno ";
				break;
			case "chequeno":
				$query = $querycase." where inv_mas_receipt.chequeno LIKE '%".$textfield."%' ".$datepiece.$finalbotharaay.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece.$creditamtdetails."  order by inv_mas_receipt.slno ";
				break;
			case "chequedate":
				$query = $querycase." where inv_mas_receipt.chequedate LIKE '%".changedateformat($textfield)."%'  ".$datepiece.$finalbotharaay.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece.$creditamtdetails."  order by inv_mas_receipt.slno ";
				break;
			case "depositdate":
				$query = $querycase." where inv_mas_receipt.depositdate LIKE '%".changedateformat($textfield)."%'  ".$datepiece.$finalbotharaay.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece.$creditamtdetails."  order by inv_mas_receipt.slno ";
				break;
			case "drawnon":
				$query = $querycase." where inv_mas_receipt.drawnon LIKE '%".$textfield."%' ".$datepiece.$finalbotharaay.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece.$creditamtdetails."  order by inv_mas_receipt.slno ";
				break;
				
			case "paymentamt":
				$query = $querycase." where inv_mas_receipt.receiptamount LIKE '%".$textfield."%'  ".$datepiece.$finalbotharaay.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece.$creditamtdetails."  order by inv_mas_receipt.slno ";
				break;
			
			default:
				 $query = $querycase." where (inv_invoicenumbers.businessname LIKE '%".$textfield."%' OR inv_dealer_invoicenumbers.businessname LIKE '%".$textfield."%'".$mbusiness.")  ".$datepiece.$finalbotharaay.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece.$creditamtdetails."  ORDER BY inv_mas_receipt.slno ";
				break;
		}
		//echo $query; exit;
		$result = runmysqlquery($query);
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
		
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','78','".date('Y-m-d').' '.date('H:i:s')."','view_receiptregister')";
		$eventresult = runmysqlquery($eventquery);
		while($fetchres = mysqli_fetch_array($result))
		{
			$totalamount += $fetchres['receiptamount'];
		}
		$fetchresultcount = mysqli_num_rows($result);
		$addlimit = " LIMIT ".$startlimit.",".$limit.";";
		$query1 = $query.$addlimit;
		$result1 = runmysqlquery($query1);
		$grid = '';
		
		
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Receipt No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Receipt Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Status</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Receipt Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Mode</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sales Person</td><td nowrap = "nowrap" class="td-border-grid" align="left">Prepared By</td><td nowrap = "nowrap" class="td-border-grid" align="left">Reconcile Type</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Remarks: Payment details</td></tr>';
		}
		
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result1))
		{
			$i_n++;
			$slnocount++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
				if($fetch['reconsilation'] == 'notseen')
					$reconciletype = 'NOT SEEN';
				elseif($fetch['reconsilation'] == 'matched')
					$reconciletype = 'MATCHED';
				elseif($fetch['reconsilation'] == 'unmatched')
					$reconciletype = 'UNMATCHED';
				if($modulepiece == "")
				{
					if($fetch['module'] == 'user_module'|| $fetch['module'] == 'Online'|| $fetch['module'] == 'customer_module')
					{
						$queryfetch = "SELECT inv_mas_users.fullname as createdby from  inv_mas_users where slno = 
						'".$fetch['createdbyid']."';";
						$resultvalue = runmysqlqueryfetch($queryfetch);
					}
					else
					{
						$queryfetch = "SELECT inv_mas_dealer.businessname as createdby from  inv_mas_dealer where slno 
						= '".$fetch['createdbyid']."';";
						$resultvalue = runmysqlqueryfetch($queryfetch);
						
					}
				}
				elseif($modulepiece == "user_module")
				{
					$queryfetch = "SELECT inv_mas_users.fullname as createdby from  inv_mas_users where slno = '".$generatedbysplit[0]."';";
					$resultvalue = runmysqlqueryfetch($queryfetch);
				}
				elseif($modulepiece == "dealer_module")
				{
					$queryfetch = "SELECT inv_mas_dealer.businessname as createdby from  inv_mas_dealer where slno = '".$generatedbysplit[0]."';";
					$resultvalue = runmysqlqueryfetch($queryfetch);
				}
				if($fetch['dealerinvoiceno']!="")
				{
					$businessname = trim($fetch['dlrbusinessname']);
					$status = trim($fetch['dlrstatus']);
					$invoiceno = trim($fetch['dlrinvoiceno']);
					$dealername = trim($fetch['dlrname']);
				}
				else if($fetch['businessname']!=""){
					$businessname = trim($fetch['businessname']);
					$status = trim($fetch['status']);
					$invoiceno = trim($fetch['invoiceno']);
					$dealername = trim($fetch['dealername']);
				}
				else if($fetch['minvoiceno']!=""){
					$businessname = trim($fetch['mbusinessname']);
					$status = trim($fetch['mstatus']);
					$invoiceno = trim($fetch['minvoiceno']);
					$dealername = trim($fetch['mdealername']);
				}
				else
				{
					$query1 = "select businessname from inv_mas_dealer where slno = ".$fetch['dealerid'];
					$fetch1 = runmysqlqueryfetch($query1);
					$businessname = $fetch1['businessname'];
				}


				$grid .= '<tr bgcolor='.$color.' class="gridrow1" align="left">';
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['slno']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat(trim($fetch['receiptdate']))."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$invoiceno."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$status."</td>";				
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['customerid'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$businessname."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['receiptamount']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".getpaymentmode($fetch['paymentmode'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$dealername."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($resultvalue['createdby'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$reconciletype."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['receiptremarks']."</td>";
				$grid .= "</tr>";
		}
	
	
	$grid .= "</table>";
	$fetchcount = mysqli_num_rows($result);
	if($slnocount >= $fetchresultcount)
		
		$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
	else
		$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
		

		echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$totalreceipts.'^'.formatnumber($totalamount).'^'.convert_number($totalamount.'^'.$query);	
	}
	break;
	
	case 'searchsettings':
		{
			$textfield = $_POST['textfield'];
			$subselection = $_POST['subselection'];
			$cancelledinvoice = $_POST['cancelledinvoice'];
			$selection = $_POST['selection'];
			$productslist = $_POST['productslist'];
			$itemlist = $_POST['itemlist'];
			$selectiontype = $_POST['selectiontype'];
			$category = $_POST['category'];
			$categorysplit = explode('/',$category);
			$categoryvalue = substr($categorysplit[2],0,-4);
			
			$selectioncombine = $textfield.'$@$'.$subselection.'$@$'.$cancelledinvoice.'$@$'.$selection.'$@$'.$productslist.'$@$'.$itemlist;
			$query11 = "Select selectiontype from inv_selectionsettings where userid = '".$userid."' and module = 'user_module' and category = '".$categoryvalue."'";
			$result22 = runmysqlquery($query11);
			if(mysqli_num_rows($result22) > 0)
			{
				while($fetch22 = mysqli_fetch_array($result22))
				{
					$selectiontypearray[] = strtolower($fetch22['selectiontype']);
				}
				if(in_array(strtolower($selectiontype),$selectiontypearray,true))
				{ 
					echo("2^"."Enter a different settings name, the name already exists.");
					exit;
	
				}
			}
			$query = "Insert into inv_selectionsettings(category,selection,
userid,module,selectiontype,createdip,createddate,lastmodifieddate,lastmodifiedby,lastmodifiedip) values 
('".$categoryvalue."','".$selectioncombine."','".$userid."','user_module','".$selectiontype."',
'".$_SERVER['REMOTE_ADDR']."','".date('Y-m-d').' '.date('H:i:s')."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."');";
			$result = runmysqlquery($query);
			
			echo('1^'.'Record Inserted Successfully.'.'^'.$userid);
		}
		break;
		
		case 'loadselection':
		{
			$lastslno = $_POST['userid'];
			$category = $_POST['category'];
			$categorysplit = explode('/',$category);
			$categoryvalue = substr($categorysplit[2],0,-4);
			
			$query = "SELECT * from inv_selectionsettings where userid = '".$lastslno."' and category = '".$categoryvalue."' and module = 'user_module'";
			$result = runmysqlquery($query);
			if(mysqli_num_rows($result) == 0)
			{
				$grid .='<select name="loadselection" class="swiftselect" id="loadselection" style="width:100px;">';
				$grid .= '<option value="default">DEFAULT</option>';
			}
			else
			{
				$grid .='<select name="loadselection" class="swiftselect" id="loadselection" style="width:100px;">';
				$grid .= '<option value="default">DEFAULT</option>';
				while($fetch = mysqli_fetch_array($result))
				{
					$grid .=	'<option value="'.$fetch['slno'].'">'.$fetch['selectiontype'].'</option>';
				}
				$grid.='</select>';
			}
			
			echo('1^'.$grid);
		}
		break;
		
		case 'displayselection':
		{
			$lastslno = $_POST['lastslno'];
			
			$query = "SELECT * from inv_selectionsettings where slno = '".$lastslno."' and module = 'user_module'";
			$fetch = runmysqlqueryfetch($query);
			
			echo('1*^*'.$fetch['selection']);
		}
		break;
		
		case 'deleteselection':
		{
			$lastslno = $_POST['lastslno'];
			
			$query = "DELETE  from  inv_selectionsettings  where slno = '".$lastslno."' and module = 'user_module'";
			$fetch = runmysqlquery($query);
			
			echo('1^'.'Record Deleted Successfully'.'^'.$userid);
		}
		break;
	
}
?>