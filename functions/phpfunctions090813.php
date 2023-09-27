<?
//Include Database Configuration details
if(file_exists("../inc/dbconfig.php"))
	include('../inc/dbconfig.php');
elseif(file_exists("../../inc/dbconfig.php"))
	include('../../inc/dbconfig.php');
else
	include('./inc/dbconfig.php');

//Connect to host
$newconnection = mysql_connect($dbhost, $dbuser, $dbpwd) or die("Cannot connect to Mysql server host");


/* -------------------- Get local server time [by adding 5.30 hours] -------------------- */
function datetimelocal($format)
{
	//$diff_timestamp = date('U') + 19800;
	$date = date($format);
	return $date;
}

/* -------------------- Run a query to database -------------------- */
function runmysqlquery($query)
{
	global $newconnection;
	
	$dbname = 'relyon_imax';
	if($_SERVER['HTTP_HOST'] == "nagamani" || $_SERVER['HTTP_HOST'] == "192.168.2.50")
	{
		$dbname = 'relyon_imax_new';
	}
	else if($_SERVER['HTTP_HOST'] == "relyonsoft.info")
	{
		$dbname = 'relyon2_nagamani';
	}

	//Connect to Database
	mysql_select_db($dbname,$newconnection) or die("Cannot connect to database");
	set_time_limit(3600);
	//Run the query
	$result = mysql_query($query,$newconnection) or die(" run Query Failed in Runquery function1.".$query); //;
	
	//Return the result
	return $result;
}

/* -------------------- Run a query to database with fetching from SELECT operation -------------------- */
function runmysqlqueryfetch($query)
{
	global $newconnection;
	
	$dbname = 'relyon_imax';
	if($_SERVER['HTTP_HOST'] == "nagamani" || $_SERVER['HTTP_HOST'] == "192.168.2.50")
	{
		$dbname = 'relyon_imax_new';
	}
	else if($_SERVER['HTTP_HOST'] == "relyonsoft.info")
	{
		$dbname = 'relyon2_nagamani';
	}

	//Connect to Database
	mysql_select_db($dbname,$newconnection) or die("Cannot connect to database");
	set_time_limit(3600);
	//Run the query
	$result = mysql_query($query,$newconnection) or die(" run Query Failed in Runquery function1.".$query); //;
	
	//Fetch the Query to an array
	$fetchresult = mysql_fetch_array($result) or die("Cannot fetch the query result.".$query);
	
	//Return the result
	return $fetchresult;
}

/* -------------------- To change the date format from DD-MM-YYYY to YYYY-MM-DD or reverse -------------------- */

function changedateformat($date)
{
	if($date <> "0000-00-00")
	{
		if(strpos($date, " "))
		$result = split(" ",$date);
		else
		$result = split("[:./-]",$date);
		$date = $result[2]."-".$result[1]."-".$result[0];
	}
	else
	{
		$date = "";
	}
	return $date;
}

function runicicidbquery($query)
{
	global $newconnection;
	 $icicidbname = "relyon_icici";
 
	 //Connect to Database
	 mysql_select_db($icicidbname,$newconnection) or die("Cannot connect to database");
	 set_time_limit(3600);
	 
	 //Run the query
	 $result = mysql_query($query,$newconnection) or die(mysql_error());
	 
	 //Return the result
	 return $result;
}

function changetimeformat($time)
{
	if($time <> "00:00:00")
	{
		$result = split(":",$time);
		$time = $result[0].":".$result[1];
	}
	else
	{
		$time = "";
	}
	return $time;
}

function changedateformatwithtime($date)
{
	if($date <> "0000-00-00 00:00:00")
	{
		if(strpos($date, " "))
		{
			$result = split(" ",$date);
			if(strpos($result[0], "-"))
				$dateonly = split("-",$result[0]);
			$timeonly =split(":",$result[1]);
			$timeonlyhm = $timeonly[0].':'.$timeonly[1];
			$date = $dateonly[2]."-".$dateonly[1]."-".$dateonly[0]." ".'('.$timeonlyhm.')';
		}
			
	}
	else
	{
		$date = "";
	}
	return $date;
}

function cusidsplit($customerid)
{
	$strlen = strlen($customerid);
	if($strlen <> '17')
	{
		if(strpos($customerid, " "))
		$result = split(" ",$customerid);
		else
		$result = split("[:./-]",$customerid);
		$customerid = $result[0].$result[1].$result[2].$result[3];
	}
	/*else
	{
		$customerid = "";
	}*/
		return $customerid;
}

function cusidcombine($customerid)
{
	$result1 = substr($customerid,0,4);
	$result2 = substr($customerid,4,4);
	$result3 = substr($customerid,8,4);
	$result4 = substr($customerid,12,5);
	$result = $result1.'-'.$result2.'-'.$result3.'-'.$result4;
	return $result;
}

function generatepwd()
{
	$charecterset0 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
	$charecterset1 = "1234567890";
	for ($i=0; $i<4; $i++)
	{
		$usrpassword .= $charecterset0[rand(0, strlen($charecterset0))];
	}
	for ($i=0; $i<4; $i++)
	{
		$usrpassword .= $charecterset1[rand(0, strlen($charecterset1))];
	}
	return $usrpassword;
}

function generatesoftkeydummy($computerid)
{
	//$softkey = generateserial($computerid, "DOTNET");
	$softkey = '1234-1234-1234';
	return $softkey;
}

function getpagelink($linkvalue)
{
	switch($linkvalue)
	{
		case 'producttodealers':return '../main/producttodealers.php'; break;
		case 'customer': return '../main/customer.php'; break;
		case 'dealer': return '../main/dealer.php'; break;
		case 'blockcancel': return '../cards/blockcancel.php'; break;
		case 'product': return '../main/products.php'; break;
		case 'usereditor': return '../usermanagement/usereditor.php'; break;
		case 'bill': return '../main/bills.php'; break;
		case 'home_dashboard': return '../home/dashboard.php'; break;
		case 'mergecustomer': return '../main/mergecustomer.php'; break;
		case 'cardsearch': return '../cards/cardsearch.php'; break;
		case 'externalregistration': return '../external/index.php'; break;
		case 'transfercards': return '../cards/transfer.php'; break;
		case 'credits': return '../main/credit.php'; break;
		case 'customeramc': return '../main/customeramc.php'; break;
		case 'unauthorised': return '../usermanagement/unauthorised.php'; break;
		case 'changepassword': return '../usermanagement/changepw.php'; break;
		case 'registrationdetails': return '../reports/registration.php'; break;
		case 'invoicedetails': return '../reports/invoicedetails.php'; break;
		case 'productshippeddetails': return '../reports/productshipped.php'; break;
		case 'cusinteraction': return '../main/cusinteraction.php'; break;
		case 'mergecustomerlist': return '../main/mergercustomerlist.php'; break;
		case 'testajax': return '../ajaxcheck/customer.php'; break;
		case 'hardwarelock': return '../main/hardwarelock.php'; break;
		case 'dealerdetails': return '../reports/dealerreport.php'; break;
		case 'contactdetails': return '../reports/contactdetailsreport.php'; break;
		case 'labelcontactdetails': return '../reports/labelsforcontactdetails.php'; break;
		case 'cuscardattachreport': return '../reports/cuscardattachreport.php'; break;
		case 'surrenderreport': return '../reports/surrenderreport.php'; break;
		case 'transactionsreport': return '../reports/transactionsreport.php'; break;
		case 'contactreport': return '../reports/contact.php'; break;
		case 'updationduedetails': return '../reports/updationdue.php'; break;
		case 'customerprofileupdate': return '../main/cusprofileupdate.php'; break;
		case 'custpayment': return '../main/custpayment.php'; break;
		case 'dealerprofileupdate': return '../main/dealerprofileupdate.php'; break;
		case 'districtmapping': return '../main/districtmapping.php'; break;
		case 'cusattachcard': return '../main/cusattachcard.php'; break;
		case 'scheme': return '../main/scheme.php'; break;
		case 'schemepricing': return '../main/schemepricing.php'; break;
		case 'productmapping': return '../main/productmapping.php'; break;
		case 'schememapping': return '../main/schememapping.php'; break;
		case 'resentpwd': return '../main/resentpwd.php'; break;
		case 'smsaccount': return '../main/smsaccount.php'; break;
		case 'smscredits': return '../main/smscredits.php'; break;
		case 'smsreceipt': return '../main/smsreceipt.php'; break;
		case 'smsreceiptdealers': return '../main/smsreceiptdealers.php'; break;
		case 'smsaccountdealers': return '../main/smsaccountdealers.php'; break;
		case 'smscreditsdealers': return '../main/smscreditsdealers.php'; break;
		case 'customerquotereq': return '../main/customerquotereq.php'; break;
		case 'smscreditssummary': return '../main/smscreditssummary.php'; break;
		case 'viewinvoice': return '../main/viewinvoice.php'; break;
		case 'rcidataviewer': return '../main/rcidataviewer.php'; break;
		case 'updationsummary': return '../reports/updationsummary.php'; break;
		case 'crossproductdetails': return '../reports/crossproductreport.php'; break;
		case 'crossproduct': return '../main/crossproductinfo.php'; break;
		case 'updationdetailedreport': return '../reports/updationdetailedreport.php'; break;
		case 'salessummary': return '../reports/salessummary.php'; break;
		case 'charts': return '../charts/index.php'; break;
		case 'deployment': return '../main/deployment.php'; break;
		case 'invoicing': return '../main/invoicing.php'; break;
		case 'receipts': return '../main/receipts.php'; break;
		case 'invoiceregister': return '../main/invoiceregister.php'; break;
		case 'receiptregister': return '../main/receiptregister.php'; break;
		case 'outstandingregister': return '../main/outstandingregister.php'; break;
		case 'manageinvoice': return '../main/updateinvoice.php'; break;
		case 'bulkprint': return '../main/bulkprinting.php'; break;
		case 'implementation': return '../main/implementation.php'; break;
		case 'ssmcallstats': return '../reports/ssm-callstats.php'; break;
		case 'implementationsummary': return '../reports/implementationreport.php'; break;
		case 'customeranalysis': return '../main/custdata.php'; break;
		case 'implementationdetailed': return '../reports/implementationstatusreport.php'; break;
		case 'receiptreconciliation': return '../main/receiptreconsilation.php'; break;
		case 'activitylog': return '../main/activitylog.php'; break;
		case 'notregistered': return '../reports/notregistered.php'; break;
		case 'categorysummary': return '../reports/categorysummary.php'; break;
		case 'addinvoices': return '../main/addinvoices.php'; break;
		case 'addbills': return '../main/addbills.php'; break;
		case 'invoiceimport': return '../reports/invoiceimport.php'; break;
		default: return '../home/dashboard.php'; break;
	}
}

function getpagetitle($linkvalue)
{
	switch($linkvalue)
	{
		case 'producttodealers':return 'Saral iMax : Product Mapping to Dealers'; break;
		case 'customer': return 'Saral iMax : Customer Master'; break;
		case 'dealer': return 'Saral iMax : Dealer Master'; break;
		case 'blockcancel': return 'Saral iMax : Block / Cancel PIN Numbers'; break;
		case 'product': return 'Saral iMax : Product Master'; break;
		case 'usereditor': return 'Saral iMax : User Editor Screen'; break;
		case 'bill': return 'Saral iMax : Purchase Screen'; break;
		case 'mergecustomer': return 'Saral iMax : Merge Customer'; break;
		case 'mergecustomerlist': return 'Saral iMax : Merge Customer List'; break;
		case 'home_dashboard': return 'Saral iMax : Dashboard'; break;
		case 'cardsearch': return 'Saral iMax : PIN Number Search'; break;
		case 'externalregistration': return 'Saral iMax : External Registration'; break;
		case 'transfercards': return 'Saral iMax : Transfer PIN Numbers'; break;
		case 'credits': return 'Saral iMax : Credits'; break;
		case 'customeramc': return 'Saral iMax : Customer AMC'; break;
		case 'hardwarelock': return 'Saral iMax : Hardware Lock'; break;
		case 'unauthorised': return 'Saral iMax : Unauthorsed Viewer'; break;
		//case 'logout': return 'Inventory : Logout'; break;
		case 'changepassword': return 'Saral iMax : Change Password'; break;
		case 'registrationdetails': return 'Saral iMax : Registration Details'; break;
		case 'invoicedetails': return 'Saral iMax : Invoice Details'; break;
		case 'productshippeddetails': return 'Saral iMax : Product Shipped Details'; break;
		case 'cusinteraction': return 'Saral iMax : Customer Interaction Details'; break;
		case 'dealerdetails': return 'Saral iMax : Dealer Stock Details'; break;
		case 'contactdetails': return 'Saral iMax : Customer Contact Details'; break;
		case 'labelcontactdetails': return 'Saral iMax : Labels for Customer Contact Details'; break;
		case 'cuscardattachreport': return 'Saral iMax : Customer PIN No Attached Details'; break;
		case 'surrenderreport': return 'Saral iMax : Surrender Details'; break;
		case 'transactionsreport': return 'Saral iMax : Transactions Details'; break;
		case 'updationduedetails': return 'Saral iMax : Updation Due Details'; break;
		case 'customerprofileupdate': return 'Saral iMax : Customer Profile Update'; break;
		case 'custpayment': return 'Saral iMax : Customer Payment Request'; break;
		case 'dealerprofileupdate': return 'Saral iMax : Dealer Profile Update'; break;
		case 'districtmapping': return 'Saral iMax : District to Dealer'; break;
		case 'cusattachcard': return 'Saral iMax : Attach PIN Number'; break;
		case 'scheme': return 'Saral iMax : Scheme Screen'; break;
		case 'schemepricing': return 'Saral iMax : Scheme Pricing Screen'; break;
		case 'productmapping': return 'Saral iMax : Product to Dealer'; break;
		case 'schememapping': return 'Saral iMax : Scheme to Dealer'; break;
		case 'resentpwd': return 'Saral iMax : Change Password'; break;
		case 'smsaccount': return 'Saral iMax : SMS Account to Customer'; break;
		case 'smscredits': return 'Saral iMax : SMS Credits'; break;
		case 'smsreceipt': return 'Saral iMax : SMS Receipt Customers'; break;
		case 'smsreceiptdealers': return 'Saral iMax : SMS Receipt Dealers'; break;
		case 'smsaccountdealers': return 'Saral iMax : SMS Account to Dealer'; break;
		case 'smscreditsdealers': return 'Saral iMax : SMS Credits'; break;
		case 'customerquotereq': return 'Saral iMax : Customer :Quote / Payment / Request Emails'; break;
		case 'smscreditssummary': return 'Saral iMax : SMS Summary'; break;
		case 'viewinvoice': return 'Saral iMax : Invoice Details'; break;
		case 'rcidataviewer': return 'Saral iMax : RCI Data Viewer'; break;
		case 'updationsummary': return 'Saral iMax : Updation Due Summary'; break;
		case 'crossproductdetails': return 'Saral iMax : Cross Product Sales'; break;
		case 'crossproduct': return 'Saral iMax : Cross Product Information'; break;
		case 'updationdetailedreport': return 'Saral iMax : Customer Stats (Year Wise)'; break;
		case 'salessummary': return 'Saral iMax : Sales Summary'; break;
		case 'charts': return 'Saral iMax : Graphic Representation'; break;
		case 'deployment': return 'Saral iMax : Implementation Master'; break;
		case 'invoicing': return 'Saral iMax : Invoicing'; break;
		case 'receipts': return 'Saral iMax : Receipts'; break;
		case 'invoiceregister': return 'Saral iMax : Invoice Register'; break;
		case 'receiptregister': return 'Saral iMax : Receipt Register'; break;
		case 'outstandingregister': return 'Saral iMax : Outstanding Register'; break;
		case 'manageinvoice': return 'Saral iMax : Manage Invoices'; break;
		case 'bulkprint': return 'Saral iMax : Bulk Print (Invoices)'; break;
		case 'implementation': return 'Saral iMax : Implementation'; break;
		case 'ssmcallstats': return 'Saral iMax : SSM Call Stats'; break;
		case 'implementationsummary': return 'Report : Implementation Summary'; break;
		case 'customeranalysis': return 'Report : Data Inaccuracy Report'; break;
		case 'implementationdetailed': return 'Report : Implementation Detailed Report'; break;
		case 'receiptreconciliation': return 'Saral iMax : Receipt Reconciliation'; break;
		case 'activitylog': return 'Saral iMax : Activity Log'; break;
		case 'notregistered': return 'Report : Not registered(Ageing) Report'; break;
		case 'categorysummary': return 'Report : Category Summary Report'; break;
		case 'addinvoices': return 'Saral iMax : Add Invoices'; break;
		case 'addbills': return 'Saral iMax : Add Bills'; break;
		case 'invoiceimport': return 'Saral iMax : Invoice Import'; break;
		default: return 'Saral iMax : Dashboard'; break;
	}
}

function getpageheader($linkvalue)
{
	switch($linkvalue)
	{
		case 'producttodealers':return 'Product Mapping to Dealers'; break;
		case 'customer': return 'Customer Master'; break;
		case 'dealer': return 'Dealer Master'; break;
		case 'blockcancel': return 'Block / Cancel PIN Numbers'; break;
		case 'product': return 'Product Master'; break;
		case 'usereditor': return 'User Editor Screen'; break;
		case 'bill': return 'Purchase Screen'; break;
		case 'mergecustomer': return 'Merge Customer'; break;
		case 'mergecustomerlist': return 'Saral iMax : Merge Customer List'; break;
		case 'home_dashboard': return 'Dashboard'; break;
		case 'cardsearch': return 'PIN Number Search'; break;
		case 'externalregistration': return 'External Registration'; break;
		case 'transfercards': return 'Transfer PIN Numbers'; break;
		case 'credits': return 'Credits'; break;
		case 'customeramc': return 'Customer AMC'; break;
		case 'hardwarelock': return 'Hardware Lock'; break;
		case 'unauthorised': return 'Unauthorised Viewer'; break;
		case 'cusinteraction': return 'Customer Interaction'; break;
		case 'changepassword': return 'Change Password'; break;
		case 'registrationdetails': return 'Registration Details'; break;
		case 'invoicedetails': return 'Invoice Details'; break;
		case 'productshippeddetails': return 'Product Shipped Details'; break;
		case 'dealerdetails': return 'Dealer Stock Details'; break;
		case 'contactdetails': return 'Customer Contact Details'; break;
		case 'labelcontactdetails': return 'Saral iMax : Labels for Customer Contact Details'; break;
		case 'cuscardattachreport': return 'Customer Card Attached Details'; break;
		case 'surrenderreport': return 'Surrender Details'; break;
		case 'transactionsreport': return 'Transactions Details'; break;
		case 'updationduedetails': return 'Updation Due Details'; break;
		case 'customerprofileupdate': return 'Customer Profile Update'; break;
		case 'custpayment': return 'Customer Payment Request'; break;
		case 'dealerprofileupdate': return 'Dealer Profile Update'; break;
		case 'districtmapping': return 'District to Dealer'; break;
		case 'cusattachcard': return 'Attach PIN Number'; break;
		case 'scheme': return 'Scheme Master'; break;
		case 'schemepricing': return 'Scheme Pricing'; break;
		case 'productmapping': return 'Product to Dealer'; break;
		case 'schememapping': return 'Scheme to Dealer'; break;
		case 'resentpwd': return 'Change Password'; break;
		case 'smsaccount': return 'SMS Account to Customer'; break;
		case 'smscredits': return 'SMS credits'; break;
		case 'smsreceipt': return 'SMS Receipt'; break;
		case 'smsreceiptdealers': return 'SMS Receipt to Dealers'; break;
		case 'smsaccountdealers': return 'SMS Account to Dealer'; break;
		case 'smscreditsdealers': return 'SMS credits'; break;
		case 'customerquotereq': return 'Quote / Payment / Request Emails'; break;
		case 'smscreditssummary': return 'SMS Summary'; break;
		case 'viewinvoice': return 'Invoice Details'; break;
		case 'updationsummary': return 'Updation Due Summary'; break;
		case 'rcidataviewer': return 'RCI Data Viewer'; break;
		case 'updationdetailedreport': return ' Customer Stats (Year Wise)'; break;
		case 'crossproductdetails': return 'Saral iMax : Cross Product Sales'; break;
		case 'crossproduct': return 'Saral iMax : Cross Product Information'; break;
		case 'salessummary': return 'Sales Summary'; break;
		case 'charts': return 'Graphic Representation'; break;
		case 'deployment': return 'Implementation Master'; break;
		case 'invoicing': return 'Invoicing'; break;
		case 'receipts': return 'Receipts'; break;
		case 'invoiceregister': return 'Invoice Register'; break;
		case 'receiptregister': return 'Receipt Register'; break;
		case 'outstandingregister': return 'Outstanding Register'; break;
		case 'manageinvoice': return 'Manage Invoices'; break;
		case 'bulkprint': return 'Bulk Print (Invoices)'; break;
		case 'implementation': return 'Implementation Process'; break;
		case 'ssmcallstats': return 'SSM Call Stats'; break;
		case 'implementationsummary': return 'Implementation Summary'; break;
		case 'customeranalysis': return 'Data Inaccuracy Report'; break;
		case 'implementationdetailed': return 'Implementation Detailed Report'; break;
		case 'receiptreconciliation': return 'Receipt Reconciliation'; break;
		case 'activitylog': return 'Activity Log'; break;
		case 'notregistered': return 'Not registered(Ageing) Report'; break;
		case 'categorysummary': return 'Category Summary Report'; break;
		case 'addinvoices': return 'Add Invoices'; break;
		case 'addbills': return 'Add Bills'; break;
		case 'invoiceimport': return 'Invoice Import'; break;
		default: return 'Dashboard'; break;
	}
}


function gridtrim($value)
{
	$desiredlength = 30;
	$length = strlen($value);
	if($length >= $desiredlength)
	{
		$value = substr($value, 0, $desiredlength);
		$value .= "...";
	}
	return $value;
}

function gridtrim10($value)
{
	$desiredlength = 10;
	$length = strlen($value);
	if($length >= $desiredlength)
	{
		$value = substr($value, 0, $desiredlength);
		$value .= "...";
	}
	return $value;
}


function gridtrimalert($value)
{
	$desiredlength = 30;
	$stripedvalue = strip_tags($value);
	$length = strlen($stripedvalue);
	if($length >= $desiredlength)
	{
		$value = substr($stripedvalue, 0, $desiredlength);
		$value .= "...";
	}
	return $value;
}

function deleterecordcheck($fieldvalue,$fieldname,$tablename)
{
	$flag = false;
	$query = "SELECT COUNT(*) AS count FROM ".$tablename." WHERE ".$fieldname." = '".$fieldvalue."'";
	$fetch = runmysqlqueryfetch($query);
	if($fetch['count'] == 0)
		$flag = true;
	else
		$flag = false;
	return $flag;
}

/*function recordcheck($fieldvalue,$fieldname,$fieldname2,$tablename)
{
	$flag = false;
	$query = "SELECT COUNT(*) AS count FROM ".$tablename." WHERE ".$fieldname." = '".$fieldvalue."' and ".$fieldname2." = '".$fieldvalue."'";
	$fetch = runmysqlqueryfetch($query);
	if($fetch['count'] == 0)
		$flag = true;
	else

		$flag = false;
	return $flag;
}*/



function compare2date()
{
	$exp_date = "2006-01-16"; $todays_date = date("Y-m-d"); $today = strtotime($todays_date); $expiration_date = strtotime($exp_date); if ($expiration_date > $today) { $valid = "yes"; } else { $valid = "no"; } 
}

/* -------------------- Upload ZIP file through PHP -------------------- */
function fileupload($filename,$filetempname)
{
//check that we have a file
  //Check if the file is JPEG image and it's size is less than 350Kb
  
  //retrieve the date.
  $date = datetimelocal('YmdHis-');
  $filebasename = $date.basename($filename);
  $ext = substr($filebasename, strrpos($filebasename, '.') + 1);
  if ($ext == "zip") 
  {
      $newname = $_SERVER['DOCUMENT_ROOT'].'/sssm/upload/'.$filebasename;
	  $downloadlink = 'http://'.$_SERVER['HTTP_HOST'].'/sssm/upload/'.$filebasename;
      if (!file_exists($newname)) 
	  {
        if ((move_uploaded_file($filetempname,$newname))) 
		{
           $result = "1^".$downloadlink; //Upload successfull
        } 
		else 
		{
           $result ="^". 4; //Problem dusring upload
        }
      } 
	  else 
	  {
         $result ="^". 3; //File already exists by same name
      }
  } 
  else 
  {
     $result = "^". 2; //Extension doesn't match
  }
  return $result;
}

/* ---------------------------- Upload Any through PHP  -------------------------------------- */
function uploadfile()
{
	$destination_path = getcwd().DIRECTORY_SEPARATOR;
	$result = 0;
	$target_path = $destination_path . basename( $_FILES['myfile']['name']);
	if(@move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path)) 
	{
		$result = 1;
	}
	sleep(1);
}


/* -------------------- Download any file through PHP header -------------------- */
function downloadfile($filelink)
{
	$filename = basename($filelink);
	header('Content-type: application/octet-stream');
	header("Content-Disposition:attachment; filename=".$filename);
	readfile($filelink);
}

function checkemailaddress($email) 
{
	// First, we check that there's one @ symbol, and that the lengths are right
	if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) 
	{
		// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
		return false;
	}
	// Split it into sections to make life easier
	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);
	for ($i = 0; $i < sizeof($local_array); $i++) 
	{
		if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) 
		{
			return false;
		}
	}
	if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) 
	{ 
		// Check if domain is IP. If not, it should be valid domain name
		$domain_array = explode(".", $email_array[1]);
		if (sizeof($domain_array) < 2) 
		{
			return false; // Not enough parts to domain
		}
		for ($i = 0; $i < sizeof($domain_array); $i++) 
		{
			if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) 
			{
				return false;
			}
		}
	}
	return true;
}

/*function replacemailvariable($content,$array)
{
	while ($item = current($array)) 
	{
		if($item == "" || $item == "0")
		$item = "-";
		$content = str_replace(key($array),$item,$content);
		next($array);
	}
	return $content;
}*/

function replacemailvariable($content,$array)
{
	$arraylength = count($array);
	for($i = 0; $i < $arraylength; $i++)
	{
		$splitvalue = explode('%^%',$array[$i]);
		$oldvalue = $splitvalue[0];
		$newvalue = $splitvalue[1];
		$content = str_replace($oldvalue,$newvalue,$content);
	}
	return $content;
}

function validateFormat($computerid)
{
	if(preg_match("/^\d{5}-\d{9}$/", $computerid))
	return true;
}

function modulegropname($shortname)
{
	switch($shortname)
	{
		case "user_module":
			return "User Module";
			break;
		case "dealer_module":
			return "Dealer Module";
			break;
	}
}

function sendwelcomeemail($customerslno,$userid)
{
	$query = "select 
	inv_mas_customer.customerid AS customerid,
	inv_mas_customer.businessname AS businessname,
	inv_mas_customer.place AS place,
	inv_mas_customer.address AS address,
	inv_mas_customer.pincode AS pincode,
	inv_mas_customer.stdcode AS stdcode,
	inv_mas_customer.initialpassword AS password,
	inv_mas_customertype.customertype AS type,
	inv_mas_customercategory.businesstype AS category,
	inv_mas_district.districtname AS districtname,
	inv_mas_state.statename AS statename
	from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode left join inv_mas_customercategory on inv_mas_customer.category = inv_mas_customercategory.slno left join inv_mas_customertype on inv_mas_customer.type = inv_mas_customertype.slno
	 where inv_mas_customer.slno = '".$customerslno."'";
	$result = runmysqlqueryfetch($query);
	
	// Fetch all contact Details
	
	$querycontactdetails = "select  phone,cell,emailid,contactperson from inv_contactdetails where customerid = '".$customerslno."'";
	$resultcontactdetails = runmysqlquery($querycontactdetails);
	// contact Details
	$contactvalues = '';
	$phoneres = '';
	$cellres = '';
	$emailidres = '';
			
	while($fetchcontactdetails = mysql_fetch_array($resultcontactdetails))
	{
		$contactperson = $fetchcontactdetails['contactperson'];
		$phone = $fetchcontactdetails['phone'];
		$cell = $fetchcontactdetails['cell'];
		$emailid = $fetchcontactdetails['emailid'];
		
		$contactvalues .= $contactperson;
		$contactvalues .= appendcomma($contactperson);
		$phoneres .= $phone;
		$phoneres .= appendcomma($phone);
		$cellres .= $cell;
		$cellres .= appendcomma($cell);
		$emailidres .= $emailid;
		$emailidres .= appendcomma($emailid);
	}
	$customerid = $result['customerid'];
	$businessname = $result['businessname'];
	$contactperson = trim($contactvalues,',');
	$place = $result['place'];
	$address = $result['address'];
	$pincode = $result['pincode'];
	$stdcode = $result['stdcode'];
	$phone = trim($phoneres,',');
	$cell =trim($cellres,',');
	$password = $result['password'];
	$type = $result['type'];
	$category = $result['category'];
	$districtname = $result['districtname'];
	$statename = $result['statename'];
	$emailid = trim($emailidres,',');
	
	//echo($emailid);exit;
	//Dummy line to override To email ID
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
		$emailid = 'archana.ab@relyonsoft.com';
	else
		$emailid = $emailid;
	

	
	//Split multiple email IDs by Comma
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);
	
	//Convert email IDs to an array. First email ID will eb assigned with Contact person name. Others will be assigned with email IDs itself as Name.
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
	$msg = file_get_contents("../mailcontents/newcustomer.htm");
	$textmsg = file_get_contents("../mailcontents/newcustomer.txt");
	$pincode = ($pincode == '')?'Not Available':$pincode;
	$stdcode = ($stdcode == '')?'Not Available':$stdcode;
	$address = ($address == '')?'Not Available':$address;
	$type = ($type == '')?'Not Available':$type;
	$category = ($category == '')?'Not Available':$category;
	
	//Create an array of replace parameters
	$array = array();
	$date = datetimelocal('d-m-Y');
	$array[] = "##DATE##%^%".$date;
	$array[] = "##NAME##%^%".$contactperson;
	$array[] = "##COMPANY##%^%".$businessname;
	$array[] = "##PLACE##%^%".$place;
	$array[] = "##ADDRESS##%^%".$address;
	$array[] = "##DISTRICT##%^%".$districtname;
	$array[] = "##STATE##%^%".$statename;
	$array[] = "##CUSID##%^%".cusidcombine($customerid);
	$array[] = "##PINCODE##%^%".$pincode;
	$array[] = "##STDCODE##%^%".$stdcode;
	$array[] = "##TYPE##%^%".$type;
	$array[] = "##PHONE##%^%".$phone;
	$array[] = "##CELL##%^%".$cell;
	$array[] = "##PASSWORD##%^%".$password;
	$array[] = "##EMAIL##%^%".$emailid;
	$array[] = "##CATEGORY##%^%".$category;
	$array[] = "##EMAILID##%^%".$emailid;
	
	//Relyon Logo for email Content, as Inline [Not attachment]
	$filearray = array(
		array('../images/icon_registration_lg.gif','inline','9999999999'),
		array('../images/customer_icon.gif','inline','7777777777'),
		array('../images/relyon-logo.jpg','inline','8888888888'),
		array('../images/re-registration-icon.gif','inline','66666666666'),
		array('../images/contact-info.gif','inline','33333333333'),
		array('../images/customer-service.gif','inline','22222222222')
	
	);
	$toarray = $emailids;
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
	{
		//$bccemailids['rashmi'] ='rashmi.hk@relyonsoft.com';
		$bccemailids['archanaab'] ='archana.ab@relyonsoft.com';
	}
	else
	{
		$bccemailids['support'] ='support@relyonsoft.com';
		$bccemailids['info'] ='info@relyonsoft.com';
		$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
		$bccemailids['relyonimax'] ='relyonimax@gmail.com';
	}
	
	$bccarray = $bccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = 'New customer details registered with Customer ID "'.cusidcombine($customerid).'"';
	$html = $msg;
	$text = $textmsg;
	$replyto = 'support@relyonsoft.com';
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray,$replyto);
	//Insert the mail forwarded details to the logs table
	$bccmailid = 'support@relyonsoft.com,info@relyonsoft.com,bigmail@relyonsoft.com';
	inserttologs($userid,$customerslno,$fromname,$fromemail,$emailid,null,$bccmailid,$subject);
	
}

 

function sendregistrationemail($customerproductslno,$userid)
{
	$query = "Select 
	inv_mas_customer.businessname as businessname,
	inv_mas_customer.place as place,
	inv_mas_customer.customerid as customerid,inv_mas_customer.slno as slno,
	inv_customerproduct.computerid as computerid,
	inv_customerproduct.softkey as softkey,inv_customerproduct.dealerid as dealerid,
	inv_mas_scratchcard.scratchnumber as pinno,
	inv_mas_product.productname as productname from inv_customerproduct Left join inv_mas_customer on inv_mas_customer.slno = inv_customerproduct.customerreference
	left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_customerproduct.cardid
	left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
	where inv_customerproduct.slno = '".$customerproductslno."'";
	$result = runmysqlqueryfetch($query);
	
	// fetch Contact Details
	$querycontactdetails = "select  emailid,contactperson from inv_contactdetails where customerid = '".$result['slno']."'";
	$resultcontactdetails = runmysqlquery($querycontactdetails);
	// contact Details
	$contactvalues = '';
	$phoneres = '';
	$cellres = '';
	$emailidres = '';
			
	while($fetchcontactdetails = mysql_fetch_array($resultcontactdetails))
	{
		$contactperson = $fetchcontactdetails['contactperson'];
		$emailid = $fetchcontactdetails['emailid'];
		
		$contactvalues .= $contactperson;
		$contactvalues .= appendcomma($contactperson);
		$emailidres .= $emailid;
		$emailidres .= appendcomma($emailid);
	}
	$contactperson = trim($contactvalues,',');
	$businessname = $result['businessname'];
	$place = $result['place'];
	$customerid = $result['customerid'];
	$customerslno = $result['slno'];
	$productname = $result['productname'];
	$pinno = $result['pinno'];
	$computerid = $result['computerid'];
	$softkey = $result['softkey'];
	$dealerid = $result['dealerid'];
	
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
		$emailid = 'archana.ab@relyonsoft.com';
	else
		$emailid = trim($emailidres,',');

	$query = "Select emailid from inv_mas_dealer where slno = '".$dealerid."'";
	$fetch = runmysqlqueryfetch($query);
	$bcceallemailid = $fetch['emailid'];
	
	 //BCC to dealer
	$bccemailarray = explode(',',$bcceallemailid);
	$bccemailcount = count($bccemailarray);
		//Dummy line to override To email ID
	//$emailid = 'meghana.b@relyonsoft.com';
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);
	
	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
				$emailids[$emailarray[$i]] = $emailarray[$i];
		}
	}
	
	for($i = 0; $i < $bccemailcount; $i++)
	{
		if(checkemailaddress($bccemailarray[$i]))
		{
				$bccemailids[$bccemailarray[$i]] = $bccemailarray[$i];
				if($i == 0 && $bccemailarray[$i] <> '')
					$bccids = $bccemailarray[$i];
				else if($bccemailarray[$i] <> '')
					$bccids .= ','.$bccemailarray[$i];
		}
	}
	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/customerregistration.htm");
	$textmsg = file_get_contents("../mailcontents/customerregistration.txt");
	$date = datetimelocal('d-m-Y');
	$array = array();
	$array[] = "##DATE##%^%".$date;
	$array[] = "##NAME##%^%".$contactperson;
	$array[] = "##COMPANY##%^%".$businessname;
	$array[] = "##PLACE##%^%".$place;
	$array[] = "##CUSTOMERID##%^%".cusidcombine($customerid);
	$array[] = "##PRODUCTNAME##%^%".$productname;
	$array[] = "##SCRATCHCARDNO##%^%".$pinno;
	$array[] = "##COMPUTERID##%^%".$computerid;
	$array[] = "##SOFTKEY##%^%".$softkey;
	$array[] = "##EMAILID##%^%".$emailid;
	
	$filearray = array(
		array('../images/registration-icon.gif','inline','1234567890'),
		array('../images/relyon-logo.jpg','inline','8888888888'),
	
	);
	$toarray = $emailids;
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
	{
		$bccemailids['rashmi'] ='meghana.b@relyonsoft.com';	}
	else
	{
		$bccemailids['relyonimax'] ='relyonimax@gmail.com';
		$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
	}

	$bccarray = $bccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = "Registration availed for ".$productname;
	$html = $msg;
	$text = $textmsg;
	$replyto = 'support@relyonsoft.com';
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray,$replyto);
	//Insert the mail forwarded details to the logs table
	$bccmailid = $bccids.','.'bigmail@relyonsoft.com'; 
	inserttologs($userid,$customerslno,$fromname,$fromemail,$emailid,null,$bccmailid ,$subject);		
	
}


// function to delete cookie and encoded the cookie name and value
function imaxdeletecookie($cookiename)
{
	 //Name Suffix for MD5 value
	 $stringsuff = "55";

	//Convert Cookie Name to base64
	$Encodename = encodevalue($cookiename);
	 //Append the encoded cookie name with 55(suffix ) for MD5 value
	 $rescookiename = $Encodename.$stringsuff;
	 
	//Set expiration to negative time, which will delete the cookie
	setcookie($Encodename ,"",time()-3600);
	setcookie($rescookiename, "",time()-3600);
	setcookie(session_name(), "",time()-3600);
}


// function to create cookie and encoded the cookie name and value
function imaxcreatecookie($cookiename,$cookievalue)
{

	 //Define prefix and suffix 
	 $prefixstring="AxtIv23";
	 $suffixstring="StPxZ46";
	 $stringsuff = "55";
	 
	 //Append Value with the Prefix and Suffix
	 $Appendvalue = $prefixstring . $cookievalue . $suffixstring;
	 
	 // Convert the Appended Value to base64
	 $Encodevalue = encodevalue( $Appendvalue);
	 
	 //Convert Cookie Name to base64
	 $Encodename = encodevalue($cookiename);

	 //Create a cookie with the encoded name and value
	 setcookie($Encodename,$Encodevalue, time()+2592000);
	
 	 //Convert Appended encode value to MD5
	 $rescookievalue = md5($Encodevalue);

	 //Appended the encoded cookie name with 55(suffix )
	 $rescookiename = $Encodename.$stringsuff;

	 //Create a cookie
	 setcookie($rescookiename,$rescookievalue, time()+2592000);
		 return false;

}

//Function to get cookie and encode it and validate
function imaxgetcookie($cookiename)
{

	$suff = "55";
	// Convert the Cookie Name to base64
	$Encodestr = encodevalue($cookiename);

	//Read cookie name
	$stringret = $_COOKIE[$Encodestr];
	$stringret = stripslashes($stringret);

	//Convert the read cookie name to md5 encode technique
	$Encodestring = md5($stringret);
	
	//Appended the encoded cookie name to 55(suffix)
	$resultstr = $Encodestr.$suff;
	$cookiemd5 = $_COOKIE[$resultstr];
	
	//Compare the encoded value wit the fetched cookie, if the condition is true decode the cookie value
	if($Encodestring == $cookiemd5)
	{
		$decodevalue = decodevalue($stringret);
		//Remove the Prefix/Suffix Characters
		$string1 = substr($decodevalue,7);
		$resultstring = substr($string1,0,-7);
		return $resultstring;
	}

	elseif(isset($Encodestring) == '')
	{
		return false;
	}
	else 
	{
		return false;
	}
	
}

//Function to logout (clear cookies)
function imaxuserlogout()
{
	session_start(); 
	session_unset();
	session_destroy(); 
	imaxdeletecookie('userid');
	imaxdeletecookie('checkpermission');
	imaxdeletecookie('sessionkind');
	imaxdeletecookie('verificationid');
}

function imaxuserlogoutredirect()
{
	imaxuserlogout();
	//$url = "../index.php";
	$url = "../index.php?link=".fullurl();
	header("Location:".$url);
	exit;	
}

function fullurl()
{
	$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
	$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
	return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}

function isvalidhostname()
{
	if($_SERVER['HTTP_HOST'] == 'rashmihk' || $_SERVER['HTTP_HOST'] == 'meghanab' || $_SERVER['HTTP_HOST'] == 'vijaykumar' || $_SERVER['HTTP_HOST'] == 'imax.relyonsoft.com' || $_SERVER['HTTP_HOST'] == 'rwmserver')
		return true;
	else
		return false;
}

function isurl($url)
{
	return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}

function sendpaymentreqemail($customerslno,$table,$userid)
{
	$query = "SELECT inv_mas_customer.businessname as businessname,inv_mas_customer.customerid as customerid,inv_custpaymentreq.paymentstatus as paymentstatus,inv_mas_customer.slno as slno  FROM inv_custpaymentreq LEFT JOIN inv_mas_customer on inv_mas_customer.slno = inv_custpaymentreq.custreferences  WHERE inv_custpaymentreq.slno = '".$customerslno."' and inv_custpaymentreq.paymentstatus = 'UNPAID' ";
	$result = runmysqlqueryfetch($query);
	
	// Fetch Contact Details 
	
	$querycontactdetails = "select  emailid from inv_contactdetails where customerid = '".$result['slno']."'";
	$resultcontactdetails = runmysqlquery($querycontactdetails);
			// contact Details
	$contactvalues = '';
	$phoneres = '';
	$cellres = '';
	$emailidres = '';
			
	while($fetchcontactdetails = mysql_fetch_array($resultcontactdetails))
	{
		$emailid = $fetchcontactdetails['emailid'];
		$emailidres .= $emailid;
		$emailidres .= appendcomma($emailid);
	}
	$customerid = $result['customerid'];
	$businessname = $result['businessname'];
	$slno = $result['slno'];
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
		$emailid = 'archana.ab@relyonsoft.com';
	else
		$emailid = trim($emailidres,',');
	
	//Split multiple email IDs by Comma
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
	$msg = file_get_contents("../mailcontents/paymentreq.htm");
	$textmsg = file_get_contents("../mailcontents/paymentreq.txt");
	
	//Create an array of replace parameters
	$array = array();
	$date = datetimelocal('d-m-Y');
	$array[] = "##DATE##%^%".$date;
	$array[] = "##COMPANY##%^%".$businessname;
	$array[] = "##TABLE##%^%".$table;
	$array[] = "##EMAILID##%^%".$emailid;
	//Relyon Logo for email Content, as Inline [Not attachment]
	$filearray = array(
		array('../images/relyon-logo.jpg','inline','8888888888'),
	);
	
	$toarray = $emailids;
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
	{
		$bccemailids['archanaab'] ='meghana.b@relyonsoft.com';
	}
	else
	{
		$bccemailids['relyonimax'] ='relyonimax@gmail.com';
		$bccemailids['accounts'] ='accounts@relyonsoft.com';
		$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
	}
	
	$bccarray = $bccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = "Online Payment request";
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray);
	//Insert the mail forwarded details to the logs table
	$bccmailid = 'accounts@relyonsoft.com,bigmail@relyonsoft.com'; 
	inserttologs($userid,$slno,$fromname,$fromemail,$emailid,null,$bccmailid,$subject);
	
}

function calculatesmsamount($quantity)
{
	$query = 'select * from inv_sms_price;';
	$result = runmysqlquery($query);
	while($fetch = mysql_fetch_array($result))
	{
		if($quantity >= $fetch['smsfromquantity']  && $quantity <= $fetch['smstoquantity'])
		{
			$price = ($fetch['price']/100);
			break;
		}
	}
	$amount = ($quantity * $price);
	return round($amount);
}





//Function to generate Online Bill In PDF format
function generatepdfbill($firstbillnumber,$custreference,$onlineinvoiceno,$invoicenoformat,$username)
{
	require_once('../pdfbillgeneration/tcpdf.php');
	
	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	
	// remove default header/footer
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	
	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	//set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	
	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
	
	//set some language-dependent strings
	$pdf->setLanguageArray($l); 
	
	
	//set font
	$pdf->SetFont('Helvetica', '',10);
	
	//add a page
	$pdf->AddPage();
	
	$query1 = "select * from inv_customerreqpending where customerid = '".$custreference."' and inv_customerreqpending.customerstatus = 'Pending' and requestfrom = 'dealer_module';";
	$result1 = runmysqlquery($query1);
	
	if(mysql_num_rows($result1) > 0)
	{
		$query = "select inv_customerreqpending.businessname as companyname,inv_customerreqpending.contactperson,inv_customerreqpending.phone,inv_customerreqpending.cell,inv_customerreqpending.emailid,inv_customerreqpending.place,inv_customerreqpending.address,inv_mas_region.category as region,inv_mas_branch.branchname as branchname,inv_mas_customercategory.businesstype,inv_mas_customertype.customertype,inv_mas_dealer.businessname as dealername,
inv_customerreqpending.stdcode, inv_customerreqpending.pincode,inv_mas_district.districtname, inv_mas_state.statename,inv_mas_customer.customerid  from inv_mas_customer left join inv_customerreqpending on inv_customerreqpending.customerid = inv_mas_customer.slno left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_customer.slno = '".$custreference."' and inv_customerreqpending.customerstatus = 'Pending';";
	}
	else
	{
		$query = "select inv_mas_customer.businessname as companyname,inv_mas_customer.place,inv_mas_customer.address,inv_mas_region.category as region,inv_mas_branch.branchname as branchname,inv_mas_customercategory.businesstype,inv_mas_customertype.customertype,inv_mas_dealer.businessname as dealername,inv_mas_customer.stdcode, inv_mas_customer.pincode,inv_mas_district.districtname, inv_mas_state.statename,inv_mas_customer.customerid  from inv_mas_customer left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_customer.slno = '".$custreference."';";
	}
	$fetchresult = runmysqlqueryfetch($query);
	
	// Fetch Contact Details 
	$querycontactdetails = "select customerid, GROUP_CONCAT(contactperson) as contactperson,  
GROUP_CONCAT(phone) as phone, GROUP_CONCAT(cell) as cell, GROUP_CONCAT(emailid) as emailid from inv_contactdetails where customerid = '".$custreference."'  group by customerid ";
	$resultcontact = runmysqlquery($querycontactdetails);
	$resultcontactdetails = mysql_fetch_array($resultcontact);
	//$resultcontactdetails = runmysqlqueryfetch($querycontactdetails);
	
	$contactvalues = removedoublecomma($resultcontactdetails['contactperson']);
	$phoneres = removedoublecomma($resultcontactdetails['phone']);
	$cellres = removedoublecomma($resultcontactdetails['cell']);
	$emailidres = removedoublecomma($resultcontactdetails['emailid']);
	
	
	$query1 = "SELECT inv_mas_product.productcode as productcode , inv_mas_product.productname as productname, inv_dealercard.usagetype as usagetype, inv_dealercard.purchasetype as purchasetype, inv_mas_scratchcard.cardid as cardno, inv_mas_scratchcard.scratchnumber as pinno,inv_dealercard.addlicence FROM inv_dealercard LEFT JOIN inv_mas_scratchcard ON inv_mas_scratchcard.cardid = inv_dealercard.cardid LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_dealercard.productcode  WHERE inv_dealercard.cusbillnumber = '".$firstbillnumber."';";
	$result = runmysqlquery($query1);
		
	$query2 = "SELECT inv_billdetail.productamount,productquantity,`year` as financialyear from inv_billdetail
left join inv_mas_product on inv_mas_product.productcode = inv_billdetail.productcode
 where inv_billdetail.cusbillnumber = '".$firstbillnumber."';";
	$result2 = runmysqlquery($query2);
	
	while($fetch2 = mysql_fetch_array($result2))
	{
		for($i=0;$i<$fetch2['productquantity'];$i++)
		{
			$amount[] = round($fetch2['productamount']/$fetch2['productquantity']);
			$financialyear[] = $fetch2['financialyear'];
		}
	}
	$query3 = "Select * from inv_bill where inv_bill.slno = '".$firstbillnumber."'";
	$result3 = runmysqlqueryfetch($query3);
	$query2 = "select paymentremarks as remarks,offerremarks,pricingtype,offertype,offerdescription,offeramount,inv_mas_dealer.businessname,invoiceremarks,service,serviceamount from  dealer_online_purchase left join inv_mas_dealer on inv_mas_dealer.slno = dealer_online_purchase.currentdealer where onlineinvoiceno = '".$onlineinvoiceno."';";
	$result2 = runmysqlqueryfetch($query2);
	$createddealername = $result2['businessname'];
	$service = $result2['service'];
	$serviceamount = $result2['serviceamount'];
	$offerremarks = $result2['offerremarks'];
	$remarks = ($result2['remarks'] == '')?'None':$result2['remarks'];
	$invoiceremarks = ($result2['invoiceremarks'] == '')?'None':$result2['invoiceremarks'];
	$offertype = $result2['offertype'];
	$offerdescription = $result2['offerdescription'];
	$offeramount = $result2['offeramount'];
	$offertypesplit = explode('~',$offertype);
	$offerdescriptionsplit = explode('~',$offerdescription);
	$offeramountsplit = explode('~',$offeramount);
	$serviceamountsplit = explode('~',$serviceamount);
	$servicesplit = explode('#',$service);
	if($service <> '')
		$servicesplitcount = count($servicesplit);
	else
		$servicesplitcount = '0';
	if($offerremarks <> '')
		$offerremarkscount = '1';
	else
		$offerremarkscount = '0';
	if($offertype == '')
		$offertypesplitcount = 0;
	else
		$offertypesplitcount = count($offertypesplit);
	$resultcount = mysql_num_rows($result);
	$linecount =  $resultcount + $offertypesplitcount + $offerremarkscount + $servicesplitcount;
	$addline1 = '';
	$addline = '';
	$offerremarksrow ='';
	$appendzero = '.00';

	/*if($offertype == '' && $linecount < 8 && $service == '')
	{
		if($offerremarks <> '')
			$productrow .= '<tr><td width="10%"></td><td width="76%" style="text-align:left;">'.$offerremarks.'</td><td width="14%">&nbsp;</td></tr>';
		$addline = $productrow.addlinebreak($linecount);
	}
	else if($offertype <> '' && $linecount < 8 && $service <> '')
	{
		if($offerremarks <> '' )
			$productrow .= '<tr><td width="10%"></td><td width="76%" style="text-align:left;">'.$offerremarks.'</td><td width="14%">&nbsp;</td></tr>';
		$addline1 = $productrow.addlinebreak($linecount);
	}*/
	$grid .='<table width="100%" border="0" align="center" cellpadding="4" cellspacing="0" >';
	$grid .='<tr><td ><table width="100%" border="0" cellspacing="0" cellpadding="4" bordercolor="#CCCCCC" style="border:1px solid "><tr bgcolor="#CCCCCC"><td width="10%"><div align="center"><strong>Sl No</strong></div></td><td width="76%"><div align="center"><strong>Description</strong></div></td><td width="14%"><div align="center"><strong>Amount</strong></div></td></tr>';
	$k = 0;
	$descriptioncount = 0;
	$servicetaxdesc = 'Service Tax Category: Information Technology Software (zzze), Support(zzzq), Training (zzc), Manpower(k), Salary Processing (22g), SMS Service (b)';
	while($fetch = mysql_fetch_array($result))
	{
		$slno++;
		$grid .= '<tr>';
		$grid .= '<td width="10%" style="text-align:centre;">'.$slno.'</td>';
		if($fetch['purchasetype'] == 'new')
			$purchasetype = 'New';
		else
			$purchasetype = 'Updation';
		if($fetch['addlicence'] == 'yes')
		{
			$usagetype = 'Additional License';
		}
		else
		{
			if($fetch['usagetype'] == 'singleuser')
				$usagetype = 'Single User';
			else
				$usagetype = 'Multi User';
		}
		$grid .= '<td width="76%" style="text-align:left;">'.$fetch['productname'].' - ('.$financialyear[$k].')<br/><span style="font-size:+7" ><strong>Purchase Type</strong> : '.$purchasetype.'&nbsp;/&nbsp;<strong>Usage Type</strong> :'.$usagetype.'&nbsp;&nbsp;/ &nbsp;<strong>PIN Number :<font color="#FF3300"> '.$fetch['pinno'].'</font></strong> (<strong>Serial</strong> : '.$fetch['cardno'].')</span></td>';
		$grid .= '<td  width="14%" style="text-align:right;" >'.$amount[$k].$appendzero.'</td>';
		$grid .="</tr>";
		if($slno == $resultcount)
			$grid .= $addline;
		if($descriptioncount > 0)
			$description .= '*';
		$description .= $slno.'$'.$fetch['productname'].' - ('.$financialyear[$k].')'.'$'.$purchasetype.'$'.$usagetype.'$'.$fetch['pinno'].'$'.$fetch['cardno'].'$'.$amount[$k];
		$k++;
		$descriptioncount++;
	  }
	  if($service <> '')
	  {
			$servicecount = 0;
			for($i=0; $i<$servicesplitcount; $i++)
			{
				$slno++;
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">'.$slno.'</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.$servicesplit[$i].'</td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.$serviceamountsplit[$i].$appendzero.'</td>';
				$grid .= "</tr>";
			//	if($i == ($servicesplitcount-1))
					//$grid .= $addline1;
				if($servicecount > 0)
					$servicegrid .= '*';
				$servicegrid .= $slno.'$'.$servicesplit[$i].'$'.$serviceamountsplit[$i];
				$k++;
				$servicecount++;
			}
	  }
	  if($offertype <> '')
	  {
			$offerdescriptioncount = 0;
			for($i=0; $i<$offertypesplitcount; $i++)
			{
				$slno++;
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">&nbsp;</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.strtoupper($offertypesplit[$i]).': '.$offerdescriptionsplit[$i].'</td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.$offeramountsplit[$i].$appendzero.'</td>';
				$grid .= "</tr>";
				if($i == ($offertypesplitcount-1))
					$grid .= $addline1;
				if($offerdescriptioncount > 0)
					$offerdescriptiongrid .= '*';
				$offerdescriptiongrid .= $offerdescriptionsplit[$i].'$'.$offertypesplit[$i].'$'.$offeramountsplit[$i];
				$k++;
				$offerdescriptioncount++;
			}
	  }
	  if($offerremarks <> '' )
	  {
			$grid .= '<tr><td width="10%"></td><td width="76%" style="text-align:left;">'.$offerremarks.'</td><td width="14%">&nbsp;</td></tr>';
	  }
	  if($linecount < 8)
	  {
	  	$grid .= addlinebreak($linecount);
	  }
	  
			
	 $amountinwords = convert_number($result3['netamount']);
	 $grid .= '<tr><td colspan="2" style="text-align:right" width="86%"><strong>Total</strong></td><td  width="14%" style="text-align:right" valign="top">'.$result3['total'].$appendzero.'</td></tr><tr><td  width="56%" style="text-align:left"><span style="font-size:+6" > '.$servicetaxdesc.'</span></td>  <td  width="30%" style="text-align:right"><strong>Service Tax @ 10.3%</strong></td> <td  width="14%" style="text-align:right">'.$result3['taxamount'].$appendzero.'</td></tr><tr>  <td  width="56%" style="text-align:right"><div align="left"><span style="font-size:+6" >E.&amp;O.E.</span></div></td>  <td  width="30%" style="text-align:right"><strong>Net Amount</strong></td><td  width="14%" style="text-align:right"><img src="../images/relyon-rupee-small.jpg" width="8" height="8" border="0" align="absmiddle"  />&nbsp;&nbsp;'.$result3['netamount'].$appendzero.'</td> </tr><tr><td colspan="3" style="text-align:left"><strong>Rupee In Words</strong>: '.$amountinwords.' only</td></tr>';
	$grid .='</table></td></tr></table>';
		
	$emailid = explode(',', trim($emailidres,','));
	$emailidplit = $emailid[0];
	$phonenumber = explode(',', trim($phoneres,','));
	$phonenumbersplit = $phonenumber[0];
	$cellnumber = explode(',', trim($cellres,','));
	$cellnumbersplit = $cellnumber[0];
	$contactperson = explode(',', trim($contactvalues,','));
	$contactpersonplit = $contactperson[0];
	$stdcode = ($fetchresult['stdcode'] == '')?'':$fetchresult['stdcode'].' - ';
	$address = $fetchresult['address'].', '.$fetchresult['place'].', '.$fetchresult['districtname'].', '.$fetchresult['statename'].', Pin: '.$fetchresult['pincode'];
	$customertype = ($fetchresult['customertype'] == '')?'Not Available':$fetchresult['customertype'];
	$businesstype = ($fetchresult['businesstype'] == '')?'Not Available':$fetchresult['businesstype'];
	$invoiceheading = ($fetchresult['statename'] == 'Karnataka')?'Tax Invoice':'Bill Of Sale';

	$invoicequery = "update inv_invoicenumbers set description = '".$description."', amount = '".$result3['total']."',servicetax = '".$result3['taxamount']."', netamount = '".$result3['netamount']."', customerid = '".cusidcombine($fetchresult['customerid'])."',phone =  '".$phonenumbersplit."',cell = '".$cellnumbersplit."',emailid = '".$emailidplit."',contactperson = '".$contactpersonplit."',stdcode = '".$stdcode."',customertype = '".$customertype."',customercategory = '".$businesstype."',branch ='".$fetchresult['branchname']."',pincode = '".$fetchresult['pincode']."',address ='".addslashes($address)."', amountinwords = '".$amountinwords."', remarks = '".$remarks."', servicetaxdesc = '".$servicetaxdesc."', offerdescription = '".$offerdescriptiongrid."', offerremarks = '".$offerremarks."', invoiceremarks = '".$invoiceremarks."', servicedescription = '".$servicegrid."', invoiceheading = '".$invoiceheading."' where slno  ='".$onlineinvoiceno."';";
	$invoiceresult = runmysqlquery($invoicequery);
	$msg = file_get_contents("../pdfbillgeneration/bill-format-new.php");
	$array = array();
	$array[] = "##BILLDATE##%^%".date('d/m/Y');
	$array[] = "##BILLNO##%^%".$invoicenoformat;
	$array[] = "##BUSINESSNAME##%^%".$fetchresult['companyname'];
	$array[] = "##CONTACTPERSON##%^%".$contactpersonplit;
	$array[] = "##PHONE##%^%".$phonenumbersplit;
	$array[] = "##CELL##%^%".$cellnumbersplit;
	$array[] = "##EMAILID##%^%".$emailidplit;
	$array[] = "##RELYONREP##%^%".$createddealername;
	$array[] = "##ADDRESS##%^%".$address;
	$array[] = "##STDCODE##%^%".$stdcode;
	$array[] = "##CUSTOMERID##%^%".cusidcombine($fetchresult['customerid']);
	$array[] = "##BRANCH##%^%".$fetchresult['branchname'];
	$array[] = "##REGION##%^%".$fetchresult['region'];
	$array[] = "##EMAILID##%^%".$fetchresult['emailid'];
	$array[] = "##CUSTOMERTYPE##%^%".$customertype;
	$array[] = "##CUSTOMERCATEGORY##%^%".$businesstype;
	$array[] = "##PAYREMARKS##%^%".$remarks;
	$array[] = "##INVREMARKS##%^%".$invoiceremarks;
	$array[] = "##TABLE##%^%".$grid;
	$array[] = "##GENERATEDBY##%^%".$username;
	$array[] = "##INVOICEHEADING##%^%".$invoiceheading;
	$html = replacemailvariable($msg,$array);
	$pdf->WriteHTML($html,true,0,true);
		
	$localtime = date('His');
	$filebasename = str_replace('/','-',$invoicenoformat).".pdf";
	$addstring ="/user";
	if($_SERVER['HTTP_HOST'] == "nagamani" || $_SERVER['HTTP_HOST'] == "bhavesh" || $_SERVER['HTTP_HOST'] == "archanaab"  || $_SERVER['HTTP_HOST'] == "192.168.2.50")
		$addstring = "/rwm/saralimax-user";
		$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filebasename;
	
	$pdf->Output($filepath ,'F');
	return $filebasename;
	//$pdf->Output('example.pdf' ,'I');	
}



//Function to delete the file 
function fileDelete($filepath,$filename) 
{
	$success = FALSE;
	if (file_exists($filepath.$filename)&&$filename!=""&&$filename!="n/a") {
		unlink ($filepath.$filename);
		$success = TRUE;
	}
	return $success;	
}

function gettotalsmscredits($smsuserid)
{
	$query = "SELECT sum(quantity) as credits from inv_smscredits where inv_smscredits.smsuserid = '".$smsuserid."';";
	$fetch = runmysqlqueryfetch($query);
	$credits = $fetch['credits'];
	$query = "SELECT utilizedcredits FROM inv_smsactivation WHERE slno = '".$smsuserid."';";
	$fetch = runmysqlqueryfetch($query);
	$utilized = $fetch['utilizedcredits'];
	$balance = $credits - $utilized;
	return $balance;
}

function smsactivationmail($slnoinserted,$userid)
{
	$query = "select * from inv_smsactivation where slno = '".$slnoinserted."';";
	$resultfetch = runmysqlqueryfetch($query);
	$smsusername = $resultfetch['smsusername'];
	$smspassword = $resultfetch['smspassword'];
	$smsfromname = $resultfetch['smsfromname'];
	$responsibleperson = $resultfetch['contactperson'];
	$mobileno = $resultfetch['cell'];
	$emailid = $resultfetch['emailid'];
	$userreference = $resultfetch['userreference'];
	
	$query2 = "select * from inv_mas_customer where slno = '".$userreference."';";
	$fetch2 = runmysqlqueryfetch($query2);
	$companyname = $fetch2['businessname'];
	#########  Mailing Starts -----------------------------------
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
	$emailids['rashmi'] = 'archana.ab@relyonsoft.com';
	else
	$emailids[$responsibleperson] = $emailid;
	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/smsactivation.htm");
	$textmsg = file_get_contents("../mailcontents/smsactivation.txt");
	$date = date('d-m-Y').' ('.date('H:i').')';
	$array = array();
	$array[] = "##USERNAME##%^%".$smsusername;
	$array[] = "##SMSPASSWORD##%^%".$smspassword;
	$array[] = "##SMSFROMNAME##%^%".$smsfromname;
	$array[] = "##RESPONSIBLEPERSON##%^%".$responsibleperson;
	$array[] = "##MOBILENO##%^%".$mobileno;
	$array[] = "##EMAILID##%^%".$emailid;
	$array[] = "##COMPANYNAME##%^%".$companyname;
	

	$filearray = array(
		array('../images/relyon-logo.jpg','inline','1234567890')
				);
	$toarray = $emailids;
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
	{
		$bccemailids['meghana'] ='meghana.b@relyonsoft.com';
	}
	else
	{
		$bccemailids['relyonimax'] ='relyonimax@gmail.com';
		$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
		$bccemailids['webmaster'] ='webmaster@relyonsoft.com';
	}
	
	$bccarray = $bccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$replyto = 'support@relyonsoft.com';
	//$textmsg ='Test Message';

	$subject = "SMS Account Activation";
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray,$replyto);
	//Insert the mail forwarded details to the logs table
	$bccmailid = 'webmaster@relyonsoft.com,bigmail@relyonsoft.com'; 
	inserttologs($userid,$userreference,$fromname,$fromemail,$emailid,null,$bccmailid,$subject);
	
}



function finalsplit($name)
{
	$array[]= split(',',$name);
	for($j=0;$j<count($array);$j++)
	{
		$splitarray = $array[$j][0];
	}
	return $splitarray;
}

function firstletterupper($result_contact)
{
	$count = 0;
	$contact = split(',',$result_contact);
	$array = array_map('trim', $contact);
	for($j=0;$j<count($array);$j++)
	{
		$res = "";
		$var1 = "";
		$res = strtolower(substr($array[$j],1));
		$var1 = strtoupper(substr($array[$j],0,1));
		$char1[] = $var1.$res;
	}
	for($i=0;$i<count($char1);$i++)
	{
		if($count > 0)
		{
			$result1 .= ',';
		}
			$result1 .=  $char1[$i];
			$count++;
	}
	return $result1 ;
}

function inserttologs($userid,$id,$fromname,$emailfrom,$emailto,$ccmailids,$bccemailids,$subject)
{
	$module = 'user_module';
	$sentthroughip = $_SERVER['REMOTE_ADDR'];
	$query = "insert into inv_logs_mails(userid,id,fromname,emailfrom,emailto,ccmailids,bccmailids,subject,date,fromip,module) values('".$userid."','".$id."','".$fromname."','".$emailfrom."','".$emailto."','".$ccmailids."','".$bccemailids."','".$subject."','".date('Y-m-d').' '.date('H:i:s')."','".$sentthroughip."','".$module."');";
	$result = runmysqlquery($query);
}

function vieworgeneratepdfinvoice111($slno,$type)
{
	ini_set('memory_limit', '2048M');
	require_once('../pdfbillgeneration/tcpdf.php');
	
	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	
	
	// remove default header/footer
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	
	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	//set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	
	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
	
	//set some language-dependent strings
	$pdf->setLanguageArray($l); 
	
	
	// set font
	$pdf->SetFont('Helvetica', '',10);
	
	// add a page
	$pdf->AddPage();

	$query = "select * from inv_invoicenumbers 	where inv_invoicenumbers.slno = '".$slno."';";
	$result = runmysqlquery($query);
	
	$appendzero = '.00';
	$grid .='<table width="100%" border="0" align="center" cellpadding="4" cellspacing="0" >';
	$grid .='<tr><td ><table width="100%" border="0" cellspacing="0" cellpadding="4" bordercolor="#CCCCCC" style="border:1px solid "><tr bgcolor="#CCCCCC"><td width="10%"><div align="center"><strong>Sl No</strong></div></td><td width="76%"><div align="center"><strong>Description</strong></div></td><td width="14%"><div align="center"><strong>Amount</strong></div></td></tr>';
	while($fetch = mysql_fetch_array($result))
	{
		$description = $fetch['description'];
		$descriptionsplit = explode('*',$description);
		for($i=0;$i<count($descriptionsplit);$i++)
		{
			$descriptionline = explode('$',$descriptionsplit[$i]);
			if($fetch['purchasetype'] == 'SMS')
			{
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">'.$descriptionline[0].'</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.$descriptionline[1].'</td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.$descriptionline[2].'</td>';
				$grid .= "</tr>";

			}
			else
			{
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">'.$descriptionline[0].'</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.$descriptionline[1].'<br/>
		<span style="font-size:+7" ><strong>Purchase Type</strong> : '.$descriptionline[2].'&nbsp;/&nbsp;<strong>Usage Type</strong> :'.$descriptionline[3].'&nbsp;&nbsp;/ &nbsp;<strong>PIN Number : <font color="#FF3300">'.$descriptionline[4].'</font></strong> (<strong>Serial</strong> : '.$descriptionline[5].')</span></td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.$descriptionline[6].$appendzero.'</td>';
				$grid .= "</tr>";
			}
		}
		
		$servicedescriptionsplit = explode('*',$fetch['servicedescription']);
		$servicedescriptioncount = count($servicedescriptionsplit);
		if($fetch['servicedescription'] <> '')
		{
			for($i=0; $i<$servicedescriptioncount; $i++)
			{
				$servicedescriptionline = explode('$',$servicedescriptionsplit[$i]);
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">'.$servicedescriptionline[0].'</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.$servicedescriptionline[1].'</td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.$servicedescriptionline[2].$appendzero.'</td>';
				$grid .= "</tr>";
			}
		}
		
		$offerdescriptionsplit = explode('*',$fetch['offerdescription']);
		$offerdescriptioncount = count($offerdescriptionsplit);
		if($fetch['offerdescription'] <> '')
		{
			for($i=0; $i<$offerdescriptioncount; $i++)
			{
				$offerdescriptionline = explode('$',$offerdescriptionsplit[$i]);
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">&nbsp;</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.strtoupper($offerdescriptionline[1]).': '.$offerdescriptionline[0].'</td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.$offerdescriptionline[2].$appendzero.'</td>';
				$grid .= "</tr>";
			}
		}

		
		if($fetch['offerremarks'] <> '')
			$grid .= '<tr><td width="10%"></td><td width="76%" style="text-align:left;">'.$fetch['offerremarks'].'</td><td width="14%">&nbsp;</td></tr>';
		if($fetch['description'] == '')
			$offerdescriptioncount = 0;
		else
			$offerdescriptioncount = count($descriptionsplit);
		if($fetch['offerdescription'] == '')
			$descriptioncount = 0;
		else
			$descriptioncount = count($descriptionsplit);
		$rowcount = $offerdescriptioncount + $descriptioncount + $servicedescriptioncount ;
		if($rowcount < 8)
		{
			$grid .= addlinebreak($rowcount);

		}
	//	echo($fetch['description']); exit;
		$grid .= '<tr><td colspan="2" style="text-align:right" width="86%"><strong>Total</strong></td><td  width="14%" style="text-align:right">'.$fetch['amount'].$appendzero.'</td></tr><tr><td  width="56%" style="text-align:left"><span style="font-size:+6" >'.$fetch['servicetaxdesc'].$appendzero.' </span></td><td  width="30%" style="text-align:right"><strong>Service Tax @ 10.3%</strong></td><td  width="14%" style="text-align:right">'.$fetch['servicetax'].$appendzero.'</td></tr><tr><td  width="56%" style="text-align:right"><div align="left"><span style="font-size:+6" >E.&amp;O.E.</span></div></td><td  width="30%" style="text-align:right"><strong>Net Amount</strong></td><td  width="14%" style="text-align:right"><img src="../images/relyon-rupee-small.jpg" width="8" height="8" border="0" align="absmiddle"  />&nbsp;&nbsp;'.$fetch['netamount'].$appendzero.'</td> </tr><tr><td colspan="3" style="text-align:left"><strong>Rupee In Words</strong>: '.$fetch['amountinwords'].' only</td></tr>';
	  }

	$grid .='</table></td></tr></table>';
	$fetchresult = runmysqlqueryfetch($query);
	
	//to fetch dealer email id 
	$query0 = "select inv_mas_dealer.emailid as dealeremailid from inv_mas_dealer where inv_mas_dealer.slno = '".$fetchresult['dealerid']."';";
	$fetch0 = runmysqlqueryfetch($query0);
	$dealeremailid = $fetch0['dealeremailid'];
	
	$msg = file_get_contents("../pdfbillgeneration/bill-format-new.php");
	$array = array();
	$stdcode = $fetchresult['stdcode'];
	$array[] = "##BILLDATE##%^%".changedateformatwithtime($fetchresult['createddate']);
	$array[] = "##BILLNO##%^%".$fetchresult['invoiceno'];
	$array[] = "##BUSINESSNAME##%^%".$fetchresult['businessname'];
	$array[] = "##CONTACTPERSON##%^%".$fetchresult['contactperson'];
	$array[] = "##ADDRESS##%^%".$fetchresult['address'];
	$array[] = "##CUSTOMERID##%^%".$fetchresult['customerid'];
	$array[] = "##EMAILID##%^%".$fetchresult['emailid'];
	$array[] = "##PHONE##%^%".$fetchresult['phone'];
	$array[] = "##CELL##%^%".$fetchresult['cell'];
	$array[] = "##STDCODE##%^%".$stdcode;
	$array[] = "##CUSTOMERTYPE##%^%".$fetchresult['customertype'];
	$array[] = "##CUSTOMERCATEGORY##%^%".$fetchresult['customercategory'];
	$array[] = "##RELYONREP##%^%".$fetchresult['dealername'];
	$array[] = "##REGION##%^%".$fetchresult['region'];
	$array[] = "##BRANCH##%^%".$fetchresult['branch'];
	$array[] = "##PAYREMARKS##%^%".$fetchresult['remarks'];
	$array[] = "##INVREMARKS##%^%".$fetchresult['invoiceremarks'];
	$array[] = "##GENERATEDBY##%^%".$fetchresult['createdby'];
	$array[] = "##INVOICEHEADING##%^%".$fetchresult['invoiceheading'];
	
	$array[] = "##TABLE##%^%".$grid;
	$html = replacemailvariable($msg,$array);
	$pdf->WriteHTML($html,true,0,true);
		
	$localtime = date('His');
	$filename = str_replace('/','-',$fetchresult['invoiceno']);
	$filebasename = $filename.".pdf";
	$addstring ="/user";
	if($_SERVER['HTTP_HOST'] == "192.168.2.132" || $_SERVER['HTTP_HOST'] == "bhavesh" || $_SERVER['HTTP_HOST'] == "nagamani"  || $_SERVER['HTTP_HOST'] == "192.168.2.50")
		$addstring = "/rwm/saralimax-user";
		$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filebasename;
	
	if($type == 'view')
		$pdf->Output('example.pdf' ,'I');	
	else
	{
		$pdf->Output($filepath ,'F');
		return $filebasename.'^'.$fetchresult['businessname'].'^'.$fetchresult['invoiceno'].'^'.$fetchresult['emailid'].'^'.$fetchresult['customerid'].'^'.$dealeremailid;
	}
}

function vieworgeneratepdfinvoice_backup($slno,$type)
{
	ini_set('memory_limit', '2048M');
	require_once('../pdfbillgeneration/tcpdf.php');
	$query1 = "select * from inv_invoicenumbers where slno = '".$slno."';";
	$resultfetch1 = runmysqlqueryfetch($query1);
	$invoicestatus = $resultfetch1['status'];
	if($invoicestatus == 'CANCELLED')
	{
		// Extend the TCPDF class to create custom Header and Footer
		class MYPDF extends TCPDF {
		//Page header
		public function Header() {
			// full background image
			// store current auto-page-break status
			$bMargin = $this->getBreakMargin();
			$auto_page_break = $this->AutoPageBreak;
			$this->SetAutoPageBreak(false, 0);
			$img_file = K_PATH_IMAGES.'invoicing-cancelled-background.jpg';
			$this->Image($img_file, 0, 80, 820, 648, '', '', '', false, 75, '', false, false, 0);
			// restore auto-page-break status
			$this->SetAutoPageBreak($auto_page_break, $bMargin);
			}
		}
		
		// create new PDF document
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	}
	else
	{
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// remove default header
		$pdf->setPrintHeader(false);
	}

	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	//set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	

	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
	
	//set some language-dependent strings
	$pdf->setLanguageArray($l); 
	
	// remove default footer
	$pdf->setPrintFooter(false);
	
	// set font
	$pdf->SetFont('Helvetica', '', 10);
	
	// add a page
	$pdf->AddPage();
	
	$query = "select * from inv_invoicenumbers 	where inv_invoicenumbers.slno = '".$slno."';";
	$result = runmysqlquery($query);
	
	$appendzero = '.00';
	$grid .='<table width="100%" border="0" align="center" cellpadding="4" cellspacing="0" >';
	$grid .='<tr><td ><table width="100%" border="0" cellspacing="0" cellpadding="4" bordercolor="#CCCCCC" style="border:1px solid "><tr bgcolor="#CCCCCC"><td width="10%"><div align="center"><strong>Sl No</strong></div></td><td width="76%"><div align="center"><strong>Description</strong></div></td><td width="14%"><div align="center"><strong>Amount</strong></div></td></tr>';
	while($fetch = mysql_fetch_array($result))
	{
		$description = $fetch['description'];
		$descriptionsplit = explode('*',$description);
		for($i=0;$i<count($descriptionsplit);$i++)
		{
			$descriptionline = explode('$',$descriptionsplit[$i]);
			if($fetch['purchasetype'] == 'SMS')
			{
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">'.$descriptionline[0].'</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.$descriptionline[1].'</td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.$descriptionline[2].'</td>';
				$grid .= "</tr>";

			}
			else
			{
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">'.$descriptionline[0].'</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.$descriptionline[1].'<br/>
		<span style="font-size:+7" ><strong>Purchase Type</strong> : '.$descriptionline[2].'&nbsp;/&nbsp;<strong>Usage Type</strong> :'.$descriptionline[3].'&nbsp;&nbsp;/ &nbsp;<strong>PIN Number : <font color="#FF3300">'.$descriptionline[4].'</font></strong> (<strong>Serial</strong> : '.$descriptionline[5].')</span></td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.$descriptionline[6].$appendzero.'</td>';
				$grid .= "</tr>";
			}
		}
		$servicedescriptionsplit = explode('*',$fetch['servicedescription']);
		$servicedescriptioncount = count($servicedescriptionsplit);
		if($fetch['servicedescription'] <> '')
		{
			for($i=0; $i<$servicedescriptioncount; $i++)
			{
				$servicedescriptionline = explode('$',$servicedescriptionsplit[$i]);
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">'.$servicedescriptionline[0].'</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.$servicedescriptionline[1].'</td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.$servicedescriptionline[2].$appendzero.'</td>';
				$grid .= "</tr>";
			}
		}
		
		$offerdescriptionsplit = explode('*',$fetch['offerdescription']);
		$offerdescriptioncount = count($offerdescriptionsplit);
		if($fetch['offerdescription'] <> '')
		{
			for($i=0; $i<$offerdescriptioncount; $i++)
			{
				$offerdescriptionline = explode('$',$offerdescriptionsplit[$i]);
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">&nbsp;</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.strtoupper($offerdescriptionline[1]).': '.$offerdescriptionline[0].'</td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.$offerdescriptionline[2].$appendzero.'</td>';
				$grid .= "</tr>";
			}
		}

		if($fetch['offerremarks'] <> '')
			$grid .= '<tr><td width="10%"></td><td width="76%" style="text-align:left;">'.$fetch['offerremarks'].'</td><td width="14%">&nbsp;</td></tr>';
		if($fetch['description'] == '')
			$offerdescriptioncount = 0;
		else
			$offerdescriptioncount = count($descriptionsplit);
		if($fetch['offerdescription'] == '')
			$descriptioncount = 0;
		else
			$descriptioncount = count($descriptionsplit);
		if($fetch['servicedescription'] == '')
			$servicedescriptioncount = 0;
		else
			$servicedescriptioncount = count($servicedescriptionsplit);
		$rowcount = $offerdescriptioncount + $descriptioncount + $servicedescriptioncount ;
		if($rowcount < 8)
		{
			$grid .= addlinebreak($rowcount);

		}
		
		if($fetch['status'] == 'EDITED')
		{
			$query011 = "select * from inv_mas_users where slno = '".$fetch['editedby']."';";
			$resultfetch011 = runmysqlqueryfetch($query011);
			$changedby = $resultfetch011['fullname'];
			$statusremarks = 'Last updated by  '.$changedby.' on '.changedateformatwithtime($fetch['editeddate']).' <br/>Remarks: '.$fetch['editedremarks'];
		}
		elseif($fetch['status'] == 'CANCELLED')
		{
			$query011 = "select * from inv_mas_users where slno = '".$fetch['cancelledby']."';";
			$resultfetch011 = runmysqlqueryfetch($query011);
			$changedby = $resultfetch011['fullname'];
			$statusremarks = 'Cancelled by '.$changedby.' on '.changedateformatwithtime($fetch['cancelleddate']).'  <br/>Remarks: '.$fetch['cancelledremarks'];

		}
		else
			$statusremarks = '';
			//echo($statusremarks); exit;
		$grid .= '<tr><td  width="56%" style="text-align:left"><span style="font-size:+6;color:#FF0000" >'.$statusremarks.'</span></td><td  width="30%" style="text-align:right"><strong>Total</strong></td><td  width="14%" style="text-align:right">'.$fetch['amount'].$appendzero.'</td></tr><tr><td  width="56%" style="text-align:left"><span style="font-size:+6" >'.$fetch['servicetaxdesc'].' </span></td><td  width="30%" style="text-align:right"><strong>Service Tax @ 10.3%</strong></td><td  width="14%" style="text-align:right">'.$fetch['servicetax'].$appendzero.'</td></tr><tr><td  width="56%" style="text-align:right"><div align="left"><span style="font-size:+6" >E.&amp;O.E.</span></div></td><td  width="30%" style="text-align:right"><strong>Net Amount</strong></td><td  width="14%" style="text-align:right"><img src="../images/relyon-rupee-small.jpg" width="8" height="8" border="0" align="absmiddle"  />&nbsp;&nbsp;'.$fetch['netamount'].$appendzero.'</td> </tr><tr><td colspan="3" style="text-align:left"><strong>Rupee In Words</strong>: '.$fetch['amountinwords'].' only</td></tr>';
	//	echo($grid1); exit;
	//	$grid .= '<tr><td colspan="2" style="text-align:right" width="86%"><strong>Total</strong></td><td  width="14%" style="text-align:right">'.$fetch['amount'].$appendzero.'</td></tr><tr><td  width="56%" style="text-align:left"><span style="font-size:+6" >'.$fetch['servicetaxdesc'].$appendzero.' </span></td><td  width="30%" style="text-align:right"><strong>Service Tax @ 10.3%</strong></td><td  width="14%" style="text-align:right">'.$fetch['servicetax'].$appendzero.'</td></tr><tr><td  width="56%" style="text-align:right"><div align="left"><span style="font-size:+6" >E.&amp;O.E.</span></div></td><td  width="30%" style="text-align:right"><strong>Net Amount</strong></td><td  width="14%" style="text-align:right"><img src="../images/relyon-rupee-small.jpg" width="8" height="8" border="0" align="absmiddle"  />&nbsp;&nbsp;'.$fetch['netamount'].$appendzero.'</td> </tr><tr><td colspan="3" style="text-align:left"><strong>Rupee In Words</strong>: '.$fetch['amountinwords'].' only</td></tr>';
	  }

	$grid .='</table></td></tr></table>';
	$fetchresult = runmysqlqueryfetch($query);
	//to fetch dealer email id 
	$query0 = "select inv_mas_dealer.emailid as dealeremailid from inv_mas_dealer where inv_mas_dealer.slno = '".$fetchresult['dealerid']."';";
	$fetch0 = runmysqlqueryfetch($query0);
	$dealeremailid = $fetch0['dealeremailid'];
	
	$msg = file_get_contents("../pdfbillgeneration/bill-format-new.php");
	$array = array();
	$stdcode = $fetchresult['stdcode'];
	$array[] = "##BILLDATE##%^%".changedateformatwithtime($fetchresult['createddate']);
	$array[] = "##BILLNO##%^%".$fetchresult['invoiceno'];
	$array[] = "##BUSINESSNAME##%^%".$fetchresult['businessname'];
	$array[] = "##CONTACTPERSON##%^%".$fetchresult['contactperson'];
	$array[] = "##ADDRESS##%^%".$fetchresult['address'];
	$array[] = "##CUSTOMERID##%^%".$fetchresult['customerid'];
	$array[] = "##EMAILID##%^%".$fetchresult['emailid'];
	$array[] = "##PHONE##%^%".$fetchresult['phone'];
	$array[] = "##CELL##%^%".$fetchresult['cell'];
	$array[] = "##STDCODE##%^%".$stdcode;
	$array[] = "##CUSTOMERTYPE##%^%".$fetchresult['customertype'];
	$array[] = "##CUSTOMERCATEGORY##%^%".$fetchresult['customercategory'];
	$array[] = "##RELYONREP##%^%".$fetchresult['dealername'];
	$array[] = "##REGION##%^%".$fetchresult['region'];
	$array[] = "##BRANCH##%^%".$fetchresult['branch'];
	$array[] = "##PAYREMARKS##%^%".$fetchresult['remarks'];
	$array[] = "##INVREMARKS##%^%".$fetchresult['invoiceremarks'];
	$array[] = "##GENERATEDBY##%^%".$fetchresult['createdby'];
	$array[] = "##INVOICEHEADING##%^%".$fetchresult['invoiceheading'];
	
	$array[] = "##TABLE##%^%".$grid;
	$html = replacemailvariable($msg,$array);
	$pdf->WriteHTML($html,true,0,true);
		
	$localtime = date('His');
	$filename = str_replace('/','-',$fetchresult['invoiceno']);
	$filebasename = $filename.".pdf";
	$addstring ="/user";
	if($_SERVER['HTTP_HOST'] == "192.168.2.50" || $_SERVER['HTTP_HOST'] == "bhavesh"  || $_SERVER['HTTP_HOST'] == "nagamani")
		$addstring = "/rwm/saralimax-user";
		$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filebasename;
	
	if($type == 'view')
		$pdf->Output('example.pdf' ,'I');	
	else
	{
		$pdf->Output($filepath ,'F');
		return $filebasename.'^'.$fetchresult['businessname'].'^'.$fetchresult['invoiceno'].'^'.$fetchresult['emailid'].'^'.$fetchresult['customerid'].'^'.$dealeremailid.'^'.$invoicestatus;
	}
	$pdf->writeHTML($html, true, false, true, false, '');

}


function vieworgeneratepdfinvoice($slno,$type)
{
	ini_set('memory_limit', '2048M');
	require_once('../pdfbillgeneration/tcpdf.php');
	$query1 = "select * from inv_invoicenumbers where slno = '".$slno."';";
	$resultfetch1 = runmysqlqueryfetch($query1);
	$invoicestatus = $resultfetch1['status'];
	if($invoicestatus == 'CANCELLED')
	{
		// Extend the TCPDF class to create custom Header and Footer
		class MYPDF extends TCPDF {
		//Page header
		public function Header() {
			// full background image
			// store current auto-page-break status
			$bMargin = $this->getBreakMargin();
			$auto_page_break = $this->AutoPageBreak;
			$this->SetAutoPageBreak(false, 0);
			$img_file = K_PATH_IMAGES.'invoicing-cancelled-background.jpg';
			$this->Image($img_file, 0, 80, 820, 648, '', '', '', false, 75, '', false, false, 0);
			// restore auto-page-break status
			$this->SetAutoPageBreak($auto_page_break, $bMargin);
			}
		}
		
		// create new PDF document
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	}
	else
	{
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// remove default header
		$pdf->setPrintHeader(false);
	}

	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	//set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	

	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
	
	//set some language-dependent strings
	$pdf->setLanguageArray($l); 
	
	// remove default footer
	$pdf->setPrintFooter(false);
	
	// set font
	$pdf->SetFont('Helvetica', '', 10);
	
	// add a page
	$pdf->AddPage();
	
	$query = "select * from inv_invoicenumbers where inv_invoicenumbers.slno = '".$slno."';";
	$result = runmysqlquery($query);
	
	$appendzero = '.00';
	$grid .='<table width="100%" border="0" align="center" cellpadding="4" cellspacing="0" >';
	$grid .='<tr><td ><table width="100%" border="0" cellspacing="0" cellpadding="4" bordercolor="#CCCCCC" style="border:1px solid "><tr bgcolor="#CCCCCC"><td width="10%"><div align="center"><strong>Sl No</strong></div></td><td width="76%"><div align="center"><strong>Description</strong></div></td><td width="14%"><div align="center"><strong>Amount</strong></div></td></tr>';
	while($fetch = mysql_fetch_array($result))
	{
		$description = $fetch['description'];
		$productbriefdescription = $fetch['productbriefdescription'];
		$productbriefdescriptionsplit = explode('#',$productbriefdescription);
		$descriptionsplit = explode('*',$description);
		for($i=0;$i<count($descriptionsplit);$i++)
		{
			$productdesvalue = '';
			$descriptionline = explode('$',$descriptionsplit[$i]);
			if($productbriefdescription <> '')
				$productdesvalue = $productbriefdescriptionsplit[$i];
			else
				$productdesvalue = 'Not Available';
			/*if($fetch['purchasetype'] == 'SMS')
			{
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">'.$descriptionline[0].'</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.$descriptionline[1].'</td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.$descriptionline[2].'</td>';
				$grid .= "</tr>";

			}
			else
			{*/
				if($description <> '')
				{
					$grid .= '<tr>';
					$grid .= '<td width="10%" style="text-align:centre;">'.$descriptionline[0].'</td>';
					$grid .= '<td width="76%" style="text-align:left;">'.$descriptionline[1].'<br/>
			<span style="font-size:+7" ><strong>Purchase Type</strong> : '.$descriptionline[2].'&nbsp;/&nbsp;<strong>Usage Type</strong> :'.$descriptionline[3].'&nbsp;&nbsp;/ &nbsp;<strong>PIN Number : <font color="#FF3300">'.$descriptionline[4].'</font></strong> (<strong>Serial</strong> : '.$descriptionline[5].')</span><br/><span style="font-size:+6" ><strong>Product Description</strong> : '.$productdesvalue.' </span></td>';
					$grid .= '<td  width="14%" style="text-align:right;" >'.formatnumber($descriptionline[6]).$appendzero.'</td>';
					$grid .= "</tr>";
				}
			//}
		}
		$itembriefdescription = $fetch['itembriefdescription'];
		$itembriefdescriptionsplit = explode('#',$itembriefdescription);
		$servicedescriptionsplit = explode('*',$fetch['servicedescription']);
		$servicedescriptioncount = count($servicedescriptionsplit);
		if($fetch['servicedescription'] <> '')
		{
			for($i=0; $i<$servicedescriptioncount; $i++)
			{
				$itemdesvalue = '';
				$servicedescriptionline = explode('$',$servicedescriptionsplit[$i]);
				if($itembriefdescription <> '')
					$itemdesvalue = $itembriefdescriptionsplit[$i];
				else
					$itemdesvalue = 'Not Available';
					
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">'.$servicedescriptionline[0].'</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.$servicedescriptionline[1].'<br/><span style="font-size:+6" ><strong>Item Description</strong> : '.$itemdesvalue.' </span></td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.formatnumber($servicedescriptionline[2]).$appendzero.'</td>';
				$grid .= "</tr>";
			}
		}
		
		$offerdescriptionsplit = explode('*',$fetch['offerdescription']);
		$offerdescriptioncount = count($offerdescriptionsplit);
		if($fetch['offerdescription'] <> '')
		{
			for($i=0; $i<$offerdescriptioncount; $i++)
			{
				$offerdescriptionline = explode('$',$offerdescriptionsplit[$i]);
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">&nbsp;</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.strtoupper($offerdescriptionline[0]).': '.$offerdescriptionline[1].'</td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.formatnumber($offerdescriptionline[2]).$appendzero.'</td>';
				$grid .= "</tr>";
			}
		}

		if($fetch['offerremarks'] <> '')
			$grid .= '<tr><td width="10%"></td><td width="76%" style="text-align:left;">'.$fetch['offerremarks'].'</td><td width="14%">&nbsp;</td></tr>';
		$descriptionlinecount = 0;
		if($description <> '')
		{
			//Add description "Internet downloaded software"
			$grid .= '<tr><td width="10%"></td><td width="76%" style="text-align:center;"><font color="#666666">INTERNET DOWNLOADED SOFTWARE</font></td><td width="14%">&nbsp;</td></tr>';
			$descriptionlinecount = 1;
		}
		if($fetch['description'] == '')
			$offerdescriptioncount = 0;
		else
			$offerdescriptioncount = count($descriptionsplit);
		if($fetch['offerdescription'] == '')
			$descriptioncount = 0;
		else
			$descriptioncount = count($descriptionsplit);
		if($fetch['servicedescription'] == '')
			$servicedescriptioncount = 0;
		else
			$servicedescriptioncount = count($servicedescriptionsplit);
		$rowcount = $offerdescriptioncount + $descriptioncount + $servicedescriptioncount + $descriptionlinecount;
		if($rowcount < 6)
		{
			$grid .= addlinebreak($rowcount);

		}
		
		if($fetch['status'] == 'EDITED')
		{
			$query011 = "select * from inv_mas_users where slno = '".$fetch['editedby']."';";
			$resultfetch011 = runmysqlqueryfetch($query011);
			$changedby = $resultfetch011['fullname'];
			$statusremarks = 'Last updated by  '.$changedby.' on '.changedateformatwithtime($fetch['editeddate']).' <br/>Remarks: '.$fetch['editedremarks'];
		}
		elseif($fetch['status'] == 'CANCELLED')
		{
			$query011 = "select * from inv_mas_users where slno = '".$fetch['cancelledby']."';";
			$resultfetch011 = runmysqlqueryfetch($query011);
			$changedby = $resultfetch011['fullname'];
			$statusremarks = 'Cancelled by '.$changedby.' on '.changedateformatwithtime($fetch['cancelleddate']).'  <br/>Remarks: '.$fetch['cancelledremarks'];

		}
		else
			$statusremarks = '';
			//echo($statusremarks); exit;
		if($fetch['seztaxtype'] == 'yes')
		{
			$servicetax1 = 0;
			$servicetax2 = 0;
			$servicetax3 = 0;
			$sezremarks = 'TAX NOT APPLICABLE AS CUSTOMER IS UNDER SPECIAL ECONOMIC ZONE.<br/>';
		}
		else
		{
			$invoicedatedisplay = substr($fetch['createddate'],0,10);
			$invoicedate =  strtotime($invoicedatedisplay);
			$expirydate = strtotime('2012-04-01');
			
			if($expirydate > $invoicedate)
			{
				$servicetax1 = roundnearestvalue($fetch['amount'] * 0.1);
				$servicetax2 = roundnearestvalue($servicetax1 * 0.02);
				$servicetaxname = 'Service Tax @ 10%';
				$servicetax3 = roundnearestvalue(($fetch['amount'] * 0.103) - (($servicetax1) + ($servicetax2)));
			}
			else
			{
				$servicetax1 = roundnearestvalue($fetch['amount'] * 0.12);
				$servicetax2 = roundnearestvalue($servicetax1 * 0.02);
				$servicetaxname = 'Service Tax @ 12%';
				$servicetax3 = roundnearestvalue(($fetch['amount'] * 0.1236) - (($servicetax1) + ($servicetax2)));
			}
			$sezremarks = '';
			
		}
		$billdatedisplay = changedateformat(substr($fetch['createddate'],0,10));
		//echo($servicetax1.'#'.$servicetax2.'#'.$servicetax3); exit;
		$grid .= '<tr><td  width="56%" style="text-align:left"><span style="font-size:+6" >'.$fetch['servicetaxdesc'].' </span></td><td  width="30%" style="text-align:right"><strong>Net Amount</strong></td><td  width="14%" style="text-align:right">'.formatnumber($fetch['amount']).$appendzero.'</td></tr><tr><td  width="56%" style="text-align:left"><span style="font-size:+6;color:#FF0000" >'.$sezremarks.'</span><span style="font-size:+6;color:#FF0000" >'.$statusremarks.'</span></td><td  width="30%" style="text-align:right"><span style="font-size:+9" ><strong>'.$servicetaxname.'<br/>Cess @ 2%<br/>Sec Cess @ 1%</strong></span></td><td  width="14%" style="text-align:right"><span style="font-size:+9" >'.formatnumber($servicetax1).$appendzero.'<br/>'.formatnumber($servicetax2).$appendzero.'<br/>'.formatnumber($servicetax3).$appendzero.'</span></td></tr><tr><td  width="56%" style="text-align:right"><div align="left"><span style="font-size:+6" >E.&amp;O.E.</span></div></td><td  width="30%" style="text-align:right"><strong>Total</strong></td><td  width="14%" style="text-align:right"><img src="../images/relyon-rupee-small.jpg" width="8" height="8" border="0" align="absmiddle"  />&nbsp;&nbsp;'.formatnumber($fetch['netamount']).$appendzero.'</td> </tr><tr><td colspan="3" style="text-align:left"><strong>Rupee In Words</strong>: '.$fetch['amountinwords'].' only</td></tr>';
	//	echo($grid1); exit;
	//	$grid .= '<tr><td colspan="2" style="text-align:right" width="86%"><strong>Total</strong></td><td  width="14%" style="text-align:right">'.$fetch['amount'].$appendzero.'</td></tr><tr><td  width="56%" style="text-align:left"><span style="font-size:+6" >'.$fetch['servicetaxdesc'].$appendzero.' </span></td><td  width="30%" style="text-align:right"><strong>Service Tax @ 10.3%</strong></td><td  width="14%" style="text-align:right">'.$fetch['servicetax'].$appendzero.'</td></tr><tr><td  width="56%" style="text-align:right"><div align="left"><span style="font-size:+6" >E.&amp;O.E.</span></div></td><td  width="30%" style="text-align:right"><strong>Net Amount</strong></td><td  width="14%" style="text-align:right"><img src="../images/relyon-rupee-small.jpg" width="8" height="8" border="0" align="absmiddle"  />&nbsp;&nbsp;'.$fetch['netamount'].$appendzero.'</td> </tr><tr><td colspan="3" style="text-align:left"><strong>Rupee In Words</strong>: '.$fetch['amountinwords'].' only</td></tr>';
	  }

	$grid .='</table></td></tr></table>';
	$fetchresult = runmysqlqueryfetch($query);
	//to fetch dealer email id 
	$query0 = "select inv_mas_dealer.emailid as dealeremailid,cell as dealercell from inv_mas_dealer where inv_mas_dealer.slno = '".$fetchresult['dealerid']."';";
	$fetch0 = runmysqlqueryfetch($query0);
	$dealeremailid = $fetch0['dealeremailid'];
	$dealercell = $fetch0['dealercell'];
	if($fetchresult['status'] == 'CANCELLED')
	{
		$color = '#FF3300';
		$invoicestatus = '( '.$fetchresult['status'].' )';
	}
	else if($fetchresult['status'] == 'EDITED')
	{
		$color = '#006600';
		$invoicestatus = '( '.$fetchresult['status'].' )';
	}
	else
	{
		$invoicestatus = '';
	}
	
	$podatepiece = (($fetchresult['podate'] == "0000-00-00") || ($fetchresult['podate'] == ''))?("Not Available"):(changedateformat($fetchresult['podate']));
	$poreferencepiece = ($fetchresult['poreference'] == "")?("Not Available"):($fetchresult['poreference']);
	
	$msg = file_get_contents("../pdfbillgeneration/bill-format-new.php");
	$array = array();
	$stdcode = $fetchresult['stdcode'];
	$array[] = "##BILLDATE##%^%".$billdatedisplay;
	$array[] = "##BILLNO##%^%".$fetchresult['invoiceno'];
	$array[] = "##STATUS##%^%".$invoicestatus;
	$array[] = "##color##%^%".$color;
	$array[] = "##DEALERDETAILS##%^%".'Email: '.$dealeremailid.' | Cell: '.$dealercell;
	$array[] = "##BUSINESSNAME##%^%".$fetchresult['businessname'];
	$array[] = "##CONTACTPERSON##%^%".$fetchresult['contactperson'];
	$array[] = "##ADDRESS##%^%".stripslashes ( stripslashes ( $fetchresult['address']));
	$array[] = "##CUSTOMERID##%^%".$fetchresult['customerid'];
	$array[] = "##EMAILID##%^%".$fetchresult['emailid'];
	$array[] = "##PHONE##%^%".$fetchresult['phone'];
	$array[] = "##CELL##%^%".$fetchresult['cell'];
	$array[] = "##STDCODE##%^%".$stdcode;
	$array[] = "##CUSTOMERTYPE##%^%".$fetchresult['customertype'];
	$array[] = "##CUSTOMERCATEGORY##%^%".$fetchresult['customercategory'];
	$array[] = "##RELYONREP##%^%".$fetchresult['dealername'];
	$array[] = "##REGION##%^%".$fetchresult['region'];
	$array[] = "##BRANCH##%^%".$fetchresult['branch'];
	$array[] = "##PAYREMARKS##%^%".$fetchresult['remarks'];
	$array[] = "##INVREMARKS##%^%".$fetchresult['invoiceremarks'];
	$array[] = "##GENERATEDBY##%^%".$fetchresult['createdby'];
	$array[] = "##INVOICEHEADING##%^%".$fetchresult['invoiceheading'];
	$array[] = "##PODATE##%^%".$podatepiece;
	$array[] = "##POREFERENCE##%^%".$poreferencepiece;
	
	$array[] = "##TABLE##%^%".$grid;
	$html = replacemailvariable($msg,$array);
	$pdf->WriteHTML($html,true,0,true);
		
	$localtime = date('His');
	$filename = str_replace('/','-',$fetchresult['invoiceno']);
	$filebasename = $filename.".pdf";
	$addstring ="/user";
	if($_SERVER['HTTP_HOST'] == "rashmihk" || $_SERVER['HTTP_HOST'] == "meghanab" || $_SERVER['HTTP_HOST'] == "archanaab"  || $_SERVER['HTTP_HOST'] == "192.168.2.50")
		$addstring = "/rwm/saralimax-user";
		$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filebasename;
	
	if($type == 'view')
		$pdf->Output($filename ,'I');	
	else
	{
		$pdf->Output($filepath ,'F');
		return $filebasename.'^'.$fetchresult['businessname'].'^'.$fetchresult['invoiceno'].'^'.$fetchresult['emailid'].'^'.$fetchresult['customerid'].'^'.$dealeremailid.'^'.$invoicestatus.'^'.$fetchresult['status'].'^'.$fetchresult['contactperson'].'^'.$fetchresult['netamount'];
	}
	$pdf->writeHTML($html, true, false, true, false, '');

}

function roundnearestvalue($amount)
{
	$firstamount = round($amount,1);
	$amount1 = round($firstamount);
	return $amount1;
}
function decodevalue($input)
{
	$input = str_replace('\\\\','\\',$input);
	$input = str_replace("\\'","'",$input);
	$length = strlen($input);
	$output = "";
	for($i = 0; $i < $length; $i++)
	{
		if($i % 2 == 0)
			$output .= chr(ord($input[$i]) - 7);
	}
	$output = str_replace("'","\'",$output);
	return $output;
}

function encodevalue($input)
{
	$length = strlen($input);
	$output1 = "";
	for($i = 0; $i < $length; $i++)
	{
		$output1 .= $input[$i];
		if($i < ($length - 1))
			$output1 .= "a";
	}
	$output = "";
	for($i = 0; $i < strlen($output1); $i++)
	{
		$output .= chr(ord($output1[$i]) + 7);
	}
	return $output;
}

function generatebillnumber($dealerregion)
{
	$query4 = "select ifnull(max(onlineinvoiceno),0)+ 1 as invoicenotobeinserted from inv_invoicenumbers where category = '".$dealerregion."'";
	$resultfetch4 = runmysqlqueryfetch($query4);
	$onlineinvoiceno = $resultfetch4['invoicenotobeinserted'];
	$invoicenoformat = 'RSL/'.$dealerregion.'/'.$onlineinvoiceno;
	return $invoicenoformat;
}

function addlinebreak($linecount)
{
	switch($linecount)
	{
		case '1':
		{
			$linebreak = '<tr><td width="10%"><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';
		}
		break;
		case '2':
		{
			$linebreak = '<tr><td width="10%"><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';
		}
		break;
		case '3':
		{
			$linebreak = '<tr><td width="10%"><br/><br/><br/><br/><br/><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';
		}
		break;
		case '4':
		{
			$linebreak = '<tr><td width="10%"><br/><br/><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';
		}
		break;
		case '5':
		{
			$linebreak = '<tr><td width="10%"><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';
		}
		break;
		
	}
	return $linebreak;
}
function addlinebreak_bulkprint($linecount)
{
	switch($linecount)
	{
		case '1':
		{
			$linebreak = '<tr><td width="10%"><br/><br/><br/><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';
		}
		break;
		case '2':
		{
			$linebreak = '<tr><td width="10%"><br/><br/><br/><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';
		}
		break;
		case '3':
		{
			$linebreak = '<tr><td width="10%"><br/><br/><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';
		}
		break;
		case '4':
		{
			$linebreak = '<tr><td width="10%"><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';
		}
		break;
		
		
	}
	return $linebreak;
}

function appendcomma($value)
{
	if($value != '')
	{
		$append = ',';
	}
	else
	{
		$append = '';
	}
	return $append;
}


function removedoublecomma($string)
{
	$finalstring = $string;
	$commas =explode(',',$string);
	$countcomma = count($commas);
	for($i=0;$i<$countcomma;$i++)
	{
		$outputstring = str_replace(',,',',',$finalstring);
		$finalstring =  $outputstring;
	}
	return $outputstring;
}

function sendpurchasesummaryemail($dealerid,$recordreferencestring)
{
	$query1 = "select dealer_online_purchase.products,dealer_online_purchase.purchasetype,dealer_online_purchase.usagetype,dealer_online_purchase.quantity,dealer_online_purchase.productpricearray,inv_invoicenumbers.offerdescription,inv_invoicenumbers.servicedescription,inv_invoicenumbers.amount,inv_invoicenumbers.invoiceno,inv_invoicenumbers.createddate,inv_invoicenumbers.businessname,inv_invoicenumbers.createdby,inv_invoicenumbers.servicetax,inv_invoicenumbers.netamount,inv_invoicenumbers.dealername as dealername  from dealer_online_purchase left join inv_invoicenumbers on inv_invoicenumbers.slno = dealer_online_purchase.onlineinvoiceno where dealer_online_purchase.slno = '".$recordreferencestring."'; ";
	$fetch1 = runmysqlqueryfetch($query1);
	$onlineinvoiceno = $fetch1['onlineinvoiceno'];
	$productvalues = $fetch1['products'];
	$purchasetypevalues = $fetch1['purchasetype'];
	$usagetypevalues = $fetch1['usagetype'];
	$productquantityvalues = $fetch1['quantity'];
	$billedamount = $fetch1['productpricearray'];
	$offerdescription = $fetch1['offerdescription'];
	$servicedescription = $fetch1['servicedescription'];
	$totalamount = $fetch1['amount'];
	$netamount = $fetch1['netamount'];
	$servicetax = $fetch1['servicetax'];
	$createddate = changedateformatwithtime($fetch1['createddate']);
	$createdby = $fetch1['createdby'];
	$businessname = $fetch1['businessname'];
	$invoiceno = $fetch1['invoiceno'];
	$dealername = $fetch1['dealername'];
	if($productvalues <> '')
	{
		$actualamount = getactualprice($productvalues,$purchasetypevalues,$usagetypevalues,$productquantityvalues);
		$billedamountsplit = explode('*',$billedamount);
		$actualamountsplit = explode('*',$actualamount);
		$productvaluessplit = explode('#',$productvalues);
	}
	
	$grid = '<table width="100%" cellpadding="3" cellspacing="0"  border = "1px" class="table-border-grid" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;text-align:left" >';
	$grid .= '<tr class="tr-grid-header"  bgcolor ="#E9E9D1"><td>Sl No</td><td>Description</td><td>Billed Price</td><td>Actual Price</td><td>Variance</td></tr>';
	$slno = 0;
	if($productvalues <> '')
	{
		for($i=0;$i<count($productvaluessplit);$i++)
		{
			$query2 = "select * from inv_mas_product where productcode = '".$productvaluessplit[$i]."'";
			$resultfetch2 = runmysqlqueryfetch($query2);
			$productname = $resultfetch2['productname'];
			$slno++;
			$varience = calculatevarience($actualamountsplit[$i],$billedamountsplit[$i]);
			if($varience > 0)
				$varience = '+'.$varience.'%';
			else
				$varience = $varience.'%';
			$grid .= '<tr><td nowrap="nowrap" class="td-border-grid">'.$slno.'</td><td nowrap="nowrap" class="td-border-grid">'.$productname.'</td><td nowrap="nowrap" class="td-border-grid">'.$billedamountsplit[$i].'</td><td nowrap="nowrap" class="td-border-grid">'.$actualamountsplit[$i].'</td><td nowrap="nowrap" class="td-border-grid"><div align="right">'.$varience.'</div></td></tr>';
		}
	}
	if($offerdescription <> '')
	{
		$offerdescriptionsplit = explode('*',$offerdescription);
		for($i=0;$i<count($offerdescriptionsplit);$i++)
		{
			$offerdescriptionline = explode('$',$offerdescriptionsplit[$i]);
			$slno++;
			$grid .= '<tr><td nowrap="nowrap" class="td-border-grid">'.$slno.'</td><td nowrap="nowrap" class="td-border-grid">'.$offerdescriptionline[0].': '.$offerdescriptionline[1].'</td><td nowrap="nowrap" class="td-border-grid">'.$offerdescriptionline[2].'</td><td nowrap="nowrap" class="td-border-grid">&nbsp;</td><td nowrap="nowrap" class="td-border-grid">&nbsp;</td></tr>';
		}
	}
	if($servicedescription <> '')
	{
		$servicedescriptionsplit = explode('*',$servicedescription);
		for($i=0;$i<count($servicedescriptionsplit);$i++)
		{
			$servicedescriptionline = explode('$',$servicedescriptionsplit[$i]);
			$slno++;
			$grid .= '<tr><td>'.$slno.'</td><td nowrap="nowrap" class="td-border-grid">'.$servicedescriptionline[1].'</td><td nowrap="nowrap" class="td-border-grid">'.$servicedescriptionline[2].'</td><td nowrap="nowrap" class="td-border-grid">&nbsp;</td><td nowrap="nowrap" class="td-border-grid">&nbsp;</td></tr>';
		}
	}
	$slno++;
	$grid .= '<tr><td>'.$slno.'</td><td nowrap="nowrap" class="td-border-grid">Total</td><td nowrap="nowrap" class="td-border-grid">'.$netamount.'</td><td nowrap="nowrap" class="td-border-grid">&nbsp;</td><td nowrap="nowrap" class="td-border-grid">&nbsp;</td></tr>';
	$grid .= '</table>';
	
	$query = "select tlemailid,mgremailid,hoemailid,emailid,businessname from inv_mas_dealer where slno = '".$dealerid."';";
	$resultfetch = runmysqlqueryfetch($query);
	$tlemailid = $resultfetch['tlemailid'];
	$mgremailid = $resultfetch['mgremailid'];
	$hoemailid = $resultfetch['hoemailid'];
	$gmailid ='relyonimax@gmail.com';
	$emailid = $resultfetch['emailid'];
	$dealername = $resultfetch['businessname'];
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
	{
		$emailid = 'meghana.b@relyonsoft.com';
	}
	else
	{
		$emailid = $emailid;
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
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
	{
		$bcceallemailid = 'rashmi.hk@relyonsoft.com';
	}
	else
	{
		$bcceallemailid = $tlemailid.','.$mgremailid.','.$hoemailid.','.$gmailid;
	}
	
	$bccemailarray = explode(',',$bcceallemailid);
	$bccemailcount = count($bccemailarray);
	
	for($i = 0; $i < $bccemailcount; $i++)
	{
		if(checkemailaddress($bccemailarray[$i]))
		{
			if($i == 0)
				$bccemailids[$contactperson] = $bccemailarray[$i];
			else
				$bccemailids[$bccemailarray[$i]] = $bccemailarray[$i];
		}
	}
	
	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/purchasesummary.htm");
	$textmsg = file_get_contents("../mailcontents/purchasesummary.txt");
	$date = date('d-m-Y');
	$time = date('H:i:s');
	$array = array();
	$array[] = "##DATE##%^%".$date;
	$array[] = "##TIME##%^%".$time;
	$array[] = "##INVOICENO##%^%".$invoiceno;
	$array[] = "##COMPANYNAME##%^%".$businessname;
	$array[] = "##CREATEDBY##%^%".$createdby;
	$array[] = "##SALESPERSON##%^%".$dealername;
	$array[] = "##SALEVALUE##%^%".$totalamount;
	$array[] = "##TAX##%^%".$servicetax;
	$array[] = "##TOTALAMOUNT##%^%".$netamount;
	$array[] = "##TABLE##%^%".$grid;
	$array[] = "##EMAILID##%^%".$emailid;
	
	$filearray = array(
	array('../images/relyon-logo.jpg','inline','1234567890')
	);

	//Mail to customer
	$toarray = $emailids;
	
	//BCC to TL/Manager
	
	$bccarray = $bccemailids;
	

	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$attachedfilename = explode('.',$filebasename);
	$subject = "Summary: Invoice (".$invoiceno.") generated for ".$dealername."";
	$html = $msg;
	$text = $textmsg;
	$replyto = 'webmaster@relyonsoft.com';
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray,$replyto);
	inserttologs(imaxgetcookie('userid'),$dealerid,$fromname,$fromemail,$emailid,null,$bcceallemailid,$subject);
	return 'sent';
}

function getactualprice($productvalues,$purchasetypevalues,$usagetypevalues,$productquantityvalues)
{
	$count = 0;
	$productssplit = explode('#',$productvalues);
	$purchasevaluesplit = explode(',',$purchasetypevalues);
	$usagevaluesplit = explode(',',$usagetypevalues);
	$productquantitysplit = explode(',',$productquantityvalues);
	for($i = 0; $i < count($productssplit); $i++)
	{
		$recordnumber = $productssplit[$i];
		$purchasetype = $purchasevaluesplit[$i];
		$usagetype = $usagevaluesplit[$i];
		$productquantity = $productquantitysplit[$i];
		if($purchasetype == 'new' && $usagetype == 'singleuser')
			$producttypeprice = 'newsuprice';
		else if($purchasetype == 'new' && $usagetype == 'multiuser')
			$producttypeprice = 'newmuprice';
		else if($purchasetype == 'updation' && $usagetype == 'singleuser')
			$producttypeprice = 'updatesuprice';
		else if($purchasetype == 'updation' && $usagetype == 'multiuser')
			$producttypeprice = 'updatemuprice';
		else if($purchasetype == 'new' && $purchasetype == 'addlic')
			$producttypeprice = 'newaddlicenseprice';
		else
			$producttypeprice = 'updationaddlicenseprice';
		$query = "select ".$producttypeprice." as productprice from inv_dealer_pricing  where product = '".$recordnumber."'";
		$fetch = runmysqlqueryfetch($query);
		$productprice = $fetch['productprice'] * $productquantity;
		if($count > 0)
				$countvalues .= '*';
		$countvalues .= $productprice;
		$count++;
	}
	return $countvalues;
}

function calculatevarience($actualamount,$billedamount)
{
	$varamount = ($billedamount-$actualamount);
	$varper = ($varamount*100)/$actualamount;
	return round($varper);
}
function getpaymentmode($mode)
{
	switch($mode)
	{
		case 'cash': $modereturned = 'Cash'; break;
		case 'onlinetransfer': $modereturned = 'Online Transfer'; break;
		case 'chequeordd': $modereturned = 'Cheque / DD'; break;
		case 'creditordebit': $modereturned = 'Credit / Debit Card'; break;
		default: $modereturned = 'Cheque / DD';
	}
	return $modereturned;
}

function remove_duplicates($str) 
{
	//in an array called $results
  preg_match_all("([\w-]+(?:\.[\w-]+)*@(?:[\w-]+\.)+[a-zA-Z]{2,7})",$str,$results);
	//sort the results alphabetically
  sort($results[0]);
	//remove duplicate results by comparing it to the previous value
  $prev="";
  while(list($key,$val)=each($results[0])) 
  {
    if($val==$prev) unset($results[0][$key]);
    else $prev=$val;
  }
	//process the array and return the remaining email addresses
  $str = "";
  foreach ($results[0] as $value)
  {
     $str .= $value.",";
  }
  return trim($str,',');
}


function resendinvoice($invoiceno)
{
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
;
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

		$fromname = "Relyon";
		$fromemail = "imax@relyon.co.in";
		require_once("../inc/RSLMAIL_MAIL.php");
		$msg = file_get_contents("../mailcontents/resend-invoice.htm");
		$textmsg = file_get_contents("../mailcontents/resend-invoice.txt");
	
		//Relyon Logo for email Content, as Inline [Not attachment]
		$filearray = array(
			array('../images/relyon-logo.jpg','inline','1234567890'),array('../filecreated/'.$filebasename,'attachment','1234567891'),array('../images/relyon-rupee-small.jpg','inline','1234567892')
		);
		if($statuscheck == 'CANCELLED')
		{
			//Relyon Logo for email Content, as Inline [Not attachment]
			$filearray = array(
				array('../images/relyon-logo.jpg','inline','1234567890'),array('../filecreated/'.$filebasename,'attachment','1234567891')
			);
			$msg = file_get_contents("../mailcontents/invoicecancel.htm");
			$textmsg = file_get_contents("../mailcontents/invoicecancel.txt");
			$subject = "Relyon Online Invoice | ".$invoiceno." (Resent - CANCELLED)";
		}
		elseif($statuscheck == 'EDITED')
		{
			$msg = file_get_contents("../mailcontents/paymentinfo1.htm");
			$textmsg = file_get_contents("../mailcontents/paymentinfo1.txt");
			$subject = "Relyon Online Invoice | ".$invoiceno." (Resent - EDITED)";
		}
		else
		{
			$msg = file_get_contents("../mailcontents/paymentinfo1.htm");
			$textmsg = file_get_contents("../mailcontents/paymentinfo1.txt");
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
		if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
		{
			$emailid = 'archana.ab@relyonsoft.com';
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
		
		if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
		{
			$dealeremailid = 'rashmi.hk@relyonsoft.com';
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
		
		if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
		{
			$bccemailids['rashmi'] ='meghana.b@relyonsoft.com';
			//$bccemailids['archanaab'] ='archana.ab@relyonsoft.com';
		}
		else
		{
			$bccemailids = array('Bigmail' => 'bigmail@relyonsoft.com', 'Accounts'=> 'bills@relyonsoft.com', 'Webmaster' => 'webmaster@relyonsoft.com', 'Relyonimax' => 'relyonimax@gmail.com');
		}
		$bccarray = $bccemailids;
		$msg = replacemailvariable($msg,$array);
		$textmsg = replacemailvariable($textmsg,$array);

		$html = $msg;
		$text = $textmsg;
		$replyto = $ccemailids[$ccemailarray[0]];
		rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray,$replyto);
		//Insert the mail forwarded details to the logs table
		$bccmailid = 'bills@relyonsoft.com,bigmail@relyonsoft.com,webmaster@relyonsoft.com'; 
		inserttologs(imaxgetcookie('userid'),$slno,$fromname,$fromemail,$emailid,null,$bccmailid,$subject);
		return('1^Sent successfully');
	}
}

function viewreceipt($receiptno,$type)
{
	ini_set('memory_limit', '2048M');
	require_once('../pdfbillgeneration/tcpdf.php');
	$query1 = "select * from inv_mas_receipt where slno = '".$receiptno."';";
	$resultfetch1 = runmysqlqueryfetch($query1);
	$receiptstatus = $resultfetch1['status'];
	if($receiptstatus == 'CANCELLED')
	{
		// Extend the TCPDF class to create custom Header and Footer
		class MYPDF extends TCPDF {
		//Page header
		public function Header() {
			// full background image
			// store current auto-page-break status
			$bMargin = $this->getBreakMargin();
			$auto_page_break = $this->AutoPageBreak;
			$this->SetAutoPageBreak(false, 0);
			$img_file = K_PATH_IMAGES.'receipt-cancelled-background.gif';
			$this->Image($img_file, 0, 70, 820, 419, '', '', '', false, 75, '', false, false, 0);
			// restore auto-page-break status
			$this->SetAutoPageBreak($auto_page_break, $bMargin);
			}
		}
		
		// create new PDF document
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	}
	else
	{
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// remove default header
		$pdf->setPrintHeader(false);
	}	
	
	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	//set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	

	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
	
	//set some language-dependent strings
	$pdf->setLanguageArray($l); 
	
	// remove default footer
	$pdf->setPrintFooter(false);
	
	// set font
	$pdf->SetFont('Helvetica', '', 10);
	
	// add a page
	$pdf->AddPage();
	
	$query1 = "select * from inv_mas_receipt where slno = '".$receiptno."'";
	$result1 = runmysqlqueryfetch($query1);
	

	$query = "select * from inv_invoicenumbers 	where inv_invoicenumbers.slno = '".$result1['invoiceno']."';";
	$fetchresult = runmysqlqueryfetch($query);
	
	$description = $fetch['description'];
	$descriptionsplit = explode('*',$description);
	$product = $descriptionsplit[1];
	
	if($result1['paymentmode'] == 'chequeordd' )
		$remarks = '<span> Cheque/DD No: '.$result1['chequeno'].' dated '.changedateformat($result1['chequedate']).', drawn on '.$result1['drawnon'].', for amount <img src="../images/relyon-rupee-small.jpg" alt="" width="8" height="8" border="0" align="absmiddle" /> '.$result1['receiptamount'].'. Cheques received are subject to realization.</span>';
	else if($result1['receiptremarks'] <> '')
	{
		$remarks = $result1['receiptremarks'];
	}
	else if($result1['receiptremarks'] == '')
	{
		$remarks = 'NONE';
	}
	
	//status of receipt
	 if($result1['status'] == 'CANCELLED')
	{
		$query011 = "select * from inv_mas_users where slno = '".$result1['cancelledby']."';";
		$resultfetch011 = runmysqlqueryfetch($query011);
		$changedby = $resultfetch011['fullname'];
		$statusremarks = 'Cancelled by '.$changedby.' on '.changedateformatwithtime($result1['cancelleddate']).' <br/>'.$result1['cancelledremarks'];
	}
	elseif($result1['status'] == 'ACTIVE')
		$statusremarks = 'Not Available.';
	// Fetch Dealer emailid 
	
	$query0 = "select inv_mas_dealer.emailid as dealeremailid from inv_mas_dealer where inv_mas_dealer.slno = '".$fetchresult['dealerid']."';";
	$fetch0 = runmysqlqueryfetch($query0);
	$dealeremailid = $fetch0['dealeremailid'];
		
	$msg = file_get_contents("../pdfbillgeneration/receipt-format.php");
	$array = array();
	$stdcode = $fetchresult['stdcode'];
	$array[] = "##RECEIPTDATE##%^%".changedateformatwithtime($result1['createddate']);
	$array[] = "##SLNO##%^%".$result1['slno'];
	$array[] = "##BUSINESSNAME##%^%".$fetchresult['businessname'];
	$array[] = "##ADDRESS##%^%".$fetchresult['address'];
	$array[] = "##CUSTOMERID##%^%".$fetchresult['customerid'];
	$array[] = "##RELYONREP##%^%".$fetchresult['dealername'];
	$array[] = "##RECEIPTREMARKS##%^%".$remarks;
	$array[] = "##GENERATEDBY##%^%".$fetchresult['createdby'];
	$array[] = "##AMOUNT##%^%".formatnumber($result1['receiptamount']);
	$array[] = "##AMOUNTINWORDS##%^%".convert_number($result1['receiptamount']);
	$array[] = "##INVOICENO##%^%".$fetchresult['invoiceno'];
	$array[] = "##MODE##%^%".getpaymentmode($result1['paymentmode']);
	$array[] = "##REMARKS##%^%".$remarks;
	$html = replacemailvariable($msg,$array);
	
	$pdf->WriteHTML($html,true,0,true);
		
	$localtime = date('His');
	$filename = 'Receipt-'.$result1['slno'];
	$filebasename = $filename.".pdf";
	$addstring ="/user";
	if($_SERVER['HTTP_HOST'] == "rashmihk" || $_SERVER['HTTP_HOST'] == "meghanab" || $_SERVER['HTTP_HOST'] == "archanaab"  || $_SERVER['HTTP_HOST'] == "192.168.2.50")
		$addstring = "/rwm/saralimax-user";
		$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filebasename;
	
	if($type == 'view')
		$pdf->Output('example.pdf' ,'I');	
	else
	{
		$pdf->Output($filepath ,'F');
		return $filebasename.'^'.$fetchresult['businessname'].'^'.$fetchresult['invoiceno'].'^'.$fetchresult['emailid'].'^'.$result1['receiptamount'].'^'.$dealeremailid.'^'.$fetchresult['contactperson'].'^'.$fetchresult['place'].'^'.$fetchresult['customerid'].'^'.$result1['status'].'^'.$statusremarks;
	}
}


function sendreceipt($receiptno,$type)
{
	//$type = 'resend';
	$receiptdetails = viewreceipt($receiptno,'resend');
	
	$receiptdetailssplit = explode('^',$receiptdetails);
	$filebasename = $receiptdetailssplit[0];
	$businessname = $receiptdetailssplit[1];
	$invoiceno = $receiptdetailssplit[2];
	$emailid =  $receiptdetailssplit[3];
	$receiptamount =  $receiptdetailssplit[4];
	$dealeremailid =  $receiptdetailssplit[5];
	
	$contactperson = $receiptdetailssplit[6];
	$place = $receiptdetailssplit[7];
	$slno = substr($receiptdetailssplit[8],15,20);	
	if($type == 'cancelled')
	{
		$status = $receiptdetailssplit[9];
		$cancelledreason = $receiptdetailssplit[10];
	}
	
	if($filebasename <> '')
	{
		//Dummy line to override To email ID
		
		if(($_SERVER['HTTP_HOST'] == "meghanab") ||($_SERVER['HTTP_HOST'] == "rashmihk") || ($_SERVER['HTTP_HOST'] == "archanaab") )
			$emailid = 'meghana.b@relyonsoft.com';
		else
			$emailid = $emailid;

		$fromname = "Relyon";
		$fromemail = "imax@relyon.co.in";
		require_once("../inc/RSLMAIL_MAIL.php");
		if($type == 'resend')
		{
			$msg = file_get_contents("../mailcontents/receipt.htm");
			$textmsg = file_get_contents("../mailcontents/receipt.txt");
		}
		elseif($type == 'cancelled')
		{
			$msg = file_get_contents("../mailcontents/cancelledreceipt.htm");
			$textmsg = file_get_contents("../mailcontents/cancelledreceipt.txt");
		}
	
		//Create an array of replace parameters
		$array = array();
		$date = datetimelocal('d-m-Y');
		$array[] = "##DATE##%^%".$date;
		$array[] = "##COMPANYNAME##%^%".$businessname;
		$array[] = "##INVOICENO##%^%".$invoiceno;
		$array[] = "##PLACE##%^%".$place;
		$array[] = "##AMOUNT##%^%".$receiptamount;
		$array[] = "##CONTACTPERSON##%^%".$contactperson;
		$array[] = "##EMAILID##%^%".$emailid;
		if($type == 'cancelled')
		{
			$array[] = "##REASON##%^%".$cancelledreason;
			$array[] = "##RECEIPTNO##%^%".$receiptno;
		}
		
		#########  Mailing Starts -----------------------------------
		$emailid = $emailid;
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
		if(($_SERVER['HTTP_HOST'] == "meghanab") ||($_SERVER['HTTP_HOST'] == "rashmihk") || ($_SERVER['HTTP_HOST'] == "archanaab") )
			$dealeremailid = 'rashmi.hk@relyonsoft.com';
		else
			$dealeremailid = $dealeremailid;
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
		
		//Relyon Logo for email Content, as Inline [Not attachment]
		if($type == 'resend')
		{
			$filearray = array(
				array('../images/relyon-logo.jpg','inline','1234567890'),
				array('../filecreated/'.$filebasename,'attachment','1234567891'),array('../images/relyon-rupee-small.jpg','inline','1234567892')
			);
		}
		elseif($type == 'cancelled')
		{
			$filearray = array(
				array('../images/relyon-logo.jpg','inline','1234567890'),
				array('../filecreated/'.$filebasename,'attachment','1234567891'));
							
		}
		$toarray = $emailids;
		
		//CC to sales person
		$ccarray = $ccemailids;
		
		if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
		{
			$bccemailids['rashmi'] ='meghana.b@relyonsoft.com';
			//$bccemailids['archanaab'] ='archana.ab@relyonsoft.com';
		}
		else
		{
			$bccemailids = array('Bigmail' => 'bigmail@relyonsoft.com', 'Accounts'=> 'bills@relyonsoft.com', 'Relyonimax' => 'relyonimax@gmail.com');
		}
		$bccarray = $bccemailids;
		$msg = replacemailvariable($msg,$array);
		$textmsg = replacemailvariable($textmsg,$array);
		if($type == 'resend')
			$subject = "Payment Receipt against Invoice | ".$invoiceno." ";
		elseif($type == 'cancelled')
			$subject = " Receipt No ".$receiptno." (Cancelled)";
		$html = $msg;
		$text = $textmsg;
		$replyto = $ccemailids[$ccemailarray[0]];
		rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray,$replyto);
		
			//Insert the mail forwarded details to the logs table
		$bccmailid = 'bills@relyonsoft.com,bigmail@relyonsoft.com,webmaster@relyonsoft.com'; 
		inserttologs(imaxgetcookie('userid'),$slno,$fromname,$fromemail,$emailid,$dealeremailid,$bccmailid,$subject);
		return('1^Receipt Sent successfully');
	}
}


// Function to display amount in Indian Format (Eg:123456 : 1,23,456)

function formatnumber($number)
{
	if(is_numeric($number))
	{
		$numbersign = "";
		$numberdecimals = "";
		
		//Retain the number sign, if present
		if(substr($number, 0, 1 ) == "-" || substr($number, 0, 1 ) == "+")
		{
			$numbersign = substr($number, 0, 1 );
			$number = substr($number, 1);
		}
		
		//Retain the decimal places, if present
		if(strpos($number, '.'))
		{
			$position = strpos($number, '.'); //echo($position.'<br/>');
			$numberdecimals = substr($number, $position); //echo($numberdecimals.'<br/>');
			$number = substr($number, 0, ($position)); //echo($number.'<br/>');
		}
		
		//Apply commas
		if(strlen($number) < 4)
		{
			$output =  $number;
		}
		else
		{
			$lastthreedigits = substr($number, -3);
			$remainingdigits = substr($number, 0, -3);
			$tempstring = "";
			for($i=strlen($remainingdigits),$j=1; $i>0; $i--,$j++)
			{
				if($j % 2 <> 0)
					$tempstring = ','.$tempstring;
				$tempstring = $remainingdigits[$i-1].$tempstring;
			}
			$output = $tempstring.$lastthreedigits;
		}
		$finaloutput = $numbersign.$output.$numberdecimals;
		return $finaloutput;	
	}
	else
	{
		$finaloutput = 0;
		return $finaloutput;
	}
}

//Function to convert the number to words
function convert_number($number) 
{ 
    if (($number < 0) || ($number > 999999999)) 
    { 
    	throw new Exception("Number is out of range");
    } 
	 
	$cn = floor($number / 10000000); /* Crores */
	$number -= $cn * 10000000;   
	
	$ln = floor($number / 100000);  /* Lakhs */
	$number -= $ln * 100000;
	
    $kn = floor($number / 1000);     /* Thousands (kilo) */ 
    $number -= $kn * 1000; 
	
    $Hn = floor($number / 100);      /* Hundreds (hecto) */ 
    $number -= $Hn * 100; 
	
    $Dn = floor($number / 10);       /* Tens (deca) */ 
    $n = $number % 10;             /* Ones */ 

    $res = ""; 


	if($cn)
	{
		 $res .= convert_number($cn) . " Crore"; 
	}
	
	if($ln)
	{
		$res .= (empty($res) ? "" : " ") . 
            convert_number($ln) . " Lakh";
	}
    if ($kn) 
    { 
		
        $res .= (empty($res) ? "" : " ") . 
            convert_number($kn) . " Thousand"; 
    } 

    if ($Hn) 
    { 
        $res .= (empty($res) ? "" : " ") . 
            convert_number($Hn) . " Hundred"; 
    } 

    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", 
        "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", 
        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", 
        "Nineteen"); 
    $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", 
        "Seventy", "Eigthy", "Ninety"); 

    if ($Dn || $n) 
    { 
        if (!empty($res)) 
        { 
            $res .= " and "; 
        } 

        if ($Dn < 2) 
        { 
            $res .= $ones[$Dn * 10 + $n]; 
        } 
        else 
        { 
            $res .= $tens[$Dn]; 

            if ($n) 
            { 
                $res .= "-" . $ones[$n]; 
            } 
        } 
    } 

    if (empty($res)) 
    { 
        $res = "zero"; 
    } 

    return $res; 
} 


function sendimplementationmail($slno,$customerreference,$dealerid,$userid)
{
	$query = "select * from imp_implementation left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference  where imp_implementation.slno = '".$slno."' and imp_implementation.customerreference = '".$customerreference."' ";
	$result = runmysqlqueryfetch($query);
	
	//To fetch Sales Person Emailid 
	$query11 = "select * from inv_mas_dealer where slno = '".$dealerid."'";
	$result11 = runmysqlqueryfetch($query11);
	$dealeremailid = $result11['emailid'];
	$branchid = $result11['branch'];
	
	//To fetch Branch Head Emailid 
	$query123 = "select * from inv_mas_users where slno = '".$userid."'";
	$result123 = runmysqlqueryfetch($query123);
	
	$branchuseremailid = $result123['emailid'];
	
	$query12 = "select emailid from inv_mas_dealer where branchhead = 'yes' and branch = '".$branchid."' ";
	$result12 = runmysqlqueryfetch($query12);
	
	$branchemailid = $result11['emailid'];
	
	$resultemailid = $dealeremailid.','.$branchemailid.','.$branchuseremailid;
	
	$businessname = $result['businessname'];
	$place = $result['place'];
	$status = implemenationstatus($slno);
	
	$statusssplit = explode('#@#',$status);
	$statusname1 = $statusssplit[0];
	$statusremarks1 = $statusssplit[1];
	
	if(strtoupper($result['customizationapplicable']) == 'NO')
	{
		$custstatusdisplay = 'Not Applicable';
		$custremarksdisplay = 'Not Available';
	}
	else
	{
		$custstatusdisplay = $result['customizationstatus'];
		$custremarksdisplay = $result['customizationremarks'];;
	}
	
	$resultcount = "SELECT count(*) as count from imp_customizationfiles where imp_customizationfiles.impref = '".$slno."';";
	$fetch10 = runmysqlqueryfetch($resultcount);
	$fetchresultcount = $fetch10['count'];;
	
	
	
	$query = "SELECT imp_customizationfiles.slno,imp_customizationfiles.remarks,imp_customizationfiles.attachfilepath from imp_customizationfiles  WHERE imp_customizationfiles.impref = '".$slno."' order by createddatetime DESC ;";
	$custgrid = '<table width="100%" cellpadding="2" cellspacing="0" border ="1">';
	$custgrid .= '<tr  align ="left"><td nowrap = "nowrap" width="20%">Sl No</td><td nowrap = "nowrap" width="80%">Remarks</td></tr>';
	$i_n = 0;
	$result1 = runmysqlquery($query);
	$fetchcount = mysql_num_rows($result1);
	if($fetchcount <> 0)
	{
		while($fetch23 = mysql_fetch_array($result1))
		{
			$i_n++;
			$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$custgrid .= '<tr bgcolor='.$color.'  align ="left">';
			$custgrid .= "<td nowrap='nowrap' width='20%'>".$slno."</td>";
			$custgrid .= "<td nowrap='nowrap' width='80%'>".gridtrim($fetch23['remarks'])."</td>";

			$custgrid .= "</tr>";
		}
		$custgrid .= "</table>";
	}
	else
	{
		$custgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"  ><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font></td></tr></table>';
	}
	

	//Add-on's Grid Details  
	$query15 = "SELECT imp_addon.slno, imp_addon.customerid, imp_addon.refslno, imp_mas_addons.addonname as addon, imp_addon.remarks,imp_addon.addon as addonslno from imp_addon left join imp_mas_addons on imp_mas_addons.slno = imp_addon.addon where refslno  = '".$slno."';";
	$addongrid = '<table width="100%" cellpadding="5" cellspacing="0"><tr><td><table width="100%" cellpadding="2" cellspacing="0" border="1">';
	$addongrid .= '<tr align ="left"><td nowrap = "nowrap"  ><strong>Sl No</strong></td><td nowrap = "nowrap" ><strong>Add-on</strong></td><td nowrap = "nowrap" ><strong>Remarks</strong></td></tr>';
	$result15 = runmysqlquery($query15);
	$slno3 =0;
	while($fetch15 = mysql_fetch_array($result15))
	{
		$slno3++;
		$addongrid .= '<tr align ="left">';
		$addongrid .= "<td nowrap='nowrap' >".$slno3."</td>";
		$addongrid .= "<td >".$fetch15['addon']."</td>";
		$addongrid .= "<td >".$fetch15['remarks']."</td>";
		$addongrid .= "</tr>";
	}
	$fetchcount2 = mysql_num_rows($result15);
	if($fetchcount2 == '0')
		$addongrid .= "<tr><td colspan ='3' class='imp_td-border-grid'><div align='center'><font color='#FF0000'><strong>No Records to Display</strong></font></div></td></tr>";
		$addongrid .= "</table></td></tr></table>";
	
	
	// Fetch Contact Details
	$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$customerreference."'; ";
	$resultfetch = runmysqlquery($query1);
	$valuecount = 0;
	while($fetchres = mysql_fetch_array($resultfetch))
	{
		if(checkemailaddress($fetchres['emailid']))
		{
			if($fetchres['contactperson'] != '')
				$emailids[$fetchres['contactperson']] = $fetchres['emailid'];
			else
				$emailids[$fetchres['emailid']] = $fetchres['emailid'];
		}
		$contactvalue .= $fetchres['contactperson'].',';
		$emailidvalues .= $fetchres['emailid'].',';
		
	}
	$contactperson = trim(removedoublecomma($contactvalue),',');
	$emailid = trim(removedoublecomma($emailidvalues),',');

	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
	{
		unset($emailids);
		$emailids[] = 'meghana.b@relyonsoft.com';
	}

	
	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/newimplementation.htm");
	$textmsg = file_get_contents("../mailcontents/newimplementation.txt");

	//Create an array of replace parameters
	$array = array();
	$date = datetimelocal('d-m-Y');
	$array[] = "##DATE##%^%".$date;
	$array[] = "##NAME##%^%".$contactperson;
	$array[] = "##COMPANY##%^%".$businessname;
	$array[] = "##PLACE##%^%".$place;
	$array[] = "##INVOICENO##%^%".$result['invoicenumber'];
	$array[] = "##STATUS##%^%".$statusname1;
	$array[] = "##STATUSREMARKS##%^%".$statusremarks1;	
	$array[] = "##PODATE##%^%".$result['podetails'];
	$array[] = "##NOCOMPANY##%^%".$result['numberofcompanies'];
	$array[] = "##NOMONTH##%^%".$result['numberofmonths'];
	$array[] = "##PROMONTH##%^%".$result['processingfrommonth'];
	$array[] = "##STARTDATE##%^%".changedateformat($result['committedstartdate']);
	$array[] = "##ADDONTABLE##%^%".$addongrid;
	$array[] = "##CUSTSTATUS##%^%".$custstatusdisplay;
	$array[] = "##CUSTREMARKS##%^%".$custremarksdisplay;
	$array[] = "##CUSTTABLE##%^%".$custgrid;
	$array[] = "##EMAILID##%^%".$emailid;

	//Relyon Logo for email Content, as Inline [Not attachment]
	$filearray = array(
		array('../images/relyon-logo.jpg','inline','8888888888'),
		array('../images/contact-info.gif','inline','33333333333')
	
	);
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
	{
		$resultemailid = 'rashmi.hk@relyonsoft.com';
	}
	else
	{
		$resultemailid = $resultemailid ;
	}
	$ccemailarray = explode(',',$resultemailid);
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
	$ccarray = $ccemailids;
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
	{
		$bccemailids['archana'] ='archana.ab@relyonsoft.com';
	}
	else
	{
		$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
		$bccemailids['vidyananda'] = 'vidyananda.csd@relyonsoft.com';
		$bccemailids['hariharan'] = 'hariharan.csd@relyonsoft.com';
		$bccemailids['relyonimax'] ='relyonimax@gmail.com';
	}

	$bccarray = $bccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = 'Implementation Request (Beta)'; 
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray);
	
	//Insert the mail forwarded details to the logs table
	$bccmailid = 'bigmail@relyonsoft.com';
	inserttologs($userid,$customerreference,$fromname,$fromemail,$emailid,null,$bccmailid,$subject);
	return 'sucess';
}


function sendupdateimpmail($slno,$dealerid,$userid)
{
	$query = "select * from imp_implementation left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference  where imp_implementation.slno = '".$slno."'";
	$result = runmysqlqueryfetch($query);
	
	$customerslno = $result['customerreference'];
	
	//To fetch Sales Person Emailid 
	$query11 = "select * from inv_mas_dealer where slno = '".$dealerid."'";
	$result11 = runmysqlqueryfetch($query11);
	
	$dealeremailid = $result11['emailid'];
	$branchid = $result11['branch'];
	$dealername = $result11['businessname'];
	
	//To fetch Branch Head Emailid 
	$query123 = "select * from inv_mas_users where slno = '".$userid."'";
	$result123 = runmysqlqueryfetch($query123);
	
	$branchuseremailid = $result123['emailid'];
	
	$query12 = "select emailid from inv_mas_dealer where branchhead = 'yes' and branch = '".$branchid."'";
	$result12 = runmysqlqueryfetch($query12);
	
	$branchemailid = $result11['emailid'];
	$branchname = $result11['businessname'];
	
	$emailid = $branchemailid.','.$branchuseremailid;
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);

	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
				$emailids[$emailarray[$i]] = $emailarray[$i];
		}
	}


	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
	{
		unset($emailids);
		$emailids[] = 'meghana.b@relyonsoft.com';
	}

	
	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/updateimp.htm");
	$textmsg = file_get_contents("../mailcontents/updateimp.txt");

	//Create an array of replace parameters
	$array = array();
	$date = datetimelocal('d-m-Y');
	$array[] = "##DATE##%^%".$date;
	$array[] = "##INVOICENO##%^%".$result['invoicenumber'];
	$array[] = "##BRANCHEADNAME##%^%".$branchname;
	$array[] = "##SALESNAME##%^%".$dealername;
	$array[] = "##EMAILID##%^%".$emailid;

	//Relyon Logo for email Content, as Inline [Not attachment]
	$filearray = array(
		array('../images/relyon-logo.jpg','inline','8888888888'),
	
	);
	
	$toarray = $emailids;
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
	{
		$bccemailids['archana'] ='archana.ab@relyonsoft.com';
	}
	else
	{
		$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
		$bccemailids['vidyananda'] = 'vidyananda.csd@relyonsoft.com';
		$bccemailids['hariharan'] = 'hariharan.csd@relyonsoft.com';
		$bccemailids['relyonimax'] ='relyonimax@gmail.com';
	}

	$bccarray = $bccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = 'Update in the Implementation Request for the Invoice No '.$result['invoicenumber'].'(Beta)'; 
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray);
	return 'sucess';
	//Insert the mail forwarded details to the logs table
	$bccmailid = 'bigmail@relyonsoft.com';
	inserttologs($userid,$customerslno,$fromname,$fromemail,$emailid,null,$bccmailid,$subject);
}



function sendshippmentmail($customerid,$type,$remarks)
{
	$query = "select count(*) as count from imp_implementation  where imp_implementation.customerreference = '".$customerid."'";
	$result = runmysqlqueryfetch($query);
	$fetchcount = $result['count'];
	if($fetchcount <> 0)
	{
		// Fetch Contact Details
		$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$customerid."'; ";
		$resultfetch = runmysqlquery($query1);
		$valuecount = 0;
		while($fetchres = mysql_fetch_array($resultfetch))
		{
			if(checkemailaddress($fetchres['emailid']))
			{
				if($fetchres['contactperson'] != '')
					$emailids[$fetchres['contactperson']] = $fetchres['emailid'];
				else
					$emailids[$fetchres['emailid']] = $fetchres['emailid'];
			}
			$contactvalue .= $fetchres['contactperson'].',';
			$emailidvalues .= $fetchres['emailid'].',';
			
		}
		$contactperson = trim(removedoublecomma($contactvalue),',');
		$emailid = trim(removedoublecomma($emailidvalues),',');
		
		$queryvalue = "Select * from inv_mas_customer where inv_mas_customer.slno = '".$customerid."'";
		$resultvalue = runmysqlqueryfetch($queryvalue);
		
		$query214 = "Select * from imp_implementation where imp_implementation.customerreference = '".$customerid."'";
		$result214 = runmysqlqueryfetch($query214);
		
		
		$busnessname = $resultvalue['businessname'];
		$place = $resultvalue['place'];
if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
		{
			unset($emailids);
			$emailids[] = 'meghana.b@relyonsoft.com';
		}
	
		
		$fromname = "Relyon";
		$fromemail = "imax@relyon.co.in";
		require_once("../inc/RSLMAIL_MAIL.php");
		if($type == 'invoice')
		{
			$msg = file_get_contents("../mailcontents/shippmentinvoice.htm");
			$textmsg = file_get_contents("../mailcontents/shippmentinvoice.txt");
		}
		elseif($type == 'manual')
		{
			$msg = file_get_contents("../mailcontents/shippmentmanual.htm");
			$textmsg = file_get_contents("../mailcontents/shippmentmanual.txt");
		}
	
		//Create an array of replace parameters
		$array = array();
		$date = datetimelocal('d-m-Y');
		$array[] = "##DATE##%^%".$date;
		$array[] = "##NAME##%^%".$contactperson;
		$array[] = "##COMPANY##%^%".$busnessname;
		$array[] = "##PLACE##%^%".$place;
		$array[] = "##INVOICENO##%^%".$result214['invoicenumber'];
		$array[] = "##REMARKS##%^%".$remarks;	
		$array[] = "##EMAILID##%^%".$emailid;

		//Relyon Logo for email Content, as Inline [Not attachment]
		$filearray = array(
			array('../images/relyon-logo.jpg','inline','8888888888'),
		
		);
		$toarray = $emailids;
		if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
		{
			$bccemailids['archana'] ='archana.ab@relyonsoft.com';
		}
		else
		{
			$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
			$bccemailids['relyonimax'] ='relyonimax@gmail.com';
		}
	
		$bccarray = $bccemailids;
		$msg = replacemailvariable($msg,$array);
		$textmsg = replacemailvariable($textmsg,$array);
		if($type == 'invoice')
			$subject = 'Shippment Information (Invoice)(Beta)'; 
		elseif($type == 'manual')
			$subject = 'Shippment Information (Manual)(Beta)'; 
		$html = $msg;
		$text = $textmsg;
		rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray);
		
		//Insert the mail forwarded details to the logs table
		$bccmailid = 'bigmail@relyonsoft.com';
		inserttologs($userid,$customerid,$fromname,$fromemail,$emailid,null,$bccmailid,$subject);
		return 'Mail has been sent Successfully.'.$result214['shipinvoiceremarks'];
	}
	else
	return 'Implementation Request is not Created Yet.';
}

function implemenationstatus($lastslno)
{
	$query = "SELECT imp_implementation.branchapproval, imp_implementation.coordinatorreject, imp_implementation.coordinatorapproval, imp_implementation.implementationstatus, inv_mas_implementer.businessname from  imp_implementation left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation 
where imp_implementation.slno = '".$lastslno."';";
	$fetch = runmysqlqueryfetch($query);
	
	$query1 = "Select iccattachmentpath,iccattachmentdate,inv_mas_implementer.businessname from imp_implementationdays
left join  inv_mas_implementer on inv_mas_implementer.slno = imp_implementationdays.iccattachmentby
where imp_implementationdays.impref = '".$lastslno."' and iccattachment = 'yes';";
	$result = runmysqlquery($query1);
	$fetchcount = mysql_num_rows($result);
	if($fetchcount <> 0)
		$result1 = runmysqlqueryfetch($query1);
	if($fetch['branchapproval'] == 'no'  && $fetch['coordinatorreject'] == 'no' && $fetch['coordinatorapproval'] == 'no' && $fetch['implementationstatus'] == 'pending')	
	{
		$statusname = 'Awaiting Branch Head Approval.';
		$statusremarks = 'Your Implementation activity has been submitted in the system. It is awaiting the approval from respective head. This will be executed shortly.';
	}
	elseif($fetch['branchapproval'] == 'yes'  && $fetch['coordinatorreject'] == 'no' && $fetch['coordinatorapproval'] == 'no' && $fetch['implementationstatus'] == 'pending')
	{
		$statusname = 'Awaiting Co-ordinator Approval.';
		$statusremarks = 'Your Implementation activity has been approved by respective head and now is with Coordinator. It wil be shortly reveiwed and processed further.';
	}
	elseif($fetch['branchapproval'] == 'no' && $fetch['coordinatorreject'] == 'yes' && $fetch['coordinatorapproval'] == 'no' && $fetch['implementationstatus'] == 'pending')
	{
		$statusname = 'Fowarded back to Branch Head.';
		$statusremarks = 'As there were few clarifications needed, your implementation activity has been forwarded back to respective head. It shall be processed soon.';
	}
	elseif($fetch['branchapproval'] == 'yes' && $fetch['coordinatorreject'] == 'no' && $fetch['coordinatorapproval'] == 'yes' && $fetch['implementationstatus'] == 'pending')
	{
		$statusname = 'Implementation, Yet to be Assigned.';
		$statusremarks = 'Your Implementation activity has been approved with all the levels. It will soon be assigned with Implementer and respective visits be scheduled.';
	}
	elseif($fetch['branchapproval'] == 'yes' && $fetch['coordinatorreject'] == 'no'  && $fetch['coordinatorapproval'] == 'yes' && $fetch['implementationstatus'] == 'pending')
	{
		$statusname = 'Assigned For Implementation.';
		$statusremarks = 'You have been assigned with Implementer  <font color="#178BFF"><strong> "'.$fetch['businessname'].'" </strong></font> . The visits scheduled shall be displayed here for your information / action.';
	}
	elseif($fetch['branchapproval'] == 'yes' && $fetch['coordinatorreject'] == 'no'  && $fetch['coordinatorapproval'] == 'yes' && $fetch['implementationstatus'] == 'progess')
	{
		$statusname = 'Implementation in progess.';
		$statusremarks = 'Visits are under progress for your Implementation. Our implmeneter has started his visits. The status remains the same until we receive "Implementation Completion Certificate.';
	}
	elseif($fetch['branchapproval'] == 'yes' && $fetch['coordinatorreject'] == 'no'  && $fetch['coordinatorapproval'] == 'yes' && $fetch['implementationstatus'] == 'completed')
	{
		$statusname = 'Implementation Completed.';
		$statusremarks = 'Your Implementation has been successfully completed. Please click here to view the "Implementation Completion Certificate.';
	}
		
		return $statusname.'#@#'.$statusremarks;
}

function sendbranchappovedmail($lastslno,$dealerid,$userid)
{
	$query = "select * from imp_implementation left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference  where imp_implementation.slno = '".$lastslno."'";
	$result = runmysqlqueryfetch($query);
	
	$approvedreason = $result['approvalremarks'];
	$customerslno = $result['customerreference'];
	$businessname = $result['businessname'];
	
	//To fetch Sales Person Emailid 
	$query11 = "select * from inv_mas_dealer where slno = '".$dealerid."' and disablelogin='no'";
	$result11 = runmysqlqueryfetch($query11);
	
	$dealeremailid = $result11['emailid'];
	$branchid = $result11['branch'];
	$dealername = $result11['businessname'];
	
	//To fetch Branch Head Emailid 
	
	$query12 = "select emailid from inv_mas_dealer where branchhead = 'yes' and branch = '".$branchid."' and disablelogin='no'";
	$result12 = runmysqlqueryfetch($query12);
	
	$query123 = "select * from inv_mas_users where slno = '".$userid."'";
	$result123 = runmysqlqueryfetch($query123);
	
	$branchname = $result11['businessname'];
	
	// Fetch coordination Emailid 
	
	$query13 = "select * from inv_mas_implementer where (branchid ='".$branchid."' or branchid ='all') and coordinator = 'yes' ";
	$result13 = runmysqlqueryfetch($query13);
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
	{
		$branchemailid = 'archana.ab@relyonsoft.com';
		$coordinatoremaild = 'rashmi.hk@relyonsoft.com';
		$branchuseremailid = 'bopanna.kn@relyonsoft.com';
	}
	else
	{
		$branchemailid = $result11['emailid']; 
		$coordinatoremaild = $result13['emailid'];
		$branchuseremailid = $result123['emailid'];
	}
	$emailid = $dealeremailid;
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);

	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
				$emailids[$emailarray[$i]] = $emailarray[$i];
		}
	}

	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
	{
		unset($emailids);
		$emailids[] = 'meghana.b@relyonsoft.com';
	}
	// CC to branchhead and Coordinator
	$ccids = $branchemailid.','.$coordinatoremaild.','.$branchuseremailid ;
	$ccemailarray = explode(',',$ccids);
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
	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/approvedbybranchhead.htm");
	$textmsg = file_get_contents("../mailcontents/approvedbybranchhead.txt");

	//Create an array of replace parameters
	$array = array();
	$date = datetimelocal('d-m-Y');
	$array[] = "##DATE##%^%".$date;
	$array[] = "##INVOICENO##%^%".$result['invoicenumber'];
	$array[] = "##BUSINESSNAME##%^%".$businessname;
	$array[] = "##BRANCHEADNAME##%^%".$branchname;
	$array[] = "##SALESNAME##%^%".$dealername;
	$array[] = "##EMAILID##%^%".$emailid;
	$array[] = "##APPREMARKS##%^%".$approvedreason;
	
	//Relyon Logo for email Content, as Inline [Not attachment]
	$filearray = array(
		array('../images/relyon-logo.jpg','inline','8888888888'),
	
	);
	
	$toarray = $emailids;
	$ccarray = $ccemailids;
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
	{
		$bccemailids['archana'] ='archana.ab@relyonsoft.com';
	}
	else
	{
		$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
		$bccemailids['relyonimax'] ='relyonimax@gmail.com';
	}

	$bccarray = $bccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = 'mysqli_ (Beta)'; 
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray);
	return 'sucess';
	//Insert the mail forwarded details to the logs table
	$bccmailid = 'bigmail@relyonsoft.com';
	inserttologs($userid,$customerslno,$fromname,$fromemail,$emailid,null,$bccmailid,$subject);
}

function matcharray($array1,$array2)
{
	$found = false;
	for($i = 0; $i < count($array1); $i++)
	{
		if(in_array($array1[$i],$array2))
		{
			$found = true;
			break;
		}
	}
	return $found;
}

function sendbranchrejectmail($lastslno,$dealerid,$userid)
{
	$query = "select * from imp_implementation left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference  where imp_implementation.slno = '".$lastslno."'";
	$result = runmysqlqueryfetch($query);
	
	$rejectreason = $result['branchrejectremarks'];
	$customerslno = $result['customerreference'];
	$businessname = $result['businessname'];
	
	//To fetch Sales Person Emailid 
	$query11 = "select * from inv_mas_dealer where slno = '".$dealerid."'";
	$result11 = runmysqlqueryfetch($query11);
	
	$dealeremailid = $result11['emailid'];
	$branchid = $result11['branch'];
	$dealername = $result11['businessname'];
	
	//To fetch Branch Head Emailid 
	
	$query12 = "select emailid from inv_mas_dealer where branchhead = 'yes' and branch = '".$branchid."'";
	$result12 = runmysqlqueryfetch($query12);
	
	$query123 = "select * from inv_mas_users where slno = '".$userid."'";
	$result123 = runmysqlqueryfetch($query123);
	
	$branchname = $result11['businessname'];
	
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
	{
		$branchemailid = 'rashmi.hk@relyonsoft.com';
		$branchuseremailid = 'bopanna.kn@relyonsoft.com';
	}
	else
	{
		$branchemailid = $result11['emailid']; 
		$branchuseremailid = $result123['emailid'];
	}
	$emailid = $dealeremailid;
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);

	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
				$emailids[$emailarray[$i]] = $emailarray[$i];
		}
	}

	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
	{
		unset($emailids);
		$emailids[] = 'meghana.b@relyonsoft.com';
	}
	// CC to branchhead and Coordinator
	$ccids = $branchemailid.','.$branchuseremailid ;
	$ccemailarray = explode(',',$ccids);
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
	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/rejectbybranchhead.htm");
	$textmsg = file_get_contents("../mailcontents/rejectbybranchhead.txt");

	//Create an array of replace parameters
	$array = array();
	$date = datetimelocal('d-m-Y');
	$array[] = "##DATE##%^%".$date;
	$array[] = "##INVOICENO##%^%".$result['invoicenumber'];
	$array[] = "##BUSINESSNAME##%^%".$businessname;
	$array[] = "##BRANCHEADNAME##%^%".$branchname;
	$array[] = "##SALESNAME##%^%".$dealername;
	$array[] = "##EMAILID##%^%".$emailid;
	$array[] = "##REJREMARKS##%^%".$rejectreason;
	
	//Relyon Logo for email Content, as Inline [Not attachment]
	$filearray = array(
		array('../images/relyon-logo.jpg','inline','8888888888'),
	
	);
	
	$toarray = $emailids;
	$ccarray = $ccemailids;
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
	{
		$bccemailids['archana'] ='rashmi.hk@relyonsoft.com';
	}
	else
	{
		$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
		$bccemailids['relyonimax'] ='relyonimax@gmail.com';
	}

	$bccarray = $bccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = 'Rejected Implementation Request by Branch Head (Beta)'; 
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray);
	return 'sucess';
	//Insert the mail forwarded details to the logs table
	$bccmailid = 'bigmail@relyonsoft.com';
	inserttologs($userid,$customerslno,$fromname,$fromemail,$emailid,null,$bccmailid,$subject);
}

function sendadvcollectmail($lastslno,$customerid,$type,$userid)
{
	$query = "select count(*) as count from imp_implementation  where imp_implementation.customerreference = '".$customerid."' and
	imp_implementation.slno =  '".$lastslno."'";
	$result = runmysqlqueryfetch($query);
	$fetchcount = $result['count'];
	if($fetchcount <> 0)
	{
		// Fetch Contact Details
		$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$customerid."'; ";
		$resultfetch = runmysqlquery($query1);
		$valuecount = 0;
		while($fetchres = mysql_fetch_array($resultfetch))
		{
			if(checkemailaddress($fetchres['emailid']))
			{
				if($fetchres['contactperson'] != '')
					$emailids[$fetchres['contactperson']] = $fetchres['emailid'];
				else
					$emailids[$fetchres['emailid']] = $fetchres['emailid'];
			}
			$contactvalue .= $fetchres['contactperson'].',';
			$emailidvalues .= $fetchres['emailid'].',';
			
		}
		$contactperson = trim(removedoublecomma($contactvalue),',');
		$emailid = trim(removedoublecomma($emailidvalues),',');
		
		$queryvalue = "Select * from inv_mas_customer where inv_mas_customer.slno = '".$customerid."'";
		$resultvalue = runmysqlqueryfetch($queryvalue);
		
		$query214 = "Select * from imp_implementation where imp_implementation.customerreference = '".$customerid."'";
		$result214 = runmysqlqueryfetch($query214);
		
		//To fetch Sales Person Emailid 
		$query11 = "select * from inv_mas_dealer where slno = '".$result214['dealerid']."'";
		$result11 = runmysqlqueryfetch($query11);
		
		$dealeremailid = $result11['emailid'];
		$branchid = $result11['branch'];
		$dealername = $result11['businessname'];
		
		//To fetch Branch Head Emailid 
		
		$query12 = "select TRIM(BOTH ',' FROM group_concat(emailid))as branchemailid,TRIM(BOTH ',' FROM group_concat(businessname))as businessname  from inv_mas_dealer where branchhead = 'yes' and branch = '".$branchid."'";
		$result12 = runmysqlqueryfetch($query12);
		$branchemailid = $result12['branchemailid'];
		
		// CC to Sales person
		$ccids = $dealeremailid;
		$ccemailarray = explode(',',$ccids);
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
		// BCC to Branch Head person
		$bccids = $branchemailid;
		$bccemailarray = explode(',',$bccids);
		$bccemailcount = count($bccemailarray);
		for($i = 0; $i < $bccemailcount; $i++)
		{
			if(checkemailaddress($bccemailarray[$i]))
			{
				if($i == 0)
					$bccemailids[$bccemailarray[$i]] = $bccemailarray[$i];
				else
					$bccemailids[$bccemailarray[$i]] = $bccemailarray[$i];
			}
		}
		
		if($type == 'receipt')
		{
			$query9 = "Select slno from inv_invoicenumbers where inv_invoicenumbers.invoiceno = '".$result214['invoicenumber']."'";
			$result9 = runmysqlqueryfetch($query9);	
			$query10 = "Select receiptamount from inv_mas_receipt where inv_mas_receipt.invoiceno = '".$result9['slno']."'";
			$result10 = runmysqlqueryfetch($query10);
			$amount = $result10['receiptamount'];
		}
		elseif($type == 'advamount')
		{
			$amount = $result214['advanceamount'];
		}
		
		
		$busnessname = $resultvalue['businessname'];
		$place = $resultvalue['place'];
if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") )
		{
			unset($emailids);
			$emailids[] = 'meghana.b@relyonsoft.com';
		}
	
		
		$fromname = "Relyon";
		$fromemail = "imax@relyon.co.in";
		require_once("../inc/RSLMAIL_MAIL.php");
		$msg = file_get_contents("../mailcontents/advcollect.htm");
		$textmsg = file_get_contents("../mailcontents/advcollect.txt");
		
	
		//Create an array of replace parameters
		$array = array();
		$date = datetimelocal('d-m-Y');
		$array[] = "##DATE##%^%".$date;
		$array[] = "##NAME##%^%".$contactperson;
		$array[] = "##COMPANY##%^%".$busnessname;
		$array[] = "##PLACE##%^%".$place;
		$array[] = "##INVOICENO##%^%".$result214['invoicenumber'];
		$array[] = "##AMOUNT##%^%".$amount;
		$array[] = "##EMAILID##%^%".$emailid;

		//Relyon Logo for email Content, as Inline [Not attachment]
		$filearray = array(
			array('../images/relyon-logo.jpg','inline','8888888888'),
		
		);
		$toarray = $emailids;
		$ccarray = $ccemailids;
		if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk"))
		{
			$bccemailids['archana'] ='rashmi.hk@relyonsoft.com';
		}
		else
		{
			$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
			$bccemailids['relyonimax'] ='relyonimax@gmail.com';
		}
	
		$bccarray = $bccemailids;
		$msg = replacemailvariable($msg,$array);
		$textmsg = replacemailvariable($textmsg,$array);
		$subject = 'Advance Payment Information (Beta)'; 
		$html = $msg;
		$text = $textmsg;
		rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray);
		return 'sucess';
		
		//Insert the mail forwarded details to the logs table
		$bccmailid = 'bigmail@relyonsoft.com';
		inserttologs($userid,$customerid,$fromname,$fromemail,$emailid,$ccids,$bccids,$subject);
	}
	else
	return 'Implementation Request is not Created Yet.';
}

//Function to generate Online Bill In PDF format
function generatecustomerexcel($value,$type)
{
	// PHPExcel
	require_once '../phpgeneration/PHPExcel.php';
	
	//PHPExcel_IOFactory
	require_once '../phpgeneration/PHPExcel/IOFactory.php';
	
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	
	//Define Style for header row
	$styleArray = array(
					'font' => array('bold' => true,),
					'fill'=> array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'color'=> array('argb' => 'FFCCFFCC')),
					'borders' => array('allborders'=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM))
				);
				
	//Define Style for content area
	$styleArrayContent = array(
					'borders' => array('allborders'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))
				);
	$pageindex = 0;
	
	$mySheet = $objPHPExcel->getActiveSheet();
	//Set the worksheet name
	$mySheet->setTitle('Customer wise');
	
	//Merge the cell
	$mySheet->mergeCells('A1:I1');
	$mySheet->mergeCells('A2:I2');
	$objPHPExcel->setActiveSheetIndex($pageindex)
					->setCellValue('A1', 'Relyon Softech Limited, Bangalore');
	$mySheet->getStyle('A1')->getFont()->setSize(12); 	
	$mySheet->getStyle('A1')->getFont()->setBold(true); 
	$mySheet->getStyle('A1')->getAlignment()->setWrapText(true);

	//Begin writing Customer wise 
	$currentrow = 3;
	
	//Set heading summary
	$mySheet->setCellValue('A'.$currentrow,'Customer wise');
	$mySheet->getStyle('A'.$currentrow)->getFont()->setSize(12); 	
	$mySheet->getStyle('A'.$currentrow)->getFont()->setBold(true);
	
	$currentrow++;
	//Set table headings
	$mySheet->setCellValue('B'.$currentrow,'Sl No')
			->setCellValue('C'.$currentrow,'Name of Customer')
			->setCellValue('D'.$currentrow,'Bill Number')
			->setCellValue('E'.$currentrow,'Pin Serial')
			->setCellValue('F'.$currentrow,'Pin Number')
			->setCellValue('G'.$currentrow,'Invoice Date')
			->setCellValue('H'.$currentrow,'Product Name')
			->setCellValue('I'.$currentrow,'Dealer')
			->setCellValue('J'.$currentrow,'Delay (number of days)');

	//Apply style for header Row
	$mySheet->getStyle('B'.$currentrow.':J'.$currentrow)->applyFromArray($styleArray);
	
	//Set table data
	$currentrow++;
	if($type == 'dealer')
	{
		$query = "select distinct inv_invoicenumbers.dealerid,inv_invoicenumbers.businessname as cusname,
	inv_invoicenumbers.customerid, inv_mas_dealer.businessname as dealerbusinessname, 
	DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) as datediffcount, 
	inv_invoicenumbers.invoiceno as invoiceno,left(inv_invoicenumbers.createddate,10) as createddate,
	inv_mas_product.productname AS productname, inv_mas_dealer.businessname as dealername,
	inv_mas_scratchcard.scratchnumber as pinno,inv_mas_scratchcard.cardid as cardid
	from inv_invoicenumbers 
	left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno
	left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
	left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
	left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
	where  (DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))
	>= '1' or DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))
	>= '60') and  inv_invoicenumbers.description <> '' and inv_invoicenumbers.products <> '' 
	and  inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' and  inv_invoicenumbers.dealerid = '".$value."'  group by inv_invoicenumbers.slno,inv_mas_scratchcard.cardid  order by DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) desc ";
	}
	elseif($type == 'branch')
	{
		$query = "select distinct inv_invoicenumbers.dealerid,inv_invoicenumbers.businessname as cusname,
	inv_invoicenumbers.customerid, inv_mas_dealer.businessname as dealerbusinessname, 
	DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) as datediffcount, 
	inv_invoicenumbers.invoiceno as invoiceno,left(inv_invoicenumbers.createddate,10) as createddate,
	inv_mas_product.productname AS productname, inv_mas_dealer.businessname as dealername,
	inv_mas_scratchcard.scratchnumber as pinno,inv_mas_scratchcard.cardid as cardid
	from inv_invoicenumbers 
	left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno
	left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
	left join inv_mas_branch on inv_mas_branch.slno = inv_invoicenumbers.branchid 
	left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
	left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
	where  (DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))
	>= '1' or DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))
	>= '60') and  inv_invoicenumbers.description <> '' and inv_invoicenumbers.products <> '' 
	and  inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' and  inv_invoicenumbers.branchid = '".$value."'  group by inv_invoicenumbers.slno,inv_mas_scratchcard.cardid  order by DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) desc ";
	}
	elseif($type == 'region')
	{
		$query = "select distinct inv_invoicenumbers.dealerid,inv_invoicenumbers.businessname as cusname,
	inv_invoicenumbers.customerid, inv_mas_dealer.businessname as dealerbusinessname, 
	DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) as datediffcount, 
	inv_invoicenumbers.invoiceno as invoiceno,left(inv_invoicenumbers.createddate,10) as createddate,
	inv_mas_product.productname AS productname, inv_mas_dealer.businessname as dealername,
	inv_mas_scratchcard.scratchnumber as pinno,inv_mas_scratchcard.cardid as cardid
	from inv_invoicenumbers 
	left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno
	left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
	left join inv_mas_branch on inv_mas_branch.slno = inv_invoicenumbers.branchid 
	left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
	left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
	where  (DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))
	>= '1' or DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))
	>= '60') and  inv_invoicenumbers.description <> '' and inv_invoicenumbers.products <> '' 
	and  inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' and  inv_invoicenumbers.regionid = '".$value."'  group by inv_invoicenumbers.slno,inv_mas_scratchcard.cardid  order by DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) desc ";
	}
	elseif($type == 'management')
	{
		$query = "select distinct inv_invoicenumbers.dealerid,inv_invoicenumbers.businessname as cusname,
	inv_invoicenumbers.customerid, inv_mas_dealer.businessname as dealerbusinessname, 
	DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) as datediffcount, 
	inv_invoicenumbers.invoiceno as invoiceno,left(inv_invoicenumbers.createddate,10) as createddate,
	inv_mas_product.productname AS productname, inv_mas_dealer.businessname as dealername,
	inv_mas_scratchcard.scratchnumber as pinno,inv_mas_scratchcard.cardid as cardid
	from inv_invoicenumbers 
	left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno
	left join inv_mas_dealer on inv_mas_dealer.slno = inv_invoicenumbers.dealerid 
	left join inv_mas_branch on inv_mas_branch.slno = inv_invoicenumbers.branchid 
	left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
	left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
	where  (DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))
	>= '1' or DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))
	>= '60') and  inv_invoicenumbers.description <> '' and inv_invoicenumbers.products <> '' 
	and  inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no'   group by inv_invoicenumbers.slno,inv_mas_scratchcard.cardid  order by DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) desc ";
	}
	$result = runmysqlquery($query);
	
	
	
	$slno_count = 0;
	$databeginrow = $currentrow;
	while($row_data = mysql_fetch_array($result))
	{
		$slno_count++;
		$mySheet->setCellValue('B'.$currentrow,$slno_count)
				->setCellValue('C'.$currentrow,$row_data['cusname'])
				->setCellValue('D'.$currentrow,$row_data['invoiceno'])
				->setCellValue('E'.$currentrow,$row_data['cardid'])
				->setCellValue('F'.$currentrow,$row_data['pinno'])
				->setCellValue('G'.$currentrow,changedateformat($row_data['createddate']))
				->setCellValue('H'.$currentrow,$row_data['productname'])
				->setCellValue('I'.$currentrow,$row_data['dealername'])
				->setCellValue('J'.$currentrow,$row_data['datediffcount']);
		$currentrow++;
	}
	
	$mySheet->getStyle('J'.($databeginrow).':J'.($currentrow-1))->getFont()->setBold(true)->setColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_RED) );
	//Apply style for Content row
	$mySheet->getStyle('B'.($databeginrow-1).':J'.($currentrow-1))->applyFromArray($styleArrayContent);
	
	//set the default width for column
	$mySheet->getColumnDimension('A')->setWidth(15);
	$mySheet->getColumnDimension('B')->setWidth(8);
	$mySheet->getColumnDimension('C')->setWidth(40);
	$mySheet->getColumnDimension('D')->setWidth(20);
	$mySheet->getColumnDimension('E')->setWidth(15);
	$mySheet->getColumnDimension('J')->setWidth(22);
	$mySheet->getColumnDimension('G')->setWidth(15);
	$mySheet->getColumnDimension('H')->setWidth(30);
	$mySheet->getColumnDimension('I')->setWidth(20);
	$mySheet->getColumnDimension('F')->setWidth(20);
		
		
		if($_SERVER['HTTP_HOST'] == "meghanab" || $_SERVER['HTTP_HOST'] == "rashmihk")
			$addstring = "/saralimax-user";
		else
			$addstring = "/user";
	$localdate = datetimelocal('Ymd');
	$localtime = datetimelocal('His');
	$filebasename = "CustomerDetails".$localdate."-".$localtime.".xls";

	//$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filebasename;
	if($_SERVER['HTTP_HOST'] == 'meghanab' || $_SERVER['HTTP_HOST'] == 'rashmihk')  
		$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/autoscripts/files/';
	else
		$filepath = getcwd().'/files/';

	$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xls');
	$objWriter->save($filepath.$filebasename);
	return $filepath.'$^$'.$filebasename;
}

function getusagetype($computerid)
{
	$usagetype = '';
	$getusagetype = substr($computerid,3,2);
	if($getusagetype == '00')
		$usagetype = 'Single User';
	else
		$usagetype = 'Multi User';
	return $usagetype;
}

##Added By Bhavesh Patel##
function forcesurrender($customerreference)
{
	$query = "SELECT getPINNo(cardid) AS pinno,cardid,slno FROM inv_customerproduct where AUTOREGISTRATIONYN = 'Y' and customerreference = '".$customerreference."' order by `date`  desc,`time` desc;";
	$result = runmysqlquery($query);
	
	$selectforce = ('<select name="forsurrender" class="swiftselect-mandatory" id="forsurrender" style="width:180px;" onchange="forcesurrenderdetails(\'countforcesurrender\');"><option value="">Make a selection</option>');
	
	while($fetch = mysql_fetch_array($result))
	{
		$selectforce .= ('<option value="'.$fetch['slno'].'">'.$fetch['pinno']. ' | ' .$fetch['cardid'].'</option>');
	}
	$selectforce .= ('</select>');
	
	return $selectforce;
}
##Added By Bhavesh Patel##
function surrendercount($refslno)
{
	$query = "select count(refslno) as refslnocount from inv_surrenderproduct where refslno =".$refslno;
	$fetchresult = runmysqlqueryfetch($query);
	$refslnocount = $fetchresult['refslnocount'];
	return $refslnocount;

}

##Added By Bhavesh Patel##
function sendsurrendermail()
{
	//	global $myemail;
	global	$businessname ;
	global	$forcecount;
	global	$emailid1;
	global	$emailtocustomer;
	global	$forceremarks ;
	global	$pinno ;
	global	$dlremail;
	global	$productname ;
	global	$cardid ;
	global	$customerid;
	global	$dlrname;
	global	$cardid;
	
	//Dummy line to override To email ID
	if(($_SERVER['HTTP_HOST'] == "bhavesh") || ($_SERVER['HTTP_HOST'] == "nagamani") ||  ($_SERVER['HTTP_HOST'] == "192.168.2.50") )
	{
		$emailid = 'hejalkumari.p@relyonsoft.com';
	}
	else
	{
		$emailid = $dlremail;
	}
	//Split multiple email IDs by Comma
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);
	
	//Convert email IDs to an array. First email ID will eb assigned with Contact person name. Others will be assigned with email IDs itself as Name.
	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
				$emailids[$emailarray[$i]] = $emailarray[$i];
		}
	}
	$fromname = "Relyon Softech Ltd";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/forcesurrender.htm");
	$textmsg = file_get_contents("../mailcontents/forcesurrender.txt");
	
	//Create an array of replace parameters
	$array = array();
	$date = datetimelocal('d-m-Y');
	$array[] = "##DATE##%^%".$date;
	$array[] = "##COMPANY##%^%".$businessname;
	$array[] = "##CUSTOMERID##%^%".$customerid;
	$array[] = "##DEALERNAME##%^%".$dlrname;
	$array[] = "##EMAILID##%^%".$emailid1;
	$array[] = "##PINNUMBER##%^%".$pinno;
	$array[] = "##PRODUCTNAME##%^%".$productname;
	$array[] = "##CARDID##%^%".$cardid;
	$array[] = "##FCOUNT##%^%".$forcecount;
	$array[] = "##DLRMAIL##%^%".$dlremail;
	
	//Relyon Logo for email Content, as Inline [Not attachment]
	$filearray = array(
		array('../images/relyon-logo.jpg','inline','8888888888'),
	
	);
	$toarray = $emailids;
	if($_SERVER['HTTP_HOST'] == "bhavesh" || $_SERVER['HTTP_HOST'] == "192.168.2.50")
	{
		if($emailtocustomer == 'yes')
		{
			$ccmymails[$businessname] ='bhavesh.d@relyonsoft.com';
		}
	}
	else
	{	
		if($emailtocustomer == 'yes')
		{
			$ccmymails[$businessname] = $emailid1;
		}
	}
	$ccarray = $ccmymails;

	if(($_SERVER['HTTP_HOST'] == "192.168.2.50") || ($_SERVER['HTTP_HOST'] == "bhavesh") ||  ($_SERVER['HTTP_HOST'] == "192.168.2.132") )
	{
		$bccemailids['Bhavesh'] ='bhavesh@relyonsoft.com';
	}
	else
	{
		$bccemailids['Bhavesh'] ='bhavesh@relyonsoft.com';
		$bccemailids['Webmaster'] ='webmaster@relyonsoft.com';
	}
	
	$bccarray = $bccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = 'PIN Number Surrendered "'.$pinno.'"';
	$html = $msg;
	$text = $textmsg;
	$replyto = 'webmaster@relyonsoft.com';
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray,$replyto);
	//Insert the mail forwarded details to the logs table
	$bccmailid = 'support@relyonsoft.com,info@relyonsoft.com,bigmail@relyonsoft.com';
	inserttologs($userid,$customerslno,$fromname,$fromemail,$emailid,$ccarray,$bccmailid,$subject);
	
}

?>