<?
	$query = "(select distinct CONCAT(inv_logs_eventtype.pagesshortname,'^[U]') as slno,CONCAT(UPPER(inv_logs_eventtype.pagesshortname) ,'  [U]') as name 
from inv_logs_eventtype where inv_logs_eventtype.modulename = 'USER' order by inv_logs_eventtype.pagesshortname)
UNION ALL
(select distinct CONCAT(inv_logs_eventtype.pagesshortname,'^[D]') as slno,CONCAT(UPPER(inv_logs_eventtype.pagesshortname) ,'  [D]') as name 
from inv_logs_eventtype where inv_logs_eventtype.modulename = 'DEALER' order by inv_logs_eventtype.pagesshortname)
UNION ALL
(select distinct CONCAT(inv_logs_eventtype.pagesshortname,'^[I]') as slno,CONCAT(UPPER(inv_logs_eventtype.pagesshortname) ,'  [I]') as name 
from inv_logs_eventtype where inv_logs_eventtype.modulename = 'IMPLEMENTATION' order by inv_logs_eventtype.pagesshortname) order by name";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['slno'].'">'.$fetch['name'].'</option>');
	}
?>
