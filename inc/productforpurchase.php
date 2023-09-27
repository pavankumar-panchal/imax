<?
	$query = "(select inv_mas_product.productcode,inv_mas_product.productname from inv_dealer_pricing 
	left join inv_mas_product on inv_dealer_pricing.product = inv_mas_product.productcode where inv_mas_product.notinuse = 'no') 
	union
	(select productcode,productname from inv_mas_product where subgroup = 'ESS') order by productname;";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
		echo('<option value="'.$fetch['productcode'].'">'.$fetch['productname'].'</option>');
	}
?>

