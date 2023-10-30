<?php
ini_set('memory_limit', '2048M');
require_once '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include('../functions/phpfunctions.php');

// PHPExcel
//require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory
//require_once '../phpgeneration/PHPExcel/IOFactory.php';
$flag = $_POST['flag'];
if($flag == '')
{
	$url = '../home/index.php?a_link=crossproductdetails'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
	$userid = imaxgetcookie('userid');
	$id = $_GET['id'];
	$dealerid = $_POST['dealerid'];
	$region = $_POST['region'];
	$activecustomer_type = $_POST['activecustomer_type'];
	$branch = $_POST['branch'];
	$type = $_POST['type'];
	$category = $_POST['category'];
	$state = $_POST['state'];
	$includemainsheet = $_POST['includemainsheet'];
	$report_type = $_POST['report_type'];

	$branchpiece = ($branch == "")?(""):(" AND inv_mas_customer.branch = '".$branch."'");
	$typepiece = ($type == "")?(""):(" AND inv_mas_customer.type = '".$type."' ");
	$activecustomerpiece = ($activecustomer_type == "")?(""):(" AND inv_mas_customer.activecustomer = '".$activecustomer_type."' ");
	$categorypiece = ($category == "")?(""):(" AND inv_mas_customer.category = '".$category."' ");
	$regionpiece = ($region == "")?(""):(" AND inv_mas_customer.region = '".$region."' ");
	$dealeridpiece = ($dealerid == "")?(""):("  AND inv_mas_dealer.slno = '".$dealerid."' ");
	$statepiece = ($state == "")?(""):("  AND inv_mas_state.statecode = '".$state."' ");

	
	if($report_type == "report_nop" || $report_type == "report_fp" || $report_type == "report_nop" || $report_type == "report_matrix" || $report_type == "report_matrix_productwise"|| $report_type == "report_domain")
	{
		$query = "CREATE TEMPORARY TABLE cross_products select inv_mas_customer.slno,inv_mas_customer.customerid, inv_mas_customer.businessname,inv_mas_customer.address, inv_mas_customer.place ,inv_mas_customer.pincode,inv_mas_district.districtname,inv_mas_state.statename,inv_mas_region.category as region,IFNULL(inv_mas_customertype.customertype, 'UnAssigned') as customertype,IFNULL(inv_mas_customercategory.businesstype, 'UnAssigned') as businesstype,inv_mas_branch.branchname as branch,inv_mas_customer.website,inv_mas_dealer.businessname as dealer,group_concat(distinct dummy.group ORDER BY dummy.date) as products,
	replace(substring(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 1), length(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 1 - 1)) + 1), ',', '') as product1,  
	replace(substring(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 2), length(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 2 - 1)) + 1), ',', '') as product2,  
	replace(substring(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 3), length(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 3 - 1)) + 1), ',', '') as product3,  
	replace(substring(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 4), length(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 4 - 1)) + 1), ',', '') as product4,  
	replace(substring(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 5), length(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 5 - 1)) + 1), ',', '') as product5,  
	replace(substring(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 6), length(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 6 - 1)) + 1), ',', '') as product6,  
	replace(substring(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 7), length(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 7 - 1)) + 1), ',', '') as product7,  
	replace(substring(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 8), length(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 8 - 1)) + 1), ',', '') as product8,  
	replace(substring(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 9), length(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 9 - 1)) + 1), ',', '') as product9,  
	replace(substring(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 10), length(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 10 - 1)) + 1), ',', '') as product10,  
	replace(substring(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 11), length(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 11 - 1)) + 1), ',', '') as product11,  
	replace(substring(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 12), length(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 12 - 1)) + 1), ',', '') as product12
	from 
	(select distinct inv_customerproduct.customerreference, inv_mas_product.group, min(inv_customerproduct.date) as `date` from inv_customerproduct left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
	 group by inv_customerproduct.customerreference, inv_mas_product.group) as dummy
	left join inv_mas_customer on inv_mas_customer.slno = dummy.customerreference
	left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
	left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
	left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
	left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
	left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer
	left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type
	left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category
	 WHERE inv_mas_dealer.slno <> '34324324324' ".$regionpiece.$dealeridpiece.$branchpiece.$typepiece.$categorypiece.$activecustomerpiece.$statepiece." group by dummy.customerreference;";
		$result = runmysqlquery($query);
	}
	elseif($report_type == "report_yearwise")
	{
		$query12 = "select distinct `year` from inv_mas_product where (`year` <> ' ')";
		$result12 = runmysqlquery($query12);

		while($fetchres = mysqli_fetch_array($result12))
		{
			$productyear[] = $fetchres['year'];
			$fromyear = substr($fetchres['year'], 0, 4);
			$toyear = substr($fetchres['year'], 5, 2);
			
			$finalfromdate = $fromyear.'-04-01';
			$finaltodate =  '20'.$toyear.'-03-31';
			
			$query = "CREATE TEMPORARY TABLE cross_products".str_replace('-','',$fetchres['year'])." select inv_mas_customer.slno,inv_mas_customer.customerid, inv_mas_customer.businessname,inv_mas_customer.address, inv_mas_customer.place ,inv_mas_customer.pincode,inv_mas_district.districtname,inv_mas_state.statename,inv_mas_region.category as region,IFNULL(inv_mas_customertype.customertype, 'UnAssigned') as customertype,IFNULL(inv_mas_customercategory.businesstype, 'UnAssigned') as businesstype,inv_mas_branch.branchname as branch,inv_mas_customer.website,inv_mas_dealer.businessname as dealer,group_concat(distinct dummy.group ORDER BY dummy.date) as products,
	replace(substring(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 1), length(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 1 - 1)) + 1), ',', '') as product1,  
	replace(substring(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 2), length(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 2 - 1)) + 1), ',', '') as product2,  
	replace(substring(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 3), length(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 3 - 1)) + 1), ',', '') as product3,  
	replace(substring(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 4), length(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 4 - 1)) + 1), ',', '') as product4,  
	replace(substring(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 5), length(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 5 - 1)) + 1), ',', '') as product5,  
	replace(substring(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 6), length(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 6 - 1)) + 1), ',', '') as product6,  
	replace(substring(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 7), length(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 7 - 1)) + 1), ',', '') as product7,  
	replace(substring(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 8), length(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 8 - 1)) + 1), ',', '') as product8,  
	replace(substring(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 9), length(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 9 - 1)) + 1), ',', '') as product9,  
	replace(substring(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 10), length(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 10 - 1)) + 1), ',', '') as product10,  
	replace(substring(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 11), length(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 11 - 1)) + 1), ',', '') as product11,  
	replace(substring(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 12), length(substring_index(group_concat(distinct dummy.group ORDER BY dummy.date), ',', 12 - 1)) + 1), ',', '') as product12
	from 
	(select distinct inv_customerproduct.customerreference, inv_mas_product.group, min(inv_customerproduct.date) as `date` from inv_customerproduct left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
	where inv_customerproduct.date BETWEEN '".$finalfromdate."' and '".$finaltodate."' 
	 group by inv_customerproduct.customerreference, inv_mas_product.group) as dummy
	left join inv_mas_customer on inv_mas_customer.slno = dummy.customerreference
	left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
	left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
	left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
	left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
	left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer
	left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type	
	left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category
	 WHERE inv_mas_dealer.slno <> '34324324324' ".$regionpiece.$dealeridpiece.$branchpiece.$typepiece.$categorypiece.$activecustomerpiece.$statepiece." group by dummy.customerreference;";
		$result = runmysqlquery($query);
		}
		
	}
	
	// Create new Spreadsheet object
	$objPHPExcel= new Spreadsheet();
	
		
	//Define Style for header row
	$styleArray = array(
						'font' => array('bold' => true,),
						'fill'=> array('fillType'=>\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,'startColor'=> array('argb' => 'FFCCFFCC')),
						'borders' => array('allBorders'=> array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))
					);
					
		//Define Style for content area
	$styleArrayContent = array(
								'borders' => array('allBorders'=> array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))
	);
	
	$pageindex = 0;			
	
	/*--------------------------Customer List---------------------------------------*/
	if($includemainsheet == 'yes')
	
	{
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet();
		
		//Set the worksheet name
		$mySheet->setTitle('Main');
		
		//Apply style for header Row
		$mySheet->getStyle('A3:Z3')->applyFromArray($styleArray);
		
					
		//Merge the cell
		$mySheet->mergeCells('A1:Z1');
		$mySheet->mergeCells('A2:Z2');
		$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'Cross Product Sales Report');
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
			
		//Fille contents for Header Row
		$objPHPExcel->setActiveSheetIndex($pageindex)
				->setCellValue('A3', 'Sl No')
				->setCellValue('B3', 'Customer ID')
				->setCellValue('C3', 'Customer Name')
				->setCellValue('D3', 'District')
				->setCellValue('E3', 'State')
				->setCellValue('F3', 'Contact Person')
				->setCellValue('G3', 'Phone')
				->setCellValue('H3', 'Cell')
				->setCellValue('I3', 'Email Id')
				->setCellValue('J3', 'Region')
				->setCellValue('K3', 'Branch')
				->setCellValue('L3', 'Type')
				->setCellValue('M3', 'Category')
				->setCellValue('N3', 'Dealer')
				->setCellValue('O3', 'Product 1')
				->setCellValue('P3', 'Product 2')
				->setCellValue('Q3', 'Product 3')
				->setCellValue('R3', 'Product 4')
				->setCellValue('S3', 'Product 5')
				->setCellValue('T3', 'Product 6')
				->setCellValue('U3', 'Product 7')
				->setCellValue('V3', 'Product 8')
				->setCellValue('W3', 'Product 9')
				->setCellValue('X3', 'Product 10')
				->setCellValue('Y3', 'Product 11')
				->setCellValue('Z3', 'Product 12');
				
		$j =4;
		$slno= 0;
		
		$query = "select * from cross_products;";
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$slno++;
			// Fetch contact Details
			
			$querycontactdetails = "select customerid , GROUP_CONCAT(contactperson) as contactperson,GROUP_CONCAT(phone) 
as phone,GROUP_CONCAT(cell) as cell,GROUP_CONCAT(emailid) as emailid from inv_contactdetails where customerid = '".$fetch['slno']."'  group by customerid ";
			$resultcontact = runmysqlquery($querycontactdetails);
			$resultcontactdetails = mysqli_fetch_array($resultcontact);
			//$resultcontactdetails = runmysqlqueryfetch($querycontactdetails);
			
			$contactvalues = removedoublecomma($resultcontactdetails['contactperson']);
			$phoneres = removedoublecomma($resultcontactdetails['phone']);
			$cellres = removedoublecomma($resultcontactdetails['cell']);
			$emailidres = removedoublecomma($resultcontactdetails['emailid']);
			
			$mySheet->setCellValue('A' . $j,$slno)
			->setCellValue('B' . $j,cusidcombine($fetch['customerid']))
			->setCellValue('C' . $j,$fetch['businessname'])
			->setCellValue('D' . $j,$fetch['districtname'])
			->setCellValue('E' . $j,$fetch['statename'])
			->setCellValue('F' . $j,trim($contactvalues,','))
			->setCellValue('G' . $j,trim($phoneres ,','))
			->setCellValue('H' . $j,trim($cellres,','))
			->setCellValue('I' . $j,trim($emailidres,','))
			->setCellValue('J' . $j,$fetch['region'])
			->setCellValue('K' . $j,$fetch['branch'])
			->setCellValue('L' . $j,$fetch['customertype'])
			->setCellValue('M' . $j,$fetch['businesstype'])
			->setCellValue('N' . $j,$fetch['dealer'])
			->setCellValue('O' . $j,$fetch['product1'])
			->setCellValue('P' . $j,$fetch['product2'])
			->setCellValue('Q' . $j,$fetch['product3'])
			->setCellValue('R' . $j,$fetch['product4'])
			->setCellValue('S' . $j,$fetch['product5'])
			->setCellValue('T' . $j,$fetch['product6'])
			->setCellValue('U' . $j,$fetch['product7'])
			->setCellValue('V' . $j,$fetch['product8'])
			->setCellValue('W' . $j,$fetch['product9'])
			->setCellValue('X' . $j,$fetch['product10'])
			->setCellValue('Y' . $j,$fetch['product11'])
			->setCellValue('Z' . $j,$fetch['product12']);
			$j++;
		}
		
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
		$mySheet->getColumnDimension('O')->setWidth(6);
		$mySheet->getColumnDimension('P')->setWidth(6);
		$mySheet->getColumnDimension('Q')->setWidth(6);
		$mySheet->getColumnDimension('R')->setWidth(6);
		$mySheet->getColumnDimension('S')->setWidth(6);
		$mySheet->getColumnDimension('T')->setWidth(6);
		$mySheet->getColumnDimension('U')->setWidth(6);
		$mySheet->getColumnDimension('V')->setWidth(6);
		$mySheet->getColumnDimension('W')->setWidth(6);
		$mySheet->getColumnDimension('X')->setWidth(6);
		$mySheet->getColumnDimension('Y')->setWidth(6);
		$mySheet->getColumnDimension('Z')->setWidth(6);
		$pageindex++;

		//Begin with Worksheet 2 (Summary)
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($pageindex);
	}

	/*--------------------------Number of Products---------------------------------------*/

	if($report_type == "report_nop")
	{
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet();
		
		//Set the worksheet name
		$mySheet->setTitle('No of Products');
		
		//Begin writing Region wise summary
		$currentrow = 1;
		
		//Set heading for Region wise summary
		$mySheet->setCellValue('A'.$currentrow,'Region wise summary');
		
		$currentrow++;
		//Set table headings
		$mySheet->setCellValue('B'.$currentrow,'Region')
				->setCellValue('C'.$currentrow,'1 Product')
				->setCellValue('D'.$currentrow,'2 Products')
				->setCellValue('E'.$currentrow,'3 Products')
				->setCellValue('F'.$currentrow,'4 Products')
				->setCellValue('G'.$currentrow,'5+ Products')
				->setCellValue('H'.$currentrow,'Total');
				
		//Apply style for header Row
		$mySheet->getStyle('B'.$currentrow.':H'.$currentrow)->applyFromArray($styleArray);
		
		//Set table data
		$currentrow++;
		$query = "select region, count(product2 = '' OR NULL) as products1,(count(product3 = '' OR NULL) - count(product2 = '' OR NULL)) as products2,(count(product4 = '' OR NULL) - count(product3 = '' OR NULL)) as products3,(count(product5 = '' OR NULL) - count(product4 = '' OR NULL)) as products4,(count(product5 <> '' OR NULL)) as products5 from cross_products group by region order by region;";
		$result = runmysqlquery($query);
		
		//Insert data
		$databeginrow = $currentrow;
		while($row_data = mysqli_fetch_array($result))
		{
			$mySheet->setCellValue('B'.$currentrow,$row_data['region'])
					->setCellValue('C'.$currentrow,$row_data['products1'])
					->setCellValue('D'.$currentrow,$row_data['products2'])
					->setCellValue('E'.$currentrow,$row_data['products3'])
					->setCellValue('F'.$currentrow,$row_data['products4'])
					->setCellValue('G'.$currentrow,$row_data['products5'])
					->setCellValue('H'.$currentrow,"=SUM(C".$currentrow.":G".$currentrow.")");
			$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
			$currentrow++;
		}
		//Insert Total
		$mySheet->setCellValue('B'.$currentrow,'Total')
				->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
				->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
				->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
				->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
				->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
				->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")");
		$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
	
		//Apply style for content Row
		$mySheet->getStyle('B'.$databeginrow.':H'.$currentrow)->applyFromArray($styleArrayContent);
		
		//set the default width for column
		$mySheet->getColumnDimension('A')->setWidth(20);
		$mySheet->getColumnDimension('B')->setWidth(20);
		$mySheet->getColumnDimension('C')->setWidth(10);
		$mySheet->getColumnDimension('D')->setWidth(10);
		$mySheet->getColumnDimension('E')->setWidth(10);
		$mySheet->getColumnDimension('F')->setWidth(10);
		$mySheet->getColumnDimension('G')->setWidth(10);
		$mySheet->getColumnDimension('H')->setWidth(10);
		
		//Begin writing Branch wise summary
		$currentrow = $currentrow + 3;
		
		//Set heading summary
		$mySheet->setCellValue('A'.$currentrow,'Branch wise summary');
		
		$currentrow++;
		//Set table headings
		$mySheet->setCellValue('B'.$currentrow,'Branch')
				->setCellValue('C'.$currentrow,'1 Product')
				->setCellValue('D'.$currentrow,'2 Products')
				->setCellValue('E'.$currentrow,'3 Products')
				->setCellValue('F'.$currentrow,'4 Products')
				->setCellValue('G'.$currentrow,'5+ Products')
				->setCellValue('H'.$currentrow,'Total');
				
		//Apply style for header Row
		$mySheet->getStyle('B'.$currentrow.':H'.$currentrow)->applyFromArray($styleArray);
		
		//Set table data
		$currentrow++;
		$query = "select branch, count(product2 = '' OR NULL) as products1,(count(product3 = '' OR NULL) - count(product2 = '' OR NULL)) as products2,(count(product4 = '' OR NULL) - count(product3 = '' OR NULL)) as products3,(count(product5 = '' OR NULL) - count(product4 = '' OR NULL)) as products4,(count(product5 <> '' OR NULL)) as products5 from cross_products group by branch order by branch;";
		$result = runmysqlquery($query);
		
		//Insert data
		$databeginrow = $currentrow;
		while($row_data = mysqli_fetch_array($result))
		{
			$mySheet->setCellValue('B'.$currentrow,$row_data['branch'])
					->setCellValue('C'.$currentrow,$row_data['products1'])
					->setCellValue('D'.$currentrow,$row_data['products2'])
					->setCellValue('E'.$currentrow,$row_data['products3'])
					->setCellValue('F'.$currentrow,$row_data['products4'])
					->setCellValue('G'.$currentrow,$row_data['products5'])
					->setCellValue('H'.$currentrow,"=SUM(C".$currentrow.":G".$currentrow.")");
			$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
			$currentrow++;
		}
		//Insert Total
		$mySheet->setCellValue('B'.$currentrow,'Total')
				->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
				->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
				->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
				->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
				->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
				->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")");
		$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
	
		//Apply style for Content row
		$mySheet->getStyle('B'.$databeginrow.':H'.$currentrow)->applyFromArray($styleArrayContent);
		
	
		//Begin writing Category wise summary
		$currentrow = $currentrow + 3;
		
		//Set heading summary
		$mySheet->setCellValue('A'.$currentrow,'Category wise summary');
		
		$currentrow++;
		//Set table headings
		$mySheet->setCellValue('B'.$currentrow,'Category')
				->setCellValue('C'.$currentrow,'1 Product')
				->setCellValue('D'.$currentrow,'2 Products')
				->setCellValue('E'.$currentrow,'3 Products')
				->setCellValue('F'.$currentrow,'4 Products')
				->setCellValue('G'.$currentrow,'5+ Products')
				->setCellValue('H'.$currentrow,'Total');
				
		//Apply style for header Row
		$mySheet->getStyle('B'.$currentrow.':H'.$currentrow)->applyFromArray($styleArray);
		
		//Set table data
		$currentrow++;
		$query = "select businesstype, count(product2 = '' OR NULL) as products1,(count(product3 = '' OR NULL) - count(product2 = '' OR NULL)) as products2,(count(product4 = '' OR NULL) - count(product3 = '' OR NULL)) as products3,(count(product5 = '' OR NULL) - count(product4 = '' OR NULL)) as products4,(count(product5 <> '' OR NULL)) as products5 from cross_products group by businesstype order by businesstype;";
		$result = runmysqlquery($query);
		
		//Insert data
		$databeginrow = $currentrow;
		while($row_data = mysqli_fetch_array($result))
		{
			$mySheet->setCellValue('B'.$currentrow,$row_data['businesstype'])
					->setCellValue('C'.$currentrow,$row_data['products1'])
					->setCellValue('D'.$currentrow,$row_data['products2'])
					->setCellValue('E'.$currentrow,$row_data['products3'])
					->setCellValue('F'.$currentrow,$row_data['products4'])
					->setCellValue('G'.$currentrow,$row_data['products5'])
					->setCellValue('H'.$currentrow,"=SUM(C".$currentrow.":G".$currentrow.")");
			$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
			$currentrow++;
		}
		//Insert Total
		$mySheet->setCellValue('B'.$currentrow,'Total')
				->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
				->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
				->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
				->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
				->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
				->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")");
		$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
	
		//Apply style for Content row
		$mySheet->getStyle('B'.$databeginrow.':H'.$currentrow)->applyFromArray($styleArrayContent);
		
	
		//Begin writing State wise summary
		$currentrow = $currentrow + 3;
		
		//Set heading summary
		$mySheet->setCellValue('A'.$currentrow,'State wise summary');
		
		$currentrow++;
		
		//Set table headings
		$mySheet->setCellValue('B'.$currentrow,'State')
				->setCellValue('C'.$currentrow,'1 Product')
				->setCellValue('D'.$currentrow,'2 Products')
				->setCellValue('E'.$currentrow,'3 Products')
				->setCellValue('F'.$currentrow,'4 Products')
				->setCellValue('G'.$currentrow,'5+ Products')
				->setCellValue('H'.$currentrow,'Total');
				
		//Apply style for header Row
		$mySheet->getStyle('B'.$currentrow.':H'.$currentrow)->applyFromArray($styleArray);
		
		//Set table data
		$currentrow++;
		$query = "select statename, count(product2 = '' OR NULL) as products1,(count(product3 = '' OR NULL) - count(product2 = '' OR NULL)) as products2,(count(product4 = '' OR NULL) - count(product3 = '' OR NULL)) as products3,(count(product5 = '' OR NULL) - count(product4 = '' OR NULL)) as products4,(count(product5 <> '' OR NULL)) as products5 from cross_products group by statename order by statename;";
		$result = runmysqlquery($query);
		
		//Insert data
		$databeginrow = $currentrow;
		while($row_data = mysqli_fetch_array($result))
		{
			$mySheet->setCellValue('B'.$currentrow,$row_data['statename'])
					->setCellValue('C'.$currentrow,$row_data['products1'])
					->setCellValue('D'.$currentrow,$row_data['products2'])
					->setCellValue('E'.$currentrow,$row_data['products3'])
					->setCellValue('F'.$currentrow,$row_data['products4'])
					->setCellValue('G'.$currentrow,$row_data['products5'])
					->setCellValue('H'.$currentrow,"=SUM(C".$currentrow.":G".$currentrow.")");
			$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
			$currentrow++;
		}
		
		//Insert Total
		$mySheet->setCellValue('B'.$currentrow,'Total')
				->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
				->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
				->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
				->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
				->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
				->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")");
		$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
	
		//Apply style for content Row
		$mySheet->getStyle('B'.$databeginrow.':H'.$currentrow)->applyFromArray($styleArrayContent);
		
	
		//Begin writing Dealer wise summary
		$currentrow = $currentrow + 3;
		
		//Set heading summary
		$mySheet->setCellValue('A'.$currentrow,'Dealer wise summary');
		
		$currentrow++;
		//Set table headings
		$mySheet->setCellValue('B'.$currentrow,'Dealer')
				->setCellValue('C'.$currentrow,'1 Product')
				->setCellValue('D'.$currentrow,'2 Products')
				->setCellValue('E'.$currentrow,'3 Products')
				->setCellValue('F'.$currentrow,'4 Products')
				->setCellValue('G'.$currentrow,'5+ Products')
				->setCellValue('H'.$currentrow,'Total');
				
		//Apply style for header Row
		$mySheet->getStyle('B'.$currentrow.':H'.$currentrow)->applyFromArray($styleArray);
		
		//Set table data
		$currentrow++;
		$query = "select dealer, count(product2 = '' OR NULL) as products1,(count(product3 = '' OR NULL) - count(product2 = '' OR NULL)) as products2,(count(product4 = '' OR NULL) - count(product3 = '' OR NULL)) as products3,(count(product5 = '' OR NULL) - count(product4 = '' OR NULL)) as products4,(count(product5 <> '' OR NULL)) as products5 from cross_products group by dealer order by dealer;";
		$result = runmysqlquery($query);
		
		//Insert data
		$databeginrow = $currentrow;
		while($row_data = mysqli_fetch_array($result))
		{
			$mySheet->setCellValue('B'.$currentrow,$row_data['dealer'])
					->setCellValue('C'.$currentrow,$row_data['products1'])
					->setCellValue('D'.$currentrow,$row_data['products2'])
					->setCellValue('E'.$currentrow,$row_data['products3'])
					->setCellValue('F'.$currentrow,$row_data['products4'])
					->setCellValue('G'.$currentrow,$row_data['products5'])
					->setCellValue('H'.$currentrow,"=SUM(C".$currentrow.":G".$currentrow.")");
			$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
			$currentrow++;
		}
		//Insert Total
		$mySheet->setCellValue('B'.$currentrow,'Total')
				->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
				->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
				->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
				->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
				->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")")
				->setCellValue('H'.$currentrow,"=SUM(H".$databeginrow.":H".($currentrow-1).")");
		$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
	
		//Apply style for content Row
		$mySheet->getStyle('B'.$databeginrow.':H'.$currentrow)->applyFromArray($styleArrayContent);
	}
	/*--------------------------First Product Based---------------------------------------*/
	elseif($report_type == "report_fp")
	{
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet();
		
		//Set the worksheet name
		$mySheet->setTitle('Cross Sales Made');

		//Begin writing First product wise summary
		$currentrow = 1;
		
		//Set heading
		$mySheet->setCellValue('A'.$currentrow,'First product wise summary');
		
		$currentrow++;
		//Set table headings
		$mySheet->setCellValue('B'.$currentrow,'First Product')
				->setCellValue('C'.$currentrow,'Total')
				->setCellValue('D'.$currentrow,'Cross Sales Made')
				->setCellValue('E'.$currentrow,'Not Made');
				
		//Apply style for header Row
		$mySheet->getStyle('B'.$currentrow.':E'.$currentrow)->applyFromArray($styleArray);
		
		//Set table data
		$currentrow++;
		$query = "select product1 as firstproduct, (count(product1 <> '' OR NOT NULL) - count(product2 = '' OR NOT NULL)) as salesmade, count(product2 = '' OR NOT NULL) as salesnotmade from cross_products group by product1 order by product1;";
		$result = runmysqlquery($query);
		
		//Insert data
		$databeginrow = $currentrow;
		while($row_data = mysqli_fetch_array($result))
		{
			$mySheet->setCellValue('B'.$currentrow,$row_data['firstproduct'])
					->setCellValue('C'.$currentrow,"=SUM(D".$currentrow.":E".$currentrow.")")
					->setCellValue('D'.$currentrow,$row_data['salesmade'])
					->setCellValue('E'.$currentrow,$row_data['salesnotmade']);
			$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
			$currentrow++;
		}
		
		//Insert Total
		$mySheet->setCellValue('B'.$currentrow,'Total')
				->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
				->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
				->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")");
		$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
	
		//Apply style for Content row
		$mySheet->getStyle('B'.$databeginrow.':E'.$currentrow)->applyFromArray($styleArrayContent);
	
		//set the default width for column
		$mySheet->getColumnDimension('A')->setWidth(25);
		$mySheet->getColumnDimension('B')->setWidth(17);
		$mySheet->getColumnDimension('C')->setWidth(12);
		$mySheet->getColumnDimension('D')->setWidth(12);
		$mySheet->getColumnDimension('E')->setWidth(12);
		
		//Begin with new Worksheet  (Region wise Summary)
		$pageindex++;
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($pageindex);
	
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet();
		
		//Set the worksheet name
		$mySheet->setTitle('Region Wise Cross Sales Made');
		
		//Begin writing First product wise summary
		$currentrow = 1;
		
		//Select distinct regions and loop it for region wise summary
		$queryregion = "select distinct region from cross_products order by region";
		$resultregion = runmysqlquery($queryregion);
		while($fetchregion = mysqli_fetch_array($resultregion))
		{
			//Give 3 empty rows
			$currentrow = $currentrow + 3;
			static $currentrow = 1;
			
			//Set heading
			$mySheet->setCellValue('A'.$currentrow,$fetchregion['region'].' | First product wise summary');
			
			$currentrow++;
			//Set table headings
			$mySheet->setCellValue('B'.$currentrow,'First Product')
					->setCellValue('C'.$currentrow,'Total')
					->setCellValue('D'.$currentrow,'Cross Sales Made')
					->setCellValue('E'.$currentrow,'Not Made');
					
			//Apply style for header Row
			$mySheet->getStyle('B'.$currentrow.':E'.$currentrow)->applyFromArray($styleArray);
			
			//Set table data
			$currentrow++;
			$query = "select product1 as firstproduct, (count(product1 <> '' OR NOT NULL) - count(product2 = '' OR NOT NULL)) as salesmade, count(product2 = '' OR NOT NULL) as salesnotmade from cross_products where region = '".$fetchregion['region']."' group by product1 order by product1;";
			$result = runmysqlquery($query);
			
			//Insert data
			$databeginrow = $currentrow;
			while($row_data = mysqli_fetch_array($result))
			{
				$mySheet->setCellValue('B'.$currentrow,$row_data['firstproduct'])
						->setCellValue('C'.$currentrow,"=SUM(D".$currentrow.":E".$currentrow.")")
						->setCellValue('D'.$currentrow,$row_data['salesmade'])
						->setCellValue('E'.$currentrow,$row_data['salesnotmade']);
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$currentrow++;
			}
			
			//Insert Total
			$mySheet->setCellValue('B'.$currentrow,'Total')
					->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
					->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
					->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")");
			$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
		
			//Apply style for Content row
			$mySheet->getStyle('B'.$databeginrow.':E'.$currentrow)->applyFromArray($styleArrayContent);
		}
		
		//set the default width for column
		$mySheet->getColumnDimension('A')->setWidth(25);
		$mySheet->getColumnDimension('B')->setWidth(17);
		$mySheet->getColumnDimension('C')->setWidth(12);
		$mySheet->getColumnDimension('D')->setWidth(12);
		$mySheet->getColumnDimension('E')->setWidth(12);

		//Begin with  new Worksheet (Branch wise Summary)
		$pageindex++;
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($pageindex);
	
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet();
		
		//Set the worksheet name
		$mySheet->setTitle('Branch Wise Cross Sales Made');
		
		//Begin writing First product wise summary
		$currentrow = 1;
		
		//Select distinct regions and loop it for branch wise summary
		$querybranch = "select distinct branch from cross_products order by branch";
		$resultbranch = runmysqlquery($querybranch);
		while($fetchbranch = mysqli_fetch_array($resultbranch))
		{
			//Give 3 empty rows
			$currentrow = $currentrow + 3;
			static $currentrow = 1;
			
			//Set heading
			$mySheet->setCellValue('A'.$currentrow,$fetchbranch['branch'].' | First product wise summary');
			
			$currentrow++;
			//Set table headings
			$mySheet->setCellValue('B'.$currentrow,'First Product')
					->setCellValue('C'.$currentrow,'Total')
					->setCellValue('D'.$currentrow,'Cross Sales Made')
					->setCellValue('E'.$currentrow,'Not Made');
					
			//Apply style for header Row
			$mySheet->getStyle('B'.$currentrow.':E'.$currentrow)->applyFromArray($styleArray);
			
			//Set table data
			$currentrow++;
			$query = "select product1 as firstproduct, (count(product1 <> '' OR NOT NULL) - count(product2 = '' OR NOT NULL)) as salesmade, count(product2 = '' OR NOT NULL) as salesnotmade from cross_products where branch = '".$fetchbranch['branch']."' group by product1 order by product1;";
			$result = runmysqlquery($query);
			
			//Insert data
			$databeginrow = $currentrow;
			while($row_data = mysqli_fetch_array($result))
			{
				$mySheet->setCellValue('B'.$currentrow,$row_data['firstproduct'])
						->setCellValue('C'.$currentrow,"=SUM(D".$currentrow.":E".$currentrow.")")
						->setCellValue('D'.$currentrow,$row_data['salesmade'])
						->setCellValue('E'.$currentrow,$row_data['salesnotmade']);
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$currentrow++;
			}
			
			//Insert Total
			$mySheet->setCellValue('B'.$currentrow,'Total')
					->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
					->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
					->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")");
			$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
		
			//Apply style for Content row
			$mySheet->getStyle('B'.$databeginrow.':E'.$currentrow)->applyFromArray($styleArrayContent);
		}
		
		//set the default width for column
		$mySheet->getColumnDimension('A')->setWidth(25);
		$mySheet->getColumnDimension('B')->setWidth(17);
		$mySheet->getColumnDimension('C')->setWidth(12);
		$mySheet->getColumnDimension('D')->setWidth(12);
		$mySheet->getColumnDimension('E')->setWidth(12);

		//Begin with  new Worksheet (Category wise Summary)
		$pageindex++;
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($pageindex);
	
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet();
		
		//Set the worksheet name
		$mySheet->setTitle('Category Wise Cross Sales Made');
		
		//Begin writing First product wise summary
		$currentrow = 1;
		
		//Select distinct regions and loop it for branch wise summary
		$querybusinesstype = "select distinct businesstype from cross_products order by businesstype";
		$resultbusinesstype = runmysqlquery($querybusinesstype);
		while($fetchbusinesstype = mysqli_fetch_array($resultbusinesstype))
		{
			//Give 3 empty rows
			$currentrow = $currentrow + 3;
			static $currentrow = 1;
			
			//Set heading
			$mySheet->setCellValue('A'.$currentrow,$fetchbusinesstype['businesstype'].' | First product wise summary');
			
			$currentrow++;
			//Set table headings
			$mySheet->setCellValue('B'.$currentrow,'First Product')
					->setCellValue('C'.$currentrow,'Total')
					->setCellValue('D'.$currentrow,'Cross Sales Made')
					->setCellValue('E'.$currentrow,'Not Made');
					
			//Apply style for header Row
			$mySheet->getStyle('B'.$currentrow.':E'.$currentrow)->applyFromArray($styleArray);
			
			//Set table data
			$currentrow++;
			$query = "select product1 as firstproduct, (count(product1 <> '' OR NOT NULL) - count(product2 = '' OR NOT NULL)) as salesmade, count(product2 = '' OR NOT NULL) as salesnotmade from cross_products where businesstype = '".$fetchbusinesstype['businesstype']."' group by product1 order by product1;";
			$result = runmysqlquery($query);
			
			//Insert data
			$databeginrow = $currentrow;
			while($row_data = mysqli_fetch_array($result))
			{
				$mySheet->setCellValue('B'.$currentrow,$row_data['firstproduct'])
						->setCellValue('C'.$currentrow,"=SUM(D".$currentrow.":E".$currentrow.")")
						->setCellValue('D'.$currentrow,$row_data['salesmade'])
						->setCellValue('E'.$currentrow,$row_data['salesnotmade']);
				$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
				$currentrow++;
			}
			
			//Insert Total
			$mySheet->setCellValue('B'.$currentrow,'Total')
					->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
					->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
					->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")");
			$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
		
			//Apply style for Content row
			$mySheet->getStyle('B'.$databeginrow.':E'.$currentrow)->applyFromArray($styleArrayContent);
		}
		
		//set the default width for column
		$mySheet->getColumnDimension('A')->setWidth(25);
		$mySheet->getColumnDimension('B')->setWidth(17);
		$mySheet->getColumnDimension('C')->setWidth(12);
		$mySheet->getColumnDimension('D')->setWidth(12);
		$mySheet->getColumnDimension('E')->setWidth(12);
	}
	
	/*--------------------------Each Product Matrix (Product to Product)---------------------------------------*/
	elseif($report_type == "report_matrix")
	{
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet();
		
		
		//Define product group array
		$productgroups = array("TDS","STO","SPP","SAC","SVH","SVI");
		
		//Set the worksheet name
		$mySheet->setTitle('Product Matrix');
		
		//Begin writing First product wise summary
		$currentrow = 1;
		
		//Set heading
		$mySheet->setCellValue('A'.$currentrow,'Product Matrix');
		$currentrow++;
		$mySheet->setCellValue('B'.$currentrow,'Made');
		
		$currentrow++;
		//Set table headings
		$mySheet->setCellValue('B'.$currentrow,'Product Group')
				->setCellValue('C'.$currentrow,'Total')
				->setCellValue('D'.$currentrow,$productgroups[0])
				->setCellValue('E'.$currentrow,$productgroups[1])
				->setCellValue('F'.$currentrow,$productgroups[2])
				->setCellValue('G'.$currentrow,$productgroups[3])
				->setCellValue('H'.$currentrow,$productgroups[4])
				->setCellValue('I'.$currentrow,$productgroups[5]);
				
		//Apply style for header Row
		$mySheet->getStyle('B'.$currentrow.':I'.$currentrow)->applyFromArray($styleArray);
		
		//Set table data
		$currentrow++;
		
		//Insert data
		$databeginrow = $currentrow;
		for($i = 0; $i < count($productgroups); $i++)
		{
			$query = "select '".$productgroups[$i]."' as productgroup, count(*) as total, count(products like concat('%".$productgroups[0]."%') OR NULL) as tds, count(products like concat('%".$productgroups[1]."%') OR NULL) as sto, count(products like concat('%".$productgroups[2]."%') OR NULL) as spp, count(products like concat('%".$productgroups[3]."%') OR NULL) as sai, count(products like concat('%".$productgroups[4]."%') OR NULL) as svh, count(products like concat('%".$productgroups[5]."%') OR NULL) as svi  from cross_products where products like '%".$productgroups[$i]."%'";
			$result = runmysqlquery($query);
			$fetch = mysqli_fetch_array($result);
			$mySheet->setCellValue('B'.$currentrow,$fetch['productgroup'])
					->setCellValue('C'.$currentrow,$fetch['total'])
					->setCellValue('D'.$currentrow,$fetch['tds'])
					->setCellValue('E'.$currentrow,$fetch['sto'])
					->setCellValue('F'.$currentrow,$fetch['spp'])
					->setCellValue('G'.$currentrow,$fetch['sai'])
					->setCellValue('H'.$currentrow,$fetch['svh'])
					->setCellValue('I'.$currentrow,$fetch['svi']);
			$currentrow++;
		}
		
		//Apply style for Content row
		$mySheet->getStyle('B'.$databeginrow.':I'.($currentrow-1))->applyFromArray($styleArrayContent);
		
		//set the default width for column
		$mySheet->getColumnDimension('A')->setWidth(15);
		$mySheet->getColumnDimension('B')->setWidth(15);
		$mySheet->getColumnDimension('C')->setWidth(9);
		$mySheet->getColumnDimension('D')->setWidth(9);
		$mySheet->getColumnDimension('E')->setWidth(9);
		$mySheet->getColumnDimension('F')->setWidth(9);
		$mySheet->getColumnDimension('G')->setWidth(9);
		$mySheet->getColumnDimension('H')->setWidth(9);
		$mySheet->getColumnDimension('I')->setWidth(9);

		//Begin writing First product wise summary
		$currentrow = 1;
		
		//Set heading
		//$mySheet->setCellValue('J'.$currentrow,'Product Matrix');
		$currentrow++;
		$mySheet->setCellValue('K'.$currentrow,'Not Made');
		
		$currentrow++;
		//Set table headings
		$mySheet->setCellValue('K'.$currentrow,'Product Group')
				->setCellValue('L'.$currentrow,'Total')
				->setCellValue('M'.$currentrow,$productgroups[0])
				->setCellValue('N'.$currentrow,$productgroups[1])
				->setCellValue('O'.$currentrow,$productgroups[2])
				->setCellValue('P'.$currentrow,$productgroups[3])
				->setCellValue('Q'.$currentrow,$productgroups[4])
				->setCellValue('R'.$currentrow,$productgroups[5]);
				
		//Apply style for header Row
		$mySheet->getStyle('K'.$currentrow.':R'.$currentrow)->applyFromArray($styleArray);
		
		//Set table data
		$currentrow++;
		
		//Insert data
		$databeginrow = $currentrow;
		for($i = 0; $i < count($productgroups); $i++)
		{
			$query = "select '".$productgroups[$i]."' as productgroup, count(*) as total, count(products not like concat('%".$productgroups[0]."%') OR NULL) as tds, count(products not like concat('%".$productgroups[1]."%') OR NULL) as sto, count(products not like concat('%".$productgroups[2]."%') OR NULL) as spp, count(products not like concat('%".$productgroups[3]."%') OR NULL) as sai, count(products not like concat('%".$productgroups[4]."%') OR NULL) as svh, count(products not like concat('%".$productgroups[5]."%') OR NULL) as svi  from cross_products where products like '%".$productgroups[$i]."%'";
			$result = runmysqlquery($query);
			$fetch = mysqli_fetch_array($result);
			$mySheet->setCellValue('K'.$currentrow,$fetch['productgroup'])
					->setCellValue('L'.$currentrow,$fetch['total'])
					->setCellValue('M'.$currentrow,$fetch['tds'])
					->setCellValue('N'.$currentrow,$fetch['sto'])
					->setCellValue('O'.$currentrow,$fetch['spp'])
					->setCellValue('P'.$currentrow,$fetch['sai'])
					->setCellValue('Q'.$currentrow,$fetch['svh'])
					->setCellValue('R'.$currentrow,$fetch['svi']);
			$currentrow++;
		}
		
		//Apply style for Content row
		$mySheet->getStyle('K'.$databeginrow.':R'.($currentrow-1))->applyFromArray($styleArrayContent);
		
		//set the default width for column
		//$mySheet->getColumnDimension('A')->setWidth(15);
		$mySheet->getColumnDimension('K')->setWidth(15);
		$mySheet->getColumnDimension('L')->setWidth(9);
		$mySheet->getColumnDimension('M')->setWidth(9);
		$mySheet->getColumnDimension('N')->setWidth(9);
		$mySheet->getColumnDimension('O')->setWidth(9);
		$mySheet->getColumnDimension('P')->setWidth(9);
		$mySheet->getColumnDimension('Q')->setWidth(9);
		$mySheet->getColumnDimension('R')->setWidth(9);

		//Begin with  new Worksheet (Region wise summary)-----------------------------------------------------
		$pageindex++;
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($pageindex);
	
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet();
		
		//Set the worksheet name
		$mySheet->setTitle('Product Matrix - Region Wise');
		
		//Begin writing First product wise summary
		$currentrow = 1;
		
		//Select distinct regions and loop it for region wise summary
		$queryregion = "select distinct region from cross_products order by region";
		$resultregion = runmysqlquery($queryregion);
		while($fetchregion = mysqli_fetch_array($resultregion))
		{
			//Give 3 empty rows
			if($currentrow > 1)
			$currentrow = $currentrow + 3;
			
			//Set heading
			$mySheet->setCellValue('A'.$currentrow,$fetchregion['region'].' | Product Matrix');
			$currentrow++;
			$mySheet->setCellValue('B'.$currentrow,'Made');
			
			$currentrow++;
			//Set table headings
			$mySheet->setCellValue('B'.$currentrow,'Product Group')
					->setCellValue('C'.$currentrow,'Total')
					->setCellValue('D'.$currentrow,$productgroups[0])
					->setCellValue('E'.$currentrow,$productgroups[1])
					->setCellValue('F'.$currentrow,$productgroups[2])
					->setCellValue('G'.$currentrow,$productgroups[3])
					->setCellValue('H'.$currentrow,$productgroups[4])
					->setCellValue('I'.$currentrow,$productgroups[5]);
					
			//Apply style for header Row
			$mySheet->getStyle('B'.$currentrow.':I'.$currentrow)->applyFromArray($styleArray);
			
			//Set table data
			$currentrow++;
			
			//Insert data
			$databeginrow = $currentrow;
			for($i = 0; $i < count($productgroups); $i++)
			{
				$query = "select '".$productgroups[$i]."' as productgroup, count(*) as total, count(products like concat('%".$productgroups[0]."%') OR NULL) as tds, count(products like concat('%".$productgroups[1]."%') OR NULL) as sto, count(products like concat('%".$productgroups[2]."%') OR NULL) as spp, count(products like concat('%".$productgroups[3]."%') OR NULL) as sai, count(products like concat('%".$productgroups[4]."%') OR NULL) as svh, count(products like concat('%".$productgroups[5]."%') OR NULL) as svi  from cross_products where products like '%".$productgroups[$i]."%' and region = '".$fetchregion['region']."'";
				$result = runmysqlquery($query);
				$fetch = mysqli_fetch_array($result);
				$mySheet->setCellValue('B'.$currentrow,$fetch['productgroup'])
						->setCellValue('C'.$currentrow,$fetch['total'])
						->setCellValue('D'.$currentrow,$fetch['tds'])
						->setCellValue('E'.$currentrow,$fetch['sto'])
						->setCellValue('F'.$currentrow,$fetch['spp'])
						->setCellValue('G'.$currentrow,$fetch['sai'])
						->setCellValue('H'.$currentrow,$fetch['svh'])
						->setCellValue('I'.$currentrow,$fetch['svi']);
				$currentrow++;
			}
			
			//Apply style for Content row
			$mySheet->getStyle('B'.$databeginrow.':I'.($currentrow-1))->applyFromArray($styleArrayContent);
		}
		
		//set the default width for column
		$mySheet->getColumnDimension('A')->setWidth(15);
		$mySheet->getColumnDimension('B')->setWidth(15);
		$mySheet->getColumnDimension('C')->setWidth(9);
		$mySheet->getColumnDimension('D')->setWidth(9);
		$mySheet->getColumnDimension('E')->setWidth(9);
		$mySheet->getColumnDimension('F')->setWidth(9);
		$mySheet->getColumnDimension('G')->setWidth(9);
		$mySheet->getColumnDimension('H')->setWidth(9);
		$mySheet->getColumnDimension('I')->setWidth(9);

		//Begin writing First product wise summary
		$currentrow = 1;
		
		//Select distinct regions and loop it for region wise summary
		$queryregion = "select distinct region from cross_products order by region";
		$resultregion = runmysqlquery($queryregion);
		while($fetchregion = mysqli_fetch_array($resultregion))
		{
			//Give 3 empty rows
			if($currentrow > 1)
			$currentrow = $currentrow + 3;

			//Set heading
			//$mySheet->setCellValue('J'.$currentrow,'Product Matrix');
			$currentrow++;
			$mySheet->setCellValue('K'.$currentrow,'Not Made');
			
			$currentrow++;
			//Set table headings
			$mySheet->setCellValue('K'.$currentrow,'Product Group')
					->setCellValue('L'.$currentrow,'Total')
					->setCellValue('M'.$currentrow,$productgroups[0])
					->setCellValue('N'.$currentrow,$productgroups[1])
					->setCellValue('O'.$currentrow,$productgroups[2])
					->setCellValue('P'.$currentrow,$productgroups[3])
					->setCellValue('Q'.$currentrow,$productgroups[4])
					->setCellValue('R'.$currentrow,$productgroups[5]);
					
			//Apply style for header Row
			$mySheet->getStyle('K'.$currentrow.':R'.$currentrow)->applyFromArray($styleArray);
			
			//Set table data
			$currentrow++;
			
			//Insert data
			$databeginrow = $currentrow;
			for($i = 0; $i < count($productgroups); $i++)
			{
				$query = "select '".$productgroups[$i]."' as productgroup, count(*) as total, count(products not like concat('%".$productgroups[0]."%') OR NULL) as tds, count(products not like concat('%".$productgroups[1]."%') OR NULL) as sto, count(products not like concat('%".$productgroups[2]."%') OR NULL) as spp, count(products not like concat('%".$productgroups[3]."%') OR NULL) as sai, count(products not like concat('%".$productgroups[4]."%') OR NULL) as svh, count(products not like concat('%".$productgroups[5]."%') OR NULL) as svi  from cross_products where products like '%".$productgroups[$i]."%' and region = '".$fetchregion['region']."'";
				$result = runmysqlquery($query);
				$fetch = mysqli_fetch_array($result);
				$mySheet->setCellValue('K'.$currentrow,$fetch['productgroup'])
						->setCellValue('L'.$currentrow,$fetch['total'])
						->setCellValue('M'.$currentrow,$fetch['tds'])
						->setCellValue('N'.$currentrow,$fetch['sto'])
						->setCellValue('O'.$currentrow,$fetch['spp'])
						->setCellValue('P'.$currentrow,$fetch['sai'])
						->setCellValue('Q'.$currentrow,$fetch['svh'])
						->setCellValue('R'.$currentrow,$fetch['svi']);
				$currentrow++;
			}
			
			//Apply style for Content row
			$mySheet->getStyle('K'.$databeginrow.':R'.($currentrow-1))->applyFromArray($styleArrayContent);
		}
		
		//set the default width for column
		//$mySheet->getColumnDimension('A')->setWidth(15);
		$mySheet->getColumnDimension('K')->setWidth(15);
		$mySheet->getColumnDimension('L')->setWidth(9);
		$mySheet->getColumnDimension('M')->setWidth(9);
		$mySheet->getColumnDimension('N')->setWidth(9);
		$mySheet->getColumnDimension('O')->setWidth(9);
		$mySheet->getColumnDimension('P')->setWidth(9);
		$mySheet->getColumnDimension('Q')->setWidth(9);
		$mySheet->getColumnDimension('R')->setWidth(9);

		//Begin with  new Worksheet (Branch wise summary)-----------------------------------------------------
		$pageindex++;
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($pageindex);
	
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet();
		
		//Set the worksheet name
		$mySheet->setTitle('Product Matrix - Branch Wise');
		
		//Begin writing First product wise summary
		$currentrow = 1;
		
		//Select distinct branch and loop it for branch wise summary
		$querybranch = "select distinct branch from cross_products order by branch";
		$resultbranch = runmysqlquery($querybranch);
		while($fetchbranch = mysqli_fetch_array($resultbranch))
		{
			//Give 3 empty rows
			if($currentrow > 1)
			$currentrow = $currentrow + 3;
			
			//Set heading
			$mySheet->setCellValue('A'.$currentrow,$fetchbranch['branch'].' | Product Matrix');
			$currentrow++;
			$mySheet->setCellValue('B'.$currentrow,'Made');
			
			$currentrow++;
			//Set table headings
			$mySheet->setCellValue('B'.$currentrow,'Product Group')
					->setCellValue('C'.$currentrow,'Total')
					->setCellValue('D'.$currentrow,$productgroups[0])
					->setCellValue('E'.$currentrow,$productgroups[1])
					->setCellValue('F'.$currentrow,$productgroups[2])
					->setCellValue('G'.$currentrow,$productgroups[3])
					->setCellValue('H'.$currentrow,$productgroups[4])
					->setCellValue('I'.$currentrow,$productgroups[5]);
					
			//Apply style for header Row
			$mySheet->getStyle('B'.$currentrow.':I'.$currentrow)->applyFromArray($styleArray);
			
			//Set table data
			$currentrow++;
			
			//Insert data
			$databeginrow = $currentrow;
			for($i = 0; $i < count($productgroups); $i++)
			{
				$query = "select '".$productgroups[$i]."' as productgroup, count(*) as total, count(products like concat('%".$productgroups[0]."%') OR NULL) as tds, count(products like concat('%".$productgroups[1]."%') OR NULL) as sto, count(products like concat('%".$productgroups[2]."%') OR NULL) as spp, count(products like concat('%".$productgroups[3]."%') OR NULL) as sai, count(products like concat('%".$productgroups[4]."%') OR NULL) as svh, count(products like concat('%".$productgroups[5]."%') OR NULL) as svi  from cross_products where products like '%".$productgroups[$i]."%' and branch = '".$fetchbranch['branch']."'";
				$result = runmysqlquery($query);
				$fetch = mysqli_fetch_array($result);
				$mySheet->setCellValue('B'.$currentrow,$fetch['productgroup'])
						->setCellValue('C'.$currentrow,$fetch['total'])
						->setCellValue('D'.$currentrow,$fetch['tds'])
						->setCellValue('E'.$currentrow,$fetch['sto'])
						->setCellValue('F'.$currentrow,$fetch['spp'])
						->setCellValue('G'.$currentrow,$fetch['sai'])
						->setCellValue('H'.$currentrow,$fetch['svh'])
						->setCellValue('I'.$currentrow,$fetch['svi']);
				$currentrow++;
			}
			
			//Apply style for Content row
			$mySheet->getStyle('B'.$databeginrow.':I'.($currentrow-1))->applyFromArray($styleArrayContent);
		}
		
		//set the default width for column
		$mySheet->getColumnDimension('A')->setWidth(15);
		$mySheet->getColumnDimension('B')->setWidth(15);
		$mySheet->getColumnDimension('C')->setWidth(9);
		$mySheet->getColumnDimension('D')->setWidth(9);
		$mySheet->getColumnDimension('E')->setWidth(9);
		$mySheet->getColumnDimension('F')->setWidth(9);
		$mySheet->getColumnDimension('G')->setWidth(9);
		$mySheet->getColumnDimension('H')->setWidth(9);
		$mySheet->getColumnDimension('I')->setWidth(9);

		//Begin writing First product wise summary
		$currentrow = 1;
		
		//Select distinct branchs and loop it for branch wise summary
		$querybranch = "select distinct branch from cross_products order by branch";
		$resultbranch = runmysqlquery($querybranch);
		while($fetchbranch = mysqli_fetch_array($resultbranch))
		{
			//Give 3 empty rows
			if($currentrow > 1)
			$currentrow = $currentrow + 3;

			//Set heading
			//$mySheet->setCellValue('J'.$currentrow,'Product Matrix');
			$currentrow++;
			$mySheet->setCellValue('K'.$currentrow,'Not Made');
			
			$currentrow++;
			//Set table headings
			$mySheet->setCellValue('K'.$currentrow,'Product Group')
					->setCellValue('L'.$currentrow,'Total')
					->setCellValue('M'.$currentrow,$productgroups[0])
					->setCellValue('N'.$currentrow,$productgroups[1])
					->setCellValue('O'.$currentrow,$productgroups[2])
					->setCellValue('P'.$currentrow,$productgroups[3])
					->setCellValue('Q'.$currentrow,$productgroups[4])
					->setCellValue('R'.$currentrow,$productgroups[5]);
					
			//Apply style for header Row
			$mySheet->getStyle('K'.$currentrow.':R'.$currentrow)->applyFromArray($styleArray);
			
			//Set table data
			$currentrow++;
			
			//Insert data
			$databeginrow = $currentrow;
			for($i = 0; $i < count($productgroups); $i++)
			{
				$query = "select '".$productgroups[$i]."' as productgroup, count(*) as total, count(products not like concat('%".$productgroups[0]."%') OR NULL) as tds, count(products not like concat('%".$productgroups[1]."%') OR NULL) as sto, count(products not like concat('%".$productgroups[2]."%') OR NULL) as spp, count(products not like concat('%".$productgroups[3]."%') OR NULL) as sai, count(products not like concat('%".$productgroups[4]."%') OR NULL) as svh, count(products not like concat('%".$productgroups[5]."%') OR NULL) as svi  from cross_products where products like '%".$productgroups[$i]."%' and branch = '".$fetchbranch['branch']."'";
				$result = runmysqlquery($query);
				$fetch = mysqli_fetch_array($result);
				$mySheet->setCellValue('K'.$currentrow,$fetch['productgroup'])
						->setCellValue('L'.$currentrow,$fetch['total'])
						->setCellValue('M'.$currentrow,$fetch['tds'])
						->setCellValue('N'.$currentrow,$fetch['sto'])
						->setCellValue('O'.$currentrow,$fetch['spp'])
						->setCellValue('P'.$currentrow,$fetch['sai'])
						->setCellValue('Q'.$currentrow,$fetch['svh'])
						->setCellValue('R'.$currentrow,$fetch['svi']);
				$currentrow++;
			}
			
			//Apply style for Content row
			$mySheet->getStyle('K'.$databeginrow.':R'.($currentrow-1))->applyFromArray($styleArrayContent);
		}
		
		//set the default width for column
		//$mySheet->getColumnDimension('A')->setWidth(15);
		$mySheet->getColumnDimension('K')->setWidth(15);
		$mySheet->getColumnDimension('L')->setWidth(9);
		$mySheet->getColumnDimension('M')->setWidth(9);
		$mySheet->getColumnDimension('N')->setWidth(9);
		$mySheet->getColumnDimension('O')->setWidth(9);
		$mySheet->getColumnDimension('P')->setWidth(9);
		$mySheet->getColumnDimension('Q')->setWidth(9);
		$mySheet->getColumnDimension('R')->setWidth(9);
		
		//Begin with  new Worksheet (Category wise summary)-----------------------------------------------------
		$pageindex++;
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($pageindex);
	
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet();
		
		//Set the worksheet name
		$mySheet->setTitle('Product Matrix - Category Wise');
		
		//Begin writing First product wise summary
		$currentrow = 1;
		
		//Select distinct businesstype and loop it for businesstype wise summary
		$querybusinesstype = "select distinct businesstype from cross_products order by businesstype";
		$resultbusinesstype = runmysqlquery($querybusinesstype);
		while($fetchbusinesstype = mysqli_fetch_array($resultbusinesstype))
		{
			//Give 3 empty rows
			if($currentrow > 1)
			$currentrow = $currentrow + 3;
			
			//Set heading
			$mySheet->setCellValue('A'.$currentrow,$fetchbusinesstype['businesstype'].' | Product Matrix');
			$currentrow++;
			$mySheet->setCellValue('B'.$currentrow,'Made');
			
			$currentrow++;
			//Set table headings
			$mySheet->setCellValue('B'.$currentrow,'Product Group')
					->setCellValue('C'.$currentrow,'Total')
					->setCellValue('D'.$currentrow,$productgroups[0])
					->setCellValue('E'.$currentrow,$productgroups[1])
					->setCellValue('F'.$currentrow,$productgroups[2])
					->setCellValue('G'.$currentrow,$productgroups[3])
					->setCellValue('H'.$currentrow,$productgroups[4])
					->setCellValue('I'.$currentrow,$productgroups[5]);
					
			//Apply style for header Row
			$mySheet->getStyle('B'.$currentrow.':I'.$currentrow)->applyFromArray($styleArray);
			
			//Set table data
			$currentrow++;
			
			//Insert data
			$databeginrow = $currentrow;
			for($i = 0; $i < count($productgroups); $i++)
			{
				$query = "select '".$productgroups[$i]."' as productgroup, count(*) as total, count(products like concat('%".$productgroups[0]."%') OR NULL) as tds, count(products like concat('%".$productgroups[1]."%') OR NULL) as sto, count(products like concat('%".$productgroups[2]."%') OR NULL) as spp, count(products like concat('%".$productgroups[3]."%') OR NULL) as sai, count(products like concat('%".$productgroups[4]."%') OR NULL) as svh, count(products like concat('%".$productgroups[5]."%') OR NULL) as svi  from cross_products where products like '%".$productgroups[$i]."%' and businesstype = '".$fetchbusinesstype['businesstype']."'";
				$result = runmysqlquery($query);
				$fetch = mysqli_fetch_array($result);
				$mySheet->setCellValue('B'.$currentrow,$fetch['productgroup'])
						->setCellValue('C'.$currentrow,$fetch['total'])
						->setCellValue('D'.$currentrow,$fetch['tds'])
						->setCellValue('E'.$currentrow,$fetch['sto'])
						->setCellValue('F'.$currentrow,$fetch['spp'])
						->setCellValue('G'.$currentrow,$fetch['sai'])
						->setCellValue('H'.$currentrow,$fetch['svh'])
						->setCellValue('I'.$currentrow,$fetch['svi']);
				$currentrow++;
			}
			
			//Apply style for Content row
			$mySheet->getStyle('B'.$databeginrow.':I'.($currentrow-1))->applyFromArray($styleArrayContent);
		}
		
		//set the default width for column
		$mySheet->getColumnDimension('A')->setWidth(15);
		$mySheet->getColumnDimension('B')->setWidth(15);
		$mySheet->getColumnDimension('C')->setWidth(9);
		$mySheet->getColumnDimension('D')->setWidth(9);
		$mySheet->getColumnDimension('E')->setWidth(9);
		$mySheet->getColumnDimension('F')->setWidth(9);
		$mySheet->getColumnDimension('G')->setWidth(9);
		$mySheet->getColumnDimension('H')->setWidth(9);
		$mySheet->getColumnDimension('I')->setWidth(9);

		//Begin writing First product wise summary
		$currentrow = 1;
		
		//Select distinct businesstypes and loop it for businesstype wise summary
		$querybusinesstype = "select distinct businesstype from cross_products order by businesstype";
		$resultbusinesstype = runmysqlquery($querybusinesstype);
		while($fetchbusinesstype = mysqli_fetch_array($resultbusinesstype))
		{
			//Give 3 empty rows
			if($currentrow > 1)
			$currentrow = $currentrow + 3;

			//Set heading
			//$mySheet->setCellValue('J'.$currentrow,'Product Matrix');
			$currentrow++;
			$mySheet->setCellValue('K'.$currentrow,'Not Made');
			
			$currentrow++;
			//Set table headings
			$mySheet->setCellValue('K'.$currentrow,'Product Group')
					->setCellValue('L'.$currentrow,'Total')
					->setCellValue('M'.$currentrow,$productgroups[0])
					->setCellValue('N'.$currentrow,$productgroups[1])
					->setCellValue('O'.$currentrow,$productgroups[2])
					->setCellValue('P'.$currentrow,$productgroups[3])
					->setCellValue('Q'.$currentrow,$productgroups[4])
					->setCellValue('R'.$currentrow,$productgroups[5]);
					
			//Apply style for header Row
			$mySheet->getStyle('K'.$currentrow.':R'.$currentrow)->applyFromArray($styleArray);
			
			//Set table data
			$currentrow++;
			
			//Insert data
			$databeginrow = $currentrow;
			for($i = 0; $i < count($productgroups); $i++)
			{
				$query = "select '".$productgroups[$i]."' as productgroup, count(*) as total, count(products not like concat('%".$productgroups[0]."%') OR NULL) as tds, count(products not like concat('%".$productgroups[1]."%') OR NULL) as sto, count(products not like concat('%".$productgroups[2]."%') OR NULL) as spp, count(products not like concat('%".$productgroups[3]."%') OR NULL) as sai, count(products not like concat('%".$productgroups[4]."%') OR NULL) as svh, count(products not like concat('%".$productgroups[5]."%') OR NULL) as svi  from cross_products where products like '%".$productgroups[$i]."%' and businesstype = '".$fetchbusinesstype['businesstype']."'";
				$result = runmysqlquery($query);
				$fetch = mysqli_fetch_array($result);
				$mySheet->setCellValue('K'.$currentrow,$fetch['productgroup'])
						->setCellValue('L'.$currentrow,$fetch['total'])
						->setCellValue('M'.$currentrow,$fetch['tds'])
						->setCellValue('N'.$currentrow,$fetch['sto'])
						->setCellValue('O'.$currentrow,$fetch['spp'])
						->setCellValue('P'.$currentrow,$fetch['sai'])
						->setCellValue('Q'.$currentrow,$fetch['svh'])
						->setCellValue('R'.$currentrow,$fetch['svi']);
				$currentrow++;
			}
			
			//Apply style for Content row
			$mySheet->getStyle('K'.$databeginrow.':R'.($currentrow-1))->applyFromArray($styleArrayContent);
		}
		
		//set the default width for column
		//$mySheet->getColumnDimension('A')->setWidth(15);
		$mySheet->getColumnDimension('K')->setWidth(15);
		$mySheet->getColumnDimension('L')->setWidth(9);
		$mySheet->getColumnDimension('M')->setWidth(9);
		$mySheet->getColumnDimension('N')->setWidth(9);
		$mySheet->getColumnDimension('O')->setWidth(9);
		$mySheet->getColumnDimension('P')->setWidth(9);
		$mySheet->getColumnDimension('Q')->setWidth(9);
		$mySheet->getColumnDimension('R')->setWidth(9);
	}
	/*--------------------------Product Matrix (Product to Type)---------------------------------------*/
	elseif($report_type == "report_matrix_productwise")
	{
		//Define product group array
		$productgroups = array("TDS","STO","SPP","SAC","SVH","SVI");
		
		for($i = 0; $i < count($productgroups); $i++)
		{
			//Add the worksheet, only if it is from second time (For first time, it has got created initially)
			if($i > 0)
			{
				$pageindex++;
				$objPHPExcel->createSheet();
				$objPHPExcel->setActiveSheetIndex($pageindex);
			}
		
			//Set Active Sheet	
			$mySheet = $objPHPExcel->getActiveSheet();
		
			//Set the worksheet name
			$mySheet->setTitle($productgroups[$i]);
			
			//Begin writing Region wise summary
			$currentrow = 1;
			
			//Set heading for Region wise summary
			$mySheet->setCellValue('A'.$currentrow,'Region wise summary');
			$currentrow++;
			$mySheet->setCellValue('B'.$currentrow,'Made');

			$currentrow++;
			//Set table headings
			$mySheet->setCellValue('B'.$currentrow,'Region')
					->setCellValue('C'.$currentrow,'Total')
					->setCellValue('D'.$currentrow,$productgroups[0])
					->setCellValue('E'.$currentrow,$productgroups[1])
					->setCellValue('F'.$currentrow,$productgroups[2])
					->setCellValue('G'.$currentrow,$productgroups[3])
					->setCellValue('H'.$currentrow,$productgroups[4])
					->setCellValue('I'.$currentrow,$productgroups[5]);
					
			//Apply style for header Row
			$mySheet->getStyle('B'.$currentrow.':I'.$currentrow)->applyFromArray($styleArray);
			
			//Set table data
			$currentrow++;
			$query = "select region, count(*) as total, count(products like concat('%".$productgroups[0]."%') OR NULL) as tds, count(products like concat('%".$productgroups[1]."%') OR NULL) as sto, count(products like concat('%".$productgroups[2]."%') OR NULL) as spp, count(products like concat('%".$productgroups[3]."%') OR NULL) as sai, count(products like concat('%".$productgroups[4]."%') OR NULL) as svh, count(products like concat('%".$productgroups[5]."%') OR NULL) as svi  from cross_products where products like '%".$productgroups[$i]."%' group by region";
			$result = runmysqlquery($query);
			
			//Insert data
			$databeginrow = $currentrow;
			while($fetch = mysqli_fetch_array($result))
			{
				$mySheet->setCellValue('B'.$currentrow,$fetch['region'])
						->setCellValue('C'.$currentrow,$fetch['total'])
						->setCellValue('D'.$currentrow,$fetch['tds'])
						->setCellValue('E'.$currentrow,$fetch['sto'])
						->setCellValue('F'.$currentrow,$fetch['spp'])
						->setCellValue('G'.$currentrow,$fetch['sai'])
						->setCellValue('H'.$currentrow,$fetch['svh'])
						->setCellValue('I'.$currentrow,$fetch['svi']);
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
					->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")");
			$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
		
			//Apply style for content Row
			$mySheet->getStyle('B'.$databeginrow.':I'.$currentrow)->applyFromArray($styleArrayContent);
			
			//Begin writing Branch wise summary-------------------------------------------------------
			$currentrow = $currentrow + 3;
			
			//Set heading summary
			$mySheet->setCellValue('A'.$currentrow,'Branch wise summary');
			$currentrow++;
			$mySheet->setCellValue('B'.$currentrow,'Made');

			$currentrow++;
			//Set table headings
			$mySheet->setCellValue('B'.$currentrow,'Branch')
					->setCellValue('C'.$currentrow,'Total')
					->setCellValue('D'.$currentrow,$productgroups[0])
					->setCellValue('E'.$currentrow,$productgroups[1])
					->setCellValue('F'.$currentrow,$productgroups[2])
					->setCellValue('G'.$currentrow,$productgroups[3])
					->setCellValue('H'.$currentrow,$productgroups[4])
					->setCellValue('I'.$currentrow,$productgroups[5]);
					
			//Apply style for header Row
			$mySheet->getStyle('B'.$currentrow.':I'.$currentrow)->applyFromArray($styleArray);
			
			//Set table data
			$currentrow++;
			$query = "select branch, count(*) as total, count(products like concat('%".$productgroups[0]."%') OR NULL) as tds, count(products like concat('%".$productgroups[1]."%') OR NULL) as sto, count(products like concat('%".$productgroups[2]."%') OR NULL) as spp, count(products like concat('%".$productgroups[3]."%') OR NULL) as sai, count(products like concat('%".$productgroups[4]."%') OR NULL) as svh, count(products like concat('%".$productgroups[5]."%') OR NULL) as svi  from cross_products where products like '%".$productgroups[$i]."%' group by branch";
			$result = runmysqlquery($query);
			
			//Insert data
			$databeginrow = $currentrow;
			while($fetch = mysqli_fetch_array($result))
			{
				$mySheet->setCellValue('B'.$currentrow,$fetch['branch'])
						->setCellValue('C'.$currentrow,$fetch['total'])
						->setCellValue('D'.$currentrow,$fetch['tds'])
						->setCellValue('E'.$currentrow,$fetch['sto'])
						->setCellValue('F'.$currentrow,$fetch['spp'])
						->setCellValue('G'.$currentrow,$fetch['sai'])
						->setCellValue('H'.$currentrow,$fetch['svh'])
						->setCellValue('I'.$currentrow,$fetch['svi']);
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
					->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")");
			$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
		
			//Apply style for content Row
			$mySheet->getStyle('B'.$databeginrow.':I'.$currentrow)->applyFromArray($styleArrayContent);
		
			//Begin writing Category wise summary-------------------------------------------------------
			$currentrow = $currentrow + 3;
			
			//Set heading summary
			$mySheet->setCellValue('A'.$currentrow,'Category wise summary');
			$currentrow++;
			$mySheet->setCellValue('B'.$currentrow,'Made');

			$currentrow++;
			//Set table headings
			$mySheet->setCellValue('B'.$currentrow,'Category')
					->setCellValue('C'.$currentrow,'Total')
					->setCellValue('D'.$currentrow,$productgroups[0])
					->setCellValue('E'.$currentrow,$productgroups[1])
					->setCellValue('F'.$currentrow,$productgroups[2])
					->setCellValue('G'.$currentrow,$productgroups[3])
					->setCellValue('H'.$currentrow,$productgroups[4])
					->setCellValue('I'.$currentrow,$productgroups[5]);
					
			//Apply style for header Row
			$mySheet->getStyle('B'.$currentrow.':I'.$currentrow)->applyFromArray($styleArray);
			
			//Set table data
			$currentrow++;
			$query = "select businesstype, count(*) as total, count(products like concat('%".$productgroups[0]."%') OR NULL) as tds, count(products like concat('%".$productgroups[1]."%') OR NULL) as sto, count(products like concat('%".$productgroups[2]."%') OR NULL) as spp, count(products like concat('%".$productgroups[3]."%') OR NULL) as sai, count(products like concat('%".$productgroups[4]."%') OR NULL) as svh, count(products like concat('%".$productgroups[5]."%') OR NULL) as svi  from cross_products where products like '%".$productgroups[$i]."%' group by businesstype";
			$result = runmysqlquery($query);
			
			//Insert data
			$databeginrow = $currentrow;
			while($fetch = mysqli_fetch_array($result))
			{
				$mySheet->setCellValue('B'.$currentrow,$fetch['businesstype'])
						->setCellValue('C'.$currentrow,$fetch['total'])
						->setCellValue('D'.$currentrow,$fetch['tds'])
						->setCellValue('E'.$currentrow,$fetch['sto'])
						->setCellValue('F'.$currentrow,$fetch['spp'])
						->setCellValue('G'.$currentrow,$fetch['sai'])
						->setCellValue('H'.$currentrow,$fetch['svh'])
						->setCellValue('I'.$currentrow,$fetch['svi']);
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
					->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")");
			$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
		
			//Apply style for content Row
			$mySheet->getStyle('B'.$databeginrow.':I'.$currentrow)->applyFromArray($styleArrayContent);

			//Begin writing State wise summary-------------------------------------------------------
			$currentrow = $currentrow + 3;
			
			//Set heading summary
			$mySheet->setCellValue('A'.$currentrow,'State wise summary');
			$currentrow++;
			$mySheet->setCellValue('B'.$currentrow,'Made');

			$currentrow++;
			//Set table headings
			$mySheet->setCellValue('B'.$currentrow,'State')
					->setCellValue('C'.$currentrow,'Total')
					->setCellValue('D'.$currentrow,$productgroups[0])
					->setCellValue('E'.$currentrow,$productgroups[1])
					->setCellValue('F'.$currentrow,$productgroups[2])
					->setCellValue('G'.$currentrow,$productgroups[3])
					->setCellValue('H'.$currentrow,$productgroups[4])
					->setCellValue('I'.$currentrow,$productgroups[5]);
					
			//Apply style for header Row
			$mySheet->getStyle('B'.$currentrow.':I'.$currentrow)->applyFromArray($styleArray);
			
			//Set table data
			$currentrow++;
			$query = "select statename, count(*) as total, count(products like concat('%".$productgroups[0]."%') OR NULL) as tds, count(products like concat('%".$productgroups[1]."%') OR NULL) as sto, count(products like concat('%".$productgroups[2]."%') OR NULL) as spp, count(products like concat('%".$productgroups[3]."%') OR NULL) as sai, count(products like concat('%".$productgroups[4]."%') OR NULL) as svh, count(products like concat('%".$productgroups[5]."%') OR NULL) as svi  from cross_products where products like '%".$productgroups[$i]."%' group by statename";
			$result = runmysqlquery($query);
			
			//Insert data
			$databeginrow = $currentrow;
			while($fetch = mysqli_fetch_array($result))
			{
				$mySheet->setCellValue('B'.$currentrow,$fetch['statename'])
						->setCellValue('C'.$currentrow,$fetch['total'])
						->setCellValue('D'.$currentrow,$fetch['tds'])
						->setCellValue('E'.$currentrow,$fetch['sto'])
						->setCellValue('F'.$currentrow,$fetch['spp'])
						->setCellValue('G'.$currentrow,$fetch['sai'])
						->setCellValue('H'.$currentrow,$fetch['svh'])
						->setCellValue('I'.$currentrow,$fetch['svi']);
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
					->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")");
			$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
		
			//Apply style for content Row
			$mySheet->getStyle('B'.$databeginrow.':I'.$currentrow)->applyFromArray($styleArrayContent);

			//Begin writing Dealer wise summary-------------------------------------------------------
			$currentrow = $currentrow + 3;
			
			//Set heading summary
			$mySheet->setCellValue('A'.$currentrow,'Dealer wise summary');
			$currentrow++;
			$mySheet->setCellValue('B'.$currentrow,'Made');

			$currentrow++;
			//Set table headings
			$mySheet->setCellValue('B'.$currentrow,'Dealer')
					->setCellValue('C'.$currentrow,'Total')
					->setCellValue('D'.$currentrow,$productgroups[0])
					->setCellValue('E'.$currentrow,$productgroups[1])
					->setCellValue('F'.$currentrow,$productgroups[2])
					->setCellValue('G'.$currentrow,$productgroups[3])
					->setCellValue('H'.$currentrow,$productgroups[4])
					->setCellValue('I'.$currentrow,$productgroups[5]);
					
			//Apply style for header Row
			$mySheet->getStyle('B'.$currentrow.':I'.$currentrow)->applyFromArray($styleArray);
			
			//Set table data
			$currentrow++;
			$query = "select dealer, count(*) as total, count(products like concat('%".$productgroups[0]."%') OR NULL) as tds, count(products like concat('%".$productgroups[1]."%') OR NULL) as sto, count(products like concat('%".$productgroups[2]."%') OR NULL) as spp, count(products like concat('%".$productgroups[3]."%') OR NULL) as sai, count(products like concat('%".$productgroups[4]."%') OR NULL) as svh, count(products like concat('%".$productgroups[5]."%') OR NULL) as svi  from cross_products where products like '%".$productgroups[$i]."%' group by dealer";
			$result = runmysqlquery($query);
			
			//Insert data
			$databeginrow = $currentrow;
			while($fetch = mysqli_fetch_array($result))
			{
				$mySheet->setCellValue('B'.$currentrow,$fetch['dealer'])
						->setCellValue('C'.$currentrow,$fetch['total'])
						->setCellValue('D'.$currentrow,$fetch['tds'])
						->setCellValue('E'.$currentrow,$fetch['sto'])
						->setCellValue('F'.$currentrow,$fetch['spp'])
						->setCellValue('G'.$currentrow,$fetch['sai'])
						->setCellValue('H'.$currentrow,$fetch['svh'])
						->setCellValue('I'.$currentrow,$fetch['svi']);
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
					->setCellValue('I'.$currentrow,"=SUM(I".$databeginrow.":I".($currentrow-1).")");
			$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('H'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('I'.$currentrow)->getCalculatedValue();
		
			//Apply style for content Row
			$mySheet->getStyle('B'.$databeginrow.':I'.$currentrow)->applyFromArray($styleArrayContent);

			//set the default width for column
			$mySheet->getColumnDimension('A')->setWidth(20);
			$mySheet->getColumnDimension('B')->setWidth(20);
			$mySheet->getColumnDimension('C')->setWidth(10);
			$mySheet->getColumnDimension('D')->setWidth(10);
			$mySheet->getColumnDimension('E')->setWidth(10);
			$mySheet->getColumnDimension('F')->setWidth(10);
			$mySheet->getColumnDimension('G')->setWidth(10);
			$mySheet->getColumnDimension('H')->setWidth(10);
			$mySheet->getColumnDimension('I')->setWidth(10);
			
			
			//Begin writing Region wise summary-----------------------------------------
			$currentrow = 1;
			
			//Set heading for Region wise summary
			$currentrow++;
			$mySheet->setCellValue('K'.$currentrow,'Not Made');

			$currentrow++;
			//Set table headings
			$mySheet->setCellValue('K'.$currentrow,'Region')
					->setCellValue('L'.$currentrow,'Total')
					->setCellValue('M'.$currentrow,$productgroups[0])
					->setCellValue('N'.$currentrow,$productgroups[1])
					->setCellValue('O'.$currentrow,$productgroups[2])
					->setCellValue('P'.$currentrow,$productgroups[3])
					->setCellValue('Q'.$currentrow,$productgroups[4])
					->setCellValue('R'.$currentrow,$productgroups[5]);
					
			//Apply style for header Row
			$mySheet->getStyle('K'.$currentrow.':R'.$currentrow)->applyFromArray($styleArray);
			
			//Set table data
			$currentrow++;
			$query = "select region, count(*) as total, count(products not like concat('%".$productgroups[0]."%') OR NULL) as tds, count(products not like concat('%".$productgroups[1]."%') OR NULL) as sto, count(products not like concat('%".$productgroups[2]."%') OR NULL) as spp, count(products not like concat('%".$productgroups[3]."%') OR NULL) as sai, count(products not like concat('%".$productgroups[4]."%') OR NULL) as svh, count(products not like concat('%".$productgroups[5]."%') OR NULL) as svi  from cross_products where products like '%".$productgroups[$i]."%' group by region";
			$result = runmysqlquery($query);
			
			//Insert data
			$databeginrow = $currentrow;
			while($fetch = mysqli_fetch_array($result))
			{
				$mySheet->setCellValue('K'.$currentrow,$fetch['region'])
						->setCellValue('L'.$currentrow,$fetch['total'])
						->setCellValue('M'.$currentrow,$fetch['tds'])
						->setCellValue('N'.$currentrow,$fetch['sto'])
						->setCellValue('O'.$currentrow,$fetch['spp'])
						->setCellValue('P'.$currentrow,$fetch['sai'])
						->setCellValue('Q'.$currentrow,$fetch['svh'])
						->setCellValue('R'.$currentrow,$fetch['svi']);
				$currentrow++;
			}
			//Insert Total
			$mySheet->setCellValue('K'.$currentrow,'Total')
					->setCellValue('L'.$currentrow,"=SUM(L".$databeginrow.":L".($currentrow-1).")")
					->setCellValue('M'.$currentrow,"=SUM(M".$databeginrow.":M".($currentrow-1).")")
					->setCellValue('N'.$currentrow,"=SUM(N".$databeginrow.":N".($currentrow-1).")")
					->setCellValue('O'.$currentrow,"=SUM(O".$databeginrow.":O".($currentrow-1).")")
					->setCellValue('P'.$currentrow,"=SUM(P".$databeginrow.":P".($currentrow-1).")")
					->setCellValue('Q'.$currentrow,"=SUM(Q".$databeginrow.":Q".($currentrow-1).")")
					->setCellValue('R'.$currentrow,"=SUM(R".$databeginrow.":R".($currentrow-1).")");
			$mySheet->getCell('L'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('M'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('N'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('O'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('P'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('Q'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('R'.$currentrow)->getCalculatedValue();
		
			//Apply style for content Row
			$mySheet->getStyle('K'.$databeginrow.':R'.$currentrow)->applyFromArray($styleArrayContent);
			
			//Begin writing Branch wise summary-------------------------------------------------------
			$currentrow = $currentrow + 3;
			
			//Set heading summary
			$currentrow++;
			$mySheet->setCellValue('K'.$currentrow,'Not Made');

			$currentrow++;
			//Set table headings
			$mySheet->setCellValue('K'.$currentrow,'Branch')
					->setCellValue('L'.$currentrow,'Total')
					->setCellValue('M'.$currentrow,$productgroups[0])
					->setCellValue('N'.$currentrow,$productgroups[1])
					->setCellValue('O'.$currentrow,$productgroups[2])
					->setCellValue('P'.$currentrow,$productgroups[3])
					->setCellValue('Q'.$currentrow,$productgroups[4])
					->setCellValue('R'.$currentrow,$productgroups[5]);
					
			//Apply style for header Row
			$mySheet->getStyle('K'.$currentrow.':R'.$currentrow)->applyFromArray($styleArray);
			
			//Set table data
			$currentrow++;
			$query = "select branch, count(*) as total, count(products not like concat('%".$productgroups[0]."%') OR NULL) as tds, count(products not like concat('%".$productgroups[1]."%') OR NULL) as sto, count(products not like concat('%".$productgroups[2]."%') OR NULL) as spp, count(products not like concat('%".$productgroups[3]."%') OR NULL) as sai, count(products not like concat('%".$productgroups[4]."%') OR NULL) as svh, count(products not like concat('%".$productgroups[5]."%') OR NULL) as svi  from cross_products where products like '%".$productgroups[$i]."%' group by branch";
			$result = runmysqlquery($query);
			
			//Insert data
			$databeginrow = $currentrow;
			while($fetch = mysqli_fetch_array($result))
			{
				$mySheet->setCellValue('K'.$currentrow,$fetch['branch'])
						->setCellValue('L'.$currentrow,$fetch['total'])
						->setCellValue('M'.$currentrow,$fetch['tds'])
						->setCellValue('N'.$currentrow,$fetch['sto'])
						->setCellValue('O'.$currentrow,$fetch['spp'])
						->setCellValue('P'.$currentrow,$fetch['sai'])
						->setCellValue('Q'.$currentrow,$fetch['svh'])
						->setCellValue('R'.$currentrow,$fetch['svi']);
				$currentrow++;
			}
			//Insert Total
			$mySheet->setCellValue('K'.$currentrow,'Total')
					->setCellValue('L'.$currentrow,"=SUM(L".$databeginrow.":L".($currentrow-1).")")
					->setCellValue('M'.$currentrow,"=SUM(M".$databeginrow.":M".($currentrow-1).")")
					->setCellValue('N'.$currentrow,"=SUM(N".$databeginrow.":N".($currentrow-1).")")
					->setCellValue('O'.$currentrow,"=SUM(O".$databeginrow.":O".($currentrow-1).")")
					->setCellValue('P'.$currentrow,"=SUM(P".$databeginrow.":P".($currentrow-1).")")
					->setCellValue('Q'.$currentrow,"=SUM(Q".$databeginrow.":Q".($currentrow-1).")")
					->setCellValue('R'.$currentrow,"=SUM(R".$databeginrow.":R".($currentrow-1).")");
			$mySheet->getCell('L'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('M'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('N'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('O'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('P'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('Q'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('R'.$currentrow)->getCalculatedValue();
		
			//Apply style for content Row
			$mySheet->getStyle('K'.$databeginrow.':R'.$currentrow)->applyFromArray($styleArrayContent);
			
		
			//Begin writing Category wise summary-------------------------------------------------------
			$currentrow = $currentrow + 3;
			
			//Set heading summary
			$currentrow++;
			$mySheet->setCellValue('K'.$currentrow,'Not Made');

			$currentrow++;
			//Set table headings
			$mySheet->setCellValue('K'.$currentrow,'Category')
					->setCellValue('L'.$currentrow,'Total')
					->setCellValue('M'.$currentrow,$productgroups[0])
					->setCellValue('N'.$currentrow,$productgroups[1])
					->setCellValue('O'.$currentrow,$productgroups[2])
					->setCellValue('P'.$currentrow,$productgroups[3])
					->setCellValue('Q'.$currentrow,$productgroups[4])
					->setCellValue('R'.$currentrow,$productgroups[5]);
					
			//Apply style for header Row
			$mySheet->getStyle('K'.$currentrow.':R'.$currentrow)->applyFromArray($styleArray);
			
			//Set table data
			$currentrow++;
			$query = "select businesstype, count(*) as total, count(products not like concat('%".$productgroups[0]."%') OR NULL) as tds, count(products not like concat('%".$productgroups[1]."%') OR NULL) as sto, count(products not like concat('%".$productgroups[2]."%') OR NULL) as spp, count(products not like concat('%".$productgroups[3]."%') OR NULL) as sai, count(products not like concat('%".$productgroups[4]."%') OR NULL) as svh, count(products not like concat('%".$productgroups[5]."%') OR NULL) as svi  from cross_products where products like '%".$productgroups[$i]."%' group by businesstype";
			$result = runmysqlquery($query);
			
			//Insert data
			$databeginrow = $currentrow;
			while($fetch = mysqli_fetch_array($result))
			{
				$mySheet->setCellValue('K'.$currentrow,$fetch['businesstype'])
						->setCellValue('L'.$currentrow,$fetch['total'])
						->setCellValue('M'.$currentrow,$fetch['tds'])
						->setCellValue('N'.$currentrow,$fetch['sto'])
						->setCellValue('O'.$currentrow,$fetch['spp'])
						->setCellValue('P'.$currentrow,$fetch['sai'])
						->setCellValue('Q'.$currentrow,$fetch['svh'])
						->setCellValue('R'.$currentrow,$fetch['svi']);
				$currentrow++;
			}
			//Insert Total
			$mySheet->setCellValue('K'.$currentrow,'Total')
					->setCellValue('L'.$currentrow,"=SUM(L".$databeginrow.":L".($currentrow-1).")")
					->setCellValue('M'.$currentrow,"=SUM(M".$databeginrow.":M".($currentrow-1).")")
					->setCellValue('N'.$currentrow,"=SUM(N".$databeginrow.":N".($currentrow-1).")")
					->setCellValue('O'.$currentrow,"=SUM(O".$databeginrow.":O".($currentrow-1).")")
					->setCellValue('P'.$currentrow,"=SUM(P".$databeginrow.":P".($currentrow-1).")")
					->setCellValue('Q'.$currentrow,"=SUM(Q".$databeginrow.":Q".($currentrow-1).")")
					->setCellValue('R'.$currentrow,"=SUM(R".$databeginrow.":R".($currentrow-1).")");
			$mySheet->getCell('L'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('M'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('N'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('O'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('P'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('Q'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('R'.$currentrow)->getCalculatedValue();
		
			//Apply style for content Row
			$mySheet->getStyle('K'.$databeginrow.':R'.$currentrow)->applyFromArray($styleArrayContent);
			
		

			//Begin writing State wise summary-------------------------------------------------------
			$currentrow = $currentrow + 3;
			
			//Set heading summary
			$currentrow++;
			$mySheet->setCellValue('K'.$currentrow,'Not Made');

			$currentrow++;
			//Set table headings
			$mySheet->setCellValue('K'.$currentrow,'State')
					->setCellValue('L'.$currentrow,'Total')
					->setCellValue('M'.$currentrow,$productgroups[0])
					->setCellValue('N'.$currentrow,$productgroups[1])
					->setCellValue('O'.$currentrow,$productgroups[2])
					->setCellValue('P'.$currentrow,$productgroups[3])
					->setCellValue('Q'.$currentrow,$productgroups[4])
					->setCellValue('R'.$currentrow,$productgroups[5]);
					
			//Apply style for header Row
			$mySheet->getStyle('K'.$currentrow.':R'.$currentrow)->applyFromArray($styleArray);
			
			//Set table data
			$currentrow++;
			$query = "select statename, count(*) as total, count(products not like concat('%".$productgroups[0]."%') OR NULL) as tds, count(products not like concat('%".$productgroups[1]."%') OR NULL) as sto, count(products not like concat('%".$productgroups[2]."%') OR NULL) as spp, count(products not like concat('%".$productgroups[3]."%') OR NULL) as sai, count(products not like concat('%".$productgroups[4]."%') OR NULL) as svh, count(products not like concat('%".$productgroups[5]."%') OR NULL) as svi  from cross_products where products like '%".$productgroups[$i]."%' group by statename";
			$result = runmysqlquery($query);
			
			//Insert data
			$databeginrow = $currentrow;
			while($fetch = mysqli_fetch_array($result))
			{
				$mySheet->setCellValue('K'.$currentrow,$fetch['statename'])
						->setCellValue('L'.$currentrow,$fetch['total'])
						->setCellValue('M'.$currentrow,$fetch['tds'])
						->setCellValue('N'.$currentrow,$fetch['sto'])
						->setCellValue('O'.$currentrow,$fetch['spp'])
						->setCellValue('P'.$currentrow,$fetch['sai'])
						->setCellValue('Q'.$currentrow,$fetch['svh'])
						->setCellValue('R'.$currentrow,$fetch['svi']);
				$currentrow++;
			}
			//Insert Total
			$mySheet->setCellValue('K'.$currentrow,'Total')
					->setCellValue('L'.$currentrow,"=SUM(L".$databeginrow.":L".($currentrow-1).")")
					->setCellValue('M'.$currentrow,"=SUM(M".$databeginrow.":M".($currentrow-1).")")
					->setCellValue('N'.$currentrow,"=SUM(N".$databeginrow.":N".($currentrow-1).")")
					->setCellValue('O'.$currentrow,"=SUM(O".$databeginrow.":O".($currentrow-1).")")
					->setCellValue('P'.$currentrow,"=SUM(P".$databeginrow.":P".($currentrow-1).")")
					->setCellValue('Q'.$currentrow,"=SUM(Q".$databeginrow.":Q".($currentrow-1).")")
					->setCellValue('R'.$currentrow,"=SUM(R".$databeginrow.":R".($currentrow-1).")");
			$mySheet->getCell('L'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('M'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('N'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('O'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('P'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('Q'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('R'.$currentrow)->getCalculatedValue();
		
			//Apply style for content Row
			$mySheet->getStyle('K'.$databeginrow.':R'.$currentrow)->applyFromArray($styleArrayContent);
			

			//Begin writing Dealer wise summary-------------------------------------------------------
			$currentrow = $currentrow + 3;
			
			//Set heading summary
			$currentrow++;
			$mySheet->setCellValue('K'.$currentrow,'Not Made');

			$currentrow++;
			//Set table headings
			$mySheet->setCellValue('K'.$currentrow,'Dealer')
					->setCellValue('L'.$currentrow,'Total')
					->setCellValue('M'.$currentrow,$productgroups[0])
					->setCellValue('N'.$currentrow,$productgroups[1])
					->setCellValue('O'.$currentrow,$productgroups[2])
					->setCellValue('P'.$currentrow,$productgroups[3])
					->setCellValue('Q'.$currentrow,$productgroups[4])
					->setCellValue('R'.$currentrow,$productgroups[5]);
					
			//Apply style for header Row
			$mySheet->getStyle('K'.$currentrow.':R'.$currentrow)->applyFromArray($styleArray);
			
			//Set table data
			$currentrow++;
			$query = "select dealer, count(*) as total, count(products not like concat('%".$productgroups[0]."%') OR NULL) as tds, count(products not like concat('%".$productgroups[1]."%') OR NULL) as sto, count(products not like concat('%".$productgroups[2]."%') OR NULL) as spp, count(products not like concat('%".$productgroups[3]."%') OR NULL) as sai, count(products not like concat('%".$productgroups[4]."%') OR NULL) as svh, count(products not like concat('%".$productgroups[5]."%') OR NULL) as svi  from cross_products where products like '%".$productgroups[$i]."%' group by dealer";
			$result = runmysqlquery($query);
			
			//Insert data
			$databeginrow = $currentrow;
			while($fetch = mysqli_fetch_array($result))
			{
				$mySheet->setCellValue('K'.$currentrow,$fetch['dealer'])
						->setCellValue('L'.$currentrow,$fetch['total'])
						->setCellValue('M'.$currentrow,$fetch['tds'])
						->setCellValue('N'.$currentrow,$fetch['sto'])
						->setCellValue('O'.$currentrow,$fetch['spp'])
						->setCellValue('P'.$currentrow,$fetch['sai'])
						->setCellValue('Q'.$currentrow,$fetch['svh'])
						->setCellValue('R'.$currentrow,$fetch['svi']);
				$currentrow++;
			}
			//Insert Total
			$mySheet->setCellValue('K'.$currentrow,'Total')
					->setCellValue('L'.$currentrow,"=SUM(L".$databeginrow.":L".($currentrow-1).")")
					->setCellValue('M'.$currentrow,"=SUM(M".$databeginrow.":M".($currentrow-1).")")
					->setCellValue('N'.$currentrow,"=SUM(N".$databeginrow.":N".($currentrow-1).")")
					->setCellValue('O'.$currentrow,"=SUM(O".$databeginrow.":O".($currentrow-1).")")
					->setCellValue('P'.$currentrow,"=SUM(P".$databeginrow.":P".($currentrow-1).")")
					->setCellValue('Q'.$currentrow,"=SUM(Q".$databeginrow.":Q".($currentrow-1).")")
					->setCellValue('R'.$currentrow,"=SUM(R".$databeginrow.":R".($currentrow-1).")");
			$mySheet->getCell('L'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('M'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('N'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('O'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('P'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('Q'.$currentrow)->getCalculatedValue();
			$mySheet->getCell('R'.$currentrow)->getCalculatedValue();
		
			//Apply style for content Row
			$mySheet->getStyle('K'.$databeginrow.':R'.$currentrow)->applyFromArray($styleArrayContent);
			

			//set the default width for column
			$mySheet->getColumnDimension('K')->setWidth(20);
			$mySheet->getColumnDimension('L')->setWidth(10);
			$mySheet->getColumnDimension('M')->setWidth(10);
			$mySheet->getColumnDimension('N')->setWidth(10);
			$mySheet->getColumnDimension('O')->setWidth(10);
			$mySheet->getColumnDimension('P')->setWidth(10);
			$mySheet->getColumnDimension('Q')->setWidth(10);
			$mySheet->getColumnDimension('R')->setWidth(10);
			
		}
	}
	/*--------------------------Cross Domain Sales---------------------------------------*/
	elseif($report_type == "report_domain")
	{
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet();
		
		//Set the worksheet name
		$mySheet->setTitle('Cross Domain Sales');
		
		//Begin writing First product wise summary
		$currentrow = 1;
		
		//Set heading summary
		$mySheet->setCellValue('A'.$currentrow,'Cross domain sales');
		
		$currentrow++;
		//Set table headings
		$mySheet->setCellValue('B'.$currentrow,'Domain')
				->setCellValue('C'.$currentrow,'Cross Sales made')
				->setCellValue('D'.$currentrow,'Not made')
				->setCellValue('E'.$currentrow,'Total');
				
		//Apply style for header Row
		$mySheet->getStyle('B'.$currentrow.':E'.$currentrow)->applyFromArray($styleArray);
		
		//Set table data
		$currentrow++;
		$query = "select count((product2 <> '' OR NULL) AND (product1 = 'SPP' OR NULL))  as product1, count((product2 = '' OR NOT NULL) AND (product1 = 'SPP' OR NULL))  as product2,count((product2 = '' OR NOT NULL) AND (product1 <> 'SPP' OR  NOT NULL)) as product3, count((product2 <> '' OR  NULL) AND (product1 <> 'SPP' OR NOT NULL)) as product4 from cross_products;";
		$result = runmysqlquery($query);
		
		//Insert data
		$databeginrow = $currentrow;
	
		while($row_data = mysqli_fetch_array($result))
		{
			$mySheet->setCellValue('B'.$currentrow,'Payroll Customers')
					->setCellValue('C'.$currentrow,$row_data['product1'])
					->setCellValue('D'.$currentrow,$row_data['product2'])
					->setCellValue('E'.$currentrow,"=SUM(C".$currentrow.":D".$currentrow.")");
			$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
			$currentrow++;
					$mySheet->setCellValue('B'.$currentrow,'Other Customers')
					->setCellValue('C'.$currentrow,$row_data['product3'])
					->setCellValue('D'.$currentrow,$row_data['product4'])
					->setCellValue('E'.$currentrow,"=SUM(C".$currentrow.":D".$currentrow.")");
			$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
			$currentrow++;
		}
		//Insert Total
		$mySheet->setCellValue('B'.$currentrow,'Total')
				->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
				->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
				->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")");
		$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
	
	
		//Apply style for content Row
		$mySheet->getStyle('B'.$databeginrow.':E'.$currentrow)->applyFromArray($styleArrayContent);
		
		//set the default width for column
		$mySheet->getColumnDimension('A')->setWidth(25);
		$mySheet->getColumnDimension('B')->setWidth(17);
		$mySheet->getColumnDimension('C')->setWidth(12);
		$mySheet->getColumnDimension('D')->setWidth(12);
		$mySheet->getColumnDimension('E')->setWidth(12);
	}
	/*--------------------------Year wise Comparism---------------------------------------*/
	if($report_type == "report_yearwise")
	{
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet();
		
		//Set the worksheet name
		$mySheet->setTitle('Statistics');
		
		//Begin writing Region wise summary
		$currentrow = 1;
		
		//Set heading for Region wise summary
		$mySheet->setCellValue('A'.$currentrow,'Region wise summary');
		$mySheet->getStyle('A'.$currentrow)->getFont()->setBold(true); 
		$mySheet->getStyle('A'.$currentrow)->getFont()->setSize(13); 
		
		$databeginrow = $currentrow;
		
		//fetch the count of the year
		$productyearcount = count($productyear);
		for($i=0;$i<$productyearcount;$i++)
		{
			
			$query = "select regionlist.regionname as region,IFNULL(dummy.product1,'0') as product1,
IFNULL(dummy.product2,'0') as product2,IFNULL(dummy.product3,'0') as product3 from 
(select distinct inv_mas_region.slno as slno, inv_mas_region.category as regionname from inv_mas_customer 
left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
 order by inv_mas_region.category) as regionlist left join (SELECT  region,count((product1 <> '' or null) and (product2 = '' or null) and (product3 = '' or null))as product1,count((product2 <> '' or null) and (product3 = '' or null)) as product2, count(product3 <> '' or null) as product3  FROM cross_products".str_replace('-','',$productyear[$i])." group by  region order by region) as dummy on 
dummy.region = regionlist.regionname order by regionlist.regionname;";
			$result23 = runmysqlquery($query);
			if($i == 0)
				$colcount = 0;
			else if($i == 1)
				$colcount = $colcount + 4;
			else
				$colcount = $colcount + 3;
			
			
			$rowcount =  $databeginrow + 4;
			$assignrowcount =  $databeginrow + 4;
			$temprowcount = $rowcount;
			while($row_data = mysqli_fetch_row($result23))
			{
				if($i==0)
					$rowcountvalue = count($row_data);
				else
					$rowcountvalue = (count($row_data)-1);
					
				//echo(count($row_data)); break;
				$countres = $colcount;
				for($j = 0;$j < $rowcountvalue;$j++)
				{
					//Insert data
					if($i == 0)
					{
						$mySheet->setCellValueByColumnAndRow($countres, $rowcount, $row_data[$j]);
						$mySheet->getStyleByColumnAndRow($countres,$rowcount)->applyFromArray($styleArrayContent);
						$mySheet->getStyleByColumnAndRow($countres, ($temprowcount-1))->applyFromArray($styleArray);
						$mySheet->getStyleByColumnAndRow($countres+1,($temprowcount-2))->applyFromArray($styleArrayContent);
			
					}
					else
					{
					  $mySheet->setCellValueByColumnAndRow(($countres), $rowcount, $row_data[$j+1]);
					  $mySheet->getStyleByColumnAndRow($countres,$rowcount)->applyFromArray($styleArrayContent);
					  $mySheet->getStyleByColumnAndRow($countres, ($temprowcount-1))->applyFromArray($styleArray);
					  $mySheet->getStyleByColumnAndRow($countres,($temprowcount-2))->applyFromArray($styleArrayContent);
					}
					$countres++;
				}
				
				$rowcount++;
				
				if($i == 0)
				{
					$mySheet->mergeCellsByColumnAndRow((($countres)-3),($temprowcount-2),($countres-1),3);	
					$mySheet->setCellValueByColumnAndRow((($countres)-3),($temprowcount-2),$productyear[$i]);
					$mySheet->getStyleByColumnAndRow((($countres)-3),($temprowcount-2))->getFont()->setBold(true);
					$mySheet->getStyleByColumnAndRow((($countres)-3),($temprowcount-2))->getFont()->setSize(12);  
					$mySheet->getStyleByColumnAndRow((($countres)-3),($temprowcount-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
				else
				{
					$mySheet->mergeCellsByColumnAndRow(($countres-3),($temprowcount-2),($countres-1),3);	
					$mySheet->setCellValueByColumnAndRow(($countres-3),($temprowcount-2),$productyear[$i]);
					$mySheet->getStyleByColumnAndRow(($countres-3),($temprowcount-2))->getFont()->setBold(true);
					$mySheet->getStyleByColumnAndRow(($countres-3),($temprowcount-2))->getFont()->setSize(12);  
					$mySheet->getStyleByColumnAndRow(($countres-3),($temprowcount-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
				//Set table headings
				if($i == 0)
				{
					$mySheet->setCellValueByColumnAndRow(($countres-4),($temprowcount-1),'Region');
					$mySheet->setCellValueByColumnAndRow(($countres-3),($temprowcount-1),'1 Product');
					$mySheet->setCellValueByColumnAndRow(($countres-2),($temprowcount-1),'2 Products');
					$mySheet->setCellValueByColumnAndRow(($countres-1),($temprowcount-1),'3+ Products');
					
				}
				else
				{	
					$mySheet->setCellValueByColumnAndRow(($countres-3),($temprowcount-1),'1 Product');
					$mySheet->setCellValueByColumnAndRow(($countres-2),($temprowcount-1),'2 Products');
					$mySheet->setCellValueByColumnAndRow(($countres-1),($temprowcount-1),'3+ Products');
				}
				$currentrow++;
			}
		}
			$mySheet->setCellValueByColumnAndRow(0, $rowcount, 'Total');	
			$mySheet->getStyleByColumnAndRow(0, $rowcount)->applyFromArray($styleArrayContent);	
			
		$highestRow = $mySheet->getHighestRow(); 
		$highestColumn = $mySheet->getHighestColumn();
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		for ($col = 1; $col < $highestColumnIndex; $col++) 
		{
			$resultantsum = 0;
			for ($row = $assignrowcount; $row <= $highestRow; $row++)
			{
				$resultantsum += $mySheet->getCellByColumnAndRow($col,$row)->getValue();
				 
			}
			$mySheet->setCellValueByColumnAndRow($col, ($row-1), $resultantsum);
			$mySheet->getStyleByColumnAndRow($col, ($row-1))->applyFromArray($styleArrayContent);
		}
		
		
		//Begin writing Branch wise summary
		$currentrow = $rowcount + 3;
		
		//Set heading for Branch wise summary
		$mySheet->setCellValue('A'.$currentrow,'Branch wise summary');
		$mySheet->getStyle('A'.$currentrow)->getFont()->setBold(true); 
		$mySheet->getStyle('A'.$currentrow)->getFont()->setSize(13); 
		
		$databeginrow = $currentrow;
		
		//fetch the count of the year
		$productyearcount = count($productyear);
		for($i=0;$i<$productyearcount;$i++)
		{
			
			$query = "select branchlist.branchname as branch,IFNULL(dummy.product1,'0') as product1,
IFNULL(dummy.product2,'0') as product2,IFNULL(dummy.product3,'0') as product3 from 
(select distinct inv_mas_branch.slno as slno, inv_mas_branch.branchname as branchname from inv_mas_customer 
left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
order by inv_mas_branch.branchname) as branchlist
left join (SELECT  branch,count((product1 <> '' or null) and (product2 = '' or null) and (product3 = '' or null))as product1,count((product2 <> '' or null) and (product3 = '' or null)) as product2, count(product3 <> '' or null) as product3 
FROM cross_products".str_replace('-','',$productyear[$i])." group by  branch order by branch) as dummy on 
dummy.branch = branchlist.branchname order by branchlist.branchname;";
			$result23 = runmysqlquery($query);
			if($i == 0)
				$colcount = 0;
			else if($i == 1)
				$colcount = $colcount + 4;
			else
				$colcount = $colcount + 3;
			
			
			$rowcount =  $databeginrow + 4;
			$assignrowcount =  $databeginrow + 4;
			$temprowcount = $rowcount;
			while($row_data = mysqli_fetch_row($result23))
			{
				if($i==0)
					$rowcountvalue = count($row_data);
				else
					$rowcountvalue = (count($row_data)-1);
					
				//echo(count($row_data)); break;
				$countres = $colcount;
				for($j = 0;$j < $rowcountvalue;$j++)
				{
					//Insert data
					if($i == 0)
					{
						$mySheet->setCellValueByColumnAndRow($countres, $rowcount, $row_data[$j]);
						$mySheet->getStyleByColumnAndRow($countres,$rowcount)->applyFromArray($styleArrayContent);
						$mySheet->getStyleByColumnAndRow($countres, ($temprowcount-1))->applyFromArray($styleArray);
						$mySheet->getStyleByColumnAndRow($countres+1,($temprowcount-2))->applyFromArray($styleArrayContent);
			
					}
					else
					{
					  $mySheet->setCellValueByColumnAndRow(($countres), $rowcount, $row_data[$j+1]);
					  $mySheet->getStyleByColumnAndRow($countres,$rowcount)->applyFromArray($styleArrayContent);
					  $mySheet->getStyleByColumnAndRow($countres, ($temprowcount-1))->applyFromArray($styleArray);
					  $mySheet->getStyleByColumnAndRow($countres,($temprowcount-2))->applyFromArray($styleArrayContent);
					}
					$countres++;
				}
				
				$rowcount++;
				
				if($i == 0)
				{
					$mySheet->mergeCellsByColumnAndRow((($countres)-3),($temprowcount-2),($countres-1),3);	
					$mySheet->setCellValueByColumnAndRow((($countres)-3),($temprowcount-2),$productyear[$i]);
					$mySheet->getStyleByColumnAndRow((($countres)-3),($temprowcount-2))->getFont()->setBold(true);
					$mySheet->getStyleByColumnAndRow((($countres)-3),($temprowcount-2))->getFont()->setSize(12);  
					$mySheet->getStyleByColumnAndRow((($countres)-3),($temprowcount-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
				else
				{
					$mySheet->mergeCellsByColumnAndRow(($countres-3),($temprowcount-2),($countres-1),3);	
					$mySheet->setCellValueByColumnAndRow(($countres-3),($temprowcount-2),$productyear[$i]);
					$mySheet->getStyleByColumnAndRow(($countres-3),($temprowcount-2))->getFont()->setBold(true);
					$mySheet->getStyleByColumnAndRow(($countres-3),($temprowcount-2))->getFont()->setSize(12);  
					$mySheet->getStyleByColumnAndRow(($countres-3),($temprowcount-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
				//Set table headings
				if($i == 0)
				{
					$mySheet->setCellValueByColumnAndRow(($countres-4),($temprowcount-1),'Branch');
					$mySheet->setCellValueByColumnAndRow(($countres-3),($temprowcount-1),'1 Product');
					$mySheet->setCellValueByColumnAndRow(($countres-2),($temprowcount-1),'2 Products');
					$mySheet->setCellValueByColumnAndRow(($countres-1),($temprowcount-1),'3+ Products');
					
				}
				else
				{	
					$mySheet->setCellValueByColumnAndRow(($countres-3),($temprowcount-1),'1 Product');
					$mySheet->setCellValueByColumnAndRow(($countres-2),($temprowcount-1),'2 Products');
					$mySheet->setCellValueByColumnAndRow(($countres-1),($temprowcount-1),'3+ Products');
				}
				$currentrow++;
			}
			
		}
			$mySheet->setCellValueByColumnAndRow(0, $rowcount, 'Total');	
			$mySheet->getStyleByColumnAndRow(0, $rowcount)->applyFromArray($styleArrayContent);	

		$highestRow = $mySheet->getHighestRow(); 
		$highestColumn = $mySheet->getHighestColumn();
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		for ($col = 1; $col < $highestColumnIndex; $col++) 
		{
			$resultantsum = 0;
			for ($row = $assignrowcount; $row <= $highestRow; $row++)
			{
				$resultantsum += $mySheet->getCellByColumnAndRow($col,$row)->getValue();
				 
			}
			$mySheet->setCellValueByColumnAndRow($col, ($row-1), $resultantsum);
			$mySheet->getStyleByColumnAndRow($col, ($row-1))->applyFromArray($styleArrayContent);
		}
		
		//Begin writing Type wise summary
		$currentrow = $rowcount + 3;
		
		//Set heading for Type wise summary
		$mySheet->setCellValue('A'.$currentrow,'Type wise summary');
		$mySheet->getStyle('A'.$currentrow)->getFont()->setBold(true); 
		$mySheet->getStyle('A'.$currentrow)->getFont()->setSize(13); 
		
		$databeginrow = $currentrow;
		
		//fetch the count of the year
		$productyearcount = count($productyear);
		for($i=0;$i<$productyearcount;$i++)
		{
			
			$query = "select typelist.customertype as customertype,ifnull(dummy.product1,'0') as product1,
ifnull(dummy.product2,'0') as product2,ifnull(dummy.product3,'0') as product3  from 
(select distinct inv_mas_customertype.slno as slno, ifnull(inv_mas_customertype.customertype,'UnAssigned') as customertype from inv_mas_customer
left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type
order by inv_mas_customertype.customertype) as typelist left join (SELECT  customertype,count((product1 <> '' or null) and (product2 = '' or null) and (product3 = '' or null))as product1,count((product2 <> '' or null) and (product3 = '' or null)) as product2, count(product3 <> '' or null) as product3 FROM cross_products".str_replace('-','',$productyear[$i])." group by  customertype order by customertype) 
as dummy on dummy.customertype = typelist.customertype order by typelist.customertype";
			$result23 = runmysqlquery($query);
			if($i == 0)
				$colcount = 0;
			else if($i == 1)
				$colcount = $colcount + 4;
			else
				$colcount = $colcount + 3;
			
			
			$rowcount =  $databeginrow + 4;
			$assignrowcount =  $databeginrow + 4;
			$temprowcount = $rowcount;
			while($row_data = mysqli_fetch_row($result23))
			{
				if($i==0)
					$rowcountvalue = count($row_data);
				else
					$rowcountvalue = (count($row_data)-1);
					
				//echo(count($row_data)); break;
				$countres = $colcount;
				for($j = 0;$j < $rowcountvalue;$j++)
				{
					//Insert data
					if($i == 0)
					{
						$mySheet->setCellValueByColumnAndRow($countres, $rowcount, $row_data[$j]);
						$mySheet->getStyleByColumnAndRow($countres,$rowcount)->applyFromArray($styleArrayContent);
						$mySheet->getStyleByColumnAndRow($countres, ($temprowcount-1))->applyFromArray($styleArray);
						$mySheet->getStyleByColumnAndRow($countres+1,($temprowcount-2))->applyFromArray($styleArrayContent);
			
					}
					else
					{
					  $mySheet->setCellValueByColumnAndRow(($countres), $rowcount, $row_data[$j+1]);
					  $mySheet->getStyleByColumnAndRow($countres,$rowcount)->applyFromArray($styleArrayContent);
					  $mySheet->getStyleByColumnAndRow($countres, ($temprowcount-1))->applyFromArray($styleArray);
					  $mySheet->getStyleByColumnAndRow($countres,($temprowcount-2))->applyFromArray($styleArrayContent);
					}
					$countres++;
				}
				
				$rowcount++;
				
				if($i == 0)
				{
					$mySheet->mergeCellsByColumnAndRow((($countres)-3),($temprowcount-2),($countres-1),3);	
					$mySheet->setCellValueByColumnAndRow((($countres)-3),($temprowcount-2),$productyear[$i]);
					$mySheet->getStyleByColumnAndRow((($countres)-3),($temprowcount-2))->getFont()->setBold(true);
					$mySheet->getStyleByColumnAndRow((($countres)-3),($temprowcount-2))->getFont()->setSize(12);  
					$mySheet->getStyleByColumnAndRow((($countres)-3),($temprowcount-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
				else
				{
					$mySheet->mergeCellsByColumnAndRow(($countres-3),($temprowcount-2),($countres-1),3);	
					$mySheet->setCellValueByColumnAndRow(($countres-3),($temprowcount-2),$productyear[$i]);
					$mySheet->getStyleByColumnAndRow(($countres-3),($temprowcount-2))->getFont()->setBold(true);
					$mySheet->getStyleByColumnAndRow(($countres-3),($temprowcount-2))->getFont()->setSize(12);  
					$mySheet->getStyleByColumnAndRow(($countres-3),($temprowcount-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
				//Set table headings
				if($i == 0)
				{
					$mySheet->setCellValueByColumnAndRow(($countres-4),($temprowcount-1),'Type');
					$mySheet->setCellValueByColumnAndRow(($countres-3),($temprowcount-1),'1 Product');
					$mySheet->setCellValueByColumnAndRow(($countres-2),($temprowcount-1),'2 Products');
					$mySheet->setCellValueByColumnAndRow(($countres-1),($temprowcount-1),'3+ Products');
					
				}
				else
				{	
					$mySheet->setCellValueByColumnAndRow(($countres-3),($temprowcount-1),'1 Product');
					$mySheet->setCellValueByColumnAndRow(($countres-2),($temprowcount-1),'2 Products');
					$mySheet->setCellValueByColumnAndRow(($countres-1),($temprowcount-1),'3+ Products');
				}
				$currentrow++;
			}
			
		}
		$mySheet->setCellValueByColumnAndRow(0, $rowcount, 'Total');	
		$mySheet->getStyleByColumnAndRow(0, $rowcount)->applyFromArray($styleArrayContent);	

		$highestRow = $mySheet->getHighestRow(); 
		$highestColumn = $mySheet->getHighestColumn();
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		for ($col = 1; $col < $highestColumnIndex; $col++) 
		{
			$resultantsum = 0;
			for ($row = $assignrowcount; $row <= $highestRow; $row++)
			{
				$resultantsum += $mySheet->getCellByColumnAndRow($col,$row)->getValue();
				 
			}
			$mySheet->setCellValueByColumnAndRow($col, ($row-1), $resultantsum);
			$mySheet->getStyleByColumnAndRow($col, ($row-1))->applyFromArray($styleArrayContent);
		}
		
		//Begin writing Category wise summary
		$currentrow = $rowcount + 3;
		
		//Set heading for Category wise summary
		$mySheet->setCellValue('A'.$currentrow,'Category wise summary');
		$mySheet->getStyle('A'.$currentrow)->getFont()->setBold(true); 
		$mySheet->getStyle('A'.$currentrow)->getFont()->setSize(13); 
		
		$databeginrow = $currentrow;
		
		//fetch the count of the year
		$productyearcount = count($productyear);
		for($i=0;$i<$productyearcount;$i++)
		{
			
			$query = "select categorylist.businesstype as businesstype,IFNULL(dummy.product1,'0') as product1,
IFNULL(dummy.product2,'0') as product2,IFNULL(dummy.product3,'0') as product3 from 
(select distinct inv_mas_customercategory.slno as slno, 
ifnull(inv_mas_customercategory.businesstype,'UnAssigned') as businesstype from inv_mas_customer 
left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category 
order by inv_mas_customercategory.businesstype) as categorylist left join (SELECT  businesstype,count((product1 <> '' or null) and (product2 = '' or null) and (product3 = '' or null))as product1,count((product2 <> '' or null) and (product3 = '' or null)) as product2, count(product3 <> '' or null) as product3 FROM cross_products".str_replace('-','',$productyear[$i])." group by  businesstype order by businesstype) as dummy on dummy.businesstype = categorylist.businesstype order by categorylist.businesstype";
			$result23 = runmysqlquery($query);
			if($i == 0)
				$colcount = 0;
			else if($i == 1)
				$colcount = $colcount + 4;
			else
				$colcount = $colcount + 3;
			
			
			$rowcount =  $databeginrow + 4;
			$assignrowcount =  $databeginrow + 4;
			$temprowcount = $rowcount;
			while($row_data = mysqli_fetch_row($result23))
			{
				if($i==0)
					$rowcountvalue = count($row_data);
				else
					$rowcountvalue = (count($row_data)-1);
					
				//echo(count($row_data)); break;
				$countres = $colcount;
				for($j = 0;$j < $rowcountvalue;$j++)
				{
					//Insert data
					if($i == 0)
					{
						$mySheet->setCellValueByColumnAndRow($countres, $rowcount, $row_data[$j]);
						$mySheet->getStyleByColumnAndRow($countres,$rowcount)->applyFromArray($styleArrayContent);
						$mySheet->getStyleByColumnAndRow($countres, ($temprowcount-1))->applyFromArray($styleArray);
						$mySheet->getStyleByColumnAndRow($countres+1,($temprowcount-2))->applyFromArray($styleArrayContent);
			
					}
					else
					{
					  $mySheet->setCellValueByColumnAndRow(($countres), $rowcount, $row_data[$j+1]);
					  $mySheet->getStyleByColumnAndRow($countres,$rowcount)->applyFromArray($styleArrayContent);
					  $mySheet->getStyleByColumnAndRow($countres, ($temprowcount-1))->applyFromArray($styleArray);
					  $mySheet->getStyleByColumnAndRow($countres,($temprowcount-2))->applyFromArray($styleArrayContent);
					}
					$countres++;
				}
				
				$rowcount++;
				
				if($i == 0)
				{
					$mySheet->mergeCellsByColumnAndRow((($countres)-3),($temprowcount-2),($countres-1),3);	
					$mySheet->setCellValueByColumnAndRow((($countres)-3),($temprowcount-2),$productyear[$i]);
					$mySheet->getStyleByColumnAndRow((($countres)-3),($temprowcount-2))->getFont()->setBold(true);
					$mySheet->getStyleByColumnAndRow((($countres)-3),($temprowcount-2))->getFont()->setSize(12);  
					$mySheet->getStyleByColumnAndRow((($countres)-3),($temprowcount-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
				else
				{
					$mySheet->mergeCellsByColumnAndRow(($countres-3),($temprowcount-2),($countres-1),3);	
					$mySheet->setCellValueByColumnAndRow(($countres-3),($temprowcount-2),$productyear[$i]);
					$mySheet->getStyleByColumnAndRow(($countres-3),($temprowcount-2))->getFont()->setBold(true);
					$mySheet->getStyleByColumnAndRow(($countres-3),($temprowcount-2))->getFont()->setSize(12);  
					$mySheet->getStyleByColumnAndRow(($countres-3),($temprowcount-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
				//Set table headings
				if($i == 0)
				{
					$mySheet->setCellValueByColumnAndRow(($countres-4),($temprowcount-1),'Category');
					$mySheet->setCellValueByColumnAndRow(($countres-3),($temprowcount-1),'1 Product');
					$mySheet->setCellValueByColumnAndRow(($countres-2),($temprowcount-1),'2 Products');
					$mySheet->setCellValueByColumnAndRow(($countres-1),($temprowcount-1),'3+ Products');
					
				}
				else
				{	
					$mySheet->setCellValueByColumnAndRow(($countres-3),($temprowcount-1),'1 Product');
					$mySheet->setCellValueByColumnAndRow(($countres-2),($temprowcount-1),'2 Products');
					$mySheet->setCellValueByColumnAndRow(($countres-1),($temprowcount-1),'3+ Products');
				}
				$currentrow++;
			}
			
		}
		$mySheet->setCellValueByColumnAndRow(0, $rowcount, 'Total');	
		$mySheet->getStyleByColumnAndRow(0, $rowcount)->applyFromArray($styleArrayContent);	

		$highestRow = $mySheet->getHighestRow(); 
		$highestColumn = $mySheet->getHighestColumn();
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		for ($col = 1; $col < $highestColumnIndex; $col++) 
		{
			$resultantsum = 0;
			for ($row = $assignrowcount; $row <= $highestRow; $row++)
			{
				$resultantsum += $mySheet->getCellByColumnAndRow($col,$row)->getValue();
				 
			}
			$mySheet->setCellValueByColumnAndRow($col, ($row-1), $resultantsum);
			$mySheet->getStyleByColumnAndRow($col, ($row-1))->applyFromArray($styleArrayContent);
		}
		
		//Begin writing State wise summary
		$currentrow = $rowcount + 3;
		
		//Set heading for State wise summary
		$mySheet->setCellValue('A'.$currentrow,'State wise summary');
		$mySheet->getStyle('A'.$currentrow)->getFont()->setBold(true); 
		$mySheet->getStyle('A'.$currentrow)->getFont()->setSize(13); 
		
		$databeginrow = $currentrow;
		
		//fetch the count of the year
		$productyearcount = count($productyear);
		for($i=0;$i<$productyearcount;$i++)
		{
			
			$query = "select statelist.statename as statename,IFNULL(dummy.product1,'0') as product1,
IFNULL(dummy.product2,'0') as product2,IFNULL(dummy.product3,'0') as product3 from (select distinct inv_mas_state.slno as slno, 
inv_mas_state.statename as statename from inv_mas_customer 
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
order by inv_mas_state.statename) as statelist left join (SELECT  statename,count((product1 <> '' or null) and (product2 = '' or null) and (product3 = '' or null))as product1,count((product2 <> '' or null) and (product3 = '' or null)) as product2, count(product3 <> '' or null) as product3 FROM cross_products".str_replace('-','',$productyear[$i])." group by  statename order by statename) as dummy on 
dummy.statename = statelist.statename order by statelist.statename;";
			$result23 = runmysqlquery($query);
			if($i == 0)
				$colcount = 0;
			else if($i == 1)
				$colcount = $colcount + 4;
			else
				$colcount = $colcount + 3;
			
			
			$rowcount =  $databeginrow + 4;
			$assignrowcount =  $databeginrow + 4;
			$temprowcount = $rowcount;
			while($row_data = mysqli_fetch_row($result23))
			{
				if($i==0)
					$rowcountvalue = count($row_data);
				else
					$rowcountvalue = (count($row_data)-1);
					
				//echo(count($row_data)); break;
				$countres = $colcount;
				for($j = 0;$j < $rowcountvalue;$j++)
				{
					//Insert data
					if($i == 0)
					{
						$mySheet->setCellValueByColumnAndRow($countres, $rowcount, $row_data[$j]);
						$mySheet->getStyleByColumnAndRow($countres,$rowcount)->applyFromArray($styleArrayContent);
						$mySheet->getStyleByColumnAndRow($countres, ($temprowcount-1))->applyFromArray($styleArray);
						$mySheet->getStyleByColumnAndRow($countres+1,($temprowcount-2))->applyFromArray($styleArrayContent);
			
					}
					else
					{
					  $mySheet->setCellValueByColumnAndRow(($countres), $rowcount, $row_data[$j+1]);
					  $mySheet->getStyleByColumnAndRow($countres,$rowcount)->applyFromArray($styleArrayContent);
					  $mySheet->getStyleByColumnAndRow($countres, ($temprowcount-1))->applyFromArray($styleArray);
					  $mySheet->getStyleByColumnAndRow($countres,($temprowcount-2))->applyFromArray($styleArrayContent);
					}
					$countres++;
				}
				
				$rowcount++;
				
				if($i == 0)
				{
					$mySheet->mergeCellsByColumnAndRow((($countres)-3),($temprowcount-2),($countres-1),3);	
					$mySheet->setCellValueByColumnAndRow((($countres)-3),($temprowcount-2),$productyear[$i]);
					$mySheet->getStyleByColumnAndRow((($countres)-3),($temprowcount-2))->getFont()->setBold(true);
					$mySheet->getStyleByColumnAndRow((($countres)-3),($temprowcount-2))->getFont()->setSize(12);  
					$mySheet->getStyleByColumnAndRow((($countres)-3),($temprowcount-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
				else
				{
					$mySheet->mergeCellsByColumnAndRow(($countres-3),($temprowcount-2),($countres-1),3);	
					$mySheet->setCellValueByColumnAndRow(($countres-3),($temprowcount-2),$productyear[$i]);
					$mySheet->getStyleByColumnAndRow(($countres-3),($temprowcount-2))->getFont()->setBold(true);
					$mySheet->getStyleByColumnAndRow(($countres-3),($temprowcount-2))->getFont()->setSize(12);  
					$mySheet->getStyleByColumnAndRow(($countres-3),($temprowcount-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
				//Set table headings
				if($i == 0)
				{
					$mySheet->setCellValueByColumnAndRow(($countres-4),($temprowcount-1),'State');
					$mySheet->setCellValueByColumnAndRow(($countres-3),($temprowcount-1),'1 Product');
					$mySheet->setCellValueByColumnAndRow(($countres-2),($temprowcount-1),'2 Products');
					$mySheet->setCellValueByColumnAndRow(($countres-1),($temprowcount-1),'3+ Products');
					
				}
				else
				{	
					$mySheet->setCellValueByColumnAndRow(($countres-3),($temprowcount-1),'1 Product');
					$mySheet->setCellValueByColumnAndRow(($countres-2),($temprowcount-1),'2 Products');
					$mySheet->setCellValueByColumnAndRow(($countres-1),($temprowcount-1),'3+ Products');
				}
				$currentrow++;
			}
			
		}
		$mySheet->setCellValueByColumnAndRow(0, $rowcount, 'Total');	
		$mySheet->getStyleByColumnAndRow(0, $rowcount)->applyFromArray($styleArrayContent);	

		$highestRow = $mySheet->getHighestRow(); 
		$highestColumn = $mySheet->getHighestColumn();
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		for ($col = 1; $col < $highestColumnIndex; $col++) 
		{
			$resultantsum = 0;
			for ($row = $assignrowcount; $row <= $highestRow; $row++)
			{
				$resultantsum += $mySheet->getCellByColumnAndRow($col,$row)->getValue();
				 
			}
			$mySheet->setCellValueByColumnAndRow($col, ($row-1), $resultantsum);
			$mySheet->getStyleByColumnAndRow($col, ($row-1))->applyFromArray($styleArrayContent);
		}
		
		
		//Begin writing District wise summary
		$currentrow = $rowcount + 3;
		
		//Set heading for District wise summary
		$mySheet->setCellValue('A'.$currentrow,'District wise summary');
		$mySheet->getStyle('A'.$currentrow)->getFont()->setBold(true); 
		$mySheet->getStyle('A'.$currentrow)->getFont()->setSize(13); 
		
		$databeginrow = $currentrow;
		
		//fetch the count of the year
		$productyearcount = count($productyear);
		for($i=0;$i<$productyearcount;$i++)
		{
			
			if($i == 0)
				$colcount = 0;
			else 
				$colcount = $colcount + 3;
			
			
			$rowcount =  $databeginrow + 4;
			$assignrowcount =  $databeginrow + 4;
			$temprowcount = $rowcount;
			
			for($j=0;$j<4;$j++)
			{
				$attachpiece = "";
				$districtheadingpiece = "";
				if($j == 0)
				{
					$attachpiece = "where statename = 'Karnataka' and districtname like '%bangalore%'";
					$districtheadingpiece = "Bangalore";
				}
				elseif($j == 1)
				{
					$attachpiece = "where statename = 'Karnataka' and districtname not like '%bangalore%'";
					$districtheadingpiece = "Rest of Karnataka";
				}
				elseif($j == 2)
				{
					$attachpiece = "where statename = 'Goa'";
					$districtheadingpiece = "Goa";
				}
				elseif($j == 3)
				{
					$attachpiece = "where statename = 'Kerala'";
					$districtheadingpiece = "Kerala";
				}
					
				$query = "SELECT  districtname,ifnull(count((product1 <> '' or null) and (product2 = '' or null) and (product3 = '' or null)),'0')as product1,ifnull(count((product2 <> '' or null) and (product3 = '' or null)),'0') as product2, ifnull(count(product3 <> '' or null),'0') as product3 FROM cross_products".str_replace('-','',$productyear[$i])." ".$attachpiece."  group by  districtname";
				$result162 = runmysqlquery($query);
				unset($products);
				
				while($fetchdistrict = mysqli_fetch_array($result162))
				{
					
					$products[0] += $fetchdistrict['product1'];
					$products[1] += $fetchdistrict['product2'];
					$products[2] += $fetchdistrict['product3'];
				}
				$countres = ($colcount+1);
				if($i == 0)
				{
					$mySheet->setCellValueByColumnAndRow(($countres-1), $rowcount, $districtheadingpiece);
					$mySheet->getStyleByColumnAndRow(($countres-1),$rowcount)->applyFromArray($styleArrayContent);
					$mySheet->getStyleByColumnAndRow(($countres-1), ($temprowcount-1))->applyFromArray($styleArray);
					$mySheet->getStyleByColumnAndRow($countres,($temprowcount-2))->applyFromArray($styleArrayContent);
					
				}
				for($k = 0;$k < 3;$k++)
				{
					if($products[$k] == '')
						$productvalue = '0';
					else
						$productvalue = $products[$k];
					$mySheet->setCellValueByColumnAndRow($countres, $rowcount, $productvalue);
					$mySheet->getStyleByColumnAndRow($countres,$rowcount)->applyFromArray($styleArrayContent);
					$mySheet->getStyleByColumnAndRow($countres, ($temprowcount-1))->applyFromArray($styleArray);
					$mySheet->getStyleByColumnAndRow($countres,($temprowcount-2))->applyFromArray($styleArrayContent);
					$countres++;
				}
					
				$rowcount++;
				if($i == 0)
				{
					$mySheet->mergeCellsByColumnAndRow((($countres)-3),($temprowcount-2),($countres-1),3);	
					$mySheet->setCellValueByColumnAndRow((($countres)-3),($temprowcount-2),$productyear[$i]);
					$mySheet->getStyleByColumnAndRow((($countres)-3),($temprowcount-2))->getFont()->setBold(true);
					$mySheet->getStyleByColumnAndRow((($countres)-3),($temprowcount-2))->getFont()->setSize(12);  
					$mySheet->getStyleByColumnAndRow((($countres)-3),($temprowcount-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
				else
				{
					$mySheet->mergeCellsByColumnAndRow(($countres-3),($temprowcount-2),($countres-1),3);	
					$mySheet->setCellValueByColumnAndRow(($countres-3),($temprowcount-2),$productyear[$i]);
					$mySheet->getStyleByColumnAndRow(($countres-3),($temprowcount-2))->getFont()->setBold(true);
					$mySheet->getStyleByColumnAndRow(($countres-3),($temprowcount-2))->getFont()->setSize(12);  
					$mySheet->getStyleByColumnAndRow(($countres-3),($temprowcount-2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
				//Set table headings
				if($i == 0)
				{
					$mySheet->setCellValueByColumnAndRow(($countres-4),($temprowcount-1),'District');
					$mySheet->setCellValueByColumnAndRow(($countres-3),($temprowcount-1),'1 Product');
					$mySheet->setCellValueByColumnAndRow(($countres-2),($temprowcount-1),'2 Products');
					$mySheet->setCellValueByColumnAndRow(($countres-1),($temprowcount-1),'3+ Products');
					
				}
				else
				{	
					$mySheet->setCellValueByColumnAndRow(($countres-3),($temprowcount-1),'1 Product');
					$mySheet->setCellValueByColumnAndRow(($countres-2),($temprowcount-1),'2 Products');
					$mySheet->setCellValueByColumnAndRow(($countres-1),($temprowcount-1),'3+ Products');
				}
				$currentrow++;
			}
		}
		$mySheet->setCellValueByColumnAndRow(0, $rowcount, 'Total');	
		$mySheet->getStyleByColumnAndRow(0, $rowcount)->applyFromArray($styleArrayContent);	

		$highestRow = $mySheet->getHighestRow(); 
		$highestColumn = $mySheet->getHighestColumn();
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		for ($col = 1; $col < $highestColumnIndex; $col++) 
		{
			$resultantsum = 0;
			for ($row = $assignrowcount; $row <= $highestRow; $row++)
			{
				$resultantsum += $mySheet->getCellByColumnAndRow($col,$row)->getValue();
				 
			}
			$mySheet->setCellValueByColumnAndRow($col, ($row-1), $resultantsum);
			$mySheet->getStyleByColumnAndRow($col, ($row-1))->applyFromArray($styleArrayContent);
		}
		
		$mySheet->getDefaultColumnDimension()->setWidth(22);
		
	}

  //Set Index to First work sheet, so that when the file is opened, this sheet will be visible
  $objPHPExcel->setActiveSheetIndex(0);
  
  //Insert logs to log table
  $query = 'select slno,username from inv_mas_users where slno = '.$userid.'';
  $fetchres = runmysqlqueryfetch($query);			
  $localdate = datetimelocal('Ymd');
  $localtime = datetimelocal('His');
  $filebasename = "CrossProductReport".$localdate."-".$localtime."-".strtolower($fetchres['username']).".xls";
  $query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_crossproduct_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
  $result = runmysqlquery($query1);	
  
  $eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','103','".date('Y-m-d').' '.date('H:i:s')."','excel_crossproduct_report".'-'.strtolower($fetchres['username'])."')";
	$eventresult = runmysqlquery($eventquery);
	  
  //Create excel file and pass it for download
  if($id == 'toexcel')
  {
	  $addstring = "/user";
	  if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") || ($_SERVER['HTTP_HOST'] == "vijaykumar"))
		  $addstring = "/saralimax-user";
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
}

?>