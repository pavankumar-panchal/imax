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
	$url = '../home/index.php?a_link=updationsummary'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
	$userid = imaxgetcookie('userid');
	$id = $_GET['id'];
	$region = $_POST['region'];
	$comparedtime = $_POST['comparedtime'];
	$groupon = $_POST['groupon'];
	
	if($comparedtime == 'previousyear')
	{
		$datettime = date('Y');
		$year1 = date('Y', mktime(0,0,0,0,0,($datettime)));
		$year2 = date('Y', mktime(0,0,0,0,0,($datettime+1)));
		$year3 = date('Y', mktime(0,0,0,0,0,($datettime)+2));
		$resultyear1 = $year1.'-'.substr($datettime,2);
		$resultyear2 = $year2.'-'.substr($year3,2);
		$yearvalue = " AND  inv_mas_product.year IN ('".$resultyear1."')  ";
		$currentyearpiece = " AND inv_mas_product.year in ('".$resultyear2."') And inv_customerproduct.purchasetype <> 'new' ";
	}
	
	elseif($comparedtime == 'alltime')
	{
		$datettime = date('Y');
		$year1 = date('Y', mktime(0,0,0,0,0,($datettime+1)));
		$year2 = date('Y', mktime(0,0,0,0,0,($datettime)+2));
		$year3 = date('Y', mktime(0,0,0,0,0,($datettime)));
		$resultyear1 = $year1.'-'.substr($year2,2);
		$resultyear2 = $year3.'-'.substr($datettime,2);
		$currentyearpiece = "AND  inv_mas_product.year IN ('".$resultyear1."') And inv_customerproduct.purchasetype <> 'new'  ";
		$yearvalue = "AND  inv_mas_product.year IN ('2004-05','2005-06','2006-07','2007-08','2008-09','2009-10','2010-11','".$resultyear2."')";
		
	}
	//echo($resultyear1.$resultyear2); exit();
	$regionpiece = ($region == "")?(""):(" AND inv_mas_region.slno = '".$region."' ");
	if($groupon == 'dealer')
	{
		$dealerpiece = ($dealerid == "")?("inv_mas_dealer.slno is not null"):("  inv_mas_dealer.slno = '".$dealerid."' ");
	
		$query = "select * from (select inv_mas_dealer.slno, inv_mas_dealer.businessname as dealername, table1.total, table1.resultgroup as totalgrp, inv_mas_region.category as region from inv_mas_dealer
	left join (select GROUP_CONCAT(temp.total SEPARATOR '^' ) as total, 
	GROUP_CONCAT(distinct temp.dealergroup SEPARATOR ',') as resultgroup, temp.currentdealer,temp.dealername 
	from (select COUNT(DISTINCT inv_mas_customer.slno) 
	as total, inv_mas_customer.currentdealer, inv_mas_dealer.businessname as dealername, inv_mas_product.group as dealergroup from 
	inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
	left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) 
	left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer where inv_mas_product.group 
	IN ('sto','tds','spp','sac','air','contact','na','others','ses','survey','svh','svi') and reregistration = 'no' 
	and inv_mas_customer.companyclosed = 'no' and inv_mas_customer.isdealer = 'no'
	and (inv_mas_customer.customerid <> '' or inv_mas_customer.customerid is null) ".$yearvalue."
	group by inv_mas_customer.currentdealer,inv_mas_product.group) as temp 
	group by temp.currentdealer order by temp.dealergroup desc) as table1 on inv_mas_dealer.slno = table1.currentdealer 
	left join inv_mas_region on inv_mas_region.slno = inv_mas_dealer.region
	where ".$dealerpiece.$regionpiece.")as table3 left join(select inv_mas_dealer.slno, inv_mas_dealer.businessname, table2.made, table2.resultgroup as madegrp, inv_mas_region.category as region from inv_mas_dealer left join(select GROUP_CONCAT(temp.total SEPARATOR '^' ) as made, 
	GROUP_CONCAT(distinct temp.dealergroup SEPARATOR ',') as resultgroup, temp.currentdealer,temp.dealername 
	from (select COUNT(DISTINCT inv_mas_customer.slno) 
	as total, inv_mas_customer.currentdealer, inv_mas_dealer.businessname as dealername, inv_mas_product.group as dealergroup from 
	inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
	left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) 
	left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer where inv_mas_product.group 
	IN ('sto','tds','spp','sac','air','contact','na','others','ses','survey','svh','svi') and reregistration = 'no' 
	and inv_mas_customer.companyclosed = 'no' and inv_mas_customer.isdealer = 'no'
	and (inv_mas_customer.customerid <> '' or inv_mas_customer.customerid is null) ".$currentyearpiece."
	group by inv_mas_customer.currentdealer,inv_mas_product.group) as temp  
	group by temp.currentdealer order by temp.dealergroup desc) as table2
	on inv_mas_dealer.slno = table2.currentdealer 
	left join inv_mas_region on inv_mas_region.slno = inv_mas_dealer.region
	where ".$dealerpiece.$regionpiece.")as table4
	on table3.slno = table4.slno;";
	}
	elseif($groupon == 'branch')
	{
		$branchpiece = ($branch == "")?("inv_mas_branch.slno is not null"):("  inv_mas_branch.slno = '".$branch."' ");
	
		$query = "select * from 
	(select inv_mas_branch.slno, inv_mas_branch.branchname as branchname,inv_mas_region.category as region,
	table1.total, table1.resultgroup as totalgrp
	from inv_mas_branch
	left join (select GROUP_CONCAT(temp.total SEPARATOR '^' ) as total, 
	GROUP_CONCAT(distinct temp.dealergroup SEPARATOR ',') as resultgroup, temp.branch,temp.branchname
	from (select COUNT(DISTINCT inv_mas_customer.slno) 
	as total, inv_mas_customer.branch, 
	inv_mas_branch.branchname as branchname, inv_mas_product.group as dealergroup from 
	inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
	left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) 
	left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch 
	where inv_mas_product.group 
	IN ('sto','tds','spp','sac','air','contact','na','others','ses','survey','svh','svi') and reregistration = 'no' 
	and inv_mas_customer.companyclosed = 'no' and inv_mas_customer.isdealer = 'no'
	and (inv_mas_customer.customerid <> '' or inv_mas_customer.customerid is null) ".$yearvalue."
	group by inv_mas_customer.branch,inv_mas_product.group) as temp 
	group by temp.branch order by temp.dealergroup desc) as table1
	on inv_mas_branch.slno = table1.branch 
	left join inv_mas_region on inv_mas_region.slno = inv_mas_branch.region
	where ".$branchpiece.$regionpiece.")as table3
	left join
	(select inv_mas_branch.slno, inv_mas_branch.branchname as branchname,inv_mas_region.category as region,
	table1.made, table1.resultgroup as madegrp
	from inv_mas_branch left join (select GROUP_CONCAT(temp.total SEPARATOR '^' ) as made, 
	GROUP_CONCAT(distinct temp.dealergroup SEPARATOR ',') as resultgroup, temp.branch,temp.branchname
	from (select COUNT(DISTINCT inv_mas_customer.slno) 
	as total, inv_mas_customer.branch, 
	inv_mas_branch.branchname as branchname, inv_mas_product.group as dealergroup from 
	inv_customerproduct left join inv_mas_customer on inv_customerproduct.customerreference = inv_mas_customer.slno 
	left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) 
	left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch 
	where inv_mas_product.group 
	IN ('sto','tds','spp','sac','air','contact','na','others','ses','survey','svh','svi') and reregistration = 'no' 
	and inv_mas_customer.companyclosed = 'no' and inv_mas_customer.isdealer = 'no'
	and (inv_mas_customer.customerid <> '' or inv_mas_customer.customerid is null) ".$currentyearpiece."
	group by inv_mas_customer.branch,inv_mas_product.group) as temp 
	group by temp.branch order by temp.dealergroup desc) as table1
	on inv_mas_branch.slno = table1.branch 
	left join inv_mas_region on inv_mas_region.slno = inv_mas_branch.region
	where ".$branchpiece.$regionpiece.")as table4
	on table3.slno = table4.slno order by table3.branchname asc;";
	
	}
		//echo($query); exit();
	
		$result = runmysqlquery($query);
				
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
		if($groupon == 'dealer')
			$mySheet->getStyle('A3:AL4')->applyFromArray($styleArray);
		elseif($groupon == 'branch')
			$mySheet->getStyle('A3:AM4')->applyFromArray($styleArray);
			//Merge the cell
		$mySheet->mergeCells('A1:AL1');
		$mySheet->mergeCells('A2:AL2');
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1','Relyon Softech Limited, Bangalore')
					->setCellValue('A2','Updation Summmary Report');
		$mySheet->getStyle('A1:A2')->getFont()->setSize(12); 	
		$mySheet->getStyle('A1:A2')->getFont()->setBold(true); 
		$mySheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
		if($groupon == 'dealer')
		{
			$mySheet->mergeCells('C2:E2');
			$mySheet->mergeCells('F2:H2');
			$mySheet->mergeCells('I2:K2');
			$mySheet->mergeCells('L2:N2');
			$mySheet->mergeCells('O2:Q2');
			$mySheet->mergeCells('R2:T2');
			$mySheet->mergeCells('U2:W2');
			$mySheet->mergeCells('X2:Z2');
			$mySheet->mergeCells('AA2:AC2');
			$mySheet->mergeCells('AD2:AF2');
			$mySheet->mergeCells('AG2:AI2');
			$mySheet->mergeCells('AJ2:AL2');
			
			$mySheet->mergeCells('C3:E3');
			$mySheet->mergeCells('F3:H3');
			$mySheet->mergeCells('I3:K3');
			$mySheet->mergeCells('L3:N3');
			$mySheet->mergeCells('O3:Q3');
			$mySheet->mergeCells('R3:T3');
			$mySheet->mergeCells('U3:W3');
			$mySheet->mergeCells('X3:Z3');
			$mySheet->mergeCells('AA3:AC3');
			$mySheet->mergeCells('AD3:AF3');
			$mySheet->mergeCells('AG3:AI3');
			$mySheet->mergeCells('AJ3:AL3');
		}elseif($groupon == 'branch')
		{
			$mySheet->mergeCells('D2:F2');
			$mySheet->mergeCells('G2:I2');
			$mySheet->mergeCells('J2:L2');
			$mySheet->mergeCells('M2:O2');
			$mySheet->mergeCells('P2:R2');
			$mySheet->mergeCells('S2:U2');
			$mySheet->mergeCells('V2:X2');
			$mySheet->mergeCells('Y2:AA2');
			$mySheet->mergeCells('AB2:AD2');
			$mySheet->mergeCells('AE2:AG2');
			$mySheet->mergeCells('AH2:AJ2');
			$mySheet->mergeCells('AK2:AM2');
			
			$mySheet->mergeCells('D3:F3');
			$mySheet->mergeCells('G3:I3');
			$mySheet->mergeCells('J3:L3');
			$mySheet->mergeCells('M3:O3');
			$mySheet->mergeCells('P3:R3');
			$mySheet->mergeCells('S3:U3');
			$mySheet->mergeCells('V3:X3');
			$mySheet->mergeCells('Y3:AA3');
			$mySheet->mergeCells('AB3:AD3');
			$mySheet->mergeCells('AE3:AG3');
			$mySheet->mergeCells('AH3:AJ3');
			$mySheet->mergeCells('AK3:AM3');
		}
		
		if($groupon == 'dealer')
		{
			//Fille contents for Header Row
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A3', 'Sl No')
						->setCellValue('B3', 'Dealername ')
						->setCellValue('C3', 'TDS')
						->setCellValue('C4', 'Total')
						->setCellValue('D4', 'Made')
						->setCellValue('E4', 'Pending')
						->setCellValue('F3', 'STO')
						->setCellValue('F4', 'Total')
						->setCellValue('G4', 'Made')
						->setCellValue('H4', 'Pending')
						->setCellValue('I3', 'SPP')
						->setCellValue('I4', 'Total')
						->setCellValue('J4', 'Made')
						->setCellValue('K4', 'Pending')
						->setCellValue('L3', 'SAC')
						->setCellValue('L4', 'Total')
						->setCellValue('M4', 'Made')
						->setCellValue('N4', 'Pending')
						->setCellValue('O3', 'CONTACT')
						->setCellValue('O4', 'Total')
						->setCellValue('P4', 'Made')
						->setCellValue('Q4', 'Pending')
						->setCellValue('R3', 'SVH')
						->setCellValue('R4', 'Total')
						->setCellValue('S4', 'Made')
						->setCellValue('T4', 'Pending')
						->setCellValue('U3', 'SVI')
						->setCellValue('U4', 'Total')
						->setCellValue('V4', 'Made')
						->setCellValue('W4', 'Pending')
						->setCellValue('X3', 'AIR')
						->setCellValue('X4', 'Total')
						->setCellValue('Y4', 'Made')
						->setCellValue('Z4', 'Pending')
						->setCellValue('AA3', 'SURVEY')
						->setCellValue('AA4', 'Total')
						->setCellValue('AB4', 'Made')
						->setCellValue('AC4', 'Pending')
						->setCellValue('AD3', 'OTHERS')
						->setCellValue('AD4', 'Total')
						->setCellValue('AE4', 'Made')
						->setCellValue('AF4', 'Pending')
						->setCellValue('AG3', 'NA')
						->setCellValue('AG4', 'Total')
						->setCellValue('AH4', 'Made')
						->setCellValue('AI4', 'Pending')
						->setCellValue('AJ3', 'SES')
						->setCellValue('AJ4', 'Total')
						->setCellValue('AK4', 'Made')
						->setCellValue('AL4', 'Pending');
		}
		elseif($groupon == 'branch')
		{
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A3', 'Sl No')
						->setCellValue('B3', 'Branchname')
						->setCellValue('C3', 'Region')
						->setCellValue('D3', 'TDS')
						->setCellValue('D4', 'Total')
						->setCellValue('E4', 'Made')
						->setCellValue('F4', 'Pending')
						->setCellValue('G3', 'STO')
						->setCellValue('G4', 'Total')
						->setCellValue('H4', 'Made')
						->setCellValue('I4', 'Pending')
						->setCellValue('J3', 'SPP')
						->setCellValue('J4', 'Total')
						->setCellValue('K4', 'Made')
						->setCellValue('L4', 'Pending')
						->setCellValue('M3', 'SAC')
						->setCellValue('M4', 'Total')
						->setCellValue('N4', 'Made')
						->setCellValue('O4', 'Pending')
						->setCellValue('P3', 'CONTACT')
						->setCellValue('P4', 'Total')
						->setCellValue('Q4', 'Made')
						->setCellValue('R4', 'Pending')
						->setCellValue('S3', 'SVH')
						->setCellValue('S4', 'Total')
						->setCellValue('T4', 'Made')
						->setCellValue('U4', 'Pending')
						->setCellValue('V3', 'SVI')
						->setCellValue('V4', 'Total')
						->setCellValue('W4', 'Made')
						->setCellValue('X4', 'Pending')
						->setCellValue('Y3', 'AIR')
						->setCellValue('Y4', 'Total')
						->setCellValue('Z4', 'Made')
						->setCellValue('AA4', 'Pending')
						->setCellValue('AB3', 'SURVEY')
						->setCellValue('AB4', 'Total')
						->setCellValue('AC4', 'Made')
						->setCellValue('AD4', 'Pending')
						->setCellValue('AE3', 'OTHERS')
						->setCellValue('AE4', 'Total')
						->setCellValue('AF4', 'Made')
						->setCellValue('AG4', 'Pending')
						->setCellValue('AH3', 'NA')
						->setCellValue('AH4', 'Total')
						->setCellValue('AI4', 'Made')
						->setCellValue('AJ4', 'Pending')
						->setCellValue('AK3', 'SES')
						->setCellValue('AK4', 'Total')
						->setCellValue('AL4', 'Made')
						->setCellValue('AM4', 'Pending');
			}					
		$j =5;
		$slno = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$slno++;
			$res = '';
			$total = $fetch['total'];
			$totalsplit = explode('^',$total);
			
			$group = strtolower($fetch['totalgrp']);
			$groupsplit = explode(',',$group);
			$res = array();
			for($m=0;$m<count($groupsplit);$m++)
			{
				$res[] = $groupsplit[$m];
			}
			
			//to fetch the made
			$res1 = '';
			$made = $fetch['made'];
			$madesplit = explode('^',$made);
			
			$group1 = strtolower($fetch['madegrp']);
			$groupsplit1 = explode(',',$group1);
			$res1 = array();
			for($k=0;$k<count($groupsplit1);$k++)
			{
				$res1[] = $groupsplit1[$k];
			}
			for($i=0;$i<count($totalsplit);$i++)
			{
				for($l=0;$l<count($madesplit);$l++)
				{
					if($l == 0)
					{
						if($i == 0)
						{
							//tds
							if(in_array('tds', $res) == true)
							{
								$key = array_search('tds', $res);
								$resultkeyvalue1 = $totalsplit[$key];
							}
							else
							{
								$resultkeyvalue1 = '0';
							}
							if(in_array('tds', $res1) == true)
							{
								$keymade = array_search('tds', $res1);
								$resultkeymade1 = $madesplit[$keymade];
							}
							else
							{
								$resultkeymade1 = '0';
							}
							$pendingres = $resultkeyvalue1 - $resultkeymade1;
							
							//sto
							if(in_array('sto', $res) == true)
							{
								$key1 = array_search('sto', $res);
								$resultkeyvalue2 = $totalsplit[$key1];
							}
							else
							{
								$resultkeyvalue2 = '0';
							}
							if(in_array('sto', $res1) == true)
							{
								$keymade1 = array_search('sto', $res1);
								$resultkeymade2 = $madesplit[$keymade1];
							}
							else
							{
								$resultkeymade2 = '0';
							}
							$pendingres1 = $resultkeyvalue2 - $resultkeymade2;
							
							//spp
							if(in_array('spp', $res) == true)
							{
								$key2 = array_search('spp', $res);
								$resultkeyvalue3 = $totalsplit[$key2];
							}
							else
							{
								$resultkeyvalue3 = '0';
							}
							if(in_array('spp', $res1) == true)
							{
								$keymade2 = array_search('spp', $res1);
								$resultkeymade3 = $madesplit[$keymade2];
							}
							else
							{
								$resultkeymade3 = '0';
							}
							$pendingres2 = $resultkeyvalue3 - $resultkeymade3;
							
							//sac
							if(in_array('sac', $res) == true)
							{
								$key3 = array_search('sac', $res);
								$resultkeyvalue4 = $totalsplit[$key3];
							}
							else
							{
								$resultkeyvalue4 = '0';
							}
							if(in_array('sac', $res1) == true)
							{
								$keymade3 = array_search('sac', $res1);
								$resultkeymade4 = $madesplit[$keymade3];
							}
							else
							{
								$resultkeymade4 = '0';
							}
							$pendingres3 = $resultkeyvalue4 - $resultkeymade4;
							
							//contact
							if(in_array('contact', $res) == true)
							{
								$key4 = array_search('contact', $res);
								$resultkeyvalue5 = $totalsplit[$key4];
							}
							else
							{
								$resultkeyvalue5 = '0';
							}
							if(in_array('contact', $res1) == true)
							{
								$keymade4 = array_search('contact', $res1);
								$resultkeymade5 = $madesplit[$keymade4];
							}
							else
							{
								$resultkeymade5 = '0';
							}
							$pendingres4 = $resultkeyvalue5 - $resultkeymade5;
							
							//svh
							if(in_array('svh', $res) == true)
							{
								$key5 = array_search('svh', $res);
								$resultkeyvalue6 = $totalsplit[$key5];
							}
							else
							{
								$resultkeyvalue6 = '0';
							}
							if(in_array('svh', $res1) == true)
							{
								$keymade5 = array_search('svh', $res1);
								$resultkeymade6 = $madesplit[$keymade5];
							}
							else
							{
								$resultkeymade6 = '0';
							}
							$pendingres5 = $resultkeyvalue6 - $resultkeymade6;
							
							//svi
							if(in_array('svi', $res) == true)
							{
								$key6 = array_search('svi', $res);
								$resultkeyvalue7 = $totalsplit[$key6];
							}
							else
							{
								$resultkeyvalue7 = '0';
							}
							if(in_array('svi', $res1) == true)
							{
								$keymade6 = array_search('svi', $res1);
								$resultkeymade7 = $madesplit[$keymade6];
							}
							else
							{
								$resultkeymade7 = '0';
							}
							$pendingres6 = $resultkeyvalue7 - $resultkeymade7;
							
							//air
							if(in_array('air', $res) == true)
							{
								$key7 = array_search('air', $res);
								$resultkeyvalue8 = $totalsplit[$key7];
							}
							else
							{
								$resultkeyvalue8 = '0';
							}
							if(in_array('air', $res1) == true)
							{
								$keymade7 = array_search('air', $res1);
								$resultkeymade8 = $madesplit[$keymade7];
							}
							else
							{
								$resultkeymade8 = '0';
							}
							$pendingres7 = $resultkeyvalue8 - $resultkeymade8;
							
							//survey
							if(in_array('survey', $res) == true)
							{
								$key8 = array_search('survey', $res);
								$resultkeyvalue9 = $totalsplit[$key8];
							}
							else
							{
								$resultkeyvalue9 = '0';
							}
							if(in_array('survey', $res1) == true)
							{
								$keymade8 = array_search('survey', $res1);
								$resultkeymade9 = $madesplit[$keymade8];
							}
							else
							{
								$resultkeymade9 = '0';
							}
							$pendingres8 = $resultkeyvalue9 - $resultkeymade9;
							
							//others
							if(in_array('others', $res) == true)
							{
								$key9 = array_search('others', $res);
								$resultkeyvalue10 = $totalsplit[$key9];
							}
							else
							{
								$resultkeyvalue10 = '0';
							}
							if(in_array('others', $res1) == true)
							{
								$keymade9 = array_search('others', $res1);
								$resultkeymade10 = $madesplit[$keymade9];
							}
							else
							{
								$resultkeymade10 = '0';
							}
							$pendingres9 = $resultkeyvalue10 - $resultkeymade10;
							
							//na
							if(in_array('na', $res) == true)
							{
								$key10 = array_search('na', $res);
								$resultkeyvalue11 = $totalsplit[$key10];
							}
							else
							{
								$resultkeyvalue11 = '0';
							}
							if(in_array('na', $res1) == true)
							{
								$keymade10 = array_search('na', $res1);
								$resultkeymade11 = $madesplit[$keymade10];
							}
							else
							{
								$resultkeymade11 = '0';
							}
							$pendingres10 = $resultkeyvalue11 - $resultkeymade11;
							
							//ses
							if(in_array('ses', $res) == true)
							{
								$key11 = array_search('ses', $res);
								$resultkeyvalue12 = $totalsplit[$key11];
							}
							else
							{
								$resultkeyvalue12 = '0';
							}
							if(in_array('ses', $res1) == true)
							{
								$keymade11 = array_search('ses', $res1);
								$resultkeymade12 = $madesplit[$keymade11];
							}
							else
							{
								$resultkeymade12 = '0';
							}
							$pendingres11 = $resultkeyvalue12 - $resultkeymade12;
							
							
							if($groupon == 'dealer')
							{
							$mySheet->setCellValue('A' . $j,$slno)
									->setCellValue('B' . $j,$fetch['dealername'])
									->setCellValue('C' . $j,$resultkeyvalue1)
									->setCellValue('D' . $j,$resultkeymade1)
									->setCellValue('E' . $j,$pendingres)
									->setCellValue('F' . $j,$resultkeyvalue2)
									->setCellValue('G' . $j,$resultkeymade2)
									->setCellValue('H' . $j,$pendingres1)
									->setCellValue('I' . $j,$resultkeyvalue3)
									->setCellValue('J' . $j,$resultkeymade3)
									->setCellValue('K' . $j,$pendingres2)
									->setCellValue('L' . $j,$resultkeyvalue4)
									->setCellValue('M' . $j,$resultkeymade4)
									->setCellValue('N' . $j,$pendingres3)
									->setCellValue('O' . $j,$resultkeyvalue5)
									->setCellValue('P' . $j,$resultkeymade5)
									->setCellValue('Q' . $j,$pendingres4)
									->setCellValue('R' . $j,$resultkeyvalue6)
									->setCellValue('S' . $j,$resultkeymade6)
									->setCellValue('T' . $j,$pendingres5)
									->setCellValue('U' . $j,$resultkeyvalue7)
									->setCellValue('V' . $j,$resultkeymade7)
									->setCellValue('W' . $j,$pendingres6)
									->setCellValue('X' . $j,$resultkeyvalue8)
									->setCellValue('Y' . $j,$resultkeymade8)
									->setCellValue('Z' . $j,$pendingres7)
									->setCellValue('AA' . $j,$resultkeyvalue9)
									->setCellValue('AB' . $j,$resultkeymade9)
									->setCellValue('AC' . $j,$pendingres8)
									->setCellValue('AD' . $j,$resultkeyvalue10)
									->setCellValue('AE' . $j,$resultkeymade10)
									->setCellValue('AF' . $j,$pendingres9)
									->setCellValue('AG' . $j,$resultkeyvalue11)
									->setCellValue('AH' . $j,$resultkeymade11)
									->setCellValue('AI' . $j,$pendingres10)
									->setCellValue('AJ' . $j,$resultkeyvalue12)
									->setCellValue('AK' . $j,$resultkeymade12)
									->setCellValue('AL' . $j,$pendingres11);
							}elseif($groupon == 'branch')
							{
								$mySheet->setCellValue('A' . $j,$slno)
										->setCellValue('B' . $j,$fetch['branchname'])
										->setCellValue('C' . $j,$fetch['region'])
										->setCellValue('D' . $j,$resultkeyvalue1)
										->setCellValue('E' . $j,$resultkeymade1)
										->setCellValue('F' . $j,$pendingres)
										->setCellValue('G' . $j,$resultkeyvalue2)
										->setCellValue('H' . $j,$resultkeymade2)
										->setCellValue('I' . $j,$pendingres1)
										->setCellValue('J' . $j,$resultkeyvalue3)
										->setCellValue('K' . $j,$resultkeymade3)
										->setCellValue('L' . $j,$pendingres2)
										->setCellValue('M' . $j,$resultkeyvalue4)
										->setCellValue('N' . $j,$resultkeymade4)
										->setCellValue('O' . $j,$pendingres3)
										->setCellValue('P' . $j,$resultkeyvalue5)
										->setCellValue('Q' . $j,$resultkeymade5)
										->setCellValue('R' . $j,$pendingres4)
										->setCellValue('S' . $j,$resultkeyvalue6)
										->setCellValue('T' . $j,$resultkeymade6)
										->setCellValue('U' . $j,$pendingres5)
										->setCellValue('V' . $j,$resultkeyvalue7)
										->setCellValue('W' . $j,$resultkeymade7)
										->setCellValue('X' . $j,$pendingres6)
										->setCellValue('Y' . $j,$resultkeyvalue8)
										->setCellValue('Z' . $j,$resultkeymade8)
										->setCellValue('AA' . $j,$pendingres7)
										->setCellValue('AB' . $j,$resultkeyvalue9)
										->setCellValue('AC' . $j,$resultkeymade9)
										->setCellValue('AD' . $j,$pendingres8)
										->setCellValue('AE' . $j,$resultkeyvalue10)
										->setCellValue('AF' . $j,$resultkeymade10)
										->setCellValue('AG' . $j,$pendingres9)
										->setCellValue('AH' . $j,$resultkeyvalue11)
										->setCellValue('AI' . $j,$resultkeymade11)
										->setCellValue('AJ' . $j,$pendingres10)
										->setCellValue('AK' . $j,$resultkeyvalue12)
										->setCellValue('AL' . $j,$resultkeymade12)
										->setCellValue('AM' . $j,$pendingres11);
							}
									$j++;
							}
						}
					}
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
		$myDataRange = 'A5:'.$myLastCell;
		if(mysqli_num_rows($result) <> 0)
		{
		//Apply style to content area range
			$mySheet->getStyle($myDataRange)->applyFromArray($styleArrayContent);
		}
		
		$mySheet->getColumnDimension('A')->setWidth(6);
		$mySheet->getColumnDimension('B')->setWidth(30);
		
		$addstring = "/user";
		if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") || ($_SERVER['HTTP_HOST'] == "archanaab"))
			$addstring = "/saralimax-user";
		
		if($id == 'toexcel')
		{
			$query = 'select slno,username from inv_mas_users where slno = '.$userid.'';
			$fetchres = runmysqlqueryfetch($query);	
			$localdate = datetimelocal('Ymd');
			$localtime = datetimelocal('His');
			$filebasename = "UpdationSummaryDetails".$localdate."-".$localtime."-".strtolower($fetchres['username']).".xls";
		
			$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_updationsummary_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
			$result = runmysqlquery($query1);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','104','".date('Y-m-d').' '.date('H:i:s')."','excel_updationsummary_report".'-'.strtolower($fetchres['username'])."')";
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
			$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','view_updationsummary_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
			$result = runmysqlquery($query1);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','105','".date('Y-m-d').' '.date('H:i:s')."','view_updationsummary_report')";
			$eventresult = runmysqlquery($eventquery);
			$localdate = datetimelocal('Ymd');
			$localtime = datetimelocal('His');
			$filename = "viewupdationsummaryreport".$localdate."-".$localtime.".htm";
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
