<?php 
	$query = "SELECT title FROM title ORDER BY title";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['title'].'">'.$fetch['title'].'</option>');
	}
?>
