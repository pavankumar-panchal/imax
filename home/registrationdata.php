<?php
include("../functions/phpfunctions.php");
$strXML = '<graph caption="Today - ' . changedateformat(datetimelocal('Y-m-d')) . ' " xAxisName="Registration" yAxisName="Units" decimalPrecision="0" formatNumberScale="0" chartRightMargin="30">';
// $strQuery = "select`type`, count(*) AS count from inv_customerproduct group by `type`;";
$strQuery = "select (select count(*) from inv_customerproduct) as total, `type`, count(*) AS count  from inv_customerproduct  group by `type`;";
//$strQuery = "select (select count(*) from inv_customerproduct where date = curdate()) as total, inv_customerproduct.type AS registrationtype, count(*) AS count  from inv_customerproduct  where date = curdate() group by `type`;";
$fetch1 = runmysqlqueryfetch($strQuery) or die(mysqli_error());
$result = runmysqlquery($strQuery) or die(mysqli_error());
if ($result) {
	if ($fetch1['total'] <> '') {
		$type = 'Total';
		$strXML .= "<set name='" . $type . "' value='" . $fetch1['total'] . "' color='AFD8F8'/>";
	}
	while ($fetch = mysqli_fetch_array($result)) {
		//if($fetch['total'] <> '')
		//{
		//	$type = 'Total';
		//	$strXML .= "<set name='" . $type . "' value='" . $fetch['total'] . "' color='AFD8F8'/>";
		$strXML .= "<set name='" . $fetch['type'] . "' value='" . $fetch['count'] . "' color='AFD8F8'/>";
		//}
	}
}
$strXML .= "</graph>";
echo $strXML;
?>