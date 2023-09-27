<?php
	include('functions/phpfunctions.php');
	
	$query="Select * from dummy_receipt_reconcile";
	$result= runmysqlquery($query);
	
	$count=0;
	
	while($query_data= mysql_fetch_array($result))
	{
		$query2="UPDATE inv_mas_receipt set reconsilation='" . $query_data["reconsilation"] . "' where slno=". $query_data["receiptno"] ;
		$result2= runmysqlquery($query2);
		
		$count++;
		
	}
	
	echo $count . " Reconcilation status updated";
?>