<?
	$query = "SELECT slno,businessname FROM inv_mas_dealer 
where slno <> '532568855'  AND inv_mas_dealer.disablelogin = 'no' 
order by businessname asc;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['slno'].'">'.$fetch['businessname'].'</option>');
	}
?>
