<?php
//ini_set("display_errors",0);
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
$todate = changedateformat($_POST['todate']);
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
$seriesnew = $_POST['seriesnew'];
$usagetype = $_POST['usagetype'];
$purchasetype = $_POST['purchasetype'];
$cancelledinvoice = $_POST['cancelledinvoice'];
$alltimecheck = $_POST['alltime'];
$receiptstatus = $_POST['receiptstatus'];
$datepiece = ($alltimecheck == 'on')?(""):(" AND (left(inv_dealer_invoicenumbers.createddate,10) between '".$fromdate."' AND '".$todate."') ");	

$cks_value = $_POST['itemarray'];
for ($i = 0;$i < count($cks_value);$i++)
{
	$k_value .= $cks_value[$i]."," ;
}

$itemvalue = rtrim($k_value , ',');
$itemlist = str_replace('\\','',$itemvalue);
$itemlistsplit = explode(',',$itemlist);
$itemlistsplitcount = count($itemlistsplit);

$querydrop = "Drop table if exists productgroupsexcel;";
$result2 = runmysqlquery($querydrop);
	
// Create a temporary table which holds product group data
	
$queryproductgroup = "CREATE TEMPORARY TABLE `productgroupsexcel` ( 
	`slno` int(10) NOT NULL auto_increment, 
	 `productcode` int(10) default NULL,      
	 `productgroup` varchar(100) collate latin1_general_ci default NULL, 
	 PRIMARY KEY  (`slno`)    
 );";
$resultproductgroup = runmysqlquery($queryproductgroup);

if($usagetype == 'addlic')
{
	$usagetypevalue = 'singleuser';
	$addlicence = "AND inv_dealercard.addlicence = 'yes'";
}
elseif($usagetype == 'singleuser')
{
	$usagetypevalue = 'singleuser';
	$addlicence = '';
}elseif($usagetype == 'multiuser')
{
	$usagetypevalue = 'multiuser';
	$addlicence = '';
}
if($chks != '')
{
	for($i = 0;$i< $productlistsplitcount; $i++)
	{
		if($i < ($productlistsplitcount-1))
			$appendor = 'or'.' ';
		else
			$appendor = '';
			
		// Get the product group of each product 
				
		$querygetproductgroup = "select * from inv_mas_product where productcode = '".$productlistsplit[$i]."'";
		$resultproductgroup = runmysqlqueryfetch($querygetproductgroup);
		 
		// Insert to temporary table 
		$queryinsertproductgroup = "insert into `productgroupsexcel`(productcode,productgroup) values('".$resultproductgroup['productcode']."','".$resultproductgroup['group']."')";
		$resultofinsert = runmysqlquery($queryinsertproductgroup);
		
		$finalproductlist .= ' inv_dealer_invoicenumbers.products'.' '.'like'.' "'.'%'.$productlistsplit[$i].'%'.'" '.$appendor."";
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
			
		$finalitemlist .= ' inv_dealer_invoicenumbers.servicedescription'.' '.'like'.' "'.'%'.$itemlistsplit[$j].'%'.'" '.$appendor1."";
	}
}

if(($cks_value != '') && ($chks != ''))
	$finallistarray = ' AND ('.$finalproductlist.' '.'OR'.' '.$finalitemlist.')';
elseif($chks == '')
	$finallistarray = ' AND ('.$finalitemlist.')';
elseif($cks_value == '')
	$finallistarray = ' AND ('.$finalproductlist.')';

$modulepiece = ($generatedbysplit[1] == "[U]")?("user_module"):("dealer_module");
$regionpiece = ($region == "")?(""):(" AND inv_dealer_invoicenumbers.regionid = '".$region."' ");

$state_typepiece = ($state == "")?(""):(" AND inv_mas_district.statecode = '".$state."' ");
$district_typepiece = ($district == "")?(""):(" AND inv_mas_dealer.district = '".$district."' ");
$dealer_typepiece = ($dealer == "")?(""):(" AND inv_dealer_invoicenumbers.dealerreference = '".$dealer."' ");
$branchpiece = ($branch == "")?(""):(" AND inv_dealer_invoicenumbers.branchid = '".$branch."' ");
$generatedpiece = ($generatedby == "")?(""):(" AND inv_dealer_invoicenumbers.createdbyid = '".$generatedbysplit[0]."' and inv_dealer_invoicenumbers.module = '".$modulepiece."'");
$seriespiece = ($series == "")?(""):(" AND inv_dealer_invoicenumbers.category = '".$series."' ");
$seriespiecenew = ($seriesnew == "")?(""):(" AND inv_dealer_invoicenumbers.state_info = '".$seriesnew."' ");
$usagetypepiece = ($usagetype == "")?(""):(" AND inv_dealercard.usagetype = '".$usagetypevalue."' ".$addlicence."  ");
$purchasetypepiece = ($purchasetype == "")?(""):(" AND inv_dealercard.purchasetype = '".$purchasetype."' ");
$statuspiece = ($status == "")?(""):(" AND inv_dealer_invoicenumbers.status = '".$status."'");
$cancelledpiece = ($cancelledinvoice == "on")?("AND inv_dealer_invoicenumbers.status <> 'CANCELLED'"):("");
$receiptstatuspiece = ($receiptstatus == "")?(""):(" and inv_mas_receipt.status = '".$receiptstatus."' ");

$todaynewtotal = 0 ;
$todayupdationtotal = 0;
$tdstotal = 0;
$tdsnew = 0; 
$tdsupdation = 0;
$spptotal = 0; 
$sppnew = 0; 
$sppupdation = 0;
$stototal = 0;
$stonew = 0 ; 
$stoupdation = 0;
$svhtotal = 0;
$svhnew = 0;
$svhupdation = 0;
$svitotal = 0; 
$svinew = 0 ; 
$sviupdation = 0;
$sactotal = 0;
$sacnew = 0;
$sacupdation = 0;
$otherstotal = 0;
$othersnew = 0 ; 
$othersupdation = 0;
$overalltotal = 0;	
$amcttoday = 0;
$attinttoday =0 ; $custtoday =0 ; $eiptoday = 0; $implementationtoday = 0; $pptoday = 0; $smstoday = 0; 
$supporttoday = 0; $tastoday = 0; $trainingtoday = 0; 


if($flag == '')
{
	$url = '../home/index.php?a_link=invoiceregister'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
	$querycase = "select distinct inv_dealer_invoicenumbers.slno,left(inv_dealer_invoicenumbers.createddate,10) as createddate, 
	inv_dealer_invoicenumbers.invoiceno, inv_dealer_invoicenumbers.gst_no,inv_dealer_invoicenumbers.businessname,
	inv_dealer_invoicenumbers.contactperson, inv_dealer_invoicenumbers.description,inv_dealer_invoicenumbers.servicedescription,
	inv_dealer_invoicenumbers.amount,(IFNULL(inv_dealer_invoicenumbers.igst,0)+IFNULL(inv_dealer_invoicenumbers.sgst,0)+IFNULL(inv_dealer_invoicenumbers.cgst,0)) as servicetax,
	inv_dealer_invoicenumbers.igst,inv_dealer_invoicenumbers.cgst,inv_dealer_invoicenumbers.sgst,inv_dealer_invoicenumbers.netamount,inv_dealer_invoicenumbers.dealername,inv_dealer_invoicenumbers.createdby,
	inv_dealer_invoicenumbers.address,inv_dealer_invoicenumbers.place,inv_dealer_invoicenumbers.region,inv_dealer_invoicenumbers.branch,
	inv_dealer_invoicenumbers.emailid,inv_dealer_invoicenumbers.stdcode,inv_dealer_invoicenumbers.phone,inv_dealer_invoicenumbers.cell,
	inv_dealer_invoicenumbers.status,inv_dealer_invoicenumbers.productquantity 
	from inv_dealer_invoicenumbers
	left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealer_invoicenumbers.dealerreference
	left join inv_dealercard on inv_dealercard.invoiceid = inv_dealer_invoicenumbers.slno
	left join inv_mas_district on inv_mas_district.districtcode = inv_mas_dealer.district
	left join inv_mas_branch on inv_mas_branch.branchname = inv_dealer_invoicenumbers.branch
	left join inv_mas_receipt on inv_mas_receipt.dealerinvoiceno = inv_dealer_invoicenumbers.slno
	left join inv_mas_region on inv_mas_region.category = inv_dealer_invoicenumbers.region";
	switch($databasefield)
	{
		case "contactperson": 
			$query = $querycase." where inv_dealer_invoicenumbers.contactperson LIKE '%".$textfield."%' ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew." order by inv_dealer_invoicenumbers.createddate desc";
			break;
		case "phone":
			$query = " where inv_dealer_invoicenumbers.phone LIKE '%".$textfield."%' OR inv_dealer_invoicenumbers.cell LIKE '%".$textfield."%'  ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  order by inv_dealer_invoicenumbers.createddate desc ";
			break;
		case "place":
			$query = $querycase." where inv_dealer_invoicenumbers.place LIKE '%".$textfield."%' ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  order by inv_dealer_invoicenumbers.createddate desc";
			break;
		case "emailid":
			$query = $querycase." where inv_dealer_invoicenumbers.emailid LIKE '%".$textfield."%'  ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew." order by inv_dealer_invoicenumbers.createddate desc";
			break;
			case "cardid":
				$query = $querycase." where inv_mas_scratchcard.cardid LIKE '%".$textfield."%' ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  order by inv_dealer_invoicenumbers.createddate desc";
				break;
		case "scratchnumber":
			$query = $querycase." where inv_mas_scratchcard.scratchnumber LIKE '%".$textfield."%'  ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  order by inv_dealer_invoicenumbers.createddate desc";
			break;
		
		case "invoiceno":
			$query = $querycase." where inv_dealer_invoicenumbers.invoiceno LIKE '%".$textfield."%' ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  order by inv_dealer_invoicenumbers.createddate desc";
			break;
		case "invoiceamt":
			$query = $querycase." where inv_dealer_invoicenumbers.netamount LIKE '%".$textfield."%' ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  order by inv_dealer_invoicenumbers.createddate desc";
			break;
		
		default:
			$query = $querycase." where inv_dealer_invoicenumbers.businessname LIKE '%".$textfield."%' ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  ORDER BY invoiceno";
			break;
	} 
			
			
		//echo $query;
		//exit();				
		//echo($query); exit;
		$result = runmysqlquery($query);
		
		// Create new PHPExcel object
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
		$mySheet->setTitle('Dealer Invoice Details');
			
		//Apply style for header Row
		$mySheet->getStyle('A3:V3')->applyFromArray($styleArray);
		
		//Merge the cell
		$mySheet->mergeCells('A1:V1');
		$mySheet->mergeCells('A2:V2');
		$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'Invoice Details Report');
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
	
		
		//Fille contents for Header Row
		$objPHPExcel->setActiveSheetIndex($pageindex)
							->setCellValue('A3', 'Sl No')
							->setCellValue('B3', 'Invoice Date')
							->setCellValue('C3', 'Invoice No')
							->setCellValue('D3', 'Dealer Name')
							->setCellValue('E3', 'Dealer GSTIN')
							->setCellValue('F3', 'Contact Person')
							->setCellValue('G3', 'Products')
							->setCellValue('H3', 'Quantity')
							->setCellValue('I3', 'Sale Value')
							->setCellValue('J3', 'Tax Amount')
							->setCellValue('K3', 'Invoice Amount')
							->setCellValue('L3', 'Sales Person')
							->setCellValue('M3', 'Prepared By')
							->setCellValue('N3', 'Address')
							->setCellValue('O3', 'Place')
							->setCellValue('P3', 'Region')
							->setCellValue('Q3', 'Branch')
							->setCellValue('R3', 'Stdcode')
							->setCellValue('S3', 'Phone')
							->setCellValue('T3', 'Cell')
							->setCellValue('U3', 'Email Id')
							->setCellValue('V3', 'Status');

		$j =4;
		$slno_count =0;
		while($fetch = mysqli_fetch_array($result))
		{
			$slno_count++;
			$productlist = $fetch['description'];
			$productlistsplit = explode('*',$productlist);
			$productstaken = '';
			$productcount = 0;
			##Changed By Bhavesh##
			for($i=0;$i<count($productlistsplit);$i++)
			{
				$products = explode('$',$productlistsplit[$i]);
				if($productcount > 0)
					$productstaken .= ', ';
				if($products[1]<> '')
				{
					$productstaken .= $products[1].','.$products[2].','.$products[3];
				}
				else
				{ $productstaken .=''; }
				$productcount ++;
			}
			
			$serviceproduct = $fetch['servicedescription'];
			$serviceproductlistsplit = explode('*',$serviceproduct);
			$serviceproductstaken = '';
			$serviceproductcount = 0;
			
			for($i=0;$i<count($serviceproductlistsplit);$i++)
			{
				$serviceproducts = explode('$',$serviceproductlistsplit[$i]);
				if($serviceproductcount > 0)
					$serviceproductstaken .= ', ';
				if($serviceproducts[1]<> '')
				{
					$serviceproductstaken .= $serviceproducts[1];
				}
				else
				{ $serviceproductstaken .=''; }
				$serviceproductcount ++;
			}
			
			if($productlist!= "" && $serviceproduct!="")
			{
				$totalproductlist = $productstaken.",".$serviceproductstaken;
			}
			else if($productlist!= "")
			{
				$totalproductlist = $productstaken;
			}
			else if($serviceproduct!="")
			{
				$totalproductlist = $serviceproductstaken;
			}
			
			$quantity = 0;
			$productquantity = explode(',',$fetch['productquantity']);
			$productcount1 = count($productquantity);
			for($i=0; $i< $productcount1; $i++)
			{
				 $quantity += (int)$productquantity[$i];
			}
			

			if($fetch['createddate'] > date('2017-07-01 h:i:s'))
			{
				$new_amount = $fetch['cgst'] + $fetch['sgst'] + $fetch['igst'];
				$new_net_amount = $fetch['amount'] + $new_amount;
				//$new_net_amount = round($new_net_amount ,2);
				$new_net_amount = $fetch['netamount'];
			}
	
			$newgst = " ".$invoice_new_gst_no." ";
			$mySheet->setCellValue('A' . $j,$slno_count)
					->setCellValue('B' . $j,changedateformat($fetch['createddate']))
					->setCellValue('C' . $j,$fetch['invoiceno'])
					->setCellValue('D' . $j,$fetch['businessname'])
					->setCellValue('E' . $j,$fetch['gst_no'])
					->setCellValue('F' . $j,$fetch['contactperson'])
					->setCellValue('G' . $j,$totalproductlist)
					->setCellValue('H' . $j,$quantity)
					->setCellValue('I' . $j,$fetch['amount'])
					->setCellValue('J' . $j,$new_amount)
					->setCellValue('K' . $j,$new_net_amount)
					->setCellValue('L' . $j,$fetch['dealername'])
					->setCellValue('M' . $j,$fetch['createdby'])
					->setCellValue('N' . $j,stripslashes(stripslashes($fetch['address'])))
					->setCellValue('O' . $j,$fetch['place'])
					->setCellValue('P' . $j,$fetch['region'])
					->setCellValue('Q' . $j,$fetch['branch'])
					->setCellValue('R' . $j,$fetch['stdcode'])
					->setCellValue('S' . $j,$fetch['phone'])
					->setCellValue('T' . $j,$fetch['cell'])
					->setCellValue('U' . $j,$fetch['emailid'])
					->setCellValue('V' . $j,$fetch['status']);
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
			->setCellValue('E'. ($highestRow + 2), 'Total Invoices')
			->setCellValue('E'. ($highestRow + 3), 'Total Sale Value')
			->setCellValue('E'. ($highestRow + 4), 'Total Tax ')
			->setCellValue('E'. ($highestRow + 5), 'Total Amount ');
		
		$objPHPExcel->setActiveSheetIndex($pageindex)
			->setCellValue('F'. ($highestRow + 2), ($highestRow-3))
			->setCellValue('F'. ($highestRow + 3), "=SUM(I4:I".($highestRow).")")
			->setCellValue('F'. ($highestRow + 4), "=SUM(J4:J".($highestRow).")")
			->setCellValue('F'. ($highestRow + 5), "=SUM(K4:K".($highestRow).")");
		
		//set the default width for column
		$mySheet->getColumnDimension('A')->setWidth(6);
		$mySheet->getColumnDimension('B')->setWidth(15);
		$mySheet->getColumnDimension('C')->setWidth(15);
		$mySheet->getColumnDimension('D')->setWidth(20);
		$mySheet->getColumnDimension('E')->setWidth(40);
		$mySheet->getColumnDimension('F')->setWidth(15);
		$mySheet->getColumnDimension('G')->setWidth(50);
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
		// Create Temporary tables 
		
		
		$querydrop1 = "Drop table if exists invoicedetailssearchexcel;";
		$result1 = runmysqlquery($querydrop1);

		$querydrop2 = "Drop table if exists servicessearchexcel;";
		$result2 = runmysqlquery($querydrop2);
	 
		// Create Temporary table to insert 'ITEM SOFTWARE' details
		$queryservices = "CREATE TEMPORARY TABLE `servicessearchexcel` ( 
		`slno` int(10) NOT NULL auto_increment, 
		 `invoiceno` int(10) default NULL,      
		 `servicename` varchar(100) collate latin1_general_ci default NULL, 
		 `serviceamount` varchar(10) collate latin1_general_ci default NULL, 
		 `createddate` datetime default '0000-00-00 00:00:00',
		`dealerid` varchar(25) collate latin1_general_ci default NULL, 
		`regionid` varchar(25) collate latin1_general_ci default NULL,   
		`branch` varchar(25) collate latin1_general_ci default NULL,  
		`branchname` varchar(25) collate latin1_general_ci default NULL, 
		`category` varchar(25) collate latin1_general_ci default NULL, 
		`state_info` varchar(25) collate latin1_general_ci default NULL,  
		 PRIMARY KEY  (`slno`)    
	 );";
		$resultservices = runmysqlquery($queryservices);

		$queryproducts = "CREATE TEMPORARY TABLE `invoicedetailssearchexcel` (                                       
				  `slno` int(10) NOT NULL auto_increment,                             
				  `invoiceno` int(10) default NULL,                                   
				  `productcode` varchar(10) collate latin1_general_ci default NULL,   
				  `usagetype` varchar(50) collate latin1_general_ci default NULL,     
				  `amount` varchar(25) collate latin1_general_ci default NULL,        
				  `purchasetype` varchar(25) collate latin1_general_ci default NULL,
				  `dealerid` varchar(25) collate latin1_general_ci default NULL, 
				  `invoicedate` datetime default '0000-00-00 00:00:00',
				  `productgroup` varchar(25) collate latin1_general_ci default NULL, 
				  `regionid` varchar(25) collate latin1_general_ci default NULL,   
				  `branch` varchar(25) collate latin1_general_ci default NULL,  
				  `branchname` varchar(25) collate latin1_general_ci default NULL,  
				  `category` varchar(25) collate latin1_general_ci default NULL,
				  `scratchnumber` varchar(25) collate latin1_general_ci default NULL,   
				  `cardid` varchar(25) collate latin1_general_ci default NULL,
				  `state_info` varchar(25) collate latin1_general_ci default NULL,      
				   PRIMARY KEY  (`slno`)                                               
				);";
		$resultproducts = runmysqlquery($queryproducts);	
		
	
		// Create Temporary Table to insert Invoice details
		$queryaddless = "CREATE TEMPORARY TABLE `addlessdescsearchexcel` ( 
			`slno` int(10) NOT NULL auto_increment, 
			 `invoiceno` int(10) default NULL,      
			 `descname` varchar(100) collate latin1_general_ci default NULL, 
			 `descamount` varchar(10) collate latin1_general_ci default NULL, 
			 `createddate` datetime default '0000-00-00 00:00:00',
			`dealerid` varchar(25) collate latin1_general_ci default NULL, 
			`regionid` varchar(25) collate latin1_general_ci default NULL,   
			`branch` varchar(25) collate latin1_general_ci default NULL,  
			`branchname` varchar(25) collate latin1_general_ci default NULL, 
			 PRIMARY KEY  (`slno`)    
		 );";
		$result = runmysqlquery($queryaddless);
		
		
		$resultsummary = runmysqlquery($query);
		
		// Add data to temporary table.
		
		// For all Search Result 
		while($fetch0 = mysqli_fetch_array($resultsummary))
		{
			// Now insert selected invoice details to temporary table condidering all details of the each invoice
			
			$query2 = "select * from inv_dealer_invoicenumbers where slno = '".$fetch0['slno']."'";
			$fetch2 = runmysqlqueryfetch($query2); //echo($query2);exit;
			// Insert data to services table
			$serviceamount = 0;
			if($fetch2['servicedescription'] <> '')
			{
				$serviceamountsplit = explode('*',$fetch2['servicedescription']);
				for($k = 0 ;$k < count($serviceamountsplit);$k++)
				{
					$finalsplit = explode('$',$serviceamountsplit[$k]); //echo($offerdescriptionsplit[$j]);exit;
					$serviceamount = $serviceamount + $finalsplit[2];
					// Insert into services table 
					$insertservices = "INSERT INTO servicessearchexcel(invoiceno,servicename,serviceamount,createddate,dealerid,regionid,branch,branchname,category,state_info) values('".$fetch2['slno']."','". $finalsplit[1]."','". $finalsplit[2]."','".$fetch2['createddate']."','".$fetch2['dealerid']."','".$fetch2['regionid']."','".$fetch2['branchid']."','".$fetch2['branch']."','".$fetch2['category']."','".$fetch2['state_info']."')";
					$result = runmysqlquery($insertservices);
				}
			}
			
			// Insert data to invoice detals table 
			
			if($fetch2['products'] <> '')
			{
				$count++;
				$totalamount = 0;
				$products = explode('#',$fetch2['products']);
				
				$description = explode('*',$fetch2['description']);
				$productquantity = explode(',',$fetch2['productquantity']);
				for($i=0,$j = 0; $i < count($description),$j < count($productquantity);$i++,$j++)
				{
					$totalamount = 0;
					$splitdescription = explode('$',$description[$i]);
					$scratchnumber = $splitdescription[4];
					$cardidsplit = $splitdescription[5];
					$amount = $splitdescription[6];
						//echo($usagetype.'^'.$amount.'^'.$purchasetype); exit;
					if($productquantity[$j] == 1)
					{
						$productcode = $products[$i];
						$usagetype = $splitdescription[3];
						$cardid = $splitdescription[5];
						$purchasetype = $splitdescription[2];
						$totalamount = $amount;
						// Fetch Product 	
						$query1 = "select inv_mas_product.group as productgroup from inv_mas_product where productcode = '".$productcode."' ";
						// echo($query1);echo('<br/>');
						$result1 = runmysqlqueryfetch($query1);
						
						// Insert into invoice details table
						$query3 = "insert into invoicedetailssearchexcel(invoiceno,productcode,usagetype,amount,purchasetype,dealerid,invoicedate,productgroup,regionid,branch,branchname,category,scratchnumber,cardid,state_info) values('".$fetch2['slno']."','".$productcode."','".$usagetype."','".$totalamount."','".$purchasetype."','".$fetch2['dealerid']."','".$fetch2['createddate']."','".$result1['productgroup']."','".$fetch2['regionid']."','".$fetch2['branchid']."','".$fetch2['branch']."','".$fetch2['category']."','".$scratchnumber."','".$cardid."','".$fetch2['state_info']."')"; 
						$result3 =  runmysqlquery($query3);
					}
					else
					{
						// echo $cardid; echo "<br>";
						$cardidsplitarray = explode('-',$cardidsplit);
						$querycard = "select * from inv_dealercard where cardid between ".$cardidsplitarray[0]." AND ".$cardidsplitarray[1];
						$resultcard = runmysqlquery($querycard);
						$cardamtcount = 0;
						while($fetchcard = mysqli_fetch_array($resultcard))
						{
							$productcode = $fetchcard['productcode'];
							$usagetype = $fetchcard['usagetype'];
							$cardid = $fetchcard['cardid'];
							$purchasetype = $fetchcard['purchasetype'];
							if($cardamtcount == 0)
								$totalamount = $amount ;
							else 
								$totalamount = 0;
							// Fetch Product 	
							$query1 = "select inv_mas_product.group as productgroup from inv_mas_product where productcode = '".$productcode."' ";
							// echo($query1);echo('<br/>');
							$result1 = runmysqlqueryfetch($query1);

							// Insert into invoice details table
							$query3 = "insert into invoicedetailssearchexcel(invoiceno,productcode,usagetype,amount,purchasetype,dealerid,invoicedate,productgroup,regionid,branch,branchname,category,scratchnumber,cardid,state_info) values('".$fetch2['slno']."','".$productcode."','".$usagetype."','".$totalamount."','".$purchasetype."','".$fetch2['dealerid']."','".$fetch2['createddate']."','".$result1['productgroup']."','".$fetch2['regionid']."','".$fetch2['branchid']."','".$fetch2['branch']."','".$fetch2['category']."','".$scratchnumber."','".$cardid."','".$fetch2['state_info']."')"; 
							$result3 =  runmysqlquery($query3);
							$cardamtcount++;
						}
					}
				}
			}	
		}
		//exit;
		$pageindex++;
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($pageindex);
	
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet($pageindex);
		
		//Set the worksheet name
		$mySheet->setTitle('Product Wise Summary');
		
		$currentrow = 1;
		$slno1 = 0;
		//Set heading
		$mySheet->setCellValue('A'.$currentrow,'Items (Software)');
		
		$styleArray1 = array(
							'font' => array('bold' => true,));
		
	
		$mySheet->getStyle('A1:D1')->applyFromArray($styleArray1);
		
		
		$currentrow++;
		//Set table headings
		$objPHPExcel->setActiveSheetIndex($pageindex)
				->setCellValue('A'.$currentrow,'Product')
				->setCellValue('B'.$currentrow,'New')
				->setCellValue('C'.$currentrow,'Updation')
				->setCellValue('D'.$currentrow,'Total');
				
		
		$j = 3;		
		//Apply style for header Row
		$mySheet->getStyle('A'.$currentrow.':D'.$currentrow)->applyFromArray($styleArray);
		$currentrow++;
		$databeginrow = $currentrow;
		
		$querygetgroups = "select distinct productgroup from productgroupsexcel";
		$resultgetgroups = runmysqlquery($querygetgroups);
		$countgroups = mysqli_num_rows($resultgetgroups);
		$groups = '';
		while($fetch10 = mysqli_fetch_array($resultgetgroups))
		{
			if($groups == '')
				$groups = $fetch10['productgroup'];
			else
				$groups = $groups.','.$fetch10['productgroup'];
		}
		$splitgroup = explode(',',$groups);
		$tdsnew = 0;
		$tdsupdation = 0;
		$totaltds = 0;
		$sppnew = 0;
		$sppupdation = 0;
		$spptotal = 0;
		$stonew = 0;
		$stoupdation = 0 ; 
		$stototal = 0;
		$svhnew = 0;
		$svhupdation = 0 ;
		$svhtotal = 0;
		$svinew = 0;
		$sviupdation = 0;
		$svitotal = 0;
		$sacnew = 0;
		$sacupdation = 0;
		$sactotal = 0;
		$othersnew = 0;
		$othersupdation = 0;
		$otherstotal = 0;
		$xbrlnew = 0;
		$xbrlupdation = 0;
		$xbrltotal = 0;
		//Fetch Group by Product Amount on Type NEW
		$querynewpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailssearchexcel where purchasetype = 'New' group by productgroup;";
		$resultnewpurchase = runmysqlquery($querynewpurchase);
		while($fetchnewpurchase = mysqli_fetch_array($resultnewpurchase))
		{
			if($fetchnewpurchase['productgroup'] == 'TDS')
				$tdsnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'SPP')
				$sppnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'STO')
				$stonewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'SVH')
				$svhnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'SVI')
				$svinewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'SAC')
				$sacnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'XBRL')
				$xbrlnewpurchase = $fetchnewpurchase['amount'];
			else	
				$othersnewpurchase = $othersnewpurchase + $fetchnewpurchase['amount'];
		}
		
		//Fetch Group by Product Amount on Type UPDATION
		$queryupdationpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailssearchexcel where purchasetype = 'Updation'  group by productgroup;";
		$resultupdationpurchase = runmysqlquery($queryupdationpurchase);
		while($fetchupdationpurchase = mysqli_fetch_array($resultupdationpurchase))
		{
			if($fetchupdationpurchase['productgroup'] == 'TDS')
				$tdsupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'SPP')
				$sppupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'STO')
				$stoupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'SVH')
				$svhupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'SVI')
				$sviupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'SAC')
				$sacupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'XBRL')
				$xbrlupdationpurchase = $fetchupdationpurchase['amount'];
			else	
				$othersupdationpurchase = $othersupdationpurchase + $fetchupdationpurchase['amount'];
		}

		$tdstotal = $tdsnewpurchase + $tdsupdationpurchase;
		$spptotal = $sppnewpurchase + $sppupdationpurchase;
		$stototal = $stonewpurchase + $stoupdationpurchase;
		$svhtotal = $svhnewpurchase + $svhupdationpurchase;
		$svitotal = $svinewpurchase + $sviupdationpurchase;
		$sactotal = $sacnewpurchase + $sacupdationpurchase;
		$xbrltotal = $xbrlnewpurchase + $xbrlupdationpurchase;
		$otherstotal = $othersnewpurchase + $othersupdationpurchase;

		$mySheet->setCellValue('A'.$j,'TDS')
				->setCellValue('B'.$j,($tdsnewpurchase))
				->setCellValue('C'.$j,($tdsupdationpurchase))
				->setCellValue('D'.$j,($tdstotal));
		$j++;
		$currentrow++;	
		
		$mySheet->setCellValue('A'.$j,'SPP')
				->setCellValue('B'.$j,($sppnewpurchase))
				->setCellValue('C'.$j,($sppupdationpurchase))
				->setCellValue('D'.$j,($spptotal));
		$j++;
		$currentrow++;
		
		$mySheet->setCellValue('A'.$j,'STO')
				->setCellValue('B'.$j,($stonewpurchase))
				->setCellValue('C'.$j,($stoupdationpurchase))
				->setCellValue('D'.$j,($stototal));
		$j++;
		$currentrow++;
		
		$mySheet->setCellValue('A'.$j,'SVH')
				->setCellValue('B'.$j,($svhnewpurchase))
				->setCellValue('C'.$j,($svhupdationpurchase))
				->setCellValue('D'.$j,($svhtotal));
		$j++;
		$currentrow++;
		
		$mySheet->setCellValue('A'.$j,'SVI')
				->setCellValue('B'.$j,($svinewpurchase))
				->setCellValue('C'.$j,($sviupdationpurchase))
				->setCellValue('D'.$j,($svitotal));
		$j++;
		$currentrow++;
		
		$mySheet->setCellValue('A'.$j,'SAC')
				->setCellValue('B'.$j,($sacnewpurchase))
				->setCellValue('C'.$j,($sacupdationpurchase))
				->setCellValue('D'.$j,($sactotal));
		$j++;
		$currentrow++;
		
		$mySheet->setCellValue('A'.$j,'XBRL')
				->setCellValue('B'.$j,($xbrlnewpurchase))
				->setCellValue('C'.$j,($xbrlupdationpurchase))
				->setCellValue('D'.$j,($xbrltotal));
		$j++;
		$currentrow++;
		
		$mySheet->setCellValue('A'.$j,'OTHERS')
				->setCellValue('B'.$j,($othersnewpurchase))
				->setCellValue('C'.$j,($othersupdationpurchase))
				->setCellValue('D'.$j,($otherstotal));
		$j++;
		$currentrow++;
	
		$mySheet->setCellValue('A'.$currentrow,'Total')
				->setCellValue('B'.$currentrow,"=SUM(B".$databeginrow.":B".($currentrow - 1).")")
				->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow - 1).")")
				->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow - 1).")");
		$mySheet->getCell('B'.$currentrow)->getCalculatedValue();		
		$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('D'.$currentrow)->getCalculatedValue();	
			
		$mySheet->getStyle('A'.$databeginrow.':D'.$currentrow)->applyFromArray($styleArrayContent);
		$mySheet->getColumnDimension('A')->setWidth(10);
		$mySheet->getColumnDimension('B')->setWidth(25);
		$mySheet->getColumnDimension('C')->setWidth(25);
		$mySheet->getColumnDimension('D')->setWidth(25);	
			
		// Increment the no of rows to give line space.
		$currentrow = $currentrow + 2;	
		
		$mySheet->setCellValue('A'.$currentrow,'Items (Services)');
		$mySheet->getStyle('A'.$currentrow.':B'.$currentrow)->applyFromArray($styleArray1);
		
		$currentrow++;
		//Set table headings
		$objPHPExcel->setActiveSheetIndex($pageindex)
				->setCellValue('A'.$currentrow,'Service Name')
				->setCellValue('B'.$currentrow,'Total');

		//Apply style for header Row
		$mySheet->getStyle('A'.$currentrow.':B'.$currentrow)->applyFromArray($styleArray);
		$currentrow++;
		$databeginrow = $currentrow;
		$j = $currentrow;
		
		$servicesall = "select ifnull(services.netamount,'0') as amount,inv_mas_service.servicename from inv_mas_service left join (select ifnull(sum(serviceamount),'0') as netamount,servicename from servicessearchexcel  group by servicename) as services on services.servicename = inv_mas_service.servicename order by inv_mas_service.servicename";
		$resultallservices = runmysqlquery($servicesall);
		$totalservices = 0;
		$i_n = 0;

		while($fetchallservices = mysqli_fetch_array($resultallservices))
		{
			$mySheet->setCellValue('A'.$j,$fetchallservices['servicename'])
					->setCellValue('B'.$j,$fetchallservices['amount']);
			$j++;
			$currentrow++;			
		}
	
		$mySheet->setCellValue('A'.$currentrow,'Total')
				->setCellValue('B'.$currentrow,"=SUM(B".$databeginrow.":B".($currentrow - 1).")");
		$mySheet->getCell('B'.$currentrow)->getCalculatedValue();		
		
		$mySheet->getStyle('A'.$databeginrow.':B'.$currentrow)->applyFromArray($styleArrayContent);
		$mySheet->getColumnDimension('A')->setWidth(30);
		$mySheet->getColumnDimension('B')->setWidth(25);
		
		
		// Increment the no of rows to give line space.
		$currentrow = $currentrow + 2;	
		
		// $mySheet->setCellValue('A'.$currentrow,'Items (Others)');
		// $mySheet->getStyle('A'.$currentrow.':B'.$currentrow)->applyFromArray($styleArray1);
		
		// $currentrow++;
		// //Set table headings
		// $objPHPExcel->setActiveSheetIndex($pageindex)
		// 		->setCellValue('A'.$currentrow,'Add/Less')
		// 		->setCellValue('B'.$currentrow,' Sales');

		// //Apply style for header Row
		// $mySheet->getStyle('A'.$currentrow.':B'.$currentrow)->applyFromArray($styleArray);
		// $currentrow++;
		// $databeginrow = $currentrow;
		// $j = $currentrow;
		
		// $addlessquerymonth = 'select sum(descamount) as amount,descname from addlessdescsearchexcel  group by descname order by descname;';
		// $addlessresultmonth = runmysqlquery($addlessquerymonth);
		// $addlessgridcount = mysqli_num_rows($addlessresultmonth);
		
		// while($fetchalloffers = mysqli_fetch_array($addlessresultmonth))
		// {
		// 	if($addlessgridcount == 2)
		// 	{
		// 	  $mySheet->setCellValue('A'.$j,$fetchalloffers['descname'])
		// 			  ->setCellValue('B'.$j,$fetchalloffers['amount']);
		// 	  $j++;
		// 	  $currentrow++;		
		// 	}
		// 	else
		// 	{
		// 		if($fetchalloffers['descname'] == 'add')
		// 		{
		// 		  $mySheet->setCellValue('A'.$j,$fetchalloffers['descname'])
		// 				  ->setCellValue('B'.$j,$fetchalloffers['amount']);
		// 		  $mySheet->setCellValue('A'.($j+1),$fetchalloffers['descname'])
		// 				  ->setCellValue('B'.($j+1),$fetchalloffers['amount']);
		// 		  $j++;
		// 		  $currentrow++;
		// 		}
		// 		else
		// 		{
		// 			$mySheet->setCellValue('A'.$j,$fetchalloffers['descname'])
		// 					->setCellValue('B'.$j,$fetchalloffers['amount']);
		// 			$mySheet->setCellValue('A'.($j+1),$fetchalloffers['descname'])
		// 				  ->setCellValue('B'.($j+1),$fetchalloffers['amount']);
		// 			$j++;
		// 			$currentrow++;
		// 		}
		// 	}
		// }
		// $addvalue = $mySheet->getCell('B'.$databeginrow)->getCalculatedValue();
		// $lessvaue = $mySheet->getCell('B'.($currentrow - 1))->getCalculatedValue();
		// $subvalue = (int)$addvalue - (int)$lessvaue;
		// $mySheet->setCellValue('A'.$currentrow,'Total')
		// 		->setCellValue('B'.$currentrow,$subvalue);
		// $mySheet->getCell('B'.$currentrow)->getCalculatedValue();		
		
		// $mySheet->getStyle('A'.$databeginrow.':B'.$currentrow)->applyFromArray($styleArrayContent);
		// $mySheet->getColumnDimension('A')->setWidth(30);
		// $mySheet->getColumnDimension('B')->setWidth(25);
		
		$addstring = "/user";
		if($_SERVER['HTTP_HOST'] == "bhumika" || $_SERVER['HTTP_HOST'] == "192.168.2.79")
			$addstring = "/rwm/saralimax-user";
		
				$query = 'select slno,fullname,username from inv_mas_users where slno = '.$userid.'';
				$fetchres = runmysqlqueryfetch($query);
				
				$username = $fetchres['username'];
				$localdate = datetimelocal('Ymd');
				$localtime = datetimelocal('His');
				$filebasename = "InvoiceRegister".$localdate."-".$localtime."-".strtolower($username).".xls";
	
				$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_invoiceregister_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
				$result = runmysqlquery($query1);
				
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','77','".date('Y-m-d').' '.date('H:i:s')."','excel_invoiceregister_report".'-'.strtolower($username)."')";
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
