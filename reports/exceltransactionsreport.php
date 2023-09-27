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
	$url = '../home/index.php?a_link=transactionsreport'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
	$userid = imaxgetcookie('userid');
	$id = $_GET['id'];
	$todate = $_POST['todate'];
	$fromdate = $_POST['fromdate'];
	$responsemessage = $_POST['responsemessage'];
	$paymentmode = $_POST['paymentmode'];
	$maxcount = $_POST['maxcount'];
	$searchrefresult = $_POST['searchrefresult'];
	$searchinput = $_POST['searchinput'];

	$chks = $_POST['productname'];
	for ($i = 0;$i < count($chks);$i++)
	{
		//$c_value .= "'" . $chks[$i] . "'" ."," ;
		if($i == 0)
		{
			$c_value = 'products like "%'.$chks[$i].'%"';
		}
		else
		{
			$c_value .= ' or products like "%'.$chks[$i].'%"';
		}
	}
	$productslist = rtrim($c_value , ',');
	$value = str_replace('\\','',$productslist);
	
	$reportdate = datetimelocal('d-m-Y');
	
	## Report fetching Details Query##
	/*$querydetail = "select customerid,company,contactperson,
					invoicenumber,amount,productname,orderid,
					responsemessage,pgtxnid,recordreference,citrus,date 
					from transactions
					where ";*/
					
	$querydetail = "select id AS `transaction`, productname as `product`, `date`, `time`, amount, company, contactperson, customerid,state, pincode,phone, emailid, citrus, razorpay,dbremarks as `Remarks` from transactions where ";
	
		if($todate <> '' && $fromdate <> '')
		{
			if($value!= "")
		   {
			  $productcodepiece = $value;
			
			if($productcodepiece != 'NONE' || $productcodepiece != '' )
			{
					$query1 = "select products, slno from inv_invoicenumbers where (".$productcodepiece.")";
					$result1 = runmysqlquery($query1);
					$i =0;
					while($fetch1 = mysqli_fetch_array($result1))
					{
						$recordslno = $fetch1['slno'];
						//echo $recordslno;
						if($recordslno <> '' || $recordslno <> NULL)
						{
							$recordchks = $recordslno;
							if($i==0)
							{
								$r_value = $recordchks;
								$i++;
							}
							else
							{
								$r_value .=','.$recordchks;
							}
						}
					}
					
					//print_r ($r_value);
		      }
			}
		    else
		    { 
		      $r_value = '';
		    }
			
			if($searchrefresult == "all") { $searchrefresultpiece = ""; } 
			
			if($paymentmode == 'both'){$paymentmodepiece = "";}
			elseif($paymentmode == 'citrus'){$paymentmodepiece = " AND citrus = 'Y' ";}
			elseif($paymentmode == 'razorpay'){$paymentmodepiece = " AND razorpay = 'Y' ";}
			elseif($paymentmode == 'card'){$paymentmodepiece = " AND ISNULL(citrus) AND razorpay = 'N' ";}
			if($r_value <> '')
			{
				$recordrefclause = " AND recordreference IN (".$r_value.") " ;
			}
			else if($r_value == '' && $value <> '')
			{
				$recordrefclause = " AND recordreference IN ('".$r_value."') " ;
			}
			else
			{ $recordrefclause ='';}
			
			if($responsemessage == '0'){$responsemessagepiece = "";}
			elseif($responsemessage == '1'){$responsemessagepiece = " AND responsemessage in ('Transaction Successful','SUCCESS') ";}
			elseif($responsemessage == '2'){$responsemessagepiece = " AND ISNULL(responsemessage) ";}			

			/*if($responsemessage == '0'){$responsemessagepiece = "";}
			elseif($responsemessage == '1'){$responsemessagepiece = " AND responsecode = 0 ";}
			elseif($responsemessage == '2'){$responsemessagepiece = " AND responsecode <> 0 ";}*/
			
			/*$responsemessagepiece = ($responsemessage == "")?(""):("  AND responsecode = '".$responsemessage."' ");*/
			$reversed = " AND reversed IS NULL";
			$datepiece = " date BETWEEN '".changedateformat($fromdate)."' AND '".changedateformat($todate)."'";
			
			$query = $querydetail.$datepiece.$responsemessagepiece.$recordrefclause.$searchrefresultpiece.$paymentmodepiece.$reversed." order by id desc; ";						
			
		}
			//echo($query);exit;
			$result = runicicidbquery($query);
		
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
		$mySheet->getStyle('A3:Q3')->applyFromArray($styleArray);
		
		//Merge the cell
		$mySheet->mergeCells('A1:Q1');
		$mySheet->mergeCells('A2:Q2');
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
					->setCellValue('A2', 'Transactions Details');
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1"A2')->getAlignment()->setWrapText(true);
	
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A3', 'Sl No')
					->setCellValue('B3', 'Transaction ID')
					->setCellValue('C3', 'Transaction Date')
					->setCellValue('D3', 'Transaction Time')
					->setCellValue('E3', 'Net Amount')
					->setCellValue('F3', 'Commission')
					->setCellValue('G3', 'Received Amount')
					->setCellValue('H3', 'Product')
					->setCellValue('I3', 'Company')
					->setCellValue('J3', 'Contact Person')
					->setCellValue('K3', 'Customer ID')
					->setCellValue('L3', 'State')
					->setCellValue('M3', 'Pincode')
					->setCellValue('N3', 'Phone')
					->setCellValue('O3', 'Email ID')
					->setCellValue('P3', 'Payment Gateway')
					->setCellValue('Q3', 'Additonal Remarks');
		$j =4;
		$slno = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$slno++;
			##Customer ID fromat##
			$custid = $fetch['customerid'];
			
			if($fetch['citrus'] == 'Y')
			{ 
				$paymode = 'Citrus';
				$commissionamt = $fetch['amount'] * (0.0271);
				$recievedamt = $fetch['amount'] - ($fetch['amount'] * (0.0271));
			}
			elseif ($fetch['razorpay'] == 'Y') 
			{
				$paymode = 'Razorpay';
				$commissionamt = $fetch['amount'] * (0.0236);
				$recievedamt = $fetch['amount'] - ($fetch['amount'] * (0.0236));
			}

			else
			{ 
				$paymode = 'ICICI';
				$commissionamt = $fetch['amount'] * (0.02729);
				$recievedamt = $fetch['amount'] - ($fetch['amount'] * (0.02729));
			}
			
			//round($number,2);number_format("1000000",2)
			$commission = number_format($commissionamt,2);
			$received = number_format($recievedamt,2);
			$netamount = number_format((float)$fetch['amount'],2);

			$mySheet->setCellValue('A' . $j,$slno)
					->setCellValue('B' . $j,$fetch['transaction'])
					->setCellValue('C' . $j,changedateformat($fetch['date']))
					->setCellValue('D' . $j,$fetch['time'])
					->setCellValue('E' . $j,$netamount)
					->setCellValue('F' . $j,$commission)
					->setCellValue('G' . $j,$received)
					->setCellValue('H' . $j,$fetch['product'])
					->setCellValue('I' . $j,$fetch['company'])
					->setCellValue('J' . $j,$fetch['contactperson'])
					->setCellValue('K' . $j,$custid)
					->setCellValue('L' . $j,$fetch['state'])
					->setCellValue('M' . $j,$fetch['pincode'])
					->setCellValue('N' . $j,$fetch['phone'])
					->setCellValue('O' . $j,$fetch['emailid'])
					->setCellValue('P' . $j,$paymode)
					->setCellValue('Q' . $j,$fetch['Remarks']);
				
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
		$mySheet->getColumnDimension('B')->setWidth(10);
		$mySheet->getColumnDimension('C')->setWidth(35);
		$mySheet->getColumnDimension('D')->setWidth(30);
		$mySheet->getColumnDimension('E')->setWidth(15);
		$mySheet->getColumnDimension('F')->setWidth(15);
		$mySheet->getColumnDimension('G')->setWidth(15);
		$mySheet->getColumnDimension('H')->setWidth(20);
		$mySheet->getColumnDimension('I')->setWidth(15);
		$mySheet->getColumnDimension('J')->setWidth(25);
		$mySheet->getColumnDimension('K')->setWidth(25);
		$mySheet->getColumnDimension('L')->setWidth(30);
		$mySheet->getColumnDimension('M')->setWidth(60);
		$mySheet->getColumnDimension('N')->setWidth(15);
		$mySheet->getColumnDimension('O')->setWidth(25);
		$mySheet->getColumnDimension('P')->setWidth(10);
		$mySheet->getColumnDimension('Q')->setWidth(25);
	
		
		if($_SERVER['HTTP_HOST'] == "bhumika" || $_SERVER['HTTP_HOST'] == "192.168.2.79")
		{ $addstring = "/saralimax-user"; }
		else{ $addstring = "/user"; }
		
		
		if($id == 'toexcel')
		{
			$query = 'select slno,username from inv_mas_users where slno = '.$userid.'';
			$fetchres = runmysqlqueryfetch($query);	
			$localdate = datetimelocal('Ymd');
			$localtime = datetimelocal('His');
			$filebasename = "Transcations-details".$localdate."-".$localtime."-".strtolower($fetchres['username']).".xls";
			
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
			$filename = "viewtransactionsreport".$localdate."-".$localtime.".htm";
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
