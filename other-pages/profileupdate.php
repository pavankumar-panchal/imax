<?php 
include('../functions/phpfunctions.php');

$query = "select * from inv_customerreqpending where customerstatus = 'pending' and requestfrom = 'dealer_module';";
$result = runmysqlquery($query);
$count = 0;
while($fetch = mysqli_fetch_array($result))
{
	$count++;
	$customerid = $fetch['customerid'];
	$businessname = $fetch['businessname'];
	$contactperson = $fetch['contactperson'];
	$address = $fetch['address'];
	$place = $fetch['place'];
	$district = $fetch['district'];
	$pincode = $fetch['pincode'];
	$stdcode = $fetch['stdcode'];
	$phone = $fetch['phone'];
	$cell = $fetch['cell'];
	$fax = $fetch['fax'];
	$emailid = $fetch['emailid'];
	$website = $fetch['website'];
	$type = $fetch['type'];
	$category = $fetch['category'];
	
	$query2 = "update inv_mas_customer set businessname = '".$businessname."', contactperson = '".$contactperson."',address = '".$address."',place = '".$place."',district = '".$district."',pincode = '".$pincode."',stdcode = '".$stdcode."',phone = '".$phone."',cell = '".$cell."',fax = '".$fax."',emailid = '".$emailid."',website = '".$website."',type = '".$type."',category = '".$category."' where slno = '".$customerid."';";
	$result2 = runmysqlquery($query2);
	
	$query1 = "update inv_customerreqpending set customerstatus = 'processed',processeddatetime = '".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."',lastmodifiedby = '1' where customerid = '".$customerid."' and customerstatus = 'pending' AND requestfrom = 'dealer_module'";
	$result1 = runmysqlquery($query1);
		
	sendprofileupdatedemail($customerid);
	echo($count." DONE <br>\n");
}


function sendprofileupdatedemail($customerid)
{
	$query ="SELECT  inv_mas_customer.slno as slno,inv_mas_customer.customerid,inv_mas_customer.businessname as businessname
,inv_customerreqpending.requestby as dealerbusinessname,inv_mas_customer.contactperson as contactperson,
inv_mas_customer.address as address,inv_mas_customer.place as place,inv_mas_district.districtname as district,
inv_mas_state.statename as state,inv_mas_customer.pincode as pincode,inv_mas_customer.currentdealer,
inv_mas_customercategory.businesstype as category,inv_mas_customer.phone as phone,inv_mas_customertype.customertype as type,
inv_mas_customer.cell as cell,inv_mas_customer.fax as fax,inv_mas_customer.emailid as emailid,inv_mas_customer.website as website,
inv_mas_customer.stdcode as stdcode,inv_mas_dealer.emailid as dealeremailid,inv_customerreqpending.processeddatetime,
inv_customerreqpending.createddate FROM inv_mas_customer 
LEFT JOIN inv_mas_district ON inv_mas_district.districtcode = inv_mas_customer.district 
LEFT JOIN inv_customerreqpending on  inv_customerreqpending.customerid = inv_mas_customer.slno
LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode 
LEFT JOIN inv_mas_dealer on  inv_customerreqpending.requestby = inv_mas_dealer.slno
LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno =inv_mas_customer.type 
LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno =inv_mas_customer.category
where inv_mas_customer.slno ='".$customerid."' and inv_customerreqpending.customerstatus = 'processed'" ;
	$fetch = runmysqlqueryfetch($query);
	
	$newcustomerid = $fetch['customerid'];
	$businessname = $fetch['businessname'];
	$contactperson = $fetch['contactperson'];
	$address = $fetch['address'];
	$state = $fetch['state'];
	$district = $fetch['district'];
	$pincode = $fetch['pincode'];
	$stdcode = $fetch['stdcode'];
	$place = $fetch['place'];
	$phone = $fetch['phone'];
	$cell = $fetch['cell'];
	$fax = $fetch['fax'];
	$emailid = $fetch['emailid'];
	$dealeremailid = $fetch['dealeremailid'];
	$website = $fetch['website'];
	$type = $fetch['type'];
	$category = $fetch['category'];
	$processeddatetime = changedateformatwithtime($fetch['processeddatetime']);
	$createddate = changedateformatwithtime($fetch['createddate']);
	$slno = $fetch['slno'];
	//Dummy line to override To email ID
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
		$emailid = 'archana.ab@relyonsoft.com';
	else
		$emailid = $result['emailid'];
		
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);
	$emailarraydealer = explode(',',$dealeremailid);
	$emailcountdealer = count($emailarraydealer);
	
	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
			if($i == 0)
				$emailids[$emailarray[$i]] = $emailarray[$i];
			else
				$emailids[$emailarray[$i]] = $emailarray[$i];
		}
	}
	
	for($i = 0; $i < $emailcountdealer; $i++)
	{
		if(checkemailaddress($emailarraydealer[$i]))
		{
				$dealeremailids[$emailarraydealer[$i]] = $emailarraydealer[$i];
		}
	}

	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("mailcontents/cusprofileupdate1.htm");
	$textmsg = file_get_contents("mailcontents/cusprofileupdate1.txt");
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
	if($fax == '')
	{
		$fax = 'Not Available';
	}
	if($website == '')
	{
		$website = 'Not Available';
	}
	if($type == '')
	{
		$type = 'Not Available';
	}
	if($category == '')
	{
		$category = 'Not Available';
	}
	$array[] = "##DATE##%^%".$date;
	$array[] = "##REQUESTDATE##%^%".$createddate;
	$array[] = "##PROCESSEDDATE##%^%".$processeddatetime;
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
	$array[] = "##FAX##%^%".$fax;
	$array[] = "##EMAILID##%^%".$emailid;
	$array[] = "##WEBSITE##%^%".$website;
	$array[] = "##TYPE##%^%".$type;
	$array[] = "##CATEGORY##%^%".$category;
	$array[] = "##CUSTOMERID##%^%".cusidcombine($newcustomerid);
	
	$filearray = array(
		array('images/relyon-logo.jpg','inline','1234567890')
		
	);
	$toarray = $emailids;
	$ccemailids = $dealeremailids;
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
	{
		$bccemailids['rashmi'] ='rashmi.hk@relyonsoft.com';
		$bccemailids['archanaab'] ='archana.ab@relyonsoft.com';
	}
	else
	{
		$bccemailids['vijaykumar'] ='vijaykumar@relyonsoft.com';
	}
	$bccarray = $bccemailids;
	$ccarray = $ccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = 'Profile Update Request has been processed by Relyon.';
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray);
	inserttologs($userid,$slno,$fromname,$fromemail,$emailid,$dealeremailid,'vijaykumar@relyonsoft.com',$subject);	
}





?>