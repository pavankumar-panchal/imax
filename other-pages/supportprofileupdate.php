<? 
include('../functions/phpfunctions.php');

$query = "select * from inv_customerreqpending where requestfrom = 'support_module' and customerstatus ='processed' 
and processeddatetime between '2009-12-08 10:43:33' and '2009-12-31 14:42:00' and customerid <> '13457' ;";
$result = runmysqlquery($query);
$count = 0;
while($fetch = mysql_fetch_array($result))
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
	$companyclosed = $fetch['companyclosed'];

	$query2 = "update inv_mas_customer set businessname = '".$businessname."', contactperson = '".$contactperson."',address = '".$address."',place = '".$place."',district = '".$district."',pincode = '".$pincode."',stdcode = '".$stdcode."',phone = '".$phone."',cell = '".$cell."',fax = '".$fax."',emailid = '".$emailid."',website = '".$website."',type = '".$type."',category = '".$category."',companyclosed = '".$companyclosed."' where slno = '".$customerid."';";
	$result2 = runmysqlquery($query2);
	
	$query1 = "update inv_customerreqpending set processeddatetime = '".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."',lastmodifiedby = '1',lastmodifiedmodule ='support_module'  where customerid = '".$customerid."' and customerstatus = 'processed' AND requestfrom = 'support_module' and processeddatetime between '2009-12-08 10:43:33' and '2009-12-31 19:27:27' and customerid <> '13457';";
	$result1 = runmysqlquery($query1);
		
	echo($count." DONE <br>\n");
}




?>