<?
	$query = "SELECT statecode,statename FROM inv_mas_state order by statename;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		$selected  = '';
		if($state == $fetch['statecode'])
			$selected = "selected = 'selected'";
		$grid .='<option value="'.$fetch['statecode'].'" '.$selected.'>'.$fetch['statename'].'</option>';
	}
?>
