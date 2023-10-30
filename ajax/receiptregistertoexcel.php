<?php
ini_set('memory_limit', '2048M');

include('../functions/phpfunctions.php');

if(imaxgetcookie('userid')<> '') 
$userid = imaxgetcookie('userid');
else
{ 
	echo('Thinking to redirect');
	exit;
}

require_once '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// PHPExcel
//require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory
//require_once '../phpgeneration/PHPExcel/IOFactory.php';
$flag = $_POST['flag'];
$fromdate = changedateformat($_POST['fromdate']);
$todate = changedateformat($_POST['todate']);
$databasefield = $_POST['databasefield'];
$textfield = $_POST['searchcriteria'];
$paymentmode = $_POST['paymentmode'];
$state = $_POST['state2'];
$region = $_POST['region'];
$dealer = $_POST['currentdealer'];
$branch = $_POST['branch'];
$generatedby = $_POST['generatedby'];
$generatedbysplit = explode('^',$generatedby);
$district = $_POST['district2'];
$chks = $_POST['productarray'];
$receiptstatus = $_POST['receiptstatus'];
$reconciletype = $_POST['reconsilation'];
for ($i = 0;$i < count($chks);$i++)
{
	$c_value .= $chks[$i]."," ;
}

$value = rtrim($c_value , ',');
$productslist = str_replace('\\','',$value);

$productlistsplit = explode(',',$productslist);
$productlistsplitcount = count($productlistsplit);
$status = $_POST['status'];
$series = $_POST['series'];
$cancelledinvoice = $_POST['cancelledinvoice'];

$cks_value = $_POST['itemarray'];
if(!empty($cks_value))
{
	for ($i = 0;$i < count($cks_value);$i++)
	{
		$k_value .= $cks_value[$i]."," ;
	}

	$itemvalue = rtrim($k_value , ',');
	$itemlist = str_replace('\\','',$itemvalue);
	$itemlistsplit = explode(',',$itemlist);
	$itemlistsplitcount = count($itemlistsplit);
}

$mks_value = $_POST['matrixarray'];
if(!empty($mks_value))
{
	for ($i = 0;$i < count($mks_value);$i++)
	{
		$mk_value .= $mks_value[$i]."," ;
	}

	$matrixvalue = rtrim($mk_value , ',');
	$matrixlist = str_replace('\\','',$matrixvalue);
	$matrixlistsplit = explode(',',$matrixlist);
	$matrixlistsplitcount = count($matrixlistsplit);
}


$alltimecheck = $_POST['alltime'];

if($chks != '')
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

if($mks_value != '')
{
	for($k = 0;$k< $matrixlistsplitcount; $k++)
	{
		if($k < ($matrixlistsplitcount-1))
			$appendor = 'or'.' ';
		else
			$appendor = '';
			
		$finalmatrixlist .= ' inv_matrixinvoicenumbers.products'.' '.'like'.' "'.'%'.$matrixlistsplit[$k].'%'.'" '.$appendor."";
	}
}


if($cks_value != '')
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
// 	$finaldlrlistarray = ' OR ('.$finaldlrproductlist.' '.'OR'.' '.$finaldlritemlist.')';
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

// $finalbotharaay = 'AND ('.$finallistarray.')';
$finalbotharaay = $finallistarray;

if($generatedby == "")
	$modulepiece = "";
else
	$modulepiece = ($generatedbysplit[1] == "[U]")?("user_module"):("dealer_module");

$state_typepiece = ($state == "")?(""):(" AND inv_mas_district.statecode = '".$state."' ");
$district_typepiece = ($district == "")?(""):(" AND inv_mas_customer.district = '".$district."' ");
$generatedpiece = ($generatedby == "")?(""):(" AND inv_mas_receipt.createdby = '".$generatedbysplit[0]."'");
$paymentmodepiece = ($paymentmode == "")?(""):(" and inv_mas_receipt.paymentmode = '".$paymentmode."' ");
$receiptstatuspiece = ($receiptstatus == "")?(""):(" and inv_mas_receipt.restatus = '".$receiptstatus."' ");
$reconciletype_piece = ($reconciletype == "")?(""):(" AND inv_mas_receipt.reconsilation = '".$reconciletype."' ");
$datepiece = ($alltimecheck == 'on')?(""):(" AND (left(inv_mas_receipt.receiptdate,10) between '".$fromdate."' AND '".$todate."') ");
$regionpiece = ($region == "")?(""):(" AND (inv_invoicenumbers.regionid = '".$region."' OR inv_dealer_invoicenumbers.regionid = '".$region."'".$mregion." ) ");
$dealer_typepiece = ($dealer == "")?(""):(" AND (inv_invoicenumbers.dealerid = '".$dealer."' OR inv_dealer_invoicenumbers.dealerid = '".$dealer."'".$mdealer." ) ");
$statuspiece = ($status == "")?(""):(" AND (inv_invoicenumbers.status = '".$status."' OR inv_dealer_invoicenumbers.status = '".$status."'".$mstatus." ) ");
$seriespiece = ($series == "")?(""):(" AND (inv_invoicenumbers.category = '".$series."' OR inv_dealer_invoicenumbers.category = '".$series."'".$mseries.") ");
$cancelledpiece = ($cancelledinvoice == "yes")?(" AND (inv_invoicenumbers.status <> 'CANCELLED' OR inv_dealer_invoicenumbers.status <> 'CANCELLED'".$mcancel.") "):("");
$branchpiece = ($branch == "")?(""):(" AND (inv_invoicenumbers.branchid = '".$branch."' OR inv_dealer_invoicenumbers.branchid = '".$branch."'".$mbranch.") ");	

$querycase = "select distinct inv_mas_receipt.slno,inv_mas_receipt.dealerinvoiceno,inv_mas_receipt.receiptdate,inv_mas_receipt.receiptremarks,inv_invoicenumbers.customerid,
inv_invoicenumbers.businessname,inv_dealer_invoicenumbers.businessname as dlrbusinessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,
inv_invoicenumbers.invoiceno,inv_dealer_invoicenumbers.invoiceno as dlrinvoiceno,inv_invoicenumbers.dealername,inv_dealer_invoicenumbers.dealername as dlrname,inv_mas_receipt.createdby as createdbyid,
inv_invoicenumbers.status,inv_dealer_invoicenumbers.status as dlrstatus, inv_mas_receipt.reconsilation,inv_mas_receipt.module,inv_invoicenumbers.address,inv_dealer_invoicenumbers.address as dlraddress,
inv_invoicenumbers.place,inv_dealer_invoicenumbers.place as dlrplace, inv_invoicenumbers.region,inv_dealer_invoicenumbers.region as dlrregion, 
inv_invoicenumbers.branch,inv_dealer_invoicenumbers.branch as dlrbranch,inv_invoicenumbers.emailid,inv_dealer_invoicenumbers.emailid as dlremailid,inv_invoicenumbers.phone,
inv_dealer_invoicenumbers.phone as dlrphone,inv_invoicenumbers.cell,inv_dealer_invoicenumbers.cell as dlrcell,inv_mas_receipt.restatus as receiptstatus,
inv_mas_receipt.chequedate,inv_mas_receipt.chequeno,inv_mas_receipt.drawnon,inv_mas_receipt.depositdate,inv_matrixinvoicenumbers.customerid as mcustomerid,inv_matrixinvoicenumbers.businessname as mbusinessname,
inv_matrixinvoicenumbers.invoiceno as minvoiceno,inv_matrixinvoicenumbers.dealername as mdealername,inv_matrixinvoicenumbers.status as mstatus,inv_matrixinvoicenumbers.address as maddress,inv_matrixinvoicenumbers.place as mplace,
inv_matrixinvoicenumbers.region as mregion,inv_matrixinvoicenumbers.branch as mbranch,inv_matrixinvoicenumbers.emailid as memailid,inv_matrixinvoicenumbers.phone as mphone,inv_matrixinvoicenumbers.cell as mcell
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

if($flag == '')
{
	$url = '../home/index.php?a_link=receiptregister'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
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
				$finalprdaraay	= $finallistarray;
					
				$query = $querycase." where (right(inv_invoicenumbers.customerid,5) like '%".$customerid."%'".$mcustomer.") ".$finalprdaraay.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece.$datepiece."  order by inv_mas_receipt.slno ";
				break;
		
			// case "contactperson": 
			// 	$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno, inv_invoicenumbers.dealername,inv_mas_receipt.createdby  as createdbyid,inv_invoicenumbers.address,inv_invoicenumbers.place, inv_invoicenumbers.region, inv_invoicenumbers.branch,inv_invoicenumbers.emailid,inv_invoicenumbers.phone,inv_invoicenumbers.cell,inv_invoicenumbers.status as invoicestatus,inv_mas_receipt.chequedate,inv_mas_receipt.chequeno,inv_mas_receipt.drawnon,inv_mas_receipt.depositdate, inv_mas_receipt.reconsilation,inv_mas_receipt.status as receiptstatus,inv_mas_receipt.module from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5) left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
			// 	where inv_invoicenumbers.contactperson LIKE '%".$textfield."%' ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece.$datepiece." order by inv_mas_receipt.slno ";
			// 	break;
			// case "phone":
			// 	$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno, inv_invoicenumbers.dealername,inv_mas_receipt.createdby  as createdbyid,inv_invoicenumbers.address,inv_invoicenumbers.place, inv_invoicenumbers.region, inv_invoicenumbers.branch,inv_invoicenumbers.emailid,inv_invoicenumbers.phone,inv_invoicenumbers.cell,inv_invoicenumbers.status as invoicestatus,inv_mas_receipt.chequedate,inv_mas_receipt.chequeno,inv_mas_receipt.drawnon,inv_mas_receipt.depositdate, inv_mas_receipt.reconsilation,inv_mas_receipt.status as receiptstatus,inv_mas_receipt.module from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno 
			// 	left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
			// 	left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
			// 	where inv_invoicenumbers.phone LIKE '%".$textfield."%' OR inv_invoicenumbers.cell LIKE '%".$textfield."%'  ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece.$datepiece."  order by inv_mas_receipt.slno ";
			// 	break;
			// case "place":
			// 	$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno, inv_invoicenumbers.dealername,inv_mas_receipt.createdby  as createdbyid,inv_invoicenumbers.address,inv_invoicenumbers.place, inv_invoicenumbers.region, inv_invoicenumbers.branch,inv_invoicenumbers.emailid,inv_invoicenumbers.phone,inv_invoicenumbers.cell,inv_invoicenumbers.status as invoicestatus,inv_mas_receipt.chequedate,inv_mas_receipt.chequeno,inv_mas_receipt.drawnon,inv_mas_receipt.depositdate, inv_mas_receipt.reconsilation,inv_mas_receipt.status as receiptstatus,inv_mas_receipt.module from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
			// 	left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
			// 	left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
			// 	where inv_invoicenumbers.place LIKE '%".$textfield."%'".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece.$datepiece."  order by inv_mas_receipt.slno ";
			// 	break;
			// case "emailid":
			// 	$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno, inv_invoicenumbers.dealername,inv_mas_receipt.createdby  as createdbyid,inv_invoicenumbers.address,inv_invoicenumbers.place, inv_invoicenumbers.region, inv_invoicenumbers.branch,inv_invoicenumbers.emailid,inv_invoicenumbers.phone,inv_invoicenumbers.cell,inv_invoicenumbers.status as invoicestatus,inv_mas_receipt.chequedate,inv_mas_receipt.chequeno,inv_mas_receipt.drawnon,inv_mas_receipt.depositdate, inv_mas_receipt.reconsilation,inv_mas_receipt.status as receiptstatus,inv_mas_receipt.module from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
			// 	left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
			// 	left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
			// 	where inv_invoicenumbers.emailid LIKE '%".$textfield."%'  ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece.$datepiece." order by inv_mas_receipt.slno ";
			// 	break;
			// case "cardid":
			// 	$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno, inv_invoicenumbers.dealername,inv_mas_receipt.createdby  as createdbyid,inv_invoicenumbers.address,inv_invoicenumbers.place, inv_invoicenumbers.region, inv_invoicenumbers.branch,inv_invoicenumbers.emailid,inv_invoicenumbers.phone,inv_invoicenumbers.cell,inv_invoicenumbers.status as invoicestatus,inv_mas_receipt.chequedate,inv_mas_receipt.chequeno,inv_mas_receipt.drawnon,inv_mas_receipt.depositdate, inv_mas_receipt.reconsilation,inv_mas_receipt.status as receiptstatus,inv_mas_receipt.module from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno 
			// 	left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
			// 	left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
			// 	left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno
			// 	LEFT JOIN inv_mas_scratchcard ON inv_mas_scratchcard.cardid = inv_dealercard.cardid
			// 	where inv_mas_scratchcard.cardid LIKE '%".$textfield."%'  ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece.$datepiece."  order by inv_mas_receipt.slno ";
			// 	break;
			// case "scratchnumber":
			// 	$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno, inv_invoicenumbers.dealername,inv_mas_receipt.createdby  as createdbyid,inv_invoicenumbers.address,inv_invoicenumbers.place, inv_invoicenumbers.region, inv_invoicenumbers.branch,inv_invoicenumbers.emailid,inv_invoicenumbers.phone,inv_invoicenumbers.cell,inv_invoicenumbers.status as invoicestatus,inv_mas_receipt.chequedate,inv_mas_receipt.chequeno,inv_mas_receipt.drawnon,inv_mas_receipt.depositdate, inv_mas_receipt.reconsilation,inv_mas_receipt.status as receiptstatus,inv_mas_receipt.module from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno 
			// 	left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
			// 	left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
			// 	left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno
			// 	LEFT JOIN inv_mas_scratchcard ON inv_mas_scratchcard.cardid = inv_dealercard.cardid
			// 	where inv_mas_scratchcard.scratchnumber LIKE '%".$textfield."%' ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece.$datepiece."  order by inv_mas_receipt.slno ";
			// 	break;
			
			case "invoiceno":
				$query = $querycase." where (inv_invoicenumbers.invoiceno LIKE '%".$textfield."%' or inv_dealer_invoicenumbers.invoiceno LIKE '%".$textfield."%'".$minvoice.") ".$finalbotharaay.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece.$datepiece."  order by inv_mas_receipt.slno ";
				break;
			// case "paymode":
			// 	$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno, inv_invoicenumbers.dealername,inv_mas_receipt.createdby  as createdbyid,inv_invoicenumbers.address,inv_invoicenumbers.place, inv_invoicenumbers.region, inv_invoicenumbers.branch,inv_invoicenumbers.emailid,inv_invoicenumbers.phone,inv_invoicenumbers.cell,inv_invoicenumbers.status,inv_mas_receipt.chequedate,inv_mas_receipt.chequeno,inv_mas_receipt.drawnon,inv_mas_receipt.depositdate, inv_mas_receipt.reconsilation,inv_mas_receipt.status as receiptstatus,inv_mas_receipt.module from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
			// 	left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
			// 	left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
			// 	where inv_mas_receipt.receiptdate BETWEEN '".$fromdate."' and '".$todate."' AND  inv_mas_receipt.paymentmode LIKE '%".$textfield."%' ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece.$datepiece."  order by inv_mas_receipt.slno ";
			// 	break;
			case "receiptno":
				$query = $querycase." where inv_mas_receipt.slno LIKE '%".$textfield."%' ".$finalbotharaay.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece.$datepiece."  order by inv_mas_receipt.slno ";
				break;
			case "chequeno":
				$query = $querycase." where inv_mas_receipt.chequeno LIKE '%".$textfield."%' ".$finalbotharaay.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece.$datepiece."  order by inv_mas_receipt.slno ";
				break;
			case "chequedate":
				$query = $querycase." where inv_mas_receipt.chequedate LIKE '%".changedateformat($textfield)."%' ".$finalbotharaay.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece."  order by inv_mas_receipt.slno ";
				break;
			case "depositdate":
				$query = $querycase." where inv_mas_receipt.depositdate LIKE '%".changedateformat($textfield)."% ".$finalbotharaay.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece."  order by inv_mas_receipt.slno ";
				break;
			case "drawnon":
				$query = $querycase." where inv_mas_receipt.drawnon LIKE '%".$textfield."%' ".$finalbotharaay.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece.$datepiece."  order by inv_mas_receipt.slno ";
				break;
				
			case "paymentamt":
				$query = $querycase." where inv_mas_receipt.receiptamount LIKE '%".$textfield."%' ".$finalbotharaay.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece.$datepiece."  order by inv_mas_receipt.slno ";
				break;
			
			default:
				$query = $querycase." where  (inv_invoicenumbers.businessname LIKE '%".$textfield."%' OR inv_dealer_invoicenumbers.businessname LIKE '%".$textfield."%'".$mbusiness.") ".$finalbotharaay.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconciletype_piece.$datepiece."  ORDER BY inv_mas_receipt.slno ";
				break;
		} 
		//echo($query); exit;
		/*$query = "select distinct inv_mas_receipt.slno,inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createdby,inv_invoicenumbers.address,inv_invoicenumbers.place,inv_invoicenumbers.region,inv_invoicenumbers.branch,inv_invoicenumbers.emailid,inv_invoicenumbers.phone,inv_invoicenumbers.cell,inv_invoicenumbers.status,inv_mas_receipt.chequedate,inv_mas_receipt.chequeno,inv_mas_receipt.drawnon,inv_mas_receipt.depositdate from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno where inv_mas_receipt.receiptdate BETWEEN '".$fromdate."' and '".$todate."'  ".$paymentmodepiece." order by inv_mas_receipt.createddate desc;";*/
				
//	echo($query); exit;
		$result = runmysqlquery($query);
		
		
		// Create new Spreadsheet object
		$objPHPExcel = new Spreadsheet();
		
		//Define Style for header row
		$styleArray = array(
							'font' => array('bold' => true,),
							'fill'=> array('fillType'=> \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,'startColor'=> array('argb' => 'FFCCFFCC')),
							'borders' => array('allBorders'=> array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM))
						);
						
		//Define Style for content area
		$styleArrayContent = array(
							'borders' => array('allBorders'=> array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))
						);
						
		//set page index
		$pageindex = 0;

		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet();
		
		//Set the worksheet name
		$mySheet->setTitle('Receipt Details');
			
		//Apply style for header Row
		$mySheet->getStyle('A3:Y3')->applyFromArray($styleArray);
		
		//Merge the cell
		$mySheet->mergeCells('A1:Y1');
		$mySheet->mergeCells('A2:Y2');
		$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'Receipt Details Report');
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
	
		
		//Fille contents for Header Row
		$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('A3', 'Sl No')
					->setCellValue('B3', 'Receipt Date')
					->setCellValue('C3', 'Receipt No')
					->setCellValue('D3', 'Customer ID')
					->setCellValue('E3', 'Customer Name')
					->setCellValue('F3', 'Receipt Amount')
					->setCellValue('G3', 'Mode')
					->setCellValue('H3', 'Invoice No')
					->setCellValue('I3', 'Sales Person')
					->setCellValue('J3', 'Prepared By')
					->setCellValue('K3', 'Address')
					->setCellValue('L3', 'Place')
					->setCellValue('M3', 'Region')
					->setCellValue('N3', 'Branch')
					->setCellValue('O3', 'Phone')
					->setCellValue('P3', 'Cell')
					->setCellValue('Q3', 'Email Id')
					->setCellValue('R3', 'Invoice Status')
					->setCellValue('S3', 'Cheque Date')
					->setCellValue('T3', 'Cheque No')
					->setCellValue('U3', 'Drawn On')
					->setCellValue('V3', 'Deposit Date')
					->setCellValue('W3', 'Reconcile Type')
					->setCellValue('X3', 'Receipt Status')
					->setCellValue('Y3', 'Remarks: Payment details');
							
		$j =4;
		$slno_count =0;
		while($fetch = mysqli_fetch_array($result))
		{
			if($fetch['chequedate'] == '')
				$chequedate = 	'';
			else
				$chequedate = changedateformat($fetch['chequedate']);
			if($fetch['depositdate'] == '')
				$depositdate = 	'';
			else
				$depositdate = changedateformat($fetch['depositdate']);
			
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
				$address = $fetch['dlraddress'];
				$place =$fetch['dlrplace'];
				$region = $fetch['dlrregion'];
				$branch = $fetch['dlrbranch'];
				$phone = $fetch['dlrphone'];
				$cell = $fetch['dlrcell'];
				$emailid = $fetch['dlremailid'];

			}
			else if($fetch['minvoiceno']!=""){
				$customerid = $fetch['mcustomerid'];
				$businessname = trim($fetch['mbusinessname']);
				$status = trim($fetch['mstatus']);
				$invoiceno = trim($fetch['minvoiceno']);
				$dealername = trim($fetch['mdealername']);
				$address = $fetch['maddress'];
				$place =$fetch['mplace'];
				$region = $fetch['mregion'];
				$branch = $fetch['mbranch'];
				$phone = $fetch['mphone'];
				$cell = $fetch['mcell'];
				$emailid = $fetch['memailid'];
			}
			else{
				$customerid = $fetch['customerid'];
				$businessname = trim($fetch['businessname']);
				$status = trim($fetch['status']);
				$invoiceno = trim($fetch['invoiceno']);
				$dealername = trim($fetch['dealername']);
				$address = $fetch['address'];
				$place =$fetch['place'];
				$region = $fetch['region'];
				$branch = $fetch['branch'];
				$phone = $fetch['phone'];
				$cell = $fetch['cell'];
				$emailid = $fetch['emailid'];
			}

			$slno_count++;
			$mySheet->setCellValue('A' . $j,$slno_count)
					->setCellValue('B' . $j,changedateformat($fetch['receiptdate']))
					->setCellValue('C' . $j,$fetch['slno'])
					->setCellValue('D' . $j,$customerid)
					->setCellValue('E' . $j,$businessname)
					->setCellValue('F' . $j,$fetch['receiptamount'])
					->setCellValue('G' . $j,getpaymentmode($fetch['paymentmode']))
					->setCellValue('H' . $j,$invoiceno)
					->setCellValue('I' . $j,$dealername)
					->setCellValue('J' . $j,$resultvalue['createdby'])
					->setCellValue('K' . $j,$address)
					->setCellValue('L' . $j,$place)
					->setCellValue('M' . $j,$region)
					->setCellValue('N' . $j,$branch)
					->setCellValue('O' . $j,$phone)
					->setCellValue('P' . $j,$cell)
					->setCellValue('Q' . $j,$emailid)
					->setCellValue('R' . $j,$status)
					->setCellValue('S' . $j,$chequedate)
					->setCellValue('T' . $j,$fetch['chequeno'])
					->setCellValue('U' . $j,$fetch['drawnon'])
					->setCellValue('V' . $j,$depositdate)
					->setCellValue('W' . $j,$reconciletype)
					->setCellValue('X' . $j,$fetch['receiptstatus'])
					->setCellValue('Y' . $j,$fetch['receiptremarks']);
					$j++;
		}
	
				//Get the last cell reference
				$highestRow = $mySheet->getHighestRow(); 
				$highestColumn = $mySheet->getHighestColumn(); 
				$myLastCell = $highestColumn.$highestRow;
				
				//Deine the content range
				$myDataRange = 'A3:'.$myLastCell;
	
				//Apply style to content area range
				$mySheet->getStyle($myDataRange)->applyFromArray($styleArrayContent);
				
				//Invoice details
				$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('E'. ($highestRow + 2), 'Total Receipts')
					->setCellValue('E'. ($highestRow + 3), 'Total Amount');

					
				$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('F'. ($highestRow + 2), ($highestRow-3))
					->setCellValue('F'. ($highestRow + 3), "=SUM(F4:F".($highestRow).")");
				
				//set the default width for column
				$mySheet->getColumnDimension('A')->setWidth(6);
				$mySheet->getColumnDimension('B')->setWidth(15);
				$mySheet->getColumnDimension('C')->setWidth(15);
				$mySheet->getColumnDimension('D')->setWidth(20);
				$mySheet->getColumnDimension('E')->setWidth(40);
				$mySheet->getColumnDimension('F')->setWidth(15);
				$mySheet->getColumnDimension('G')->setWidth(15);
				$mySheet->getColumnDimension('H')->setWidth(15);
				$mySheet->getColumnDimension('I')->setWidth(25);
				$mySheet->getColumnDimension('J')->setWidth(25);
				$mySheet->getColumnDimension('K')->setWidth(50);
				$mySheet->getColumnDimension('L')->setWidth(20);
				$mySheet->getColumnDimension('M')->setWidth(20);
				$mySheet->getColumnDimension('N')->setWidth(20);
				$mySheet->getColumnDimension('O')->setWidth(20);
				$mySheet->getColumnDimension('P')->setWidth(20);
				$mySheet->getColumnDimension('Q')->setWidth(30);
				$mySheet->getColumnDimension('R')->setWidth(20);
				$mySheet->getColumnDimension('S')->setWidth(20);
				$mySheet->getColumnDimension('T')->setWidth(20);
				$mySheet->getColumnDimension('U')->setWidth(20);
				$mySheet->getColumnDimension('V')->setWidth(20);
				$mySheet->getColumnDimension('W')->setWidth(20);
				$mySheet->getColumnDimension('X')->setWidth(20);
				$mySheet->getColumnDimension('Y')->setWidth(40);
				$pageindex++;

		$addstring = "/user";
		if($_SERVER['HTTP_HOST'] == "rashmihk" || $_SERVER['HTTP_HOST'] == "meghanab" )
			$addstring = "/saralimax-user";
		
				$query = 'select slno,fullname from inv_mas_users where slno = '.$userid.'';
				$fetchres = runmysqlqueryfetch($query);
				$username = $fetchres['fullnaame'];
				$localdate = datetimelocal('Ymd');
				$localtime = datetimelocal('His');
				$filebasename = "ReceiptRegister".$localdate."-".$localtime."-".strtolower($username).".xls";
	
				$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_receiptregister_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
				$result = runmysqlquery($query1);
				
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','79','".date('Y-m-d').' '.date('H:i:s')."','excel_receiptregister_report".'-'.strtolower($username)."')";
				$eventresult = runmysqlquery($eventquery);
				
				$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filebasename;
				$downloadlink = 'http://'.$_SERVER['HTTP_HOST'].$addstring.'/filecreated/'.$filebasename;
				
				$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xls');
				$objWriter->save($filepath);
				$fp = fopen($filebasename,"wa+");
				if($fp)
				{
					downloadfile($filepath);
					fclose($fp);
				}
				//unlink($filepath);
				unlink($filebasename);
				exit; 
		}
	
?>
