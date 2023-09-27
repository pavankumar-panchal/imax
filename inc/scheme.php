<?php
	$query = "SELECT slno, schemename FROM inv_mas_scheme ORDER BY schemename";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		if($fetch['slno'] == '1')
		{
		echo('<option value="'.$fetch['slno'].'" selected = "selected">'.$fetch['schemename'].'</option>');
		}
		else
		
		echo('<option value="'.$fetch['slno'].'">'.$fetch['schemename'].'</option>');
	}
?>
