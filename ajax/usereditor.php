<?
ob_start("ob_gzhandler");
include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');
include('../inc/checksession.php');

$lastslno = $_POST['lastslno'];
$type = $_POST['type'];
if(imaxgetcookie('userid')<> '') 
$userid = imaxgetcookie('userid');
else
{ 
	echo('Thinking to redirect');
	exit;
}
switch($type)
{	

	case 'generateuserlist':
	{
		$login_type = $_POST['login_type'];
		$login_typepiece = ($login_type == "")?(""):(" AND inv_mas_users.disablelogin = '".$login_type."' ");
		$query = "SELECT slno,fullname FROM inv_mas_users where slno <> '532568855' ".$login_typepiece." ORDER BY fullname";
		$result = runmysqlquery($query);
		$grid = '';
		$count = 1;
		while($fetch = mysqli_fetch_array($result))
		{
			if($count > 1)
			$grid .='^*^';
			$grid .= $fetch['fullname'].'^'.$fetch['slno'];
			$count++;
		}
		echo($grid);
	}
	break;
	
	case 'userdetailstoform':
	{
		$query1 = "SELECT count(*) as count from inv_mas_users where slno = '".$lastslno."'";
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)
		{
			$query = "SELECT slno,username,fullname,description,registration,withoutscratchcard,dealer,bills,credits,editcustomercontact,products,mergecustomer,suggestedmerging,blockcancel,transfercard,disablelogin,createddate, passwordchanged ,initialpassword ,editdealerpassword ,contactdetailsreport,productshippedreports,dealerinvreports,regreports,invoicedetailsreport,updationdetailsreport,editcustomerpassword,cellno,emailid,customerpendingrequest,dealerpendingrequest,cusattachcard,hardwarelock,districtmapping,customerpayment,welcomemail,scheme,schemepricing,producttodealer,producttodealers,schemetodealer,smscreditstocustomers,smscreditstodealer,smsaccounttocustomers,smsaccounttodealer,smssummary,smsreceipttocustomers,smsreceiptstodealer,cuspinattachedreport,suggestedmerging,labelprint,viewinvoice,updationsummaryreport,salessummaryreport,viewrcidata,crossproductreport,updationdetailedreport,crossproductsales,invoicing,invoiceregister,receiptsregister,outstandingregister,manageinvoice,bulkprintinvoice,masterimplementation,createimplementation,reregistration,impsummaryreport,datainaccuracyreport,impstatusreport, receiptreconsilation,activitylog,notregisteredreport,categorysummary,addinvoices,addbills,forcesurrender,surrenderreport,newtransferpin,transactionsreport,pindetails,importinvoices,importreceipt,customuser,mailamccustomer,addproductsnew,importinvoicesgst,autoreceiptreconciliation,transferredpinsreport,managedealerinvoice,dealerreceipts,dealerreceiptreconciliation,dealerinvoiceregister,dealerbulkprintinvoice,matrixinvoicing,managematrixinvoice,matrixbulkprintinvoice,matrixinvoiceregister FROM inv_mas_users WHERE slno = '".$lastslno."';";
			$fetch = runmysqlqueryfetch($query);
			echo($fetch['slno'].'^'.$fetch['username'].'^'.$fetch['fullname'].'^'.$fetch['initialpassword'].'^'.
			$fetch['description'].'^'.$fetch['registration'].'^'.$fetch['withoutscratchcard'].'^'.$fetch['dealer'].'^'.
			$fetch['bills'].'^'.$fetch['credits'].'^'.$fetch['editcustomercontact'].'^'.$fetch['products'].'^'.
			$fetch['mergecustomer'].'^'.$fetch['blockcancel'].'^'.$fetch['transfercard'].'^'.$fetch['disablelogin'].'^'.
			changedateformatwithtime($fetch['createddate']).'^'.$fetch['regreports'].'^'.$fetch['contactdetailsreport'].'^'.
			$fetch['dealerinvreports'].'^'.$fetch['productshippedreports'].'^'.$fetch['invoicedetailsreport'].'^'.
			$fetch['updationdetailsreport'].'^'.$fetch['editcustomerpassword'].'^'.$fetch['cellno'].'^'.
			$fetch['emailid'].'^'.$fetch['customerpendingrequest'].'^'.$fetch['dealerpendingrequest'].'^'.
			$fetch['cusattachcard'].'^'.$fetch['hardwarelock'].'^'.$fetch['districtmapping'].'^'.$fetch['customerpayment'].'^'.
			$fetch['welcomemail'].'^'.$fetch['scheme'].'^'.$fetch['schemepricing'].'^'.$fetch['producttodealer'].'^'.
			$fetch['producttodealers'].'^'.$fetch['schemetodealer'].'^'.$fetch['smscreditstocustomers'].'^'.$fetch['smscreditstodealer'].'^'.
			$fetch['smsaccounttocustomers'].'^'.$fetch['smsaccounttodealer'].'^'.strtolower($fetch['passwordchanged']).'^'.
			$fetch['initialpassword'].'^'.$fetch['smssummary'].'^'.$fetch['smsreceipttocustomers'].'^'.$fetch['smsreceiptstodealer'].'^'.
			$fetch['editdealerpassword'].'^'.$fetch['cuspinattachedreport'].'^'.$fetch['suggestedmerging'].'^'.$fetch['labelprint'].'^'.
			$fetch['viewinvoice'].'^'.$fetch['updationsummaryreport'].'^'.$fetch['salessummaryreport'].'^'.$fetch['viewrcidata'].'^'.
			$fetch['crossproductreport'].'^'.$fetch['updationdetailedreport'].'^'.$fetch['crossproductsales'].'^'.$fetch['invoicing'].'^'.
			$fetch['invoiceregister'].'^'.$fetch['receiptsregister'].'^'.$fetch['outstandingregister'].'^'.$fetch['manageinvoice'].'^'.
			$fetch['bulkprintinvoice'].'^'.$fetch['masterimplementation'].'^'.$fetch['createimplementation'].'^'.$fetch['reregistration'].'^'.
			$fetch['impsummaryreport'].'^'.$fetch['datainaccuracyreport'].'^'.$fetch['impstatusreport'].'^'.$fetch['receiptreconsilation'].'^'.
			$fetch['activitylog'].'^'.$fetch['notregisteredreport'].'^'.$fetch['categorysummary'].'^'.$fetch['addinvoices'].'^'.
			$fetch['addbills'].'^'.$fetch['forcesurrender'].'^'.$fetch['surrenderreport'].'^'.$fetch['newtransferpin'].'^'.
			$fetch['transactionsreport'].'^'.$fetch['pindetails'].'^'.$fetch['importinvoices'].'^'.$fetch['importreceipt'].'^'.
			$fetch['customuser'].'^'.$fetch['mailamccustomer'].'^'.$fetch['addproductsnew'].'^'.$fetch['importinvoicesgst'].'^'.
			$fetch['autoreceiptreconciliation'].'^'.$fetch['transferredpinsreport'].'^'.$fetch['managedealerinvoice'].'^'.
			$fetch['dealerreceipts'].'^'.$fetch['dealerreceiptreconciliation'].'^'.$fetch['dealerinvoiceregister'].'^'.$fetch['dealerbulkprintinvoice'].'^'.
			$fetch['matrixinvoicing'].'^'.$fetch['managematrixinvoice'].'^'.$fetch['matrixbulkprintinvoice'].'^'.$fetch['matrixinvoiceregister']);
		}
		else
		{
			echo($lastslno.'^'.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'^'.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
		}
	}
	break;
	case 'resetpwd':
	{
		$password = $_POST['password'];
		$lastslno = $_POST['lastslno'];
		$query1 = "UPDATE inv_mas_users SET loginpassword = AES_ENCRYPT('".$password."','imaxpasswordkey'),initialpassword = '".$password."',passwordchanged ='N' WHERE slno = '".$lastslno."'";
		$result = runmysqlquery($query1);
		$query = "select  initialpassword  as initialpassword from inv_mas_users WHERE slno = '".$lastslno."'";
		$result = runmysqlqueryfetch($query);
		echo($result['initialpassword']);
		//echo($query1.'^'.$query);
	}
	break;
	case 'save':
	{
		$username = $_POST['username'];
		$fullname = $_POST['fullname'];
		$password = $_POST['password'];
		$description = $_POST['description'];
		$cellno = $_POST['cellno'];
		$emailid = $_POST['emailid'];
		$registration = $_POST['registration']; 
		$withoutscratchcard = $_POST['withoutscratchcard']; 
		$forcesurrender = $_POST['forcesurrender']; 
		$surrenderreport = $_POST['surrenderreport']; 
		$newtransferpin = $_POST['newtransferpin'];
		$transactionsreport = $_POST['transactionsreport'];
		$dealer = $_POST['dealer']; 
		$bills = $_POST['bills']; 
		$credits = $_POST['credits']; 
		$editcustomercontact = $_POST['editcustomercontact'];
		$products = $_POST['products'];
		$mergecustomer = $_POST['mergecustomer'];
		$blockcancel = $_POST['blockcancel'];
		$transfercard = $_POST['transfercard'];
		$disablelogin = $_POST['disablelogin'];
		$regreports = $_POST['regreports'];
		$contactdetails = $_POST['contactdetails'];
		$dealerinvreports = $_POST['dealerreports'];
		$productshipped = $_POST['productshipped'];
		$invoicedetails = $_POST['invoicedetails'];
		$updationduedetails = $_POST['updationduedetails'];
		$editcustomerpassword = $_POST['editcustomerpassword'];
		$editdealerpassword = $_POST['editdealerpassword'];
		$customerpendingrequest = $_POST['customerpendingrequest'];
		$dealerpendingrequest = $_POST['dealerpendingrequest'];
		$cusattachcard = $_POST['cusattachcard'];
		$hardwarelock = $_POST['hardwarelock'];
		$customerpayment = $_POST['customerpayment'];
		$welcomemail = $_POST['welcomemail'];
		$districtmapping = $_POST['districtmapping'];
		$scheme = $_POST['scheme'];
		$schemepricing = $_POST['schemepricing'];
		$producttodealer = $_POST['producttodealer'];
		$producttodealers = $_POST['producttodealers'];
		$schemetodealer = $_POST['schemetodealer'];
		$smscreditstocustomers = $_POST['smscreditstocustomers'];
		$smscreditstodealer = $_POST['smscreditstodealer'];
		$smsaccounttocustomers = $_POST['smsaccounttocustomers'];
		$smsaccounttodealer = $_POST['smsaccounttodealer'];
		$smscreditssummary = $_POST['smscreditssummary'];
		$smsreceiptstocustomers = $_POST['smsreceiptstocustomers'];
		$smsreceiptstodealers = $_POST['smsreceiptstodealers'];
		$pinnoattachedreport = $_POST['pinnoattachedreport'];
		$suggestedmerging = $_POST['suggestedmerging'];
		$labelprint = $_POST['labelprint'];
		$viewinvoice = $_POST['viewinvoice'];
		$updationsummaryreport = $_POST['updationsummaryreport'];
		$salessummaryreport = $_POST['salessummaryreport'];
		$viewrcidata = $_POST['viewrcidata'];
		$crossproductreport = $_POST['crossproductreport'];
		$updationdetailedreport = $_POST['updationdetailedreport'];
		$crossproductsales = $_POST['crossproductsales'];
		$invoicing = $_POST['invoicing'];
		$invoice_register = $_POST['invoice_register'];
		$receipt_register = $_POST['receipt_register'];
		$outstanding_register = $_POST['outstanding_register'];
		$manageinvoice = $_POST['manageinvoice'];
		$bulkprintinvoice = $_POST['bulkprintinvoice'];
		$masterimplementation = $_POST['masterimplementation'];
		$createimplementation = $_POST['createimplementation'];
		$reregistration = $_POST['reregistration'];
		$impsummaryreport = $_POST['impsummaryreport'];
		$datainaccuracyreport = $_POST['datainaccuracyreport'];
		$impstatusreport = $_POST['impstatusreport'];
		$receiptreconsilation = $_POST['receiptreconsilation'];
		$activitylog = $_POST['activitylog'];
		$notregisteredreport = $_POST['notregisteredreport'];
		$categorysummary = $_POST['categorysummary'];
		$addinvoice = $_POST['addinvoice'];
		$addbills = $_POST['addbills'];
		$pindetails = $_POST['pindetails'];
		$importinvoices = $_POST['importinvoices'];
		$importreceipt = $_POST['importreceipt'];
                $customuser = $_POST['customuser'];
                $customusertest = $_POST['customusertest'];
                $customfilter = $_POST['customfilter'];
                $mailamccustomer= $_POST['mailamccustomer'];
                $addproductsnew = $_POST['addproductsnew'];
		$importinvoicesgst = $_POST['importinvoicesgst'];
		$createddate = datetimelocal('Y-m-d').' '.datetimelocal('H:i:s');
		//added on 07-02-2019
		$autoreceiptreconciliation = $_POST['autoreceiptreconsilation'];
		//added on 26-09-2019
        $transferredpinsreport = $_POST['transferredpinsreport'];
		$managedealerinvoice = $_POST['managedealerinvoice'];
		$dealerreceipts = $_POST['dealerreceipts'];
		$dealerreceiptreconciliation = $_POST['dealerreceiptreconciliation'];
		$dealerinvoice_register = $_POST['dealerinvoice_register'];
		$dealerbulkprintinvoice = $_POST['dealerbulkprintinvoice'];
		$matrixinvoicing = $_POST['matrixinvoicing'];
		$managematrixinvoice = $_POST['managematrixinvoice'];
		$matrixbulkprintinvoice = $_POST['matrixbulkprintinvoice'];
		$matrixinvoice_register = $_POST['matrixinvoice_register'];
		if($lastslno == "")
		{
			$query = "SELECT count(*) as count from inv_mas_users where username = '".$username."' ";
			$fetch = runmysqlqueryfetch($query);
			if($fetch['count'] >0)
			{
				echo("2^"."User Name already exists please enter a different User Name");
			}
			else
			{
				$query = "SELECT (MAX(slno) + 1) AS newuserid FROM inv_mas_users";
				$fetch = runmysqlqueryfetch($query);
				$lastslno = $fetch['newuserid'];
				$password = generatepwd();
					
				$query = "INSERT INTO inv_mas_users(slno,username,fullname,initialpassword,loginpassword,passwordchanged,description,registration,withoutscratchcard,dealer,bills,credits,editcustomercontact,products,mergecustomer,blockcancel,transfercard,disablelogin,createddate,regreports,dealerinvreports,contactdetailsreport,productshippedreports,invoicedetailsreport,updationdetailsreport,editcustomerpassword,cellno,emailid,customerpendingrequest,dealerpendingrequest,hardwarelock,cusattachcard,customerpayment,welcomemail,districtmapping,lastmodifieddate,lastmodifiedby,createdip,lastmodifiedip,schemetodealer,producttodealer,producttodealers,schemepricing,scheme,smscreditstocustomers,smscreditstodealer,smsaccounttocustomers,smsaccounttodealer,smssummary,smsreceipttocustomers,smsreceiptstodealer,editdealerpassword,cuspinattachedreport,suggestedmerging,labelprint,viewinvoice,updationsummaryreport,salessummaryreport,viewrcidata,crossproductreport,updationdetailedreport,crossproductsales,invoicing,invoiceregister,receiptsregister,outstandingregister,manageinvoice,bulkprintinvoice,masterimplementation,createimplementation,reregistration,impsummaryreport,datainaccuracyreport,impstatusreport,receiptreconsilation,activitylog,notregisteredreport,categorysummary,addinvoices, addbills,forcesurrender,surrenderreport,newtransferpin,transactionsreport,pindetails,importinvoices,importreceipt,customuser,customusertest,customfilter,mailamccustomer,addproductsnew,importinvoicesgst,autoreceiptreconciliation,transferredpinsreport,managedealerinvoice,dealerreceipts,dealerreceiptreconciliation,dealerinvoiceregister,dealerbulkprintinvoice,matrixinvoicing,managematrixinvoice,matrixbulkprintinvoice,matrixnvoiceregister) 
				VALUES('".$lastslno."','".trim($username)."','".trim($fullname)."','".$password."',AES_ENCRYPT('".$password."','imaxpasswordkey'),'N','".$description."','".$registration."','".$withoutscratchcard."','".$dealer."','".$bills."','".$credits."','".$editcustomercontact."','".$products."','".$mergecustomer."','".$blockcancel."','".$transfercard."','".$disablelogin."','".$createddate."','".$regreports."','".$dealerinvreports."','".$contactdetails."','".$productshipped."','".$invoicedetails."','".$updationduedetails."','".$editcustomerpassword."','".$cellno."','".$emailid."','".$customerpendingrequest."','".$dealerpendingrequest."','".$cusattachcard."','".$hardwarelock."','".$customerpayment."','".$welcomemail."','".$districtmapping."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".$_SERVER['REMOTE_ADDR']."','".$schemetodealer."','".$producttodealer."','".$producttodealers."','".$schemepricing."','".$scheme."','".$smscreditstocustomers."','".$smscreditstodealer."','".$smsaccounttocustomers."','".$smsaccounttodealer."','".$smscreditssummary."','".$smsreceiptstocustomers."','".$smsreceiptstodealers."','".$editdealerpassword."','".$pinnoattachedreport."','".$suggestedmerging."','".$labelprint."','".$viewinvoice."','".$updationsummaryreport."','".$salessummaryreport."','".$viewrcidata."','".$crossproductreport."','".$updationdetailedreport."','".$crossproductsales."','".$invoicing."','".$invoice_register."','".$receipt_register."','".$outstanding_register."','".$manageinvoice."','".$bulkprintinvoice."','".$masterimplementation."','".$createimplementation."','".$reregistration."','".$impsummaryreport."','".$datainaccuracyreport."','".$impstatusreport."','".$receiptreconsilation."','".$activitylog."','".$notregisteredreport."','".$categorysummary."','".$addinvoice."','".$addbills."','".$forcesurrender."','".$surrenderreport."','".$newtransferpin."','".$transactionsreport."','".$pindetails."','".$importinvoices."','".$importreceipt."','".$customuser."','".$customusertest."','".$customfilter."','".$mailamccustomer."','".$addproductsnew."','".$importinvoicesgst."','".$autoreceiptreconsilation."','".$transferredpinsreport."','".$managedealerinvoice."','".$dealerreceipts."','".$dealerreceiptreconciliation."','".$dealerinvoice_register."','".$dealerbulkprintinvoice."','".$matrixinvoicing."','".$managematrixinvoice."'.'".$matrixbulkprintinvoice."','".$matrixinvoice_register."');";
				$result = runmysqlquery($query);
				echo("1^"."User Record '".$lastslno."' Saved Successfully");
				
			}
			
		}
		else
		{
			$query = "SELECT count(*) as count from inv_mas_users where username = '".$username."' AND slno <> '".$lastslno."'";
			$fetch = runmysqlqueryfetch($query);
			if($fetch['count'] >0)
			{
				echo("2^"."User Name already exists please enter a different User Name");
	
			}
			else
			{
			$query = "UPDATE inv_mas_users SET username = '".trim($username)."', fullname = '".trim($fullname)."', description = '".$description."', withoutscratchcard = '".$withoutscratchcard."', registration = '".$registration."', dealer = '".$dealer."', bills = '".$bills."', credits = '".$credits."', editcustomercontact = '".$editcustomercontact."', products = '".$products."', mergecustomer = '".$mergecustomer."',suggestedmerging = '".$suggestedmerging."', blockcancel = '".$blockcancel."', transfercard = '".$transfercard."', disablelogin = '".$disablelogin."', regreports = '".$regreports."', dealerinvreports = '".$dealerinvreports."', contactdetailsreport = '".$contactdetails."', productshippedreports = '".$productshipped."',invoicedetailsreport='".$invoicedetails."', updationdetailsreport = '".$updationduedetails."' , editcustomerpassword = '".$editcustomerpassword."', cellno = '".$cellno."', emailid = '".$emailid."', customerpendingrequest = '".$customerpendingrequest."' , dealerpendingrequest = '".$dealerpendingrequest."' , cusattachcard = '".$cusattachcard."' , hardwarelock = '".$hardwarelock."' , districtmapping = '".$districtmapping."', customerpayment = '".$customerpayment."',welcomemail= '".$welcomemail."' ,lastmodifieddate ='".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedby ='".$userid."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."' , schemetodealer = '".$schemetodealer."',scheme = '".$scheme."',producttodealer = '".$producttodealer."',producttodealers = '".$producttodealers."',schemepricing = '".$schemepricing."',smscreditstocustomers= '".$smscreditstocustomers."',smscreditstodealer= '".$smscreditstodealer."',smsaccounttocustomers= '".$smsaccounttocustomers."',smsaccounttodealer= '".$smsaccounttodealer."',passwordchanged= 'Y',smssummary = '".$smscreditssummary."',smsreceipttocustomers = '".$smsreceiptstocustomers."',smsreceiptstodealer = '".$smsreceiptstodealers."',editdealerpassword = '".$editdealerpassword."',cuspinattachedreport = '".$pinnoattachedreport."',labelprint = '".$labelprint."' ,viewinvoice = '".$viewinvoice."',updationsummaryreport = '".$updationsummaryreport."',salessummaryreport = '".$salessummaryreport."' ,viewrcidata = '".$viewrcidata."',crossproductreport = '".$crossproductreport."' ,updationdetailedreport = '".$updationdetailedreport."',crossproductsales = '".$crossproductsales."',invoicing = '".$invoicing."',invoiceregister = '".$invoice_register."',receiptsregister = '".$receipt_register."',outstandingregister = '".$outstanding_register."',manageinvoice = '".$manageinvoice."',bulkprintinvoice = '".$bulkprintinvoice."',masterimplementation = '".$masterimplementation."',createimplementation = '".$createimplementation."',reregistration = '".$reregistration."',impsummaryreport = '".$impsummaryreport."',datainaccuracyreport = '".$datainaccuracyreport."',impstatusreport = '".$impstatusreport."',receiptreconsilation = '".$receiptreconsilation."',activitylog = '".$activitylog."',notregisteredreport = '".$notregisteredreport."' ,categorysummary = '".$categorysummary."',addinvoices = '".$addinvoice."',addbills = '".$addbills."',forcesurrender = '".$forcesurrender."', surrenderreport ='".$surrenderreport."', newtransferpin ='".$newtransferpin."', transactionsreport ='".$transactionsreport."', pindetails ='".$pindetails."', importinvoices ='".$importinvoices."', importreceipt ='".$importreceipt."', customuser ='".$customuser."', customusertest ='".$customusertest."', customfilter ='".$customfilter."',mailamccustomer ='".$mailamccustomer."', addproductsnew ='".$addproductsnew."', importinvoicesgst ='".$importinvoicesgst."',autoreceiptreconciliation = '".$autoreceiptreconciliation."',transferredpinsreport='".$transferredpinsreport."',managedealerinvoice='".$managedealerinvoice."',dealerreceipts='".$dealerreceipts."',dealerreceiptreconciliation = '".$dealerreceiptreconciliation."',dealerinvoiceregister = '".$dealerinvoice_register."',dealerbulkprintinvoice = '".$dealerbulkprintinvoice."',matrixinvoicing='".$matrixinvoicing."',managematrixinvoice='".$managematrixinvoice."',matrixbulkprintinvoice='".$matrixbulkprintinvoice."',matrixinvoiceregister = '".$matrixinvoice_register."' WHERE slno = '".$lastslno."'";

			$result = runmysqlquery($query);
			echo("1^"."User Record '".$lastslno."' Saved Successfully");
			}
		}
		
	}
	break;
	case 'delete':
	{
		$result = runmysqlqueryfetch("SELECT slno FROM inv_mas_users WHERE  slno = '".$lastslno."'");
		$fetchuserid = $result['slno'];
		$recordflag1 = deleterecordcheck($lastslno,'userid','inv_bill');
		$recordflag2 = deleterecordcheck($lastslno,'createdby','inv_credits');
		$recordflag3 = deleterecordcheck($lastslno,'lastmodifiedby','inv_credits');
		$recordflag4 = deleterecordcheck($lastslno,'createdby','inv_customeramc');
		$recordflag5 = deleterecordcheck($lastslno,'lastmodifiedby','inv_customeramc');
		$recordflag6 = deleterecordcheck($lastslno,'createdby','inv_customerinteraction');
		$recordflag7 = deleterecordcheck($lastslno,'lastmodifiedby','inv_customerinteraction');
		$recordflag8 = deleterecordcheck($lastslno,'userid','inv_dealercard');
		$recordflag9 = deleterecordcheck($lastslno,'generatedby','inv_customerproduct');
		$recordflag10 = deleterecordcheck($lastslno,'createdby','inv_hardwarelock');
		$recordflag11 = deleterecordcheck($lastslno,'lastmodifiedby','inv_hardwarelock');
		$recordflag12 = deleterecordcheck($lastslno,'createdby','inv_mas_customer');
		$recordflag13 = deleterecordcheck($lastslno,'lastmodifiedby','inv_mas_customer');
		$recordflag14 = deleterecordcheck($lastslno,'userid','inv_mas_dealer');
		$recordflag15 = deleterecordcheck($lastslno,'lastmodifiedby','inv_mas_dealer');
		$recordflag16 = deleterecordcheck($lastslno,'userid','inv_mas_product');
		$recordflag17 = deleterecordcheck($lastslno,'lastmodifiedby','inv_mas_product');
		if($recordflag1 == true && $recordflag2 == true && $recordflag3 == true && $recordflag4 == true && $recordflag5 == true && $recordflag6 == true && $recordflag7 == true && $recordflag8 == true && $recordflag9 == true && $recordflag10 == true &&$recordflag11 == true && $recordflag12 == true && $recordflag13 == true && $recordflag14 == true && $recordflag15 == true && $recordflag16 == true&& $recordflag17 == true)
		{
			$query1 = "DELETE FROM inv_mas_users WHERE slno = '".$lastslno."'";
			$result = runmysqlquery($query1);
			echo("1^"."User Record Deleted Successfully");
		}
		else
		{
			echo("2^"."User Record cannot be deleted as the record referred.");
		}
	}
	break;
}
?>