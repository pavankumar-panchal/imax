<?php 
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

include("../functions/phpfunctions.php");





/*-----------------------------Do not edit this piece of code - Begin-----------------------------*/

$query = "SHOW TABLE STATUS like 'transactions'";
$result = runicicidbquery($query);
$row = mysqli_fetch_array($result);
$nextautoincrementid = $row['Auto_increment'];


$merchatid = "00004074";
$date = date('Y-m-d');
$time = date('H:i:s');
$userip = $_SERVER["REMOTE_ADDR"];
$userbrowserlanguage = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
$userbrowseragent = $_SERVER["HTTP_USER_AGENT"];
$relyontransactionid = $nextautoincrementid; 

/*-----------------------------Do not edit this piece of code - End-----------------------------*/

//Main Details
$orderid = ""; //Optional
$invoicenumber = ""; //Optional

//User Details
$company = substr($_POST['businessname'],0,80); //Optional
$contactperson = substr($_POST['contactperson'],0,50);
$address1 = substr($_POST['address'],0,50);
$address2 = ""; //Optional
$address3 = ""; //Optional
$city = substr($_POST['district'],0,30);
$state = substr($_POST["state"],0,30);
$country = "IND"; //No change
$pincode =  ($_POST["pincode"] == '')?'111111':$_POST["pincode"];
$phone = substr($_POST['phone'],0,15); //Optional
$emailid = substr($_POST['emailid'],0,80); //Optional
$customerid = "";
$amount = $_POST['amount']; 
$recordreference = $_POST["dealerid"];
$productname = "Saral iMax Credit";
$quantity = "1";
//exit;


$responseSuccessURL = "http://relyonsoft.com/dealer/paydealerlogin/completerazor.php"; //Need to change as per location of response page

//Do not touch this. Inserting the record to Relyon main Credit Card transaction table.

$query = "insert into `transactions` (date, time, userip, orderid, responseurl, invoicenumber, amount, company, contactperson, address1,
address2, address3, city, state, pincode, phone, emailid, customerid, productname, quantity, userbrowserlanguage, userbrowseragent,recordreference,razorpay) 
values('".$date."', '".$time."', '".$userip."', '2735464', '".$responseSuccessURL."','".$invoicenumber."', '".$mainamount."', '".$company."', 
'".$contactperson."', '".addslashes($address1)."', '".addslashes($address2)."', '".addslashes($address3)."', '".$city."', '".$state."', 
'".$pincode."', '".$phone."', '".$emailid."', '".$customerid."', '".$productname."', '".$quantity."', '".$userbrowserlanguage."', '".$userbrowseragent."', 
'".$recordreference."','Y')";
$result = runicicidbquery($query);

$querytxnid = "select max(id) as txnid from transactions";
$resulttxnid = runicicidbquery($querytxnid);
$fetchtxnid = mysqli_fetch_array($resulttxnid);
$txnid_nums = $fetchtxnid['txnid'];

$query1 = "update transactions set responsecode = '".$ResponseCode."', responsemessage = '".$newResponseMessage."', pgtxnid = '".$TxnID."',
payment_method='".$payment_method."', authidcode = '".$TxnID."', rrn = '".$TxnID."', cvrespcode = '".$CVRespCode."', fdmsscore = '".$FDMSScore."', 
fdmsresult = '".$FDMSResult."', cookievalue = '".$Cookie."' where orderid = '2735464'";
$result1 = runicicidbquery($query1);

//Select the values from transation table
$query2 = "select * from transactions where orderid = '2735464'";
$result2 = runicicidbquery($query2);
$transaction = mysqli_fetch_array($result2);

$company = $transaction['company'];
$contactperson = $transaction['contactperson'];
$address = $transaction['address1'];
$place = $transaction['city'];
$phone = $transaction['phone'];
$slno = $transaction['recordreference'];

// if($ResponseCode == 0)
// {
	//Update payment details to SPP Customer table
	$query3 = "insert into `inv_credits`(dealerid, credittype, creditamount, createddate, remarks, createdby)
  values('".$transaction['recordreference']."', '".$method."', '".$transaction['amount']."', '".$transaction['date']."', 'Payment made by ".$method." at ".$transaction['time']."', '2')";
	$result3 = runmysqlquery($query3);

//Get the next record serial number for insertion in receipts table
$query45 ="select max(slno) + 1 as receiptslno from inv_mas_receipt";
$resultfetch45 = runmysqlqueryfetch($query45);
$receiptslno = $resultfetch45['receiptslno'];
	
  //Insert Receipt Details
$query55 = "INSERT INTO inv_mas_receipt(slno,receiptamount,paymentmode,receiptremarks,createddate,createdby,createdip,lastmodifieddate,lastmodifiedby,lastmodifiedip,customerreference,receiptdate,receipttime,module,partialpayment) 
values('".$receiptslno."','".$transaction['amount']."','creditordebit','','".date('Y-m-d').' '.date('H:i:s')."','2','".$_SERVER['REMOTE_ADDR']."','".date('Y-m-d').' '.date('H:i:s')."','2','".$_SERVER['REMOTE_ADDR']."','".$transaction['recordreference']."','".date('Y-m-d')."','".date('H:i:s')."','Online','no');";
$result55 = runmysqlquery($query55);

$receiptquery = "update transactions set receiptnumber =  '".$receiptslno."' where orderid = '2735464'";
    $receiptresult = runicicidbquery($receiptquery);

// senddealerreceipt('220153','resend');

$receiptdetails = viewdealeronlinereceipt($receiptslno,'resend');
	$receiptdetailssplit = explode('^',$receiptdetails);
	$filebasename = $receiptdetailssplit[0];
	
	//$amt = $transaction['amount'];
	$amt = '2500';
	$emailid = 'bhumika.p@relyonsoft.com';
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);
	
	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
				$emailids[$emailarray[$i]] = $emailarray[$i];
		}
	}

	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/paymentinfo.htm");
	$textmsg = file_get_contents("../mailcontents/paymentinfo.txt");
	$date = datetimelocal('d-m-Y');
	$array = array();
	$array[] = "##DATE##%^%".$date;
	$array[] = "##NAME##%^%".$contactperson;
	$array[] = "##COMPANY##%^%".$company;
	$array[] = "##PLACE##%^%".$place;
	$array[] = "##DEALERID##%^%".$slno;
	$array[] = "##ADDRESS##%^%".$address;
	$array[] = "##PHONE##%^%".$phone;
	$array[] = "##PAYMENTAMOUNT##%^%".$amt;
	
	$filearray = array(
		array('../images/relyon-logo.jpg','inline','1234567890'),array('../images/relyon-rupee-small.jpg','inline','1234567892'),array('../filecreated/'.$filebasename,'attachment','1234567891')
	);
	
	//Mail to customer
	$toarray = $emailids;
	
	//Copy of email to Accounts / Vijay Kumar / Bigmails
	$bccarray = array('bhumika' => 'bhumipatel728@gmail.com'); 

	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = "Payment Successfully made by ".$company;
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray); 
	fileDelete('../filecreated/',$filebasename);
	
	//Insert the mail forwarded details to the logs table
	$bccmailid = 'accounts@relyonsoft.com,bigmail@relyonsoft.com,webmaster@relyonsoft.com'; 
	inserttologs(imaxgetcookie('dealeruserid'),$transaction['recordreference'],$fromname,$fromemail,$emailid,null,$bccmailid ,$subject);

		?>

</body>
</html>