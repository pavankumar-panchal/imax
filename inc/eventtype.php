<?php
	$query = "(select distinct CONCAT(inv_logs_eventtype.slno,'^[U]') as slno,CONCAT(UPPER(inv_logs_eventtype.eventtype) ,'  [U]') as name from inv_logs_eventtype where inv_logs_eventtype.modulename = 'USER' order by inv_logs_eventtype.eventtype)
UNION ALL
(select distinct CONCAT(inv_logs_eventtype.slno,'^[D]') as slno,CONCAT(UPPER(inv_logs_eventtype.eventtype) ,'  [D]') as name 
from inv_logs_eventtype where inv_logs_eventtype.modulename = 'DEALER' order by inv_logs_eventtype.eventtype)
UNION ALL
(select distinct CONCAT(inv_logs_eventtype.slno,'^[I]') as slno,CONCAT(UPPER(inv_logs_eventtype.eventtype) ,'  [I]') as name 
from inv_logs_eventtype where inv_logs_eventtype.modulename = 'IMPLEMENTATION' order by inv_logs_eventtype.eventtype) order by name ";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['slno'].'">'.$fetch['name'].'</option>');
	}
?>
