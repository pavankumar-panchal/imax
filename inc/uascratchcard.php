<?
	$query = "select cardid from inv_mas_scratchcard where attached = 'no' order by cardid desc limit 0,10;";
	$result = runmysqlquery($query);
	
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['cardid'].'">'.$fetch['cardid'].'</option>');
	}
?>
