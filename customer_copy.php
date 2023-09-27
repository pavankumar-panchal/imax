<?
/*
##Created By Bhavesh Patel
##Modify By Bhavesh Patel
##DATE: 08.07.13
##Description: in SURRENDER HISTORY ADDED AUTOREGIGISTRATIONYN = "Y" and added forcesurrenderdetails
*/
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
include('../softkey/regfunction.bin');
$lastslno = $_POST['lastslno'];
$switchtype = $_POST['switchtype'];

// Current Year 
$currentyear = "2019-20";
switch($switchtype)
{
	case 'save':
	{
		$customerid = $_POST['customerid'];
		$businessname = $_POST['businessname'];
		$oldbusinessname = $_POST['oldbusinessname'];
		$address = $_POST['address'];
		$place = $_POST['place'];
		$pincode = $_POST['pincode'];
		$district = $_POST['district'];
		$region = $_POST['region'];
		$category = $_POST['category'];
		$type = $_POST['type'];
		$fax = $_POST['fax'];
		$stdcode = $_POST['stdcode'];
			
		$gst_no = $_POST['gst_no'];	
		$sez_enabled = $_POST['sez_enabled'];
			
		$website = $_POST['website'];
		$remarks = $_POST['remarks'];
		$disablelogin = $_POST['disablelogin'];
		$corporateorder = $_POST['corporateorder'];
		$currentdealer = $_POST['currentdealer'];
		$branch = $_POST['branch'];
		$companyclosed = $_POST['companyclosed'];
		$isdealer = $_POST['isdealer'];
		$displayinwebsite = $_POST['displayinwebsite'];
		$promotionalsms = $_POST['promotionalsms'];
		$promotionalemail = $_POST['promotionalemail'];
		$contactarray = $_POST['contactarray'];
		$totalarray = $_POST['totalarray'];
		$totalsplit = explode(',',$totalarray);
		$contactsplit = explode('****',$contactarray);
		$contactcount = count($contactsplit);
		if($contactcount > 1)
		{
			for($i=0;$i<$contactcount;$i++)
			{
				$contactressplit[] = explode('#',$contactsplit[$i]);
			}
		}
		else
		{
			for($i=0;$i<$contactcount;$i++)
			{
				$contactressplit[] = explode('#',$contactsplit[$i]);
			}
		}
		
		$createddate = datetimelocal('d-m-Y').' '.datetimelocal('H:i:s');
		$date = datetimelocal('d-m-Y');
		if($lastslno == "")
		{

			$query = runmysqlqueryfetch("SELECT (MAX(slno) + 1) AS newcustomerid FROM inv_mas_customer");
			$cusslno = $query['newcustomerid'];

			$query1 = "Insert into inv_mas_customer(slno,customerid,businessname,address, place,pincode,district,region,category,type,stdcode,website,remarks,disablelogin,createddate,createdby,corporateorder,currentdealer,fax,firstproduct,activecustomer,lastmodifieddate,lastmodifiedby,createdip,lastmodifiedip,branch,companyclosed,isdealer,displayinwebsite,promotionalsms,promotionalemail,oldbusinessname,gst_no,sez_enabled) values ('".$cusslno."','','".trim($businessname)."','".$address."','".$place."','".$pincode."','".$district."','".$region."','".$category."','".$type."','".$stdcode."','".$website."','".$remarks."','".$disablelogin."','".changedateformatwithtime($createddate)."','".$userid."','".$corporateorder."','".$currentdealer."','".$fax."','','yes','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".$_SERVER['REMOTE_ADDR']."','".$branch."','".$companyclosed."','".$isdealer."','".$displayinwebsite."','".$promotionalsms."','".$promotionalemail."','".trim($oldbusinessname)."','".$gst_no."','".$sez_enabled."');";
			$result = runmysqlquery($query1);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','41','".date('Y-m-d').' '.date('H:i:s')."','".$cusslno."')";
			$eventresult = runmysqlquery($eventquery);

			for($j=0;$j<count($contactressplit);$j++)
			{
				$selectiontype = $contactressplit[$j][0];
				$contactperson = $contactressplit[$j][1];
				$phone = $contactressplit[$j][2];
				$cell = $contactressplit[$j][3];
				$emailid = $contactressplit[$j][4];
				//Added Space after comma is not Available in phone, cell and emailid fields
				$phonespace = str_replace(", ", ",",$phone);
				$phonevalue = str_replace(',',', ',$phonespace);
				
				$cellspace = str_replace(", ", ",",$cell);
				$cellvalue = str_replace(',',', ',$cellspace);
				
				$emailidspace = str_replace(", ", ",",$emailid);
				$emailidvalue = str_replace(',',', ',$emailidspace);
				
				$query2 = "Insert into inv_contactdetails(customerid,selectiontype,contactperson,phone,cell,emailid) values  ('".$cusslno."','".$selectiontype."','".$contactperson."','".$phonevalue."','".$cellvalue."','".$emailidvalue."');";
				$result = runmysqlquery($query2);
			}
			//echo("1^"."Customer Record Saved Successfully");
			$responsearray1['errorcode'] = "1";
			$responsearray1['errormessage'] = "Customer Record Saved Successfully";
			echo(json_encode($responsearray1));

		}
		/*else
		{
						
			$query4 = 'SELECT * from inv_mas_customer WHERE slno = "'.$lastslno.'"';
			$queryfetch = runmysqlqueryfetch($query4);
			
			$query5 = 'SELECT * from inv_contactdetails WHERE customerid = "'.$lastslno.'"';
			$result = runmysqlquery($query5);
			while($fetchres = mysql_fetch_array($result))
			{
				if($fetchres['selectiontype'] <> '')
					$appendedtype = ',';
				else
					$appendedtype = '';
				if($fetchres['designation'] <> '')
					$appdesignation = ',';
				else
					$appdesignation = '';
				if($fetchres['contactperson'] <> '')
					$appcontactperson = ',';
				else
					$appcontactperson = ' ';
				if($fetchres['phone'] <> '')
					$appendphone = ',';
				else
					$appendphone = '';
				if($fetchres['cell'] <> '')
					$appendcell = ',';
					else
					$appendcell = '';
					
				if($fetchres['emailid'] <> '')
					$appendemailid = ',';
					else
					$appendemailid = '';
				
				
				$selectiontype .= $fetchres['selectiontype'].$appendedtype;
				$designation .= $fetchres['designation'].$appdesignation;
				$contactperson .= $fetchres['contactperson'].$appcontactperson;
				$phone .= $fetchres['phone'].$appendphone;
				$cell .= $fetchres['cell'].$appendcell;
				$emailid .= $fetchres['emailid'].$appendemailid;
				
				
			} 
			$e_selectiontype = rtrim($selectiontype,',');
			$e_designation = rtrim($designation,',');
			$e_contactperson = rtrim($contactperson,',');
			$e_phone = rtrim($phone,',');
			$e_cell = rtrim($cell,',');
			$e_emailid = rtrim($emailid,',');

			$e_businessname = addslashes($queryfetch['businessname']);
			$e_address = addslashes($queryfetch['address']);
			$e_place = addslashes($queryfetch['place']);
			$e_pincode = $queryfetch['pincode'];
			$e_district = $queryfetch['district'];
			$e_region = $queryfetch['region'];
			$e_category = $queryfetch['category'];
			$e_type = $queryfetch['type'];
			$e_fax = $queryfetch['fax'];
			$e_stdcode = $queryfetch['stdcode'];
			$e_website = $queryfetch['website'];
			$e_remarks = addslashes($queryfetch['remarks']);
			$e_disablelogin = $queryfetch['disablelogin'];
			$e_corporateorder = $queryfetch['corporateorder'];
			$e_currentdealer = $queryfetch['currentdealer'];
			$e_branch = $queryfetch['branch'];
			$e_companyclosed = $_POST['companyclosed'];	
			$e_isdealer = $_POST['isdealer'];
			
		if($businessname <> $e_businessname || $address <> $e_address || $place <> $e_place || $pincode <> $e_pincode || $district <> $e_district || $region <> $e_region || $category <> $e_category || $type <> $e_type || $stdcode <> $e_stdcode || $phone <> $e_phone  || $website <> $e_website || $remarks <> $e_remarks || $disablelogin <> $e_disablelogin || $corporateorder <> $e_corporateorder || $currentdealer <> $e_currentdealer || $fax <> $e_fax || $branch <> $e_branch || $companyclosed <> $e_companyclosed || $isdealer <> $e_isdealer)
			{
				$query3 = "Insert into inv_logs_save(customerid,businessname,contactperson,address, place,pincode,district,region,category,type,stdcode,phone,cell,emailid ,website,remarks,disablelogin,corporateorder ,currentdealer,fax,branch,companyclosed,date,time,system,isdealer) values ('".$lastslno."','".$e_businessname."','".$e_contactperson."','".$e_address."','".$e_place."','".$e_pincode."','".$e_district."','".$e_region."','".$e_category."','".$e_type."','".$e_stdcode."','".$e_phone."','".$e_cell."','".$e_emailid."','".$e_website."','".$e_remarks."','".$e_disablelogin."','".$corporateorder."','".$currentdealer."','".$fax."','".$branch."','".$companyclosed."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','".$e_isdealer."');";
				$result = runmysqlquery($query3);
				$query2 = "UPDATE inv_mas_customer SET businessname = '".trim($businessname)."',address = '".$address."',place = '".$place."',pincode = '".$pincode."',district = '".$district."',region = '".$region."',category = '".$category."',type = '".$type."',stdcode = '".$stdcode."',website = '".$website."',remarks = '".$remarks."',disablelogin = '".$disablelogin."',corporateorder = '".$corporateorder."',currentdealer = '".$currentdealer."',fax ='".$fax."',lastmodifieddate ='".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedby ='".$userid."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."' ,companyclosed = '".$companyclosed."' ,branch = '".$branch."',isdealer = '".$isdealer."' WHERE slno = '".$lastslno."'";
				
				$result = runmysqlquery($query2);
				for($j=0;$j<count($contactressplit);$j++)
				{
					$selectiontype = $contactressplit[$j][0];
					$designation = $contactressplit[$j][1];
					$contactperson = $contactressplit[$j][2];
					$phone = $contactressplit[$j][3];
					$cell = $contactressplit[$j][4];
					$emailid = $contactressplit[$j][5];
					//Added Space after comma is not available in phone, cell and emailid fields
					$phonespace = str_replace(", ", ",",$phone);
					$phonevalue = str_replace(',',', ',$phonespace);
					
					$cellspace = str_replace(", ", ",",$cell);
					$cellvalue = str_replace(',',', ',$cellspace);
					
					$emailidspace = str_replace(", ", ",",$emailid);
					$emailidvalue = str_replace(',',', ',$emailidspace);
					
					$query21 = "UPDATE inv_contactdetails SET contactperson = '".$contactperson."',phone = '".$phonevalue."',cell = '".$cellvalue."',emailid = '".$emailidvalue."',selectiontype = '".$selectiontype."' WHERE customerid = '".$lastslno."'";
					$result = runmysqlquery($query21);
				}
			}*/
			else
			{
			
				$query4 = 'SELECT * from inv_mas_customer WHERE slno = "'.$lastslno.'"';
				$queryfetch = runmysqlqueryfetch($query4);
				$e_businessname = addslashes($queryfetch['businessname']);
				$e_oldbusinessname = addslashes($queryfetch['oldbusinessname']);
				$e_address = addslashes($queryfetch['address']);
				$e_place = addslashes($queryfetch['place']);
				$e_pincode = $queryfetch['pincode'];
				$e_district = $queryfetch['district'];
				$e_region = $queryfetch['region'];
				$e_category = $queryfetch['category'];
				$e_type = $queryfetch['type'];
				$e_fax = $queryfetch['fax'];
				$e_stdcode = $queryfetch['stdcode'];
				$e_gstno = $queryfetch['gst_no'];
				$e_sez_enabled= $queryfetch['sez_enabled'];
				$e_website = $queryfetch['website'];
				$e_remarks = addslashes($queryfetch['remarks']);
				$e_disablelogin = $queryfetch['disablelogin'];
				$e_corporateorder = $queryfetch['corporateorder'];
				$e_currentdealer = $queryfetch['currentdealer'];
				$e_branch = $queryfetch['branch'];
				$e_companyclosed = $queryfetch['companyclosed'];	
				$e_isdealer = $queryfetch['isdealer'];
				
				$query11 ="SELECT customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$lastslno."'; ";
				$resultfetch = runmysqlquery($query11);
				$valuecount1 = 0;$valuecount2 = 0;
				while($fetchres = mysql_fetch_array($resultfetch))
				{
					if($valuecount1 > 0)
						$oldcontactarray .= '****';
					
					$selectiontype1 = $fetchres['selectiontype'];
					$contactperson1 = $fetchres['contactperson'];
					$phone1 = $fetchres['phone'];
					$cell1 = $fetchres['cell'];
					$emailid1 = $fetchres['emailid'];
					
					$oldcontactarray .= $selectiontype1.'#'.$contactperson1.'#'.$phone1.'#'.$cell1.'#'.$emailid1;
					$valuecount1++;
				}
				
				//Insert details to logs Table	
				$query3 = "Insert into inv_logs_save(customerid,businessname,address, place,pincode,district,region,category,type,stdcode,website,remarks,disablelogin,corporateorder ,currentdealer,fax,branch,companyclosed,date,time,system,isdealer,contactdetails,oldbusinessname) values ('".$lastslno."','".$e_businessname."','".$e_address."','".$e_place."','".$e_pincode."','".$e_district."','".$e_region."','".$e_category."','".$e_type."','".$e_stdcode."','".$e_website."','".$e_remarks."','".$e_disablelogin."','".$corporateorder."','".$currentdealer."','".$fax."','".$branch."','".$companyclosed."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','".$e_isdealer."','".$oldcontactarray."','".$e_oldbusinessname."');";
				$result = runmysqlquery($query3);
				
				$query1 = "UPDATE inv_mas_customer SET businessname = '".trim($businessname)."',oldbusinessname = '".trim($oldbusinessname)."',address = '".$address."',place = '".$place."',pincode = '".$pincode."',district = '".$district."',region = '".$region."',category = '".$category."',type = '".$type."',stdcode = '".$stdcode."',website = '".$website."',remarks = '".$remarks."',disablelogin = '".$disablelogin."',corporateorder = '".$corporateorder."',currentdealer = '".$currentdealer."',fax ='".$fax."',lastmodifieddate ='".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedby ='".$userid."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."' ,companyclosed = '".$companyclosed."' ,branch = '".$branch."',isdealer = '".$isdealer."' ,displayinwebsite = '".$displayinwebsite."' ,promotionalsms = '".$promotionalsms."' ,promotionalemail = '".$promotionalemail."',gst_no = '".$gst_no."',sez_enabled = '".$sez_enabled."' WHERE slno = '".$lastslno."'";
				
				$result = runmysqlquery($query1);
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','42','".date('Y-m-d').' '.date('H:i:s')."','".$lastslno."')";
				$eventresult = runmysqlquery($eventquery);
				for($i=0;$i<count($totalsplit);$i++)
				{
					$deleteslno = $totalsplit[$i];
					$query22 = "DELETE FROM inv_contactdetails WHERE slno = '".$deleteslno."'";
					$result = runmysqlquery($query22);
				}
				for($j=0;$j<count($contactressplit);$j++)
				{
					$selectiontype = $contactressplit[$j][0];
					$contactperson = $contactressplit[$j][1];
					$phone = $contactressplit[$j][2];
					$cell = $contactressplit[$j][3];
					$emailid = $contactressplit[$j][4];
					$slno = $contactressplit[$j][5];
					//Added Space after comma is not Available in phone, cell and emailid fields
					$phonespace = str_replace(", ", ",",$phone);
					$phonevalue = str_replace(',',', ',$phonespace);
					
					$cellspace = str_replace(", ", ",",$cell);
					$cellvalue = str_replace(',',', ',$cellspace);
					
					$emailidspace = str_replace(", ", ",",$emailid);
					$emailidvalue = str_replace(',',', ',$emailidspace);
					if($slno <> '')
					{
						$query21 = "UPDATE inv_contactdetails SET contactperson = '".$contactperson."',phone = '".$phonevalue."',cell = '".$cellvalue."',emailid = '".$emailidvalue."',selectiontype = '".$selectiontype."' WHERE slno = '".$slno."'";
						$result = runmysqlquery($query21);
					}
					else
					{
						$query21 = "Insert into inv_contactdetails(customerid,selectiontype,contactperson,phone,cell,emailid) values  ('".$lastslno."','".$selectiontype."','".$contactperson."','".$phonevalue."','".$cellvalue."','".$emailidvalue."');";
						$result = runmysqlquery($query21);
					}
					
				}
				  $responsearray1 = array();
				  //echo("4^"."Customer Record Saved Successfully");
				  $responsearray1['errorcode'] = "1";
				  $responsearray1['errormessage'] = "Customer Record Saved Successfully";
				  echo(json_encode($responsearray1));
				
			}

		//}
	
	}
	break;
	
	case 'customergstcode':
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
		$result = runmysqlqueryfetch("SELECT customerid FROM inv_mas_customer WHERE  slno = '".$lastslno."'");
		$fetchcusID = $result['customerid'];
		
		$recordflag1 = deleterecordcheck($lastslno,'customerreference','inv_customerproduct');
		$recordflag2 = deleterecordcheck($lastslno,'customerreference','inv_customeramc');
		$recordflag3 = deleterecordcheck($lastslno,'customerid','inv_invoicenumbers');
		$recordflag4 = deleterecordcheck($lastslno,'customerid','inv_crossproduct');
		$recordflag5 = deleterecordcheck($lastslno,'customerid','inv_crossproductstatus');
		$recordflag6 = deleterecordcheck($lastslno,'customerid','inv_customerinteraction');
		$recordflag7 = deleterecordcheck($lastslno,'customerid','inv_customerreqpending');
		$recordflag8 = deleterecordcheck($lastslno,'custreferences','inv_custpaymentreq');
		//$recordflag9 = deleterecordcheck($lastslno,'customerreference','dealer_online_purchase');
		$recordflag10 = deleterecordcheck($lastslno,'customerreference','inv_hardwarelock');
		$recordflag11 = deleterecordcheck($lastslno,'userreference','inv_sms_bill');
		$recordflag12 = deleterecordcheck($lastslno,'userreference','inv_smsactivation');
		$recordflag13 = deleterecordcheck($lastslno,'custreference','pre_online_purchase');
		$recordflag14 = deleterecordcheck($lastslno,'customerid','ssm_callregister');
		$recordflag15 = deleterecordcheck($lastslno,'customerid','ssm_emailregister');
		$recordflag16 = deleterecordcheck($lastslno,'customerid','ssm_errorregister');
		$recordflag17 = deleterecordcheck($lastslno,'customerid','ssm_inhouseregister');
		$recordflag18 = deleterecordcheck($lastslno,'customerid','ssm_invoice');
		$recordflag19 = deleterecordcheck($lastslno,'customerid','ssm_onsiteregister');
		$recordflag20 = deleterecordcheck($lastslno,'customerid','ssm_receipts');
		$recordflag21 = deleterecordcheck($lastslno,'customerid','ssm_referenceregister');
		$recordflag22 = deleterecordcheck($lastslno,'customerid','ssm_requirementregister');
		$recordflag23 = deleterecordcheck($lastslno,'customerid','ssm_skyperegister');
		$recordflag24 = deleterecordcheck($lastslno,'customerid','inv_contactreqpending');

		
		if(($recordflag1 == true) && ($recordflag2 == true) && ($recordflag3 == true) && ($recordflag4 == true) && ($recordflag5 == true) && ($recordflag6 == true) && ($recordflag7 == true) && ($recordflag8 == true)  && ($recordflag10 == true) && ($recordflag11 == true) && ($recordflag12 == true) && ($recordflag13 == true) && ($recordflag14 == true) && ($recordflag15 == true) && ($recordflag16 == true) && ($recordflag17 == true) && ($recordflag18 == true) && ($recordflag19 == true) && ($recordflag20 == true) && ($recordflag21 == true) && ($recordflag22 == true) && ($recordflag23 == true)&& ($recordflag24 == true))
		{
			$query2 = "DELETE FROM inv_mas_customer WHERE slno = '".$lastslno."'";
			$result1 = runmysqlquery($query2);
			$query1 = "DELETE FROM inv_contactdetails WHERE customerid = '".$lastslno."'";
			$result = runmysqlquery($query1);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','43','".date('Y-m-d').' '.date('H:i:s')."','".$lastslno."')";
			$eventresult = runmysqlquery($eventquery);
			//echo("2^"."Customer Record Deleted Successfully");
			$responsearray1['errorcode'] = '2';
			$responsearray1['errormessage'] = "Customer Record Deleted Successfully";
			echo(json_encode($responsearray1));

			
		}
		else
		{
			//echo("3^"."Customer Record cannot be deleted as the record referred.");
			//$responsearray1['type'] = $recordflag1.'#'.$recordflag2.'#'.$recordflag3.'#'.$recordflag4.'#'.$recordflag5.'#'.$recordflag6.'#'.$recordflag7.'#'.$recordflag8.'#'.$recordflag10.'#'.$recordflag11.'#'.$recordflag12.'#'.$recordflag13.'#'.$recordflag14.'#'.$recordflag15.'#'.$recordflag16.'#'.$recordflag17.'#'.$recordflag18.'#'.$recordflag19.'#'.$recordflag20.'#'.$recordflag21.'#'.$recordflag22.'#'.$recordflag23.'#'.$recordflag24;
			$responsearray1['errorcode'] = '3';
			$responsearray1['errormessage'] = "Customer Record cannot be deleted as the record referred";
			echo(json_encode($responsearray1));
		}
		
	}
	break;
	
	case 'resetpwd':
	{
		$password = $_POST['password'];
		$lastslno = $_POST['cusid'];
		$query1 = "UPDATE inv_mas_customer SET loginpassword = AES_ENCRYPT('".$password."','imaxpasswordkey'),initialpassword = '".$password."',passwordchanged ='N' WHERE slno = '".$lastslno."'";
		$result = runmysqlquery($query1);
		$query = "select  initialpassword  as initialpassword,passwordchanged from inv_mas_customer  WHERE slno = '".$lastslno."'";
		$result = runmysqlqueryfetch($query);
		$responsearray2 = array();
		$responsearray2['errorcode'] = '1';
		$responsearray2['initialpassword'] = $result['initialpassword'];
		$responsearray2['passwordchanged'] = $result['passwordchanged'];
		//echo('1^'.$result['initialpassword'].'^'.$result['passwordchanged']);
		echo(json_encode($responsearray2));
	}
	break;
	
	case 'searchcustomerlist':
	{
		$databasefield = $_POST['databasefield'];
		$textfield = $_POST['textfield'];
		$state = $_POST['state'];
		$region = $_POST['region'];
		$dealer = $_POST['dealer2'];
		$district = $_POST['district'];
		$productslist = str_replace('\\','',$_POST['productscode']);
		$branch2 = $_POST['branch2'];
		$type2 = $_POST['type2'];
		$category2= $_POST['category2'];
		$regionpiece = ($region == "")?(""):(" AND inv_mas_customer.region = '".$region."' ");
		$state_typepiece = ($state == "")?(""):(" AND inv_mas_district.statecode = '".$state."' ");
		$district_typepiece = ($district == "")?(""):(" AND inv_mas_customer.district = '".$district."' ");
		$dealer_typepiece = ($dealer == "")?(""):(" AND inv_mas_customer.currentdealer = '".$dealer."' ");
		$branchpiece = ($branch2 == "")?(""):(" AND inv_mas_customer.branch = '".$branch2."' ");
		if($type2 == 'Not Selected')
		{
			$typepiece = ($type2 == "")?(""):(" AND inv_mas_customer.type = '' ");
		}
		else
		{
			$typepiece = ($type2 == "")?(""):(" AND inv_mas_customer.type = '".$type2."' ");
		}
		if($category2 == 'Not Selected')
		{
			$categorypiece = ($category2 == "")?(""):(" AND inv_mas_customer.category = '' ");
		}
		else
		{
			$categorypiece = ($category2 == "")?(""):(" AND inv_mas_customer.category = '".$category2."' ");
		}
		switch($databasefield)
		{
			case "slno":
				$customeridlen = strlen($textfield);
				$lastcustomerid = cusidsplit($textfield);
				if($customeridlen == 5)
				{
					$query = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
	LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) 
	as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
	where inv_customerproduct.customerreference IS NOT NULL and (inv_mas_customer.slno = '".$textfield."') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece."  ORDER BY businessname ";
				}
				elseif($customeridlen > 5)
				{
					$query = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
	LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) 
	as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
	where inv_customerproduct.customerreference IS NOT NULL and (inv_mas_customer.customerid like '%".$lastcustomerid."%' ) ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece."  ORDER BY businessname ";
				}
				break;
			case "region":
				$query = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
 LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
 LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_mas_region.category LIKE '".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece."  ORDER BY businessname";
				break;
			case "contactperson": 
				$query = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_contactdetails.contactperson LIKE '%".$textfield."%'  ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece."  ORDER BY businessname";
				break;
			case "phone":
				$query = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
 left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
 LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
 LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_contactdetails.phone LIKE '%".$textfield."%' OR inv_contactdetails.cell LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece."  ORDER BY businessname ";
				break;
			case "place":
				$query = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
 LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
 LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_mas_customer.place LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece."  ORDER BY businessname";
				break;
			case "emailid":
				$query = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
 LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
 LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_contactdetails.emailid LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece."  ORDER BY businessname";
				break;
				case "cardid":
					$query = "SELECT DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname  FROM inv_mas_customer LEFT JOIN (inv_customerproduct LEFT JOIN inv_mas_scratchcard ON inv_customerproduct.cardid = inv_mas_scratchcard.cardid) ON  inv_mas_customer.slno = inv_customerproduct.customerreference 
 left join inv_dealercard on inv_dealercard.cardid = inv_mas_scratchcard.cardid
 left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
 left join inv_mas_product on left(inv_customerproduct.computerid,3) IN (".$productslist.")
 left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
 left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode 
WHERE inv_customerproduct.customerreference IS NOT NULL and  inv_mas_scratchcard.cardid LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece."  ORDER BY businessname";
					break;
			case "scratchnumber":
				$query = "SELECT DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname  FROM inv_mas_customer LEFT JOIN (inv_customerproduct LEFT JOIN inv_mas_scratchcard ON inv_customerproduct.cardid = inv_mas_scratchcard.cardid) ON  inv_mas_customer.slno = inv_customerproduct.customerreference 
 left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
 left join inv_mas_product on left(inv_customerproduct.computerid,3) IN (".$productslist.")
 left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
 left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode 
WHERE inv_customerproduct.customerreference IS NOT NULL and  inv_mas_scratchcard.scratchnumber LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece."  ORDER BY businessname ";
				break;
			case "computerid":
				$query = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
 LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
 LEFT JOIN (select distinct customerreference,computerid from inv_customerproduct  where left(computerid,3)  IN (".$productslist.")) as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_customerproduct.computerid LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece."  ORDER BY businessname";
				break;
			case "softkey":
				$query = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
 LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
 LEFT JOIN (select distinct customerreference,softkey from inv_customerproduct  where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_customerproduct.softkey LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$grouppiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece."  ORDER BY businessname ";
				break;
			case "billno":
				$query = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
 LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
 LEFT JOIN (select distinct customerreference,billnumber from inv_customerproduct  where left(computerid,3)  IN (".$productslist.")) as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_customerproduct.billnumber LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece."  ORDER BY businessname ";
				break;
			
			case 'businessname':
				$query = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
 LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where  inv_customerproduct.customerreference IS NOT NULL and inv_mas_customer.businessname LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece."  ORDER BY businessname ";
				break;
			default:
				$query = "select DISTINCT inv_mas_customer.slno AS slno, inv_mas_customer.businessname AS businessname from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
 LEFT JOIN (select distinct customerreference from inv_customerproduct where left(computerid,3)  IN (".$productslist.")) 
as inv_customerproduct ON inv_mas_customer.slno = inv_customerproduct.customerreference 
where inv_customerproduct.customerreference IS NOT NULL and inv_mas_customer.businessname LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece."  ORDER BY businessname";
				break;
		}
			$result = runmysqlquery($query);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','45','".date('Y-m-d').' '.date('H:i:s')."','Advanced search')";
			$eventresult = runmysqlquery($eventquery);

			$searchcustomerlistarray = array();
			$count = 0;
			while($fetch = mysql_fetch_array($result))
			{
				$searchcustomerlistarray[$count] = $fetch['businessname'].'^'.$fetch['slno'];
				$count++;
				
			}
		echo(json_encode($searchcustomerlistarray));
		//echo($query);
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
		while($fetch = mysql_fetch_array($result))
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
		$query = "SELECT slno,businessname,customerid FROM inv_mas_customer ORDER BY businessname";
		$result = runmysqlquery($query);
		$count = mysql_num_rows($result);
		$responsearray3['count'] = $count;
		echo(json_encode($responsearray3));
	}
	break;
	
	case 'customerdetailstoform':
	{
		$query1 = "SELECT count(*) as count from inv_mas_customer where slno = '".$lastslno."'";
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)
		{
			$query = "SELECT inv_mas_customer.slno, inv_mas_customer.customerid,  
			inv_mas_customer.businessname,inv_mas_customer.oldbusinessname,
			inv_mas_customer.address, inv_mas_customer.place, inv_mas_customer.district,inv_mas_district.statecode as state,
			 inv_mas_customer.pincode, inv_mas_customer.fax, inv_mas_customer.region,inv_mas_customer.branch, 
			 inv_mas_customer.companyclosed, inv_mas_customer.stdcode, inv_mas_customer.website, inv_mas_customer.category, 
			 inv_mas_customer.type, inv_mas_customer.isdealer,inv_mas_customer.remarks, inv_mas_customer.currentdealer, 
			  inv_mas_customer.passwordchanged, inv_mas_customer.disablelogin, inv_mas_customer.corporateorder,
			  inv_mas_customer.createddate,inv_mas_customer.activecustomer, inv_mas_users.fullname, 
			  inv_mas_product.productname, inv_mas_district.districtname as districtname, 
			  inv_mas_state.statename as statename,inv_mas_state.state_gst_code as state_gst, inv_mas_customer.initialpassword as password, 
			  inv_mas_customer.displayinwebsite, inv_mas_customer.promotionalsms,inv_mas_customer.promotionalemail ,inv_mas_customer.gst_no ,inv_mas_customer.sez_enabled
			  FROM inv_mas_customer 
			  LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_mas_customer.firstproduct  
			  LEFT JOIN inv_mas_users ON inv_mas_customer.createdby = inv_mas_users.slno 
			  left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode 
			  left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode 
			  where inv_mas_customer.slno = '".$lastslno."';";
			  
			$fetch = runmysqlqueryfetch($query);
			
			$query1 ="SELECT customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$lastslno."'; ";
			$resultfetch = runmysqlquery($query1);
			$valuecount = 0;
			while($fetchres = mysql_fetch_array($resultfetch))
			{
				if($valuecount > 0)
					$contactarray .= '****';
				
				$selectiontype = $fetchres['selectiontype'];
				$contactperson = $fetchres['contactperson'];
				$phone = $fetchres['phone'];
				$cell = $fetchres['cell'];
				$emailid = $fetchres['emailid'];
				$slno = $fetchres['slno'];
				
				$contactarray .= $selectiontype.'#'.$contactperson.'#'.$phone.'#'.$cell.'#'.$emailid.'#'.$slno;
				$valuecount++;
				$contactvalues .= $contactperson;
				$contactvalues .= appendcomma($contactperson);
				$phoneres .= $phone;
				$phoneres .= appendcomma($phone);
				$cellres .= $cell;
				$cellres .= appendcomma($cell);
				$emailidres .= $emailid;
				$emailidres .= appendcomma($emailid);
				
			}
			$rescontact = trim($contactvalues,',');
			$resphone = trim($phoneres,',');
			$rescell = trim($cellres,',');
			$resemailid = trim($emailidres,',');
			if($fetch['customerid'] == '')
			$customerid = '';
			else
			$customerid = cusidcombine($fetch['customerid']);
			
			// 2011-12 Summary
				$query2 = "select * from 
(select distinct inv_mas_product.group from inv_mas_product where inv_mas_product.group in ('TDS','SPP','STO','SAC','XBRL','GST') and inv_mas_product.year = '".$currentyear."') as groups
left join
(select distinct inv_mas_product.group as bills from inv_invoicenumbers join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode where  right(inv_invoicenumbers.customerid,5) = '".$lastslno."' and inv_mas_product.year = '".$currentyear."' and (inv_invoicenumbers.cancelledby is null)) as bills
on bills.bills = groups.group
left join
(select distinct inv_mas_product.group as pins from inv_dealercard join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode where  inv_dealercard.customerreference = '".$lastslno."' and inv_mas_product.year = '".$currentyear."') as pins
on pins.pins = groups.group
left join
(select distinct inv_mas_product.group as registrations from inv_customerproduct join inv_mas_product on left(inv_customerproduct.computerid,3) = inv_mas_product.productcode where  inv_customerproduct.customerreference = '".$lastslno."' and inv_mas_product.year = '".$currentyear."') as registrations
on registrations.registrations = groups.group order by groups.group desc"; //echo($query2);exit;
			$result2 = runmysqlquery($query2);
			$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="4" class="year-grid-border">';
			$grid .= '<tr bgcolor = "#E2F1F1">';
			$grid .= '<td nowrap = "nowrap" class="year-grid-td-border" align="center" ></td>';
			$grid .= '<td nowrap = "nowrap" class="year-grid-td-border" align="center"><strong>Bill</strong></td>';
			$grid .= '<td nowrap = "nowrap" class="year-grid-td-border" align="center"><strong>PIN</strong></td>';
			$grid .= '<td nowrap = "nowrap" class="year-grid-td-border" align="center"><strong>Regn</strong>
			</td>';
  			$grid .= '</tr>';
			$i_n = 0;
			while($fetch2 = mysql_fetch_array($result2))
			{
				$i_n++;
				$slno++;
				$color;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				
				$bills = ($fetch2['bills'] == '')?'NO':'YES';
				$pins = ($fetch2['pins'] == '')?'NO':'YES';
				$registrations = ($fetch2['registrations'] == '')?'NO':'YES';
				
				$billbgcolor = ($bills == 'YES')?'#c1ddb9':'#FFD9D9';
				$pinsbgcolor = ($pins == 'YES')?'#c1ddb9':'#FFD9D9';
				$registrationsbgcolor = ($registrations == 'YES')?'#c1ddb9':'#FFD9D9';
				
				
				$grid .= '<tr>';
				$grid .= '<td nowrap = "nowrap" class="year-grid-td-border" align="center"  bgcolor = "#E2F1F1"><strong>'.$fetch2['group'].'</strong></td>';
				$grid .= '<td nowrap = "nowrap" class="year-grid-td-border" align="center" bgcolor='.$billbgcolor.'>'.$bills.'</td>';
				$grid .= '<td nowrap = "nowrap" class="year-grid-td-border" align="center"  bgcolor='.$pinsbgcolor.'>'.$pins.'</td>';
				$grid .= '<td nowrap = "nowrap" class="year-grid-td-border" align="center"  bgcolor='.$registrationsbgcolor.'>'.$registrations.'</td>';
				$grid .= '</tr>';
			}
			$grid .= '</table>';
			$responsearray = array();
			$responsearray['cusslno'] = $fetch['slno'];
			$responsearray['customerid'] = $customerid;
			$responsearray['businessname'] = $fetch['businessname'];
			$responsearray['oldbusinessname'] = $fetch['oldbusinessname'];
			$responsearray['address'] = $fetch['address'];
			$responsearray['place'] = $fetch['place'];
			$responsearray['district'] = $fetch['district'];
			$responsearray['state'] = $fetch['state'];
			
			
			$responsearray['gst_no'] = $fetch['gst_no'];
			$responsearray['state_gst'] = $fetch['state_gst'];
			$responsearray['sez_enabled'] = $fetch['sez_enabled'];
			
			$responsearray['pincode'] = $fetch['pincode'];
			$responsearray['region'] = $fetch['region'];
			$responsearray['stdcode'] = $fetch['stdcode'];
			
			$responsearray['website'] = $fetch['website'];
			$responsearray['category'] = $fetch['category'];
			$responsearray['type'] = $fetch['type'];
			$responsearray['remarks'] = $fetch['remarks'];
			$responsearray['dealerbusinessname'] = $fetch['dealerbusinessname'];
			$responsearray['password'] = $fetch['password'];
			$responsearray['passwordchanged'] = strtolower($fetch['passwordchanged']);
			$responsearray['disablelogin'] = $fetch['disablelogin'];
			$responsearray['createddate'] = changedateformatwithtime($fetch['createddate']);
			$responsearray['fullname'] = $fetch['fullname'];
			$responsearray['productname'] = $fetch['productname'];
			$responsearray['p_editcustomercontact'] = $p_editcustomercontact;
			$responsearray['p_registration'] = $p_registration;
			$responsearray['userid'] = $userid;
			$responsearray['corporateorder'] = strtolower($fetch['corporateorder']);
			$responsearray['currentdealer'] = $fetch['currentdealer'];
			$responsearray['p_editcustomerpassword'] = $p_editcustomerpassword;
			$responsearray['fax'] = $fetch['fax'];
			$responsearray['activecustomer'] = $fetch['activecustomer'];
			$responsearray['branch'] = $fetch['branch'];
			$responsearray['companyclosed'] = $fetch['companyclosed'];
			$responsearray['districtname'] = $fetch['districtname'];
			$responsearray['statename'] = $fetch['statename'];
			$responsearray['password'] = $fetch['password'];
			$responsearray['isdealer'] = $fetch['isdealer'];
			$responsearray['contactarray'] = $contactarray;
			$responsearray['rescontact'] = $rescontact;
			$responsearray['resphone'] = $resphone;
			$responsearray['rescell'] = $rescell;
			$responsearray['resemailid'] = $resemailid;
			$responsearray['displayinwebsite'] = $fetch['displayinwebsite'];
			$responsearray['promotionalsms'] = $fetch['promotionalsms'];
			$responsearray['grid'] = $grid;
			echo(json_encode($responsearray));
			
		}
		else
		{
			$responsearray = array();
			$responsearray['cusslno'] = $lastslno;
			$responsearray['customerid'] = '';
			$responsearray['businessname'] = '';
			$responsearray['oldbusinessname'] = '';
			$responsearray['address'] = '';
			$responsearray['place'] = '';
			$responsearray['district'] = '';
			$responsearray['state'] = '';
			
			$responsearray['gst_no'] = '';
			$responsearray['state_gst'] = '';
			$responsearray['sez_enabled'] = '';
			
			$responsearray['pincode'] = '';
			$responsearray['region'] = '';
			$responsearray['stdcode'] ='';
			$responsearray['website'] = '';
			$responsearray['category'] = '';
			$responsearray['type'] = '';
			$responsearray['remarks'] = '';
			$responsearray['dealerbusinessname'] = '';
			$responsearray['password'] = '';
			$responsearray['passwordchanged'] = '';
			$responsearray['disablelogin'] = '';
			$responsearray['createddate'] = '';
			$responsearray['fullname'] = '';
			$responsearray['productname'] = '';
			$responsearray['p_editcustomercontact'] = '';
			$responsearray['p_registration'] = '';
			$responsearray['userid'] = '';
			$responsearray['corporateorder'] = '';
			$responsearray['currentdealer'] = '';
			$responsearray['p_editcustomerpassword'] = '';
			$responsearray['fax'] = '';
			$responsearray['activecustomer'] = '';
			$responsearray['branch'] = '';
			$responsearray['companyclosed'] = '';
			$responsearray['districtname'] = '';
			$responsearray['statename'] = '';
			$responsearray['password'] = '';
			$responsearray['isdealer'] = '';
			$responsearray['contactarray'] = '';
			$responsearray['rescontact'] = '';
			$responsearray['resphone'] = '';
			$responsearray['rescell'] = '';
			$responsearray['resemailid'] = '';
			$responsearray['displayinwebsite'] = '';
			$responsearray['promotionalsms'] = '';
			$responsearray['grid'] = '';
			echo(json_encode($responsearray));
		}
	}
	break;
	
	case 'searchbycustomerid':
	{
		$customerid = $_POST['customerid'];
		$customeridlen = strlen($customerid);
		$lastcustomerid = cusidsplit($customerid);
		$lastslno = substr($customerid, $customeridlen - 5);
		$responsearray4 = array();
		if($customeridlen == 5)
		{
			$query1 = "SELECT count(*) as count from inv_mas_customer where slno = '".$lastcustomerid."'";
			$fetch1 = runmysqlqueryfetch($query1);
			
			if($fetch1['count'] > 0)
			{
					$query = "SELECT inv_mas_customer.slno, inv_mas_customer.customerid, inv_mas_customer.businessname, inv_mas_customer.oldbusinessname,inv_mas_customer.address, inv_mas_customer.place, inv_mas_customer.district,inv_mas_district.statecode as state, inv_mas_customer.pincode, inv_mas_customer.fax, inv_mas_customer.region, inv_mas_customer.companyclosed, inv_mas_customer.branch, inv_mas_customer.stdcode, inv_mas_customer.website, inv_mas_customer.category, inv_mas_customer.type, inv_mas_customer.isdealer,inv_mas_customer.remarks, inv_mas_customer.currentdealer,  inv_mas_customer.passwordchanged, inv_mas_customer.disablelogin, inv_mas_customer.corporateorder, inv_mas_customer.createddate, inv_mas_customer.promotionalsms,inv_mas_customer.promotionalemail,inv_mas_customer.activecustomer,inv_mas_users.fullname, inv_mas_product.productname, inv_mas_district.districtname as districtname,inv_mas_state.statename as statename,inv_mas_state.state_gst_code as state_gst,inv_mas_customer.initialpassword as password,inv_mas_customer.gst_no
				,inv_mas_customer.sez_enabled FROM inv_mas_customer LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_mas_customer.firstproduct  LEFT JOIN  inv_mas_users ON  inv_mas_users.slno = inv_mas_customer.createdby left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode  left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_customer.slno = '".$lastcustomerid."';";
				$fetch = runmysqlqueryfetch($query);
				
			$query1 ="SELECT customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$lastcustomerid."'; ";
			$resultfetch = runmysqlquery($query1);
			$valuecount = 0;
			while($fetchres = mysql_fetch_array($resultfetch))
			{
				if($valuecount > 0)
					$contactarray .= '****';
				$selectiontype = $fetchres['selectiontype'];
				$contactperson = $fetchres['contactperson'];
				$phone = $fetchres['phone'];
				$cell = $fetchres['cell'];
				$emailid = $fetchres['emailid'];
				$slno = $fetchres['slno'];
				
				$contactarray .= $selectiontype.'#'.$contactperson.'#'.$phone.'#'.$cell.'#'.$emailid.'#'.$slno;
				$valuecount++;
				$contactvalues .= $contactperson.',';
				$phoneres .= $phone.',';
				$cellres .= $cell.',';
				$emailidres .= $emailid.',';
			}
			$char1 = str_replace(',,',',',$contactvalues);
			$rescontact = trim($char1,',');
			
			$char2 = str_replace(',,',',',$phoneres);
			$resphone = trim($char2,',');
			
			$char3 = str_replace(',,',',',$cellres);
			$rescell = trim($char3,',');
			
			$char4 = str_replace(',,',',',$emailidres);
			$resemailid = trim($char4,',');
				
			// 2011-12 Summary
				$query2 = "select * from 
(select distinct inv_mas_product.group from inv_mas_product where inv_mas_product.group in ('TDS','SPP','STO','SAC','GST') and inv_mas_product.year = '".$currentyear."') as groups
left join
(select distinct inv_mas_product.group as bills from inv_invoicenumbers join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode where  right(inv_invoicenumbers.customerid,5) = '".$lastslno."' and inv_mas_product.year = '".$currentyear."' and (inv_invoicenumbers.cancelledby is null)) as bills
on bills.bills = groups.group
left join
(select distinct inv_mas_product.group as pins from inv_dealercard join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode where  inv_dealercard.customerreference = '".$lastslno."' and inv_mas_product.year = '".$currentyear."') as pins
on pins.pins = groups.group
left join
(select distinct inv_mas_product.group as registrations from inv_customerproduct join inv_mas_product on left(inv_customerproduct.computerid,3) = inv_mas_product.productcode where  inv_customerproduct.customerreference = '".$lastslno."' and inv_mas_product.year = '".$currentyear."') as registrations
on registrations.registrations = groups.group order by groups.group desc"; 
			$result2 = runmysqlquery($query2);
			$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="4" class="year-grid-border">';
			$grid .= '<tr bgcolor = "#E2F1F1">';
			$grid .= '<td nowrap = "nowrap" class="year-grid-td-border" align="center" ></td>';
			$grid .= '<td nowrap = "nowrap" class="year-grid-td-border" align="center"><strong>Bill</strong></td>';
			$grid .= '<td nowrap = "nowrap" class="year-grid-td-border" align="center"><strong>PIN</strong></td>';
			$grid .= '<td nowrap = "nowrap" class="year-grid-td-border" align="center"><strong>Regn</strong>
			</td>';
  			$grid .= '</tr>';
			$i_n = 0;
			while($fetch2 = mysql_fetch_array($result2))
			{
				$i_n++;
				$slno++;
				$color;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				
				$bills = ($fetch2['bills'] == '')?'NO':'YES';
				$pins = ($fetch2['pins'] == '')?'NO':'YES';
				$registrations = ($fetch2['registrations'] == '')?'NO':'YES';
				
				$billbgcolor = ($bills == 'YES')?'#c1ddb9':'#FFD9D9';
				$pinsbgcolor = ($pins == 'YES')?'#c1ddb9':'#FFD9D9';
				$registrationsbgcolor = ($registrations == 'YES')?'#c1ddb9':'#FFD9D9';
				
				
				$grid .= '<tr>';
				$grid .= '<td nowrap = "nowrap" class="year-grid-td-border" align="center"  bgcolor = "#E2F1F1"><strong>'.$fetch2['group'].'</strong></td>';
				$grid .= '<td nowrap = "nowrap" class="year-grid-td-border" align="center" bgcolor='.$billbgcolor.'>'.$bills.'</td>';
				$grid .= '<td nowrap = "nowrap" class="year-grid-td-border" align="center"  bgcolor='.$pinsbgcolor.'>'.$pins.'</td>';
				$grid .= '<td nowrap = "nowrap" class="year-grid-td-border" align="center"  bgcolor='.$registrationsbgcolor.'>'.$registrations.'</td>';
				$grid .= '</tr>';
			}
			$grid .= '</table>';				
			
			$responsearray4['cusslno'] = $fetch['slno'];
			$responsearray4['customerid'] = cusidcombine($fetch['customerid']);
			$responsearray4['businessname'] = $fetch['businessname'];
			$responsearray4['oldbusinessname'] = $fetch['oldbusinessname'];
			$responsearray4['address'] = $fetch['address'];
			$responsearray4['place'] = $fetch['place'];
			$responsearray4['district'] = $fetch['district'];
			$responsearray4['state'] = $fetch['state'];
			
			$responsearray4['gst_no'] = $fetch['gst_no'];
			$responsearray4['state_gst'] = $fetch['state_gst'];
			$responsearray4['sez_enabled'] = $fetch['sez_enabled'];
			
			
			$responsearray4['pincode'] = $fetch['pincode'];
			$responsearray4['region'] = $fetch['region'];
			$responsearray4['stdcode'] = $fetch['stdcode'];
			$responsearray4['website'] = $fetch['website'];
			$responsearray4['category'] = $fetch['category'];
			$responsearray4['type'] = $fetch['type'];
			$responsearray4['remarks'] = $fetch['remarks'];
			$responsearray4['dealerbusinessname'] = $fetch['dealerbusinessname'];
			$responsearray4['password'] = $fetch['password'];
			$responsearray4['passwordchanged'] = strtolower($fetch['passwordchanged']);
			$responsearray4['disablelogin'] = $fetch['disablelogin'];
			$responsearray4['createddate'] = changedateformatwithtime($fetch['createddate']);
			$responsearray4['fullname'] = $fetch['fullname'];
			$responsearray4['productname'] = $fetch['productname'];
			$responsearray4['p_editcustomercontact'] = $p_editcustomercontact;
			$responsearray4['p_registration'] = $p_registration;
			$responsearray4['userid'] = $userid;
			$responsearray4['corporateorder'] = strtolower($fetch['corporateorder']);
			$responsearray4['currentdealer'] = $fetch['currentdealer'];
			$responsearray4['p_editcustomerpassword'] = $p_editcustomerpassword;
			$responsearray4['fax'] = $fetch['fax'];
			$responsearray4['activecustomer'] = $fetch['activecustomer'];
			$responsearray4['branch'] = $fetch['branch'];
			$responsearray4['companyclosed'] = $fetch['companyclosed'];
			$responsearray4['districtname'] = $fetch['districtname'];
			$responsearray4['statename'] = $fetch['statename'];
			$responsearray4['password'] = $fetch['password'];
			$responsearray4['isdealer'] = $fetch['isdealer'];
			$responsearray4['contactarray'] = $contactarray;
			$responsearray4['rescontact'] = $rescontact;
			$responsearray4['resphone'] = $resphone;
			$responsearray4['rescell'] = $rescell;
			$responsearray4['resemailid'] = $resemailid;
			$responsearray4['displayinwebsite'] = $fetch['displayinwebsite'];
			$responsearray4['promotionalsms'] = $fetch['promotionalsms'];
			$responsearray4['grid'] = $grid;
			echo(json_encode($responsearray4));	
			
			}
			else
			{
			  $responsearray4['cusslno'] = $lastslno;
			  $responsearray4['customerid'] = '';
			  $responsearray4['businessname'] = '';
			  $responsearray4['oldbusinessname'] = '';
			  $responsearray4['address'] = '';
			  $responsearray4['place'] = '';
			  $responsearray4['district'] = '';
			  $responsearray4['state'] = '';
			  
			  
			  $responsearray4['gst_no'] = '';
			  $responsearray4['state_gst'] = '';
			  $responsearray4['sez_enabled'] = '';
			  
			  
			  
			  $responsearray4['pincode'] = '';
			  $responsearray4['region'] = '';
			  $responsearray4['stdcode'] = '';
			  $responsearray4['website'] = '';
			  $responsearray4['category'] = '';
			  $responsearray4['type'] = '';
			  $responsearray4['remarks'] = '';
			  $responsearray4['dealerbusinessname'] = '';
			  $responsearray4['password'] = '';
			  $responsearray4['passwordchanged'] = '';
			  $responsearray4['disablelogin'] = '';
			  $responsearray4['createddate'] = '';
			  $responsearray4['fullname'] = '';
			  $responsearray4['productname'] = '';
			  $responsearray4['p_editcustomercontact'] = '';
			  $responsearray4['p_registration'] = '';
			  $responsearray4['userid'] = '';
			  $responsearray4['corporateorder'] = '';
			  $responsearray4['currentdealer'] = '';
			  $responsearray4['p_editcustomerpassword'] = '';
			  $responsearray4['fax'] = '';
			  $responsearray4['activecustomer'] = '';
			  $responsearray4['branch'] = '';
			  $responsearray4['companyclosed'] = '';
			  $responsearray4['districtname'] = '';
			  $responsearray4['statename'] = '';
			  $responsearray4['password'] = '';
			  $responsearray4['isdealer'] = '';
			  $responsearray4['contactarray'] = '';
			  $responsearray4['rescontact'] = '';
			  $responsearray4['resphone'] = '';
			  $responsearray4['rescell'] = '';
			  $responsearray4['resemailid'] = '';
			  $responsearray4['displayinwebsite'] = '';
			  $responsearray4['promotionalsms'] = '';
			  $responsearray4['grid'] = '';
			  echo(json_encode($responsearray4));	
			}
		}
		elseif($customeridlen > 5)
		{
			$query1 = "SELECT count(*) as count from inv_mas_customer where inv_mas_customer.customerid   = '".$lastcustomerid."'";
			$fetch1 = runmysqlqueryfetch($query1);
			if($fetch1['count'] > 0)
			{
				$query = "SELECT inv_mas_customer.slno, inv_mas_customer.customerid, inv_mas_customer.businessname,  inv_mas_customer.oldbusinessname,inv_mas_customer.address, inv_mas_customer.place, inv_mas_customer.district,inv_mas_district.statecode as state, inv_mas_customer.pincode, inv_mas_customer.fax, inv_mas_customer.region, inv_mas_customer.branch, inv_mas_customer.companyclosed, inv_mas_customer.stdcode, inv_mas_customer.website, inv_mas_customer.category, inv_mas_customer.type, inv_mas_customer.isdealer,inv_mas_customer.remarks, inv_mas_customer.currentdealer, inv_mas_customer.passwordchanged, inv_mas_customer.disablelogin, inv_mas_customer.corporateorder, inv_mas_customer.createddate, inv_mas_customer.promotionalsms,inv_mas_customer.promotionalemail,inv_mas_customer.activecustomer,inv_mas_users.fullname, inv_mas_product.productname,inv_mas_district.districtname as districtname,inv_mas_state.statename as statename,inv_mas_state.state_gst_code as state_gst,inv_mas_customer.initialpassword as password,inv_mas_customer.gst_no
				,inv_mas_customer.sez_enabled FROM inv_mas_customer LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_mas_customer.firstproduct  LEFT JOIN  inv_mas_users ON  inv_mas_users.slno = inv_mas_customer.createdby left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_customer.customerid = '".$lastcustomerid."'";
				$fetch = runmysqlqueryfetch($query);	
				
			$query1 ="SELECT customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$fetch['slno']."'; ";
			$resultfetch = runmysqlquery($query1);
			$valuecount = 0;
			while($fetchres = mysql_fetch_array($resultfetch))
			{
				if($valuecount > 0)
					$contactarray .= '****';
				$selectiontype = $fetchres['selectiontype'];
				$contactperson = $fetchres['contactperson'];
				$phone = $fetchres['phone'];
				$cell = $fetchres['cell'];
				$emailid = $fetchres['emailid'];
				$slno = $fetchres['slno'];
				
				$contactarray .= $selectiontype.'#'.$contactperson.'#'.$phone.'#'.$cell.'#'.$emailid.'#'.$slno;
				$valuecount++;
				$contactvalues .= $contactperson.',';
				$phoneres .= $phone.',';
				$cellres .= $cell.',';
				$emailidres .= $emailid.',';
				
			}	
			
			$char1 = str_replace(',,',',',$contactvalues);
			$rescontact = trim($char1,',');
			
			$char2 = str_replace(',,',',',$phoneres);
			$resphone = trim($char2,',');
			
			$char3 = str_replace(',,',',',$cellres);
			$rescell = trim($char3,',');
			
			$char4 = str_replace(',,',',',$emailidres);
				
				
			// 2011-12 Summary
			
				$query2 = "select * from 
(select distinct inv_mas_product.group from inv_mas_product where inv_mas_product.group in ('TDS','SPP','STO','SAC','GST') and inv_mas_product.year = '".$currentyear."') as groups
left join
(select distinct inv_mas_product.group as bills from inv_invoicenumbers join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode where  right(inv_invoicenumbers.customerid,5) = '".$lastslno."' and inv_mas_product.year = '".$currentyear."' and (inv_invoicenumbers.cancelledby is null)) as bills
on bills.bills = groups.group
left join
(select distinct inv_mas_product.group as pins from inv_dealercard join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode where  inv_dealercard.customerreference = '".$lastslno."' and inv_mas_product.year = '".$currentyear."') as pins
on pins.pins = groups.group
left join
(select distinct inv_mas_product.group as registrations from inv_customerproduct join inv_mas_product on left(inv_customerproduct.computerid,3) = inv_mas_product.productcode where  inv_customerproduct.customerreference = '".$lastslno."' and inv_mas_product.year = '".$currentyear."') as registrations
on registrations.registrations = groups.group order by groups.group desc"; 
			$result2 = runmysqlquery($query2);
			$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="4" class="year-grid-border">';
			$grid .= '<tr bgcolor = "#E2F1F1">';
			$grid .= '<td nowrap = "nowrap" class="year-grid-td-border" align="center" ></td>';
			$grid .= '<td nowrap = "nowrap" class="year-grid-td-border" align="center"><strong>Bill</strong></td>';
			$grid .= '<td nowrap = "nowrap" class="year-grid-td-border" align="center"><strong>PIN</strong></td>';
			$grid .= '<td nowrap = "nowrap" class="year-grid-td-border" align="center"><strong>Regn</strong>
			</td>';
  			$grid .= '</tr>';
			$i_n = 0;
			while($fetch2 = mysql_fetch_array($result2))
			{
				$i_n++;
				$slno++;
				$color;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				
				$bills = ($fetch2['bills'] == '')?'NO':'YES';
				$pins = ($fetch2['pins'] == '')?'NO':'YES';
				$registrations = ($fetch2['registrations'] == '')?'NO':'YES';
				
				$billbgcolor = ($bills == 'YES')?'#c1ddb9':'#FFD9D9';
				$pinsbgcolor = ($pins == 'YES')?'#c1ddb9':'#FFD9D9';
				$registrationsbgcolor = ($registrations == 'YES')?'#c1ddb9':'#FFD9D9';
				
				
				$grid .= '<tr>';
				$grid .= '<td nowrap = "nowrap" class="year-grid-td-border" align="center"  bgcolor = "#E2F1F1"><strong>'.$fetch2['group'].'</strong></td>';
				$grid .= '<td nowrap = "nowrap" class="year-grid-td-border" align="center" bgcolor='.$billbgcolor.'>'.$bills.'</td>';
				$grid .= '<td nowrap = "nowrap" class="year-grid-td-border" align="center"  bgcolor='.$pinsbgcolor.'>'.$pins.'</td>';
				$grid .= '<td nowrap = "nowrap" class="year-grid-td-border" align="center"  bgcolor='.$registrationsbgcolor.'>'.$registrations.'</td>';
				$grid .= '</tr>';
			}
			$grid .= '</table>';					
			
						//echo($fetch['slno'].'^'.cusidcombine($fetch['customerid']).'^'.$fetch['businessname'].'^'.$fetch['address'].'^'.$fetch['place'].'^'.$fetch['district'].'^'.$fetch['state'].'^'.$fetch['pincode'].'^'.$fetch['region'].'^'.$fetch['stdcode'].'^'.$fetch['website'].'^'.$fetch['category'].'^'.$fetch['type'].'^'.$fetch['remarks'].'^'.''.'^'.$fetch['dealerbusinessname'].'^'.''.'^'.$fetch['password'].'^'.strtolower($fetch['passwordchanged']).'^'.$fetch['disablelogin'].'^'.changedateformatwithtime($fetch['createddate']).'^'.$fetch['fullname'].'^'.$fetch['productname']);
		//	echo('^'.$p_editcustomercontact.'^'.$p_registration.'^'.$userid.'^'.strtolower($fetch['corporateorder']).'^'.$fetch['currentdealer'].'^'.$p_editcustomerpassword.'^'.$fetch['fax'].'^'.$fetch['activecustomer'].'^'.$fetch['branch'].'^'.$fetch['companyclosed'].'^'.$fetch['districtname'].'^'.$fetch['statename'].'^'.$fetch['password'].'^'.$fetch['isdealer'].'^'.$contactarray.'^'.$rescontact.'^'.$resphone.'^'.$rescell.'^'.$resemailid.'^'.$fetch['promotionalsms'].'^'.$fetch['promotionalemail'].'^'.$grid);
			
			$responsearray4['cusslno'] = $fetch['slno'];
			$responsearray4['customerid'] = cusidcombine($fetch['customerid']);
			$responsearray4['businessname'] = $fetch['businessname'];
			$responsearray4['oldbusinessname'] = $fetch['oldbusinessname'];
			$responsearray4['address'] = $fetch['address'];
			$responsearray4['place'] = $fetch['place'];
			$responsearray4['district'] = $fetch['district'];
			$responsearray4['state'] = $fetch['state'];
			
			
			$responsearray4['gst_no'] = $fetch['gst_no'];
			$responsearray4['state_gst'] = $fetch['state_gst'];
			$responsearray4['sez_enabled'] = $fetch['sez_enabled'];
			
			
			$responsearray4['pincode'] = $fetch['pincode'];
			$responsearray4['region'] = $fetch['region'];
			$responsearray4['stdcode'] = $fetch['stdcode'];
			$responsearray4['website'] = $fetch['website'];
			$responsearray4['category'] = $fetch['category'];
			$responsearray4['type'] = $fetch['type'];
			$responsearray4['remarks'] = $fetch['remarks'];
			$responsearray4['dealerbusinessname'] = $fetch['dealerbusinessname'];
			$responsearray4['password'] = $fetch['password'];
			$responsearray4['passwordchanged'] = strtolower($fetch['passwordchanged']);
			$responsearray4['disablelogin'] = $fetch['disablelogin'];
			$responsearray4['createddate'] = changedateformatwithtime($fetch['createddate']);
			$responsearray4['fullname'] = $fetch['fullname'];
			$responsearray4['productname'] = $fetch['productname'];
			$responsearray4['p_editcustomercontact'] = $p_editcustomercontact;
			$responsearray4['p_registration'] = $p_registration;
			$responsearray4['userid'] = $userid;
			$responsearray4['corporateorder'] = strtolower($fetch['corporateorder']);
			$responsearray4['currentdealer'] = $fetch['currentdealer'];
			$responsearray4['p_editcustomerpassword'] = $p_editcustomerpassword;
			$responsearray4['fax'] = $fetch['fax'];
			$responsearray4['activecustomer'] = $fetch['activecustomer'];
			$responsearray4['branch'] = $fetch['branch'];
			$responsearray4['companyclosed'] = $fetch['companyclosed'];
			$responsearray4['districtname'] = $fetch['districtname'];
			$responsearray4['statename'] = $fetch['statename'];
			$responsearray4['password'] = $fetch['password'];
			$responsearray4['isdealer'] = $fetch['isdealer'];
			$responsearray4['contactarray'] = $contactarray;
			$responsearray4['rescontact'] = $rescontact;
			$responsearray4['resphone'] = $resphone;
			$responsearray4['rescell'] = $rescell;
			$responsearray4['resemailid'] = $resemailid;
			$responsearray4['promotionalsms'] = $fetch['promotionalsms'];
			$responsearray4['grid'] = $grid;
			echo(json_encode($responsearray4));	
			

				
				
			}
			else
			{
			  $responsearray4['cusslno'] = $lastslno;
			  $responsearray4['customerid'] = '';
			  $responsearray4['businessname'] = '';
			  $responsearray4['oldbusinessname'] = '';
			  $responsearray4['address'] = '';
			  $responsearray4['place'] = '';
			  $responsearray4['district'] = '';
			  $responsearray4['state'] = '';
			  
			  $responsearray4['gst_no'] = '';
			  $responsearray4['state_gst'] = '';
			  $responsearray4['sez_enabled'] = '';
			  
			  
			  $responsearray4['pincode'] = '';
			  $responsearray4['region'] = '';
			  $responsearray4['stdcode'] = '';
			  $responsearray4['website'] = '';
			  $responsearray4['category'] = '';
			  $responsearray4['type'] = '';
			  $responsearray4['remarks'] = '';
			  $responsearray4['dealerbusinessname'] = '';
			  $responsearray4['password'] = '';
			  $responsearray4['passwordchanged'] = '';
			  $responsearray4['disablelogin'] = '';
			  $responsearray4['createddate'] = '';
			  $responsearray4['fullname'] = '';
			  $responsearray4['productname'] = '';
			  $responsearray4['p_editcustomercontact'] = '';
			  $responsearray4['p_registration'] = '';
			  $responsearray4['userid'] = '';
			  $responsearray4['corporateorder'] = '';
			  $responsearray4['currentdealer'] = '';
			  $responsearray4['p_editcustomerpassword'] = '';
			  $responsearray4['fax'] = '';
			  $responsearray4['activecustomer'] = '';
			  $responsearray4['branch'] = '';
			  $responsearray4['companyclosed'] = '';
			  $responsearray4['districtname'] = '';
			  $responsearray4['statename'] = '';
			  $responsearray4['password'] = '';
			  $responsearray4['isdealer'] = '';
			  $responsearray4['contactarray'] = '';
			  $responsearray4['rescontact'] = '';
			  $responsearray4['resphone'] = '';
			  $responsearray4['rescell'] = '';
			  $responsearray4['resemailid'] = '';
			  $responsearray4['displayinwebsite'] = '';
			  $responsearray4['promotionalsms'] = '';
			  $responsearray4['grid'] = '';
			  echo(json_encode($responsearray4));
			}
		}
		else
		{
			  $responsearray4['cusslno'] = $lastslno;
			  $responsearray4['customerid'] = '';
			  $responsearray4['businessname'] = '';
			  $responsearray4['oldbusinessname'] = '';
			  $responsearray4['address'] = '';
			  $responsearray4['place'] = '';
			  $responsearray4['district'] = '';
			  $responsearray4['state'] = '';
			   $responsearray4['gst_no'] = '';
			  $responsearray4['pincode'] = '';
			  $responsearray4['region'] = '';
			  $responsearray4['stdcode'] = '';
			  $responsearray4['website'] = '';
			  $responsearray4['category'] = '';
			  $responsearray4['type'] = '';
			  $responsearray4['remarks'] = '';
			  $responsearray4['dealerbusinessname'] = '';
			  $responsearray4['password'] = '';
			  $responsearray4['passwordchanged'] = '';
			  $responsearray4['disablelogin'] = '';
			  $responsearray4['createddate'] = '';
			  $responsearray4['fullname'] = '';
			  $responsearray4['productname'] = '';
			  $responsearray4['p_editcustomercontact'] = '';
			  $responsearray4['p_registration'] = '';
			  $responsearray4['userid'] = '';
			  $responsearray4['corporateorder'] = '';
			  $responsearray4['currentdealer'] = '';
			  $responsearray4['p_editcustomerpassword'] = '';
			  $responsearray4['fax'] = '';
			  $responsearray4['activecustomer'] = '';
			  $responsearray4['branch'] = '';
			  $responsearray4['companyclosed'] = '';
			  $responsearray4['districtname'] = '';
			  $responsearray4['statename'] = '';
			  $responsearray4['password'] = '';
			  $responsearray4['isdealer'] = '';
			  $responsearray4['contactarray'] = '';
			  $responsearray4['rescontact'] = '';
			  $responsearray4['resphone'] = '';
			  $responsearray4['rescell'] = '';
			  $responsearray4['resemailid'] = '';
			  $responsearray4['displayinwebsite'] = '';
			  $responsearray4['promotionalsms'] = '';
			  $responsearray4['grid'] = '';
			  echo(json_encode($responsearray4));
		}
	}
	break;
	
	case 'customerregistration':
	{
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$lastslno = $_POST['lastslno'];
		$resultcount = "SELECT inv_mas_product.productname as productname FROM inv_customerproduct left join inv_mas_product on left(inv_customerproduct.computerid, 3) = inv_mas_product.productcode left join inv_mas_users on inv_customerproduct.generatedby = inv_mas_users.slno left join inv_mas_dealer on inv_customerproduct.dealerid = inv_mas_dealer.slno  where inv_customerproduct.AUTOREGISTRATIONYN = 'N' and customerreference = '".$lastslno."' order by `date`  desc,`time` desc ; ";
		$resultfetch = runmysqlquery($resultcount);
		$fetchresultcount = mysql_num_rows($resultfetch);
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
			$startlimit = $slno ;
			$slno = $slno;
		}
		$query = "SELECT inv_mas_product.productname as productname,getPINNo(inv_customerproduct.cardid) AS cardid, 
inv_customerproduct.computerid AS computerid,inv_customerproduct.softkey AS softkey,inv_customerproduct.date AS regdate, 
inv_customerproduct.time AS regtime, inv_mas_users.fullname AS generatedby, inv_mas_dealer.businessname AS businessname, 
inv_customerproduct.billnumber as Billnum,inv_customerproduct.billamount as billamount,inv_customerproduct.remarks as remarks 
FROM inv_customerproduct left join inv_mas_product on left(inv_customerproduct.computerid, 3) = inv_mas_product.productcode 
left join inv_mas_users on inv_customerproduct.generatedby = inv_mas_users.slno 
left join inv_mas_dealer on inv_customerproduct.dealerid = inv_mas_dealer.slno  where inv_customerproduct.AUTOREGISTRATIONYN = 'N' and customerreference = '".$lastslno."' order by `date`  desc,`time` desc LIMIT ".$startlimit.",".$limit."; ";

		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Pin Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Computer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Soft Key</td><td nowrap = "nowrap" class="td-border-grid" align="left">Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Time</td><td nowrap = "nowrap" class="td-border-grid" align="left">Generatd By</td><td nowrap = "nowrap" class="td-border-grid" align="left">Dealer</td><td nowrap = "nowrap" class="td-border-grid" align="left">Bill No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Bill Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td></tr>';
		}
		
		$i_n = 0;
		while($fetch = mysql_fetch_row($result))
		{
			$i_n++;
			$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow1" bgcolor='.$color.'>';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slno."</td>";
			for($i = 0; $i < count($fetch); $i++)
			{
				
				if($i == 4)
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch[$i])."</td>";
				else if($i == 5)
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changetimeformat($fetch[$i])."</td>";
				else 
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch[$i]."</td>";
				
			}
			$grid .= "</tr>";
		}
		$grid .= "</table>";

		$fetchcount = mysql_num_rows($result);
		if($slno >= $fetchresultcount)
		$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
		$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="getmorecustomerregistration(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'more\');" style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmorecustomerregistration(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'all\');" class ="resendtext1" style="cursor:pointer"><font color= "#000000">(Show All Records)</font></a></div></td></tr></table>';
		
	
		echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid;
	}
	break;
	
	
	case 'customerautoregistration':
	{
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$lastslno = $_POST['lastslno'];
		
		$resultcount = "SELECT inv_mas_product.productname as productname 
		FROM inv_customerproduct 
		left join inv_mas_product on left(inv_customerproduct.computerid, 3) = inv_mas_product.productcode 
		left join inv_mas_users on inv_customerproduct.generatedby = inv_mas_users.slno 
		left join inv_mas_dealer on inv_customerproduct.dealerid = inv_mas_dealer.slno 
		where inv_customerproduct.AUTOREGISTRATIONYN = 'Y' and customerreference = '".$lastslno."' order by `date`  desc,`time` desc ; ";
		$resultfetch = runmysqlquery($resultcount);
		$fetchresultcount = mysql_num_rows($resultfetch);
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
			$startlimit = $slno ;
			$slno = $slno;
		}
		/*$query = "SELECT inv_mas_product.productname as productname,getPINNo(inv_customerproduct.cardid) AS cardid, 
inv_customerproduct.date AS regdate, inv_customerproduct.time AS regtime, inv_mas_dealer.businessname AS businessname, if(inv_customerproduct.ACTIVELICENSE=1,'Active','Deactive') as activelicense,inv_customerproduct.remarks as remarks 
FROM inv_customerproduct left join inv_mas_product on left(inv_customerproduct.computerid, 3) = inv_mas_product.productcode 
left join inv_mas_users on inv_customerproduct.generatedby = inv_mas_users.slno 
left join inv_mas_dealer on inv_customerproduct.dealerid = inv_mas_dealer.slno where inv_customerproduct.AUTOREGISTRATIONYN = 'Y' and customerreference = '".$lastslno."' order by `date`  desc,`time` desc LIMIT ".$startlimit.",".$limit."; ";
*/
		$query = "SELECT inv_mas_product.productname as productname,getPINNo(inv_customerproduct.cardid) AS cardid,
		inv_mas_dealer.businessname AS businessname, if(inv_customerproduct.ACTIVELICENSE=1,'Active','Deactive') as activelicense,
		inv_customerproduct.remarks as remarks FROM inv_customerproduct 
		left join inv_mas_product on left(inv_customerproduct.computerid, 3) = inv_mas_product.productcode 
		left join inv_mas_users on inv_customerproduct.generatedby = inv_mas_users.slno 
		left join inv_mas_dealer on inv_customerproduct.dealerid = inv_mas_dealer.slno 
		where inv_customerproduct.AUTOREGISTRATIONYN = 'Y' and customerreference = '".$lastslno."' order by `date`  desc,`time` desc LIMIT ".$startlimit.",".$limit."; ";

		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header">
		<td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Pin Number</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Dealer</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Active/Deactive</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Details</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td>
		</tr>';
		}
		
		$i_n = 0;
		while($fetch = mysql_fetch_array($result))
		{
			$i_n++;
			$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			
			$PIN = $fetch['cardid'];
			$remarks = $fetch['remarks'];
			
			## Calling Function surrender($PIN,$lastslno)##
			$result07 = surrender($PIN,$lastslno);
			##total count for active and surrender list##
			$totalcount = $result07[0] + $result07[2];
			//echo "result07[0] ". $result07[0]." result07[2] ". $result07[2] ."<br /> TOTAL ".$totalcount ;
			## Checking Usage Type from Dealer card table##
			if($result07[1]=='singleuser'){$UT = '(S)';}
			elseif($result07[1]=='multiuser'){$UT = '(M)';}
			elseif($result07[1]=='additionallicense'){$UT = '(AL)';}
			
			
			
			$grid .= '<tr class="gridrow1" bgcolor='.$color.'>';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slno."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['productname']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'><a href'#' Onclick = 'displaypindetails(\"$PIN\");' class='resendtext' style='cursor:pointer;'>".$fetch['cardid']." ".$UT." ".$regtype."</a></td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['businessname']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['activelicense']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'><a href'#' class='resendtext' Onclick = 
			'displaysurrender(\"$PIN\",\"$lastslno\");' style='cursor:pointer;'>more (".$totalcount.")</a></td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['remarks']."</td>";
			$grid .= "</tr>";
			
		}
		/*while($fetch = mysql_fetch_row($result))
		{
			$i_n++;
			$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow1" bgcolor='.$color.'>';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slno."</td>";
			for($i = 0; $i < count($fetch); $i++)
			{
				$PIN = $fetch[1];
				$remarks = $fetch[4];
				## Calling Function surrender($PIN,$lastslno)##
				$result07 = surrender($PIN,$lastslno);
				##total count for active and surrender list##
				$totalcount = $result07[0] + $result07[2];
				//echo "result07[0] ". $result07[0]." result07[2] ". $result07[2] ."<br /> TOTAL ".$totalcount ;
				## Checking Usage Type from Dealer card table##
				if($result07[1]=='singleuser'){$UT = '(S)';}
				elseif($result07[1]=='multiuser'){$UT = '(M)';}
				elseif($result07[1]=='additionallicense'){$UT = '(AL)';}
				
				$fetch[5]= "<a href'#' class='resendtext' Onclick = 'displaysurrender(\"$PIN\",\"$lastslno\");' style='cursor:pointer;'>more (".$totalcount.")</a>";
				
					if($i == 1)
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'><a href'#' Onclick = 'displaypindetails(\"$PIN\");' class='resendtext' style='cursor:pointer;'>".$fetch[1]." ".$UT."</a></td>";		
					else if($i == 4)
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch[5]."</td>";
					else if($i == 5)
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$remarks."</td>";
				else
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch[$i]."</td>";
			}
			$grid .= "</tr>";
		}*/
		$grid .= "</table>";

		$fetchcount = mysql_num_rows($result);
		if($slno >= $fetchresultcount)
		$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
		$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="getmorecustomerautoregistration(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'more\');" style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmorecustomerautoregistration(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'all\');" class ="resendtext1" style="cursor:pointer"><font color= "#000000">(Show All Records)</font></a></div></td></tr></table>';
		
		##Added By Bhavesh Patel 08.07.13 for Force Surrender details##
		$selectforce = forcesurrender($lastslno);
		echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$selectforce;
	}
	break;

	case 'scratchdetailstoform':
	{
		$responsearray5 = array();
		$cardid = $_POST['cardid'];
		$query = "SELECT distinct inv_dealercard.cardid , inv_mas_scratchcard.scratchnumber, 
		inv_mas_scratchcard.blocked,inv_mas_scratchcard.cancelled,inv_mas_dealer.businessname as attachedto, 
		inv_mas_dealer.slno as dealerid, inv_mas_product.productcode, inv_mas_product.productname, 
		inv_dealercard.purchasetype, inv_dealercard.usagetype, inv_dealercard.date as attachdate, 
		inv_customerproduct.date as registereddate, inv_mas_customer.businessname as registeredto,
		inv_dealercard.scheme,inv_mas_scheme.schemename as schemename 
		from inv_dealercard 
		left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid 
		left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno 
		left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode 
		left join (select cardid, customerreference, min(`date`) as `date` from inv_customerproduct GROUP BY cardid) 
		AS inv_customerproduct on inv_dealercard.cardid = inv_customerproduct.cardid 
		left join inv_mas_customer on  inv_customerproduct.customerreference = inv_mas_customer.slno 
		left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme 
		where inv_dealercard.cardid = '".$cardid."';";
		
		$fetch = runmysqlqueryfetch($query);
		$attcheddate = substr($fetch['attachdate'] ,0,10);
		$registereddate =$fetch['registereddate'];
		if($registereddate != '')
			$registereddate = changedateformat($registereddate);
			
			
		if($fetch['blocked'] == 'yes')
		$cardstatus = 'Blocked';
		else if($fetch['cancelled'] == 'yes')
		$cardstatus = 'Cancelled';
		else
		{
		$cardstatus = 'Active';
		}
		$responsearray5['errorcode'] = '1';
		$responsearray5['cardid'] = $fetch['cardid'];
		$responsearray5['scratchnumber'] = $fetch['scratchnumber'];
		$responsearray5['purchasetype'] = $fetch['purchasetype'];
		$responsearray5['usagetype'] = $fetch['usagetype'];
		$responsearray5['attachedto'] = $fetch['attachedto'];
		$responsearray5['dealerid'] = $fetch['dealerid'];
		$responsearray5['attcheddate'] = changedateformat($attcheddate);
		$responsearray5['registereddate'] = $registereddate;
		$responsearray5['registeredto'] = $fetch['registeredto'];
		$responsearray5['cardstatus'] = $cardstatus;
		$responsearray5['schemename'] = $fetch['schemename'];
		$responsearray5['productname'] = $fetch['productname'];
		$responsearray5['productcode'] = $fetch['productcode'];
		

		echo(json_encode($responsearray5));
	}
	break;
	
	case 'generatesoftkey':
	{
		$errorfound = array();
		$registrationtype = $_POST['registrationtype'];
		switch($registrationtype)
		{
			case 'newlicence':
			{
				$customerid = $_POST['customerid'];
				$cardid = $_POST['scratchnumber'];
				$delaerrep = $_POST['delaerrep']; /* Dealer ID*/
				$productcode = $_POST['productcode'];
				//$searchdelaerrep = $_POST['searchdelaerrep'];/* Dealer Name*/
				$productname = $_POST['productname'];
				$computerid = $_POST['computerid'];
				$billno = $_POST['billno'];
				$billamount = $_POST['billamount'];
				$regremarks = $_POST['regremarks'];
				$usagetype = $_POST['usagetype'];
				$purchasetype = $_POST['purchasetype'];
				$date = datetimelocal('d-m-Y');
				$systemip = '';
				if($usagetype == 'multiuser')
					$usagetypecode = '09';
				elseif($usagetype == 'singleuser')
					$usagetypecode = '00';
				elseif($usagetype == 'additionallicense')
					$usagetypecode = '00';

				$computeridedition = substr($computerid, 0, 3);
				$computeridusagetype = substr($computerid, 3, 2);
				$computeridformat = substr($computerid, 5, 1);
				$computeridlength = strlen($computerid);
				if($computeridlength <> 15)
				{ echo(json_encode("2^"."Computer Id format is not valid.")); }
				elseif($computeridedition <> $productcode)
				{ echo(json_encode("2^"."Computer Id is not matching with the product you have selected.")); }
				elseif($computeridusagetype <> $usagetypecode)
				{ echo(json_encode("2^"."Computer Id is not matching with the purchase type.")); }
				elseif($computeridformat <> '-')
				{ echo(json_encode("2^"."Computer Id format is not valid.")); }
				
				else
				{
					$errorfound = "";
					//Card Number present in Database
					if($errorfound = "")
					{
						$query ="select * from inv_mas_scratchcard where cardid = '".$cardid."' and blocked = 'yes'";
						$result = runmysqlquery($query);
						if(mysql_num_rows($result) == 0)
						$errorfound = '2 ^Pin is blocked';
					}
					
					if($errorfound = "")
					{
						$query ="select * from inv_mas_scratchcard where cardid = '".$cardid."' and attached = 'yes' and blocked = 'no'";
						$result = runmysqlquery($query);
						if(mysql_num_rows($result) == 0)
						$errorfound = '2 ^Invalid pin';
					}
								
					if($errorfound == "")
					{
						//Card No is not registered
						$query ="select * from inv_mas_scratchcard where cardid = '".$cardid."' and registered = 'no'";
						$result = runmysqlquery($query);
						if(mysql_num_rows($result) == 0)
						$errorfound = '2^ This pin is already registered';
					}
					if($errorfound == "")
					{
						//Product code is the same, attached with Card
						$query ="select inv_mas_scratchcard.scratchnumber, inv_dealercard.dealerid, inv_dealercard.scheme as cardscheme from inv_mas_scratchcard left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where inv_mas_scratchcard.cardid = '".$cardid."' and inv_dealercard.productcode = '".$productcode."'";
						$result = runmysqlquery($query);
						if(mysql_num_rows($result) == 0)
						$errorfound = '2^ This Card belongs to a different Product.';
						//Read the card ID, dealer ID
						else
						{
							$fetch = mysql_fetch_array($result);
							$scratchnumber = $fetch['scratchnumber'];
							$cardscheme = $fetch['cardscheme'];
						}
					}
					if($errorfound == "")
					{
						//Usage type is the same, attached with Card
						$query ="select inv_mas_scratchcard.cardid as cradid from inv_mas_scratchcard left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where inv_mas_scratchcard.cardid = '".$cardid."' and inv_dealercard.productcode = '".$productcode."'";
						$result = runmysqlqueryfetch($query);
						if($computeridusagetype == '00')
						{
							$query ="select * from inv_mas_scratchcard left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where inv_mas_scratchcard.cardid = '".$cardid."' and (inv_dealercard.usagetype = 'singleuser' OR inv_dealercard.usagetype = 'additionallicense')";
							$result = runmysqlquery($query);
							if(mysql_num_rows($result) == 0)
							$errorfound = '2^ This Card belongs to a Multiuser.';
						}
						elseif($computeridusagetype == '09')
						{
							$query ="select * from inv_mas_scratchcard left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where inv_mas_scratchcard.cardid = '".$cardid."' and (inv_dealercard.usagetype = 'multiuser')";
							$result = runmysqlquery($query);
							if(mysql_num_rows($result) == 0)
							$errorfound = '2^ This Card belongs to Single User or Additonal License.';
						}

						else
							$errorfound = '2^ The usage type of computer ID is invalid.'.$computeridusagetype;
					}
					if($errorfound == "")
					{
						$query = "SELECT `type` as prdtype,productname as productname FROM inv_mas_product WHERE productcode = '".$productcode."'";
						$fetch = runmysqlqueryfetch($query);
						$prdtype = $fetch['prdtype'];
						$productname = $fetch['productname'];
						$softkey  = generateserial($computerid, $prdtype);
						//$softkey  = generatesoftkeydummy($computerid, $prdtype);
						$query = "UPDATE inv_mas_scratchcard SET registered = 'yes', blocked = 'no', online = 'no', cancelled = 'no' WHERE cardid = '".$cardid."';";
						$result = runmysqlquery($query);
						//If this is the first registration for this customer, generate customerid, update it to customer master and send welcome email
						$sendcustomeridpassword = "";
						$query22 = "Select * from inv_mas_customer where slno ='".$customerid."' and customerid = ''; ";
						$fetchresult = runmysqlquery($query22);
						if(mysql_num_rows($fetchresult) <> 0)
						{
							$query1 = "Select * from inv_customerproduct where customerreference = '".$customerid."'";
							$result = runmysqlquery($query1);
							if(mysql_num_rows($result) == 0)
							{
								$newcustomerid = generatecustomerid($customerid,$productcode,$delaerrep);
								$password = generatepwd();
								$query14 = "UPDATE inv_mas_customer SET customerid = '".$newcustomerid."',loginpassword = AES_ENCRYPT('".$password."','imaxpasswordkey'),initialpassword = '".$password."', firstproduct ='".$productcode."',firstdealer ='".$delaerrep."',passwordchanged = 'N' WHERE slno = '".$customerid."'";
								$result = runmysqlquery($query14); 
								$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','42','".date('Y-m-d').' '.date('H:i:s')."','".$customerid."')";
								$eventresult = runmysqlquery($eventquery);
								$sendcustomeridpassword = cusidcombine($newcustomerid)."%".$password;
								sendwelcomeemail($customerid,$userid);
							}
						}
						//Find the next slno to be inserted
						$query = "SELECT (MAX(slno) + 1) AS newslno FROM inv_customerproduct";
						$fetch = runmysqlqueryfetch($query);
						$customerproductslno = $fetch['newslno'];
						$query = "INSERT INTO inv_customerproduct(slno,customerreference,cardid,computerid,softkey,cusbillnumber,billnumber,billamount,dealerid,generatedby,system,date,time,remarks,reregistration,`type`,module) VALUES('".$customerproductslno."','".$customerid."','".$cardid."','".$computerid."','".$softkey."',(SELECT cusbillnumber from inv_dealercard where cardid = '".$cardid."'),'".$billno."','".$billamount."','".$delaerrep."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','".$regremarks."','no','newlicence','user_module');";
						$result = runmysqlquery($query);
						
						//Get the dealer Branch and Region
						$query111 = "select branch,region from inv_mas_dealer where slno = '".$delaerrep."';";
						$fetchresult111 = runmysqlqueryfetch($query111);
						$branchid = $fetchresult111['branch'];
						$regionid = $fetchresult111['region'];
						
						$query = "UPDATE inv_mas_customer SET currentdealer = '".$delaerrep."',activecustomer ='yes',branch = '".$branchid."',region = '".$regionid."' WHERE slno = '".$customerid."'";
						$result = runmysqlquery($query); 
						
						sendregistrationemail($customerproductslno,$userid);
						
						$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','44','".date('Y-m-d').' '.date('H:i:s')."','".$customerid."')";
						$eventresult = runmysqlquery($eventquery);

						$query = "INSERT INTO inv_logs_softkeygen(generatedby,customerref,cardno,computerid,softkey,`date`,time,system,module) VALUES('".$userid."','".$customerid."','".$cardid."','".$computerid."','".$softkey."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','user_module');";
						$result = runmysqlquery($query);
						//Attching card to customer if purchasetype is new
/*						if(freeupdateproductcode($productcode) <> 'codenotfound' && $purchasetype == 'new' && $cardscheme == '1')
						{
							$newproductcode = freeupdateproductcode($productcode);
							$query1 = "select cardid as cusattachedcard from inv_mas_scratchcard where attached = 'no' order by cardid limit 1 ;";
							$result1 = runmysqlqueryfetch($query1);
							$cusattachedcard = $result1['cusattachedcard'];
							$query2 = "INSERT INTO inv_dealercard (dealerid, cardid, productcode, date, remarks, cusbillnumber, usagetype, purchasetype,userid,scheme,initialusagetype,initialpurchasetype,initialproduct,initialdealerid,customerreference,cuscardattacheddate) values('".$delaerrep."','".$cusattachedcard."','".$newproductcode."','".datetimelocal('Y-m-d')." ".datetimelocal('H:i:s')."','','".$billno."','".$usagetype."','updation','".$userid."','7','".$usagetype."','updation','".$newproductcode."','".$delaerrep."','".$customerid."','".datetimelocal('Y-m-d')." ".datetimelocal('H:i:s')."');";
							$result2 = runmysqlquery($query2);
							$query3 = "update inv_mas_scratchcard set attached = 'yes', registered='no', blocked='no', online='no', cancelled='no'  where attached = 'no' and cardid = ".$cusattachedcard.";";			
							$result3 = runmysqlquery($query3);
							sendfreeupdationcardemail($cusattachedcard,$customerproductslno);
							
						}*/
					
						$errorfound = "3^Softkey Generated Your Softkey is: ".$softkey."^".$sendcustomeridpassword;
					}
					echo(json_encode($errorfound));
				}
			}
			break;
			
			case 'updationlicense':
			{
				$customerid = $_POST['customerid'];
				$cardid = $_POST['scratchnumber'];
				$delaerrep = $_POST['delaerrep']; /* Dealer ID*/
				$productcode = $_POST['productcode'];
				//$searchdelaerrep = $_POST['searchdelaerrep'];/* Dealer Name*/
				$productname = $_POST['productname'];
				$computerid = $_POST['computerid'];
				$billno = $_POST['billno'];
				$billamount = $_POST['billamount'];
				$regremarks = $_POST['regremarks'];
				$usagetype = $_POST['usagetype'];
				$purchasetype = $_POST['purchasetype'];
				$date = datetimelocal('d-m-Y');
				$systemip = '';
				if($usagetype == 'multiuser')
					$usagetypecode = '09';
				elseif($usagetype == 'singleuser')
					$usagetypecode = '00';
				elseif($usagetype == 'additionallicense')
					$usagetypecode = '00';

				$computeridedition = substr($computerid, 0, 3);
				$computeridusagetype = substr($computerid, 3, 2);
				$computeridformat = substr($computerid, 5, 1);
				$computeridlength = strlen($computerid);
				if($computeridlength <> 15)
				{ echo(json_encode("2^"."Computer Id format is not valid.")); }
				elseif($computeridedition <> $productcode)
				{ echo(json_encode("2^"."Computer Id is not matching with the product you have selected.")); }
				elseif($computeridusagetype <> $usagetypecode)
				{ echo(json_encode("2^"."Computer Id is not matching with the purchase type.")); }
				elseif($computeridformat <> '-')
				{ echo(json_encode("2^"."Computer Id format is not valid.")); }
				
				else
				{
					$errorfound = "";
					//Card Number present in Database
					
					if($errorfound = "")
					{
						$query ="select * from inv_mas_scratchcard where cardid = '".$cardid."' and attached = 'yes' and blocked = 'no'";
						$result = runmysqlquery($query);
						if(mysql_num_rows($result) == 0)
						$errorfound = '2 ^Invalid pin';
					}
					if($errorfound == "")
					{
						//Card No is not registered
						$query ="select * from inv_mas_scratchcard where cardid = '".$cardid."' and registered = 'no'";
						$result = runmysqlquery($query);
						if(mysql_num_rows($result) == 0)
						$errorfound = '2^ This pin is already registered';
					}
					if($errorfound == "")
					{
						//Product code is the same, attached with Card
						$query ="select inv_mas_scratchcard.scratchnumber, inv_dealercard.dealerid from inv_mas_scratchcard left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where inv_mas_scratchcard.cardid = '".$cardid."' and inv_dealercard.productcode = '".$productcode."'";
						$result = runmysqlquery($query);
						if(mysql_num_rows($result) == 0)
						$errorfound = '2^ This Card belongs to a different Product.';
						//Read the card ID, dealer ID
						else
						{
							$fetch = mysql_fetch_array($result);
							$scratchnumber = $fetch['scratchnumber'];
						}
					}
					if($errorfound == "")
					{
						//check if card is attached to the customer
						$query012 ="select * from inv_dealercard where cardid = '".$cardid."' and (customerreference = '' or customerreference IS NULL);";
						$result = runmysqlquery($query012);
						if(mysql_num_rows($result) == 0)
						{
							$query013 ="select * from inv_dealercard where cardid = '".$cardid."' and customerreference = '".$customerid."';";
							$result1 = runmysqlquery($query013);
							if(mysql_num_rows($result1) == 0)
								$errorfound = '2^ This Card belongs to a different Customer';
						}
					}
					if($errorfound == "")
					{
						//Usage type is the same, attached with Card
						$query ="select inv_mas_scratchcard.cardid as cradid from inv_mas_scratchcard left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where inv_mas_scratchcard.cardid = '".$cardid."' and inv_dealercard.productcode = '".$productcode."'";
						$result = runmysqlqueryfetch($query);
						if($computeridusagetype == '00')
						{
							$query ="select * from inv_mas_scratchcard left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where inv_mas_scratchcard.cardid = '".$cardid."' and (inv_dealercard.usagetype = 'singleuser' OR inv_dealercard.usagetype = 'additionallicense')";
							$result = runmysqlquery($query);
							if(mysql_num_rows($result) == 0)
							$errorfound = '2^ This Card belongs to a Multiuser.';
						}
						elseif($computeridusagetype == '09')
						{
							$query ="select * from inv_mas_scratchcard left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where inv_mas_scratchcard.cardid = '".$cardid."' and (inv_dealercard.usagetype = 'multiuser')";
							$result = runmysqlquery($query);
							if(mysql_num_rows($result) == 0)
							$errorfound = '2^ This Card belongs to Single User or Additonal License.';
						}

						else
							$errorfound = '2^ The usage type of computer ID is invalid.'.$computeridusagetype;
					}
					if($errorfound == "")
					{
						$query = "SELECT `type` as prdtype,productname as productname FROM inv_mas_product WHERE productcode = '".$productcode."'";
						$fetch = runmysqlqueryfetch($query);
						$prdtype = $fetch['prdtype'];
						$productname = $fetch['productname'];
						$softkey  = generateserial($computerid, $prdtype);
						//$softkey  = generatesoftkeydummy($computerid, $prdtype);
						$query = "UPDATE inv_mas_scratchcard SET registered = 'yes', blocked = 'no', online = 'no', cancelled = 'no' WHERE cardid = '".$cardid."';";
						$result = runmysqlquery($query);
							//If this is the first registration for this customer, generate customerid, update it to customer master and send welcome email
						$query22 = "Select * from inv_mas_customer where slno ='".$customerid."' and customerid = ''; ";
						$fetchresult = runmysqlquery($query22);
						if(mysql_num_rows($fetchresult) <> 0)
						{
							$query1 = "Select * from inv_customerproduct where customerreference = '".$customerid."'";
							$result = runmysqlquery($query1);
							if(mysql_num_rows($result) == 0)
							{
								$newcustomerid = generatecustomerid($customerid,$productcode,$delaerrep);
								$password = generatepwd();
								$query14 = "UPDATE inv_mas_customer SET customerid = '".$newcustomerid."',loginpassword = AES_ENCRYPT('".$password."','imaxpasswordkey'),initialpassword = '".$password."',firstproduct ='".$productcode."',firstdealer ='".$delaerrep."' ,passwordchanged = 'N' WHERE slno = '".$customerid."'";
								$result = runmysqlquery($query14); 
								$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','42','".date('Y-m-d').' '.date('H:i:s')."','".$customerid."')";
								$eventresult = runmysqlquery($eventquery);
								$sendcustomeridpassword = cusidcombine($newcustomerid)."%".$password;
								sendwelcomeemail($customerid,$userid);
							}
						}
							//Find the next slno to be inserted
						$query = "SELECT (MAX(slno) + 1) AS newslno FROM inv_customerproduct";
						$fetch = runmysqlqueryfetch($query);
						$customerproductslno = $fetch['newslno'];
						$query = "INSERT INTO inv_customerproduct(slno,customerreference,cardid,computerid,softkey,cusbillnumber,billnumber,billamount,dealerid,generatedby,system,date,time,remarks,reregistration,`type`,module) VALUES('".$customerproductslno."','".$customerid."','".$cardid."','".$computerid."','".$softkey."',(SELECT cusbillnumber from inv_dealercard where cardid = '".$cardid."'),'".$billno."','".$billamount."','".$delaerrep."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','".$regremarks."','no','updationlicense','user_module');";
					$result = runmysqlquery($query);
					
					//Get the dealer Branch and Region
					$query111 = "select branch,region from inv_mas_dealer where slno = '".$delaerrep."';";
					$fetchresult111 = runmysqlqueryfetch($query111);
					$branchid = $fetchresult111['branch'];
					$regionid = $fetchresult111['region'];
					
					$query = "UPDATE inv_mas_customer SET currentdealer = '".$delaerrep."',activecustomer ='yes',branch = '".$branchid."',region = '".$regionid."' WHERE slno = '".$customerid."'";
					$result = runmysqlquery($query); 

					sendregistrationemail($customerproductslno,$userid);
					$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','44','".date('Y-m-d').' '.date('H:i:s')."','".$customerid."')";
					$eventresult = runmysqlquery($eventquery);
					$query = "INSERT INTO inv_logs_softkeygen(generatedby,customerref,cardno,computerid,softkey,`date`,time,system,module) VALUES('".$userid."','".$customerid."','".$cardid."','".$computerid."','".$softkey."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','user_module');";
					$result = runmysqlquery($query);
					
					$errorfound = "3^Softkey Generated Your Softkey is: ".$softkey."^".$sendcustomeridpassword;
					}
					echo(json_encode($errorfound));
				}
			}
			break;
			
			case 'reregistration':
			{
				$customerid = $_POST['customerid'];
				$scratchnumber = $_POST['scratchnumber'];
				$delaerrep = $_POST['delaerrep']; /* Dealer ID*/
				$productcode = $_POST['productcode'];
				//$searchdelaerrep = $_POST['searchdelaerrep'];/* Dealer Name*/
				$productname = $_POST['productname'];
				$computerid = $_POST['computerid'];
				$billno = $_POST['billno'];
				$billamount = $_POST['billamount'];
				$regremarks = $_POST['regremarks'];
				$usagetype = $_POST['usagetype'];
				$purchasetype = $_POST['purchasetype'];
				if($usagetype == 'multiuser')
					$usagetypecode = '09';
				elseif($usagetype == 'singleuser')
					$usagetypecode = '00';
				elseif($usagetype == 'additionallicense')
					$usagetypecode = '00';
				$computeridedition = substr($computerid, 0, 3);
				$computeridusagetype = substr($computerid, 3, 2);
				if($computeridedition <> $productcode)
				{
					echo(json_encode("2^"."Computer Id is not matching with the product you have selected.".$productcode.$usagetype));
				}
				elseif($computeridusagetype <> $usagetypecode)
				{
					echo(json_encode("2^"."Computer Id is not matching with the purchase type.".$productcode.$usagetype));
				}
				else
				{
					$query = "SELECT `type` as prdtype FROM inv_mas_product WHERE productcode = '".$productcode."'";
					$fetch = runmysqlqueryfetch($query);
					$prdtype = $fetch['prdtype'];
					//$softkey  = generatesoftkeydummy($computerid, $prdtype);
					$softkey = generateserial($computerid, $prdtype);
					
					$query = "INSERT INTO inv_customerproduct(customerreference,cardid,computerid,softkey,cusbillnumber,billnumber,billamount,dealerid,generatedby,system,date,time,remarks,reregistration,`type`,module) VALUES('".$customerid."','".$scratchnumber."','".$computerid."','".$softkey."',(SELECT cusbillnumber from inv_dealercard where cardid = '".$scratchnumber."'),'".$billno."','".$billamount."','".$delaerrep."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','".$regremarks."','yes','reregistration','user_module');";
					$result = runmysqlquery($query);
					
					//Get the dealer Branch and Region
					$query111 = "select branch,region from inv_mas_dealer where slno = '".$delaerrep."';";
					$fetchresult111 = runmysqlqueryfetch($query111);
					$branchid = $fetchresult111['branch'];
					$regionid = $fetchresult111['region'];
					
					$query = "UPDATE inv_mas_customer SET currentdealer = '".$delaerrep."',activecustomer ='yes',branch = '".$branchid."',region = '".$regionid."' WHERE slno = '".$customerid."'";
					$result = runmysqlquery($query); 
					
					$updatedata = $customerid."|^|".$scratchnumber."|^|".$computerid."|^|".$softkey."|^|"." "."|^|".$billno."|^|".$billamount."|^|".$delaerrep."|^|"." "."|^|".$_SERVER['REMOTE_ADDR']."-".php_uname(n)."|^|".datetimelocal('Y-m-d')."|^|".datetimelocal('H:i:s')."|^|".$regremarks."|^|"."no";
				//	$query = "INSERT INTO inv_logs(date,time,type,action,existingdata,updateddata) VALUES('".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','Customer Screen','Registration - Reregistration','','".$updatedata."');";
				//	$result = runmysqlquery($query);
					
					$query = "UPDATE inv_mas_scratchcard SET registered = 'yes', blocked = 'no', online = 'no', cancelled = 'no' WHERE cardid = '".$scratchnumber."';";
					$result = runmysqlquery($query);
					$query = "INSERT INTO inv_logs_softkeygen(generatedby,customerref,cardno,computerid,softkey,`date`,time,system,module) VALUES('".$userid."','".$customerid."','".$scratchnumber."','".$computerid."','".$softkey."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','user_module');";
					$result = runmysqlquery($query);
					echo(json_encode("3^Softkey Generated Successfully. Your Softkey is: ".$softkey."^".$sendcustomeridpassword));
				}
			}
			break;
			
			case 'withoutcard':
			{
				$customerid = $_POST['customerid'];
				$scratchnumber = $_POST['scratchnumber'];
				$delaerrep = $_POST['delaerrep']; /* Dealer ID*/
				$productcode = $_POST['productcode'];
				//$searchdelaerrep = $_POST['searchdelaerrep'];/* Dealer Name*/
				$productname = $_POST['productname'];
				$computerid = $_POST['computerid'];
				$billno = $_POST['billno'];
				$billamount = $_POST['billamount'];
				$regremarks = $_POST['regremarks'];
				$usagetype = $_POST['usagetype'];
				$purchasetype = $_POST['purchasetype'];
				if($usagetype == 'multiuser')
					$usagetypecode = '09';
				elseif($usagetype == 'singleuser')
					$usagetypecode = '00';
				elseif($usagetype == 'additionallicense')
					$usagetypecode = '00';
				$computeridedition = substr($computerid, 0, 3);
				$computeridusagetype = substr($computerid, 3, 2);
				$errorfound = "";
				
				if($errorfound == "")
				{
					//Check if product is there in the Product master
					$query = "SELECT `type` as prdtype FROM inv_mas_product WHERE productcode = '".$computeridedition."'";
					$result = runmysqlquery($query);
					if(mysql_num_rows($result) == 0)
					$errorfound = '2^ The Product code ('.$computeridedition.') is not defined in Product Master.';
				}

				if($errorfound == "")
				{
					//If this is the first registration for this customer, generate customerid, update it to customer master and send welcome email
					$query22 = "Select * from inv_mas_customer where slno ='".$customerid."' and customerid = ''; ";

					$fetchresult = runmysqlquery($query22);
					if(mysql_num_rows($fetchresult) <> 0)
					{
						$query1 = "Select * from inv_customerproduct where customerreference = '".$customerid."'";
						$result = runmysqlquery($query1);
						if(mysql_num_rows($result) == 0)
						{
							$password = generatepwd();
							$newcustomerid = generatecustomerid($customerid,$computeridedition,$delaerrep);
							$query14 = "UPDATE inv_mas_customer SET customerid = '".$newcustomerid."',loginpassword = AES_ENCRYPT('".$password."','imaxpasswordkey'),initialpassword = '".$password."',passwordchanged ='N' WHERE slno = '".$customerid."'";
							$result = runmysqlquery($query14); 
							$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','42','".date('Y-m-d').' '.date('H:i:s')."','".$customerid."')";
							$eventresult = runmysqlquery($eventquery);
							$sendcustomeridpassword = cusidcombine($newcustomerid)."%".$password;
							sendwelcomeemail($customerid,$userid);
						}
					}
					$query = "SELECT `type` as prdtype FROM inv_mas_product WHERE productcode = '".$computeridedition."'";
					$fetch = runmysqlqueryfetch($query);
					$prdtype = $fetch['prdtype'];
					//$softkey  = generatesoftkeydummy($computerid, $prdtype);
					$softkey = generateserial($computerid, $prdtype);
					$query = "INSERT INTO inv_customerproduct(customerreference,cardid,computerid,softkey,cusbillnumber,billnumber,billamount,dealerid,generatedby,system,date,time,remarks,reregistration,`type`,module) VALUES('".$customerid."','','".$computerid."','".$softkey."','','".$billno."','".$billamount."','".$delaerrep."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','".$regremarks."','no','withoutcard','user_module');";
					$result = runmysqlquery($query);
					
					//Get the dealer Branch and Region
					$query111 = "select branch,region from inv_mas_dealer where slno = '".$delaerrep."';";
					$fetchresult111 = runmysqlqueryfetch($query111);
					$branchid = $fetchresult111['branch'];
					$regionid = $fetchresult111['region'];
					
					$query = "UPDATE inv_mas_customer SET currentdealer = '".$delaerrep."',activecustomer ='yes',branch = '".$branchid."',region = '".$regionid."' WHERE slno = '".$customerid."'";
					$result = runmysqlquery($query); 
					$scratchnumber ='';
					
					$updatedata = $customerid."|^|".$scratchnumber."|^|".$computerid."|^|".$softkey."|^|"." "."|^|".$billno."|^|".$billamount."|^|".$delaerrep."|^|"." "."|^|".$_SERVER['REMOTE_ADDR']."-".php_uname(n)."|^|".datetimelocal('Y-m-d')."|^|".datetimelocal('H:i:s')."|^|".$regremarks."|^|"."no";
					$query = "INSERT INTO inv_logs_softkeygen(generatedby,customerref,cardno,computerid,softkey,`date`,time,system,module) VALUES('".$userid."','".$customerid."','','".$computerid."','".$softkey."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','user_module');";
					$result = runmysqlquery($query);
					
					$errorfound = "3^Softkey Generated Successfully. Your Softkey is: ".$softkey."^".$sendcustomeridpassword;
				}
				echo(json_encode($errorfound));
			}
			break;
		}
	}
	break;
	
	//Transfer the PIN Number
	case 'scratchtransfer':
	{
		$responsearray8 = array();
		$tfproduct = $_POST['tfproduct'];
		$tfdealer = $_POST['tfdealer'];
		$tfpurchasetype = $_POST['tfpurchasetype'];
		$tfusagetype = $_POST['tfusagetype'];
		$cardid = $_POST['cardid'];
		
		$dealerid = ($_POST['dealerid'] == "")?($tfdealer):($_POST['dealerid']);
		$productcode = ($_POST['productcode'] == "")?($tfproduct):($_POST['productcode']);
		$usagetype = ($_POST['usagetype'] == "")?($tfusagetype):($_POST['usagetype']);
		$purchasetype = ($_POST['purchasetype'] == "")?($tfpurchasetype):($_POST['purchasetype']);
		
		
		$query = "UPDATE inv_dealercard SET dealerid = '".$dealerid."', productcode = '".$productcode."', purchasetype = '".$purchasetype."', usagetype = '".$usagetype."' WHERE cardid = '".$cardid."'";
		$result = runmysqlquery($query);
		
		
		$query1 = "insert into `inv_logs_transfercards` (transferfromdealerid, transferfromproduct, transferfromusagetype, 	transferfrompurchasetype, transfertodealerid, transfertoproduct, 	transfertousagetype, transfertopurchasetype, transferdate, 	transfertime, system, userid, module) values('".$tfdealer."', '".$tfproduct."', '".$tfusagetype."', '".$tfpurchasetype."', '".$dealerid."', '".$productcode."', '".$usagetype."', '".$purchasetype."' ,'".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."', '".$_SERVER['REMOTE_ADDR']."', '".$userid."', 'user_module')";
		$result = runmysqlquery($query1);
		$responsearray8['errorcode']	= '1';
		echo(json_encode($responsearray8));
	}
	break;
	case 'getreregscratchcardlist':
	{
		$responsearray9 = array();
		$lastslno = $_POST['lastslno'];
		$query = "SELECT DISTINCT inv_mas_scratchcard.cardid AS cardid,inv_mas_scratchcard.scratchnumber AS scratchnumber FROM inv_mas_dealer LEFT JOIN (inv_customerproduct  JOIN inv_mas_scratchcard ON inv_customerproduct.cardid = inv_mas_scratchcard.cardid) ON inv_mas_dealer.slno = inv_customerproduct.dealerid left JOIN inv_mas_product ON inv_mas_product.productcode = left(inv_customerproduct.computerid,3)  JOIN inv_mas_users ON inv_mas_users.slno = inv_customerproduct.generatedby
 where inv_customerproduct.customerreference = '".$lastslno."' and inv_mas_scratchcard.cancelled = 'no' ;  ";
		$result = runmysqlquery($query);
		$grid .='<select name="searchscratchnumber" class="swiftselect-mandatory" id="searchscratchnumber" onchange ="scratchdetailstoform(document.getElementById(\'searchscratchnumber\').value)">';
		$grid .= '<option value="">Select A Scratch Card</option>';
		while($fetch = mysql_fetch_array($result))
		{
			$grid .=	'<option value="'.$fetch['cardid'].'">'.$fetch['scratchnumber'].'</option>';
		}
		$grid.='</select>';
		$responsearray9['errorcode'] = '1';
		$responsearray9['grid'] = $grid;
		//echo('1^'.$grid);
		echo(json_encode($responsearray9));
	}
	break;
	case 'carddetailstoform':
	{
		$cardlastslno = $_POST['cardlastslno'];
		$query = "SELECT distinct inv_dealercard.cardid , inv_mas_scratchcard.scratchnumber, inv_mas_scratchcard.blocked,
		inv_mas_scratchcard.cancelled,inv_mas_dealer.businessname as attachedto, inv_mas_dealer.slno as dealerid, 
		inv_mas_product.productcode, inv_mas_product.productname, inv_dealercard.purchasetype, inv_dealercard.usagetype, 
		inv_dealercard.date as attachdate, inv_customerproduct.date as registereddate, 
		inv_mas_customer.businessname as registeredto 
		from inv_dealercard 
		left joininv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid
		left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno 
		left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode 
		left join (select cardid, customerreference, min(`date`) as `date` 
		from inv_customerproduct GROUP BY cardid) AS inv_customerproduct on  inv_dealercard.cardid = inv_customerproduct.cardid 
		left join inv_mas_customer on  inv_customerproduct.customerreference = inv_mas_customer.slno 
		where inv_dealercard.cardid = '".$cardlastslno."';";
		
		$fetch = runmysqlqueryfetch($query);
		$attcheddate = substr($fetch['attachdate'] ,0,10);
		$registereddate =$fetch['registereddate'];
		if($registereddate != '')
			$registereddate = changedateformat($registereddate);
		if($fetch['blocked'] == 'yes')
		$cardstatus = 'Blocked';
		else if($fetch['cancelled'] == 'yes')
		$cardstatus = 'Cancelled';
		else
		{
			$cardstatus = 'Active';
		}
		echo($fetch['cardid'].'^'.$fetch['scratchnumber'].'^'.$fetch['purchasetype'].'^'.$fetch['usagetype'].'^'.$fetch['attachedto'].'^'.$fetch['dealerid'].'^'.$fetch['productcode'].'^'.$fetch['productname'].'^'.changedateformat($attcheddate).'^'.$registereddate.'^'.$registereddate.'^'.$fetch['registeredto'].'^'.$cardstatus);

	}
	break;
	case 'cardsearchdetailstoform':
	{
		  $responsearray10 = array();
		  $cardlastslno = $_POST['cardlastslno'];
		  $query = "SELECT distinct inv_dealercard.cardid , inv_mas_scratchcard.scratchnumber, 
	  inv_mas_scratchcard.blocked,inv_mas_scratchcard.cancelled,inv_mas_dealer.businessname as attachedto, 
	  inv_mas_dealer.slno as dealerid, inv_mas_product.productcode, inv_mas_product.productname, 
	  inv_dealercard.purchasetype, inv_dealercard.usagetype, inv_dealercard.date as attachdate, 
	  inv_customerproduct.date as registereddate, inv_customerproduct.businessname as registeredto,inv_dealercard.scheme ,
	  inv_mas_scheme.schemename as schemename ,inv_mas_customer.businessname as businessname from inv_dealercard 
	  left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid 
	  left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno 
	  left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode 
	  left join 
	  (select cardid, customerreference, min(`date`) as `date` ,inv_mas_customer.businessname as businessname
	  from inv_customerproduct 
	  left join inv_mas_customer on  inv_customerproduct.customerreference = inv_mas_customer.slno 
	  GROUP BY cardid) 
	  AS inv_customerproduct on  inv_dealercard.cardid = inv_customerproduct.cardid 
	  left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme 
	  left join inv_mas_customer on  inv_dealercard.customerreference = inv_mas_customer.slno 
	  where inv_dealercard.cardid = '".$cardlastslno."'";
		$results = runmysqlquery($query);
		$fetch = mysql_fetch_array($results);
		//$fetch = runmysqlqueryfetch($query);
		if($fetch['purchasetype'] == '' && $fetch['usagetype'] == ''  && $fetch['attachedto'] == '' && $fetch['dealerid'] == '' && $fetch['productcode'] == '' && $fetch['productname'] == '' && $fetch['registereddate'] == ''&& $fetch['attachdate'] == '' && $fetch['registeredto'] == '' && $fetch['remarks'] == '')
		{
			$queyscratch="select scratchnumber from inv_mas_scratchcard where cardid = '".$cardlastslno."'";
			$resultscratch = runmysqlqueryfetch($queyscratch);
			$fetch['scratchnumber'] = $resultscratch['scratchnumber'];
			$fetch['cardid'] = $cardlastslno;
			$fetch['usagetype'] = 'Not Available';
			$fetch['attachedto'] = 'Not Available';
			$fetch['dealerid'] = 'Not Available';
			$fetch['productcode'] = 'Not Available';
			$fetch['productname'] = 'Not Available';
			$fetch['registeredto'] = 'Not Available';
			$fetch['remarks'] = 'Not Available';
		}
		else
			
		$attcheddate = substr($fetch['attachdate'] ,0,10);
		$registereddate =$fetch['registereddate'];
		if($registereddate != '')
			$registereddate = changedateformat($registereddate);
		if($fetch['blocked'] == 'yes')
		$cardstatus = 'Blocked';
		else if($fetch['cancelled'] == 'yes')
		$cardstatus = 'Cancelled';
		else
		{
			$cardstatus = 'Active';
		}
		$responsearray10['cardid'] = $fetch['cardid'];
		$responsearray10['scratchnumber'] = $fetch['scratchnumber'];
		$responsearray10['purchasetype'] = $fetch['purchasetype'];
		$responsearray10['usagetype'] = $fetch['usagetype'];
		$responsearray10['attachedto'] = $fetch['attachedto'];
		$responsearray10['dealerid'] = $fetch['dealerid'];
		$responsearray10['productcode'] = $fetch['productcode'];
		$responsearray10['productname'] = $fetch['productname'];
		$responsearray10['attcheddate'] = changedateformat($attcheddate);
		$responsearray10['registereddate'] = $fetch['registereddate'];
		$responsearray10['registeredto'] = $fetch['registeredto'];
		$responsearray10['cardstatus'] = $cardstatus;
		$responsearray10['remarks'] = $fetch['remarks'];
		$responsearray10['schemename'] = $fetch['schemename'];
		$responsearray10['businessname'] = $fetch['businessname'];

		
		//echo($fetch['cardid'].'^'.$fetch['scratchnumber'].'^'.$fetch['purchasetype'].'^'.$fetch['usagetype'].'^'.$fetch['attachedto'].'^'.$fetch['dealerid'].'^'.$fetch['productcode'].'^'.$fetch['productname'].'^'.changedateformat($attcheddate).'^'.$registereddate.'^'.$fetch['registeredto'].'^'.$cardstatus.'^'.$fetch['remarks'].'^'.$fetch['schemename'].'^'.$fetch['businessname']);
		echo(json_encode($responsearray10));
		

	}
	break;
	case 'getcardlist':
	{
		$cardarray = array();
		$limit = $_POST['limit'];
		$startindex = $_POST['startindex'];
		$query = "SELECT cardid,scratchnumber FROM inv_mas_scratchcard  ORDER BY scratchnumber LIMIT ".$startindex.",".$limit.";";
		$result = runmysqlquery($query);
		$grid = '';
		$count = 0;
		while($fetch = mysql_fetch_array($result))
		{
			$cardarray[$count] =  $fetch['scratchnumber'].' | '.$fetch['cardid'].'^'.$fetch['cardid'];
			$count++;
		}
		echo(json_encode($cardarray));
	 }
	break;
	case 'getcardcount':
	{
		$cardcountarray = array();
		$query = "SELECT count(*) as totalcardcount FROM inv_mas_scratchcard;";
		$resultfetch = runmysqlqueryfetch($query);
		$totalcardcount = $resultfetch['totalcardcount'];
		$cardcountarray['totalcardcount'] = $totalcardcount;
		echo(json_encode($cardcountarray));
	 }
	break;
	case 'newregcardlist':
	{
		## To Enable New Products for Autoregistration and product code like inv_dealercard.productcode != "XXX" ##
		$newregcardlistarray = array();
		/*$query = "select * from inv_mas_scratchcard 
		left join inv_dealercard on inv_dealercard.cardid = inv_mas_scratchcard.cardid 
		where attached = 'yes' and registered = 'no' and blocked = 'no' and inv_dealercard.purchasetype = 'new' and 
		(inv_dealercard.productcode <> 353 and inv_dealercard.productcode <> 308 and 
		inv_dealercard.productcode <> 371 and inv_dealercard.productcode <> 215 and 
		inv_dealercard.productcode <> 216 and inv_dealercard.productcode <> 217 and 
		inv_dealercard.productcode <> 515 and inv_dealercard.productcode <> 243 and
		inv_dealercard.productcode <> 242 and inv_dealercard.productcode <> 881 and inv_dealercard.productcode <> 690
		and inv_dealercard.productcode <> 885 and inv_dealercard.productcode <> 886 and inv_dealercard.productcode <> 887
		and inv_dealercard.productcode <> 888 and inv_dealercard.productcode <> 643 and inv_dealercard.productcode <> 658 		        and inv_dealercard.productcode <> 659 and inv_dealercard.productcode <> 882 and inv_dealercard.productcode <> 883        and inv_dealercard.productcode <> 884 and inv_dealercard.productcode<>214 and inv_dealercard.productcode <> 309 and inv_dealercard.productcode <> 001 and inv_dealercard.productcode <>372 and inv_dealercard.productcode <> 354 and inv_dealercard.productcode <> 660 and inv_dealercard.productcode <> 661 and inv_dealercard.productcode <> 644 and inv_dealercard.productcode <> 484 and inv_dealercard.productcode <> 485 and inv_dealercard.productcode <> 483 and inv_dealercard.productcode <>482 and inv_dealercard.productcode <> 516 and inv_dealercard.productcode <> 481 and inv_dealercard.productcode <> 244 and inv_dealercard.productcode <> 245 and inv_dealercard.productcode <> 818 and inv_dealercard.productcode <> 691 and inv_dealercard.productcode <> 219 and inv_dealercard.productcode <> 220 and inv_dealercard.productcode <> 221 and inv_dealercard.productcode <> 218 and inv_dealercard.productcode <> 222 and inv_dealercard.productcode <> 223 and inv_dealercard.productcode <> 224 and inv_dealercard.productcode <> 889 and inv_dealercard.productcode <> 890 and inv_dealercard.productcode <> 891 and inv_dealercard.productcode <> 892 and inv_dealercard.productcode <> 662 and inv_dealercard.productcode <> 664 and inv_dealercard.productcode <> 667 and inv_dealercard.productcode <> 246 and inv_dealercard.productcode <> 247 and inv_dealercard.productcode <> 373 and inv_dealercard.productcode <> 310 and inv_dealercard.productcode <> 355 and inv_dealercard.productcode <> 486 and inv_dealercard.productcode <> 517);";*/
		$query = "select inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber from inv_mas_scratchcard
		left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
		left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode
		where attached = 'yes' and registered = 'no' and blocked = 'no' and inv_dealercard.purchasetype = 'new' and 
		inv_mas_product.newproduct!= 1";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysql_fetch_array($result))
		{

			$newregcardlistarray[$count] =  $fetch['scratchnumber'].' | '.$fetch['cardid'].'^'.$fetch['cardid'];
			$count++;
		}
		echo(json_encode($newregcardlistarray));
	 }
	break;
	
	case 'checkpin':
	{
		$newregcardlistarray = array();
		$query = "select * from inv_mas_scratchcard left join inv_dealercard on inv_dealercard.cardid = inv_mas_scratchcard.cardid where attached = 'yes' and registered = 'no' and blocked = 'no' and inv_dealercard.purchasetype = 'new' and inv_dealercard.customerreference=".$_POST['custslno']." and inv_mas_scratchcard.scratchnumber='".$_POST['pin']."';";
		$result = runmysqlquery($query);
		$count = mysql_num_rows($result);
		if($count==1)
		{
			$newregcardlistarray="1^Valid PIN";
		}
		else
		{
			$newregcardlistarray="2^Not a valid PIN";
		}
		echo(json_encode($newregcardlistarray));
	 }
	break;
	
	case 'attachcardlist':
	{
		$newregcardlistarray = array();
		/*$query = "select * from inv_mas_scratchcard left join inv_dealercard on inv_dealercard.cardid = inv_mas_scratchcard.cardid where attached = 'yes' 
		and registered = 'no' and blocked = 'no' and inv_dealercard.purchasetype = 'new' and 
		(inv_dealercard.productcode = 353 or inv_dealercard.productcode = 308 or inv_dealercard.productcode = 371 or inv_dealercard.productcode = 215 or 
		inv_dealercard.productcode = 216 or inv_dealercard.productcode = 217 or inv_dealercard.productcode = 515 or inv_dealercard.productcode = 242 or 
		inv_dealercard.productcode = 243 or inv_dealercard.productcode = 881 or inv_dealercard.productcode = 690
		or inv_dealercard.productcode = 885 or inv_dealercard.productcode = 886 or inv_dealercard.productcode = 887
		or inv_dealercard.productcode = 888 or inv_dealercard.productcode = 643 or inv_dealercard.productcode = 658 or inv_dealercard.productcode = 659 or inv_dealercard.productcode = 882 or inv_dealercard.productcode = 883 or inv_dealercard.productcode = 884 or inv_dealercard.productcode = 214 or inv_dealercard.productcode = 309 or inv_dealercard.productcode = 001 or inv_dealercard.productcode = 372 or inv_dealercard.productcode = 354 or inv_dealercard.productcode = 660 or inv_dealercard.productcode = 661 or inv_dealercard.productcode = 644 or inv_dealercard.productcode = 484 or inv_dealercard.productcode = 485 or inv_dealercard.productcode = 483 or inv_dealercard.productcode = 482 or inv_dealercard.productcode = 516 or inv_dealercard.productcode = 481 or inv_dealercard.productcode = 244 or inv_dealercard.productcode = 245 or inv_dealercard.productcode = 818 or inv_dealercard.productcode = 691 or inv_dealercard.productcode = 219 or inv_dealercard.productcode = 220 or inv_dealercard.productcode = 221 or inv_dealercard.productcode = 218 or inv_dealercard.productcode = 222 or inv_dealercard.productcode = 223 or inv_dealercard.productcode = 224 or inv_dealercard.productcode = 889 or inv_dealercard.productcode = 890 or inv_dealercard.productcode = 891 or inv_dealercard.productcode = 892 or inv_dealercard.productcode = 662 or inv_dealercard.productcode = 664 or inv_dealercard.productcode = 667 or inv_dealercard.productcode = 246 or inv_dealercard.productcode = 247 or inv_dealercard.productcode = 373 or inv_dealercard.productcode = 310 or inv_dealercard.productcode = 355 or inv_dealercard.productcode = 486 or inv_dealercard.productcode = 517) and inv_dealercard.customerreference=".$_POST['custslno'].";";*/
		
		$query = "select inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber from inv_mas_scratchcard
		left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
		left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode
		where attached = 'yes' and registered = 'no' and blocked = 'no' and inv_dealercard.purchasetype = 'new' and 
		inv_mas_product.newproduct = 1 and inv_dealercard.customerreference =".$_POST['custslno'];
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysql_fetch_array($result))
		{

			$newregcardlistarray[$count] =  $fetch['scratchnumber'].' | '.$fetch['cardid'].'^'.$fetch['cardid'];
			$count++;
		}
		echo(json_encode($newregcardlistarray));
	 }
	break;
	
	case 'reregcardlist':
	{
		$reregcardlistarray = array();
		$lastslno = $_POST['lastslno'];
		/*$query = "SELECT DISTINCT inv_mas_scratchcard.cardid AS cardid,inv_mas_scratchcard.scratchnumber AS scratchnumber 
		FROM inv_mas_dealer 
		LEFT JOIN (inv_customerproduct  JOIN inv_mas_scratchcard ON inv_customerproduct.cardid = inv_mas_scratchcard.cardid) ON inv_mas_dealer.slno = inv_customerproduct.dealerid 
left JOIN inv_mas_product ON inv_mas_product.productcode = left(inv_customerproduct.computerid,3)  
JOIN inv_mas_users ON inv_mas_users.slno = inv_customerproduct.generatedby
 where inv_customerproduct.customerreference = '".$lastslno."' and (inv_mas_product.productcode <> 353 and inv_mas_product.productcode <> 308 and inv_mas_product.productcode <> 371 and inv_mas_product.productcode <> 215 and inv_mas_product.productcode <> 216 and inv_mas_product.productcode <> 217 and inv_mas_product.productcode <> 515 and inv_mas_product.productcode <> 242 and inv_mas_product.productcode <> 243 and inv_mas_product.productcode <> 881 and inv_mas_product.productcode <> 690 and inv_mas_product.productcode <> 885 and inv_mas_product.productcode <> 886
 and inv_mas_product.productcode <> 887 and inv_mas_product.productcode <> 888 and inv_mas_product.productcode <> 643 and inv_mas_product.productcode <> 658 and inv_mas_product.productcode <> 659 and inv_mas_product.productcode <> 882 and inv_mas_product.productcode <> 883 and inv_mas_product.productcode <> 884  and inv_mas_product.productcode<>214 and inv_mas_product.productcode <> 309 and inv_mas_product.productcode <> 001 and inv_mas_product.productcode <>372 and inv_mas_product.productcode <> 354 and inv_mas_product.productcode <> 660 and inv_mas_product.productcode <> 661 and inv_mas_product.productcode <> 644 and inv_mas_product.productcode <> 484 and inv_mas_product.productcode <> 485 and inv_mas_product.productcode <> 483 and inv_mas_product.productcode <> 482 and inv_mas_product.productcode <> 516 and inv_mas_product.productcode <> 481 and inv_mas_product.productcode <> 244 and inv_mas_product.productcode <> 245 and inv_mas_product.productcode <> 818 and inv_mas_product.productcode <> 691 and inv_mas_product.productcode <> 219 and inv_mas_product.productcode <> 220 and inv_mas_product.productcode <> 221)  and  inv_mas_scratchcard.cancelled = 'no'; ";*/
 
 /*$query = "select  distinct `inv_mas_scratchcard`.cardid, `inv_mas_scratchcard`.`scratchnumber` from `inv_mas_scratchcard` 
right join `inv_customerproduct` on `inv_mas_scratchcard`.`cardid`=`inv_customerproduct`.`cardid` and  inv_mas_scratchcard.cancelled = 'no'
right join inv_mas_customer ON inv_mas_customer.slno = inv_customerproduct.customerreference
right join inv_dealercard on `inv_mas_scratchcard`.`cardid`=`inv_dealercard`.`cardid`
right join `inv_mas_product` on `inv_dealercard`.`productcode`=`inv_mas_product`.`productcode` and inv_mas_product.productcode 
NOT IN
(353,308,371,215,216,217,515,242,243,881,690,885,886,887,888,643,658,659,882,883,884,214,309,001,372,354,660,661,644,484,
485,483,482,516,481,244,245,818,691,219,220,221,218,222,223,224,889,890,891,892,662,664,667,246,247,373,310,355,486,517)
where inv_customerproduct.customerreference = '".$lastslno."'";*/

        $query = "select distinct inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber from inv_mas_scratchcard        
		right join inv_customerproduct on inv_mas_scratchcard.cardid=inv_customerproduct.cardid and  			
		inv_mas_scratchcard.cancelled = 'no'
		right join inv_mas_customer ON inv_mas_customer.slno = inv_customerproduct.customerreference
		right join inv_dealercard on inv_mas_scratchcard.cardid=inv_dealercard.cardid
		right join inv_mas_product on inv_dealercard.productcode=inv_mas_product.productcode and 
		inv_mas_product.newproduct!= 1 where inv_customerproduct.customerreference = '".$lastslno."'";
		$result = runmysqlquery($query);
		$grid = '';
		$count = 0;
		while($fetch = mysql_fetch_array($result))
		{
			$reregcardlistarray[$count] =  $fetch['scratchnumber'].' | '.$fetch['cardid'].'^'.$fetch['cardid'];
			$count++;
		}
		echo(json_encode($reregcardlistarray));
	 }
	break;
	case 'updationcardlist':
	{
		$updationcardlistarray = array();
		/*$query = "select * from inv_mas_scratchcard left join inv_dealercard on inv_dealercard.cardid = inv_mas_scratchcard.cardid where attached = 'yes' and registered = 'no' and (inv_dealercard.productcode <> 353 and inv_dealercard.productcode <> 308 and inv_dealercard.productcode <> 371 and inv_dealercard.productcode <> 215 and inv_dealercard.productcode <> 216 and inv_dealercard.productcode <> 515 and inv_dealercard.productcode <> 242 and inv_dealercard.productcode <> 243 and inv_dealercard.productcode <> 881 and inv_dealercard.productcode <> 885
and inv_dealercard.productcode <> 886 and inv_dealercard.productcode <> 887 and inv_dealercard.productcode <> 888
and inv_dealercard.productcode <> 690 and inv_dealercard.productcode <> 643 and inv_dealercard.productcode <> 658 and inv_dealercard.productcode <> 659 and inv_dealercard.productcode <> 882 and inv_dealercard.productcode <> 883 and inv_dealercard.productcode <> 884  and inv_dealercard.productcode <> 214 and inv_dealercard.productcode <> 309 and inv_dealercard.productcode <> 001 and inv_dealercard.productcode <> 372 and inv_dealercard.productcode <> 354 and inv_dealercard.productcode <> 660 and inv_dealercard.productcode <> 661 and inv_dealercard.productcode <> 644 and inv_dealercard.productcode <> 484 and inv_dealercard.productcode <> 485 and inv_dealercard.productcode <> 483 and inv_dealercard.productcode <>482 and inv_dealercard.productcode <> 516 and inv_dealercard.productcode <> 481 and inv_dealercard.productcode <> 244 and inv_dealercard.productcode <> 245 and inv_dealercard.productcode <> 818 and inv_dealercard.productcode <> 691 and inv_dealercard.productcode <> 219 and inv_dealercard.productcode <> 220 and inv_dealercard.productcode <> 221 and inv_dealercard.productcode <> 218 and inv_dealercard.productcode <> 222 and inv_dealercard.productcode <> 223 and inv_dealercard.productcode <> 224 and inv_dealercard.productcode <> 889 and inv_dealercard.productcode <> 890 and inv_dealercard.productcode <> 891 and inv_dealercard.productcode <> 892 and inv_dealercard.productcode <> 662 and inv_dealercard.productcode <> 664 and inv_dealercard.productcode <> 667 and inv_dealercard.productcode <> 246 and inv_dealercard.productcode <> 247 and inv_dealercard.productcode <> 373 and inv_dealercard.productcode <> 310 and inv_dealercard.productcode <> 355 and inv_dealercard.productcode <> 486 and inv_dealercard.productcode <> 517) and blocked = 'no' and inv_dealercard.purchasetype = 'updation';";*/
 
        $query = "select inv_mas_scratchcard.cardid, inv_mas_scratchcard.scratchnumber from inv_mas_scratchcard
		left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
		left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode
		where attached = 'yes' and registered = 'no' and blocked = 'no' and inv_dealercard.purchasetype = 'updation' and 
		inv_mas_product.newproduct!= 1";
		$result = runmysqlquery($query);
		$grid = '';
		$count = 0;
		while($fetch = mysql_fetch_array($result))
		{
			$updationcardlist[$count] .=  $fetch['scratchnumber'].' | '.$fetch['cardid'].'^'.$fetch['cardid'];
			$count++;
		}
		echo(json_encode($updationcardlist));
	 }
	break;
	case 'resendwelcomeemail':
	{
		
		$customerid = $_POST['customerid'];
		$slnotobeinserted = substr($customerid,15,21);
		$query = "Select disablelogin from inv_mas_customer where slno = '".$slnotobeinserted."'";
		$fetch = runmysqlqueryfetch($query);
		$diablelogin = $fetch['disablelogin'];
		if($diablelogin == 'yes')
		{
			$responsearray['errorcode'] = '2';
			$responsearray['errormessage'] = 'Login is diabled for the selected customer';
			//echo('2^Login is diabled for the selected customer.');
			echo(json_encode($responsearray));
		}
		else
		{
			sendwelcomeemail($slnotobeinserted,$userid);
			$responsearray['errorcode'] = '1';
			$responsearray['errormessage'] = 'Welcome Mail has been sent Successfully for the selected customer';
			echo(json_encode($responsearray));
		}
	}
	break;
	case 'generatecustomerattachcards':
	{
		$lastslno = $_POST['lastslno'];
		$query = "select inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_dealer.businessname,inv_mas_product.productname,inv_dealercard.purchasetype,inv_dealercard.usagetype,inv_mas_scheme.schemename,inv_dealercard.cuscardattacheddate,inv_dealercard.remarks,inv_dealercard.cuscardremarks as cardremarks,inv_mas_scratchcard.blocked,inv_mas_scratchcard.cancelled
from inv_dealercard left join inv_mas_scratchcard on inv_dealercard.cardid =inv_mas_scratchcard.cardid left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme where customerreference ='".$lastslno."' and inv_mas_scratchcard.registered = 'no' order by inv_dealercard.cuscardattacheddate;";
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header">
		<td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">PIN Serial Number</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">PIN Number</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Dealer</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">product</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Usage Type</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Type</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Scheme</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Attached Date</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Card Remarks</td>
		</tr>';
		$i_n = 0;$slno = 0;
		$result = runmysqlquery($query);
		while($fetch = mysql_fetch_row($result))
		{
			$i_n++;$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow1" bgcolor='.$color.'>';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slno."</td>";
			for($i = 0; $i < count($fetch); $i++)
			{
				if($i == 8)
				{
				    $grid .= "<td nowrap='nowrap' class='td-border-grid' 
					align='left'>".changedateformatwithtime($fetch[$i])."</td>";
				}
				
				else if($i >=0 && $i<10)
				{
					if($fetch[10] == "yes"  || $fetch[11] == "yes")
					{
					  $grid .= "<td nowrap='nowrap' class='td-border-grid'
					  align='left'><strong>".gridtrim($fetch[$i])."</strong></td>";
					}
					else
					{
					  $grid .= "<td nowrap='nowrap' class='td-border-grid'  align='left'>".gridtrim($fetch[$i])."</td>";
					}
					
				}
				
			}
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		echo('1^'.$grid);
	}
	break;
	
	## Bhavesh Patel Added ##
	case 'surrenderhistory':
	{
		$lastslno = $_POST['lastslno'];
		$PIN = $_POST['pin'];
		
		$query1 = "select * from inv_customerproduct where customerreference = '".$lastslno."' 
					and getPINNo(inv_customerproduct.cardid)= '".$PIN."' and AUTOREGISTRATIONYN = 'Y'";
		$fetchresult = runmysqlqueryfetch($query1);
		$refslno = $fetchresult['slno'];
		$HDDID = $fetchresult['HDDID'];
		$ETHID = $fetchresult['ETHID'];
		$REGDATE = $fetchresult['REGDATE'];
		$datetime = $fetchresult['date']." ".$fetchresult['time'];
		
		
		$query2 = "select * from inv_surrenderproduct where refslno = '".$refslno."'";
		$resultfetch = runmysqlquery($query2);
		$fetchresultcount = mysql_num_rows($resultfetch);

		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header">
		<td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Status</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Computer Name</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Computer IP</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Created By</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Date</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">REGDATE</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">HDDID</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">ETHID</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Network IP</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">System IP</td>
		</tr>';
		$i_n = 0;$slno = 0;
		if($HDDID <> "")
		{
			$i_n++;
			$slno++;
			$status = "Active";	
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow1" bgcolor='.$color.' style="font-weight:bold;">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slno."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$status."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetchresult['COMPUTERNAME']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetchresult['COMPUTERIP']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetchresult['CREATEDBY']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($datetime)."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($REGDATE)."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetchresult['HDDID']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetchresult['ETHID']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetchresult['forceremarks']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>&nbsp;</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>&nbsp;</td>";
			$grid .= "</tr>";
		}
		if($fetchresultcount > 0)
		{
			//inv_customerproduct.customerreference, inv_customerproduct.slno,
			$query = "select inv_surrenderproduct.COMPUTERNAME,inv_surrenderproduct.COMPUTERIP,
			inv_surrenderproduct.CREATEDBY,inv_surrenderproduct.surrendertime,inv_surrenderproduct.REGDATE,
			inv_surrenderproduct.HDDID,inv_surrenderproduct.ETHID,inv_surrenderproduct.forceremarks,
			inv_surrenderproduct.networkip, inv_surrenderproduct.systemip 
			from inv_customerproduct 
			INNER JOIN inv_surrenderproduct ON inv_surrenderproduct.refslno=inv_customerproduct.slno where 
			inv_customerproduct.customerreference='".$lastslno."' 
			and getPINNo(inv_customerproduct.cardid)= '".$PIN."' order by inv_surrenderproduct.slno desc";	
			$result = runmysqlquery($query);
		
		while($fetch = mysql_fetch_row($result))
		{
			$i_n++;$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow1" bgcolor='.$color.'>';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slno."</td>";
			for($i = 0; $i < count($fetch); $i++)
			{
				if($i == 0)
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>Surrender</td>";
				
				if($i == 3 || $i == 4)
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch[$i])."</td>";
				else
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch[$i]."</td>";
				
			}
			$grid .= "</tr>";
		}
	}
		$grid .= "</table>";
		echo('1^'.$grid);
	}
	
	## Bhavesh Patel Added ##
	case 'forcesurrenderdetails':
	{
		$switchforce = $_POST['switchforce'];
		$refslno = $_POST['refslno'];
		switch($switchforce)
		{
			
			case 'countforcesurrender':
			{				
				$query1 = "select refslno, 
				SUM(CASE WHEN forces = 0 THEN 1 ELSE 0 END) AS onlinecount,  
				SUM(CASE WHEN forces = 1 THEN 1 ELSE 0 END) AS forcecount
				from inv_surrenderproduct where refslno = '".$refslno."'";
				$fetchresult = runmysqlqueryfetch($query1);
				$onlinecount = $fetchresult['onlinecount'];
				$forcecount = $fetchresult['forcecount'];
				//Checking Condition for Records
				if($onlinecount == 0)
				{$onlinecount = 'Not Done Yet';}
				else if($onlinecount > 1)
				{$onlinecount = $fetchresult['onlinecount'].' Records';}
				else
				{$onlinecount = $fetchresult['onlinecount'].' Record';}
				if($forcecount == 0)
				{$forcecount = 'Not Done Yet';}
				else if($forcecount > 1)
				{$forcecount = $fetchresult['forcecount'].' Records';}
				else
				{$forcecount = $fetchresult['forcecount'].' Record';}
				
				//Fetching records to insert into inv_surrenderproduct table
				$query2="select HDDID, ETHID from inv_customerproduct where slno = '".$refslno."'";
				$fetchresult2 = runmysqlqueryfetch($query2);
				$HDDID = $fetchresult2['HDDID'];
				$ETHID = $fetchresult2['ETHID'];
				if(($HDDID <> '' || $HDDID <> NULL) || ($ETHID <> '' || $ETHID <> NULL))
				{ $pinstatus = 'Active'; }else{ $pinstatus = 'Surrender';}
				
				echo('1'.'^'.$forcecount.'^'.$onlinecount.'^'.$pinstatus);
			}
			break;
			
			case 'forcesurrender':
			{
				$forceremarks = $_POST['forceremarks'];
				$emailtocustomer = $_POST['emailtocustomer'];
				$businessname = $_POST['compname'];
				$customerid = $_POST['custid'];
				$emailid1 = $_POST['custmailid'];
				
				//Fetching records to insert into inv_surrenderproduct table
				$query1="select count(inv_customerproduct.cardid) as cardidcount,
				inv_customerproduct.cardid,getPINNo(cardid) AS pinno,inv_customerproduct.date,inv_customerproduct.time,
				inv_customerproduct.HDDID,inv_customerproduct.ETHID,inv_customerproduct.COMPUTERNAME,
				inv_customerproduct.COMPUTERIP,inv_customerproduct.REGTYPE,inv_mas_product.productname as productname,
				inv_mas_dealer.emailid AS dlremail,inv_mas_dealer.contactperson AS dlrname from inv_customerproduct
				left join inv_mas_product on left(inv_customerproduct.computerid, 3) = inv_mas_product.productcode 
				left join inv_mas_dealer on inv_customerproduct.dealerid = inv_mas_dealer.slno 
				where inv_customerproduct.slno = '".$refslno."'";
				#$query1 = "select count(cardid) as cardidcount,cardid,HDDID,ETHID,COMPUTERNAME,COMPUTERIP,REGTYPE from inv_customerproduct where slno = '".$refslno."'";
				$fetchresult = runmysqlqueryfetch($query1);
				$cardidcount = $fetchresult['cardidcount'];
				$REGDATE = $fetchresult['date'].' '.$fetchresult['time'];
				$cardid = $fetchresult['cardid'];
				$HDDID = $fetchresult['HDDID'];
				$ETHID = $fetchresult['ETHID'];
				$COMPUTERNAME = $fetchresult['COMPUTERNAME'];
				$COMPUTERIP = $fetchresult['COMPUTERIP'];
				$REGTYPE = $fetchresult['REGTYPE'];
				$pinno = $fetchresult['pinno'];
				$dlremail = $fetchresult['dlremail'];
				$productname = $fetchresult['productname'];
				$dlrname = $fetchresult['dlrname'];
				##$CREATEDBY = 'IU-'.$userid;
				$CREATEDBY = '';
				if($cardidcount <> 0)
				{
					if($HDDID != '' || $ETHID != '')
					{
						$query = "Insert into inv_surrenderproduct(refslno,REGDATE,HDDID,ETHID, REGTYPE,COMPUTERNAME,COMPUTERIP,CREATEDBY,surrendertime,networkip,systemip,forces,forceremarks,userref) 
						values ('".$refslno."','".$REGDATE."','".$HDDID."','".$ETHID."','".$REGTYPE."','".$COMPUTERNAME."','".$COMPUTERIP."','".$CREATEDBY."','".date('Y-m-d').' '.date('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','".$_SERVER['REMOTE_ADDR']."','1','".$forceremarks."','".$userid."');";
						$result = runmysqlquery($query);
						
						
						$query1 = "UPDATE inv_mas_scratchcard SET registered = 'no' WHERE cardid = '".$cardid."'";
						$result1 = runmysqlquery($query1);
						
						$query2 = "UPDATE inv_customerproduct SET HDDID = '',ETHID = '',COMPUTERNAME = '',COMPUTERIP = '',CREATEDBY = '' WHERE cardid = '".$cardid."'";
						$result2 = runmysqlquery($query2);
						
						$query3 = "select refslno, 
						SUM(CASE WHEN forces = 0 THEN 1 ELSE 0 END) AS onlinecount,  
						SUM(CASE WHEN forces = 1 THEN 1 ELSE 0 END) AS forcecount
						from inv_surrenderproduct where refslno = '".$refslno."'";
						$fetchresult3 = runmysqlqueryfetch($query3);
						$onlinecount = $fetchresult3['onlinecount'];
						$forcecount = $fetchresult3['forcecount'];
						if($forcecount == 0)
						{ $forcecount = 'Not Surrender Yet'; }
						else if($forcecount > 1)
						{ $forcecount = $fetchresult3['forcecount'].' Times'; }
						else
						{ $forcecount = $fetchresult3['forcecount'].' Time'; }
						
						$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) 
						values('".$userid."','".$_SERVER['REMOTE_ADDR']."','252','".date('Y-m-d').' '.date('H:i:s')."','".$lastslno."')";
						$eventresult = runmysqlquery($eventquery);
						##Sending Mail##
						sendsurrendermail();
						$message = '3^Cardid: '.$cardid.' Got Surrender Now Customer can Register!!';
					}
					else
					{
						$message = '2^Card ID Cannot be Surrender!! Not Yet Registered!!';
					}
				}
				echo($message);
			}
			break;
		}
		
	}
	
	break;
	
	case 'rcidetailsgrid':
	{
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$lastslno = $_POST['lastslno'];
		$resultcount = "select customerid,registeredname,inv_mas_product.productname,pinnumber,computerid,productversion,operatingsystem,processor,`date`,servicename from inv_logs_webservices 
		left join inv_mas_product on inv_mas_product.productcode = left(inv_logs_webservices.computerid,3) where right(customerid,5) = '".$lastslno."' order by `date`  desc; ";
		$resultfetch = runmysqlquery_old($resultcount);
		$fetchresultcount = mysql_num_rows($resultfetch);
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
			$startlimit = $slno ;
			$slno = $slno;
		}
		$query = "select `date`,inv_mas_product.productname,productversion,operatingsystem,processor,registeredname,pinnumber,computerid,servicename from inv_logs_webservices left join inv_mas_product on inv_mas_product.productcode = left(inv_logs_webservices.computerid,3) where right(customerid,5) = '".$lastslno."' order by `date`  desc LIMIT ".$startlimit.",".$limit."; ";
		$result = runmysqlquery_old($query);
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" style="font-size:12px">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Version</td><td nowrap = "nowrap" class="td-border-grid" align="left">Operating System</td><td nowrap = "nowrap" class="td-border-grid" align="left">Processor</td><td nowrap = "nowrap" class="td-border-grid" align="left">Registered Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">PIN Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Computer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Service Name</td></tr>';
		}
		
		$i_n = 0;
		while($fetch = mysql_fetch_row($result))
		{
			$i_n++;
			$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow1" bgcolor='.$color.'>';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slno."</td>";
			for($i = 0; $i < count($fetch); $i++)
			{
				
				if($i == 0)
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch[$i])."</td>";
				else
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch[$i]."</td>";
			}
			$grid .= "</tr>";
		}
		$grid .= "</table>";

		$fetchcount = mysql_num_rows($result);
		if($slno >= $fetchresultcount)
		$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
		$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="getmorercidetails(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'more\');" style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmorercidetails(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'all\');" class ="resendtext1" style="cursor:pointer"><font color= "#000000">(Show All Records)</font></a></div></td></tr></table>';
		
	
		echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid;
	}
	break;
	case 'invoicedetailsgrid':
	{
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$lastslno = $_POST['lastslno'];
		$query = "select customerid from inv_mas_customer where slno = '".$lastslno."';";
		$resultfetch= runmysqlqueryfetch($query);
		$customerref = cusidcombine($resultfetch['customerid']);
		$resultcount = "select customerid,businessname,contactperson,description,invoiceno,dealername,createddate,amount,servicetax,netamount,purchasetype
from inv_invoicenumbers where customerid = '".$customerref."'order by createddate  desc; ";
		$resultfetch = runmysqlquery($resultcount);
		$fetchresultcount = mysql_num_rows($resultfetch);
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
			$startlimit = $slno ;
			$slno = $slno;
		}
		$query = "select slno,createddate,invoiceno,netamount,createdby,status,sbtax
from inv_invoicenumbers where customerid = '".$customerref."'order by createddate  desc LIMIT ".$startlimit.",".$limit."; ";
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" style="font-size:12px">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Received Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Outstanding Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Status</td><td nowrap = "nowrap" class="td-border-grid" align="left">Generated By</td><td nowrap = "nowrap" class="td-border-grid" align="left">Action</td></tr>';
		}
		
		$i_n = 0;
		while($fetch = mysql_fetch_array($result))
		{
			$i_n++;
			$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$query1 = "select sum(receiptamount) as receivedamount from inv_mas_receipt where invoiceno = '".$fetch['slno']."' and status != 'CANCELLED';";
			$resultfetch = runmysqlqueryfetch($query1);
			if($resultfetch['receivedamount'] == '')
			{
				$receivedamount = 0;
			}
			else
			{
				$receivedamount = $resultfetch['receivedamount'];
			}
			$balanceamount = $fetch['netamount'] - $receivedamount;
			$grid .= '<tr class="gridrow1" bgcolor='.$color.'>';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slno."</td> ";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch['createddate'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['invoiceno']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['netamount']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$receivedamount."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$balanceamount."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['status']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['createdby']."</td>";
			//$incoiceno = $fetch[1];
			//$invoicenosplit = explode('/',$incoiceno );
			$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewinvoice(\''.$fetch['slno'].'\');" class="resendtext"> View >></a> </td>';
			$grid .= "</tr>";
		}
		$grid .= "</table>";

		$fetchcount = mysql_num_rows($result);
		if($slno >= $fetchresultcount)
		$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
		$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="getmoreinvoicedetails(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'more\');" style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmoreinvoicedetails(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'all\');" class ="resendtext1" style="cursor:pointer"><font color= "#000000">(Show All Records)</font></a></div></td></tr></table>';
		
	
		echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$query1;
	}
	break;
	case 'customeralertsave':
	{
		$lastslno = $_POST['lastslno'];
		$subject = $_POST['alertsubject'];
		$content = $_POST['alertcontent'];
		
		$query = "insert into inv_customeralerts (subject, content, customerreference,entereddate,enteredby,enteredip,messagestatus	) values( '".$subject."',	'".$content."', '".$lastslno."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','unread')";
		$result = runmysqlquery($query);
		echo(json_encode('1^Alert saved successfully.'));
	}
	break;
	case 'customeralertdelete':
	{
		$messageid = $_POST['messageid'];
	
		$query = "Delete from inv_customeralerts where slno = '".$messageid."'";
		$result = runmysqlquery($query);
		echo(json_encode('1^Alert Deleted successfully.'));
	}
	break;
	case 'customeralertgridtoform':
	{
		$customeralertgridtoformarray = array();
		$messageid = $_POST['messageid'];
		$query ="select * from inv_customeralerts where slno = '".$messageid."'; ";
		$resultfetch = runmysqlqueryfetch($query);
		$customeralertgridtoformarray['errorcode'] = '1';
		$customeralertgridtoformarray['subject'] = $resultfetch['subject'];
		$customeralertgridtoformarray['content'] = $resultfetch['content'];
		echo(json_encode($customeralertgridtoformarray));
	}
	break;
	case 'customeralertgrid':
	{
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$lastslno = $_POST['lastslno'];
		$resultcount = "select inv_customeralerts.subject,inv_customeralerts.content from inv_customeralerts where customerreference = '".$lastslno."' order by entereddate desc; ";
		$resultfetch = runmysqlquery($resultcount);
		$fetchresultcount = mysql_num_rows($resultfetch);
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
			$startlimit = $slno ;
			$slno = $slno;
		}
		$query = "select inv_customeralerts.subject,inv_customeralerts.content,inv_mas_users.fullname,inv_customeralerts.slno,inv_customeralerts.entereddate from inv_customeralerts left join inv_mas_users on inv_customeralerts.enteredby = inv_mas_users.slno where customerreference = '".$lastslno."' order by entereddate desc LIMIT ".$startlimit.",".$limit."; ";
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" style="font-size:12px">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Subject</td><td nowrap = "nowrap" class="td-border-grid" align="left">Content</td><td nowrap = "nowrap" class="td-border-grid" align="left">Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Entered By</td></tr>';
		}
		
		$i_n = 0;
		while($fetch = mysql_fetch_array($result))
		{
			$i_n++;
			$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow" bgcolor='.$color.' onclick ="customeralertgridtoform(\''.$fetch['slno'].'\');">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slno."</td> ";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['subject'])."</td>";
			$grid .= '<td nowrap="nowrap" class="td-border-grid" align="left">'.gridtrimalert($fetch['content']).'</td>';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch['entereddate'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['fullname']."</td>";
			$grid .= "</tr>";
		}
		$grid .= "</table>";

		$fetchcount = mysql_num_rows($result);
		if($slno >= $fetchresultcount)
		$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
		$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="generatecustomeralertgrid(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'more\');" style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmorecustomeralertgrid(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'all\');" class ="resendtext1" style="cursor:pointer"><font color= "#000000">(Show All Records)</font></a></div></td></tr></table>';
		
	
		echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$query1;
	}
	break;

	case 'pininform':
	{
		$responsearray10 = array();
		
		$PIN = $_POST['pinumber'];
		
		$query1 = "select cardid from inv_mas_scratchcard WHERE scratchnumber = '".$PIN."'";
		$fetch1 = runmysqlqueryfetch($query1);
		$PINcardid = $fetch1['cardid'];
		
		$query = "SELECT distinct inv_dealercard.cardid , inv_mas_scratchcard.scratchnumber, 
		inv_mas_scratchcard.blocked,inv_mas_scratchcard.cancelled,inv_mas_dealer.businessname as attachedto, 
		inv_mas_dealer.slno as dealerid, inv_mas_product.productcode, inv_mas_product.productname, 
		inv_dealercard.purchasetype, inv_dealercard.usagetype, inv_dealercard.date as attachdate, 
		inv_customerproduct.date as registereddate, inv_customerproduct.businessname as registeredto,inv_dealercard.scheme ,
		inv_mas_scheme.schemename as schemename ,inv_mas_customer.businessname as businessname from inv_dealercard 
		left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid 
		left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno 
		left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode 
		left join 
		(select cardid, customerreference, min(`date`) as `date` ,inv_mas_customer.businessname as businessname
		from inv_customerproduct 
		left join inv_mas_customer on  inv_customerproduct.customerreference = inv_mas_customer.slno 
		GROUP BY cardid) 
		AS inv_customerproduct on  inv_dealercard.cardid = inv_customerproduct.cardid 
		left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme 
		left join inv_mas_customer on  inv_dealercard.customerreference = inv_mas_customer.slno 
		where inv_dealercard.cardid = '".$PINcardid."'";
		$results = runmysqlquery($query);
		$fetch = mysql_fetch_array($results);
		//$fetch = runmysqlqueryfetch($query);
		if($fetch['purchasetype'] == '' && $fetch['usagetype'] == ''  && $fetch['attachedto'] == '' && $fetch['dealerid'] == '' && $fetch['productcode'] == '' && $fetch['productname'] == '' && $fetch['registereddate'] == ''&& $fetch['attachdate'] == '' && $fetch['registeredto'] == '' && $fetch['remarks'] == '')
		{
			$queyscratch="select scratchnumber from inv_mas_scratchcard where scratchnumber = '".$PIN."'";
			$resultscratch = runmysqlqueryfetch($queyscratch);
			$fetch['scratchnumber'] = $PIN;
			$fetch['cardid'] = $resultscratch['cardid'];
			$fetch['usagetype'] = 'Not Available';
			$fetch['attachedto'] = 'Not Available';
			$fetch['dealerid'] = 'Not Available';
			$fetch['productcode'] = 'Not Available';
			$fetch['productname'] = 'Not Available';
			$fetch['registeredto'] = 'Not Available';
			$fetch['remarks'] = 'Not Available';
		}
		else
			
		$attcheddate = substr($fetch['attachdate'] ,0,10);
		$registereddate =$fetch['registereddate'];
		if($registereddate != '')
			$registereddate = changedateformat($registereddate);
		if($fetch['blocked'] == 'yes')
			$cardstatus = 'Blocked';
		else if($fetch['cancelled'] == 'yes')
			$cardstatus = 'Cancelled';
		else
		{
			$cardstatus = 'Active';
		}
		$responsearray10['cardid'] = $fetch['cardid'];
		$responsearray10['scratchnumber'] = $fetch['scratchnumber'];
		$responsearray10['purchasetype'] = $fetch['purchasetype'];
		$responsearray10['usagetype'] = $fetch['usagetype'];
		$responsearray10['attachedto'] = $fetch['attachedto'];
		$responsearray10['dealerid'] = $fetch['dealerid'];
		$responsearray10['productcode'] = $fetch['productcode'];
		$responsearray10['productname'] = $fetch['productname'];
		$responsearray10['attcheddate'] = changedateformat($attcheddate);
		$responsearray10['registereddate'] = $fetch['registereddate'];
		$responsearray10['registeredto'] = $fetch['registeredto'];
		$responsearray10['cardstatus'] = $cardstatus;
		$responsearray10['remarks'] = $fetch['remarks'];
		$responsearray10['schemename'] = $fetch['schemename'];
		$responsearray10['businessname'] = $fetch['businessname'];

		
		//echo($fetch['cardid'].'^'.$fetch['scratchnumber'].'^'.$fetch['purchasetype'].'^'.$fetch['usagetype'].'^'.$fetch['attachedto'].'^'.$fetch['dealerid'].'^'.$fetch['productcode'].'^'.$fetch['productname'].'^'.changedateformat($attcheddate).'^'.$registereddate.'^'.$fetch['registeredto'].'^'.$cardstatus.'^'.$fetch['remarks'].'^'.$fetch['schemename'].'^'.$fetch['businessname']);
		echo(json_encode($responsearray10));
	}
	break;


}

function generatecustomerid($customerid,$productcode,$delaerrep)
{
	$query = "SELECT * FROM inv_mas_customer where slno = '".$customerid."'";
	$fetch = runmysqlqueryfetch($query);
	$district = $fetch['district'];
	
	$query = runmysqlqueryfetch("SELECT distinct statecode from inv_mas_district where districtcode = '".$district."'");
	$cusstatecode = $query['statecode'];
	$newcustomerid = $cusstatecode.$district.$delaerrep.$productcode.$customerid;
	return $newcustomerid;
}



/*function freeupdateproductcode($productcode)
{
	switch($productcode)
	{
		case '638': return '639'; break;
		case '647': return '650'; break;
		case '648': return '651'; break;
		case '349': return '350'; break;
		case '304': return '305'; break;
		case '469': return '472'; break;
		case '471': return '473'; break;
		case '470': return '474'; break;
		case '511': return '512'; break;
		default: return 'codenotfound';
	}
}

function sendfreeupdationcardemail($cusattachedcard,$customerproductslno)
{
		
		$query1 = "select inv_mas_scratchcard.scratchnumber as pinno,inv_mas_dealer.businessname as dealername,inv_mas_dealer.emailid as dealeremailid,inv_mas_product.productname as newproductname from inv_dealercard left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealercard.dealerid 
left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode where inv_dealercard.cardid = '".$cusattachedcard."' ";
		$resultfetch = runmysqlqueryfetch($query1);
		$pinno = $resultfetch['pinno'];
		$dealername = $resultfetch['dealername'];
		$newproductname = $resultfetch['newproductname'];
		$dealeremailid = $resultfetch['dealeremailid'];
		
		$query = "Select inv_mas_customer.businessname as businessname,inv_mas_customer.contactperson as contactperson,inv_mas_customer.place as place,inv_mas_customer.emailid as emailid,inv_mas_customer.customerid as customerid, inv_mas_product.productname as oldproductname ,inv_customerproduct.date as regdate from inv_customerproduct Left join inv_mas_customer on inv_mas_customer.slno = inv_customerproduct.customerreference left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_customerproduct.cardid	left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)	where inv_customerproduct.slno = '".$customerproductslno."'";
	$result = runmysqlqueryfetch($query);
	
	$regdate = $result['regdate'];
	$businessname = $result['businessname'];
	$contactperson = $result['contactperson'];
	$place = $result['place'];
	$customerid = $result['customerid'];
	$oldproductname = $result['oldproductname'];
	$emailid = $result['emailid'];

		//Dummy line to override To email ID
	//$emailid = 'rashmi.hk@relyonsoft.com';
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);
	
		// CC mail to dealer 
	$ccemailarray = explode(',',$dealeremailid);
	$ccemailcount = count($ccemailarray);
	
	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
				$emailids[$emailarray[$i]] = $emailarray[$i];
		}
	}

	for($i = 0; $i < $ccemailcount; $i++)
	{
		if(checkemailaddress($ccemailarray[$i]))
		{
			$ccemailids[$ccemailarray[$i]] = $ccemailarray[$i];
		}
	}
	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/customercardinfo.htm");
	$textmsg = file_get_contents("../mailcontents/customercardinfo.txt");
	$date = datetimelocal('d-m-Y');
	$array = array();
	$array[] = "##DATE##%^%".changedateformat($regdate);
	$array[] = "##NAME##%^%".$contactperson;
	$array[] = "##COMPANY##%^%".$businessname;
	$array[] = "##PLACE##%^%".$place;
	$array[] = "##CUSTOMERID##%^%".cusidcombine($customerid);
	$array[] = "##OLDPRODUCTNAME##%^%".$oldproductname;
	$array[] = "##NEWPRODUCTNAME##%^%".$newproductname;
	$array[] = "##SCRATCHCARDNO##%^%".$pinno;
	$array[] = "##CARDID##%^%".$cusattachedcard;
	$array[] = "##DEALERNAME##%^%".$dealername;
	
	$filearray = array(
		array('../images/relyon-logo.jpg','inline','1234567890')
	);
	$toarray = $emailids;
	$bccemailids['vijaykumar'] ='vijaykumar@relyonsoft.com';
	$bccarray = $bccemailids;
	$ccarray = $ccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = "Free PIN No for ".$newproductname." (2010-11)";
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray); 
						
}*/
function surrender($PIN,$lastslno)
{
	$fetchresult07 = "";
	$query07 ="select inv_customerproduct.customerreference, inv_customerproduct.slno as slno ,getPINNo(inv_customerproduct.cardid) as pin, 	inv_surrenderproduct.surrendertime,inv_surrenderproduct.networkip, inv_surrenderproduct.systemip 
	from inv_customerproduct 
	INNER JOIN inv_surrenderproduct ON inv_surrenderproduct.refslno=inv_customerproduct.slno where inv_customerproduct.customerreference='".$lastslno."' 
	and getPINNo(inv_customerproduct.cardid)= '".$PIN."'"; 
		$result = runmysqlquery($query07);
		$surrendercount = mysql_num_rows($result);
		$fetchresult07[0] = $surrendercount;
		
		$query08 = "select getPINNo(inv_dealercard.cardid) as Pin,inv_dealercard.usagetype as usagetype 
	,getPINNo(inv_customerproduct.cardid) AS cardid from inv_dealercard 
	left join inv_customerproduct on inv_customerproduct.cardid = inv_dealercard.cardid
	where inv_dealercard.customerreference = '".$lastslno."' and getPINNo(inv_dealercard.cardid) = '".$PIN."'";
	
	/*$query08 = "select getPINNo(inv_dealercard.cardid) as Pin,inv_dealercard.usagetype as usagetype 
	,getPINNo(inv_customerproduct.cardid) AS cardid,inv_customerproduct.REGTYPE from inv_dealercard 
	left join inv_customerproduct on inv_customerproduct.cardid = inv_dealercard.cardid
	left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_customerproduct.cardid
	where inv_dealercard.customerreference = '".$lastslno."' and inv_mas_scratchcard.scratchnumber = '".$PIN."'";*/
	$fetch = runmysqlqueryfetch($query08);
	$fetchresult07[1] = $fetch['usagetype'];
	//$fetchresult07[3] = $fetch['REGTYPE'];
	
	## Changed By Bhavesh Patel  20.16.13 16:52##
	$query1 = "select * from inv_customerproduct where customerreference = '".$lastslno."' and getPINNo(inv_customerproduct.cardid)= '".$PIN."' and AUTOREGISTRATIONYN = 'Y' and HDDID <> ''";
	$result1 = runmysqlquery($query1);
	$registeredcount = mysql_num_rows($result1);
	$fetchresult07[2] = $registeredcount;
	return $fetchresult07;
	
}
?>