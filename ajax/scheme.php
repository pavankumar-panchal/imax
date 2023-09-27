<?
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

$lastslno = $_POST['lastslno'];
$switchtype = $_POST['switchtype'];

switch($switchtype)
{
	case 'save':
	{
		$schemename = $_POST['schemename'];
		$schemedescription = $_POST['schemedescription'];
		$disablescheme = $_POST['disablescheme'];
		$startdate = $_POST['startdate'];
		$todate = $_POST['todate'];
		if($lastslno == "")
		{

				$query = "Insert into inv_mas_scheme(schemename,description,disablescheme,fromdate,todate,userid,createddate,createdip,lastmodifieddate,lastmodifiedby,lastmodifiedip) values ('".trim($schemename)."','".$schemedescription."','".$disablescheme."','".changedateformat($startdate)."','".changedateformat($todate)."','".$userid."','".date('Y-m-d').' '.date('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."');";
			$result = runmysqlquery($query);
			$responsearray = array();
			$responsearray['errormessage'] = "1^"."Scheme Saved Successfully";
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','8','".date('Y-m-d').' '.date('H:i:s')."')";
			$eventresult = runmysqlquery($eventquery);
			echo(json_encode($responsearray));
			//echo(json_encode("1^"."Scheme Saved Successfully"));

		}
		else
		{
						
			$query = "UPDATE inv_mas_scheme SET schemename = '".trim($schemename)."',description = '".$schemedescription."',disablescheme = '".$disablescheme."',fromdate = '".changedateformat($startdate)."',todate = '".changedateformat($todate)."', createddate = '".datetimelocal('H:i:s')."',lastmodifieddate = '".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedby ='".$userid."',lastmodifiedip =  '".$_SERVER['REMOTE_ADDR']."'  WHERE slno = '".$lastslno."'";
			$result = runmysqlquery($query);
			$responsearray = array();
			$responsearray['errormessage'] = "4^"."Scheme Saved Successfully";
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','9','".date('Y-m-d').' '.date('H:i:s')."')";
			$eventresult = runmysqlquery($eventquery);
			echo(json_encode($responsearray));

		}
	
	}
	break;
	case 'delete':
	{
		$responsearray1 = array();
		$recordflag1 = deleterecordcheck($lastslno,'scheme','inv_billdetail');
		$recordflag2 = deleterecordcheck($lastslno,'scheme','inv_dealercard');

		if($recordflag1 == true && $recordflag2 == true)
		{
			$query = "delete from inv_mas_scheme where slno = '".$lastslno."'";
			$result = runmysqlquery($query);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','10','".date('Y-m-d').' '.date('H:i:s')."')";
			$eventresult = runmysqlquery($eventquery);
			
			$responsearray['errormessage'] = "2^"."Record Deleted Successfully";
		}
		else
		{
			$responsearray['errormessage'] = "3^"."Scheme cannot be deleted as the record referred";
		}
		echo(json_encode($responsearray));
	}
	break;
	case 'generateschemelist':
	{
		$generateschemelistarray = array();
		$query = "SELECT slno,schemename FROM inv_mas_scheme ORDER BY schemename";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$generateschemelistarray[$count] = $fetch['schemename'].'^'.$fetch['slno'];
			$count++;
		}
		echo(json_encode($generateschemelistarray));
	}
	break;
	case 'schemedetailstoform':
	{
		$schemedetailstoformarray = array();
		$query1 = "SELECT count(*) as count from inv_mas_scheme where slno = '".$lastslno."'";
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)
		{
			$query = "select inv_mas_scheme.slno,inv_mas_scheme.schemename,inv_mas_scheme.description,inv_mas_scheme.disablescheme,inv_mas_scheme.fromdate,inv_mas_scheme.todate,inv_mas_users.fullname from inv_mas_scheme left join inv_mas_users on inv_mas_users.slno = inv_mas_scheme.userid where inv_mas_scheme.slno = '".$lastslno."';";
			$fetch = runmysqlqueryfetch($query);
			$schemedetailstoformarray['errorcode'] = '1';
			$schemedetailstoformarray['slno'] = $fetch['slno'];
			$schemedetailstoformarray['schemename'] = $fetch['schemename'];
			$schemedetailstoformarray['description'] = $fetch['description'];
			$schemedetailstoformarray['disablescheme'] = $fetch['disablescheme'];
			$schemedetailstoformarray['fromdate'] = changedateformat($fetch['fromdate']);
			$schemedetailstoformarray['todate'] = changedateformat($fetch['todate']);
			$schemedetailstoformarray['fullname'] = $fetch['fullname'];
			
			
			echo(json_encode($schemedetailstoformarray));
			//echo('1^'.$fetch['slno'].'^'.$fetch['schemename'].'^'.$fetch['description'].'^'.$fetch['disablescheme'].'^'.changedateformat($fetch['fromdate']).'^'.changedateformat($fetch['todate']).'^'.$fetch['fullname']);
			
		}
		else
		{
			$schemedetailstoformarray['errorcode'] = '2';
			$schemedetailstoformarray['slno'] = $lastslno;
			$schemedetailstoformarray['schemename'] = '';
			$schemedetailstoformarray['description'] = '';
			$schemedetailstoformarray['disablescheme'] = '';
			$schemedetailstoformarray['fromdate'] = '';
			$schemedetailstoformarray['todate'] = '';
			$schemedetailstoformarray['fullname'] = '';
			echo(json_encode($schemedetailstoformarray));
			//echo('2^'.$lastslno.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
		}
	}
	break;
	
	case 'generateschemegrid':
	{
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$resultcount = "select inv_mas_scheme.slno,inv_mas_scheme.schemename,inv_mas_scheme.description,inv_mas_scheme.disablescheme,inv_mas_scheme.fromdate,inv_mas_scheme.todate,inv_mas_users.fullname from inv_mas_scheme left join inv_mas_users on inv_mas_users.slno = inv_mas_scheme.userid order by fromdate ; ";
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
		$query = "select inv_mas_scheme.slno,inv_mas_scheme.schemename,inv_mas_scheme.description,inv_mas_scheme.disablescheme,inv_mas_scheme.fromdate,inv_mas_scheme.todate,inv_mas_users.fullname from inv_mas_scheme left join inv_mas_users on inv_mas_users.slno = inv_mas_scheme.userid order by fromdate LIMIT ".$startlimit.",".$limit."; ";
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		//$grid = '<tr><td><table width="100%" cellpadding="3" cellspacing="0">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Scheme Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Description</td><td nowrap = "nowrap" class="td-border-grid" align="left">Disable Scheme</td><td nowrap = "nowrap" class="td-border-grid" align="left">From Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">To Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Entered By</td></tr>';
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
			$grid .= '<tr bgcolor='.$color.' class="gridrow" onclick ="schemedetailstoform(\''.$fetch['slno'].'\');" >';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['schemename'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['description'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['disablescheme'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch['fromdate'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch['todate'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['fullname'])."</td>";
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoregenerateschemegrid(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >></a><a onclick ="getmoregenerateschemegrid(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
			echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid;	
	}
	break;
	
}



?>