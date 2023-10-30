<?php
ini_set('memory_limit', '2048M');

include('../functions/phpfunctions.php');
require_once '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// PHPExcel
//require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory
//require_once '../phpgeneration/PHPExcel/IOFactory.php';

// Create new PHPExcel object
$objPHPExcel= new Spreadsheet();

$flag = $_POST['flag'];
if($flag == '')
{
	$url = '../home/index.php?a_link=productshippeddetails'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
	$userid = imaxgetcookie('userid');
	$id = $_GET['id'];
	$fromdate = $_POST['fromdate'];
	$todate = $_POST['todate'];
	$geography = $_POST['geography'];
	$region = $_POST['region'];
	$state = $_POST['state'];
	$district = $_POST['district'];
	$groupon = $_POST['groupon'];
	$productcode = $_POST['productcode'];
	$dealerid = $_POST['dealerid'];
	$billnumberfrom = $_POST['billnumberfrom'];
	$billnumberto = $_POST['billnumberto'];
	$multilogin = $_POST['multilogin'];
	
	if($fromdate <> '' && $todate <> '')
	{
		if($geography == "") { $geographypiece = ""; } 
		elseif($geography == "region") { $geographypiece = " AND inv_mas_customer.region = '".$region."' " ; }
		elseif($geography == "state") { $geographypiece = " AND inv_mas_customer.state = '".$state."' " ; }
		elseif($geography == "district") { $geographypiece = " AND inv_mas_customer.district = '".$district."' " ; }
			
		$productcodepiece = ($productcode == "")?(""):(" AND inv_dealercard.productcode = '".$productcode."' ");
		$dealeridpiece = ($dealerid == "")?(""):(" AND inv_customerproduct.dealerid = '".$dealerid."' ");
		$billnumberpiece = ($billnumberfrom == "" || $billnumberto == "")?(""):(" AND inv_customerproduct.cusbillnumber BETWEEN '".$billnumberfrom."' AND '".$billnumberto."' ");
		$multiloginpiece = (isset($multilogin))?(" AND inv_dealercard.usagetype = 'multiuser' "):("");
		
		if($groupon == 'product') { $grouponpiece = " inv_mas_product.productname "; }
		elseif($groupon == 'dealer') { $grouponpiece = " inv_mas_dealer.businessname "; }	
		
		$query = "SELECT inv_customerproduct.cardid as cardid, inv_customerproduct.date as `date`, inv_mas_dealer.businessname as dealername, inv_customerproduct.cusbillnumber as cusbillnumber,  inv_customerproduct.remarks as remarks, inv_mas_product.productname as productname from inv_customerproduct  LEFT JOIN inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno   LEFT JOIN inv_mas_users on inv_mas_users.slno = inv_customerproduct.generatedby  LEFT JOIN inv_mas_dealer ON inv_mas_dealer.slno = inv_customerproduct.dealerid  LEFT JOIN inv_dealercard ON inv_dealercard.cardid = inv_customerproduct.cardid  LEFT JOIN inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode WHERE inv_customerproduct.date BETWEEN '".changedateformat($fromdate)."' AND '".changedateformat($todate)."' ".$geographypiece.$productcodepiece.$dealeridpiece.$billnumberpiece.$multiloginpiece."  ORDER BY `date` desc, ".$grouponpiece.";";
	}
	
		$result = runmysqlquery($query);
		// Create new PHPExcel object
		$objPHPExcel= new Spreadsheet();
		
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet();
			
		//Define Style for header row
		$styleArray = array(
							'font' => array('bold' => true,),
							'fill'=> array('fillType'=>\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,'startColor'=> array('argb' => 'FFCCFFCC')),
							'borders' => array('allBorders'=> array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM))
						);
		//Apply style for header Row
		$mySheet->getStyle('A3:G3')->applyFromArray($styleArray);
		
			//Merge the cell
		$mySheet->mergeCells('A1:G1');
		$mySheet->mergeCells('A2:G2');
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'Product Registration Details');
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
	
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A3', 'Sl No')
					->setCellValue('B3', 'Product')
					->setCellValue('C3', 'Pin Serial Number')
					->setCellValue('D3', 'Dealer')
					->setCellValue('E3', 'Date')
					->setCellValue('F3', 'Bill Number')
					->setCellValue('G3', 'Remarks');
				
		$j = 4;
		$slno = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$slno++;
			$mySheet->setCellValue('A' . $j,$slno)
					->setCellValue('B' . $j,$fetch['productname'])
					->setCellValue('C' . $j,$fetch['cardid'])
					->setCellValue('D' . $j,$fetch['dealername'])
					->setCellValue('E' . $j,changedateformat($fetch['date']))
					->setCellValue('F' . $j,$fetch['cusbillnumber'])
					->setCellValue('G' . $j,$fetch['remarks']);
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
		$mySheet->getColumnDimension('C')->setWidth(17);
		$mySheet->getColumnDimension('D')->setWidth(31);
		$mySheet->getColumnDimension('E')->setWidth(13);
		$mySheet->getColumnDimension('F')->setWidth(16);
		$mySheet->getColumnDimension('G')->setWidth(40);
		
		$addstring = "/user";
		if($_SERVER['HTTP_HOST'] == "rashmihk" || $_SERVER['HTTP_HOST'] == "meghanab" ||  $_SERVER['HTTP_HOST'] == "archanaab")
			$addstring = "/saralimax-user";
		if($id == 'toexcel')
		{
			$query = 'select slno,username from inv_mas_users where slno = '.$userid.'';
			$fetchres = runmysqlqueryfetch($query);
			$localdate = datetimelocal('Ymd');
			$localtime = datetimelocal('His');
			$filebasename = "ProductShippedReport".$localdate."-".$localtime."-".strtolower($fetchres['username']).".xls";
	
			$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_productshipped_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
			$result = runmysqlquery($query1);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','99','".date('Y-m-d').' '.date('H:i:s')."','excel_productshipped_report".'-'.strtolower($fetchres['username'])."')";
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
			unlink($filebasename);
			exit; 
		}
		elseif($id == 'view')
		{
			$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','view_productshipped_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
			$result = runmysqlquery($query1);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','98','".date('Y-m-d').' '.date('H:i:s')."','view_productshipped_report')";
			$eventresult = runmysqlquery($eventquery);
	
			$localdate = datetimelocal('Ymd');
			$localtime = datetimelocal('His');
			$filename = "viewproductshippedreport".$localdate."-".$localtime.".htm";
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
