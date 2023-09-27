<?php
//ini_set("display_errors",0);
ini_set('memory_limit', '2048M');

include('../functions/phpfunctions.php');
require_once '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// PHPExcel
//require_once '../phpgeneration/vendor/phpOffice/PhpSpreadsheet/Spreadsheet.php';

//require_once '../phpgeneration/PhpSpreadsheet/Writer/Xlsx.php';
//require_once '../phpgeneration/PhpSpreadsheet/Writer/Xls.php';

//PHPExcel_IOFactory
//require_once '../phpgeneration/vendor/phpOffice/PhpSpreadsheet/IOFactory.php';

$flag = $_POST['flag'];
if($flag == '')
{
	$url = '../home/index.php?a_link=contactdetails'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
	
	$checkvalue = $_POST['checkvalue'];
	$userid = imaxgetcookie('userid');
	$id = $_GET['id'];
	$todate = $_POST['todate'];
	$fromdate = $_POST['fromdate'];
	$productcode = $_POST['productcode'];
	$dealerid = $_POST['dealerid'];
	$region = $_POST['region'];
	$chks = $_POST['productname'];
	for ($i = 0;$i < count($chks);$i++)
	{
		$c_value .= "'" . $chks[$i] . "'" ."," ;
	}
	$productslist = rtrim($c_value , ',');
	$value = str_replace('\\','',$productslist);
	
	$seprecord = $_POST['seprecord'];
	$activecustomer_type = $_POST['activecustomer_type'];
	$reportdate = datetimelocal('d-m-Y');
	$reregenable = $_POST['reregenable'];
	$contact_type = $_POST['contact_type'];
	$branch = $_POST['branch'];
	$type = $_POST['type'];
	$category = $_POST['category'];
	$usagetype = $_POST['usagetype'];
	$purchasetype = $_POST['purchasetype'];
	$scheme = $_POST['scheme'];
	$reregistration  = $_POST['rereg'];
	$card  = $_POST['card'];
		$selectedfields = array("cusid","cusname","productname","date");
	$selectedphone=false;
		for($k=0;$k<count($checkvalue);$k++)
		{
			switch($checkvalue[$k])
			{
				case 'Address':
				{
					$addressvalue = (",inv_mas_customer.address as address");
					$selectedfields[] = "address";
				}
				break;
				case 'Place':
				{
					$selectedfields[] = "place";
					$placevalue = (",inv_mas_customer.place as place");
				}
				break;
				case 'Pincode':
				{
					$selectedfields[] = "pincode";
					$pincodevalue = (",inv_mas_customer.pincode as pincode");
				}
				break;
				case 'District':
				{
					$selectedfields[] = "district";
					$districtvalue = (",inv_mas_district.districtname as district");
				}
				break;
				case 'State':
				{
					$selectedfields[] = "state";
					$statevalue = (",inv_mas_state.statename as state");
				}
				break;
				case 'Contact Person':
				{
					$selectedfields[] = "contactperson";
					$contactpersonvalue = (", GROUP_CONCAT(inv_contactdetails.contactperson) as contactperson");
				}
				break;
				case 'Stdcode':
				{
					$selectedfields[] = "stdcode";
					$stdcode = (",inv_mas_customer.stdcode");
				}
				break;
				case 'Phone':
				{
					$selectedfields[] = "phone";
					$stdcode = (",inv_mas_customer.stdcode");
					$phonevalue = (",GROUP_CONCAT(inv_contactdetails.phone) as phone");
				}
				break;
				case 'Cell':
				{
					$selectedfields[] = "cell";
					$cellvalue = (",GROUP_CONCAT(inv_contactdetails.cell) as cell");
				}
				break;
				case 'Emailid':
				{
					$selectedfields[] = "emailid";
					$emailidvalue = (",GROUP_CONCAT(inv_contactdetails.emailid) as emailid");
				}
				break;
				case 'Region':
				{
					$selectedfields[] = "region";
					$regionvalue = (",inv_mas_region.category as region");
					$regionjoin = ("LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region ");
				}
				break;
				case 'Branch':
				{
					$selectedfields[] = "branch";
					$branchvalue = (",inv_mas_branch.branchname as branch");
					$branchjoin = ("LEFT JOIN inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch ");
				}
				break;
				case 'Type':
				{
					$selectedfields[] = "type";
					$typevalue = (",inv_mas_customertype.customertype as `type`");
					$typejoin = ("LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type ");
				}
				break;
				case 'Category':
				{
					$selectedfields[] = "category";
					$categoryvalue = (",inv_mas_customercategory.businesstype as category");
					$categoryjoin = ("LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category ");
				}
				break;
				case 'Website':
				{
					$selectedfields[] = "website";
					$websitevalue = (",inv_mas_customer.website as website");
				}
				break;
				case 'Dealer':
				{
					$selectedfields[] = "dealername";
					$dealervalue = (",inv_mas_dealer.businessname as dealername");
				}
				break;
				case 'Dealer Email':
				{
					$selectedfields[] = "dealeremail";
					$dealeremailvalue = (",inv_mas_dealer.emailid as dealeremail");
				}
				break;
				case 'Relyon Executive':
				{
					$selectedfields[] = "relyonexecutive";
					$relyonexecutive = (",inv_mas_dealer.relyonexecutive as relyonexecutive");
				}
				break;
				case 'Scheme':
				{
					$selectedfields[] = "schemename";
					$schemevalue = (",inv_mas_scheme.schemename");
				}
				break;
				case 'Purchase Type':
				{
					$selectedfields[] = "purchasetype";
					$purchasetypevalue = (",inv_dealercard.purchasetype");
				}
				break;
				case 'Usage Type':
				{
					$selectedfields[] = "usagetype";
					$usagetypevalue = (",inv_dealercard.usagetype");
				}
				break;
			}
		}
			
		$productcodepiece = ($chks == "")?(""):(" AND  inv_mas_product.productcode IN (".$value.") ");	
		$usagetypepiece = ($usagetype == "")?(""):(" AND inv_dealercard.usagetype = '".$usagetype."' ");
		$schemepiece = ($scheme == "")?(""):(" AND inv_mas_scheme.slno = '".$scheme."' ");
		$purchasetypepiece = ($purchasetype == "")?(""):(" AND inv_dealercard.purchasetype = '".$purchasetype."' ");
		$branchpiece = ($branch == "")?(""):(" AND inv_mas_customer.branch = '".$branch."'   ");
		$typepiece = ($type == "")?(""):(" AND inv_mas_customer.type = '".$type."' ");
		$activecustomerpiece = ($activecustomer_type == "")?(""):(" AND inv_mas_customer.activecustomer = '".$activecustomer_type."' ");
		$categorypiece = ($category == "")?(""):(" AND inv_mas_customer.category = '".$category."' ");
		//$productcodepiece = ($chks == "")?(""):(" AND  inv_mas_product.productcode IN (".$value.") ");
		$regionpiece = ($region == "")?(""):(" AND inv_mas_customer.region = '".$region."' ");
		
		if($card == "") {$cardpiece = ""; }
		elseif($card == 'withcard') {$cardpiece = " AND inv_customerproduct.cardid <> '' ";}
		elseif($card == 'withoutcard') {$cardpiece = " AND inv_customerproduct.cardid = '' ";}
		
		if($reregistration == "") {$reregistrationpiece = ""; }
		elseif($reregistration == 'yes') {$reregistrationpiece =  " AND inv_customerproduct.reregistration = 'yes' ";}
		elseif($reregistration == 'no') {$reregistrationpiece = " AND inv_customerproduct.reregistration = 'no' ";}
	
		$dealeridpiece = ($dealerid == "")?(""):("  AND inv_mas_dealer.slno = '".$dealerid."' ");
		$datepiece = (($todate == "") && ($fromdate == ""))?(""):("  AND (inv_customerproduct.date BETWEEN '".changedateformat($fromdate)."' AND '".changedateformat($todate)."')   ");
		$regionpiece = ($region == "")?(""):(" AND  inv_mas_customer.region = '".$region."'");
		//$productcodepiece = ($chks == "")?(""):(" AND inv_mas_product.productcode IN (".$value.") ");
		$dealeridpiece = ($dealerid == "")?(""):("  AND inv_mas_dealer.slno = '".$dealerid."' ");
		
		
		$query1 = "Drop table if exists custproductgroupwise;";
		$result1 = runmysqlquery($query1);
		
		$query2 = "CREATE TEMPORARY TABLE if not exists custproductgroupwise 
       (select  inv_customerproduct.date, inv_customerproduct.computerid, inv_customerproduct.customerreference, 
	   inv_customerproduct.dealerid,inv_customerproduct.cardid,inv_customerproduct.reregistration,inv_mas_product.productcode
	   from inv_customerproduct 
	   left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) 
       where inv_customerproduct.slno <> '123456789' ".$datepiece.$reregistrationpiece.$productcodepiece.$cardpiece." order by  	
	   inv_customerproduct.date desc);";
		$result2 = runmysqlquery($query2);		
		
		$query = "SELECT inv_mas_customer.slno,inv_mas_customer.customerid as cusid,inv_mas_customer.businessname as cusname ,inv_mas_district.districtname as district,inv_mas_product.productname,custproductgroupwise.date".$addressvalue.$placevalue.$pincodevalue.$districtvalue.$statevalue.$regionvalue.$branchvalue.$typevalue.$categoryvalue.$websitevalue.$dealervalue.$dealeremailvalue.$relyonexecutive.$schemevalue.$purchasetypevalue.$usagetypevalue.$stdcode."
FROM  custproductgroupwise
LEFT JOIN inv_mas_product on custproductgroupwise.productcode = inv_mas_product.productcode
LEFT JOIN inv_mas_customer on custproductgroupwise.customerreference= inv_mas_customer.slno
LEFT JOIN inv_dealercard on inv_dealercard.cardid = custproductgroupwise.cardid 
left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme 
LEFT JOIN inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district 
LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode 
LEFT JOIN inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer 
".$regionjoin.$branchjoin.$typejoin.$categoryjoin."
WHERE  inv_mas_customer.slno <> '99999999999999' ".$regionpiece.$dealeridpiece.$branchpiece.$typepiece.$categorypiece.$activecustomerpiece.$usagetypepiece.$purchasetypepiece.$schemepiece." group by inv_mas_customer.slno order by cusname";	
		$result = runmysqlquery($query);
    
   // echo $query;
    //exit();
	
	// Create new Spreadsheet object
	$objPHPExcel = new Spreadsheet();
	
	//Set Active Sheet	
	$mySheet = $objPHPExcel->getActiveSheet();
		
	//Define Style for header row
	$styleArray = array(
						'font' => array('bold' => true,),
						'fill'=> array('fillType'=>\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,'startColor'=> array('argb' => 'FFCCFFCC')),
						'borders' => array('allBorders'=> array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM))
					);
	$value = chr(69+(count($checkvalue))).'3';
	
	//Apply style for header Row
	$mySheet->getStyle('A3:'.$value.'')->applyFromArray($styleArray);
				
	//Merge the cell
	$mySheet->mergeCells('A1:U1');
	$mySheet->mergeCells('A2:U2');
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
				->setCellValue('A2', 'Customer Contact Details Report');
	$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
	$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
	$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
		
	//Fille contents for Header Row
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A3', 'Sl No')
			->setCellValue('B3', 'Customer ID')
			->setCellValue('C3', 'Customer Name')
			->setCellValue('D3', 'Product')
			->setCellValue('E3', 'Registration Date');
		for ($j=0; $j<count($checkvalue); $j++)
		{
				$assciivalue = chr(70+$j).'3';
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($assciivalue,$checkvalue[$j]);
		}
	
		$j =4;
		$slno= 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$querycontactdetails = "select customerid ".$contactpersonvalue.$phonevalue.$cellvalue.$emailidvalue." from inv_contactdetails where customerid = '".$fetch['slno']."'  group by customerid ";
			$resultquerycontactdetails = runmysqlquery($querycontactdetails);
			if(mysqli_num_rows($resultquerycontactdetails) <> 0 )
				$resultcontactdetails = runmysqlqueryfetch($querycontactdetails);
			
			$contactvalues = trim(removedoublecomma($resultcontactdetails['contactperson']),',');
			$phoneres =trim(removedoublecomma($resultcontactdetails['phone']),',');
			$cellres = trim(removedoublecomma($resultcontactdetails['cell']),',');
			$emailid = trim(removedoublecomma($resultcontactdetails['emailid']),',');
			switch($contact_type)
				{
					case 'uniquemailid':
					{
						for($i=0;$i<count($selectedfields);$i++)
						{
							if($selectedfields[$i] == "emailid")
							{
								//$emailid = $fetch[$selectedfields[$i]];
								$splitemailid = explode(',',$emailid);
								$countemailid = count($splitemailid);
								if($countemailid > 0)
								{
									for($l=0;$l < $countemailid ;$l++)
									{
										$slno++;
										$mySheet->setCellValue('A' . $j,$slno);
										for($m=0;$m<count($selectedfields);$m++)
										{
											$resultvalue = chr(66+$m).$j;
											if($selectedfields[$m] == "cusid")
												$mySheet->setCellValue($resultvalue,cusidcombine($fetch[$selectedfields[$m]]));
											elseif($selectedfields[$m] == "contactperson")
												$mySheet->setCellValue($resultvalue,str_replace(",",", ",trim($contactvalues)));
											elseif($selectedfields[$m] == "emailid")
												$mySheet->setCellValue($resultvalue,strtolower(trim($splitemailid[$l])));
											elseif($selectedfields[$m] == "phone")
												$mySheet->setCellValue($resultvalue,str_replace(",",", ",trim($phoneres)));
											elseif($selectedfields[$m] == "cell")
												$mySheet->setCellValue($resultvalue,str_replace(",",", ",trim($cellres)));
											else if($selectedfields[$m] == "stdcode")
												$mySheet->setCellValueExplicit($resultvalue,$fetch[$selectedfields[$m]]);
											else
												$mySheet->setCellValue($resultvalue,$fetch[$selectedfields[$m]]);
										
										}
										$j++;
												
									}
								}
							}
						}
					}
				break;
						
				case 'uniquecellno':
					{
						for($i=0;$i<count($selectedfields);$i++)
						{
							if($selectedfields[$i] == "cell")
							{
								$splitcell = explode(',',$cellres);
								$countcell = count($splitcell);
								if($countcell > 0)
								{
									for($l=0;$l < $countcell ;$l++)
									{
										$slno++;
										$mySheet->setCellValue('A' . $j,$slno);
										for($m=0;$m<count($selectedfields);$m++)
										{
											$resultvalue = chr(66+$m).$j;
											if($selectedfields[$m] == "cusid")
												$mySheet->setCellValue($resultvalue,cusidcombine($fetch[$selectedfields[$m]]));
											elseif($selectedfields[$m] == "contactperson")
												$mySheet->setCellValue($resultvalue,str_replace(",",", ",trim($contactvalues)));
											elseif($selectedfields[$m] == "emailid")
												$mySheet->setCellValue($resultvalue,strtolower($emailid));
											elseif($selectedfields[$m] == "phone")
												$mySheet->setCellValue($resultvalue,str_replace(",",", ",trim($phoneres)));
											elseif($selectedfields[$m] == "cell")
												$mySheet->setCellValue($resultvalue,trim($splitcell[$l]));
											else if($selectedfields[$m] == "stdcode")
												$mySheet->setCellValueExplicit($resultvalue,$fetch[$selectedfields[$m]]);
											else
												$mySheet->setCellValue($resultvalue,$fetch[$selectedfields[$m]]);
										
										}
										$j++;
												
									}
								}
							}
						}
						break;
					}
					default:
								
								$slno++;
								$mySheet->setCellValue('A' . $j,$slno);
								for($m=0; $m < count($selectedfields); $m++)
								{
									$resultvalue = chr(66+$m).$j;
									if($selectedfields[$m] == "cusid")
										$mySheet->setCellValue($resultvalue,cusidcombine($fetch[$selectedfields[$m]]));
									else if($selectedfields[$m] == "contactperson")
										$mySheet->setCellValue($resultvalue,str_replace(",",", ",trim($contactvalues)));
									else if($selectedfields[$m] == "phone")
										$mySheet->setCellValue($resultvalue,str_replace(",",", ",trim($phoneres)));
									else if($selectedfields[$m] == "cell")
										$mySheet->setCellValue($resultvalue,str_replace(",",", ",trim($cellres)));
									else if($selectedfields[$m] == "emailid")
										$mySheet->setCellValue($resultvalue,str_replace(",",", ",trim($emailid)));
									else if($selectedfields[$m] == "stdcode")
										$mySheet->setCellValueExplicit($resultvalue,$fetch[$selectedfields[$m]]);
									else
										$mySheet->setCellValue($resultvalue,$fetch[$selectedfields[$m]]);
									
								}
								$j++;
				}
	
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
		$mySheet->getColumnDimension('B')->setWidth(20);
		$mySheet->getColumnDimension('C')->setWidth(35);
		$mySheet->getColumnDimension('D')->setWidth(35);
		$mySheet->getColumnDimension('E')->setWidth(10);
		$setvalue = chr(67+(count($checkvalue)));
		$mySheet->getDefaultColumnDimension('F:'.$setvalue.'')->setWidth(35);
		
	
		if(($_SERVER['HTTP_HOST'] == "bhumika") || ($_SERVER['HTTP_HOST'] == "192.168.2.79"))
			$addstring = "/saralimax-user";
		else
			$addstring = "/user";
		
		if($id == 'toexcel')
		{
			$query = 'select slno,username from inv_mas_users where slno = '.$userid.'';
			$fetchres = runmysqlqueryfetch($query);	
			$username = $fetchres['username'];		
			$localdate = datetimelocal('Ymd');
			$localtime = datetimelocal('His');
			$filebasename = "CustomerContactDetails".$localdate."-".$localtime."-".strtolower($username).".xls";
			$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_contactdetails_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
			$result = runmysqlquery($query1);	
			
			$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filebasename;
			$downloadlink = 'http://'.$_SERVER['HTTP_HOST'].$addstring.'/filecreated/'.$filebasename;
			
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','90','".date('Y-m-d').' '.date('H:i:s')."','excel_contactdetails_report".'-'.strtolower($username)."')";
			$eventresult = runmysqlquery($eventquery);

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
			$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','view_contactdetails_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
			$result = runmysqlquery($query1);
	
			$localdate = datetimelocal('Ymd');
			$localtime = datetimelocal('His');
			$filename = "viewcontactdetailsreport".$localdate."-".$localtime.".htm";
			$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filename;
			
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','89','".date('Y-m-d').' '.date('H:i:s')."','view_contactdetails_report')";
			$eventresult = runmysqlquery($eventquery);

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
