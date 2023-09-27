<?
	$query = "SELECT slno, category FROM inv_mas_region ORDER BY category";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['slno'].'">'.$fetch['category'].'</option>');
	}
?>
