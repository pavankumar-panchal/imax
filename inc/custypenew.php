<?
	$query = "SELECT slno,customertype FROM inv_mas_customertype ORDER BY customertype";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		$selected  = '';
		if($type == $fetch['slno'])
			$selected = 'selected = "selected"';
		$grid .='<option value="'.$fetch['slno'].'" '.$selected.'>'.$fetch['customertype'].'</option>';
	}
?>
