<?php
include('functions/phpfunctions.php');

echo "Remove end to run this code";
end();

$query = "select * from dummy_receipt";
$result = runmysqlquery($query);
$count = 0;
while ($fetch = mysqli_fetch_array($result)) {
	$invoiceslno1 = $fetch['slno'];
	$invoiceno = $fetch['invoiceno'];
	$invoiceamount = '';
	$receiptamount = $fetch['receiptamount'];
	$paymentmode = $fetch['paymentmode'];
	$createddate = $fetch['createddate'];
	$createdby = $fetch['createdby'];
	$createdip = $fetch['createdip'];
	$lastmodifieddate = $fetch['lastmodifieddate'];
	$lastmodifiedby = $fetch['lastmodifiedby'];
	$lastmodifiedip = $fetch['lastmodifiedip'];
	$customerreference = $fetch['customerreference'];
	$chequedate = $fetch['chequedate'];
	$chequeno = $fetch['chequeno'];
	$chequeamount = $fetch['chequeamount'];
	$drawnon = $fetch['drawnon'];
	$depositdate = $fetch['depositdate'];
	$receiptdate = $fetch['receiptdate'];
	$receipttime = $fetch['receipttime'];
	$module = $fetch['module'];
	$status = $fetch['status'];

	$count++;
	$query0 = "select slno from inv_invoicenumbers where invoiceno = '" . $invoiceno . "'";
	$fetch0 = runmysqlqueryfetch($query0);
	$invoiceslno = $fetch0['slno'];

	$query1 = "insert into inv_mas_receipt (slno,invoiceno, invoiceamount, receiptamount, paymentmode, createddate, createdby, createdip, lastmodifieddate, lastmodifiedby, lastmodifiedip, customerreference, chequedate, 	chequeno, chequeamount, drawnon, depositdate, receiptdate, receipttime, module, status)
	values('" . $invoiceslno1 . "','" . $invoiceslno . "', '" . $invoiceamount . "', '" . $receiptamount . "','" . $paymentmode . "', '" . $createddate . "', '1', '99.99.99.99', '" . $createddate . "', '1', '99.99.99.99', '" . $customerreference . "', '" . $chequedate . "', '" . $chequeno . "', '" . $chequeamount . "', 	'" . $drawnon . "', '" . $depositdate . "', '" . $receiptdate . "', '00:00:00', 'user_module', 'ACTIVE')";
	$fetch1 = runmysqlquery($query1);
	echo ($count . '^' . " DONE <br>\n");
}

?>