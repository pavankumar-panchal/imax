<?
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');
include('../inc/checksession.php');


if(imaxgetcookie('userid') <> '') 
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
	case 'invoicedetails':
	{
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$currentdate = date('Y-m-d');
		
		$resultcount = "SELECT count(distinct inv_invoicenumbers.slno) as  count from inv_invoicenumbers  left join (select sum(receiptamount) as receiptamount, invoiceno, status from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid where left(inv_invoicenumbers.createddate,10) <= '".$currentdate."' and DATEDIFF('".$currentdate."',left(inv_invoicenumbers.createddate,10)) >= '0' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 and inv_invoicenumbers.status <> 'CANCELLED' ORDER BY inv_invoicenumbers.createddate desc ;";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];
		
		$invoiceresult = "SELECT distinct inv_invoicenumbers.slno, sum(inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount  from inv_invoicenumbers  left join (select sum(receiptamount) as receiptamount, invoiceno, status from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid where left(inv_invoicenumbers.createddate,10) <= '".$currentdate."' and DATEDIFF('".$currentdate."',left(inv_invoicenumbers.createddate,10)) >= '0' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 and inv_invoicenumbers.status <> 'CANCELLED'  group by inv_invoicenumbers.slno with rollup limit ".($fetchresultcount).",1 ";
		$fetchresult1 = runmysqlquery($invoiceresult);
		if(mysqli_num_rows($fetchresult1) > 0)
		{
			$fetchresult = runmysqlqueryfetch($invoiceresult);
			$totalinvoices = $fetchresultcount;
			$totalamount = $fetchresult['outstandingamount'];
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
		$query = "SELECT distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.contactperson ,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_dealer.businessname as dealername,inv_invoicenumbers.netamount,ifnull(inv_mas_receipt.receiptamount,0) as receiptamount, (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount , DATEDIFF('".$currentdate."',left(inv_invoicenumbers.createddate,10)) as age,inv_invoicenumbers.status,outstandingremarks1,outstandingremarks2 from inv_invoicenumbers  left join (select sum(receiptamount) as receiptamount, invoiceno, status  from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid where left(inv_invoicenumbers.createddate,10) <= '".$currentdate."' and DATEDIFF('".$currentdate."',left(inv_invoicenumbers.createddate,10))>= '0' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 and inv_invoicenumbers.status <> 'CANCELLED'  ORDER BY inv_invoicenumbers.createddate desc LIMIT ".$startlimit.",".$limit." ; ";
		$result = runmysqlquery($query);
		//echo($query);exit;
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Status</td><td nowrap = "nowrap" class="td-border-grid" align="left">Outstanding Email</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Contact Person Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sales Person</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Received Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Outstanding Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Age (Days)</td><td nowrap = "nowrap" class="td-border-grid" align="left">Remarks 1</td><td nowrap = "nowrap" class="td-border-grid" align="left">Remarks 2</td></tr>';
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
			if($fetch['outstandingamount'] < 0)
				$outstandingamount = '0';
			else
				$outstandingamount = $fetch['outstandingamount'];
			if($fetch['age'] < 0)
				$age = '0';
			else
				$age = $fetch['age'];
				
			$outstandingremarks1 = ($fetch['outstandingremarks1'] == '')? 'NONE' : $fetch['outstandingremarks1'];
			$outstandingremarks2 = ($fetch['outstandingremarks2'] == '')? 'NONE' : $fetch['outstandingremarks2'];
			if($outstandingremarks1 == 'NONE')
			{
				$remarks1 = 'NONE';
			}
			else
			{
				$remarks1 = $outstandingremarks1;
			}
			if($outstandingremarks2 == 'NONE')
			{
				$remarks2 ='NONE';
			}
			else
			{
				$remarks2 = $outstandingremarks2;
			}
			$resultantremarks1 = ($remarks1 == 'NONE')? '<span style="text-decoration:underline">NONE</span>' : gridtrim10($remarks1);
			$resultantremarks2 = ($remarks2 == 'NONE')? '<span style="text-decoration:underline">NONE</span>' : gridtrim10($remarks2);
			
			$grid .= '<tr bgcolor='.$color.' class="gridrow1" align="left">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat(trim($fetch['createddate']))."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['invoiceno']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['status']."</td>";
			$grid .= '<td nowrap="nowrap" class="td-border-grid" align="left"><div><a onclick="sendoutstandingemail(\''.$fetch['slno'].'\');" class="resendtext" style = "cursor:pointer" title = "Send Outstanding Email">Send</a></div></td>';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['customerid'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim(trim($fetch['businessname']))."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['contactperson']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['dealername']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['netamount'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['receiptamount'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".($outstandingamount)."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($age)."</td>";
			$grid .= '<td nowrap="nowrap" class="td-border-grid" align="left"><div><input type="hidden" name="hiddenremarks1'.$slnocount.'" id="hiddenremarks1'.$slnocount.'" value = "'.$outstandingremarks1.'"/><a class = "remarksclass" onclick="updateremarks(\''.$fetch['slno'].'\',\'remarks1\',\''.$slnocount.'\',\'default\');"  title = "Add or Update Outstanding Remarks">'.$resultantremarks1.'</a></div></td>';
			$grid .= '<td nowrap="nowrap" class="td-border-grid" align="left"><div>  <input type="hidden" name="hiddenremarks2'.$slnocount.'" id="hiddenremarks2'.$slnocount.'" value = "'.$outstandingremarks2.'"/><a class = "remarksclass" onclick="updateremarks(\''.$fetch['slno'].'\',\'remarks2\',\''.$slnocount.'\',\'default\');"  title = "Add or Update Outstanding Remarks">'.$resultantremarks2.'</a></div></td>';
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoreinvoicedetails(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoreinvoicedetails(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$totalinvoices.'^'.formatnumber($totalamount).'^'.convert_number($totalamount);	
	}
	break;
	case 'searchinvoices':
	{
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$sortby = $_POST['sortby'];
		$sortby1 = $_POST['sortby1'];
		$aged = $_POST['aged'];
		$fromdate = changedateformat($_POST['fromdate']);
		
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
		$cancelledinvoice = $_POST['cancelledinvoice'];
		$receiptstatus = $_POST['receiptstatus'];
		$totalreceipts = '0';
		$totalamount = '0';
		$itemlist = $_POST['itemlist'];
		$itemlistsplit = explode(',',$itemlist);
		$itemlistsplitcount = count($itemlistsplit);
		
		//check the status of the cancelled invoice
		$cancelledinvoicepiece = ($cancelledinvoice == "no")?(""):("AND inv_invoicenumbers.status <> 'CANCELLED'");
		
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
		}
		
		if(($itemlist != '') && ($productslist != ''))
			$finallistarray = ' AND ('.$finalproductlist.' '.'OR'.' '.$finalitemlist.')';
		elseif($productslist == '')
			$finallistarray = ' AND ('.$finalitemlist.')';
		elseif($itemlist == '')
			$finallistarray = ' AND ('.$finalproductlist.')';
			
		$modulepiece = ($generatedbysplit[1] == "[U]")?("user_module"):("dealer_module");
		$regionpiece = ($region == "")?(""):(" AND inv_invoicenumbers.regionid = '".$region."' ");
		
		$state_typepiece = ($state == "")?(""):(" AND inv_mas_district.statecode = '".$state."' ");
		$district_typepiece = ($district == "")?(""):(" AND inv_mas_customer.district = '".$district."' ");
		$dealer_typepiece = ($dealer == "")?(""):(" AND inv_invoicenumbers.dealerid = '".$dealer."' ");
		$branchpiece = ($branch == "")?(""):(" AND inv_invoicenumbers.branchid = '".$branch."' ");
		$generatedpiece = ($generatedby == "")?(""):(" AND inv_invoicenumbers.createdbyid = '".$generatedbysplit[0]."' and inv_invoicenumbers.module = '".$modulepiece."'");
		$statuspiece = ($status == "")?(""):(" AND inv_invoicenumbers.status = '".$status."' ");
		$seriespiece = ($series == "")?(""):(" AND inv_invoicenumbers.category = '".$series."' ");
		$receiptstatuspiece = ($receiptstatus == "")?(""):(" and inv_mas_receipt.status = '".$receiptstatus."' ");
		
		$resultcount = "SELECT count(distinct inv_invoicenumbers.slno) as  count from inv_invoicenumbers  
left join (select sum(receiptamount) as receiptamount, invoiceno,status from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0   ".$finallistarray.$cancelledinvoicepiece.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$receiptstatuspiece." ORDER BY  inv_invoicenumbers.createddate desc ;";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];
		
		//echo($resultcount);exit;
		/*$invoiceresult = "SELECT inv_invoicenumbers.slno ,sum(inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount from inv_invoicenumbers  
left join (select sum(receiptamount) as receiptamount, invoiceno from inv_mas_receipt group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 AND (".$finalproductlist.") ".$cancelledinvoicepiece.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece." group by inv_invoicenumbers.slno with rollup   limit ".($fetchresultcount).",1 ";
		$fetchresult1 = runmysqlquery($invoiceresult);
		if(mysqli_num_rows($fetchresult1) > 0)
		{
			$fetchresult = runmysqlqueryfetch($invoiceresult);
			$totalinvoices = $fetchresultcount;
			$totalamount = $fetchresult['outstandingamount'];
		}
		else
		{
			$totalreceipts = '0';
			$totalamount = '0';
		}*/
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
					
				$query = "SELECT distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno, inv_invoicenumbers.contactperson, inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_dealer.businessname as dealername,inv_invoicenumbers.netamount,ifnull(inv_mas_receipt.receiptamount,0) as receiptamount, (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount , DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) as age,inv_invoicenumbers.status,outstandingremarks1,outstandingremarks2 from inv_invoicenumbers  left join (select sum(receiptamount) as receiptamount, invoiceno,status from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno 
left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 AND right(inv_invoicenumbers.customerid,5) like '%".$customerid."%' ".$finallistarray.$cancelledinvoicepiece.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$receiptstatuspiece."   ORDER BY ".$sortby." ".$sortby1.", inv_invoicenumbers.createddate desc";
				break;
		
			case "contactperson": 
				$query = "SELECT distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.contactperson, inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_dealer.businessname as dealername,inv_invoicenumbers.netamount,ifnull(inv_mas_receipt.receiptamount,0) as receiptamount, (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount , DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) as age,inv_invoicenumbers.status,outstandingremarks1,outstandingremarks2 from inv_invoicenumbers  left join (select sum(receiptamount) as receiptamount, invoiceno,status from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno 
left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 AND inv_invoicenumbers.contactperson LIKE '%".$textfield."%' ".$finallistarray.$cancelledinvoicepiece.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$receiptstatuspiece." ORDER BY ".$sortby." ".$sortby1.", inv_invoicenumbers.createddate desc";
				break;
			case "phone":
				$query = "SELECT distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.contactperson, inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_dealer.businessname as dealername,inv_invoicenumbers.netamount,ifnull(inv_mas_receipt.receiptamount,0) as receiptamount, (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount , DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) as age,inv_invoicenumbers.status,outstandingremarks1,outstandingremarks2 from inv_invoicenumbers  left join (select sum(receiptamount) as receiptamount, invoiceno,status from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno 
left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0  AND inv_invoicenumbers.phone LIKE '%".$textfield."%' OR inv_invoicenumbers.cell LIKE '%".$textfield."%' ".$finallistarray.$cancelledinvoicepiece.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$receiptstatuspiece."  ORDER BY ".$sortby." ".$sortby1.", inv_invoicenumbers.createddate desc";
				break;
			case "place":
				$query = "SELECT distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.contactperson, inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_dealer.businessname as dealername,inv_invoicenumbers.netamount,ifnull(inv_mas_receipt.receiptamount,0) as receiptamount, (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount , DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) as age,inv_invoicenumbers.status,outstandingremarks1,outstandingremarks2 from inv_invoicenumbers  left join (select sum(receiptamount) as receiptamount, invoiceno,status from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno 
left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0  AND inv_invoicenumbers.place LIKE '%".$textfield."%' ".$finallistarray.$cancelledinvoicepiece.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$receiptstatuspiece."  ORDER BY ".$sortby." ".$sortby1.", inv_invoicenumbers.createddate desc";
				break;
			case "emailid":
				$query = "SELECT distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.contactperson, inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_dealer.businessname as dealername,inv_invoicenumbers.netamount,ifnull(inv_mas_receipt.receiptamount,0) as receiptamount, (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount , DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) as age,inv_invoicenumbers.status,outstandingremarks1,outstandingremarks2 from inv_invoicenumbers  left join (select sum(receiptamount) as receiptamount, invoiceno,status from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno 
left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 AND inv_invoicenumbers.emailid LIKE '%".$textfield."%'  ".$finallistarray.$cancelledinvoicepiece.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$receiptstatuspiece."ORDER BY ".$sortby." ".$sortby1.", inv_invoicenumbers.createddate desc";
				break;
				case "cardid":
					$query = "SELECT distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.contactperson, inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_dealer.businessname as dealername,inv_invoicenumbers.netamount,ifnull(inv_mas_receipt.receiptamount,0) as receiptamount, (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount , DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) as age,inv_invoicenumbers.status,outstandingremarks1,outstandingremarks2 from inv_invoicenumbers  left join (select sum(receiptamount) as receiptamount, invoiceno,status  from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno 
left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno
LEFT JOIN inv_mas_scratchcard ON inv_mas_scratchcard.cardid = inv_dealercard.cardid
where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 and inv_mas_scratchcard.cardid LIKE '%".$textfield."%'  ".$finallistarray.$cancelledinvoicepiece.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$receiptstatuspiece."  ORDER BY ".$sortby." ".$sortby1.", inv_invoicenumbers.createddate desc";
					break;
			case "scratchnumber":
				$query = "SELECT distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.contactperson, inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_dealer.businessname as dealername,inv_invoicenumbers.netamount,ifnull(inv_mas_receipt.receiptamount,0) as receiptamount, (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount , DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) as age,inv_invoicenumbers.status,outstandingremarks1,outstandingremarks2 from inv_invoicenumbers  left join (select sum(receiptamount) as receiptamount, invoiceno,status from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno 
left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno
LEFT JOIN inv_mas_scratchcard ON inv_mas_scratchcard.cardid = inv_dealercard.cardid
where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 and inv_mas_scratchcard.scratchnumber LIKE '%".$textfield."%'  ".$finallistarray.$cancelledinvoicepiece.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$receiptstatuspiece."  ORDER BY ".$sortby." ".$sortby1.", inv_invoicenumbers.createddate desc";
				break;
			
			default:
				$query = "SELECT distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.contactperson, inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_dealer.businessname as dealername,inv_invoicenumbers.netamount,ifnull(inv_mas_receipt.receiptamount,0) as receiptamount, (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount , DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) as age,inv_invoicenumbers.status,outstandingremarks1,outstandingremarks2 from inv_invoicenumbers  left join (select sum(receiptamount) as receiptamount, invoiceno,status from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno 
left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 AND inv_invoicenumbers.businessname LIKE '%".$textfield."%' ".$finallistarray.$cancelledinvoicepiece.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$receiptstatuspiece." ORDER BY ".$sortby." ".$sortby1.", inv_invoicenumbers.createddate desc";
				break;
		} 
		echo($query);exit;
		$result = runmysqlquery($query);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','80','".date('Y-m-d').' '.date('H:i:s')."','view_outstandingregister')";
		$eventresult = runmysqlquery($eventquery);
		
		while($fetchres = mysqli_fetch_array($result))
		{
			$totalamount += $fetchres['outstandingamount'];
		}
		
		$fetchresultcount = mysqli_num_rows($result);
		$totalinvoices = $fetchresultcount;
		$addlimit = " LIMIT ".$startlimit.",".$limit.";";
		$query1 = $query.$addlimit; //echo($query);exit;
		$result1 = runmysqlquery($query1);
		$grid = '';
		
		
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Status</td><td nowrap = "nowrap" class="td-border-grid" align="left">Outstanding Email</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Contact Person Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sales Person</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Received Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Outstanding Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Age (Days)</td><td nowrap = "nowrap" class="td-border-grid" align="left">Remarks 1</td><td nowrap = "nowrap" class="td-border-grid" align="left">Remarks 2</td></tr>';
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
			if($fetch['outstandingamount'] < 0)
				$outstandingamount = '0';
			else
				$outstandingamount = $fetch['outstandingamount'];
			if($fetch['age'] < 0)
				$age = '0';
			else
				$age = $fetch['age'];
				
			$outstandingremarks1 = ($fetch['outstandingremarks1'] == '')? 'NONE' : $fetch['outstandingremarks1'];
			$outstandingremarks2 = ($fetch['outstandingremarks2'] == '')? 'NONE' : $fetch['outstandingremarks2'];
			if($outstandingremarks1 == 'NONE')
			{
				$remarks1 = 'NONE';
			}
			else
			{
				$remarks1 = $outstandingremarks1;
			}
			if($outstandingremarks2 == 'NONE')
			{
				$remarks2 ='NONE';
			}
			else
			{
				$remarks2 = $outstandingremarks2;
			}
			$resultantremarks1 = ($remarks1 == 'NONE')? '<span style="text-decoration:underline">NONE</span>' : gridtrim10($remarks1);
			$resultantremarks2 = ($remarks2 == 'NONE')? '<span style="text-decoration:underline">NONE</span>' : gridtrim10($remarks2);
			
			$grid .= '<tr bgcolor='.$color.' class="gridrow1" align="left">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat(trim($fetch['createddate']))."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['invoiceno']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['status']."</td>";
			$grid .= '<td nowrap="nowrap" class="td-border-grid" align="left"><div><a onclick="sendoutstandingemail(\''.$fetch['slno'].'\');" class="resendtext" style = "cursor:pointer" title = "Send Outstanding Email">Send</a></div></td>';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['customerid'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['businessname'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['contactperson']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['dealername']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['netamount'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['receiptamount'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($outstandingamount)."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($age)."</td>";
			$grid .= '<td nowrap="nowrap" class="td-border-grid" align="left"><div><input type="hidden" name="hiddensearchremarks2'.$slnocount.'" id="hiddensearchremarks1'.$slnocount.'" value = "'.$outstandingremarks1.'"/><a class = "remarksclass" onclick="updateremarks(\''.$fetch['slno'].'\',\'remarks1\',\''.$slnocount.'\',\'search\');"  title = "Add or Update Outstanding Remarks">'.$resultantremarks1.'</a></div></td>';
			$grid .= '<td nowrap="nowrap" class="td-border-grid" align="left"><div>  <input type="hidden" name="hiddensearchremarks2'.$slnocount.'" id="hiddensearchremarks2'.$slnocount.'" value = "'.$outstandingremarks2.'"/><a class = "remarksclass" onclick="updateremarks(\''.$fetch['slno'].'\',\'remarks2\',\''.$slnocount.'\',\'search\');"  title = "Add or Update Outstanding Remarks">'.$resultantremarks2.'</a></div></td>';
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';

			echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$totalinvoices.'^'.formatnumber($totalamount).'^'.convert_number($totalamount);		
	}
	break;
	
	case 'sendoutstandingmail':
	{
		$invoiceslno = $_POST['invoiceno'];
		$query = "SELECT distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.netamount,ifnull(inv_mas_receipt.receiptamount,0) as receiptamount, (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount,inv_invoicenumbers.emailid,inv_invoicenumbers.contactperson,inv_mas_dealer.emailid as dealeremailid,inv_invoicenumbers.place from inv_invoicenumbers left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid left join (select sum(receiptamount) as receiptamount, invoiceno, status from inv_mas_receipt where inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno where (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0  and inv_invoicenumbers.status <> 'CANCELLED'  and inv_invoicenumbers.slno = '".$invoiceslno."'";
		$fetch = runmysqlqueryfetch($query);
		$businessname = $fetch['businessname'];
		$contactperson = $fetch['contactperson'];
		$date = changedateformat($fetch['createddate']);
		$place = $fetch['place'];
		$totalamount = $fetch['netamount'];
		$receiptamount = $fetch['receiptamount'];
		$outstandingamount = $fetch['outstandingamount'];
		$customerid = $fetch['customerid'];
		$invoiceno = $fetch['invoiceno'];
		$emailid = $fetch['emailid'];
		$dealeremailid = $fetch['dealeremailid'];
		$fromname = "Relyon";
		$fromemail = "imax@relyon.co.in";
		require_once("../inc/RSLMAIL_MAIL.php");
		$msg = file_get_contents("../mailcontents/outstanding-mail.htm");
		$textmsg = file_get_contents("../mailcontents/outstanding-mail.txt");
		$slno = substr($customerid,15,20);
	//	$emailid = 'archana.ab@relyonsoft.com';
		$todaydate = datetimelocal('d-m-Y');
		
		$pdf = vieworgeneratepdfinvoice($invoiceslno,'send');
		$pdfsplit = explode('^',$pdf);
		$filebasename = $pdfsplit[0];
		
		//Create an array of replace parameters
		$array = array();
		
		$array[] = "##DATE##%^%".$date;
		$array[] = "##COMPANYNAME##%^%".$businessname;
		$array[] = "##INVOICENO##%^%".$invoiceno;
		$array[] = "##PLACE##%^%".$place;
		$array[] = "##TOTALAMOUNT##%^%".$totalamount;
		$array[] = "##CONTACTPERSON##%^%".$contactperson;
		$array[] = "##EMAILID##%^%".$emailid;
		$array[] = "##CUSTOMERID##%^%".$customerid;
		$array[] = "##RECEIPTAMOUNT##%^%".$receiptamount;
		$array[] = "##OUTSTANDINGAMOUNT##%^%".$outstandingamount;
		$array[] = "##TODAYDATE##%^%".$todaydate;
		
		#########  Mailing Starts -----------------------------------
		if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
		{
			$emailid = 'meghana.b@relyonsoft.com';
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
					$emailids[$emailarray[$i]] = $emailarray[$i];
			}
		}
		
		//CC to Sales person
		if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
		{
			$dealeremailid = 'rashmi.hk@relyonsoft.com';
		}
		else
		{	
			$dealeremailid = $dealeremailid;
		}
		$ccemailarray = explode(',',$dealeremailid);
		$ccemailcount = count($ccemailarray);
		for($i = 0; $i < $ccemailcount; $i++)
		{
			if(checkemailaddress($ccemailarray[$i]))
			{
				if($i == 0)
					$ccemailids[$ccemailarray[$i]] = $ccemailarray[$i];
				else
					$ccemailids[$ccemailarray[$i]] = $ccemailarray[$i];
			}
		}
		
		//Relyon Logo for email Content, as Inline [Not attachment]
		$filearray = array(
			array('../images/relyon-logo.jpg','inline','1234567890'),
			array('../filecreated/'.$filebasename,'attachment','1234567891'),
			array('../images/relyon-rupee-small.jpg','inline','1234567892')
		);
		$toarray = $emailids;
		
		//CC to sales person
		$ccarray = $ccemailids;
		
		if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
		{
			$bccemailids['rashmi'] ='meghana.b@relyonsoft.com';
			//$bccemailids['archanaab'] ='archana.ab@relyonsoft.com';
		}
		else
		{
			$bccemailids = array('Bigmail' => 'bigmail@relyonsoft.com', 'Accounts'=> 'accounts@relyonsoft.com', 'Relyonimax' => 'relyonimax@gmail.com');
		}
		$bccarray = $bccemailids;
		$msg = replacemailvariable($msg,$array);
		$textmsg = replacemailvariable($textmsg,$array);
		$subject = "Amount outstanding against Invoice No : ".$invoiceno." ";
		$html = $msg;
		$text = $textmsg;
		rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray);
			//Insert the mail forwarded details to the logs table
		$bccmailid = 'accounts@relyonsoft.com,bigmail@relyonsoft.com'; 
		inserttologs(imaxgetcookie('userid'),$slno,$fromname,$fromemail,$emailid,$dealeremailid,$bccmailid,$subject);
		echo('1^Outstanding Email Sent Successfully.');
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
		
		case 'updateremarks':
			{
				$type = $_POST['type'];
				$invoiceslno = $_POST['invoiceno'];
				$remarks = $_POST['remarks'];
				
				if($type == '1')
				{
					$query = "UPDATE inv_invoicenumbers set outstandingremarks1 = '".$remarks."' where slno = '".$invoiceslno."'";
					$result = runmysqlquery($query);
					
					echo('1^Remarks Updated Successfully');
				}
				else if($type == '2')
				{
					$query = "UPDATE inv_invoicenumbers set outstandingremarks2 = '".$remarks."' where slno = '".$invoiceslno."'";
					$result = runmysqlquery($query);
					
					echo('1^Remarks Updated Successfully');
				}
			}
			break;
}



?>
