<?php
	$query = "select distinct operatingsystem from inv_logs_webservices order by operatingsystem;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['operatingsystem'].'">'.$fetch['operatingsystem'].'</option>');
	}
?>
