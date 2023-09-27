<?
include('../functions/phpfunctions.php');
//require_once('../pdfbillgeneration/tcpdf/tcpdf.php');
require_once('../pdfbillgeneration/tcpdf.php');
ini_set('memory_limit', -1);

$userid = imaxgetcookie('userid');
$slno = $_POST['hiddenslno'];
$newarray = explode(',',$slno);
$letterhead  = $_POST['letterhead'];

//var_dump($_POST);exit;

//$invoicestatus  = $_POST['invoicestatus'];

rsort($newarray);
//var_dump($newarray);
//exit();

for($l = 0;$l < count($newarray);$l++)
{
	$grid = '';
	$query = "select * from inv_matrixinvoicenumbers where inv_matrixinvoicenumbers.slno = '".$newarray[$l]."' order by inv_matrixinvoicenumbers.slno;";
	$result = runmysqlquery($query); 
	
	$final_amount = 0;
	$appendzero = '.00';
	$grid .='<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" >';
	$grid .='<tr><td >
	<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC" style="border:1px solid">
	<tr bgcolor="#CCCCCC">
	<td width="7%"><div align="center" ><strong>Sl No</strong></div></td>
	<td width="64%"><div align="center"><strong>Description</strong></div></td>
	<td width="5%"><div align="center"><strong>Qty</strong></div></td>
	<td width="12%"><div align="center"><strong>Rate</strong></div></td>
	<td width="12%"><div align="center"><strong>Amount</strong></div></td>
	</tr>';

	$countslno=1;
	$list = [];
	while($fetch = mysqli_fetch_array($result))
	{
		$description = $fetch['description'];
		$descriptionsplit = explode('*',$description);
		$proquantity = $fetch['productquantity'];
		$proquantitysplit = explode(',',$proquantity);
		
		for($i=0,$j=0;$i<count($descriptionsplit),$j<count($proquantitysplit);$i++,$j++)
		{
			$productdesvalue = '';
			$descriptionline = explode('$',$descriptionsplit[$i]);
            
			$actualproductpricearraysplit = explode('*',$fetch['actualproductpricearray']);
			$productarray = explode('#',$fetch['products']);
			$servicequery = "select hsncode,`group` from inv_mas_matrixproduct where id = '".$productarray[$i]."'";
			$servicefetch = runmysqlqueryfetch($servicequery);
			$servicecode = $servicefetch['hsncode'];
			$group = $servicefetch['group'];
			$progroup = ($group == 'Hardware') ? 'HSN' : 'SAC';
			$progroup1 = ($group == 'Hardware') ? 'Hardware' : 'Software';
			if(!in_array($progroup1, $list, true)){
				array_push($list, $progroup1);
			}
			
			if($description <> '')
			{
				$grid .= '<tr>';
				$grid .= '<td width="7%" style="text-align:centre;">'.$countslno.'</td>';

				// if($checkInvoicedate < $checkJuly) 
				// {
				// 	$grid .= '<td width="64%" style="text-align:left;">'.$descriptionline[1].'<br/>
				// 	<span style="font-size:+7" ><strong>Purchase Type</strong> : '.$descriptionline[2].'&nbsp;/&nbsp;<strong>Usage Type</strong> :'.$descriptionline[3].'&nbsp;&nbsp;/ &nbsp;<strong>PIN Number : <font color="#000000">'.$descriptionline[4].'</font></strong>&nbsp;/&nbsp;</span><br/><span style="font-size:+6" ><strong>Serial</strong> : '.$descriptionline[5].' </span></td>';
				// } 
			    // else 
			    // {
			    // 	if($checkInvoicedate1 < $checkMarch)
			    // 	{
				// 		$grid .= '<td width="64%" style="text-align:left;">'.$descriptionline[1].'<br/>
				// 		<span style="font-size:+7" ><strong>Purchase Type</strong> : '.$descriptionline[2].'&nbsp;/&nbsp;<strong>Usage Type</strong> :'.$descriptionline[3].'&nbsp;&nbsp; /&nbsp;</span><br/><span style="font-size:+6" ><strong>Serial</strong> : '.$descriptionline[4].' </span><span style="font-size:+6" > / <strong>SAC</strong> : 997331</span></td>';
				// 	}
				// 	else
				// 	{
				$grid .= '<td width="64%" style="text-align:left;">'.$descriptionline[1].'<br/>
				<span style="font-size:+7" ><strong>Purchase Type</strong> : '.$descriptionline[2].'&nbsp;</span><span style="font-size:+6" > / <strong>Serial</strong> : '.$descriptionline[3].' </span><span style="font-size:+6" > / <strong>'.$progroup.'</strong> : '.$servicecode.'</span></td>';
				// 	}
				// }
				$grid .= '<td width="5%"  style="text-align:right;" >'.$proquantitysplit[$j].'</td>';
				$grid .= '<td width="12%"  style="text-align:right;" >'.$actualproductpricearraysplit[$j].$appendzero.'</td>';				
				$grid .= '<td width="12%" style="text-align:right;" >'.formatnumber($descriptionline[4]).$appendzero.'</td>';
				$grid .= "</tr>";
			
				$final_amount = $final_amount + $descriptionline[6];
                                $incno++;
                                $countslno++;
			}
		}
		
		//print_r($list);
		if(in_array('Hardware',$list) && in_array('Software',$list))
			$producttype = 'Goods and Services';
		else
		{
			$producttype = (in_array('Hardware',$list)) ? 'Goods' : 'Services';
		}
		
		$descriptionlinecount = 0;
		if($description <> '')
		{
			$descriptionlinecount = 1;
		}
		$rowcount = $descriptionlinecount;
		if($rowcount < 8)
		{
			if($letterhead == 'on')
				$grid .= addmatrixlinebreak_bulkprint($rowcount);
			else
				$grid .= addmatrixlinebreak($rowcount);

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
			
		$invoicedatedisplay = substr($fetch['createddate'],0,10);
		$invoicedate =  strtotime($invoicedatedisplay);
		$expirydate = strtotime('2012-04-01');
		$expirydate1 = strtotime('2015-06-01');
		$expirydate2 = strtotime('2015-11-15');
		$KK_Cess_date = strtotime('2016-05-31');
		
		//$gst_date = '2017-06-08'; // used to get date from gst_rates
		$gst_date = date('Y-m-d');
		$gst_tax_date = strtotime('2017-07-01');
		
		
		//gst rate fetching
		
		$gst_tax_query= "select igst_rate,cgst_rate,sgst_rate from gst_rates where from_date <= '$gst_date' AND to_date >= '$gst_date'";
		$gst_tax_result = runmysqlqueryfetch($gst_tax_query);
		$igst_tax_rate = $gst_tax_result['igst_rate'];
		$cgst_tax_rate = $gst_tax_result['cgst_rate'];
		$sgst_tax_rate = $gst_tax_result['sgst_rate'];
		
		//gst rate fetching ends
		/*----------------------------*/
		$new_gst_no = $fetch['gst_no'];
		$custpan = "";
        $search_customer =  str_replace("-","",$fetch['customerid']);
        $customer_details = "select inv_mas_customer.gst_no as gst_no,inv_mas_customer.sez_enabled as sez_enabled,
        inv_mas_district.statecode as state_code,inv_mas_state.statename as statename
        ,inv_mas_state.state_gst_code as state_gst_code,panno from inv_mas_customer 
        left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode
        left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode 
        where inv_mas_customer.customerid like '%".$search_customer."%'";

        $fetch_customer_details = runmysqlqueryfetch($customer_details);
		$statename = $fetch_customer_details['statename'];
		$statecode = $fetch_customer_details['state_gst_code'];
		$custpan = $fetch_customer_details['panno'];
		
        
		if($gst_tax_date <= $invoicedate)
		{
			//echo "mine";
			//echo $gst_tax_date."<br>"; 
			//echo $invoicedate;
			//exit();
			
			//echo $fetch['cgst'];
			//exit();
			
			$sezremarks = '';
			if($fetch['seztaxtype'] == 'yes')
			{
			    $sezremarks = 'TAX NOT APPLICABLE AS CUSTOMER IS UNDER SPECIAL ECONOMIC ZONE.<br/>';
			}
			
			//if($fetch_customer_details['state_code'] == '29')//if Relyon and Customer are in same State
			if(($fetch['cgst'] != '0' &&  $fetch['sgst'] != '0'))
			{
			// $cgst_tax_amount = roundnearestvalue($fetch['amount'] * ($cgst_tax_rate/100));
			// $sgst_tax_amount = roundnearestvalue($fetch['amount'] * ($sgst_tax_rate/100));
				
				$gst_tax_column ='<tr><td  width="53%" style="text-align:right"></td>
				<td  width="35%" style="text-align:right"><strong>CGST Tax @'.$cgst_tax_rate.'% </strong></td>
				<td width="12%" style="text-align:right;font-size:+9">'.formatnumber($fetch['cgst']).'</td></tr>';
				
				$gst_tax_column .='<tr><td  width="53%" style="text-align:right"></td>
				<td  width="35%" style="text-align:right"><strong>SGST Tax @'.$sgst_tax_rate.'% </strong></td>
				<td width="12%" style="text-align:right;font-size:+9">'.formatnumber($fetch['sgst']).'</td></tr>';
			}
			else
			{
				//$igst_tax_amount = roundnearestvalue($fetch['amount'] * ($igst_tax_rate/100));
				
				$gst_tax_column ='<tr>
				<td  width="53%" style="text-align:right"></td>
				<td  width="35%" style="text-align:right"><strong>IGST Tax @'.$igst_tax_rate.'% </strong></td>
				<td width="12%" style="text-align:right;font-size:+9">'.formatnumber($fetch['igst']).'</td></tr>';
			}

			
			$billdatedisplay = changedateformat(substr($fetch['createddate'],0,10));
			//echo($servicetax1.'#'.$servicetax2.'#'.$servicetax3); exit; // To be added Here
			$grid .= '<tr>
			<td  width="53%" style="text-align:left"><span style="font-size:+6" ></span></td>
			<td  width="35%" style="text-align:right"><strong>Net Amount</strong></td>
			<td  width="12%" style="text-align:right">'.formatnumber($fetch['amount']).$appendzero.'</td></tr>'.$gst_tax_column.
			'<tr>
			<td  width="53%" style="text-align:left"><span style="font-size:+6;color:#FF0000" >'.$sezremarks.'</span><span style="font-size:+6;color:#FF0000" >'.$statusremarks.'</span></td>
			<td  width="35%" style="text-align:right"></td>
			<td width="12%" style="text-align:right"></td></tr>';
		}
		       			

		$podatepiece = (($fetch['podate'] == "0000-00-00") || ($fetch['podate'] == ''))?("Not Available"):(changedateformat($fetch['podate']));
		$poreferencepiece = ($fetch['poreference'] == "")?("Not Available"):($fetch['poreference']);
		/*-----------------Round Off ----------------------*/
		$roundoff = 'false';
		$roundoff_value = '';
		$addition_amount = $fetch['amount']+$fetch['igst']+$fetch['cgst']+$fetch['sgst'];
		
		$roundoff_value = ($fetch['netamount'])- ($addition_amount);
		//echo $fetch['netamount'] . "amount ". $addition_amount;
		if($roundoff_value != 0 || $roundoff_value != 0.00)
		{
			$roundoff = 'true';
		}
		/* if($addition_amount > $fetch['netamount'])
		{
		$roundoff_value = ($addition_amount)- ($fetch['netamount']);
		$roundoff = 'true';
		}
		else if( $addition_amount < $fetch['netamount'])
		{
			$roundoff_value = ($fetch['netamount']) - ($addition_amount);
			$roundoff = 'true';
		}
		else
		{ 
			$roundoff_value = '';
			$roundoff = 'false';
		}*/

		/*----Round Off Done ---------------------------*/

		/*----Round Off Done ---------------------------*/


		/*------------------Check Ends --------------------------*/

		if($roundoff == 'true')
		{
			$roundoff_value = number_format($roundoff_value,2);
			$grid .= '<tr>
			<td  width="53%" style="text-align:right"><div align="left"></div></td>
			<td  width="35%" style="text-align:right"><strong>Round Off</strong></td>
			<td  width="12%" style="text-align:right">&nbsp;&nbsp;'.$roundoff_value.'</td> 
			</tr>';
		}

		$grid .= '<tr>
		<td  width="53%" style="text-align:right"><div align="left"><span style="font-size:+6" >E.&amp;O.E.</span></div></td>
		<td  width="35%" style="text-align:right"><strong>Total</strong></td>
		<td  width="12%" style="text-align:right"><img src="../images/relyon-rupee-small.jpg" width="8" height="8" border="0" alt="Relynsoft" align="absmiddle"  />&nbsp;&nbsp;'.formatnumber($fetch['netamount'] ).$appendzero.'</td> 
		</tr><tr><td colspan="3" style="text-align:left"><strong>Rupee In Words</strong>: '.convert_number($fetch['netamount']).' only</td></tr>';

		if($fetch['tdsdeclaration'] == 'yes')
		{
			$grid .= '<tr><td colspan="3" style="text-align:left"><strong>TDS Declararton for software</strong>: <br/>In Terms Of Notification No.21/2012 Dt.13 June 2012, We Hereby Declare That Transaction With 
			Remarks “ref. TDS Declaration” Is Software Acquired in A Subsequent Transfer And Is Transferred 
			Without Any Modification And Is Subjected to Tax Deduction At Source Under Section 194J 
			And/or Under Section 195 On Payment For The Previous Transfer Of Such Software. You Are Not 
			Required To Deduct Tax At Source On This Account.</td></tr>';
		}
		
			//echo($grid); exit;
			//	$grid .= '<tr><td colspan="2" style="text-align:right" width="86%"><strong>Total</strong></td><td  width="14%" style="text-align:right">'.$fetch['amount'].$appendzero.'</td></tr><tr><td  width="56%" style="text-align:left"><span style="font-size:+6" >'.$fetch['servicetaxdesc'].$appendzero.' </span></td><td  width="30%" style="text-align:right"><strong>Service Tax @ 10.3%</strong></td><td  width="14%" style="text-align:right">'.$fetch['servicetax'].$appendzero.'</td></tr><tr><td  width="56%" style="text-align:right"><div align="left"><span style="font-size:+6" >E.&amp;O.E.</span></div></td><td  width="30%" style="text-align:right"><strong>Net Amount</strong></td><td  width="14%" style="text-align:right"><img src="../images/relyon-rupee-small.jpg" width="8" height="8" border="0" alt="Relynsoft" align="absmiddle"  />&nbsp;&nbsp;'.$fetch['netamount'].$appendzero.'</td> </tr><tr><td colspan="3" style="text-align:left"><strong>Rupee In Words</strong>: '.$fetch['amountinwords'].' only</td></tr>';
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
	if($letterhead == 'on')
		$msg = file_get_contents("../pdfbillgeneration/matrix-bill-format-gst-duplicate-withouthead.php");
	else
	{
		if($fetchresult['irn'] == "")
			$msg = file_get_contents("../pdfbillgeneration/matrix-bill-format-gst-duplicate.php");
		else
			$msg = file_get_contents("../pdfbillgeneration/matrix-bill-format-gst-duplicate-irn.php");	
	}

	$image_path = '../qrimages/'.$fetchresult['qrimagepath']; 
	$print = '<div><img src="'.$image_path .'"></div>';

	$branch_gst_code = $fetchresult['state_info'];
	$branchdetails = "select * from inv_mas_branch where branch_gst_code = '".$branch_gst_code."'";
	$fetchdetails = runmysqlqueryfetch($branchdetails);
	$branch_gstin = $fetchdetails['branch_gstin'];
	$branch_add = $fetchdetails['branch_address'].', '.$fetchdetails['branch_place'].' - '.$fetchdetails['branch_pincode'];
	$branch_gst_code = $fetchdetails['branch_gst_code'];
	$branch_acc_no = $fetchdetails['branch_acc_no'];
	$branch_ifsc_code = $fetchdetails['branch_ifsc_code'];
	$branch_bank = $fetchdetails['branch_bank'];

	$array = array();
	$stdcode = $fetchresult['stdcode'];
	$array[] = "##BILLDATE##%^%".$billdatedisplay;
	$array[] = "##BILLNO##%^%".$fetchresult['invoiceno'];
	$array[] = "##STATUS##%^%".$invoicestatus;
	$array[] = "##color##%^%".$color;
	$array[] = "##DEALERDETAILS##%^%".'Email: '.$dealeremailid.' | Cell: '.$dealercell;
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
	$array[] = "##PODATE##%^%".$podatepiece;
	$array[] = "##POREFERENCE##%^%".$poreferencepiece;
	$array[] = "##INVOICEDT##%^%".$fetchresult['createddate'];
	$array[] = "##IRN##%^%".$fetchresult['irn'];
	$array[] = "##qrimage##%^%".$print;
	$array[] = "##BRANCHGSTIN##%^%".$branch_gstin;
	$array[] = "##BRANCHADDRESS##%^%".$branch_add;
	$array[] = "##BRANCHGSTCODE##%^%".$branch_gst_code;
	$array[] = "##PRODUCTGROUP##%^%".$producttype;
	$array[] = "##BANKACCNO##%^%".$branch_acc_no;
	$array[] = "##IFSCCODE##%^%".$branch_ifsc_code;
	$array[] = "##BANKBRANCH##%^%".$branch_bank;
	//$array[] = "##INVOICEIMAGE##%^%".$imagepath;
	
	if($new_gst_no!= '')
	{
		//echo "gstn" . $new_gst_no;
        $array[] = "##CUSTOMERGSTIN##%^%".$new_gst_no;
		$custpan = substr($new_gst_no,2,10);
		$array[] = "##CUSTOMERPAN##%^%".$custpan;
	}
	else
	{
	    $novalus = 'Not Registered Under GST';
	    $array[] = "##CUSTOMERGSTIN##%^%".$novalus;
		$array[] = "##CUSTOMERPAN##%^%".$custpan;
	}
    $array[] = "##POP##%^%".$fetch_customer_details['statename'];
    $array[] = "##CODE##%^%".$fetch_customer_details['state_gst_code'];
    
	$array[] = "##TABLE##%^%".$grid;
	
	  if(($fetch['deduction'] == '1') && ($fetch['tanno'] != ''))
      {
        $array[] = "##NOTE##%^%".$note;
        $array[] = "##CONTENT##%^%".$content;
      }
      else 
      {
         $note = "";$content = ""; $array[] = "##NOTE##%^%".$note;$array[] = "##CONTENT##%^%".$content;
      }
	$html[] = replacemailvariable($msg,$array);
	
}

$localdate = datetimelocal('Ymd');
$localtime = datetimelocal('His');


$checkedcount = count($html) ;
$totallooprun = ($checkedcount % 100 == 0)?($checkedcount/100):(ceil($checkedcount/100));
$slno = 0;

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// *** set an empty signature appearance ***
//$pdf->addEmptySignatureAppearance(180, 80, 15, 15);

for($i = 0; $i < $totallooprun ; $i++)
{


	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	
	// remove default header
	$pdf->setPrintHeader(false);
	
	if($letterhead == 'on')
	{
		$pdf->setPageOrientation('P','off','2');
		
	}
	else
	{
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
		$pdf->SetAutoPageBreak(TRUE,PDF_MARGIN_BOTTOM);
		
		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
		
		//set some language-dependent strings
		$pdf->setLanguageArray($l); 
	}
	$pdf->setPrintFooter(false);
	// set font
	$pdf->SetFont('Helvetica', '', 10);

	
	$loopcount = 100;
	if($i == ($totallooprun-1))
	{
		if($checkedcount % 100 <> 0)
			$loopcount = $checkedcount % 100;
		else
			$loopcount = 100;
	}
	for($j=0;$j<$loopcount;$j++)
	{
		$pdf->startPageGroup();
		// add$pdf->AddPage(); a page
		$pdf->AddPage();

		$pdf->WriteHTML($html[$slno],true,0,true);

		if(($pdf->getGroupPageNo()) == 2)
			$pdf->SetMargins(PDF_MARGIN_LEFT, '100', PDF_MARGIN_RIGHT);
			//$pdf->writeHTMLCell(0, 0, '', '', '', '', 1, 0, true, 'C', true);
		$slno++;
	}
	
	//exit;
	$query11 = "SELECT username from inv_mas_users where slno = '".$userid."'";
	$resultfetch = runmysqlqueryfetch($query11); 
	$username = $resultfetch['username'];
	
	$filename = 'Invoices'."-".$username."-".$localdate."-".$localtime."-".($i+1);
	$filebasename = $filename.".pdf";
	$addstring ="";
	if($_SERVER['HTTP_HOST'] == "localhost")
		$addstring = "/imax/user";
	$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filebasename;
	
	$pdf->Output($filepath ,'I');
	$finalfilearray[] = $filebasename;
	$finalfilepatharray[] = $filepath;
	
}
exit;
	$filezipname = 'Invoices'."-".$username."-".$localdate."-".$localtime.".zip";
	$filezipnamepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filezipname;
	$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','85','".date('Y-m-d').' '.date('H:i:s')."','PDF_Bulk Print(Invoices)')";
	$eventresult = runmysqlquery($eventquery);
	$zip = new ZipArchive;
	$newzip = $zip->open($filezipnamepath, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
	if ($newzip === TRUE)
	 {
			for($i = 0;$i <count($finalfilearray);$i++)
			{
				$zip->addFile($finalfilepatharray[$i], $finalfilearray[$i]);
			}
			$zip->close();
	}
	for($i = 0;$i <count($finalfilearray);$i++)
	{
		unlink($finalfilepatharray[$i]) ;
	}
	
	$downloadlink = 'http://'.$_SERVER['HTTP_HOST'].$addstring.'/filecreated/'.$filezipname;
	downloadfile($filepath);
		
?>
