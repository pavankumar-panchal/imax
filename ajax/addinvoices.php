<?php
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

$query = "select * from inv_mas_users where slno = '".$userid."';";
$resultfetch = runmysqlqueryfetch($query);
$username = $resultfetch['fullname'];

$lastslno = $_POST['lastslno'];
$switchtype = $_POST['switchtype'];
include('../inc/checkpermission.php');

switch($switchtype)
{
	case 'generatecustomerlist':
	{
		$limit = $_POST['limit'];
		$startindex = $_POST['startindex'];
		$customerarray = array();
		$query = "SELECT slno,businessname,customerid FROM inv_mas_customer ORDER BY businessname  LIMIT ".$startindex.",".$limit.";";
		$result = runmysqlquery($query);
		$grid = '';
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$customerarray[$count] = $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
		//echo($grid);
		echo(json_encode($customerarray));
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
	case 'customerdetailstoform':
	{
		$customerdetailstoformarray = array();
		$query = "select inv_mas_customer.slno as slno, inv_mas_customer.businessname as companyname,inv_mas_customer.place,inv_mas_customer.address,inv_mas_region.category as region,inv_mas_branch.branchname as branch	,inv_mas_customercategory.businesstype as businesstype,inv_mas_customertype.customertype as customertype,inv_mas_dealer.businessname as dealername,inv_mas_customer.stdcode, inv_mas_customer.pincode,inv_mas_district.districtname, inv_mas_state.statename,inv_mas_customer.customerid  from inv_mas_customer left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_customer.slno = '".$lastslno."';";
		$fetch = runmysqlqueryfetch($query);
		
		// Fetch Contact Details
		
		$querycontactdetails = "select  phone,cell, emailid,contactperson from inv_contactdetails where customerid = '".$lastslno."'";
		$resultcontactdetails = runmysqlquery($querycontactdetails);
		// contact Details
		$contactvalues = '';
		$phoneres = '';
		$cellres = '';
		$emailidres = '';
				
		while($fetchcontactdetails = mysqli_fetch_array($resultcontactdetails))
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
		$customerid = ($fetch['customerid'] == '')?'':cusidcombine($fetch['customerid']);	
		$pincode = ($fetch['pincode'] == '')?'':' Pin - '.$fetch['pincode'];
		$address = $fetch['address'].', '.$fetch['districtname'].', '.$fetch['statename'].$pincode;
		$contactperson = trim($contactvalues,',');
		$phonenumber = explode(',',trim($phoneres,','));
		$phone = $phonenumber[0];
		$cellnumber = explode(',',trim($cellres,','));
		$cell = $cellnumber[0];
		$emailid = trim($emailidres,',');
		$emailidplit = explode(',', $emailid);
		
		$customerdetailstoformarray['errorcode'] = '1';	
		$customerdetailstoformarray['slno'] = $fetch['slno'];
		$customerdetailstoformarray['customerid'] = $customerid;
		$customerdetailstoformarray['companyname'] = $fetch['companyname'];
		$customerdetailstoformarray['contactvalues'] = trim($contactvalues,',');
		$customerdetailstoformarray['address'] = $address;
		$customerdetailstoformarray['phone'] = $phone;
		$customerdetailstoformarray['cell'] = $cell;
		$customerdetailstoformarray['emailidplit'] = $emailidplit[0];
		$customerdetailstoformarray['region'] = $fetch['region'];
		$customerdetailstoformarray['branch'] = $fetch['branch'];
		$customerdetailstoformarray['businesstype'] = $fetch['businesstype'];
		$customerdetailstoformarray['customertype'] = $fetch['customertype'];
		$customerdetailstoformarray['dealername'] = $fetch['dealername'];

		echo(json_encode($customerdetailstoformarray));		//echo('1^'.$fetch['slno'].'^'.$customerid.'^'.$fetch['companyname'].'^'.trim($contactvalues,',').'^'.$address.'^'.$phone.'^'.$cell.'^'.$emailidplit[0].'^'.$fetch['region'].'^'.$fetch['branch'].'^'.$fetch['businesstype'].'^'.$fetch['customertype'].'^'.$fetch['dealername'].'^'.$query2);
	}
	break;
	case 'searchbycustomerid':
	{
		$searchbycustomeridarray = array();
		$customerid = $_POST['customerid'];
		$customeridlen = strlen($customerid);
		$customerid = ($_POST['customerid'] == 5)?($_POST['customerid']):(substr($customerid, $customeridlen - 5));
		if($customeridlen == 5)
		{
		  $query1 = "SELECT count(*) as count from inv_mas_customer where slno = '".$customerid."'";
		  $fetch1 = runmysqlqueryfetch($query1);
		  
		  if($fetch1['count'] > 0)
		  {
			$query = "SELECT inv_mas_customer.slno, inv_mas_customer.customerid, inv_mas_customer.businessname,  inv_mas_customer.address, inv_mas_customer.place, inv_mas_customer.district,inv_mas_district.statecode as state, inv_mas_customer.pincode, inv_mas_customer.fax, inv_mas_customer.region, inv_mas_customer.stdcode,  inv_mas_customer.activecustomer, inv_mas_customer.website, inv_mas_customer.category, inv_mas_customer.type, inv_mas_customer.remarks, inv_mas_customer.currentdealer,  inv_mas_customer.initialpassword as password, inv_mas_customer.passwordchanged, inv_mas_customer.disablelogin, inv_mas_customer.corporateorder, inv_mas_customer.createddate,inv_mas_users.fullname, inv_mas_product.productname, inv_mas_district.districtname as districtname,inv_mas_state.statename as statename  FROM inv_mas_customer LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_mas_customer.firstproduct  LEFT JOIN  inv_mas_users ON  inv_mas_users.slno = inv_mas_customer.createdby left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode  left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_customer.slno = '".$customerid."'";
			$fetch = runmysqlqueryfetch($query);
			
			// Fetch Contact Details 
			$querycontactdetails = "select phone,cell,emailid,contactperson from inv_contactdetails where customerid = '".$customerid."'";
			$resultcontactdetails = runmysqlquery($querycontactdetails);
			
			// contact Details
			$contactvalues = '';
			$phoneres = '';
			$cellres = '';
			$emailidres = '';
					
			while($fetchcontactdetails = mysqli_fetch_array($resultcontactdetails))
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
			$phonenumber = explode(',', trim($phoneres,','));
			$phone = $phonenumber[0];
			$cellnumber = explode(',', trim($cellres,','));
			$cell = $cellnumber[0];
			$emailid = explode(',', trim($emailidres,','));
			$emailidplit = $emailid[0];
			
			$customerid = ($fetch['customerid'] == '')?'':cusidcombine($fetch['customerid']);	
			if($fetch['currentdealer'] == '')
				$currentdealer = '';
			else
			{
				$query = "select * from inv_mas_dealer where slno = '".$fetch['currentdealer']."'";
				$resultfetch = runmysqlqueryfetch($query);
				$currentdealer = $resultfetch['businessname'];
			}
			
			$searchbycustomeridarray['errorcode'] = '1';
			$searchbycustomeridarray['slno'] = $fetch['slno'];
			$searchbycustomeridarray['customerid'] =cusidcombine( $fetch['customerid']);
			$searchbycustomeridarray['businessname'] = $fetch['businessname'];
			$searchbycustomeridarray['contactvalues'] = $contactvalues;
			$searchbycustomeridarray['address'] = $fetch['address'];
			$searchbycustomeridarray['district'] = $fetch['district'];
			$searchbycustomeridarray['state'] = $fetch['state'];
			$searchbycustomeridarray['pincode'] = $fetch['pincode'];
			$searchbycustomeridarray['stdcode'] = $fetch['stdcode'];
			$searchbycustomeridarray['phone'] = $phone;
			$searchbycustomeridarray['cell'] = $cell;
			$searchbycustomeridarray['emailidplit'] = $emailidplit;
			$searchbycustomeridarray['website'] = $fetch['website'];
			$searchbycustomeridarray['category'] = $fetch['category'];
			$searchbycustomeridarray['type'] = $fetch['type'];
			$searchbycustomeridarray['remarks'] = $fetch['remarks'];
			$searchbycustomeridarray['currentdealer'] = $currentdealer;
			$searchbycustomeridarray['disablelogin'] = $fetch['disablelogin'];
			$searchbycustomeridarray['createddate'] = changedateformatwithtime($fetch['createddate']);
			$searchbycustomeridarray['corporateorder'] = strtolower($fetch['corporateorder']);			
			$searchbycustomeridarray['fax'] = $fetch['fax'];				
			$searchbycustomeridarray['userid'] = $userid;				
			$searchbycustomeridarray['activecustomer'] = $fetch['activecustomer'];
			$searchbycustomeridarray['districtname'] = $fetch['districtname'];
			$searchbycustomeridarray['statename'] = $fetch['statename'];
			$searchbycustomeridarray['password'] = $fetch['password'];
			$searchbycustomeridarray['passwordchanged'] = strtolower($fetch['passwordchanged']);
		  }
		  else
		  {
			  $searchbycustomeridarray['customerid'] = '';
		  }
			//	echo('1^'.$fetch['slno'].'^'.$customerid.'^'.$fetch['businessname'].'^'.trim($contactvalues,',').'^'.$fetch['address'].'^'.$fetch['place'].'^'.$fetch['district'].'^'.$fetch['state'].'^'.$fetch['pincode'].'^'.$fetch['stdcode'].'^'.$phone.'^'.$cell.'^'.$emailidplit.'^'.$fetch['website'].'^'.$fetch['category'].'^'.$fetch['type'].'^'.$fetch['remarks'].'^'.$currentdealer.'^'.$fetch['disablelogin'].'^'.changedateformatwithtime($fetch['createddate']).'^'.strtolower($fetch['corporateorder']).'^'.$fetch['fax'].'^'.$userid.'^'.''.'^'.$fetch['activecustomer'].'^'.$fetch['districtname'].'^'.$fetch['statename'].'^'.$fetch['password'].'^'.strtolower($fetch['passwordchanged']));
		}

			echo(json_encode($searchbycustomeridarray));

	}
	break;
	case 'calculateamount':
	{
		$selectedcookievalue = $_POST['selectedcookievalue'];
		$selectedcookievaluesplit = explode('#',$selectedcookievalue);
		$pricingtype = removedoublecomma($_POST['pricingtype']);
		$purchasevalues = removedoublecomma($_POST['purchasevalues']);
		$usagevalues = removedoublecomma($_POST['usagevalues']);
		$productamountvalues = removedoublecomma($_POST['productamountvalues']);
		$descriptiontypevalues = removedoublecomma($_POST['descriptiontypevalues']);
		$descriptionvalues = removedoublecomma($_POST['descriptionvalues']);
		$descriptionamountvalues = removedoublecomma($_POST['descriptionamountvalues']);
		$productquantityvalues = removedoublecomma($_POST['productquantityvalues']);
		$purchasevaluesplit = explode(',',$purchasevalues);
		$usagevaluesplit = explode(',',$usagevalues);
		$productamountsplit = explode(',',$productamountvalues);
		$productquantitysplit = explode(',',$productquantityvalues);
		$descriptionamountvaluesnew = str_replace(',','~',$descriptionamountvalues);
		$descriptiontypevaluesnew = str_replace(',','~',$descriptiontypevalues);
		$descamt = getdescriptionamount($descriptionamountvaluesnew,$descriptiontypevaluesnew);
		$seztaxtype = $_POST['seztaxtype'];
		switch($pricingtype)
		{
			case 'normal':
			{
				$productcount = count($productamountsplit);
				$totalamount = 0;
				$servicetax = 0;
				$netamount = 0;
				for($i=0;$i<$productcount; $i++)
				{
					$totalamount += $productamountsplit[$i];
				}
				$totalamount = $totalamount + $descamt;
				// edited by bhavesh $servicetax = roundnearest($totalamount * 0.103);
				if($seztaxtype == 'yes')
					$servicetax = 0;
				else
					$servicetax = roundnearest($totalamount * 0.1236); 
				$netamount = $totalamount + $servicetax;
				$amount = '1^'.$totalamount.'^'.$servicetax.'^'.$netamount.'^'.$descamt;
			}
			break;
		}
		echo(json_encode($amount.'^'.count($selectedcookievaluesplit)));
	}
	break;
	case 'invoicedetailsgrid':
	{
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$lastslno = $_POST['lastslno'];
		$resultcount = "select inv_invoicenumbers.slno as invoicenumber,receiptamount, inv_invoicenumbers.netamount,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.contactperson,inv_invoicenumbers.description,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createddate,inv_invoicenumbers.createdby ,inv_invoicenumbers.amount,inv_invoicenumbers.servicetax,inv_invoicenumbers.netamount,inv_invoicenumbers.purchasetype ,dealer_online_purchase.slno as invoiceslno,inv_invoicenumbers.seztaxtype as seztaxtype,inv_invoicenumbers.seztaxfilepath as seztaxfilepath from inv_invoicenumbers left join (select sum(receiptamount) as receiptamount,invoiceno as invoiceno from inv_mas_receipt group by invoiceno)inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno
left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno
where right(customerid,5) ='".$lastslno."' order by createddate  desc; ";
		$resultfetch = runmysqlquery($resultcount);
		$fetchresultcount = mysqli_num_rows($resultfetch);
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
		$query = "select inv_invoicenumbers.slno as invoicenumber ,receiptamount, inv_invoicenumbers.netamount,inv_invoicenumbers.customerid,inv_invoicenumbers.businessname,inv_invoicenumbers.contactperson,inv_invoicenumbers.description,inv_invoicenumbers.invoiceno,inv_invoicenumbers.dealername,inv_invoicenumbers.createddate,inv_invoicenumbers.createdby ,inv_invoicenumbers.amount,inv_invoicenumbers.servicetax,inv_invoicenumbers.netamount,inv_invoicenumbers.purchasetype ,dealer_online_purchase.slno as invoiceslno,inv_invoicenumbers.status,inv_invoicenumbers.seztaxtype as seztaxtype,inv_invoicenumbers.seztaxfilepath as seztaxfilepath from inv_invoicenumbers left join (select sum(receiptamount) as receiptamount,invoiceno as invoiceno from inv_mas_receipt group by invoiceno)inv_mas_receipt on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno
left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno
where right(customerid,5) = '".$lastslno."' order by createddate  desc LIMIT ".$startlimit.",".$limit."; ";
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Payment</td><td nowrap = "nowrap" class="td-border-grid" align="left">Status</td><td nowrap = "nowrap" class="td-border-grid" align="left">Generated By</td><td nowrap = "nowrap" class="td-border-grid" align="left">Action<input type="hidden" name="invoicelastslno" id="invoicelastslno" /><input type="hidden" name="filepathinvoicing" id="filepathinvoicing" /></td><td nowrap = "nowrap" class="td-border-grid" align="left">Email</td><td nowrap = "nowrap" class="td-border-grid" align="left">SEZ Download</td></tr>';
		}
		
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$i_n++;
			$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow1" bgcolor='.$color.'>';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slno."</td> ";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch['createddate'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['invoiceno']."</td>";

			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['netamount'].$paynow."</td>";
			if($fetch['receiptamount'] == '' || $fetch['receiptamount'] < $fetch['netamount'])
			{
				if($fetch['status'] == 'CANCELLED')
				{
					$grid .= '<td  nowrap="nowrap" class="td-border-grid" align="center"><span class="redtext">CANCELLED</span></td>';
				}
				else
				{
					$grid .= '<td  nowrap="nowrap" class="td-border-grid" align="center">'.getpaymentstatus($fetch['receiptamount'],$fetch['netamount']).'&nbsp;<span class="resendtext" style "cursor:pointer" onclick = "paynow(\''.$fetch['invoicenumber'].'\');">(Pay Now)</span></td>';
				}
			}
			else
			{
				if($fetch['status'] == 'CANCELLED')
				{
					$grid .= '<td  nowrap="nowrap" class="td-border-grid" align="center"><span class="redtext">CANCELLED</span></td>';
				}
				else
				{
					$grid .= "<td  nowrap='nowrap' class='td-border-grid' align='center'>".getpaymentstatus($fetch['receiptamount'],$fetch['netamount'])."&nbsp;</td>";
				}
			}
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['status']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['createdby']."</td>";
			$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewinvoice(\''.$fetch['invoicenumber'].'\');" class="resendtext" style = "cursor:pointer"> View >></a> </td>';
			$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"><div id= "resendemail" style ="display:block;"> <a onclick="resendinvoice(\''.$fetch['invoicenumber'].'\');" class="resendtext" style = "cursor:pointer"> Resend </a> </div><div id ="resendprocess" style ="display:none;"></div></td>';
			if($fetch['seztaxtype'] == 'yes')
			{
				$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"><img src="../images/sez_download.gif" width="15" height="15" border="0" align="absmiddle" style="cursor:pointer" alt="Download" title="Download" onclick ="viewdownloadpath(\''.$fetch['seztaxfilepath'].'\')"/></td>';
			}
			else
			{
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>Not Avaliable</td>";

			}
			$grid .= "</tr>";
		}
		$grid .= "</table>";

		$fetchcount = mysqli_num_rows($result);
		if($slno >= $fetchresultcount)
		$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
		$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="getmoreinvoicedetails(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'more\');" style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmoreinvoicedetails(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'all\');" class ="resendtext1" style="cursor:pointer"><font color= "#000000">(Show All Records)</font></a></div></td></tr></table>';
	
		echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$invoicenosplit[2];
	}
	break;
	case 'getdealerdetails':
	{
		$dealerid = $_POST['dealerid'];
		$query = "select inv_mas_dealer.slno,inv_mas_dealer.businessname,inv_mas_dealer.contactperson,inv_mas_dealer.phone,inv_mas_dealer.cell,inv_mas_dealer.emailid,inv_mas_dealer.place,inv_mas_district.districtname,inv_mas_state.statename from inv_mas_dealer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_dealer.district
		left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_dealer.slno = '".$dealerid."'";
		$fetch = runmysqlqueryfetch($query);
		$grid = '<table width="100%" border="0" cellspacing="0" cellpadding="4">';
		$grid .= '<tr>';
        $grid .= '<td colspan="2"><div align="center" style="color:#006699; font-size:17px; font-weight:bold">Contact Details</div></td>';
        $grid .= '</tr>';
		
  		$grid .= '<tr>';
        $grid .= '<td width="45%"><strong>Contact Person: </strong></td>';
        $grid .= '<td width="55%" id="displaydealercontactperson" >'.$fetch['contactperson'].'</td>';
        $grid .= '</tr>';
        $grid .= '<tr>';
        $grid .= '<td valign="top"><strong>Phone: </strong></td>';
        $grid .= '<td  id="displaydealerphone" >'.$fetch['phone'].'</td>';
        $grid .= '</tr>';
        $grid .= '<tr>';
       	$grid .= '<td><strong>Cell: </strong></td>';
        $grid .= '<td id="displaydealercell" >'.$fetch['cell'].'</td>';
       	$grid .= '</tr>';
        $grid .= '<tr>';
        $grid .= '<td ><strong>Email ID: </strong></td>';
        $grid .= '<td id="displaydealeremailid">'.$fetch['emailid'].'</td>';
        $grid .= '</tr>';
        $grid .= '<tr>';
        $grid .= ' <td ><strong>Place:</strong></td>';
        $grid .= ' <td id="displaydealerplace">'.$fetch['place'].'</td>';
        $grid .= '</tr>';
        $grid .= '<tr>';
        $grid .= '<td><strong>District:</strong></td>';
        $grid .= '<td id="displaydealerdistrict">'.$fetch['districtname'].'</td>';
        $grid .= '</tr>';
        $grid .= '<tr>';
        $grid .= '<td><strong>State:</strong></td>';
        $grid .= '<td id="displaydealerstate">'.$fetch['statename'].'</td>';
        $grid .= '</tr>';
		$grid .= '<tr>';
        $grid .= '<td></td>';
        $grid .= '<td align="right" ><input type="button" value="Close" id="closecolorboxbutton"  onclick="$().colorbox.close();" class="swiftchoicebutton"/></td>';
        $grid .= '</tr>';
		$grid .= '</table>';
		/*echo('1^'.$fetch['slno'].'^'.$fetch['businessname'].'^'.$fetch['contactperson'].'^'.$fetch['phone'].'^'.$fetch['cell'].'^'.$fetch['emailid'].'^'.$fetch['place'].'^'.$fetch['districtname'].'^'.$fetch['statename']);*/
		
		echo('1^'.$fetch['slno'].'^'.$fetch['businessname'].'^'.$grid);
	}
	break;
	case 'resendinvoice':
	{
		$invoiceno = $_POST['invoiceno'];
		$sent = resendinvoice($invoiceno);
		echo(json_encode($sent));
		
	}
	break;
	case 'searchbyinvoiceno':
	{
		$invoiceno = strtolower($_POST['invoiceno']);
		$query = "select distinct inv_mas_customer.slno,inv_mas_customer.businessname from inv_invoicenumbers left join inv_mas_customer on inv_mas_customer.slno = right(inv_invoicenumbers.customerid,5) where invoiceno like '%".$invoiceno."%' and inv_mas_customer.slno is not null order  by inv_mas_customer.businessname";
		$result = runmysqlquery($query);
		if(mysqli_num_rows($result) == 0)
		{
			echo(json_encode('2#@# Invalid Invoice No'));
		}
		else
		{
			$grid = '';
			$count = 1;
			while($fetch = mysqli_fetch_array($result))
			{
				if($count > 1)
					$grid .='^*^';
				$grid .= $fetch['businessname'].'^'.$fetch['slno'];
				$count++;
			}
			echo(json_encode('1#@#'.$grid));
		}
	}
	break;
	case 'proceedforpurchase':
	{
	
		$lastslno = $_POST['lastslno'];
		$selectedcookievalue = $_POST['selectedcookievalue'];
		$selectedcookievaluesplit = explode('#',$selectedcookievalue);
		$pricingtype = $_POST['pricingtype'];
		$dealerid = $_POST['dealerid'];
		$servicetaxamount = $_POST['servicetaxamount'];
		$purchasevalues = removedoublecomma(trim($_POST['purchasevalues'],','));
		$usagevalues = removedoublecomma(trim($_POST['usagevalues'],','));
		$productamountvalues = removedoublecomma(trim($_POST['productamountvalues'],','));
		$descriptiontypevalues = removedoublecomma(trim($_POST['descriptiontypevalues'],','));
		$descriptionvalues = removedoublecomma(trim($_POST['descriptionvalues'],','));
		$descriptionamountvalues = removedoublecomma(trim($_POST['descriptionamountvalues'],','));
		$productquantityvalues = removedoublecomma(trim($_POST['productquantityvalues'],','));
		$cardlistvalues = removedoublecomma(trim($_POST['cardlist'],','));
		$invoicetime =$_POST['invoicetime'];
		$invoicedate =$_POST['invoicedate'];
		$generatedby = $_POST['generatedby'];
		$invoiceremarks = $_POST['invoiceremarks'];
		$paymentremarks = $_POST['paymentremarks'];
		$offeramount = $_POST['offeramount'];
		$offerremarks = $_POST['offerremarks'];
		$inclusivetaxamount = $_POST['inclusivetaxamount'];
		$paymenttype = $_POST['paymenttype'];
		$servicelist = $_POST['servicelist'];
		$serviceamountvalues = $_POST['serviceamountvalues'];
		$paymenttypeselected = $_POST['paymenttypeselected'];
		$paymentmode = $_POST['paymentmode'];
		$chequedate = changedateformat($_POST['chequedate']);
		$chequeno = $_POST['chequeno'];
		$drawnon = $_POST['drawnon'];
		$duedate = changedateformat($_POST['duedate']);
		$paymentamount = $_POST['paymentamount'];
		$depositdate = changedateformat($_POST['depositdate']);
		$purchasevaluesplit = explode(',',$purchasevalues);
		$usagevaluesplit = explode(',',$usagevalues);
		$productamountsplit = explode(',',$productamountvalues);
		$productquantitysplit = explode(',',$productquantityvalues);
		$servicelistsplit = explode('#',$servicelist);
		$serviceamountvaluessplit = explode('~',$serviceamountvalues);
		$descriptionamountvaluessplit = explode('~',$descriptionamountvalues);
		$descriptionvaluesplit = explode('~',$descriptionvalues);
		$descriptiontypevaluessplit = explode('~',$descriptiontypevalues);
		$cusname = $_POST['cusname'];
		$cuscontactperson = $_POST['cuscontactperson'];
		$cusaddress = $_POST['cusaddress'];
		$cusemail = $_POST['cusemail'];
		$cusphone = $_POST['cusphone'];
		$cuscell = $_POST['cuscell'];
		$customertype = $_POST['custype'];
		$businesstype = $_POST['cuscategory'];
		$invoicedated = $_POST['invoicedated'];
		$servicelistvalues = $_POST['servicelistvalues'];
		$podate = $_POST['podate'];
		$poreference = $_POST['poreference'];
		$privatenote = $_POST['privatenote'];
		$servicelistvaluessplit = explode('#',$servicelistvalues);
		$itemleveldescriptionvalues = $_POST['itemleveldescription'];
		$itemleveldescriptionlistsplit = explode('#',$itemleveldescriptionvalues);
		$productleveldescriptionvalues = $_POST['productleveldescription'];
		$productleveldescriptionlistsplit = explode('#',$productleveldescriptionlist);
		$seztaxtype = $_POST['seztaxtype'];
		$seztaxfilepath = $_POST['seztaxfilepath'];
		$invoicenoformat = $_POST['invoiceno'];
		$invoicedateformat = changedateformat($invoicedate).' '.$invoicetime;
		$generatedbysplit = explode('^',$generatedby);
		$modulecheckedvalue = $generatedbysplit[1];
		$generatedbyvalue = $generatedbysplit[0];
		
		//check for invoiceno exists
		$query3333 = "select * from inv_invoicenumbers where invoiceno = '".$invoicenoformat."'";
		$resukt3333 = runmysqlquery($query3333);
		if(mysqli_num_rows($resukt3333) == 0)
		{
			if($modulecheckedvalue == '[D]')
			{
				
				$module = 'dealer_module';
				$query = "select * from inv_mas_dealer where slno = '".$generatedbyvalue."';";
				$resultfetch = runmysqlqueryfetch($query);
				$createdbyusername = $resultfetch['businessname'];
			}
			else
			{
				$module = 'user_module';
				$query = "select * from inv_mas_users where slno = '".$generatedbyvalue."';";
				$resultfetch = runmysqlqueryfetch($query);
				$createdbyusername = $resultfetch['fullname'];
			}
	
			if($seztaxtype == 'yes')
			{
				$seztaxtype1 = $seztaxtype;
				$seztaxfilepath1 = $seztaxfilepath;
				$seztaxdate1 = $invoicedateformat;
				$seztaxattachedby1 = $userid;
			}
			else
			{
				$seztaxtype1 = $seztaxtype;
				$seztaxfilepath1 = '';
				$seztaxdate1 = '';
				$seztaxattachedby1 = '';
			}
			
	
			
			//server side check for product amount field
			for($i=1;$i<count($productamountsplit);$i++)
			{
				if($productamountsplit[$i] == '')
				{
					echo(json_encode('2^Invalid Entry1^'.$productamountvalues)); exit;
				}
			}
			
			//server side check for Add/Less amount field
			for($i=0;$i<count($descriptiontypevaluessplit);$i++)
			{
				if($descriptiontypevaluessplit[$i] <> '')
				{
					if($descriptionvaluesplit[$i] == '')
					{
						echo(json_encode('2^Invalid Entry2^'.$descriptiontypevalues)); exit;
					}
					if($descriptionamountvaluessplit[$i] == '')
					{
						echo(json_encode('2^Invalid Entry3^'.$descriptionamountvalues)); exit;
					}
				}
			}
			
			
	
			if($selectedcookievalue <> '')
			{
				$cardlistvaluessplit = explode(',',$cardlistvalues);
				for($i = 0; $i < count($cardlistvaluessplit); $i++)
				{
					$cardid = $cardlistvaluessplit[$i];

					$query378 = "select * from inv_dealercard where  cardid = '".$cardid."'";
					$result378 = runmysqlquery($query378);
					if(mysqli_num_rows($result378) == 0)
					{
						$query77 = "select * from inv_mas_scratchcard where cardid = '".$cardid."' and flag = 'yes';";
						$result77 = runmysqlquery($query77);
						if(mysqli_num_rows($result77) == 0)
						{
							echo(json_encode('2^Invalid Card^'.$cardid)); exit;
						}
					}
				}
			}
			//Check for Dealer region if CSD/BKG SPP invoice should not be generated
			$dealerquery = "select * from inv_mas_dealer where slno = '".$dealerid."';";
			$dealerresultfetch = runmysqlqueryfetch($dealerquery);
			$dealerregion = $dealerresultfetch['region'];
			
			
			//Calculate Add/Less amount
			$descamt = getdescriptionamount($descriptionamountvalues,$descriptiontypevalues);
			
			//Calculate total Service amount 
			$serviceamountvaluessplit = explode('~',$serviceamountvalues);
			for($i=0;$i<count($serviceamountvaluessplit);$i++)
			{
				$totalserviceamount +=  $serviceamountvaluessplit[$i];
			}
			
		
			//Assign service tax amount
			$servicetax = $servicetaxamount;
			
			$pricingamount = 0;
			if($selectedcookievalue <> '')
			{
				//Recalculation of amount
				$calculatedprice = calculatenormalprice($productamountsplit,$productquantitysplit);
				$calculatedpricesplit = explode('$',$calculatedprice);
				
				//total product price with quantity multiplied
				$totalproductprice = $calculatedpricesplit[0];
				
				//Product price without quantity
				$productpricearray = $calculatedpricesplit[1];
				
	
				//Product prices in array
				$totalproductpricearray = $calculatedpricesplit[2];
				
				//Calculate actual product price to insert in dealer online purchase table
				$actualcalculatedprice = calculatetotalamount($selectedcookievaluesplit,$purchasevaluesplit,$usagevaluesplit,$productquantitysplit,$pricingtype,$offeramount);
				$actualcalculatedpricesplit = explode('$',$actualcalculatedprice);
				$actualproductprice = $actualcalculatedpricesplit[2];
					
				//Calculate Total amount
				$totalproductprice = $totalproductprice + $descamt + $totalserviceamount;
			}
			else
			{
				$totalproductprice = '';
				$productpricearray = '';
				$totalproductpricearray = '';
				//Calculate Total amount
				$totalproductprice =  $descamt + $totalserviceamount;
			}
	
			$netamount = $servicetax + $totalproductprice ;
			
			//Get the customer details
			$query1 = "select * from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode =inv_mas_customer.district left join inv_mas_state on inv_mas_state.statecode =inv_mas_district.statecode left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on  inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category  where inv_mas_customer.slno = '".$lastslno."';";
			$fetch = runmysqlqueryfetch($query1);
		
		
			// Fetch Contact Details
			$querycontactdetails = "select customerid, GROUP_CONCAT(contactperson) as contactperson,  
	GROUP_CONCAT(phone) as phone, GROUP_CONCAT(cell) as cell, GROUP_CONCAT(emailid) as emailid from inv_contactdetails where customerid = '".$lastslno."'  group by customerid ";
			$resultcontact = runmysqlquery($querycontactdetails);
			$resultcontactdetails = mysqli_fetch_array($resultcontact);
			//$resultcontactdetails = runmysqlqueryfetch($querycontactdetails);
			
			$contactvalues = removedoublecomma($resultcontactdetails['contactperson']);
			$phoneres = removedoublecomma($resultcontactdetails['phone']);
			$cellres = removedoublecomma($resultcontactdetails['cell']);
			$emailidres = removedoublecomma($resultcontactdetails['emailid']);
			
			//fetch the details from customer pending table
			$query22 = "SELECT count(*) as count from inv_contactreqpending where customerid = '".$lastslno."' and customerstatus = 'pending' and editedtype = 'edit_type'";
			$result22 = runmysqlqueryfetch($query22);
			if($result22['count'] == 0)
			{
				$resultantemailid = $emailidres;
			}
			else
			{
				// Fetch of contact details, from pending request table if any
				$querycontactpending = "select GROUP_CONCAT(emailid) as pendemailid from inv_contactreqpending where customerid = '".$lastslno."' and customerstatus = 'pending' and editedtype = 'edit_type' group by customerid ";
				$resultcontactpending = runmysqlqueryfetch($querycontactpending);
				
				$emailidpending = removedoublecomma($resultcontactpending['pendemailid']);
				
				$finalemailid = $emailidres.','.$emailidpending;
				$resultantemailid = remove_duplicates($finalemailid);
			}
			$finalemailids = $resultantemailid.','.$cusemail;
			$resultantemailid = remove_duplicates($finalemailids);
			
			//Fetched customer contact details
			$generatedcustomerid = $fetch['customerid'];
			$phonenumber = explode(',', $cusphone);
			$phone = $phonenumber[0];
			$cellnumber = explode(',',$cuscell);
			$cell = $cellnumber[0];
			$businessname = $cusname;
			$address = addslashes($cusaddress);
			$place = $fetch['place'];
			$district = $fetch['districtcode'];
			$state = $fetch['statename'];
			$pincode = $fetch['pincode'];
			$contactperson = trim($cuscontactperson,',');
			$stdcode = $fetch['stdcode'];
			$phone = $phonenumber[0];
			$fax = $fetch['fax'];
			$cell = $cellnumber[0];
			$branchname = $fetch['branchname'];
			$emailid = trim($cusemail,',');
			//$category = $fetch['category'];
			//$type = $fetch['type'];
			$currentdealer = $dealerid;
			$customerid17digit = $fetch['customerid'];
			$firstproduct = $fetch['firstproduct'];
			
			$category = $businesstype;
			$type = $customertype;
			
					//If it is a new customer, generate new customer id and update it in Customer Master
			if($firstproduct  == '')
			{
				if($selectedcookievalue <> '')
					$firstproduct = $selectedcookievaluesplit[0];
				else
					$firstproduct = '000';
				//Get new customer id
				$query = "select statecode from inv_mas_district where districtcode  = '".$district."';";
				$fetch = runmysqlqueryfetch($query);
				$statecode = $fetch['statecode'];
				$newcustomerid = $statecode.$district.$currentdealer.$firstproduct.$lastslno;
				$password = generatepwd();
				//update customer master with customer product
				$query = "update inv_mas_customer set firstdealer = '".$currentdealer."' , firstproduct = '".$firstproduct."', initialpassword = '".$password."', loginpassword = AES_ENCRYPT('".$password."','imaxpasswordkey') where slno = '".$lastslno."';";
				$result = runmysqlquery($query);
		
			}
			
			//Check for payment type to update payment remarks 
			if($paymenttype == 'credit/debit')
			{
				$paymentremarks = 'Selected for Credit/Debit Card Payment. This is subject to successful transaction';
				$paymenttypeselected = 'paymentmadenow';
				$paymentmode = 'creditordebit';
			}
			
			//Define payment remarks for the invoice
			if($paymenttypeselected == 'paymentmadelater')
				$paymentremarksnew = 'Payment Due!! (Due Date: '.changedateformat($duedate).') '.$paymentremarks;
			else
			{
				if($paymentmode == 'chequeordd')
					$paymentremarksnew = 'Received Cheque No: '.$chequeno.', dated '.changedateformat($chequedate).', drawn on '.$drawnon.', for amount '.$paymentamount.'. Cheques received are subject to realization.';
				else if($paymentmode == 'cash')
					$paymentremarksnew = $paymentremarks;
				else
					$paymentremarksnew = 'Payment through Online Transfer. '.$paymentremarks.'';
			}
			$invoiceremarks = ($invoiceremarks == '')?'None':$invoiceremarks;
			$paymentremarksnew = ($paymentremarksnew == '')?'None':$paymentremarksnew;
						
			
	
			//Fetch the max slno from dealer online purchase table
			$countquery = "select ifnull(max(slno),0) + 1 as slnotobeinserted from dealer_online_purchase;";
			$fetchcount = runmysqlqueryfetch($countquery);
			$slnotobeinserted = $fetchcount['slnotobeinserted'];
						
			//If payment is made later, set the payment mode to empty
			if($paymenttypeselected == 'paymentmadelater')
			{
				$paymentmode = "";
				$duedate = $duedate;
			}
			else
				$duedate = changedateformat($invoicedate);
		
			//Insert the purchase details in dealer online purchase table
			$query = "insert into `dealer_online_purchase`(slno,customerreference,businessname,address,place,district,state,pincode,contactperson,stdcode,phone,fax,cell,emailid,category,type,currentdealer,amount,netamount,servicetax, products, paymentdate, paymenttime, purchasetype, paymenttype, usagetype, offertype, offerdescription, offeramount, invoiceremarks, paymentremarks,quantity,pricingtype,pricingamount,productpricearray,createdby,createdip,createddate,lastmodifieddate,lastmodifiedip,lastmodifiedby,totalproductpricearray,offerremarks,module,service,serviceamount,paymenttypeselected,paymentmode,actualproductprice,duedate,privatenote,podate,poreference,productbriefdescription,itembriefdescription,seztaxtype,seztaxfilepath,seztaxdate,seztaxattachedby) values
	('".$slnotobeinserted."','".$lastslno."','".$businessname."','".addslashes($address)."','".$place."','".$district."','".$state."','".$pincode."','".$contactperson."','".$stdcode."','".$phone."','".$fax."','".$cell."','".$emailid."','".$category."','".$type."','".$currentdealer."','".$totalproductprice."','".$netamount."','".$servicetax."','".$selectedcookievalue."','','','".$purchasevalues."','".$paymenttype."','".$usagevalues."','".$descriptiontypevalues."','".$descriptionvalues."','".$descriptionamountvalues."','".$invoiceremarks."','".$paymentremarks."','".$productquantityvalues."','".$pricingtype."','".$pricingamount."','".$productpricearray."','".$generatedbyvalue."','".$_SERVER['REMOTE_ADDR']."','".$invoicedateformat."','".$invoicedateformat."','".$_SERVER['REMOTE_ADDR']."','".$userid."','".$totalproductpricearray."','".$offerremarks."','".$module."','".$servicelist."','".$serviceamountvalues."','".$paymenttypeselected."','".$paymentmode."','".$actualproductprice."','".$duedate."','".$privatenote."','".changedateformat($podate)."','".$poreference."','".$productleveldescriptionvalues."','".$itemleveldescriptionvalues."','".$seztaxtype1."','".$seztaxfilepath1."','".$seztaxdate1."','".$seztaxattachedby1."')";
			$result = runmysqlquery($query);
			//$amount = '1^'.$calculatedprice.'^'.$totalproductprice.'^'.$servicetax.'^'.$netamount.'^'.$slnotobeinserted;
	
			if($selectedcookievalue <> '')
			{
				//Update current dealer of customer  with logged in dealer id
				$query = "update inv_mas_customer set currentdealer = '".$currentdealer."' where slno = '".$lastslno."';";
				$result = runmysqlquery($query);
			}
			
			
		//Get the logged in  dealer details
		$query0 = "select billingname,inv_mas_region.category as region,inv_mas_dealer.emailid as dealeremailid,inv_mas_dealer.region as regionid,inv_mas_dealer.branch  as branchid,inv_mas_dealer.district as dealerdistrict from inv_mas_dealer left join inv_mas_region on inv_mas_region.slno = inv_mas_dealer.region  where inv_mas_dealer.slno = '".$currentdealer."';";
		$fetch0 = runmysqlqueryfetch($query0);
		$dealername = $fetch0['billingname'];
		$dealerregion = $fetch0['region'];
		$dealeremailid = $fetch0['dealeremailid'];
		$regionid = $fetch0['regionid'];
		$branchid = $fetch0['branchid'];
		$dealerdistrict = $fetch0['dealerdistrict'];
	
		//update region and branch of customer as per dealer
		$query11 = "update inv_mas_customer set branch = '".$branchid."', region = '".$regionid."' where slno = '".$lastslno."';";
		$result11 = runmysqlquery($query11);
		
	
		
		
		//Get the statename and district name
		$query112 = "select districtname,statename,category as regionname,branchname from inv_mas_district left join inv_mas_state on inv_mas_district.statecode = inv_mas_state.statecode left join inv_mas_region on inv_mas_region.slno = inv_mas_district.region
	left join inv_mas_branch on inv_mas_branch.slno = inv_mas_district.branchid where inv_mas_district.districtcode = '".$dealerdistrict."'";
		$resultfetch112 = runmysqlqueryfetch($query112);
		$districtname = $resultfetch112['districtname'];
		$statename = $resultfetch112['statename'];
		$branchname = $resultfetch112['branchname'];
	
		$emailid = explode(',', trim($cusemail,','));
		$emailidplit = $emailid[0];
		$phonenumber = explode(',', trim($cusphone,','));
		$phonenumbersplit = $phonenumber[0];
		$cellnumber = explode(',', trim($cuscell,','));
		$cellnumbersplit = $cellnumber[0];
		$contactperson = explode(',', trim($cuscontactperson,','));
		$contactpersonplit = $contactperson[0];
		$stdcode = ($fetch['stdcode'] == '')?'':$fetch['stdcode'].' - ';
		$address1 = $address.', Pin: '.$pincode;
		$invoiceheading = ($fetch['statename'] == 'Karnataka')?'Tax Invoice':'Bill Of Sale';
		$branchname = $branchname;
		$amountinwords = convert_number($netamount);
		$servicetaxdesc = 'Service Tax Category: Information Technology Software (zzze), Support(zzzq), Training (zzc), Manpower(k), Salary Processing (22g), SMS Service (b)';
		
		$invoicedate = $invoicedateformat;
	
		$currentdate = strtotime(date('Y-m-d'));
		//$currentdate = strtotime('2011-04-04');
		$backdateddate = strtotime('2011-03-31');
		$expirydate = strtotime('2011-04-04');
		$invoicenoformatspilt = explode('/',$invoicenoformat);
		$onlineinvoiceno = $invoicenoformatspilt[2];
		$onlinecategory = $invoicenoformatspilt[1];
		
		
			//Get the next record serial number for insertion in invoicenumbers table
		$query133 = "select ifnull(max(tempslno),0) + 1 as billref from inv_invoicenumbers";
		$resultfetch133 = runmysqlqueryfetch($query133);
		$onlineinvoiceslno = $resultfetch133['billref'];
		//Insert complete invoice details to invoicenumbers table 
		$query = "Insert into inv_invoicenumbers(slno,customerid,businessname,contactperson,address,place,pincode,emailid,description,invoiceno,dealername,createddate,createdby,amount,servicetax,netamount,phone,cell,customertype,customercategory,region,purchasetype,category,onlineinvoiceno,dealerid,products,productquantity,pricingtype,createdbyid,totalproductpricearray,actualproductpricearray,module,servicetype,serviceamount,paymenttypeselected,paymentmode,stdcode,branch,amountinwords,remarks,servicetaxdesc,invoiceheading,offerremarks,invoiceremarks,duedate,branchid,regionid,privatenote,podate,poreference,productbriefdescription,itembriefdescription,seztaxtype,seztaxfilepath,seztaxdate,seztaxattachedby,tempslno) values('".$onlineinvoiceslno."','".cusidcombine($generatedcustomerid)."','".$businessname."','".$contactpersonplit."','".addslashes($address1)."','".$place."','".$pincode."','".$emailidplit."','','".$invoicenoformat."','".$dealername."','".$invoicedate."','".$createdbyusername."','".$totalproductprice."','".$servicetax."','".$netamount."','".$phonenumbersplit."','".$cellnumbersplit."','".$type."','".$category."','".$dealerregion."','Product','".$onlinecategory."','".$onlineinvoiceno."','".$currentdealer."','".$selectedcookievalue."','".$productquantityvalues."','".$pricingtype."','".$generatedbyvalue."','".$totalproductpricearray."','".$actualproductprice."','".$module."','".$servicelist."','".$serviceamountvalues."','".$paymenttypeselected."','".$paymentmode."','".$stdcode."','".$branchname."','".$amountinwords."','".$paymentremarksnew."','".$servicetaxdesc."','".$invoiceheading."','".$offerremarks."','".$invoiceremarks."','".$duedate."','".$branchid."','".$regionid."','".$privatenote."','".changedateformat($podate)."','".$poreference."','".$productleveldescriptionvalues."','".$itemleveldescriptionvalues."','".$seztaxtype1."','".$seztaxfilepath1."','".$seztaxdate1."','".$seztaxattachedby1."','".$onlineinvoiceslno."');";
		$result = runmysqlquery($query);
		
		if($selectedcookievalue <> '')
		{
			//Total product quantity
			for($i=0;$i<count($productquantitysplit);$i++)
			{
				$totalproductquantity += $productquantitysplit[$i];
			}
		
					
			//per product price
			$productpricearraysplit = explode('*',$productpricearray);
				
			//Make the product details to an array
			$arraysplit = explode('#',$selectedcookievalue);
			$purchasetypesplit = explode(',',$purchasetype);
			$usagetypesplit = explode(',',$usagetype);
			$cardlistvaluessplit = explode(',',$cardlistvalues);
			//print($cardlistvaluessplit[0]);exit;
						
				if($selectedcookievalue <> '')
				{
					//Total product quantity
					for($i=0;$i<count($productquantitysplit);$i++)
					{
						$totalproductquantity += $productquantitysplit[$i];
					}
				
							
					//per product price
					$productpricearraysplit = explode('*',$productpricearray);
						
					//Make the product details to an array
					$arraysplit = explode('#',$selectedcookievalue);
					$purchasetypesplit = explode(',',$purchasetype);
					$usagetypesplit = explode(',',$usagetype);
				
					$k = 0;
					for($i = 0; $i < $totalproductquantity; $i++)
					{
						for($j = 0; $j < $productquantitysplit[$i]; $j++)
						{
							$cardidvalue = $cardlistvaluessplit[$i];
							$query378 = "select * from inv_dealercard where  cardid = '".$cardidvalue."'";
							$result77 = runmysqlquery($query378);
							if(mysqli_num_rows($result77) > 0)
							{
								//Execute the PIN number Purchase
								$query7 = "SELECT attachPIN() as cardid;";
								$result7 = runmysqlqueryfetch($query7);
								
								$cardid = $result7['cardid'];
							}
							else
								$cardid = $cardlistvaluessplit[$i];
							
							$usagetypevalue = $usagevaluesplit[$i];
							if($usagetypevalue == 'addlic')
							{
								$usagetype = 'singleuser';
								$addlicence = 'yes';
							}
							else
							{
								$usagetype = $usagevaluesplit[$i];
								$addlicence = '';
							}
				
							$query7 = "INSERT INTO inv_dealercard(dealerid,cardid,productcode,date,usagetype,purchasetype,userid,customerreference,initialusagetype,initialpurchasetype,initialproduct,initialdealerid,cusbillnumber,scheme,cuscardattacheddate,cuscardattachedby,usertype,addlicence,invoiceid) values('".$currentdealer."','".$cardid."','".$arraysplit[$i]."','".$invoicedateformat."','".$usagetype."','".$purchasevaluesplit[$i]."','2','".$lastslno."','".$usagetype."','".$purchasevaluesplit[$i]."','".$arraysplit[$i]."','".$currentdealer."','".$firstbillnumber."','1','".$invoicedateformat."','".$currentdealer."','user','".$addlicence."','".$onlineinvoiceslno."')";
							$result1 = runmysqlquery($query7);
						
						  $query8 = "update inv_mas_scratchcard set flag = 'no', attached = 'yes' where cardid = '".$cardid."'";
						  $result8 = runmysqlquery($query8);
						  $k++;
						}
					}
						//Attach that PIN Number to that customer
						
	
			}
		}
		
		if($selectedcookievalue <> '')
		{
			$carddetailsquery = "select * from inv_dealercard left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid  where invoiceid = '".$onlineinvoiceslno."';";
			$carddetailsresult = runmysqlquery($carddetailsquery);
			$slno = 0;
			$k;
			$descriptioncount = 0;
			$k=0;
			while($carddetailsfetch = mysqli_fetch_array($carddetailsresult))
			{
				$slno++;
				if($carddetailsfetch['purchasetype'] == 'new')
					$purchasetype = 'New';
				else
					$purchasetype = 'Updation';
				if($carddetailsfetch['addlicence'] == 'yes')
				{
					$usagetype = 'Additional License';
				}
				else
				{
					if($carddetailsfetch['usagetype'] == 'singleuser')
						$usagetype = 'Single User';
					else
						$usagetype = 'Multi User';
				}
				
				if($descriptioncount > 0)
					$description .= '*';
				$description .= $slno.'$'.$carddetailsfetch['productname'].' - ('.$carddetailsfetch['year'].')'.'$'.$purchasetype.'$'.$usagetype.'$'.$carddetailsfetch['scratchnumber'].'$'.$carddetailsfetch['cardid'].'$'.$productpricearraysplit[$k];
				$k++;
				$descriptioncount++;
			}
		}
			if($servicelist <> '')
			{
				$servicecount = 0;
				for($i=0; $i<count($servicelistsplit); $i++)
				{
					$slno++;
					if($servicecount > 0)
						$servicegrid .= '*';
					$servicegrid .= $slno.'$'.$servicelistsplit[$i].'$'.$serviceamountvaluessplit[$i];
					$servicecount++;
				}
			}
			if($descriptiontypevalues <> '')
			  {
				$offerdescriptioncount = 0;
				for($i=0; $i<count($descriptiontypevaluessplit); $i++)
				{
					$slno++;
					if($offerdescriptioncount > 0)
						$offerdescriptiongrid .= '*';
					$offerdescriptiongrid .= $descriptiontypevaluessplit[$i].'$'.$descriptionvaluesplit[$i].'$'.$descriptionamountvaluessplit[$i];
					$offerdescriptioncount++;
				}
			  }
			//Update online invoice number
			$invoicequery = "update inv_invoicenumbers set description = '".$description."', offerdescription = '".$offerdescriptiongrid."', servicedescription = '".$servicegrid."' where slno  ='".$onlineinvoiceslno."';";
			$invoiceresult = runmysqlquery($invoicequery);
	
			//update preonline purchase table with invoice no and other details 
			$query1 = "UPDATE dealer_online_purchase SET paymentdate = '".changedateformat($invoicedate)."', paymenttime = '".$invoicetime."',paymentremarks = '".$paymentremarksnew."',onlineinvoiceno = '".$onlineinvoiceslno."' WHERE slno = '".$slnotobeinserted."'";
			$result = runmysqlquery($query1);
			
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','70','".$invoicedateformat."','".$lastslno.'$$'.$onlineinvoiceslno."')";
			$eventresult = runmysqlquery($eventquery);
				
			
			echo(json_encode('1^Card Attached^'.$onlineinvoiceslno.''));
		}
		else
			echo(json_encode('2^Invoice already present'));

	}
	break;
	case 'customerregistration':
	{
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$resultcount = "SELECT inv_mas_product.productname as productname FROM inv_customerproduct left join inv_mas_product on left(inv_customerproduct.computerid, 3) = inv_mas_product.productcode left join inv_mas_users on inv_customerproduct.generatedby = inv_mas_users.slno left join inv_mas_dealer on inv_customerproduct.dealerid = inv_mas_dealer.slno  where customerreference = '".$lastslno."' order by `date`  desc,`time` desc ; ";
		$resultfetch = runmysqlquery($resultcount);
		$fetchresultcount = mysqli_num_rows($resultfetch);
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
			$startlimit = $slno;
			$slno = $slno;
		}
		
		$query = "SELECT inv_mas_product.productname as productname,getPINNo(inv_customerproduct.cardid) AS cardid, 
inv_customerproduct.computerid AS computerid,inv_customerproduct.softkey AS softkey,inv_customerproduct.date AS regdate, 
inv_customerproduct.time AS regtime, inv_mas_users.fullname AS generatedby, inv_mas_dealer.businessname AS businessname, 
inv_customerproduct.billnumber as Billnum,inv_customerproduct.billamount as billamount,inv_customerproduct.remarks as remarks 
FROM inv_customerproduct 
left join inv_mas_product on left(inv_customerproduct.computerid, 3) = inv_mas_product.productcode 
left join inv_mas_users on inv_customerproduct.generatedby = inv_mas_users.slno 
left join inv_mas_dealer on inv_customerproduct.dealerid = inv_mas_dealer.slno  where customerreference = '".$lastslno."' order by `date`  desc,`time` desc LIMIT ".$startlimit.",".$limit."; ";

		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">PIN Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Computer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Soft Key</td><td nowrap = "nowrap" class="td-border-grid" align="left">Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Time</td><td nowrap = "nowrap" class="td-border-grid" align="left">Generatd By</td><td nowrap = "nowrap" class="td-border-grid" align="left">Dealer</td><td nowrap = "nowrap" class="td-border-grid" align="left">Bill No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Bill Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td></tr>';
		}
		
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$i_n++;
			$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow1" bgcolor='.$color.' align="left">';
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left' >".$slno."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['productname']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['cardid']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['computerid']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['softkey']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['regdate']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['regtime']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['generatedby']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['businessname']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['Billnum']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['billamount']."</td>";
			$grid .= "<td align ='left' nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['remarks']."</td>";
			$grid .= "</tr>";
		}
		$grid .= "</table>";
 
		$fetchcount = mysqli_num_rows($result);
		if($slno >= $fetchresultcount)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"  ><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td align ="left"><div align ="left" style="padding-right:10px">&nbsp;&nbsp;&nbsp;<a onclick ="getmorecustomerregistration(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'more\');" class="resendtext" style="cursor:pointer;">Show More Records >></a> &nbsp;&nbsp;&nbsp;<a onclick ="getmorecustomerregistration(\''.$lastslno.'\',\''.$startlimit.'\',\''.$slno.'\',\'all\');" class="resendtext1"><font color= "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
		$responsearray21 = array();
		$responsearray21['errorcode'] = '1';
		$responsearray21['grid'] = $grid;
		$responsearray21['count'] = $fetchresultcount;
		$responsearray21['linkgrid'] = $linkgrid;
		echo(json_encode($responsearray21));
		
		
	}
	break;
	
	case 'contactsave':
	{
		$businessname = $_POST['businessname'];
		$address = $_POST['address'];
		$place = $_POST['place'];
		$pincode = $_POST['pincode'];
		$district = $_POST['district'];
		$category = $_POST['category'];
		$type = $_POST['type'];
		$lastslno = $_POST['lastslno'];
		$fax = $_POST['fax'];
		$stdcode = $_POST['stdcode'];
		$contactarray = $_POST['contactarray'];
		$totalarray = $_POST['totalarray'];
		$branch = $_POST['branch'];
		$region = $_POST['region'];
		$customerid = $_POST['customerid'];
		$currentdealer = $_POST['currentdealer'];
		$totalsplit = explode(',',$totalarray);
		$contactsplit = explode('****',$contactarray);
		$contactcount = count($contactsplit);
		if($contactcount > 1)
		{
			for($i=0;$i<$contactcount;$i++)
			{
				$contactressplit[] = explode('#',$contactsplit[$i]);
			}
		}
		else
		{
			for($i=0;$i<$contactcount;$i++)
			{
				$contactressplit[] = explode('#',$contactsplit[$i]);
			}
		}
		
		$website = $_POST['website'];
		$remarks = $_POST['remarks'];
		$createddate = datetimelocal('d-m-Y').' '.datetimelocal('H:i:s');
		$date = datetimelocal('d-m-Y');
		
		$customerid17digit = cusidsplit($customerid);
		$cusslno = substr($customerid17digit,12,5);
		$query22 = "select * from inv_mas_customer where customerid = '".$customerid17digit."';";
		$result22 = runmysqlquery($query22);
		if(mysqli_num_rows($result22) == 0)
		{
			$query = "Insert into inv_mas_customer(slno,customerid,businessname,address, place,pincode,district,region,category,type,stdcode,website,remarks,password,passwordchanged,disablelogin,createddate,createdby,corporateorder,currentdealer,fax,activecustomer,lastmodifieddate,lastmodifiedby,createdip,lastmodifiedip,branch,companyclosed, promotionalsms,promotionalemail) values ('".$cusslno."','".$customerid17digit."','".trim($businessname)."','".$address."','".$place."','".$pincode."','".$district."','".$region."','".$category."','".$type."','".$stdcode."','".$website."','".$remarks."','','N','no','".changedateformatwithtime($createddate)."','2','no','".$currentdealer."','".$fax."','yes','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".$_SERVER['REMOTE_ADDR']."','".$branch."','no','no','no');";
			$result = runmysqlquery($query);
			for($j=0;$j<count($contactressplit);$j++)
			{
				$selectiontype = $contactressplit[$j][0];
				$contactperson = $contactressplit[$j][1];
				$phone = $contactressplit[$j][2];
				$cell = $contactressplit[$j][3];
				$emailid = $contactressplit[$j][4];
				//Added Space after comma is not avaliable in phone, cell and emailid fields
				$phonespace = str_replace(", ", ",",$phone);
				$phonevalue = str_replace(',',', ',$phonespace);
				
				$cellspace = str_replace(", ", ",",$cell);
				$cellvalue = str_replace(',',', ',$cellspace);
				
				$emailidspace = str_replace(", ", ",",$emailid);
				$emailidvalue = str_replace(',',', ',$emailidspace);
				
				$query2 = "Insert into inv_contactdetails(customerid,selectiontype,contactperson,phone,cell,emailid) values  ('".$cusslno."','".$selectiontype."','".$contactperson."','".$phonevalue."','".$cellvalue."','".$emailidvalue."');";
				$result = runmysqlquery($query2);
			}
	
			$responsesavearray = array();
			$responsesavearray['successcode'] = "1";
			$responsesavearray['successmessage'] = "Customer Record  Saved Successfully";
			
		}
		else
		{
			$responsesavearray = array();
			$responsesavearray['successcode'] = "2";
			$responsesavearray['successmessage'] = "Customer ID already present";
		}
		echo(json_encode($responsesavearray));
			
	}
	break;
}


function calculatenormalprice($productamountsplit,$productquantitysplit)
{
	for($i = 0; $i < count($productamountsplit); $i++)
	{
		for($j=1;$j<=$productquantitysplit[$i];$j++)
		{
			$singleproductprice = $productamountsplit[$i];
			if($prdcount > 0)
			$productpricearray .= '*';
				$productpricearray .= ($singleproductprice);
			$prdcount++;
		}
		$productprice = $productamountsplit[$i] * ($productquantitysplit[$i]);
		if($prdcount1 > 0)
		$totalproductpricearray .= '*';
			$totalproductpricearray .= ($productprice);
		$prdcount1++;
		$productprice = ($productprice);
		$totalproductprice += $productprice ;
	}
	return $totalproductprice.'$'.$productpricearray.'$'.$totalproductpricearray;
}


function calculatetotalamount($selectedcookievaluesplit,$purchasevaluesplit,$usagevaluesplit,$productquantitysplit,$pricingtype,$offeramount)
{
	$prdcount = 0;
	if(($pricingtype == 'offer') || ($pricingtype == 'inclusivetax'))
	{
		$productratio = productratio($selectedcookievaluesplit,$purchasevaluesplit,$usagevaluesplit,$offeramount,$productquantitysplit);
	}
	else
	{
		$productratio = 1;
	}
	for($i = 0; $i < count($selectedcookievaluesplit); $i++)
	{
		$recordnumber = $selectedcookievaluesplit[$i];
		$purchasetype = $purchasevaluesplit[$i];
		$usagetype = $usagevaluesplit[$i];
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
		$singleproductprice = $fetch['productprice'];
		if($prdcount > 0)
			$productpricearray .= '*';
		if(($i == count($selectedcookievaluesplit)-1) && (($pricingtype == 'offer')|| ($pricingtype == 'inclusivetax')))
		{
			$lastproductprice = $offeramount - $totalproductprice;
			$productpricearray .= $lastproductprice;
		}
		else
		{
			$productpricearray .= roundnearest($singleproductprice * $productratio);
		}
		$prdcount++;
		$productprice = $fetch['productprice'] * ($productquantitysplit[$i]);
		if($prdcount1 > 0)
			$totalproductpricearray .= '*';
		$totalproductpricearray .= roundnearest($productprice * $productratio);
		$prdcount1++;
		$productprice = roundnearest($productprice * $productratio);
		$totalproductprice += $productprice ;
	}
	return $totalproductprice.'$'.$productpricearray.'$'.$totalproductpricearray.'$'.$productratio;
}

function getdescriptionamount($descriptionamountvalues,$descriptiontypevalues)
{
	$descriptionamountsplit = explode('~',$descriptionamountvalues);
	$descriptiontypesplit = explode('~',$descriptiontypevalues);
	$descriptioncount = count($descriptionamountsplit);
	$amount = 0;
	for($i=0;$i<$descriptioncount; $i++)
	{
		if($descriptiontypesplit[$i] == 'add')
			$amount = ($amount) + $descriptionamountsplit[$i];
		else if($descriptiontypesplit[$i] == 'less')
			$amount = ($amount) - $descriptionamountsplit[$i];
		else
			$amount;
	}
	return roundnearest($amount);
}

function productratio($selectedcookievaluesplit,$purchasevaluesplit,$usagevaluesplit,$offeramount,$productquantitysplit)
{
	for($i = 0; $i < count($selectedcookievaluesplit); $i++)
	{
		$recordnumber = $selectedcookievaluesplit[$i];
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
		$totalproductprice += $productprice;
	}
	$productratio = ($offeramount)/($totalproductprice);
	return round($productratio,2);
}

function roundnearest($amount)
{
	$firstamount = round($amount,1);
	$amount1 = round($firstamount);
	return $amount1;
}




function getpaymentstatus($receiptamount,$netamount)
{
	 if($receiptamount == '')
	 {
	   return '<span class="redtext">UNPAID</span>';
	 }
	 else if($receiptamount < $netamount)
	 { 
	  return '<span class="redtext">PARTIAL</span>';
	 }
	 else if($receiptamount == $netamount)
	 { 
	  return '<span class="greentext">PAID</span>';
	 } 
	 else
	   return '<span class="greentext">PAID</span>';
}

function getstartnumber($region)
{
	switch($region)
	{
		case 'BKG': $startnumber = '1'; break;
		case 'BKM': $startnumber = '1';break;
		case 'CSD': $startnumber = '11101';break;
		default: $startnumber = '1';break;
	}
	return ($startnumber-1);
}

?>