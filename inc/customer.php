<?php
	$query = "SELECT slno,businessname,customerid FROM inv_mas_customer ORDER BY businessname;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		 echo('<label><input type="checkbox" name="custname[]" id="'.$fetch['businessname'].'" value ="'.$fetch['slno'].'" />&nbsp;'.$fetch['productname']);
		 echo(''.$fetch['businessname'].'</lable>');
		 echo('<br/>');
	}
?>