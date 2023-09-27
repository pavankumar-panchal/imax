<?php
ini_set('memory_limit', '2048M');

include('../functions/phpfunctions.php');
require_once '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//PHPExcel
//require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory
require_once '../phpgeneration/PHPExcel/IOFactory.php';
$flag = $_POST['flag'];
if($flag == '')
{
	$url = '../home/index.php?a_link=categorysummary'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
	$userid = imaxgetcookie('userid');
	$id = $_REQUEST['id'];
	$region = $_POST['region'];
	$branch = $_POST['branch'];
	$dealer = $_POST['dealerid'];
	$productgroup = $_POST['productgroup'];
	
	$regionpiece = ($region == "")?(""):(" AND inv_mas_region.slno = '".$region."' ");

	$branchpiece = ($branch == "")?(""):(" AND inv_mas_branch.slno = '".$branch."' ");
	
	$dealerpiece = ($dealer == "")?(""):("  AND inv_mas_dealer.slno = '".$dealer."' ");
	
	$productgrouppiece = ($productgroup == "ALL")?(""):(" where  inv_mas_product.group = '".$productgroup."' ");
	

	$query = "SELECT DISTINCT inv_mas_customer.slno,inv_mas_customer.customerid as cusid,inv_mas_customer.businessname as cusname ,inv_mas_customer.address as address,inv_mas_customer.place as place,inv_mas_customer.pincode as pincode,inv_mas_district.districtname as district,inv_mas_state.statename,inv_mas_region.category as region,inv_mas_branch.branchname as branch,inv_mas_customertype.customertype as customertype,inv_mas_customercategory.businesstype as category,inv_mas_dealer.businessname as dealername,inv_contactdetails.cell,inv_contactdetails.phone,inv_contactdetails.contactperson,inv_contactdetails.emailid FROM inv_mas_customer left join (select distinct customerid, group_concat(cell) as cell,group_concat(phone) as phone,group_concat(contactperson) as contactperson,group_concat(emailid) as emailid  from inv_contactdetails group by customerid )as  inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
left join  (select distinct customerreference,computerid,dealerid,cardid  from inv_customerproduct left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)  ".$productgrouppiece." group by customerreference) as inv_customerproduct on inv_customerproduct.customerreference = inv_mas_customer.slno
LEFT JOIN inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode LEFT JOIN inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region LEFT JOIN inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category where inv_customerproduct.customerreference is not null ".$regionpiece.$branchpiece.$dealerpiece." order by inv_mas_customer.slno;";
	
		$result = runmysqlquery($query);
		
		//echo($query); exit;
		$pageindex = 0;
		
		// Create new PHPExcel object
		$objPHPExcel = new Spreadsheet();
		
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet();
			
		//Define Style for header row
		$styleArray = array(
							'font' => array('bold' => true,),
							'fill'=> array('fillType'=>\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,'startColor'=> array('argb' => 'FFCCFFCC')),
							'borders' => array('allBorders'=> array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM))
						);
						
		//Apply style for header Row
		$mySheet->getStyle('A3:Q3')->applyFromArray($styleArray);
		
		//Set the worksheet name
		$mySheet->setTitle(strtoupper($productgroup).' Customers');
		
		//Merge the cell
		$mySheet->mergeCells('A1:Q1');
		$mySheet->mergeCells('A2:Q2');
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'Category Summary Details');
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
		
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A3', 'Sl No')
					->setCellValue('B3', 'Customer ID')
					->setCellValue('C3', 'Company Name')
					->setCellValue('D3', 'Contact Person')
					->setCellValue('E3', 'Address')
					->setCellValue('F3', 'Phone')
					->setCellValue('G3', 'Cell')
					->setCellValue('H3', 'Email ID')
					->setCellValue('I3', 'Place')
					->setCellValue('J3', 'Pincode')
					->setCellValue('K3', 'District')
					->setCellValue('L3', 'State')
					->setCellValue('M3', 'Region')
					->setCellValue('N3', 'Branch')
					->setCellValue('O3', 'Current Dealer')
					->setCellValue('P3', 'Category')
					->setCellValue('Q3', 'Type');
	
		$j =4;
		$slno = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			
			$slno++;
			$contactvalues = trim(removedoublecomma($fetch['contactperson']),',');
			$phonevalues = trim(removedoublecomma($fetch['phone']),',');
			$cellvalues = trim(removedoublecomma($fetch['cell']),',');
			$emailidvalues = trim(removedoublecomma($fetch['emailid']),',');
			$mySheet->setCellValue('A' . $j,$slno)
							->setCellValue('B' . $j,cusidcombine($fetch['cusid']))
							->setCellValue('C' . $j,$fetch['cusname'])
							->setCellValue('D' . $j,$contactvalues)
							->setCellValue('E' . $j,$fetch['address'])
							->setCellValue('F' . $j,$phonevalues)
							->setCellValue('G' . $j,$cellvalues)
							->setCellValue('H' . $j,$emailidvalues)
							->setCellValue('I' . $j,$fetch['place'])
							->setCellValue('J' . $j,$fetch['pincode'])
							->setCellValue('K' . $j,$fetch['district'])
							->setCellValue('L' . $j,$fetch['statename'])
							->setCellValue('M' . $j,$fetch['region'])
							->setCellValue('N' . $j,$fetch['branch'])
							->setCellValue('O' . $j,$fetch['dealername'])
							->setCellValue('P' . $j,$fetch['category'])
							->setCellValue('Q' . $j,$fetch['customertype']);
							
			$j++;
		}
						
		//Define Style for content area
		$styleArrayContent = array(
							'borders' => array('allBorders'=> array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))
						);
		//Get the last cell reference
		$highestRow = $mySheet->getHighestRow(); 
		$highestColumn = $mySheet->getHighestColumn(); 
		$myLastCell = $highestColumn.$highestRow;
		
		//Deine the content range
		$myDataRange = 'A4:'.$myLastCell;
		if(mysqli_num_rows($result) <> 0)
		{
		//Apply style to content area range
			$mySheet->getStyle($myDataRange)->applyFromArray($styleArrayContent);
		}
		
		//set the default width for column
		$mySheet->getColumnDimension('A')->setWidth(6);
		$mySheet->getColumnDimension('B')->setWidth(30);
		$mySheet->getColumnDimension('C')->setWidth(50);
		$mySheet->getColumnDimension('D')->setWidth(50);
		$mySheet->getColumnDimension('E')->setWidth(50);
		$mySheet->getColumnDimension('F')->setWidth(20);
		$mySheet->getColumnDimension('G')->setWidth(20);
		$mySheet->getColumnDimension('H')->setWidth(20);
		$mySheet->getColumnDimension('I')->setWidth(20);
		$mySheet->getColumnDimension('J')->setWidth(20);
		$mySheet->getColumnDimension('K')->setWidth(20);
		$mySheet->getColumnDimension('L')->setWidth(20);
		$mySheet->getColumnDimension('M')->setWidth(20);
		$mySheet->getColumnDimension('N')->setWidth(20);
		$mySheet->getColumnDimension('O')->setWidth(20);
		$mySheet->getColumnDimension('P')->setWidth(20);
		$mySheet->getColumnDimension('Q')->setWidth(20);
	
		$pageindex++;
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($pageindex);
	
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet();
		
		//Set the worksheet name
		$mySheet->setTitle(strtoupper($productgroup).' Category Summary');
		
		//Merge the cell
		$mySheet->mergeCells('A1:C1');
		$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('A1', 'Summary Details');
		$mySheet->getStyle('A1')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1')->getFont()->setBold(true); 
		$mySheet->getStyle('A1')->getAlignment()->setWrapText(true);
		$currentrow = 0;
			
		//Define Style for header row
		$styleArray = array(
							'font' => array('bold' => true,),
							'fill'=> array('fillType'=>\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,'startColor'=> array('argb' => 'FFCCFFCC')),
							'borders' => array('allBorders'=> array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM))
						);
		//Apply style for header Row
		$mySheet->getStyle('A3:C3')->applyFromArray($styleArray);	
		
		//Fille contents for Header Row
		$objPHPExcel->setActiveSheetIndex($pageindex)
				->setCellValue('A3', 'Sl No')
				->setCellValue('B3', 'Category')
				->setCellValue('C3', 'Count');
				
		$j =4;
		$slno= 0;
		
		$query = "select inv_mas_customercategory.businesstype, count(distinct inv_mas_customer.slno)as count from inv_mas_customer left join (select distinct customerreference,computerid,dealerid from inv_customerproduct left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) ".$productgrouppiece." group by customerreference) as inv_customerproduct on inv_customerproduct.customerreference = inv_mas_customer.slno left join inv_mas_customercategory on inv_mas_customercategory.slno =inv_mas_customer.category left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch where inv_customerproduct.customerreference is not null  ".$regionpiece.$branchpiece.$dealerpiece."  group by inv_mas_customer.category order by inv_mas_customercategory.businesstype asc;";

		
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			
			$slno++;
			if($fetch['businesstype']== '')
				$businesstype = 'Not Assigned';
			else
				$businesstype = $fetch['businesstype'];
			$mySheet->setCellValue('A' . $j,$slno)
							->setCellValue('B' . $j,$businesstype)
							->setCellValue('C' . $j,$fetch['count']);
							
			$j++;
		}
		$mySheet->setCellValue('B'.$j,'Total')
						->setCellValue('C'.$j,"=SUM(C4:C".($j-1).")");
						
		//Define Style for content area
		$styleArrayContent = array(
							'borders' => array('allBorders'=> array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))
						);
		//Get the last cell reference
		$highestRow = $mySheet->getHighestRow(); 
		$highestColumn = $mySheet->getHighestColumn(); 
		$myLastCell = $highestColumn.$highestRow;
		
		//Deine the content range
		$myDataRange = 'A4:'.$myLastCell;
		if(mysqli_num_rows($result) <> 0)
		{
		//Apply style to content area range
			$mySheet->getStyle($myDataRange)->applyFromArray($styleArrayContent);
		}
		
		//set the default width for column
		$mySheet->getColumnDimension('A')->setWidth(6);
		$mySheet->getColumnDimension('B')->setWidth(30);
		$mySheet->getColumnDimension('C')->setWidth(17);
		
		$objPHPExcel->setActiveSheetIndex(0);
		
		$addstring = "/user";
		if($_SERVER['HTTP_HOST'] == "meghanab" || $_SERVER['HTTP_HOST'] == "rashmihk")
			$addstring = "/saralimax-user";
		if($id == 'toexcel')
		{
			$query = 'select slno,username from inv_mas_users where slno = '.$userid.'';
			$fetchres = runmysqlqueryfetch($query);
			$localdate = datetimelocal('Ymd');
			$localtime = datetimelocal('His');
			$filebasename = "categorysummary-".$productgroup."-".$localdate."-".$localtime."-".strtolower($fetchres['username']).".xls";
		
			$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_categorysummary_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
			$result = runmysqlquery($query1);
			
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','101','".date('Y-m-d').' '.date('H:i:s')."','excel_categorysummary_report".'-'.strtolower($fetchres['username'])."')";
			$eventresult = runmysqlquery($eventquery);
			
			$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filebasename;
			$downloadlink = getcwd().'/filecreated/'.$filebasename;
			$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xls');
			$objWriter->save($filepath);
			$fp = fopen($filepath,"r+");
			if($fp)
			{
				$line = fgets($fp);
				downloadfile($filepath);
				fclose($fp);
			} 
			
			unlink($filebasename);
			exit; 
		}
		elseif($id == 'view')
		{
			$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','view_categorysummary_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
			$result = runmysqlquery($query1);
			
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','100','".date('Y-m-d').' '.date('H:i:s')."','view_categorysummary_report')";
			$eventresult = runmysqlquery($eventquery);
	
			$localdate = datetimelocal('Ymd');
			$localtime = datetimelocal('His');
			$filename = "viewcategorysummaryreport".$localdate."-".$localtime.".htm";
			$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filename;
	
			$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Html');
			$objWriter->save($filepath);
			
			// get contents of a file into a string
			$handle = fopen($filepath, "r");
			$contents = fread($handle, filesize($filepath));
			fclose($handle);
			
			echo($contents);
			unlink($filepath);
			exit;
		}
	}

?>
