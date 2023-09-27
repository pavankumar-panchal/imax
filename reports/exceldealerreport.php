<?
ini_set('memory_limit', '4048M');

include('../functions/phpfunctions.php');

//PHPExcel
require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory
require_once '../phpgeneration/PHPExcel/IOFactory.php';
$flag = $_POST['flag'];
if($flag == '')
{
	$url = '../home/index.php?a_link=dealerdetails'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
	$id = $_GET['id'];
	$todate = $_POST['todate'];
	$fromdate = $_POST['fromdate'];
	$geography = $_POST['geography'];
	$region = $_POST['region'];
	$dealerregion = $_POST['dealerregion'];
	$state = $_POST['state'];
	$usagetype = $_POST['usagetype'];
	$purchasetype = $_POST['purchasetype'];
	$chks = $_POST['productname'];
	for ($i = 0;$i < count($chks);$i++)
	{
		$c_value .= "'" . $chks[$i] . "'" ."," ;
	}
	$productslist = rtrim($c_value , ',');
	$value = str_replace('\\','',$productslist);
	
	$dealerid = $_POST['dealerid'];
	$reportdate = datetimelocal('d-m-Y');
	$scheme = $_POST['scheme'];
	$userid = imaxgetcookie('userid');
	
		if($todate <> '' && $fromdate <> '')
		{
			//echo($reportdate);
			if($geography == "") { $geographypiece = ""; } 
			elseif($geography == "region") { $geographypiece = " AND inv_mas_customer.region = '".$region."' " ; }
			elseif($geography == "state") { $geographypiece = " AND inv_mas_district.statecode = '".$state."' " ; }
			elseif($geography == "district") { $geographypiece = " AND inv_mas_customer.district = '".$district."' " ; }
				
			$productcodepiece = ($chks == "")?(""):(" AND  inv_mas_product.productcode IN (".$value.") ");
			$schemepiece = ($scheme == "")?(""):(" AND inv_mas_scheme.slno = '".$scheme."' ");
			$usagetypepiece = ($usagetype == "")?(""):(" AND inv_dealercard.usagetype = '".$usagetype."' ");
			$purchasetypepiece = ($purchasetype == "")?(""):(" AND inv_dealercard.purchasetype = '".$purchasetype."' ");
			$dealerregionpiece = ($dealerregion == "")?(""):(" AND inv_mas_dealer.region = '".$dealerregion."' ");
			
			$dealeridpiece = ($dealerid == "")?(""):("  AND inv_mas_dealer.slno = '".$dealerid."' ");
			$datepiece = " AND left(inv_dealercard.date,10) BETWEEN '".changedateformat($fromdate)."' AND '".changedateformat($todate)."'";
			
				$query = "select inv_mas_customer.businessname as cusname,inv_mas_dealer.businessname as dealername,
	inv_mas_scratchcard.cardid as cardid, inv_mas_product.productname as productname,inv_dealercard.date as attcheddate, inv_customerproduct.billnumber as billnumber , inv_customerproduct.date as registereddate,
	inv_mas_scratchcard.scratchnumber as scratchnumber,inv_dealercard.usagetype as usagetype,
	inv_dealercard.purchasetype as purchasetype,inv_mas_users.fullname ,inv_mas_scheme.schemename as scheme,
	inv_mas_region.category as region,inv_bill.remarks as billremarks,inv_dealercard.customerreference from inv_dealercard 
	left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealercard.dealerid
	LEFT JOIN inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
	LEFT JOIN inv_customerproduct on inv_dealercard.cardid = inv_customerproduct.cardid 
	and inv_customerproduct.reregistration = 'no' LEFT JOIN inv_mas_customer on inv_mas_customer.slno = inv_customerproduct.customerreference LEFT JOIN inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
	left join inv_mas_users on inv_mas_users.slno = inv_dealercard.userid 
	left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme 
	LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_dealer.region 
	left join inv_mas_district  on inv_mas_district.districtcode = inv_mas_customer.district
	left join inv_bill on inv_bill.slno = inv_dealercard.cusbillnumber WHERE inv_dealercard.slno <> '9449599733' and inv_dealercard.cardid!= 0 ".$datepiece.$geographypiece.$productcodepiece.$dealeridpiece.$schemepiece.$dealerregionpiece.$usagetypepiece.$purchasetypepiece."; ";
	
		}
		//echo($query);exit;
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
		$mySheet->getStyle('A3:P3')->applyFromArray($styleArray);
		
		//Merge the cell
		$mySheet->mergeCells('A1:P1');
		$mySheet->mergeCells('A2:P2');
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'Dealer Inventory Details');
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
		
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A3', 'Sl No')
					->setCellValue('B3', 'Dealer')
					->setCellValue('C3', 'Pin Serial Number')
					->setCellValue('D3', 'Product')
					->setCellValue('E3', 'Date')
					->setCellValue('F3', 'Bill No')
					->setCellValue('G3', 'Registered On')
					->setCellValue('H3', 'Attached To')
					->setCellValue('I3', 'Registered To')
					->setCellValue('J3', 'PIN Number')
					->setCellValue('K3', 'Usage Type')
					->setCellValue('L3', 'Purchase Type')
					->setCellValue('M3', 'Attached By')
					->setCellValue('N3', 'Scheme')
					->setCellValue('O3', 'Region')
					->setCellValue('P3', 'Purchase Remarks');
	
	
		$j =4;
		$slno = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			// Fetch customer name 
			if(($fetch['customerreference'] == '')||($fetch['customerreference'] == 'null'))
			{
				$businessname = '';
			}
			else
			{
				
				$query2 = "select * from inv_mas_customer where slno = '".$fetch['customerreference']."'";
				$fetch2 = runmysqlqueryfetch($query2);
				$businessname = $fetch2['businessname'];
			}
			$slno++;
			if($fetch['registereddate'] == '')
			$registereddate = '';
			else
			$registereddate = changedateformat($fetch['registereddate']);
			$mySheet->setCellValue('A' . $j,$slno)
							->setCellValue('B' . $j,$fetch['dealername'])
							->setCellValue('C' . $j,$fetch['cardid'])
							->setCellValue('D' . $j,$fetch['productname'])
							->setCellValue('E' . $j,changedateformat(substr($fetch['attcheddate'],0,10)))
							->setCellValue('F' . $j,$fetch['billnumber'])
							->setCellValue('G' . $j,$registereddate)
							->setCellValue('H' . $j,$businessname)
							->setCellValue('I' . $j,$fetch['cusname'])
							->setCellValue('J' . $j,$fetch['scratchnumber'])
							->setCellValue('K' . $j,$fetch['usagetype'])
							->setCellValue('L' . $j,$fetch['purchasetype'])
							->setCellValue('M' . $j,$fetch['fullname'])
							->setCellValue('N' . $j,$fetch['scheme'])
							->setCellValue('O' . $j,$fetch['region'])
							->setCellValue('P' . $j,$fetch['billremarks']);
							
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
		$mySheet->getColumnDimension('G')->setWidth(13);
		$mySheet->getColumnDimension('H')->setWidth(30);
		$mySheet->getColumnDimension('I')->setWidth(30);
		$mySheet->getColumnDimension('J')->setWidth(16);
		$mySheet->getColumnDimension('K')->setWidth(12);
		$mySheet->getColumnDimension('L')->setWidth(15);
		$mySheet->getColumnDimension('M')->setWidth(25);
		$mySheet->getColumnDimension('N')->setWidth(26);
		$mySheet->getColumnDimension('O')->setWidth(7);
		$mySheet->getColumnDimension('P')->setWidth(40);
		
		
		$addstring = "/user";
		if($_SERVER['HTTP_HOST'] == "meghanab" || $_SERVER['HTTP_HOST'] == "rashmihk")
			$addstring = "/saralimax-user";
		if($id == 'toexcel')
		{
			$query = 'select slno,username from inv_mas_users where slno = '.$userid.'';
			$fetchres = runmysqlqueryfetch($query);
			$localdate = datetimelocal('Ymd');
			$localtime = datetimelocal('His');
			$filebasename = "DealerDetails".$localdate."-".$localtime."-".strtolower($fetchres['username']).".xls";
		
			$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_dealerdetails_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
			$result = runmysqlquery($query1);
			
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','101','".date('Y-m-d').' '.date('H:i:s')."','excel_dealerdetails_report".'-'.strtolower($fetchres['username'])."')";
			$eventresult = runmysqlquery($eventquery);
			
			$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filebasename;
			$downloadlink = 'http://'.$_SERVER['HTTP_HOST'].$addstring.'/filecreated/'.$filebasename;
			
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
			$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','view_dealerdetails_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
			$result = runmysqlquery($query1);
			
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','100','".date('Y-m-d').' '.date('H:i:s')."','view_dealerdetails_report')";
			$eventresult = runmysqlquery($eventquery);
	
			$localdate = datetimelocal('Ymd');
			$localtime = datetimelocal('His');
			$filename = "viewdealerdetailsreport".$localdate."-".$localtime.".htm";
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
