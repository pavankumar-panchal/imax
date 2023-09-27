<?php
ini_set('memory_limit', '2048M');

include('../functions/phpfunctions.php');

//PHPExcel
require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory
require_once '../phpgeneration/PHPExcel/IOFactory.php';

$id = $_GET['id'];
$fromdate = $_POST['fromdate'];
$todate = $_POST['todate'];
$dealerid = $_POST['dealerid'];

$groupon = $_POST['groupon'];

if($groupon == 'regngiven')
{
	$leftjoin = "LEFT JOIN inv_mas_customer on inv_mas_customer.slno = inv_customerproduct.customerreference";
}
else if($groupon == 'acctholder')
{
	$leftjoin = "LEFT JOIN inv_mas_customer on inv_mas_customer.currentdealer = inv_mas_dealer.slno";
}


if($fromdate <> '' && $todate <> '')
{
	$dealerpiece = ($dealerid == "")?("inv_mas_dealer.slno is not null"):(" inv_mas_dealer.slno = '".$dealerid."' ");

	$query = "SELECT Table3.slno as dealerid,Table3.businessname as dealername, Table3.slnonew as `new`, Table4.slnoupdation as `updation` 
FROM (SELECT inv_mas_dealer.slno, inv_mas_dealer.businessname,table1.slnonew FROM inv_mas_dealer
LEFT JOIN 
(SELECT count(inv_customerproduct.customerreference) as slnonew, 
inv_mas_dealer.slno, inv_mas_dealer.businessname
FROM inv_mas_dealer
LEFT JOIN inv_customerproduct ON inv_customerproduct.dealerid = inv_mas_dealer.slno 
".$leftjoin."
WHERE inv_customerproduct.date BETWEEN '".changedateformat($fromdate)."' AND '".changedateformat($todate)."'
AND inv_customerproduct.purchasetype = 'new' 
AND inv_customerproduct.reregistration = 'no'  
GROUP BY inv_mas_dealer.slno) as table1 on inv_mas_dealer.slno = table1.slno WHERE   ".$dealerpiece.") AS Table3
left join 
(SELECT inv_mas_dealer.slno, inv_mas_dealer.businessname,table1.slnoupdation FROM inv_mas_dealer
LEFT JOIN 
(SELECT count(inv_customerproduct.customerreference) as slnoupdation, 
inv_mas_dealer.slno, inv_mas_dealer.businessname  
FROM inv_mas_dealer
LEFT JOIN inv_customerproduct ON inv_customerproduct.dealerid = inv_mas_dealer.slno 
".$leftjoin."
WHERE inv_customerproduct.date BETWEEN '".changedateformat($fromdate)."' AND '".changedateformat($todate)."' 
AND inv_customerproduct.purchasetype = 'updation'  
AND inv_customerproduct.reregistration = 'no'  
GROUP BY inv_mas_dealer.slno) as table1 on inv_mas_dealer.slno = table1.slno WHERE  ".$dealerpiece.") AS Table4 ON Table3.slno = Table4.slno order by Table3.businessname asc" ;
}
	
	$result = runmysqlquery($query);

	$result = runmysqlquery($query);
	
	// Create new Spreadsheet object
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
	$mySheet->getStyle('A2:F2')->applyFromArray($styleArray);
	
	//Merge the cell
	$mySheet->mergeCells('A1:F1');
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'Sales Summary Details');
	$mySheet->getStyle('A1')->getFont()->setSize(12); 	
	$mySheet->getStyle('A1')->getFont()->setBold(true); 
	$mySheet->getStyle('A1')->getAlignment()->setWrapText(true);
	
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A2', 'Sl No')
				->setCellValue('B2', 'Dealer Name')
				->setCellValue('C2', 'Total')
				->setCellValue('D2', 'New')
				->setCellValue('E2', 'Updation')
				->setCellValue('F2', 'Conversion');


	$j =3;
	$slno = 0;
	while($fetch = mysqli_fetch_array($result))
	{
		$slno++;
		$total = $fetch['new'] + $fetch['updation'];
		if($fetch['new'] == '')
		$newpurchase = '0';
		else
		$newpurchase = $fetch['new'];
		
		if($fetch['updation'] == '')
		$updationpurchase = '0';
		else
		$updationpurchase = $fetch['updation'];
		
		$mySheet->setCellValue('A' . $j,$slno)
				->setCellValue('B' . $j,$fetch['dealername'])
				->setCellValue('C' . $j,$total)
				->setCellValue('D' . $j,$newpurchase)
				->setCellValue('E' . $j,$updationpurchase)
				->setCellValue('F' . $j,$fetch['conversion']);
				
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
	$myDataRange = 'A3:'.$myLastCell;
	if(mysqli_num_rows($result) <> 0)
	{
	//Apply style to content area range
		$mySheet->getStyle($myDataRange)->applyFromArray($styleArrayContent);
	}
	
	//set the default width for column
	$mySheet->getColumnDimension('A')->setWidth(6);
	$mySheet->getColumnDimension('B')->setWidth(30);
	$mySheet->getColumnDimension('C')->setWidth(12);
	$mySheet->getColumnDimension('D')->setWidth(12);
	$mySheet->getColumnDimension('E')->setWidth(12);
	$mySheet->getColumnDimension('F')->setWidth(12);
	
	
	$localdate = datetimelocal('Ymd');
	$localtime = datetimelocal('His');
	$filebasename = "SalesSummary".$localdate."-".$localtime.".xls";
	$addstring = "/user";
	if($_SERVER['HTTP_HOST'] == "meghanab")
		$addstring = "/saralimax-user";
	if($id == 'toexcel')
	{
		$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_salessummary_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
		$result = runmysqlquery($query1);

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
		unlink($filepath);
		exit;
	}
	elseif($id == 'view')
	{
		$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','view_salessummary_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
		$result = runmysqlquery($query1);

		
		$localdate = datetimelocal('Ymd');
		$localtime = datetimelocal('His');
		$filename = "viewsalessummaryreport".$localdate."-".$localtime.".htm";
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
		

?>
