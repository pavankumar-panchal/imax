<?
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

//PHPExcel 
//require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory
//require_once '../phpgeneration/PHPExcel/IOFactory.php';

$flag = $_POST['flag'];
$fromdate = changedateformat($_POST['fromdate']);
$sortby = $_POST['sortby'];
$sortby1 = $_POST['sortby1'];
$aged = $_POST['aged'];

$databasefield = $_POST['databasefield'];
$textfield = $_POST['searchcriteria'];
$state = $_POST['state2'];
$region = $_POST['region'];
$dealer = $_POST['currentdealer'];
$branch = $_POST['branch'];
$generatedby = $_POST['generatedby'];
$generatedbysplit = explode('^',$generatedby);
$district = $_POST['district2'];
$chks = $_POST['productarray'];
$receiptstatus = $_POST['receiptstatus'];
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
for ($i = 0;$i < count($cks_value);$i++)
{
	$k_value .= $cks_value[$i]."," ;
}

$itemvalue = rtrim($k_value , ',');
$itemlist = str_replace('\\','',$itemvalue);
$itemlistsplit = explode(',',$itemlist);
$itemlistsplitcount = count($itemlistsplit);

$cancelledinvoicepiece = ($cancelledinvoice == "on")?("AND inv_invoicenumbers.status <> 'CANCELLED'"):("");
$receiptstatuspiece = ($receiptstatus == "")?(""):(" and inv_mas_receipt.status = '".$receiptstatus."' ");

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
}

if(($cks_value != '') && ($chks != ''))
	$finallistarray = ' AND ('.$finalproductlist.' '.'OR'.' '.$finalitemlist.')';
elseif($chks == '')
	$finallistarray = ' AND ('.$finalitemlist.')';
elseif($cks_value == '')
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
					
				$query = "SELECT distinct inv_invoicenumbers.slno,inv_invoicenumbers.description,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.contactperson,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_dealer.businessname as dealername,inv_mas_dealer.emailid as dealeremail,inv_mas_dealer.cell as dealercell,inv_invoicenumbers.netamount,ifnull(inv_mas_receipt.receiptamount,0) as receiptamount, (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount , DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) as age,inv_invoicenumbers.status,inv_invoicenumbers.emailid from inv_invoicenumbers  left join (select sum(receiptamount) as receiptamount, invoiceno,status from inv_mas_receipt WHERE inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno 
left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 AND right(inv_invoicenumbers.customerid,5) like '%".$customerid."%' ".$finallistarray.$cancelledinvoicepiece.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$receiptstatuspiece."   ORDER BY ".$sortby." ".$sortby1.", inv_invoicenumbers.createddate desc";
				break;
		
			case "contactperson": 
				$query = "SELECT distinct inv_invoicenumbers.slno,inv_invoicenumbers.description,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.contactperson,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_dealer.businessname as dealername,inv_mas_dealer.emailid as dealeremail,inv_mas_dealer.cell as dealercell,inv_invoicenumbers.netamount,ifnull(inv_mas_receipt.receiptamount,0) as receiptamount, (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount , DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) as age,inv_invoicenumbers.status from inv_invoicenumbers  left join (select sum(receiptamount) as receiptamount, invoiceno,status,inv_invoicenumbers.emailid from inv_mas_receipt WHERE inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno 
left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0  AND inv_invoicenumbers.contactperson LIKE '%".$textfield."%' ".$finallistarray.$cancelledinvoicepiece.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece." ORDER BY ".$sortby." ".$sortby1.", inv_invoicenumbers.createddate desc";
				break;
			case "phone":
				$query = "SELECT distinct inv_invoicenumbers.slno,inv_invoicenumbers.description,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.contactperson,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_dealer.businessname as dealername,inv_mas_dealer.emailid as dealeremail,inv_mas_dealer.cell as dealercell,inv_invoicenumbers.netamount,ifnull(inv_mas_receipt.receiptamount,0) as receiptamount, (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount , DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) as age,inv_invoicenumbers.status,inv_invoicenumbers.emailid from inv_invoicenumbers  left join (select sum(receiptamount) as receiptamount, invoiceno,status from inv_mas_receipt WHERE inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno 
left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0  AND inv_invoicenumbers.phone LIKE '%".$textfield."%' OR inv_invoicenumbers.cell LIKE '%".$textfield."%'  ".$finallistarray.$cancelledinvoicepiece.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece."  ORDER BY ".$sortby." ".$sortby1.", inv_invoicenumbers.createddate desc";
				break;
			case "place":
				$query = "SELECT distinct inv_invoicenumbers.slno,inv_invoicenumbers.description,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.contactperson,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_dealer.businessname as dealername,inv_mas_dealer.emailid as dealeremail,inv_mas_dealer.cell as dealercell,inv_invoicenumbers.netamount,ifnull(inv_mas_receipt.receiptamount,0) as receiptamount, (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount , DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) as age,inv_invoicenumbers.status,inv_invoicenumbers.emailid from inv_invoicenumbers  left join (select sum(receiptamount) as receiptamount, invoiceno,status from inv_mas_receipt WHERE inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno 
left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 AND inv_invoicenumbers.place LIKE '%".$textfield."%' ".$finallistarray.$cancelledinvoicepiece.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece."  ORDER BY ".$sortby." ".$sortby1.", inv_invoicenumbers.createddate desc";
				break;
			case "emailid":
				$query = "SELECT distinct inv_invoicenumbers.slno,inv_invoicenumbers.description,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.contactperson,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_dealer.businessname as dealername,inv_mas_dealer.emailid as dealeremail,inv_mas_dealer.cell as dealercell,inv_invoicenumbers.netamount,ifnull(inv_mas_receipt.receiptamount,0) as receiptamount, (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount , DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) as age,inv_invoicenumbers.status,inv_invoicenumbers.emailid from inv_invoicenumbers  left join (select sum(receiptamount) as receiptamount, invoiceno,status from inv_mas_receipt WHERE inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno 
left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 AND inv_invoicenumbers.emailid LIKE '%".$textfield."%'  ".$finallistarray.$cancelledinvoicepiece.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece."ORDER BY ".$sortby." ".$sortby1.", inv_invoicenumbers.createddate desc";
				break;
				case "cardid":
					$query = "SELECT distinct inv_invoicenumbers.slno,inv_invoicenumbers.description,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.contactperson,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_dealer.businessname as dealername,inv_mas_dealer.emailid as dealeremail,inv_mas_dealer.cell as dealercell,inv_invoicenumbers.netamount,ifnull(inv_mas_receipt.receiptamount,0) as receiptamount, (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount , DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) as age,inv_invoicenumbers.status,inv_invoicenumbers.emailid from inv_invoicenumbers  left join (select sum(receiptamount) as receiptamount, invoiceno,status from inv_mas_receipt WHERE inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno 
left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno
LEFT JOIN inv_mas_scratchcard ON inv_mas_scratchcard.cardid = inv_dealercard.cardid
where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 and inv_mas_scratchcard.cardid LIKE '%".$textfield."%' ".$finallistarray.$cancelledinvoicepiece.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece."  ORDER BY ".$sortby." ".$sortby1.", inv_invoicenumbers.createddate desc";
					break;
			case "scratchnumber":
				$query = "SELECT distinct inv_invoicenumbers.slno,inv_invoicenumbers.description,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.contactperson,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_dealer.businessname as dealername,inv_mas_dealer.emailid as dealeremail,inv_mas_dealer.cell as dealercell,inv_invoicenumbers.netamount,ifnull(inv_mas_receipt.receiptamount,0) as receiptamount, (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount , DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) as age,inv_invoicenumbers.status,inv_invoicenumbers.emailid from inv_invoicenumbers  left join (select sum(receiptamount) as receiptamount, invoiceno,status from inv_mas_receipt WHERE inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno 
left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno
LEFT JOIN inv_mas_scratchcard ON inv_mas_scratchcard.cardid = inv_dealercard.cardid
where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0 and inv_mas_scratchcard.scratchnumber LIKE '%".$textfield."%'  ".$finallistarray.$cancelledinvoicepiece.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece."  ORDER BY ".$sortby." ".$sortby1.", inv_invoicenumbers.createddate desc";
				break;
			
			default:
				$query = "SELECT distinct inv_invoicenumbers.slno,inv_invoicenumbers.description,left(inv_invoicenumbers.createddate,10) as createddate,inv_invoicenumbers.invoiceno,inv_invoicenumbers.contactperson,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_mas_dealer.businessname as dealername,inv_mas_dealer.emailid as dealeremail,inv_mas_dealer.cell as dealercell,inv_invoicenumbers.netamount,ifnull(inv_mas_receipt.receiptamount,0) as receiptamount, (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) as outstandingamount , DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) as age,inv_invoicenumbers.status,inv_invoicenumbers.emailid from inv_invoicenumbers  left join (select sum(receiptamount) as receiptamount, invoiceno,status from inv_mas_receipt WHERE inv_mas_receipt.status <> 'CANCELLED' group by invoiceno) as inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno 
left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where left(inv_invoicenumbers.createddate,10) <= '".$fromdate."' and DATEDIFF('".$fromdate."',left(inv_invoicenumbers.createddate,10)) >= '".$aged."' and (inv_invoicenumbers.netamount-ifnull(inv_mas_receipt.receiptamount,0)) > 0  AND inv_invoicenumbers.businessname LIKE '%".$textfield."%' ".$finallistarray.$cancelledinvoicepiece.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece." ORDER BY ".$sortby." ".$sortby1.", inv_invoicenumbers.createddate desc";
				break;
		} 
				
		//echo($query); exit;
		$result = runmysqlquery($query);
		
		// Create new Spreadsheet object
		$objPHPExcel = new Spreadsheet();
		
		//Define Style for header row
		$styleArray = array(
							'font' => array('bold' => true,),
							'fill'=> array('fillType'=> \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,'color'=> array('argb' => 'FFCCFFCC')),
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
		$mySheet->getStyle('A3:V3')->applyFromArray($styleArray);
		
		//Merge the cell
		$mySheet->mergeCells('A1:V1');
		$mySheet->mergeCells('A2:V2');
		$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'Outstanding Details Report');
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
	
		
		//Fille contents for Header Row
		$objPHPExcel->setActiveSheetIndex($pageindex)
							->setCellValue('A3', 'Sl No')
							->setCellValue('B3', 'Invoice Date')
							->setCellValue('C3', 'Invoice No')
							->setCellValue('D3', 'PIN Details')
							->setCellValue('E3', 'Customer ID')
							->setCellValue('F3', 'Customer Name')
							->setCellValue('G3', 'Sales Person')
							->setCellValue('H3', 'Sales Person Email')
							->setCellValue('I3', 'Sales Person Cell')
							->setCellValue('J3', 'Invoice Amount')
							->setCellValue('K3', 'Received Amount')
							->setCellValue('L3', 'Outstanding Amount')
							->setCellValue('M3', 'Age (Days)')
							->setCellValue('N3', 'Status')
							->setCellValue('O3', 'Contact Person Name')
							->setCellValue('P3', 'General Emailid')
							->setCellValue('Q3', 'GM/Director Emailid')
							->setCellValue('R3', 'HR Head Emailid')
							->setCellValue('S3', 'IT-Head/EDP Emailid')
							->setCellValue('T3', 'Software User Emailid')
							->setCellValue('U3', 'Finance Head Emailid')
							->setCellValue('V3', 'Others Emailid');

		$j =4;
		$slno_count =0;
		while($fetch = mysqli_fetch_array($result))
		{
			
			//added for pin details
			
			$totaldesp=$fetch['description'];
		   
		    $totaldesparr=explode("*",$totaldesp);
		    $newdesp='';
		   
		    for($i=0;$i<count($totaldesparr);$i++)
		    {
		        $temparr=explode("$",$totaldesparr[$i]);
		       if($i>0)
		       {
		           $newdesp .= '/';
		       }
		        $temp=substr(preg_replace("/\([^)]+\)/","",$temparr[1]), 0, -2);
		        $newdesp .= $temp.'-'.$temparr[4];
		    }
			
			//ends
			
			$customeridfetch = substr($fetch['customerid'],-5,5);
			$generalemailid = '';$gmemailid = '';$hrheademailid = '';$itheademailid = '';$softwareemailid = '';
			$financeemailid = '';$othersemailid = '';
			$query2 = "Select group_concat(emailid) as emailid from inv_contactdetails where customerid = '".$customeridfetch."' and selectiontype = 'general'";
			$result2 = runmysqlqueryfetch($query2);
			if($result2['emailid'] <> '')
			{
				$generalemailid = trim(removedoublecomma($result2['emailid']),",");
			}
			$query3 = "Select group_concat(emailid) as emailid from inv_contactdetails where customerid = '".$customeridfetch."' and selectiontype = 'gm/director'";
			$result3 = runmysqlqueryfetch($query3);
			if($result3['emailid'] <> '')
			{
				$gmemailid = trim(removedoublecomma($result3['emailid']),",");
			}
			$query4 = "Select group_concat(emailid) as emailid from inv_contactdetails where customerid = '".$customeridfetch."' and selectiontype = 'hrhead'";
			$result4 = runmysqlqueryfetch($query4);
			if($result4['emailid'] <> '')
			{
				$hrheademailid = trim(removedoublecomma($result4['emailid']),",");
			}
			
			$query5 = "Select group_concat(emailid) as emailid from inv_contactdetails where customerid = '".$customeridfetch."' and selectiontype = 'ithead/edp'";
			$result5 = runmysqlqueryfetch($query5);
			if($result5['emailid'] <> '')
			{
				$itheademailid = trim(removedoublecomma($result5['emailid']),",");
			}
			
			$query6 = "Select group_concat(emailid) as emailid from inv_contactdetails where customerid = '".$customeridfetch."' and selectiontype = 'softwareuser'";
			$result6 = runmysqlqueryfetch($query6);
			if($result6['emailid'] <> '')
			{
				$softwareemailid = trim(removedoublecomma($result6['emailid']),",");
			}
			
			$query7= "Select group_concat(emailid) as emailid from inv_contactdetails where customerid = '".$customeridfetch."' and selectiontype = 'financehead'";
			$result7 = runmysqlqueryfetch($query7);
			if($result7['emailid'] <> '')
			{
				$financeemailid = trim(removedoublecomma($result7['emailid']),",");
			}
			
			$query8 = "Select group_concat(emailid) as emailid from inv_contactdetails where customerid = '".$customeridfetch."' and selectiontype = 'others'";
			$result8 = runmysqlqueryfetch($query8);
			if($result8['emailid'] <> '')
			{
				$financeemailid = trim(removedoublecomma($result8['emailid']),",");
			}
			
			if($fetch['outstandingamount'] < 0)
				$outstandingamount = '0';
			else
				$outstandingamount = $fetch['outstandingamount'];
			$slno_count++;
			$mySheet->setCellValue('A' . $j,$slno_count)
					->setCellValue('B' . $j,changedateformat($fetch['createddate']))
					->setCellValue('C' . $j,$fetch['invoiceno'])
					->setCellValue('D' . $j,$newdesp)
					->setCellValue('E' . $j,$fetch['customerid'])
					->setCellValue('F' . $j,$fetch['businessname'])
					->setCellValue('G' . $j,$fetch['dealername'])
					->setCellValue('H' . $j,$fetch['dealeremail'])
					->setCellValue('I' . $j,$fetch['dealercell'])
					->setCellValue('J' . $j,$fetch['netamount'])
					->setCellValue('K' . $j,$fetch['receiptamount'])
					->setCellValue('L' . $j,$outstandingamount)
					->setCellValue('M' . $j,$fetch['age'])
					->setCellValue('N' . $j,$fetch['status'])
					->setCellValue('O' . $j,$fetch['contactperson'])
					->setCellValue('P' . $j,$generalemailid)
					->setCellValue('Q' . $j,$gmemailid)
					->setCellValue('R' . $j,$hrheademailid)
					->setCellValue('S' . $j,$itheademailid)
					->setCellValue('T' . $j,$softwareemailid)
					->setCellValue('U' . $j,$financeemailid)
					->setCellValue('V' . $j,$othersemailid);
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
					->setCellValue('E'. ($highestRow + 2), 'Total Invoice')
					->setCellValue('E'. ($highestRow + 3), 'Total Outstanding');

					
				$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('I'. ($highestRow + 2), ($highestRow-3))
					->setCellValue('I'. ($highestRow + 3), "=SUM(L4:L".($highestRow).")");
				
				//set the default width for column
				$mySheet->getColumnDimension('A')->setWidth(6);
				$mySheet->getColumnDimension('B')->setWidth(15);
				$mySheet->getColumnDimension('C')->setWidth(15);
				$mySheet->getColumnDimension('D')->setWidth(30);
				$mySheet->getColumnDimension('E')->setWidth(20);
				$mySheet->getColumnDimension('F')->setWidth(40);
				$mySheet->getColumnDimension('G')->setWidth(15);
				$mySheet->getColumnDimension('H')->setWidth(15);
				$mySheet->getColumnDimension('I')->setWidth(25);
				$mySheet->getColumnDimension('J')->setWidth(25);
				$mySheet->getColumnDimension('K')->setWidth(25);
				$mySheet->getColumnDimension('L')->setWidth(25);
				$mySheet->getColumnDimension('M')->setWidth(25);
				$mySheet->getColumnDimension('N')->setWidth(30);
				$mySheet->getColumnDimension('O')->setWidth(30);
				$mySheet->getColumnDimension('P')->setWidth(30);
				$mySheet->getColumnDimension('Q')->setWidth(30);
				$mySheet->getColumnDimension('R')->setWidth(30);
				$mySheet->getColumnDimension('S')->setWidth(30);
				$mySheet->getColumnDimension('T')->setWidth(30);
				$mySheet->getColumnDimension('U')->setWidth(30);
				$mySheet->getColumnDimension('V')->setWidth(30);
				
				
				$pageindex++;

				$addstring = "/user";
				if($_SERVER['HTTP_HOST'] == "rashmihk" || $_SERVER['HTTP_HOST'] == "archanaab"|| $_SERVER['HTTP_HOST'] == "meghanab")
					$addstring = "/saralimax-user";
				
						$query = 'select slno,fullname from inv_mas_users where slno = '.$userid.'';
						$fetchres = runmysqlqueryfetch($query);
						$username = $fetchres['fullnaame'];
						$localdate = datetimelocal('Ymd');
						$localtime = datetimelocal('His');
						$filebasename = "OutstandingRegister".$localdate."-".$localtime."-".strtolower($username).".xls";
			
						$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_outstandingregister_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
						$result = runmysqlquery($query1);
						
						$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','81','".date('Y-m-d').' '.date('H:i:s')."','excel_outstandingregister_report".'-'.strtolower($username)."')";
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
