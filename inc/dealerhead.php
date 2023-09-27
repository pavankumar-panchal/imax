<?
	$query = "SELECT slno,businessname FROM inv_mas_dealer where maindealers = 'yes' ORDER BY businessname";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['slno'].'">'.$fetch['businessname'].'</option>');
	}
?>
