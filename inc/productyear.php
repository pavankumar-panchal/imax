<?
	$query = "select max(`year`)as currentyear from inv_mas_product;";
	$resultftech = runmysqlqueryfetch($query);
	$query = "select distinct `year` as prdyear from inv_mas_product where (`year` <> '' and `year` <> '".$resultftech['currentyear']."') order by `year` DESC ;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		 echo('<label><input type="checkbox" name="productyear[]" id="'.$fetch['prdyear'].'" value ="'.$fetch['prdyear'].'" />&nbsp;'.$fetch['prdyear']);
		 echo('</label>');
		 echo('<br/>');
	}
?>