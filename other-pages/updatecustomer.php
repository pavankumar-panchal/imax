<?php 
include('../functions/phpfunctions.php');

$query = "select * from csdcus ;";
$result12 = runmysqlquery($query);
$count = 0;
while($fetch = mysqli_fetch_array($result12))
{
	$count++;
	$customerid = $fetch['customerid'];
	$address = $fetch['address'];
	$pincode = $fetch['pincode'];
	$phone = $fetch['phone'];
	$cell = $fetch['cell'];
	$emailid  = $fetch['emailid'];
	$date = datetimelocal('Y-m-d');
	$query1 = "UPDATE inv_mas_customer SET address = '".$address."',pincode = '".$pincode."',phone = '".$phone."',cell = '".$cell."',emailid  = '".$emailid."',lastmodifieddate ='".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedby ='1',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."'  WHERE slno = '".$customerid."'";
	$result = runmysqlquery($query1);
	
	echo($count.$customerid." DONE <br>\n");
}



?>