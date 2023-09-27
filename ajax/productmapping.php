<?
ini_set("display_errors",0);
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');

if(imaxgetcookie('userid')<> '') 
$userid = imaxgetcookie('userid');
else
{ 
	echo('Thinking to redirect');
	exit;
}

include('../inc/checkpermission.php');
$type = $_POST['type'];
$lastslno = $_POST['lastslno'];

switch($type)
{
	case 'save':
	{
		$responsearray = array();
		$productcode = $_POST['productcode'];
		$dealerid = $_POST['dealerid'];
		if($lastslno == '')
		{
			$query = "select * from inv_productmapping where dealerid = '".$dealerid."' and productcode = '".$productcode."' ";
			$result = runmysqlquery($query);
			if(mysqli_num_rows($result) == 0)
			{
				$query = "INSERT INTO inv_productmapping(dealerid,productcode,userid,createddate,createdip,lastmodifieddate,lastmodifiedip,lastmodifiedby) VALUES('".$dealerid."','".$productcode."','".$userid."','".date('Y-m-d').'('.date('H:i:s').')'."','".$_SERVER['REMOTE_ADDR']."','".date('Y-m-d').' '.date('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','".$userid."')";
				$result = runmysqlquery($query);
				
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','14','".date('Y-m-d').' '.date('H:i:s')."')";
				$eventresult = runmysqlquery($eventquery);
			
				$responsearray['errormessage'] =  "1^ Record Saved Successfully.";
			//	echo("1^ Record Saved Successfully.");
			}
			else
				$responsearray['errormessage'] =  "2^ The Product already exists to the selected dealer.";

			
		}
		else
		{
			$query = "select * from inv_productmapping where dealerid = '".$dealerid."' and productcode = '".$productcode."' and slno <> '".$lastslno."'";
			$result = runmysqlquery($query);
			if(mysqli_num_rows($result) == 0)
			{
				$query = "UPDATE inv_productmapping SET productcode = '".$productcode."',lastmodifieddate ='".date('Y-m-d').'('.date('H:i:s').')'."',lastmodifiedip ='".$_SERVER['REMOTE_ADDR']."', lastmodifiedby = '".$userid."'  WHERE slno = '".$lastslno."'";
				$result = runmysqlquery($query);
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','15','".date('Y-m-d').' '.date('H:i:s')."')";
				$eventresult = runmysqlquery($eventquery);
				$responsearray['errormessage'] =  "1^ Record Saved Successfully.";
			}
			else
				$responsearray['errormessage'] =  "2^ The Product already exists to the selected dealer.";

		}
		echo(json_encode($responsearray));
		
	}
	break;
	
	case 'delete':
	{
		$$responsearray1 = array();
		$query = "DELETE FROM inv_productmapping WHERE slno = '".$lastslno."'";
		$result = runmysqlquery($query);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','16','".date('Y-m-d').' '.date('H:i:s')."')";
		$eventresult = runmysqlquery($eventquery);
		//echo("2^ Record Deleted Successfully.");
		$responsearray1['errormessage'] =  "2^ Record Deleted Successfully.";
		echo(json_encode($responsearray1));
	}
	break;
	
	case 'generatedealerlist':
	{
		$generatedealerlistarray = array();
		$relyonexcecutive_type = $_POST['relyonexcecutive_type'];
		$login_type = $_POST['login_type'];
		$dealerregion = $_POST['dealerregion'];
		$relyonexcecutive_typepiece = ($relyonexcecutive_type == "")?(""):(" AND inv_mas_dealer.relyonexecutive = '".$relyonexcecutive_type."' ");
		$login_typepiece = ($login_type == "")?(""):(" AND inv_mas_dealer.disablelogin = '".$login_type."' ");
		$dealerregionpiece = ($dealerregion == "")?(""):(" AND inv_mas_dealer.region = '".$dealerregion."' ");
		$query = "SELECT slno,businessname FROM inv_mas_dealer where slno <> '532568855' ".$relyonexcecutive_typepiece.$login_typepiece.$dealerregionpiece." ORDER BY businessname";
		$result = runmysqlquery($query);
		//$grid = '<select name="dealerlist" size="5" class="swiftselect" id="dealerlist" style="width:210px; height:400px;" onclick ="selectfromlist(); showunregdcards(); billnumberFunction();" onchange="selectfromlist(); billnumberFunction(); "  >';
		$grid = '';
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$generatedealerlistarray[$count] = $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
		echo(json_encode($generatedealerlistarray));
	}
	break;
	
	case 'generategrid':
	{	
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$dealerid = $_POST['dealerid'];
		$resultcount =  "select inv_productmapping.slno,inv_mas_product.productname,inv_productmapping.createddate,inv_mas_users.fullname,inv_productmapping.lastmodifieddate from inv_productmapping left join inv_mas_product on inv_mas_product.productcode = inv_productmapping.productcode
left join inv_mas_users on inv_mas_users.slno = inv_productmapping.userid where dealerid = '".$dealerid."' and allowdealerpurchase = 'yes' order by createddate desc";
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
			$startlimit = $slnocount;
			$slnocount = $slnocount;
		}
		$query = "select inv_productmapping.slno,inv_mas_product.productname,inv_productmapping.createddate,inv_mas_users.fullname,inv_productmapping.lastmodifieddate from inv_productmapping left join inv_mas_product on inv_mas_product.productcode = inv_productmapping.productcode
left join inv_mas_users on inv_mas_users.slno = inv_productmapping.userid where dealerid = '".$dealerid."' and allowdealerpurchase = 'yes' order by createddate desc LIMIT ".$startlimit.",".$limit." ;";
		$result = runmysqlquery($query);

		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid">Sl No</td><td nowrap = "nowrap" class="td-border-grid">Product Name</td><td nowrap = "nowrap" class="td-border-grid">Created By</td><td nowrap = "nowrap" class="td-border-grid">Created By</td><td nowrap = "nowrap" class="td-border-grid">Last Modified By</td></tr>';
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
			$grid .= '<tr class="gridrow" bgcolor='.$color.' onclick="gridtoform(\''.$fetch[0].'\'); ">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$slnocount."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['productname']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['fullname']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".changedateformatwithtime($fetch['createddate'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".changedateformatwithtime($fetch['lastmodifieddate'])."</td>";
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a class="resendtext" onclick ="getmoreproductdatagrid(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer">Show More Records >>&nbsp;&nbsp;&nbsp;</a><a onclick ="getmoreproductdatagrid(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
			echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid;
		
	}
	break;
	
	case 'gridtoform':
	{
		$gridtoformarray = array();
		$query = "select inv_productmapping.slno,inv_productmapping.productcode,inv_mas_users.fullname from inv_productmapping left join inv_mas_users on inv_mas_users.slno = inv_productmapping.userid where inv_productmapping.slno = '".$lastslno."';";
		$fetch = runmysqlqueryfetch($query);
		$gridtoformarray['errorcode'] = '1';
		$gridtoformarray['slno'] = $fetch['slno'];
		$gridtoformarray['productcode'] = $fetch['productcode'];
		$gridtoformarray['fullname'] = $fetch['fullname'];
		echo(json_encode($gridtoformarray));
		//echo('1^'.$fetch['slno'].'^'.$fetch['productcode'].'^'.$fetch['fullname']);
	}
	break;
}


?>
