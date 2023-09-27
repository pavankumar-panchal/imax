<?php
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

for($j = 0;$j < count($newarray);$j++)
{
	$grid = '';
	$query = "select * from inv_invoicenumbers where inv_invoicenumbers.slno = '".$newarray[$j]."' order by inv_invoicenumbers.slno DESC;";

	$result = runmysqlquery($query);
	
	$final_amount = 0;
	$appendzero = '.00';
	$grid .='<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" >';
	$grid .='<tr><td ><table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC" style="border:1px solid "><tr bgcolor="#CCCCCC"><td width="10%"><div align="center"><strong>Sl No</strong></div></td><td width="76%"><div align="center"><strong>Description</strong></div></td><td width="14%"><div align="center"><strong>Amount</strong></div></td></tr>';

    $countslno=1;
	while($fetch = mysqli_fetch_array($result))
	{
		$description = $fetch['description'];
		$descriptionsplit = explode('*',$description);
		for($i=0;$i<count($descriptionsplit);$i++)
		{
			$descriptionline = explode('$',$descriptionsplit[$i]);
			if($description <> '')
				{			
					$grid .= '<tr>';
					$grid .= '<td width="10%" style="text-align:centre;">'.$countslno.'</td>';
					$grid .= '<td width="76%" style="text-align:left;">'.$descriptionline[1].'<br/>
			<span style="font-size:+7" ><strong>Purchase Type</strong> : '.$descriptionline[2].'&nbsp;/&nbsp;<strong>Usage Type</strong> :'.$descriptionline[3].'&nbsp;&nbsp;/ &nbsp;<strong>PIN Number : <font color="#000000">'.$descriptionline[4].'</font></strong> (<strong>Serial</strong> : '.$descriptionline[5].')</span><br/><span style="font-size:+6" ><strong>Product Description</strong> : '.$productdesvalue.' </span><span style="font-size:+6" > / <strong>SAC</strong> : 997331</span></td>';
					$grid .= '<td  width="14%" style="text-align:right;" >'.formatnumber($descriptionline[6]).$appendzero.'</td>';
					$grid .= "</tr>";
					
					$final_amount = $final_amount + $descriptionline[6];
                    $incno++;
                    $countslno++;
				}
		}
		$servicedescriptionsplit = explode('*',$fetch['servicedescription']);
		$servicedescriptioncount = count($servicedescriptionsplit);
		if($fetch['servicedescription'] <> '')
		{
			for($i=0; $i<$servicedescriptioncount; $i++)
			{
				//$servicedescriptionline = explode('$',$servicedescriptionsplit[$i]);
				$itemdesvalue = '';
				$servicedescriptionline = explode('$',$servicedescriptionsplit[$i]);
				if($itembriefdescription <> '')
					$itemdesvalue = $itembriefdescriptionsplit[$i];
				else
					$itemdesvalue = 'Not Available';
				
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">'.$countslno.'</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.$servicedescriptionline[1].'<br/><span style="font-size:+6" ><strong>Item Description</strong> : '.$itemdesvalue.' </span> / <span style="font-size:+6" ><strong>SAC:</strong> 997331</span></td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.formatnumber($servicedescriptionline[2]).$appendzero.'</td>';
				$grid .= "</tr>";
				$final_amount = $final_amount + $servicedescriptionline[2];
                $countslno++;
                            
			}
		}
		
		$offerdescriptionsplit = explode('*',$fetch['offerdescription']);
		$offerdescriptioncount = count($offerdescriptionsplit);
		if($fetch['offerdescription'] <> '')
		{
		     $grid .= '<tr><td width="10%" style="text-align:centre;">&nbsp;</td><td width="76%" style="text-align:left;">Gross Amount</td><td  width="14%" style="text-align:right;" >'.formatnumber($final_amount).$appendzero.'</td></tr>';
		    
			for($i=0; $i<$offerdescriptioncount; $i++)
			{
				$offerdescriptionline = explode('$',$offerdescriptionsplit[$i]);
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">&nbsp;</td>';
				
				if($offerdescriptionline[0] == 'percentage' || $offerdescriptionline[0] == 'amount')
				{
				    $grid .= '<td width="76%" style="text-align:left;">'.$offerdescriptionline[1].'</td>';
				}
				else
				{
				    $grid .= '<td width="76%" style="text-align:left;">'.strtoupper($offerdescriptionline[0]).': '.$offerdescriptionline[1].'</td>';
				}
				
				$grid .= '<td  width="14%" style="text-align:right;" >'.formatnumber($offerdescriptionline[2]).'</td>';
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
			$descriptioncount = 0;
		else
			$descriptioncount = count($descriptionsplit);
		if($fetch['offerdescription'] == '')
			$offerdescriptioncount = 0;
		else
			$offerdescriptioncount = count($offerdescriptionsplit);
		if($fetch['servicedescription'] == '')
			$servicedescriptioncount = 0;
		else
			$servicedescriptioncount = count($servicedescriptionsplit);
		$rowcount = $offerdescriptioncount + $descriptioncount + $servicedescriptioncount + $descriptionlinecount;
		if($rowcount < 8)
		{
			if($letterhead == 'on')
				$grid .= addlinebreak_bulkprint($rowcount);
			else
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
		$podatepiece = (($fetchresult['podate'] == "0000-00-00") || ($fetchresult['podate'] == ''))?("Not Avaliable"):(changedateformat($fetchresult['podate']));
		$poreferencepiece = ($fetchresult['poreference'] == "")?("Not Avaliable"):($fetchresult['poreference']);
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
        $search_customer =  str_replace("-","",$fetch['customerid']);
        $customer_details = "select inv_mas_customer.gst_no as gst_no,inv_mas_customer.sez_enabled as sez_enabled,
        inv_mas_district.statecode as state_code,inv_mas_state.statename as statename
        ,inv_mas_state.state_gst_code as state_gst_code,panno from inv_mas_customer 
        left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode
        left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode 
        where inv_mas_customer.customerid like '%".$search_customer."%'";

        $fetch_customer_details = runmysqlqueryfetch($customer_details);
		$custpan = $fetch_customer_details['panno'];

        if(is_numeric($fetch_customer_details['gst_no']))
        {
        	if($fetch['gst_no']!= ""  && $fetch['gst_no']!= '0')
        	{
	        	$query_gst_last_no = "select customer_gstin_logs.gst_no as new_gst_no from customer_gstin_logs
	        	left join inv_invoicenumbers on customer_gstin_logs.gstin_id = inv_invoicenumbers.gst_no
	        	where inv_invoicenumbers.gst_no=".$fetch['gst_no'];
	        	$fetch_gst_last_no = runmysqlqueryfetch($query_gst_last_no);
	        	$new_gst_no = $fetch_gst_last_no['new_gst_no'];
				$customer_gst_code = substr($fetch_gst_last_no['new_gst_no'], 0, 2);
	        }
			else if($fetch['gst_no'] == '0')
			{
				$new_gst_no = "";
				$customer_gst_code = "";
			}
	        else
	        {
	        	$querygstgetdetail = "select gst_no as new_gst_no from customer_gstin_logs where customer_slno = right($search_customer,5) and gstin_id = ".$fetch_customer_details['gst_no'];
				$fetchgstgetdetail = runmysqlqueryfetch($querygstgetdetail);
			
				$new_gst_no = $fetchgstgetdetail['new_gst_no'];
				$customer_gst_code = substr($fetchgstgetdetail['new_gst_no'], 0, 2);
	        }
        	
			
    	}
    	else
    	{
    		if($fetch['gst_no']!= "" && $fetch['gst_no']!= '0')
        	{
	        	$query_gst_last_no = "select customer_gstin_logs.gst_no as new_gst_no from customer_gstin_logs
	        	left join inv_invoicenumbers on customer_gstin_logs.gstin_id = inv_invoicenumbers.gst_no
	        	where inv_invoicenumbers.gst_no=".$fetch['gst_no'];
	        	$fetch_gst_last_no = runmysqlqueryfetch($query_gst_last_no);
	        	$new_gst_no = $fetch_gst_last_no['new_gst_no'];
				$customer_gst_code = substr($fetch_gst_last_no['new_gst_no'], 0, 2);
	        }
			else if($fetch['gst_no'] == '0')
			{
				$new_gst_no = "";
				$customer_gst_code = "";
			}
			else
			{
				$new_gst_no = $fetch_customer_details['gst_no'];
				$customer_gst_code = substr($fetch_customer_details['state_gst_code'], 0, 2);
			}

    	}
/*-----------------SEZ and NON-SEZ Check---------------------------*/

		if($fetch['seztaxtype'] == 'yes' || $fetch_customer_details['sez_enabled'] == 'yes')
		{
		    
        		  if($fetch['seztaxtype'] == 'yes')
        			{
        			    $sezremarks = 'TAX NOT APPLICABLE AS CUSTOMER IS UNDER SPECIAL ECONOMIC ZONE.<br/>';
        			}			
        			
        			if($gst_tax_date <= $invoicedate)
        		    {
//                    	if($fetch['cgst'] == '0' &&  $fetch['sgst'] == '0')
//						{
//							$gst_tax_column ='<tr><td  width="56%" style="text-align:right"></td><td  width="30%" style="text-align:right"><strong>IGST Tax @'.$igst_tax_rate.'% </strong></td><td width="14%" style="text-align:right;font-size:+9">'.formatnumber($fetch['igst']).'</td></tr>';
//						}
//						else
//						{
//							$gst_tax_column ='<tr><td  width="56%" style="text-align:right"></td><td  width="30%" style="text-align:right"><strong>CGST Tax @'.$cgst_tax_rate.'% </strong></td><td width="14%" style="text-align:right;font-size:+9">'.formatnumber($fetch['cgst']).'</td></tr><tr><td  width="56%" style="text-align:right"></td><td  width="30%" style="text-align:right"><strong>SGST Tax @'.$sgst_tax_rate.'% </strong></td><td width="14%" style="text-align:right;font-size:+9">'.formatnumber($fetch['sgst']).'</td></tr>';
//						}
						if($fetch['cgst']!= '0' &&  $fetch['sgst']!= '0' && $customer_gst_code == 29)
						{
							$gst_tax_column ='<tr><td  width="56%" style="text-align:right"></td><td  width="30%" style="text-align:right"><strong>CGST Tax @'.$cgst_tax_rate.'% </strong></td><td width="14%" style="text-align:right;font-size:+9">'.formatnumber($fetch['cgst']).'</td></tr><tr><td  width="56%" style="text-align:right"></td><td  width="30%" style="text-align:right"><strong>SGST Tax @'.$sgst_tax_rate.'% </strong></td><td width="14%" style="text-align:right;font-size:+9">'.formatnumber($fetch['sgst']).'</td></tr>';
						}
						else
						{
							$gst_tax_column ='<tr><td  width="56%" style="text-align:right"></td><td  width="30%" style="text-align:right"><strong>IGST Tax @'.$igst_tax_rate.'% </strong></td><td width="14%" style="text-align:right;font-size:+9">'.formatnumber($fetch['igst']).'</td></tr>';
						}
        		    }
        		    else
        		    {
                    	if($expirydate >= $invoicedate || $expirydate1 > $invoicedate)
            			{
            				$servicetax1 = 0;
            				$servicetax2 = 0;
            				$servicetax3 = 0;
            			
            				$servicetaxname = '<br/>Cess @ 2%<br/>Sec Cess @ 1%';
            				$totalservicetax = formatnumber($servicetax1).$appendzero.'<br/>'.formatnumber($servicetax2).$appendzero.'<br/>'.
            				formatnumber($servicetax3).$appendzero;
            			}
            			else if($expirydate2 > $invoicedate)
            			{
            				$servicetax1 = 0;
            				$totalservicetax = formatnumber($servicetax1).$appendzero;
            			}
            			else
            			{
            				$servicetax1 = 0;
            				$totalservicetax = formatnumber($servicetax1).$appendzero;
            				$servicetaxname1 = 'SB Cess @ 0.5%';
            				$servicetax2 = 0;
            				$servicetaxname2 = 'KK Cess @ 0.5%';
            				$servicetax3 = 0;
            				$totalservicetax1 = $servicetax2.$appendzero;
            				
            				$sbcolumn = '<tr><td  width="56%" style="text-align:left">&nbsp;</td>
            				<td  width="30%" style="text-align:right"><strong>'.$servicetaxname1.'</strong></td>
            				<td  width="14%" style="text-align:right"><span style="font-size:+9" >'.$totalservicetax1.'</span>
            				</td></tr>';
                        	if($KK_Cess_date < $invoicedate)
                        		{
                        			$kkcolumn = '<tr><td  width="56%" style="text-align:left">&nbsp;</td>
                        			<td  width="30%" style="text-align:right"><strong>'.$servicetaxname2.'</strong></td>
                        			<td  width="14%" style="text-align:right"><span style="font-size:+9" >'.$totalservicetax1.'</span>
                        				</td></tr>';
                        		}
            			}
		    }
		    
		            $billdatedisplay = changedateformat(substr($fetch['createddate'],0,10));
				$grid .= '<tr>
				<td  width="56%" style="text-align:left"><span style="font-size:+6" >Accounting Code For Service</span></td>
				<td  width="30%" style="text-align:right"><strong>Net Amount</strong></td>
				<td  width="14%" style="text-align:right">'.formatnumber($fetch['amount']).$appendzero.'</td></tr>
				<tr>
				<td  width="56%" style="text-align:left"><span style="font-size:+6;color:#FF0000" >'.$sezremarks.'</span><span style="font-size:+6;color:#FF0000" >'.$statusremarks.'</span></td>
				<td  width="30%" style="text-align:right"><span style="font-size:+9" ><strong>'.$servicetaxname.'</strong></span></td><td width="14%" style="text-align:right"><span style="font-size:+9" >'.$totalservicetax.'</span></td></tr>'.$sbcolumn .$kkcolumn.$gst_tax_column;
        		
		}
		else
		{
		    if($gst_tax_date <= $invoicedate)
		    {
		        if($fetch['cgst'] != '0' &&  $fetch['sgst'] != '0')
		        {
                	$gst_tax_column ='<tr><td  width="56%" style="text-align:right"></td>
                	<td  width="30%" style="text-align:right"><strong>CGST Tax @'.$cgst_tax_rate.'% </strong></td>
                	<td width="14%" style="text-align:right;font-size:+9">'.formatnumber($fetch['cgst']).'</td></tr>';
                	
                	$gst_tax_column .='<tr><td  width="56%" style="text-align:right"></td>
                	<td  width="30%" style="text-align:right"><strong>SGST Tax @'.$sgst_tax_rate.'% </strong></td>
                	<td width="14%" style="text-align:right;font-size:+9">'.formatnumber($fetch['sgst']).'</td></tr>';
		        }
		        else
		        {
		           $gst_tax_column ='<tr><td  width="56%" style="text-align:right"></td>
                    <td  width="30%" style="text-align:right"><strong>IGST Tax @'.$igst_tax_rate.'% </strong></td>
                    <td width="14%" style="text-align:right;font-size:+9">'.formatnumber($fetch['igst']).'</td></tr>';
		        }

            	
            	
        $billdatedisplay = changedateformat(substr($fetch['createddate'],0,10));
		//echo($servicetax1.'#'.$servicetax2.'#'.$servicetax3); exit; // To be added Here
				$grid .= '<tr>
		<td  width="56%" style="text-align:left"><span style="font-size:+6" ></span></td>
		<td  width="30%" style="text-align:right"><strong>Net Amount</strong></td>
		<td  width="14%" style="text-align:right">'.formatnumber($fetch['amount']).$appendzero.'</td></tr>
		<tr>
		<td  width="56%" style="text-align:left"><span style="font-size:+6;color:#FF0000" >'.$sezremarks.'</span><span style="font-size:+6;color:#FF0000" >'.$statusremarks.'</span></td>
		<td  width="30%" style="text-align:right"><span style="font-size:+9" ><strong>'.$servicetaxname.'</strong></span></td><td width="14%" style="text-align:right"><span style="font-size:+9" >'.$totalservicetax.'</span></td></tr>'.$sbcolumn .$kkcolumn.$gst_tax_column;
		        
		    }
		    else
		    {
		        //echo "minetrtrt";
		        //echo $gst_tax_date."<br>"; 
		        //echo $invoicedate;
		        //exit();
            			if($expirydate >= $invoicedate)
            			{
            			$servicetax1 = roundnearestvalue($fetch['amount'] * 0.1);
            			$servicetax2 = roundnearestvalue($servicetax1 * 0.02);
            			$servicetaxname = 'Service Tax @ 10% <br/>Cess @ 2%<br/>Sec Cess @ 1%';
            			$servicetax3 = roundnearestvalue(($fetch['amount'] * 0.103) - (($servicetax1) + ($servicetax2)));
            			$totalservicetax = formatnumber($servicetax1).$appendzero.'<br/>'.formatnumber($servicetax2).$appendzero.'<br/>'.formatnumber($servicetax3).$appendzero;
            			}
            			else if($expirydate1 > $invoicedate)
            			{
            				$servicetax1 = roundnearestvalue($fetch['amount'] * 0.12);
            				$servicetax2 = roundnearestvalue($servicetax1 * 0.02);
            				$servicetaxname = 'Service Tax @ 12% <br/>Cess @ 2%<br/>Sec Cess @ 1%';
            				$servicetax3 = roundnearestvalue(($fetch['amount'] * 0.1236) - (($servicetax1) + ($servicetax2)));
            				$totalservicetax = formatnumber($servicetax1).$appendzero.'<br/>'.formatnumber($servicetax2).$appendzero.'<br/>'.formatnumber($servicetax3).$appendzero;
            			}
            			else if($expirydate2 > $invoicedate)
            			{
            				$servicetax1 = roundnearestvalue($fetch['amount'] * 0.14);
            				$servicetaxname = 'Service Tax @ 14%';
            				$totalservicetax = formatnumber($servicetax1).$appendzero;
            			}
            			else
            			{
            				$servicetax1 = roundnearestvalue($fetch['amount'] * 0.14);
            				$servicetax2 = roundnearestvalue($fetch['amount'] * 0.005);
            				$servicetaxname = 'Service Tax @ 14%';
            				$servicetaxname1 = 'SB Cess @ 0.5%';
            				$totalservicetax = formatnumber($servicetax1).$appendzero;
            				$totalservicetax1 = formatnumber($servicetax2).$appendzero;
            				
            				$sbcolumn = '<tr><td  width="56%" style="text-align:left">&nbsp;</td>
            				<td  width="30%" style="text-align:right"><strong>'.$servicetaxname1.'</strong></td>
            				<td  width="14%" style="text-align:right"><span style="font-size:+9" >'.$totalservicetax1.'</span>
            				</td></tr>';
            
            				if($KK_Cess_date < $invoicedate)
            				{
            	               $KK_Cess_tax = roundnearestvalue($fetch['amount'] * 0.005);
            				   $kkcolumn='<tr><td  width="56%" style="text-align:right"></td><td  width="30%" style="text-align:right"><strong>KK Cess @ 0.5% </strong></td><td width="14%" style="text-align:right;font-size:+9">'.formatnumber($KK_Cess_tax).$appendzero.'</td></tr>';
            				}
            			}
            			
            			
            			$billdatedisplay = changedateformat(substr($fetch['createddate'],0,10));
		//echo($servicetax1.'#'.$servicetax2.'#'.$servicetax3); exit; // To be added Here
		$grid .= '<tr>
		<td  width="56%" style="text-align:left"><span style="font-size:+6" >'.$fetch['servicetaxdesc'].' </span></td>
		<td  width="30%" style="text-align:right"><strong>Net Amount</strong></td>
		<td  width="14%" style="text-align:right">'.formatnumber($fetch['amount']).$appendzero.'</td></tr>
		<tr>
		<td  width="56%" style="text-align:left"><span style="font-size:+6;color:#FF0000" >'.$sezremarks.'</span><span style="font-size:+6;color:#FF0000" >'.$statusremarks.'</span></td>
		<td  width="30%" style="text-align:right"><span style="font-size:+9" ><strong>'.$servicetaxname.'</strong></span></td><td width="14%" style="text-align:right"><span style="font-size:+9" >'.$totalservicetax.'</span></td></tr>'.$sbcolumn .$kkcolumn.$gst_tax_column;
		
		    }//else condition ends		
			
			$sezremarks = '';
			
		}
		
/*-----------------Round Off ----------------------*/
  $roundoff = 'false';
  $roundoff_value = '';
  $addition_amount = $fetch['amount']+$fetch['igst']+$fetch['cgst']+$fetch['sgst']+$fetch['kktax']+$fetch['sbtax']+$fetch['servicetax'];
  
 $roundoff_value = ($fetch['netamount'])- ($addition_amount);

if($roundoff_value != 0 || $roundoff_value != 0.00)
{
  $roundoff = 'true';
}

/*------------------Check Ends --------------------------*/

if($roundoff == 'true')
{
	$roundoff_value = number_format($roundoff_value,2);
	$grid .= '<tr>
	<td  width="56%" style="text-align:right"><div align="left"></div></td>
	<td  width="30%" style="text-align:right"><strong>Round Off</strong></td>
	<td  width="14%" style="text-align:right">&nbsp;&nbsp;'.$roundoff_value.'</td> 
	</tr>';
}
	
$grid .= '<tr>
<td  width="56%" style="text-align:right"><div align="left"><span style="font-size:+6" >E.&amp;O.E.</span></div></td>
<td  width="30%" style="text-align:right"><strong>Total</strong></td>
<td  width="14%" style="text-align:right"><img src="../images/relyon-rupee-small.jpg" width="8" height="8" border="0" alt="Relynsoft" align="absmiddle"  />&nbsp;&nbsp;'.formatnumber($fetch['netamount'] ).$appendzero.'</td> 
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
	if($letterhead == 'on')
		$msg = file_get_contents("../pdfbillgeneration/bill-format-new2.php");
	else
		$msg = file_get_contents("../pdfbillgeneration/bill-format-new.php");
		
	if($gst_tax_date <= $invoicedate)
	{
		if($letterhead == 'on')
	        //$msg = file_get_contents("../pdfbillgeneration/bill-format-gst-duplicate.php");
			$msg = file_get_contents("../pdfbillgeneration/bill-format-gst-duplicate-withouthead.php");	
		else
		{
			if($fetchresult['irn'] == "")
				$msg = file_get_contents("../pdfbillgeneration/bill-format-gst-duplicate.php");
			else
				$msg = file_get_contents("../pdfbillgeneration/bill-format-gst-duplicate-irn.php");	
		}
		
	}

	if($fetchresult['module'] == 'dealer_module')
		$image_path = '../../dealer/qrimages/'.$fetchresult['qrimagepath']; 
	else
		$image_path = '../qrimages/'.$fetchresult['qrimagepath']; 
	$print = '<div><img src="'.$image_path .'"></div>';
	
	$array = array();
	
//	$imagepath = '<img src="../pdfbillgeneration/images/relyon-logo.png">';
	
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
	//$array[] = "##INVOICEIMAGE##%^%".$imagepath;
	
	if($new_gst_no != '')
	{
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
