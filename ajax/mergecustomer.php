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

$type = $_POST['type'];

switch($type)
{
	case 'mergecustomer':
	{
		$customeridfrom = $_POST['customerfrom'];
		$customeridto = $_POST['customerto'];
		$businessnamefrom = $_POST['businessnamefrom'];
		$businessnameto = $_POST['businessnameto'];
		$businessname = $_POST['businessname'];
		$address = $_POST['address'];
		$place = $_POST['place'];
		$pincode = $_POST['pincode'];
		$remarks = $_POST['remarks'];
		$district = $_POST['district'];
		$region = $_POST['region'];
		$category = $_POST['categoryvalue'];
		$type = $_POST['typevalue'];
		$fax = $_POST['fax'];
		$stdcode = $_POST['stdcode'];
		$website = $_POST['website'];
		$currentdealer = $_POST['currentdealer'];
		$branch = $_POST['branch'];
		$contactarray = $_POST['contactarray'];
		$totalarray = $_POST['deletearray'];
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

		if($customeridfrom == $customeridto)
		{
			echo('2^'.'Please select a diffferent Customer ID.');
			exit;
		}
		else
		{
			$query456 = "Select * from inv_mas_customer where slno = '".$customeridfrom."';";
			$fetchresult = runmysqlqueryfetch($query456);
			$frmcustomerid = cusidcombine($fetchresult['customerid']);
			$frmcusid = $fetchresult['customerid'];
			
			$query45 = "Select * from inv_mas_customer where slno = '".$customeridto."';";
			$fetchval = runmysqlqueryfetch($query45);
			$tocustomerid = cusidcombine($fetchval['customerid']);
			$tocusid = $fetchval['customerid'];
			
			
			$query1 ="SELECT customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$customeridfrom."'; ";
			$resultfetch = runmysqlquery($query1);
			$valuecount = 0;
			while($fetchres = mysqli_fetch_array($resultfetch))
			{
				if($valuecount > 0)
					$fromcontactarray .= '*';
				
				$selectiontype = $fetchres['selectiontype'];
				$contactperson = $fetchres['contactperson'];
				$phone = $fetchres['phone'];
				$cell = $fetchres['cell'];
				$emailid = $fetchres['emailid'];
				
				$fromcontactarray .= $selectiontype.'#'.$contactperson.'#'.$phone.'#'.$cell.'#'.$emailid;
				$valuecount++;
			}
			$recordflag1 = recordcheck($customeridfrom,'customerreference','inv_customerproduct');
			$recordflag2 = recordcheck($customeridfrom,'customerreference','inv_customeramc');
			$recordflag3 = recordcheck($customeridfrom,'customerid','inv_customerinteraction');
			$recordflag4 = recordcheck($customeridfrom,'customerid','inv_customerreqpending');
			$recordflag5 = recordcheck($customeridfrom,'custreferences','inv_custpaymentreq');
			$recordflag6 = recordcheck($customeridfrom,'customerreference','inv_dealercard');
			$recordflag7 = recordcheck($customeridfrom,'customerreference','inv_hardwarelock');
			$recordflag8 = recordcheck($customeridfrom,'customerid','inv_logs_save');
			$recordflag9 = recordcheck($customeridfrom,'userreference','inv_logs_sms_delete');
			$recordflag10 = recordcheck($customeridfrom,'customerref','inv_logs_softkeygen');
			$recordflag11 = recordcheck($customeridfrom,'userreference','inv_sms_bill');
			$recordflag12 = recordcheck($customeridfrom,'userreference','inv_smsactivation');
			$recordflag13 = recordcheck($customeridfrom,'custreference','pre_online_purchase');
			$recordflag14 = recordcheck($customeridfrom,'customerid','ssm_callregister');
			$recordflag15 = recordcheck($customeridfrom,'customerid','ssm_emailregister');
			$recordflag16 = recordcheck($customeridfrom,'customerid','ssm_errorregister');
			$recordflag17 = recordcheck($customeridfrom,'customerid','ssm_inhouseregister');
			$recordflag18 = recordcheck($customeridfrom,'customerid','ssm_invoice');
			$recordflag19 = recordcheck($customeridfrom,'customerid','ssm_onsiteregister');
			$recordflag20 = recordcheck($customeridfrom,'customerid','ssm_receipts');
			$recordflag21 = recordcheck($customeridfrom,'customerid','ssm_referenceregister');
			$recordflag22 = recordcheck($customeridfrom,'customerid','ssm_requirementregister');
			$recordflag23 = recordcheck($customeridfrom,'customerid','ssm_skyperegister');
			$recordflag24 = recordcheck($customeridfrom,'customerid','inv_crossproduct');
			$recordflag25 = recordcheck($customeridfrom,'customerid','inv_crossproductstatus');
			$recordflag26 = recordcheck($customeridfrom,'customerid','inv_contactreqpending');
			$recordflag27 = recordcheck($customeridfrom,'customerid','inv_logs_crossproductupdatelogs');
			$recordflag28 = recordcheck($customeridfrom,'customerreference','inv_mas_receipt');
			$recordflag29 = recordcheck($customeridfrom,'customerreference','dealer_online_purchase');
			$recordflag30 = recordcheck($frmcustomerid,'customerid','inv_invoicenumbers');
			$recordflag31 = recordcheck($frmcusid,'customerid','inv_logs_webservices');
			$recordflag32 = recordcheck($customeridfrom,'customerreference','inv_customeralerts');
			$recordflag33 = recordcheck($frmcustomerid,'customerid','imp_cfentries');
			$recordflag34 = recordcheck($customeridfrom,'customerreference','imp_implementation');
			$recordflag35 = recordcheck($customeridfrom,'customerreference','imp_logs_implementation');
			$recordflag36 = recordcheck($customeridfrom,'customerreference','imp_rafiles');
			
			
			if($recordflag1 == true)
			{
				$query11 = "update inv_customerproduct set customerreference = '".$customeridto."' where customerreference = '".$customeridfrom."';";
				$result11 = runmysqlquery($query11);
			}
			if($recordflag2 == true)
			{
				$query12 ="update inv_customeramc set customerreference = '".$customeridto."' where customerreference = '".$customeridfrom."';";
				$result12 = runmysqlquery($query12);
			}
			if($recordflag3 == true)
			{
				$query13 = "update inv_customerinteraction set customerid = '".$customeridto."' where customerid = '".$customeridfrom."';";
				$result13 = runmysqlquery($query13);
			}
			if($recordflag4 == true)
			{
				$query14 ="update inv_customerreqpending set customerid = '".$customeridto."' where customerid = '".$customeridfrom."';";
				$result14 = runmysqlquery($query14);
			}
			if($recordflag5 == true)
			{
				$query15 ="update inv_custpaymentreq set custreferences = '".$customeridto."' where custreferences = '".$customeridfrom."';";
				$result15 = runmysqlquery($query15);
			}
			if($recordflag6 == true)
			{
				$query16 ="update inv_dealercard set customerreference = '".$customeridto."' where customerreference = '".$customeridfrom."';";
				$result16 = runmysqlquery($query16);
			}
			if($recordflag7 == true)
			{
				$query17 ="update inv_hardwarelock set customerreference = '".$customeridto."' where customerreference = '".$customeridfrom."';";
				$result17 = runmysqlquery($query17);
			}
			if($recordflag8 == true)
			{
				$query18 = "update inv_logs_save set customerid = '".$customeridto."' where customerid = '".$customeridfrom."';";
				$result18 = runmysqlquery($query18);
			}
			if($recordflag9 == true)
			{
				$query19 = "update inv_logs_sms_delete set userreference = '".$customeridto."' where userreference = '".$customeridfrom."';";
				$result19 = runmysqlquery($query19);
			}
			if($recordflag10 == true)
			{
				$query20 = "update inv_logs_softkeygen set customerref = '".$customeridto."' where customerref = '".$customeridfrom."';";
				$result20 = runmysqlquery($query20);
			}
			if($recordflag11 == true)
			{
				$query21 = "update inv_sms_bill set userreference = '".$customeridto."' where userreference = '".$customeridfrom."';";
				$result21 = runmysqlquery($query21);
			}
			if($recordflag12 == true)
			{
				$query22 = "update inv_smsactivation set userreference = '".$customeridto."' where userreference = '".$customeridfrom."';";
				$result22 = runmysqlquery($query22);
			}
			if($recordflag13 == true)
			{
				$query23 = "update pre_online_purchase set custreference = '".$customeridto."' where custreference = '".$customeridfrom."';";
				$result23 = runmysqlquery($query23);
			}
			if($recordflag14 == true)
			{
				$query24 = "update ssm_callregister set customerid = '".$customeridto."' where customerid = '".$customeridfrom."';";
				$result24 = runmysqlquery($query24);
			}
			if($recordflag15 == true)
			{
				$query25 = "update ssm_emailregister set customerid = '".$customeridto."' where customerid = '".$customeridfrom."';";
				$result25 = runmysqlquery($query25);
			}
			if($recordflag16== true)
			{
				$query26 = "update ssm_errorregister set customerid = '".$customeridto."' where customerid = '".$customeridfrom."';";
				$result26 = runmysqlquery($query26);
			}
			if($recordflag17== true)
			{
				$query27 = "update ssm_inhouseregister set customerid = '".$customeridto."' where customerid = '".$customeridfrom."';";
				$result27 = runmysqlquery($query27);
			}
			if($recordflag18== true)
			{
				$query28 = "update ssm_invoice set customerid = '".$customeridto."' where customerid = '".$customeridfrom."';";
				$result28 = runmysqlquery($query28);
			}
			if($recordflag19== true)
			{
				$query29 = "update ssm_onsiteregister set customerid = '".$customeridto."' where customerid = '".$customeridfrom."';";
				$result29 = runmysqlquery($query29);
			}
			if($recordflag20== true)
			{
				$query30 = "update ssm_receipts set customerid = '".$customeridto."' where customerid = '".$customeridfrom."';";
				$result30 = runmysqlquery($query30);
			}
			if($recordflag21== true)
			{
				$query31 = "update ssm_referenceregister set customerid = '".$customeridto."' where customerid = '".$customeridfrom."';";
				$result31 = runmysqlquery($query31);
			}
			if($recordflag22== true)
			{
				$query32 = "update ssm_requirementregister set customerid = '".$customeridto."' where customerid = '".$customeridfrom."';";
				$result32 = runmysqlquery($query32);
			}
			if($recordflag23== true)
			{
				$query33 = "update ssm_skyperegister set customerid = '".$customeridto."' where customerid = '".$customeridfrom."';";
				$result33 = runmysqlquery($query33);
			}
			if($recordflag24== true)
			{
				$query34 = "update inv_crossproduct set customerid = '".$customeridto."' where customerid = '".$customeridfrom."';";
				$result34 = runmysqlquery($query34);
			}
			
			if($recordflag25== true)
			{
				$query35 = "update inv_crossproductstatus set customerid = '".$customeridto."' where customerid = '".$customeridfrom."';";
				$result35 = runmysqlquery($query35);
			}
			if($recordflag26== true)
			{
				$query36 = "update inv_contactreqpending set customerid = '".$customeridto."' where customerid = '".$customeridfrom."';";
				$result36 = runmysqlquery($query36);
			}
			
			if($recordflag27== true)
			{
				$query37 = "update inv_logs_crossproductupdatelogs set customerid = '".$customeridto."' where customerid = '".$customeridfrom."';";
				$result37 = runmysqlquery($query37);
			}
			if($recordflag28== true)
			{
				$query28 = "update inv_mas_receipt set customerreference = '".$customeridto."' where customerreference = '".$customeridfrom."';";
				$result28 = runmysqlquery($query28);
			}
			if($recordflag29== true)
			{
				
				$query39 = "update dealer_online_purchase set customerreference = '".$customeridto."' where customerreference = '".$customeridfrom."';";
				$result39 = runmysqlquery($query39);
			}
			if($recordflag30== true)
			{
			
				$query40 = "update inv_invoicenumbers set customerid = '".$tocustomerid."' where customerid = '".$frmcustomerid."';";
				$result40 = runmysqlquery($query40);
			}
			if($recordflag31== true)
			{
				$query41 = "update inv_logs_webservices set customerid = '".$tocusid."' where customerid = '".$frmcusid."';";
				$result41 = runmysqlquery($query41);
			}
			if($recordflag32== true)
			{
				
				$query42 = "update inv_customeralerts set customerreference = '".$customeridto."' where customerreference = '".$customeridfrom."';";
				$result42 = runmysqlquery($query42);
			}
			if($recordflag33== true)
			{
				
				$query43 = "update imp_cfentries set customerid = '".$tocustomerid."' where customerid = '".$frmcustomerid."';";
				$result43 = runmysqlquery($query43);
			}
			if($recordflag34== true)
			{
				
				$query44 = "update imp_implementation set customerreference = '".$customeridto."' where customerreference = '".$customeridfrom."';";
				$result44 = runmysqlquery($query44);
			}
			if($recordflag35== true)
			{
				
				$query45 = "update imp_logs_implementation set customerreference = '".$customeridto."' where customerreference = '".$customeridfrom."';";
				$result45 = runmysqlquery($query45);
			}
			if($recordflag36== true)
			{
				
				$query46 = "update imp_rafiles set customerreference = '".$customeridto."' where customerreference = '".$customeridfrom."';";
				$result46 = runmysqlquery($query46);
			}
			
			
			$queryuu = "Insert into inv_logs_customermerge(fromcompany,fromaddress, fromplace,frompincode,fromdistrict,fromregion,fromcategory,fromtype,fromstdcode,fromwebsite,fromcurrentdealer,fromfax,frombranch,mergeto,mergedby,mergedatetime,mergefrom,mergetype,fromcontactperson,fromremarks) values ('".$fetchresult['businessname']."','".addslashes($fetchresult['address'])."','".$fetchresult['place']."','".$fetchresult['pincode']."','".$fetchresult['district']."','".$fetchresult['region']."','".$fetchresult['category']."','".$fetchresult['type']."','".$fetchresult['stdcode']."','".$fetchresult['website']."','".$fetchresult['currentdealer']."','".$fetchresult['fax']."','".$fetchresult['branch']."','".$customeridto."','".$userid."','".date('Y-m-d').' '.date('H:i:s')."','".$customeridfrom."','singlemerge','".$fromcontactarray."','".$fetchresult['remarks']."');";
			$resultuu = runmysqlquery($queryuu);
				
			$query34 = "UPDATE inv_mas_customer SET businessname = '".trim($businessname)."',address = '".addslashes($address)."',place = '".$place."',pincode = '".$pincode."',district = '".$district."',region = '".$region."',category = '".$category."',type = '".$type."',stdcode = '".$stdcode."',website = '".$website."',currentdealer = '".$currentdealer."',fax ='".$fax."',branch = '".$branch."',remarks='".$remarks."' WHERE slno = '".$customeridto."'";
			$result34 = runmysqlquery($query34);
				
			for($i=0;$i<count($totalsplit);$i++)
			{
				$deleteslno = $totalsplit[$i];
				$query22 = "DELETE FROM inv_contactdetails WHERE slno = '".$deleteslno."'";
				$result996 = runmysqlquery($query22);
			}
			for($j=0;$j<count($contactressplit);$j++)
			{
				$selectiontype = $contactressplit[$j][0];
				$contactperson = trim($contactressplit[$j][1]);
				$phone = trim($contactressplit[$j][2]);
				$cell = trim($contactressplit[$j][3]);
				$emailid = trim($contactressplit[$j][4]);
				$slno = trim($contactressplit[$j][5]);
				
				if($slno <> '')
				{
					$queryres = "UPDATE inv_contactdetails SET contactperson = '".$contactperson."',phone = '".$phone."',cell = '".$cell."',emailid = '".$emailid."',selectiontype = '".$selectiontype."',customerid = '".$customeridto."' WHERE slno = '".$slno."'";
					$resultres = runmysqlquery($queryres);
				}
				else
				{
					$query21tt = "Insert into inv_contactdetails(customerid,selectiontype,contactperson,phone,cell,emailid) values  ('".$customeridto."','".$selectiontype."','".$contactperson."','".$phone."','".$cell."','".$emailid."');";
					$resulttt = runmysqlquery($query21tt);
				}
				
			}
				
			$queryyy = "delete from inv_mas_customer where slno = '".$customeridfrom."';";
			$resultyy = runmysqlquery($queryyy);
					
		echo(json_encode('1^'.$businessnamefrom." - ".$customeridfrom." has been Merged with ".$businessnameto." - ".$customeridto));
			
				
		}
	}
	break;
	case 'generatecustomerlist':
	{
		$limit = $_POST['limit'];
		$startindex = $_POST['startindex'];
		$generatecustomerlistarray = array();
		$query = "SELECT slno,businessname,customerid FROM inv_mas_customer ORDER BY businessname LIMIT ".$startindex.",".$limit.";";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$generatecustomerlistarray[$count] = $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
		echo(json_encode($generatecustomerlistarray));
	}
	break;
	case 'getcustomercount':
	{
		$responsearray3 = array();
		$query = "SELECT slno,businessname,customerid FROM inv_mas_customer ORDER BY businessname";
		$result = runmysqlquery($query);
		$count = mysqli_num_rows($result);
		$responsearray3['count'] = $count;
		echo(json_encode($responsearray3));
	}
	break;
	case 'generatecustomerlist2':
	{
		$query = "SELECT slno,businessname,customerid FROM inv_mas_customer ORDER BY businessname";
		$result = runmysqlquery($query);
		$grid = '<select name="customerlist2" size="5" class="swiftselect" id="customerlist2" style="width:210px; height:400px;" onclick ="selectfromlist2();" onchange="selectfromlist2();"  >';
		while($fetch = mysqli_fetch_array($result))
		{
			$grid .= '<option value="'.$fetch['slno'].'">'.$fetch['businessname'].'</option>';
		}
		$grid .= '</select>';
		echo($grid);
	}
	break;
	
	case 'customerdetailstoform':
	{
			$lastslno = $_POST['lastslno'];
			$query = "SELECT inv_mas_customer.slno, inv_mas_customer.customerid,inv_mas_customer.businessname, inv_mas_customer.address, inv_mas_customer.place, inv_mas_customer.district,inv_mas_district.statecode as state, inv_mas_customer.pincode, inv_mas_customer.fax, inv_mas_customer.region,inv_mas_customer.branch, inv_mas_customer.companyclosed, inv_mas_customer.stdcode, inv_mas_customer.website, inv_mas_customer.category, inv_mas_customer.type, inv_mas_customer.remarks, inv_mas_customer.currentdealer,  inv_mas_branch.branchname as branchname, inv_mas_customercategory.businesstype as businesstype, inv_mas_customertype.customertype,inv_mas_region.category as regionname, inv_mas_district.districtname as districtname,inv_mas_state.statename as statename, inv_mas_dealer.businessname as dealerbusinessname FROM inv_mas_customer LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_mas_customer.firstproduct  LEFT JOIN inv_mas_users ON inv_mas_customer.createdby = inv_mas_users.slno left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category  left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer where inv_mas_customer.slno = '".$lastslno."';";
			$fetch = runmysqlqueryfetch($query);
			
			$query1 ="SELECT customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$lastslno."'; ";
			$resultfetch = runmysqlquery($query1);
			$valuecount = 0;
			while($fetchres = mysqli_fetch_array($resultfetch))
			{
				if($valuecount > 0)
				{
					$contactarray .= '****';
					$refcontactarray .= '****';
				}
				
				$selectiontype = $fetchres['selectiontype'];
				$contactperson = $fetchres['contactperson'];
				$phone = $fetchres['phone'];
				$cell = $fetchres['cell'];
				$emailid = $fetchres['emailid'];
				$slno = $fetchres['slno'];
				
				$contactarray .= $selectiontype.'#'.$contactperson.'#'.$phone.'#'.$cell.'#'.$emailid.'#'.$slno;
				$refcontactarray .= $selectiontype.'#'.$contactperson.'#'.$phone.'#'.$cell.'#'.$emailid.'#'.$slno;
				$valuecount++;
			}
			
			$phone = $fetch['phone'];
			$phonearray = explode(',',$phone);
			$phonecount = count($phonearray);
			if($phonecount >= 2)
				$phonevalue = ereg_replace(', ',',',$phone);
			else
				$phonevalue = $phone;
			
			$cell = $fetch['cell'];
			$cellarray = explode(',',$cell);
			$cellcount = count($cellarray);
			if($cellcount >= 2)
				$cellvalue = ereg_replace(', ',',',$cell);
			else
				$cellvalue = $cell;
				
			echo('1^'.$fetch['slno'].'^'.$fetch['businessname'].'^'.$fetch['contactperson'].'^'.$fetch['address'].'^'.$fetch['place'].'^'.$fetch['district'].'^'.$fetch['state'].'^'.$fetch['pincode'].'^'.$fetch['stdcode'].'^'.$phonevalue.'^'.$cellvalue.'^'.$fetch['emailid'].'^'.$fetch['website'].'^'.$fetch['category'].'^'.$fetch['type'].'^'.$fetch['region'].'^'.$fetch['branch'].'^'.$fetch['currentdealer'].'^'.$fetch['districtname'].'^'.$fetch['statename'].'^'.$fetch['branchname'].'^'.$fetch['businesstype'].'^'.$fetch['customertype'].'^'.$fetch['regionname'].'^'.$fetch['dealerbusinessname'].'^'.$fetch['fax'].'^'.cusidcombine($fetch['customerid']).'^'.$contactarray.'^'.$refcontactarray.'^'.$fetch['remarks']);
	
	}
	break;
	
	
	
}

function recordcheck($fieldvalue,$fieldname,$tablename)
{
	$flag = false;
	$query = "SELECT COUNT(*) AS count FROM ".$tablename." WHERE ".$fieldname." = '".$fieldvalue."'";
	$fetch = runmysqlqueryfetch($query);
	if($fetch['count'] != 0)
	{
		$flag = true;
	}
	else
	{
		$flag = false;
	}
	return $flag;
}


	
?>
