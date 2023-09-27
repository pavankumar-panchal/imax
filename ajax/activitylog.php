<?
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');
include('../inc/checksession.php');

if(imaxgetcookie('userid')<> '') 
$userid = imaxgetcookie('userid');
else
{ 
	echo('Thinking to redirect');
	exit;
}

$switchtype = $_POST['switchtype'];

switch($switchtype)
{
	
	case 'searchactivity':
	{
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$fromdate = changedateformat($_POST['fromdate']);
		$todate = changedateformat($_POST['todate']);
		
		$databasefield = $_POST['databasefield'];
		$textfield = $_POST['textfield'];
		$modulename = $_POST['modulename'];
		$eventtype = $_POST['eventtype'];
		$pageshortname = $_POST['pageshortname'];
		$generatedby = $_POST['username'];
		$generatedbysplit = explode('^',$generatedby);
		$eventtypesplit = explode('^',$eventtype);
		$pageshortnamesplit = explode('^',$pageshortname);
		
		if($generatedbysplit[1] == "[U]")
			$modulepiece = 'USER';
		elseif($generatedbysplit[1] == "[D]")
			$modulepiece = 'DEALER';
		elseif($generatedbysplit[1] == "[I]")
			$modulepiece = 'IMPLEMENTATION';
			
		if($eventtypesplit[1] == "[U]")
			$eventpiece = 'USER';
		elseif($eventtypesplit[1] == "[D]")
			$eventpiece = 'DEALER';
		elseif($eventtypesplit[1] == "[I]")
			$eventpiece = 'IMPLEMENTATION';
			
		if($pageshortnamesplit[1] == "[U]")
			$pageshortpiece = 'USER';
		elseif($pageshortnamesplit[1] == "[D]")
			$pageshortpiece = 'DEALER';
		elseif($pageshortnamesplit[1] == "[I]")
			$pageshortpiece = 'IMPLEMENTATION';
		
		$generatedpiece = ($generatedby == "")?(""):(" AND inv_logs_event.userid = '".$generatedbysplit[0]."' and inv_logs_eventtype.modulename = '".$modulepiece."'");
		$eventtypepiece = ($eventtype == "")?(""):(" AND inv_logs_eventtype.slno = '".$eventtypesplit[0]."' and inv_logs_eventtype.modulename = '".$eventpiece."'");
		$modulenamepiece = ($modulename == "")?(""):(" AND inv_logs_eventtype.modulename = '".strtoupper($modulename)."' ");
		$pageshortnamepiece = ($pageshortname == "")?(""):(" AND inv_logs_eventtype.pagesshortname = '".$pageshortnamesplit[0]."' and inv_logs_eventtype.modulename = '".$pageshortpiece."'");

		$start_ts = strtotime($fromdate);
		$end_ts = strtotime($todate);
		$datediff = $end_ts - $start_ts;
		$noofdays = round($datediff / 86400);
		if($noofdays > 6)
		{
			echo('2^'.'Date limit should be within 7 days');
			exit;
		}

		
		$resultcount = "select count(distinct inv_logs_event.slno) as count from inv_logs_event 
		left join inv_logs_eventtype on inv_logs_eventtype.slno = inv_logs_event.eventtype
where (left(inv_logs_event.eventdatetime,10) between '".$fromdate."' AND '".$todate."')   ".$generatedpiece.$eventtypepiece.$modulenamepiece.$paymentmodepiece.$pageshortnamepiece." order by inv_logs_event.slno;";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];
		
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
		
		switch($databasefield)
		{
			case "userid":
					
				$query = "select distinct inv_logs_event.slno, inv_logs_event.remarks, inv_logs_event.eventdatetime as `datetime`, 
inv_logs_eventtype.modulename, inv_logs_eventtype.eventtype, inv_logs_eventtype.pagesshortname,
CASE WHEN inv_logs_eventtype.modulename = 'USER'  THEN inv_mas_users.username  WHEN inv_logs_eventtype.modulename = 'DEALER' THEN inv_mas_dealer.businessname ELSE inv_mas_implementer.businessname END AS usernametype,inv_logs_event.system
from inv_logs_event left join inv_logs_eventtype on inv_logs_eventtype.slno = inv_logs_event.eventtype
left join inv_mas_users on inv_mas_users.slno =  inv_logs_event.userid and  inv_logs_eventtype.modulename = 'USER'
left join inv_mas_dealer on inv_mas_dealer.slno =  inv_logs_event.userid and  inv_logs_eventtype.modulename = 'DEALER'
left join inv_mas_implementer on inv_mas_implementer.slno =  inv_logs_event.userid and inv_logs_eventtype.modulename = 'IMPLEMENTATION'
where (left(inv_logs_event.eventdatetime,10) between '".$fromdate."' AND '".$todate."') AND inv_logs_event.userid LIKE '%".$textfield."%' ".$generatedpiece.$eventtypepiece.$modulenamepiece.$paymentmodepiece.$pageshortnamepiece."  order by inv_logs_event.slno ";
				break;
			case "systemip":
				$query = "select distinct inv_logs_event.slno, inv_logs_event.remarks, inv_logs_event.eventdatetime as `datetime`, 
inv_logs_eventtype.modulename, inv_logs_eventtype.eventtype, inv_logs_eventtype.pagesshortname,
CASE WHEN inv_logs_eventtype.modulename = 'USER'  THEN inv_mas_users.username  WHEN inv_logs_eventtype.modulename = 'DEALER' THEN inv_mas_dealer.businessname ELSE inv_mas_implementer.businessname END AS usernametype,inv_logs_event.system
from inv_logs_event left join inv_logs_eventtype on inv_logs_eventtype.slno = inv_logs_event.eventtype
left join inv_mas_users on inv_mas_users.slno =  inv_logs_event.userid and  inv_logs_eventtype.modulename = 'USER'
left join inv_mas_dealer on inv_mas_dealer.slno =  inv_logs_event.userid and  inv_logs_eventtype.modulename = 'DEALER'
left join inv_mas_implementer on inv_mas_implementer.slno =  inv_logs_event.userid and inv_logs_eventtype.modulename = 'IMPLEMENTATION'
where (left(inv_logs_event.eventdatetime,10) between '".$fromdate."' AND '".$todate."') AND  inv_logs_event.system LIKE '%".$textfield."%' ".$generatedpiece.$eventtypepiece.$modulenamepiece.$paymentmodepiece.$pageshortnamepiece."  order by inv_logs_event.slno";
				break;
			default:
				$query = "select distinct  inv_logs_event.slno, inv_logs_event.remarks as remarks, inv_logs_event.eventdatetime as `datetime`,inv_logs_eventtype.modulename, inv_logs_eventtype.eventtype, inv_logs_eventtype.pagesshortname,
CASE WHEN inv_logs_eventtype.modulename = 'USER'  THEN inv_mas_users.username  WHEN inv_logs_eventtype.modulename = 'DEALER' THEN inv_mas_dealer.businessname ELSE inv_mas_implementer.businessname END AS usernametype,inv_logs_event.system
from inv_logs_event left join inv_logs_eventtype on inv_logs_eventtype.slno = inv_logs_event.eventtype
left join inv_mas_users on inv_mas_users.slno =  inv_logs_event.userid and  inv_logs_eventtype.modulename = 'USER'
left join inv_mas_dealer on inv_mas_dealer.slno =  inv_logs_event.userid and  inv_logs_eventtype.modulename = 'DEALER'
left join inv_mas_implementer on inv_mas_implementer.slno =  inv_logs_event.userid and inv_logs_eventtype.modulename = 'IMPLEMENTATION'
where (left(inv_logs_event.eventdatetime,10) between '".$fromdate."' AND '".$todate."') AND  inv_logs_eventtype.modulename LIKE '%".$textfield."%' ".$generatedpiece.$eventtypepiece.$modulenamepiece.$paymentmodepiece.$pageshortnamepiece."  order by inv_logs_event.slno ";
				break;
		} 
		$result = runmysqlquery($query);
		/*$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','78','".date('Y-m-d').' '.date('H:i:s')."','view_receiptregister')";
		$eventresult = runmysqlquery($eventquery);*/
		$fetchresultcount = mysqli_num_rows($result);
		$addlimit = " LIMIT ".$startlimit.",".$limit.";";
		$query1 = $query.$addlimit;
		$result1 = runmysqlquery($query1);
		$grid = '';
			
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Module Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Event Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Pages Short Names</td><td nowrap = "nowrap" class="td-border-grid" align="left">Username</td><td nowrap = "nowrap" class="td-border-grid" align="left">System IP</td><td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td><td nowrap = "nowrap" class="td-border-grid" align="left">Date</td></tr>';
		}
		
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result1))
		{
			$i_n++;
			$slnocount++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
				$grid .= '<tr bgcolor='.$color.'  align="left">';
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['modulename']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['eventtype']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['pagesshortname']."</td>";				
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['usernametype']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['system']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['remarks']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch['datetime'])."</td>";
				$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
	
			echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid;	
	}
	break;
	
	
	case 'todayactivity':
	{
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		
		$resultcount = "select count(distinct inv_logs_event.slno) as count from inv_logs_event 
		left join inv_logs_eventtype on inv_logs_eventtype.slno = inv_logs_event.eventtype
where (left(inv_logs_event.eventdatetime,10) between '".date('Y-m-d')."' AND '".date('Y-m-d')."')    order by inv_logs_event.slno;";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];
		
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
		
					
		$query = "select distinct inv_logs_event.slno, inv_logs_event.remarks, inv_logs_event.eventdatetime as `datetime`, 
inv_logs_eventtype.modulename, inv_logs_eventtype.eventtype, inv_logs_eventtype.pagesshortname,
CASE WHEN inv_logs_eventtype.modulename = 'USER'  THEN inv_mas_users.username  WHEN inv_logs_eventtype.modulename = 'DEALER' THEN inv_mas_dealer.businessname ELSE inv_mas_implementer.businessname END AS usernametype,inv_logs_event.system
from inv_logs_event left join inv_logs_eventtype on inv_logs_eventtype.slno = inv_logs_event.eventtype
left join inv_mas_users on inv_mas_users.slno =  inv_logs_event.userid and  inv_logs_eventtype.modulename = 'USER'
left join inv_mas_dealer on inv_mas_dealer.slno =  inv_logs_event.userid and  inv_logs_eventtype.modulename = 'DEALER'
left join inv_mas_implementer on inv_mas_implementer.slno =  inv_logs_event.userid and inv_logs_eventtype.modulename = 'IMPLEMENTATION'
where (left(inv_logs_event.eventdatetime,10) between '".date('Y-m-d')."' AND '".date('Y-m-d')."') order by inv_logs_event.slno ";
				
		$result = runmysqlquery($query);
		/*$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','78','".date('Y-m-d').' '.date('H:i:s')."','view_receiptregister')";
		$eventresult = runmysqlquery($eventquery);*/
		$fetchresultcount = mysqli_num_rows($result);
		$addlimit = " LIMIT ".$startlimit.",".$limit.";";
		$query1 = $query.$addlimit;
		$result1 = runmysqlquery($query1);
		$grid = '';
			
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Module Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Event Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Pages Short Names</td><td nowrap = "nowrap" class="td-border-grid" align="left">Username</td><td nowrap = "nowrap" class="td-border-grid" align="left">System IP</td><td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td><td nowrap = "nowrap" class="td-border-grid" align="left">Date</td></tr>';
		}
		
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result1))
		{
			$i_n++;
			$slnocount++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
				$grid .= '<tr bgcolor='.$color.'  align="left">';
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['modulename']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['eventtype']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['pagesshortname']."</td>";				
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['usernametype']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['system']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['remarks']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch['datetime'])."</td>";
				$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoretodaysresult(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoretodaysresult(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
	
			echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid;	
	}
	break;
	
}

?>
