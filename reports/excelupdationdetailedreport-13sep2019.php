<?
ini_set('memory_limit', '2048M');

include('../functions/phpfunctions.php');


//PHPExcel 
require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory
require_once '../phpgeneration/PHPExcel/IOFactory.php';

$flag = $_POST['flag'];
if($flag == '')
{
	$url = '../home/index.php?a_link=updationdetailedreport'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
	$userid = imaxgetcookie('userid');
	$group = $_POST['group'];
	$dealerid = $_POST['dealerid'];
	$branch = $_POST['branch'];
	$type = $_POST['type'];
	$category = $_POST['category'];
	$region = $_POST['region'];
	$activecustomer_type = $_POST['activecustomer_type'];
	$includemainsheet = $_POST['includemainsheet'];
	$summarize = $_POST['summarize'];
	
	
	if(in_array('regionwise', $summarize, true))
		$regionwise = 'regionwise';
	else
		$regionwise = '';
		
	if(in_array('branchwise', $summarize, true))
		$branchwise = 'branchwise';
	else
		$branchwise = '';
		
	if(in_array('statewise', $summarize, true))
		$statewise = 'statewise';
	else
		$statewise = '';
		
	if(in_array('dealerwise', $summarize, true))
		$dealerwise = 'dealerwise';
	else
		$dealerwise = '';
		
	if(in_array('customertypewise', $summarize, true))
		$customertypewise = 'customertypewise';
	else
		$customertypewise = '';
		
	if(in_array('customercategorywise', $summarize, true))
		$customercategorywise = 'customercategorywise';
	else
		$customercategorywise = '';
		
		$districtwise = 'district';
		
		//echo($regionwise); exit;

	
	$regionpiece = ($region == "")?(""):(" AND inv_mas_customer.region = '".$region."' ");
	$dealerpiece = ($dealerid == "")?(""):("AND  inv_mas_dealer.slno = '".$dealerid."' ");
	$typepiece = ($type == "")?(""):(" AND inv_mas_customer.type = '".$type."' ");
	$branchpiece = ($branch == "")?(""):(" AND inv_mas_customer.branch = '".$branch."' ");
	$categorypiece = ($category == "")?(""):(" AND inv_mas_customer.category = '".$category."' ");
	
	
	$query2 = "create temporary table prd200405 (select distinct inv_customerproduct.customerreference,  inv_mas_product.group
	from inv_customerproduct left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) 
	where reregistration = 'no' AND inv_mas_product.year = '2004-05' AND inv_mas_product.group = '".$group."') ;";
	$result2 = runmysqlquery($query2);
	
	$query2 = "ALTER table prd200405 add index (customerreference);";
	$result2 = runmysqlquery($query2);
	
	$query2 = "create temporary table prd200506 select distinct inv_customerproduct.customerreference,  inv_mas_product.group
	from inv_customerproduct left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) 
	where reregistration = 'no' AND inv_mas_product.year = '2005-06' AND inv_mas_product.group = '".$group."';";
	$result2 = runmysqlquery($query2);
	
	$query2 = "ALTER table prd200506 add index (customerreference);";
	$result2 = runmysqlquery($query2);
	
	$query2 = "create temporary table prd200607 select distinct inv_customerproduct.customerreference,  inv_mas_product.group
	from inv_customerproduct left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) 
	where reregistration = 'no' AND inv_mas_product.year = '2006-07' AND inv_mas_product.group = '".$group."';";
	$result2 = runmysqlquery($query2);
	
	$query2 = "ALTER table prd200607 add index (customerreference);";
	$result2 = runmysqlquery($query2);
	
	$query2 = "create temporary table prd200708 select distinct inv_customerproduct.customerreference,  inv_mas_product.group
	from inv_customerproduct left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) 
	where reregistration = 'no' AND inv_mas_product.year = '2007-08' AND inv_mas_product.group = '".$group."';";
	$result2 = runmysqlquery($query2);
	
	$query2 = "ALTER table prd200708 add index (customerreference);";
	$result2 = runmysqlquery($query2);
	
	$query2 = "create temporary table prd200809 select distinct inv_customerproduct.customerreference,  inv_mas_product.group
	from inv_customerproduct 
	left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) 
	where reregistration = 'no' AND inv_mas_product.year = '2008-09' AND inv_mas_product.group = '".$group."';";
	$result2 = runmysqlquery($query2);
	
	$query2 = "ALTER table prd200809 add index (customerreference);";
	$result2 = runmysqlquery($query2);
	
	$query2 = "create temporary table prd200910 select distinct inv_customerproduct.customerreference,  inv_mas_product.group
	from inv_customerproduct left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) 
	where reregistration = 'no' AND inv_mas_product.year = '2009-10' AND inv_mas_product.group = '".$group."';";
	$result2 = runmysqlquery($query2);
	
	$query2 = "ALTER table prd200910 add index (customerreference);";
	$result2 = runmysqlquery($query2);
	
	$query2 = "create temporary table prd201011 select distinct inv_customerproduct.customerreference,  inv_mas_product.group
	from inv_customerproduct left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) 
	where reregistration = 'no' AND inv_mas_product.year = '2010-11' AND inv_mas_product.group = '".$group."';";
	$result2 = runmysqlquery($query2);
	
	
	$query2 = "ALTER table prd201011 add index (customerreference);";
	$result2 = runmysqlquery($query2);
	
	
	$query2 = "create temporary table prd201112 select distinct inv_customerproduct.customerreference,  inv_mas_product.group
	from inv_customerproduct left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) 
	where reregistration = 'no' AND inv_mas_product.year = '2011-12' AND inv_mas_product.group = '".$group."';";
	$result2 = runmysqlquery($query2);
	
	
	$query2 = "ALTER table prd201112 add index (customerreference);";
	$result2 = runmysqlquery($query2);
	
	$query24 = "create temporary table prd201213 select distinct inv_customerproduct.customerreference,  inv_mas_product.group
	from inv_customerproduct left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) 
	where reregistration = 'no' AND inv_mas_product.year = '2012-13' AND inv_mas_product.group = '".$group."';";
	$result24 = runmysqlquery($query24);
	
	
	$query24 = "ALTER table prd201213 add index (customerreference);";
	$result24 = runmysqlquery($query24);
	
	$query2 = "create temporary table yearwise_customers select 
inv_mas_customer.slno,inv_mas_customer.customerid,inv_mas_customer.businessname,inv_mas_district.districtname as district,inv_mas_state.statename as state,inv_mas_region.category as region,inv_mas_branch.branchname as branch,inv_mas_customertype.customertype as custype,inv_mas_customercategory.businesstype as cuscategory,inv_mas_dealer.businessname as dealer,
IFNULL(prd200405.group,'') as prd200405,IFNULL(prd200506.group,'') as prd200506,IFNULL(prd200607.group,'') as prd200607,IFNULL(prd200708.group,'') as prd200708,IFNULL(prd200809.group,'') as prd200809,IFNULL(prd200910.group,'') as prd200910,IFNULL(prd201011.group,'') as prd201011,IFNULL(prd201112.group,'') as prd201112,IFNULL(prd201213.group,'') as prd201213
from inv_mas_customer left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer
left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district 
left join inv_mas_state on  inv_mas_state.statecode = inv_mas_district.statecode 
left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type 
left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category 
left join prd200405 on inv_mas_customer.slno = prd200405.customerreference
left join prd200506 on inv_mas_customer.slno = prd200506.customerreference
left join prd200607 on inv_mas_customer.slno = prd200607.customerreference
left join prd200708 on inv_mas_customer.slno = prd200708.customerreference
left join prd200809 on inv_mas_customer.slno = prd200809.customerreference
left join prd200910 on inv_mas_customer.slno = prd200910.customerreference
left join prd201011 on inv_mas_customer.slno = prd201011.customerreference
left join prd201112 on inv_mas_customer.slno = prd201112.customerreference
left join prd201213 on inv_mas_customer.slno = prd201213.customerreference
WHERE (prd200405.group IS NOT NULL OR prd200506.group IS NOT NULL OR prd200607.group IS NOT NULL OR prd200708.group IS NOT NULL OR prd200809.group IS NOT NULL OR prd200910.group IS NOT NULL OR prd201011.group IS NOT NULL OR prd201112.group IS NOT NULL OR prd201213.group IS NOT NULL) ".$dealerpiece.$regionpiece.$branchpiece.$typepiece.$categorypiece.";";

		$result2 = runmysqlquery($query2);
		
		// Create new Spreadsheet object
		$objPHPExcel= new Spreadsheet();
		
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
		$pageindex = 0;
		if($includemainsheet == 'yes')
		{
			//Set Active Sheet	
			$mySheet = $objPHPExcel->getActiveSheet();
			
			//Set the worksheet name
			$mySheet->setTitle('Main ('.strtoupper($group).")");
				
	
			//Apply style for header Row
			$mySheet->getStyle('A3:W3')->applyFromArray($styleArray);
			
			//Merge the cell
			$mySheet->mergeCells('A1:W1');
			$mySheet->mergeCells('A2:W2');
			$objPHPExcel->setActiveSheetIndex($pageindex)
						->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
						->setCellValue('A2', 'Updation Detailed Report');
			$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
			$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
			$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
		
			
			//Fille contents for Header Row
			$objPHPExcel->setActiveSheetIndex($pageindex)
						->setCellValue('A3', 'Sl No')
						->setCellValue('B3', 'Customer ID')
						->setCellValue('C3', 'Business Name')
						->setCellValue('D3', 'District')
						->setCellValue('E3', 'State')
						->setCellValue('F3', 'Contact Person')
						->setCellValue('G3', 'Cell')
						->setCellValue('H3', 'Phone')
						->setCellValue('I3', 'Emailid')
						->setCellValue('J3', 'Region')
						->setCellValue('K3', 'Branch')
						->setCellValue('L3', 'Type')
						->setCellValue('M3', 'Category')
						->setCellValue('N3', 'Dealer')
						->setCellValue('O3', '2004-05')
						->setCellValue('P3', '2005-06')
						->setCellValue('Q3', '2006-07')
						->setCellValue('R3', '2007-08')
						->setCellValue('S3', '2008-09')
						->setCellValue('T3', '2009-10')
						->setCellValue('U3', '2010-11')
						->setCellValue('V3', '2011-12')
						->setCellValue('W3', '2012-13');
			$j =4;
			$slno_count = 0;
			
			$arrayreplace = array(',,,',',,');
			
			$groupyear1 = str_replace('-','',$fetch2['year']);
			$query23 = "select * from yearwise_customers;";
			$result23 = runmysqlquery($query23);
			while($fetch = mysqli_fetch_array($result23))
			{
				// Fetch contact Details
				$querycontactdetails = "select customerid, GROUP_CONCAT(contactperson) as contactperson,  
GROUP_CONCAT(phone) as phone, GROUP_CONCAT(cell) as cell, GROUP_CONCAT(emailid) as emailid from inv_contactdetails where customerid = '".$fetch['slno']."'  group by customerid ";
				$resultcontact = runmysqlquery($querycontactdetails);
				$resultcontactdetails = mysqli_fetch_array($resultcontact);
				//$resultcontactdetails = runmysqlqueryfetch($querycontactdetails);
				
				$contactvalues = removedoublecomma($resultcontactdetails['contactperson']);
				$phoneres = removedoublecomma($resultcontactdetails['phone']);
				$cellres = removedoublecomma($resultcontactdetails['cell']);
				$emailidres = removedoublecomma($resultcontactdetails['emailid']);
				
				
				$prd200405 = ($fetch['prd200405'] == '')?('NO'):('YES');
				$prd200506 = ($fetch['prd200506'] == '')?('NO'):('YES');
				$prd200607 = ($fetch['prd200607'] == '')?('NO'):('YES');
				$prd200708 = ($fetch['prd200708'] == '')?('NO'):('YES');
				$prd200809 = ($fetch['prd200809'] == '')?('NO'):('YES');
				$prd200910 = ($fetch['prd200910'] == '')?('NO'):('YES');
				$prd201011 = ($fetch['prd201011'] == '')?('NO'):('YES');
				$prd201112 = ($fetch['prd201112'] == '')?('NO'):('YES');
				$prd201213 = ($fetch['prd201213'] == '')?('NO'):('YES');
					
				$slno_count++;
				$mySheet->setCellValue('A' . $j,$slno_count)
						->setCellValue('B' . $j,cusidcombine($fetch['customerid']))
						->setCellValue('C' . $j,$fetch['businessname'])
						->setCellValue('D' . $j,$fetch['district'])
						->setCellValue('E' . $j,$fetch['state'])
						->setCellValue('F' . $j,trim($contactvalues,','))
						->setCellValue('G' . $j,trim($cellres,','))
						->setCellValue('H' . $j,trim($phoneres,','))
						->setCellValue('I' . $j,trim($emailidres,','))
						->setCellValue('J' . $j,$fetch['region'])
						->setCellValue('K' . $j,$fetch['branch'])
						->setCellValue('L' . $j,$fetch['custype'])
						->setCellValue('M' . $j,$fetch['cuscategory'])
						->setCellValue('N' . $j,$fetch['dealer'])
						->setCellValue('O' . $j,$prd200405)
						->setCellValue('P' . $j,$prd200506)
						->setCellValue('Q' . $j,$prd200607)
						->setCellValue('R' . $j,$prd200708)
						->setCellValue('S' . $j,$prd200809)
						->setCellValue('T' . $j,$prd200910)
						->setCellValue('U' . $j,$prd201011)
						->setCellValue('V' . $j,$prd201112)
						->setCellValue('W' . $j,$prd201213);
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
				$mySheet->getColumnDimension('M')->setWidth(20);
				$mySheet->getColumnDimension('N')->setWidth(20);
				$mySheet->getColumnDimension('O')->setWidth(32);
				$mySheet->getColumnDimension('P')->setWidth(10);
				$mySheet->getColumnDimension('Q')->setWidth(10);
				$mySheet->getColumnDimension('R')->setWidth(10);
				$mySheet->getColumnDimension('S')->setWidth(10);
				$mySheet->getColumnDimension('T')->setWidth(10);
				$mySheet->getColumnDimension('U')->setWidth(10);
				$mySheet->getColumnDimension('V')->setWidth(10);
				$mySheet->getColumnDimension('W')->setWidth(10);
				$pageindex++;
				
				//Begin with Worksheet 2 (Summary)
				$objPHPExcel->createSheet();
				$objPHPExcel->setActiveSheetIndex($pageindex);
			}

			//Set Active Sheet	
			$mySheet = $objPHPExcel->getActiveSheet();
			
			//Set the worksheet name
			$mySheet->setTitle('Total Customers ('.strtoupper($group).")");
			
			//Merge the cell
			$mySheet->mergeCells('A1:I1');
			$objPHPExcel->setActiveSheetIndex($pageindex)
						->setCellValue('A1', 'Total Customers');
			$mySheet->getStyle('A1')->getFont()->setSize(12); 	
			$mySheet->getStyle('A1')->getFont()->setBold(true); 
			$mySheet->getStyle('A1')->getAlignment()->setWrapText(true);
			
			if($regionwise == "regionwise")
			{
				//Begin writing Region wise
				$currentrow = 3;
				
				//Set heading 
				$mySheet->setCellValue('A'.$currentrow,'Region wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'Region')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12')
						->setCellValue('K'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select region,count(prd200405 = '".$group."' or null) as prd200405,count(prd200506 = '".$group."' or null) as prd200506,count(prd200607 = '".$group."' or null) as prd200607 ,count(prd200708 = '".$group."' or null) as prd200708,count(prd200809 = '".$group."' or null) as prd200809,count(prd200910 = '".$group."' or null) as prd200910,count(prd201011 = '".$group."' or null) as prd201011,count(prd201112 = '".$group."' or null) as prd201112 ,count(prd201213 = '".$group."' or null) as prd201213 from yearwise_customers group by region;";
				$result = runmysqlquery($query);
				
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['region'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112'])
							->setCellValue('K'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
						->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
		
				//Apply style for content Row
				$mySheet->getStyle('B'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
			}
			
			if($branchwise == "branchwise")
			{
				//Begin writing Branch wise 
				$currentrow = $currentrow + 3;
				
				//Set heading summary
				$mySheet->setCellValue('A'.$currentrow,'Branch wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'Branch')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12')
						->setCellValue('K'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select branch,count(prd200405 = '".$group."' or null) as prd200405,count(prd200506 = '".$group."' or null) as prd200506,count(prd200607 = '".$group."' or null) as prd200607 ,count(prd200708 = '".$group."' or null) as prd200708,count(prd200809 = '".$group."' or null) as prd200809,count(prd200910 = '".$group."' or null) as prd200910,count(prd201011 = '".$group."' or null) as prd201011 ,count(prd201112 = '".$group."' or null) as prd201112,count(prd201213 = '".$group."' or null) as prd201213 from yearwise_customers group by branch;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['branch'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112'])
							->setCellValue('K'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
						->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
				
				//Apply style for Content row
				$mySheet->getStyle('B'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
				
				//set the default width for column
				$mySheet->getColumnDimension('A')->setWidth(16);
				$mySheet->getColumnDimension('B')->setWidth(17);
				$mySheet->getColumnDimension('C')->setWidth(12);
				$mySheet->getColumnDimension('D')->setWidth(12);
				$mySheet->getColumnDimension('E')->setWidth(12);
				$mySheet->getColumnDimension('F')->setWidth(12);
				$mySheet->getColumnDimension('G')->setWidth(12);
				$mySheet->getColumnDimension('H')->setWidth(12);
				$mySheet->getColumnDimension('I')->setWidth(12);
				$mySheet->getColumnDimension('J')->setWidth(12);
				$mySheet->getColumnDimension('K')->setWidth(12);
			}
			if($statewise == "statewise")
			{
				//Begin writing State wise 
				$currentrow = $currentrow + 3;
				
				//Set heading summary
				$mySheet->setCellValue('A'.$currentrow,'State wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'State')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12')
						->setCellValue('K'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select state,count(prd200405 = '".$group."' or null) as prd200405,count(prd200506 = '".$group."' or null) as prd200506,count(prd200607 = '".$group."' or null) as prd200607 ,count(prd200708 = '".$group."' or null) as prd200708,count(prd200809 = '".$group."' or null) as prd200809,count(prd200910 = '".$group."' or null) as prd200910,count(prd201011 = '".$group."' or null) as prd201011,count(prd201112 = '".$group."' or null) as prd201112,count(prd201213 = '".$group."' or null) as prd201213 from yearwise_customers group by state;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['state'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112'])
							->setCellValue('K'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
						->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
				
				//Apply style for Content row
				$mySheet->getStyle('B'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
			}
			if($dealerwise == "dealerwise")
			{
				//Begin writing dealer wise 
				$currentrow = $currentrow + 3;
				
				//Set heading summary
				$mySheet->setCellValue('A'.$currentrow,'Dealer wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'Dealer')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12')
						->setCellValue('K'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select dealer,count(prd200405 = '".$group."' or null) as prd200405,count(prd200506 = '".$group."' or null) as prd200506,count(prd200607 = '".$group."' or null) as prd200607 ,count(prd200708 = '".$group."' or null) as prd200708,count(prd200809 = '".$group."' or null) as prd200809,count(prd200910 = '".$group."' or null) as prd200910,count(prd201011 = '".$group."' or null) as prd201011,count(prd201112 = '".$group."' or null) as prd201112,count(prd201213 = '".$group."' or null) as prd201213 from yearwise_customers group by dealer;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['dealer'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112'])
							->setCellValue('K'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
						->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
				
				//Apply style for Content row
				$mySheet->getStyle('B'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
			}
			if($customertypewise == "customertypewise")
			{
				//Begin writing dealer wise 
				$currentrow = $currentrow + 3;
				
				//Set heading summary
				$mySheet->setCellValue('A'.$currentrow,'Customer Type wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'Customer Type')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12')
						->setCellValue('K'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select custype,count(prd200405 = '".$group."' or null) as prd200405,count(prd200506 = '".$group."' or null) as prd200506,count(prd200607 = '".$group."' or null) as prd200607 ,count(prd200708 = '".$group."' or null) as prd200708,count(prd200809 = '".$group."' or null) as prd200809,count(prd200910 = '".$group."' or null) as prd200910,count(prd201011 = '".$group."' or null) as prd201011,count(prd201112 = '".$group."' or null) as prd201112 ,count(prd201213 = '".$group."' or null) as prd201213 from yearwise_customers group by custype;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['custype'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112'])
							->setCellValue('K'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
						->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
				
				//Apply style for Content row
				$mySheet->getStyle('B'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
			}
			if($customercategorywise == "customercategorywise")
			{
				//Begin writing dealer wise 
				$currentrow = $currentrow + 3;
				
				//Set heading summary
				$mySheet->setCellValue('A'.$currentrow,'Customer Category wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'Customer Category')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12')
						->setCellValue('K'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select cuscategory,count(prd200405 = '".$group."' or null) as prd200405,count(prd200506 = '".$group."' or null) as prd200506,count(prd200607 = '".$group."' or null) as prd200607 ,count(prd200708 = '".$group."' or null) as prd200708,count(prd200809 = '".$group."' or null) as prd200809,count(prd200910 = '".$group."' or null) as prd200910,count(prd201011 = '".$group."' or null) as prd201011,count(prd201112 = '".$group."' or null) as prd201112,count(prd201213 = '".$group."' or null) as prd201213 from yearwise_customers group by cuscategory;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['cuscategory'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112'])
							->setCellValue('K'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
						->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
				
				//Apply style for Content row
				$mySheet->getStyle('B'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
			}
			/*if($districtwise == "district")
			{
				//Begin writing dealer wise 
				$currentrow = $currentrow + 3;
				
				//Set heading summary
				$mySheet->setCellValue('A'.$currentrow,'District Wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'District')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':J'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select district,count(prd200405 = '".$group."' or null) as prd200405,count(prd200506 = '".$group."' or null) as prd200506,count(prd200607 = '".$group."' or null) as prd200607 ,count(prd200708 = '".$group."' or null) as prd200708,count(prd200809 = '".$group."' or null) as prd200809,count(prd200910 = '".$group."' or null) as prd200910,count(prd201011 = '".$group."' or null) as prd201011,count(prd201112 = '".$group."' or null) as prd201112 from yearwise_customers where state = 'Karnataka' group by district ;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['district'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				
				//Apply style for Content row
				$mySheet->getStyle('B'.$databeginrow.':J'.$currentrow)->applyFromArray($styleArrayContent);
			}*/
			//Begin with Worksheet 3 (New sales)
			$pageindex++;
			$objPHPExcel->createSheet();
			$objPHPExcel->setActiveSheetIndex($pageindex);
		
			//Set Active Sheet	
			$mySheet = $objPHPExcel->getActiveSheet();
			
			//Set the worksheet name
			$mySheet->setTitle('New Sales ('.strtoupper($group).")");
			
			//Merge the cell
			$mySheet->mergeCells('A1:K1');
			$objPHPExcel->setActiveSheetIndex($pageindex)
						->setCellValue('A1', 'New Sales');
			$mySheet->getStyle('A1')->getFont()->setSize(12); 	
			$mySheet->getStyle('A1')->getFont()->setBold(true); 
			$mySheet->getStyle('A1')->getAlignment()->setWrapText(true);
			$currentrow = 0;
			if($regionwise == "regionwise")
			{
				//Begin writing Region wise
				$currentrow = 3;
				
				//Set heading 
				$mySheet->setCellValue('A'.$currentrow,'Region wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'Region')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12')
						->setCellValue('K'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select region,
				count(prd200405 = '".$group."' or null) as prd200405, 
				count((prd200506 = '".$group."' or null) and (prd200405 <> '".$group."' or not null)) as prd200506, 
				count((prd200607 = '".$group."' or null) and (prd200506 <> '".$group."' or not null)and (prd200405 <> '".$group."' or not null)) as prd200607,
				count((prd200708 = '".$group."' or null) and (prd200607 <> '".$group."' or not null)and (prd200506 <> '".$group."' or not null)and (prd200405 <> '".$group."' or not null)) as prd200708 ,
				count((prd200809 = '".$group."' or null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd200809 ,
				count((prd200910 = '".$group."' or null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd200910 , 
				count((prd201011 = '".$group."' or null) and (prd200910 <> '".$group."' or not null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd201011, 
				count((prd201112 = '".$group."' or null) and (prd201011 <> '".$group."' or null) and(prd200910 <> '".$group."' or not null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd201112 , 
				count((prd201213 = '".$group."' or null)and (prd201112 <> '".$group."' or null) and (prd201011 <> '".$group."' or null) and(prd200910 <> '".$group."' or not null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd201213 
				from yearwise_customers group by region;";
				$result = runmysqlquery($query);
				
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['region'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112'])
							->setCellValue('K'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
						->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
				
				//Apply style for content Row
				$mySheet->getStyle('B'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
			}
			if($branchwise == 'branchwise')
			{
				//Begin writing Branch wise 
				$currentrow = $currentrow + 3;
				
				//Set heading summary
				$mySheet->setCellValue('A'.$currentrow,'Branch Wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'Branch')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12')
						->setCellValue('K'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select branch,count(prd200405 = '".$group."' or null) as prd200405, count((prd200506 = '".$group."' or null) and (prd200405 <> '".$group."' or not null)) as prd200506, count((prd200607 = '".$group."' or null) and (prd200506 <> '".$group."' or not null)and (prd200405 <> '".$group."' or not null)) as prd200607,count((prd200708 = '".$group."' or null) and (prd200607 <> '".$group."' or not null)and (prd200506 <> '".$group."' or not null)and (prd200405 <> '".$group."' or not null)) as prd200708 ,count((prd200809 = '".$group."' or null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd200809 ,count((prd200910 = '".$group."' or null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd200910 , count((prd201011 = '".$group."' or null) and (prd200910 <> '".$group."' or not null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd201011,  count((prd201112 = '".$group."' or null) and (prd201011 <> '".$group."' or null) and(prd200910 <> '".$group."' or not null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd201112,  count((prd201213 = '".$group."' or null)and (prd201112 <> '".$group."' or null) and (prd201011 <> '".$group."' or null) and(prd200910 <> '".$group."' or not null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd201213 from yearwise_customers group by branch;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['branch'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112'])
							->setCellValue('K'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
						->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
				
				//Apply style for Content row
				$mySheet->getStyle('B'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
				
				//set the default width for column
				$mySheet->getColumnDimension('A')->setWidth(16);
				$mySheet->getColumnDimension('B')->setWidth(17);
				$mySheet->getColumnDimension('C')->setWidth(12);
				$mySheet->getColumnDimension('D')->setWidth(12);
				$mySheet->getColumnDimension('E')->setWidth(12);
				$mySheet->getColumnDimension('F')->setWidth(12);
				$mySheet->getColumnDimension('G')->setWidth(12);
				$mySheet->getColumnDimension('H')->setWidth(12);
				$mySheet->getColumnDimension('I')->setWidth(12);
				$mySheet->getColumnDimension('J')->setWidth(12);
				$mySheet->getColumnDimension('K')->setWidth(12);
				
			}
			if($statewise == 'statewise')
			{
				//Begin writing State wise 
				$currentrow = $currentrow + 3;
				
				//Set heading summary
				$mySheet->setCellValue('A'.$currentrow,'State Wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'state')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12')
						->setCellValue('K'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select state,count(prd200405 = '".$group."' or null) as prd200405, count((prd200506 = '".$group."' or null) and (prd200405 <> '".$group."' or not null)) as prd200506, count((prd200607 = '".$group."' or null) and (prd200506 <> '".$group."' or not null)and (prd200405 <> '".$group."' or not null)) as prd200607,count((prd200708 = '".$group."' or null) and (prd200607 <> '".$group."' or not null)and (prd200506 <> '".$group."' or not null)and (prd200405 <> '".$group."' or not null)) as prd200708 ,count((prd200809 = '".$group."' or null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd200809 ,count((prd200910 = '".$group."' or null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd200910 , count((prd201011 = '".$group."' or null) and (prd200910 <> '".$group."' or not null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd201011, count((prd201112 = '".$group."' or null) and (prd201011 <> '".$group."' or null) and(prd200910 <> '".$group."' or not null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd201112, count((prd201213 = '".$group."' or null)and (prd201112 <> '".$group."' or null) and (prd201011 <> '".$group."' or null) and(prd200910 <> '".$group."' or not null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd201213 from yearwise_customers group by state;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['state'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112'])
							->setCellValue('K'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
						->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
				
				//Apply style for Content row
				$mySheet->getStyle('B'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
			}
			if($dealerwise == 'dealerwise')
			{
				//Begin writing Dealer wise 
				$currentrow = $currentrow + 3;
				
				//Set heading summary
				$mySheet->setCellValue('A'.$currentrow,'Dealer Wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'Dealer')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12')
						->setCellValue('K'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select dealer,count(prd200405 = '".$group."' or null) as prd200405, count((prd200506 = '".$group."' or null) and (prd200405 <> '".$group."' or not null)) as prd200506, count((prd200607 = '".$group."' or null) and (prd200506 <> '".$group."' or not null)and (prd200405 <> '".$group."' or not null)) as prd200607,count((prd200708 = '".$group."' or null) and (prd200607 <> '".$group."' or not null)and (prd200506 <> '".$group."' or not null)and (prd200405 <> '".$group."' or not null)) as prd200708 ,count((prd200809 = '".$group."' or null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd200809 ,count((prd200910 = '".$group."' or null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd200910 , count((prd201011 = '".$group."' or null) and (prd200910 <> '".$group."' or not null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd201011,  count((prd201112 = '".$group."' or null) and (prd201011 <> '".$group."' or null) and(prd200910 <> '".$group."' or not null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd201112,  count((prd201213 = '".$group."' or null)and (prd201112 <> '".$group."' or null) and (prd201011 <> '".$group."' or null) and(prd200910 <> '".$group."' or not null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd201213 from yearwise_customers group by dealer;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['dealer'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112'])
							->setCellValue('K'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
						->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
				
				//Apply style for Content row
				$mySheet->getStyle('B'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
			}
			if($customertypewise == 'customertypewise')
			{
				//Begin writing Dealer wise 
				$currentrow = $currentrow + 3;
				
				//Set heading summary
				$mySheet->setCellValue('A'.$currentrow,'Customer Type Wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'Customer Type')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12')
						->setCellValue('K'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select custype,count(prd200405 = '".$group."' or null) as prd200405, count((prd200506 = '".$group."' or null) and (prd200405 <> '".$group."' or not null)) as prd200506, count((prd200607 = '".$group."' or null) and (prd200506 <> '".$group."' or not null)and (prd200405 <> '".$group."' or not null)) as prd200607,count((prd200708 = '".$group."' or null) and (prd200607 <> '".$group."' or not null)and (prd200506 <> '".$group."' or not null)and (prd200405 <> '".$group."' or not null)) as prd200708 ,count((prd200809 = '".$group."' or null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd200809 ,count((prd200910 = '".$group."' or null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd200910 , count((prd201011 = '".$group."' or null) and (prd200910 <> '".$group."' or not null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd201011,  count((prd201112 = '".$group."' or null) and (prd201011 <> '".$group."' or null) and(prd200910 <> '".$group."' or not null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd201112,  count((prd201213 = '".$group."' or null)and (prd201112 <> '".$group."' or null) and (prd201011 <> '".$group."' or null) and(prd200910 <> '".$group."' or not null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd201213 from yearwise_customers group by custype;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['custype'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112'])
							->setCellValue('K'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
						->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
				
				//Apply style for Content row
				$mySheet->getStyle('B'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
			}
			if($customercategorywise == 'customercategorywise')
			{
				//Begin writing Dealer wise 
				$currentrow = $currentrow + 3;
				
				//Set heading summary
				$mySheet->setCellValue('A'.$currentrow,'Customer Category Wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'Customer Category')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12')
						->setCellValue('K'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':k'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select cuscategory,count(prd200405 = '".$group."' or null) as prd200405, count((prd200506 = '".$group."' or null) and (prd200405 <> '".$group."' or not null)) as prd200506, count((prd200607 = '".$group."' or null) and (prd200506 <> '".$group."' or not null)and (prd200405 <> '".$group."' or not null)) as prd200607,count((prd200708 = '".$group."' or null) and (prd200607 <> '".$group."' or not null)and (prd200506 <> '".$group."' or not null)and (prd200405 <> '".$group."' or not null)) as prd200708 ,count((prd200809 = '".$group."' or null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd200809 ,count((prd200910 = '".$group."' or null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd200910 , count((prd201011 = '".$group."' or null) and (prd200910 <> '".$group."' or not null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd201011, count((prd201112 = '".$group."' or null) and (prd201011 <> '".$group."' or null) and(prd200910 <> '".$group."' or not null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd201112 , count((prd201213 = '".$group."' or null) and (prd201112 <> '".$group."' or null) and (prd201011 <> '".$group."' or null) and(prd200910 <> '".$group."' or not null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd201213 from yearwise_customers group by cuscategory;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['cuscategory'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112'])
							->setCellValue('K'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
						->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
				
				//Apply style for Content row
				$mySheet->getStyle('B'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
			}
			
			/*if($districtwise == 'district')
			{
				//Begin writing Dealer wise 
				$currentrow = $currentrow + 3;
				
				//Set heading summary
				$mySheet->setCellValue('A'.$currentrow,'District Wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'District')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':J'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select district,count(prd200405 = '".$group."' or null) as prd200405, count((prd200506 = '".$group."' or null) and (prd200405 <> '".$group."' or not null)) as prd200506, count((prd200607 = '".$group."' or null) and (prd200506 <> '".$group."' or not null)and (prd200405 <> '".$group."' or not null)) as prd200607,count((prd200708 = '".$group."' or null) and (prd200607 <> '".$group."' or not null)and (prd200506 <> '".$group."' or not null)and (prd200405 <> '".$group."' or not null)) as prd200708 ,count((prd200809 = '".$group."' or null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd200809 ,count((prd200910 = '".$group."' or null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd200910 , count((prd201011 = '".$group."' or null) and (prd200910 <> '".$group."' or not null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd201011, count((prd201112 = '".$group."' or null) and (prd201011 <> '".$group."' or null) and(prd200910 <> '".$group."' or not null) and (prd200809 <> '".$group."' or not null) and (prd200708 <> '".$group."' or not null) and (prd200607 <> '".$group."' or not null) and (prd200506 <> '".$group."' or not null) and (prd200405 <> '".$group."' or not null)) as prd201112 from yearwise_customers where state = 'karnataka' group by district;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['district'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				
				//Apply style for Content row
				$mySheet->getStyle('B'.$databeginrow.':J'.$currentrow)->applyFromArray($styleArrayContent);
			}*/
			
			//Begin with Worksheet 4 (Updations)
			$pageindex++;
			$objPHPExcel->createSheet();
			$objPHPExcel->setActiveSheetIndex($pageindex);
		
			//Set Active Sheet	
			$mySheet = $objPHPExcel->getActiveSheet();
			
			//Set the worksheet name
			$mySheet->setTitle('Updations ('.strtoupper($group).")");
			
			//Merge the cell
			$mySheet->mergeCells('A1:K1');
			$objPHPExcel->setActiveSheetIndex($pageindex)
						->setCellValue('A1', 'Updations');
			$mySheet->getStyle('A1')->getFont()->setSize(12); 	
			$mySheet->getStyle('A1')->getFont()->setBold(true); 
			$mySheet->getStyle('A1')->getAlignment()->setWrapText(true);
			
			$currentrow = 3;
			//Set heading 
			$mySheet->setCellValue('B'.($currentrow-1),'All Time Comparision');
			
			$currentrow = 0;
			if($regionwise == 'regionwise')
			{
				//Begin writing Region wise
				$currentrow = 3;
			
				$mySheet->setCellValue('A'.$currentrow,'Region wise');

				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'Region')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12')
						->setCellValue('K'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select region,0 as prd200405, count((prd200506 = '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 = '".$group."' or null) and ((prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200607,count((prd200708 = '".$group."' or null) and ((prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200708 ,count((prd200809 = '".$group."' or null) and ((prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or  null))) as prd200809 ,count((prd200910 = '".$group."' or null) and ((prd200809 = '".$group."' or  null) or (prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or  null))) as prd200910 ,	
				count((prd201011 = '".$group."' or null) and ((prd200910 = '".$group."' or null) or (prd200809 = '".$group."' or null) or (prd200708 = '".$group."' or null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or null))) as prd201011, 
				count((prd201112 = '".$group."' or null) and ((prd201011 = '".$group."' or null) or (prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201112,  count((prd201213 = '".$group."' or null) and ((prd201112 = '".$group."' or null) or (prd201011 = '".$group."' or null) or(prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201213
				from yearwise_customers group by region;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['region'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112'])
							->setCellValue('K'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
						->setCellValue('K'.$currentrow,"=SUM(J".$databeginrow.":K".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
				
				//Apply style for content Row
				$mySheet->getStyle('B'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
				
				//set the default width for column
				$mySheet->getColumnDimension('A')->setWidth(16);
				$mySheet->getColumnDimension('B')->setWidth(17);
				$mySheet->getColumnDimension('C')->setWidth(12);
				$mySheet->getColumnDimension('D')->setWidth(12);
				$mySheet->getColumnDimension('E')->setWidth(12);
				$mySheet->getColumnDimension('F')->setWidth(12);
				$mySheet->getColumnDimension('G')->setWidth(12);
				$mySheet->getColumnDimension('H')->setWidth(12);
				$mySheet->getColumnDimension('I')->setWidth(12);
				$mySheet->getColumnDimension('J')->setWidth(12);
				$mySheet->getColumnDimension('K')->setWidth(12);
				
			}
			if($branchwise == 'branchwise')
			{
				//Begin writing Branch wise 
				$currentrow = $currentrow + 3;
				
				//Set heading summary
				$mySheet->setCellValue('A'.$currentrow,'Branch Wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'Branch')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12')
						->setCellValue('K'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select branch,0 as prd200405, count((prd200506 = '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 = '".$group."' or null) and ((prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200607,count((prd200708 = '".$group."' or null) and ((prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200708 ,count((prd200809 = '".$group."' or null) and ((prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or  null))) as prd200809 ,count((prd200910 = '".$group."' or null) and ((prd200809 = '".$group."' or  null) or (prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or  null))) as prd200910 , count((prd201011 = '".$group."' or null) and ((prd200910 = '".$group."' or null) or (prd200809 = '".$group."' or null) or (prd200708 = '".$group."' or null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or null))) as prd201011, count((prd201112 = '".$group."' or null) and ((prd201011 = '".$group."' or null) or (prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201112 , count((prd201213 = '".$group."' or null) and ((prd201112 = '".$group."' or null) or (prd201011 = '".$group."' or null) or (prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201213 from yearwise_customers group by branch;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['branch'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112'])
							->setCellValue('K'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
						->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
				
				//Apply style for Content row
				$mySheet->getStyle('B'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
			}
			if($statewise == 'statewise')
			{
				//Begin writing State wise 
				$currentrow = $currentrow + 3;
				
				//Set heading summary
				$mySheet->setCellValue('A'.$currentrow,'State Wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'state')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12')
						->setCellValue('K'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select state,0 as prd200405, count((prd200506 = '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 = '".$group."' or null) and ((prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200607,count((prd200708 = '".$group."' or null) and ((prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200708 ,count((prd200809 = '".$group."' or null) and ((prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or  null))) as prd200809 ,count((prd200910 = '".$group."' or null) and ((prd200809 = '".$group."' or  null) or (prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or  null))) as prd200910 , count((prd201011 = '".$group."' or null) and ((prd200910 = '".$group."' or null) or (prd200809 = '".$group."' or null) or (prd200708 = '".$group."' or null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or null))) as prd201011,count((prd201112 = '".$group."' or null) and ((prd201011 = '".$group."' or null) or (prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201112,count((prd201213 = '".$group."' or null) and ((prd201112 = '".$group."' or null) or (prd201011 = '".$group."' or null) or (prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201213 from yearwise_customers group by state;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['state'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112'])
							->setCellValue('J'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
						->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
				
				//Apply style for Content row
				$mySheet->getStyle('B'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
			}
			if($dealerwise == 'dealerwise')
			{
				//Begin writing Dealer wise 
				$currentrow = $currentrow + 3;
				
				//Set heading summary
				$mySheet->setCellValue('A'.$currentrow,'Dealer Wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'Dealer')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12')
						->setCellValue('K'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select dealer,0 as prd200405, count((prd200506 = '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 = '".$group."' or null) and ((prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200607,count((prd200708 = '".$group."' or null) and ((prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200708 ,count((prd200809 = '".$group."' or null) and ((prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or  null))) as prd200809 ,count((prd200910 = '".$group."' or null) and ((prd200809 = '".$group."' or  null) or (prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or  null))) as prd200910 , count((prd201011 = '".$group."' or null) and ((prd200910 = '".$group."' or null) or (prd200809 = '".$group."' or null) or (prd200708 = '".$group."' or null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or null))) as prd201011, count((prd201112 = '".$group."' or null) and ((prd201011 = '".$group."' or null) or (prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201112 , count((prd201213 = '".$group."' or null) and ((prd201112 = '".$group."' or null) or (prd201011 = '".$group."' or null) or (prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201213 from yearwise_customers group by dealer;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['dealer'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112'])
							->setCellValue('K'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
						->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
				
				//Apply style for Content row
				$mySheet->getStyle('B'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
			}
			if($customertypewise == 'customertypewise')
			{
				//Begin writing Dealer wise 
				$currentrow = $currentrow + 3;
				
				//Set heading summary
				$mySheet->setCellValue('A'.$currentrow,'Customer Type Wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'Customer Type')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12')
						->setCellValue('K'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select custype,0 as prd200405, count((prd200506 = '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 = '".$group."' or null) and ((prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200607,count((prd200708 = '".$group."' or null) and ((prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200708 ,count((prd200809 = '".$group."' or null) and ((prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or  null))) as prd200809 ,count((prd200910 = '".$group."' or null) and ((prd200809 = '".$group."' or  null) or (prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or  null))) as prd200910 , count((prd201011 = '".$group."' or null) and ((prd200910 = '".$group."' or null) or (prd200809 = '".$group."' or null) or (prd200708 = '".$group."' or null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or null))) as prd201011,  count((prd201112 = '".$group."' or null) and ((prd201011 = '".$group."' or null) or (prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201112,  count((prd201213 = '".$group."' or null) and ((prd201112 = '".$group."' or null) or(prd201011 = '".$group."' or null) or (prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201213 from yearwise_customers group by custype;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['custype'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112'])
							->setCellValue('K'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
						->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
				
				//Apply style for Content row
				$mySheet->getStyle('B'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
			}
			if($customercategorywise == 'customercategorywise')
			{
				//Begin writing Dealer wise 
				$currentrow = $currentrow + 3;
				
				//Set heading summary
				$mySheet->setCellValue('A'.$currentrow,'Customer Category Wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'Customer Category')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12')
						->setCellValue('K'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select cuscategory,0 as prd200405, count((prd200506 = '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 = '".$group."' or null) and ((prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200607,count((prd200708 = '".$group."' or null) and ((prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200708 ,count((prd200809 = '".$group."' or null) and ((prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or  null))) as prd200809 ,count((prd200910 = '".$group."' or null) and ((prd200809 = '".$group."' or  null) or (prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or  null))) as prd200910 ,	 count((prd201011 = '".$group."' or null) and ((prd200910 = '".$group."' or null) or (prd200809 = '".$group."' or null) or (prd200708 = '".$group."' or null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or null))) as prd201011, 	count((prd201112 = '".$group."' or null) and ((prd201011 = '".$group."' or null) or (prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201112, 	count((prd201213 = '".$group."' or null) and ((prd201112 = '".$group."' or null) or (prd201011 = '".$group."' or null) or (prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201213 from yearwise_customers group by cuscategory;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['cuscategory'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112'])
							->setCellValue('K'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
						->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
				
				//Apply style for Content row
				$mySheet->getStyle('B'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
			}
			
			
			/*if($districtwise == 'district')
			{
				//Begin writing Dealer wise 
				$currentrow = $currentrow + 3;
				
				//Set heading summary
				$mySheet->setCellValue('A'.$currentrow,'District Wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'District')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':J'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select district,0 as prd200405, count((prd200506 = '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 = '".$group."' or null) and ((prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200607,count((prd200708 = '".$group."' or null) and ((prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200708 ,count((prd200809 = '".$group."' or null) and ((prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or  null))) as prd200809 ,count((prd200910 = '".$group."' or null) and ((prd200809 = '".$group."' or  null) or (prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or  null))) as prd200910 ,	 count((prd201011 = '".$group."' or null) and ((prd200910 = '".$group."' or null) or (prd200809 = '".$group."' or null) or (prd200708 = '".$group."' or null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or null))) as prd201011, 	count((prd201112 = '".$group."' or null) and ((prd201011 = '".$group."' or null) or (prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201112 from yearwise_customers where state = 'Karnataka' group by district;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['district'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				
				//Apply style for Content row
				$mySheet->getStyle('B'.$databeginrow.':J'.$currentrow)->applyFromArray($styleArrayContent);
			}*/
			
			//previous year comparision
			//Begin writing Region wise
			
			$currentrow = 3;
			
			//Set heading 
			$mySheet->setCellValue('M'.($currentrow-1),'Previous year comparision');
			
			$currentrow = 0;
			
			if($regionwise == 'regionwise')
			{
				$currentrow = 3;
				
				$mySheet->setCellValue('L'.$currentrow,'Region wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('M'.$currentrow,'Region')
						->setCellValue('N'.$currentrow,'2004-05')
						->setCellValue('O'.$currentrow,'2005-06')
						->setCellValue('P'.$currentrow,'2006-07')
						->setCellValue('Q'.$currentrow,'2007-08')
						->setCellValue('R'.$currentrow,'2008-09')
						->setCellValue('S'.$currentrow,'2009-10')
						->setCellValue('T'.$currentrow,'2010-11')
						->setCellValue('U'.$currentrow,'2011-12')
						->setCellValue('V'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('M'.$currentrow.':V'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select region,0 as prd200405, count((prd200506 = '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 = '".$group."' or null) and (prd200506 = '".$group."' or  null)) as prd200607,count((prd200708 = '".$group."' or null) and (prd200607 = '".$group."' or  null)) as prd200708 ,count((prd200809 = '".$group."' or null) and (prd200708 = '".$group."' or  null)) as prd200809 ,count((prd200910 = '".$group."' or null) and (prd200809 = '".$group."' or  null)) as prd200910 , count((prd201011 = '".$group."' or null) and (prd200910 = '".$group."' or null)) as prd201011,count((prd201112 = '".$group."' or null) and (prd201011 = '".$group."' or null)) as prd201112 ,count((prd201213 = '".$group."' or null) and (prd201112 = '".$group."' or null)) as prd201213 from yearwise_customers group by region;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('M'.$currentrow,$row_data['region'])
							->setCellValue('N'.$currentrow,$row_data['prd200405'])
							->setCellValue('O'.$currentrow,$row_data['prd200506'])
							->setCellValue('P'.$currentrow,$row_data['prd200607'])
							->setCellValue('Q'.$currentrow,$row_data['prd200708'])
							->setCellValue('R'.$currentrow,$row_data['prd200809'])
							->setCellValue('S'.$currentrow,$row_data['prd200910'])
							->setCellValue('T'.$currentrow,$row_data['prd201011'])
							->setCellValue('U'.$currentrow,$row_data['prd201112'])
							->setCellValue('V'.$currentrow,$row_data['prd201213']);
						$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('M'.$currentrow,'Total')
						->setCellValue('N'.$currentrow,"=SUM(N".$databeginrow.":N".($currentrow-1).")")
						->setCellValue('O'.$currentrow,"=SUM(O".$databeginrow.":O".($currentrow-1).")")
						->setCellValue('P'.$currentrow,"=SUM(P".$databeginrow.":P".($currentrow-1).")")
						->setCellValue('Q'.$currentrow,"=SUM(Q".$databeginrow.":Q".($currentrow-1).")")
						->setCellValue('R'.$currentrow,"=SUM(R".$databeginrow.":R".($currentrow-1).")")
						->setCellValue('S'.$currentrow,"=SUM(S".$databeginrow.":S".($currentrow-1).")")
						->setCellValue('T'.$currentrow,"=SUM(T".$databeginrow.":T".($currentrow-1).")")
						->setCellValue('U'.$currentrow,"=SUM(U".$databeginrow.":U".($currentrow-1).")")
						->setCellValue('V'.$currentrow,"=SUM(V".$databeginrow.":V".($currentrow-1).")");
				$mySheet->getCell('N'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('O'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('P'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('Q'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('R'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('S'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('T'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('U'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('V'.$currentrow)->getCalculatedValue();
				
				//Apply style for content Row
				$mySheet->getStyle('M'.$databeginrow.':V'.$currentrow)->applyFromArray($styleArrayContent);
			}
			if($branchwise == 'branchwise')
			{
				//Begin writing Branch wise 
				$currentrow = $currentrow + 3;
				
				//Set heading 
				$mySheet->setCellValue('L'.$currentrow,'Branch wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('M'.$currentrow,'Branch')
						->setCellValue('N'.$currentrow,'2004-05')
						->setCellValue('O'.$currentrow,'2005-06')
						->setCellValue('P'.$currentrow,'2006-07')
						->setCellValue('Q'.$currentrow,'2007-08')
						->setCellValue('R'.$currentrow,'2008-09')
						->setCellValue('S'.$currentrow,'2009-10')
						->setCellValue('T'.$currentrow,'2010-11')
						->setCellValue('U'.$currentrow,'2011-12')
						->setCellValue('V'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('M'.$currentrow.':V'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select branch,0 as prd200405, count((prd200506 = '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 = '".$group."' or null) and (prd200506 = '".$group."' or  null)) as prd200607,count((prd200708 = '".$group."' or null) and (prd200607 = '".$group."' or  null)) as prd200708 ,count((prd200809 = '".$group."' or null) and (prd200708 = '".$group."' or  null)) as prd200809 ,count((prd200910 = '".$group."' or null) and (prd200809 = '".$group."' or  null)) as prd200910 , count((prd201011 = '".$group."' or null) and (prd200910 = '".$group."' or null)) as prd201011,count((prd201112 = '".$group."' or null) and (prd201011 = '".$group."' or null)) as prd201112,count((prd201213 = '".$group."' or null) and (prd201112 = '".$group."' or null)) as prd201213 from yearwise_customers group by branch;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('M'.$currentrow,$row_data['branch'])
							->setCellValue('N'.$currentrow,$row_data['prd200405'])
							->setCellValue('O'.$currentrow,$row_data['prd200506'])
							->setCellValue('P'.$currentrow,$row_data['prd200607'])
							->setCellValue('Q'.$currentrow,$row_data['prd200708'])
							->setCellValue('R'.$currentrow,$row_data['prd200809'])
							->setCellValue('S'.$currentrow,$row_data['prd200910'])
							->setCellValue('T'.$currentrow,$row_data['prd201011'])
							->setCellValue('U'.$currentrow,$row_data['prd201112'])
							->setCellValue('V'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('M'.$currentrow,'Total')
						->setCellValue('N'.$currentrow,"=SUM(N".$databeginrow.":N".($currentrow-1).")")
						->setCellValue('O'.$currentrow,"=SUM(O".$databeginrow.":O".($currentrow-1).")")
						->setCellValue('P'.$currentrow,"=SUM(P".$databeginrow.":P".($currentrow-1).")")
						->setCellValue('Q'.$currentrow,"=SUM(Q".$databeginrow.":Q".($currentrow-1).")")
						->setCellValue('R'.$currentrow,"=SUM(R".$databeginrow.":R".($currentrow-1).")")
						->setCellValue('S'.$currentrow,"=SUM(S".$databeginrow.":S".($currentrow-1).")")
						->setCellValue('T'.$currentrow,"=SUM(T".$databeginrow.":T".($currentrow-1).")")
						->setCellValue('U'.$currentrow,"=SUM(U".$databeginrow.":U".($currentrow-1).")")
						->setCellValue('V'.$currentrow,"=SUM(V".$databeginrow.":V".($currentrow-1).")");
				$mySheet->getCell('N'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('O'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('P'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('Q'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('R'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('S'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('T'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('U'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('V'.$currentrow)->getCalculatedValue();
				
				//Apply style for content Row
				$mySheet->getStyle('M'.$databeginrow.':V'.$currentrow)->applyFromArray($styleArrayContent);
				
				//set the default width for column
				$mySheet->getColumnDimension('L')->setWidth(16);
				$mySheet->getColumnDimension('M')->setWidth(17);
				$mySheet->getColumnDimension('N')->setWidth(12);
				$mySheet->getColumnDimension('O')->setWidth(12);
				$mySheet->getColumnDimension('P')->setWidth(12);
				$mySheet->getColumnDimension('Q')->setWidth(12);
				$mySheet->getColumnDimension('R')->setWidth(12);
				$mySheet->getColumnDimension('S')->setWidth(12);
				$mySheet->getColumnDimension('T')->setWidth(12);
				$mySheet->getColumnDimension('U')->setWidth(12);
				$mySheet->getColumnDimension('V')->setWidth(12);
			}
			if($statewise == 'statewise')
			{
				//Begin writing State wise 
				$currentrow = $currentrow + 3;
				
				//Set heading 
				$mySheet->setCellValue('L'.$currentrow,'State wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('M'.$currentrow,'State')
						->setCellValue('N'.$currentrow,'2004-05')
						->setCellValue('O'.$currentrow,'2005-06')
						->setCellValue('P'.$currentrow,'2006-07')
						->setCellValue('Q'.$currentrow,'2007-08')
						->setCellValue('R'.$currentrow,'2008-09')
						->setCellValue('S'.$currentrow,'2009-10')
						->setCellValue('T'.$currentrow,'2010-11')
						->setCellValue('U'.$currentrow,'2011-12')
						->setCellValue('V'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('M'.$currentrow.':V'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select state,0 as prd200405, count((prd200506 = '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 = '".$group."' or null) and (prd200506 = '".$group."' or  null)) as prd200607,count((prd200708 = '".$group."' or null) and (prd200607 = '".$group."' or  null)) as prd200708 ,count((prd200809 = '".$group."' or null) and (prd200708 = '".$group."' or  null)) as prd200809 ,count((prd200910 = '".$group."' or null) and (prd200809 = '".$group."' or  null)) as prd200910 , count((prd201011 = '".$group."' or null) and (prd200910 = '".$group."' or null)) as prd201011,count((prd201112 = '".$group."' or null) and (prd201011 = '".$group."' or null)) as prd201112 ,count((prd201213 = '".$group."' or null) and (prd201112 = '".$group."' or null)) as prd201213 from yearwise_customers group by state;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('M'.$currentrow,$row_data['state'])
							->setCellValue('N'.$currentrow,$row_data['prd200405'])
							->setCellValue('O'.$currentrow,$row_data['prd200506'])
							->setCellValue('P'.$currentrow,$row_data['prd200607'])
							->setCellValue('Q'.$currentrow,$row_data['prd200708'])
							->setCellValue('R'.$currentrow,$row_data['prd200809'])
							->setCellValue('S'.$currentrow,$row_data['prd200910'])
							->setCellValue('T'.$currentrow,$row_data['prd201011'])
							->setCellValue('U'.$currentrow,$row_data['prd201112'])
							->setCellValue('V'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('M'.$currentrow,'Total')
						->setCellValue('N'.$currentrow,"=SUM(N".$databeginrow.":N".($currentrow-1).")")
						->setCellValue('O'.$currentrow,"=SUM(O".$databeginrow.":O".($currentrow-1).")")
						->setCellValue('P'.$currentrow,"=SUM(P".$databeginrow.":P".($currentrow-1).")")
						->setCellValue('Q'.$currentrow,"=SUM(Q".$databeginrow.":Q".($currentrow-1).")")
						->setCellValue('R'.$currentrow,"=SUM(R".$databeginrow.":R".($currentrow-1).")")
						->setCellValue('S'.$currentrow,"=SUM(S".$databeginrow.":S".($currentrow-1).")")
						->setCellValue('T'.$currentrow,"=SUM(T".$databeginrow.":T".($currentrow-1).")")
						->setCellValue('U'.$currentrow,"=SUM(U".$databeginrow.":U".($currentrow-1).")")
						->setCellValue('V'.$currentrow,"=SUM(V".$databeginrow.":V".($currentrow-1).")");
				$mySheet->getCell('N'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('O'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('P'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('Q'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('R'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('S'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('T'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('U'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('V'.$currentrow)->getCalculatedValue();
				
				//Apply style for content Row
				$mySheet->getStyle('M'.$databeginrow.':V'.$currentrow)->applyFromArray($styleArrayContent);
			}
			if($dealerwise == 'dealerwise')
			{
				//Begin writing Dealer wise 
				$currentrow = $currentrow + 3;
				
				//Set heading 
				$mySheet->setCellValue('L'.$currentrow,'Dealer wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('M'.$currentrow,'Dealer')
						->setCellValue('N'.$currentrow,'2004-05')
						->setCellValue('O'.$currentrow,'2005-06')
						->setCellValue('P'.$currentrow,'2006-07')
						->setCellValue('Q'.$currentrow,'2007-08')
						->setCellValue('R'.$currentrow,'2008-09')
						->setCellValue('S'.$currentrow,'2009-10')
						->setCellValue('T'.$currentrow,'2010-11')
						->setCellValue('U'.$currentrow,'2011-12')
						->setCellValue('V'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('M'.$currentrow.':V'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select dealer,0 as prd200405, count((prd200506 = '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 = '".$group."' or null) and (prd200506 = '".$group."' or  null)) as prd200607,count((prd200708 = '".$group."' or null) and (prd200607 = '".$group."' or  null)) as prd200708 ,count((prd200809 = '".$group."' or null) and (prd200708 = '".$group."' or  null)) as prd200809 ,count((prd200910 = '".$group."' or null) and (prd200809 = '".$group."' or  null)) as prd200910 , count((prd201011 = '".$group."' or null) and (prd200910 = '".$group."' or null)) as prd201011,count((prd201112 = '".$group."' or null) and (prd201011 = '".$group."' or null)) as prd201112 ,count((prd201213 = '".$group."' or null) and (prd201112 = '".$group."' or null)) as prd201213 from yearwise_customers group by dealer;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('M'.$currentrow,$row_data['dealer'])
							->setCellValue('N'.$currentrow,$row_data['prd200405'])
							->setCellValue('O'.$currentrow,$row_data['prd200506'])
							->setCellValue('P'.$currentrow,$row_data['prd200607'])
							->setCellValue('Q'.$currentrow,$row_data['prd200708'])
							->setCellValue('R'.$currentrow,$row_data['prd200809'])
							->setCellValue('S'.$currentrow,$row_data['prd200910'])
							->setCellValue('T'.$currentrow,$row_data['prd201011'])
							->setCellValue('U'.$currentrow,$row_data['prd201112'])
							->setCellValue('V'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('M'.$currentrow,'Total')
						->setCellValue('N'.$currentrow,"=SUM(N".$databeginrow.":N".($currentrow-1).")")
						->setCellValue('O'.$currentrow,"=SUM(O".$databeginrow.":O".($currentrow-1).")")
						->setCellValue('P'.$currentrow,"=SUM(P".$databeginrow.":P".($currentrow-1).")")
						->setCellValue('Q'.$currentrow,"=SUM(Q".$databeginrow.":Q".($currentrow-1).")")
						->setCellValue('R'.$currentrow,"=SUM(R".$databeginrow.":R".($currentrow-1).")")
						->setCellValue('S'.$currentrow,"=SUM(S".$databeginrow.":S".($currentrow-1).")")
						->setCellValue('T'.$currentrow,"=SUM(T".$databeginrow.":T".($currentrow-1).")")
						->setCellValue('U'.$currentrow,"=SUM(U".$databeginrow.":U".($currentrow-1).")")
						->setCellValue('V'.$currentrow,"=SUM(V".$databeginrow.":V".($currentrow-1).")");
				$mySheet->getCell('N'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('O'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('P'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('Q'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('R'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('S'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('T'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('U'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('V'.$currentrow)->getCalculatedValue();
				
				//Apply style for content Row
				$mySheet->getStyle('M'.$databeginrow.':V'.$currentrow)->applyFromArray($styleArrayContent);
			}
			if($customertypewise == 'customertypewise')
			{
				//Begin writing Dealer wise 
				$currentrow = $currentrow + 3;
				
				//Set heading 
				$mySheet->setCellValue('L'.$currentrow,'Customer Type wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('M'.$currentrow,'Customer Type')
						->setCellValue('N'.$currentrow,'2004-05')
						->setCellValue('O'.$currentrow,'2005-06')
						->setCellValue('P'.$currentrow,'2006-07')
						->setCellValue('Q'.$currentrow,'2007-08')
						->setCellValue('R'.$currentrow,'2008-09')
						->setCellValue('S'.$currentrow,'2009-10')
						->setCellValue('T'.$currentrow,'2010-11')
						->setCellValue('U'.$currentrow,'2011-12')
						->setCellValue('V'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('M'.$currentrow.':U'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select custype,0 as prd200405, count((prd200506 = '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 = '".$group."' or null) and (prd200506 = '".$group."' or  null)) as prd200607,count((prd200708 = '".$group."' or null) and (prd200607 = '".$group."' or  null)) as prd200708 ,count((prd200809 = '".$group."' or null) and (prd200708 = '".$group."' or  null)) as prd200809 ,count((prd200910 = '".$group."' or null) and (prd200809 = '".$group."' or  null)) as prd200910 , count((prd201011 = '".$group."' or null) and (prd200910 = '".$group."' or null)) as prd201011,count((prd201112 = '".$group."' or null) and (prd201011 = '".$group."' or null)) as prd201112 ,count((prd201213 = '".$group."' or null) and (prd201112 = '".$group."' or null)) as prd201213 from yearwise_customers group by custype;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('M'.$currentrow,$row_data['custype'])
							->setCellValue('N'.$currentrow,$row_data['prd200405'])
							->setCellValue('O'.$currentrow,$row_data['prd200506'])
							->setCellValue('P'.$currentrow,$row_data['prd200607'])
							->setCellValue('Q'.$currentrow,$row_data['prd200708'])
							->setCellValue('R'.$currentrow,$row_data['prd200809'])
							->setCellValue('S'.$currentrow,$row_data['prd200910'])
							->setCellValue('T'.$currentrow,$row_data['prd201011'])
							->setCellValue('U'.$currentrow,$row_data['prd201112'])
							->setCellValue('V'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('M'.$currentrow,'Total')
						->setCellValue('N'.$currentrow,"=SUM(N".$databeginrow.":N".($currentrow-1).")")
						->setCellValue('O'.$currentrow,"=SUM(O".$databeginrow.":O".($currentrow-1).")")
						->setCellValue('P'.$currentrow,"=SUM(P".$databeginrow.":P".($currentrow-1).")")
						->setCellValue('Q'.$currentrow,"=SUM(Q".$databeginrow.":Q".($currentrow-1).")")
						->setCellValue('R'.$currentrow,"=SUM(R".$databeginrow.":R".($currentrow-1).")")
						->setCellValue('S'.$currentrow,"=SUM(S".$databeginrow.":S".($currentrow-1).")")
						->setCellValue('T'.$currentrow,"=SUM(T".$databeginrow.":T".($currentrow-1).")")
						->setCellValue('U'.$currentrow,"=SUM(U".$databeginrow.":U".($currentrow-1).")")
						->setCellValue('V'.$currentrow,"=SUM(V".$databeginrow.":V".($currentrow-1).")");
				$mySheet->getCell('N'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('O'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('P'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('Q'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('R'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('S'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('T'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('U'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('V'.$currentrow)->getCalculatedValue();
				
				//Apply style for content Row
				$mySheet->getStyle('M'.$databeginrow.':V'.$currentrow)->applyFromArray($styleArrayContent);
			}
			if($customercategorywise == 'customercategorywise')
			{
				//Begin writing Dealer wise 
				$currentrow = $currentrow + 3;
				
				//Set heading 
				$mySheet->setCellValue('L'.$currentrow,'Customer Category wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('M'.$currentrow,'Customer Category')
						->setCellValue('N'.$currentrow,'2004-05')
						->setCellValue('O'.$currentrow,'2005-06')
						->setCellValue('P'.$currentrow,'2006-07')
						->setCellValue('Q'.$currentrow,'2007-08')
						->setCellValue('R'.$currentrow,'2008-09')
						->setCellValue('S'.$currentrow,'2009-10')
						->setCellValue('T'.$currentrow,'2010-11')
						->setCellValue('U'.$currentrow,'2011-12')
						->setCellValue('V'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('M'.$currentrow.':V'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select cuscategory,0 as prd200405, count((prd200506 = '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 = '".$group."' or null) and (prd200506 = '".$group."' or  null)) as prd200607,count((prd200708 = '".$group."' or null) and (prd200607 = '".$group."' or  null)) as prd200708 ,count((prd200809 = '".$group."' or null) and (prd200708 = '".$group."' or  null)) as prd200809 ,count((prd200910 = '".$group."' or null) and (prd200809 = '".$group."' or  null)) as prd200910 , count((prd201011 = '".$group."' or null) and (prd200910 = '".$group."' or null)) as prd201011, count((prd201112 = '".$group."' or null) and (prd201011 = '".$group."' or null)) as prd201112, count((prd201213 = '".$group."' or null) and (prd201112 = '".$group."' or null)) as prd201213 from yearwise_customers group by cuscategory;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('M'.$currentrow,$row_data['cuscategory'])
							->setCellValue('N'.$currentrow,$row_data['prd200405'])
							->setCellValue('O'.$currentrow,$row_data['prd200506'])
							->setCellValue('P'.$currentrow,$row_data['prd200607'])
							->setCellValue('Q'.$currentrow,$row_data['prd200708'])
							->setCellValue('R'.$currentrow,$row_data['prd200809'])
							->setCellValue('S'.$currentrow,$row_data['prd200910'])
							->setCellValue('T'.$currentrow,$row_data['prd201011'])
							->setCellValue('U'.$currentrow,$row_data['prd201112'])
							->setCellValue('V'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('M'.$currentrow,'Total')
						->setCellValue('N'.$currentrow,"=SUM(N".$databeginrow.":N".($currentrow-1).")")
						->setCellValue('O'.$currentrow,"=SUM(O".$databeginrow.":O".($currentrow-1).")")
						->setCellValue('P'.$currentrow,"=SUM(P".$databeginrow.":P".($currentrow-1).")")
						->setCellValue('Q'.$currentrow,"=SUM(Q".$databeginrow.":Q".($currentrow-1).")")
						->setCellValue('R'.$currentrow,"=SUM(R".$databeginrow.":R".($currentrow-1).")")
						->setCellValue('S'.$currentrow,"=SUM(S".$databeginrow.":S".($currentrow-1).")")
						->setCellValue('T'.$currentrow,"=SUM(T".$databeginrow.":T".($currentrow-1).")")
						->setCellValue('U'.$currentrow,"=SUM(U".$databeginrow.":U".($currentrow-1).")")
						->setCellValue('V'.$currentrow,"=SUM(V".$databeginrow.":V".($currentrow-1).")");
				$mySheet->getCell('N'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('O'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('P'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('Q'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('R'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('S'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('T'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('U'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('V'.$currentrow)->getCalculatedValue();
				
				//Apply style for content Row
				$mySheet->getStyle('M'.$databeginrow.':V'.$currentrow)->applyFromArray($styleArrayContent);
			}
			
			/*if($districtwise == 'district')
			{
				//Begin writing Dealer wise 
				$currentrow = $currentrow + 3;
				
				//Set heading 
				$mySheet->setCellValue('L'.$currentrow,'District wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('M'.$currentrow,'District')
						->setCellValue('N'.$currentrow,'2004-05')
						->setCellValue('O'.$currentrow,'2005-06')
						->setCellValue('P'.$currentrow,'2006-07')
						->setCellValue('Q'.$currentrow,'2007-08')
						->setCellValue('R'.$currentrow,'2008-09')
						->setCellValue('S'.$currentrow,'2009-10')
						->setCellValue('T'.$currentrow,'2010-11')
						->setCellValue('U'.$currentrow,'2011-12');
						
				//Apply style for header Row
				$mySheet->getStyle('M'.$currentrow.':U'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data
				$currentrow++;
				$query = "select district,0 as prd200405, count((prd200506 = '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 = '".$group."' or null) and (prd200506 = '".$group."' or  null)) as prd200607,count((prd200708 = '".$group."' or null) and (prd200607 = '".$group."' or  null)) as prd200708 ,count((prd200809 = '".$group."' or null) and (prd200708 = '".$group."' or  null)) as prd200809 ,count((prd200910 = '".$group."' or null) and (prd200809 = '".$group."' or  null)) as prd200910 , count((prd201011 = '".$group."' or null) and (prd200910 = '".$group."' or null)) as prd201011, count((prd201112 = '".$group."' or null) and (prd201011 = '".$group."' or null)) as prd201112 from yearwise_customers where state = 'Karnataka' group by district;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('M'.$currentrow,$row_data['district'])
							->setCellValue('N'.$currentrow,$row_data['prd200405'])
							->setCellValue('O'.$currentrow,$row_data['prd200506'])
							->setCellValue('P'.$currentrow,$row_data['prd200607'])
							->setCellValue('Q'.$currentrow,$row_data['prd200708'])
							->setCellValue('R'.$currentrow,$row_data['prd200809'])
							->setCellValue('S'.$currentrow,$row_data['prd200910'])
							->setCellValue('T'.$currentrow,$row_data['prd201011'])
							->setCellValue('U'.$currentrow,$row_data['prd201112']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('M'.$currentrow,'Total')
						->setCellValue('N'.$currentrow,"=SUM(N".$databeginrow.":N".($currentrow-1).")")
						->setCellValue('O'.$currentrow,"=SUM(O".$databeginrow.":O".($currentrow-1).")")
						->setCellValue('P'.$currentrow,"=SUM(P".$databeginrow.":P".($currentrow-1).")")
						->setCellValue('Q'.$currentrow,"=SUM(Q".$databeginrow.":Q".($currentrow-1).")")
						->setCellValue('R'.$currentrow,"=SUM(R".$databeginrow.":R".($currentrow-1).")")
						->setCellValue('S'.$currentrow,"=SUM(S".$databeginrow.":S".($currentrow-1).")")
						->setCellValue('T'.$currentrow,"=SUM(T".$databeginrow.":T".($currentrow-1).")")
						->setCellValue('U'.$currentrow,"=SUM(U".$databeginrow.":U".($currentrow-1).")");
				$mySheet->getCell('N'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('O'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('P'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('Q'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('R'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('S'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('T'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('U'.$currentrow)->getCalculatedValue();
				
				//Apply style for content Row
				$mySheet->getStyle('M'.$databeginrow.':U'.$currentrow)->applyFromArray($styleArrayContent);
			}*/
			
			//Begin with Worksheet 5 (Dropout)
			$pageindex++;
			$objPHPExcel->createSheet();
			$objPHPExcel->setActiveSheetIndex($pageindex);
		
			//Set Active Sheet	
			$mySheet = $objPHPExcel->getActiveSheet();
			
			//Set the worksheet name
			$mySheet->setTitle('Drop-out ('.strtoupper($group).")");
			
			//Merge the cell
			$mySheet->mergeCells('A1:K1');
			$objPHPExcel->setActiveSheetIndex($pageindex)
						->setCellValue('A1', 'Updations');
			$mySheet->getStyle('A1')->getFont()->setSize(12); 	
			$mySheet->getStyle('A1')->getFont()->setBold(true); 
			$mySheet->getStyle('A1')->getAlignment()->setWrapText(true);
			
			//Begin writing Region wise
			$currentrow = 3;
			
			//Set heading 
			$mySheet->setCellValue('B'.($currentrow-1),'All Time Comparision');
				
			$currentrow = 0;
			
			if($regionwise == 'regionwise')
			{
				$currentrow = 3;
				
				$mySheet->setCellValue('A'.$currentrow,'Region wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'Region')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12')
						->setCellValue('K'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data (All time)
				$currentrow++;
				$query = "select region,0 as prd200405, count((prd200506 <> '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 <> '".$group."' or null) and ((prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200607,count((prd200708 <> '".$group."' or null) and ((prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200708 ,count((prd200809 <> '".$group."' or null) and ((prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or  null))) as prd200809 ,count((prd200910 <> '".$group."' or null) and ((prd200809 = '".$group."' or  null) or (prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or  null))) as prd200910 , 	
				count((prd201011 <> '".$group."' or null) and ((prd200910 = '".$group."' or null) or (prd200809 = '".$group."' or null) or (prd200708 = '".$group."' or null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or null))) as prd201011 ,
					count((prd201112 <> '".$group."' or null) and ((prd201011 = '".$group."' or null) or (prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201112,count((prd201213 <> '".$group."' or null) and ((prd201112 = '".$group."' or null) or (prd201011 = '".$group."' or null) or (prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201213   from yearwise_customers group by region;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['region'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112'])
							->setCellValue('K'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
						->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
				
				//Apply style for content Row
				$mySheet->getStyle('B'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
				
				//set the default width for column
				$mySheet->getColumnDimension('A')->setWidth(16);
				$mySheet->getColumnDimension('B')->setWidth(17);
				$mySheet->getColumnDimension('C')->setWidth(12);
				$mySheet->getColumnDimension('D')->setWidth(12);
				$mySheet->getColumnDimension('E')->setWidth(12);
				$mySheet->getColumnDimension('F')->setWidth(12);
				$mySheet->getColumnDimension('G')->setWidth(12);
				$mySheet->getColumnDimension('H')->setWidth(12);
				$mySheet->getColumnDimension('I')->setWidth(12);
				$mySheet->getColumnDimension('J')->setWidth(12);
				$mySheet->getColumnDimension('K')->setWidth(12);
			}
			if($branchwise == 'branchwise')
			{
				//Begin writing Branch wise 
				$currentrow = $currentrow + 3;
				
				//Set heading summary
				$mySheet->setCellValue('A'.$currentrow,'Branch Wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'Branch')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12')
						->setCellValue('K'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data (All time)
				$currentrow++;
				$query = "select branch,0 as prd200405, count((prd200506 <> '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 <> '".$group."' or null) and ((prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200607,count((prd200708 <> '".$group."' or null) and ((prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200708 ,count((prd200809 <> '".$group."' or null) and ((prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or  null))) as prd200809 ,count((prd200910 <> '".$group."' or null) and ((prd200809 = '".$group."' or  null) or (prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or  null))) as prd200910 , count((prd201011 <> '".$group."' or null) and ((prd200910 = '".$group."' or null) or (prd200809 = '".$group."' or null) or (prd200708 = '".$group."' or null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or null))) as prd201011 ,count((prd201112 <> '".$group."' or null) and ((prd201011 = '".$group."' or null) or (prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201112,count((prd201213 <> '".$group."' or null) and ((prd201112 = '".$group."' or null) or (prd201011 = '".$group."' or null) or (prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201213  from yearwise_customers group by branch;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['branch'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112'])
							->setCellValue('K'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
						->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
				
				//Apply style for Content row
				$mySheet->getStyle('B'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
			}
			if($statewise == "statewise")
			{
				//Begin writing State wise 
				$currentrow = $currentrow + 3;
				
				//Set heading summary
				$mySheet->setCellValue('A'.$currentrow,'State Wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'state')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12')
						->setCellValue('K'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data (All time)
				$currentrow++;
				$query = "select state,0 as prd200405, count((prd200506 <> '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 <> '".$group."' or null) and ((prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200607,count((prd200708 <> '".$group."' or null) and ((prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200708 ,count((prd200809 <> '".$group."' or null) and ((prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or  null))) as prd200809 ,count((prd200910 <> '".$group."' or null) and ((prd200809 = '".$group."' or  null) or (prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or  null))) as prd200910 , count((prd201011 <> '".$group."' or null) and ((prd200910 = '".$group."' or null) or (prd200809 = '".$group."' or null) or (prd200708 = '".$group."' or null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or null))) as prd201011 ,count((prd201112 <> '".$group."' or null) and ((prd201011 = '".$group."' or null) or (prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201112,count((prd201213 <> '".$group."' or null) and ((prd201112 = '".$group."' or null) or (prd201011 = '".$group."' or null) or (prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201213  from yearwise_customers group by state;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['state'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112'])
							->setCellValue('K'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
						->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
				
				//Apply style for Content row
				$mySheet->getStyle('B'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
			}
			if($dealerwise == 'dealerwise')
			{
				//Begin writing Dealer wise 
				$currentrow = $currentrow + 3;
				
				//Set heading summary
				$mySheet->setCellValue('A'.$currentrow,'Dealer Wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'Dealer')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12')
						->setCellValue('K'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data (All time)
				$currentrow++;
				$query = "select dealer,0 as prd200405, count((prd200506 <> '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 <> '".$group."' or null) and ((prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200607,count((prd200708 <> '".$group."' or null) and ((prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200708 ,count((prd200809 <> '".$group."' or null) and ((prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or  null))) as prd200809 ,count((prd200910 <> '".$group."' or null) and ((prd200809 = '".$group."' or  null) or (prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or  null))) as prd200910 , count((prd201011 <> '".$group."' or null) and ((prd200910 = '".$group."' or null) or (prd200809 = '".$group."' or null) or (prd200708 = '".$group."' or null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or null))) as prd201011 ,count((prd201112 <> '".$group."' or null) and ((prd201011 = '".$group."' or null) or (prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201112 ,count((prd201213 <> '".$group."' or null) and ((prd201112 = '".$group."' or null) or (prd201011 = '".$group."' or null) or (prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201213 from yearwise_customers group by dealer;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['dealer'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112'])
							->setCellValue('K'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
						->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
				
				//Apply style for Content row
				$mySheet->getStyle('B'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
			}
			if($customertypewise == 'customertypewise')
			{
				//Begin writing Dealer wise 
				$currentrow = $currentrow + 3;
				
				//Set heading summary
				$mySheet->setCellValue('A'.$currentrow,'Customer Type Wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'Customer Type')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12')
						->setCellValue('K'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data (All time)
				$currentrow++;
				$query = "select custype,0 as prd200405, count((prd200506 <> '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 <> '".$group."' or null) and ((prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200607,count((prd200708 <> '".$group."' or null) and ((prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200708 ,count((prd200809 <> '".$group."' or null) and ((prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or  null))) as prd200809 ,count((prd200910 <> '".$group."' or null) and ((prd200809 = '".$group."' or  null) or (prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or  null))) as prd200910 , count((prd201011 <> '".$group."' or null) and ((prd200910 = '".$group."' or null) or (prd200809 = '".$group."' or null) or (prd200708 = '".$group."' or null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or null))) as prd201011 ,count((prd201112 <> '".$group."' or null) and ((prd201011 = '".$group."' or null) or (prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201112 ,count((prd201213 <> '".$group."' or null) and ((prd201112 = '".$group."' or null) or(prd201011 = '".$group."' or null) or (prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201213 from yearwise_customers group by custype;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['custype'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112'])
							->setCellValue('K'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
						->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
				
				//Apply style for Content row
				$mySheet->getStyle('B'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
			}
			if($customercategorywise == 'customercategorywise')
			{
				//Begin writing Dealer wise 
				$currentrow = $currentrow + 3;
				
				//Set heading summary
				$mySheet->setCellValue('A'.$currentrow,'Customer Category Wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'Customer Category')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12')
						->setCellValue('K'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':K'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data (All time)
				$currentrow++;
				$query = "select cuscategory,0 as prd200405, count((prd200506 <> '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 <> '".$group."' or null) and ((prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200607,count((prd200708 <> '".$group."' or null) and ((prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200708 ,count((prd200809 <> '".$group."' or null) and ((prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or  null))) as prd200809 ,count((prd200910 <> '".$group."' or null) and ((prd200809 = '".$group."' or  null) or (prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or  null))) as prd200910 , count((prd201011 <> '".$group."' or null) and ((prd200910 = '".$group."' or null) or (prd200809 = '".$group."' or null) or (prd200708 = '".$group."' or null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or null))) as prd201011 ,count((prd201112 <> '".$group."' or null) and ((prd201011 = '".$group."' or null) or (prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201112 ,count((prd201213 <> '".$group."' or null) and ((prd201112 = '".$group."' or null) or (prd201011 = '".$group."' or null) or (prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201213 from yearwise_customers group by cuscategory;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['cuscategory'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112'])
							->setCellValue('K'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")")
						->setCellValue('K'.$currentrow,"=SUM(K".$databeginrow.":K".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('K'.$currentrow)->getCalculatedValue();
				
				//Apply style for Content row
				$mySheet->getStyle('B'.$databeginrow.':K'.$currentrow)->applyFromArray($styleArrayContent);
			}
			
			/*if($districtwise == 'district')
			{
				//Begin writing Dealer wise 
				$currentrow = $currentrow + 3;
				
				//Set heading summary
				$mySheet->setCellValue('A'.$currentrow,'District Wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('B'.$currentrow,'District')
						->setCellValue('C'.$currentrow,'2004-05')
						->setCellValue('D'.$currentrow,'2005-06')
						->setCellValue('E'.$currentrow,'2006-07')
						->setCellValue('F'.$currentrow,'2007-08')
						->setCellValue('G'.$currentrow,'2008-09')
						->setCellValue('H'.$currentrow,'2009-10')
						->setCellValue('I'.$currentrow,'2010-11')
						->setCellValue('J'.$currentrow,'2011-12');
						
				//Apply style for header Row
				$mySheet->getStyle('B'.$currentrow.':J'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data (All time)
				$currentrow++;
				$query = "select district,0 as prd200405, count((prd200506 <> '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 <> '".$group."' or null) and ((prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200607,count((prd200708 <> '".$group."' or null) and ((prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or null))) as prd200708 ,count((prd200809 <> '".$group."' or null) and ((prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or  null) or (prd200506 = '".$group."' or  null) or (prd200405 = '".$group."' or  null))) as prd200809 ,count((prd200910 <> '".$group."' or null) and ((prd200809 = '".$group."' or  null) or (prd200708 = '".$group."' or  null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or  null))) as prd200910 , count((prd201011 <> '".$group."' or null) and ((prd200910 = '".$group."' or null) or (prd200809 = '".$group."' or null) or (prd200708 = '".$group."' or null) or (prd200607 = '".$group."' or null) or (prd200506 = '".$group."' or null) or (prd200405 = '".$group."' or null))) as prd201011 ,count((prd201112 <> '".$group."' or null) and ((prd201011 = '".$group."' or null) or (prd200910 = '".$group."' or not null) or (prd200809 = '".$group."' or not null) or (prd200708 = '".$group."' or not null) or (prd200607 = '".$group."' or not null) or (prd200506 = '".$group."' or not null) or (prd200405 = '".$group."' or not null))) as prd201112  from yearwise_customers where state = 'Karnataka' group by district;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('B'.$currentrow,$row_data['district'])
							->setCellValue('C'.$currentrow,$row_data['prd200405'])
							->setCellValue('D'.$currentrow,$row_data['prd200506'])
							->setCellValue('E'.$currentrow,$row_data['prd200607'])
							->setCellValue('F'.$currentrow,$row_data['prd200708'])
							->setCellValue('G'.$currentrow,$row_data['prd200809'])
							->setCellValue('H'.$currentrow,$row_data['prd200910'])
							->setCellValue('I'.$currentrow,$row_data['prd201011'])
							->setCellValue('J'.$currentrow,$row_data['prd201112']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('B'.$currentrow,'Total')
						->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
						->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
						->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
						->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
						->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
						->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")")
						->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")")
						->setCellValue('J'.$currentrow,"=SUM(J".$databeginrow.":J".($currentrow-1).")");
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('J'.$currentrow)->getCalculatedValue();
				
				//Apply style for Content row
				$mySheet->getStyle('B'.$databeginrow.':J'.$currentrow)->applyFromArray($styleArrayContent);
			}*/
			
			
			//previous year comparision
			//Begin writing Region wise
			$currentrow = 3;
			
			//Set heading 
			$mySheet->setCellValue('M'.($currentrow-1),'Previous year comparision');
			
			$currentrow = 0;
			
			if($regionwise == "regionwise")
			{
				$currentrow = 3;
				
				$mySheet->setCellValue('L'.$currentrow,'Region wise');

				$currentrow++;
				
				//Set table headings
				$mySheet->setCellValue('M'.$currentrow,'Region')
						->setCellValue('N'.$currentrow,'2004-05')
						->setCellValue('O'.$currentrow,'2005-06')
						->setCellValue('P'.$currentrow,'2006-07')
						->setCellValue('Q'.$currentrow,'2007-08')
						->setCellValue('R'.$currentrow,'2008-09')
						->setCellValue('S'.$currentrow,'2009-10')
						->setCellValue('T'.$currentrow,'2010-11')
						->setCellValue('U'.$currentrow,'2011-12')
						->setCellValue('V'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('M'.$currentrow.':V'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data (Previous year)
				$currentrow++;
				$query = "select region,0 as prd200405, count((prd200506 <> '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 <> '".$group."' or null) and (prd200506 = '".$group."' or  null)) as prd200607,count((prd200708 <> '".$group."' or null) and (prd200607 = '".$group."' or  null)) as prd200708 ,count((prd200809 <> '".$group."' or null) and (prd200708 = '".$group."' or  null)) as prd200809 ,count((prd200910 <> '".$group."' or null) and (prd200809 = '".$group."' or  null)) as prd200910 , count((prd201011 <> '".$group."' or null) and (prd200910 = '".$group."' or null)) as prd201011, count((prd201112 <> '".$group."' or null) and (prd201011 = '".$group."' or null)) as prd201112, count((prd201213 <> '".$group."' or null) and (prd201112 = '".$group."' or null)) as prd201213 from yearwise_customers group by region;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('M'.$currentrow,$row_data['region'])
							->setCellValue('N'.$currentrow,$row_data['prd200405'])
							->setCellValue('O'.$currentrow,$row_data['prd200506'])
							->setCellValue('P'.$currentrow,$row_data['prd200607'])
							->setCellValue('Q'.$currentrow,$row_data['prd200708'])
							->setCellValue('R'.$currentrow,$row_data['prd200809'])
							->setCellValue('S'.$currentrow,$row_data['prd200910'])
							->setCellValue('T'.$currentrow,$row_data['prd201011'])
							->setCellValue('U'.$currentrow,$row_data['prd201112'])
							->setCellValue('V'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('M'.$currentrow,'Total')
						->setCellValue('N'.$currentrow,"=SUM(N".$databeginrow.":N".($currentrow-1).")")
						->setCellValue('O'.$currentrow,"=SUM(O".$databeginrow.":O".($currentrow-1).")")
						->setCellValue('P'.$currentrow,"=SUM(P".$databeginrow.":P".($currentrow-1).")")
						->setCellValue('Q'.$currentrow,"=SUM(Q".$databeginrow.":Q".($currentrow-1).")")
						->setCellValue('R'.$currentrow,"=SUM(R".$databeginrow.":R".($currentrow-1).")")
						->setCellValue('S'.$currentrow,"=SUM(S".$databeginrow.":S".($currentrow-1).")")
						->setCellValue('T'.$currentrow,"=SUM(T".$databeginrow.":T".($currentrow-1).")")
						->setCellValue('U'.$currentrow,"=SUM(U".$databeginrow.":U".($currentrow-1).")")
						->setCellValue('V'.$currentrow,"=SUM(V".$databeginrow.":V".($currentrow-1).")");
				$mySheet->getCell('N'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('O'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('P'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('Q'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('R'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('S'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('T'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('U'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('V'.$currentrow)->getCalculatedValue();
				
				//Apply style for content Row
				$mySheet->getStyle('M'.$databeginrow.':V'.$currentrow)->applyFromArray($styleArrayContent);
			}
			if($branchwise == "branchwise")
			{
				//Begin writing Branch wise 
				$currentrow = $currentrow + 3;
				
				//Set heading 
				$mySheet->setCellValue('L'.$currentrow,'Branch wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('M'.$currentrow,'Branch')
						->setCellValue('N'.$currentrow,'2004-05')
						->setCellValue('O'.$currentrow,'2005-06')
						->setCellValue('P'.$currentrow,'2006-07')
						->setCellValue('Q'.$currentrow,'2007-08')
						->setCellValue('R'.$currentrow,'2008-09')
						->setCellValue('S'.$currentrow,'2009-10')
						->setCellValue('T'.$currentrow,'2010-11')
						->setCellValue('U'.$currentrow,'2011-12')
						->setCellValue('V'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('M'.$currentrow.':V'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data (Previous year)
				$currentrow++;
				$query = "select branch,0 as prd200405, count((prd200506 <> '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 <> '".$group."' or null) and (prd200506 = '".$group."' or  null)) as prd200607,count((prd200708 <> '".$group."' or null) and (prd200607 = '".$group."' or  null)) as prd200708 ,count((prd200809 <> '".$group."' or null) and (prd200708 = '".$group."' or  null)) as prd200809 ,count((prd200910 <> '".$group."' or null) and (prd200809 = '".$group."' or  null)) as prd200910 , count((prd201011 <> '".$group."' or null) and (prd200910 = '".$group."' or null)) as prd201011,count((prd201112 <> '".$group."' or null) and (prd201011 = '".$group."' or null)) as prd201112,count((prd201213 <> '".$group."' or null) and (prd201112 = '".$group."' or null)) as prd201213 from yearwise_customers group by branch;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('M'.$currentrow,$row_data['branch'])
							->setCellValue('N'.$currentrow,$row_data['prd200405'])
							->setCellValue('O'.$currentrow,$row_data['prd200506'])
							->setCellValue('P'.$currentrow,$row_data['prd200607'])
							->setCellValue('Q'.$currentrow,$row_data['prd200708'])
							->setCellValue('R'.$currentrow,$row_data['prd200809'])
							->setCellValue('S'.$currentrow,$row_data['prd200910'])
							->setCellValue('T'.$currentrow,$row_data['prd201011'])
							->setCellValue('U'.$currentrow,$row_data['prd201112'])
							->setCellValue('V'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('M'.$currentrow,'Total')
						->setCellValue('N'.$currentrow,"=SUM(N".$databeginrow.":N".($currentrow-1).")")
						->setCellValue('O'.$currentrow,"=SUM(O".$databeginrow.":O".($currentrow-1).")")
						->setCellValue('P'.$currentrow,"=SUM(P".$databeginrow.":P".($currentrow-1).")")
						->setCellValue('Q'.$currentrow,"=SUM(Q".$databeginrow.":Q".($currentrow-1).")")
						->setCellValue('R'.$currentrow,"=SUM(R".$databeginrow.":R".($currentrow-1).")")
						->setCellValue('S'.$currentrow,"=SUM(S".$databeginrow.":S".($currentrow-1).")")
						->setCellValue('T'.$currentrow,"=SUM(T".$databeginrow.":T".($currentrow-1).")")
						->setCellValue('U'.$currentrow,"=SUM(U".$databeginrow.":U".($currentrow-1).")")
						->setCellValue('V'.$currentrow,"=SUM(V".$databeginrow.":V".($currentrow-1).")");
				$mySheet->getCell('N'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('O'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('P'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('Q'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('R'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('S'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('T'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('U'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('V'.$currentrow)->getCalculatedValue();
				
				//Apply style for content Row
				$mySheet->getStyle('M'.$databeginrow.':V'.$currentrow)->applyFromArray($styleArrayContent);
				
				//set the default width for column
				$mySheet->getColumnDimension('L')->setWidth(16);
				$mySheet->getColumnDimension('M')->setWidth(17);
				$mySheet->getColumnDimension('N')->setWidth(12);
				$mySheet->getColumnDimension('O')->setWidth(12);
				$mySheet->getColumnDimension('P')->setWidth(12);
				$mySheet->getColumnDimension('Q')->setWidth(12);
				$mySheet->getColumnDimension('R')->setWidth(12);
				$mySheet->getColumnDimension('S')->setWidth(12);
				$mySheet->getColumnDimension('T')->setWidth(12);
				$mySheet->getColumnDimension('U')->setWidth(12);
				$mySheet->getColumnDimension('V')->setWidth(12);
			}
			if($statewise == "statewise")
			{
				//Begin writing State wise 
				$currentrow = $currentrow + 3;
				
				//Set heading 
				$mySheet->setCellValue('L'.$currentrow,'State wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('M'.$currentrow,'State')
						->setCellValue('N'.$currentrow,'2004-05')
						->setCellValue('O'.$currentrow,'2005-06')
						->setCellValue('P'.$currentrow,'2006-07')
						->setCellValue('Q'.$currentrow,'2007-08')
						->setCellValue('R'.$currentrow,'2008-09')
						->setCellValue('S'.$currentrow,'2009-10')
						->setCellValue('T'.$currentrow,'2010-11')
						->setCellValue('U'.$currentrow,'2011-12')
						->setCellValue('V'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('M'.$currentrow.':V'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data (Previous year)
				$currentrow++;
				$query = "select state,0 as prd200405, count((prd200506 <> '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 <> '".$group."' or null) and (prd200506 = '".$group."' or  null)) as prd200607,count((prd200708 <> '".$group."' or null) and (prd200607 = '".$group."' or  null)) as prd200708 ,count((prd200809 <> '".$group."' or null) and (prd200708 = '".$group."' or  null)) as prd200809 ,count((prd200910 <> '".$group."' or null) and (prd200809 = '".$group."' or  null)) as prd200910 , count((prd201011 <> '".$group."' or null) and (prd200910 = '".$group."' or null)) as prd201011,count((prd201112 <> '".$group."' or null) and (prd201011 = '".$group."' or null)) as prd201112 ,count((prd201213 <> '".$group."' or null) and (prd201112 = '".$group."' or null)) as prd201213 from yearwise_customers group by state;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('M'.$currentrow,$row_data['state'])
							->setCellValue('N'.$currentrow,$row_data['prd200405'])
							->setCellValue('O'.$currentrow,$row_data['prd200506'])
							->setCellValue('P'.$currentrow,$row_data['prd200607'])
							->setCellValue('Q'.$currentrow,$row_data['prd200708'])
							->setCellValue('R'.$currentrow,$row_data['prd200809'])
							->setCellValue('S'.$currentrow,$row_data['prd200910'])
							->setCellValue('T'.$currentrow,$row_data['prd201011'])
							->setCellValue('U'.$currentrow,$row_data['prd201112'])
							->setCellValue('V'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('M'.$currentrow,'Total')
						->setCellValue('N'.$currentrow,"=SUM(N".$databeginrow.":N".($currentrow-1).")")
						->setCellValue('O'.$currentrow,"=SUM(O".$databeginrow.":O".($currentrow-1).")")
						->setCellValue('P'.$currentrow,"=SUM(P".$databeginrow.":P".($currentrow-1).")")
						->setCellValue('Q'.$currentrow,"=SUM(Q".$databeginrow.":Q".($currentrow-1).")")
						->setCellValue('R'.$currentrow,"=SUM(R".$databeginrow.":R".($currentrow-1).")")
						->setCellValue('S'.$currentrow,"=SUM(S".$databeginrow.":S".($currentrow-1).")")
						->setCellValue('T'.$currentrow,"=SUM(T".$databeginrow.":T".($currentrow-1).")")
						->setCellValue('U'.$currentrow,"=SUM(U".$databeginrow.":U".($currentrow-1).")")
						->setCellValue('V'.$currentrow,"=SUM(V".$databeginrow.":V".($currentrow-1).")");
				$mySheet->getCell('N'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('O'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('P'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('Q'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('R'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('S'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('T'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('U'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('V'.$currentrow)->getCalculatedValue();
				
				//Apply style for content Row
				$mySheet->getStyle('M'.$databeginrow.':V'.$currentrow)->applyFromArray($styleArrayContent);
			}
			if($dealerwise == "dealerwise")
			{
				//Begin writing Dealer wise 
				$currentrow = $currentrow + 3;
				
				//Set heading 
				$mySheet->setCellValue('L'.$currentrow,'Dealer wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('M'.$currentrow,'Dealer')
						->setCellValue('N'.$currentrow,'2004-05')
						->setCellValue('O'.$currentrow,'2005-06')
						->setCellValue('P'.$currentrow,'2006-07')
						->setCellValue('Q'.$currentrow,'2007-08')
						->setCellValue('R'.$currentrow,'2008-09')
						->setCellValue('S'.$currentrow,'2009-10')
						->setCellValue('T'.$currentrow,'2010-11')
						->setCellValue('U'.$currentrow,'2011-12')
						->setCellValue('V'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('M'.$currentrow.':V'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data (previous year)
				$currentrow++;
				$query = "select dealer,0 as prd200405, count((prd200506 <> '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 <> '".$group."' or null) and (prd200506 = '".$group."' or  null)) as prd200607,count((prd200708 <> '".$group."' or null) and (prd200607 = '".$group."' or  null)) as prd200708 ,count((prd200809 <> '".$group."' or null) and (prd200708 = '".$group."' or  null)) as prd200809 ,count((prd200910 <> '".$group."' or null) and (prd200809 = '".$group."' or  null)) as prd200910 , count((prd201011 <> '".$group."' or null) and (prd200910 = '".$group."' or null)) as prd201011,count((prd201112 <> '".$group."' or null) and (prd201011 = '".$group."' or null)) as prd201112 ,count((prd201213 <> '".$group."' or null) and (prd201112 = '".$group."' or null)) as prd201213 from yearwise_customers group by dealer;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('M'.$currentrow,$row_data['dealer'])
							->setCellValue('N'.$currentrow,$row_data['prd200405'])
							->setCellValue('O'.$currentrow,$row_data['prd200506'])
							->setCellValue('P'.$currentrow,$row_data['prd200607'])
							->setCellValue('Q'.$currentrow,$row_data['prd200708'])
							->setCellValue('R'.$currentrow,$row_data['prd200809'])
							->setCellValue('S'.$currentrow,$row_data['prd200910'])
							->setCellValue('T'.$currentrow,$row_data['prd201011'])
							->setCellValue('U'.$currentrow,$row_data['prd201112'])
							->setCellValue('V'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('M'.$currentrow,'Total')
						->setCellValue('N'.$currentrow,"=SUM(N".$databeginrow.":N".($currentrow-1).")")
						->setCellValue('O'.$currentrow,"=SUM(O".$databeginrow.":O".($currentrow-1).")")
						->setCellValue('P'.$currentrow,"=SUM(P".$databeginrow.":P".($currentrow-1).")")
						->setCellValue('Q'.$currentrow,"=SUM(Q".$databeginrow.":Q".($currentrow-1).")")
						->setCellValue('R'.$currentrow,"=SUM(R".$databeginrow.":R".($currentrow-1).")")
						->setCellValue('S'.$currentrow,"=SUM(S".$databeginrow.":S".($currentrow-1).")")
						->setCellValue('T'.$currentrow,"=SUM(T".$databeginrow.":T".($currentrow-1).")")
						->setCellValue('U'.$currentrow,"=SUM(U".$databeginrow.":U".($currentrow-1).")")
						->setCellValue('V'.$currentrow,"=SUM(V".$databeginrow.":V".($currentrow-1).")");
				$mySheet->getCell('N'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('O'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('P'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('Q'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('R'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('S'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('T'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('U'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('V'.$currentrow)->getCalculatedValue();
				
				//Apply style for content Row
				$mySheet->getStyle('M'.$databeginrow.':V'.$currentrow)->applyFromArray($styleArrayContent);
			}
			if($customertypewise == "customertypewise")
			{
				//Begin writing Dealer wise 
				$currentrow = $currentrow + 3;
				
				//Set heading 
				$mySheet->setCellValue('L'.$currentrow,'Customer Type wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('M'.$currentrow,'Customer Type')
						->setCellValue('N'.$currentrow,'2004-05')
						->setCellValue('O'.$currentrow,'2005-06')
						->setCellValue('P'.$currentrow,'2006-07')
						->setCellValue('Q'.$currentrow,'2007-08')
						->setCellValue('R'.$currentrow,'2008-09')
						->setCellValue('S'.$currentrow,'2009-10')
						->setCellValue('T'.$currentrow,'2010-11')
						->setCellValue('U'.$currentrow,'2011-12')
						->setCellValue('V'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('M'.$currentrow.':V'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data (previous year)
				$currentrow++;
				$query = "select custype,0 as prd200405, count((prd200506 <> '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 <> '".$group."' or null) and (prd200506 = '".$group."' or  null)) as prd200607,count((prd200708 <> '".$group."' or null) and (prd200607 = '".$group."' or  null)) as prd200708 ,count((prd200809 <> '".$group."' or null) and (prd200708 = '".$group."' or  null)) as prd200809 ,count((prd200910 <> '".$group."' or null) and (prd200809 = '".$group."' or  null)) as prd200910 , count((prd201011 <> '".$group."' or null) and (prd200910 = '".$group."' or null)) as prd201011,count((prd201112 <> '".$group."' or null) and (prd201011 = '".$group."' or null)) as prd201112,count((prd201213 <> '".$group."' or null) and (prd201112 = '".$group."' or null)) as prd201213 from yearwise_customers group by custype;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('M'.$currentrow,$row_data['custype'])
							->setCellValue('N'.$currentrow,$row_data['prd200405'])
							->setCellValue('O'.$currentrow,$row_data['prd200506'])
							->setCellValue('P'.$currentrow,$row_data['prd200607'])
							->setCellValue('Q'.$currentrow,$row_data['prd200708'])
							->setCellValue('R'.$currentrow,$row_data['prd200809'])
							->setCellValue('S'.$currentrow,$row_data['prd200910'])
							->setCellValue('T'.$currentrow,$row_data['prd201011'])
							->setCellValue('U'.$currentrow,$row_data['prd201112'])
							->setCellValue('V'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('M'.$currentrow,'Total')
						->setCellValue('N'.$currentrow,"=SUM(N".$databeginrow.":N".($currentrow-1).")")
						->setCellValue('O'.$currentrow,"=SUM(O".$databeginrow.":O".($currentrow-1).")")
						->setCellValue('P'.$currentrow,"=SUM(P".$databeginrow.":P".($currentrow-1).")")
						->setCellValue('Q'.$currentrow,"=SUM(Q".$databeginrow.":Q".($currentrow-1).")")
						->setCellValue('R'.$currentrow,"=SUM(R".$databeginrow.":R".($currentrow-1).")")
						->setCellValue('S'.$currentrow,"=SUM(S".$databeginrow.":S".($currentrow-1).")")
						->setCellValue('T'.$currentrow,"=SUM(T".$databeginrow.":T".($currentrow-1).")")
						->setCellValue('U'.$currentrow,"=SUM(U".$databeginrow.":U".($currentrow-1).")")
						->setCellValue('V'.$currentrow,"=SUM(V".$databeginrow.":V".($currentrow-1).")");
				$mySheet->getCell('N'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('O'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('P'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('Q'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('R'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('S'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('T'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('U'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('V'.$currentrow)->getCalculatedValue();
				
				//Apply style for content Row
				$mySheet->getStyle('M'.$databeginrow.':V'.$currentrow)->applyFromArray($styleArrayContent);
			}
			if($customercategorywise == "customercategorywise")
			{
				//Begin writing Dealer wise 
				$currentrow = $currentrow + 3;
				
				//Set heading 
				$mySheet->setCellValue('L'.$currentrow,'Customer Category wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('M'.$currentrow,'Customer Category')
						->setCellValue('N'.$currentrow,'2004-05')
						->setCellValue('O'.$currentrow,'2005-06')
						->setCellValue('P'.$currentrow,'2006-07')
						->setCellValue('Q'.$currentrow,'2007-08')
						->setCellValue('R'.$currentrow,'2008-09')
						->setCellValue('S'.$currentrow,'2009-10')
						->setCellValue('T'.$currentrow,'2010-11')
						->setCellValue('U'.$currentrow,'2011-12')
						->setCellValue('V'.$currentrow,'2012-13');
						
				//Apply style for header Row
				$mySheet->getStyle('M'.$currentrow.':V'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data (previous year)
				$currentrow++;
				$query = "select cuscategory,0 as prd200405, count((prd200506 <> '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 <> '".$group."' or null) and (prd200506 = '".$group."' or  null)) as prd200607,count((prd200708 <> '".$group."' or null) and (prd200607 = '".$group."' or  null)) as prd200708 ,count((prd200809 <> '".$group."' or null) and (prd200708 = '".$group."' or  null)) as prd200809 ,count((prd200910 <> '".$group."' or null) and (prd200809 = '".$group."' or  null)) as prd200910 , count((prd201011 <> '".$group."' or null) and (prd200910 = '".$group."' or null)) as prd201011,count((prd201112 <> '".$group."' or null) and (prd201011 = '".$group."' or null)) as prd201112 ,count((prd201213 <> '".$group."' or null) and (prd201112 = '".$group."' or null)) as prd201213 from yearwise_customers group by cuscategory;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('M'.$currentrow,$row_data['cuscategory'])
							->setCellValue('N'.$currentrow,$row_data['prd200405'])
							->setCellValue('O'.$currentrow,$row_data['prd200506'])
							->setCellValue('P'.$currentrow,$row_data['prd200607'])
							->setCellValue('Q'.$currentrow,$row_data['prd200708'])
							->setCellValue('R'.$currentrow,$row_data['prd200809'])
							->setCellValue('S'.$currentrow,$row_data['prd200910'])
							->setCellValue('T'.$currentrow,$row_data['prd201011'])
							->setCellValue('U'.$currentrow,$row_data['prd201112'])
							->setCellValue('V'.$currentrow,$row_data['prd201213']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('M'.$currentrow,'Total')
						->setCellValue('N'.$currentrow,"=SUM(N".$databeginrow.":N".($currentrow-1).")")
						->setCellValue('O'.$currentrow,"=SUM(O".$databeginrow.":O".($currentrow-1).")")
						->setCellValue('P'.$currentrow,"=SUM(P".$databeginrow.":P".($currentrow-1).")")
						->setCellValue('Q'.$currentrow,"=SUM(Q".$databeginrow.":Q".($currentrow-1).")")
						->setCellValue('R'.$currentrow,"=SUM(R".$databeginrow.":R".($currentrow-1).")")
						->setCellValue('S'.$currentrow,"=SUM(S".$databeginrow.":S".($currentrow-1).")")
						->setCellValue('T'.$currentrow,"=SUM(T".$databeginrow.":T".($currentrow-1).")")
						->setCellValue('U'.$currentrow,"=SUM(U".$databeginrow.":U".($currentrow-1).")")
						->setCellValue('V'.$currentrow,"=SUM(V".$databeginrow.":V".($currentrow-1).")");
				$mySheet->getCell('N'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('O'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('P'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('Q'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('R'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('S'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('T'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('U'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('V'.$currentrow)->getCalculatedValue();
				
				//Apply style for content Row
				$mySheet->getStyle('M'.$databeginrow.':V'.$currentrow)->applyFromArray($styleArrayContent);
			}
			
			/*if($districtwise == "district")
			{
				//Begin writing Dealer wise 
				$currentrow = $currentrow + 3;
				
				//Set heading 
				$mySheet->setCellValue('L'.$currentrow,'District wise');
				
				$currentrow++;
				//Set table headings
				$mySheet->setCellValue('M'.$currentrow,'District')
						->setCellValue('N'.$currentrow,'2004-05')
						->setCellValue('O'.$currentrow,'2005-06')
						->setCellValue('P'.$currentrow,'2006-07')
						->setCellValue('Q'.$currentrow,'2007-08')
						->setCellValue('R'.$currentrow,'2008-09')
						->setCellValue('S'.$currentrow,'2009-10')
						->setCellValue('T'.$currentrow,'2010-11')
						->setCellValue('U'.$currentrow,'2011-12');
						
				//Apply style for header Row
				$mySheet->getStyle('M'.$currentrow.':U'.$currentrow)->applyFromArray($styleArray);
				
				//Set table data (previous year)
				$currentrow++;
				$query = "select district,0 as prd200405, count((prd200506 <> '".$group."' or null) and (prd200405 = '".$group."' or null)) as prd200506, count((prd200607 <> '".$group."' or null) and (prd200506 = '".$group."' or  null)) as prd200607,count((prd200708 <> '".$group."' or null) and (prd200607 = '".$group."' or  null)) as prd200708 ,count((prd200809 <> '".$group."' or null) and (prd200708 = '".$group."' or  null)) as prd200809 ,count((prd200910 <> '".$group."' or null) and (prd200809 = '".$group."' or  null)) as prd200910 , count((prd201011 <> '".$group."' or null) and (prd200910 = '".$group."' or null)) as prd201011,count((prd201112 <> '".$group."' or null) and (prd201011 = '".$group."' or null)) as prd201112 from yearwise_customers  where state = 'karnataka' group by district;";
				$result = runmysqlquery($query);
				
				//Insert data
				$databeginrow = $currentrow;
				while($row_data = mysqli_fetch_array($result))
				{
					$mySheet->setCellValue('M'.$currentrow,$row_data['district'])
							->setCellValue('N'.$currentrow,$row_data['prd200405'])
							->setCellValue('O'.$currentrow,$row_data['prd200506'])
							->setCellValue('P'.$currentrow,$row_data['prd200607'])
							->setCellValue('Q'.$currentrow,$row_data['prd200708'])
							->setCellValue('R'.$currentrow,$row_data['prd200809'])
							->setCellValue('S'.$currentrow,$row_data['prd200910'])
							->setCellValue('T'.$currentrow,$row_data['prd201011'])
							->setCellValue('U'.$currentrow,$row_data['prd201112']);
					$currentrow++;
				}
				
				//Insert Total
				$mySheet->setCellValue('M'.$currentrow,'Total')
						->setCellValue('N'.$currentrow,"=SUM(N".$databeginrow.":N".($currentrow-1).")")
						->setCellValue('O'.$currentrow,"=SUM(O".$databeginrow.":O".($currentrow-1).")")
						->setCellValue('P'.$currentrow,"=SUM(P".$databeginrow.":P".($currentrow-1).")")
						->setCellValue('Q'.$currentrow,"=SUM(Q".$databeginrow.":Q".($currentrow-1).")")
						->setCellValue('R'.$currentrow,"=SUM(R".$databeginrow.":R".($currentrow-1).")")
						->setCellValue('S'.$currentrow,"=SUM(S".$databeginrow.":S".($currentrow-1).")")
						->setCellValue('T'.$currentrow,"=SUM(T".$databeginrow.":T".($currentrow-1).")")
						->setCellValue('U'.$currentrow,"=SUM(U".$databeginrow.":U".($currentrow-1).")");
				$mySheet->getCell('N'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('O'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('P'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('Q'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('R'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('S'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('T'.$currentrow)->getCalculatedValue();
				$mySheet->getCell('U'.$currentrow)->getCalculatedValue();
				
				//Apply style for content Row
				$mySheet->getStyle('M'.$databeginrow.':U'.$currentrow)->applyFromArray($styleArrayContent);
			}*/
			$addstring = "/user";
			if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk"))
				$addstring = "/saralimax-user";
			
				$query = 'select slno,username from inv_mas_users where slno = '.$userid.'';
				$fetchres = runmysqlqueryfetch($query);	
				$localdate = datetimelocal('Ymd');
				$localtime = datetimelocal('His');
				$heading = strtoupper($group);
				$filebasename = "CustomerStats(".$heading.")".$localdate."-".$localtime."-".strtolower($fetchres['username']).".xls";
				
				$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filebasename;
				$downloadlink = 'http://'.$_SERVER['HTTP_HOST'].$addstring.'/filecreated/'.$filebasename;
		
				$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_updationdetailed_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
				$result = runmysqlquery($query1);
				
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','102','".date('Y-m-d').' '.date('H:i:s')."','excel_updationdetailed_report".'-'.strtolower($fetchres['username'])."')";
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
