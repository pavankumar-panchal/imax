<?php 
include('../functions/phpfunctions.php');

$query = "select slno,`password` as password from inv_mas_users ;";
$result = runmysqlquery($query);
$count = 0;
while($fetch = mysqli_fetch_array($result))
{
	$count++;
	$slno  = $fetch['slno'];
	$passwd = $fetch['password'];
	$encodepassword = encrypt_decrpt($passwd);
	$query2 = "update inv_mas_users set `password` = '".$encodepassword."' where slno = '".$slno."';";
	$result2 = runmysqlquery($query2);
	echo($count." DONE <br>\n");
	
}
	


?>
