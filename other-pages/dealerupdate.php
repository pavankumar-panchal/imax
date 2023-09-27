<? 
include('../functions/phpfunctions.php');

$query = "select * from 1dealer ;";
$result12 = runmysqlquery($query);
$count = 0;
while($fetch = mysql_fetch_array($result12))
{
	$count++;
	$slno = $fetch['slno'];
	$emailid = $fetch['emailid'];
	$personalemailid = $fetch['personalemailid'];
	$tlemailid = $fetch['tlemailid'];
	$mgremailid = $fetch['mgremailid'];
	$hoemailid  = $fetch['hoemailid'];
	$date = datetimelocal('Y-m-d');
	$query1 = "UPDATE inv_mas_dealer SET emailid = '".$emailid."',tlemailid = '".$tlemailid."',mgremailid = '".$mgremailid."',hoemailid = '".$hoemailid."',personalemailid  = '".$personalemailid."',lastmodifieddate ='".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedby ='1',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."'  WHERE slno = '".$slno."'";
	$result = runmysqlquery($query1);
	
	echo($count.$slno." DONE <br>\n");
}



?>