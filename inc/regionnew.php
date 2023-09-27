<?php
	$query = "SELECT slno, category FROM inv_mas_region ORDER BY category";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		$selected  = '';
		if($region == $fetch['slno'])
			$selected = 'selected = "selected"';
		$grid .='<option value="'.$fetch['slno'].'" '.$selected.'>'.$fetch['category'].'</option>';
	}
?>
