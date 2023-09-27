<?
	$query = "SELECT slno,businesstype FROM inv_mas_customercategory ORDER BY businesstype";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		$selected  = '';
		if($category == $fetch['slno'])
			$selected = 'selected = "selected"';
		$grid .='<option value="'.$fetch['slno'].'" '.$selected.'>'.$fetch['businesstype'].'</option>';
	}
?>
