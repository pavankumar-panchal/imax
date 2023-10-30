<?php
	$query = "SELECT slno FROM inv_bill order by slno;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['slno'].'">'.$fetch['slno'].'</option>');
	}
?>
