<?
	$query = "SELECT businessname FROM inv_mas_dealer order by businessname;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['businessname'].'">'.wordwrap($fetch['businessname'], 25, "<br />\n").'</option>');
	}
?>
