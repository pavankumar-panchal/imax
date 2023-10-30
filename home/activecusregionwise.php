<?php
include("../functions/phpfunctions.php");
$strXML = '<graph caption="Yearly " xAxisName="Region" yAxisName="Units" decimalPrecision="0" formatNumberScale="0" chartRightMargin="30">';
$strQuery = "select inv_mas_region.category AS region, count(*) AS count from inv_mas_customer left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region where inv_mas_customer.activecustomer ='yes' group by region";
$result = runmysqlquery($strQuery) or die(mysqli_error());
if ($result) {
    while ($fetch = mysqli_fetch_array($result)) {
        if ($fetch['region'] == '') {
            $region = 'Unassigned';
            $strXML .= "<set name='" . $region . "' value='" . $fetch['count'] . "' color='FF8282'/>";
        } else
            $strXML .= "<set name='" . $fetch['region'] . "' value='" . $fetch['count'] . "' color='FF8282'/>";
    }
}
$strXML .= "</graph>";
echo $strXML;
?>