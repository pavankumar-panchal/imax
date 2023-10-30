<?php
	$query = "SELECT distinct dealerid as slno,inv_mas_dealer.businessname FROM inv_invoicenumbers 
left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid  where inv_mas_dealer.businessname <> '' order by businessname;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['slno'].'">'.wordwrap($fetch['businessname'], 25, "<br />\n").'</option>');
	}
?>
