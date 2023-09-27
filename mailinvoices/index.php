
<?php
include('/home/manju/public/imax.relyonsoft.net/public/user/mailinvoices/functions/phpfunctions.php');

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

//error_reporting(0);


$querycrone= "SELECT * FROM tbl_cronRange ORDER BY id DESC LIMIT 1";
$fetchcrone = runmysqlqueryfetch($querycrone);
$from_id=$fetchcrone['from_id'];
$to_id=$fetchcrone['to_id'];

$query1="select * from `inv_invoicenumbers` where left(inv_invoicenumbers.createddate,10) between '2020-06-19' and '2020-07-21' limit '$from_id', '$to_id'";
//$query1="select * from `inv_invoicenumbers` where invoiceno = 'RSL2020RL001046'  limit $from_id, $to_id";
$result1 = runmysqlquery($query1);
$count = mysql_num_rows($result1);
if($count > 0)
{
	while($fetch = mysql_fetch_array($result1))
	{
			$invoiceno = $fetch['slno'];

			$type = 'resend';
			$invoicedetails = vieworgeneratepdfinvoice($invoiceno,$type);
			$invoicedetailssplit = explode('^',$invoicedetails);
			$filebasename = $invoicedetailssplit[0];
			$businessname = $invoicedetailssplit[1];
			$invoiceno = $invoicedetailssplit[2];
			$emailid =  $invoicedetailssplit[3];
			$customerid =  $invoicedetailssplit[4];
			$dealeremailid =  $invoicedetailssplit[5];
			$invoicestatus = $invoicedetailssplit[6];
			$statuscheck = $invoicedetailssplit[7];
			$contactperson = $invoicedetailssplit[8];
			$netamount = $invoicedetailssplit[9];
			$slno = substr($customerid,15,20);
			
			$query1 = "select * from inv_mas_customer where slno = '".$slno."';";
			$resultfetch = runmysqlqueryfetch($query1);
			$place = $resultfetch['place'];
			// Fetch Contact Details
			$querycontactdetails = "select customerid,GROUP_CONCAT(emailid) as emailid from inv_contactdetails where customerid = '".$slno."'  group by customerid ";
			$resultcontact = runmysqlquery($querycontactdetails);
			$resultcontactdetails = mysql_fetch_array($resultcontact);
			//$resultcontactdetails = runmysqlqueryfetch($querycontactdetails);
			
			$emailidres = removedoublecomma($resultcontactdetails['emailid']);
			
			//fetch the details from customer pending table
			$query22 = "SELECT count(*) as count from inv_contactreqpending where customerid = '".$slno."' and customerstatus = 'pending' and editedtype = 'edit_type'";
			$result22 = runmysqlqueryfetch($query22);
			if($result22['count'] == 0)
			{
				$resultantemailid = $emailidres;
			}
			else
			{
				// Fetch of contact details, from pending request table if any
				$querycontactpending = "select GROUP_CONCAT(emailid) as pendemailid from inv_contactreqpending where customerid = '".$slno."' and customerstatus = 'pending' and editedtype = 'edit_type' group by customerid ";
				$resultcontactpending = runmysqlqueryfetch($querycontactpending);
				
				$emailidpending = removedoublecomma($resultcontactpending['pendemailid']);
				
				$finalemailid = $emailidres.','.$emailidpending;
				$resultantemailid = remove_duplicates($finalemailid);
			}
			
			if($filebasename <> '')
			{
				//echo $filebasename;
				//exit;

				$fromname = "Relyon";
				$fromemail = "imax@relyon.co.in";
				require_once("inc/RSLMAIL_MAIL.php");
				$msg = file_get_contents("/home/manju/public/imax.relyonsoft.net/public/user/mailinvoices/mailcontents/resend-invoice.htm");
				$textmsg = file_get_contents("/home/manju/public/imax.relyonsoft.net/public/user/mailinvoices/mailcontents/resend-invoice.txt");
			
				//Relyon Logo for email Content, as Inline [Not attachment]
				$filearray = array(
					array('/home/manju/public/imax.relyonsoft.net/public/user/filecreated/'.$filebasename,'attachment','1234567891'),array('/home/manju/public/imax.relyonsoft.net/public/user/mailinvoices/images/relyon-logo.jpg','inline','1234567890'),array('/home/manju/public/imax.relyonsoft.net/public/user/mailinvoices/images/relyon-rupee-small.jpg','inline','1234567892')
				);
				if($statuscheck == 'CANCELLED')
				{
					//Relyon Logo for email Content, as Inline [Not attachment]
					$filearray = array(
						array('/home/manju/public/imax.relyonsoft.net/public/user/mailinvoices/images/relyon-logo.jpg','inline','1234567890'),array('/home/manju/public/imax.relyonsoft.net/public/user/filecreated/'.$filebasename,'attachment','1234567891')
					);
					$msg = file_get_contents("/home/manju/public/imax.relyonsoft.net/public/user/mailinvoices/mailcontents/invoicecancel.htm");
					$textmsg = file_get_contents("/home/manju/public/imax.relyonsoft.net/public/user/mailinvoices/mailcontents/invoicecancel.txt");
					$subject = "Relyon Online Invoice | ".$invoiceno." (Resent - CANCELLED)";
				}
				elseif($statuscheck == 'EDITED')
				{
					$msg = file_get_contents("/home/manju/public/imax.relyonsoft.net/public/user/mailinvoices/mailcontents/paymentinfo1.htm");
					$textmsg = file_get_contents("/home/manju/public/imax.relyonsoft.net/public/user/mailinvoices/mailcontents/paymentinfo1.txt");
					$subject = "Relyon Online Invoice | ".$invoiceno." (Resent - EDITED)";
				}
				else
				{
					$msg = file_get_contents("/home/manju/public/imax.relyonsoft.net/public/user/mailinvoices/mailcontents/paymentinfo1.htm");
					$textmsg = file_get_contents("/home/manju/public/imax.relyonsoft.net/public/user/mailinvoices/mailcontents/paymentinfo1.txt");
					$subject = "Relyon Online Invoice | ".$invoiceno." (Resent)";
				}
					
				//Create an array of replace parameters
				$array = array();
				$date = datetimelocal('d-m-Y');
				$array[] = "##DATE##%^%".$date;
				$array[] = "##COMPANYNAME##%^%".$businessname;
				$array[] = "##PLACE##%^%".$place;
				$array[] = "##INVOICENO##%^%".$invoiceno;
				$array[] = "##CONTACTPERSON##%^%".$contactperson;
				$array[] = "##TOTALAMOUNT##%^%".$netamount;
				$array[] = "##SUBJECT##%^%".$subject;
				$array[] = "##EMAILID##%^%".$resultantemailid;
				$array[] = "##CUSTOMERID##%^%".$customerid;
				
				#########  Mailing Starts -----------------------------------
				//$emailid = 'rashmi.hk@relyonsoft.com';
				if(($_SERVER['HTTP_HOST'] == "bhumika") || ($_SERVER['HTTP_HOST'] == "192.168.2.79"))
				{
					$emailid = 'bhumika.p@relyonsoft.com';
				}
				else
				{
					$emailid = $resultantemailid;
				}
				
				$emailarray = explode(',',$emailid);
				$emailcount = count($emailarray);

				for($i = 0; $i < $emailcount; $i++)
				{
					if(checkemailaddress($emailarray[$i]))
					{
							$emailids[$emailarray[$i]] = $emailarray[$i];
					}
				}
				
				//CC to Sales person
				
				if(($_SERVER['HTTP_HOST'] == "bhumika") || ($_SERVER['HTTP_HOST'] == "192.168.2.79"))
				{
					$dealeremailid = 'bhumika.p@relyonsoft.com';
				}
				else
				{
					$dealeremailid = $dealeremailid;
				}
				$ccemailarray = explode(',',$dealeremailid);
				$ccemailcount = count($ccemailarray);
				for($i = 0; $i < $ccemailcount; $i++)
				{
					if(checkemailaddress($ccemailarray[$i]))
					{
						if($i == 0)
							$ccemailids[$ccemailarray[$i]] = $ccemailarray[$i];
						else
							$ccemailids[$ccemailarray[$i]] = $ccemailarray[$i];
					}
				}

				$toarray = $emailids;
				
				//CC to sales person
				$ccarray = $ccemailids;
				
				if(($_SERVER['HTTP_HOST'] == "bhumika") || ($_SERVER['HTTP_HOST'] == "192.168.2.79"))
				{
					$bccemailids['rashmi'] ='bhumika.p@relyonsoft.com';
					//$bccemailids['archanaab'] ='archana.ab@relyonsoft.com';
				}
				else
				{
					$bccemailids['bhumika'] ='bhumika.p@relyonsoft.com';
					// $bccemailids = array('Bigmail' => 'bigmail@relyonsoft.com', 'Accounts'=> 'bills@relyonsoft.com', 'Relyonimax' => 'relyonimax@gmail.com', 'Usha' => 'dealers@relyonsoft.com', 'Madhuri H N' => 'madhuri.hn@relyonsoft.com');
				}
				$bccarray = $bccemailids;
				$msg = replacemailvariable($msg,$array);
				$textmsg = replacemailvariable($textmsg,$array);

				$html = $msg;
				$text = $textmsg;
				$replyto = $ccemailids[$ccemailarray[0]];
				
				//$to_email = array("dhanalakshmi.g@relyonsoft.com");
				//$to_cc = array("manjunath.sm@relyonsoft.com");
				//$to_bcc = array("samar.s@relyonsoft.com");
				rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray,$replyto);
				//rslmail($fromname, $fromemail, $to_email, $subject, $text, $html,$to_cc,$to_bcc,$filearray,$replyto);
				
				//Insert the mail forwarded details to the logs table
				//$bccmailid = 'bills@relyonsoft.com,bigmail@relyonsoft.com,dealers@relyonsoft.com,madhuri.hn@relyonsoft.com'; 
				//inserttologs(imaxgetcookie('userid'),$slno,$fromname,$fromemail,$emailid,null,$bccmailid,$subject);
				//return('1^Sent successfully');
			}
	}
}
       

    $fromidNew=$from_id+100;
    $toidNew = 100;

    $query3="INSERT INTO tbl_cronRange(from_id, to_id, regdatetime) VALUES ('$fromidNew','$toidNew', now())";
    $fetch3 = runmysqlquery($query3);

?>