<?php
	$query = "select slno,fullname from inv_mas_users where disablelogin = 'no' order by fullname;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['slno'].'">'.$fetch['fullname'].'</option>');
	}
?>
