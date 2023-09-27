<?php
ini_set('memory_limit', '2048M');

include('../functions/phpfunctions.php');


//PHPExcel 
require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory
require_once '../phpgeneration/PHPExcel/IOFactory.php';

$flag = $_POST['flag'];
if($flag == '')
{
	$url = '../home/index.php?a_link=surrenderreport'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
	$userid = imaxgetcookie('userid');
	$id = $_GET['id'];
	$todate = $_POST['todate'];
	$fromdate = $_POST['fromdate'];
	$fsurrenderby = $_POST['fsurrenderby'];
	$fosurrender = $_POST['fosurrender'];
	$maxcount = $_POST['maxcount'];
	$searchrefresult = $_POST['searchrefresult'];
	$searchinput = $_POST['searchinput'];

	$chks = $_POST['productname'];
	for ($i = 0;$i < count($chks);$i++)
	{
		$c_value .= "'" . $chks[$i] . "'" ."," ;
	}
	$productslist = rtrim($c_value , ',');
	$value = str_replace('\\','',$productslist);
	
	$reportdate = datetimelocal('d-m-Y');
	
	$datepiece = " substring(inv_surrenderproduct.surrendertime,1,10) BETWEEN '".changedateformat($fromdate)."' AND '".changedateformat($todate)."'";
	$productcodepiece = ($chks == "")?(""):(" AND  inv_mas_product.productcode IN (".$value.") ");
	$fsurrenderbypiece = ($fsurrenderby == "")?(""):("  AND inv_mas_users.slno = '".$fsurrenderby."' ");
	$fosurrenderpiece = ($fosurrender == "")?(""):("  AND inv_surrenderproduct.forces = '".$fosurrender."' ");
	
	if($searchrefresult == "all") { $searchrefresultpiece = ""; } 
	elseif($searchrefresult == "refslno") { $searchrefresultpiece = " AND inv_surrenderproduct.refslno = '".$searchinput."' " ; }
	elseif($searchrefresult == "businessname") { $searchrefresultpiece = " AND inv_mas_customer.businessname like '%".$searchinput."%' " ; }
	elseif($searchrefresult == "customerid") { $searchrefresultpiece = " AND  inv_customerproduct.customerreference ='".$searchinput."' " ; }
	elseif($searchrefresult == "cardid") { $searchrefresultpiece = " AND inv_customerproduct.cardid = '".$searchinput."' " ; }
			


	
	## Report fetching Details Query##
	$querydetail = "select inv_mas_customer.businessname,inv_mas_customer.customerid,
	inv_mas_scratchcard.scratchnumber,inv_mas_product.productname,inv_surrenderproduct.HDDID,
	inv_surrenderproduct.ETHID,inv_surrenderproduct.COMPUTERNAME,inv_surrenderproduct.networkip,
	inv_surrenderproduct.surrendertime,inv_surrenderproduct.REGDATE,inv_surrenderproduct.forces,
	inv_surrenderproduct.CREATEDBY,inv_mas_users.fullname,inv_surrenderproduct.forceremarks,
	inv_surrenderproduct.refslno 
	from  inv_surrenderproduct 
	left join inv_customerproduct on inv_surrenderproduct.refslno = inv_customerproduct.slno
	left join inv_mas_users on inv_mas_users.slno = inv_surrenderproduct.userref
	left join inv_mas_customer on inv_customerproduct.customerreference = SUBSTR(inv_mas_customer.slno,-5)
	left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_customerproduct.cardid
	left join inv_mas_product on inv_mas_product.productcode = SUBSTR(inv_customerproduct.computerid,1,3)
	where ";
	
		if($todate <> '' && $fromdate <> '')
		{
			
			##Max Count is validating ##
			if($searchrefresult == 'maxcount') 
			{ 
				// Max Count Got from POST field
				$maxcount; 
				
				$query1 = "select distinct(refslno) from inv_surrenderproduct";		
				$result1 = runmysqlquery($query1);
				$i=0;
				while($fetch1 = mysqli_fetch_array($result1))
				{
					/*##Fetching Refslno##
					$refslno = $fetch1['refslno'];
					
					##Fetching Count of Refslno
					$resultcount = surrendercount($refslno);
					if($maxcount < $resultcount)
					{
						//echo 'refslno '.$refslno.'<br /> count '.$maxcount.'<br /> result'.$resultcount.'<br />';
						##Count Wise Fetching Record##
						$query =  $querydetail."inv_surrenderproduct.refslno =".$refslno." order by inv_surrenderproduct.slno desc";
					}*/
					
				/*	##Fetching Refslno##
					$refslno = $fetch1['refslno'];
					##Fetching Count of Refslno
					$resultcount = surrendercount($refslno);
					//echo 'resultcount '.$resultcount;
					if($maxcount < $resultcount)
					{
						 $ref = $refslno;
					}
					else
					{
						$ref ='09'; 
					}
					##Count Wise Fetching Record##
					$query =  $querydetail.$datepiece.$productcodepiece.$fsurrenderbypiece.$fosurrenderpiece."
					 and inv_surrenderproduct.refslno = ".$ref." order by inv_surrenderproduct.refslno desc";
					//echo '<br />query'.$query ;exit;
					
					$ref = $refslno;
					//echo "<br />ref ".$ref;*/
					
					##Fetching Refslno##
					$refslno = $fetch1['refslno'];
					##Fetching Count of Refslno
					$resultcount = surrendercount($refslno);
					//echo 'resultcount '.$resultcount;
					if($maxcount < $resultcount)
					{
						$ref = $refslno;
						//echo "<br />ref ".$ref;
						if($i==0)
						{
							$ref_value = $ref;
							$i++;
						}
						else
						{
							$ref_value .=','.$ref;
						}
						//echo $ref_value;
						
					}
				}

			}
			if($maxcount <> '')
			{
				$refvalueresultpiece = (" AND inv_surrenderproduct.refslno IN (".$ref_value.") ");
				#Count Wise Fetching Record##
				$query =  $querydetail.$datepiece.$productcodepiece.$fsurrenderbypiece.$fosurrenderpiece.$refvalueresultpiece." order by inv_surrenderproduct.refslno desc ;";
				//echo '<br />query '.$query . '<br />';
			}
			else
			{
				$query = $querydetail.$datepiece.$searchrefresultpiece.$productcodepiece.$fsurrenderbypiece.$fosurrenderpiece." order by inv_surrenderproduct.refslno desc ;";		
			}
		/*	else
			{
				
				#$query = $querydetail.$datepiece.$searchrefresultpiece.$productcodepiece.$fsurrenderbypiece." order by inv_surrenderproduct.slno desc";		
				$query = $querydetail.$datepiece.$searchrefresultpiece.$productcodepiece.$fsurrenderbypiece.$fosurrenderpiece." order by inv_surrenderproduct.slno desc";		
			}*/

		}
		//echo '<br />query '.$query . '<br />';
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
		$mySheet->getStyle('A3:N3')->applyFromArray($styleArray);
		
		//Merge the cell
		$mySheet->mergeCells('A1:T1');
		$mySheet->mergeCells('A2:T2');
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'Surrendered PIN Details');
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1"A2')->getAlignment()->setWrapText(true);
	
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A3', 'Sl No')
					->setCellValue('B3', 'Company Name')
					->setCellValue('C3', 'Customer ID')
					->setCellValue('D3', 'PIN Number')
					->setCellValue('E3', 'Product Name')
					->setCellValue('F3', 'HDDID')
					->setCellValue('G3', 'ETHID')
					->setCellValue('H3', 'Computer Name')
					->setCellValue('I3', 'Network IP')
					->setCellValue('J3', 'Surrender Date')
					->setCellValue('K3', 'Registered Date')
					->setCellValue('L3', 'Surrender Details')
					->setCellValue('M3', 'Offline Remarks')
					->setCellValue('N3', 'Reference Slno');
		$j =4;
		$slno = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$slno++;
			##Surrender Date##
			if($fetch['surrendertime'] == '') 
			{	$surrenderdate = '';}
			else
			{	$surrenderdate = changedateformatwithtime($fetch['surrendertime']);}
			##Register Date##
			if($fetch['REGDATE'] == '') 
			{	$registereddate = '';}
			else
			{	$registereddate = changedateformatwithtime($fetch['REGDATE']);}
			##Surrender As Online Or Offline##
			if($fetch['forces'] == '0') 
			{	$surrenderas = 'App Surrender';}
			else
			{	$surrenderas = 'Force Surrender' ;}
			##Created BY  Online Or Offline##
			if($fetch['CREATEDBY'] == '') 
			{	$appsurrenderby = ''; }
			else
			{	$appsurrenderby = $surrenderas.'-'. $fetch['CREATEDBY'] ;}
			
			##Created BY  Username##
			if($fetch['fullname'] == '') 
			{	$forsurrenderby = '';}
			else
			{	$forsurrenderby = $surrenderas.'-'. $fetch['fullname'] ;}
			
			$surrenderby = $forsurrenderby.$appsurrenderby;
			##Customer ID fromat##
			$custid = cusidcombine($fetch['customerid']);
			
			$mySheet->setCellValue('A' . $j,$slno)
					->setCellValue('B' . $j,$fetch['businessname'])
					->setCellValue('C' . $j,$custid)
					->setCellValue('D' . $j,$fetch['scratchnumber'])
					->setCellValue('E' . $j,$fetch['productname'])
					->setCellValue('F' . $j,$fetch['HDDID'])
					->setCellValue('G' . $j,$fetch['ETHID'])
					->setCellValue('H' . $j,$fetch['COMPUTERNAME'])
					->setCellValue('I' . $j,$fetch['networkip'])
					->setCellValue('J' . $j,$surrenderdate)
					->setCellValue('K' . $j,$registereddate)
					->setCellValue('L' . $j,$surrenderby)
					->setCellValue('M' . $j,$fetch['forceremarks'])
					->setCellValue('N' . $j,$fetch['refslno']);
				
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
		$mySheet->getColumnDimension('A')->setWidth(8);
		$mySheet->getColumnDimension('B')->setWidth(60);
		$mySheet->getColumnDimension('C')->setWidth(35);
		$mySheet->getColumnDimension('D')->setWidth(30);
		$mySheet->getColumnDimension('E')->setWidth(35);
		$mySheet->getColumnDimension('F')->setWidth(15);
		$mySheet->getColumnDimension('G')->setWidth(15);
		$mySheet->getColumnDimension('H')->setWidth(20);
		$mySheet->getColumnDimension('I')->setWidth(15);
		$mySheet->getColumnDimension('J')->setWidth(25);
		$mySheet->getColumnDimension('K')->setWidth(25);
		$mySheet->getColumnDimension('L')->setWidth(60);
		$mySheet->getColumnDimension('M')->setWidth(30);
		$mySheet->getColumnDimension('N')->setWidth(10);
	
		
		if($_SERVER['HTTP_HOST'] == "nagamani" || $_SERVER['HTTP_HOST'] == "192.168.2.50" || $_SERVER['HTTP_HOST'] == "bhavesh")
		{ $addstring = "/rwm/saralimax-user"; }
		else{ $addstring = "/user"; }
		
		
		if($id == 'toexcel')
		{
			$query = 'select slno,username from inv_mas_users where slno = '.$userid.'';
			$fetchres = runmysqlqueryfetch($query);	
			$localdate = datetimelocal('Ymd');
			$localtime = datetimelocal('His');
			$filebasename = "Customer-pinno-surrender-details".$localdate."-".$localtime."-".strtolower($fetchres['username']).".xls";
			
			$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) 
			VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_customer_pinno_surrender_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
			$result = runmysqlquery($query1);
			
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) 
			values('".$userid."','".$_SERVER['REMOTE_ADDR']."','253','".date('Y-m-d').' '.date('H:i:s')."','excel_customer_pinno_surrender_report".'-'.strtolower($fetchres['username'])."')";
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
			$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','view_customer_pinno_surrender_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
			$result = runmysqlquery($query1);
			
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) 
			values('".$userid."','".$_SERVER['REMOTE_ADDR']."','254','".date('Y-m-d').' '.date('H:i:s')."','view_customer_pinno_surrender_report')";
			$eventresult = runmysqlquery($eventquery);
	
			$localdate = datetimelocal('Ymd');
			$localtime = datetimelocal('His');
			$filename = "viewsurrenderreport".$localdate."-".$localtime.".htm";
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
