<?php

ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');

ini_set('memory_limit', '-1');
if(imaxgetcookie('userid')<> '') 
$userid = imaxgetcookie('userid');
else
{ 
	echo(json_encode('Thinking to redirect'));
	exit;
}
include('../inc/checksession.php');
$lastslno = $_POST['lastslno'];
$switchtype = $_POST['switchtype'];
switch($switchtype)
{
	case 'cardsearch':
	{
		$textfield = $_POST['textfield'];
		$subselection = $_POST['subselection'];
		$scheme = $_POST['scheme'];
		$orderby = $_POST['orderby'];
		$showtype = $_POST['showtype'];
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$schemepiece = ($scheme == "")?(""):(" AND inv_mas_scheme.slno = '".$scheme."' ");
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
		if(strlen($textfield) > 0)
		{
			switch($orderby)
			{
				case "cardid":
					$orderbyfield = "cardid";
					break;
				case "scratchnumber":
					$orderbyfield = "scratchnumber";
					break;
				case "attached":
					$orderbyfield = "attached";
					break;
				case "registered":
					$orderbyfield = "registered";
					break;
				case "blocked":
					$orderbyfield = "blocked";
					break;
				case "online":
					$orderbyfield = "online";
					break;
				case "cancelled":
					$orderbyfield = "cancelled";
				break;
				default:
					$orderbyfield = "scratchnumber";
					break;
			}
			
			switch($subselection)
			{
					case "cardid":
					$query = "select inv_mas_scratchcard.slno,inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_scratchcard.attached,
inv_mas_scratchcard.registered,inv_mas_scratchcard.blocked,inv_mas_scratchcard.online,inv_mas_scratchcard.cancelled,
inv_mas_scheme.schemename from inv_mas_scratchcard left join inv_dealercard on inv_dealercard.cardid = inv_mas_scratchcard.cardid
left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme  WHERE inv_mas_scratchcard.cardid LIKE '%".$textfield."%'  ".$schemepiece." ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
					case "scratchnumber":
					$query = "select inv_mas_scratchcard.slno,inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_scratchcard.attached,
inv_mas_scratchcard.registered,inv_mas_scratchcard.blocked,inv_mas_scratchcard.online,inv_mas_scratchcard.cancelled,
inv_mas_scheme.schemename from inv_mas_scratchcard left join inv_dealercard on inv_dealercard.cardid = inv_mas_scratchcard.cardid
left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme WHERE inv_mas_scratchcard.scratchnumber LIKE '%".$textfield."%'  ".$schemepiece." ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
					case "attached": 
						$query = "select inv_mas_scratchcard.slno,inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_scratchcard.attached,
inv_mas_scratchcard.registered,inv_mas_scratchcard.blocked,inv_mas_scratchcard.online,inv_mas_scratchcard.cancelled,
inv_mas_scheme.schemename from inv_mas_scratchcard left join inv_dealercard on inv_dealercard.cardid = inv_mas_scratchcard.cardid
left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme  WHERE inv_mas_scratchcard.attached LIKE '%".$textfield."%' ".$schemepiece." ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
					case "registered":
						$query = "select inv_mas_scratchcard.slno,inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_scratchcard.attached,
inv_mas_scratchcard.registered,inv_mas_scratchcard.blocked,inv_mas_scratchcard.online,inv_mas_scratchcard.cancelled,
inv_mas_scheme.schemename from inv_mas_scratchcard left join inv_dealercard on inv_dealercard.cardid = inv_mas_scratchcard.cardid
left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme  WHERE inv_mas_scratchcard.registered LIKE '%".$textfield."%'  ".$schemepiece." ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
					case "blocked":
						$query = "select inv_mas_scratchcard.slno,inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_scratchcard.attached,
inv_mas_scratchcard.registered,inv_mas_scratchcard.blocked,inv_mas_scratchcard.online,inv_mas_scratchcard.cancelled,
inv_mas_scheme.schemename from inv_mas_scratchcard left join inv_dealercard on inv_dealercard.cardid = inv_mas_scratchcard.cardid
left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme  WHERE inv_mas_scratchcard.blocked LIKE '%".$textfield."%'  ".$schemepiece." ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
					case "online":
						$query = "select inv_mas_scratchcard.slno,inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_scratchcard.attached,
inv_mas_scratchcard.registered,inv_mas_scratchcard.blocked,inv_mas_scratchcard.online,inv_mas_scratchcard.cancelled,
inv_mas_scheme.schemename from inv_mas_scratchcard left join inv_dealercard on inv_dealercard.cardid = inv_mas_scratchcard.cardid
left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme  WHERE inv_mas_scratchcard.online LIKE '%".$textfield."%'  ".$schemepiece." ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
					case "cancelled":
						$query = "select inv_mas_scratchcard.slno,inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_scratchcard.attached,
inv_mas_scratchcard.registered,inv_mas_scratchcard.blocked,inv_mas_scratchcard.online,inv_mas_scratchcard.cancelled,
inv_mas_scheme.schemename from inv_mas_scratchcard left join inv_dealercard on inv_dealercard.cardid = inv_mas_scratchcard.cardid
left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme  WHERE inv_mas_scratchcard.cancelled LIKE '%".$textfield."%'  ".$schemepiece." ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
					default:
						$query = "select inv_mas_scratchcard.slno,inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_scratchcard.attached,
inv_mas_scratchcard.registered,inv_mas_scratchcard.blocked,inv_mas_scratchcard.online,inv_mas_scratchcard.cancelled,
inv_mas_scheme.schemename from inv_mas_scratchcard left join inv_dealercard on inv_dealercard.cardid = inv_mas_scratchcard.cardid
left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme  WHERE inv_mas_scratchcard.scratchnumber LIKE '%".$textfield."%'  ".$schemepiece." ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
			}
			if($startlimit == 0)
			{
				$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" >';
				
				$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">PIN Serial Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">PIN Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Attached</td><td nowrap = "nowrap" class="td-border-grid" align="left">Registered</td><td nowrap = "nowrap" class="td-border-grid" align="left">Blocked</td><td nowrap = "nowrap" class="td-border-grid" align="left">Online</td><td nowrap = "nowrap" class="td-border-grid" align="left">Cancelled</td><td nowrap = "nowrap" class="td-border-grid" align="left">Scheme</td></tr>';
			}
			$result = runmysqlquery($query);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','67','".date('Y-m-d').' '.date('H:i:s')."')";
			$eventresult = runmysqlquery($eventquery);
			while($fetch = mysqli_fetch_array($result))
			{
				$i_n++;
				$slnocount++;
				$color;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				
				$grid .= '<tr class="gridrow" bgcolor='.$color.'>';
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['slno']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['cardid']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['scratchnumber']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['attached']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['registered']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['blocked']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['online']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['cancelled']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['schemename']."</td>";
			
				$grid .= '</tr>';
			}
			$fetchcount = mysqli_num_rows($result);
			if($fetchcount < $limit)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px">&nbsp;&nbsp;&nbsp;<a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class ="resendtext1" style="cursor:pointer"><font color= "#000000">(Show All Records)</font></a></div></td></tr></table>';
			$grid .= "</table>";
			echo '1^'.$grid.'^'.$fetchcount.'^'.$linkgrid;
			//echo($query);
		}
	}
	break;
}
?>
