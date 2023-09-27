<?php
ini_set('memory_limit', '2048M');

include('../functions/phpfunctions.php');

//PHPExcel 
require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory
require_once '../phpgeneration/PHPExcel/IOFactory.php';

$flag = 'true';
if($flag == '')
{
	$url = '../home/index.php?a_link=updationdue_deletedetails'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
	$userid = '38';
	$id = 'toexcel';
	$dealerid = '';
	$contact_type = 'none';
	$compareyear = 'alltime';
	$region = '';
	$branch = '';
	$type = '';
	$category = '';
	$state = '';
	$splitby = 'none';
	$orderby = 'customerid';
	
	//Get the orderby field
	switch($orderby)
	{
		case 'customerid': $orderbyfield = 'order by customerid'; break;
		case 'companyname': $orderbyfield = 'order by businessname'; break;
		case 'state': $orderbyfield = 'order by statename'; break;
		case 'region': $orderbyfield = 'order by region'; break;
		case 'branch': $orderbyfield = 'order by branch'; break;
		case 'delaer': $orderbyfield = 'order by dealer'; break;
		default: $orderbyfield = 'order by customerid'; break;
	}
	
	//Get the product groups and implode with single quote
	$productchkdvalue = 'tds';
	//$prdvalue = implode("','",$productchkdvalue);
$prdvalue = 'tds';
	//Get the current year
	$query = "select max(`year`)as currentyear from inv_mas_product";
	$resultfetch = runmysqlqueryfetch($query);
	$currentyear = $resultfetch['currentyear'];

	//Get all the years in the product tablek
	$query = "select  group_concat(distinct `year`) as allyear from inv_mas_product where (`year` <> '' or null) and `year` <> '".$currentyear."' ;";
	$resultfetch = runmysqlqueryfetch($query);
	$allyear = $resultfetch['allyear'];
	
	//Explode Implode year with comma
	$allyeararray = explode(',',$allyear);
	
	// Implode year with single quote 
	$allyearvalue = implode("','",$allyeararray);
	
	//Get the previous year
	$datettime = date('Y');
	$year1 = date('Y', mktime(0,0,0,0,0,($datettime)));
	$previousyear = $year1.'-'.substr($datettime,2);
	
	$productgrouppiece = ($productchkdvalue == "")?(""):(" AND inv_mas_product.group IN ('".$prdvalue."') ");
	$productyearpiece = ($chkdvalue == "")?(""):("  AND  inv_mas_product.year IN ('".$splitvalue."') ");
	$currentyearpiece = ($compareyear == "alltime")?(" AND  inv_mas_product.year IN ('".$allyearvalue."') "):("  AND  inv_mas_product.year IN ('".$previousyear."') ");
	$currentyearpiece1 = "  AND  inv_mas_product.year IN ('".$currentyear."') ";
	$dealeridpiece = ($dealerid == "")?(""):("  AND inv_mas_dealer.slno = '".$dealerid."' ");
	$regionpiece = ($region == "")?(""):("  AND inv_mas_region.slno = '".$region."' ");
	$branchpiece = ($branch == "")?(""):("  AND inv_mas_branch.slno = '".$branch."' ");
	$typepiece = ($type == "")?(""):("  AND inv_mas_customertype.slno = '".$type."' ");
	$categorypiece = ($category == "")?(""):("  AND inv_mas_customercategory.slno = '".$category."' ");
	$statepiece = ($state == "")?(""):("  AND inv_mas_state.statecode = '".$state."' ");
	
	$query1 = "drop table if exists inv_customerproduct_temp1_delete";
	$result1 = runmysqlquery($query1);
	
	  
	$query2 = "create   table if not exists inv_customerproduct_temp1_delete (select inv_customerproduct.customerreference, inv_customerproduct.date, inv_customerproduct.time, inv_mas_product.year, inv_mas_product.productname  from inv_customerproduct left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) where inv_customerproduct.reregistration = 'no' AND inv_customerproduct.cardid > 0   ".$currentyearpiece.$productgrouppiece.");";
	$result2 = runmysqlquery($query2);

	$query3 = "drop table if exists inv_customerproduct_temp2_delete";
	$result3 = runmysqlquery($query3);
	
	 $query4 = "create  table if not exists inv_customerproduct_temp2_delete (select inv_customerproduct.customerreference, inv_customerproduct.date, inv_customerproduct.time, inv_mas_product.year, inv_mas_product.productname  from inv_customerproduct left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) where inv_customerproduct.reregistration = 'no' AND inv_customerproduct.cardid > 0  ".$currentyearpiece.$productgrouppiece.");";
	  $result4 = runmysqlquery($query4);
		
	  $query5 = "ALTER table inv_customerproduct_temp1_delete add index (customerreference);";
	  $result5 = runmysqlquery($query5);
	  
	  $query6 = "ALTER table inv_customerproduct_temp1_delete add index (date);";
	  $result6 = runmysqlquery($query6);
	  
	  $query7 = "ALTER table inv_customerproduct_temp1_delete add index (time);";
	  $result7 = runmysqlquery($query7);
	  
	  $query8 = "ALTER table inv_customerproduct_temp2_delete add index (customerreference);;";
	  $result8 = runmysqlquery($query8);
	  
	  $query9 = "ALTER table inv_customerproduct_temp1_delete add index (customerreference);";
	  $result9 = runmysqlquery($query9);
	  
	  $query10 = "ALTER table inv_customerproduct_temp2_delete add index (date);";
	  $result10 = runmysqlquery($query10);
	  
	  $query11 = "ALTER table inv_customerproduct_temp2_delete add index (time);";
	  $result11 = runmysqlquery($query11);

	$query3 = "drop table if exists updationdue_delete";
	$result3 = runmysqlquery($query3);
	
	//$query = "create  table updationdue_delete select distinct inv_mas_customer.slno,inv_mas_customer.address,inv_mas_customer.customerid,inv_mas_customer.businessname,inv_mas_customer.pincode,inv_mas_customer.place,	inv_mas_dealer.businessname as dealername,inv_mas_region.category as region,oldyear.group as prdgroup,inv_mas_district.districtname as districtname,inv_mas_state.statename as statename,inv_mas_customercategory.businesstype as cuscategory,inv_mas_customertype.customertype as custype,inv_mas_branch.branchname as branch from (select distinct inv_customerproduct.customerreference AS customerreference, inv_mas_product.group ,inv_mas_customer.region	from inv_customerproduct left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno where reregistration = 'no' AND inv_customerproduct.cardid > 0 ".$currentyearpiece.$productgrouppiece.") AS oldyear left join (select distinct inv_customerproduct.customerreference AS customerreference, inv_mas_product.group from inv_customerproduct left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) 	left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno where reregistration = 'no' AND inv_customerproduct.cardid > 0 ".$currentyearpiece1.$productgrouppiece.") AS newyear on oldyear.customerreference = newyear.customerreference AND oldyear.group = newyear.group left join inv_mas_customer on oldyear.customerreference = inv_mas_customer.slno left join inv_mas_dealer on inv_mas_customer.currentdealer = inv_mas_dealer.slno left join inv_mas_region on oldyear.region = inv_mas_region.slno left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type where newyear.customerreference is null and inv_mas_customer.companyclosed = 'no' and inv_mas_customer.isdealer = 'no' ".$dealeridpiece.$regionpiece.$branchpiece.$typepiece.$categorypiece.$statepiece."  order by inv_mas_customer.slno;";
	
	$query = "create temporary table updationdue select distinct inv_mas_customer.slno,inv_mas_customer.address,inv_mas_customer.customerid,inv_mas_customer.businessname,inv_mas_customer.pincode,inv_mas_customer.place,	inv_mas_dealer.businessname as dealername,inv_mas_region.category as region,oldyear.group as prdgroup,inv_mas_district.districtname as districtname,inv_mas_state.statename as statename,inv_mas_customercategory.businesstype as cuscategory,inv_mas_customertype.customertype as custype,inv_mas_branch.branchname as branch from (select distinct inv_customerproduct.customerreference AS customerreference, inv_mas_product.group ,inv_mas_customer.region	from inv_customerproduct left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno where reregistration = 'no' AND inv_customerproduct.cardid > 0 ".$currentyearpiece.$productgrouppiece.") AS oldyear left join (select distinct inv_customerproduct.customerreference AS customerreference, inv_mas_product.group from inv_customerproduct left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) 	left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno where reregistration = 'no' AND inv_customerproduct.cardid > 0 ".$currentyearpiece1.$productgrouppiece.") AS newyear on oldyear.customerreference = newyear.customerreference AND oldyear.group = newyear.group left join inv_mas_customer on oldyear.customerreference = inv_mas_customer.slno left join inv_mas_dealer on inv_mas_customer.currentdealer = inv_mas_dealer.slno left join inv_mas_region on oldyear.region = inv_mas_region.slno left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type where newyear.customerreference is null and inv_mas_customer.companyclosed = 'no' and inv_mas_customer.isdealer = 'no' ".$dealeridpiece.$regionpiece.$branchpiece.$typepiece.$categorypiece.$statepiece."  order by inv_mas_customer.slno;";
		echo($query);exit;
	//
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
		
		//Merge the cell
		$mySheet->mergeCells('A1:U1');
		$mySheet->mergeCells('A2:U2');
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'Updation Due Details');
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
	
		//query to split sheet
		if($splitby == "region")
		{
			$appendquery = "select distinct region from updationdue_delete order by region;";
			$fetchedvalue = 'region';
			$fieldname = 'where '.'region =';
		}
		else if($splitby == "branch")
		{
			$appendquery = "select distinct branch from updationdue_delete order by branch;";
			$fetchedvalue = 'branch';
			$fieldname = 'where '.'branch =';
		}
		else if($splitby == "state")
		{
			$appendquery = "select distinct statename from updationdue_delete order by statename;";
			$fetchedvalue ='statename';
			$fieldname = 'where '.'statename =';
		}
		else if($splitby == "dealer")
		{
			$appendquery = "select distinct dealername from updationdue_delete order by dealername;";
			$fetchedvalue = 'dealername';
			$fieldname =  'where '.'dealername =';
		}
		else if($splitby == "none")
		{
			$fieldname = "";
			$fetch2[$fetchedvalue] = "";
			$appendquery = "select distinct region from updationdue_delete order by region;";
		}
		$query2 = $appendquery;
		$result2 = runmysqlquery($query2);
		$pageindex = 0;
		while($fetch2 = mysqli_fetch_array($result2))
		{
			if($splitby == "none")
			{
				$query = "select * from updationdue_delete left join (SELECT distinct s1.date, s1.customerreference, s1.productname, s1.year FROM inv_customerproduct_temp1_delete as s1 LEFT JOIN inv_customerproduct_temp2_delete as s2 ON s1.customerreference = s2.customerreference AND concat(s1.date, ' ', s1.time) < concat(s2.date, ' ', s2.time)  WHERE s2.customerreference IS NULL order by s1.customerreference) as temp1 on updationdue_delete.slno = temp1.customerreference ".$orderbyfield.";";
//echo($query); exit();
			}
			else
				$query = "select * from updationdue_delete left join (SELECT distinct s1.date, s1.customerreference, s1.productname, s1.year FROM inv_customerproduct_temp1_delete as s1 LEFT JOIN inv_customerproduct_temp2_delete as s2 ON s1.customerreference = s2.customerreference AND concat(s1.date, ' ', s1.time) < concat(s2.date, ' ', s2.time)  WHERE s2.customerreference IS NULL order by s1.customerreference) as temp1 on updationdue_delete.slno = temp1.customerreference ".$fieldname." '".$fetch2[$fetchedvalue]."' ".$orderbyfield.";";
			$result = runmysqlquery($query);
			
						echo('Done'); exit;

			
			$j =4;
			$slno_count =0;
			while($fetch = mysqli_fetch_array($result))
			{
				
				// Fetch Contact Details
				$querycontactdetails = "select customerid, GROUP_CONCAT(contactperson) as contactperson,  
GROUP_CONCAT(phone) as phone, GROUP_CONCAT(cell) as cell, GROUP_CONCAT(emailid) as emailid from inv_contactdetails where customerid = '".$fetch['slno']."'  group by customerid ";
				$resultcontact = runmysqlquery($querycontactdetails);
				$resultcontactdetails = mysqli_fetch_array($resultcontact);
				//$resultcontactdetails = runmysqlqueryfetch($querycontactdetails);
				
				$contactvalues = removedoublecomma($resultcontactdetails['contactperson']);
				$phoneres = removedoublecomma($resultcontactdetails['phone']);
				$cellres = removedoublecomma($resultcontactdetails['cell']);
				$emailidres = removedoublecomma($resultcontactdetails['emailid']);
				//Set Active Sheet	
				$mySheet = $objPHPExcel->getActiveSheet();
				
				//Apply style for header Row
				$mySheet->getStyle('A3:U3')->applyFromArray($styleArray);
				
				//File contents for Header Row
				$objPHPExcel->setActiveSheetIndex($pageindex)
							->setCellValue('A3', 'Sl No')
							->setCellValue('B3', 'Customer ID')
							->setCellValue('C3', 'Business Name')
							->setCellValue('D3', 'Contact person')
							->setCellValue('E3', 'Address')
							->setCellValue('F3', 'Place')
							->setCellValue('G3', 'Pincode')
							->setCellValue('H3', 'District')
							->setCellValue('I3', 'State')
							->setCellValue('J3', 'Cell')
							->setCellValue('K3', 'Phone')
							->setCellValue('L3', 'Emailid')
							->setCellValue('M3', 'Region')
							->setCellValue('N3', 'Branch')
							->setCellValue('O3', 'Type')
							->setCellValue('P3', 'Category')
							->setCellValue('Q3', 'Product Group')
							->setCellValue('R3', 'Last Reg Date')
							->setCellValue('S3', 'Last Product Edition')
							->setCellValue('T3', 'Last Year')
							->setCellValue('U3', 'Dealer Name');
						
					switch($contact_type)
					{
							case "uniquemailid":
								
								// Unique Emailids
								$emailidarray = trim($emailidres,',');
								$splitemailid = explode(',',$emailidarray);
								for($i=0;$i<count($splitemailid);$i++)
								{
									$slno_count++;
									$mySheet->setCellValue('A' . $j,$slno_count)
										->setCellValue('B' . $j,cusidcombine($fetch['customerid']))
										->setCellValue('C' . $j,$fetch['businessname'])
										->setCellValue('D' . $j,trim($contactvalues,','))
										->setCellValue('E' . $j,$fetch['address'])
										->setCellValue('F' . $j,$fetch['place'])
										->setCellValue('G' . $j,$fetch['pincode'])
										->setCellValue('H' . $j,$fetch['districtname'])
										->setCellValue('I' . $j,$fetch['statename'])
										->setCellValue('J' . $j,trim($cellres,','))
										->setCellValue('K' . $j,trim($phoneres,','))
										->setCellValue('L' . $j,$splitemailid[$i])
										->setCellValue('M' . $j,$fetch['region'])
										->setCellValue('N' . $j,$fetch['branch'])
										->setCellValue('O' . $j,$fetch['custype'])
										->setCellValue('P' . $j,$fetch['cuscategory'])
										->setCellValue('Q' . $j,$fetch['prdgroup'])
										->setCellValue('R' . $j,changedateformat($fetch['date']))
										->setCellValue('S' . $j,$fetch['productname'])
										->setCellValue('T' . $j,$fetch['year'])
										->setCellValue('U' . $j,$fetch['dealername']);
										$j++;
								}
								break;
								
							case "uniquecellno":
								// Unique Cell Nos
								$cellarray = trim($cellres,',');
								$splitcell = explode(',',$cellarray);
								for($i=0;$i<count($splitcell);$i++)
								{	
									$slno_count++;
									$mySheet->setCellValue('A' . $j,$slno_count)
										->setCellValue('B' . $j,cusidcombine($fetch['customerid']))
										->setCellValue('C' . $j,$fetch['businessname'])
										->setCellValue('D' . $j,trim($contactvalues,','))
										->setCellValue('E' . $j,$fetch['address'])
										->setCellValue('F' . $j,$fetch['place'])
										->setCellValue('G' . $j,$fetch['pincode'])
										->setCellValue('H' . $j,$fetch['districtname'])
										->setCellValue('I' . $j,$fetch['statename'])
										->setCellValue('J' . $j,$splitcell[$i])
										->setCellValue('K' . $j,trim($phoneres,','))
										->setCellValue('L' . $j,trim($emailidres,','))
										->setCellValue('M' . $j,$fetch['region'])
										->setCellValue('N' . $j,$fetch['branch'])
										->setCellValue('O' . $j,$fetch['custype'])
										->setCellValue('P' . $j,$fetch['cuscategory'])
										->setCellValue('Q' . $j,$fetch['prdgroup'])
										->setCellValue('R' . $j,changedateformat($fetch['date']))
										->setCellValue('S' . $j,$fetch['productname'])
										->setCellValue('T' . $j,$fetch['year'])
										->setCellValue('U' . $j,$fetch['dealername']);
										$j++;
										
								}
								break;
						
							default:
								$slno_count++;
								$mySheet->setCellValue('A' . $j,$slno_count)
										->setCellValue('B' . $j,cusidcombine($fetch['customerid']))
										->setCellValue('C' . $j,$fetch['businessname'])
										->setCellValue('D' . $j,trim($contactvalues,','))
										->setCellValue('E' . $j,$fetch['address'])
										->setCellValue('F' . $j,$fetch['place'])
										->setCellValue('G' . $j,$fetch['pincode'])
										->setCellValue('H' . $j,$fetch['districtname'])
										->setCellValue('I' . $j,$fetch['statename'])
										->setCellValue('J' . $j,trim($cellres,','))
										->setCellValue('K' . $j,trim($phoneres,','))
										->setCellValue('L' . $j,trim($emailidres,','))
										->setCellValue('M' . $j,$fetch['region'])
										->setCellValue('N' . $j,$fetch['branch'])
										->setCellValue('O' . $j,$fetch['custype'])
										->setCellValue('P' . $j,$fetch['cuscategory'])
										->setCellValue('Q' . $j,$fetch['prdgroup'])
										->setCellValue('R' . $j,changedateformat($fetch['date']))
										->setCellValue('S' . $j,$fetch['productname'])
										->setCellValue('T' . $j,$fetch['year'])
										->setCellValue('U' . $j,$fetch['dealername']);
								$j++;
						break;
					}
			}
		
			//Get the last cell reference
			$highestRow = $mySheet->getHighestRow(); 
			$highestColumn = $mySheet->getHighestColumn(); 
			$myLastCell = $highestColumn.$highestRow;
			
			//Define Style for content area
			$styleArrayContent = array(
					'borders' => array('allBorders'=> array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))
				);
						
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
			$mySheet->getColumnDimension('C')->setWidth(35);
			$mySheet->getColumnDimension('D')->setWidth(40);
			$mySheet->getColumnDimension('E')->setWidth(59);
			$mySheet->getColumnDimension('F')->setWidth(13);
			$mySheet->getColumnDimension('G')->setWidth(11);
			$mySheet->getColumnDimension('H')->setWidth(21);
			$mySheet->getColumnDimension('I')->setWidth(25);
			$mySheet->getColumnDimension('J')->setWidth(27);
			$mySheet->getColumnDimension('K')->setWidth(23);
			$mySheet->getColumnDimension('L')->setWidth(39);
			$mySheet->getColumnDimension('M')->setWidth(7);
			$mySheet->getColumnDimension('N')->setWidth(15);
			$mySheet->getColumnDimension('O')->setWidth(15);
			$mySheet->getColumnDimension('P')->setWidth(15);
			$mySheet->getColumnDimension('Q')->setWidth(15);
			$mySheet->getColumnDimension('R')->setWidth(32);
			$mySheet->getColumnDimension('S')->setWidth(32);
			$mySheet->getColumnDimension('T')->setWidth(32);
			$mySheet->getColumnDimension('U')->setWidth(32);
		
			if($splitby == 'none')
			{
				//Set the worksheet name
				$mySheet->setTitle('Main');
				break;
			}
			else
			{
				//Set the worksheet name
				$mySheet->setTitle(substr($fetch2[$fetchedvalue],0,15));
			}
			//Get the total number of sheets to be created
			$pagecount = mysqli_num_rows($result2);
			if($pageindex == ($pagecount -1))
				break;
			else
			{
				//Begin with Worksheet 2 (Summary)
				$pageindex++;
				$objPHPExcel->createSheet();
				$objPHPExcel->setActiveSheetIndex($pageindex);
				$mySheet = $objPHPExcel->getActiveSheet();
			}
		}

		$addstring = "/user";
		if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk" ) || ($_SERVER['HTTP_HOST'] == "archanaab" ))
			$addstring = "/saralimax-user";
		
		if($id == 'toexcel')
		{
			$query = 'select slno,username from inv_mas_users where slno = '.$userid.'';
			$fetchres = runmysqlqueryfetch($query);	
			$localdate = datetimelocal('Ymd');
			$localtime = datetimelocal('His');
			$filebasename = "UpdationDetails".$localdate."-".$localtime."-".strtolower($fetchres['username']).".xls";
			
			$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filebasename;
			$downloadlink = 'http://'.$_SERVER['HTTP_HOST'].$addstring.'/filecreated/'.$filebasename;
	
			$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_updationdue_delete_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
			$result = runmysqlquery($query1);
			
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','92','".date('Y-m-d').' '.date('H:i:s')."','excel_updationdue_delete_report".'-'.strtolower($fetchres['username'])."')";
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
		elseif($id == 'view')
		{
			$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','view_updationdue_delete_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
			$result = runmysqlquery($query1);
			
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','93','".date('Y-m-d').' '.date('H:i:s')."','view_updationdue_delete_report')";
			$eventresult = runmysqlquery($eventquery);
			
			$localdate = datetimelocal('Ymd');
			$localtime = datetimelocal('His');
			$filename = "viewupdationdue_delete".$localdate."-".$localtime.".htm";
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
