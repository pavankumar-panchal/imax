<?
	$query = "SELECT districtcode,districtname FROM inv_mas_district where statecode = '".$state."' order by districtname;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		$selected  = '';
		if($district == $fetch['districtcode'])
			$selected = "selected = 'selected'";
		$grid .='<option value="'.$fetch['districtcode'].'" '.$selected.'>'.$fetch['districtname'].'</option>';
	}
?>
