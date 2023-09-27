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
//include('../softkey/regfunction.bin');
$lastslno = $_POST['lastslno'];
$switchtype = $_POST['switchtype'];



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
		$lastslno = $_POST['lastslno'];
		$query1 = "SELECT count(*) as count from inv_mas_customer  where inv_mas_customer.slno = '".$lastslno."'";
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)
		{
			$query = "select inv_mas_customer.slno as slno, inv_mas_customer.businessname as companyname,inv_mas_customer.place,inv_mas_customer.address,inv_mas_region.category as region,inv_mas_branch.branchname as branch	,inv_mas_customercategory.businesstype,inv_mas_customertype.customertype,inv_mas_dealer.businessname as dealername,inv_mas_customer.stdcode, inv_mas_customer.pincode,inv_mas_district.districtname, inv_mas_state.statename,inv_mas_customer.customerid  from inv_mas_customer left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_customer.slno = '".$lastslno."'";
			$fetch = runmysqlqueryfetch($query);
			
			$querycontactdetails = "select  phone,cell, emailid,contactperson from inv_contactdetails where customerid = '".$lastslno."'";
			$resultcontactdetails = runmysqlquery($querycontactdetails);
			
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
			$pincode = ($resultfetch['pincode'] == '')?'':'Pin - '.$fetch['pincode'];
			$address = $fetch['address'].', '.$fetch['districtname'].', '.$fetch['statename'].$pincode;
			
			$contactperson = trim($contactvalues,',');
			$phonenumber = explode(',',trim($phoneres,','));
			$phone = $phonenumber[0];
			$cellnumber = explode(',',trim($cellres,','));
			$cell = $cellnumber[0];
			$emailid = trim($emailidres,',');
			$emailidplitvalues = explode(',', $emailid);
			$emailidplit = $emailidplitvalues[0];
			
			$customerdetailstoformarray['errorcode'] = '1';
			$customerdetailstoformarray['slno'] = $fetch['slno'];
			$customerdetailstoformarray['customerid'] = $customerid;
			$customerdetailstoformarray['companyname'] = $fetch['companyname'];
			$customerdetailstoformarray['contactperson'] = $contactperson;
			$customerdetailstoformarray['address'] = $address;
			$customerdetailstoformarray['phone'] = $phone;
			$customerdetailstoformarray['cell'] = $cell;
			$customerdetailstoformarray['emailidplit'] = $emailidplit;
			$customerdetailstoformarray['region'] = $fetch['region'];
			$customerdetailstoformarray['branch'] = $fetch['branch'];
			$customerdetailstoformarray['businesstype'] = $fetch['businesstype'];
			$customerdetailstoformarray['customertype'] = $fetch['customertype'];
			$customerdetailstoformarray['dealername'] = $fetch['dealername'];
		
			//echo('1^'.$fetch['slno'].'^'.$customerid.'^'.$fetch['companyname'].'^'.$contactperson.'^'.$address.'^'.$phone.'^'.$cell.'^'.$emailidplit.'^'.$fetch['region'].'^'.$fetch['branch'].'^'.$fetch['businesstype'].'^'.$fetch['customertype'].'^'.$fetch['dealername']);
		}
		else
		{
			$customerdetailstoformarray['errorcode'] = '';
			$customerdetailstoformarray['slno'] = '';
			$customerdetailstoformarray['customerid'] = '';
			$customerdetailstoformarray['companyname'] = '';
			$customerdetailstoformarray['contactperson'] = '';
			$customerdetailstoformarray['address'] = '';
			$customerdetailstoformarray['phone'] = '';
			$customerdetailstoformarray['cell'] = '';
			$customerdetailstoformarray['emailidplit'] = '';
			$customerdetailstoformarray['region'] ='';
			$customerdetailstoformarray['branch'] = '';
			$customerdetailstoformarray['businesstype'] = '';
			$customerdetailstoformarray['customertype'] = '';
			$customerdetailstoformarray['dealername'] = '';
			//echo(''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
		}
			echo(json_encode($customerdetailstoformarray));
	}
	break;
	
	case 'invoicedetails':
	{
		$invoicegrid= "";
		$invoicedetailsarray = array();
		$lastslno = $_POST['lastslno'];
		$query1 = "SELECT count(*) as count from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode where inv_mas_customer.slno = '".$lastslno."'";
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)
		{
			$query12 = "SELECT count(*) as count from imp_cfentries where substring(imp_cfentries.customerid,16) = '".$lastslno."'";
			$fetch12 = runmysqlqueryfetch($query12);
			if($fetch12['count'] > 0)
			{
				// $resquery = "select * from imp_cfentries 
				// where imp_cfentries.productcode in ('265','816','640','604','643','851','871','874','834','836','835','841','838',
				// '843','844','850','869','872','875','870','873','876','861','863',
				// '862','864','262','261','269','266','267','268','237','238','877','878','879','880','882','883','884','001','885','886','887','888','889','890','891','892','893','894','895','896','897','898','899','900','902','903','904','905','800','944','939','940','941','948','946','947','952','950','949','953','957','958','959','962')
				// and substring(imp_cfentries.customerid,16) = '".$lastslno."'";
				$resquery = "select * from imp_cfentries where substring(imp_cfentries.customerid,16) = '".$lastslno."'";
				$resresult = runmysqlquery($resquery);
				$cfgrid = '<select name="cfdetails" class="swiftselect" id="cfdetails" style="width:125px;" ><option value="" selected="selected">Select an Entry</option>';
				while($resfetch = mysqli_fetch_array($resresult))
				{
					$cfgrid .= '<option value="'.$resfetch['invoiceno'].'%%'.''.'%%'.$resfetch['productcode'].'">'.$resfetch['invoiceno'].'</option>';
				}
				$cfgrid .= "</select>";
			}
			// $query121 = "select distinct count( *) as count
			// from inv_invoicenumbers left join inv_dealercard on inv_invoicenumbers.slno = inv_dealercard.invoiceid
			// where inv_dealercard.productcode in ('265','816','640','604','643','851','871','874','834','836','835','841','838',
			// '843','844','850','869','872','875','870','873','876','861','863',
			// '862','864','262','261','269','266','267','268','237','238','877','878','879','880','882','883','884','001','885','886','887','888','889','890','891','892','893','894','895','896','897','898','899','900','902','903','904','905','800','944','939','940','941','948','946','947','952','950','949','953','957','958','959','962')
			// and substring(inv_invoicenumbers.customerid,16) = '".$lastslno."'";
			$query121 = "select distinct count( *) as count
			from inv_invoicenumbers 
			left join inv_dealercard on inv_invoicenumbers.slno = inv_dealercard.invoiceid
			left join inv_mas_product on inv_mas_product.`productcode` = `inv_dealercard`.`productcode`
			where substring(inv_invoicenumbers.customerid,16) = '".$lastslno."' and inv_mas_product.`year` >='2021-22' and inv_mas_product.`group` ='SPP'";
			$fetch123 = runmysqlqueryfetch($query121);
			$invoicegrid = '<select name="invoicedetails" class="swiftselect" id="invoicedetails" style="width:125px;" ><option value="" selected="selected">Select a Invoice</option>';
			if($fetch123['count'] > 0)
			{
				// $query = "select distinct inv_invoicenumbers.slno, inv_invoicenumbers.customerid,
				// inv_invoicenumbers.businessname, inv_invoicenumbers.description
				// ,inv_invoicenumbers.invoiceno, inv_invoicenumbers.netamount,inv_dealercard.productcode
				// from inv_invoicenumbers left join inv_dealercard on inv_invoicenumbers.slno = inv_dealercard.invoiceid
				// where inv_dealercard.productcode in ('265','816','640','604','643','851','871','874','834','836','835','841','838',
				// '843','844','850','869','872','875','870','873','876','861','863',
				// '862','864','262','261','269','266','267','268','237','238','877','878','879','880','882','883','884','001','885','886','887','888','889','890','891','892','893','894','895','896','897','898','899','900','902','903','904','905','800','944','939','940','941','948','946','947','952','950','949','953','957','958','959','962')
				// and substring(inv_invoicenumbers.customerid,16) = '".$lastslno."'";
				$query = "select distinct inv_invoicenumbers.slno, inv_invoicenumbers.customerid,
				inv_invoicenumbers.businessname, inv_invoicenumbers.description
				,inv_invoicenumbers.invoiceno, inv_invoicenumbers.netamount,inv_dealercard.productcode
				from inv_invoicenumbers left join inv_dealercard on inv_invoicenumbers.slno = inv_dealercard.invoiceid
				left join inv_mas_product on inv_mas_product.`productcode` = `inv_dealercard`.`productcode`
				where substring(inv_invoicenumbers.customerid,16) = '".$lastslno."' and inv_mas_product.`year` >='2021-22' and inv_mas_product.`group` ='SPP'";
				$result = runmysqlquery($query);

				while($fetch = mysqli_fetch_array($result))
				{
					$invoicegrid .= '<option value="'.$fetch['invoiceno'].'%%'.$fetch['slno'].'%%'.$fetch['productcode'].'">'.$fetch['invoiceno'].'</option>';
				}
			}
			
			$servicename  = array();
			$query2 = "select servicename from inv_mas_service where notinuse = 'yes'";
			$result2 = runmysqlquery($query2);
			$count2 = mysqli_num_rows($result2);
			if($count2 > 0)
			{
				while($fetch2 = mysqli_fetch_array($result2))
				{
					$servicename[] = $fetch2['servicename'];
				}

				$query3 = "select servicetype,invoiceno,slno from inv_invoicenumbers where substring(inv_invoicenumbers.customerid,16) = '".$lastslno."' and description = '' and left(createddate,10) >= '2023-04-01'";
				$result3 = runmysqlquery($query3);
				while($fetch3 = mysqli_fetch_array($result3))
				{
					$servicetype = explode('#',$fetch3['servicetype']);
					//print_r($servicetype);
					
					if(array_intersect($servicetype,$servicename))
					{
						$invoicegrid .= '<option value="'.$fetch3['invoiceno'].'%%'.$fetch3['slno'].'">'.$fetch3['invoiceno'].'</option>';
					}
				}
			}
			

			$query1 = "select * from inv_matrixinvoicenumbers where substring(customerid,16) = '".$lastslno."';";
			$result1 = runmysqlquery($query1);

			while($fetch1 = mysqli_fetch_array($result1))
			{
				$invoicegrid .= '<option value="'.$fetch1['invoiceno'].'%%'.$fetch1['slno'].'%%matrix'.'">'.$fetch1['invoiceno'].'</option>';
			}
			$invoicegrid .= "</select>";

			$invoicedetailsarray['errorcode'] = '1';
			$invoicedetailsarray['count'] = $fetch12['count'];
			$invoicedetailsarray['cfgrid'] = $cfgrid;
			$invoicedetailsarray['invoicegrid'] = $invoicegrid;
			//echo('1^'.$fetch12['count'].'^'.$cfgrid.'^'.$invoicegrid);
			
		}
		else
		{
			$invoicedetailsarray['errorcode'] = '';
			$invoicedetailsarray['count'] = '';
			$invoicedetailsarray['cfgrid'] = '';
			$invoicedetailsarray['invoicegrid'] = '';
		}
		echo(json_encode($invoicedetailsarray));

	}
	break;
	
	case 'customervalidation':
	{
		$lastslno = $_POST['lastslno'];
		
		$query1 = "SELECT count(*) as count from imp_implementation left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode  where imp_implementation.customerreference = '".$lastslno."'";
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)
		{
			$impquery = "select implementationstatus,slno from imp_implementation where customerreference = '".$lastslno."' order by slno desc limit 1;";
			$impfetch = runmysqlqueryfetch($impquery);
			$implementationstatus = $impfetch['implementationstatus'];
			$impslno = $impfetch['slno'];

			if($implementationstatus!= 'completed')
			{
				$query12 = "SELECT imp_implementation.slno,imp_implementation.customerreference,imp_implementation.invoicenumber,
				imp_implementation.advancecollected, imp_implementation.advanceamount,imp_implementation.advanceremarks,
				imp_implementation.balancerecoveryremarks,imp_implementation.podetails,
				imp_implementation.numberofcompanies,imp_implementation.numberofmonths,
				imp_implementation.processingfrommonth,imp_implementation.additionaltraining,imp_implementation.freedeliverables,
				imp_implementation.schemeapplicable,imp_implementation.commissionapplicable,imp_implementation.commissionname, imp_implementation.commissionmobile,imp_implementation.commissionemail, imp_implementation.commissionvalue,imp_implementation.masterdatabyrelyon,
				imp_implementation.masternumberofemployees,imp_implementation.salescommitments,imp_implementation.attendanceapplicable,
				imp_implementation.attendanceremarks,imp_implementation.attendancefilepath
				,imp_implementation.attendancefiledate,imp_implementation.attendancefileattachedby,imp_implementation.shipinvoiceapplicable,
				imp_implementation.shipinvoiceremarks,imp_implementation.shipmanualapplicable,
				imp_implementation.shipmanualremarks,imp_implementation.customizationapplicable,imp_implementation.customizationremarks,
				imp_implementation.customizationreffilepath,imp_implementation.customizationreffiledate
				,imp_implementation.customizationreffileattachedby,imp_implementation.customizationbackupfilepath,
				imp_implementation.customizationbackupfiledate, imp_implementation.customizationbackupfileattachedby,
				imp_implementation.customizationstatus,imp_implementation.implementationstatus,imp_implementation.webimplemenationapplicable, imp_implementation.webimplemenationremarks ,branchapproval, attendancedeletefilepath,imp_implementation.podetailspath,imp_implementation.podetailsdate,imp_implementation.podetailsattachedby ,imp_implementation.customizationcustomerdisplay,imp_implementation.dealerid,imp_implementation.committedstartdate, imp_implementation.podate as podate,implementationtype,impremarks ,imp_implementation.producttype
				from imp_implementation  
				left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference 
				left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode 
				where imp_implementation.customerreference = '".$lastslno."' and imp_implementation.slno = '".$impslno."'";
				$fetch = runmysqlqueryfetch($query12);

				// if($fetch['implementationtype']!= "")
				// {
				// 	$impquery = "select * from imp_mas_implementationtype where slno = '".$fetch['implementationtype']."'";
				// 	$impfetch = runmysqlqueryfetch($impquery);
				// 	$implementationtype = $impfetch['imptype'];
				// }
				
				$query66 ="Delete from imp_rafiles where impref = '' or impref is null ;;";
				$result66 = runmysqlquery($query66);
				
				$query1 ="SELECT imp_addon.slno, imp_addon.customerid, imp_addon.refslno, imp_mas_addons.addonname as addon, imp_addon.remarks,imp_addon.addon as addonslno from imp_addon left join imp_mas_addons on imp_mas_addons.slno = imp_addon.addon where refslno  = '".$fetch['slno']."'; ";
				$resultfetch = runmysqlquery($query1);
				$valuecount = 0;
				while($fetchres = mysqli_fetch_array($resultfetch))
				{
					if($valuecount > 0)
						$addonarray .= '****';
					$addon = $fetchres['addon'];
					$remarks = $fetchres['remarks'];
					$slno = $fetchres['slno'];
					$addonslno = $fetchres['addonslno'];
					
					$addonarray .= $addon.'#'.$remarks.'#'.$slno.'#'.$addonslno;
					$valuecount++;
				}
				
				// $query13 = "SELECT count(*) as count from imp_cfentries where  imp_cfentries.invoiceno = '".$fetch['invoicenumber']."'";
				// $fetch13 = runmysqlqueryfetch($query13);
				
				// if($fetch['invoicenumber']!= "")
				// {
				// 	if($fetch13['count'] == 0)
				// 	{
				// 		$query = "select distinct inv_invoicenumbers.slno, inv_invoicenumbers.customerid,
				// 		inv_invoicenumbers.businessname, inv_invoicenumbers.description, inv_invoicenumbers.invoiceno, inv_invoicenumbers.netamount
				// 		from inv_invoicenumbers where  inv_invoicenumbers.invoiceno = '".$fetch['invoicenumber']."'";
				// 		$result = runmysqlqueryfetch($query);
				// 		$productsplit = explode('*',$result['description']);
				// 		$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="3" class="table-border-grid">';
				// 		$grid .='<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Slno</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Usage Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Pin Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Price</td></tr>';
				// 		for($i=0;$i<count($productsplit);$i++)
				// 		{
				// 			$splitproduct[] = explode('$',$productsplit[$i]);
				// 		}
				// 		$slno = 0;
				// 		for($j=0;$j<count($splitproduct);$j++)
				// 		{
				// 			$slno++;
				// 			$i_n++;
				// 			if($i_n%2 == 0)
				// 			$color = "#edf4ff";
				// 			else
				// 			$color = "#f7faff";
				// 			$grid .= '<tr class="gridrow1"  bgcolor='.$color.' align="left">';
				// 			$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$slno.'</td>';
				// 			$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$splitproduct[$j][1].'</td>';
				// 			$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$splitproduct[$j][2].'</td>';
				// 			$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$splitproduct[$j][3].'</td>';
				// 			$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$splitproduct[$j][4].'</td>';
				// 			$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$splitproduct[$j][6].'</td>';
				// 			$grid .= '</tr>';
				// 		}
				// 		$grid .= '</table>';
				// 	}
				// 	else
				// 	{
				// 		$query = "select distinct inv_mas_product.productname as product,imp_cfentries.usagetype,imp_cfentries.purchasetype,
				// 		inv_mas_scratchcard.scratchnumber,imp_cfentries.customerid from imp_cfentries left join inv_mas_product on inv_mas_product.productcode = imp_cfentries.productcode left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = imp_cfentries.cardid where imp_cfentries.invoiceno = '".$fetch['invoicenumber']."'";
				// 		$result = runmysqlquery($query);
				// 		$grid = '<table width="100%" border="0" cellspacing="0" cellpadding="3" class="table-border-grid">';
				// 		$grid .='<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Slno</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Usage Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Pin Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Price</td></tr>';
				// 		$slno = 0;
				// 		while($fetchres = mysqli_fetch_array($result))
				// 		{
				// 				$slno++;
				// 				$i_n++;
				// 				if($i_n%2 == 0)
				// 				$color = "#edf4ff";
				// 				else
				// 				$color = "#f7faff";
				// 				$grid .= '<tr class="gridrow1"  bgcolor='.$color.' align="left">';
				// 				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$slno.'</td>';
				// 				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$fetchres['product'].'</td>';
				// 				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$fetchres['usagetype'].'</td>';
				// 				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$fetchres['purchasetype'].'</td>';
				// 				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$fetchres['scratchnumber'].'</td>';
				// 				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">-</td>';
				// 				$grid .= '</tr>';
				// 				$customerid = substr($fetchres['customerid'],15);
				// 		}
				// 		$grid .= '</table>';
				// 	}
				// }
								
				$customervalidationarray = array();
				$customervalidationarray['errorcode'] = '1';
				$customervalidationarray['slno'] = $fetch['slno'];
				$customervalidationarray['customerreference'] = $fetch['customerreference'];
				$customervalidationarray['invoicenumber'] = $fetch['invoicenumber'];
				$customervalidationarray['implementationtype'] = $fetch['implementationtype'];
				$customervalidationarray['impremarks'] = $fetch['impremarks'];
				$customervalidationarray['advancecollected'] = $fetch['advancecollected'];
				$customervalidationarray['advanceamount'] = $fetch['advanceamount'];
				$customervalidationarray['advanceremarks'] = $fetch['advanceremarks'];
				$customervalidationarray['balancerecoveryremarks'] = $fetch['balancerecoveryremarks'];
				$customervalidationarray['podetails'] = $fetch['podetails'];
				$customervalidationarray['numberofcompanies'] = $fetch['numberofcompanies'];
				$customervalidationarray['numberofmonths'] = $fetch['numberofmonths'];
				$customervalidationarray['processingfrommonth'] = $fetch['processingfrommonth'];
				$customervalidationarray['additionaltraining'] = $fetch['additionaltraining'];
				$customervalidationarray['freedeliverables'] = $fetch['freedeliverables'];
				$customervalidationarray['schemeapplicable'] = $fetch['schemeapplicable'];
				$customervalidationarray['commissionapplicable'] = $fetch['commissionapplicable'];
				$customervalidationarray['commissionname'] = $fetch['commissionname'];
				$customervalidationarray['commissionmobile'] = $fetch['commissionmobile'];
				$customervalidationarray['commissionemail'] = $fetch['commissionemail'];
				$customervalidationarray['commissionvalue'] = $fetch['commissionvalue'];
				$customervalidationarray['masterdatabyrelyon'] = $fetch['masterdatabyrelyon'];
				$customervalidationarray['masternumberofemployees'] = $fetch['masternumberofemployees'];
				$customervalidationarray['salescommitments'] = $fetch['salescommitments'];
				$customervalidationarray['attendanceapplicable'] = $fetch['attendanceapplicable'];
				$customervalidationarray['attendanceremarks'] = $fetch['attendanceremarks'];
				$customervalidationarray['attendancefilepath'] = $fetch['attendancefilepath'];
				$customervalidationarray['shipinvoiceapplicable'] = $fetch['shipinvoiceapplicable'];
				$customervalidationarray['shipinvoiceremarks'] = $fetch['shipinvoiceremarks'];
				$customervalidationarray['shipmanualapplicable'] = $fetch['shipmanualapplicable'];
				$customervalidationarray['shipmanualremarks'] = $fetch['shipmanualremarks'];
				$customervalidationarray['customizationapplicable'] = $fetch['customizationapplicable'];
				$customervalidationarray['customizationremarks'] = $fetch['customizationremarks'];
				$customervalidationarray['customizationreffilepath'] = $fetch['customizationreffilepath'];
				$customervalidationarray['customizationbackupfilepath'] = $fetch['customizationbackupfilepath'];
				$customervalidationarray['customizationstatus'] = $fetch['customizationstatus'];
				$customervalidationarray['implementationstatus'] = $fetch['implementationstatus'];
				//$customervalidationarray['grid'] = $grid;
				$customervalidationarray['addonarray'] = $addonarray;
				$customervalidationarray['webimplemenationapplicable'] = $fetch['webimplemenationapplicable'];
				$customervalidationarray['webimplemenationremarks'] = $fetch['webimplemenationremarks'];
				$customervalidationarray['branchapproval'] = $fetch['branchapproval'];
				$customervalidationarray['attendancedeletefilepath'] = $fetch['attendancedeletefilepath'];
				$customervalidationarray['podetailspath'] = $fetch['podetailspath'];
				$customervalidationarray['customizationcustomerdisplay'] = $fetch['customizationcustomerdisplay'];
				$customervalidationarray['dealerid'] = $fetch['dealerid'];
				$customervalidationarray['committedstartdate'] = changedateformat($fetch['committedstartdate']);
				$customervalidationarray['podate'] = changedateformat($fetch['podate']);
				//echo('1^'.$fetch['slno'].'^'.$fetch['customerreference'].'^'.$fetch['invoicenumber'].'^'.$fetch['advancecollected'].'^'.$fetch['advanceamount'].'^'.$fetch['advanceremarks'].'^'.$fetch['balancerecoveryremarks'].'^'.$fetch['podetails'].'^'.$fetch['numberofcompanies'].'^'.$fetch['numberofmonths'].'^'.$fetch['processingfrommonth'].'^'.$fetch['additionaltraining'].'^'.$fetch['freedeliverables'].'^'.$fetch['schemeapplicable'].'^'.$fetch['commissionapplicable'].'^'.$fetch['commissionname'].'^'.$fetch['commissionmobile'].'^'.$fetch['commissionemail'].'^'.$fetch['commissionvalue'].'^'.$fetch['masterdatabyrelyon'].'^'.$fetch['masternumberofemployees'].'^'.$fetch['salescommitments'].'^'.$fetch['attendanceapplicable'].'^'.$fetch['attendanceremarks'].'^'.$fetch['attendancefilepath'].'^'.$fetch['shipinvoiceapplicable'].'^'.$fetch['shipinvoiceremarks'].'^'.$fetch['shipmanualapplicable'].'^'.$fetch['shipmanualremarks'].'^'.$fetch['customizationapplicable'].'^'.$fetch['customizationremarks'].'^'.$fetch['customizationreffilepath'].'^'.$fetch['customizationbackupfilepath'].'^'.$fetch['customizationstatus'].'^'.$fetch['implementationstatus'].'^'.$grid.'^'.$addonarray.'^'.$fetch['webimplemenationapplicable'].'^'.$fetch['webimplemenationremarks'].'^'.$fetch['branchapproval'].'^'.$fetch['attendancedeletefilepath'].'^'.$fetch['podetailspath'].'^'.$fetch['customizationcustomerdisplay'].'^'.$fetch['dealerid'].'^'.changedateformat($fetch['committedstartdate']));
			}
			else{
				$customervalidationarray['errorcode'] = '2';
				$customervalidationarray['errormsg'] = 'Create Implementation.';
			}

		}
		else
		{
			$customervalidationarray['errorcode'] = '2';
			$customervalidationarray['errormsg'] = 'Create Implementation.';
			//echo('2^'.'No Invoice Entry for Saral Paypack.');
		}
		echo(json_encode($customervalidationarray));
	}
	break;
	
	case 'invoiceconfirmation':
	{
		$rslno = $_POST['rslno'];
		$invoiceslno = $_POST['invoiceslno'];
		$query13 = "SELECT count(*) as count from imp_cfentries where  imp_cfentries.invoiceno = '".$rslno."'";
		$fetch13 = runmysqlqueryfetch($query13);
		$query = "select distinct inv_invoicenumbers.slno, inv_invoicenumbers.customerid,
		inv_invoicenumbers.businessname, inv_invoicenumbers.description, inv_invoicenumbers.invoiceno, inv_invoicenumbers.netamount,servicetype
		from inv_invoicenumbers where  inv_invoicenumbers.invoiceno = '".$rslno."'";

		$query989 = "select count(*) as count from inv_mas_receipt where invoiceno = '".$invoiceslno."'";
		$result11 = runmysqlqueryfetch($query989);
		if($result11['count'] == 0)
		{
			$receiptgrid = '<select name="receipt" class="diabledatefield" id="receipt" style="width: 201px;" disabled="disabled">
			<option selected="selected" value="" >---Select---</option></select>';
		}
		else
		{
			$query66 = "select * from inv_mas_receipt where invoiceno = '".$invoiceslno."'";
			$result11 = runmysqlquery($query66);
			$receiptgrid = '<select name="receipt" class="diabledatefield" id="receipt" style="width: 201px;" disabled="disabled">';
			$receiptgrid .= '<option selected="selected" value="" >---Select---</option>';
			while($fetch55 = mysqli_fetch_array($result11))
			{
				$receiptgrid .= '<option value="'.$fetch55['slno'].'">Receipt/'.$fetch55['slno'].' '.'-'.' '.'Rs'.' '.$fetch55['receiptamount'].'</option>';
			}
			$receiptgrid .= '</select>';
		}
		
		$result = runmysqlqueryfetch($query);
		$productsplit = explode('*',$result['description']);
		$grid = '<table width="100%" border="0" cellspacing="0" cellpadding="3" class="table-border-grid">';
		$grid .='<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Slno</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Usage Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Pin Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Net Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">&nbsp;</td></tr>';
		for($i=0;$i<count($productsplit);$i++)
		{
			$splitproduct[] = explode('$',$productsplit[$i]);
		}
		
		$slno = 0;
		if(!empty($result['description']))
		{
			for($j=0;$j<count($splitproduct);$j++)
			{
				$slno++;
				$i_n++;
				if($i_n%2 == 0)
				$color = "#edf4ff";
				else
				$color = "#f7faff";
				$grid .= '<tr  bgcolor='.$color.' align="left">';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$slno.'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$splitproduct[$j][1].'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$splitproduct[$j][2].'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$splitproduct[$j][3].'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$splitproduct[$j][4].'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$result['netamount'].'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left"><a onclick="viewinvoice(\''.$result['slno'].'\');" class="resendtext" style = "cursor:pointer"> View >></a> </td>';
				$grid .= '</tr>';
			}
		}
		
		if(!empty($result['servicetype']))
		{
			$servicetypesplit = explode('#',$result['servicetype']);
			for($k=0;$k<count($servicetypesplit);$k++)
			{
				$slno++;
				$i_n++;
				if($i_n%2 == 0)
				$color = "#edf4ff";
				else
				$color = "#f7faff";
				$grid .= '<tr  bgcolor='.$color.' align="left">';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$slno.'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$servicetypesplit[$k].'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left"></td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left"></td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left"></td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$result['netamount'].'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left"><a onclick="viewinvoice(\''.$result['slno'].'\');" class="resendtext" style = "cursor:pointer"> View >></a> </td>';
				$grid .= '</tr>';
			}
		}
		

		$grid .= '</table>';
		//echo('1'.'^'.$grid.'^'.$result['invoiceno'].'^'.substr($result['customerid'],15).'^'.$fetch13['count']);
		$responsearray32 = array();
		$responsearray32['errorcode'] = "1";
		$responsearray32['grid'] = $grid;
		$responsearray32['invoiceno'] = $result['invoiceno'];
		$responsearray32['customerid'] = substr($result['customerid'],15);
		$responsearray32['count'] = $fetch13['count'];
		$responsearray32['receiptgrid'] = $receiptgrid;
		echo(json_encode($responsearray32));
	}
	break;

	case 'invoicematrixconfirmation':
	{
		$rslno = $_POST['rslno'];
		$invoiceslno = $_POST['invoiceslno'];
		$query = "select distinct slno, customerid,businessname, description,invoiceno,netamount from inv_matrixinvoicenumbers where invoiceno = '".$rslno."'";

		$query989 = "select count(*) as count from inv_mas_receipt where matrixinvoiceno = '".$invoiceslno."'";
		$result11 = runmysqlqueryfetch($query989);
		if($result11['count'] == 0)
		{
			$receiptgrid = '<select name="receipt" class="diabledatefield" id="receipt" style="width: 201px;" disabled="disabled">
			<option selected="selected" value="" >---Select---</option></select>';
		}
		else
		{
			$query66 = "select * from inv_mas_receipt where matrixinvoiceno = '".$invoiceslno."'";
			$result11 = runmysqlquery($query66);
			$receiptgrid = '<select name="receipt" class="diabledatefield" id="receipt" style="width: 201px;" disabled="disabled">';
			$receiptgrid .= '<option selected="selected" value="" >---Select---</option>';
			while($fetch55 = mysqli_fetch_array($result11))
			{
				$receiptgrid .= '<option value="'.$fetch55['slno'].'">Receipt/'.$fetch55['slno'].' '.'-'.' '.'Rs'.' '.$fetch55['receiptamount'].'</option>';
			}
			$receiptgrid .= '</select>';
		}
		
		$result = runmysqlqueryfetch($query);
		$productsplit = explode('*',$result['description']);
		$grid = '<table width="100%" border="0" cellspacing="0" cellpadding="3" class="table-border-grid">';
		$grid .='<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Slno</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Pin Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Net Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">&nbsp;</td></tr>';
		for($i=0;$i<count($productsplit);$i++)
		{
			$splitproduct[] = explode('$',$productsplit[$i]);
		}
		$slno = 0;
		for($j=0;$j<count($splitproduct);$j++)
			{
				$slno++;
				$i_n++;
				if($i_n%2 == 0)
				$color = "#edf4ff";
				else
				$color = "#f7faff";
				$grid .= '<tr  bgcolor='.$color.' align="left">';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$slno.'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$splitproduct[$j][1].'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$splitproduct[$j][2].'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$splitproduct[$j][3].'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$result['netamount'].'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left"><a onclick="viewmatrixinvoice(\''.$result['slno'].'\');" class="resendtext" style = "cursor:pointer"> View >></a> </td>';
				$grid .= '</tr>';
			}
		$grid .= '</table>';
		//echo('1'.'^'.$grid.'^'.$result['invoiceno'].'^'.substr($result['customerid'],15).'^'.$fetch13['count']);
		$responsearray32 = array();
		$responsearray32['errorcode'] = "1";
		$responsearray32['grid'] = $grid;
		$responsearray32['invoiceno'] = $result['invoiceno'];
		$responsearray32['customerid'] = substr($result['customerid'],15);
		$responsearray32['count'] = '0';
		$responsearray32['receiptgrid'] = $receiptgrid;
		echo(json_encode($responsearray32));
	}
	break;
	
	case 'cfinvoiceconfirmation':
	{
		$rslno = $_POST['rslno'];
		
		$query = "select distinct inv_mas_product.productname as product,imp_cfentries.usagetype,imp_cfentries.purchasetype,
		inv_mas_scratchcard.scratchnumber,imp_cfentries.customerid from imp_cfentries left join inv_mas_product on inv_mas_product.productcode = imp_cfentries.productcode left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = imp_cfentries.cardid where imp_cfentries.invoiceno = '".$rslno."'";
		$result = runmysqlquery($query);
		$grid = '<table width="100%" border="0" cellspacing="0" cellpadding="3" class="table-border-grid">';
		$grid .='<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Slno</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Usage Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Pin Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Price</td></tr>';
		$valuecount = 0;
		while($fetchres = mysqli_fetch_array($result))
		{
				$slno++;
				$i_n++;
				if($i_n%2 == 0)
				$color = "#edf4ff";
				else
				$color = "#f7faff";
				$grid .= '<tr  bgcolor='.$color.' align="left">';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$slno.'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$fetchres['product'].'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$fetchres['usagetype'].'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$fetchres['purchasetype'].'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$fetchres['scratchnumber'].'</td>';
				$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">-</td>';
				$grid .= '</tr>';
				$customerid = substr($fetchres['customerid'],15);
		}
		$grid .= '</table>';
		//echo('2'.'^'.$grid.'^'.$rslno.'^'.$customerid);
		$responsearray33['errorcode'] = "2";
		$responsearray33['grid'] = $grid;
		$responsearray33['invoiceno'] = $rslno;
		$responsearray33['customerid'] = $customerid;
		echo(json_encode($responsearray33));
			
	}
	break;
	
	case 'save':
	{
		$lastslno = $_POST['lastslno'];
		$customerreference = $_POST['customerreference'];
		$invoicenumber = $_POST['invoicenumber'];
		$advancecollected = $_POST['advancecollected'];
		$advancecollecreceipt = $_POST['advancecollecreceipt'];
		$advanceamount = $_POST['advanceamount'];
		$advanceremarks = $_POST['advanceremarks'];
		$balancerecoveryremarks = $_POST['balancerecoveryremarks'];
		$podetails = $_POST['podetails'];
		$numberofcompanies = $_POST['numberofcompanies'];
		$numberofmonths = $_POST['numberofmonths'];
		$processingfrommonth = $_POST['processingfrommonth'];
		$additionaltraining = $_POST['additionaltraining'];
		$freedeliverables = $_POST['freedeliverables'];
		$schemeapplicable = $_POST['schemeapplicable'];
		$commissionapplicable = $_POST['commissionapplicable'];
		$commissionname = $_POST['commissionname'];
		$commissionmobile = $_POST['commissionmobile'];
		$commissionemail = $_POST['commissionemail'];
		$commissionvalue = $_POST['commissionvalue'];
		$masterdatabyrelyon = $_POST['masterdatabyrelyon'];
		$masternumberofemployees = $_POST['masternumberofemployees'];
		$salescommitments = $_POST['salescommitments'];
		$attendanceapplicable = $_POST['attendanceapplicable'];
		$attendanceremarks = $_POST['attendanceremarks'];
		
		$attendancedeletefilepath = $_POST['attendancedeletefilepath'];
		$attendancefilepath = $_POST['attendancefilepath'];
		$shipinvoiceapplicable = $_POST['shipinvoiceapplicable'];
		$shipinvoiceremarks = $_POST['shipinvoiceremarks'];
		$shipmanualapplicable = $_POST['shipmanualapplicable'];
		$shipmanualremarks = $_POST['shipmanualremarks'];
		$customizationapplicable = $_POST['customizationapplicable'];
		$customizationremarks = $_POST['customizationremarks'];
		$customizationreffilepath = $_POST['customizationreffilepath'];
		$customizationbackupfilepath = $_POST['customizationbackupfilepath'];
		$customizationstatus = $_POST['customizationstatus'];
		$webimplemenationapplicable = $_POST['webimplemenationapplicable'];
		$webimplemenationremarks = $_POST['webimplemenationremarks'];
		$pofilepath = $_POST['pofilepath'];
		$customizationcustomerview = $_POST['customizationcustomerview'];
	
		$contactarray = $_POST['contactarray'];
		$contactsplit = explode('~',$contactarray);
		$contactcount = count($contactsplit);
		$totalarray = $_POST['totalarray'];
		$totalsplit = explode(',',$totalarray);
		$rafslno = $_POST['rafslno'];
		$deleterafslno = $_POST['deleterafslno'];
		$dealerid = $_POST['dealerid'];
		$DPC_startdate = $_POST['DPC_startdate'];
		$DPC_podatedetails = $_POST['DPC_podatedetails'];
		$productcode = $_POST['productcode'];
		$imp_statustype = $_POST['imp_statustype'];
		$imptype_remarks = $_POST['imptype_remarks'];
		$producttype = $_POST['producttype'];
		
		if($rafslno != '')
		{
			$slnoraf = explode(',',$rafslno);
			$countdslno = count($slnoraf);
		}
		if($contactarray != '')
		{
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
		}
		if($lastslno == "")
		{
			$query99 = runmysqlqueryfetch("SELECT ifnull(MAX(slno),0)+1 AS slno FROM imp_implementation");
			$refslno = $query99['slno'];
			for($j=0;$j<$countdslno;$j++)
			{
				$queryres = "Update imp_rafiles set impref = '".$refslno."' where slno = '".$slnoraf[$j]."' ";
				$result11 = runmysqlquery($queryres);
			}

			$query = "Insert into imp_implementation(slno,customerreference,invoicenumber,implementationtype,impremarks,
			advancecollected, advanceamount,advanceremarks,balancerecoveryremarks,podetails,
			numberofcompanies,numberofmonths,processingfrommonth,additionaltraining,freedeliverables,schemeapplicable,
			commissionapplicable,commissionname,commissionmobile,commissionemail,commissionvalue,masterdatabyrelyon,
			masternumberofemployees,salescommitments,attendanceapplicable,attendanceremarks,attendancefilepath
			,attendancefiledate,attendancefileattachedby,shipinvoiceapplicable,shipinvoiceremarks,shipmanualapplicable,
			shipmanualremarks,customizationapplicable,customizationremarks,customizationreffilepath,customizationreffiledate
			,customizationreffileattachedby,customizationbackupfilepath,customizationbackupfiledate, customizationbackupfileattachedby,
			customizationstatus,webimplemenationapplicable,webimplemenationremarks,attendancedeletefilepath,podetailspath,podetailsdate,podetailsattachedby,customizationcustomerdisplay,dealerid,createdmodule,createddatetime,createdby,createdip,committedstartdate,advancecollecreceipt,productcode,podate,producttype) values('".$refslno."','".$customerreference."','".$invoicenumber."','".$imp_statustype."','".$imptype_remarks."','".$advancecollected."','".$advanceamount."','".$advanceremarks."','".$balancerecoveryremarks."','".$podetails."','".$numberofcompanies."','".$numberofmonths."','".$processingfrommonth."','".$additionaltraining."','".$freedeliverables."','".$schemeapplicable."','".$commissionapplicable."','".$commissionname."','".$commissionmobile."','".$commissionemail."','".$commissionvalue."','".$masterdatabyrelyon."','".$masternumberofemployees."','".$salescommitments."','".$attendanceapplicable."','".$attendanceremarks."','".$attendancefilepath."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$shipinvoiceapplicable."','".$shipinvoiceremarks."','".$shipmanualapplicable."','".$shipmanualremarks."','".$customizationapplicable."','".$customizationremarks."','".$customizationreffilepath."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$customizationbackupfilepath."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$customizationstatus."','".$webimplemenationapplicable."','".$webimplemenationremarks."','".$attendancedeletefilepath."','".$pofilepath."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$customizationcustomerview."','".$dealerid."','user_module','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".changedateformat($DPC_startdate)."','".$advancecollecreceipt."','".$productcode."','".changedateformat($DPC_podatedetails)."','".$producttype."') ";
			$result = runmysqlquery($query);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','23','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
			$eventresult = runmysqlquery($eventquery);

			if($contactarray != '')
			{
				for($j=0;$j<count($contactressplit);$j++)
				{
					$addontype = $contactressplit[$j][0];
					$addonremarks = $contactressplit[$j][1];
					$slno = $contactressplit[$j][2];
					
					$query21 = "Insert into imp_addon(customerid,refslno,addon,remarks) values 
					('".$customerreference."','".$refslno."','".$addontype."','".addslashes($addonremarks)."');";
					$result12 = runmysqlquery($query21);
				}
			}
			if($imp_statustype == '6' || $imp_statustype == '11')
			{
				$result = sendimplementationmail($refslno,$customerreference,$dealerid,$userid);
			}
			echo(json_encode("1^"."Customer Record Saved Successfully"."^".$refslno));
		}
		else
		{
			$query4 = 'SELECT * from imp_implementation WHERE slno = "'.$lastslno.'"';
			$queryfetch = runmysqlqueryfetch($query4);
			$e_customerreference = $queryfetch['customerreference'];
			$e_invoicenumber = $queryfetch['invoicenumber'];
			$e_advancecollected = $queryfetch['advancecollected'];
			$e_advanceamount = $queryfetch['advanceamount'];
			$e_advanceremarks = $queryfetch['advanceremarks'];
			$e_balancerecoveryremarks = $queryfetch['balancerecoveryremarks'];
			$e_podetails = $queryfetch['podetails'];
			$e_numberofcompanies = $queryfetch['numberofcompanies'];
			$e_numberofmonths = $queryfetch['numberofmonths'];
			$e_processingfrommonth = $queryfetch['processingfrommonth'];
			$e_additionaltraining = $queryfetch['additionaltraining'];
			$e_freedeliverables = $queryfetch['freedeliverables'];
			$e_schemeapplicable = $queryfetch['schemeapplicable'];
			$e_commissionapplicable = $queryfetch['commissionapplicable'];
			$e_commissionname = $queryfetch['commissionname'];
			$e_commissionmobile = $queryfetch['commissionmobile'];
			$e_commissionemail = $queryfetch['commissionemail'];
			$e_commissionvalue = $queryfetch['commissionvalue'];
			$e_masterdatabyrelyon = $queryfetch['masterdatabyrelyon'];
			$e_masternumberofemployees = $queryfetch['masternumberofemployees'];
			$e_salescommitments = $queryfetch['salescommitments'];
			$e_attendanceapplicable = $queryfetch['attendanceapplicable'];
			$e_attendanceremarks = $queryfetch['attendanceremarks'];
			$e_attendancefilepath = $queryfetch['attendancefilepath'];
			$e_shipinvoiceapplicable = $queryfetch['shipinvoiceapplicable'];
			$e_shipinvoiceremarks = $queryfetch['shipinvoiceremarks'];
			$e_shipmanualapplicable = $queryfetch['shipmanualapplicable'];
			$e_shipmanualremarks = $queryfetch['shipmanualremarks'];
			$e_customizationapplicable = $queryfetch['customizationapplicable'];
			$e_customizationremarks = $queryfetch['customizationremarks'];

			$e_customizationreffilepath = $queryfetch['customizationreffilepath'];
			$e_customizationbackupfilepath = $queryfetch['customizationbackupfilepath'];
			$e_customizationstatus = $queryfetch['customizationstatus'];
			$e_webimplemenationapplicable = $queryfetch['webimplemenationapplicable'];
			$e_webimplemenationremarks = $queryfetch['webimplemenationremarks'];
			$e_implementationstatus = $queryfetch['implementationstatus'];
			$e_podetailspath = $queryfetch['podetailspath'];
			$e_attendancedeletefilepath = $queryfetch['attendancedeletefilepath'];
			$e_customizationcustomerdisplay = $queryfetch['customizationcustomerdisplay'];
			$e_committedstartdate = $queryfetch['committedstartdate'];
			$e_advancecollecreceipt = $queryfetch['advancecollecreceipt'];
			$e_DPC_podatedetails = $queryfetch['podate'];
			$e_producttype = $queryfetch['producttype'];
			
			$query1 ="SELECT imp_addon.slno, imp_addon.customerid, imp_addon.refslno , imp_mas_addons.addonname as addon, imp_addon.remarks,imp_addon.addon as addonslno from imp_addon left join imp_mas_addons on imp_mas_addons.slno = imp_addon.addon where refslno  = '".$lastslno."'; ";
			$resultfetch = runmysqlquery($query1);
			$valuecount = 0;
			while($fetchres = mysqli_fetch_array($resultfetch))
			{
				if($valuecount > 0)
					$addonarray .= '****';
				$addon = $fetchres['addon'];
				$remarks = $fetchres['remarks'];
				$addonslno = $fetchres['addonslno'];
				
				$addonarray .= $addon.'#'.$remarks.'#'.$addonslno;
				$valuecount++;
			}

			
			$query23 = "Insert into imp_logs_implementation(customerreference,invoicenumber,
			advancecollected, advanceamount,advanceremarks,balancerecoveryremarks,podetails,
			numberofcompanies,numberofmonths,processingfrommonth,additionaltraining,freedeliverables,schemeapplicable,
			commissionapplicable,commissionname,commissionmobile,commissionemail,commissionvalue,masterdatabyrelyon,
			masternumberofemployees,salescommitments,attendanceapplicable,attendanceremarks,attendancefilepath
			,attendancefiledate,attendancefileattachedby,shipinvoiceapplicable,shipinvoiceremarks,shipmanualapplicable,
			shipmanualremarks,customizationapplicable,customizationremarks,customizationreffilepath,customizationreffiledate
			,customizationreffileattachedby,customizationbackupfilepath,customizationbackupfiledate, customizationbackupfileattachedby,
			customizationstatus,webimplemenationapplicable,webimplemenationremarks,implementationstatus,datetime,system,addondetails,attendancedeletefilepath,podetailspath,podetailsdate,podetailsattachedby,customizationcustomerdisplay,dealerid,createdmodule,createddatetime,createdby,createdip,committedstartdate,advancecollecreceipt,podate,producttype) values('".$e_customerreference."','".$e_invoicenumber."','".$e_advancecollected."','".addslashes($e_advanceamount)."','".addslashes($e_advanceremarks)."','".addslashes($e_balancerecoveryremarks)."','".addslashes($e_podetails)."','".$e_numberofcompanies."','".addslashes($e_numberofmonths)."','".addslashes($e_processingfrommonth)."','".addslashes($e_additionaltraining)."','".addslashes($e_freedeliverables)."','".addslashes($e_schemeapplicable)."','".addslashes($e_commissionapplicable)."','".addslashes($e_commissionname)."','".addslashes($e_commissionmobile)."','".addslashes($e_commissionemail)."','".addslashes($e_commissionvalue)."','".$e_masterdatabyrelyon."','".$e_masternumberofemployees."','".addslashes($e_salescommitments)."','".$e_attendanceapplicable."','".addslashes($e_attendanceremarks)."','".$e_attendancefilepath."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$e_shipinvoiceapplicable."','".addslashes($e_shipinvoiceremarks)."','".$e_shipmanualapplicable."','".addslashes($e_shipmanualremarks)."','".$e_customizationapplicable."','".addslashes($e_customizationremarks)."','".$e_customizationreffilepath."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$e_customizationbackupfilepath."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$e_customizationstatus."','".$e_webimplemenationapplicable."','".addslashes($e_webimplemenationremarks)."','".$e_implementationstatus."','".date('Y-m-d').' '.date('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','".$addonarray."','".addslashes($e_attendancedeletefilepath)."','".addslashes($e_podetailspath)."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".addslashes($e_customizationcustomerdisplay)."','".$dealerid."','dealer_module','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".changedateformat($e_committedstartdate)."','".$e_advancecollecreceipt."','".changedateformat($e_DPC_podatedetails)."','".$e_producttype."') ";
			$result = runmysqlquery($query23);
			if($deleterafslno != '')
			{
				$queryres ="Delete from imp_rafiles where slno = '".$deleterafslno."';";
				$result11 = runmysqlquery($queryres);
			}
			if($queryfetch['branchreject'] == 'yes')
			{
				$queryres = "UPDATE imp_implementation SET branchreject = 'no',branchapproval='no' WHERE imp_implementation.customerreference = '".$customerreference."' and imp_implementation.slno = '".$lastslno."'";
				$result11 = runmysqlquery($queryres);
			}
			$query11 = "UPDATE imp_implementation SET invoicenumber = '".$invoicenumber."',implementationtype='".$imp_statustype."',impremarks='".$imptype_remarks."',advancecollected = '".$advancecollected."',advanceamount = '".$advanceamount."',advanceremarks = '".$advanceremarks."',balancerecoveryremarks = '".$balancerecoveryremarks."',podetails = '".$podetails."',numberofcompanies = '".$numberofcompanies."',numberofmonths = '".$numberofmonths."',processingfrommonth = '".$processingfrommonth."',additionaltraining = '".$additionaltraining."',freedeliverables = '".$freedeliverables."',schemeapplicable ='".$schemeapplicable."',commissionapplicable ='".$commissionapplicable."',commissionname ='".$commissionname."',commissionmobile = '".$commissionmobile."' ,commissionemail = '".$commissionemail."' ,commissionvalue = '".$commissionvalue."',masterdatabyrelyon = '".$masterdatabyrelyon."' ,masternumberofemployees = '".$masternumberofemployees."',salescommitments = '".$salescommitments."',attendanceapplicable = '".$attendanceapplicable."',attendanceremarks = '".$attendanceremarks."',attendancefilepath = '".$attendancefilepath."',attendancefiledate = '".date('Y-m-d').' '.date('H:i:s')."',attendancefileattachedby = '".$userid."',shipinvoiceapplicable = '".$shipinvoiceapplicable."',shipinvoiceremarks = '".$shipinvoiceremarks."',shipmanualapplicable = '".$shipmanualapplicable."',shipmanualremarks = '".$shipmanualremarks."',customizationapplicable = '".$customizationapplicable."',customizationremarks = '".$customizationremarks."',customizationreffilepath = '".$customizationreffilepath."',customizationreffiledate = '".date('Y-m-d').' '.date('H:i:s')."',customizationreffileattachedby ='".$userid."',customizationbackupfilepath ='".$customizationbackupfilepath."',customizationbackupfiledate ='".date('Y-m-d').' '.date('H:i:s')."',customizationbackupfileattachedby = '".$userid."' ,customizationstatus = '".$customizationstatus."',webimplemenationapplicable = '".$webimplemenationapplicable."',webimplemenationremarks = '".$webimplemenationremarks."' ,attendancedeletefilepath = '".$attendancedeletefilepath."',podetailspath = '".$pofilepath."',podetailsdate ='".date('Y-m-d').' '.date('H:i:s')."',podetailsattachedby = '".$userid."',customizationcustomerdisplay = '".$customizationcustomerview."',dealerid = '".$dealerid."',committedstartdate = '".changedateformat($DPC_startdate)."',advancecollecreceipt = '".$advancecollecreceipt."',productcode = '".$productcode."', podate = '".changedateformat($DPC_podatedetails)."',producttype='".$e_producttype."' WHERE slno = '".$lastslno."'";
				$result = runmysqlquery($query11);
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','24','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
				$eventresult = runmysqlquery($eventquery);
				for($j=0;$j<$countdslno;$j++)
				{
					$queryres = "Update imp_rafiles set impref = '".$lastslno."' where slno = '".$slnoraf[$j]."' ";
					$result11 = runmysqlquery($queryres);
				}
				for($i=0;$i<count($totalsplit);$i++)
				{
					$deleteslno = $totalsplit[$i];
					$query22 = "DELETE FROM imp_addon WHERE slno = '".$deleteslno."'";
					$result = runmysqlquery($query22);
				}
				if($contactarray != '')
				{
					for($j=0;$j<count($contactressplit);$j++)
					{
						$addontype = $contactressplit[$j][0];
						$addonremarks = $contactressplit[$j][1];
						$slno = $contactressplit[$j][2];
						if($slno <> '')
						{
							$query21 = "UPDATE imp_addon SET addon = '".$addontype."',remarks = '".addslashes($addonremarks)."' WHERE slno = '".$slno."'";
							$result = runmysqlquery($query21);
						}
						else
						{
							$query23 = "Insert into imp_addon(customerid,refslno,addon,remarks) values  ('".$customerreference."','".$lastslno."','".$addontype."','".addslashes($addonremarks)."');";
							$result = runmysqlquery($query23);
						}
					}
				}
			
			echo(json_encode("2^"."Customer Record Saved Successfully"."^".$lastslno));
		}
			
		
	}
	break;
	
	case 'implementationvalidate':
	{
		$lastslno = $_POST['lastslno'];
		$query1 = "select count(distinct inv_invoicenumbers.slno) as count from inv_invoicenumbers
		left join inv_dealercard on inv_invoicenumbers.slno = inv_dealercard.invoiceid
		left join inv_mas_customer on inv_mas_customer.slno = substring(inv_invoicenumbers.customerid,16)
		left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode
		where inv_dealercard.productcode in ('265','816','640','604','643','851','871','874','834','836','835','841','838',
		'843','844','850','869','872','875','870','873','876','861','863',
		'862','864','262','261','269','266','267','268','237','238','877','878','879','880','882','883','884','001',
		'885','886','887','888','889','890','891','892','893','894','895','896','897','898','899','900','902','903','904','905','800','944','939','940','941','948','946','947','952','950','949','953','957','958','959','962')
		and substring(inv_invoicenumbers.customerid,16)  = '".$lastslno."'";
		$fetch1 = runmysqlqueryfetch($query1);
		$queryres ="Delete from imp_rafiles where impref = '' or impref is null ;";
		$result11 = runmysqlquery($queryres);
		
		$query12 = "SELECT count(*) as count from imp_cfentries where substring(imp_cfentries.customerid,16) = '".$lastslno."'";
		$fetch12 = runmysqlqueryfetch($query12);

		if($fetch1['count'] > 0)
		{
			echo(json_encode('1^'.'Invoice Found'));
			exit;
		}
		elseif($fetch12['count'] > 0)
		{
			echo(json_encode('1^'.'Invoice Found'));
			exit;
		}
		else
		{
			echo(json_encode('2^'.'No Invoice Entry for Saral Paypack.'));
			exit;
		}
	}
	break;
	case 'deletepath':
	{
		$pathlink = $_POST['pathlink'];
		$lastslno = $_POST['lastslno'];
		unlink($pathlink);
		$query = "Update imp_implementation set attendancedeletefilepath = '',attendancefilepath = '',attendancefiledate ='',attendancefileattachedby='' where slno = '".$lastslno."'";
		$result = runmysqlquery($query);
		echo(json_encode('1^'.'Success'));
	}
	break;
	case 'approve':
	{
		$responsearray11 = array();
		$customerreference = $_POST['customerreference'];
		$appremarks = $_POST['appremarks'];
		$remarks = $_POST['remarks'];
		$type = $_POST['type'];
		$lastslno = $_POST['lastslno'];
		$dealerid = $_POST['dealerid'];
		if($type == 'approved')
		{
			$query = "UPDATE imp_implementation SET branchreject = 'no',branchapproval = 'yes',coordinatorreject='no',approvalremarks = '".$remarks."', advancesnotcollectedremarks = '".$advremarks."',branchapprovalmodule = 'user_module', branchapprovaldatetime = '".date('Y-m-d').' '.date('H:i:s')."', branchapprovalip = '".$_SERVER['REMOTE_ADDR']."' , branchapprovalby = '".$userid."' WHERE imp_implementation.customerreference = '".$customerreference."' and imp_implementation.slno = '".$lastslno."'";
			$result = runmysqlquery($query);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','25','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
			$eventresult = runmysqlquery($eventquery);
			$result = sendbranchappovedmail($lastslno,$dealerid,$userid);
			$responsearray11['errorcode']= "3";
			$responsearray11['errormsg']= "Implemenation has been Approved.";
		}
		else if($type == 'rejected')
		{
			$query = "UPDATE imp_implementation SET branchreject = 'yes',branchapproval='no',branchrejectremarks = '".$remarks."', branchrejectmodule = 'dealer_module', branchrejectdatetime = '".date('Y-m-d').' '.date('H:i:s')."', branchrejectip = '".$_SERVER['REMOTE_ADDR']."' , branchrejectby = '".$userid."' WHERE imp_implementation.customerreference = '".$customerreference."' and imp_implementation.slno = '".$lastslno."'";
			$result = runmysqlquery($query);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','26','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
			$eventresult = runmysqlquery($eventquery);
			$result1 = sendbranchrejectmail($lastslno,$dealerid,$userid);
			if($result1 == 'sucess')
			{
				$responsearray11['errorcode']= "2";
				$responsearray11['errormsg']= "Implemenation Request has been Rejected.";
			}
		}
		echo(json_encode($responsearray11));
		//echo('3^'.'Implemenation has been Approved.');
	}
	break;
	case 'rafilesave':
	{
		$slnoarraylist = '';
		$slnolist = '';
		$requrimentremarks = $_POST['requrimentremarks'];
		$link_value = $_POST['link_value'];
		$lastslno = $_POST['lastslno'];
		$customerreference = $_POST['customerreference'];
		$impraflastslno = $_POST['impraflastslno'];
		if($impraflastslno == '')
		{
			
			$query = "Insert into imp_rafiles (customerreference,attachfilepath, remarks, createddatetime,createdby, createdip, lastupdateddatetime, lastupdatedip, lastupdatedby) values('".$customerreference."','".$link_value."','".$requrimentremarks."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".date('Y-m-d').' '.date('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','".$userid."');";
			$result = runmysqlquery($query);
			$query12 = "SELECT slno AS slno FROM imp_rafiles where customerreference = '".$customerreference."' and (`impref` is NULL or `impref` = '')";
			$result222 = runmysqlquery($query12);
			while($fetch = mysqli_fetch_array($result222))
			{
				$slnoarraylist .= $fetch['slno'].',';
			}
			$slnolist = trim($slnoarraylist,',');
		}
		else
		{
			$query = "Update imp_rafiles set attachfilepath =  '".$link_value."', remarks = '".$requrimentremarks."', lastupdateddatetime = '".date('Y-m-d').' '.date('H:i:s')."',lastupdatedip = '".$_SERVER['REMOTE_ADDR']."',lastupdatedby = '".$userid."',customerreference =  '".$customerreference."' where slno = '".$impraflastslno."' ; ";
			$result = runmysqlquery($query);
			$query12 = "SELECT slno AS slno FROM imp_rafiles where slno = '".$impraflastslno."'";
			$result222 = runmysqlquery($query12);
			while($fetch = mysqli_fetch_array($result222))
			{
				$slnoarraylist .= $fetch['slno'];
			}
			$slnolist = trim($slnoarraylist,',');
		}
		
		echo(json_encode('1^Record has been marked for Save'.'^'.$slnolist));
	}
	break;
	case 'rafiledelete':
	{
		$impraflastslno = $_POST['impraflastslno'];
		$customerreference = $_POST['customerreference'];
		$query1 = "select * from imp_rafiles where slno = '".$impraflastslno."';";
		$resultfetch = runmysqlqueryfetch($query1);
		$query12 = "SELECT slno AS slno FROM imp_rafiles where customerreference = '".$customerreference."'";
		$result222 = runmysqlquery($query12);
		while($fetch = mysqli_fetch_array($result222))
		{
			$slnoarraylist .= $fetch['slno'].',';
		}
		$slnolist = trim($slnoarraylist,',');
		//$query = "Delete from imp_rafiles where slno = '".$impraflastslno."';";
		//$result = runmysqlquery($query);
		//unlink($filepath);
		echo(json_encode('2^Record has been marked for Deletion '.'^'.$impraflastslno.'^'.$slnolist));
	}
	break;
	case 'rafilegrid':
	{
		$lastslno = $_POST['lastslno'];
		$imprefpiece = ($_POST['impref']!='') ? " or impref = ".$_POST['impref'] : "";
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];	
		$showtype = $_POST['showtype'];
		$resultcount = "SELECT count(*) as count from imp_rafiles where customerreference = '".$lastslno."';";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];;
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
		
		$impslno = [];
		$query0 = "select slno from `imp_rafiles` where customerreference =  '".$lastslno."' and (`impref` is NULL or `impref` = ''".$imprefpiece.");";
		$result0 = runmysqlquery($query0);
		$count0 = mysqli_num_rows($result0);
		if($count0 > 0)
		{
			while($fetch0 = mysqli_fetch_array($result0))
			{
				array_push($impslno,$fetch0['slno']);
			}
			$impref = implode(',',$impslno);
			$query = "SELECT slno,attachfilepath,remarks from imp_rafiles  WHERE `slno` in (".$impref.") order by createddatetime DESC LIMIT ".$startlimit.",".$limit.";";
		}
		else
		{
			$query = "SELECT imp_rafiles.slno,attachfilepath,remarks from imp_rafiles left join `imp_implementation` on imp_implementation.slno = imp_rafiles.`impref`  WHERE imp_implementation.customerreference = '".$lastslno."' and `implementationstatus`!= 'completed' order by imp_rafiles.createddatetime DESC LIMIT ".$startlimit.",".$limit.";";
		}
		//exit;
		
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header" align ="left"><td nowrap = "nowrap" class="td-border-grid" >Sl No</td><td nowrap = "nowrap" class="td-border-grid">File Name</td><td nowrap = "nowrap" class="td-border-grid">Remarks</td><td nowrap = "nowrap" class="td-border-grid">Download</td></tr>';
		}
		$i_n = 0;
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$i_n++;
			$slno++;
			$color;
			$filename = explode('/',$fetch['attachfilepath']);
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow" bgcolor='.$color.' onclick="rafgridtoform(\''.$fetch['slno'].'\'); " align ="left">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$slno."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$filename[5]."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".gridtrim($fetch['remarks'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'><div align = 'center'><img src='../images/download-arrow.gif'  onclick = viewfilepath('".$fetch['attachfilepath']."','1') /></div></td>";
			$grid .= "</tr>";
		}
		$fetchcount = mysqli_num_rows($result);
		if($fetchcount == '0')
			$grid .= "<tr><td colspan ='4'><div align='center'><font color='#FF0000'><strong>No Records to Display</strong></font></div></td></tr>";
		$grid .= "</table>";

		if($slno >= $fetchresultcount)
			$linkgrid = '';
			//$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid = '';
			//$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="getmoredatagrid(\''.$startlimit.'\',\''.$slno.'\',\'more\'); " style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmoredatagrid(\''.$startlimit.'\',\''.$slno.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
		$responsearray8 = array();
		$responsearray8['errorcode'] = "1";
		$responsearray8['grid'] = $grid;
		$responsearray8['linkgrid'] = $linkgrid;
		$responsearray8['fetchresultcount'] = $fetchresultcount;
		$responsearray8['fetchcount'] = $fetchcount;
		echo(json_encode($responsearray8));
		
		//echo '1^'.$grid.'^'.$linkgrid.'^'.$fetchresultcount.'^'.$fetchcount;
	}
	break;
	case 'rafgridtoform':
	{
		$rafgridtoformarray = array();
		$implastslno = $_POST['implastslno'];
		$query = "SELECT * from  imp_rafiles where slno = '".$implastslno."';";
		$fetch = runmysqlqueryfetch($query);
		$filename = explode('/',$fetch['attachfilepath']);
		$rafgridtoformarray['errorcode'] = '1';
		$rafgridtoformarray['filename'] = $filename[5];
		$rafgridtoformarray['remarks'] = $fetch['remarks'];
		$rafgridtoformarray['attachfilepath'] = $fetch['attachfilepath'];
		echo(json_encode($rafgridtoformarray));
	//	echo('1^'.$filename[5].'^'.$fetch['remarks'].'^'.$fetch['attachfilepath']);
	}
	break;
	case 'implemenationstatus':
	{
		$implemenationstatusarray = array();
		
		$lastslno = $_POST['lastslno'];
		$query = "SELECT imp_implementation.branchapproval,imp_implementation.approvalremarks as branchremarks,
		imp_implementation.coordinatorreject, imp_implementation.coordinatorrejectremarks,
		imp_implementation.branchreject,imp_implementation.branchrejectremarks as branchrejectremarks,
		imp_implementation.coordinatorapproval, imp_implementation.coordinatorapprovalremarks, 
		imp_implementation.implementationstatus, inv_mas_implementer.businessname, imp_implementation.advancecollected ,imp_implementation.advancesnotcollectedremarks from  imp_implementation 
		left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation 
		where imp_implementation.slno = '".$lastslno."';";
		$fetch = runmysqlqueryfetch($query);
		
		$query1 = "Select assigneddate from imp_implementationdays where imp_implementationdays.impref = '".$lastslno."';";
		$result = runmysqlquery($query1);
		$fetchcount = mysqli_num_rows($result);
		$tablegrid = '<table width="100%" border="0" cellspacing="0" cellpadding="5">';
		$tablegrid .= '<tr><td width="30%"><strong>Assigned To:</strong></td><td width="70%">'.$fetch['businessname'].'</td></tr>';
		$tablegrid .= '<tr><td><strong>No of Days:</strong></td><td>'.$fetchcount.'</td></tr></table>';
		$implemenationstatusarray['errorcode'] = '1';
		$implemenationstatusarray['branchapproval'] = $fetch['branchapproval'];
		$implemenationstatusarray['coordinatorreject'] = $fetch['coordinatorreject'];
		$implemenationstatusarray['coordinatorapproval'] = $fetch['coordinatorapproval'];
		$implemenationstatusarray['implementationstatus'] = $fetch['implementationstatus'];
		$implemenationstatusarray['branchremarks'] = $fetch['branchremarks'];
		$implemenationstatusarray['coordinatorrejectremarks'] = $fetch['coordinatorrejectremarks'];
		$implemenationstatusarray['coordinatorapprovalremarks'] = $fetch['coordinatorapprovalremarks'];
		$implemenationstatusarray['tablegrid'] = $tablegrid;
		$implemenationstatusarray['advancecollected'] = $fetch['advancecollected'];
		$implemenationstatusarray['advancesnotcollectedremarks'] = $fetch['advancesnotcollectedremarks'];
		$implemenationstatusarray['branchrejectremarks'] = $fetch['branchrejectremarks'];
		$implemenationstatusarray['branchreject'] = $fetch['branchreject'];
		echo(json_encode($implemenationstatusarray));
		
		
		//echo('1^'.$fetch['branchapproval'].'^'.$fetch['coordinatorreject'].'^'.$fetch['coordinatorapproval'].'^'.$fetch['implementationstatus'].'^'.$fetch['branchremarks'].'^'.$fetch['coordinatorrejectremarks'].'^'.$fetch['coordinatorapprovalremarks'].'^'.$tablegrid.'^'.$fetch['advancecollected'].'^'.$fetch['advancesnotcollectedremarks']);
		
	}
	break;
	
	case 'resetfunc':
	{
		$lastslno = $_POST['lastslno'];
		$customerreference = $_POST['customerreference'];
		$resultcount = "SELECT count(*) as count from imp_implementation where imp_implementation.slno = '".$lastslno."' and imp_implementation.customerreference = '".$customerreference."';";
		$fetch10 = runmysqlqueryfetch($resultcount);
		
		if($fetch10['count'] == '0')
		{
			$queryres ="Delete from imp_rafiles where impref = '' or impref is null ;";
			$result11 = runmysqlquery($queryres);

			echo(json_encode('2^'.'Record is not Found!!.'));
		}
		else
		{
			echo(json_encode('1^'.'Record is Found!!.'));
		}
	}
	
	break;
	case 'customizationgrid':
	{
		$implastslno = $_POST['imprslno'];
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];	
		$showtype = $_POST['showtype'];
		$resultcount = "SELECT count(*) as count from imp_customizationfiles where imp_customizationfiles.impref = '".$implastslno."';";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];;
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
		
		$query = "SELECT imp_customizationfiles.slno,imp_customizationfiles.remarks,imp_customizationfiles.attachfilepath from imp_customizationfiles  WHERE imp_customizationfiles.impref = '".$implastslno."' order by createddatetime DESC LIMIT ".$startlimit.",".$limit.";";
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="2" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header" align ="left"><td nowrap = "nowrap" class="td-border-grid" >Sl No</td><td nowrap = "nowrap" class="td-border-grid">Remarks</td><td nowrap = "nowrap" class="td-border-grid">Downloadlink</td></tr>';
		}
		$i_n = 0;
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$i_n++;
			$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow" bgcolor='.$color.'  align ="left">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$slno."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'>".gridtrim($fetch['remarks'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid'><div align = 'center'><img src='../images/download-arrow.gif'  onclick = viewfilepath('".$fetch['attachfilepath']."','1') /></div></td>";
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		if($slno >= $fetchresultcount)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="getmorecustomerregistration(\''.$startlimit.'\',\''.$slno.'\',\'more\'); " style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmorecustomerregistration(\''.$startlimit.'\',\''.$slno.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
		
		echo '1^'.$grid.'^'.$linkgrid.'^'.$fetchresultcount;
	}
	break;
	
	/*case 'appovedmail':
	{
		
		$lastslno = $_POST['lastslno'];
		$dealerid = $_POST['dealerid'];
		sendbranchappovedmail($lastslno,$dealerid,$userid);
		echo(json_encode("1^Mail has been sent Successfully."));
	}
	break;*/
	
	case 'updatedmail':
	{
		
		$lastslno = $_POST['lastslno'];
		$dealerid = $_POST['dealerid'];
		sendupdateimpmail($lastslno,$dealerid,$userid);
		echo(json_encode("1^Mail has been sent Successfully."));
	}
	break;
	
	case 'shipinvoicemail':
	{
		
		$customerid = $_POST['customerid'];
		$type = $_POST['type'];
		$remarks = $_POST['remarks'];
		$result = sendshippmentmail($customerid,$type,$remarks);
		echo(json_encode("1^Mail has been sent Successfully."));
	}
	break;
	
	case 'searchbycustomerid':
	{
		$searchbycustomeridarray = array();
		$customerid = $_POST['customerid'];
		$customeridlen = strlen($customerid);
		$customerid = ($_POST['customerid'] == 5)?($_POST['customerid']):(substr($customerid, $customeridlen - 5));
		
		$query1 = "SELECT count(*) as count from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode where inv_mas_customer.slno = '".$customerid."'";
		$fetch1 = runmysqlqueryfetch($query1);
			
		if($fetch1['count'] > 0)
		{
			$query2 = "SELECT distinct inv_mas_customer.slno as slno,inv_mas_customer.businessname as businessname,inv_mas_customer.customerid as customerid from inv_mas_customer where inv_mas_customer.slno = '".$customerid."'";
			$fetch123 = runmysqlqueryfetch($query2);
			$searchbycustomeridarray['errorcode'] = '1';
			$searchbycustomeridarray['slno'] = $fetch123['slno'];
			$searchbycustomeridarray['customerid'] = $fetch123['customerid'];
			$searchbycustomeridarray['businessname'] = $fetch123['businessname'];
			
			
			
			//echo('1^'.$fetch123['slno'].'^'.$fetch123['customerid'].'^'.$fetch123['businessname']);			
		}
		else
		{
			$searchbycustomeridarray['errorcode'] = '2';
			//echo('2^'.'Not Found the details');
		}
		echo(json_encode($searchbycustomeridarray));
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
	
	case 'searchcustomerlist':
	{
		$databasefield = $_POST['databasefield'];
		$textfield = $_POST['textfield'];
		$state2 = $_POST['state'];
		$region2 = $_POST['region'];
		$dealer2 = $_POST['dealer2'];
		$branch2 = $_POST['branch2'];
		$category2= $_POST['category2'];
		$type2= $_POST['type2'];
		$statuslist = $_POST['statuslist'];
		$statuslistsplit = explode(',',$statuslist);
		$countsummarize = count($statuslistsplit);
		for($i = 0; $i<$countsummarize; $i++)
		{
			if($i < ($countsummarize-1))
					$appendor = 'or'.' ';
				else
					$appendor = '';
			switch($statuslistsplit[$i])
			{
				case 'status1' :
				{
					$statuspiece = " imp_implementation.branchapproval = 'no' AND imp_implementation.branchreject = 'yes' AND imp_implementation.implementationstatus = 'pending' ";
				}
				break;
				case 'status2' :
				{
					$statuspiece = " imp_implementation.branchapproval = 'no' AND imp_implementation.branchreject = 'no' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'no' AND  imp_implementation.implementationstatus = 'pending'";
				}
				break;
				case 'status3' :
				{
					$statuspiece = " imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.implementationstatus = 'pending' ";
				}
				break;
				case 'status4' :
				{
					$statuspiece = " imp_implementation.branchapproval = 'no' AND imp_implementation.coordinatorreject = 'yes' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.implementationstatus = 'pending' ";
				}
				break;
				case 'status5' :
				{
					$statuspiece = " imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'pending' ";
				}
				break;
				case 'status6' :
				{
					$statuspiece = " imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'assigned' ";
				}
				break;
				case 'status7' :
				{
					$statuspiece = " imp_implementation.branchapproval = 'yes'  AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'progess' ";
				}
				break;
				case 'status8' :
				{
					$statuspiece = " imp_implementation.branchapproval = 'yes'  AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'completed' ";
				}
				break;
				case 'status9' :
				{
					$statuspiece = "right(imp_cfentries.customerid,5) not in (select customerreference from imp_implementation)";
				}
				break;
			}
			$finalstatuslist .= ''.'('.$statuspiece.')'.'  '.$appendor.'';
		}
		if($finalstatuslist != '')
		{
			$finalliststatus = ' AND'.'('.$finalstatuslist.')';
		}
		else
		{
			$finalliststatus = "";
		}
		
		$regionpiece = ($region2 == "")?(""):(" AND inv_mas_dealer.region = '".$region2."' ");
		$state_typepiece = ($state2 == "")?(""):(" AND inv_mas_district.statecode = '".$state2."' ");
		$district_typepiece = ($district2 == "")?(""):(" AND inv_mas_customer.district = '".$district2."' ");
		$dealer_typepiece = ($dealer2 == "")?(""):(" AND inv_mas_customer.currentdealer = '".$dealer2."' ");
		$branchpiece = ($branch2 == "")?(""):(" AND inv_mas_dealer.branch = '".$branch2."' ");
		if($type2 == 'Not Selected')
		{
			$typepiece = ($type2 == "")?(""):(" AND inv_mas_customer.type = ''");
		}
		else
		{
			$typepiece = ($type2 == "")?(""):(" AND inv_mas_customer.type = '".$type2."' ");
		}
		if($category2 == 'Not Selected')
		{
			$categorypiece = ($category2 == "")?(""):(" AND inv_mas_customer.category = ''");
		}
		else
		{
			$categorypiece = ($category2 == "")?(""):(" AND inv_mas_customer.category = '".$category2."' ");
		}
		
		switch($databasefield)
		{
			case 'slno':
			{
				$customeridlen = strlen($textfield);
				$lastcustomerid = cusidsplit($textfield);
				$query = "select DISTINCT inv_mas_customer.slno,inv_mas_customer.businessname from inv_mas_customer 
				left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno  
				left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode
				left join imp_implementation on imp_implementation.customerreference = inv_mas_customer.slno
				left join imp_implementationdays on imp_implementationdays.impref = imp_implementation.slno
				left join imp_cfentries on right(imp_cfentries.customerid,5) = inv_mas_customer.slno
				left join inv_mas_dealer on imp_implementation.dealerid = inv_mas_dealer.slno 
				where (inv_mas_customer.slno LIKE '%".$lastcustomerid."%' OR inv_mas_customer.customerid LIKE '%".$lastcustomerid."%') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$typepiece.$categorypiece.$branchpiece.$finalliststatus."  ORDER BY inv_mas_customer.businessname"; //echo($query);exit;
				$result = runmysqlquery($query);
			}
			break;
			
			case 'contactperson':
			{
				$query = "select DISTINCT inv_mas_customer.slno,inv_mas_customer.businessname from inv_mas_customer 
				left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno  
				left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode
				left join imp_implementation on imp_implementation.customerreference = inv_mas_customer.slno
				left join imp_implementationdays on imp_implementationdays.impref = imp_implementation.slno
				left join imp_cfentries on right(imp_cfentries.customerid,5) = inv_mas_customer.slno
				left join inv_mas_dealer on imp_implementation.dealerid = inv_mas_dealer.slno 
				where inv_contactdetails.contactperson LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$typepiece.$categorypiece.$branchpiece.$finalliststatus."  ORDER BY inv_mas_customer.businessname";
				$result = runmysqlquery($query);
			}
			break;
			
			case 'place':
			{
				$query = "select DISTINCT inv_mas_customer.slno,inv_mas_customer.businessname from inv_mas_customer 
				left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno  
				left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode
				left join imp_implementation on imp_implementation.customerreference = inv_mas_customer.slno
				left join imp_implementationdays on imp_implementationdays.impref = imp_implementation.slno
				left join imp_cfentries on right(imp_cfentries.customerid,5) = inv_mas_customer.slno
				left join inv_mas_dealer on imp_implementation.dealerid = inv_mas_dealer.slno 
				where inv_mas_customer.place LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$typepiece.$categorypiece.$branchpiece.$finalliststatus."  ORDER BY inv_mas_customer.businessname";
				$result = runmysqlquery($query);
			}
			break;
			
			case 'phone':
			{
				$query = "select DISTINCT inv_mas_customer.slno,inv_mas_customer.businessname from inv_mas_customer 
				left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno  
				left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode
				left join imp_implementation on imp_implementation.customerreference = inv_mas_customer.slno
				left join imp_implementationdays on imp_implementationdays.impref = imp_implementation.slno
				left join imp_cfentries on right(imp_cfentries.customerid,5) = inv_mas_customer.slno
				left join inv_mas_dealer on imp_implementation.dealerid = inv_mas_dealer.slno 
				where (inv_contactdetails.phone LIKE '%".$textfield."%'  OR inv_contactdetails.cell LIKE '%".$textfield."%') ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$typepiece.$categorypiece.$branchpiece.$finalliststatus."  ORDER BY inv_mas_customer.businessname";
				$result = runmysqlquery($query);
			}
			break;
			
			case 'emailid':
			{
				$query = "select DISTINCT inv_mas_customer.slno,inv_mas_customer.businessname from inv_mas_customer 
				left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno  
				left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode
				left join imp_implementation on imp_implementation.customerreference = inv_mas_customer.slno
				left join imp_implementationdays on imp_implementationdays.impref = imp_implementation.slno
				left join imp_cfentries on right(imp_cfentries.customerid,5) = inv_mas_customer.slno
				left join inv_mas_dealer on imp_implementation.dealerid = inv_mas_dealer.slno 
				where inv_contactdetails.emailid LIKE '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$typepiece.$categorypiece.$branchpiece.$finalliststatus."  ORDER BY inv_mas_customer.businessname";
				$result = runmysqlquery($query);
			}
			break;
			
			default:
			{
				$query = "select DISTINCT inv_mas_customer.slno,inv_mas_customer.businessname from inv_mas_customer 
				left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno  
				left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode
				left join imp_implementation on imp_implementation.customerreference = inv_mas_customer.slno
				left join imp_implementationdays on imp_implementationdays.impref = imp_implementation.slno
				left join imp_cfentries on right(imp_cfentries.customerid,5) = inv_mas_customer.slno
				left join inv_mas_dealer on imp_implementation.dealerid = inv_mas_dealer.slno 
				where inv_mas_customer.businessname LIKE  '%".$textfield."%' ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$typepiece.$categorypiece.$branchpiece.$finalliststatus."  ORDER BY inv_mas_customer.businessname"; //echo($query);exit;
				$result = runmysqlquery($query);
			}
			break;
		}
		$searchcustomerlistarray = array();
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$searchcustomerlistarray[$count] = $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
		echo(json_encode($searchcustomerlistarray));
	}
	break;
	
	case 'filtercustomerlist':
	{
		$status = $_POST['impsearch'];
		$statuspiece = '';
		if($status == 'Awaiting Branch Head Approval')
		{
			$statuspiece = "AND imp_implementation.branchapproval = 'no' AND imp_implementation.branchreject = 'no'  AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.implementationstatus = 'pending'";
		}
		else if($status == 'Fowarded back to Sales Person')
		{
			$statuspiece = "AND imp_implementation.branchapproval = 'no' AND imp_implementation.branchreject = 'yes' AND imp_implementation.implementationstatus = 'pending'";
		}
		else if($status == 'Awaiting Co-ordinator Approval')
		{
			$statuspiece = "AND imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.implementationstatus = 'pending'";
		}
		else if($status == 'Fowarded back to Branch Head')
		{
			$statuspiece = "AND imp_implementation.branchapproval = 'no' AND imp_implementation.coordinatorreject = 'yes' AND imp_implementation.coordinatorapproval = 'no' AND imp_implementation.implementationstatus = 'pending'";
		}
		else if($status == 'Implementation, Yet to be Assigned')
		{
			$statuspiece = "AND imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'pending'";
		}
		else if($status == 'Assigned For Implementation')
		{
			$statuspiece = "AND imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'assigned'";
		}
		else if($status == 'Implementation in progess')
		{
			$statuspiece = "AND imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'progess'";
		}
		else if($status == 'Implementation Completed')
		{
			$statuspiece = "AND imp_implementation.branchapproval = 'yes' AND imp_implementation.coordinatorreject = 'no' AND imp_implementation.coordinatorapproval = 'yes' AND imp_implementation.implementationstatus = 'completed'";
		}

		if($status == "")
		{
			$query = "select DISTINCT inv_mas_customer.slno,inv_mas_customer.businessname from inv_mas_customer 
			left join imp_implementation on imp_implementation.customerreference = inv_mas_customer.slno
			where inv_mas_customer.slno <> '99999999999' ".$statuspiece." ORDER BY businessname";
			$result = runmysqlquery($query);
			$filtercustomerlistarray = array();
			$count = 0;
			while($fetch = mysqli_fetch_array($result))
			{
				$filtercustomerlistarray[$count] = $fetch['businessname'].'^'.$fetch['slno'];
				$count++;
			}
		}
		else
		{
			$query0 = "select max(imp_implementation.slno) as impslno,inv_mas_customer.businessname,imp_implementation.customerreference from imp_implementation left join inv_mas_customer on imp_implementation.customerreference = inv_mas_customer.slno where inv_mas_customer.slno <> '99999999999' group by imp_implementation.customerreference ORDER BY businessname;";
			$result0 = runmysqlquery($query0);
			$filtercustomerlistarray = array();
			$count = 0;
			while($fetch0 = mysqli_fetch_array($result0))
			{
				$query = "select  inv_mas_customer.slno,inv_mas_customer.businessname from inv_mas_customer 
				left join imp_implementation on imp_implementation.customerreference = inv_mas_customer.slno
				where inv_mas_customer.slno = '".$fetch0['customerreference']."' and imp_implementation.slno = '".$fetch0['impslno']."' ".$statuspiece." ORDER BY businessname";
				$result = runmysqlquery($query);
				// $filtercustomerlistarray = array();
				// $count = 0;
				while($fetch = mysqli_fetch_array($result))
				{
					$filtercustomerlistarray[$count] = $fetch['businessname'].'^'.$fetch['slno'];
					$count++;
				}
			}
		}
		
		echo(json_encode($filtercustomerlistarray));
	}
	break;
	case 'invoicecfdetailstoform':
	{
		$grid = '';
		$responsearray21 = array();
		$implslno = $_POST['implslno'];
		$query11 = "SELECT * from imp_implementation where  imp_implementation.slno = '".$implslno."'";
		$fetch = runmysqlqueryfetch($query11);
		$advancecollecreceipt = $fetch['advancecollecreceipt'];
		$productcodevalue = $fetch['productcode'];
		
		// if($fetch['invoicenumber']!= "")
		// {
			$query13 = "SELECT count(*) as count from imp_cfentries where  imp_cfentries.invoiceno = '".$fetch['invoicenumber']."'";
			$fetch13 = runmysqlqueryfetch($query13);
			if($fetch13['count'] == 0)
			{
				if($fetch['producttype']!= 'matrix')
				{
					$query = "select distinct inv_invoicenumbers.slno, inv_invoicenumbers.customerid,
					inv_invoicenumbers.businessname, inv_invoicenumbers.description, inv_invoicenumbers.invoiceno, inv_invoicenumbers.netamount,servicetype
					from inv_invoicenumbers where  inv_invoicenumbers.invoiceno = '".$fetch['invoicenumber']."'";
				}
				else
				{
					$query = "select distinct slno, customerid,businessname, description,invoiceno,netamount from inv_matrixinvoicenumbers where invoiceno = '".$fetch['invoicenumber']."'";
				}
				
				$result1 = runmysqlquery($query);
				$count1 = mysqli_num_rows($result1);
				if($count1 > 0)
				{
					$result = runmysqlqueryfetch($query);
					$productsplit = explode('*',$result['description']);
					$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="3" class="table-border-grid">';
					$grid .='<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Slno</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Usage Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Pin Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Net Amouont</td><td nowrap = "nowrap" class="td-border-grid" align="left">&nbsp;</td></tr>';
					for($i=0;$i<count($productsplit);$i++)
					{
						$splitproduct[] = explode('$',$productsplit[$i]);
					}
					$slno = 0;
					if(!empty($result['description']))
					{
						for($j=0;$j<count($splitproduct);$j++)
						{
							$slno++;
							$i_n++;
							if($i_n%2 == 0)
							$color = "#edf4ff";
							else
							$color = "#f7faff";
							$grid .= '<tr   bgcolor='.$color.' align="left">';
							$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$slno.'</td>';
							$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$splitproduct[$j][1].'</td>';
							$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$splitproduct[$j][2].'</td>';
							if($fetch['producttype']!= 'matrix')
								$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$splitproduct[$j][3].'</td>';
							else
								$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left"></td>';
							$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$splitproduct[$j][4].'</td>';
							$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$result['netamount'].'</td>';
							if($fetch['producttype']!= 'matrix')
								$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left"><a onclick="viewinvoice(\''.$result['slno'].'\');" class="resendtext" style = "cursor:pointer"> View >></a> </td>';
							else
								$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left"><a onclick="viewmatrixinvoice(\''.$result['slno'].'\');" class="resendtext" style = "cursor:pointer"> View >></a> </td>';
							$grid .= '</tr>';
						}
					}
					if(!empty($result['servicetype']))
					{
						$servicetypesplit = explode('#',$result['servicetype']);
						for($k=0;$k<count($servicetypesplit);$k++)
						{
							$slno++;
							$i_n++;
							if($i_n%2 == 0)
							$color = "#edf4ff";
							else
							$color = "#f7faff";
							$grid .= '<tr  bgcolor='.$color.' align="left">';
							$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$slno.'</td>';
							$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$servicetypesplit[$k].'</td>';
							$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left"></td>';
							$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left"></td>';
							$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left"></td>';
							$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$result['netamount'].'</td>';
							$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left"><a onclick="viewinvoice(\''.$result['slno'].'\');" class="resendtext" style = "cursor:pointer"> View >></a> </td>';
							$grid .= '</tr>';
						}
					}
					$grid .= '</table>';
				}
				
				$invoiceno = ($fetch['producttype']!= 'matrix') ? 'invoiceno' : 'matrixinvoiceno';
				if($advancecollecreceipt <> '')
				{ 
					$query989 = "select count(*) as count from inv_mas_receipt where ".$invoiceno." = '".$result['slno']."'";
					$result11 = runmysqlqueryfetch($query989);
					if($result11['count'] == 0)
					{
						$receiptgrid = '<select name="receipt" class="diabledatefield" id="receipt" style="width: 201px;" >
						<option selected="selected" value="" >---Select---</option></select>';
					}
					else
					{
						$query66 = "select * from inv_mas_receipt where ".$invoiceno." = '".$result['slno']."'";
						$result11 = runmysqlquery($query66);
						$receiptgrid = '<select name="receipt" class="diabledatefield" id="receipt" style="width: 201px;" >';
						$receiptgrid .= '<option  value="" >---Select---</option>';
						while($fetch55 = mysqli_fetch_array($result11))
						{
							$receiptgrid .= '<option  ' . ($advancecollecreceipt == $fetch55['slno'] ? 'selected="selected" ' : '') . ' value="'.$fetch55['slno'].'">Receipt/'.$fetch55['slno'].' '.'-'.' '.'Rs'.' '.$fetch55['receiptamount'].'</option>'; 
							
						}
						$receiptgrid .= '</select>';
					}
				}
				else
				{
					$query989 = "select count(*) as count from inv_mas_receipt where ".$invoiceno." = '".$result['slno']."'";
					$result11 = runmysqlqueryfetch($query989);
					if($result11['count'] == 0)
					{
						$receiptgrid = '<select name="receipt" class="diabledatefield" id="receipt" style="width: 201px;" >
						<option selected="selected" value="" >---Select---</option></select>';
					}
					else
					{
						$query66 = "select * from inv_mas_receipt where ".$invoiceno." = '".$result['slno']."'";
						$result11 = runmysqlquery($query66);
						$receiptgrid = '<select name="receipt" class="diabledatefield" id="receipt" style="width: 201px;" >';
						$receiptgrid .= '<option  value="" selected="selected" >---Select---</option>';
						while($fetch55 = mysqli_fetch_array($result11))
						{
							$receiptgrid .= '<option  value="'.$fetch55['slno'].'">Receipt/'.$fetch55['slno'].' '.'-'.' '.'Rs'.' '.$fetch55['receiptamount'].'</option>'; 
							
							//$receiptgrid .= '<option value="'.$fetch55['slno'].'">Receipt/'.$fetch55['slno'].' '.'-'.' '.'Rs'.' '.$fetch55['receiptamount'].'</option>';
						}
						$receiptgrid .= '</select>';
					}
				}
				$responsearray21['errorcode'] = "1";
				$responsearray21['customerreference'] = $fetch['customerreference'];
				$responsearray21['invoicenumber'] = $fetch['invoicenumber'];
				$responsearray21['grid'] = $grid;
				$responsearray21['receiptgrid'] = $receiptgrid;
				$responsearray21['advanceremarks'] = $fetch['advanceremarks'];
				$responsearray21['advancecollected'] = $fetch['advancecollected'];
				$responsearray21['productcode'] = $productcodevalue;
				echo(json_encode($responsearray21));
				
			}
			else
			{
				$query = "select distinct inv_mas_product.productname as product,imp_cfentries.usagetype,imp_cfentries.purchasetype,
				inv_mas_scratchcard.scratchnumber,imp_cfentries.customerid,imp_cfentries.productcode as productcode from imp_cfentries left join inv_mas_product on inv_mas_product.productcode = imp_cfentries.productcode left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = imp_cfentries.cardid where imp_cfentries.invoiceno = '".$fetch['invoicenumber']."'";
				$result = runmysqlquery($query);
				$grid = '<table width="100%" border="0" cellspacing="0" cellpadding="3" class="table-border-grid">';
				$grid .='<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Slno</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Usage Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Pin Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Price</td></tr>';
				$slno = 0;
				while($fetchres = mysqli_fetch_array($result))
				{
						$slno++;
						$i_n++;
						if($i_n%2 == 0)
						$color = "#edf4ff";
						else
						$color = "#f7faff";
						$grid .= '<tr bgcolor='.$color.' align="left">';
						$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$slno.'</td>';
						$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$fetchres['product'].'</td>';
						$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$fetchres['usagetype'].'</td>';
						$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$fetchres['purchasetype'].'</td>';
						$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">'.$fetchres['scratchnumber'].'</td>';
						$grid .= '<td nowrap = "nowrap" class="td-border-grid" align="left">-</td>';
						$grid .= '</tr>';
						$customerid = substr($fetchres['customerid'],15);
				}
				$grid .= '</table>';
				
				$responsearray21['errorcode'] = "2";
				$responsearray21['customerreference'] = $fetch['customerreference'];
				$responsearray21['invoicenumber'] = $fetch['invoicenumber'];
				$responsearray21['grid'] = $grid;
				$responsearray21['advanceamount'] = $fetch['advanceamount'];
				$responsearray21['advanceremarks'] = $fetch['advanceremarks'];
				$responsearray21['advancecollected'] = $fetch['advancecollected'];
				$responsearray21['productcode'] = $fetch['productcode'];
				echo(json_encode($responsearray21));
			}
		// }
		// else
		// {
		// 	$responsearray21['errorcode'] = "3";
		// 	echo(json_encode($responsearray21));
		// }
		
	}
	break;
	case 'advcollectmail':
	{
		$responsearray20= array();
		$lastslno = $_POST['lastslno'];
		$customerid = $_POST['customerid'];
		$type = $_POST['type'];
		$result = sendadvcollectmail($lastslno,$customerid,$type,$userid);
		if($result == 'sucess')
		{
			$responsearray20['errorcode']= '1';
			$responsearray20['errormsg']= 'Mail sent Successfully.';
		}
		echo(json_encode($responsearray20));
	}
	break;
	}
	
?>