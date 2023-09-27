<?php
   
	//Update the Payment status 
	$query1 = "UPDATE pre_online_purchase SET paymentstatus = 'PAID', paymentdate = '".date('Y-m-d')."', paymenttime = '".date('H:i:s')."' WHERE slno = '".$recordreferencestring."'";
	$result = runmysqlquery($query1);

	//Check the dealer name 
	$query2 = "SELECT * from pre_online_purchase  where slno = '".$recordreferencestring."' ";
	$transaction = runmysqlqueryfetch($query2);
	$company = $transaction['businessname'];
	$contactperson = $transaction['contactperson'];
	$address = $transaction['address'];
	$place = $transaction['place'];
	$district = $transaction['district'];
	$pincode = $transaction['pincode'];
	$stdcode = $transaction['stdcode'];
	$phone = $transaction['phone'];
	$fax = $transaction['fax'];
	$cell = $transaction['cell'];
	$producttotal = $transaction['amount'];
	$emailid = $transaction['emailid'];
	$website = $transaction['website'];
	$category = $transaction['category'];
	$type = $transaction['type'];
	$currentdealer = $transaction['currentdealer'];
	$product = $transaction['products'];
	$custreference = $transaction['custreference'];
	$customerpurchasetype = $transaction['purchasetype'];
	
	$amount = $transaction['amount'];
	
	$split = explode('*',$product);
	$quantity = count($split);
	$productpurchasetype = $transaction['productpurchasetype'];
	$productpurchaseremarks = $transaction['remarks'];
	$productcodearray = $transaction['productcode'];
	$totalproductpricearray = $transaction['totalproductpricearray'];
	
	$igst_amount_val = $transaction['igst'];//forgstpre_tax_total
	$cgst_amount_val = $transaction['cgst'];
	$sgst_amount_val = $transaction['sgst'];
	$gst_type = $transaction['gst_type'];
	$withoutround = $transaction['withoutround'];
	$pre_tax_total = $transaction['pre_tax_total'];
	
	$productusagetype = $transaction['usagetype'];

	// To calculate the quantity
	$quantitycount = 1;
	for($k= 0;$k<$quantity;$k++)
	{
		$productquantity .= $quantitycount.',';
	}

	//take dealer details
		$result1 = runmysqlqueryfetch("Select inv_mas_dealer.slno as slno,inv_mas_dealer.businessname as businessname,inv_mas_dealer.emailid as emailid ,inv_mas_dealer.region  as regionid,inv_mas_dealer.branch as branchid,inv_mas_region.category as regionname,inv_mas_branch.branchname as branchname,inv_mas_dealer.relyonexecutive as relyonexecutive from inv_mas_dealer left join inv_mas_region on  inv_mas_region.slno = inv_mas_dealer.region 
left join inv_mas_branch on  inv_mas_branch.slno = inv_mas_dealer.branch where inv_mas_dealer.slno = '".$currentdealer."'");

			$dealerid = $result1['slno'];
			$dealername = $result1['businessname'];
			$dealeremailid = $result1['emailid'];
			$branchname = $result1['branchname'];
			$regionname = $result1['regionname'];
			$regionid = $result1['regionid'];
			$branchid = $result1['branchid'];

		//Get the customer details

		$fetchquery = "select * from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode =inv_mas_customer.district left join inv_mas_state on inv_mas_state.statecode =inv_mas_district.statecode left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on  inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category  where inv_mas_customer.slno = '".$custreference."';";

		$fetchresult1 = runmysqlqueryfetch($fetchquery);

		$customerid = $fetchresult1['customerid'];
		$categoryname = ($fetchresult1['businesstype'] == '')?'Not Available':$fetchresult1['businesstype'];
		$typename = ($fetchresult1['customertype'] == '')?'Not Available':$fetchresult1['customertype'];
		$districtname = $fetchresult1['districtname'];
		$statename = $fetchresult1['statename'];

		//$branchname = $fetchresult1['branchname'];

		//$regionname = $fetchresult1['category'];



	//Get the next record serial number for insertion in invoicenumbers table
	$query1 = "select ifnull(max(slno),0) + 1 as billref from inv_invoicenumbers";
	$resultfetch1 = runmysqlqueryfetch($query1);
	//$onlineinvoiceno = $resultfetch1['billref'];
	$onlineinvoiceno_new = $resultfetch1['billref'];

	//$invoicenogenerated = generatebillnumber($regionname);
	/*	$query4 = "select ifnull(max(onlineinvoiceno),".getstartnumber($dealerregion).")+ 1 as invoicenotobeinserted from 
	inv_invoicenumbers where category = '".$regionname."'";
	$resultfetch4 = runmysqlqueryfetch($query4);
	$onlineinvoiceslno = $resultfetch4['invoicenotobeinserted'];
    $invoicenoformat = 'RSL/'.$regionname.'/'.$onlineinvoiceslno;
	$invoicenogeneratedsplit = explode('$',$invoicenogenerated);*/
	

function getstartnumbernew($state_info)
{
        switch($state_info)
        {
            case '2021RL': $startnumber = '1'; break;
            case '2021RI': $startnumber = '1';break;
            default: $startnumber = '1';break;
        }
    return ($startnumber-1);
}

    //$year = '2017';
    $year = '2021';
	if($gst_type == 'CSGST')
	{   
		//$state_info = 'L';
		$state_info = 'L';
		$varState = '2021RL';
		    
		/*$queryonlineinv = "select ifnull(max(onlineinvoiceno),".getstartnumbernew($state_info).")+ 1 as invoicenotobeinserted 
		from inv_invoicenumbers where state_info = '".$state_info."'";*/
		
		$queryonlineinv = "select ifnull(max(onlineinvoiceno),0)+ 1 as invoicenotobeinserted from inv_invoicenumbers where invoiceno like '%".$varState."%'";
	
			$resultfetchinv = runmysqlqueryfetch($queryonlineinv);
			$onlineinvoiceno = $resultfetchinv['invoicenotobeinserted'];
			$onlineinvoiceno=(string)$onlineinvoiceno;
			$onlineinvoiceno=sprintf('%06d', $onlineinvoiceno);
			$invoicenoformat = 'RSL'.$year.'R'.$state_info.''.$onlineinvoiceno;
	}
	else
	{
	    //$onlineinvoiceno='000100';
	     //$state_info = 'I';
	     $state_info = 'I';
	     $varState = '2021RI';
		    
		 /*$queryonlineinv = "select ifnull(max(onlineinvoiceno),".getstartnumbernew($state_info).")+ 1 as invoicenotobeinserted 
		 from inv_invoicenumbers where state_info = '".$state_info."'";*/
		 
		 $queryonlineinv = "select ifnull(max(onlineinvoiceno),0)+ 1 as invoicenotobeinserted from inv_invoicenumbers where invoiceno like '%".$varState."%'";
		
		$resultfetchinv = runmysqlqueryfetch($queryonlineinv);
		$onlineinvoiceno = $resultfetchinv['invoicenotobeinserted'];
		$onlineinvoiceno=sprintf('%06d', $onlineinvoiceno);
		$onlineinvoiceno=(string)$onlineinvoiceno;		
		$invoicenoformat = 'RSL'.$year.'R'.$state_info.''.$onlineinvoiceno;
	}
       
	//added for new series ends	
	
	
	//$invoicenoformat = $invoicenogeneratedsplit[0];
	//$onlineinvoiceslno = $invoicenogeneratedsplit[1];

	//Insert the inv_invoicenumbers table 
	$resultantquery = "Insert into inv_invoicenumbers(invoiceno,category,onlineinvoiceno,year,invoice_type,state_info) 
	values('".$invoicenoformat."','".$regionname."','".$onlineinvoiceno."','".$year."','R','".$state_info ."');";
	$resultvalue3 = runmysqlquery($resultantquery);

    $query99 = "SELECT slno FROM inv_invoicenumbers ORDER BY slno DESC LIMIT 1";
    $resultfetch99 = runmysqlqueryfetch($query99);
    $inv_slno = $resultfetch99['slno'];
        
	//update preonline purchase with invoice no
	$query3555 = "update pre_online_purchase set onlineinvoiceno = '".$onlineinvoiceno_new."' 
	where slno = '".$recordreferencestring."'";
	$result355 = runmysqlquery($query3555);

	//file to test by manju
	$myFile = "001100";
	$fh = fopen($myFile, 'a');

	//Generate the Bill
	$arraysplit = explode('*',$product);
	$productpurchasetypesplit = explode(',',$productpurchasetype);
	$productusagetypesplit = explode(',',$productusagetype);
	for($i = 0; $i < count($arraysplit); $i++)
	{
		$recordnumber = $arraysplit[$i];
		//To fetch the details from the master table
		$query3 = "Select * from inv_relyonsoft_prices where slno = '".$recordnumber."'";
		$fetchresult = runmysqlqueryfetch($query3);
		$prdprice = $fetchresult['updationprice'];
		$productamountsplit[] = $fetchresult['updationprice'];

		$total += $prdprice;
		$currentdate = strtotime(date('Y-m-d'));

		//$taxamt = round($prdprice *.15);
		//$sevicetax += round($prdprice *.14);
		//$sbtax += round($prdprice *.005);
		//$kktax += round($prdprice *.005);
		
		    if($gst_type == 'CSGST')
			{
			    $cgst += ($prdprice *.09)/100;
		        $sgst += ($prdprice *.09)/100;
			}
			else
			{
			    $igst += ($prdprice *.18)/100;
			}

        $taxamt = $igst + $cgst + $sgst;
		$netamount += $prdprice + $taxamt;

		$my_string= "\n" . $onlineinvoiceno_new . ", NetAmount=" . $netamount .", Product Price=" . $prdprice . ", Tax Amount=".$taxamt;

		fwrite($fh,$my_string);

		//Execute the PIN number Purchase

		$query17 = "SELECT attachPIN() as cardid;";
		$result75 = runmysqlqueryfetch($query17);

		//Attach that PIN Number to that dealer/customer

		$query744 = "INSERT INTO inv_dealercard(dealerid,cardid,productcode,date,usagetype,purchasetype,userid,customerreference,initialusagetype,initialpurchasetype,initialproduct,initialdealerid,cusbillnumber,scheme,invoiceid,cuscardattacheddate,cuscardattachedby,usertype,addlicence) values('".$dealerid."','".$result75['cardid']."','".$fetchresult['productcode']."','".date('Y-m-d').' '.date('H:i:s')."','".$productusagetypesplit[$i]."','".$productpurchasetypesplit[$i]."','2','".$custreference."','".$fetchresult['usagetype']."','".$fetchresult['purchasetype']."','".$fetchresult['productcode']."','".$dealerid."','','1','".$onlineinvoiceno_new."','".date('Y-m-d').' '.date('H:i:s')."','2','web','')";

		$result177 = runmysqlquery($query744);	

	}

	fclose($fh);

	//Check whether purcased mode is relyon representative or self


	//Description of product	
	$carddetailsquery = "select * from inv_dealercard left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid  where invoiceid = '".$onlineinvoiceno_new."';";

	$carddetailsresult = runmysqlquery($carddetailsquery);
	$slno = 0;
	$k;
	$descriptioncount = 0;
	$k=0;
	while($carddetailsfetch = mysql_fetch_array($carddetailsresult))
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
			if($carddetailsfetch['usagetype'] == 'singleuser' || $carddetailsfetch['usagetype'] == 'additionallicense')
				$usagetype = 'Single User';
			else
				$usagetype = 'Multi User';
		}

		if($descriptioncount > 0)

			$description .= '*';

		$description .= $slno.'$'.$carddetailsfetch['productname'].' - ('.$carddetailsfetch['year'].')'.'$'.$purchasetype.'$'.$usagetype.'$'.$carddetailsfetch['scratchnumber'].'$'.$carddetailsfetch['cardid'].'$'.$productamountsplit[$k];
		$k++;
		$descriptioncount++;
	}

	$invemailid = explode(',', trim($emailid,','));
	$emailidplit = $invemailid[0];
	$invphonenumber = explode(',', trim($phone,','));
	$phonenumbersplit = $invphonenumber[0];
	$invcellnumber = explode(',', trim($cell,','));
	$cellnumbersplit = $invcellnumber[0];
	$invcontactperson = explode(',', trim($contactperson,','));
	$contactpersonplit = $invcontactperson[0];
	$invstdcode = ($stdcode == '')?'':$stdcode.' - ';
	$invaddress = $address.', '.$place.', '.$districtname.', '.$statename.', Pin: '.$pincode;
	//$invoiceheading = ($statename == 'Karnataka')?'Tax Invoice':'Bill Of Sale';
	$invoiceheading = ($statename == 'Karnataka')?'Tax Invoice':'Tax Invoice';
	//$amountinwords = convert_number($netamount);
	
	$amountinwords = convert_number($amount);

	$servicetaxdesc = 'Service Tax Category: Information Technology Software (zzze), Support(zzzq), Training (zzc), Manpower(k), Salary Processing (22g), SMS Service (b)';

	//Update complete invoice details to invoicenumbers table 
	$query666 = "UPDATE inv_invoicenumbers  SET customerid = '".cusidcombine($customerid)."', businessname = '".trim($company)."',contactperson = '".$contactpersonplit."', 
	address = '".$invaddress."', place = '".$place."',pincode = '".$pincode."' ,emailid = '".$emailidplit."' , description = '".$description."' ,
	dealername = '".$dealername."', createddate = '".date('Y-m-d').' '.date('H:i:s')."',createdby = 'Webmaster', amount = '".$pre_tax_total."' , 
	servicetax  = '0.00', sbtax  = '0.00', kktax  = '0.00', igst = '".$igst_amount_val."', cgst = '".$cgst_amount_val."', sgst = '".$sgst_amount_val."', 
	netamount = '".$amount."' , phone  = '".$phonenumbersplit."' , cell = '".$cellnumbersplit."', customertype ='".$typename."' ,customercategory = '".$categoryname."' ,
	region = '".$regionname."' ,purchasetype = 'Product' ,	dealerid = '".$dealerid."' ,products = '".$productcodearray."' ,
	productquantity =    '".trim($productquantity,',')."' ,pricingtype = 'default' ,createdbyid = '2' ,totalproductpricearray = '".$totalproductpricearray."',
	actualproductpricearray = '".$totalproductpricearray."' ,module = 'user_module' ,servicetype = '' ,serviceamount = '' ,paymenttypeselected = 'paymentmadenow',
	paymentmode = '".$paymentmode."' ,stdcode = '".$invstdcode."' ,branch = '".$branchname."' ,amountinwords = '".$amountinwords."' ,remarks = '".$invoicepayremarks."' ,
	servicetaxdesc = '".$servicetaxdesc."' ,invoiceheading = '".$invoiceheading."',servicedescription = '',offerdescription = '' ,offerremarks = '' ,invoiceremarks = 'None' ,
	duedate = '',status = 'ACTIVE',regionid = '".$regionid."',branchid = '".$branchid."' where slno = '".$onlineinvoiceno_new."';";

	$result66 = runmysqlquery($query666);

	//Get the next record serial number for insertion in receipts table
	$query45 ="select max(slno) + 1 as receiptslno from inv_mas_receipt";
	$resultfetch45 = runmysqlqueryfetch($query45);
	$receiptslno = $resultfetch45['receiptslno'];

	//Insert Receipt Details
	$query55 = "INSERT INTO inv_mas_receipt(slno,invoiceno,invoiceamount,receiptamount,paymentmode,receiptremarks,privatenote,createddate,createdby,createdip,lastmodifieddate,lastmodifiedby,lastmodifiedip,customerreference,receiptdate,receipttime,module,partialpayment) values('".$receiptslno."','".$onlineinvoiceno_new."','".$amount."','".$amount."','".$paymenttype."','','','".date('Y-m-d').' '.date('H:i:s')."','2','".$_SERVER['REMOTE_ADDR']."','".date('Y-m-d').' '.date('H:i:s')."','2','".$_SERVER['REMOTE_ADDR']."','".$custreference."','".date('Y-m-d')."','".date('H:i:s')."','Online','no');";

	$result55 = runmysqlquery($query55);

	//Send receipt email
	sendreceipt($receiptslno,'resend');

	//Online bill Generation in PDF
	$pdftype = 'send';
	$invoicedetails = vieworgeneratepdfinvoice($onlineinvoiceno_new,$pdftype);
	$invoicedetailssplit = explode('^',$invoicedetails);
	$filebasename = $invoicedetailssplit[0];

	//Select the main data through record-reference
	$grid = '<table width="588px" cellpadding="3" cellspacing="0" border="1" align="center" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px" >';

	$grid .= '<tr bgcolor ="#FF8080"><td nowrap = "nowrap" ><strong>Product Name</strong></td><td nowrap = "nowrap" ><strong>Usage Type</strong></td><td nowrap = "nowrap" ><strong>Purchase Type</strong></td><td nowrap = "nowrap" ><strong>PIN Serial Number</strong></td><td nowrap = "nowrap" ><strong>PIN Number</strong></td></tr>';

	$query0 = "SELECT inv_mas_product.productcode as productcode,inv_mas_product.subgroup ,inv_mas_product.productname as productname, inv_dealercard.usagetype as usagetype, 
inv_dealercard.purchasetype as purchasetype, inv_mas_scratchcard.cardid as cardno,inv_mas_scratchcard.scratchnumber as pinno  FROM inv_dealercard 
LEFT JOIN inv_mas_scratchcard ON inv_mas_scratchcard.cardid = inv_dealercard.cardid 
LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_dealercard.productcode 
WHERE inv_dealercard.invoiceid='".$onlineinvoiceno_new."'";

	$result5 = runmysqlquery($query0);
	while($fetch5 = mysql_fetch_array($result5))
	{
	    $query7 = "SELECT subgroup,count(subgroup) as subgroupcount FROM inv_dealercard LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_dealercard.productcode 
                    WHERE inv_dealercard.invoiceid='".$onlineinvoiceno_new."' and subgroup = '".$fetch5['subgroup']."' group by subgroup";
        $fetch7 = runmysqlqueryfetch($query7);
        $subgroupcount = $fetch7['subgroupcount'];
        $subgroup = $fetch7['subgroup'];


        $query9 = "select productcode,subgroup,productname from inv_mas_product where year = '2020-21' and subgroup = '".$fetch5['subgroup']."';";
        $fetch9 = runmysqlqueryfetch($query9);

        //echo $fetch5['productcode']. '  ' . $fetch9['productcode'].'<br>';
        $grid .= "<tr>";

        if($subgroupcount > 1)
        {
            if($fetch5['productcode'] == $fetch9['productcode'])
            {
                $productname = $fetch5['productname'];
                $cardid = '';
                $pinno = '';
                $purchase = "";
                $usage = '';
            }

            else if($fetch5['productcode']!= $fetch9['productcode']){
                if($fetch5['purchasetype'] == "updation")
                {
                    $purchase = "Updation";
                }
                else
                {
                    $purchase = "New";
                }

                if($fetch5['usagetype'] == 'singleuser' || $fetch5['usagetype'] == 'additionallicense')
                    $usage = 'Single User';
                else
                    $usage = 'Multi User';
                $cardid = $fetch5['cardno'];
                $pinno = $fetch5['pinno'];
            }

        }
        else if($subgroupcount == 1){
            if($fetch5['purchasetype'] == "updation")
            {
                $purchase = "Updation";
            }
            else
            {
                $purchase = "New";
            }

            if($fetch5['usagetype'] == 'singleuser' || $fetch5['usagetype'] == 'additionallicense')
                $usage = 'Single User';
            else
                $usage = 'Multi User';
            $cardid = $fetch5['cardno'];
            $pinno = $fetch5['pinno'];
        }

        $grid .= "<td nowrap = 'nowrap'>".$fetch5['productname']."</td>";
        $grid .= "<td nowrap = 'nowrap'>".$usage."</td>";
        $grid .= "<td nowrap = 'nowrap'>".$purchase."</td>";
        $grid .= "<td nowrap = 'nowrap'>".$cardid."</td>";
        $grid .= "<td nowrap = 'nowrap'>".$pinno."</td>";

        $grid .= "</tr>";


	}
	$grid .= "</table>";	

	#########  Mailing Starts -----------------------------------

		//$emailid = 'rashmi.hk@relyonsoft.com';

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

		$fromname = "Relyon";
		$fromemail = "imax@relyon.co.in";
		require_once("../inc/RSLMAIL_MAIL.php");
		$msg = file_get_contents("../mailcontents/paymentinfo1.htm");
		$textmsg = file_get_contents("../mailcontents/paymentinfo1.txt");

		$date = date('d-m-Y');

		$time = date('H:i:s');

		$array = array();

		$array[] = "##DATE##%^%".$date;
		$array[] = "##TIME##%^%".$time;
		$array[] = "##COMPANYNAME##%^%".$company;
		$array[] = "##CONTACTPERSON##%^%".$contactperson;
		$array[] = "##PLACE##%^%".$place;
		$array[] = "##ADDRESS##%^%".$address;
		$array[] = "##PINCODE##%^%".$pincode;
		$array[] = "##STDCODE##%^%".$stdcode;
		$array[] = "##PHONE##%^%".$phone;
		$array[] = "##CELL##%^%".$cell;
		$array[] = "##EMAIL##%^%".$emailid;
		//$array[] = "##TOTALAMOUNT##%^%".$netamount;
		$array[] = "##TOTALAMOUNT##%^%".$amount;
		$array[] = "##TABLE##%^%".$grid;
		$array[] = "##INVOICENOFORMAT##%^%".$invoicenoformat;
		$filearray = array(

		array('../images/relyon-logo.jpg','inline','1234567890'),array('../images/relyon-rupee-small.jpg','inline','1234567892'),
		array('../upload/'.$filebasename.'','attachment','1234567891')

		);


		//Mail to customer
		$toarray = $emailids;
		//CC to the dealer
		//$dealeremailid = 'bhumika.p@relyonsoft.com';
		$ccemailarray = explode(',',$dealeremailid);
		$ccemailcount = count($ccemailarray);

		for($i = 0; $i < $ccemailcount; $i++)
		{
			if(checkemailaddress($ccemailarray[$i]))
			{
					$ccemailids[$ccemailarray[$i]] = $ccemailarray[$i];
			}
		}

		$ccarray = $ccemailids;

		//Copy of email to Accounts / Vijay Kumar / Bigmails 

		$bccarray = array('Bigmail' => 'bigmail@relyonsoft.com', 'Accounts' => 'bills@relyonsoft.com', 'Webmaster' => 'webmaster@relyonsoft.com', 'Relyonimax'=> 'relyonimax@gmail.com'); 

		//$bccarray = array('archana' => 'archana.ab@relyonsoft.com');

		$msg = replacemailvariable($msg,$array);
		$textmsg = replacemailvariable($textmsg,$array);
		$attachedfilename = explode('.',$filebasename);
		$subject = "Relyon Online Product Purchase | Invoice: ".$attachedfilename[0];
		$html = $msg;
		$text = $textmsg;
		rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray);
		fileDelete('../upload/',$filebasename);
		//rslcookiedelete();
		
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