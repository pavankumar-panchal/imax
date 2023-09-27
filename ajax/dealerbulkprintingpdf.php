<?
include('../functions/phpfunctions.php');
require_once('../pdfbillgeneration/tcpdf.php');
ini_set('memory_limit', -1);

$userid = imaxgetcookie('userid');
$slno = $_POST['hiddenslno'];
$newarray = explode(',',$slno);
$letterhead  = $_POST['letterhead'];
rsort($newarray);

for($j = 0;$j < count($newarray);$j++)
{
	$grid = '';
	$query = "select * from inv_dealer_invoicenumbers 	where inv_dealer_invoicenumbers.slno = '".$newarray[$j]."' order by inv_dealer_invoicenumbers.slno;";
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
	while($fetch = mysqli_fetch_array($result))
	{
		$description = $fetch['description'];
		$descriptionsplit = explode('*',$description);
		$proquantity = $fetch['productquantity'];
		$proquantitysplit = explode(',',$proquantity);
		$actualproductpricearraysplit = explode('*',$fetch['actualproductpricearray']);
		for($i=0,$k=0;$i<count($descriptionsplit),$k<count($proquantitysplit);$i++,$k++)
		{
			$productdesvalue = '';
			$descriptionline = explode('$',$descriptionsplit[$i]);
			if($description <> '')
			{
				$grid .= '<tr>';
				$grid .= '<td width="7%" style="text-align:centre;">'.$countslno.'</td>';
				$grid .= '<td width="64%" style="text-align:left;">'.$descriptionline[1].'<br/>
				<span style="font-size:+7" ><strong>Purchase Type</strong> : '.$descriptionline[2].'&nbsp;/&nbsp;<strong>Usage Type</strong> :'.$descriptionline[3].'&nbsp;&nbsp;/ &nbsp;<strong>PIN Number : <font color="#000000">'.$descriptionline[4].'</font></strong>&nbsp;/&nbsp;</span><br/><span style="font-size:+6" ><strong>Serial</strong> : '.$descriptionline[5].' </span><span style="font-size:+6" > / <strong>SAC</strong> : 997331</span></td>';
				$grid .= '<td width="5%"  style="text-align:right;" >'.$proquantitysplit[$k].'</td>';	
				$grid .= '<td width="12%"  style="text-align:right;" >'.$actualproductpricearraysplit[$k].$appendzero.'</td>';			
				$grid .= '<td width="12%" style="text-align:right;" >'.formatnumber($descriptionline[6]).$appendzero.'</td>';
				$grid .= "</tr>";
			
				$final_amount = $final_amount + $descriptionline[6];
								$incno++;
								$countslno++;
			}
		}
		
		$descriptionlinecount = 0;
		if($description <> '')
		{
			//Add description "Internet downloaded software"
			$grid .= '<tr>
			<td ></td>
			<td  style="text-align:center;"><font color="#666666">INTERNET DOWNLOADED SOFTWARE</font></td>
			<td >&nbsp;</td>
			<td >&nbsp;</td>
			</tr>';
			$descriptionlinecount = 1;
		}

		$servicedescriptionsplit = explode('*',$fetch['servicedescription']);
		$servicedescriptioncount = count($servicedescriptionsplit);
		if($fetch['servicedescription'] <> '')
		{
			for($i=0; $i<$servicedescriptioncount; $i++)
			{
				$itemdesvalue = '';
				$servicedescriptionline = explode('$',$servicedescriptionsplit[$i]);
					
				$grid .= '<tr>';
				$grid .= '<td width="7%" style="text-align:centre;">'.$countslno.'</td>';
				$grid .= '<td width="64%" style="text-align:left;">'.$servicedescriptionline[1].'<br/><span style="font-size:+6" ><strong>Item Description</strong> : '.$itemdesvalue.' </span> / <span style="font-size:+6" ><strong>SAC:</strong> 997331</span></td>';
				$grid .= '<td  width="5%" style="text-align:right;" ></td>';
				$grid .= '<td  width="5%" style="text-align:right;" ></td>';
				$grid .= '<td  width="12%" style="text-align:right;" >'.formatnumber($servicedescriptionline[2]).$appendzero.'</td>';
				$grid .= "</tr>";
				$final_amount = $final_amount + $servicedescriptionline[2];
				$countslno++;
							
			}
		}

		$rowcount = $descriptionlinecount + $servicedescriptioncount;
		if($rowcount < 8)
		{
			if($letterhead == 'on')
				$grid .= addinvoicelinebreak_bulkprint($rowcount);
			else
				$grid .= addinvoicelinebreak($rowcount);

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
		$custpan = "";
		$new_gst_no = $fetch['gst_no'];
		$fetch5 = runmysqlqueryfetch("select *,statename,state_gst_code,districtname from inv_mas_dealer
				left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.slno
				left join inv_mas_state on inv_mas_district.statecode = inv_mas_state.statecode
					where inv_mas_dealer.slno = '".$fetch['dealerreference']."'");
		$statename = $fetch5['statename'];
		$statecode = $fetch5['state_gst_code'];
		$custpan = $fetch5['panno'];
		
		
		if($gst_tax_date <= $invoicedate)
		{
			//echo "mine";
			//echo $gst_tax_date."<br>"; 
			//echo $invoicedate;
			//exit();
			
			//echo $fetch['cgst'];
			//exit();
			
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
			<td  width="53%" style="text-align:left"><span style="font-size:+6;color:#FF0000" >'.$statusremarks.'</span></td>
			<td  width="35%" style="text-align:right"></td>
			<td width="12%" style="text-align:right"></td></tr>';
		}
						
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
	// if($letterhead == 'on')
	// 	$msg = file_get_contents("../pdfbillgeneration/dealer-bill-format-new2.php");
	// else
	// 	$msg = file_get_contents("../pdfbillgeneration/dealer-bill-format-new.php");
		
	if($gst_tax_date <= $invoicedate)
	{
		if($letterhead == 'on')
	        //$msg = file_get_contents("../pdfbillgeneration/bill-format-gst.php");
			$msg = file_get_contents("../pdfbillgeneration/dealer-bill-format-gst-withouthead.php");
		else
		{
			if($fetchresult['irn'] == "")
				$msg = file_get_contents("../pdfbillgeneration/dealer-bill-format-gst.php");
			else
				$msg = file_get_contents("../pdfbillgeneration/dealer-bill-format-gst-irn.php");
		}
		 	
	}	
	$image_path = '../dlrqrimages/'.$fetchresult['qrimagepath']; 
	$print = '<div><img src="'.$image_path .'"></div>';
	
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
	$array[] = "##CUSTOMERID##%^%".'';
	$array[] = "##EMAILID##%^%".$fetchresult['emailid'];
	$array[] = "##PHONE##%^%".$fetchresult['phone'];
	$array[] = "##CELL##%^%".$fetchresult['cell'];
	$array[] = "##STDCODE##%^%".$stdcode;
	$array[] = "##RELYONREP##%^%".$fetchresult['dealername'];
	$array[] = "##REGION##%^%".$fetchresult['region'];
	$array[] = "##BRANCH##%^%".$fetchresult['branch'];
	$array[] = "##PAYREMARKS##%^%".$fetchresult['remarks'];
	$array[] = "##INVREMARKS##%^%".$fetchresult['invoiceremarks'];
	$array[] = "##GENERATEDBY##%^%".$fetchresult['createdby'];
	$array[] = "##INVOICEHEADING##%^%".$fetchresult['invoiceheading'];
	$array[] = "##PODATE##%^%".'';
	$array[] = "##POREFERENCE##%^%".'';
	//$array[] = "##INVOICEIMAGE##%^%".$imagepath;
	$array[] = "##INVOICEDT##%^%".$fetchresult['createddate'];
	$array[] = "##IRN##%^%".$fetchresult['irn'];
	$array[] = "##qrimage##%^%".$print;
	
	if($new_gst_no != '')
	{
		$array[] = "##CUSTOMERGSTIN##%^%".$new_gst_no;
		$custpan = substr($new_gst_no,2,10);
		$array[] = "##DEALERPAN##%^%".$custpan;
	}
	else
	{
		$novalus = 'Not Registered Under GST';
		$array[] = "##CUSTOMERGSTIN##%^%".$novalus;
		$array[] = "##DEALERPAN##%^%".$custpan;
	}
	$array[] = "##POP##%^%".$statename;
	$array[] = "##CODE##%^%".$statecode;
	
	
	$array[] = "##TABLE##%^%".$grid;
	
	if(($resultfetch1['deduction'] == '1') && ($resultfetch1['tanno'] != ''))
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
//exit;
$localdate = datetimelocal('Ymd');
$localtime = datetimelocal('His');


$checkedcount = count($html) ; //exit;
$totallooprun = ($checkedcount % 100 == 0)?($checkedcount/100):(ceil($checkedcount/100));
$slno = 0;


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
			$pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
			//$pdf->writeHTMLCell(0, 0, '', '', '', '', 1, 0, true, 'C', true);
		$slno++;
	}
	//exit;
	$query11 = "SELECT username from inv_mas_users where slno = '".$userid."'";
	$resultfetch = runmysqlqueryfetch($query11); 
	$username = $resultfetch['username'];
	
	$filename = 'Invoices'."-".$username."-".$localdate."-".$localtime."-".($i+1);
	$filebasename = $filename.".pdf";
	$addstring ="/user";
	if($_SERVER['HTTP_HOST'] == "meghanab")
		$addstring = "/saralimax-user";
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
