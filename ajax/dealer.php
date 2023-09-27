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
$type = $_POST['type'];

switch($type)
{	
	case 'save':
	{   
		$responsearray = array();
		$dealerid = $_POST['dealerid'];
		$businessname = $_POST['businessname'];
		$dealerusername = $_POST['dealerusername'];
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
		$relyonexecutive = $_POST['relyonexecutive'];
		$disablelogin = $_POST['disablelogin'];
		$remarks = $_POST['remarks'];
		$revenuesharenewsale = $_POST['revenuesharenewsale'];
		$revenueshareupsale = $_POST['revenueshareupsale'];
		$taxamount = $_POST['taxamount'];
		$taxname = $_POST['taxname'];
		$tlemailid = $_POST['tlemailid'];
		$mgremailid = $_POST['mgremailid'];
		$hoemailid = $_POST['hoemailid'];
		$dealernotinuse = $_POST['dealernotinuse'];
		$telecaller = $_POST['telecaller'];
		$personalemailid = $_POST['personalemailid'];
		$enablebilling = $_POST['enablebilling'];
		$enablematrixbilling = $_POST['enablematrixbilling'];
		$branchhead = $_POST['branchhead'];
		
		#Added on 19th Jan 2018
		     $maindealers = $_POST['maindealers'];
		    $dealerhead = $_POST['dealerhead'];
		#ends

        #Added on 23rd Aug 2019
        $dealertype = $_POST['dealertype'];
        $newdealertype = $_POST['newdealertype'];
        #ends

		$branch = $_POST['branch'];
		$billingname = $_POST['billingname'];
		$saifreepin = $_POST['saifreepin'];
		$createddate = datetimelocal('Y-m-d').' '.datetimelocal('H:i:s');
		$gst_no = $_POST['gst_no'];
		$panno = $_POST['panno'];
		$editcustdata = $_POST['editcustdata'];
		$forcesurrender = $_POST['forcesurrender'];
		
			
		if($lastslno == "")
		{	$password = generatepwd();
			$query = "SELECT (MAX(slno) + 1) AS newdealerid FROM inv_mas_dealer";
			$fetch = runmysqlqueryfetch($query);
			$dealerid = $fetch['newdealerid'];
			$fetchcountusername = runmysqlqueryfetch("SELECT COUNT(*) AS count FROM inv_mas_dealer WHERE dealerusername = '".$dealerusername."'");
			if($fetchcountusername['count'] > 0)
			{
				$responsearray['errormessage'] = '3^Enter the different Username as it exists already.';
				//echo('3^Enter the different Username as it exists already.');
			}
			else
			{
			  $query1 = "INSERT INTO inv_mas_dealer (slno,businessname, contactperson,dealerusername, address, place, district, pincode, stdcode, phone, cell, region, emailid, website, relyonexecutive, disablelogin, remarks, initialpassword,loginpassword, passwordchanged, revenuesharenewsale, taxamount, taxname, tlemailid, mgremailid, hoemailid, createddate, userid,revenueshareupsale,personalemailid,dealernotinuse,telecaller,lastmodifieddate,lastmodifiedby,createdip,lastmodifiedip,enablebilling,enablematrixbilling,branchhead,branch,billingname,saifreepin,gst_no,maindealers,dealerhead,dealertype,dealertypehead,panno,editcustdata,forcesurrender) VALUES('".$dealerid."','".trim($businessname)."','".$contactperson."','".$dealerusername."','".$address."','".$place."','".$district."','".$pincode."','".$stdcode."','".$phonevalue."','".$cellvalue."','".$region."','".$emailid."','".$website."','".$relyonexecutive."','".$disablelogin."','".$remarks."','".$password."',AES_ENCRYPT('".$password."','imaxpasswordkey'),'N','".$revenuesharenewsale."','".$taxamount."','".$taxname."','".$tlemailid."','".$mgremailid."','".$hoemailid."','".$createddate."','".$userid."','".$revenueshareupsale."','".$personalemailid."','".$dealernotinuse."','".$telecaller."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".$_SERVER['REMOTE_ADDR']."','".$enablebilling."','".$enablematrixbilling."','".$branchhead."','".$branch."','".$billingname."','".$saifreepin."','".$gst_no."','','','".$dealertype."','".$newdealertype."','".$panno."','".$editcustdata."','".$forcesurrender."');";
			  
			  $result = runmysqlquery($query1);
			  
			 // echo $query1;
			  
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','27','".date('Y-m-d').' '.date('H:i:s')."','".$dealerid."')";
			$eventresult = runmysqlquery($eventquery);

			  sendwelcomeemailtodealer($dealerid);
			  $responsearray['errormessage'] = "1^"."Dealer Record '".$dealerid."' Saved Successfully";
			//echo("1^"."Dealer Record '".$dealerid."' Saved Successfully");
			}
					

		}
		else
		{
			$fetchcountusername = runmysqlqueryfetch("SELECT COUNT(*) AS count FROM inv_mas_dealer WHERE dealerusername = '".$dealerusername."' AND slno <> '".$lastslno."'");
			if($fetchcountusername['count'] > 0)
			{
				 $responsearray['errormessage'] = '3^Enter the different Username as it exists already.';
				//echo('3^Enter the different Username as it exists already.');
			}
			else
			{
					$query2 = "UPDATE inv_mas_dealer SET businessname = '".trim($businessname)."',contactperson = '".$contactperson."',dealerusername = '".$dealerusername."',address = '".$address."',place = '".$place."',district = '".$district."',pincode = '".$pincode."',stdcode = '".$stdcode."',phone = '".$phonevalue."',cell = '".$cellvalue."',region = '".$region."',emailid = '".$emailid."',website = '".$website."',relyonexecutive = '".$relyonexecutive."',disablelogin = '".$disablelogin."',remarks = '".$remarks."',revenuesharenewsale = '".$revenuesharenewsale."',revenueshareupsale = '".$revenueshareupsale."',taxamount = '".$taxamount."',taxname = '".$taxname."',tlemailid = '".$tlemailid."',mgremailid = '".$mgremailid."',hoemailid = '".$hoemailid."',dealernotinuse = '".$dealernotinuse."',personalemailid = '".$personalemailid."',telecaller = '".$telecaller."',enablebilling = '".$enablebilling."',enablematrixbilling='".$enablematrixbilling."',lastmodifieddate ='".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedby ='".$userid."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."',branchhead = '".$branchhead."',branch = '".$branch."',billingname = '".$billingname."',saifreepin = '".$saifreepin."',gst_no = '".$gst_no."', dealertype = '".$dealertype."',dealertypehead = '".$newdealertype."',panno = '".$panno."',editcustdata = '".$editcustdata."',forcesurrender = '".$forcesurrender."' WHERE slno = '".$lastslno."';";
				
				$result = runmysqlquery($query2);
				
				//echo $query2;
				
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','28','".date('Y-m-d').' '.date('H:i:s')."','".$lastslno."')";
				$eventresult = runmysqlquery($eventquery);
				$query = "SELECT slno FROM inv_mas_dealer WHERE slno = '".$lastslno."';";
				$fetch = runmysqlqueryfetch($query);
				$dealerid = $fetch['slno'];
				$responsearray['errormessage'] = "1^"."Dealer Record '".$dealerid."' Saved Successfully";
				//echo("1^"."Dealer Record '".$dealerid."' Saved Successfully");

			}
		}
		echo(json_encode($responsearray));
	}
	break;
	case 'dealergstcode':
    {
      $stateid = $_POST['stateid'];
      
      $result = runmysqlqueryfetch("SELECT state_gst_code FROM inv_mas_state WHERE statecode = '".$stateid."'");
	  $fetchcusID = $result['state_gst_code'];
	  
	  $responsearraygst['state_gst_code'] = $fetchcusID;
	  echo(json_encode($responsearraygst));
    }
    break;
	case 'delete':
	{
		$responsearray = array();
		$recordflag1 = deleterecordcheck($lastslno,'dealerid','inv_bill');
		$recordflag2 = deleterecordcheck($lastslno,'dealerid','inv_credits');
		$recordflag3 = deleterecordcheck($lastslno,'dealerid','inv_customerproduct');
		$recordflag4 = deleterecordcheck($lastslno,'dealerid','inv_dealercard');
		$recordflag5 = deleterecordcheck($lastslno,'firstdealer','inv_mas_customer');
		$recordflag6 = deleterecordcheck($lastslno,'currentdealer','inv_mas_customer');
		if($recordflag1 == true && $recordflag2 == true && $recordflag3 == true && $recordflag4 == true && $recordflag5 == true && $recordflag6 == true)
		{
			$result = runmysqlqueryfetch("SELECT slno FROM inv_mas_dealer WHERE  slno = '".$lastslno."'");
			$fetchdeaID = $result['slno'];
			$query = "DELETE FROM inv_mas_dealer WHERE slno = '".$lastslno."'";
			$result = runmysqlquery($query);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','29','".date('Y-m-d').' '.date('H:i:s')."','".$lastslno."')";
			$eventresult = runmysqlquery($eventquery);
			$responsearray['errormessage'] = "2^"."Dealer Record '".$fetchdeaID."' Deleted Successfully";
			//echo("2^"."Dealer Record '".$fetchdeaID."' Deleted Successfully");
		}
		else
		{
			$responsearray['errormessage'] = "3^"."Dealer Record cannot be deleted as the record referred.";
			//echo("3^"."Dealer Record cannot be deleted as the record referred.");
		}
		
		/*$query = "INSERT INTO inv_logs(date,time,type,action,existingdata,updateddata) VALUES('".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','Customer Screen','Delete Record','','".$updatedata."');";
		$result = runmysqlquery($query);*/
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
		$grid = '';
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$generatedealerlistarray[$count] = $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
		echo(json_encode($generatedealerlistarray));
		//echo($query);
	}
	break;
	
	case 'dealerdetailstoform':
	{
		$dealerdetailstoformarray = array();
		$currentcreditavail = getcurrentcredit($lastslno);
		$query1 = "SELECT count(*) as count from inv_mas_dealer where slno = '".$lastslno."'";
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)
		{
			$query = "SELECT inv_mas_dealer.slno,inv_mas_dealer.businessname, inv_mas_dealer.gst_no ,inv_mas_dealer.maindealers,inv_mas_dealer.dealertype,inv_mas_dealer.dealertypehead,inv_mas_state.state_gst_code as state_gst, inv_mas_dealer.contactperson, inv_mas_dealer.dealerusername, inv_mas_dealer.address, inv_mas_dealer.place, inv_mas_dealer.district, inv_mas_district.statecode as state, inv_mas_dealer.pincode, inv_mas_dealer.stdcode,inv_mas_dealer.phone, inv_mas_dealer.cell, inv_mas_dealer.region as region, inv_mas_dealer.emailid, inv_mas_dealer.website, inv_mas_dealer.relyonexecutive, inv_mas_dealer.disablelogin, inv_mas_dealer.remarks, 	inv_mas_dealer.passwordchanged,inv_mas_dealer.revenuesharenewsale,inv_mas_dealer.revenueshareupsale,inv_mas_dealer.taxamount,inv_mas_dealer.taxname  ,inv_mas_dealer.tlemailid  ,inv_mas_dealer.mgremailid  ,inv_mas_dealer.hoemailid  ,inv_mas_dealer.personalemailid ,inv_mas_dealer.dealernotinuse,inv_mas_dealer.telecaller ,inv_mas_dealer.createddate ,inv_mas_users.fullname,inv_mas_dealer.initialpassword as initialpassword ,inv_mas_dealer.enablebilling,inv_mas_dealer.enablematrixbilling,inv_mas_dealer.branchhead,inv_mas_dealer.branch,inv_mas_dealer.billingname ,inv_mas_dealer.saifreepin,panno,editcustdata,inv_mas_dealer.forcesurrender FROM inv_mas_dealer 
			left join inv_mas_users ON inv_mas_dealer.userid = inv_mas_users.slno 
			left join inv_mas_district on inv_mas_district.districtcode = inv_mas_dealer.district 
			left join inv_mas_region on inv_mas_region.slno = inv_mas_dealer.region 
			left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
			where inv_mas_dealer.slno = '".$lastslno."';";
			
			$fetch = runmysqlqueryfetch($query);
			$dealerdetailstoformarray['slno'] = $fetch['slno'];
			$dealerdetailstoformarray['businessname'] = $fetch['businessname'];
			$dealerdetailstoformarray['contactperson'] = $fetch['contactperson'];
			$dealerdetailstoformarray['address'] = $fetch['address'];
			$dealerdetailstoformarray['place'] = $fetch['place'];
			$dealerdetailstoformarray['district'] = $fetch['district'];
			$dealerdetailstoformarray['state'] = $fetch['state'];
			$dealerdetailstoformarray['pincode'] = $fetch['pincode'];
			$dealerdetailstoformarray['stdcode'] = $fetch['stdcode'];
			$dealerdetailstoformarray['phone'] = $fetch['phone'];
			$dealerdetailstoformarray['cell'] = $fetch['cell'];
			$dealerdetailstoformarray['region'] = $fetch['region'];
			$dealerdetailstoformarray['emailid'] = $fetch['emailid'];
			$dealerdetailstoformarray['website'] = $fetch['website'];
			$dealerdetailstoformarray['relyonexecutive'] = $fetch['relyonexecutive'];
			$dealerdetailstoformarray['disablelogin'] = $fetch['disablelogin'];
			$dealerdetailstoformarray['remarks'] = $fetch['remarks'];
			$dealerdetailstoformarray['initialpassword'] = $fetch['initialpassword'];
			$dealerdetailstoformarray['passwordchanged'] = strtolower($fetch['passwordchanged']);
			$dealerdetailstoformarray['revenuesharenewsale'] = $fetch['revenuesharenewsale'];
			$dealerdetailstoformarray['taxamount'] = $fetch['taxamount'];
			$dealerdetailstoformarray['taxname'] = $fetch['taxname'];
			$dealerdetailstoformarray['tlemailid'] = $fetch['tlemailid'];
			$dealerdetailstoformarray['mgremailid'] = $fetch['mgremailid'];
			$dealerdetailstoformarray['hoemailid'] = $fetch['hoemailid'];
			$dealerdetailstoformarray['createddate'] = changedateformatwithtime($fetch['createddate']);
			$dealerdetailstoformarray['fullname'] = $fetch['fullname'];
			$dealerdetailstoformarray['userid'] = $userid;
			$dealerdetailstoformarray['dealerusername'] = $fetch['dealerusername'];
			$dealerdetailstoformarray['revenueshareupsale'] = $fetch['revenueshareupsale'];
			$dealerdetailstoformarray['currentcreditavail'] = $currentcreditavail;
			$dealerdetailstoformarray['personalemailid'] = $fetch['personalemailid'];
			$dealerdetailstoformarray['dealernotinuse'] = $fetch['dealernotinuse'];
			$dealerdetailstoformarray['telecaller'] = $fetch['telecaller'];
			$dealerdetailstoformarray['initialpassword'] = $fetch['initialpassword'];
			$dealerdetailstoformarray['p_editdealerpassword'] = $p_editdealerpassword;
			$dealerdetailstoformarray['enablebilling'] = $fetch['enablebilling'];
			$dealerdetailstoformarray['enablematrixbilling'] = $fetch['enablematrixbilling'];
			$dealerdetailstoformarray['branchhead'] = $fetch['branchhead'];
			$dealerdetailstoformarray['branch'] = $fetch['branch'];
			$dealerdetailstoformarray['billingname'] = $fetch['billingname'];
			$dealerdetailstoformarray['saifreepin'] = $fetch['saifreepin'];
			$dealerdetailstoformarray['gst_no'] = $fetch['gst_no'];
			$dealerdetailstoformarray['state_gst'] = $fetch['state_gst'];
			//$dealerdetailstoformarray['maindealers'] = $fetch['maindealers'];
			//$dealerdetailstoformarray['dealerhead'] = $fetch['dealerhead'];
            $dealerdetailstoformarray['dealertype'] = $fetch['dealertype'];
            $dealerdetailstoformarray['dealertypehead'] = $fetch['dealertypehead'];
			$dealerdetailstoformarray['panno'] = $fetch['panno'];
			$dealerdetailstoformarray['editcustdata'] = $fetch['editcustdata'];
			$dealerdetailstoformarray['forcesurrender'] = $fetch['forcesurrender'];
			
			//echo($fetch['slno'].'^'.$fetch['businessname'].'^'.$fetch['contactperson'].'^'.$fetch['address'].'^'.$fetch['place'].'^'.$fetch['district'].'^'.$fetch['state'].'^'.$fetch['pincode'].'^'.$fetch['stdcode'].'^'.$fetch['phone'].'^'.$fetch['cell'].'^'.$fetch['region'].'^'.$fetch['emailid'].'^'.$fetch['website'].'^'.$fetch['relyonexecutive'].'^'.$fetch['disablelogin'].'^'.$fetch['remarks'].'^'.$fetch['initialpassword'].'^'.strtolower($fetch['passwordchanged']).'^'.$fetch['revenuesharenewsale'].'^'.$fetch['taxamount'].'^'.$fetch['taxname'].'^'.$fetch['tlemailid'].'^'.$fetch['mgremailid'].'^'.$fetch['hoemailid'].'^'.changedateformatwithtime($fetch['createddate']).'^'.$fetch['fullname'].'^'.$userid.'^'.$fetch['dealerusername'].'^'.$fetch['revenueshareupsale'].'^'.$currentcreditavail.'^'.$fetch['personalemailid'].'^'.$fetch['dealernotinuse'].'^'.$fetch['telecaller'].'^'.$fetch['initialpassword'].'^'.$p_editdealerpassword.'^'.$fetch['enablebilling'].'^'.$fetch['branchhead'].'^'.$fetch['branch'].'^'.$fetch['billingname'].'^'.$fetch['saifreepin']);
		}
		else
		{
			$dealerdetailstoformarray['slno'] = '';
			$dealerdetailstoformarray['businessname'] = '';
			$dealerdetailstoformarray['contactperson'] = '';
			$dealerdetailstoformarray['address'] = '';
			$dealerdetailstoformarray['district'] = '';
			$dealerdetailstoformarray['place'] = '';
			$dealerdetailstoformarray['state'] = '';
			$dealerdetailstoformarray['pincode'] = '';
			$dealerdetailstoformarray['stdcode'] = '';
			$dealerdetailstoformarray['phone'] = '';
			$dealerdetailstoformarray['cell'] = '';
			$dealerdetailstoformarray['region'] = '';
			$dealerdetailstoformarray['emailid'] = '';
			$dealerdetailstoformarray['website'] = '';
			$dealerdetailstoformarray['relyonexecutive'] = '';
			$dealerdetailstoformarray['disablelogin'] = '';
			$dealerdetailstoformarray['remarks'] = '';
			$dealerdetailstoformarray['initialpassword'] = '';
			$dealerdetailstoformarray['passwordchanged'] = '';
			$dealerdetailstoformarray['revenuesharenewsale'] = '';
			$dealerdetailstoformarray['taxamount'] ='';
			$dealerdetailstoformarray['taxname'] = '';
			$dealerdetailstoformarray['tlemailid'] = '';
			$dealerdetailstoformarray['mgremailid'] = '';
			$dealerdetailstoformarray['hoemailid'] = '';
			$dealerdetailstoformarray['createddate'] = '';
			$dealerdetailstoformarray['fullname'] = '';
			$dealerdetailstoformarray['userid'] = '';
			$dealerdetailstoformarray['dealerusername'] = '';
			$dealerdetailstoformarray['revenueshareupsale'] = '';
			$dealerdetailstoformarray['currentcreditavail'] = '';
			$dealerdetailstoformarray['personalemailid'] = '';
			$dealerdetailstoformarray['dealernotinuse'] = '';
			$dealerdetailstoformarray['telecaller'] = '';
			$dealerdetailstoformarray['initialpassword'] = '';
			$dealerdetailstoformarray['p_editdealerpassword'] ='';
			$dealerdetailstoformarray['enablebilling'] = '';
			$dealerdetailstoformarray['enablematrixbilling'] = '';
			$dealerdetailstoformarray['branchhead'] = '';
			$dealerdetailstoformarray['branch'] = '';
			$dealerdetailstoformarray['billingname'] = '';
			$dealerdetailstoformarray['saifreepin'] = '';
			$dealerdetailstoformarray['gst_no'] = '';
			$dealerdetailstoformarray['state_gst'] = '';
			//$dealerdetailstoformarray['maindealers'] = '';
			//$dealerdetailstoformarray['dealerhead'] = '';
            $dealerdetailstoformarray['dealertype'] = '';
            $dealerdetailstoformarray['dealertypehead'] = '';
            $dealerdetailstoformarray['panno'] = '';
			$dealerdetailstoformarray['editcustdata'] = '';
			$dealerdetailstoformarray['forcesurrender'] = '';
			
			
			//echo($lastslno.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
		}
		echo(json_encode($dealerdetailstoformarray));
	}
	break;
	case 'resetpwd':
	{
		$resetpwdarray = array();
		$password = $_POST['password'];
		$lastslno = $_POST['dealerid'];
		$query = "UPDATE inv_mas_dealer SET loginpassword = AES_ENCRYPT('".$password."','imaxpasswordkey'),initialpassword = '".$password."',passwordchanged ='N' WHERE inv_mas_dealer.slno = '".$lastslno."'";
		$result = runmysqlquery($query);
		$query = "select  initialpassword as initialpassword,passwordchanged from inv_mas_dealer  WHERE slno = '".$lastslno."'";
		$result = runmysqlqueryfetch($query);
		$resetpwdarray['errorcode'] = '1';
		$resetpwdarray['initialpassword'] = $result['initialpassword'];
		$resetpwdarray['passwordchanged'] = $result['passwordchanged'];
		//echo('1^'.$result['initialpassword'].'^'.$result['passwordchanged']);
		echo(json_encode($resetpwdarray));
	}
	break;
	case 'searchbydealerid':
	{
		$currentcreditavail = getcurrentcredit($lastslno);
		$query1 = "SELECT count(*) as count from inv_mas_dealer where slno = '".$_POST['dealerid']."'";
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)
		{
			$query = "SELECT inv_mas_dealer.slno,inv_mas_dealer.businessname,inv_mas_dealer.contactperson,inv_mas_dealer.address,inv_mas_dealer.place,inv_mas_dealer.district,pincode,inv_mas_dealer.stdcode,inv_mas_dealer.phone,inv_mas_dealer.gst_no,inv_mas_state.state_gst_code as state_gst, inv_mas_dealer.cell,inv_mas_dealer.region,inv_mas_dealer.emailid,inv_mas_dealer.website,inv_mas_dealer.relyonexecutive,inv_mas_dealer.disablelogin,inv_mas_dealer.remarks,inv_mas_dealer.initialpassword as initialpassword,inv_mas_dealer.passwordchanged, inv_mas_dealer.revenuesharenewsale,inv_mas_dealer.revenueshareupsale,
inv_mas_dealer.taxamount,inv_mas_dealer.taxname,inv_mas_dealer.tlemailid,inv_mas_dealer.mgremailid,
inv_mas_dealer.hoemailid,inv_mas_dealer.personalemailid ,inv_mas_dealer.dealernotinuse,inv_mas_dealer.telecaller, inv_mas_dealer.createddate, inv_mas_users.fullname,inv_mas_dealer.enablebilling,editcustdata,forcesurrender,inv_mas_dealer.enablematrixbilling,inv_mas_dealer.branchhead,inv_mas_dealer.branch,panno FROM inv_mas_dealer LEFT JOIN inv_mas_users ON inv_mas_dealer.userid = inv_mas_users.slno WHERE slno = '".$_POST['dealerid']."'";
			$fetch = runmysqlqueryfetch($query);
			
			echo($fetch['slno'].'^'.$fetch['businessname'].'^'.$fetch['contactperson'].'^'.$fetch['address'].'^'.$fetch['place'].'^'.$fetch['district'].'^'.$fetch['pincode'].'^'.$fetch['stdcode'].'^'.$fetch['phone'].'^'.$fetch['cell'].'^'.$fetch['region'].'^'.$fetch['emailid'].'^'.$fetch['website'].'^'.$fetch['relyonexecutive'].'^'.$fetch['disablelogin'].'^'.$fetch['remarks'].'^'.$fetch['initialpassword'].'^'.$fetch['passwordchanged'].'^'.$fetch['revenuesharenewsale'].'^'.$fetch['taxamount'].'^'.$fetch['taxname'].'^'.$fetch['tlemailid'].'^'.$fetch['mgremailid'].'^'.$fetch['hoemailid'].'^'.changedateformatwithtime($fetch['createddate']).'^'.$fetch['fullname'].'^'.$userid.'^'.$fetch['dealerusername'].'^'.$fetch['revenueshareupsale'].'^'.$currentcreditavail.'^'.$fetch['personalemailid'].'^'.$fetch['dealernotinuse'].'^'.$fetch['telecaller'].'^'.$p_editdealerpassword.'^'.$enablebilling.'^'.$enablematrixbilling.'^'.$fetch['branchhead']).'^'.$fetch['branch'].'^'.$fetch['gst_no'].'^'.$fetch['state_gst'].'^'.$fetch['panno'].'^'.$fetch['editcustdata'].'^'.$fetch['forcesurrender'];
		}
		else
		{
			echo($lastslno.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
		}
	}
	break;
	
	case 'dealercardregistered':
	{
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$resultcount = "select inv_mas_scratchcard.cardid, inv_mas_scratchcard.scratchnumber, inv_mas_product.productname, inv_dealercard.purchasetype, inv_dealercard.usagetype, inv_mas_customer.businessname ,inv_dealercard.date from inv_dealercard left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid and inv_mas_scratchcard.registered = 'yes' AND inv_mas_scratchcard.cancelled ='No' left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode left join inv_customerproduct on inv_dealercard.cardid = inv_customerproduct.cardid left join inv_mas_customer on inv_mas_customer.slno = inv_customerproduct.customerreference where inv_dealercard.dealerid = '".$lastslno."' and inv_mas_scratchcard.cardid !='' order by inv_mas_scratchcard.cardid";
		$fetch10 = runmysqlquery($resultcount);
		$fetchresultcount = mysqli_num_rows($fetch10);
		if($showtype == 'all')
		$limit = 100000;
		else
		$limit = 10;
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
		$query = "select inv_mas_scratchcard.cardid, inv_mas_scratchcard.scratchnumber, inv_mas_product.productname, inv_dealercard.purchasetype, inv_dealercard.usagetype, inv_mas_customer.businessname ,inv_dealercard.date from inv_dealercard left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid and inv_mas_scratchcard.registered = 'yes' AND inv_mas_scratchcard.cancelled ='No' left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode left join inv_customerproduct on inv_dealercard.cardid = inv_customerproduct.cardid left join inv_mas_customer on inv_mas_customer.slno = inv_customerproduct.customerreference where inv_dealercard.dealerid = '".$lastslno."' and inv_mas_scratchcard.cardid !='' order by inv_mas_scratchcard.cardid LIMIT ".$startlimit.",".$limit.";";
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align ="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align ="left">PIN Serial Number</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">PIN Number</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Usage Type</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Customer ID</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Date Time</td></tr>';
		}
		
		
		$i_n = 0;
		while($fetch = mysqli_fetch_row($result))
		{
			$i_n++;
			$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow1" bgcolor='.$color.'>';
			$grid .= "<td nowrap='nowrap' class='td-border-grid'  align ='left'>".$slno."</td>";
			for($i = 0; $i < count($fetch); $i++)
			{
				if($i == 6)
				$grid .= "<td nowrap='nowrap' class='td-border-grid'  align ='left'>".changedateformatwithtime($fetch[$i])."</td>";
				else
				$grid .= "<td nowrap='nowrap' class='td-border-grid'  align ='left'>".$fetch[$i]."</td>";
			}
			$grid .= "</tr>";
		}
			$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		if($slno >=$fetchresultcount)
		
		$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2" ><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
		$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="getmoredealercardregisted(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'more\');" style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmoredealercardregisted(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color="#000000">(Show All Records)</font></a></div></td></tr></table>';
		
		echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid;
		
	}
	break;
	
	case 'dealercardunregistered':
	{
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$resultcount = "select inv_mas_scratchcard.cardid, inv_mas_scratchcard.scratchnumber, inv_mas_product.productname, inv_dealercard.purchasetype, inv_dealercard.usagetype,inv_dealercard.date from inv_dealercard left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode where inv_mas_scratchcard.registered = 'no' AND inv_mas_scratchcard.attached = 'Yes' AND inv_mas_scratchcard.blocked = 'no' and inv_dealercard.dealerid = '".$lastslno."' ORDER BY inv_mas_scratchcard.cardid ";
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
		$query = "select inv_mas_scratchcard.cardid, inv_mas_scratchcard.scratchnumber, inv_mas_product.productname, inv_dealercard.purchasetype, inv_dealercard.usagetype,inv_dealercard.date from inv_dealercard left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode where inv_mas_scratchcard.registered = 'no' AND inv_mas_scratchcard.attached = 'Yes' AND inv_mas_scratchcard.blocked = 'no' and inv_dealercard.dealerid = '".$lastslno."' ORDER BY inv_mas_scratchcard.cardid LIMIT ".$startlimit.",".$limit.";";
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid"  align ="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align ="left">PIN Serial Number</td><td nowrap = "nowrap" class="td-border-grid" align ="left">PIN Number</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Usage Type</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Date Time</td></tr>';
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
			$grid .= '<tr class="gridrow1" bgcolor='.$color.'>';
			$grid .= "<td nowrap='nowrap' class='td-border-grid'  align ='left'>".$slnocount."</td>";
			for($i = 0; $i < count($fetch); $i++)
			{
				if($i == 6)
				$grid .= "<td nowrap='nowrap' class='td-border-grid'  align ='left'>".changedateformatwithtime($fetch[$i])."</td>";
				else
				$grid .= "<td nowrap='nowrap' class='td-border-grid'  align ='left'>".$fetch[$i]."</td>";
			}
			$grid .= "</tr>";
		}
		$grid .= "</table>";

		$fetchcount = mysqli_num_rows($result);
		if($slnocount >= $fetchresultcount)
		
		$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
		$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="getmoredealercardunregisted(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmoredealercardunregisted(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
		
		echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid;
	}
	break;
	case 'dealersearch':
	{
		$textfield = $_POST['textfield'];
		$subselection = $_POST['subselection'];
		$orderby = $_POST['orderby'];
		$region = $_POST['region'];
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$regionpiece = ($region == '')?(""):(" AND inv_mas_dealer.region = '".$region."' ");
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
				case "dealerid":
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
				case "place":
					$orderbyfield = "place";
					break;
				default:
					$orderbyfield = "businessname";
					break;
			}
			
			switch($subselection)
			{
				case "dealerid":
					$query = "SELECT inv_mas_dealer.slno,inv_mas_dealer.businessname, inv_mas_dealer.contactperson, inv_mas_dealer.address, inv_mas_dealer.place, inv_mas_dealer.district, inv_mas_district.statecode as state, inv_mas_dealer.pincode, inv_mas_dealer.stdcode,inv_mas_dealer.phone, inv_mas_dealer.cell, inv_mas_region.category as region, inv_mas_dealer.emailid, inv_mas_dealer.website, inv_mas_dealer.relyonexecutive, inv_mas_dealer.disablelogin, inv_mas_dealer.telecaller, inv_mas_dealer.remarks, inv_mas_dealer.passwordchanged, inv_mas_dealer.revenuesharenewsale, inv_mas_dealer.revenueshareupsale, inv_mas_dealer.taxamount, inv_mas_dealer.taxname , inv_mas_dealer.tlemailid , inv_mas_dealer.mgremailid , inv_mas_dealer.hoemailid , inv_mas_dealer.createddate , inv_mas_users.fullname,inv_mas_dealer.personalemailid ,inv_mas_dealer.dealernotinuse FROM inv_mas_dealer left join inv_mas_users ON inv_mas_users.slno = inv_mas_dealer.userid left join inv_mas_district on inv_mas_district.districtcode = inv_mas_dealer.district left join inv_mas_region on inv_mas_region.slno = inv_mas_dealer.region  WHERE inv_mas_dealer.slno LIKE '%".$textfield."%' ".$regionpiece." ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
					case "contactperson":
					$query = "SELECT inv_mas_dealer.slno,inv_mas_dealer.businessname, inv_mas_dealer.contactperson, inv_mas_dealer.address, inv_mas_dealer.place, inv_mas_dealer.district, inv_mas_district.statecode as state, inv_mas_dealer.pincode, inv_mas_dealer.stdcode,inv_mas_dealer.phone, inv_mas_dealer.cell, inv_mas_region.category as region, inv_mas_dealer.emailid, inv_mas_dealer.website, inv_mas_dealer.relyonexecutive, inv_mas_dealer.disablelogin, inv_mas_dealer.telecaller, inv_mas_dealer.remarks, inv_mas_dealer.passwordchanged, inv_mas_dealer.revenuesharenewsale, inv_mas_dealer.revenueshareupsale, inv_mas_dealer.taxamount, inv_mas_dealer.taxname  , inv_mas_dealer.tlemailid , inv_mas_dealer.mgremailid , inv_mas_dealer.hoemailid  , inv_mas_dealer.createddate, inv_mas_users.fullname,inv_mas_dealer.personalemailid ,inv_mas_dealer.dealernotinuse  FROM inv_mas_dealer left join inv_mas_users ON inv_mas_users.slno = inv_mas_dealer.userid  left join inv_mas_district on inv_mas_district.districtcode = inv_mas_dealer.district left join inv_mas_region on inv_mas_region.slno = inv_mas_dealer.region  WHERE inv_mas_dealer.contactperson LIKE '%".$textfield."%' ".$regionpiece." ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
				case "phone":
					$query = "SELECT inv_mas_dealer.slno,inv_mas_dealer.businessname, inv_mas_dealer.contactperson, inv_mas_dealer.address, inv_mas_dealer.place, inv_mas_dealer.district, inv_mas_district.statecode as state, inv_mas_dealer.pincode, inv_mas_dealer.stdcode,inv_mas_dealer.phone, inv_mas_dealer.cell, inv_mas_region.category as region, inv_mas_dealer.emailid, inv_mas_dealer.website, inv_mas_dealer.relyonexecutive, inv_mas_dealer.disablelogin, inv_mas_dealer.telecaller, inv_mas_dealer.remarks, inv_mas_dealer.passwordchanged, inv_mas_dealer.revenuesharenewsale, inv_mas_dealer.revenueshareupsale, inv_mas_dealer.taxamount, inv_mas_dealer.taxname  , inv_mas_dealer.tlemailid , inv_mas_dealer.mgremailid , inv_mas_dealer.hoemailid  , inv_mas_dealer.createddate, inv_mas_users.fullname ,inv_mas_dealer.personalemailid ,inv_mas_dealer.dealernotinuse FROM inv_mas_dealer left join inv_mas_users ON inv_mas_users.slno = inv_mas_dealer.userid  left join inv_mas_district on inv_mas_district.districtcode = inv_mas_dealer.district  left join inv_mas_region on inv_mas_region.slno = inv_mas_dealer.region WHERE inv_mas_dealer.phone LIKE '%".$textfield."%' ".$regionpiece." ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
				case "place":
					$query = "SELECT inv_mas_dealer.slno,inv_mas_dealer.businessname, inv_mas_dealer.contactperson, inv_mas_dealer.address, inv_mas_dealer.place, inv_mas_dealer.district, inv_mas_district.statecode as state, inv_mas_dealer.pincode, inv_mas_dealer.stdcode,inv_mas_dealer.phone, inv_mas_dealer.cell, inv_mas_region.category as region, inv_mas_dealer.emailid, inv_mas_dealer.website, inv_mas_dealer.relyonexecutive, inv_mas_dealer.disablelogin, inv_mas_dealer.telecaller, inv_mas_dealer.remarks, inv_mas_dealer.passwordchanged, inv_mas_dealer.revenuesharenewsale, inv_mas_dealer.revenueshareupsale, inv_mas_dealer.taxamount, inv_mas_dealer.taxname  , inv_mas_dealer.tlemailid , inv_mas_dealer.mgremailid , inv_mas_dealer.hoemailid , inv_mas_dealer.createddate, inv_mas_users.fullname ,inv_mas_dealer.personalemailid ,inv_mas_dealer.dealernotinuse FROM inv_mas_dealer left join inv_mas_users ON inv_mas_users.slno = inv_mas_dealer.userid  left join inv_mas_district on inv_mas_district.districtcode = inv_mas_dealer.district left join inv_mas_region on inv_mas_region.slno = inv_mas_dealer.region  WHERE inv_mas_dealer.place LIKE '%".$textfield."%'  ".$regionpiece." ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
				case "emailid":
					$query = "SELECT inv_mas_dealer.slno,inv_mas_dealer.businessname, inv_mas_dealer.contactperson, inv_mas_dealer.address, inv_mas_dealer.place, inv_mas_dealer.district, inv_mas_district.statecode as state, inv_mas_dealer.pincode, inv_mas_dealer.stdcode,inv_mas_dealer.phone, inv_mas_dealer.cell, inv_mas_region.category as region, inv_mas_dealer.emailid, inv_mas_dealer.website, inv_mas_dealer.relyonexecutive, inv_mas_dealer.disablelogin, inv_mas_dealer.telecaller, inv_mas_dealer.remarks, inv_mas_dealer.passwordchanged, inv_mas_dealer.revenuesharenewsale, inv_mas_dealer.revenueshareupsale, inv_mas_dealer.taxamount, inv_mas_dealer.taxname  , inv_mas_dealer.tlemailid , inv_mas_dealer.mgremailid , inv_mas_dealer.hoemailid  , inv_mas_dealer.createddate, inv_mas_users.fullname ,inv_mas_dealer.personalemailid ,inv_mas_dealer.dealernotinuse FROM inv_mas_dealer left join inv_mas_users ON inv_mas_users.slno = inv_mas_dealer.userid  left join inv_mas_district on inv_mas_district.districtcode = inv_mas_dealer.district left join inv_mas_region on inv_mas_region.slno = inv_mas_dealer.region  WHERE inv_mas_dealer.emailid LIKE '%".$textfield."%' ".$regionpiece."  ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
				case "relyonexecutive":
					$query = "SELECT inv_mas_dealer.slno,inv_mas_dealer.businessname, inv_mas_dealer.contactperson, inv_mas_dealer.address, inv_mas_dealer.place, inv_mas_dealer.district, inv_mas_district.statecode as state, inv_mas_dealer.pincode, inv_mas_dealer.stdcode,inv_mas_dealer.phone, inv_mas_dealer.cell, inv_mas_region.category as region, inv_mas_dealer.emailid, inv_mas_dealer.website, inv_mas_dealer.relyonexecutive, inv_mas_dealer.disablelogin, inv_mas_dealer.telecaller, inv_mas_dealer.remarks, inv_mas_dealer.passwordchanged , inv_mas_dealer.revenuesharenewsale, inv_mas_dealer.revenueshareupsale, inv_mas_dealer.taxamount, inv_mas_dealer.taxname  , inv_mas_dealer.tlemailid , inv_mas_dealer.mgremailid , inv_mas_dealer.hoemailid , inv_mas_dealer.createddate, inv_mas_users.fullname,inv_mas_dealer.personalemailid ,inv_mas_dealer.dealernotinuse  FROM inv_mas_dealer left join inv_mas_users ON inv_mas_users.slno = inv_mas_dealer.userid  left join inv_mas_district on inv_mas_district.districtcode = inv_mas_dealer.district left join inv_mas_region on inv_mas_region.slno = inv_mas_dealer.region  WHERE inv_mas_dealer.relyonexecutive LIKE '%".$textfield."%' ".$regionpiece." ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
				
				default:
					$query = "SELECT inv_mas_dealer.slno,inv_mas_dealer.businessname, inv_mas_dealer.contactperson, inv_mas_dealer.address, inv_mas_dealer.place, inv_mas_dealer.district, inv_mas_district.statecode as state, inv_mas_dealer.pincode, inv_mas_dealer.stdcode,inv_mas_dealer.phone, inv_mas_dealer.cell, inv_mas_region.category as region, inv_mas_dealer.emailid, inv_mas_dealer.website, inv_mas_dealer.relyonexecutive, inv_mas_dealer.disablelogin, inv_mas_dealer.telecaller, inv_mas_dealer.remarks, inv_mas_dealer.passwordchanged, inv_mas_dealer.revenuesharenewsale, inv_mas_dealer.revenueshareupsale, inv_mas_dealer.taxamount, inv_mas_dealer.taxname  , inv_mas_dealer.tlemailid , inv_mas_dealer.mgremailid , inv_mas_dealer.hoemailid , inv_mas_dealer.createddate, inv_mas_users.fullname ,inv_mas_dealer.personalemailid ,inv_mas_dealer.dealernotinuse FROM inv_mas_dealer left join inv_mas_users ON inv_mas_users.slno = inv_mas_dealer.userid  left join inv_mas_district on inv_mas_district.districtcode = inv_mas_dealer.district left join inv_mas_region on inv_mas_region.slno = inv_mas_dealer.region  WHERE inv_mas_dealer.businessname LIKE '%".$textfield."%' ".$regionpiece." ORDER BY ".$orderbyfield." LIMIT ".$startlimit.",".$limit.";";
					break;
			}
			if($startlimit == 0)
			{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" >';
			
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid"  align ="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Business Name</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Contact Person</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Address</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Place</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">District</td><td nowrap = "nowrap" class="td-border-grid" align ="left">State</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">PIN Code</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">STD Code</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Phone</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Cell</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Region</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Email ID</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Website</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Relyon Executive</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Disable Login</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Tele Caller</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Dealer Not In Use</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Remarks</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Password Changed</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Revenue Share New</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Revenue Share Updation</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Tax Amount</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Tax Name</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Personal Email ID</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">TL Email ID</td><td nowrap = "nowrap" class="td-border-grid">Manager Email ID</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">HO Email ID</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Created Date</td><td nowrap = "nowrap" class="td-border-grid"  align ="left">Created By</td></tr>';
			}
			$result = runmysqlquery($query);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','31','".date('Y-m-d').' '.date('H:i:s')."')";
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
				$grid .= '<tr class="gridrow" onclick ="dealerdetailstoform(\''.$fetch['slno'].'\');"  bgcolor='.$color.'>';
				$grid .= "<td nowrap='nowrap' class='td-border-grid'  align ='left'>".$slnocount."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['slno']."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['businessname'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['contactperson'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['address'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['place'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['district'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['state'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'".gridtrim($fetch['pincode'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".$fetch['stdcode']."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['phone'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['cell'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['region'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['emailid'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['website'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['relyonexecutive'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['disablelogin'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['telecaller'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['dealernotinuse'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['remarks'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['passwordchanged'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['revenuesharenewsale'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['revenueshareupsale'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['taxamount'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['taxname'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['personalemailid'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['tlemailid'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['mgremailid'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['hoemailid'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".changedateformatwithtime($fetch['createddate'])."</td><td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch['fullname'])."</td>";
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
	
	case 'attachscratchcard':
	{
	
		$dealerid = $_POST['dealerid'];
		$ttproduct = $_POST['ttproduct'];
		//$billnumber = $_POST['billnumber'];
		$date = $_POST['date'];
		$scratchcardfrom = $_POST['scratchcardfrom'];
		//$scratchcardto = $_POST['scratchcardto'];
		$formultiuser = $_POST['formultiuser'];
		//$forfree = $_POST['forfree'];
		$forupdate = $_POST['forupdate'];
	//	$additionallicence = $_POST['additionallicence'];
		
		/*$query = "SELECT COUNT(*) as count FROM inv_mas_scratchcard WHERE cardid between '".$scratchcardfrom."' and '".$scratchcardto."';";
		$fetch = runmysqlqueryfetch($query);
		$count = $fetch['count'];*/
		
		$query = "SELECT count(*) as count FROM inv_mas_scratchcard WHERE cardid = '".$scratchcardfrom."' AND attached = 'no';";
		$fetch = runmysqlqueryfetch($query);
		if($fetch['count'] > 0)
		{

		$query1 = "INSERT INTO inv_dealercard(dealerid,cardid,productcode,date,usagetype,purchasetype,userid) values('".$dealerid."','".$scratchcardfrom."','".$ttproduct."','".changedateformat($date)."','".$formultiuser."','".$forupdate."','".$userid."')";
		$result1 = runmysqlquery($query1);

		$query2 = "UPDATE inv_mas_scratchcard SET attached = 'yes', registered = 'no', blocked = 'no', online = 'no', cancelled = 'no'  WHERE cardid = '".$scratchcardfrom."' AND attached = 'no';";
		$result2 = runmysqlquery($query2);
		
		echo("1^"."Card (".$scratchcardfrom.") Attached Successfully.");
		}
		
	}
	break;
    //added on 21-10-2019
    case 'pspdealerlist':
    {
        $lastno = $_POST['dealerid'];
        $dealertype = $_POST['dealertype'];
        $responsearray = array();
        $responsearray['pspdealeroptions']=array();
        $responsearray['mspdealeroptions']=array();

        if($dealertype == 'msp') {
           $query = "select businessname,slno from inv_mas_dealer where dealertype = 'psp' and dealertypehead = '" . $lastno . "'";
            $result = runmysqlquery($query);
        }

            $pspdealerotions = '';
           // $pspdealeroptions = '<select name="pspdealerslist" size="5" class="swiftselect" id="pspdealerslist" style="width:250px; height:200px;" multiple>';

            while($fetch = mysqli_fetch_array($result))
            {
               // $pspdealeroptions='hiiiiii';
               $pspdealeroptions .= '<input type="checkbox" name="pspdealerslist[]" id="'.$fetch['slno'].'" value="'.$fetch['slno'].'" ><label for="'.$fetch['slno'].'"> '.$fetch['businessname'].'</label><br>';
            }
        //$pspdealeroptions='hiiiiii';
            //$pspdealeroptions .= '</select>';

        $mspdeaquery = "select businessname,slno from inv_mas_dealer where dealertype = 'msp' and slno!= ".$lastno;
        $mspdearesult = runmysqlquery($mspdeaquery);

        $mspdealeroptions = '';
        $mspdealeroptions = '<select name="mspdealerslist"  class="swiftselect" id="mspdealerslist">';
        $mspdealeroptions .= '<option  value="" selected>Select MSP Dealer</option>';
        while($mspdeafetch = mysqli_fetch_array($mspdearesult))
        {
            //$mspdealeroptions .= '<input type="radio" name="mspdealerslist" id="'.$mspdeafetch['slno'].'" value="'.$mspdeafetch['slno'].'" style="width:10px; height:10px; margin:5px">'.$mspdeafetch['businessname'].'<br>';
            $mspdealeroptions .= '<option  value="'.$mspdeafetch['slno'].'" >'.$mspdeafetch['businessname'].'</option>';
        }
        $mspdealeroptions .= '</select>';

        $responsearray['errorcode'] = 1;
        $responsearray['pspdealeroptions'] = $pspdealeroptions;
        $responsearray['mspdealeroptions'] = $mspdealeroptions;
        echo(json_encode($responsearray));
    }
    break;
	
	case 'dealerunassignedproduct':
	{	
		$dealerid = $_POST['lastslno'];
		$responsearray1 = array();
		$resultcount =  "select distinct inv_mas_product.productcode,inv_mas_product.productname from inv_mas_product
where inv_mas_product.productcode not in (select distinct inv_productmapping.productcode from inv_productmapping 
where dealerid = '".$dealerid."' ) and inv_mas_product.allowdealerpurchase = 'yes' order by inv_mas_product.productname";
		$result = runmysqlquery($resultcount);

		$productlistoptions = '';
		$productlistoptions = '<select name="productlist" size="5" class="swiftselect" id="productlist" style="width:280px; height:200px;" >';
		if( mysqli_num_rows($result) > 0)
		{
			while($fetch = mysqli_fetch_array($result))
			{
				$productlistoptions .= '<option value="'.$fetch['productcode'].'"  ondblclick="addproductentry(\''.$fetch['productcode'].'\',\'productlist\',\'selectedproducts\',\'addtype\');">'.$fetch['productname'].'</option>';
			}
		}
		$productlistoptions .= '</select>';
		$responsearray1['errorcode'] = "1";
		$responsearray1['productlistoptions'] = $productlistoptions;
		echo(json_encode($responsearray1));
		
	}
	break;
	case 'dealerassignedproduct':
	{	
		$dealerid = $_POST['lastslno'];
		$responsearray2 = array();
		$resultcount =  "select distinct inv_mas_product.productcode,inv_mas_product.productname from inv_mas_product
where inv_mas_product.productcode in (select distinct inv_productmapping.productcode from inv_productmapping 
where dealerid = '".$dealerid."' ) and inv_mas_product.allowdealerpurchase = 'yes' order by inv_mas_product.productname";
		$result = runmysqlquery($resultcount);

		$productlistoptions = '';
		$productlistoptions = '<select name="selectedproducts" size="5" class="swiftselect" id="selectedproducts" style="width:280px; height:200px;" >';
		while($fetch = mysqli_fetch_array($result))
		{
			$productlistoptions .= '<option value="'.$fetch['productcode'].'"  ondblclick="addproductentry(\''.$fetch['productcode'].'\',\'selectedproducts\',\'productlist\',\'addtype\')">'.$fetch['productname'].'</option>';
		}
		$productlistoptions .= '</select>';
		$responsearray2['errorcode'] = "1";
		$responsearray2['productlistoptions'] = $productlistoptions;
		echo(json_encode($responsearray2));
		
	}
	break;
	
	case 'updateproducts':
	{
		$productcode = $_POST['productarray'];
		$dealerid = $_POST['lastslno'];
		$responsearray3 = array();
		$splitvalue = explode(',',$productcode);
		for ($i = 0;$i < count($splitvalue);$i++)
		{
			$productcode_value .= "'" . $splitvalue[$i] . "'" ."," ;
		}
		$productslist = rtrim($productcode_value , ',');
		$finalproduct = str_replace('\\','',$productslist);
		
		$query = "select * from inv_productmapping where dealerid = '".$dealerid."'";
		$result = runmysqlquery($query);
		if(mysqli_num_rows($result) == 0)
		{
			for ($i = 0;$i < count($splitvalue);$i++)
			{
				$query11 = "INSERT INTO inv_productmapping(dealerid,productcode,userid,createddate,createdip,lastmodifieddate,lastmodifiedip,lastmodifiedby) VALUES('".$dealerid."','".$splitvalue[$i]."','".$userid."','".date('Y-m-d').'('.date('H:i:s').')'."','".$_SERVER['REMOTE_ADDR']."','".date('Y-m-d').' '.date('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','".$userid."')";
				$result11 = runmysqlquery($query11);
				//echo($query);
			}
		}
		else
		{
			$query12 = "DELETE FROM inv_productmapping WHERE dealerid = '".$dealerid."'";
			$result12 = runmysqlquery($query12);
			
			for ($i = 0;$i < count($splitvalue);$i++)
			{
				$query13 = "INSERT INTO inv_productmapping(dealerid,productcode,userid,createddate,createdip,lastmodifieddate,lastmodifiedip,lastmodifiedby) VALUES('".$dealerid."','".$splitvalue[$i]."','".$userid."','".date('Y-m-d').'('.date('H:i:s').')'."','".$_SERVER['REMOTE_ADDR']."','".date('Y-m-d').' '.date('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','".$userid."')";
				$result13 = runmysqlquery($query13);
			}
		}
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','30','".date('Y-m-d').' '.date('H:i:s')."','".$dealerid."')";
		$eventresult = runmysqlquery($eventquery);
		$responsearray3['errorcode'] = "1";
		$responsearray3['errormsg'] = "Record Saved Successfully.";
		echo(json_encode($responsearray3));		
		
	}
	break;
    //added on 06-11-2019
    case 'updatepspdealers' :
    {
        $pspdealist = "";
        $lastno = $_POST['lastno'];
        $mspdealerid = $_POST['mspdealerid'];
        $pspdealerlistid = $_POST['pspdealerlistid'];
        $pspdealist = explode(',',$pspdealerlistid);
        for($i=0;$i<count($pspdealist);$i++)
        {
            $query = "update inv_mas_dealer set dealertypehead = ".$mspdealerid." where slno = ".$pspdealist[$i];
            $result = runmysqlquery($query);
        }
    }
    break;
	
	
/*	case 'selectbillnumber':
	{
		$dealerid = $_POST['dealerid'];
		$query = "SELECT slno FROM inv_bill WHERE dealerid = '".$dealerid."'";
		$result = runmysqlquery($query);
		
		$grid = '<select name="billnumber" class="swiftselect-mandatory" id="billnumber" style="width:180px;"> <option value = "">Select A Bill</option>';
		while($fetch = mysqli_fetch_array($result))
		{
			$grid .= '<option value = "'.$fetch['slno'].'">'.$fetch['slno'].'</option>';
		}
		$grid .= '</select>'; 
		echo($grid);
	}
	break;
*/
}

// function to send an email on new Dealer creation
function sendwelcomeemailtodealer($dealerid)
{
	$query = "Select inv_mas_dealer.businessname,inv_mas_dealer.contactperson,inv_mas_dealer.place,inv_mas_dealer.phone,inv_mas_dealer.emailid,inv_mas_dealer.cell,inv_mas_dealer.stdcode,inv_mas_dealer.pincode,inv_mas_dealer.address,inv_mas_dealer.dealerusername,inv_mas_dealer.initialpassword as password,inv_mas_district.districtname,inv_mas_state.statename from inv_mas_dealer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_dealer.district left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_dealer.slno = '".$dealerid."' ";
	$fetchresult = runmysqlqueryfetch($query);
	$businessname = $fetchresult['businessname'];
	$contactperson = $fetchresult['contactperson'];
	$place = $fetchresult['place'];
	$phone = $fetchresult['phone'];
	$cell = $fetchresult['cell'];
	$stdcode = $fetchresult['stdcode'];
	$pincode = $fetchresult['pincode'];
	$address = $fetchresult['address'];
	$dealerusername = $fetchresult['dealerusername'];
	$password = $fetchresult['password'];
	$districtname = $fetchresult['districtname'];
	$statename = $fetchresult['statename'];
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
		$emailid = 'archana.ab@relyonsoft.com';
	else
		$emailid = $fetchresult['emailid'];
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
	$msg = file_get_contents("../mailcontents/newdealer.htm");
	$textmsg = file_get_contents("../mailcontents/new-dealer-text.txt");
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
	$array[] = "##DEALERID##%^%".$dealerid;
	$array[] = "##USERNAME##%^%".$dealerusername;
	$array[] = "##PASSWORD##%^%".$password;
	$array[] = "##PINCODE##%^%".$pincode;
	$array[] = "##STDCODE##%^%".$stdcode;
	$array[] = "##CELL##%^%".$cell;
	$array[] = "##ADDRESS##%^%".$address;
	$array[] = "##EMAILID##%^%".$emailid;
	
	$filearray = array(
		array('../images/relyon-logo.jpg','inline','8888888888'),
		array('../images/channel-partner.gif','inline','4444444444'),
		array('../images/contact-info.gif','inline','33333333333'),
		array('../images/dealer.gif','inline','22222222222')
	);
	$toarray = $emailids;
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
	{
		$bccemailids['archanaab'] ='meghana.b@relyonsoft.com';
	}
	else
	{
		$bccemailids['relyonimax'] ='relyonimax@gmail.com';
		$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
	}

	$bccarray = $bccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = 'New Channel Partner Registered in the system for "'.$businessname.'"';
	$html = $msg;
	$text = $textmsg;
	$replyto = 'info@relyonsoft.com';
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray,$replyto);
	//Insert the mail forwarded details to the logs table
	$bccmailid = 'bigmail@relyonsoft.com'; 
	inserttologs(imaxgetcookie('userid'),$dealerid,$fromname,$fromemail,$emailid,null,$bccmailid,$subject);
}

function getcurrentcredit($dealerid)
{
	$query0 = "SELECT sum(creditamount) as totalcredit FROM inv_credits WHERE dealerid = '".$dealerid."'";
	$resultfetch = runmysqlqueryfetch($query0);
	$totalcredit = $resultfetch['totalcredit'];
	$query1 = "SELECT sum(netamount) as billedamount FROM inv_bill WHERE dealerid = '".$dealerid."' AND billstatus ='successful'";
	$resultfetch1 = runmysqlqueryfetch($query1);
	$billedamount = $resultfetch1['billedamount'];
	$totalcreditavl = $totalcredit - $billedamount;
	return $totalcreditavl;
}
?>