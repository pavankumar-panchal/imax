<?
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');

if(imaxgetcookie('userid')<> '') 
$userid = imaxgetcookie('userid');
else
{ 
	echo(json_encode('Thinking to redirect'));
	exit;
}
include('../inc/checksession.php');
include('../inc/checkpermission.php');
$changetype = $_POST['changetype'];
$lastslno = $_POST['lastslno'];
switch($changetype)
{
	case 'save':
	{
		$productname = $_POST['productname'];
		$productcode = $_POST['productcode'];
		$productnotinuse = $_POST['productnotinuse'];
		$producttype = $_POST['producttype'];
		$productgroup = $_POST['productgroup'];
		$updationtype = $_POST['updationtype'];
		$dealerpurchasecaption = $_POST['dealerpurchasecaption'];
		$allowdealerpurchase = $_POST['allowdealerpurchase'];
		/*$year = datetimelocal('Y');
		$financialyear = $year."-".(substr($year,2,2) + 1);*/
		$financialyear = $_POST['financialyear'];
		if($lastslno == "")
		{
			$query = "SELECT COUNT(*) AS count FROM inv_mas_product WHERE productcode = '".$productcode."'";
			$fetchcode = runmysqlqueryfetch($query);
			$query = "SELECT COUNT(*) AS count FROM inv_mas_product WHERE productname = '".$productname."'";
			$fetchproduct = runmysqlqueryfetch($query);
			if($fetchcode['count'] > 0)
			{
				echo(json_encode("2^"."Please Enter the Different Product Code as the product code already exists."));
				exit;
			}
			elseif($fetchproduct['count'] > 0)
			{
				echo(json_encode("2^"."Please Enter the Different Product Name as the product Name already exists."));
				exit;
			}
			else
			{
				$query = "INSERT INTO inv_mas_product(productcode, productname,`type`,prdrept,notinuse, `year`,`group`,allowdealerpurchase,dealerpurchasecaption,userid,updation) VALUES('".$productcode."','".$productname."','".$producttype."','','".$productnotinuse."','".$financialyear."','".$productgroup."','".$allowdealerpurchase."','".$dealerpurchasecaption."','".$userid."','".$updationtype."');";
				$result = runmysqlquery($query);
				
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','5','".date('Y-m-d').' '.date('H:i:s')."')";
				$eventresult = runmysqlquery($eventquery);
			}
		}
		else
		{
			$query = "SELECT COUNT(*) AS count FROM inv_mas_product WHERE productcode = '".$productcode."' AND slno <> '".$lastslno."'";
			$fetchcode = runmysqlqueryfetch($query);
			$query = "SELECT COUNT(*) AS count FROM inv_mas_product WHERE productname = '".$productname."' AND slno <> '".$lastslno."'";
			$fetchproduct = runmysqlqueryfetch($query);
			if($fetchcode['count'] > 0)
			{
				echo(json_encode("2^"."Please Enter the Different Product Code as the product code already exists."));
				exit;
			}
			elseif($fetchproduct['count'] > 0)
			{
				echo(json_encode("2^"."Please Enter the Different Product Name as the product Name already exists."));
				exit;
			}
			else
			{
				$query = "UPDATE inv_mas_product SET productname = '".trim($productname)."',productcode = '".$productcode."', `type` = '".$producttype."', prdrept = '', notinuse = '".$productnotinuse."', `group` = '".$productgroup."', `updation` = '".$updationtype."', `dealerpurchasecaption` = '".$dealerpurchasecaption."', `allowdealerpurchase` = '".$allowdealerpurchase."',lastmodifieddate = '".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedby = '".$userid."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."', year = '".$financialyear."'  WHERE slno = '".$lastslno."'";
				$result = runmysqlquery($query);
				
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','6','".date('Y-m-d').' '.date('H:i:s')."')";
				$eventresult = runmysqlquery($eventquery);
			}
		}
		echo(json_encode("1^"."Product Record '".$productcode."' Saved Successfully"));
		//echo($query);
	}
	break;

	case 'generateproductlist':
	{
		$generateproductlistarray = array();
		$query = "SELECT slno,productname,productcode FROM inv_mas_product ORDER BY productname";
		$result = runmysqlquery($query);
		//$grid = '<select name="productlist" size="5" class="swiftselect" id="productlist" style="width:210px; height:400px;" onclick ="selectfromlist();" onchange="selectfromlist();"  >';
		$grid = '';
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$generateproductlistarray[$count] = $fetch['productname'].'^'.$fetch['productcode'];
			$count++;
		}
		echo(json_encode($generateproductlistarray));
	}
	break;
	
	case 'productdetailstoform':
	{
		$productdetailstoformarray = array();
		$query1 = "SELECT count(*) as count from inv_mas_product where productcode = '".$lastslno."'";
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0) 		
		{
			$query = "SELECT  inv_mas_product.slno,inv_mas_product.productcode,inv_mas_product.productname,inv_mas_product.notinuse,inv_mas_product.type,inv_mas_product.group,inv_mas_product.allowdealerpurchase,inv_mas_product.dealerpurchasecaption ,inv_mas_product.updation, inv_mas_users.fullname ,inv_mas_product.year from inv_mas_product LEFT JOIN inv_mas_users ON inv_mas_users.slno = inv_mas_product.userid where productcode = '".$lastslno."';";
			$fetch = runmysqlqueryfetch($query);
			$productdetailstoformarray['errorcode'] = '1';
			$productdetailstoformarray['slno'] = $fetch['slno'];
			$productdetailstoformarray['productcode'] = $fetch['productcode'];
			$productdetailstoformarray['productname'] = $fetch['productname'];
			$productdetailstoformarray['notinuse'] = $fetch['notinuse'];
			$productdetailstoformarray['type'] = $fetch['type'];
			$productdetailstoformarray['group'] = $fetch['group'];
			$productdetailstoformarray['allowdealerpurchase'] = $fetch['allowdealerpurchase'];
			$productdetailstoformarray['dealerpurchasecaption'] = $fetch['dealerpurchasecaption'];
			$productdetailstoformarray['updation'] = $fetch['updation'];
			$productdetailstoformarray['year'] = $fetch['year'];
			
			echo(json_encode($productdetailstoformarray));
			//echo('1^'.$fetch['slno'].'^'.$fetch['productcode'].'^'.$fetch['productname'].'^'.$fetch['notinuse'].'^'.$fetch['type'].'^'.$fetch['group'].'^'.$fetch['allowdealerpurchase'].'^'.$fetch['dealerpurchasecaption'].'^'.$fetch['updation'].'^'.$fetch['year']);
		}
		else
		{
			//echo('2^'.$lastslno.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
			$productdetailstoformarray['errorcode'] = '2';
			$productdetailstoformarray['slno'] = $lastslno;
			$productdetailstoformarray['productcode'] = '';
			$productdetailstoformarray['productname'] = '';
			$productdetailstoformarray['notinuse'] = '';
			$productdetailstoformarray['type'] = '';
			$productdetailstoformarray['group'] = '';
			$productdetailstoformarray['allowdealerpurchase'] = '';
			$productdetailstoformarray['dealerpurchasecaption'] = '';
			$productdetailstoformarray['updation'] = '';
			$productdetailstoformarray['year'] = '';
		}
	}
	break;
	
	case 'productsearch':
	{
		$textfield = $_POST['textfield'];
		$subselection = $_POST['subselection'];
		$orderby = $_POST['orderby'];
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
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
		if(strlen($textfield) > 0)
		{
			switch($orderby)
			{
				case "productcode":
					$orderbyfield = "productcode";
					break;
				case "productname":
					$orderbyfield = "productname";
					break;
				case "productnotinuse":
					$orderbyfield = "notinuse";
					break;
				case "producttype":
					$orderbyfield = "type";
					break;
				case "productgroup":
					$orderbyfield = "group";
					break;
				default:
					$orderbyfield = "productname";
					break;
			}
			switch($subselection)
			{
				case "productcode":
					$query = "SELECT inv_mas_product.slno,inv_mas_product.productcode,inv_mas_product.productname,inv_mas_product.notinuse,inv_mas_product.allowdealerpurchase,inv_mas_product.dealerpurchasecaption,inv_mas_product.updation,inv_mas_users.fullname  from inv_mas_product LEFT JOIN inv_mas_users on inv_mas_product.userid = inv_mas_users.slno WHERE  productcode LIKE '%".$textfield."%' ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
					case "productname":
					$query = "SELECT inv_mas_product.slno,inv_mas_product.productcode,inv_mas_product.productname,inv_mas_product.notinuse,inv_mas_product.allowdealerpurchase,inv_mas_product.dealerpurchasecaption,inv_mas_product.updation,inv_mas_users.fullname from inv_mas_product LEFT JOIN inv_mas_users on inv_mas_product.userid = inv_mas_users.slno  WHERE  productname LIKE '%".$textfield."%' ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
					case "productnotinuse":
					$query = "SELECT inv_mas_product.slno,inv_mas_product.productcode,inv_mas_product.productname,inv_mas_product.notinuse,inv_mas_product.allowdealerpurchase,inv_mas_product.dealerpurchasecaption,inv_mas_product.updation,inv_mas_users.fullname from inv_mas_product LEFT JOIN inv_mas_users on inv_mas_product.userid = inv_mas_users.slno  WHERE  notinuse LIKE '%".$textfield."%' ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
					case "producttype":
					$query = "SELECT inv_mas_product.slno,inv_mas_product.productcode,inv_mas_product.productname,inv_mas_product.notinuse,inv_mas_product.allowdealerpurchase,inv_mas_product.dealerpurchasecaption,inv_mas_product.updation,inv_mas_users.fullname from inv_mas_product LEFT JOIN inv_mas_users on inv_mas_product.userid = inv_mas_users.slno  WHERE  `type` LIKE '%".$textfield."%' ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
					case "productgroup":
					$query = "SELECT inv_mas_product.slno,inv_mas_product.productcode,inv_mas_product.productname,inv_mas_product.notinuse,inv_mas_product.allowdealerpurchase,inv_mas_product.dealerpurchasecaption,inv_mas_product.updation,inv_mas_users.fullname from inv_mas_product LEFT JOIN inv_mas_users on inv_mas_product.userid = inv_mas_users.slno  WHERE  `group` LIKE '%".$textfield."%' ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
			}
			if($startlimit == 0)
			{
				$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" >';
				
				$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Code</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Not In Use</td><td nowrap = "nowrap" class="td-border-grid" align="left">Updation</td><td nowrap = "nowrap" class="td-border-grid" align="left">Allow Dealer Purchase</td><td nowrap = "nowrap" class="td-border-grid" align="left">Dealer Purchase Caption</td><td nowrap = "nowrap" class="td-border-grid" align="left">User Name</td></tr>';
			}
			$result = runmysqlquery($query);
			//$slnocount = 0;
			while($fetch = mysqli_fetch_array($result))
			{
				$i_n++;
				$slnocount++;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				static $count = 0;
				$count++;
				$radioid = 'nameloadcustomerradio'.$count;
				$grid .= '<tr class="gridrow" onclick ="productdetailstoform(\''.$fetch['productcode'].'\');"  bgcolor='.$color.'>';
				
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['productcode'])."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".addslashes($fetch['productname'])."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['notinuse'])."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['updation'])."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['allowdealerpurchase'])."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['dealerpurchasecaption'])."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['fullname'])."</td>";
			
				$grid .= '</tr>';
			}
			$grid .= "</table>";

			$fetchcount = mysqli_num_rows($result);
			if($fetchcount < $limit)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr ><td align ="left"><div align ="left" style="padding-right:10px">&nbsp;&nbsp;&nbsp;<a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" class="resendtext"  style="cursor:pointer;">Show More Records >> &nbsp;&nbsp;&nbsp;</a><a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','7','".date('Y-m-d').' '.date('H:i:s')."')";
			$eventresult = runmysqlquery($eventquery);
				
			echo '1^'.$grid.'^'.$fetchcount.'^'.$linkgrid;	
		}
	}
	break;

	case 'generategrid':
	{
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$resultcount = "SELECT inv_mas_product.slno,inv_mas_product.productcode,inv_mas_product.productname,
inv_mas_product.notinuse,inv_mas_product.allowdealerpurchase,
inv_mas_product.dealerpurchasecaption,inv_mas_product.updation,inv_mas_users.fullname as lastmodifiedby 
from inv_mas_product LEFT JOIN inv_mas_users on inv_mas_product.userid = inv_mas_users.slno  WHERE notinuse = 'No' ";
		$fetch10 = runmysqlquery($resultcount);
		$fetchresultcount = mysqli_num_rows($fetch10);
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
		$query = "SELECT inv_mas_product.slno,inv_mas_product.productcode,inv_mas_product.productname,
inv_mas_product.notinuse,inv_mas_product.allowdealerpurchase,
inv_mas_product.dealerpurchasecaption ,inv_mas_product.updation,inv_mas_users.fullname as lastmodifiedby 
from inv_mas_product LEFT JOIN inv_mas_users on inv_mas_product.userid = inv_mas_users.slno  WHERE notinuse = 'No' LIMIT ".$startlimit.",".$limit.";";
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" >';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Code</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Not In Use</td><td nowrap = "nowrap" class="td-border-grid" align="left">Updation</td><td nowrap = "nowrap" class="td-border-grid" align="left">Allow Dealer Purchase</td><td nowrap = "nowrap" class="td-border-grid" align="left">Dealer Purchase Caption</td><td nowrap = "nowrap" class="td-border-grid" align="left">User Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Last Modified Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Last Modified By</td></tr>';
		}
			$result = runmysqlquery($query);
			while($fetch = mysqli_fetch_array($result))
			{
				$slnocount++;
				$i_n++;
				$color;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				
				$grid .= '<tr class="gridrow" onclick ="productdetailstoform(\''.$fetch['productcode'].'\');"  bgcolor='.$color.'>';
				
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['productcode']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".addslashes($fetch['productname'])."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['notinuse']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['updation']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['allowdealerpurchase']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['dealerpurchasecaption']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['fullname']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['lastmodifieddate']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['lastmodifiedby']."</td>";
			
				$grid .= '</tr>';
			}
			$grid .= "</table>";
			$fetchcount = mysqli_num_rows($result);
			if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px">&nbsp;&nbsp;&nbsp;<a onclick ="getmoreproductdatagrid(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" class="resendtext" style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmoreproductdatagrid(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');"  style="cursor:pointer" class="resendtext1"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
			echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid;	
	}
	break;
}
?>
