<?php
ini_set("display_errors",0);
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');
if(imaxgetcookie('userid')<> '') 
$userid = imaxgetcookie('userid');
else
{ 
	echo(json_encode('Thinking to redirect'));
	exit;
}

include('../inc/checksession.php');
include('../inc/checkpermission.php');
$lastslno = $_POST['lastslno'];
$switchtype = $_POST['switchtype'];

switch($switchtype)
{
	case 'save':
	{
		$responsearray = array();
		$customerreference = $_POST['customerreference'];
		$productcode = $_POST['productcode'];
		$startdate = $_POST['startdate'];
		$enddate =$_POST['enddate'];;
		//$enddate = $_POST['enddate'];
		$remarks = $_POST['remarks'];
		$billno = $_POST['billno'];
		$billamount = $_POST['billamount'];
		$billdate = $_POST['billdate'];
		$createddate = datetimelocal('d-m-Y').' '.datetimelocal('H:i:s');
		if($lastslno == "")
		{
			$query = "Insert into inv_customeramc(customerreference,productcode,startdate,enddate, remarks,createddate,createdby,billno,billamount,billdate,lastmodifieddate,lastmodifiedby,createdip,lastmodifiedip) values ('".$customerreference."','".$productcode."','".changedateformat($startdate)."','".changedateformat($enddate)."','".$remarks."','".changedateformatwithtime($createddate)."','".$userid."','".$billno."','".$billamount."','".changedateformat($billdate)."','".changedateformatwithtime($createddate)."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".$_SERVER['REMOTE_ADDR']."');";
			$result = runmysqlquery($query);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','46','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
			$eventresult = runmysqlquery($eventquery);

			$query2 = "select businessname as businessname,place as place from inv_mas_customer where slno ='".$customerreference."'";
			$fetch = runmysqlqueryfetch($query2);
			// Fetch Contact Details
			$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$customerreference."'; ";
			$resultfetch = runmysqlquery($query1);
			$valuecount = 0;
			while($fetchres = mysqli_fetch_array($resultfetch))
			{
				if(checkemailaddress($fetchres['emailid']))
				{
					if($fetchres['contactperson'] != '')
						$emailids[$fetchres['contactperson']] = $fetchres['emailid'];
					else
						$emailids[$fetchres['emailid']] = $fetchres['emailid'];
				}
				$contactperson = $fetchres['contactperson'];
				
				$contactvalues .= $contactperson;
				$contactvalues .= appendcomma($contactperson);
				$emailidres .= $emailid;
				$emailidres .= appendcomma($emailid);
			}
			
			$businessname = $fetch['businessname'];
			$contactperson = trim($contactvalues,',');
			$place = $fetch['place'];
			$businessname = $fetch['businessname'];
			$query3 = "SELECT productname as productname from inv_mas_product where productcode = '".$productcode."' ";
			$fetch1 = runmysqlqueryfetch($query3 );
			$productname = $fetch1['productname'];
			$query4 = "SELECT MAX(slno) as slno FROM inv_customeramc";
			$fetch2 = runmysqlqueryfetch($query4);
			$lastslno = $fetch2['slno'];
			
			#########  Mailing Starts -----------------------------------
			//$emailid = 'meghana.b@relyonsoft.com';
			if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
				$emailid = trim($emailidres,',');
			else
				$emailid = trim($emailidres,',');
				
			
			$fromname = "Relyon";
			$fromemail = "imax@relyon.co.in";
			require_once("../inc/RSLMAIL_MAIL.php");
			$msg = file_get_contents("../mailcontents/newamc.htm");
			$textmsg = file_get_contents("../mailcontents/newamc.txt");
			$date = datetimelocal('d-m-Y');
			$array = array();
			$array[] = "##DATE##%^%".$date;
			$array[] = "##NAME##%^%".$contactperson;
			$array[] = "##COMPANY##%^%".$businessname;
			$array[] = "##PLACE##%^%".$place;
			$array[] = "##STARTDATE##%^%".$startdate;
			$array[] = "##ENDDATE##%^%".$enddate;
			$array[] = "##AMCID##%^%".$lastslno;
			$array[] = "##PRODUCTNAME##%^%".$productname;
			$array[] = "##EMAILID##%^%".$emailid;
			$filearray = array(
				array('../images/relyon-logo.jpg','inline','8888888888'),
			);
			if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
			{
				$bccemailids['rashmi'] ='rashmi.hk@relyonsoft.com';
				$bccemailids['archanaab'] ='archana.ab@relyonsoft.com';
			}
			else
			{
				$bccemailids['relyonimax'] ='relyonimax@gmail.com';
				$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
			}
			$bccarray = $bccemailids;
			$toarray = $emailids;
			$msg = replacemailvariable($msg,$array);
			$textmsg = replacemailvariable($textmsg,$array);
			$subject = 'Maintenance Contract registered for "'.$businessname.'"';
			$text = "This is a HTML format email. Please enable HTML viewing in your email client.";
			$html = $msg;
			$text = $textmsg;
			rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray); 
			
			//Insert the mail forwarded details to the logs table
			$bccmailid = 'bigmail@relyonsoft.com'; 
			inserttologs(imaxgetcookie('userid'),$customerreference,$fromname,$fromemail,$emailid,null,$bccmailid,$subject);
			
			#########  Mailing Ends ----------------------------------------			
		}
		else
		{
			$query = "UPDATE inv_customeramc SET productcode = '".$productcode."',startdate = '".changedateformat($startdate)."',enddate = '".changedateformat($enddate)."',remarks = '".$remarks."',billno = '".$billno."',billamount = '".$billamount."',billdate = '".changedateformat($billdate)."',lastmodifiedby ='".$userid."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."' WHERE slno = '".$lastslno."'";
			$result = runmysqlquery($query);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','47','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
			$eventresult = runmysqlquery($eventquery);
		}
		$responsearray['errormessage'] = "1^"."Customer AMC Record '".$lastslno."' Saved Successfully";
		echo(json_encode($responsearray));
		//echo("1^"."Customer AMC Record '".$lastslno."' Saved Successfully");
		//echo($query);
	}
	break;

	case 'delete':
	{
		$responsearray = array();
		$query = "DELETE FROM inv_customeramc WHERE slno = '".$lastslno."'";
		$result = runmysqlquery($query);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','48','".date('Y-m-d').' '.date('H:i:s')."')";
		$eventresult = runmysqlquery($eventquery);
		$responsearray['errormessage'] = "2^"."Customer AMC Record Deleted Successfully";
		///echo("2^"."Customer AMC Record Deleted Successfully");
		echo(json_encode($responsearray));
	}
	break;
	case 'getcustomercount':
	{
		$responsearray3 = array();
		$query = "SELECT slno,businessname,customerid FROM inv_mas_customer ORDER BY businessname";
		$result = runmysqlquery($query);
		$count = mysqli_num_rows($result);
		$responsearray3['count'] = $count;
		echo(json_encode($responsearray3));
	}
	break;
	case 'generatecustomerlist':
	{
		$limit = $_POST['limit'];
		$startindex = $_POST['startindex'];
		$generatecustomerlistarray = array();
		$query = "SELECT slno,businessname,customerid FROM inv_mas_customer ORDER BY businessname LIMIT ".$startindex.",".$limit.";";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$generatecustomerlistarray[$count] = $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
		echo(json_encode($generatecustomerlistarray));
	}
	break;
	
	case 'generategrid':
	{
		$lastslno = $_POST['lastslno'];
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$resultcount = "SELECT inv_customeramc.slno,inv_mas_customer.businessname,inv_mas_product.productname,inv_customeramc.startdate,
inv_customeramc.enddate,inv_customeramc.remarks,inv_customeramc.createddate ,inv_customeramc.billdate,
inv_mas_users.fullname FROM inv_customeramc LEFT JOIN inv_mas_users ON inv_mas_users.slno = inv_customeramc.createdby 
LEFT JOIN inv_mas_customer ON inv_mas_customer.slno = inv_customeramc.customerreference  
LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_customeramc.productcode 
WHERE inv_customeramc.customerreference = '".$lastslno."' ORDER BY slno";
		$fetch10 = runmysqlquery($resultcount);
		$fetchresultcount = mysqli_num_rows($fetch10);
		if($showtype == 'all')
		$limit = 100000;
		else
		$limit = 10;
		if($startlimit == '')
		{
			$startlimit = 0;
			$slno = 0;
		}
		else
		{
			$startlimit = $slno ;
			$slno = $slno;
		}

		$query = "SELECT inv_customeramc.slno,inv_mas_customer.businessname,inv_mas_product.productname,inv_customeramc.startdate,
inv_customeramc.enddate,inv_customeramc.remarks,inv_customeramc.createddate ,inv_customeramc.billdate,
inv_mas_users.fullname FROM inv_customeramc LEFT JOIN inv_mas_users ON inv_mas_users.slno = inv_customeramc.createdby 
LEFT JOIN inv_mas_customer ON inv_mas_customer.slno = inv_customeramc.customerreference  
LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_customeramc.productcode 
WHERE inv_customeramc.customerreference = '".$lastslno."' ORDER BY slno LIMIT ".$startlimit.",".$limit.";";
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Start Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">End Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td><td nowrap = "nowrap" class="td-border-grid" align="left">Created Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Bill Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">User</td></tr>';
		}
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_row($result))
		{
			$i_n++;
			$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow" bgcolor='.$color.' onclick="gridtoform(\''.$fetch[0].'\',\''.$lastslno.'\')">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slno."</td>";
			for($i = 1; $i < count($fetch); $i++)
			{
				if($i == 3 || $i == 4 || $i == 6)
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch[$i])."</td>";
				else
				if($i == 7)
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch[$i])."</td>";
				else
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch[$i])."</td>";
				
				
			}
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
			if($slno >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="generatemoredatagrid(\''.$startlimit.'\',\''.$slno.'\',\'more\');" style="cursor:pointer">Show More Records >></a><a onclick ="generatemoredatagrid(\''.$startlimit.'\',\''.$slno.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			echo $grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$query;	
	}
	break;
	
	case 'gridtoform':
	{
		$gridtoformarray = array();
		$customerreference = $_POST['customerreference'];
		$amclastslno = $_POST['amclastslno'];
		$query1 = "SELECT count(*) as count from inv_customeramc where customerreference = '".$customerreference."'";
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)
		{
			$query = "SELECT inv_customeramc.startdate,inv_customeramc.slno,inv_customeramc.customerreference,
inv_customeramc.productcode,inv_customeramc.startdate,inv_customeramc.enddate,inv_customeramc.remarks,inv_customeramc.createddate, inv_mas_users.fullname , inv_customeramc.billno, inv_customeramc.billamount, inv_customeramc.billdate, inv_mas_customer.businessname FROM inv_customeramc LEFT JOIN inv_mas_users On inv_customeramc.createdby = inv_mas_users.slno  
left join inv_mas_customer on inv_mas_customer.slno = inv_customeramc.customerreference  WHERE inv_customeramc.customerreference = '".$customerreference."' AND inv_customeramc.slno = '".$amclastslno."';";
			$fetch = runmysqlqueryfetch($query);
			$startdate = $fetch['startdate']; 
			$enddate = $fetch['enddate']; 
			$todays_date = date("Y-m-d"); 
			$today = strtotime($todays_date); 
			$expiration_date1 = strtotime($startdate); 
			$expiration_date2 = strtotime($enddate); 
			if ($expiration_date1 > $today) { $msg = "Future"; } 
			elseif($expiration_date2 < $today) { $msg = "Expired"; }
			else { $msg = "Active"; }
			$gridtoformarray['slno'] = $fetch['slno'];
			$gridtoformarray['customerreference'] = $fetch['customerreference'];
			$gridtoformarray['productcode'] = $fetch['productcode'];
			$gridtoformarray['startdate'] = changedateformat($fetch['startdate']);
			$gridtoformarray['enddate'] = changedateformat($fetch['enddate']);
			$gridtoformarray['remarks'] = $fetch['remarks'];
			$gridtoformarray['createddate'] = changedateformatwithtime($fetch['createddate']);
			$gridtoformarray['fullname'] = $fetch['fullname'];
			$gridtoformarray['msg'] = $msg;
			$gridtoformarray['userid'] = $userid;
			$gridtoformarray['billno'] = $fetch['billno'];
			$gridtoformarray['billamount'] = $fetch['billamount'];
			$gridtoformarray['billdate'] = $fetch['billdate'];
			//$gridtoformarray['billamount'] = $fetch['billamount'];
			$gridtoformarray['expiration_date1'] = $expiration_date1;
			$gridtoformarray['expiration_date2'] = $expiration_date2;
			$gridtoformarray['today'] = $today;
			//$gridtoformarray['today'] = $fetch['businessname'];
			$gridtoformarray['businessname'] = $fetch['businessname'];
			
			echo($fetch['slno'].'^'.$fetch['customerreference'].'^'.$fetch['productcode'].'^'.changedateformat($fetch['startdate']).'^'.changedateformat($fetch['enddate']).'^'.$fetch['remarks'].'^'.changedateformatwithtime($fetch['createddate']).'^'.$fetch['fullname'].'^'.$msg.'^'.$userid.'^'.$fetch['billno'].'^'.$fetch['billamount'].'^'.changedateformat($fetch['billdate']).'^'.$expiration_date1.'^'.$expiration_date2.'^'.$today.'^'.$fetch['businessname']);
		}
		else
		{
			$gridtoformarray['slno'] = '';
			$gridtoformarray['customerreference'] = '';
			$gridtoformarray['productcode'] = '';
			$gridtoformarray['startdate'] = '';
			$gridtoformarray['enddate'] = '';
			$gridtoformarray['remarks'] = '';
			$gridtoformarray['createddate'] = '';
			$gridtoformarray['fullname'] = '';
			$gridtoformarray['msg'] = '';
			$gridtoformarray['userid'] = '';
			$gridtoformarray['billno'] = '';
			$gridtoformarray['billamount'] = '';
			$gridtoformarray['billdate'] = '';
			//$gridtoformarray['billamount'] = '';
			$gridtoformarray['expiration_date1'] = '';
			$gridtoformarray['expiration_date2'] = '';
			$gridtoformarray['today'] = '';
			//$gridtoformarray['today'] = '';
			$gridtoformarray['businessname'] = '';
			echo(json_encode($gridtoformarray));
		}
	}
	break;
	
	case 'displaycustomer':
	{
		$grid = '';
		$customerreference = $_POST['customerreference'];
		$query = "SELECT businessname from inv_mas_customer where slno = '".$customerreference."';";
		$fetch2 = runmysqlqueryfetch($query);
		$query = "select distinct inv_mas_product.productname,inv_mas_product.productcode from inv_customerproduct left join inv_mas_product on left(inv_customerproduct.computerid,3) = inv_mas_product.productcode  where 
customerreference = '".$customerreference."' and reregistration = 'no';";
			$result = runmysqlquery($query);
			$grid .= '<option value="">Make A Selection</option>';
			while($fetch = mysqli_fetch_array($result))
			{
					$grid .= '<option value="'.$fetch['productcode'].'">'.$fetch['productname'].'</option>';
			}
			echo(json_encode($grid.'^'.$fetch2['businessname'].'^'.$customerreference));
	}
	break;
	
	case 'searchbycontractid':
	{
		$searchbycontractidarray = array();
		$contractid = $_POST['contractid'];
		$query1 = "SELECT count(*) as count from inv_customeramc where slno = '".$contractid."'";
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)
		{
			$query = "SELECT inv_customeramc.slno,inv_customeramc.customerreference, inv_mas_product.productname, inv_mas_product.productcode, inv_customeramc.startdate,inv_customeramc.enddate,inv_customeramc.remarks,inv_customeramc.createddate, inv_customeramc.billno,inv_customeramc.billamount,inv_mas_users.fullname,inv_customeramc.billdate FROM inv_customeramc LEFT JOIN inv_mas_users On inv_customeramc.createdby = inv_mas_users.slno left join inv_mas_product on inv_customeramc.productcode = inv_mas_product.productcode WHERE inv_customeramc.slno = '".$contractid."';";
			$fetch = runmysqlqueryfetch($query);
			$query1 = "SELECT businessname from inv_mas_customer where slno = '".$fetch['customerreference']."';";
			$fetch1 = runmysqlqueryfetch($query1);
			$startdate = $fetch['startdate']; 
			$enddate = $fetch['enddate']; 
			$todays_date = date("Y-m-d"); 
			$product = '<option value="'.$fetch['productcode'].'">'.$fetch['productname'].'</option>';
			$today = strtotime($todays_date); 
			$expiration_date1 = strtotime($startdate); 
			$expiration_date2 = strtotime($enddate); 
			if ($expiration_date1 > $today) { $msg = "Future"; } 
			elseif($expiration_date2 < $today) { $msg = "Expired"; }
			else { $msg = "Active"; }
			
			$searchbycontractidarray['slno'] = $fetch['slno'];
			$searchbycontractidarray['businessname'] = $fetch['businessname'];
			$searchbycontractidarray['product'] = $product;
			$searchbycontractidarray['startdate'] = changedateformat($fetch['startdate']);
			$searchbycontractidarray['enddate'] = changedateformat($fetch['enddate']);
			$searchbycontractidarray['remarks'] = $fetch['remarks'];
			$searchbycontractidarray['createddate'] = changedateformat($fetch['createddate']);
			$searchbycontractidarray['fullname'] = $fetch['fullname'];
			$searchbycontractidarray['msg'] = $msg;
			$searchbycontractidarray['userid'] = $userid;
			$searchbycontractidarray['customerreference'] = $fetch['customerreference'];
			$searchbycontractidarray['billno'] = $fetch['billno'];
			$searchbycontractidarray['billamount'] = $fetch['billamount'];
			$searchbycontractidarray['billdate'] = changedateformat($fetch['billdate']);
			
			//echo($fetch['slno'].'^'.$fetch1['businessname'].'^'.$product.'^'.changedateformat($fetch['startdate']).'^'.changedateformat($fetch['enddate']).'^'.$fetch['remarks'].'^'.changedateformat($fetch['createddate']).'^'.$fetch['fullname'].'^'.$msg.'^'.$userid.'^'.$fetch['customerreference'].'^'.$fetch['billno'].'^'.$fetch['billamount'].'^'.changedateformat($fetch['billdate']));
		}
		else
		{
			$searchbycontractidarray['slno'] = '';
			$searchbycontractidarray['businessname'] ='';
			$searchbycontractidarray['product'] ='';
			$searchbycontractidarray['startdate'] = '';
			$searchbycontractidarray['enddate'] ='';
			$searchbycontractidarray['remarks'] = '';
			$searchbycontractidarray['createddate'] = '';
			$searchbycontractidarray['fullname'] = '';
			$searchbycontractidarray['msg'] = '';
			$searchbycontractidarray['userid'] = '';
			$searchbycontractidarray['customerreference'] = '';
			$searchbycontractidarray['billno'] = '';
			$searchbycontractidarray['billamount'] = '';
			$searchbycontractidarray['billdate'] = '';
		}
		echo(json_encode($searchbycontractidarray));
	}
	break;
}
?>