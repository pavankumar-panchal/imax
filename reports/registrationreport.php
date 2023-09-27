<?
ini_set('memory_limit', '2048M');

include('../functions/phpfunctions.php');
require_once '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
//PHPExcel
//require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory 
//require_once '../phpgeneration/PHPExcel/IOFactory.php';

$flag = $_POST['flag'];
if($flag == '')
{
	$url = '../home/index.php?a_link=registrationdetails'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
	$id = $_GET['id'];
	$fromdate = $_POST['fromdate'];
	$todate = $_POST['todate'];
	$customerselection = $_POST['customerselection'];
	$searchtext = $_POST['searchtext'];
	$searchtextid = $_POST['searchtextid'];
	$geography = $_POST['geography'];
	$region = $_POST['region'];
	$state = $_POST['state'];
	$district = $_POST['district'];
	$groupon = $_POST['groupon'];
	$branch = $_POST['branch'];
	$card = $_POST['card'];
	$reregistration = $_POST['reregistration'];
	$generatedby = $_POST['generatedby'];
	$dealerid = $_POST['dealerid'];
	$billnumberfrom = $_POST['billnumberfrom'];
	$billnumberto = $_POST['billnumberto'];
	$multilogin = $_POST['multilogin'];
	$userid = imaxgetcookie('userid');
	$chks = $_POST['productname'];
	for ($i = 0;$i < count($chks);$i++)
	{
		$c_value .= "'" . $chks[$i] . "'" ."," ;
	}
	$productslist = rtrim($c_value , ',');
	$value = str_replace('\\','',$productslist);
	
	$usagetype = $_POST['usagetype'];
	$purchasetype = $_POST['purchasetype'];
	$scheme = $_POST['scheme'];
	if($fromdate <> '' && $todate <> '')
	{
		$customerselectionpiece = ($customerselection == 'allcustomer')?(""):(" AND inv_customerproduct.customerreference = '".$searchtext."' ");
		
		if($geography == "") { $geographypiece = ""; } 
		elseif($geography == "region") { $geographypiece = " AND inv_mas_customer.region = '".$region."' " ; }
		elseif($geography == "state") { $geographypiece = " AND inv_mas_district.statecode  = '".$state."' " ; }
		elseif($geography == "district") { $geographypiece = " AND inv_mas_customer.district = '".$district."' " ; }
		
		if($card == "") {$cardpiece = ""; }
		elseif($card == 'withcard') {$cardpiece = " AND inv_customerproduct.cardid <> '' ";}
		elseif($card == 'withoutcard') {$cardpiece = " AND inv_customerproduct.cardid = '' ";}
		
		if($reregistration == "") {$reregistrationpiece = ""; }
		elseif($reregistration == 'yes') {$reregistrationpiece =  " AND inv_customerproduct.reregistration = 'yes' ";}
		elseif($reregistration == 'no') {$reregistrationpiece = " AND inv_customerproduct.reregistration = 'no' ";}
		
		$generatedbypiece = ($generatedby == "")?(""):(" AND inv_customerproduct.generatedby = '".$generatedby."' ");
		$productcodepiece = ($chks == "")?(""):(" AND  inv_mas_product.productcode IN (".$value.") ");
		$usagetypepiece = ($usagetype == "")?(""):(" AND inv_dealercard.usagetype = '".$usagetype."' ");
		$purchasetypepiece = ($purchasetype == "")?(""):(" AND inv_dealercard.purchasetype = '".$purchasetype."' ");
		$dealeridpiece = ($dealerid == "")?(""):(" AND inv_customerproduct.dealerid = '".$dealerid."' ");
		$branchpiece = ($branch == "")?(""):(" AND inv_mas_customer.branch = '".$branch."' ");
		$schemepiece = ($scheme == "")?(""):(" AND inv_mas_scheme.slno = '".$scheme."' ");
		$billnumberpiece = ($billnumberfrom == "" || $billnumberto == "")?(""):(" AND inv_customerproduct.cusbillnumber BETWEEN '".$billnumberfrom."' AND '".$billnumberto."' ");
		
		if($groupon == 'product') { $grouponpiece = " inv_mas_product.productname "; }
		elseif($groupon == 'generatedby') { $grouponpiece = " inv_mas_users.fullname "; }
		elseif($groupon == 'dealer') { $grouponpiece = " inv_mas_dealer.businessname "; }	
		elseif($groupon == 'date') { $grouponpiece = " inv_customerproduct.date "; }	
		
		$query = "SELECT distinct inv_mas_customer.customerid as cuid,inv_mas_customer.slno as cuslno, inv_mas_customer.businessname as customername,
                inv_mas_customer.address as customeraddress,inv_mas_customer.phone1 as customerphone,inv_mas_customer.cell1 as customercell,
                inv_mas_customer.fax as customerfax,inv_mas_branch.branchname as branchname,inv_mas_region.category as region,inv_dealercard.usagetype as usagetype,
                inv_dealercard.purchasetype as purchasetype, inv_customerproduct.cardid as cardid,inv_mas_scratchcard.scratchnumber as scratchnumber, 
                inv_customerproduct.date as `date`, inv_customerproduct.computerid as computerid, inv_customerproduct.softkey as softkey,inv_mas_users.fullname as userid,
                inv_mas_dealer.businessname as dealername,inv_customerproduct.billnumber as cusbillnumber, inv_customerproduct.billamount as billamount,inv_customerproduct.reregistration as reregistration,
                inv_customerproduct.remarks as remarks, inv_mas_product.productname as productname,inv_mas_scheme.schemename,inv_dealercard.invoiceid,inv_mas_state.statename as state, inv_mas_district.districtname as district,inv_mas_customercategory.businesstype as category
                from inv_customerproduct 
                LEFT JOIN inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
                LEFT JOIN inv_mas_users on inv_mas_users.slno = inv_customerproduct.generatedby 
                LEFT JOIN inv_mas_dealer ON inv_mas_dealer.slno = inv_customerproduct.dealerid 
                LEFT JOIN inv_dealercard ON inv_dealercard.cardid = inv_customerproduct.cardid 
                LEFT JOIN inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) 
                LEFT JOIN inv_mas_scheme on inv_mas_scheme.slno =inv_dealercard.scheme 
                Left join inv_mas_scratchcard on inv_mas_scratchcard.cardid=inv_customerproduct.cardid 
                Left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district 
                left join inv_mas_branch on inv_mas_branch.slno=inv_mas_customer.branch
                left join inv_mas_customercategory on inv_mas_customercategory.slno=inv_mas_customer.category  
                left join inv_mas_region on inv_mas_region.slno=inv_mas_customer.region
                left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno 
                left join inv_mas_state on inv_mas_district.statecode = inv_mas_state.statecode
                WHERE inv_customerproduct.date BETWEEN '".changedateformat($fromdate)."' AND '".changedateformat($todate)."' ".$geographypiece.$reregistrationpiece.$cardpiece.$customerselectionpiece.$generatedbypiece.$productcodepiece.$usagetypepiece.$purchasetypepiece.$dealeridpiece.$billnumberpiece.$branchpiece.$schemepiece."   ORDER BY `date` desc, ".$grouponpiece.";";
	
	}
	$result = runmysqlquery($query);
	//echo($query);exit;
	
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
	$mySheet->getStyle('A3:AB3')->applyFromArray($styleArray);
	
	//Merge the cell
	$mySheet->mergeCells('A1:AB1');
	$mySheet->mergeCells('A2:AB2');
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
				->setCellValue('A2', 'Product Registration Details');
	$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
	$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
	$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
	
	
	//Fille contents for Header Row
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A3', 'Sl No')
			->setCellValue('B3', 'Product')
			->setCellValue('C3', 'Customer')
			->setCellValue('D3', 'Pin Serial Number')
			->setCellValue('E3', 'PIN Number')
			->setCellValue('F3', 'Date')
			->setCellValue('G3', 'Computer ID')
			->setCellValue('H3', 'Soft Key')
			->setCellValue('I3', 'Generated By')
			->setCellValue('J3', 'Dealer')
			->setCellValue('K3', 'Bill Number')
			->setCellValue('L3', 'Billed Amount')
			->setCellValue('M3', 'Remarks')
			->setCellValue('N3', 'Scheme')
			->setCellValue('O3', 'Branch')
			->setCellValue('P3', 'Region')
			->setCellValue('Q3', 'Usage Type')
			->setCellValue('R3', 'Purchase Type')
			->setCellValue('S3', 'Customer ID')
			->setCellValue('T3', 'Address')
			->setCellValue('U3', 'Phone')
			->setCellValue('V3', 'Cell')
			->setCellValue('W3', 'Fax')
			->setCellValue('X3', 'Invoice No')
			->setCellValue('Y3', 'State')
			->setCellValue('Z3', 'Re-Registration')
			->setCellValue('AA3', 'District')
			->setCellValue('AB3', 'Category');

	
	$j = 4;
	$slno = 0;
	while($fetch = mysqli_fetch_array($result))
	{
		if($fetch['invoiceid']!="")
		{
			$query1 = "select invoiceno from inv_invoicenumbers where slno = ".$fetch['invoiceid'];
			$result1 = runmysqlquery($query1);
			$count1 = mysqli_num_rows($result1);
			if($count1 > 0)
			{
				$fetch1 = runmysqlqueryfetch($query1);
				$invoiceno = $fetch1['invoiceno'];
			}
		}
		else
		{
			$invoiceno = "";
		}

		$slno++;
		$mySheet->setCellValue('A' . $j,$slno)
				->setCellValue('B' . $j,$fetch['productname'])
				->setCellValue('C' . $j,$fetch['customername'])
				->setCellValue('D' . $j,$fetch['cardid'])
				->setCellValue('E' . $j,$fetch['scratchnumber'])
				->setCellValue('F' . $j,changedateformat($fetch['date']))
				->setCellValue('G' . $j,$fetch['computerid'])
				->setCellValue('H' . $j,$fetch['softkey'])
				->setCellValue('I' . $j,$fetch['userid'])
				->setCellValue('J' . $j,$fetch['dealername'])
				->setCellValue('K' . $j,$fetch['cusbillnumber'])
				->setCellValue('L' . $j,$fetch['billamount'])
				->setCellValue('M' . $j,$fetch['remarks'])
				->setCellValue('N' . $j,$fetch['schemename'])
				->setCellValue('O' . $j,$fetch['branchname'])
				->setCellValue('P' . $j,$fetch['region'])
				->setCellValue('Q' . $j,$fetch['usagetype'])
				->setCellValue('R' . $j,$fetch['purchasetype'])
				->setCellValue('S' . $j,cusidcombine($fetch['cuid']))
				->setCellValue('T' . $j,$fetch['customeraddress'])
				->setCellValue('U' . $j,$fetch['customerphone'])
				->setCellValue('V' . $j,$fetch['customercell'])
				->setCellValue('W' . $j,$fetch['customerfax'])
				->setCellValue('X' . $j,$invoiceno)
				->setCellValue('Y' . $j,$fetch['state'])
				->setCellValue('Z' . $j,$fetch['reregistration'])
				->setCellValue('AA' . $j,$fetch['district'])
				->setCellValue('AB' . $j,$fetch['category']);
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
		$mySheet->getColumnDimension('C')->setWidth(35);
		$mySheet->getColumnDimension('D')->setWidth(17);
		$mySheet->getColumnDimension('E')->setWidth(25);
		$mySheet->getColumnDimension('F')->setWidth(13);
		$mySheet->getColumnDimension('G')->setWidth(16);
		$mySheet->getColumnDimension('H')->setWidth(15);
		$mySheet->getColumnDimension('I')->setWidth(18);
		$mySheet->getColumnDimension('J')->setWidth(43);
		$mySheet->getColumnDimension('K')->setWidth(12);
		$mySheet->getColumnDimension('L')->setWidth(15);
		$mySheet->getColumnDimension('M')->setWidth(39);
		$mySheet->getColumnDimension('N')->setWidth(26);
		$mySheet->getColumnDimension('O')->setWidth(15);
		$mySheet->getColumnDimension('P')->setWidth(10);
		$mySheet->getColumnDimension('Q')->setWidth(12);
		$mySheet->getColumnDimension('R')->setWidth(14);
		$mySheet->getColumnDimension('S')->setWidth(20);
		$mySheet->getColumnDimension('T')->setWidth(100);
		$mySheet->getColumnDimension('U')->setWidth(20);
		$mySheet->getColumnDimension('V')->setWidth(20);
		$mySheet->getColumnDimension('W')->setWidth(20);
		$mySheet->getColumnDimension('X')->setWidth(15);
		$mySheet->getColumnDimension('Y')->setWidth(15);
		$mySheet->getColumnDimension('Z')->setWidth(25);
		$mySheet->getColumnDimension('AA')->setWidth(25);
		$mySheet->getColumnDimension('AB')->setWidth(35);

		$addstring = "/user";
		if($_SERVER['HTTP_HOST'] == "localhost" || $_SERVER['HTTP_HOST'] == "meghanab")
			$addstring = "/project/imax";
		if($id == 'toexcel')
		{
			$query = 'select slno,username from inv_mas_users where slno = '.$userid.'';
			$fetchres = runmysqlqueryfetch($query);
			$localdate = datetimelocal('Ymd');
			$localtime = datetimelocal('His');
			$filebasename = "RegistrationReport".$localdate."-".$localtime."-".strtolower($fetchres['username']).".xls";
			$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_registration_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
			$result = runmysqlquery($query1);	
			
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','97','".date('Y-m-d').' '.date('H:i:s')."','excel_registration_report".'-'.strtolower($fetchres['username'])."')";
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
		elseif($id == 'view')
		{
			$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','view_registration_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
			$result = runmysqlquery($query1);
			
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','96','".date('Y-m-d').' '.date('H:i:s')."','view_registration_report')";
			$eventresult = runmysqlquery($eventquery);
	
			$localdate = datetimelocal('Ymd');
			$localtime = datetimelocal('His');
			$filename = "viewregistrationreport".$localdate."-".$localtime.".htm";
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