<?php
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
$type = $_POST['type'];
switch($type)
{	
	case 'save':
	{   
		$deployerid = $_POST['deployerid'];
		$businessname = $_POST['businessname'];
		$implementerusername = $_POST['implementerusername'];
		$contactperson = $_POST['contactperson'];
		$address = $_POST['address'];
		$place = $_POST['place'];
		$district = $_POST['district'];
		//$state = $_POST['state'];
		$pincode = $_POST['pincode'];
		$stdcode = $_POST['stdcode'];
		//Added Space after comma is not avaliable in phone and cell fields
		$phone = $_POST['phone'];
		$phonespace = str_replace(", ", ",",$phone);
		$phonevalue = str_replace(',',', ',$phonespace);
		
		$cell = $_POST['cell'];
		$cellspace = str_replace(", ", ",",$cell);
		$cellvalue = str_replace(',',', ',$cellspace);
		
		$region = $_POST['region'];
		$emailid = $_POST['emailid'];
		$website = $_POST['website'];
		$designtype = $_POST['designtype'];
		$coordinator = $_POST['coordinator'];
		$disablelogin = $_POST['disablelogin'];
		$remarks = $_POST['remarks'];
		$tlemailid = $_POST['tlemailid'];
		$mgremailid = $_POST['mgremailid'];
		$hoemailid = $_POST['hoemailid'];
		$branchid = $_POST['branchid'];
		$implementernotinuse = $_POST['implementernotinuse'];
		$personalemailid = $_POST['personalemailid'];
		$createddate = datetimelocal('Y-m-d').' '.datetimelocal('H:i:s');
		$handhold = $_POST['handhold'];
		if($lastslno == "")
		{	$password = generatepwd();
			$query = "SELECT ifnull(MAX(slno),0)+1 AS newdeployerid FROM inv_mas_implementer";
			$fetch = runmysqlqueryfetch($query);
			$deployerid = $fetch['newdeployerid'];
			
			$fetchcountusername = runmysqlqueryfetch("SELECT COUNT(*) AS count FROM inv_mas_implementer WHERE implementerusername = '".$implementerusername."'");
			
			if($fetchcountusername['count'] > 0)
			{
				echo(json_encode('3^Enter the different Username as it exists already.'));
			}
			else
			{
				$query1 = "INSERT INTO inv_mas_implementer (slno,businessname, contactperson,implementerusername,address, place, district, pincode, stdcode, phone, cell, region, emailid, website, coordinator, disablelogin, remarks, initialpassword,loginpassword, passwordchanged, implementertype,tlemailid, mgremailid, hoemailid, createddate,  userid, personalemailid, implementernotinuse, lastmodifieddate, lastmodifiedby, createdip, lastmodifiedip,branchid,handhold) VALUES('".$deployerid."','".trim($businessname)."','".$contactperson."','".$implementerusername."','".$address."','".$place."','".$district."','".$pincode."','".$stdcode."','".$phonevalue."','".$cellvalue."','".$region."','".$emailid."','".$website."','".$coordinator."','".$disablelogin."','".$remarks."','".$password."',AES_ENCRYPT('".$password."','imaxpasswordkey'),'N','".$designtype."','".$tlemailid."','".$mgremailid."','".$hoemailid."','".$createddate."','".$userid."','".$personalemailid."','".$implementernotinuse."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".$_SERVER['REMOTE_ADDR']."','".$branchid."','".$handhold."');";
				$result = runmysqlquery($query1);
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','20','".date('Y-m-d').' '.date('H:i:s')."','".$deployerid."')";
				$eventresult = runmysqlquery($eventquery);
				$resvalue = sendwelcomeemailtoimp($deployerid);
	
				echo(json_encode("1^"."Record Saved Successfully".'^'.$resvalue));
			}
					

		}
		else
		{
			$fetchcountusername = runmysqlqueryfetch("SELECT COUNT(*) AS count FROM inv_mas_implementer WHERE implementerusername = '".$implementerusername."' AND slno <> '".$lastslno."'");
			if($fetchcountusername['count'] > 0)
			{
				echo(json_encode('3^Enter the different Username as it exists already.'));
			}
			else
			{
					$query2 = "UPDATE inv_mas_implementer SET businessname = '".trim($businessname)."',contactperson = '".$contactperson."',implementerusername = '".$implementerusername."',address = '".$address."',place = '".$place."',district = '".$district."',pincode = '".$pincode."',stdcode = '".$stdcode."',phone = '".$phonevalue."',cell = '".$cellvalue."',region = '".$region."',emailid = '".$emailid."',website = '".$website."',coordinator = '".$coordinator."',disablelogin = '".$disablelogin."',remarks = '".$remarks."',tlemailid = '".$tlemailid."',mgremailid = '".$mgremailid."',hoemailid = '".$hoemailid."',implementernotinuse = '".$implementernotinuse."',personalemailid = '".$personalemailid."',implementertype = '".$designtype."',lastmodifieddate ='".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedby ='".$userid."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."',branchid = '".$branchid."',handhold='".$handhold."' WHERE slno = '".$lastslno."';";
				$result = runmysqlquery($query2);
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','21','".date('Y-m-d').' '.date('H:i:s')."','".$lastslno."')";
				$eventresult = runmysqlquery($eventquery);
				$query = "SELECT slno FROM inv_mas_implementer WHERE slno = '".$lastslno."';";
				$fetch = runmysqlqueryfetch($query);
				$deployerid = $fetch['slno'];
				echo(json_encode("1^"."Record Saved Successfully"));

			}
		}
	}
	break;
	
	case 'delete':
	{
		/*$recordflag1 = deleterecordcheck($lastslno,'dealerid','inv_bill');
		$recordflag2 = deleterecordcheck($lastslno,'dealerid','inv_credits');
		$recordflag3 = deleterecordcheck($lastslno,'dealerid','inv_customerproduct');
		$recordflag4 = deleterecordcheck($lastslno,'dealerid','inv_dealercard');
		$recordflag5 = deleterecordcheck($lastslno,'firstdealer','inv_mas_customer');
		$recordflag6 = deleterecordcheck($lastslno,'currentdealer','inv_mas_customer');
		if($recordflag1 == true && $recordflag2 == true && $recordflag3 == true && $recordflag4 == true && $recordflag5 == true && $recordflag6 == true)
		{*/
			$result = runmysqlqueryfetch("SELECT slno FROM inv_mas_implementer WHERE  slno = '".$lastslno."'");
			$fetchdeaID = $result['slno'];
			$query = "DELETE FROM inv_mas_implementer WHERE slno = '".$lastslno."'";
			$result = runmysqlquery($query);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','22','".date('Y-m-d').' '.date('H:i:s')."')";
			$eventresult = runmysqlquery($eventquery);
			echo(json_encode("2^"."Dealer Record '".$fetchdeaID."' Deleted Successfully"));
		/*}
		else
		{
			echo("3^"."Dealer Record cannot be deleted as the record referred.");
		}*/
		

	}
	break;
	
	case 'generatedeployerlist':
	{
		$generatedeployerlistarray = array();
		$coordinator_type = $_POST['coordinator_type'];
		$login_type = $_POST['login_type'];
		$deployerregion = $_POST['deployerregion'];
		$coordinator_typepiece = ($coordinator_type == "")?(""):(" AND inv_mas_implementer.coordinator = '".$coordinator_type."' ");
		$login_typepiece = ($login_type == "")?(""):(" AND inv_mas_implementer.disablelogin = '".$login_type."' ");
		$deployerregion = ($deployerregion == "")?(""):(" AND inv_mas_implementer.region = '".$deployerregion."' ");

		//$grid = '<select name="dealerlist" size="5" class="swiftselect" id="dealerlist" style="width:210px; height:400px;" onclick ="selectfromlist(); showunregdcards(); billnumberFunction();" onchange="selectfromlist(); billnumberFunction(); "  >';*/
		$query = "SELECT slno,businessname FROM inv_mas_implementer  where  slno <> '532568855' ".$coordinator_typepiece.$login_typepiece.$deployerregion." ORDER BY businessname";
		$result = runmysqlquery($query);
		
		$generatedeployerlistarray = array();
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$generatedeployerlistarray[$count] = $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
		echo(json_encode($generatedeployerlistarray));
		//echo($query);
	}
	break;
	
	case 'deployerdetailstoform':
	{
		$deployerdetailstoformarray = array();
		$lastslno = $_POST['lastslno'];
		$query1 = "SELECT count(*) as count from inv_mas_implementer where slno = '".$lastslno."'";
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)
		{
			$query = "SELECT inv_mas_implementer.slno,inv_mas_implementer.businessname, inv_mas_implementer.contactperson, 
			inv_mas_implementer.implementerusername, inv_mas_implementer.address, inv_mas_implementer.place, inv_mas_implementer.district, 
			inv_mas_district.statecode as state, inv_mas_implementer.pincode, inv_mas_implementer.stdcode,inv_mas_implementer.phone, 
			inv_mas_implementer.cell, inv_mas_implementer.region as region, inv_mas_implementer.emailid, inv_mas_implementer.website, 
			inv_mas_implementer.coordinator, inv_mas_implementer.disablelogin, inv_mas_implementer.remarks, 
			inv_mas_implementer.passwordchanged,inv_mas_implementer.implementertype,
			inv_mas_implementer.tlemailid,inv_mas_implementer.mgremailid  ,inv_mas_implementer.hoemailid  ,inv_mas_implementer.personalemailid ,
			inv_mas_implementer.implementernotinuse,inv_mas_implementer.createddate ,
			inv_mas_users.fullname,inv_mas_implementer.initialpassword as initialpassword ,inv_mas_implementer.branchid as branchid,handhold FROM inv_mas_implementer left join inv_mas_users ON inv_mas_implementer.userid = inv_mas_users.slno 
			left join inv_mas_district on inv_mas_district.districtcode = inv_mas_implementer.district 
			where inv_mas_implementer.slno = '".$lastslno."';";
			$fetch = runmysqlqueryfetch($query);
			
			$deployerdetailstoformarray['slno'] = $fetch['slno'];
			$deployerdetailstoformarray['businessname'] = $fetch['businessname'];
			$deployerdetailstoformarray['contactperson'] = $fetch['contactperson'];
			$deployerdetailstoformarray['address'] = $fetch['address'];
			$deployerdetailstoformarray['place'] = $fetch['place'];
			$deployerdetailstoformarray['district'] = $fetch['district'];
			$deployerdetailstoformarray['state'] = $fetch['state'];
			$deployerdetailstoformarray['pincode'] = $fetch['pincode'];
			$deployerdetailstoformarray['stdcode'] = $fetch['stdcode'];
			$deployerdetailstoformarray['phone'] = $fetch['phone'];
			$deployerdetailstoformarray['cell'] = $fetch['cell'];
			$deployerdetailstoformarray['region'] = $fetch['region'];
			$deployerdetailstoformarray['emailid'] = $fetch['emailid'];
			$deployerdetailstoformarray['website'] = $fetch['website'];
			$deployerdetailstoformarray['coordinator'] = $fetch['coordinator'];
			$deployerdetailstoformarray['disablelogin'] = $fetch['disablelogin'];
			$deployerdetailstoformarray['remarks'] = $fetch['remarks'];
			$deployerdetailstoformarray['initialpassword'] = $fetch['initialpassword'];
			$deployerdetailstoformarray['passwordchanged'] = strtolower($fetch['passwordchanged']);
			$deployerdetailstoformarray['tlemailid'] = $fetch['tlemailid'];
			$deployerdetailstoformarray['mgremailid'] = $fetch['mgremailid'];
			$deployerdetailstoformarray['hoemailid'] = $fetch['hoemailid'];
			$deployerdetailstoformarray['createddate'] = changedateformatwithtime($fetch['createddate']);
			$deployerdetailstoformarray['fullname'] = $fetch['fullname'];
			$deployerdetailstoformarray['userid'] = $userid;
			$deployerdetailstoformarray['implementerusername'] = $fetch['implementerusername'];
			$deployerdetailstoformarray['personalemailid'] = $fetch['personalemailid'];
			$deployerdetailstoformarray['implementernotinuse'] = $fetch['implementernotinuse'];
			$deployerdetailstoformarray['implementertype'] = $fetch['implementertype'];
			$deployerdetailstoformarray['branchid'] = $fetch['branchid'];
			$deployerdetailstoformarray['resetpwd'] ='yes' ;
			$deployerdetailstoformarray['handhold'] =$fetch['handhold'] ;
			
			//echo($fetch['slno'].'^'.$fetch['businessname'].'^'.$fetch['contactperson'].'^'.$fetch['address'].'^'.$fetch['place'].'^'.$fetch['district'].'^'.$fetch['state'].'^'.$fetch['pincode'].'^'.$fetch['stdcode'].'^'.$fetch['phone'].'^'.$fetch['cell'].'^'.$fetch['region'].'^'.$fetch['emailid'].'^'.$fetch['website'].'^'.$fetch['coordinator'].'^'.$fetch['disablelogin'].'^'.$fetch['remarks'].'^'.$fetch['initialpassword'].'^'.strtolower($fetch['passwordchanged']).'^'.$fetch['tlemailid'].'^'.$fetch['mgremailid'].'^'.$fetch['hoemailid'].'^'.changedateformatwithtime($fetch['createddate']).'^'.$fetch['fullname'].'^'.$userid.'^'.$fetch['implementerusername'].'^'.$fetch['personalemailid'].'^'.$fetch['implementernotinuse'].'^'.$fetch['implementertype'].'^'.'yes'.'^'.$fetch['branchid']);
		}
		else
		{
			$deployerdetailstoformarray['slno'] ='';
			$deployerdetailstoformarray['businessname'] = '';
			$deployerdetailstoformarray['contactperson'] = '';
			$deployerdetailstoformarray['address'] = '';
			$deployerdetailstoformarray['place'] ='';
			$deployerdetailstoformarray['district'] = '';
			$deployerdetailstoformarray['state'] = '';
			$deployerdetailstoformarray['pincode'] = '';
			$deployerdetailstoformarray['stdcode'] = '';
			$deployerdetailstoformarray['phone'] = '';
			$deployerdetailstoformarray['cell'] = '';
			$deployerdetailstoformarray['region'] = '';
			$deployerdetailstoformarray['emailid'] = '';
			$deployerdetailstoformarray['website'] = '';
			$deployerdetailstoformarray['coordinator'] = '';
			$deployerdetailstoformarray['disablelogin'] = '';
			$deployerdetailstoformarray['remarks'] = '';
			$deployerdetailstoformarray['initialpassword'] = '';
			$deployerdetailstoformarray['passwordchanged'] = '';
			$deployerdetailstoformarray['tlemailid'] = '';
			$deployerdetailstoformarray['mgremailid'] = '';
			$deployerdetailstoformarray['hoemailid'] = '';
			$deployerdetailstoformarray['createddate'] = '';
			$deployerdetailstoformarray['fullname'] = '';
			$deployerdetailstoformarray['userid'] = '';
			$deployerdetailstoformarray['implementerusername'] = '';
			$deployerdetailstoformarray['personalemailid'] = '';
			$deployerdetailstoformarray['implementernotinuse'] ='';
			$deployerdetailstoformarray['implementertype'] = '';
			$deployerdetailstoformarray['branchid'] = '';
			$deployerdetailstoformarray['resetpwd'] ='' ;
			$deployerdetailstoformarray['handhold'] = '';
		}
		echo(json_encode($deployerdetailstoformarray));
	}
	break;
	case 'resetpwd':
	{
		$password = $_POST['password'];
		$lastslno = $_POST['deployerid'];
		$query = "UPDATE inv_mas_implementer SET loginpassword = AES_ENCRYPT('".$password."','imaxpasswordkey'),initialpassword = '".$password."',passwordchanged ='N' WHERE inv_mas_implementer.slno = '".$lastslno."'";
		$result = runmysqlquery($query);
		$query = "select  initialpassword as initialpassword from inv_mas_implementer  WHERE slno = '".$lastslno."'";
		$result = runmysqlqueryfetch($query);
		echo(json_encode('1^'.$result['initialpassword']));
	}
	break;
	
	
	case 'deployersearch':
	{
		$textfield = $_POST['textfield'];
		$subselection = $_POST['subselection'];
		$orderby = $_POST['orderby'];
		$region = $_POST['region'];
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$regionpiece = ($region == '')?(""):(" AND inv_mas_implementer.region = '".$region."' ");
		if($showtype == 'all')
			$limit = 100000;
		else
			$limit = 10;
		if($startlimit == '')
		{
			$startlimit = 0;$slnocount = 0;
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
				case "deployerid":
					$orderbyfield = "slno";
					break;
				case "emailid":
					$orderbyfield = "emailid";
					break;
				case "contactperson":
					$orderbyfield = "contactperson";
					break;
				case "phone":
					$orderbyfield = "phone";
					break;
				default:
					$orderbyfield = "businessname";
					break;
			}
			
			$querycase = "SELECT inv_mas_implementer.slno,inv_mas_implementer.businessname, 
			inv_mas_implementer.contactperson, inv_mas_implementer.address, inv_mas_implementer.place, 
			inv_mas_district.districtname as district, inv_mas_state.statename as state, inv_mas_implementer.pincode, 
			inv_mas_implementer.stdcode,inv_mas_implementer.phone, inv_mas_implementer.cell, inv_mas_region.category as region, 
			inv_mas_implementer.emailid, inv_mas_implementer.website, inv_mas_implementer.coordinator, inv_mas_implementer.disablelogin, 
			inv_mas_implementer.remarks, inv_mas_implementer.passwordchanged, inv_mas_implementer.tlemailid , 
			inv_mas_implementer.mgremailid , inv_mas_implementer.hoemailid , inv_mas_implementer.createddate , 
			inv_mas_users.fullname,inv_mas_implementer.personalemailid ,inv_mas_implementer.implementernotinuse,handhold FROM inv_mas_implementer 
			left join inv_mas_users ON inv_mas_users.slno = inv_mas_implementer.userid 
			left join inv_mas_district on inv_mas_district.districtcode = inv_mas_implementer.district 
			left join inv_mas_state on inv_mas_state.slno = inv_mas_district.statecode
			left join inv_mas_region on inv_mas_region.slno = inv_mas_implementer.region";

			switch($subselection)
			{
				case "deployerid":
					$query = $querycase." WHERE inv_mas_implementer.slno  LIKE '%".$textfield."%' ".$regionpiece." ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
					case "contactperson":
					$query = $querycase." WHERE inv_mas_implementer.contactperson  LIKE '%".$textfield."%' ".$regionpiece." ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
				case "phone":
					$query = $querycase." WHERE (inv_mas_implementer.phone LIKE '%".$textfield."%' OR  inv_mas_implementer.cell LIKE '%".$textfield."%') ".$regionpiece." ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
				
				case "emailid":
					$query = $querycase." WHERE inv_mas_implementer.emailid LIKE '%".$textfield."%' ".$regionpiece."  ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
				case "coordinator":
					$query = $querycase." WHERE inv_mas_implementer.coordinator  LIKE '%".$textfield."%' ".$regionpiece." ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
				
				default:
					$query = $querycase." WHERE inv_mas_implementer.businessname LIKE '%".$textfield."%' ".$regionpiece." ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
			}
			if($startlimit == 0)
			{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" >';
			
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid"  align ="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Business Name</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Contact Person</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Address</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Place</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">District</td><td nowrap = "nowrap" class="td-border-grid" align ="left">State</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">PIN Code</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">STD Code</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Phone</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Cell</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Region</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Email ID</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Website</td>
			<td nowrap = "nowrap" class="td-border-grid"  align ="left">Co-odinator</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Disable Login</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Deployer Not In Use</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Remarks</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Password Changed</td>
			<td nowrap = "nowrap" class="td-border-grid" align ="left">Type</td>
			<td nowrap = "nowrap" class="td-border-grid"  align ="left">Personal Email ID</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">TL Email ID</td><td nowrap = "nowrap" class="td-border-grid">Manager Email ID</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">HO Email ID</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Created Date</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Created By</td></tr>';
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
				$grid .= '<tr class="gridrow" onclick ="deployerdetailstoform(\''.$fetch['slno'].'\');"  bgcolor='.$color.'>';
				$grid .= "<td nowrap='nowrap' class='td-border-grid'  align ='left'>".$slnocount."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['slno']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".$fetch['businessname']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".$fetch['contactperson']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['address'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['place'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['district'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['state'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['pincode'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".$fetch['stdcode']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['phone'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['cell'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['region'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['emailid'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['website'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['coordinator'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['disablelogin'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['implementernotinuse'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['remarks'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['passwordchanged'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['implementertype'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['personalemailid'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['tlemailid'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['mgremailid'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['hoemailid'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".changedateformatwithtime($fetch['createddate'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['fullname'])."</td>";
				$grid .= '</tr>';
			}
			$fetchcount = mysqli_num_rows($result);
			if($fetchcount < $limit)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >></a><a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer">(Show All Records)</a></div></td></tr></table>';
			$grid .= "</table>";
			echo '1^'.$grid.'^'.$fetchcount.'^'.$linkgrid;
		}
	}
	break;
	
	
}

// function to send an email on new Dealer creation
function sendwelcomeemailtoimp($deployerid)
{
	$query = "Select inv_mas_implementer.businessname,inv_mas_implementer.contactperson,inv_mas_implementer.place,inv_mas_implementer.phone,inv_mas_implementer.emailid,inv_mas_implementer.cell,inv_mas_implementer.stdcode,inv_mas_implementer.pincode,inv_mas_implementer.address,inv_mas_implementer.implementerusername,inv_mas_implementer.initialpassword as password,inv_mas_district.districtname,inv_mas_state.statename,inv_mas_implementer.implementertype as implementertype,inv_mas_implementer.branchid  from inv_mas_implementer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_implementer.district left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_implementer.slno = '".$deployerid."' ";
	$fetchresult = runmysqlqueryfetch($query);
	$businessname = $fetchresult['businessname'];
	$contactperson = $fetchresult['contactperson'];
	$place = $fetchresult['place'];
	$phone = $fetchresult['phone'];
	$cell = $fetchresult['cell'];
	$stdcode = $fetchresult['stdcode'];
	$pincode = $fetchresult['pincode'];
	$address = $fetchresult['address'];
	$implementerusername = $fetchresult['implementerusername'];
	$password = $fetchresult['password'];
	$districtname = $fetchresult['districtname'];
	$statename = $fetchresult['statename'];
	$branchid = $fetchresult['branchid'];
	$implementertype = $fetchresult['implementertype'];
	if($implementertype == 'implementer' )
		$implementertypename = 'Implementer';
	elseif($implementertype == 'customizer' )
		$implementertypename = 'Customiser';
	elseif($implementertype == 'webmodule' )
		$implementertypename = 'Web Module Implementer';
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
		$emailid = 'meghana.b@relyonsoft.com';
	else
		$emailid = $fetchresult['emailid'];
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);
	
	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
			$emailids[$emailarray[$i]] = $emailarray[$i];
		}
	}

	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/newdeployer.htm");
	$textmsg = file_get_contents("../mailcontents/newdeployer.txt");
	$array = array();
	$date = datetimelocal('d-m-Y');
	if($pincode == '')
	{
		$pincode = 'Not Available';
	}
	if($stdcode == '')
	{
		$stdcode = 'Not Available';
	}
	if($address == '')
	{
		$address = 'Not Available';
	}
	$array[] = "##DATE##%^%".$date;
	$array[] = "##NAME##%^%".$contactperson;
	$array[] = "##COMPANY##%^%".$businessname;
	$array[] = "##PLACE##%^%".$place;
	$array[] = "##DISTRICT##%^%".$districtname;
	$array[] = "##STATE##%^%".$statename;
	$array[] = "##PHONE##%^%".$phone;
	$array[] = "##DEPLOYERID##%^%".$deployerid;
	$array[] = "##USERNAME##%^%".$implementerusername;
	$array[] = "##PASSWORD##%^%".$password;
	$array[] = "##PINCODE##%^%".$pincode;
	$array[] = "##STDCODE##%^%".$stdcode;
	$array[] = "##CELL##%^%".$cell;
	$array[] = "##ADDRESS##%^%".$address;
	$array[] = "##EMAILID##%^%".$emailid;
	$array[] = "##TYPE##%^%".$implementertypename;
	
	$filearray = array(
		array('../images/relyon-logo.jpg','inline','8888888888'),
		array('../images/channel-partner.gif','inline','4444444444'),
		array('../images/contact-info.gif','inline','33333333333'),
		array('../images/dealer.gif','inline','22222222222')
	);
	$toarray = $emailids;
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
	{
		$bccemailids['archanaab'] ='archana.ab@relyonsoft.com';
	}
	else
	{
		$bccemailids['relyonimax'] ='relyonimax@gmail.com';
		$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
	}

	$bccarray = $bccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = 'New login created at Saral iMax for Implementation (Beta)';
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray);
	//Insert the mail forwarded details to the logs table
	$bccmailid = 'bigmail@relyonsoft.com'; 
	inserttologs(imaxgetcookie('userid'),$dealerid,$fromname,$fromemail,$emailid,null,$bccmailid,$subject);
	return 'success';
}


?>