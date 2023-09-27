<?
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
$fromdate = changedateformat($_POST['fromdate']);
$todate = changedateformat($_POST['todate']);
$databasefield = $_POST['databasefield'];
$textfield = $_POST['searchcriteria'];
$modulename = $_POST['modulename'];
$eventtype = $_POST['eventtype'];
$pageshortname = $_POST['pageshortname'];
$generatedby = $_POST['username'];
$generatedbysplit = explode('^',$generatedby);
$eventtypesplit = explode('^',$eventtype);
$pageshortnamesplit = explode('^',$pageshortname);

if($generatedbysplit[1] == "[U]")
	$modulepiece = 'USER';
elseif($generatedbysplit[1] == "[D]")
	$modulepiece = 'DEALER';
elseif($generatedbysplit[1] == "[I]")
	$modulepiece = 'IMPLEMENTATION';
	
if($eventtypesplit[1] == "[U]")
	$eventpiece = 'USER';
elseif($eventtypesplit[1] == "[D]")
	$eventpiece = 'DEALER';
elseif($eventtypesplit[1] == "[I]")
	$eventpiece = 'IMPLEMENTATION';
	
if($pageshortnamesplit[1] == "[U]")
	$pageshortpiece = 'USER';
elseif($pageshortnamesplit[1] == "[D]")
	$pageshortpiece = 'DEALER';
elseif($pageshortnamesplit[1] == "[I]")
	$pageshortpiece = 'IMPLEMENTATION';

$generatedpiece = ($generatedby == "")?(""):(" AND inv_logs_event.userid = '".$generatedbysplit[0]."' and inv_logs_eventtype.modulename = '".$modulepiece."'");
$eventtypepiece = ($eventtype == "")?(""):(" AND inv_logs_eventtype.slno = '".$eventtypesplit[0]."' and inv_logs_eventtype.modulename = '".$eventpiece."'");
$modulenamepiece = ($modulename == "")?(""):(" AND inv_logs_eventtype.modulename = '".strtoupper($modulename)."' ");
$pageshortnamepiece = ($pageshortname == "")?(""):(" AND inv_logs_eventtype.pagesshortname = '".$pageshortnamesplit[0]."' and inv_logs_eventtype.modulename = '".$pageshortpiece."'");

if($flag == '')
{
	$url = '../home/index.php?a_link=activitylog'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
	switch($databasefield)
		{
			case "userid":
					
				$query = "select distinct inv_logs_event.slno, inv_logs_event.remarks, inv_logs_event.eventdatetime as `datetime`, 
inv_logs_eventtype.modulename, inv_logs_eventtype.eventtype, inv_logs_eventtype.pagesshortname,
CASE WHEN inv_logs_eventtype.modulename = 'USER'  THEN inv_mas_users.username  WHEN inv_logs_eventtype.modulename = 'DEALER' THEN inv_mas_dealer.businessname ELSE inv_mas_implementer.businessname END AS usernametype,inv_logs_event.system
from inv_logs_event left join inv_logs_eventtype on inv_logs_eventtype.slno = inv_logs_event.eventtype
left join inv_mas_users on inv_mas_users.slno =  inv_logs_event.userid and  inv_logs_eventtype.modulename = 'USER'
left join inv_mas_dealer on inv_mas_dealer.slno =  inv_logs_event.userid and  inv_logs_eventtype.modulename = 'DEALER'
left join inv_mas_implementer on inv_mas_implementer.slno =  inv_logs_event.userid and inv_logs_eventtype.modulename = 'IMPLEMENTATION'
where (left(inv_logs_event.eventdatetime,10) between '".$fromdate."' AND '".$todate."') AND inv_logs_event.userid LIKE '%".$textfield."%' ".$generatedpiece.$eventtypepiece.$modulenamepiece.$paymentmodepiece.$pageshortnamepiece."  order by inv_logs_event.slno ";
				break;
			case "systemip":
				$query = "select distinct inv_logs_event.slno, inv_logs_event.remarks, inv_logs_event.eventdatetime as `datetime`, 
inv_logs_eventtype.modulename, inv_logs_eventtype.eventtype, inv_logs_eventtype.pagesshortname,
CASE WHEN inv_logs_eventtype.modulename = 'USER'  THEN inv_mas_users.username  WHEN inv_logs_eventtype.modulename = 'DEALER' THEN inv_mas_dealer.businessname ELSE inv_mas_implementer.businessname END AS usernametype,inv_logs_event.system
from inv_logs_event left join inv_logs_eventtype on inv_logs_eventtype.slno = inv_logs_event.eventtype
left join inv_mas_users on inv_mas_users.slno =  inv_logs_event.userid and  inv_logs_eventtype.modulename = 'USER'
left join inv_mas_dealer on inv_mas_dealer.slno =  inv_logs_event.userid and  inv_logs_eventtype.modulename = 'DEALER'
left join inv_mas_implementer on inv_mas_implementer.slno =  inv_logs_event.userid and inv_logs_eventtype.modulename = 'IMPLEMENTATION'
where (left(inv_logs_event.eventdatetime,10) between '".$fromdate."' AND '".$todate."') AND  inv_logs_event.system LIKE '%".$textfield."%' ".$generatedpiece.$eventtypepiece.$modulenamepiece.$paymentmodepiece.$pageshortnamepiece."  order by inv_logs_event.slno";
				break;
			default:
				$query = "select distinct inv_logs_event.slno, inv_logs_event.remarks as remarks, inv_logs_event.eventdatetime as `datetime`,inv_logs_eventtype.modulename, inv_logs_eventtype.eventtype, inv_logs_eventtype.pagesshortname,
CASE WHEN inv_logs_eventtype.modulename = 'USER'  THEN inv_mas_users.username  WHEN inv_logs_eventtype.modulename = 'DEALER' THEN inv_mas_dealer.businessname ELSE inv_mas_implementer.businessname END AS usernametype,inv_logs_event.system
from inv_logs_event left join inv_logs_eventtype on inv_logs_eventtype.slno = inv_logs_event.eventtype
left join inv_mas_users on inv_mas_users.slno =  inv_logs_event.userid and  inv_logs_eventtype.modulename = 'USER'
left join inv_mas_dealer on inv_mas_dealer.slno =  inv_logs_event.userid and  inv_logs_eventtype.modulename = 'DEALER'
left join inv_mas_implementer on inv_mas_implementer.slno =  inv_logs_event.userid and inv_logs_eventtype.modulename = 'IMPLEMENTATION'
where (left(inv_logs_event.eventdatetime,10) between '".$fromdate."' AND '".$todate."') AND  inv_logs_eventtype.modulename LIKE '%".$textfield."%' ".$generatedpiece.$eventtypepiece.$modulenamepiece.$paymentmodepiece.$pageshortnamepiece."  order by inv_logs_event.slno ";
				break;
		} 
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
		$mySheet->setTitle('Activity Log Details');
			
		//Apply style for header Row
		$mySheet->getStyle('A3:H3')->applyFromArray($styleArray);
		
		//Merge the cell
		$mySheet->mergeCells('A1:H1');
		$mySheet->mergeCells('A2:H2');
		$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'Activity Log Report');
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
	
		
		//Fille contents for Header Row
		$objPHPExcel->setActiveSheetIndex($pageindex)
							->setCellValue('A3', 'Sl No')
							->setCellValue('B3', 'Module Name')
							->setCellValue('C3', 'Event Type')
							->setCellValue('D3', 'Pages Short Names')
							->setCellValue('E3', 'Username')
							->setCellValue('F3', 'Remarks')
							->setCellValue('G3', 'Date')
							->setCellValue('H3', 'System IP');
							

		$j =4;
		$slno_count =0;
		while($fetch = mysqli_fetch_array($result))
		{
			
			$slno_count++;
			$mySheet->setCellValue('A' . $j,$slno_count)
					->setCellValue('B' . $j,$fetch['modulename'])
					->setCellValue('C' . $j,$fetch['eventtype'])
					->setCellValue('D' . $j,$fetch['pagesshortname'])
					->setCellValue('E' . $j,$fetch['usernametype'])
					->setCellValue('F' . $j,$fetch['remarks'])
					->setCellValue('G' . $j,changedateformatwithtime($fetch['datetime']))
					->setCellValue('H' . $j,$fetch['system']);
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
				$mySheet->getColumnDimension('C')->setWidth(30);
				$mySheet->getColumnDimension('D')->setWidth(30);
				$mySheet->getColumnDimension('E')->setWidth(30);
				$mySheet->getColumnDimension('F')->setWidth(15);
				$mySheet->getColumnDimension('G')->setWidth(20);
				$mySheet->getColumnDimension('H')->setWidth(20);

		$addstring = "/user";
		if($_SERVER['HTTP_HOST'] == "rashmihk" || $_SERVER['HTTP_HOST'] == "meghanab" )
			$addstring = "/saralimax-user";
		
				$query = 'select slno,fullname from inv_mas_users where slno = '.$userid.'';
				$fetchres = runmysqlqueryfetch($query);
				$username = $fetchres['fullnaame'];
				$localdate = datetimelocal('Ymd');
				$localtime = datetimelocal('His');
				$filebasename = "Activitylogs".$localdate."-".$localtime."-".strtolower($username).".xls";
	
				$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_activity_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
				$result = runmysqlquery($query1);
				
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','79','".date('Y-m-d').' '.date('H:i:s')."','excel_activity_report".'-'.strtolower($username)."')";
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
