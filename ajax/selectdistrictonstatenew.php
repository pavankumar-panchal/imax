<?php
include('../functions/phpfunctions.php');
$statecode = $_POST['statecode'];
$query = "SELECT districtcode,districtname FROM inv_mas_district WHERE statecode = '".$statecode."' order by districtname;";
$result = runmysqlquery($query);
echo('<select name="newdistrict" class="swiftselect-mandatory" id="newdistrict" style="width:200px"><option value="">Select A District</option>');
while($fetch = mysqli_fetch_array($result))
{
	echo('<option value="'.$fetch['districtcode'].'">'.$fetch['districtname'].'</option>');
}
echo('</select>');
?>
