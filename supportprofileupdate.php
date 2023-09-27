<?php 
include('./functions/phpfunctions.php');

$customerslno = '13457';
sendemail($customerslno);
echo(" DONE <br>\n");


function sendemail($customerslno)
{
$query = "select 
	inv_mas_customer.customerid AS customerid,
	inv_mas_customer.businessname AS businessname,
	inv_mas_customer.contactperson AS contactperson,
	inv_mas_customer.place AS place,
	inv_mas_customer.address AS address,
	inv_mas_customer.pincode AS pincode,
	inv_mas_customer.stdcode AS stdcode,
	inv_mas_customer.phone AS phone,inv_mas_customer.initialpassword AS password,
	inv_mas_customer.cell AS cell,
	inv_mas_customertype.customertype AS type,
	inv_mas_customercategory.businesstype AS category,
	inv_mas_customer.emailid AS emailid,
	inv_mas_district.districtname AS districtname,
	inv_mas_state.statename AS statename
	from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode left join inv_mas_customercategory on inv_mas_customer.category = inv_mas_customercategory.slno left join inv_mas_customertype on inv_mas_customer.type = inv_mas_customertype.slno
	 where inv_mas_customer.slno = '".$customerslno."'";
	$result = runmysqlqueryfetch($query);
	
	$customerid = $result['customerid'];
	$businessname = $result['businessname'];
	$contactperson = $result['contactperson'];
	$place = $result['place'];
	$address = $result['address'];
	$pincode = $result['pincode'];
	$stdcode = $result['stdcode'];
	$phone = $result['phone'];
	$cell = $result['cell'];
	$password = $result['password'];
	$type = $result['type'];
	$category = $result['category'];
	$districtname = $result['districtname'];
	$statename = $result['statename'];
		//$emailid = $result['emailid'];
	
	//Dummy line to override To email ID
$emailid = 'meghana.b@relyonsoft.com';

	//Split multiple email IDs by Comma
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);
	
	//Convert email IDs to an array. First email ID will eb assigned with Contact person name. Others will be assigned with email IDs itself as Name.
	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
				$emailids[$emailarray[$i]] = $emailarray[$i];
		}
	}
	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("mailcontents/newcustomer.htm");
	$textmsg = file_get_contents("mailcontents/newcustomer.txt");
	$pincode = ($pincode == '')?'Not Available':$pincode;
	$stdcode = ($stdcode == '')?'Not Available':$stdcode;
	$address = ($address == '')?'Not Available':$address;
	$type = ($type == '')?'Not Available':$type;
	$category = ($category == '')?'Not Available':$category;

	//Create an array of replace parameters
	$array = array();
	$date = datetimelocal('d-m-Y');
	$array[] = "##DATE##%^%".$date;
	$array[] = "##NAME##%^%".$contactperson;
	$array[] = "##COMPANY##%^%".$businessname;
	$array[] = "##PLACE##%^%".$place;
	$array[] = "##ADDRESS##%^%".$address;
	$array[] = "##DISTRICT##%^%".$districtname;
	$array[] = "##STATE##%^%".$statename;
	$array[] = "##CUSID##%^%".cusidcombine($customerid);
	$array[] = "##PINCODE##%^%".$pincode;
	$array[] = "##STDCODE##%^%".$stdcode;
	$array[] = "##TYPE##%^%".$type;
	$array[] = "##PHONE##%^%".$phone;
	$array[] = "##CELL##%^%".$cell;
	$array[] = "##PASSWORD##%^%".$password;
	$array[] = "##EMAIL##%^%".$emailid;
	$array[] = "##CATEGORY##%^%".$category;
	//Relyon Logo for email Content, as Inline [Not attachment]
	$filearray = array(
	
	);
	$toarray = $emailids;
	$bccemailids['rashmi'] ='rashmi.hk@relyonsoft.com';

	$bccarray = $bccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = "New customer details registered with Customer ID '".cusidcombine($customerid)."'";
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray);
	
}



?>