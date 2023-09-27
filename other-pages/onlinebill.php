<?php 
include('../functions/phpfunctions.php');

$query = "select * from inv_sms_bill where slno in ('23','24','25','30','31','32','33','38','40');";
$result = runmysqlquery($query);
$count = 0;
while($fetch = mysqli_fetch_array($result))
{
	$userreference = $fetch['userreference'];
	$query2 = "select * from inv_smscredits where billref = '".$fetch['slno']."'";
	$resultfetch = runmysqlqueryfetch($query2);
	$quantity = $resultfetch['quantity'];
	$totalcredits = gettotalsmscredits($smsuserid);
	
	//Get the inoice no from online bill table
	$query1 = "select max(slno) + 1 as billref from inv_invoicenumbers";
	$resultfetch1 = runmysqlqueryfetch($query1);
	$onlineinvoiceno = $resultfetch1['billref'];
		
	//Insert Online bill no to bill table
	$query = "Insert into inv_invoicenumbers(slno) values('".$onlineinvoiceno."');";
	$result = runmysqlquery($query);
	
	$query2 = "select * from inv_mas_customer where slno = '".$userreference."'";
	$fetch2 = runmysqlqueryfetch($query2);
	$company = $fetch2['businessname'];
	$emailid = $fetch2['emailid'];
	
	echo('Done');

}




?>