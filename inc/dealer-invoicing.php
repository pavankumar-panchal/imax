<?
	$query = "SELECT slno,businessname FROM inv_mas_dealer where disablelogin = 'no' and dealernotinuse = 'no' order by businessname;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['slno'].'">'.wordwrap($fetch['businessname'], 25, "<br />\n").'</option>');
	}
?>