
function getcustomerproductlist(divid12,customerid)
{

<?php
	//include('../functions/phpfunctions.php');
	$cuslastslno = $_POST['displaycustomername'];
	//echo('productlist = \'');
	echo($cuslastslno);
	/*$query = "select inv_mas_product.productname,inv_mas_product.productcode from inv_customerproduct left join inv_mas_product on
left(inv_customerproduct.computerid,3) = inv_mas_product.productcode  where 
customerreference = '".$cuslastslno."' and reregistration = 'no';";
	$result = runmysqlquery($query);
	echo('<select name="productcode" class="swiftselect-mandatory" id="productcode" style="width:180px;" );"><option value="">Make A Selection</option>');
	while($fetch = mysqli_fetch_array($result))
	{
			echo('<option value="'.$fetch['productcode'].'">'.$fetch['productname'].'</option>');
	}
	echo("</select>';"."\n\t\t");*/


?>
document.getElementById(divid12).innerHTML = productlist;	
}



