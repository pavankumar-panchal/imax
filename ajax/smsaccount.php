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

$lastslno = $_POST['lastslno'];
$switchtype = $_POST['switchtype'];
$smslastslno = $_POST['smslastslno'];
switch($switchtype)
{
	case 'save':
	{
		$customerreference = $_POST['customerreference'];
		$smsactivateddate = date('Y-m-d').' '.date('H:i:s');
		$smsusername = $_POST['username'];
		$smsfromname = $_POST['fromname'];
		$smspassword = $_POST['password'];
		$quantity = $_POST['quantity'];
		$disablesmsaccount = $_POST['disablesmsaccount'];
		$contactperson = $_POST['contactperson'];
		$emailid = $_POST['emailid'];
		$cell = $_POST['cell'];
		$croptext = $_POST['croptext'];
		$accounttype = $_POST['accounttype'];
		
		if($smslastslno == '')
		{
			$query1 = "select * from inv_smsactivation where smsusername = '".$smsusername."' ";
			$result1 = runmysqlquery($query1);
			if(mysqli_fetch_row($result1) > 0)
			{
				echo(json_encode("2^"."User Name Already exists. Please use a different Username"));
			}
			else
			{
				$querycount = "select max(slno) + 1 as slno from inv_smsactivation;";
				$resultcount = runmysqlqueryfetch($querycount);
				$slnotobeinserted =$resultcount['slno'];
				$query = "INSERT INTO inv_smsactivation(slno,userreference,usertype,contactperson,emailid,cell,activatesmsaccount ,smsfromname,smsusername,smspassword,smsaccountdisabled,createdby,createddate,createdip,lastmodifiedby,lastmodifieddate,lastmodifiedip,croptext,accounttype) values('".$slnotobeinserted."','".$customerreference."','customer','".$contactperson."','".$emailid."','".$cell."','yes','".$smsfromname."','".$smsusername."','".$smspassword."','".$disablesmsaccount."','".$userid."','".date('Y-m-d').' '.date('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','".$userid."','".date('Y-m-d').' '.date('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','".$croptext."','".$accounttype."');";
				$result = runmysqlquery($query);
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','63','".date('Y-m-d').' '.date('H:i:s')."')";
				$eventresult = runmysqlquery($eventquery);
				smsactivationmail($slnotobeinserted,$userid);
				echo(json_encode("1^"."SMS Account activated Successfully"));
			}
		}
		else
		{
			$query = "select * from inv_smsactivation where smsusername = '".$smsusername."' and slno <> '".$smslastslno."'";
			$result = runmysqlquery($query);
			if(mysqli_fetch_row($result) > 0)
			{
				echo(json_encode("2^"."User Name Already exists. Please use a different Username"));
			}
			else
			{
				$query = "UPDATE inv_smsactivation SET contactperson = '".$contactperson."',emailid = '".$emailid."',cell = '".$cell."',activatesmsaccount = 'yes',smsfromname = '".$smsfromname."',smsusername = '".$smsusername."',smspassword = '".$smspassword."',smsaccountdisabled = '".$disablesmsaccount."',lastmodifiedby = '".$userid."',lastmodifieddate = '".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."',croptext = '".$croptext."',accounttype = '".$accounttype."' where slno = '".$smslastslno."' and usertype = 'customer'";
				$result = runmysqlquery($query);
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','63','".date('Y-m-d').' '.date('H:i:s')."')";
				$eventresult = runmysqlquery($eventquery);
				echo(json_encode("1^"."SMS Account Updated Successfully"));
			}
		}
	}
	break;
	case 'disable':
	{
		$customerreference = $_POST['customerreference'];
		$disablesmsaccount = $_POST['disablesmsaccount'];
		$smslastslno = $_POST['smslastslno'];
		$query = "UPDATE inv_smsactivation set smsaccountdisabled = '".$disablesmsaccount."' where slno = '".$smslastslno."' and usertype = 'customer';";
		$result = runmysqlquery($query);
		echo(json_encode("1^"."SMS Account Disabled Successfully"));
	}
	break;
	case 'generatecustomerlist':
	{
		$limit = $_POST['limit'];
		$startindex = $_POST['startindex'];
		$customerarray = array();
		$query = "SELECT slno,businessname,customerid FROM inv_mas_customer ORDER BY businessname  LIMIT ".$startindex.",".$limit.";";
		$result = runmysqlquery($query);
		$grid = '';
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$customerarray[$count] = $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
		//echo($grid);
		echo(json_encode($customerarray));
	}
	break;
	case 'getcustomercount':
	{
		$responsearray3 = array();
		$query = "SELECT count(*) as count FROM inv_mas_customer ORDER BY businessname";
		$resultfetch = runmysqlqueryfetch($query);
		$count = $resultfetch['count'];
		$responsearray3['count'] = $count;
		echo(json_encode($responsearray3));
	}
	break;
	case 'gridtoform':
	{
		$gridtoformarray = array();
		$smslastslno = $_POST['smslastslno'];
		$query = "select slno,contactperson,emailid,cell,smsfromname,smsusername,smspassword,smsaccountdisabled,croptext,accounttype from inv_smsactivation where slno = '".$smslastslno."' and usertype = 'customer';";
		$result = runmysqlquery($query);
		if(mysqli_fetch_row($result) > 0)
		{
			$fetch = runmysqlqueryfetch($query);
			$gridtoformarray['errorcode'] = '1';
			$gridtoformarray['slno'] = $fetch['slno'];
			$gridtoformarray['contactperson'] = $fetch['contactperson'];
			$gridtoformarray['emailid'] = $fetch['emailid'];
			$gridtoformarray['cell'] = $fetch['cell'];
			$gridtoformarray['smsfromname'] = $fetch['smsfromname'];
			$gridtoformarray['smsusername'] = $fetch['smsusername'];
			$gridtoformarray['smspassword'] = $fetch['smspassword'];
			$gridtoformarray['smsaccountdisabled'] = $fetch['smsaccountdisabled'];
			$gridtoformarray['croptext'] = $fetch['croptext'];
			$gridtoformarray['accounttype'] = $fetch['accounttype'];
			
			//echo('1^'.$fetch['slno'].'^'.$fetch['contactperson'].'^'.$fetch['emailid'].'^'.$fetch['cell'].'^'.$fetch['smsfromname'].'^'.$fetch['smsusername'].'^'.$fetch['smspassword'].'^'.$fetch['smsaccountdisabled'].'^'.$fetch['croptext'].'^'.$fetch['accounttype']);
		}
		else
		{
			$gridtoformarray['errorcode'] = '2';
			$gridtoformarray['slno'] = '';
			$gridtoformarray['contactperson'] = '';
			$gridtoformarray['emailid'] = '';
			$gridtoformarray['cell'] = '';
			$gridtoformarray['smsfromname'] = '';
			$gridtoformarray['smsusername'] = '';
			$gridtoformarray['smspassword'] = '';
			$gridtoformarray['smsaccountdisabled'] = '';
			$gridtoformarray['croptext'] = '';
			$gridtoformarray['accounttype'] = '';
		}
		echo(json_encode($gridtoformarray));
	}
	break;
	case 'generateaccountgrid':
	{
		$customerreference = $_POST['customerreference'];
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$resultcount = "select slno,contactperson,emailid,cell,smsfromname,smsusername,smspassword,smsaccountdisabled,croptext from inv_smsactivation where userreference = '".$customerreference."' and usertype = 'customer';";
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
		$query = "select slno,contactperson,emailid,cell,smsfromname,smsusername,smspassword,smsaccountdisabled,croptext,accounttype from inv_smsactivation where userreference = '".$customerreference."' and usertype = 'customer' LIMIT ".$startlimit.",".$limit."; ";
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		//$grid = '<tr><td><table width="100%" cellpadding="3" cellspacing="0">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Contact Person</td><td nowrap = "nowrap" class="td-border-grid" align="left">Email ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Contact Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">From Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">User Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Account Disabled</td><td nowrap = "nowrap" class="td-border-grid" align="left">Activation Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Crop Text</td></tr>';
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
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['accounttype'])."</td>";
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
	case 'searchbycustomerid':
	{
		$searchbycustomeridarray = array();
		$customerid = $_POST['customerid'];
		$customeridlen = strlen($customerid);
		$lastcustomerid = substr($customerid, $customeridlen - 5);
		$customerid = ($customeridlen == 5)?($customerid):($lastcustomerid);
		$query = "SELECT * from inv_mas_customer where slno = '".$customerid."'";
		$result = runmysqlquery($query);
		if(mysqli_num_rows($result) > 0)
		{
			$fetchresult = mysqli_fetch_array($result);
			$businessname = $fetchresult['businessname'];
			$searchbycustomeridarray['errorcode'] = '1';
			$searchbycustomeridarray['businessname'] = $businessname;
			$searchbycustomeridarray['customerid'] = $customerid;
			//echo('1^'.$businessname.'^'.$customerid);
		}
		else
		{
			$searchbycustomeridarray['errorcode'] = '2';
			$searchbycustomeridarray['errormessage'] = 'Customer Not Available';
			//echo('2^Customer Not Available');
		}
		echo(json_encode($searchbycustomeridarray));


	}
	break;
	case 'getcustomerid':
	{
		$lastslno = $_POST['lastslno'];
		$query = "select * from inv_mas_customer where slno = '".$lastslno."';";
		$resultfetch = runmysqlqueryfetch($query);
		$customerid = $resultfetch['customerid'];
		echo(json_encode('1^'.cusidcombine($customerid)));
	}
	break;
}



?>