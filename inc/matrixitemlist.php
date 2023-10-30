<?php
	$query = "SELECT id,productname FROM inv_mas_matrixproduct order by id;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<label for = "'.$fetch['productname'].'"><input type="checkbox" checked="checked"  name="matrixarray[]" id="'.$fetch['productname'].'" value ="'.$fetch['id'].'" />&nbsp;'.$fetch['productname']);
		 echo('</label>');
		echo('<br/>');
	}
?>