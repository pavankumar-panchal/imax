<?php
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');
include('../inc/checksession.php');

if(imaxgetcookie('userid')<> '') 
$userid = imaxgetcookie('userid');
else
{ 
	echo(json_encode('Thinking to redirect'));
	exit;
}

include('../inc/checkpermission.php');
$type = $_POST['type'];
$lastslno = $_POST['lastslno'];

switch($type)
{
	case 'save':
	{
		$scheme = $_POST['scheme'];
		$dealerid = $_POST['dealerid'];
		if($lastslno == '')
		{
			$query = "select * from inv_schememapping where dealerid = '".$dealerid."' and scheme = '".$scheme."' ";
			$result = runmysqlquery($query);
			if(mysqli_num_rows($result) == 0)
			{
				$query = "INSERT INTO inv_schememapping(dealerid,scheme,userid,createddate,createdip,lastmodifieddate,lastmodifiedip,lastmodifiedby) VALUES('".$dealerid."','".$scheme."','".$userid."','".date('Y-m-d').' '.date('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','".date('Y-m-d').' '.date('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','".$userid."')";
				$result = runmysqlquery($query);
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','11','".date('Y-m-d').' '.date('H:i:s')."')";
				$eventresult = runmysqlquery($eventquery);
			
				$responsearray = array();
				$responsearray['errormessage'] = "1^ Record Saved Successfully.";
				//echo("1^ Record Saved Successfully.");
			}
			else
			{
				$responsearray = array();
				$responsearray['errormessage'] = "2^ The Scheme already exists to the selected dealer.";
				//echo("2^ The Scheme already exists to the selected dealer.");
			}
				
		}
		else
		{
			$query = "select * from inv_schememapping where dealerid = '".$dealerid."' and scheme = '".$scheme."' and slno <> '".$lastslno."'";
			$result = runmysqlquery($query);
			if(mysqli_num_rows($result) == 0)
			{
				$query = "UPDATE inv_schememapping SET scheme = '".$scheme."',lastmodifieddate ='".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedip ='".$_SERVER['REMOTE_ADDR']."', lastmodifiedby = '".$userid."'  WHERE slno = '".$lastslno."'";
				$result = runmysqlquery($query);
				
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','12','".date('Y-m-d').' '.date('H:i:s')."')";
				$eventresult = runmysqlquery($eventquery);
			
				$responsearray = array();
				$responsearray['errormessage'] = "1^ Record Saved Successfully.";
				//echo("1^ Record Saved Successfully.");
			}
			else
			{
				$responsearray = array();
				$responsearray['errormessage'] = "2^The Scheme already exists to the selected dealer.";
				//echo("2^The Scheme already exists to the selected dealer.");
			}
		}
		echo(json_encode($responsearray));
		
	}
	break;
	
	case 'delete':
	{
		$responsearray = array();
		$query = "DELETE FROM inv_schememapping WHERE slno = '".$lastslno."'";
		$result = runmysqlquery($query);
		
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','13','".date('Y-m-d').' '.date('H:i:s')."')";
		$eventresult = runmysqlquery($eventquery);
				
		$responsearray['errormessage'] = "2^Record Deleted Successfully.";
		echo(json_encode($responsearray));
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
		$dealerid = $_POST['dealerid'];
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$resultcount = "select inv_schememapping.slno,inv_mas_scheme.schemename,inv_schememapping.createddate,inv_mas_users.fullname,inv_schememapping.lastmodifieddate from inv_schememapping left join inv_mas_scheme on inv_mas_scheme.slno = inv_schememapping.scheme
left join inv_mas_users on inv_mas_users.slno = inv_schememapping.userid where dealerid = '".$dealerid."' order by createddate";
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
		$query = "select inv_schememapping.slno,inv_mas_scheme.schemename,inv_schememapping.createddate,inv_mas_users.fullname,inv_schememapping.lastmodifieddate from inv_schememapping left join inv_mas_scheme on inv_mas_scheme.slno = inv_schememapping.scheme
left join inv_mas_users on inv_mas_users.slno = inv_schememapping.userid where dealerid = '".$dealerid."' order by createddate LIMIT ".$startlimit.",".$limit." ;";
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Scheme Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Created By</td><td nowrap = "nowrap" class="td-border-grid" align="left">Created Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Last Modified By</td></tr>';
		}
		$result = runmysqlquery($query);
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
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['schemename']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['fullname']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch['createddate'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch['lastmodifieddate'])."</td>";
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a class="resendtext" onclick ="getmoreschememappingdatagrid(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer;">Show More Records >>&nbsp;&nbsp;&nbsp;</a><a onclick ="getmoreschememappingdatagrid(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer;"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
			echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid;	
		
	}
	break;
	
	case 'gridtoform':
	{
		$gridtoformarray = array();
		$query = "select inv_schememapping.slno,inv_schememapping.scheme,inv_mas_users.fullname from inv_schememapping left join inv_mas_users on inv_schememapping.userid = inv_mas_users.slno where inv_schememapping.slno = '".$lastslno."';";
		$fetch = runmysqlqueryfetch($query);
		$gridtoformarray['errorcode'] = '1';
		$gridtoformarray['slno'] = $fetch['slno'];
		$gridtoformarray['scheme'] = $fetch['scheme'];
		$gridtoformarray['fullname'] = $fetch['fullname'];
		//echo('1^'.$fetch['slno'].'^'.$fetch['scheme'].'^'.$fetch['fullname']);
		echo(json_encode($gridtoformarray));
	}
	break;
}


?>
