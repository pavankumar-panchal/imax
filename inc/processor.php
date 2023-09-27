<?
	$query = "select distinct processor from inv_logs_webservices order by processor;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['processor'].'">'.$fetch['processor'].'</option>');
	}
?>
