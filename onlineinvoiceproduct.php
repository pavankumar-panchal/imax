<?php 
include('./functions/phpfunctions.php');

//$query = "select * from inv_bill where onlineinvoiceno in ('8','9','10','11','12','13','14','15','16','17','18','23');";
$query0 = "select distinct  inv_dealercard.customerreference,inv_bill.slno as slno,inv_bill.onlineinvoiceno as onlineinvoiceno,
left(inv_bill.billdate,10) as billdate
from inv_bill
left join inv_billdetail on inv_billdetail.cusbillnumber = inv_bill.slno
left join inv_dealercard on inv_dealercard.cusbillnumber = inv_billdetail.cusbillnumber
where inv_bill.onlineinvoiceno in ('1','2','3','4','5','6','7','19','20','21','22') order by inv_bill.onlineinvoiceno; ";
$result1 = runmysqlquery($query0);
$count = 0;

	while($fetch1 = mysqli_fetch_array($result1))
	{
		$custreference = $fetch1['customerreference'];
		$firstbillnumber = $fetch1['slno'];
		$onlineinvoiceno = $fetch1['onlineinvoiceno'];
		$billdate = $fetch1['billdate'];
		
		$query = "select inv_mas_customer.businessname as companyname,inv_mas_customer.contactperson,inv_mas_customer.phone,inv_mas_customer.cell,
	inv_mas_customer.emailid,inv_mas_customer.place,inv_mas_customer.address,inv_mas_region.category as region,inv_mas_branch.branchname as branchname,inv_mas_customercategory.businesstype,inv_mas_customertype.customertype,inv_mas_dealer.businessname as dealername,inv_mas_customer.stdcode, inv_mas_customer.pincode,inv_mas_district.districtname, inv_mas_state.statename,inv_mas_customer.customerid  from inv_mas_customer left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch
	left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_customer.slno = '".$custreference."';";
	
		$fetchresult = runmysqlqueryfetch($query);
		
		
		// Fetch contact Details
		
		$querycontactdetails = "select group_concat(phone) as phone,group_concat(cell) as cell ,group_concat(emailid) as emailid,group_concat(contactperson) as contactperson from inv_contactdetails where customerid = '".$custreference."'";
		$fetchcontactdetails = runmysqlqueryfetch($querycontactdetails);
		
		
		$query1 = "SELECT inv_mas_product.productcode as productcode , inv_mas_product.productname as productname, inv_dealercard.usagetype as usagetype, inv_dealercard.purchasetype as purchasetype, inv_mas_scratchcard.cardid as cardno, inv_mas_scratchcard.scratchnumber as pinno FROM inv_dealercard LEFT JOIN inv_mas_scratchcard ON inv_mas_scratchcard.cardid = inv_dealercard.cardid LEFT JOIN inv_mas_product
	ON inv_mas_product.productcode = inv_dealercard.productcode  WHERE inv_dealercard.cusbillnumber = '".$firstbillnumber."';";
		$result = runmysqlquery($query1);
			
			
		$query2 = "SELECT inv_billdetail.productamount from inv_billdetail where inv_billdetail.cusbillnumber = '".$firstbillnumber."';";
		$result2 = runmysqlquery($query2);
		while($fetch2 = mysql_fetch_array($result2))
		{
			$amount[] = $fetch2['productamount'];
		}
			
		$query3 = "Select * from inv_bill where inv_bill.slno = '".$firstbillnumber."'";
		$result3 = runmysqlqueryfetch($query3);
		
		$k = 0;
		$descriptioncount = 0;
		$description = '';
		$servicetaxdesc = 'Service Tax applicable under Service Tax Act: "Taxable Service" category "zzze" (information technology software).';
		while($fetch = mysql_fetch_array($result))
		{
			$slno++;
			$k++;
			if($fetch['purchasetype'] == 'new')
				$purchasetype = 'New';
			else
				$purchasetype = 'Updation';
			if($fetch['usagetype'] == 'singleuser')
				$usagetype = 'Single User';
			else
				$usagetype = 'Multi User';
			if($descriptioncount > 0)
				$description .= '*';
			$description .= $k.'$'.$fetch['productname'].'$'.$purchasetype.'$'.$usagetype.'$'.$fetch['pinno'].'$'.$fetch['cardno'].'$'.$amount[$k];
			
			$descriptioncount++;
		  }
		 $amountinwords = convert_number($result3['netamount']);
			
		$emailid = explode(trim(fetchcontactdetails['emailid'] ,','));
		$emailidplit = $emailid[0];
		$phonenumber = explode(trim(fetchcontactdetails['phone'] ,','));
		$phonenumbersplit = $phonenumber[0];
		$cellnumber = explode(trim(fetchcontactdetails['cell'] ,','));
		$cellnumbersplit = $cellnumber[0];
		$contactperson = explode(trim(fetchcontactdetails['contactperson'] ,','));
		$contactpersonplit = $contactperson[0];
		$invoiceno = 'RSL/Online/'.$onlineinvoiceno;
	
		$stdcode = ($fetchresult['stdcode'] == '')?'':$fetchresult['stdcode'].' - ';
		$address = $fetchresult['address'].', '.$fetchresult['place'].', '.$fetchresult['districtname'].', '.$fetchresult['statename'].', Pin: '.$fetchresult['pincode'];
	
	
		$invoicequery = "update inv_invoicenumbers set description = '".$description."', amount = '".$result3['total']."',servicetax = '".$result3['taxamount']."', netamount = '".$result3['netamount']."', customerid = '".cusidcombine($fetchresult['customerid'])."',phone =  '".$phonenumbersplit."',cell = '".$cellnumbersplit."',emailid = '".$emailidplit."',contactperson = '".$contactpersonplit."',stdcode = '".$stdcode."',customertype = '".$fetchresult['customertype']."',customercategory = '".$fetchresult['businesstype']."',region = '".$fetchresult['region']."',branch ='".$fetchresult['branchname']."',pincode = '".$fetchresult['pincode']."',address ='".$address."', amountinwords = '".$amountinwords."', remarks = '".$remarks."', servicetaxdesc = '".$servicetaxdesc."',invoiceno = '".$invoiceno."',createddate = '".$billdate."',dealername = '".$fetchresult['dealername']."',createdby = 'Webmaster',businessname = '".$fetchresult['companyname']."' ,purchasetype = 'Product' , place = '".$fetchresult['place']."', remarks = 'Payment received through Credit/Debit card.' where slno  ='".$onlineinvoiceno.",';";
		$invoiceresult = runmysqlquery($invoicequery);
		$count++;
		
	}

echo('Done'.$count);




?>