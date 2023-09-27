<?php
include('../functions/phpfunctions.php');
$dealerid = $_POST['dealerid'];
$query = "SELECT slno FROM inv_bill WHERE dealerid = '".$dealerid."' order by slno;";
$result = runmysqlquery($query);
echo('<select name="billnumber" class="swiftselect-mandatory" id="billnumber"><option value="">Select A Bill</option>');
while($fetch = mysqli_fetch_array($result))
{
	echo('<option value="'.$fetch['slno'].'">'.$fetch['slno'].'</option>');
}
echo('</select>');
?>