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

include('../inc/checkpermission.php');

$lastslno = $_POST['lastslno'];
$switchtype = $_POST['switchtype'];

switch($switchtype)
{
	case 'smscreditssummarygrid':
	{
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$resultcount = "select inv_mas_customer.businessname,inv_smsactivation.smsusername,inv_smsactivation.smsfromname,inv_smsactivation.usertype, inv_smsactivation.contactperson,inv_smsactivation.cell,inv_smsactivation.emailid, ifnull(credits.totalcredits,0) as totalcredits, utilizedcredits from inv_smsactivation left join (select smsuserid, sum(quantity) as totalcredits from inv_smscredits group by smsuserid) as credits on credits.smsuserid = inv_smsactivation.slno left join inv_mas_customer on inv_mas_customer.slno = inv_smsactivation.userreference where inv_smsactivation.accounttype = 'service' and inv_mas_customer.businessname is not null order by inv_smsactivation.slno;";
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
		$query = "select inv_mas_customer.businessname,inv_smsactivation.smsusername,inv_smsactivation.smsfromname,inv_smsactivation.usertype, inv_smsactivation.contactperson,inv_smsactivation.cell,inv_smsactivation.emailid, ifnull(credits.totalcredits,0) as totalcredits, utilizedcredits from inv_smsactivation left join (select smsuserid, sum(quantity) as totalcredits from inv_smscredits group by smsuserid) as credits on credits.smsuserid = inv_smsactivation.slno left join inv_mas_customer on inv_mas_customer.slno = inv_smsactivation.userreference where inv_smsactivation.accounttype = 'service' and inv_mas_customer.businessname is not null order by inv_smsactivation.slno LIMIT ".$startlimit.",".$limit."; ";
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		//$grid = '<tr><td><table width="100%" cellpadding="3" cellspacing="0">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Company Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Total Credits</td><td nowrap = "nowrap" class="td-border-grid" align="left">Total Utilized</td><td nowrap = "nowrap" class="td-border-grid" align="left">Balance Available</td><td nowrap = "nowrap" class="td-border-grid" align="left">User Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">From Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Contact person</td><td nowrap = "nowrap" class="td-border-grid" align="left">Contact Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Email ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">User Type</td></tr>';
		}
		
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$totalcredits = $fetch['totalcredits'];
			$totalutilized = $fetch['utilizedcredits'];
			$balanceavailable = $totalcredits - $totalutilized;
			$i_n++;
			$slnocount++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr bgcolor='.$color.' class="gridrow1">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['businessname'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($totalcredits)."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($totalutilized)."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($balanceavailable)."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['smsusername'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['smsfromname'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['contactperson'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['cell'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['emailid'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['usertype'])."</td>";

			$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($fetch10);
		if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoresmscreditssummary(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoresmscreditssummary(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			$creditsdetails = getbalancecredits();
			$totalbalanceavialable = explode('^',$creditsdetails);
			
			$totalpurchased = $totalbalanceavialable[0];
			$totalutilized = $totalbalanceavialable[1];
			$stockavailable = $totalbalanceavialable[2];
			$totalallocated = $totalbalanceavialable[3];
			$totalusedbyusers = $totalbalanceavialable[4];
			$unusedwithusers = $totalbalanceavialable[5];
			$balanceavailable = $totalbalanceavialable[6];
			
			echo '1^'.$grid.'^'.$fetchcount.'^'.$linkgrid.'^'.$totalpurchased.'^'.$totalutilized.'^'.$stockavailable.'^'.$totalallocated.'^'.$totalusedbyusers.'^'.$unusedwithusers.'^'.$balanceavailable;	
			//echo($creditsdetails);
	}
	break;
	case 'smscreditssummarygridpromo':
	{
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$resultcount = "select inv_mas_customer.businessname,inv_smsactivation.smsusername,inv_smsactivation.smsfromname,inv_smsactivation.usertype, inv_smsactivation.contactperson,inv_smsactivation.cell,inv_smsactivation.emailid, ifnull(credits.totalcredits,0) as totalcredits, utilizedcredits from inv_smsactivation left join (select smsuserid, sum(quantity) as totalcredits from inv_smscredits group by smsuserid) as credits on credits.smsuserid = inv_smsactivation.slno left join inv_mas_customer on inv_mas_customer.slno = inv_smsactivation.userreference where inv_smsactivation.accounttype = 'promotional' and inv_mas_customer.businessname is not null order by inv_smsactivation.slno;";
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
		$query = "select inv_mas_customer.businessname,inv_smsactivation.smsusername,inv_smsactivation.smsfromname,inv_smsactivation.usertype, inv_smsactivation.contactperson,inv_smsactivation.cell,inv_smsactivation.emailid, ifnull(credits.totalcredits,0) as totalcredits, utilizedcredits from inv_smsactivation left join (select smsuserid, sum(quantity) as totalcredits from inv_smscredits group by smsuserid) as credits on credits.smsuserid = inv_smsactivation.slno left join inv_mas_customer on inv_mas_customer.slno = inv_smsactivation.userreference where inv_smsactivation.accounttype = 'promotional' and inv_mas_customer.businessname is not null order by inv_smsactivation.slno LIMIT ".$startlimit.",".$limit."; ";
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		//$grid = '<tr><td><table width="100%" cellpadding="3" cellspacing="0">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Company Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Total Credits</td><td nowrap = "nowrap" class="td-border-grid" align="left">Total Utilized</td><td nowrap = "nowrap" class="td-border-grid" align="left">Balance Available</td><td nowrap = "nowrap" class="td-border-grid" align="left">User Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">From Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Contact person</td><td nowrap = "nowrap" class="td-border-grid" align="left">Contact Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Email ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">User Type</td></tr>';
		}
		
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$totalcredits = $fetch['totalcredits'];
			$totalutilized = $fetch['utilizedcredits'];
			$balanceavailable = $totalcredits - $totalutilized;
			$i_n++;
			$slnocount++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr bgcolor='.$color.' class="gridrow1">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['businessname'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($totalcredits)."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($totalutilized)."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($balanceavailable)."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['smsusername'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['smsfromname'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['contactperson'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['cell'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['emailid'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['usertype'])."</td>";

			$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($fetch10);
		if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoresmscreditssummarypromo(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoresmscreditssummarypromo(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			$creditsdetailspromo = getbalancecreditspromo();
			$totalbalanceavialablepromo = explode('^',$creditsdetailspromo);
			
			
			$totalpurchasedpromo = $totalbalanceavialablepromo[0];
			$totalutilizedpromo = $totalbalanceavialablepromo[1];
			$stockavailablepromo = $totalbalanceavialablepromo[2];
			$totalallocatedpromo = $totalbalanceavialablepromo[3];
			$totalusedbyuserspromo = $totalbalanceavialablepromo[4];
			$unusedwithuserspromo = $totalbalanceavialablepromo[5];
			$balanceavailablepromo = $totalbalanceavialablepromo[6];
			
			echo '1^'.$grid.'^'.$fetchcount.'^'.$linkgrid.'^'.$totalpurchasedpromo.'^'.$totalutilizedpromo.'^'.$stockavailablepromo.'^'.$totalallocatedpromo.'^'.$totalusedbyuserspromo.'^'.$unusedwithuserspromo.'^'.$balanceavailablepromo;	
			//echo($creditsdetails);
	}
	break;
	
}

function getbalancecredits()
{
	$accountid = "relyon";
	$accountpassword = "smssoftware";
	$targeturl = "http://hapi.smsapi.org/CreditCheck.aspx?";
	$targeturl .= "Username=".$accountid;
	$targeturl .= "&Password=".$accountpassword;
	$response = file_get_contents($targeturl);
	$totalbalance = explode("'",$response);
	$totalavailable = $totalbalance[1] - $totalbalance[3];
	
	//get assigned credits
	$query = 'select sum(quantity) as assigned from inv_smscredits left join inv_smsactivation on inv_smsactivation.slno = inv_smscredits.smsuserid where accounttype = "service";';
	$resultfetch = runmysqlqueryfetch($query);
	$totalassigned = $resultfetch['assigned'];
	
	//Total Utilized credits
	$query1 = 'select sum(utilizedcredits) as numberofcredits from inv_smsactivation where inv_smsactivation.accounttype = "service"';
	$resultfetch1 = runmysqlqueryfetch($query1);
	$totalutilized = $resultfetch1['numberofcredits'];
	
	//unutilized credits
	$unutilized = $totalassigned - $totalutilized;
	
	//Balance Usable
	$balance = $totalavailable - $unutilized;
	
	$creditdetails = $totalbalance[1].'^'.$totalbalance[3].'^'.$totalavailable.'^'.$totalassigned.'^'.$totalutilized.'^'.$unutilized.'^'.$balance;
	
	//$creditdetails = $totalavailable.'^'.$unutilized.'^'.$balance.'^'.$totalbalance[1].'^'.$totalbalance[3];
	return $creditdetails;
}


function getbalancecreditspromo()
{
	$accountid = "relyonpromo";
	$accountpassword = "smssoftware";
	$targeturl = "http://hapi.smsapi.org/CreditCheck.aspx?";
	$targeturl .= "Username=".$accountid;
	$targeturl .= "&Password=".$accountpassword;
	$response = file_get_contents($targeturl);
	$totalbalance = explode("'",$response);
	$totalavailable = $totalbalance[1] - $totalbalance[3];
	
	//get unassigned credits
	$query = 'select sum(quantity) as assigned from inv_smscredits left join inv_smsactivation on inv_smsactivation.slno = inv_smscredits.smsuserid where accounttype = "promotional";';
	$resultfetch = runmysqlqueryfetch($query);
	$totalassigned = $resultfetch['assigned'];
	
	//Stock Available
	$query1 = 'select sum(utilizedcredits) as numberofcredits from inv_smsactivation where inv_smsactivation.accounttype = "promotional";';
	$resultfetch1 = runmysqlqueryfetch($query1);
	$totalutilized = $resultfetch1['numberofcredits'];
	
	//unutilized credits
	$unutilized = $totalassigned - $totalutilized;
	
	//Balance Usable
	$balance = $totalavailable - $unutilized;
	
	//$creditdetails = $totalavailable.'^'.$unutilized.'^'.$balance.'^'.$totalbalance[1].'^'.$totalbalance[3];
$creditdetails = $totalbalance[1].'^'.$totalbalance[3].'^'.$totalavailable.'^'.$totalassigned.'^'.$totalutilized.'^'.$unutilized.'^'.$balance;
	return $creditdetails;
}
?>