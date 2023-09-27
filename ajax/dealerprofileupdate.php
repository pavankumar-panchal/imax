<?php
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');
include('../inc/checksession.php');
include('../inc/checkpermission.php');

if(imaxgetcookie('userid')<> '') 
$userid = imaxgetcookie('userid');
else
{ 
	echo('Thinking to redirect');
	exit;
}

$changetype = $_POST['changetype'];
$lastslno = $_POST['lastslno'];

switch($changetype)
{

	case 'generategrid':
	{
			$startlimit = $_POST['startlimit'];
			$slno = $_POST['slno'];
			$showtype = $_POST['showtype'];
			if($showtype == 'all')
				$limit = 100000;
			else
				$limit = 10;
			$query = "SELECT count(*) as count from inv_dealerreqpending where inv_dealerreqpending.dealerstatus='pending'"; 
			$fetch1 = runmysqlqueryfetch($query);
			if($fetch1['count'] > 0)
			{
					$query1 = "SELECT distinct inv_dealerreqpending.dealerid as dealerid,
inv_dealerreqpending.contactperson as contactperson,inv_dealerreqpending.address as address,
inv_dealerreqpending.place as place,inv_mas_district.districtname as district,inv_mas_state.statename as state,
inv_dealerreqpending.pincode as pincode,inv_dealerreqpending.phone as phone,inv_dealerreqpending.website as website, 
inv_dealerreqpending.cell as cell,inv_dealerreqpending.emailid as emailid,inv_mas_region.category as region,
inv_dealerreqpending.stdcode as stdcode,inv_dealerreqpending.createddate as createddate,
inv_dealerreqpending.businessname as businessname,inv_dealerreqpending.personalemailid as personalemailid FROM inv_dealerreqpending 
LEFT JOIN inv_mas_district ON inv_mas_district.districtcode = inv_dealerreqpending.district 
LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode 
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_dealerreqpending.region 
where inv_dealerreqpending.dealerstatus = 'pending'  order by createddate";
					$fetch6 = runmysqlquery($query1);
					$fetchresultcount = mysqli_num_rows($fetch6);
					if($startlimit == '')
					{
						$startlimit = 0;
						$slno = 0;
					}
					else
					{
						$startlimit = $slno;
						$slno = $slno;
					}
				$query2 = "SELECT distinct inv_dealerreqpending.dealerid as dealerid,
inv_dealerreqpending.contactperson as contactperson,inv_dealerreqpending.address as address,
inv_dealerreqpending.place as place,inv_mas_district.districtname as district,inv_mas_state.statename as state,
inv_dealerreqpending.pincode as pincode,inv_dealerreqpending.phone as phone,inv_dealerreqpending.website as website, 
inv_dealerreqpending.cell as cell,inv_dealerreqpending.emailid as emailid,inv_mas_region.category as region,
inv_dealerreqpending.stdcode as stdcode,inv_dealerreqpending.createddate as createddate,
inv_dealerreqpending.businessname as businessname,inv_dealerreqpending.personalemailid as personalemailid FROM inv_dealerreqpending 
LEFT JOIN inv_mas_district ON inv_mas_district.districtcode = inv_dealerreqpending.district 
LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode 
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_dealerreqpending.region 
where inv_dealerreqpending.dealerstatus = 'pending'  order by createddate  LIMIT ".$startlimit.",".$limit.";";
					if($startlimit == 0)
					{
						$grid .= '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" style="text-align:left">';  
						$grid .= '<tr nowrap = "nowrap" class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid">Sl no</td><td nowrap = "nowrap" class="td-border-grid">Dealerid</td><td nowrap = "nowrap" class="td-border-grid">Business Name</td><td nowrap = "nowrap" class="td-border-grid">Contact Person</td><td nowrap = "nowrap" class="td-border-grid">Address</td><td nowrap = "nowrap" class="td-border-grid">State</td><td nowrap = "nowrap" class="td-border-grid">District</td><td nowrap = "nowrap" class="td-border-grid">Pincode</td><td nowrap = "nowrap" class="td-border-grid">Region</td><td nowrap = "nowrap" class="td-border-grid">STD Code</td><td nowrap = "nowrap" class="td-border-grid">Phone</td><td nowrap = "nowrap" class="td-border-grid">Cell</td><td nowrap = "nowrap" class="td-border-grid">Email ID</td><td nowrap = "nowrap" class="td-border-grid">Website</td><td nowrap = "nowrap" class="td-border-grid">Region</td><td nowrap = "nowrap" class="td-border-grid">Request date/time</td><td nowrap = "nowrap" class="td-border-grid">Personal Email</td></tr>';
					}
						$i_n = 0;
						$result = runmysqlquery($query2);
						while($fetch = mysqli_fetch_array($result))
						{
							$slno++;
							$i_n++;
							$color;
							if($i_n%2 == 0)
								$color = "#edf4ff";
							else
								$color = "#f7faff";
							
							$grid .= '<tr nowrap = "nowrap" class="gridrow" onclick ="dealerdetailstoform(\''.$fetch['dealerid'].'\');"  bgcolor='.$color.'>';
							
							$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$slno."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['dealerid']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['businessname']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['contactperson']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['address']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['state']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['district']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['pincode']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['region']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['stdcode']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['phone']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['cell']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['emailid']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['website']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['region']."</td><td nowrap='nowrap' class='td-border-grid'>".changedateformatwithtime($fetch['createddate'])."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['personalemailid']."</td>";
						
							$grid .= '</tr>';
						}
					$grid .= "</table>";
					$fetchcount = mysqli_num_rows($result);
					if($slno >= $fetchresultcount)
						$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#EBEBEB"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
					else
						$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="getmoredata(\''.$startlimit.'\',\''.$slno.'\',\'more\');">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmoredata(\''.$startlimit.'\',\''.$slno.'\',\'all\');" class="resendtext1"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
				
				echo('1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid);
		}
	else
		{
			echo("2^ No datas found to be displayed.");
		}	
	}
	
	break;
	case 'dealerdetailstoform':
	{
		
		$query1 = "SELECT count(*) as count from inv_dealerreqpending where inv_dealerreqpending.dealerid = '".$lastslno."' and dealerstatus ='pending'";
		$fetch1 = runmysqlqueryfetch($query1);
		
		if($fetch1['count'] > 0) 		
		{
			$query = "select tempdealer.slno as tempslno, tempdealer.dealerid as tempdealerid,tempdealer.businessname as tempbusinessname,tempdealer.contactperson as tempcontactperson,tempdealer.address as tempaddress,tempdealer.place as tempplace,tempdealer.dealerstatus as tempdealerstatus,
tempdealer.district as tempdistrict,tempdealer.districtname as tempdistrictname,tempdealer.statename as tempstatename,
tempdealer.state as tempstate,tempdealer.pincode as temppincode,tempdealer.phone as tempphone,
tempdealer.cell as tempcell,tempdealer.emailid as tempemailid,tempdealer.website as tempwebsite,tempdealer.stdcode as tempstdcode,
tempdealer.region as tempregion ,tempdealer.regionname as tempregionname,tempdealer.createddate as tempcreateddate,tempdealer.personalemailid as temppersonalemailid,
newdealer.slno as slno,newdealer.businessname as businessname,
newdealer.contactperson as contactperson,newdealer.address as address,newdealer.place as place,
newdealer.district as district,newdealer.districtname as districtname,newdealer.statename as statename,
newdealer.pincode as pincode,newdealer.region as region,newdealer.regionname as regionname,newdealer.phone as phone,
newdealer.cell as cell,newdealer.emailid as emailid,newdealer.website as website,newdealer.stdcode as stdcode,
newdealer.personalemailid as personalemailid
from (SELECT distinct inv_dealerreqpending.slno as slno, inv_dealerreqpending.dealerid as dealerid,inv_dealerreqpending.businessname as businessname,inv_dealerreqpending.contactperson as contactperson,inv_dealerreqpending.address as address,
inv_dealerreqpending.place as place,inv_dealerreqpending.district as district,inv_mas_state.statecode as state,
inv_dealerreqpending.pincode as pincode,inv_mas_region.category as regionname,
inv_dealerreqpending.region as region,inv_dealerreqpending.phone as phone,inv_dealerreqpending.cell as cell,
inv_dealerreqpending.emailid as emailid,inv_dealerreqpending.website as website,inv_dealerreqpending.stdcode as stdcode,inv_dealerreqpending.personalemailid as personalemailid,
inv_dealerreqpending.createddate as createddate,inv_dealerreqpending.dealerstatus as dealerstatus,
inv_mas_district.districtname,inv_mas_state.statename,inv_mas_region.category FROM inv_dealerreqpending 
LEFT JOIN inv_mas_district ON inv_mas_district.districtcode = inv_dealerreqpending.district 
LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode 
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_dealerreqpending.region where inv_dealerreqpending.dealerid = '".$lastslno."'
and dealerstatus ='pending') as tempdealer join
(SELECT distinct inv_mas_dealer.slno as slno,inv_mas_dealer.businessname as businessname,inv_mas_dealer.personalemailid as personalemailid,inv_mas_dealer.contactperson as contactperson,inv_mas_dealer.address as address,inv_mas_dealer.place as place,
inv_mas_dealer.district as district,inv_mas_dealer.pincode as pincode,
inv_mas_dealer.region as region,inv_mas_dealer.phone as phone,inv_mas_region.category as regionname ,
inv_mas_dealer.cell as cell,inv_mas_dealer.emailid as emailid,inv_mas_dealer.website as website,
inv_mas_dealer.stdcode as stdcode,inv_mas_district.districtname,inv_mas_state.statename as statename FROM inv_mas_dealer 
LEFT JOIN inv_mas_district ON inv_mas_district.districtcode = inv_mas_dealer.district 
LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_dealer.region where inv_mas_dealer.slno ='".$lastslno."') as newdealer
";
			$fetch = runmysqlqueryfetch($query);
			$requestfrom = $fetch['requestfrom'];
			$createddate = changedateformatwithtime($fetch['tempcreateddate']);
				
			echo($fetch['tempdealerid'].'^'.$fetch['tempcontactperson'].'^'.$fetch['tempaddress'].'^'.$fetch['tempplace'].'^'.$fetch['tempdistrict'].'^'.$fetch['tempstate'].'^'.$fetch['temppincode'].'^'.$fetch['tempstdcode'].'^'.$fetch['tempphone'].'^'.$fetch['tempcell'].'^'.$fetch['tempemailid'].'^'.$fetch['tempwebsite'].'^'.$fetch['tempregion'].'^'.$fetch['slno'].'^'.$fetch['contactperson'].'^'.$fetch['address'].'^'.$fetch['place'].'^'.$fetch['district'].'^'.$fetch['state'].'^'.$fetch['pincode'].'^'.$fetch['stdcode'].'^'.$fetch['phone'].'^'.$fetch['cell'].'^'.$fetch['emailid'].'^'.$fetch['website'].'^'.$fetch['regionname'].'^'.$fetch['districtname'].'^'.$fetch['statename'].'^'.$createddate.'^'.$fetch['tempbusinessname'].'^'.$fetch['businessname'].'^'.$fetch['personalemailid'].'^'.$fetch['temppersonalemailid'].'^'.$fetch['tempslno']);
			
		}
	}
	break;
	
	case 'processupdate':
	{
		//Receive the values
		$lastslno = $_POST['lastslno'];
		$dealerbusiness_action = $_POST['dealerbusiness_action'];
		$dealercontact_action = $_POST['dealercontact_action'];
		$dealeraddress_action = $_POST['dealeraddress_action'];
		$dealerplace_action = $_POST['dealerplace_action'];
		$dealerdistrict_action = $_POST['dealerdistrict_action'];
		$dealerpincode_action = $_POST['dealerpincode_action'];
		$dealerstd_action = $_POST['dealerstd_action'];
		$dealerphone_action = $_POST['dealerphone_action'];
		$dealercell_action = $_POST['dealercell_action'];
		$dealeremail_action = $_POST['dealeremail_action'];
		$dealerpersemail_action = $_POST['dealerpersemail_action'];
		$dealerwebsite_action = $_POST['dealerwebsite_action'];
		$dealerregion_action = $_POST['dealerregion_action'];
		$newbusinessname = $_POST['newbusinessname'];
		$newcontactperson = $_POST['newcontactperson'];
		$newaddress = $_POST['newaddress'];
		$newplace = $_POST['newplace'];
		$newdistrict = $_POST['newdistrict'];
		$newpincode = $_POST['newpincode'];
		$newstdcode = $_POST['newstdcode'];
		//Added Space after comma is not avaliable in phone and cell fields
		$phone = $_POST['newphone'];
		$phonespace = str_replace(", ", ",",$phone);
		$phonevalue = str_replace(',',', ',$phonespace);
		
		$cell = $_POST['newcell'];
		$cellspace = str_replace(", ", ",",$cell);
		$cellvalue = str_replace(',',', ',$cellspace);
		$newemailid = $_POST['newemailid'];
		$newpersemailid = $_POST['newpersemailid'];
		$newwebsite = $_POST['newwebsite'];
		$newregion = $_POST['newregion'];
		$lastupdateslno = $_POST['lastupdateslno'];

		//Prepare an array of approvals
		$approvalarray = array();
		if($dealercontact_action == "approve")
			$approvalarray[] = "businessname^".trim($newbusinessname);
		if($dealercontact_action == "approve")
			$approvalarray[] = "contactperson^".$newcontactperson;
		if($dealeraddress_action == "approve")
			$approvalarray[] = "address^".$newaddress;
		if($dealerplace_action == "approve")
			$approvalarray[] = "place^".$newplace;
		if($dealerdistrict_action == "approve")
			$approvalarray[] = "district^".$newdistrict;
		if($dealerpincode_action == "approve")
			$approvalarray[] = "pincode^".$newpincode;
		if($dealerstd_action == "approve")
			$approvalarray[] = "stdcode^".$newstdcode;
		if($dealerphone_action == "approve")
			$approvalarray[] = "phone^".$phonevalue;
		if($dealercell_action == "approve")
			$approvalarray[] = "cell^".$cellvalue;
		if($dealeremail_action == "approve")
			$approvalarray[] = "emailid^".$newemailid;
		if($dealerpersemail_action == "approve")
			$approvalarray[] = "personalemailid ^".$newpersemailid;
		if($dealerwebsite_action == "approve")
			$approvalarray[] = "website^".$newwebsite;
		if($dealerregion_action == "approve")
			$approvalarray[] = "region^".$newregion;
		

		//Frame a proper update query
		$approvalcount = count($approvalarray);
		if($approvalcount > 0)
		{
			$updatepiece = "";
			for($i = 0; $i < $approvalcount; $i++)
			{
				if($i > 0)
					$updatepiece .= ", ";
				$approvalsplit = explode("^", $approvalarray[$i]);
				$updatepiece .= $approvalsplit[0]." = '".$approvalsplit[1]."'";
				
			}
			$updatequery = "update inv_mas_dealer set ".$updatepiece.",lastmodifieddate ='".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedby= '".$userid."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."' where slno = '".$lastslno."'" ;
			$result = runmysqlquery($updatequery);
			
			
		}
		$query = "update inv_dealerreqpending set dealerstatus = 'processed' ,requestby ='".$lastslno."',
		processeddatetime = '".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."',lastmodifiedby = '".$userid."' where dealerid = '".$lastslno."' and dealerstatus = 'pending' and slno = '".$lastupdateslno."'  ";
		$result = runmysqlquery($query);
		$updatedata = $dealerbusiness_action."|^|".$dealercontact_action."|^|".$dealeraddress_action."|^|".$dealerplace_action."|^|".$dealerpincode_action."|^|".$dealerdistrict_action."|^|".$dealerstd_action."|^|".$dealerphone_action."|^|".$dealercell_action."|^|".$dealeremail_action."|^|".$dealerwebsite_action."|^|".$dealerregion_action."|^|".$newbusinessname."|^|".$newcontactperson."|^|".$newaddress."|^|".$newplace."|^|".$newpincode."|^|".$newdistrict."|^|".$newstdcode."|^|".$newphone."|^|".$newcell."|^|".$newemailid."|^|".$newpersemailid."|^|".$newwebsite."|^|".$newregion;
			
			$query2 = "INSERT INTO inv_process_logs(date,time,type,action,updateddata,system) VALUES('".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','Dealer Profile','Processed','".$updatedata."','".$_SERVER['REMOTE_ADDR']."');";
			$result = runmysqlquery($query2);
		
		
	}
	
####################  Mailing Starts ----------------------------------------------------
				$query1 = "SELECT createddate,processeddatetime from inv_dealerreqpending 
				where inv_dealerreqpending.dealerid = '".$lastslno."' and dealerstatus = 'processed' and slno = '".$lastupdateslno."'" ;
				$fetch1 = runmysqlqueryfetch($query1);
				$createddate = changedateformatwithtime($fetch1['createddate']);
				$datecreated = substr($createddate,0,10);
				$timecreated = substr($createddate,11);
				$processeddatetime = changedateformatwithtime($fetch1['processeddatetime']);
				$processeddate =  substr($processeddatetime,0,10);
				$processedtime =  substr($processeddatetime,11);
			
				$query23 ="SELECT inv_mas_dealer.slno as slno,inv_mas_dealer.businessname as businessname,
inv_mas_dealer.contactperson as contactperson,inv_mas_dealer.address as address,inv_mas_dealer.place as place,
inv_mas_district.districtname as district,inv_mas_dealer.pincode as pincode,inv_mas_state.statename as state,
inv_mas_dealer.phone as phone,inv_mas_region.category as region,inv_mas_dealer.personalemailid as personalemailid,
inv_mas_dealer.cell as cell,inv_mas_dealer.emailid as emailid,inv_mas_dealer.website as website,
inv_mas_dealer.stdcode as stdcode,inv_mas_district.districtname FROM inv_mas_dealer 
LEFT JOIN inv_mas_district ON inv_mas_district.districtcode = inv_mas_dealer.district 
LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_dealer.region where inv_mas_dealer.slno= '".$lastslno."'";
			$fetch23 = runmysqlqueryfetch($query23);
			$businessname = $fetch23['businessname'];
			$contactperson = $fetch23['contactperson'];
			$address = $fetch23['address'];
			$state = $fetch23['state'];
			$district = $fetch23['district'];
			$pincode = $fetch23['pincode'];
			$stdcode = $fetch23['stdcode'];
			$place = $fetch23['place'];
			$phone = $fetch23['phone'];
			$cell = $fetch23['cell'];
			$personalemailid = $fetch23['personalemailid'];
			$website = $fetch23['website'];
			$region = $fetch23['region'];
			$slno = $fetch23['slno'];
			//
			//$emailid = 'archana.ab@relyonsoft.com';
			if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
				$emailid = 'meghana.b@relyonsoft.com';
			else
				$emailid = $fetch23['emailid'];
			$emailarray = explode(',',$emailid);
			$emailcount = count($emailarray);
			
			for($i = 0; $i < $emailcount; $i++)
			{
				if(checkemailaddress($emailarray[$i]))
				{
						$emailids[$contactperson] = $emailarray[$i];
				}
			}
			$fromname = "Relyon";
			$fromemail = "imax@relyon.co.in";
			require_once("../inc/RSLMAIL_MAIL.php");
			$msg = file_get_contents("../mailcontents/dealerprofileupdate.htm");
			$textmsg = file_get_contents("../mailcontents/dealerprofileupdate.txt");
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
			if($website == '')
			{
				$website = 'Not Available';
			}
			
			$array[] = "##DATE##%^%".$date;
			$array[] = "##REQUESTDATE##%^%".$datecreated;
			$array[] = "##REQUESTTIME##%^%".$timecreated;
			$array[] = "##PROCESSEDDATE##%^%".$processeddate;
			$array[] = "##PROCESSEDTIME##%^%".$processedtime;
			$array[] = "##COMPANY##%^%".$businessname;
			$array[] = "##NAME##%^%".$contactperson;
			$array[] = "##ADDRESS##%^%".$address;
			$array[] = "##PLACE##%^%".$place;
			$array[] = "##DISTRICT##%^%".$district;
			$array[] = "##STATE##%^%".$state;
			$array[] = "##PINCODE##%^%".$pincode;
			$array[] = "##STDCODE##%^%".$stdcode;
			$array[] = "##PHONE##%^%".$phone;
			$array[] = "##CELL##%^%".$cell;
			$array[] = "##EMAILID##%^%".$emailid;
			$array[] = "##WEBSITE##%^%".$website;
			$array[] = "##PERSONALEMAILID##%^%".$personalemailid; 
			$array[] = "##REGION##%^%".$region;
			$array[] = "##EMAILID##%^%".$emailid;
			
			$filearray = array(
							array('../images/relyon-logo.jpg','inline','8888888888'),
							array('../images/contact-info.gif','inline','33333333333'),
						
						);
			$toarray = $emailids;
			if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
			{
				$bccemailids['archana'] ='archana.ab@relyonsoft.com';
			}
			else
			{
				$bccemailids['relyonimax'] ='relyonimax@gmail.com';
				$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
			}

			$bccarray = $bccemailids;
//			print_r($emailids);
			$msg = replacemailvariable($msg,$array);
			$textmsg = replacemailvariable($textmsg,$array);
			$subject = 'Profile Update Request has been processed by Relyon.';
			$html = $msg;
			$text = $textmsg;
			$replyto = 'info@relyonsoft.com';
			rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray,$replyto);
			
			//Insert the mail forwarded details to the logs table
			$bccmailid = 'bigmail@relyonsoft.com'; 
			inserttologs(imaxgetcookie('userid'),$slno,$fromname,$fromemail,$emailid,null,$bccmailid,$subject);
			
#####################  Mailing Ends --------------------------------------------------------------
	//echo($updatequery);
	$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','4','".date('Y-m-d').' '.date('H:i:s')."','".$lastslno.'$$'.$lastupdateslno."')";
	$eventresult = runmysqlquery($eventquery);
	echo("1^"."Dealer Record has been Processed Successfully [".$newbusinessname."]");
	break;
	
}

?>
