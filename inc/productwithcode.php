<?
	$query = "SELECT productcode,productname FROM inv_mas_product WHERE notinuse = 'no' order by productname;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['productcode'].'">'.wordwrap($fetch['productname'], 25, "<br />\n").' ['.wordwrap($fetch['productcode'], 25, "<br />\n").']'.'</option>');
	}
?>
