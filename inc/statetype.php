<?
	 include('../functions/phpfunctions.php');

	$query = "SELECT statecode,statename FROM inv_mas_state order by statename;";
	$result = runmysqlquery($query);
	echo('<option value="">Select A State</option>');
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['statecode'].'">'.$fetch['statename'].'</option>');
	}
?>
