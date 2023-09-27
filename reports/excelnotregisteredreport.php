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
	$url = '../home/index.php?a_link=notregistered'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
	$userid = imaxgetcookie('userid');
	$dealerid = $_POST['dealerid'];
	$branch = $_POST['branch'];
	$region = $_POST['region'];
	$summarize = $_POST['summarize'];
	$fromdate = changedateformat($_POST['fromdate']);
	$todate = changedateformat($_POST['todate']);
	$generatedby = $_POST['generatedby'];
	$generatedbysplit = explode('^',$generatedby);
	$alltimecheck = $_POST['alltime'];
	
    //$chks = $_POST['selectproduct'];
    $chks = $_POST['productarray'];
	for ($i = 0;$i < count($chks);$i++)
	{
		$c_value .= $chks[$i]."," ;
	}
	
	$value = rtrim($c_value , ',');
	$productslist = str_replace('\\','',$value);
	
    $productlistsplit = explode(',',$productslist);
	$productlistsplitcount = count($productlistsplit);


	if(in_array('dealerwise', $summarize, true))
		$dealerwise = 'dealerwise';
	else
		$dealerwise = '';
	
	if(in_array('customerwise', $summarize, true))
		$customerwise = 'customerwise';
	else
		$customerwise = '';
	
	if(in_array('regionwise', $summarize, true))
		$regionwise = 'regionwise';
	else
		$regionwise = '';
		
	if(in_array('branchwise', $summarize, true))
		$branchwise = 'branchwise';
	else
		$branchwise = '';
		
		
	$regionpiece = ($region == "")?(""):(" AND inv_invoicenumbers.regionid = '".$region."' ");
	$dealerpiece = ($dealerid == "")?(""):("AND  inv_invoicenumbers.dealerid = '".$dealerid."' ");
	$branchpiece = ($branch == "")?(""):("AND  inv_invoicenumbers.branchid = '".$branch."' ");
	$modulepiece = ($generatedbysplit[1] == "[U]")?("user_module"):("dealer_module");
	$generatedpiece = ($generatedby == "")?(""):(" AND inv_invoicenumbers.createdbyid = '".$generatedbysplit[0]."' and inv_invoicenumbers.module = '".$modulepiece."'");
	$datepiece = ($alltimecheck == 'on')?(""):(" AND (left(inv_invoicenumbers.createddate,10) between '".$fromdate."' AND '".$todate."') ");


	if($chks != '')
	{
		for($i = 0;$i< $productlistsplitcount; $i++)
		{
			if($i < ($productlistsplitcount-1))
				$appendor = 'or'.' ';
			else
				$appendor = '';
		
	  $finalproductlist .= ' inv_invoicenumbers.products'.' '.'like'.' "'.'%'.$productlistsplit[$i].'%'.'" '.$appendor."";
		}
	}
	if($chks != '')
	
     $finallistarray = ' AND ('.$finalproductlist.')';
	

 $query2 = "create temporary table prd17 (select distinct inv_invoicenumbers.slno as invoiceno,inv_invoicenumbers.dealerid
,inv_invoicenumbers.regionid,inv_invoicenumbers.branchid from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '1' and
DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) <= '7')) and inv_invoicenumbers.description <> '' 
and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' ".$finallistarray.$regionpiece.$dealerpiece.$branchpiece.$generatedpiece.$datepiece."
group by inv_invoicenumbers.slno) ;";
	$result2 = runmysqlquery($query2);
	
	$query2 = "ALTER table prd17 add index (invoiceno);";
	$result2 = runmysqlquery($query2);
	
	
 	$query2 = "create temporary table prd815 (select distinct inv_invoicenumbers.slno as invoiceno,inv_invoicenumbers.dealerid
,inv_invoicenumbers.regionid,inv_invoicenumbers.branchid from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '8' and
DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) <= '15')) and inv_invoicenumbers.description <> '' 
and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no'  ".$finallistarray.$regionpiece.$dealerpiece.$branchpiece.$generatedpiece.$datepiece."
group by inv_invoicenumbers.slno);";
	$result2 = runmysqlquery($query2);
	
	$query2 = "ALTER table prd815 add index (invoiceno);";
	$result2 = runmysqlquery($query2);
		
 $query2 = "create temporary table prd1630 (select distinct inv_invoicenumbers.slno as invoiceno,inv_invoicenumbers.dealerid
,inv_invoicenumbers.regionid,inv_invoicenumbers.branchid from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '16' and
DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) <= '30')) and inv_invoicenumbers.description <> '' 
and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no'  ".$finallistarray.$regionpiece.$dealerpiece.$branchpiece.$generatedpiece.$datepiece."
group by inv_invoicenumbers.slno);";
	$result2 = runmysqlquery($query2);
	
	$query2 = "ALTER table prd1630 add index (invoiceno);";
	$result2 = runmysqlquery($query2);
	
	$query2 = "create temporary table prd3160 (select distinct inv_invoicenumbers.slno as invoiceno,inv_invoicenumbers.dealerid
,inv_invoicenumbers.regionid,inv_invoicenumbers.branchid from inv_invoicenumbers 
left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno 
left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '31' and
DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))	<= '60')) and inv_invoicenumbers.description <> '' 
and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no'  ".$finallistarray.$regionpiece.$dealerpiece.$branchpiece.$generatedpiece.$datepiece."
group by inv_invoicenumbers.slno)";
	$result2 = runmysqlquery($query2);
	
	$query2 = "ALTER table prd3160 add index (invoiceno);";
	$result2 = runmysqlquery($query2);
	
	$query2 = "create temporary  table prd61 (select distinct inv_invoicenumbers.slno as invoiceno,inv_invoicenumbers.dealerid
,inv_invoicenumbers.regionid,inv_invoicenumbers.branchid from inv_invoicenumbers 
left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno 
left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '61')) and inv_invoicenumbers.description <> '' 
and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' ".$finallistarray.$regionpiece.$dealerpiece.$branchpiece.$generatedpiece.$datepiece." 
group by inv_invoicenumbers.slno)";
	$result2 = runmysqlquery($query2);
	
	$query2 = "ALTER table prd61 add index (invoiceno);";
	$result2 = runmysqlquery($query2);
	
		
	$query2 = "create  temporary table yearwise_customers (select distinct inv_mas_dealer.slno as dealerid,
	inv_mas_dealer.businessname as dealer,inv_mas_branch.branchname as branch,inv_mas_region.category as region,prd17.invoiceno as billcount17,
	prd815.invoiceno as billcount815,prd1630.invoiceno as billcount1630,prd3160.invoiceno as billcount3160,prd61.invoiceno as billcount61 from inv_invoicenumbers 
	left join prd17 on inv_invoicenumbers.slno = prd17.invoiceno left join prd815 on inv_invoicenumbers.slno = prd815.invoiceno left join prd1630 on
	inv_invoicenumbers.slno = prd1630.invoiceno left join prd3160 on inv_invoicenumbers.slno = prd3160.invoiceno left join prd61 on inv_invoicenumbers.slno = prd61.invoiceno 
	left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid left join inv_mas_region on inv_mas_region.slno = inv_invoicenumbers.regionid 
	left join inv_mas_branch on inv_mas_branch.slno = inv_invoicenumbers.branchid where (prd17.invoiceno IS NOT NULL OR prd815.invoiceno IS NOT NULL OR prd1630.invoiceno 
	IS NOT NULL OR prd3160.invoiceno IS NOT NULL OR prd61.invoiceno IS NOT NULL) group by inv_invoicenumbers.slno) ";
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
	$styleArray1 = array(
					'font' => array('bold' => true,),
					'borders' => array('allBorders'=> array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM))
				);
	$pageindex = 0;
	
	if($customerwise == "customerwise")
	{

		$mySheet = $objPHPExcel->getActiveSheet();
		//Set the worksheet name
		$mySheet->setTitle('Customer wise');
		
		//Merge the cell
		$mySheet->mergeCells('A1:I1');
		$mySheet->mergeCells('A2:I2');
		$objPHPExcel->setActiveSheetIndex($pageindex)
						->setCellValue('A1', 'Relyon Softech Limited, Bangalore')
						->setCellValue('A2', 'Not registered (Ageing) Report');
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
	
		//Begin writing Customer wise 
		$currentrow = 4;
		
		//Set heading summary
		$mySheet->setCellValue('A'.$currentrow,'Customer wise');
		$mySheet->getStyle('A'.$currentrow)->getFont()->setSize(12); 	
		$mySheet->getStyle('A'.$currentrow)->getFont()->setBold(true);
		
		$currentrow++;
		//Set table headings
		$mySheet->setCellValue('B'.$currentrow,'Sl No')
		        ->setCellValue('C'.$currentrow,'CustomerId')
				->setCellValue('D'.$currentrow,'Name of Customer')
				->setCellValue('E'.$currentrow,'EmailId')
				->setCellValue('F'.$currentrow,'Branch')
				->setCellValue('G'.$currentrow,'Bill Number')
				->setCellValue('H'.$currentrow,'Pin Serial')
				->setCellValue('I'.$currentrow,'Pin Number')
				->setCellValue('J'.$currentrow,'Invoice Date')
				->setCellValue('K'.$currentrow,'Product Name')
			    ->setCellValue('L'.$currentrow,'Dealer')
				->setCellValue('M'.$currentrow,'Dealer EmailId')
				->setCellValue('N'.$currentrow,'Dealer Cell')
				->setCellValue('O'.$currentrow,'Generated By')
				->setCellValue('P'.$currentrow,'Invoice Amount')
				->setCellValue('Q'.$currentrow,'Delay (number of days)');
				
		//Apply style for header Row
		$mySheet->getStyle('B'.$currentrow.':Q'.$currentrow)->applyFromArray($styleArray);
		
		//Set table data
		$currentrow++;
		$query = "select distinct inv_invoicenumbers.dealerid,inv_invoicenumbers.businessname as cusname,
inv_invoicenumbers.customerid,inv_invoicenumbers.branch,inv_invoicenumbers.emailid as customermail, inv_mas_dealer.businessname as dealerbusinessname,inv_mas_dealer.emailid as emailid,inv_mas_dealer.cell as cell, 
DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) as datediffcount, 
 inv_invoicenumbers.invoiceno as invoiceno,left(inv_invoicenumbers.createddate,10) as createddate,
inv_mas_product.productname AS productname, inv_mas_dealer.businessname as dealername,inv_invoicenumbers.createdby as createdby,
inv_mas_scratchcard.scratchnumber as pinno, inv_mas_scratchcard.cardid as cardid,inv_invoicenumbers.netamount as netamount
from inv_invoicenumbers 
left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno
left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
where  inv_invoicenumbers.description <> '' and inv_invoicenumbers.products <> '' 
and  inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' ".$finallistarray.$regionpiece.$dealerpiece.$branchpiece.$generatedpiece.$datepiece." group by inv_invoicenumbers.slno,inv_mas_scratchcard.cardid  order by DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) desc ";
		$result = runmysqlquery($query);

		$slno_count = 0;
		$databeginrow = $currentrow;
		while($row_data = mysqli_fetch_array($result))
		{
			$slno_count++;
			$mySheet->setCellValue('B'.$currentrow,$slno_count)
			        ->setCellValue('C'.$currentrow,$row_data['customerid'])
					->setCellValue('D'.$currentrow,$row_data['cusname'])
					->setCellValue('E'.$currentrow,$row_data['customermail'])
					->setCellValue('F'.$currentrow,$row_data['branch'])
					->setCellValue('G'.$currentrow,$row_data['invoiceno'])
					->setCellValue('H'.$currentrow,$row_data['cardid'])
					->setCellValue('I'.$currentrow,$row_data['pinno'])
					->setCellValue('J'.$currentrow,changedateformat($row_data['createddate']))
					->setCellValue('K'.$currentrow,$row_data['productname'])
					->setCellValue('L'.$currentrow,$row_data['dealername'])
					->setCellValue('M'.$currentrow,$row_data['emailid'])
					->setCellValue('N'.$currentrow,$row_data['cell'])
					->setCellValue('O'.$currentrow,$row_data['createdby'])
					->setCellValue('P'.$currentrow,$row_data['netamount'])
					->setCellValue('Q'.$currentrow,$row_data['datediffcount']);
			$currentrow++;
		}
		
		$mySheet->getStyle('Q'.($databeginrow).':Q'.($currentrow-1))->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color( \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED) );
		//Apply style for Content row
		$mySheet->getStyle('B'.($databeginrow-1).':Q'.($currentrow-1))->applyFromArray($styleArrayContent);
		
		//set the default width for column 
		$mySheet->getColumnDimension('A')->setWidth(15);
		$mySheet->getColumnDimension('B')->setWidth(8);
		$mySheet->getColumnDimension('C')->setWidth(20);
		$mySheet->getColumnDimension('D')->setWidth(40);
		$mySheet->getColumnDimension('E')->setWidth(40);
		$mySheet->getColumnDimension('F')->setWidth(20);
		$mySheet->getColumnDimension('G')->setWidth(20);
		$mySheet->getColumnDimension('H')->setWidth(15);
		$mySheet->getColumnDimension('I')->setWidth(22);
		$mySheet->getColumnDimension('J')->setWidth(15);
		$mySheet->getColumnDimension('K')->setWidth(30);
		$mySheet->getColumnDimension('L')->setWidth(20);
		$mySheet->getColumnDimension('M')->setWidth(20);
		$mySheet->getColumnDimension('N')->setWidth(20);
		$mySheet->getColumnDimension('O')->setWidth(20);
		$mySheet->getColumnDimension('P')->setWidth(20);
		$mySheet->getColumnDimension('Q')->setWidth(20);
		$pageindex++;
		//Begin with Worksheet 2 (Summary)
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($pageindex);
	}
	
	if($dealerwise == "dealerwise")
	{
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet();
		
		//Set the worksheet name
		$mySheet->setTitle('Dealer wise');
		
		//Merge the cell
		$mySheet->mergeCells('A1:G1');
		$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('A1', 'Dealer wise');
		$mySheet->getStyle('A1')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1')->getFont()->setBold(true); 
		$mySheet->getStyle('A1')->getAlignment()->setWrapText(true);
	
		
		//Begin writing dealer wise 
		$currentrow = 3;
		
		
		$currentrow++;
		//Set table headings
		$mySheet->setCellValue('B'.$currentrow,'Dealer');
		$mySheet->mergeCells('C'.$currentrow.':G'.$currentrow);
		$mySheet->setCellValue('C'.$currentrow,'Billed but Not Registered for (Days)');
		
		$mySheet->getStyle('B'.$currentrow.':G'.$currentrow)->applyFromArray($styleArray1);
		$mySheet->getStyle('B'.$currentrow.':G'.$currentrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		
		$currentrow++;
		$mySheet->setCellValue('C'.$currentrow,'1-7')
				->setCellValue('D'.$currentrow,'8-15')
				->setCellValue('E'.$currentrow,'16-30')
				->setCellValue('F'.$currentrow,'31-60')
				->setCellValue('G'.$currentrow,'> 61');
				
		//Apply style for header Row
		$mySheet->getStyle('B'.$currentrow)->applyFromArray($styleArray1);
		$mySheet->getStyle('C'.$currentrow.':G'.$currentrow)->applyFromArray($styleArray);
		$mySheet->getStyle('C'.$currentrow.':G'.$currentrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		
	
		$currentrow++;
		$query = "select dealer, count(billcount17 or null) as billcount17, count(billcount815 or null) as billcount815, 
count(billcount1630 or null) as  billcount1630, count(billcount3160 or null) as billcount3160, count(billcount61 or null) as billcount61 from yearwise_customers group by dealer  order by dealer;";
		$result = runmysqlquery($query);
		
		//Insert data
		$databeginrow = $currentrow;			
		if(mysqli_num_rows($result) <> 0)
		{
			while($row_data = mysqli_fetch_array($result))
			{
				$mySheet->setCellValue('B'.$currentrow,$row_data['dealer'])
						->setCellValue('C'.$currentrow,$row_data['billcount17'])
						->setCellValue('D'.$currentrow,$row_data['billcount815'])
						->setCellValue('E'.$currentrow,$row_data['billcount1630'])
						->setCellValue('F'.$currentrow,$row_data['billcount3160'])
						->setCellValue('G'.$currentrow,$row_data['billcount61']);
				$currentrow++;
			}
		}
		else
		{
			$mySheet->setCellValue('B'.$currentrow,'')
					->setCellValue('C'.$currentrow,'0')
					->setCellValue('D'.$currentrow,'0')
					->setCellValue('E'.$currentrow,'0')
					->setCellValue('F'.$currentrow,'0')
					->setCellValue('G'.$currentrow,'0');
				$currentrow++;
		}
		
		//Insert Total
		$mySheet->setCellValue('B'.$currentrow,'Total')
				->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
				->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
				->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
				->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
				->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")");
		$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
		
		//Apply style for Content row
		$mySheet->getStyle('B'.$currentrow.':G'.$currentrow)->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color( \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED ) );
		$mySheet->getStyle('B'.$databeginrow.':G'.$currentrow)->applyFromArray($styleArrayContent);
		
		//set the default width for column
		$mySheet->getColumnDimension('A')->setWidth(15);
		$mySheet->getColumnDimension('B')->setWidth(40);
		$mySheet->getColumnDimension('C')->setWidth(20);
		$mySheet->getColumnDimension('D')->setWidth(20);
		$mySheet->getColumnDimension('E')->setWidth(20);
		$mySheet->getColumnDimension('F')->setWidth(20);
		$mySheet->getColumnDimension('G')->setWidth(20);
		$pageindex++;
		//Begin with Worksheet 2 (Summary)
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($pageindex);
		
	}
	if($regionwise == "regionwise")
	{
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet();
		
		//Set the worksheet name
		$mySheet->setTitle('Region wise');
		
		//Merge the cell
		$mySheet->mergeCells('A1:G1');
		$mySheet->setCellValue('A1', 'Region wise');
		$mySheet->getStyle('A1')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1')->getFont()->setBold(true); 
		$mySheet->getStyle('A1')->getAlignment()->setWrapText(true);
	
		
		//Begin writing region wise 
		$currentrow = 3;
		
		
		$currentrow++;
		//Set table headings
		$mySheet->setCellValue('B'.$currentrow,'Region');
		$mySheet->mergeCells('C'.$currentrow.':G'.$currentrow);
		$mySheet->setCellValue('C'.$currentrow,'Billed but Not Registered for (Days)');
		
		$mySheet->getStyle('B'.$currentrow.':G'.$currentrow)->applyFromArray($styleArray1);
		$mySheet->getStyle('B'.$currentrow.':G'.$currentrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		
		$currentrow++;
		$mySheet->setCellValue('C'.$currentrow,'1-7')
				->setCellValue('D'.$currentrow,'8-15')
				->setCellValue('E'.$currentrow,'16-30')
				->setCellValue('F'.$currentrow,'31-60')
				->setCellValue('G'.$currentrow,'> 61');
				
		//Apply style for header Row
		$mySheet->getStyle('B'.$currentrow)->applyFromArray($styleArray1);
		$mySheet->getStyle('C'.$currentrow.':G'.$currentrow)->applyFromArray($styleArray);
		$mySheet->getStyle('C'.$currentrow.':G'.$currentrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		
	
		$currentrow++;
		$query = "select region, count(billcount17 or null) as billcount17, count(billcount815 or null) as billcount815, 
count(billcount1630 or null) as  billcount1630, count(billcount3160 or null) as billcount3160, count(billcount61 or null) as billcount61 from yearwise_customers group by region  order by region;";
		$result = runmysqlquery($query);
		
		//Insert data
		$databeginrow = $currentrow;			
		if(mysqli_num_rows($result) <> 0)
		{
			while($row_data = mysqli_fetch_array($result))
			{
				$mySheet->setCellValue('B'.$currentrow,$row_data['region'])
						->setCellValue('C'.$currentrow,$row_data['billcount17'])
						->setCellValue('D'.$currentrow,$row_data['billcount815'])
						->setCellValue('E'.$currentrow,$row_data['billcount1630'])
						->setCellValue('F'.$currentrow,$row_data['billcount3160'])
						->setCellValue('G'.$currentrow,$row_data['billcount61']);
				$currentrow++;
			}
		}
		else
		{
			$mySheet->setCellValue('B'.$currentrow,'')
					->setCellValue('C'.$currentrow,'0')
					->setCellValue('D'.$currentrow,'0')
					->setCellValue('E'.$currentrow,'0')
					->setCellValue('F'.$currentrow,'0')
					->setCellValue('G'.$currentrow,'0');
				$currentrow++;
		}
		//Insert Total
		$mySheet->setCellValue('B'.$currentrow,'Total')
				->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
				->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
				->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
				->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
				->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")");
		$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
		
		//Apply style for Content row
		$mySheet->getStyle('B'.$currentrow.':G'.$currentrow)->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color( \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED ) );
		$mySheet->getStyle('B'.$databeginrow.':G'.$currentrow)->applyFromArray($styleArrayContent);
		
		//set the default width for column
		$mySheet->getColumnDimension('A')->setWidth(15);
		$mySheet->getColumnDimension('B')->setWidth(40);
		$mySheet->getColumnDimension('C')->setWidth(20);
		$mySheet->getColumnDimension('D')->setWidth(20);
		$mySheet->getColumnDimension('E')->setWidth(20);
		$mySheet->getColumnDimension('F')->setWidth(20);
		$mySheet->getColumnDimension('G')->setWidth(20);
		$pageindex++;
		//Begin with Worksheet 2 (Summary)
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($pageindex);
		
	}
	
	if($branchwise == "branchwise")
	{
		//Set Active Sheet	
		$mySheet = $objPHPExcel->getActiveSheet();
		
		//Set the worksheet name
		$mySheet->setTitle('Branch wise');
		
		//Merge the cell
		$mySheet->mergeCells('A1:G1');
		$mySheet->setCellValue('A1', 'Branch wise');
		$mySheet->getStyle('A1')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1')->getFont()->setBold(true); 
		$mySheet->getStyle('A1')->getAlignment()->setWrapText(true);
	
		
		//Begin writing branch wise 
		$currentrow = 3;
		
		
		$currentrow++;
		//Set table headings
		$mySheet->setCellValue('B'.$currentrow,'Branch');
		$mySheet->mergeCells('C'.$currentrow.':G'.$currentrow);
		$mySheet->setCellValue('C'.$currentrow,'Billed but Not Registered for (Days)');
		
		$mySheet->getStyle('B'.$currentrow.':G'.$currentrow)->applyFromArray($styleArray1);
		$mySheet->getStyle('B'.$currentrow.':G'.$currentrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		
		$currentrow++;
		$mySheet->setCellValue('C'.$currentrow,'1-7')
				->setCellValue('D'.$currentrow,'8-15')
				->setCellValue('E'.$currentrow,'16-30')
				->setCellValue('F'.$currentrow,'31-60')
				->setCellValue('G'.$currentrow,'> 61');
				
		//Apply style for header Row
		$mySheet->getStyle('B'.$currentrow)->applyFromArray($styleArray1);
		$mySheet->getStyle('C'.$currentrow.':G'.$currentrow)->applyFromArray($styleArray);
		$mySheet->getStyle('C'.$currentrow.':G'.$currentrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		
	
		$currentrow++;
		$query = "select branch, count(billcount17 or null) as billcount17, count(billcount815 or null) as billcount815, 
count(billcount1630 or null) as  billcount1630, count(billcount3160 or null) as billcount3160, count(billcount61 or null) as billcount61 from yearwise_customers group by branch  order by branch;";
		$result = runmysqlquery($query);
		
		//Insert data
		$databeginrow = $currentrow;			
		
		if(mysqli_num_rows($result) <> 0)
		{
			while($row_data = mysqli_fetch_array($result))
			{
				$mySheet->setCellValue('B'.$currentrow,$row_data['branch'])
						->setCellValue('C'.$currentrow,$row_data['billcount17'])
						->setCellValue('D'.$currentrow,$row_data['billcount815'])
						->setCellValue('E'.$currentrow,$row_data['billcount1630'])
						->setCellValue('F'.$currentrow,$row_data['billcount3160'])
						->setCellValue('G'.$currentrow,$row_data['billcount61']);
				$currentrow++;
			}
		}
		else
		{
			$mySheet->setCellValue('B'.$currentrow,'')
					->setCellValue('C'.$currentrow,'0')
					->setCellValue('D'.$currentrow,'0')
					->setCellValue('E'.$currentrow,'0')
					->setCellValue('F'.$currentrow,'0')
					->setCellValue('G'.$currentrow,'0');
				$currentrow++;
		}
		//Insert Total
		$mySheet->setCellValue('B'.$currentrow,'Total')
				->setCellValue('C'.$currentrow,"=SUM(C".$databeginrow.":C".($currentrow-1).")")
				->setCellValue('D'.$currentrow,"=SUM(D".$databeginrow.":D".($currentrow-1).")")
				->setCellValue('E'.$currentrow,"=SUM(E".$databeginrow.":E".($currentrow-1).")")
				->setCellValue('F'.$currentrow,"=SUM(F".$databeginrow.":F".($currentrow-1).")")
				->setCellValue('G'.$currentrow,"=SUM(G".$databeginrow.":G".($currentrow-1).")");
		$mySheet->getCell('C'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('D'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('E'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('F'.$currentrow)->getCalculatedValue();
		$mySheet->getCell('G'.$currentrow)->getCalculatedValue();
		
		//Apply style for Content row
		$mySheet->getStyle('B'.$currentrow.':G'.$currentrow)->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color( \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED ) );
		$mySheet->getStyle('B'.$databeginrow.':G'.$currentrow)->applyFromArray($styleArrayContent);
		
		//set the default width for column
		$mySheet->getColumnDimension('A')->setWidth(15);
		$mySheet->getColumnDimension('B')->setWidth(40);
		$mySheet->getColumnDimension('C')->setWidth(20);
		$mySheet->getColumnDimension('D')->setWidth(20);
		$mySheet->getColumnDimension('E')->setWidth(20);
		$mySheet->getColumnDimension('F')->setWidth(20);
		$mySheet->getColumnDimension('G')->setWidth(20);
		
	}
			
			$objPHPExcel->setActiveSheetIndex(0);
			$addstring = "/user";
			if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk"))
				$addstring = "/saralimax-user";
			
				$query = 'select slno,username from inv_mas_users where slno = '.$userid.'';
				$fetchres = runmysqlqueryfetch($query);	
				$localdate = datetimelocal('Ymd');
				$localtime = datetimelocal('His');
				$heading = strtoupper($group);
				$filebasename = "NotRegistered".$localdate."-".$localtime."-".strtolower($fetchres['username']).".xls";
				
				$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filebasename;
				$downloadlink = 'http://'.$_SERVER['HTTP_HOST'].$addstring.'/filecreated/'.$filebasename;
		
				$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_notregistered_report','','".$_SERVER['REMOTE_ADDR']."')";
				$result = runmysqlquery($query1);
				
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','250','".date('Y-m-d').' '.date('H:i:s')."','excel_notregistered_report".'-'.strtolower($fetchres['username'])."')";
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
