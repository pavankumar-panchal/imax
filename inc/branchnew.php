<?
	$query = "SELECT slno, branchname FROM inv_mas_branch ORDER BY branchname";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		$selected  = '';
		if($branch == $fetch['slno'])
			$selected = 'selected = "selected"';
		$grid .='<option value="'.$fetch['slno'].'" '.$selected.'>'.$fetch['branchname'].'</option>';
	}
?>
