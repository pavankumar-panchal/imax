<?php
ob_start("ob_gzhandler");
ini_set('memory_limit', '-1');
include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');

if(imaxgetcookie('userid')<> '') 
$userid = imaxgetcookie('userid');
else
{ 
	echo('Thinking to redirect');
	exit;
}
include('../inc/checksession.php');
include('../inc/checkpermission.php');

$lastslno = $_POST['lastslno'];
$switchtype = $_POST['switchtype'];

switch($switchtype)
{
	case 'rcidetails':
	{
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$resultcount = "select count(*) as totalcount from inv_logs_webservices left join inv_mas_customer on right(inv_logs_webservices.customerid,5) = inv_mas_customer.slno left join inv_mas_product on inv_mas_product.productcode = left(inv_logs_webservices.computerid,3) order by `date` desc;";
		$fetch10 = runmysqlqueryfetch_old($resultcount);
		$fetchresultcount = $fetch10['totalcount'];
		if($showtype == 'all')
		$limit = 100000;
		else
		$limit = 10;
		if($startlimit == '')
		{
			$startlimit = 0;
			$slnocount = 0;
		}
		else
		{
			$startlimit = $slnocount ;
			$slnocount = $slnocount;
		}
		$query = "select inv_logs_webservices.date,inv_mas_customer.businessname,inv_logs_webservices.customerid,inv_mas_product.productname,inv_logs_webservices.productversion,inv_logs_webservices.operatingsystem,inv_logs_webservices.processor,inv_logs_webservices.registeredname,inv_logs_webservices.pinnumber,inv_logs_webservices.computerid,inv_logs_webservices.servicename from inv_logs_webservices 
left join inv_mas_customer on right(inv_logs_webservices.customerid,5) = inv_mas_customer.slno 
left join inv_mas_product on inv_mas_product.productcode = left(inv_logs_webservices.computerid,3)
order by `date` desc LIMIT ".$startlimit.",".$limit." ; ";
		$result = runmysqlquery_old($query);
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid">Sl No</td><td nowrap = "nowrap" class="td-border-grid">Date</td><td nowrap = "nowrap" class="td-border-grid">Company Name</td><td nowrap = "nowrap" class="td-border-grid">Customer ID</td><td nowrap = "nowrap" class="td-border-grid">Product Name</td><td nowrap = "nowrap" class="td-border-grid">Product Version</td><td nowrap = "nowrap" class="td-border-grid">Operating System</td><td nowrap = "nowrap" class="td-border-grid">Processor</td><td nowrap = "nowrap" class="td-border-grid">Registered Name</td><td nowrap = "nowrap" class="td-border-grid">Registered PIN</td><td nowrap = "nowrap" class="td-border-grid">Computer ID</td><td nowrap = "nowrap" class="td-border-grid">Service Name</td></tr>';
		}
		
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$i_n++;
			$slnocount++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
				
			if($fetch['customerid'] == '')
				$customerid = '';
			else
				$customerid = cusidcombine($fetch['customerid']);
			$grid .= '<tr bgcolor='.$color.' class="gridrow1">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' style='text-align:left'>".$slnocount."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' style='text-align:left'>".changedateformatwithtime($fetch['date'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' style='text-align:left'>".trim($fetch['businessname'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' style='text-align:left'>".$customerid."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' style='text-align:left'>".trim($fetch['productname'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' style='text-align:left'>".trim($fetch['productversion'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' style='text-align:left'>".trim($fetch['operatingsystem'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' style='text-align:left'>".trim($fetch['processor'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' style='text-align:left'>".trim($fetch['registeredname'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' style='text-align:left'>".trim($fetch['pinnumber'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' style='text-align:left'>".trim($fetch['computerid'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' style='text-align:left'>".trim($fetch['servicename'])."</td>";
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorercidetails(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorercidetails(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid;	
	}
	break;
	
	case 'rcidatafilter':
	{
		$databasefield = $_POST['databasefield'];
		$textfield = $_POST['textfield'];
		$state = $_POST['state'];
		$region = $_POST['region'];
		$district = $_POST['district'];
		$productslist = str_replace('\\','',$_POST['productscode']);
		$branch= $_POST['branch'];
		$type = $_POST['type'];
		$category= $_POST['category'];
		$operatingsystem= $_POST['operatingsystem'];
		$processor= $_POST['processor'];
		$showtype = $_POST['showtype'];
		$slnocount = $_POST['slnocount'];
		$startlimit = $_POST['startlimit'];
		
		$regionpiece = ($region == "")?(""):(" AND inv_mas_customer.region = '".$region."' ");
		$state_typepiece = ($state == "")?(""):(" AND inv_mas_district.statecode = '".$state."' ");
		$district_typepiece = ($district == "")?(""):(" AND inv_mas_customer.district = '".$district."' ");
		$branchpiece = ($branch == "")?(""):(" AND inv_mas_customer.branch = '".$branch."' ");
		$operatingsystempiece = ($operatingsystem == "")?(""):(" AND inv_logs_webservices.operatingsystem = '".$operatingsystem."' ");
		$processorpiece = ($processor == "")?(""):(" AND inv_logs_webservices.processor = '".$processor."' ");
		
		if($type == 'Not Selected')
		{
			$typepiece = ($type == "")?(""):(" AND inv_mas_customer.type = '' ");
		}
		else
		{
			$typepiece = ($type == "")?(""):(" AND inv_mas_customer.type = '".$type."' ");
		}
		if($category == 'Not Selected')
		{
			$categorypiece = ($category == "")?(""):(" AND inv_mas_customer.category = '' ");
		}
		else
		{
			$categorypiece = ($category == "")?(""):(" AND inv_mas_customer.category = '".$category."' ");
		}
		
		if($showtype == 'all')
			$limit = 100000;
		else
			$limit = 10;
		if($startlimit == '')
		{
			$startlimit = 0;$slnocount = 0;
		}
		else
		{
			$startlimit = $slnocount;
			$slnocount = $slnocount;
		}
		switch($databasefield)
		{
			case "customerid":
				$customeridlen = strlen($textfield);
				$lastcustomerid = cusidsplit($textfield);
				if($customeridlen == 5)
				{
					$query = "select inv_logs_webservices.date,inv_mas_customer.businessname,inv_logs_webservices.customerid,inv_mas_product.productname,inv_logs_webservices.productversion,inv_logs_webservices.operatingsystem,inv_logs_webservices.processor,inv_logs_webservices.registeredname,inv_logs_webservices.pinnumber,inv_logs_webservices.computerid,inv_logs_webservices.servicename from inv_logs_webservices left join inv_mas_customer on right(inv_logs_webservices.customerid,5) = inv_mas_customer.slno
left join inv_mas_product on inv_mas_product.productcode = left(inv_logs_webservices.computerid,3)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type
left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category where  left(inv_logs_webservices.computerid,3)  IN (".$productslist.") AND right(inv_logs_webservices.customerid,5) =
 '".$textfield."' ".$regionpiece.$district_typepiece.$state_typepiece.$branchpiece.$typepiece.$categorypiece.$operatingsystempiece.$processorpiece."  ORDER BY inv_logs_webservices.date desc";
				}
				elseif($customeridlen > 5)
				{
					$query = "select inv_logs_webservices.date,inv_mas_customer.businessname,inv_logs_webservices.customerid,inv_mas_product.productname,inv_logs_webservices.productversion,inv_logs_webservices.operatingsystem,inv_logs_webservices.processor,inv_logs_webservices.registeredname,inv_logs_webservices.pinnumber,inv_logs_webservices.computerid,inv_logs_webservices.servicename from inv_logs_webservices left join inv_mas_customer on right(inv_logs_webservices.customerid,5) = inv_mas_customer.slno left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_mas_product on inv_mas_product.productcode = left(inv_logs_webservices.computerid,3)
left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type
left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category where left(inv_logs_webservices.computerid,3)  IN (".$productslist.") AND  inv_logs_webservices.customerid like
	 '".$lastcustomerid."' ".$regionpiece.$district_typepiece.$state_typepiece.$branchpiece.$typepiece.$categorypiece.$operatingsystempiece.$processorpiece."  ORDER BY inv_logs_webservices.date desc";
				}
				break;
				case "pinnumber":
				$query = "select inv_logs_webservices.date,inv_mas_customer.businessname,inv_logs_webservices.customerid,inv_mas_product.productname,inv_logs_webservices.productversion,inv_logs_webservices.operatingsystem,inv_logs_webservices.processor,inv_logs_webservices.registeredname,inv_logs_webservices.pinnumber,inv_logs_webservices.computerid,inv_logs_webservices.servicename from inv_logs_webservices left join inv_mas_customer on right(inv_logs_webservices.customerid,5) = inv_mas_customer.slno left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_mas_product on inv_mas_product.productcode = left(inv_logs_webservices.computerid,3)
left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type
left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category where left(inv_logs_webservices.computerid,3)  IN (".$productslist.") AND  inv_logs_webservices.pinnumber LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$branchpiece.$typepiece.$categorypiece.$operatingsystempiece.$processorpiece."  ORDER BY inv_logs_webservices.date desc";
				break;
			case "computerid":
				$query = "select inv_logs_webservices.date,inv_mas_customer.businessname,inv_logs_webservices.customerid,inv_mas_product.productname,inv_logs_webservices.productversion,inv_logs_webservices.operatingsystem,inv_logs_webservices.processor,inv_logs_webservices.registeredname,inv_logs_webservices.pinnumber,inv_logs_webservices.computerid,inv_logs_webservices.servicename from inv_logs_webservices left join inv_mas_customer on right(inv_logs_webservices.customerid,5) = inv_mas_customer.slno left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_mas_product on inv_mas_product.productcode = left(inv_logs_webservices.computerid,3)
left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type
left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category where left(inv_logs_webservices.computerid,3)  IN (".$productslist.") AND  inv_logs_webservices.computerid LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$branchpiece.$typepiece.$categorypiece.$operatingsystempiece.$processorpiece."  ORDER BY inv_logs_webservices.date desc";
				break;
			case 'businessname':
				$query = "select inv_logs_webservices.date,inv_mas_customer.businessname,inv_logs_webservices.customerid,inv_mas_product.productname,inv_logs_webservices.productversion,inv_logs_webservices.operatingsystem,inv_logs_webservices.processor,inv_logs_webservices.registeredname,inv_logs_webservices.pinnumber,inv_logs_webservices.computerid,inv_logs_webservices.servicename from inv_logs_webservices left join inv_mas_customer on right(inv_logs_webservices.customerid,5) = inv_mas_customer.slno left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_mas_product on inv_mas_product.productcode = left(inv_logs_webservices.computerid,3)
left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type
left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category where  left(inv_logs_webservices.computerid,3)  IN (".$productslist.") AND  inv_mas_customer.businessname LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$branchpiece.$typepiece.$categorypiece.$operatingsystempiece.$processorpiece."  ORDER BY inv_logs_webservices.date desc";
				break;
				case 'registeredname':
				$query = "select inv_logs_webservices.date,inv_mas_customer.businessname,inv_logs_webservices.customerid,inv_mas_product.productname,inv_logs_webservices.productversion,inv_logs_webservices.operatingsystem,inv_logs_webservices.processor,inv_logs_webservices.registeredname,inv_logs_webservices.pinnumber,inv_logs_webservices.computerid,inv_logs_webservices.servicename from inv_logs_webservices left join inv_mas_customer on right(inv_logs_webservices.customerid,5) = inv_mas_customer.slno left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_mas_product on inv_mas_product.productcode = left(inv_logs_webservices.computerid,3)
left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type
left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category where  left(inv_logs_webservices.computerid,3)  IN (".$productslist.") AND  inv_logs_webservices.registeredname LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$branchpiece.$typepiece.$categorypiece.$operatingsystempiece.$processorpiece."  ORDER BY inv_logs_webservices.date desc";
				break;
			default:
				$query = "select inv_logs_webservices.date,inv_mas_customer.businessname,inv_logs_webservices.customerid,inv_mas_product.productname,inv_logs_webservices.productversion,inv_logs_webservices.operatingsystem,inv_logs_webservices.processor,inv_logs_webservices.registeredname,inv_logs_webservices.pinnumber,inv_logs_webservices.computerid,inv_logs_webservices.servicename from inv_logs_webservices left join inv_mas_customer on right(inv_logs_webservices.customerid,5) = inv_mas_customer.slno left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_mas_product on inv_mas_product.productcode = left(inv_logs_webservices.computerid,3)
left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region
left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type
left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category where left(inv_logs_webservices.computerid,3)  IN (".$productslist.") AND  inv_logs_webservices.registeredname LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$branchpiece.$typepiece.$categorypiece.$operatingsystempiece.$processorpiece."  ORDER BY inv_logs_webservices.date desc";
				break;
		}
					
			$result = runmysqlquery_old($query);
			$fetchresultcount = mysqli_num_rows($result);
			$addlimit = " LIMIT ".$startlimit.",".$limit.";";
			$query1 = $query.$addlimit;
			$result1 = runmysqlquery_old($query1);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','59','".date('Y-m-d').' '.date('H:i:s')."','view_rcidata')";
			$eventresult = runmysqlquery($eventquery);
			$grid = '';
			if($startlimit == 0)
			{
				$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" >';
				
					$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid">Sl No</td><td nowrap = "nowrap" class="td-border-grid">Date</td><td nowrap = "nowrap" class="td-border-grid">Company Name</td><td nowrap = "nowrap" class="td-border-grid">Customer ID</td><td nowrap = "nowrap" class="td-border-grid">Product Name</td><td nowrap = "nowrap" class="td-border-grid">Product Version</td><td nowrap = "nowrap" class="td-border-grid">Operating System</td><td nowrap = "nowrap" class="td-border-grid">Processor</td><td nowrap = "nowrap" class="td-border-grid">Registered Name</td><td nowrap = "nowrap" class="td-border-grid">Registered PIN</td><td nowrap = "nowrap" class="td-border-grid">Computer ID</td><td nowrap = "nowrap" class="td-border-grid">Service Name</td></tr>';
			}
			while($fetch = mysqli_fetch_array($result1))
			{
				$i_n++;
				$color;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				$slnocount++;
				$grid .= '<tr bgcolor='.$color.' class="gridrow1">';
				$grid .= "<td nowrap='nowrap' class='td-border-grid' style='text-align:left'>".$slnocount."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' style='text-align:left'>".changedateformatwithtime($fetch['date'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' style='text-align:left'>".trim($fetch['businessname'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' style='text-align:left'>".cusidcombine($fetch['customerid'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' style='text-align:left'>".trim($fetch['productname'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' style='text-align:left'>".trim($fetch['productversion'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' style='text-align:left'>".trim($fetch['operatingsystem'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' style='text-align:left'>".trim($fetch['processor'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' style='text-align:left'>".trim($fetch['registeredname'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' style='text-align:left'>".trim($fetch['pinnumber'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' style='text-align:left'>".trim($fetch['computerid'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' style='text-align:left'>".trim($fetch['servicename'])."</td>";
				$grid .= '</tr>';
			}
			$grid .= "</table>";

		if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmorefilterrcidata(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmorefilterrcidata(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
		echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid;	
		
	}
	break;

}

?>