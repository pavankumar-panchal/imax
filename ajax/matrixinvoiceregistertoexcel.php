<?
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
$chks = $_POST['matrixarray'];
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
$datepiece = ($alltimecheck == 'on')?(""):(" AND (left(inv_matrixinvoicenumbers.createddate,10) between '".$fromdate."' AND '".$todate."') ");	

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

if($chks != '')
{
	for($i = 0;$i< $productlistsplitcount; $i++)
	{
		if($i < ($productlistsplitcount-1))
			$appendor = 'or'.' ';
		else
			$appendor = '';
			
		// Get the product group of each product 
				
		$querygetproductgroup = "select * from inv_mas_matrixproduct where id = '".$productlistsplit[$i]."'";
		$resultproductgroup = runmysqlqueryfetch($querygetproductgroup);
		 
		// Insert to temporary table 
		$queryinsertproductgroup = "insert into `productgroupsexcel`(productcode,productgroup) values('".$resultproductgroup['productcode']."','".$resultproductgroup['group']."')";
		$resultofinsert = runmysqlquery($queryinsertproductgroup);
		
		$finalproductlist .= ' inv_matrixinvoicenumbers.products'.' '.'like'.' "'.'%'.$productlistsplit[$i].'%'.'" '.$appendor."";
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
			
		$finalitemlist .= ' inv_matrixinvoicenumbers.servicedescription'.' '.'like'.' "'.'%'.$itemlistsplit[$j].'%'.'" '.$appendor1."";
	}
}

$finallistarray = ' AND ('.$finalproductlist.')';

$modulepiece = ($generatedbysplit[1] == "[U]")?("user_module"):("dealer_module");
$regionpiece = ($region == "")?(""):(" AND inv_matrixinvoicenumbers.regionid = '".$region."' ");

$state_typepiece = ($state == "")?(""):(" AND inv_mas_district.statecode = '".$state."' ");
$district_typepiece = ($district == "")?(""):(" AND inv_mras_customer.district = '".$district."' ");
$dealer_typepiece = ($dealer == "")?(""):(" AND inv_matrixinvoicenumbers.dealerid = '".$dealer."' ");
$branchpiece = ($branch == "")?(""):(" AND inv_matrixinvoicenumbers.branchid = '".$branch."' ");
$generatedpiece = ($generatedby == "")?(""):(" AND inv_matrixinvoicenumbers.createdbyid = '".$generatedbysplit[0]."' and inv_matrixinvoicenumbers.module = '".$modulepiece."'");
$seriespiece = ($series == "")?(""):(" AND inv_matrixinvoicenumbers.category = '".$series."' ");
$seriespiecenew = ($seriesnew == "")?(""):(" AND inv_matrixinvoicenumbers.invoice_type = '".$seriesnew."' ");
$statuspiece = ($status == "")?(""):(" AND inv_matrixinvoicenumbers.status = '".$status."'");
$cancelledpiece = ($cancelledinvoice == "on")?("AND inv_matrixinvoicenumbers.status <> 'CANCELLED'"):("");
$receiptstatuspiece = ($receiptstatus == "")?(""):(" and inv_mas_receipt.status = '".$receiptstatus."' ");

$todaynewtotal = 0 ;
$todayupdationtotal = 0;
$softwaretotal = 0;
$softwareupdation = 0;
$softwarenew = 0;
$hardwaretotal = 0; 
$hardwarenew = 0; 
$hardwareupdation = 0;
$overalltotal = 0; 

if($flag == '')
{
	$url = '../home/index.php?a_link=matrixinvoiceregister'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
	$querycase = "select distinct inv_matrixinvoicenumbers.slno,left(inv_matrixinvoicenumbers.createddate,10) as createddate, inv_matrixinvoicenumbers.invoiceno,inv_mas_customer.gst_no,inv_matrixinvoicenumbers.gst_no as invoice_gst_no,inv_matrixinvoicenumbers.customerid,inv_matrixinvoicenumbers.businessname,inv_matrixinvoicenumbers.contactperson, inv_matrixinvoicenumbers.description, inv_matrixinvoicenumbers.amount,(IFNULL(inv_matrixinvoicenumbers.igst,0)+IFNULL(inv_matrixinvoicenumbers.sgst,0)+IFNULL(inv_matrixinvoicenumbers.cgst,0)) as servicetax,inv_matrixinvoicenumbers.igst,inv_matrixinvoicenumbers.cgst,inv_matrixinvoicenumbers.sgst,inv_matrixinvoicenumbers.netamount,inv_matrixinvoicenumbers.dealername,inv_matrixinvoicenumbers.createdby,inv_matrixinvoicenumbers.address,inv_matrixinvoicenumbers.place,inv_matrixinvoicenumbers.region,inv_matrixinvoicenumbers.branch,inv_matrixinvoicenumbers.emailid,inv_matrixinvoicenumbers.stdcode,inv_matrixinvoicenumbers.phone,inv_matrixinvoicenumbers.cell,inv_matrixinvoicenumbers.status,inv_matrixinvoicenumbers.productquantity from inv_matrixinvoicenumbers
	left join inv_mas_customer on inv_mas_customer.slno = right(inv_matrixinvoicenumbers.customerid,5)
	left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
	left join inv_mas_branch on inv_mas_branch.branchname = inv_matrixinvoicenumbers.branch
	left join inv_mas_receipt on inv_mas_receipt.matrixinvoiceno = inv_matrixinvoicenumbers.slno
	left join inv_mas_region on inv_mas_region.category = inv_matrixinvoicenumbers.region";
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
					
				$query = $querycase." where right(inv_matrixinvoicenumbers.customerid,5) like '%".$customerid."%' ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  order by inv_matrixinvoicenumbers.createddate desc";
				break;
		
			case "contactperson": 
				$query = $querycase." where inv_matrixinvoicenumbers.contactperson LIKE '%".$textfield."%' ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew." order by inv_matrixinvoicenumbers.createddate desc";
				break;
			case "phone":
				$query = $querycase." where inv_matrixinvoicenumbers.phone LIKE '%".$textfield."%' OR inv_matrixinvoicenumbers.cell LIKE '%".$textfield."%'  ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  order by inv_matrixinvoicenumbers.createddate desc ";
				break;
			case "place":
				$query = $querycase." where inv_matrixinvoicenumbers.place LIKE '%".$textfield."%' ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  order by inv_matrixinvoicenumbers.createddate desc";
				break;
			case "emailid":
				$query = $querycase." where inv_matrixinvoicenumbers.emailid LIKE '%".$textfield."%'  ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew." order by inv_matrixinvoicenumbers.createddate desc";
				break;
			
			case "invoiceno":
				$query = $querycase." where inv_matrixinvoicenumbers.invoiceno LIKE '%".$textfield."%' ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  order by inv_matrixinvoicenumbers.createddate desc";
				break;
			case "invoiceamt":
				$query = $querycase." where inv_matrixinvoicenumbers.netamount LIKE '%".$textfield."%' ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  order by inv_matrixinvoicenumbers.createddate desc";
				break;
			
			default:
				$query = $querycase." where inv_matrixinvoicenumbers.businessname LIKE '%".$textfield."%' ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  ORDER BY invoiceno";
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
		$mySheet->setTitle('Invoice Details');
			
		//Apply style for header Row
		$mySheet->getStyle('A3:W3')->applyFromArray($styleArray);
		
		//Merge the cell
		$mySheet->mergeCells('A1:W1');
		$mySheet->mergeCells('A2:W2');
		$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'Matrix Invoice Details Report');
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
	
		
		//Fille contents for Header Row
		$objPHPExcel->setActiveSheetIndex($pageindex)
							->setCellValue('A3', 'Sl No')
							->setCellValue('B3', 'Invoice Date')
							->setCellValue('C3', 'Invoice No')
							->setCellValue('D3', 'Customer ID')
							->setCellValue('E3', 'Customer Name')
							->setCellValue('F3', 'Customer GSTIN')
							->setCellValue('G3', 'Contact Person')
							->setCellValue('H3', 'Products')
							->setCellValue('I3', 'Quantity')
							->setCellValue('J3', 'Sale Value')
							->setCellValue('K3', 'Tax Amount')
							->setCellValue('L3', 'Invoice Amount')
							->setCellValue('M3', 'Sales Person')
							->setCellValue('N3', 'Prepared By')
							->setCellValue('O3', 'Address')
							->setCellValue('P3', 'Place')
							->setCellValue('Q3', 'Region')
							->setCellValue('R3', 'Branch')
							->setCellValue('S3', 'Stdcode')
							->setCellValue('T3', 'Phone')
							->setCellValue('U3', 'Cell')
							->setCellValue('V3', 'Email Id')
							->setCellValue('W3', 'Status')
							;

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
					$productstaken .= $products[1].','.$products[2];
				}
				else
				{ $productstaken .=''; }
				$productcount ++;
			}
			
			
			$totalproductlist = $productstaken;
			
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

			/*-----------------Added for New Multiple GSTIN Code--------------------------*/

			$invoice_new_gst_no = '';
			$customer_gst_slno = substr($fetch['customerid'], -5);
			$invoice_new_gst_no = $fetch['gst_no'];

			/*-----------------Added for New Multiple GSTIN Code--------------------------*/
			//KK Cess Ends	
			$newgst = " ".$invoice_new_gst_no." ";
			$mySheet->setCellValue('A' . $j,$slno_count)
					->setCellValue('B' . $j,changedateformat($fetch['createddate']))
					->setCellValue('C' . $j,$fetch['invoiceno'])
					->setCellValue('D' . $j,$fetch['customerid'])
					->setCellValue('E' . $j,$fetch['businessname'])
					->setCellValue('F' . $j,$newgst)
					->setCellValue('G' . $j,$fetch['contactperson'])
					->setCellValue('H' . $j,$totalproductlist)
					->setCellValue('I' . $j,$quantity)
					->setCellValue('J' . $j,$fetch['amount'])
					->setCellValue('K' . $j,$new_amount)
					->setCellValue('L' . $j,$new_net_amount)
					//->setCellValue('J' . $j,$fetch['servicetax'])
					//->setCellValue('K' . $j,$fetch['netamount'])
					->setCellValue('M' . $j,$fetch['dealername'])
					->setCellValue('N' . $j,$fetch['createdby'])
					->setCellValue('O' . $j,stripslashes(stripslashes($fetch['address'])))
					->setCellValue('P' . $j,$fetch['place'])
					->setCellValue('Q' . $j,$fetch['region'])
					->setCellValue('R' . $j,$fetch['branch'])
					->setCellValue('S' . $j,$fetch['stdcode'])
					->setCellValue('T' . $j,$fetch['phone'])
					->setCellValue('U' . $j,$fetch['cell'])
					->setCellValue('V' . $j,$fetch['emailid'])
					->setCellValue('W' . $j,$fetch['status']);
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
			->setCellValue('F'. ($highestRow + 3), "=SUM(J4:J".($highestRow).")")
			->setCellValue('F'. ($highestRow + 4), "=SUM(K4:K".($highestRow).")")
			->setCellValue('F'. ($highestRow + 5), "=SUM(L4:L".($highestRow).")");
		
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
		$mySheet->getColumnDimension('W')->setWidth(30);
		// Create Temporary tables 
		
		
		$querydrop1 = "Drop table if exists invoicedetailssearchexcel;";
		$result1 = runmysqlquery($querydrop1);

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
		
		$resultsummary = runmysqlquery($query);
		
		// Add data to temporary table.
		
		// For all Search Result 
		while($fetch0 = mysqli_fetch_array($resultsummary))
		{
			// Now insert selected invoice details to temporary table condidering all details of the each invoice
			
			$query2 = "select * from inv_matrixinvoicenumbers where slno = '".$fetch0['slno']."'";
			$fetch2 = runmysqlqueryfetch($query2); //echo($query2);exit;
			// Insert data to services table
			
			// Insert data to invoice detals table 
			
			if($fetch2['products'] <> '')
			{
				
				$count++;
				$totalamount = 0;
				$products = explode('#',$fetch2['products']);
				
				$description = explode('*',$fetch2['description']);
				$productquantity = explode(',',$fetch2['productquantity']);
				$k=0;
				for($i = 0 ; $i < count($description);$i++)
				{
					for($j = 0 ; $j < $productquantity[$i];$j++)
					{
					  $totalamount = 0;
					  $splitdescription = explode('$',$description[$k]);
					  
					  $productcode = $products[$i];
					  $amount = $splitdescription[4];
					  $purchasetype = $splitdescription[2];   //echo($usagetype.'^'.$amount.'^'.$purchasetype); exit;
					  $totalamount = $amount;
					  $k++;	 	  
					  // Fetch Product 	
					  
					  $query1 = "select inv_mas_matrixproduct.group as productgroup from inv_mas_matrixproduct where id = '".$productcode."' ";
					 // echo($query1);echo('<br/>');
					  $result1 = runmysqlqueryfetch($query1);
					  
					  // Insert into invoice details table
					  
					  $query3 = "insert into invoicedetailssearchexcel(invoiceno,productcode,usagetype,amount,purchasetype,dealerid,invoicedate,productgroup,regionid,branch,branchname,category,state_info) values('".$fetch2['slno']."','".$productcode."','".$usagetype."','".$totalamount."','".$purchasetype."','".$fetch2['dealerid']."','".$fetch2['createddate']."','".$result1['productgroup']."','".$fetch2['regionid']."','".$fetch2['branchid']."','".$fetch2['branch']."','".$fetch2['category']."','".$fetch2['state_info']."')"; 
					  $result3 =  runmysqlquery($query3);
					}
				}
			}	
		}
		
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
		$todaynewtotal = 0 ;
		$todayupdationtotal = 0;
		$softwaretotal = 0;
		$softwareupdation = 0;
		$softwarenew = 0;
		$hardwaretotal = 0; 
		$hardwarenew = 0; 
		$hardwareupdation = 0;
		$overalltotal = 0;

		//Fetch Group by Product Amount on Type NEW
		$querynewpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailssearchexcel where purchasetype = 'New' group by productgroup;";
		$resultnewpurchase = runmysqlquery($querynewpurchase);
		$softwarenewpurchase= 0;$hardwarenewpurchase = 0;
		while($fetchnewpurchase = mysqli_fetch_array($resultnewpurchase))
		{
			if($fetchnewpurchase['productgroup'] == 'Software')
				$softwarenewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'Hardware')
				$hardwarenewpurchase = $fetchnewpurchase['amount'];
		}
		
		//Fetch Group by Product Amount on Type UPDATION
		$queryupdationpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailssearchexcel where purchasetype = 'Updation'  group by productgroup;";
		$resultupdationpurchase = runmysqlquery($queryupdationpurchase);
		$softwareupdationpurchase= 0;$hardwareupdationpurchase = 0;
		while($fetchupdationpurchase = mysqli_fetch_array($resultupdationpurchase))
		{
			if($fetchupdationpurchase['productgroup'] == 'Software')
				$softwareupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'Hardware')
				$hardwareupdationpurchase = $fetchupdationpurchase['amount'];
		}

		$softwaretotal = $softwarenewpurchase + $softwareupdationpurchase;
		$hardwaretotal = $hardwarenewpurchase + $hardwareupdationpurchase;
		$mySheet->setCellValue('A'.$j,'Software')
				->setCellValue('B'.$j,($softwarenewpurchase))
				->setCellValue('C'.$j,($softwareupdationpurchase))
				->setCellValue('D'.$j,($softwaretotal));
		$j++;
		$currentrow++;	
		
		$mySheet->setCellValue('A'.$j,'Hardware')
				->setCellValue('B'.$j,($hardwarenewpurchase))
				->setCellValue('C'.$j,($hardwareupdationpurchase))
				->setCellValue('D'.$j,($hardwaretotal));
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
	
		
		$addstring = "/user";
		if($_SERVER['HTTP_HOST'] == "bhumika" || $_SERVER['HTTP_HOST'] == "192.168.2.79")
			$addstring = "/rwm/saralimax-user";
		
				$query = 'select slno,fullname,username from inv_mas_users where slno = '.$userid.'';
				$fetchres = runmysqlqueryfetch($query);
				
				$username = $fetchres['username'];
				$localdate = datetimelocal('Ymd');
				$localtime = datetimelocal('His');
				$filebasename = "MatrixinvoiceRegister".$localdate."-".$localtime."-".strtolower($username).".xls";
	
				$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_matrixinvoiceregister_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
				$result = runmysqlquery($query1);
				
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','275','".date('Y-m-d').' '.date('H:i:s')."','excel_matrixinvoiceregister_report".'-'.strtolower($username)."')";
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
