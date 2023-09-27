<?
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');

if(imaxgetcookie('userid')<> '') 
$dealerid = imaxgetcookie('userid');
else
{ 
	echo(json_encode('Thinking to redirect'));
	exit;
}
include('../inc/checksession.php');
include('../inc/checkpermission.php');
$type = $_POST['type'];
$lastslno = $_POST['lastslno'];
switch($type)
{
	case 'save':
	{
		$responsearray = array();
		$district = $_POST['district'];
		$dealerid = $_POST['dealerid'];
		if($lastslno == '')
		{
			$query = "select * from inv_districtmapping where dealerid = '".$dealerid."' and districtcode = '".$district."' ";
			$result = runmysqlquery($query);
			if(mysqli_num_rows($result) == 0)
			{
				$query = "INSERT INTO inv_districtmapping(dealerid,districtcode) VALUES('".$dealerid."','".$district."')";
				$result = runmysqlquery($query);
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','38','".date('Y-m-d').' '.date('H:i:s')."','".$dealerid."')";
				$eventresult = runmysqlquery($eventquery);

				$responsearray['errormessage'] = "1^ Record Saved Successfully.";
				//echo("1^ Record Saved Successfully.");
			}
			else
			{
				$responsearray['errormessage'] = "1^ Record Saved Successfully.";
				//echo("2^ This district is already exists to the selected dealer.");
			}
				
		}
		else
		{
			$query = "select * from inv_districtmapping where dealerid = '".$dealerid."' and districtcode = '".$district."' ";
			$result = runmysqlquery($query);
			if(mysqli_num_rows($result) == 0)
			{
				$query = "UPDATE inv_districtmapping SET districtcode = '".$district."' WHERE slno = '".$lastslno."'";
				$result = runmysqlquery($query);
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','39','".date('Y-m-d').' '.date('H:i:s')."','".$dealerid."')";
				$eventresult = runmysqlquery($eventquery);
				$responsearray['errormessage'] = "1^ Record Saved Successfully.";
				//echo("1^ Record Saved Successfully.");
			}
			else
			{
				$responsearray['errormessage'] = "2^ This district is already exists to the selected dealer.";
				//echo("2^ This district is already exists to the selected dealer.");
			}
		}
		echo(json_encode($responsearray));
		
	}
	break;
	
	case 'delete':
	{
		$responsearray1 = array();
		$query = "DELETE FROM inv_districtmapping WHERE slno = '".$lastslno."'";
		$result = runmysqlquery($query);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','40','".date('Y-m-d').' '.date('H:i:s')."')";
		$eventresult = runmysqlquery($eventquery);
		$responsearray1['errormessage'] = "2^ Record Deleted Successfully.";
		echo(json_encode($responsearray1));
		//echo("2^ Record Deleted Successfully.");
	}
	break;
	
	case 'generatedealerlist':
	{
		$responsearray2 = array();
		$relyonexcecutive_type = $_POST['relyonexcecutive_type'];
		$login_type = $_POST['login_type'];
		$dealerregion = $_POST['dealerregion'];
		$relyonexcecutive_typepiece = ($relyonexcecutive_type == "")?(""):(" AND inv_mas_dealer.relyonexecutive = '".$relyonexcecutive_type."' ");
		$login_typepiece = ($login_type == "")?(""):(" AND inv_mas_dealer.disablelogin = '".$login_type."' ");
		$dealerregionpiece = ($dealerregion == "")?(""):(" AND inv_mas_dealer.region = '".$dealerregion."' ");
		$query = "SELECT slno,businessname FROM inv_mas_dealer where slno <> '532568855' ".$relyonexcecutive_typepiece.$login_typepiece.$dealerregionpiece." ORDER BY businessname";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$responsearray2[$count] = $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
		echo(json_encode($responsearray2));
	}
	break;
	
	case 'getdealername':
	{
		$dealerid = $_POST['dealerid'];
		$query = "SELECT businessname FROM inv_mas_dealer WHERE slno = '".$dealerid."'";
		$fetch = runmysqlqueryfetch($query);
		echo($fetch['businessname'].'^');
		
		$query0 = "SELECT sum(creditamount) as totalcredit FROM inv_credits WHERE dealerid = '".$dealerid."'";
		$resultfetch = runmysqlqueryfetch($query0);
		$totalcredit = $resultfetch['totalcredit'];
		$query1 = "SELECT sum(netamount) as billedamount FROM inv_bill WHERE dealerid = '".$dealerid."' AND billstatus ='successful'";
		$resultfetch1 = runmysqlqueryfetch($query1);
		$billedamount =$resultfetch1['billedamount'];
		$totalcredit = $totalcredit - $billedamount;
		echo('1^'.$totalcredit);
	}
	break;
	
	case 'generategrid':
	{	
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$dealerid = $_POST['dealerid'];
		$resultcount = "Select inv_districtmapping.slno,inv_mas_district.districtname,inv_mas_state.statename from inv_districtmapping left join inv_mas_district on inv_mas_district.districtcode = inv_districtmapping.districtcode
left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_districtmapping.dealerid ='".$dealerid."' order by inv_districtmapping.slno ;";
		$resultfetch = runmysqlquery($resultcount);
		$fetchresultcount = mysqli_num_rows($resultfetch);
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
		$query = "Select inv_districtmapping.slno,inv_mas_district.districtname,inv_mas_state.statename from inv_districtmapping left join inv_mas_district on inv_mas_district.districtcode = inv_districtmapping.districtcode
left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_districtmapping.dealerid ='".$dealerid."' LIMIT ".$startlimit.",".$limit." ;";
		$result = runmysqlquery($query);
		
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid"  align="left">District</td><td nowrap = "nowrap" class="td-border-grid"  align="left">State</td></tr>';
		}
		$i_n = 0;
		while($fetch = mysqli_fetch_row($result))
		{
			$i_n++;
			$slnocount++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow" bgcolor='.$color.' onclick="gridtoform(\''.$fetch[0].'\'); ">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
			for($i = 1; $i < count($fetch); $i++)
			{
			
				$grid .= "<td nowrap='nowrap' class='td-border-grid'  align='left'>".gridtrim($fetch[$i])."</td>";
			}
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		if($slnocount >= $fetchresultcount)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="getmoredistrictdatagrid(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmoredistrictdatagrid(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color ="#000000">(Show All Records)</font></a></div></td></tr></table>';
		
		echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid;
		
	}
	break;
	
	case 'gridtoform':
	{
		$gridtoformarray = array();
		$query = "select inv_districtmapping.slno,inv_mas_district.districtcode,inv_mas_district.statecode from inv_districtmapping 
left join inv_mas_district on inv_mas_district.districtcode = inv_districtmapping.districtcode where inv_districtmapping.slno = '".$lastslno."'";
		$fetch = runmysqlqueryfetch($query);
		$gridtoformarray['errorcode'] = '1';
		$gridtoformarray['slno'] = $fetch['slno'];
		$gridtoformarray['districtcode'] = $fetch['districtcode'];
		$gridtoformarray['statecode'] =$fetch['statecode'];
		//echo('1^'.$fetch['slno'].'^'.$fetch['districtcode'].'^'.$fetch['statecode']);
		echo(json_encode($gridtoformarray));
	}
	break;
}


?>
