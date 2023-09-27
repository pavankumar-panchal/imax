<?
include('./functions/phpfunctions.php');

echo "Remove end";
end();

$query0 = "select dealerid,productcode,purchasetype,usagetype,cardid,amount,`date`,quatity from inv_update_dealercard ; ";
$result1 = runmysqlquery($query0);
$count = 0;


while($fetch1 = mysql_fetch_array($result1))
{
	$scratchlimit = $fetch1['quatity'];
	$cardarray = explode(',',$fetch1['cardid']);
	for($j=0; $j< $scratchlimit; $j++)
	{
		$query7 = "SELECT * from inv_mas_scratchcard where cardid = '".$cardarray[$j]."'";
		$result16 = runmysqlquery($query7);
		if(mysql_num_rows($result16) == 0)
		{
			echo('Card ID Doesnt exist!!!');
			exit;
		}
	}
	

		$billstatus = 'successful';
		
		// calculate the service tax and the net amount.
		$servicetax = round($fetch1['amount'] * 0.1236); // edited By bhavesh round($fetch1['amount'] * 0.103);
		$netamount = $fetch1['amount'] + $servicetax;
		
		$fetch12 = runmysqlqueryfetch("SELECT MAX(slno)+1 AS cusbillnumber FROM inv_bill;");
		$query = "INSERT INTO inv_bill(slno,dealerid,billdate,remarks,total,taxamount,netamount,billstatus) values('".$fetch12['cusbillnumber']."','".$fetch1['dealerid'] ."','".$fetch1['date']."','".$fetch1['remarks']."','".$fetch1['amount']."','".$servicetax."','".$netamount."','".$billstatus."')";
		$result = runmysqlquery($query);
		$query = "INSERT INTO inv_billdetail (cusbillnumber , productcode , productquantity , usagetype , purchasetype,productamount) VALUES ('".$fetch12['cusbillnumber']."','".$fetch1['productcode']."','".$fetch1['quatity']."','".$fetch1['usagetype']."','".$fetch1['purchasetype']."','".$fetch1['amount']."');";
		$result = runmysqlquery($query);
		
		
		//$cardcount = count($cardarray);
		for($i=0; $i< $scratchlimit; $i++)
		{
			
			 $query3 = "update inv_mas_scratchcard set attached = 'yes',flag = 'no' where attached = 'no' and cardid = ".$cardarray[$i].";";   
			 $result3 = runmysqlquery($query3);
			 $query2 = "INSERT INTO inv_dealercard (dealerid, cardid, productcode, date, remarks, cusbillnumber, usagetype, purchasetype, free, addlicence,scheme) values('".$fetch1['dealerid']."', '".$cardarray[$i]."', '".$fetch1['productcode']."','".$fetch1['date']."', '".$fetch['remarks']."', '".$fetch12['cusbillnumber']."', '".$fetch1['usagetype']."', '".$fetch1['purchasetype']."','','','1');";
			 $result2 = runmysqlquery($query2);
			
		}
		 $count++;
}
echo('Sucessfully'.'^'.$count);

?>