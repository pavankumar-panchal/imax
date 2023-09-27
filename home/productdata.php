<?php
    include("../functions/phpfunctions.php");

    $strXML = '<graph caption="Today - '.changedateformat(datetimelocal('Y-m-d')).' " xAxisName="Registration" yAxisName="Units" decimalPrecision="0" formatNumberScale="0" chartRightMargin="30">';
//	$strQuery = "select (select count(*) from inv_customerproduct where date = curdate()) as total, inv_customerproduct.type AS registrationtype, count(*) AS count  from inv_customerproduct  where date = curdate() group by `type`;";
	$strQuery = "select inv_mas_product.productname, productstats.prdcount from (select LEFT(computerid,3) as prdcode, count(*) AS prdcount from inv_customerproduct where LEFT(computerid,3) in (select productcode from inv_mas_product where forreports = 'yes') AND reregistration = 'no' group by LEFT(computerid,3)) as productstats left join inv_mas_product on productstats.prdcode = inv_mas_product.productcode order by productname;";
	$fetch1 = runmysqlqueryfetch($strQuery);
    $result = runmysqlquery($strQuery);
    if ($result) 
	{
        while($fetch = mysqli_fetch_array($result)) 
		{
            $strXML .= "<set name='" . $fetch['productname'] . "' value='" . $fetch['prdcount'] . "' color='AFD8F8'/>";
        }
    }
    $strXML .= "</graph>";
   echo $strXML;
?>
