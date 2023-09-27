<?php

ini_set('memory_limit', '2048M');

include('../functions/phpfunctions.php');

if(imaxgetcookie('userid')<> '') 
$userid = imaxgetcookie('userid');
else
{ 
	echo('Thinking to redirect');
	exit;
}
require_once '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// PHPExcel
//require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory
//require_once '../phpgeneration/PHPExcel/IOFactory.php';

$flag = $_POST['flag'];
 
if($flag == '')
{
	$url = '../home/index.php?a_link=smscreditssummary'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
	$query = "select 
	inv_mas_customer.businessname,inv_smsactivation.smsusername,inv_smsactivation.smsfromname,inv_smsactivation.usertype, 
	inv_smsactivation.contactperson,inv_smsactivation.cell,inv_smsactivation.emailid, ifnull(credits.totalcredits,0) as 
	totalcredits, utilizedcredits from inv_smsactivation left join (select smsuserid, sum(quantity) as totalcredits from 
	inv_smscredits group by smsuserid) as credits on credits.smsuserid = inv_smsactivation.slno 
	left join inv_mas_customer on inv_mas_customer.slno = inv_smsactivation.userreference where 
	inv_smsactivation.accounttype = 'promotional' and 
	inv_mas_customer.businessname is not null order by inv_smsactivation.slno";

	$result = runmysqlquery($query);
	
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
	$mySheet->setTitle('SMSCredits Details');

	//Apply style for header Row
	$mySheet->getStyle('A3:K3')->applyFromArray($styleArray);
	
	//Merge the cell
	$mySheet->mergeCells('A1:K1');
	$mySheet->mergeCells('A2:K2');
	$objPHPExcel->setActiveSheetIndex($pageindex)
				->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
				->setCellValue('A2', 'SMSCredits Details Report');
	$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
	$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
	$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
	
	//Fille contents for Header Row
	$objPHPExcel->setActiveSheetIndex($pageindex)
						->setCellValue('A3', 'Sl No')
						->setCellValue('B3', 'Company Name')
						->setCellValue('C3', 'Total Credits')
						->setCellValue('D3', 'Total Utilized')
						->setCellValue('E3', 'Balance Available')
						->setCellValue('F3', 'User Name')
						->setCellValue('G3', 'From Name')
						->setCellValue('H3', 'Contact person')
						->setCellValue('I3', 'Contact Number')
						->setCellValue('J3', 'Email ID')
						->setCellValue('K3', 'User Type');
				
	$j =4;
	$slno_count =0;		
	while($fetch = mysqli_fetch_array($result))
	{
		$totalcredits = $fetch['totalcredits'];
		$totalutilized = $fetch['utilizedcredits'];
		$balanceavailable = $totalcredits - $totalutilized;
		
		$slno_count++;
		$mySheet->setCellValue('A' . $j,$slno_count)
				->setCellValue('B' . $j,$fetch['businessname'])
				->setCellValue('C' . $j,$totalcredits)
				->setCellValue('D' . $j,$totalutilized)
				->setCellValue('E' . $j,$balanceavailable)
				->setCellValue('F' . $j,$fetch['smsusername'])
				->setCellValue('G' . $j,$fetch['smsfromname'])
				->setCellValue('H' . $j,$fetch['contactperson'])
				->setCellValue('I' . $j,$fetch['cell'])
				->setCellValue('J' . $j,$fetch['emailid'])
				->setCellValue('K' . $j,$fetch['usertype']);
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
	$mySheet->getColumnDimension('B')->setWidth(40);
	$mySheet->getColumnDimension('C')->setWidth(15);
	$mySheet->getColumnDimension('D')->setWidth(20);
	$mySheet->getColumnDimension('E')->setWidth(15);
	$mySheet->getColumnDimension('F')->setWidth(35);
	$mySheet->getColumnDimension('G')->setWidth(20);
	$mySheet->getColumnDimension('H')->setWidth(20);
	$mySheet->getColumnDimension('I')->setWidth(15);
	$mySheet->getColumnDimension('J')->setWidth(40);
	$mySheet->getColumnDimension('K')->setWidth(15);
	$pageindex++;
	
	$addstring = "/user";
	if($_SERVER['HTTP_HOST'] == "rashmihk" || $_SERVER['HTTP_HOST'] == "meghanab" || $_SERVER['HTTP_HOST'] == "bhumika")
		$addstring = "/saralimax-user";
	
			$query = 'select slno,fullname,username from inv_mas_users where slno = '.$userid.'';
			$fetchres = runmysqlqueryfetch($query);
			$username = $fetchres['username'];
			$localdate = datetimelocal('Ymd');
			$localtime = datetimelocal('His');
			$filebasename = "SMSCredits".$localdate."-".$localtime."-".strtolower($username).".xls";
	
			$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_SMSCredits_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
			$result = runmysqlquery($query1);
			
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','79','".date('Y-m-d').' '.date('H:i:s')."','excel_SMSCredits_report".'-'.strtolower($username)."')";
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
?>