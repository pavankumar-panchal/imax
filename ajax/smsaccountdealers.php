<?

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

$lastslno = $_POST['lastslno'];
$switchtype = $_POST['switchtype'];
$smslastslno = $_POST['smslastslno'];
switch($switchtype)
{
	case 'save':
	{
		$dealerid = $_POST['dealerid'];
		$smsactivateddate = date('Y-m-d').' '.date('H:i:s');
		$smsusername = $_POST['username'];
		$smsfromname = $_POST['fromname'];
		$smspassword = $_POST['password'];
		$disablesmsaccount = $_POST['disablesmsaccount'];
		$contactperson = $_POST['contactperson'];
		$emailid = $_POST['emailid'];
		$cell = $_POST['cell'];
		$croptext = $_POST['croptext'];
		if($smslastslno == '')
		{
			$query1 = "select * from inv_smsactivation where smsusername = '".$smsusername."' ";
			$result1 = runmysqlquery($query1);
			if(mysqli_fetch_row($result1) > 0)
			{
				echo("2^"."User Name Already exists. Please use a different Username");
			}
			else
			{
				$query = "INSERT INTO inv_smsactivation(userreference,usertype,contactperson,emailid,cell,smsfromname,smsusername,smspassword,smsaccountdisabled,createdby,createddate,createdip,lastmodifiedby,lastmodifieddate,lastmodifiedip,croptext) values('".$dealerid."','dealer','".$contactperson."','".$emailid."','".$cell."','".$smsfromname."','".$smsusername."','".$smspassword."','".$disablesmsaccount."','".$userid."','".date('Y-m-d').' '.date('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','".$userid."','".date('Y-m-d').' '.date('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','".$croptext."');";
				$result = runmysqlquery($query);
				$query1 = "select max(slno) as smsuserid from inv_smsactivation;";
				$resultfetch = runmysqlqueryfetch($query1);
				$smsuserid = $resultfetch['smsuserid'];
				echo("1^"."SMS Account activated Successfully");
			}
		}
		else
		{
			$query = "select * from inv_smsactivation where smsusername = '".$smsusername."' and slno <> '".$smslastslno."'";
			$result = runmysqlquery($query);
			if(mysqli_fetch_row($result) > 0)
			{
				echo("2^"."User Name Already exists. Please use a different Username");
			}
			else
			{
				$query = "UPDATE inv_smsactivation SET contactperson = '".$contactperson."',emailid = '".$emailid."',cell = '".$cell."',smsfromname = '".$smsfromname."',smsusername = '".$smsusername."',smspassword = '".$smspassword."',smsaccountdisabled = '".$disablesmsaccount."',lastmodifiedby = '".$userid."',lastmodifieddate = '".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."',croptext = '".$croptext."' where slno = '".$smslastslno."' and usertype = 'dealer';";
				$result = runmysqlquery($query);
				echo("1^"."SMS Account Updated Successfully");
			}
		}
	}
	break;
	case 'disable':
	{
		$dealerid = $_POST['dealerid'];
		$disablesmsaccount = $_POST['disablesmsaccount'];
		$query = "UPDATE inv_smsactivation set smsaccountdisabled = '".$disablesmsaccount."' where slno = '".$smslastslno."' and usertype = 'dealer'; ";
		$result = runmysqlquery($query);
		echo("1^"."SMS Account Disabled Successfully");
	}
	break;
	case 'generatedealerlist':
	{
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
		$count = 1;
		while($fetch = mysqli_fetch_array($result))
		{
			if($count > 1)
				$grid .='^*^';
			$grid .= $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
		echo($grid);
	}
	break;
	
	case 'gridtoform':
	{
		$smslastslno = $_POST['smslastslno'];
		$query = "select slno,contactperson,emailid,cell,smsfromname,smsusername,smspassword,smsaccountdisabled,croptext from inv_smsactivation where slno = '".$smslastslno."' and usertype = 'dealer';";
		$result = runmysqlquery($query);
		if(mysqli_fetch_row($result) > 0)
		{
			$fetch = runmysqlqueryfetch($query);
			echo('1^'.$fetch['slno'].'^'.$fetch['contactperson'].'^'.$fetch['emailid'].'^'.$fetch['cell'].'^'.$fetch['smsfromname'].'^'.$fetch['smsusername'].'^'.$fetch['smspassword'].'^'.$fetch['smsaccountdisabled'].'^'.$fetch['croptext']);
		}
		else
		{
			echo('2^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
		}
	}
	break;
	case 'generateaccountgrid':
	{
		$dealerid = $_POST['dealerid'];
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$resultcount = "select slno,contactperson,emailid,cell,smsfromname,smsusername,smspassword,smsaccountdisabled,croptext from inv_smsactivation where userreference = '".$dealerid."' and usertype = 'dealer';";
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
		$query = "select slno,contactperson,emailid,cell,smsfromname,smsusername,smspassword,smsaccountdisabled,croptext from inv_smsactivation where userreference = '".$dealerid."' and usertype = 'dealer' LIMIT ".$startlimit.",".$limit."; ";
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		//$grid = '<tr><td><table width="100%" cellpadding="3" cellspacing="0">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Contact Person</td><td nowrap = "nowrap" class="td-border-grid" align="left">Email ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Contact Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Account Active</td><td nowrap = "nowrap" class="td-border-grid" align="left">From Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">User Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Account Disabled</td><td nowrap = "nowrap" class="td-border-grid" align="left">Crop Text</td></tr>';
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
			$grid .= '<tr bgcolor='.$color.' class="gridrow" onclick ="gridtoform(\''.$fetch['slno'].'\');" align="left">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['contactperson'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['emailid'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['cell'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['smsfromname'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['smsusername'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['smsaccountdisabled'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['croptext'])."</td>";
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoregenerateaccountgrid(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >></a><a onclick ="getmoregenerateaccountgrid(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
			echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid;	
	}
	break;
	case 'searchbydealerid':
	{
		$dealerid = $_POST['dealerid'];
		$query = "SELECT * from inv_mas_dealer where slno = '".$dealerid."'";
		$result = runmysqlquery($query);
		if(mysqli_num_rows($result) > 0)
		{
			$fetchresult = mysqli_fetch_array($result);
			$businessname = $fetchresult['businessname'];
			echo('1^'.$businessname.'^'.$dealerid);
		}
		else
		{
			echo('2^Dealer Not Available'.$query);
		}

	}
	break;
}
?>