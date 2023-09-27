<?
	$query = "(select distinct CONCAT(inv_mas_users.slno,'^[U]') as slno,CONCAT(UPPER(inv_mas_users.fullname) ,'  [U]') as name 
from inv_mas_users where inv_mas_users.disablelogin = 'no') 
UNION ALL
(select distinct CONCAT(inv_mas_dealer.slno,'^[D]') as slno,CONCAT(UPPER(inv_mas_dealer.businessname) ,'  [D]') as name 
from inv_mas_dealer where inv_mas_dealer.disablelogin = 'no') order by name";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['slno'].'">'.$fetch['name'].'</option>');
	}
?>
