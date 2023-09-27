<?php
	$query = "SELECT distinct productcode,productname,`group` as productgroup FROM inv_logs_webservices
left join inv_mas_product on inv_mas_product.productcode = left(inv_logs_webservices.computerid,3) where productcode <> ''
 order by productname;";
	$result = runmysqlquery_old($query);
	while($fetch = mysqli_fetch_array($result))
	{
		 echo('<label><input type="checkbox" checked="checked"  name="productarray[]" id="'.$fetch['productname'].'" value ="'.$fetch['productcode'].'"  producttype ="'.$fetch['productgroup'].'"/>&nbsp;'.$fetch['productname']);
		 echo('<font color = "#999999">&nbsp;('.$fetch['productcode'].')</font></lable>');
		 echo('<br/>');
	}
?>