<?php
	$query = "select distinct year from inv_mas_product where year <> '' order by year desc";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['year'].'">'.$fetch['year'].'</option>');
	}

?>
