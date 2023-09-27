<? 
include('../functions/phpfunctions.php');

//Take all registered cards with their first registration information
$query = "SELECT distinct s1.date,s1.time, s1.dealerid, left(s1.computerid,3) as productcode,s1.customerreference,s1.cardid
FROM inv_customerproduct s1
LEFT JOIN inv_customerproduct s2 ON s1.cardid = s2.cardid AND concat(s1.date, ' ', s1.time)  > concat(s2.date, ' ', s2.time)
WHERE s2.cardid IS NULL and s1.cardid > 0 order by s1.cardid";
$result = runmysqlquery($query);

while($fetch = mysql_fetch_array($result))
{
	$cardid = $fetch['cardid'];
	$date = $fetch['date'];
	$time = $fetch['time'];
	$dealerid = $fetch['dealerid'];
	$productcode = $fetch['productcode'];
	$customerreference = $fetch['customerreference'];
	
	$query1 = "SELECT `group` from inv_mas_product where productcode = '".$productcode."'";
	$result1 = runmysqlqueryfetch($query1);
	$productgroup = $result1['group'];
	
	$query2 = "SELECT * from inv_customerproduct left join inv_mas_product on inv_mas_product.productcode =  left(inv_customerproduct.computerid,3) where inv_mas_product.group = '".$productgroup."' and inv_customerproduct.date < '".$date."' and inv_customerproduct.customerreference = '".$customerreference."'";
	$result2 = runmysqlquery($query2);
	
	$initialpurchase = (mysql_num_rows($result2) == 0)?'new':'updation';
	
	$query3 = "update inv_dealercard set initialpurchasetype = '".$initialpurchase."' where cardid = '".$cardid."'";
	$result3 = runmysqlquery($query3);
	echo($cardid." | ".$initialpurchase."<br>\n");
}
echo("DONE");

?>