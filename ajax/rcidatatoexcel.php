<?php
ini_set('memory_limit', '2048M');

include('../functions/phpfunctions.php');

if(imaxgetcookie('userid')<> '') 
$userid = imaxgetcookie('userid');
else
{ 
	echo(json_encode('Thinking to redirect'));
	exit;
}

//PHPExcel 
require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory
require_once '../phpgeneration/PHPExcel/IOFactory.php';

$flag = $_POST['flag'];
if($flag == '')
{
	$url = '../home/index.php?a_link=rcidataviewer'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
	
	$databasefield = $_POST['databasefield'];
	$textfield = $_POST['searchcriteria'];
	$state = $_POST['state'];
	$region = $_POST['region'];
	$district = $_POST['district'];
	$chks = $_POST['productarray'];
	for ($i = 0;$i < count($chks);$i++)
	{
		$c_value .= "'" . $chks[$i] . "'" ."," ;
	}
	$productslist = rtrim($c_value , ',');
	$productslist = str_replace('\\','',$productslist);
	
	$branch= $_POST['branch'];
	$type = $_POST['type'];
	$category= $_POST['category'];
	$operatingsystem= $_POST['os'];
	$processor= $_POST['processor'];
	
	$regionpiece = ($region == "")?(""):(" AND inv_mas_customer.region = '".$region."' ");
	$state_typepiece = ($state == "")?(""):(" AND inv_mas_district.statecode = '".$state."' ");
	$district_typepiece = ($district == "")?(""):(" AND inv_mas_customer.district = '".$district."' ");
	$branchpiece = ($branch == "")?(""):(" AND inv_mas_customer.branch = '".$branch."' ");
	$operatingsystempiece = ($operatingsystem == "")?(""):(" AND inv_logs_webservices.operatingsystem = '".$operatingsystem."' ");
	$processorpiece = ($processor == "")?(""):(" AND inv_logs_webservices.processor = '".$processor."' ");
		
	if($type == 'Not Selected')
	{
		$typepiece = ($type == "")?(""):(" AND inv_mas_customer.type = '' ");
	}
	else
	{
		$typepiece = ($type == "")?(""):(" AND inv_mas_customer.type = '".$type."' ");
	}
	if($category == 'Not Selected')
	{
		$categorypiece = ($category == "")?(""):(" AND inv_mas_customer.category = '' ");
	}
	else
	{
		$categorypiece = ($category == "")?(""):(" AND inv_mas_customer.category = '".$category."' ");
	}
	switch($databasefield)
	{
		case "customerid":
			$customeridlen = strlen($textfield);
			$lastcustomerid = cusidsplit($textfield);
			if($customeridlen == 5)
			{
				$query = "select inv_logs_webservices.date,inv_mas_customer.businessname,inv_logs_webservices.customerid,inv_mas_product.productname,inv_logs_webservices.productversion,inv_logs_webservices.operatingsystem,inv_logs_webservices.processor,inv_logs_webservices.registeredname,inv_logs_webservices.pinnumber,inv_logs_webservices.computerid,inv_logs_webservices.servicename from inv_logs_webservices left join inv_mas_customer on right(inv_logs_webservices.customerid,5) = inv_mas_customer.slno
left join inv_mas_product on inv_mas_product.productcode = left(inv_logs_webservices.computerid,3)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type
left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category where  left(inv_logs_webservices.computerid,3)  IN (".$productslist.") AND right(inv_logs_webservices.customerid,5) =
'".$textfield."' ".$regionpiece.$district_typepiece.$state_typepiece.$branchpiece.$typepiece.$categorypiece.$operatingsystempiece.$processorpiece."  ORDER BY inv_logs_webservices.date desc";
			}
			elseif($customeridlen > 5)
			{
				$query = "select inv_logs_webservices.date,inv_mas_customer.businessname,inv_logs_webservices.customerid,inv_mas_product.productname,inv_logs_webservices.productversion,inv_logs_webservices.operatingsystem,inv_logs_webservices.processor,inv_logs_webservices.registeredname,inv_logs_webservices.pinnumber,inv_logs_webservices.computerid,inv_logs_webservices.servicename from inv_logs_webservices left join inv_mas_customer on right(inv_logs_webservices.customerid,5) = inv_mas_customer.slno left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_mas_product on inv_mas_product.productcode = left(inv_logs_webservices.computerid,3)
left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type
left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category where left(inv_logs_webservices.computerid,3)  IN (".$productslist.") AND  inv_logs_webservices.customerid like
 '".$lastcustomerid."' ".$regionpiece.$district_typepiece.$state_typepiece.$branchpiece.$typepiece.$categorypiece.$operatingsystempiece.$processorpiece."  ORDER BY inv_logs_webservices.date desc";
			}
			break;
			case "pinnumber":
			$query = "select inv_logs_webservices.date,inv_mas_customer.businessname,inv_logs_webservices.customerid,inv_mas_product.productname,inv_logs_webservices.productversion,inv_logs_webservices.operatingsystem,inv_logs_webservices.processor,inv_logs_webservices.registeredname,inv_logs_webservices.pinnumber,inv_logs_webservices.computerid,inv_logs_webservices.servicename from inv_logs_webservices left join inv_mas_customer on right(inv_logs_webservices.customerid,5) = inv_mas_customer.slno left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_mas_product on inv_mas_product.productcode = left(inv_logs_webservices.computerid,3)
left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type
left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category where left(inv_logs_webservices.computerid,3)  IN (".$productslist.") AND  inv_logs_webservices.pinnumber LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$branchpiece.$typepiece.$categorypiece.$operatingsystempiece.$processorpiece."  ORDER BY inv_logs_webservices.date desc";
			break;
		case "computerid":
			$query = "select inv_logs_webservices.date,inv_mas_customer.businessname,inv_logs_webservices.customerid,inv_mas_product.productname,inv_logs_webservices.productversion,inv_logs_webservices.operatingsystem,inv_logs_webservices.processor,inv_logs_webservices.registeredname,inv_logs_webservices.pinnumber,inv_logs_webservices.computerid,inv_logs_webservices.servicename from inv_logs_webservices left join inv_mas_customer on right(inv_logs_webservices.customerid,5) = inv_mas_customer.slno left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_mas_product on inv_mas_product.productcode = left(inv_logs_webservices.computerid,3)
left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type
left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category where left(inv_logs_webservices.computerid,3)  IN (".$productslist.") AND  inv_logs_webservices.computerid LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$branchpiece.$typepiece.$categorypiece.$operatingsystempiece.$processorpiece."  ORDER BY inv_logs_webservices.date desc";
			break;
		case 'businessname':
			$query = "select inv_logs_webservices.date,inv_mas_customer.businessname,inv_logs_webservices.customerid,inv_mas_product.productname,inv_logs_webservices.productversion,inv_logs_webservices.operatingsystem,inv_logs_webservices.processor,inv_logs_webservices.registeredname,inv_logs_webservices.pinnumber,inv_logs_webservices.computerid,inv_logs_webservices.servicename from inv_logs_webservices left join inv_mas_customer on right(inv_logs_webservices.customerid,5) = inv_mas_customer.slno left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_mas_product on inv_mas_product.productcode = left(inv_logs_webservices.computerid,3)
left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type
left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category where  left(inv_logs_webservices.computerid,3)  IN (".$productslist.") AND  inv_mas_customer.businessname LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$branchpiece.$typepiece.$categorypiece.$operatingsystempiece.$processorpiece."  ORDER BY inv_logs_webservices.date desc";
			break;
			case 'registeredname':
			$query = "select inv_logs_webservices.date,inv_mas_customer.businessname,inv_logs_webservices.customerid,inv_mas_product.productname,inv_logs_webservices.productversion,inv_logs_webservices.operatingsystem,inv_logs_webservices.processor,inv_logs_webservices.registeredname,inv_logs_webservices.pinnumber,inv_logs_webservices.computerid,inv_logs_webservices.servicename from inv_logs_webservices left join inv_mas_customer on right(inv_logs_webservices.customerid,5) = inv_mas_customer.slno left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_mas_product on inv_mas_product.productcode = left(inv_logs_webservices.computerid,3)
left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type
left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category where  left(inv_logs_webservices.computerid,3)  IN (".$productslist.") AND  inv_logs_webservices.registeredname LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$branchpiece.$typepiece.$categorypiece.$operatingsystempiece.$processorpiece."  ORDER BY inv_logs_webservices.date desc";
			break;
		default:
			$query = "select inv_logs_webservices.date,inv_mas_customer.businessname,inv_logs_webservices.customerid,inv_mas_product.productname,inv_logs_webservices.productversion,inv_logs_webservices.operatingsystem,inv_logs_webservices.processor,inv_logs_webservices.registeredname,inv_logs_webservices.pinnumber,inv_logs_webservices.computerid,inv_logs_webservices.servicename from inv_logs_webservices left join inv_mas_customer on right(inv_logs_webservices.customerid,5) = inv_mas_customer.slno left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_mas_product on inv_mas_product.productcode = left(inv_logs_webservices.computerid,3)
left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type
left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category where left(inv_logs_webservices.computerid,3)  IN (".$productslist.") AND  inv_logs_webservices.registeredname LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$branchpiece.$typepiece.$categorypiece.$operatingsystempiece.$processorpiece."  ORDER BY inv_logs_webservices.date desc";
			break;
	}
	//	echo($query); exit;
		$result = runmysqlquery_old($query);
		
		// Create new PHPExcel object
		$objPHPExcel = new Spreadsheet();
		
		//Define Style for header row
		$styleArray = array(
							'font' => array('bold' => true,),
							'fill'=> array('fillType'=>\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,'startColor'=> array('argb' => 'FFCCFFCC')),
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
		$mySheet->setTitle('RCI Data');
			
		//Apply style for header Row
		$mySheet->getStyle('A3:U3')->applyFromArray($styleArray);
		
		//Merge the cell
		$mySheet->mergeCells('A1:L1');
		$mySheet->mergeCells('A2:L2');
		$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'RCI Details Report');
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
	
		
		//Fille contents for Header Row
		$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('A3', 'Sl No')
					->setCellValue('B3', 'Date')
					->setCellValue('C3', 'Company Name')
					->setCellValue('D3', 'Customer ID')
					->setCellValue('E3', 'Product Name')
					->setCellValue('F3', 'Product Version')
					->setCellValue('G3', 'Operating System')
					->setCellValue('H3', 'Processor')
					->setCellValue('I3', 'Registered Name')
					->setCellValue('J3', 'Registered PIN')
					->setCellValue('K3', 'Computer ID')
					->setCellValue('L3', 'Service Name');
		$j =4;
		$slno_count =0;
		while($fetch = mysqli_fetch_array($result))
		{
			$slno_count++;
			$mySheet->setCellValue('A' . $j,$slno_count)
					->setCellValue('B' . $j,changedateformatwithtime($fetch['date']))
					->setCellValue('C' . $j,$fetch['businessname'])
					->setCellValue('D' . $j,cusidcombine($fetch['customerid']))
					->setCellValue('E' . $j,$fetch['productname'])
					->setCellValue('F' . $j,$fetch['productversion'])
					->setCellValue('G' . $j,$fetch['operatingsystem'])
					->setCellValue('H' . $j,$fetch['processor'])
					->setCellValue('I' . $j,$fetch['registeredname'])
					->setCellValue('J' . $j,$fetch['pinnumber'])
					->setCellValue('K' . $j,$fetch['computerid'])
					->setCellValue('L' . $j,$fetch['servicename']);
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
				
				//set the default width for column
				$mySheet->getColumnDimension('A')->setWidth(6);
				$mySheet->getColumnDimension('B')->setWidth(20);
				$mySheet->getColumnDimension('C')->setWidth(35);
				$mySheet->getColumnDimension('D')->setWidth(20);
				$mySheet->getColumnDimension('E')->setWidth(20);
				$mySheet->getColumnDimension('F')->setWidth(25);
				$mySheet->getColumnDimension('G')->setWidth(25);
				$mySheet->getColumnDimension('H')->setWidth(25);
				$mySheet->getColumnDimension('I')->setWidth(30);
				$mySheet->getColumnDimension('J')->setWidth(6);
				$mySheet->getColumnDimension('K')->setWidth(20);
				$mySheet->getColumnDimension('L')->setWidth(20);
				$pageindex++;

			$addstring = "/user";
			if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk"))
				$addstring = "/saralimax-user";
			
				$query = 'select slno,username from inv_mas_users where slno = '.$userid.'';
				$fetchres = runmysqlqueryfetch($query);	
				$localdate = datetimelocal('Ymd');
				$localtime = datetimelocal('His');
				$filebasename = "RCIdata".$localdate."-".$localtime."-".strtolower($fetchres['username']).".xls";
				
				$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filebasename;
				$downloadlink = 'http://'.$_SERVER['HTTP_HOST'].$addstring.'/filecreated/'.$filebasename;
		
				$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_rcidata_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
				$result = runmysqlquery($query1);
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','60','".date('Y-m-d').' '.date('H:i:s')."','excel_rcidata_report".'-'.strtolower($fetchres['username'])."')";
				$eventresult = runmysqlquery($eventquery);
				
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
	
?>
