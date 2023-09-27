<?
	$query = "SELECT slno,businessname FROM inv_mas_dealer order by businessname;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		$selected  = '';
		if($currentdealer == $fetch['slno'])
			$selected = 'selected = "selected"';
		$grid .='<option value="'.$fetch['slno'].'" '.$selected.'>'.wordwrap($fetch['businessname'], 25, "<br />\n").'</option>';
	}
?>
