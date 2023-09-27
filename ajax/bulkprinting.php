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
	case 'searchinvoices':
	{
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
		$seriesnew = $_POST['seriesnew'];
		$usagetype = $_POST['usagetype'];
		$purchasetype = $_POST['purchasetype'];
		$cancelledinvoice = $_POST['cancelledinvoice'];
		$receiptstatus = $_POST['receiptstatus'];
		$itemlist = $_POST['itemlist'];
		$itemlistsplit = explode(',',$itemlist);
		$itemlistsplitcount = count($itemlistsplit);
		

		if($usagetype == 'addlic')
		{
			$usagetypevalue = 'singleuser';
			$addlicence = "AND inv_dealercard.addlicence = 'yes'";
		}
		elseif($usagetype == 'singleuser')
		{
			$usagetypevalue = 'singleuser';
			$addlicence = '';
		}elseif($usagetype == 'mutiuser')
		{
			$usagetypevalue = 'mutiuser';
			$addlicence = '';
		}
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
		
		$seriespiece = ($series == "")?(""):(" AND inv_invoicenumbers.category = '".$series."' ");
		$seriespiecenew = ($seriesnew == "")?(""):(" AND inv_invoicenumbers.state_info = '".$seriesnew."' ");
		
		$usagetypepiece = ($usagetype == "")?(""):(" AND inv_dealercard.usagetype = '".$usagetypevalue."' ".$addlicence."  ");
		$purchasetypepiece = ($purchasetype == "")?(""):(" AND inv_dealercard.purchasetype = '".$purchasetype."' ");
		$cancelledpiece = ($cancelledinvoice == "yes")?("AND inv_invoicenumbers.status <> 'CANCELLED'"):("");
		$statuspiece = ($status == "")?(""):(" AND inv_invoicenumbers.status = '".$status."'");
		$receiptstatuspiece = ($receiptstatus == "")?(""):(" and inv_mas_receipt.status = '".$receiptstatus."' ");

		
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
					
				$query = "select distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.amount,(inv_invoicenumbers.servicetax+IFNULL(inv_invoicenumbers.sbtax,0)) as servicetax,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_invoicenumbers.status,inv_invoicenumbers.products as products from inv_invoicenumbers
left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno
left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno
where left(inv_invoicenumbers.createddate,10) between '".$fromdate."' and '".$todate."' AND right(inv_invoicenumbers.customerid,5) like '%".$customerid."%' ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  order by inv_invoicenumbers.slno";
				break;
		
			case "contactperson": 
				$query = "select distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.amount,(inv_invoicenumbers.servicetax+IFNULL(inv_invoicenumbers.sbtax,0)) as servicetax,inv_invoicenumbers.netamount,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_invoicenumbers.status,inv_invoicenumbers.products as products from inv_invoicenumbers
left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno
left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno
where left(inv_invoicenumbers.createddate,10) between '".$fromdate."' and '".$todate."' AND inv_invoicenumbers.contactperson LIKE '%".$textfield."%' ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew." order by inv_invoicenumbers.slno";
				break;
			case "phone":
				$query = "select distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.amount,(inv_invoicenumbers.servicetax+IFNULL(inv_invoicenumbers.sbtax,0)) as servicetax,inv_invoicenumbers.netamount,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_invoicenumbers.status,inv_invoicenumbers.products as products from inv_invoicenumbers
left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno
left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno
where left(inv_invoicenumbers.createddate,10) between '".$fromdate."' and '".$todate."' AND inv_invoicenumbers.phone LIKE '%".$textfield."%' OR inv_invoicenumbers.cell LIKE '%".$textfield."%' ".$productlistarray.$itemlistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  order by inv_invoicenumbers.slno ";
				break;
			case "place":
				$query = "select distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.amount,(inv_invoicenumbers.servicetax+IFNULL(inv_invoicenumbers.sbtax,0)) as servicetax,inv_invoicenumbers.netamount,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_invoicenumbers.status,inv_invoicenumbers.products as products from inv_invoicenumbers
left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno
left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno
where left(inv_invoicenumbers.createddate,10) between '".$fromdate."' and '".$todate."' AND inv_invoicenumbers.place LIKE '%".$textfield."%' ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  order by inv_invoicenumbers.slno";
				break;
			case "emailid":
				$query = "select distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.amount,(inv_invoicenumbers.servicetax+IFNULL(inv_invoicenumbers.sbtax,0)) as servicetax,inv_invoicenumbers.netamount,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_invoicenumbers.status,inv_invoicenumbers.products as products from inv_invoicenumbers
left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno
left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno
where left(inv_invoicenumbers.createddate,10) between '".$fromdate."' and '".$todate."' AND inv_invoicenumbers.emailid LIKE '%".$textfield."%'  ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew." order by inv_invoicenumbers.slno";
				break;
				case "cardid":
					$query = "select distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.amount,(inv_invoicenumbers.servicetax+IFNULL(inv_invoicenumbers.sbtax,0)) as servicetax,inv_invoicenumbers.netamount,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_invoicenumbers.status,inv_invoicenumbers.products as products from inv_invoicenumbers
left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno
left join inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno
LEFT JOIN inv_mas_scratchcard ON inv_mas_scratchcard.cardid = inv_dealercard.cardid
where inv_mas_scratchcard.cardid LIKE '%".$textfield."%'  AND left(inv_invoicenumbers.createddate,10) between '".$fromdate."' and '".$todate."' ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  order by inv_invoicenumbers.slno";
					break;
			case "scratchnumber":
				$query = "select distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.amount,(inv_invoicenumbers.servicetax+IFNULL(inv_invoicenumbers.sbtax,0)) as servicetax,inv_invoicenumbers.netamount,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_invoicenumbers.status,inv_invoicenumbers.products as products from inv_invoicenumbers
left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno
left join inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno
LEFT JOIN inv_mas_scratchcard ON inv_mas_scratchcard.cardid = inv_dealercard.cardid
where inv_mas_scratchcard.scratchnumber LIKE '%".$textfield."%'  AND left(inv_invoicenumbers.createddate,10) between '".$fromdate."' and '".$todate."' ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  order by inv_invoicenumbers.slno";
				break;
			
			case "invoiceno":
				$query = "select distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.amount,(inv_invoicenumbers.servicetax+IFNULL(inv_invoicenumbers.sbtax,0)) as servicetax,inv_invoicenumbers.netamount,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_invoicenumbers.status,inv_invoicenumbers.products as products from inv_invoicenumbers
left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno
left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno
where left(inv_invoicenumbers.createddate,10) between '".$fromdate."' and '".$todate."' AND  inv_invoicenumbers.invoiceno LIKE '%".$textfield."%' ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  order by inv_invoicenumbers.slno";
				break;
			case "invoiceamt":
				$query = "select distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.amount,(inv_invoicenumbers.servicetax+IFNULL(inv_invoicenumbers.sbtax,0)) as servicetax,inv_invoicenumbers.netamount,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_invoicenumbers.status,inv_invoicenumbers.products as products from inv_invoicenumbers
left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno
left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno
where left(inv_invoicenumbers.createddate,10) between '".$fromdate."' and '".$todate."' AND  inv_invoicenumbers.netamount LIKE '%".$textfield."%' ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  order by inv_invoicenumbers.slno";
				break;
			
			default:
				$query = "select distinct inv_invoicenumbers.slno,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.amount,(inv_invoicenumbers.servicetax+IFNULL(inv_invoicenumbers.sbtax,0)) as servicetax,inv_invoicenumbers.netamount,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_invoicenumbers.status,inv_invoicenumbers.products as products,inv_invoicenumbers.servicedescription as servicedescription from inv_invoicenumbers
left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno
left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno
where left(inv_invoicenumbers.createddate,10) between '".$fromdate."' and '".$todate."' AND inv_invoicenumbers.businessname LIKE '%".$textfield."%' ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  order by inv_invoicenumbers.slno";
				break;
		} 
		//echo($query);exit;
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','84','".date('Y-m-d').' '.date('H:i:s')."','view_bulk_print(Invoices)')";
		$eventresult = runmysqlquery($eventquery);
		$result1 = runmysqlquery($query);
		
		$fetchresultcount = mysqli_num_rows($result1);
		$grid = '';
			
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">&nbsp;</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Action</td><td nowrap = "nowrap" class="td-border-grid" align="left">Email</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Products</td><td nowrap = "nowrap" class="td-border-grid" align="left">Status</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sale Value</td><td nowrap = "nowrap" class="td-border-grid" align="left">Tax Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sales Person</td><td nowrap = "nowrap" class="td-border-grid" align="left">Prepared By</td></tr>';
		}
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result1))
		{
			$i_n++;
			$slnocount++;
			$color;
			$productsplit = explode('#',$fetch['products']);
			$productsplitcount = count($productsplit);
			
			$servicedescriptionsplit = explode('*',$fetch['servicedescription']);
			$servicedescriptioncount = count($servicedescriptionsplit);
			
			if($fetch['products'] == '')
				$totalcount = $servicedescriptioncount;
			elseif(($fetch['products'] != '') && ($fetch['servicedescription'] != ''))
				$totalcount = $servicedescriptioncount + $productsplitcount;
			else
				$totalcount = $productsplitcount;
			
			
			
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$countselected = 'countselected';
				$grid .= '<tr bgcolor='.$color.' class="gridrow1" align="left">';
				$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"><input type="checkbox" name="resultcheckbox'.$slnocount.'" id ="resultcheckbox'.$slnocount.'" value = "'.$fetch['slno'].'" onclick = "javascript:selectanddeselect(\''.$countselected.'\')" />';
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
				$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewinvoice(\''.$fetch['slno'].'\');" class="resendtext" style = "cursor:pointer"> View >></a> </td>';
				$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"><div id= "resendemail'.$slnocount.'" style ="display:block;"> <a onclick="resendinvoice(\''.$fetch['slno'].'\',\''.$slnocount.'\');" class="resendtext" style = "cursor:pointer"> Resend </a> </div><div id ="resendprocess'.$slnocount.'" style ="display:none;"></div></td>';
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat(trim($fetch['createddate']))."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['invoiceno']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='center'><a onclick='displayproductdetails(\"".$fetch['slno']."\");' class='resendtext' style = 'cursor:pointer'>".$totalcount."</a></td>";

				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['status'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['customerid'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['businessname'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['amount']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['servicetax'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['netamount'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['dealername'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['createdby'])."</td>";
				
				$grid .= "</tr>";
		}
		$grid .= "</table>";
		
		
		echo '1^'.$grid.'^'.$fetchresultcount;	
	}
	break;
	case 'resendinvoice':
	{
		$invoiceno = $_POST['invoiceno'];
		$sent = resendinvoice($invoiceno);
		echo($sent);
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
	
	case 'productdetailsgrid':
	{
		$productslno = $_POST['productslno'];
		$query = "select * from inv_invoicenumbers where slno = '".$productslno."' ;";
		$result = runmysqlquery($query);
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" style="font-size:12px">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Usage Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Pin Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Pin Serial Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Amount</td></tr>';
		
		$i_n = 0;$slnocount = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$i_n++;
			$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$description = $fetch['description'];
			$descriptionsplit = explode('*',$description);
			$descriptionsplitcount = count($descriptionsplit);
			
			$servicedescriptionsplit = explode('*',$fetch['servicedescription']);
			$servicedescriptioncount = count($servicedescriptionsplit);

			if($fetch['products'] == '')
			$fetchresultantcount = $servicedescriptioncount;
			elseif(($fetch['products'] != '') && ($fetch['servicedescription'] != ''))
				$fetchresultantcount = $servicedescriptioncount + $descriptionsplitcount;
			else
				$fetchresultantcount = $descriptionsplitcount;
				
			if($description != '')
			{
				for($i=0;$i<$descriptionsplitcount;$i++)
				{
					$descriptionline = explode('$',$descriptionsplit[$i]);
					$grid .= '<tr bgcolor='.$color.'>';
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$descriptionline[0]."</td> ";
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$descriptionline[1]."</td>";
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$descriptionline[2]."</td>";
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$descriptionline[3]."</td>";
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$descriptionline[4]."</td>";
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$descriptionline[5]."</td>";
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$descriptionline[6]."</td>";
					$grid .= "</tr>";
				}
			}
			
			if($fetch['servicedescription'] <> '')
			{
				for($i=0; $i<$servicedescriptioncount; $i++)
				{
					$count++;
					$servicedescriptionline = explode('$',$servicedescriptionsplit[$i]);
					$grid .= '<tr bgcolor='.$color.' >';
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$servicedescriptionline[0]."</td> ";
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$servicedescriptionline[1]."</td>";
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>&nbsp;</td>";
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>&nbsp;</td>";
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>&nbsp;</td>";
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>&nbsp;</td>";
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$servicedescriptionline[2]."</td>";
					$grid .= "</tr>";
				}
			}
			
		}
		$grid .= "</table>";

		$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		
	
		echo '1^'.$grid.'^'.$fetchresultantcount.'^'.$linkgrid;
	}
break;

	
}


?>