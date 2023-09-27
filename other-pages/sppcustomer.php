<?php 
include('../functions/phpfunctions.php');

$query = "select * from 1cusspp ;";
$result12 = runmysqlquery($query);
$count = 0;
while($fetch = mysqli_fetch_array($result12))
{
	$count++;
	$businessname = $fetch['name'];
	$contactperson = $fetch['contactperson'];
	$address = $fetch['address'];
	$place = $fetch['place'];
	$pincode = $fetch['pincode'];
	$district = $fetch['district'];
	$region = $fetch['region'];
	$phone = $fetch['phone'];
	$cell = $fetch['cell'];
	$emailid  = $fetch['emailid'];
	$website = $fetch['website'];
	$branch = $fetch['branch'];
	$prdtype = $fetch['type'];
	$password = $fetch['password'];
	$firstproduct = $fetch['firstproduct'];
	$createddate = datetimelocal('Y-m-d').' '.datetimelocal('H:i:s');
	$date = datetimelocal('Y-m-d');
	
	$query = runmysqlqueryfetch("SELECT (MAX(slno) + 1) AS newcustomerid FROM inv_mas_customer");
	$cusslno = $query['newcustomerid'];
	
	$query1 = "Insert into inv_mas_customer(slno,businessname,contactperson,address, place,pincode,district,region,phone,cell,emailid ,website,password,passwordchanged,disablelogin,createddate,createdby,corporateorder,currentdealer,firstproduct,activecustomer,lastmodifieddate,lastmodifiedby,createdip,lastmodifiedip,branch,companyclosed,firstdealer) values ('".$cusslno."','".trim($businessname)."','".$contactperson."','".$address."','".$place."','".$pincode."','".$district."','2','".$phone."','".$cell."','".$emailid."','".$website."','".$password."','N','no','".$createddate."','1','no','1325','".$firstproduct."','yes','".date('Y-m-d').' '.date('H:i:s')."','1','999.999.999.99','999.999.999.99','".$branch."','no','1325');";
	$result = runmysqlquery($query1);
	$query = runmysqlqueryfetch("SELECT distinct statecode from inv_mas_district where districtcode = '".$district."'");
	$cusstatecode = $query['statecode'];
	$delaerrep = '1325';
	$newcustomerid = $cusstatecode.$district.$delaerrep.$firstproduct.$cusslno;
	$query14 = "UPDATE inv_mas_customer SET customerid = '".$newcustomerid."' WHERE slno = '".$cusslno."'";
	$result = runmysqlquery($query14);
	$query34 = "SELECT (MAX(slno) + 1) AS newslno FROM inv_customerproduct";
	$fetch1 = runmysqlqueryfetch($query34);
	$customerproductslno = $fetch1['newslno'];
	$appendval = '-987654321';
	$computerid = $firstproduct.$prdtype.$appendval;
	$query33 = "INSERT INTO inv_customerproduct(slno,customerreference,cardid,computerid,softkey,cusbillnumber,billnumber,billamount,dealerid,generatedby,system,date,time,remarks,reregistration,`type`,module) VALUES('".$customerproductslno."','".$cusslno."','0','".$computerid."','1234-1234-1234','','','','".$delaerrep."','1','999.999.999.99','".date('Y-m-d')."','".date('H:i:s')."','','no','newlicence','user_module');";
	$result3 = runmysqlquery($query33);
	
	echo($count." DONE <br>\n");
}



?>